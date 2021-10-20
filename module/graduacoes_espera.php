<?php
	$form_key = $_SESSION['el_form_key'] = "f" . md5(rand(1, 512384));
?>
<?php if(date("YmdHis") > date("YmdHis", strtotime($basePlayer->graduando))): ?>
<script type="text/javascript" src="js/graduacoes_conclusao.js"></script>
<div class="titulo-secao"><p><?php echo t('graduacoes_espera.g1')?></p></div>
  <br />
<form id="fGraduacao">
	<input type="hidden" id="key" value="<?php echo $form_key ?>" />
    <input type="hidden"  id="id" value="<?php echo encode($basePlayer->id) ?>"/>
</form>

<!-- Mensagem nos Topos das Seções -->
<?php msg('14',''.t('graduacoes_espera.g2').'', ''.t('graduacoes_espera.g3').'<input class="button" type="button" id="bHospitalQuartoCura" value="'.t('botoes.concluir_graduacao').'" onclick="doFinalizaGraduacao();" />');?>
<!-- Mensagem nos Topos das Seções -->

<?php else: ?>
<?php
	$diff = get_time_difference(date('Y-m-d H:i:s'), $basePlayer->graduando);	
?>
<script type="text/javascript">
	var _h = <?php echo $diff['hours'] ?>;
	var _m = <?php echo $diff['minutes'] ?>;
	var _s = <?php echo $diff['seconds'] ?>;
	
	var _r = 0;
	var _i = 0;
</script>
<div class="titulo-secao"><p><?php echo t('graduacoes_espera.g1')?></p></div>
  <br />
<!-- Mensagem nos Topos das Seções -->
<?php msg('14',''.t('graduacoes_espera.g4').'', ''.t('graduacoes_espera.g5').' <span id="cnTimer">--:--:--</span></span>');?>
<!-- Mensagem nos Topos das Seções -->
  
<br />
<?php endif; ?>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-9048204353030493";
/* NG - Graduações */
google_ad_slot = "0023755321";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>