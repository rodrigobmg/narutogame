<?php
	$arena	= Recordset::query('SELECT * FROM arena WHERE id=' . $basePlayer->id_arena)->row_array();
	$diff	= get_time_difference(date('Y-m-d H:i:s'), $arena['data_inicio']);
?>
<script type="text/javascript">
	(function () {
		var _arena_iv	= setInterval(function () {
			$.ajax({
				url:		'?acao=arena_ping',
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
<div class="titulo-secao"><p><?php echo t('arena.titulo')?></p></div><br />
<?php msg('2',''.t('geral.g42').'', ''.t('geral.g43').' <span id="arena-timer" class="verde" style="font-size: 16px">--:--:--</span>');?>

