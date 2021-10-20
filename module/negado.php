<?php
	if (isset($_GET['source'])) {
		$is_ban	= in_array($_GET['source'], $denied_reason_is_ban);
	} else {
		$is_ban	= false;
	}
?>
<div class="titulo-secao"><p><?php echo t('titulos.negado'); ?></p></div>
<?php 
	$_GET['e'] = isset($_GET['e']) ?  $_GET['e'] : 1;	
	if($_GET['e']!=2){
		msg('6',''.t('negado.titulo').'', ''.t($is_ban ? 'negado.msg_ban' : 'negado.msg').'');
	}else{
		msg('6',''.t('negado.titulo').'', t('negado.msg_ban'));

	}
?>
<?php if(isset($_SESSION['negado']) && isset($_SESSION['universal']) && $_SESSION['universal']): ?>
<pre>
<?php print_r($_SESSION['negado']); ?>
</pre>
<?php endif ?>
<br />
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