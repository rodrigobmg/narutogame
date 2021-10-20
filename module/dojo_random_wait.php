<?php
	$basePlayer->clearModifiers();
	Fight::cleanup();	
?>
<script type="text/javascript">
	setInterval(function () {
		$.ajax({
			url:		'?acao=dojo_random_queue',
			data:		{check_location: 1},
			type:		'post',
			success:	function (result) {
				if(result.location) {
					location.href	= '?secao=' + result.location;
				}
			}
		});
	}, 2000);
	
	$(document).ready(function () {
		$('#b-cancel-queue').on('click', function () {
			lock_screen(true);

			$.ajax({
				url:		'?acao=dojo_random_queue',
				data:		{dequeue: 1},
				type:		'post',
				success:	function (result) {
					if(!result.success) {
						jalert("<?php echo t('random.cancel_error') ?>");
					}
				}
			});
		});
	});
</script>
<div class="titulo-secao"><p><?php echo t('random.wait_title') ?></p></div>
<br />
<?php echo msg('6', t('random.wait_msg_title'), ($basePlayer->id_random_queue_type == 4 ? t('random.wait_msg') : t('random.wait_msg2'))); ?>
<a href="#" class="button" id="b-cancel-queue"><?php echo t('botoes.cancelar') ?></a>