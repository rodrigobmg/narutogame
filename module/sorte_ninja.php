<link href="css/luck<?php echo LAYOUT_TEMPLATE?>.css" rel="stylesheet" type="text/css"/>
<?php
	$week_data			= unserialize(Player::getFlag('sorte_ninja_semanal', $basePlayer->id));
	$week_full			= true;
	$color_counter		= 0;

	$daily_currency		= 2000 - $basePlayer->bonus_vila['mo_sorte_preco'];
	$daily_vip			= 1;

	$weekly_currency	= 6000 - $basePlayer->bonus_vila['mo_sorte_preco_semanal'];
	$weekly_vip			= 3;

	if(is_array($week_data)) {
		foreach($week_data as $day => $used) {
			if(!$used) {
				$week_full	= false;
			}
		}
	} else {
		$week_full	= false;
		$week_data	= ['1' => false, '2' => false, '3' => false, '4' => false, '5' => false, '6' => false, '7' => false];
	}

	$block_by_time	= false;
	$hour			= date('H');
	$minute			= date('i');

	if(($hour == 23 && $minute >= 45) || ($hour == 0 && $minute <= 15)) {
		$block_by_time = true;
	}
?>
<div class="titulo-secao"><p><?php echo t('titulos.sorte_ninja'); ?></p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "6846234570";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Sorte Ninja -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<?php if(!$basePlayer->tutorial()->sorte){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 10,

	  steps: [
	  {
		element: "#luck-container",
		title: "<?php echo t("tutorial.titulos.sorte.1");?>",
		content: "<?php echo t("tutorial.mensagens.sorte.1");?>",
		placement: "bottom"
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
<br /><br />
<div id="cnBase" class="direita">
	<?php if($block_by_time): ?>
		<?php msg('14', t('sorte.msg_titulo'), t('sorte.msg_unavailable')) ?>
	<?php else: ?>
		<div id="luck-container">
			<div id="daynames">
				<?php for($f = 1; $f <= 7; $f++): ?>
					<div class="dayname"><?php echo t('daynames.' . $f) ?></div>
				<?php endfor ?>
			</div>
			<div id="luck-status">
				<?php for($f = 1; $f <= 7; $f++): ?>
					<div class="day-<?php echo $f ?> day <?php echo $week_data && $week_data[$f] ? 'green' : '' ?>">
						<div class="ball"></div>
						<div class="check"></div>
					</div>
				<?php endfor ?>
			</div>
			<div id="luck-stripes">
				<div id="luck-stripe-1" class="luck-stripe"></div>
				<div id="luck-stripe-2" class="luck-stripe"></div>
				<div id="luck-stripe-3" class="luck-stripe"></div>
				<div id="luck-stripe-4" class="luck-stripe"></div>
			</div>
			<div id="luck-stripes-shadows">
				<div></div>
				<div></div>
				<div></div>
				<div></div>
			</div>
			<div id="luck-mask"></div>
			<div id="luck-types">
				<div class="daily"><?php echo t('sorte_ninja.index.daily') ?></div>
				<div class="weekly"><?php echo t('sorte_ninja.index.weekly') ?></div>
			</div>
			<div id="buttons">
				<div class="daily">
					<div class="luck-button" data-type="daily" data-currency="0">
						<span><?php echo $daily_currency ?><br />Ryou</span>
					</div>
					<div class="luck-button" data-type="daily" data-currency="1">
						<span><?php echo $daily_vip . '<br />' . t('sorte_ninja.daily.vip') ?></span>
					</div>
				</div>
				<div class="weekly">
					<div class="luck-button" data-type="weekly" data-currency="0">
						<span><?php echo $weekly_currency ?><br />Ryou</span>
					</div>
					<div class="luck-button" data-type="weekly" data-currency="1">
						<span><?php echo $weekly_vip . '<br />' . t('sorte_ninja.weekly.vip') ?></span>
					</div>
				</div>
			</div>
			<div id="luck-button"><span><?php echo t('sorte_ninja.index.play') ?></span></div>
			<div id="result"></div>
		</div>
		<br /><br /><br />
	<?php endif ?>
	<!-- Mensagem nos Topos das Seções -->
	<?php $premios = new Recordset('SELECT *, nome_'.Locale::get().' as nome  FROM loteria_premio WHERE tipo = "sorte" ORDER BY id'); ?>
	<table width="730" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td height="49" class="subtitulo-home">
				<table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
					<tr>
						<td width="90" align="center">&nbsp;</td>
						<td align="center"><?php echo t('sorte.itens'); ?></td>
						<td width="200" align="center"><?php echo t('sorte.chances'); ?></td>
						<td width="190" align="center"><?php echo t('sorte.vezes'); ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<table width="730" border="0" cellpadding="0" cellspacing="0">
		<?php foreach($premios->result_array() as $premio): ?>
			<?php
				$bg		= ++$color_counter % 2 ? "class='cor_sim'" : "class='cor_nao'";
				$sql_total_premios	= new Recordset("select count(id) as total FROM sorte_ninja_log WHERE id_premio = ". $premio['id'] ." AND id_player = ". $basePlayer->id ."");
			?>
			<?php foreach($sql_total_premios->result_array() as $resp_total_premios): ?>
				<tr <?php echo $bg; ?>>
					<td width="90" height="30" align="center">
						<div class="img-lateral-dojo2">
							<img src="<?php echo img()?>layout/loteria/<?php echo $premio['id'] ?>.png" alt="<?php echo $premio['nome_'. Locale::get()] ?>" width="53" height="53"  style="margin-top:5px" />
						</div>
					</td>
					<td class="amarelo">
						<strong style="font-size:13px">
						<?php if($premio['coin']): ?>
							<?php echo $premio['coin'] ?> <?php echo t('geral.creditos'); ?>
						<?php elseif($premio['exp']): ?>
							<?php echo $premio['exp'] ?> <?php echo t('geral.pontos_exp'); ?>
						<?php else: ?>
							<?php echo $premio['nome'] ?>
						<?php endif; ?>
						</strong>
					</td>
					<td width="200"><?php echo $premio['chance'] ?>%</td>
					<td width="190"><?php echo $resp_total_premios['total'] > 0 ? " ".t('academia_treinamento.at20')." ".$resp_total_premios['total']." ".t('conquistas.c31')." " : ""?> </td>
				</tr>
				<tr height="4"></tr>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</table>
</div>
<script type="text/javascript" src="js/luck.js"></script>
