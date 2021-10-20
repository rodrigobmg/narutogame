<div class="titulo-secao"><p><?php echo t('titulos.reputacoes'); ?></p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "5229900576";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Reputações -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br /><br />
<?php
	$vilas	= Recordset::query('SELECT *, nome_'.Locale::get().' AS nome FROM vila WHERE id < 13 ORDER BY id', true);
	$cn		= 0;
	$c = "";
?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
<?php foreach($vilas->result_array() as $vila): ?>
<?php
	
	$cn++;
	$bg = ++$c % 2 ? "bgcolor='#413625'" : "bgcolor='#251a13'";
?>
<tr <?= $bg ?>>
	<?php
		if(!Recordset::query('SELECT id_player FROM player_reputacao WHERE id_player=' . $basePlayer->id . ' AND id_vila=' . $vila['id'])->num_rows) {
			Recordset::insert('player_reputacao', array(
				'id_player'		=> $basePlayer->id,
				'id_reputacao'	=> 5,
				'id_vila'		=> $vila['id'],
				'reputacao'		=> 0
			));
		}
		
		$rep = Recordset::query('
		SELECT
			a.nome_'.Locale::get().' AS nome,
			a.pontos AS max_reputacao,
			b.reputacao
		FROM
			reputacao a
			JOIN player_reputacao b ON b.id_reputacao=a.id AND b.id_player=' . $basePlayer->id . ' AND b.id_vila=' . $vila['id'])->row_array();
	?>
	<td width="80"><img src="<?php echo img() ?>layout/diplomacia/<?= $vila['id'] ?>.jpg" class="imgVila" /></td>
	<td width="219" align="center">
		<strong class="amarelo" style="font-size:13px"><?php echo $vila['nome'] ?></strong><br />
		<span class="verde">X: <?php echo $vila['xpos'] ?> / Y: <?php echo $vila['ypos'] ?></span>
	</td>
	<td width="466" align="center">
		<?php if($rep['max_reputacao']): ?>
		<?php barra_exp3($rep['reputacao'], $rep['max_reputacao'], 327, $rep['nome'] . ': ' . $rep['reputacao'] . '/ ' . $rep['max_reputacao'], "#2C531D", "#537F3D",  $cn % 2 ? 1 : 2); ?>
		<?php else: ?>
		<?php barra_exp3(0, 0, 327, $rep['nome'], "#2C531D", "#537F3D",  $cn % 2 ? 1 : 2); ?>		
		<?php endif; ?>
	</td>
</tr>
<tr height="4"></tr>
<?php endforeach; ?>
</table>