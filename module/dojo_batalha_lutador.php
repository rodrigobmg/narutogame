<div class="titulo-secao titulo-secao-dojo titulo-secao-dojo-1"><p><?php echo t('dojo_batalha_espera.d4')?></p></div>
<br />
<?php if(!$basePlayer->tutorial()->battle){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 31,
	 
	  steps: [
	  {
		element: ".a-esquerda-batalha",
		title: "<?php echo t("tutorial.titulos.dojo.3");?>",
		content: "<?php echo t("tutorial.mensagens.dojo.3");?>",
		placement: "right"
	  },{
		element: ".b-direita-batalha",
		title: "<?php echo t("tutorial.titulos.dojo.4");?>",
		content: "<?php echo t("tutorial.mensagens.dojo.4");?>",
		placement: "left"
	  },{
		element: ".log-batalha",
		title: "<?php echo t("tutorial.titulos.dojo.5");?>",
		content: "<?php echo t("tutorial.mensagens.dojo.5");?>",
		placement: "top"
	  },{
		element: "#d-actionbar",
		title: "<?php echo t("tutorial.titulos.dojo.6");?>",
		content: "<?php echo t("tutorial.mensagens.dojo.6");?>",
		placement: "top"
	  },{
		element: ".new_dojo",
		title: "<?php echo t("tutorial.titulos.dojo.7");?>",
		content: "<?php echo t("tutorial.mensagens.dojo.7");?>",
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
<div align="center" style="position: relative; bottom: 5px">
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "4392295775";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Dojo -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>	
<div id="cnBase">
<?php	
	$baseEnemy = unserialize(SharedStore::G('_BATALHA_' . $basePlayer->id));

	/*
	echo '<pre style="text-align: left">';
	print_r($baseEnemy);
	echo '</pre>';
	*/

	require("template/painel_pvp.php");
?>
</div>