<?php
	$arena	= Recordset::query('SELECT * FROM exame_chuunin WHERE id=' . $basePlayer->id_exame_chuunin)->row_array();
	$diff	= get_time_difference(date('Y-m-d H:i:s'), $arena['data_inicio']);
?>
<script type="text/javascript">
	(function () {
		var _arena_iv	= setInterval(function () {
			$.ajax({
				url:		'?acao=exame_ping',
				dataType:	'json',
				success:	function (result) {
					if(result.redirect) {
						location.href	= result.redirect;	
					}
				}
			});
		}, 2000);

		createTimer(<?php echo $diff['hours'] ?>, <?php echo $diff['minutes'] ?>, <?php echo $diff['seconds'] ?>, 'arena-timer', function () {}, null, true);
	})();
</script>
<div class="titulo-secao"><p><?php echo t('exame.titulo')?></p></div><br />
<?php msg('2',''.t('geral.g42').'', ''.t('exame.texto_espera').' <span id="arena-timer" class="verde" style="font-size: 16px">--:--:--</span>');?>

