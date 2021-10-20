<script type="text/javascript">
	$(document).ready(function () {
		$('#b-espera').click(function () {
			$.ajax({
				type:		'post',
				url:		'?acao=torneio_espera_sair',
				success:	function () {
					location.href = '?secao=torneio';
				}
			});
		});
		
		setInterval(function () {
			$.ajax({
				type:		'post',
				url:		'?acao=torneio_ping',
				dataType:	'script'
			});
		}, 2000);
	});
</script>
<div class="titulo-secao"><p><?php echo t('titulos.torneio_ninja');?></p></div>
<br />
<?php msg('6',''. t('torneios.aguarda_oponente') .'', ''. t('torneios.espera_desc') .'.<br /><br /><a class="button" id="b-espera" >'.t('botoes.sair_espera').'</a>');?>

<br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "7345762174";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Torneio -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>