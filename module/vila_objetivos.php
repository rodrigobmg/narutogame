<?php if(!$basePlayer->tutorial()->objetivos){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 22,
	 
	  steps: [
	  {
		element: ".msg_gai",
		title: "<?php echo t("tutorial.titulos.vila.3");?>",
		content: "<?php echo t("tutorial.mensagens.vila.3");?>",
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
<div class="titulo-secao"><p><?php echo t('vila.objetivos.title')?></p></div>
<?php msg(6,''.t('vila.objetivos.msg_titulo').'',''.t('vila.objetivos.msg_texto').''); ?>
<?php
	$groups	= Recordset::query('SELECT * FROM vila_objetivos');
?>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="subtitulo-home">
			<table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
				<tr>
					<td align="center" width="60">&nbsp;</td>
					<td align="center" width="350"><?php echo t('geral.descricao') ?></td>
					<td width="80" align="center"><?php echo t('geral.premios') ?></td>
					<td width="230" align="center">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div id="group-objectives">
	<table width="730" border="0" cellpadding="0" cellspacing="0">
		<?php 
		$cn = 0;
		foreach ($groups->result_array() as $group): 
		$cor = ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
		?>
			<?php
				$totals	= Recordset::query('
					SELECT
						SUM(total) AS total

					FROM
						vila_objetivos_player

					WHERE
						objetivo=' . $group['objetivo'] . ' AND
						id_vila=' . $basePlayer->id_vila);


				$total	= (int)$totals->row()->total;
			?>
			<tr <?php echo $cor?>>
				<td width="60" align="center">
					<img src="<?php echo img()?>/layout/icon/<?php echo ($total >= $group['total'] ? "button_ok" : "button_cancel" )?>.png" />
				</td>
				<td width="350" align="center"><b class="amarelo"><?php echo $group['nome_' . Locale::get()] ?></b></td>
				<td width="80" align="center">
					<img src="<?php echo img('layout/requer.gif') ?>" id="i-objetivo-group-<?php echo $group['id'] ?>" style="cursor: pointer" />
					
					<?php 
						$premio = "
						<b class='verde'>".t('geral.premios')."</b><br /><br />
						
						".t('vila.objetivos.exp').": ". $group['exp_vila'] ."<br />";
					?>
					
					<?php echo generic_tooltip('i-objetivo-group-'.$group['id'], $premio )?>

				</td>
				<td width="230" align="center">
					<?php barra_exp3($total, $group['total'], 132,  $total . ' ' . t('geral.de') . ' ' . $group['total'], "#2C531D", "#537F3D", 1); ?>
				</td>
			</tr>
			<tr height="4"></tr>
		<?php endforeach ?>
	</table>
</div>