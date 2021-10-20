<?php if($basePlayer->getAttribute('missao_interativa') || $basePlayer->getAttribute('id_missao_especial') || $basePlayer->getAttribute('id_missao_guild') || $basePlayer->getAttribute('missao_invasao') || $basePlayer->id_evento || $basePlayer->getAttribute('id_missao_guild2')): ?>
	<div id="left-quests-container"> 
	<?php for($f = 0; $f <= 5; $f++): ?>
		<?php
			if($f == 0 && !$basePlayer->getAttribute('missao_interativa')) {
				continue;
			}

			if($f == 1 && !$basePlayer->getAttribute('id_missao_especial')) {
				continue;
			}	

			if($f == 2 && !$basePlayer->getAttribute('id_missao_guild')) {
				continue;
			}	

			if($f == 3 && !$basePlayer->getAttribute('missao_invasao')) {
				continue;
			}
			
			if($f == 4 && !$basePlayer->id_evento) {
				continue;
			}

			if($f == 5 && !$basePlayer->getAttribute('id_missao_guild2')) {
				continue;
			}	

			switch($f) {
				case 0:
					$quest 			= Recordset::query('SELECT id, nome_br, nome_en, equipe, especial FROM quest WHERE id=' . $basePlayer->getAttribute('id_missao'), true)->row_array();
					
					if($quest['equipe']) {
						$player_quest	= Recordset::query('SELECT data_conclusao FROM player_quest WHERE id_quest=' . $quest['id'] . ' AND id_player=' . $basePlayer->id . ' AND id_equipe=' . $basePlayer->id_equipe)->row_array();
					} else {
						$player_quest	= Recordset::query('SELECT data_conclusao FROM player_quest WHERE id_quest=' . $quest['id'] . ' AND id_player=' . $basePlayer->id)->row_array();
					}

					break;

				case 1:
					$quest			= Recordset::query('SELECT id, nome_br, nome_en, equipe, especial FROM quest WHERE id=' . $basePlayer->getAttribute('id_missao_especial'), true)->row_array();
					$player_quest	= Recordset::query('SELECT data_conclusao FROM player_quest WHERE id_quest=' . $quest['id'] . ' AND id_player=' . $basePlayer->id)->row_array();

					break;

				case 2:
					$quest = Recordset::query('SELECT id, nome_br, nome_en, descricao_en, descricao_br descricao FROM quest_guild WHERE id=' . $basePlayer->getAttribute('id_missao_guild'), true)->row_array();

					break;
				
				case 3:
					$quest	= Recordset::query('
						SELECT
							a.nome_'.Locale::get().' AS nome_vila,
							c.nome_' . Locale::get() . ' AS local

						FROM
							vila a JOIN npc_vila b ON b.id_vila=a.id
							JOIN local_mapa c ON c.id=b.mlocal

						WHERE
							b.id=' . $basePlayer->getAttribute('missao_invasao_npc'))->row_array();
					
					break;
				
				case 4:
					$quest_helper_evento	= $basePlayer->hasItemW(20265);
					$evento_especial		= Recordset::query('SELECT npc_especial FROM evento WHERE id=' . $basePlayer->id_evento, true)->row()->npc_especial;
				
					break;

				case 5:
					$quest = Recordset::query('SELECT id, nome_br, nome_en, descricao_en, descricao_br FROM quest_guild WHERE id=' . $basePlayer->getAttribute('id_missao_guild2'), true)->row_array();
				
					break;
			}
		?>
		<?php if($f == 0 || $f == 1): ?>
			<?php
				$diff	= get_time_difference(date('Y-m-d H:i:s'), $player_quest['data_conclusao']);;									
			?>
			<b class="vinho" style="font-size:14px">
				<?php echo $quest['nome_'. Locale::get()] ?><br />
				<?php if($f != 1): ?>
					[ <span id="cn-span-timer-top-<?php echo $f ?>">--:--:--</span> ]<br /><br />
				<?php endif ?>
			</b>
			<script type="text/javascript">
				createTimer(<?php echo $diff['hours'] ?>, <?php echo $diff['minutes'] ?>, <?php echo $diff['seconds'] ?>, 'cn-span-timer-top-<?php echo $f ?>', function () {});
			</script>
			<ul>
			<?php
				$quest_items	= Recordset::query('
					SELECT
						b.nome_'.Locale::get().' AS npc_nome, 
						c.nome_'.Locale::get().' AS item_nome, 
						x1, 
						y1, 
						x2,
						y2,
						a.id_npc,
						a.id_item,

						a.npc_total,
						a.item_total
					FROM
						quest_npc_item a LEFT JOIN npc b ON a.id_npc=b.id 
						LEFT JOIN item c ON c.id=a.id_item
					WHERE a.id_quest=' . $quest['id'], true);

				foreach($quest_items->result_array() as $quest_item) {
					if(!$quest['equipe']) {	// Missão interativa comum
						$my_item = Recordset::query('
							SELECT * 
							FROM 
								player_quest_npc_item 
							WHERE 
								id_player_quest=' . $quest['id'] . ' AND 
								id_player=' . $basePlayer->id . ' AND 
								id_npc=' . $quest_item['id_npc'] . ' AND
								id_item=' . $quest_item['id_item'])->row_array();

						if($quest['especial']) {
							$npc_style = $my_item['npc_total'] >= $quest_item['npc_total'] ? 'style="font-size:11px; text-decoration:line-through; color:#ffffff; font-weight:bold;"' : '';
						?>	
							<br />
							<li <?php echo $npc_style ?>>
								<strong class="verde_menu"><?php echo t('missoes.inimigos') ?></strong><br />
								<?php echo barra_exp3($my_item['npc_total'], $quest_item['npc_total'], 132, $my_item['npc_total'] . ' ' . t('missoes.de') . ' ' . $quest_item['npc_total'] .' ' . t('missoes.vezes'), "#2C531D", "#537F3D",2);?>
							</li>
						<?php	
						} else {
						?>	
							<!-- Teste Novo -->
							<?php if($quest_item['id_npc']): ?>
								<?php $npc_style = $my_item['npc_total'] >= $quest_item['npc_total'] ? 'style="font-size:11px; text-decoration:line-through; color:#ffffff; font-weight:bold;"' : ''; ?>
								<div style="width: 200px; height:auto;">
									<li <?php echo $npc_style ?>>
										<strong class="verde_menu"><?php echo t('missoes.derrotar_npc') ?> <?php echo $quest_item['npc_nome'] ?></strong>
										<?php echo barra_exp3($my_item['npc_total'], $quest_item['npc_total'], 132, $my_item['npc_total'] . ' de ' . $quest_item['npc_total'] .' ' . t('missoes.vezes'), "#2C531D", "#537F3D",2);?>
										<strong class="verde_menu"><?php echo t('missoes.coordenadas') ?></strong><br />
										<span class="chumbo">
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
								<li <?php echo $item_style ?>>
									<strong class="laranja_menu"><?php echo t('missoes.adiquirir_itens') ?> ( <?php echo $quest_item['item_nome']?> )</strong>
									<?php echo barra_exp3( $my_item['item_total'], $quest_item['item_total'], 132, $my_item['item_total'] . " " . t('missoes.de') . " " . $quest_item['item_total'], "#2C531D", "#537F3D",2); ?>
								</li><br />
								</div>
							<?php endif; ?>
					<?php		
						}
					} else { // Missão de equipe
						$my_item = Recordset::query('
							SELECT 
								id_player, id_npc, qtd 
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
                            <span style="font-size:11px; color:#b82d02; font-weight:bold;">[<?php echo t('missoes.alvo') ?>] <?php echo t('missoes.derrotar_npc') ?>: <?php echo $quest_item['npc_nome'] ?></span><br />
							<strong class="verde_menu"><?php echo t('missoes.coordenadas') ?></strong><br />
                               <span class="chumbo">
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
                            <span style="font-size:11px; color:#b82d02; font-weight:bold;"><?php echo t('missoes.derrotar_npc') ?>: <?php echo $quest_item['npc_nome'] ?></span><br />
                            <strong class="verde_menu"><?php echo t('missoes.coordenadas') ?></strong><br />
                                 <span class="chumbo">
                                     X <?php echo t('missoes.de') ?> <?php echo $quest_item['x1'] ?> <?php echo t('missoes.ate') ?> <?php echo $quest_item['x2'] ?><br /> 
                                     Y <?php echo t('missoes.de') ?> <?php echo $quest_item['y1'] ?> <?php echo t('missoes.ate') ?> <?php echo $quest_item['y2'] ?>
                                 </span>
                             </li>
						<?php endif; ?>
					<?php endif; ?>
				<?php	
					}
				}
			?>
			</ul>
		<?php elseif($f == 2 || $f == 5): ?>
			<b class="vinho" style="font-size:14px">
				<?php echo $quest['nome_' . Locale::get()] ?>
			</b>
			<?php
				$quest_items	= Recordset::query('
					SELECT 
						a.*,
						b.nome_'.Locale::get().' AS npc_nome,
						c.nome_'.Locale::get().' AS item_nome,
						b.x1,
						b.y1,
						b.x2,
						b.y2

					FROM 
						quest_guild_npc_item a LEFT JOIN npc b ON b.id=a.id_npc
						LEFT JOIN item c ON c.id=a.id_item

					WHERE 
						a.id_quest_guild=' . $basePlayer->getAttribute($f == 5 ? 'id_missao_guild2' : 'id_missao_guild'), true);
			?>
			<ul>
			<?php foreach($quest_items->result_array() as $quest_item): ?>
				<?php
					if($f == 5) {
						$my_item = Recordset::query('
							SELECT
								a.* ,
								d.nome_'.Locale::get().' AS nome_vila,
								d.id AS id_vila
							FROM 
								guild_quest_npc_item a
								LEFT JOIN npc b ON b.id=a.id_npc
								LEFT JOIN vila d ON d.id=b.id_vila									
							WHERE 
								a.id_quest_guild=' . $quest['id'] . ' AND 
								a.id_guild=' . $basePlayer->getAttribute('id_guild') . ' AND 
								a.id_npc=' . $quest_item['id_npc'] . ' AND
								a.id_item=' . $quest_item['id_item'])->row_array();	
					} else {
						$my_item = Recordset::query('
							SELECT * 
							FROM 
								player_quest_guild_npc_item
							WHERE 
								id_quest_guild=' . $quest['id'] . ' AND 
								id_player=' . $basePlayer->id . ' AND 
								id_npc=' . $quest_item['id_npc'] . ' AND
								id_item=' . $quest_item['id_item'])->row_array();
					}
					
					$npc_style	= $my_item['npc_total']		>= $quest_item['npc_total']		? 'style="text-decoration:line-through"' : '';
					$item_style	= $my_item['item_total']	>= $quest_item['item_total']	? 'style="text-decoration:line-through"' : '';
				?>
				<?php if($quest_item['id_npc']): ?>
					<div style="width: 200px; height:auto;">
						<li <?php echo $npc_style ?>>
							<strong class="verde_menu"><?php echo t('missoes.derrotar_npc') ?> <?php echo $quest_item['npc_nome'] ?></strong>
							<?php echo barra_exp3($my_item['npc_total'], $quest_item['npc_total'], 132, $my_item['npc_total'] . ' de ' . $quest_item['npc_total'] .' ' . t('missoes.vezes'), "#2C531D", "#537F3D",2);?>
							<strong class="verde_menu"><?php echo t('missoes.coordenadas') ?></strong><br />
							<span class="chumbo">
							X ( <?php echo $quest_item['x1'] ?> <?php echo t('missoes.ate') ?> <?php echo $quest_item['x2'] ?> )<br />
							Y ( <?php echo $quest_item['y1'] ?> <?php echo t('missoes.ate') ?> <?php echo $quest_item['y2'] ?> )
							</span>
						</li>
				<?php endif; ?>
					</div>
				<?php if($quest_item['id_item']): ?>
						<li <?php echo $item_style ?>>
							<strong class="laranja_menu"><?php echo t('missoes.adiquirir_itens') ?> ( <?php echo $quest_item['item_nome']?> )</strong>
							<?php echo barra_exp3( $my_item['item_total'], $quest_item['item_total'], 132, $my_item['item_total'] . " " . t('missoes.de') . " " . $quest_item['item_total'], "#2C531D", "#537F3D",2); ?>
						</li>
				<?php endif; ?>
			<?php endforeach; ?>
			</ul>
		<?php elseif($f == 3): ?>
			<?php
				$npc	= Recordset::query('SELECT * FROM npc_vila WHERE id=' . $basePlayer->missao_invasao_npc)->row_array();
			?>
			<b class="verde_menu">
				<?php echo t('templates.t28')?> <?php echo $quest['nome_vila'] ?> <?php echo t('templates.t29')?> <?php echo $quest['local'] ?>
			</b>
			<br /><br />
			<span class="laranja_menu">
			<?php if ($npc['batalha']): ?>
				<?php echo t('templates.t76') ?>
			<?php else: ?>
				<?php echo t('templates.t77') ?>
			<?php endif ?>
			</span>
			<br />
			<br />
		<?php elseif($f == 4): ?>
			<?php 
				$evento_npcs = Recordset::query('
					SELECT 
						a.nome_'.Locale::get().' AS nome, 
						a.xpos,
						a.ypos, 
						c.morto 
					FROM 
						evento_npc a JOIN evento_npc_evento b ON b.id_evento_npc=a.id AND b.id_evento=' . $basePlayer->id_evento . '
						JOIN evento_npc_equipe c ON c.id_evento=b.id_evento AND c.id_equipe=' . $basePlayer->id_equipe . ' AND c.id_evento_npc=a.id
				'); 
			?>
			<ul>
			<?php foreach($evento_npcs->result_array() as $npc): ?>
			<li style="padding-bottom: 2px;<?php echo $npc['morto'] ? 'text-decoration:line-through' : '' ?>">
				<b class="verde_menu" style="font-size:13px"><?php echo t('missoes.derrotar')?> <?php echo $npc['nome'] ?></b><br />
				<?php if($quest_helper_evento && !$evento_especial): ?>
				<span class="chumbo">X:<?php echo $npc['xpos'] ?> <?php echo t('geral.e')?> Y:<?php echo $npc['ypos'] ?></span>
			</li>
				<?php endif; ?>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	<?php endfor; ?>
	</div>
<?php endif; ?>