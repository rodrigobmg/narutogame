<?php
//global_message2('msg_global.bingo_book_vila', array('Carlos', 'Ariane', 'Areia'));
if ($_SESSION['universal']) {
	//print(date('w'));
	//print('<pre>');
	//print_r($_SERVER);
	//print($_SESSION['universal'] ? 1 : 0);
	//print(t('msg_global.equipamento_lendario'));
	//global_message("O Player <span>".$basePlayer->nome."</span> conseguiu um equipamento <b class='equip_lendario'>LENDÁRIO</b>");
	//global_message2('msg_global.equipamento_lendario', array($basePlayer->nome,'teste'));
	//arch_parse(NG_ARCH_SELF, $basePlayer);
	//Equipamentos random
	//$basePlayer->generate_equipment($basePlayer);
	//Equipamento de determinada raridade
	/*$array = explode(",",'3');
		$raridade = rand(1, count($array)) - 1;
		$basePlayer->generate_equipment($basePlayer, $array[$raridade]);*/
}
if ($basePlayer->credibilidade == 0) {
	$playerBanido = Recordset::query("select motivo,DATE_FORMAT(data_liberado,'%d/%m/%Y %H:%m:%s') data_liberado from player_banido where tipo = 'PuniçãoPVP' and liberado = 0 and id_player = " . $basePlayer->id)->row_array();
}
$bb_count				= (int)Recordset::query('SELECT COUNT(id) AS total FROM bingo_book WHERE id_player=' . $basePlayer->id . ' AND morto="1"')->row()->total;
$arena_count			= (int)Recordset::query('SELECT COUNT(id) AS total FROM arena WHERE player_id=' . $basePlayer->id)->row()->total;
$npc_tounament_count	= (int)Recordset::query('
		SELECT
			SUM(a.vitorias) AS total

		FROM
			torneio_player a JOIN torneio b ON b.id=a.id_torneio

		WHERE
			b.npc="1" AND
			a.id_player=' . $basePlayer->id)->row()->total;

$pvp_tounament_count	= (int)Recordset::query('
		SELECT
			SUM(a.vitorias) AS total

		FROM
			torneio_player a JOIN torneio b ON b.id=a.id_torneio

		WHERE
			b.npc="0" AND
			a.id_player=' . $basePlayer->id)->row()->total;

$ar_imagens	= array(
	'agi'				=> 'layout/icones/agi.png',
	'con'				=> 'layout/icones/conhe.png',
	'for'				=> 'layout/icones/forc.png',
	'int'				=> 'layout/icones/inte.png',
	'res'				=> 'layout/icones/defense.png',
	'nin'				=> 'layout/icones/nin.png',
	'gen'				=> 'layout/icones/gen.png',
	'tai'				=> 'layout/icones/tai.png',
	'ken'				=> 'layout/icones/ken.png',
	'atk_fisico'		=> 'layout/icones/atk_fisico.png',
	'atk_magico'		=> 'layout/icones/atk_magico.png',
	'def_fisico'		=> 'layout/icones/shield_fisico.png',
	'def_magico'		=> 'layout/icones/shield_magico.png',
	'def_base'			=> 'layout/icones/shield.png',
	'ene'				=> 'layout/icones/ene.png',
	'hp'				=> 'layout/icones/p_hp.png',
	'sp'				=> 'layout/icones/p_chakra.png',
	'sta'				=> 'layout/icones/p_stamina.png',
	'def_base'			=> 'layout/icones/shield.png',
	'prec_fisico'		=> 'layout/icones/prec_tai.png',
	'prec_magico'		=> 'layout/icones/precisao.png',
	/*'crit_min'		=> 'layout/icones/p_stamina.png',
		'crit_max'			=> 'layout/icones/p_stamina.png',*/
	'crit_total'		=> 'layout/icones/p_stamina.png',
	/*'esq_min'			=> 'layout/icones/p_stamina.png',
		'esq_max'			=> 'layout/icones/p_stamina.png',*/
	'esq_total'			=> 'layout/icones/p_stamina.png',
	'esq'				=> 'layout/icones/esquiva.png',
	'det'				=> 'layout/icones/deter.png',
	'conv'				=> 'layout/icones/convic.png',
	'esquiva'			=> 'layout/icones/esquiva2.png',
	'conc'				=> 'layout/icones/target2.png'
);
?>
<?php
$bar_width_1	= "132";
$max_ats_1 		= max(
	$basePlayer->getAttribute('tai_calc'),
	$basePlayer->getAttribute('ken_calc'),
	$basePlayer->getAttribute('nin_calc'),
	$basePlayer->getAttribute('gen_calc'),
	$basePlayer->getAttribute('con_calc'),
	$basePlayer->getAttribute('int_calc'),
	$basePlayer->getAttribute('ene_calc'),
	$basePlayer->getAttribute('for_calc'),
	$basePlayer->getAttribute('agi_calc')
);

$qr = Recordset::query("SELECT * FROM player_quest_status WHERE id_player=" . $basePlayer->id);
$rr = $qr->row_array();
$rank = Recordset::query("SELECT * FROM ranking WHERE id_player=" . $basePlayer->id)->row_array();
$rank_conquista = Recordset::query("SELECT * FROM ranking_conquista WHERE id_player=" . $basePlayer->id)->row_array();
?>
<div class="titulo-secao">
	<p><?php echo t('titulos.status') ?></p>
</div><br />
<script type="text/javascript">
	google_ad_client = "ca-pub-9166007311868806";
	google_ad_slot = "5788303770";
	google_ad_width = 728;
	google_ad_height = 90;
</script>
<!-- NG - Status -->
<script type="text/javascript" src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br /><br />
<?php
if ($basePlayer->credibilidade == 0) {
	msg('4', 'Suspenso de PVP por Punição', $playerBanido['motivo'] . '<br/> <b class="laranja">Punido até: ' . $playerBanido['data_liberado'] . '</b>');
}
?>
<?php
if (isset($_GET['e']) && $_GET['e']) {
	msg('4', '' . t('academia_jutsu.problema') . '', '' . t('templates.t65') . '');
}
?>
<?php if (!$basePlayer->tutorial()->status) { ?>
	<script>
		$("#topo2").css("z-index", 'initial');
		$("#menu-container").css("z-index", 'initial');
		$(function() {
			var tour = new Tour({
				backdrop: true,
				page: 1,

				steps: [{
					element: "#menu-container",
					title: "<?php echo t("tutorial.titulos.status.1"); ?>",
					content: "<?php echo t("tutorial.mensagens.status.1"); ?>",
					placement: "bottom"
				}, {
					element: ".h-combates",
					title: "<?php echo t("tutorial.titulos.status.2"); ?>",
					content: "<?php echo t("tutorial.mensagens.status.2"); ?>",
					placement: "right"
				}, {
					element: ".h-missoes",
					title: "<?php echo t("tutorial.titulos.status.3"); ?>",
					content: "<?php echo t("tutorial.mensagens.status.3"); ?>",
					placement: "bottom"
				}, {
					element: ".h-treinamento",
					title: "<?php echo t("tutorial.titulos.status.4"); ?>",
					content: "<?php echo t("tutorial.mensagens.status.4"); ?>",
					placement: "left"
				}, {
					element: ".tutorial-atributos",
					title: "<?php echo t("tutorial.titulos.status.5"); ?>",
					content: "<?php echo t("tutorial.mensagens.status.5"); ?>",
					placement: "right"
				}, {
					element: ".tutorial-formulas",
					title: "<?php echo t("tutorial.titulos.status.6"); ?>",
					content: "<?php echo t("tutorial.mensagens.status.6"); ?>",
					placement: "left"
				}, {
					element: ".layout_change",
					title: "<?php echo t("tutorial.titulos.status.7"); ?>",
					content: "<?php echo t("tutorial.mensagens.status.7"); ?>",
					placement: "right"
				}]
			});
			//Renicia o Tour
			tour.restart();
			// Initialize the tour
			tour.init(true);
			// Start the tour
			tour.start(true);
		});
	</script>
<?php } ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="left" style="vertical-align:top">
			<div class="h-combates">
				<div class="h-combates-div"><span class="amarelo" style="font-family:Mission Script; font-size:22px"><?php echo t('status.resumo_combate') ?></span></div>
				<div style="width: 230px; text-align: center; padding-top: 18px; font-size: 12px !important; line-height: 14px;">
					<span class="verde"><?php echo t('status.vitoria_dojo') ?>:</span> <?php echo (int)$basePlayer->vitorias_d ?> <br />
					<span class="verde"><?php echo t('status.vitoria_dojo_pvp') ?>:</span> <?php echo (int)$basePlayer->vitorias ?> <br />
					<span class="verde"><?php echo t('status.vitoria_mapa_pvp') ?>:</span> <?php echo (int)$basePlayer->vitorias_f ?> <br />
					<span class="verde"><?php echo t('status.vitoria_rnd') ?>:</span> <?php echo (int)$basePlayer->vitorias_rnd ?> <br />
					<span class="vermelho"><?php echo t('status.derrota_npc') ?>:</span> <?php echo (int)$basePlayer->derrotas_npc ?> <br />
					<span class="vermelho"><?php echo t('status.derrota_pvp') ?>:</span> <?php echo (int)$basePlayer->derrotas ?> <br />
					<span class="vermelho"><?php echo t('status.derrota_pvp2') ?>:</span> <?php echo (int)$basePlayer->derrotas_f ?> <br />
					<span class="vermelho"><?php echo t('status.derrota_rnd') ?>:</span> <?php echo (int)$basePlayer->derrotas_rnd ?> <br />
					<span class="cinza"><?php echo t('status.empate') ?> PVP:</span> <?php echo (int)$basePlayer->empates ?> <br />
					<span class="cinza"><?php echo t('status.empate') ?> NPC:</span> <?php echo (int)$basePlayer->empates_npc ?> <br />
					<span class="cinza"><?php echo t('status.fugas') ?>:</span> <?php echo (int)$basePlayer->fugas ?>
				</div>
			</div>
			<div class="h-missoes">
				<div class="h-combates-div"><span class="amarelo" style="font-family:Mission Script; font-size:22px"><?php echo t('status.missoes_completas') ?></span></div>
				<div style="width: 230px; text-align: center; padding-top: 18px; font-size: 12px !important; line-height: 14px;">
					<?php if ($qr->num_rows) : ?>
						<span class="verde"><?php echo t('status.rank_s') ?>:</span> <?php echo (int)$rr['quest_s'] ?> OK / <?php echo (int)$rr['falha_s'] ?> <?php echo t('missoes.falhas') ?><br />
						<span class="verde"><?php echo t('status.rank_a') ?>:</span> <?php echo (int)$rr['quest_a'] ?> OK / <?php echo (int)$rr['falha_a'] ?> <?php echo t('missoes.falhas') ?><br />
						<span class="verde"><?php echo t('status.rank_b') ?>:</span> <?php echo (int)$rr['quest_b'] ?> OK / <?php echo (int)$rr['falha_b'] ?> <?php echo t('missoes.falhas') ?><br />
						<span class="verde"><?php echo t('status.rank_c') ?>:</span> <?php echo (int)$rr['quest_c'] ?> OK / <?php echo (int)$rr['falha_c'] ?> <?php echo t('missoes.falhas') ?><br />
						<span class="verde"><?php echo t('status.rank_d') ?>:</span> <?php echo (int)$rr['quest_d'] ?> OK / <?php echo (int)$rr['falha_d'] ?> <?php echo t('missoes.falhas') ?><br />
						<span class="verde"><?php echo t('status.tarefas') ?>:</span> <?php echo (int)$rr['tarefa'] ?>
					<?php else : ?>
						<span class="verde"><?php echo t('status.rank_s') ?>:</span> 0 OK / 0 <?php echo t('missoes.falhas') ?><br />
						<span class="verde"><?php echo t('status.rank_a') ?>:</span> 0 OK / 0 <?php echo t('missoes.falhas') ?><br />
						<span class="verde"><?php echo t('status.rank_b') ?>:</span> 0 OK / 0 <?php echo t('missoes.falhas') ?><br />
						<span class="verde"><?php echo t('status.rank_c') ?>:</span> 0 OK / 0 <?php echo t('missoes.falhas') ?><br />
						<span class="verde"><?php echo t('status.rank_d') ?>:</span> 0 OK / 0 <?php echo t('missoes.falhas') ?><br />
						<span class="verde"><?php echo t('status.tarefas') ?>:</span> 0
					<?php endif; ?>
				</div>
			</div>
			<div class="h-treinamento">
				<div class="h-combates-div"><span class="amarelo" style="font-family:Mission Script; font-size:22px"><?php echo t('status.treino_at') ?></span></div>
				<div style="width: 230px; text-align: center; padding-top: 18px; font-size: 12px !important; line-height: 14px;">
					<span class="laranja"><?php echo t('status.treino_total') ?>:</span> <?php echo $basePlayer->treino_total ?><br />
					<span class="laranja"><?php echo t('status.pontos_dist') ?>:</span> <?php echo $basePlayer->treino_gasto ?><br />
					<span class="laranja"><?php echo t('status.tl_bb') ?>:</span> <?php echo $bb_count ?><br />
					<span class="laranja"><?php echo t('status.tl_hr') ?>:</span> <?php echo $basePlayer->played_hours ?>h<br />
					<span class="laranja"><?php echo t('status.tl_torneio_npc') ?>:</span> <?php echo $npc_tounament_count ?><br />
					<span class="laranja"><?php echo t('status.tl_torneio_pvp') ?>:</span> <?php echo $pvp_tounament_count ?><br />
					<span class="laranja"><?php echo t('status.tl_arena') ?>:</span> <?php echo $arena_count ?><br />

				</div>
			</div>
		</td>
	</tr>
</table>
<br />
<br />
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50%" style="vertical-align:top" class="tutorial-atributos">
			<div class="titulo-home2">
				<p><?php echo t('status.atributos') ?></p>
			</div>
			<?php
			$ar_habilidades	= array(
				'tai',
				'ken',
				'nin',
				'gen',
				'for',
				'agi',
				'int',
				'con',
				'res',
				/*'conc',
							'esq',
							'conv',
							'det',*/
				'ene'
			);

			$cn = 0;
			?>
			<?php foreach ($ar_habilidades as $habil) :	?>
				<?php $cn++; ?>

				<div class="bg_td">
					<div class="cinza atr_float" style="width: 90px; text-align:left; padding-left:8px;"><?php echo t('at.' . $habil) ?></div>
					<div class="atr_float" style="width: 30px; text-align:left; margin-top: 4px;">
						<img src="<?php echo img($ar_imagens[$habil]) ?>" id="i-<?php echo $habil ?>" />
						<?php
						if ($habil == "ene") {
							echo generic_tooltip('i-' . $habil, t('at.desc.' . $habil) . "<br /><br /><b class='bold_bege'>" . t('status.class') . " + " . t('actions.a257') . ": </b>" . $basePlayer->getAttribute($habil . '_raw') . "<br /><b class='bold_bege'>" . t('actions.a258') . ": </b>" . $basePlayer->getAttribute($habil . '_item') . "<br /><b class='bold_bege'>" . t('templates.t59') . ": </b>" . $basePlayer->getAttribute($habil . '_arv') . "<br /><br /><b class='bold_bege'>Total: </b>" . ($basePlayer->getAttribute($habil . '_calc') + $basePlayer->getAttribute($habil . '_calc2')));
						} else if ($habil == "conc" || $habil == "esq" || $habil == "conv" || $habil == "det") {
							echo generic_tooltip('i-' . $habil, t('at.desc.' . $habil) . "<br /><br /><b class='bold_bege'>" . t('status.class') . " + " . t('actions.a257') . ": </b>" . $basePlayer->getAttribute($habil . '_raw2') . "<br /><b class='bold_bege'>" . t('actions.a258') . ": </b>" . $basePlayer->getAttribute($habil . '_item') . "<br /><b class='bold_bege'>" . t('templates.t59') . ": </b>" . $basePlayer->getAttribute($habil . '_arv') . "<br /><br /><b class='bold_bege'>Total: </b>" . ($basePlayer->getAttribute($habil . '_calc') + $basePlayer->getAttribute($habil . '_calc2')));
						} else {
							echo generic_tooltip('i-' . $habil, t('at.desc.' . $habil) . "<br /><br /><b class='bold_bege'>" . t('status.class') . " + " . t('actions.a257') . ": </b>" . $basePlayer->getAttribute($habil . '_raw') . "<br /><b class='bold_bege'>" . t('actions.a258') . ": </b>" . $basePlayer->getAttribute($habil . '_item') . "<br /><b class='bold_bege'>" . t('templates.t59') . ": </b>" . $basePlayer->getAttribute($habil . '_arv') . "<br /><br /><b class='bold_bege'>Total: </b>" . $basePlayer->getAttribute($habil . '_calc'));
						}
						?>
					</div>
					<div class="atr_float" style="width: 90px; text-align:left;">
						<?php if ($habil == "conc" || $habil == "esq" || $habil == "conv" || $habil == "det") { ?>
							<span class="amarelo_claro">+ <?php echo  $basePlayer->getAttribute($habil . '_raw2') ?> ( + <?php echo $basePlayer->getAttribute($habil . '_arv') + $basePlayer->getAttribute($habil . '_item'); ?> )</span>
						<?php } else { ?>
							<span class="amarelo_claro">+ <?php echo  $basePlayer->getAttribute($habil . '_raw') ?> ( + <?php echo $basePlayer->getAttribute($habil . '_arv') + $basePlayer->getAttribute($habil . '_item'); ?> )</span>
						<?php } ?>
					</div>

					<div class="atr_float" style="margin-top: 8px;">
						<?php if ($habil == "ene") {
							barra_exp3($basePlayer->getAttribute($habil . '_calc') + $basePlayer->getAttribute($habil . '_calc2'), $max_ats_1, $bar_width_1, $basePlayer->getAttribute($habil . '_calc') + $basePlayer->getAttribute($habil . '_calc2'), "#2C531D", "#537F3D", $cn % 2 ? 1 : 2);
						} else if ($habil == "conc" || $habil == "esq" || $habil == "conv" || $habil == "det") {
							barra_exp3(($basePlayer->getAttribute($habil . '_calc2') + $basePlayer->getAttribute($habil . '_calc')), $max_ats_1, $bar_width_1, ($basePlayer->getAttribute($habil . '_calc2') + $basePlayer->getAttribute($habil . '_calc')), "#2C531D", "#537F3D", $cn % 2 ? 1 : 2);
						} else {
							barra_exp3($basePlayer->getAttribute($habil . '_calc'), $max_ats_1, $bar_width_1, $basePlayer->getAttribute($habil . '_calc'), "#2C531D", "#537F3D", $cn % 2 ? 1 : 2);
						}
						?>
					</div>
				</div>
			<?php endforeach; ?>

		</td>
		<td width="50%" align="right" style="vertical-align:top" class="tutorial-formulas">
			<div class="titulo-home2">
				<p><?php echo t('status.formulas') ?></p>
			</div>
			<?php
			$ar_habilidades	= array(
				'hp',
				'sp',
				'sta',
				'atk_fisico',
				'atk_magico',
				'def_fisico',
				'def_magico',
				/*'prec_fisico',*/
				'prec_magico',
				'conc',
				'esq',
				'conv',
				'esquiva',
				'det'
			);

			$cn = 0;
			?>
			<?php foreach ($ar_habilidades as $habil) : ?>
				<?php
				$cn++;

				$desc_extra = '';

				if ($habil == 'conc') {
					$desc_extra .= '<br /><br /><b class="bold_bege">' . t('formula.crit_total') . ':</b> ' . $basePlayer->getAttribute('crit_total_calc') . ' %<br />';
				}

				if ($habil == 'esq') {
					$desc_extra .= '<br /><br /><b class="bold_bege">' . t('formula.esq_total') . ':</b> ' . $basePlayer->getAttribute('esq_total_calc') . ' %<br />';
				}
				?>
				<div class="bg_td2" style="left: -2px;">
					<div class="cinza atr_float" style="width: 110px; text-align:left; padding-left:8px;"><?php echo t('formula2.' . $habil) ?></div>
					<div class="atr_float" style="width: 25px; text-align:left; margin-top: 3px;">
						<img src="<?php echo img($ar_imagens[$habil]) ?>" id="i-<?php echo $habil ?>" />
						<?php echo generic_tooltip('i-' . $habil, t('formula.desc.' . $habil) . $desc_extra) ?>
					</div>
					<div class="atr_float" style="width: 80px; text-align:left;">
						<span class="amarelo_claro">
							<?php
							if ($basePlayer->hasAttribute($habil . '_arv_calc')) {
								$sum = $basePlayer->getAttribute($habil . '_arv_calc') + $basePlayer->getAttribute($habil . '_item_calc');
							} else {
								$sum = $basePlayer->getAttribute($habil . '_arv') + $basePlayer->getAttribute($habil . '_item');
							}

							if ($basePlayer->hasAttribute($habil . '_raw')) {
								$sum	+= $basePlayer->getAttribute($habil . '_raw');
							}
							?>
							<?php if ($basePlayer->hasAttribute($habil . '_calc')) : ?>
								<?php echo $basePlayer->getAttribute($habil . '_calc') - $sum ?>
							<?php else : ?>
								<?php echo $basePlayer->getAttribute($habil) - $sum ?>
							<?php endif; ?>
							(+ <?php echo $sum ?>)

						</span>
					</div>
					<div class="atr_float" style="margin-top: 8px;">
						<?php if ($basePlayer->hasAttribute($habil . '_calc')) : ?>
							<?php barra_exp3($basePlayer->getAttribute($habil . '_calc'), $max_ats_1, $bar_width_1, $basePlayer->getAttribute($habil . '_calc'), "#2C531D", "#537F3D",  $cn % 2 ? 1 : 2) ?>
						<?php else : ?>
							<?php barra_exp3($basePlayer->getAttribute($habil), $max_ats_1, $bar_width_1, $basePlayer->getAttribute($habil), "#2C531D", "#537F3D",  $cn % 2 ? 1 : 2) ?>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</td>
	</tr>
</table>