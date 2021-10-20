<?php 
	//Define limite de missões por graduação
	$limite_graduacao  = unserialize(LIMITE_MISSOES);
	$limite_graduacao =  $limite_graduacao[$basePlayer->id_graduacao];
?>

<?php if(!$basePlayer->tutorial()->missoes){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 30,

	  steps: [
	  {
		element: ".missoes-tempo",
		title: "<?php echo t("tutorial.titulos.missoes.1");?>",
		content: "<?php echo t("tutorial.mensagens.missoes.1");?>",
		placement: "top"
	  },{
		element: ".missoes-interativa",
		title: "<?php echo t("tutorial.titulos.missoes.2");?>",
		content: "<?php echo t("tutorial.mensagens.missoes.2");?>",
		placement: "top"
	  },{
		element: ".missoes-especial",
		title: "<?php echo t("tutorial.titulos.missoes.3");?>",
		content: "<?php echo t("tutorial.mensagens.missoes.3");?>",
		placement: "top"
	  }
	]});
	//Renicia o Tour
	tour.restart();
	// Initialize the tour
	tour.init(true);
	// Start the tour
	tour.start(true);
});
</script>
<?php } ?>
<?php
	$_SESSION['missoes_key']	= md5(date("YmdHis") . rand(1, 32768));
	$has_queue					= $basePlayer->getVIPItem(22888);
	$reaID						= (int)rand(1, 65535);
?>
<style type="text/css">
	#quest-queue-status {
		background-image: url(<?php echo img('layout/missoes_popup.png') ?>);
		height: 236px;
		display: block;
		padding-top: 50px;
		padding-left: 25px;
		padding-right: 25px;
		width: 478px;

		position: absolute;
		z-index: 2;
		right: 80px;
		top: -32px;
	}

	#quest-queue-status .table {
		height: 100px;
		margin-bottom: 40px;
		background-image: url(<?php echo img('layout/missoes_popup_barra.png') ?>);
		background-repeat: no-repeat;
	}

	.floatable-quest-queue {
		position: fixed !important;
		right: inherit !important;
		top: 128px !important;
		margin-left: 185px !important;
	}
</style>
<?php if($_SESSION['usuario']['msg_vip']): ?>
<script type="text/javascript">
	head.ready(function () {
		$(document).ready(function() {
			if(!$.cookie("missoes")) {
				$("#dialog").dialog({
					width: 540,
					height: 480,
					title: 'Dicas & Vantagens Vips das Missões Ninja',
					modal: true,
					close: function(){
						$.cookie("missoes", "foo", { expires: 1 });
					}
				});
			}
		});
	});
</script>
<?php endif ?>
<?php if ($has_queue): ?>
<script type="text/javascript">
	$(document).ready(function() {
		var	quests		= [];
		var	container	= $('#quest-queue-status');

		function redraw() {
			var	html	= '<div class="table"><table border="0" width="100%" cellpadding="4" cellspacing="0" width="100%">';
			var	reset	= $(document.createElement('A')).addClass('button').html('<?php echo t('missoes_fila.clean') ?>');
			var	accept	= $(document.createElement('A')).addClass('button').html('<?php echo t('missoes_fila.accept') ?>');
			var remove	= $(document.createElement('A')).addClass('button').html('<?php echo t('missoes_fila.remove') ?>');
			var	c		= 0;

			html	+=  '<thead>' +
							'<th colspan="2" style="height: 52px"><?php echo t('missoes_fila.header.name') ?></th>' +
							'<th><?php echo t('missoes_fila.header.exp') ?></th>' +
							'<th><?php echo t('missoes_fila.header.ryou') ?></th>' +
							'<th><?php echo t('missoes_fila.header.rep') ?></th>' +
							'<th><?php echo t('missoes_fila.header.time') ?></th>' +
						'</thead>';

			quests.forEach(function (quest) {
				if(!quest) {
					return;
				}

				var	mul	= 0;

				switch(parseInt(quest.multiplier)) {
					case 1:
						mul = 1;

						break;
					case 2:
						mul = 2;

						break;
					case 3:
						mul = 4;

						break;
					case 4:
						mul = 8;

						break;
					case 5:
						mul = 12;

						break;
				}

				html	+=  '<tr style="background-color: ' + (c++ % 2 ? '#413625' : '#251a13') + '">' +
								'<td><input type="checkbox" data-id="' + quest.id + '" data-duration="' + parseInt(quest.multiplier) + '" /></td>' +
								'<td>' + quest.name + '</td>' +
								'<td>' + (quest.exp * mul) + '</td>' +
								'<td>' + (quest.currency * mul) + '</td>' +
								'<td>' + (quest.reputation * mul) + '</td>' +
								'<td>' + quest.time + '</td>' +
							'</tr>';
			});

			html	+= '</table></div>';

			reset.on('click', function () {
				quests	= [];

				$('.quest-queue-button').removeClass('ui-state-disabled');

				container.hide();
				redraw();
			});

			accept.on('click', function () {
				lock_screen(true);

				var	data	= {'quests[]': [], 'durations[]': [], key: '<?php echo $_SESSION['missoes_key'] ?>'};

				$('input[type=checkbox]', container).each(function () {
					var	_	= $(this);

					data['quests[]'].push(_.data('id'));
					data['durations[]'].push(_.data('duration'));
				});

				$.ajax({
					url:		'?acao=missoes_fila',
					data:		data,
					type:		'post',
					dataType:	'json',
					success:	function (result) {
						if(result.success) {
							location.href	= '?secao=missoes_espera';
						} else {
							lock_screen(false);
							var	errors	= [];

							result.messages.forEach(function (message) {
								errors.push('<li>' + message + '</li>');
							});

							jalert('<ul>' + errors.join('') + '</ul>');
						}
					}
				});
			});

			remove.on('click', function () {
				var	checkboxes	= $('input[type=checkbox]:checked', container);

				if(checkboxes.length == 3) {
					reset.trigger('click');
				} else {
					checkboxes.each(function () {
						var	_	= $(this);

						delete quests[_.data('id')];

						var quests_tmp	= []
						quests.forEach(function (q) {
							if(q) {
								quests_tmp[q.id]	= q;
							}
						});

						quests	= quests_tmp;

						$('.quest-queue-button').each(function () {
							if($(this).data('id') == _.data('id')) {
								$(this).removeClass('ui-state-disabled');
							}
						});

						_.parent().parent().remove();
					});

					if(!$('input[type=checkbox]', container).length) {
						reset.trigger('click');
					}
				}
			});

			container.html(html);
			container.append(accept, reset, remove);
		}

		$('.quest-queue-button').on('click', function () {
			var	_	= $(this);

			if(_.hasClass('ui-state-disabled')) {
				return;
			}

			var length	= 0;

			quests.forEach(function (q) {
				if(q) {
					length++;
				}
			});


			if(length >= 3) {
				jalert('Você já adicionou o máximo de missões!');

				return;
			}

			_.addClass('ui-state-disabled');

			var	time	= $('#m_' + _.data('select'))[0];

			quests[_.data('id')] = {
				id:				_.data('id'),
				name:			_.data('name'),
				multiplier:	 	$('#m_' + _.data('select')).val(),
				exp:			_.data('exp'),
				currency:		_.data('currency'),
				reputation:		_.data('reputation'),
				time:			time.options[time.selectedIndex].text
			};

			container.show();
			redraw();
		});
	});
</script>
<?php endif ?>
<div id="quest-queue-status" style="display: none"></div>
<div class="titulo-secao"><p><?php echo t('titulos.missoes') ?></p></div>
<?php
	Player::moveLocal($basePlayer->id, 4, $basePlayer->id_vila_atual);

	$fall		= false;
	$fall_timer	= false;

	// Punição so acima do 15
	if($basePlayer->level >= 15) {
		$fall		= hasFall($basePlayer->getAttribute('id_vila'), 4);
		$fall_timer	= get_fall_time($basePlayer->getAttribute('id_vila'), 4);
	}

	if($fall && $basePlayer->getAttribute('level') >= 15):
?>
  <?php
  	msg('3','Sala do Kage em Reconstrução', 'Os ninjas inimigos destruiram a sala do Kage de sua vila e por isso suas miss&ccedil;es levam o dobro do tempo para serem concluidas por 24 horas.<br /><br />
  	<strong>Punição: O tempo gasto nas miss&otilde;es são dobradas por 24 horas.</strong>
  	<br /><br />
  	Tempo de punição restante: <span id="d-penality-timer">--:--:--</span>');
  ?>

<?php endif; ?>
<br>
<?php if(Player::getFlag('missao_total_dia', $basePlayer->id) >= $limite_graduacao):?>
  <?php msg('3','Problema', 'Você alcançou o limite diário de missões ninja, volte amanhã para realizar novas aventuras.<br><br><span class="verde">Missões especiais não contam no limite diário de missões.</span>');?>
<?php endif;?>
<br/>
<?php if($basePlayer->getAttribute('id_missao') && $basePlayer->getAttribute('id_missao_especial')): ?>
	<?php msg('3','Problema', 'Você já está fazendo todos os tipos de missão permitidos no momento.<br />Conclua ou cancela uma das missões atuais para continuar.');?>
<?php else: ?>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- NG - Missões -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9166007311868806"
     data-ad-slot="1857631774"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<br/><br/>
<div id="dialog" style="display:none">
	<div style="background:url(<?php echo img()?>layout/popup/Missoes.png); background-repeat:no-repeat; width:495px !important; height: 417px !important;">
		<div style="position:relative; width:280px; top:150px; padding-left: 30px;">

			<b><a href="index.php?secao=vantagens" class="linksSite3" style="font-size:16px"><?php echo t('academia_treinamento.at2')?></a></b><br /><br />
			<ul style="margin:0; padding:0;">
				<li style="margin-bottom:5px">
					<b><a href="index.php?secao=vantagens" class="linksSite3"><?php echo t('geral.g30')?></a></b><br />
					<?php echo t('geral.g31')?>
				</li><br />
				<li style="margin-bottom:5px">
					<b><a href="index.php?secao=vantagens" class="linksSite3"><?php echo t('geral.g32')?></a></b><br />
					<?php echo t('geral.g33')?>
				</li>
				<br />
				<li style="margin-bottom:5px">
					<b><a href="index.php?secao=vantagens" class="linksSite3"><?php echo t('geral.g34')?></a></b><br />
					<?php echo t('geral.g35')?>
				</li>
			</ul>
		</div>
	</div>
</div>

<table border="0" cellpadding="0" cellspacing="0" align="center" class="with-n-tabs"  id="tb100" data-auto-default="1">
	<tr>
		<?php if(!$basePlayer->getAttribute('id_missao')): ?>
		<td><a class="button missoes-tempo" rel="#missoes-tempo"><?php echo t('missoes.tempo') ?></a></td>
        <td width="20"></td>
		<td><a class="button missoes-interativa" rel="#missoes-interativa"><?php echo t('missoes.interativa') ?></a></td>
        <td width="20"></td>
		<?php endif; ?>
		<?php if(!$basePlayer->getAttribute('id_missao_especial')): ?>
		<td><a class="button missoes-especial" rel="#missoes-especial"><?php echo t('missoes.especial') ?></a></td>
		<?php endif; ?>
	</tr>
</table>
<br /><br />
<div id="HOTWordsTxt" name="HOTWordsTxt">
<?php for($v = 0; $v <= 2; $v++): ?>
	<?php
		$ranksb		= array("D", "C", "B", "A", "S");
		$ranks		= array(1, 2, 3, 4, 5);
		$ids		= array('tempo', 'interativa', 'especial');

		if($v < 2 && $basePlayer->getAttribute('id_missao')) {
			continue;
		}

		if($v == 2 && $basePlayer->getAttribute('id_missao_especial')) {
			continue;
		}
	?>
	<div id="missoes-<?php echo $ids[$v] ?>">
		<table width="730" border="0" cellpadding="0" cellspacing="0" class="with-n-tabs"  id="tabs-missoes" data-auto-default="1">
			<tr>
				<?php foreach($ranksb as $rank): ?>
				<td><a class="button" rel="#missao-<?php echo $ids[$v] ?>-<?php echo $rank ?>">Rank <?php echo $rank ?></a></td>
				<?php endforeach ?>
			</tr>
		</table>
		<br /><br />
		<?php
			if($basePlayer->getAttribute('id_missao') && $v < 2) {
				continue;
			}

			if($basePlayer->getAttribute('id_missao_especial') && $v == 2) {
				continue;
			}

			$first		= true;
			$cn			= 0;
			$cp			= 0;
			$interativa	= $v <= 0 ? '0' : '1';
			$especial	= $v == 2 ? '1' : '0';


			if(($basePlayer->id_vila_atual == $basePlayer->id_vila) || verifica_diplomacia($basePlayer->id_vila, $basePlayer->id_vila_atual)) {
				$sql = 'SELECT
					a.*,
					b.nome_'.Locale::get().' AS nome_graduacao,
					b.id AS grad_id

				FROM
					quest a LEFT JOIN graduacao b ON b.id=a.id_graduacao

				WHERE
					id_pai IS NULL
					AND equipe=0
					AND especial=' . $especial . '
					AND interativa=' . $interativa . '
					AND id_vila=0';
			} else {
				$sql = 'SELECT
						a.*,
						b.nome_'.Locale::get().' AS nome_graduacao,
						b.id AS grad_id

					FROM
						quest a LEFT JOIN graduacao b ON b.id=a.id_graduacao

					WHERE
						id_pai IS NULL
						AND equipe=0
						AND especial=' . $especial . '
						AND interativa=' . $interativa . '
						AND id_vila=' . $basePlayer->getAttribute('id_vila_atual');
			}

			$sql .= PHP_EOL . ' ORDER by level ASC';

			$quests = Recordset::query($sql)->result_array();

			$quests_done = Recordset::query('
				SELECT
					*
				FROM
					player_quest

				WHERE
					id_vila=' . $basePlayer->getAttribute('id_vila_atual') . ' AND
					id_player=' . $basePlayer->id . '

				UNION

				SELECT
					*
				FROM
					player_quest

				WHERE
					id_vila=0 AND
					id_player=' . $basePlayer->id)->result_array();
		?>
		<table width="730" border="0" cellpadding="0" cellspacing="0">
		  <tbody><tr>
			<td class="subtitulo-home">
				<table width="730" border="0" cellpadding="2" cellspacing="0" class="bold_branco">
				  <tbody><tr>
					<td width="330" align="center"><?php echo t('geral.descricao')?></td>
					<td width="100" align="center"><?php echo t('geral.duracao')?></td>
					<td width="100" align="center"><?php echo t('geral.recompensa')?></td>
					<td width="90" align="center">Level</td>
					<td width="120" align="center">Status</td>
				  </tr>
				</tbody></table>
			</td>
		  </tr>
		</tbody></table>
		<?php foreach($ranks as $rank): ?>
		<table id="missao-<?php echo $ids[$v] ?>-<?php echo $ranksb[$rank-1] ?>" width="730" border="0" cellpadding="2" cellspacing="0" <?php echo  !$first ? "style='display: none'" : "" ?>>
			<?php foreach($quests as $r): ?>
			<?php
				$rr = array('falha'		=> 0);
				$cc = array('completa'	=> 0);

				if($r['id_rank'] != $rank) {
					continue;
				}

				$r['exp']	+= percent($basePlayer->bonus_vila['sk_missao_exp'], $r['exp']);
				$r['ryou']	+= percent($basePlayer->getAttribute('inc_ryou') + $basePlayer->bonus_vila['sk_missao_ryou'], $r['ryou']);

				$is_complete = false;
				foreach($quests_done as $q) {
					if($q['id_quest'] == $r['id'] && !$q['falha']) {
						$is_complete = true;
						//break;
						$cc['completa'] = 1;

					} elseif($q['id_quest'] == $r['id'] && $q['falha']) {
						$rr['falha'] = 1;
					}
				}
				/*if(!$_SESSION['universal']){
					if($is_complete) {
						continue;
					}
				}*/

				$cor = ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
			?>
			<tr <?php echo $cor;?>>
				<td width="330" height="34" align="left" style="vertical-align:middle;">
					<b style="font-size: 13px" class="amarelo"><?php echo $r['nome_' . Locale::get()] ?></b>
					<br />
					<br /><br />
					<p style="width: 320px; margin:auto; color:#FFFFFF"> <?php echo $r['descricao_' . Locale::get()] ?></p>
					<br />
					<ul style="text-align:left;">
						<?php if($r['interativa'] && !$r['especial']): ?>
						<li class="azul" style="font-weight:bold;"><?php echo t('missoes.objetivos') ?>:</li>
							<?php
								$qi = Recordset::query("
								SELECT
									b.nome_".Locale::get()." AS npc_nome,
									c.nome_".Locale::get()." AS item_nome,
									npc_total,
									item_total,
									a.id_npc,
									a.id_item,
									x1,
									y1,
									x2,
									y2
								FROM
									quest_npc_item a LEFT JOIN npc b ON a.id_npc=b.id
									LEFT JOIN item c ON c.id=a.id_item
								WHERE a.id_quest=" . $r['id'], true);

								foreach($qi->result_array() as $ri) {
									if($ri['id_npc']) {
										echo "<br /><li><b class='verde'>" . t('missoes.derrotar') . ":</b> <span class='cinza'>" . $ri['npc_total'] . "x " . t('missoes.o_npc') . " " . $ri['npc_nome'] . "</span></li>";
									}

									if($ri['id_item']) {
										echo "<li><b class='laranja'>" . t('missoes.adiquirir') . ":</b><span class='cinza'> " . $ri['item_total'] . "x  " . $ri['item_nome'] . "</span></li><br />";
									}
								}
							?>
						<?php elseif($r['interativa'] && $r['especial']): ?>
							<?php
								$qi = Recordset::query("SELECT a.npc_total FROM quest_npc_item a WHERE a.id_quest=" . $r['id'], true);

								foreach($qi->result_array() as $ri) {
									echo "<b class='verde'>" . t('missoes.derrotar') . ":</b> <span class='cinza'>" . $ri['npc_total'] . " " . t('missoes.ninjas_inimigos') . "</span><br />";
								}
							?>

						<?php endif; ?>
					</ul>
				</td>
				<td class="verde" width="100" align="center">
					<?php if(!$cc['completa']){?>
						<?php if((int)$r['duracao']): ?>
						<?php $reaID++; ?>
						<select id="m_<?php echo $reaID ?>" onchange="doQuestCalc('<?php echo $reaID ?>')">
							<?php for($f = 1; $f <= 5; $f++): ?>
							<?php
								$field = $f != 1 ? $f : "";

								if(!(int)$r['duracao' . $field]) {
									continue;
								}

								$hour		= substr($r['duracao' . $field], 0, 2) * ($fall ? 2 : 1);
								$minute		= substr($r['duracao' . $field], 2, 2) * ($fall ? 2 : 1);
								$secs		= substr($r['duracao' . $field], 4, 2) * ($fall ? 2 : 1);

								$conclusao	= mktime($hour, $minute, $secs, 0, 0, 0);

								if($r['id_rank'] && $basePlayer->bonus_vila['sk_missao_tempo']) {
									$conclusao	= strtotime('-' . $basePlayer->bonus_vila['sk_missao_tempo'] . ' minutes', $conclusao);
								}
							?>
							<option value="<?php echo $f ?>"><?php echo $hour >= 24 ? $hour . date(":i:s", $conclusao) : date("H:i:s", $conclusao) ?></option>
							<?php endfor; ?>
						</select>
						<?php else: ?>
						-
						<?php endif; ?>
					<?php }else{?>
						<?php
							$quests_done2 = Recordset::query('
								SELECT
									*
								FROM
									player_quest

								WHERE
									id_quest=' . $r['id'] . ' AND
									id_player=' . $basePlayer->id)->row_array();
						?>

					 	<?php echo t('botoes.concluido') ?>: <br /><br /><?php echo  date("d/m/Y", strtotime($quests_done2['data_conclusao'])) . " &agrave;s " . date("H:i:s", strtotime($quests_done2['data_conclusao'])); ?>
					<?php }?>
				</td>
				<td class="verde" width="100" align="center">
					<input type="hidden" id="v_<?php echo $reaID ?>" value="<?php echo (float)$r['ryou'] ?>" />
					<input type="hidden" id="e_<?php echo $reaID ?>" value="<?php echo $r['exp'] ?>" />
					<?php if($r['interativa'] && $r['especial'] && $basePlayer->id_vila_atual == $basePlayer->id_vila): ?>
					<?php echo (int)$r['exp'] ?> <?php echo t('missoes.treino') ?>
					<?php else: ?>
					<p id="vv_<?php echo $reaID ?>">RY$ <?php echo sprintf("%1.2f", (float)$r['ryou']) ?></p>
					<p id="ee_<?php echo $reaID ?>"><?php echo (int)$r['exp'] ?> <?php echo t('geral.pontos_exp') ?></p>
					<?php /*<p><?php echo (int)$r['reputacao'] > 0 ? (int)$r['reputacao'] : 100 ?> <?php echo t('geral.pontos_rep') ?></p>*/?>
					<?php endif; ?>
				</td>
				<td width="90" class="amarelo" align="center" style="vertical-align:middle; url('<?php echo img(); ?>layout/interno/bg-requerimento.png') no-repeat center"><b style="font-size:10px;">
						<?php echo graduation_name($basePlayer->id_vila, $r['grad_id'])?><br />
						Lvl. <?php echo $r['level'] ?></b>
				</td>
				<td width="120" align="center">
				<?php if($basePlayer->level >= $r['level'] && $basePlayer->id_graduacao >= $r['id_graduacao'] && !$rr['falha'] && !$cc['completa'] && (Player::getFlag('missao_total_dia', $basePlayer->id) < $limite_graduacao || $r['especial'])): ?>
					<a class="button" onclick="doAceitaQuest('<?php echo $r['id'] ?>', '<?php echo $reaID ?>', '<?php echo $_SESSION['missoes_key']?>')"><?php echo t('botoes.aceitar') ?></a>
					<?php if ($has_queue && !$r['interativa']): ?>
						<br />
						<br />
						<a class="button quest-queue-button" data-id="<?php echo $r['id'] ?>" data-select="<?php echo $reaID ?>" data-exp="<?php echo $r['exp'] ?>" data-currency="<?php echo $r['ryou'] ?>" data-reputation="<?php echo $r['reputacao'] ?>" data-name="<?php echo $r['nome_' . Locale::get()] ?>"><?php echo t('botoes.por_fila') ?></a>
					<?php endif ?>
				<?php elseif($rr['falha']): ?>
					<a class="button ui-state-red"><?php echo t('botoes.falha') ?></a>
				<?php elseif($cc['completa']): ?>
					<a class="button ui-state-green"><?php echo t('botoes.concluido') ?></a>
				<?php else: ?>
					<a class="button ui-state-disabled"><?php echo t('botoes.aceitar') ?></a>
				<?php endif;  ?>
				</td>
			</tr>
			<tr height="4"></tr>
			<?php endforeach; ?>
		</table>
		<?php endforeach; ?>
	</div>
<?php endfor; ?>
</div>
<script type="text/javascript">
	head.ready(function () {
		<?php if($basePlayer->id_missao): ?>
		doMissaoTipo(2);
		<?php else: ?>
		doMissaoTipo(0);
		<?php endif; ?>
	});
</script>
<?php endif; ?>
<?php if ($fall_timer): ?>
	<script type="text/javascript">
		createTimer(<?php echo $fall_timer->format('%H') ?>, <?php echo $fall_timer->format('%i') ?>, <?php echo $fall_timer->format('%s') ?>, 'd-penality-timer');
	</script>
<?php endif ?>
