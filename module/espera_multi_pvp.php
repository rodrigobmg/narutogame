<script>
	(function () {
		var _iv	= setInterval(function () {
			$.ajax({
				url:		'?acao=espera_multi_pvp',
				type:		'post',
				dataType:	'json',
				success:	function (result) {
					if(result.accepted) {
						clearInterval(_iv);
					
						location.href	= '?secao=dojo_batalha_multi_pvp';
					} else if(result.cancelled) {
						clearInterval(_iv);

						location.href	= '?secao=personagem_status';
					}
				},
				error:	function () {
					clearInterval(_iv);
				
					location.href	= '?secao=equipe_detalhe';					
				}
			});
		}, 2000);
		
		$(document).ready(function () {
			$('#btn-cancel-team-pvp-wait').on('click', function () {
				$.ajax({
					url:		'?acao=espera_multi_pvp',
					type:		'post',
					data:		{cancel: 1}
				});
			});
		});
	})();
</script>
<div class="titulo-secao"><p><?php echo t('equipe.pvp.wait'); ?></p></div>
<?php echo msg(5, t('equipe.pvp.wait'), t('equipe.pvp.wait_msg') . ($basePlayer->dono_equipe ? '<a class="button" id="btn-cancel-team-pvp-wait">'.t('botoes.cancelar').'</a>' : '')) ?>