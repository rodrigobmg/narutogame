<div class="titulo-secao titulo-secao-dojo titulo-secao-dojo-2"><p>Dojo</p></div>
<br />
<div align="center" style="position: relative; bottom: 5px">
	<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "2217558578";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Combate -->
<script type="text/javascript" src="//pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>
<div id="cnBase" >
<?php
	$rEnemy = Recordset::query("SELECT id_player, id_playerb, enemy FROM batalha WHERE id=" . $basePlayer->id_batalha)->row_array();

	if($rEnemy['enemy'] == $basePlayer->id) {
		$baseEnemy = new Player($rEnemy['id_player']);
	} else {
		$baseEnemy = new Player($rEnemy['id_playerb']);
	}

	$interactivePVP = true;
	require("template/painel_pvp.php");
?>
</div>
