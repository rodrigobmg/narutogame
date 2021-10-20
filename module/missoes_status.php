<?php
	$redir_script	= true;
	$equipe			= 0;

	if(isset($_GET['especial']) && $basePlayer->getAttribute('id_missao_especial')) {
		$quest	= Recordset::query('SELECT * FROM quest WHERE id=' . $basePlayer->getAttribute('id_missao_especial'), true)->row_array();	
	} elseif($basePlayer->getAttribute('id_missao')) {
		$quest	= Recordset::query('SELECT * FROM quest WHERE id=' . $basePlayer->getAttribute('id_missao'), true)->row_array();
		
		if($quest['equipe']) {
			$equipe	= $basePlayer->id_equipe;
		}
		
		// aka missão normal de tempo
		if(!$quest['interativa'] && !$quest['equipe']) {
			redirect_to('missoes_espera');
		}
	} else {
		redirect_to('negado');
	}
	
	$player_quest	= Recordset::query('SELECT * FROM player_quest WHERE id_quest=' . $quest['id'] . ' AND id_player=' . $basePlayer->id . ' AND id_equipe=' . $equipe)->row_array();

	// Rank da quest para o contador --->
		if(!Recordset::query('SELECT id FROM player_quest_status WHERE id_player=' . $basePlayer->id)->num_rows) {
			Recordset::insert('player_quest_status', array(
				'id_player'	=> $basePlayer->id
			));
		}
		
		switch($quest['id_rank']) {
			case 5:
				$field = "quest_s";
			
				break;
				
			case 4:
				$field = "quest_a";
			
				break;
				
			case 3:
				$field = "quest_b";
			
				break;
				
			case 2:
				$field = "quest_c";
			
				break;
				
			case 1:
				$field = "quest_d";
			
				break;
			
			default:
				$field = "tarefa";
				
		}	
	// <---

	// Verifica se a quest ja foi concluida --->
		$finaliza = true;

		$quest_items = Recordset::query('
			SELECT 
				a.id_npc, 
				b.nome_' . Locale::get() . ' AS npc_nome, 
				a.npc_total, 
				c.nome_' . Locale::get() . ' AS item_nome,
				a.id_item,
				a.item_total, 
				x1,
				y1, 
				x2, 
				y2 
			FROM 
				quest_npc_item a LEFT JOIN npc b ON a.id_npc=b.id
				LEFT JOIN item c ON c.id=a.id_item
			WHERE a.id_quest=' . $quest['id'], true);

		if($quest['equipe'] && !$basePlayer->getAttribute('dono_equipe') && !$basePlayer->getAttribute('sub_equipe')) { // quest de equipe e não é o dono da equipe
			$finaliza = false;
		} elseif($quest['equipe'] && ($basePlayer->getAttribute('dono_equipe') || $basePlayer->getAttribute('sub_equipe'))) { // quest de equipe e é o dono da equipe
			foreach($quest_items->result_array() as $quest_item) {
				$my_item = Recordset::query('
					SELECT 
						qtd
					FROM 
						equipe_quest_npc
					WHERE 
						id_player_quest=' . $quest['id'] . ' AND 
						id_npc=' . $quest_item['id_npc'] . ' AND 
						id_equipe=' . $basePlayer->getAttribute('id_equipe'));
								
				if($my_item->num_rows) {
					if($my_item->row()->qtd < 1) {
						$finaliza = false;
						
						break;
					}				
				} else {
					$finaliza = false;
						
					break;
				}
			}
		} else { // missao interativa comum
			foreach($quest_items->result_array() as $quest_item) {
				if($quest['especial']) {
					$my_item = Recordset::query('
						SELECT 
							npc_total, 
							item_total 
						
						FROM 
							player_quest_npc_item 
						
						WHERE 
							id_player_quest=' . $quest['id'] . '
							AND id_player=' . $basePlayer->id)->row_array();
				} else {
					$my_item = Recordset::query('
						SELECT 
							npc_total,
							item_total
						FROM 
							player_quest_npc_item 
						WHERE 
							id_player_quest=' . $quest['id'] . ' AND 
							id_player=' . $basePlayer->id . ' AND 
							id_npc=' . $quest_item['id_npc'] . ' AND
							id_item=' . $quest_item['id_item'])->row_array();
				}
				
				if($my_item['npc_total'] < $quest_item['npc_total']) {
					$finaliza = false;
				}
	
				if($my_item['item_total'] < $quest_item['item_total']) {
					$finaliza = false;
				}
			}
		}
		
		// a quase foi marcada pra ser finalizada e aindan ão foi finalizada no banco(ou seja, va dar recompensa)
		if($finaliza && !$player_quest['finalizada']) {
			if($quest['equipe']) { // Equipe
				$players = Recordset::query('SELECT id, id_equipe FROM player WHERE id_equipe=' . $basePlayer->getAttribute('id_equipe'));

				foreach($players->result_array() as $player) {
					$p		= new Player($player['id']);
					
					// se existir pra outra equipe não da recompensa
					if(!Recordset::query('SELECT id FROM player_quest WHERE id_player=' . $player['id'] . ' AND completa=1 AND id_equipe !=' . $player['id_equipe'] . ' AND id_quest=' . $quest['id'])->num_rows) {
						// Recompensa -->
							$ryou	= $quest['ryou'] + percent($p->getAttribute('inc_ryou') + $p->bonus_vila['sk_missao_ryou'], $quest['ryou']);
							$exp	= $quest['exp'] + percent($p->bonus_vila['sk_missao_exp'], $quest['exp']);
							
							$p->setAttribute('ryou', $p->getAttribute('ryou') + $ryou);
							$p->setAttribute('exp',  $p->getAttribute('exp')  + $exp);
						// <---

						// Log
						Recordset::insert('player_recompensa_log', array(
							'id_player'	=> $basePlayer->id,
							'fonte'		=> 'quest_equipe',
							'exp'		=> $exp,
							'ryou'		=> $ryou,
							'recebido'	=> 1
						));
	
						// Reputação --->
							reputacao($p->id, $player_quest['id_vila'] ? $player_quest['id_vila'] : $basePlayer->id_vila, $quest['reputacao']);
						// <---

						// Atualiza os contadores de quest
						Recordset::update('player_quest_status', array(
							$field 		=> array('escape' => false, 'value' => $field . '+ 1')
						), array(
							'id_player'	=> $p->id
						));					
					}
					
					// Marca a quest como finalizada
					Recordset::update('player_quest', array(
						'finalizada'	=> '1',
						'completa'		=> '1'
					), array(
						'id_quest'		=> $quest['id'],
						'id_player'		=> $p->id
					));					
				}
			} else {
				if($quest['especial'] && !$quest['id_vila']) { // Especial so da vila de origem
					
					$quest['exp']	+= percent($basePlayer->bonus_vila['sk_missao_exp'], $quest['exp']);
					// Log
					Recordset::insert('player_recompensa_log', array(
						'id_player'	=> $basePlayer->id,
						'fonte'		=> 'quest_especial',
						'treino'	=> $quest['exp']
					));
				} else { // Interativa normal
					$ryou	= $quest['ryou'] + percent(($basePlayer->getAttribute('inc_ryou') + $basePlayer->bonus_vila['sk_missao_ryou']), $quest['ryou']);
					$exp	= $quest['exp'] + percent($basePlayer->bonus_vila['sk_missao_exp'], $quest['exp']);
					
					$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') + $ryou);
					$basePlayer->setAttribute('exp',  $basePlayer->getAttribute('exp')  + $exp);

					// Log
					Recordset::insert('player_recompensa_log', array(
						'id_player'	=> $basePlayer->id,
						'fonte'		=> 'quest',
						'exp'		=> $exp,
						'ryou'		=> $ryou,
						'recebido'	=> 1
					));

					// Reputação --->
						reputacao($basePlayer->id, $player_quest['id_vila'] ? $player_quest['id_vila'] : $basePlayer->id_vila, $quest['reputacao']);
					// <---					
				}
				
				Recordset::update('player_quest', array(
					'finalizada'	=> '1'
				), array(
					'id_quest'		=> $quest['id'],
					'id_player'		=> $basePlayer->id
				));
				
				// NÃO Atualiza o contador de quest se for especial
				if(!$quest['especial']) {
					Recordset::update('player_quest_status', array(
						$field 		=> array('escape' => false, 'value' => $field . '+ 1')
					), array(
						'id_player'	=> $basePlayer->id
					));
				}
			}
		}
	// <---

	if($finaliza && !$player_quest['finalizada']) {
		$player_quest = Recordset::query('SELECT * FROM player_quest WHERE id_quest=' . $quest['id'] . ' AND id_player=' . $basePlayer->id)->row_array();	
	}

	arch_parse(NG_ARCH_QUEST, $basePlayer);
	arch_parse(NG_ARCH_SELF, $basePlayer);

	$quest['ryou']	+= percent($basePlayer->getAttribute('inc_ryou') + $basePlayer->bonus_vila['sk_missao_ryou'], $quest['ryou']);	
	$quest['exp']	+= percent($basePlayer->bonus_vila['sk_missao_exp'], $quest['exp']);	
	$special		= false;
	
	// Corrige a conclusão da quest caso o loop superior falha(desync em sql ou o que for) --->
	if(!$player_quest['finalizada'] && $quest['equipe']) {
		$lider	= Recordset::query('SELECT a.id_missao FROM player a JOIN equipe b ON b.id_player=a.id WHERE b.id=' . $basePlayer->id_equipe)->row_array();
		
		if($lider['id_missao'] != $basePlayer->id_missao) {
			Recordset::update('player_quest', array(
				'finalizada'	=> 1
			), array(
				'id_player'		=> $basePlayer->id,
				'id_quest'		=> $basePlayer->id_missao
			));
		
			$special = true;
		}
	}
	// <---
	
	$diff = get_time_difference(date('Y-m-d H:i:s'), $player_quest['data_conclusao']);
?>
<div class="titulo-secao"><p><?php echo t('titulos.missoes_status') ?></p></div>
<br />
<?php if($player_quest['finalizada'] || $special): ?>
<?php
	// Se a missão for de drop, remove os itens --->
		foreach($quest_items->result_array() as $quest_item) {
			if($quest_item['id_item']) {
				$item = Recordset::query('SELECT id_tipo FROM item WHERE id=' . $quest_item['id_item'])->row_array();
				
				if($item['id_tipo'] != 27) {
					continue;
				}
				
				Recordset::delete('player_item', array(
					'id_player'	=> $basePlayer->id,
					'id_item'	=> $quest_item['id_item']
				));
			}
		}
	// <---
?>
<script type="text/javascript" src="js/missoes_concluida.js"></script>

<?php if($quest['especial'] && !$quest['id_vila']): ?>
	<?php 
		$msg =  sprintf(t('missoes_status.msg'), $quest['exp']);
	?>
<?php else: ?>
	<?php
	$msg = "
		Como recompensa você ganhou: <br />
		<span class='verde'>". $quest['exp'] . ' ' .  t('geral.pontos_exp') ."</span><br />
	    <span class='verde'>RY$ ". sprintf("%1.2f", $quest['ryou']) ."</span><br />";
	
	?>
<?php endif; ?>
<?php msg('1',t('missoes_status.missao_ok1'), sprintf(t('missoes_status.missao_ok2'), $quest['texto_conclusao_'.Locale::get()], $msg) . '<a class="button" onclick="doFinalizaMissaso('. $quest['id'] .', '. ($quest['especial'] ? 1 : 0).');"> '. t('botoes.finalizar_missao') .'</a>');?>

<?php else: ?>
<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
		<b><?php echo $quest['nome_' . Locale::get()] ?></b><br />
		<p>
			<?php echo $quest['descricao_' . Locale::get()] ?><br /><br />
			<ul>
			<?php if($quest['especial']): ?>
				<?php foreach($quest_items->result_array() as $quest_item): ?>
					<?php
						$my_item = Recordset::query('
							SELECT 
								npc_total 
							FROM 
								player_quest_npc_item
							WHERE
								id_player=' . $basePlayer->id . ' AND
								id_player_quest=' . $quest['id'])->row_array();
					
						$player_style = $my_item['npc_total'] >= $quest_item['npc_total'] ? 'style="font-size:11px; text-decoration:line-through; color:#ffffff; font-weight:bold;"' : '';
					?>
					<li <?php echo $player_style ?>>
					<strong class="verde"><?php echo t('missoes.inimigos') ?></strong><br />
					<?php echo barra_exp3($my_item['npc_total'], $quest_item['npc_total'], 327, $my_item['npc_total'] . ' ' . t('missoes.de') . ' ' . $quest_item['npc_total'] .' ' . t('missoes.vezes'), "#2C531D", "#537F3D",2);?>

					</li>
				<?php endforeach; ?>
			<?php else: ?>
				<?php foreach($quest_items->result_array() as $quest_item): ?>
				<?php if(!$quest['equipe']): // Missão interativa comum ?>
					<?php
						$my_item = Recordset::query('
									SELECT * 
									FROM 
										player_quest_npc_item 
									WHERE 
										id_player_quest=' . $quest['id'] . ' AND 
										id_player=' . $basePlayer->id . ' AND 
										id_npc=' . $quest_item['id_npc'] . ' AND
										id_item=' . $quest_item['id_item'])->row_array();
					?>
					<?php if($quest_item['id_npc']): ?>
						<?php $npc_style = $my_item['npc_total'] >= $quest_item['npc_total'] ? 'style="font-size:11px; text-decoration:line-through; color:#ffffff; font-weight:bold;"' : ''; ?>
						<div style="width: 150px; height:auto; float: left">
							<li <?php echo $npc_style ?>>
								<strong class="verde"><?php echo t('missoes.derrotar_npc') ?> <?php echo $quest_item['npc_nome'] ?></strong>
								<?php echo barra_exp3($my_item['npc_total'], $quest_item['npc_total'], 132, $my_item['npc_total'] . ' de ' . $quest_item['npc_total'] .' ' . t('missoes.vezes'), "#2C531D", "#537F3D",2);?>
								<br />
								<strong class="verde"><?php echo t('missoes.coordenadas') ?></strong><br />
								<span class="cinza">
								X ( <?php echo $quest_item['x1'] ?> <?php echo t('missoes.ate') ?> <?php echo $quest_item['x2'] ?> )<br />
								Y ( <?php echo $quest_item['y1'] ?> <?php echo t('missoes.ate') ?> <?php echo $quest_item['y2'] ?> )
								</span>
							</li>
						<?php if(!$quest_item['id_item']): ?>
						</div>
						<?php endif; ?>
					<?php endif; ?>
					<?php if($quest_item['id_item']): ?>
						<?php $item_style = $my_item['item_total'] >= $quest_item['item_total'] ? 'style="font-size:11px; text-decoration:line-through; color:#ffffff; font-weight:bold;"' : ''; ?>
						<br />
						<li <?php echo $item_style ?>>
							<strong class="laranja"><?php echo t('missoes.adiquirir_itens') ?><br />( <?php echo $quest_item['item_nome']?> )</strong>
							<?php echo barra_exp3( $my_item['item_total'], $quest_item['item_total'], 132, $my_item['item_total'] . " " . t('missoes.de') . " " . $quest_item['item_total'], "#2C531D", "#537F3D",2); ?>
						</li><br />
						</div>
					<?php endif; ?>
				<?php else: // Missão de equipe ?>
					<?php
						$my_item = Recordset::query('
							SELECT *
							FROM 
								equipe_quest_npc 
							WHERE 
								id_player_quest=' . $basePlayer->getAttribute('id_missao') . ' AND 
								id_npc=' . $quest_item['id_npc'] . ' AND 
								id_equipe=' . $basePlayer->getAttribute('id_equipe'))->row_array();
					?>
					<?php if($my_item['id_player'] == $basePlayer->id): // O NPC é meu ?>
						<?php if($my_item['qtd'] > 0): ?>
							<li style="font-size:11px; text-decoration:line-through; color:#ffffff; font-weight:bold;">
                            [<?php echo t('missoes.alvo') ?>] <?php echo t('missoes.derrotar_npc') ?>: <?php echo $quest_item['npc_nome'] ?>
                            </li>
						<?php else: ?>
							<li style="width: 200px; float: left; padding-bottom: 10px">
                            <span style="font-size:11px; color:#FF6600; font-weight:bold;">[<?php echo t('missoes.alvo') ?>] <?php echo t('missoes.derrotar_npc') ?>: <?php echo $quest_item['npc_nome'] ?></span><br />
							<strong class="verde"><?php echo t('missoes.coordenadas') ?></strong><br />
                               <span class="cinza">
                                    X <?php echo t('missoes.de') ?> <?php echo $quest_item['x1'] ?> <?php echo t('missoes.ate') ?> <?php echo $quest_item['x2'] ?><br />
                                    Y <?php echo t('missoes.de') ?> <?php echo $quest_item['y1'] ?> <?php echo t('missoes.ate') ?> <?php echo $quest_item['y2'] ?>
                               </span> 
                            </li>
						<?php endif; ?>
					<?php else: // o NPC é de outra pessoa ?>
						<?php if($my_item['qtd'] > 0): ?>
							<li style="font-size:11px; text-decoration:line-through; color:#ffffff; font-weight:bold;"><?php echo t('missoes.derrotar_npc') ?>: <?php echo $quest_item['npc_nome'] ?></li>
						<?php else: ?>
							<li style="width: 200px; float: left; padding-bottom: 10px">
                            <span style="font-size:11px; color:#FF6600; font-weight:bold;"><?php echo t('missoes.derrotar_npc') ?>: <?php echo $quest_item['npc_nome'] ?></span><br />
                            <strong class="verde"><?php echo t('missoes.coordenadas') ?></strong><br />
                                 <span class="cinza">
                                     X <?php echo t('missoes.de') ?> <?php echo $quest_item['x1'] ?> <?php echo t('missoes.ate') ?> <?php echo $quest_item['x2'] ?><br /> 
                                     Y <?php echo t('missoes.de') ?> <?php echo $quest_item['y1'] ?> <?php echo t('missoes.ate') ?> <?php echo $quest_item['y2'] ?>
                                 </span>
                             </li>
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			</ul>
			<div class="break"></div>
			<br /><br />
			<?php if(!isset($_GET['especial']) && !$basePlayer->id_missao_especial): ?>
				<span style="font-size:13px;" class="laranja"><?php echo t('missoes_status.conclusao'); ?> <span id="cnTimer">--:--:--</span></span>
				<script type="text/javascript">
					createTimer(<?php echo $diff['hours'] ?>, <?php echo $diff['minutes'] ?>, <?php echo $diff['seconds'] ?>, 'cnTimer', null, null, true);
				</script>
			<?php endif ?>
			<?php if( ($quest['equipe'] && ($basePlayer->getAttribute('dono_equipe') || $basePlayer->getAttribute('sub_equipe'))) || (!$quest['equipe'])): ?>
				<a class="button" style="margin-left: 30px" onclick="doCancelaMissao(<?php echo $quest['especial'] ? '1' : '' ?>)"><?php echo t('botoes.cancelar_missao') ?></a>
			<?php endif; ?>
			</p>
		</div>
	</div>
</div>
<?php endif; ?>