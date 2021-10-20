<?php
	$dt_end	= $basePlayer->treino_tempo_jutsu;
	$jutsu	= Recordset::query("SELECT nome_" . Locale::get() . " FROM item WHERE id=" . $basePlayer->id_jutsu_treino, true)->row_array();
?>

<div class="titulo-secao"><p><?php echo t('titulos.treinamento_jutsus')?></p></div>
<br />
<div id="HOTWordsTxt" name="HOTWordsTxt">
  <div id="cnBase" class="direita">
    <?php if(date("YmdHis") > date("YmdHis", strtotime($basePlayer->treino_tempo_jutsu))): ?>
    <form method="post" action="?acao=personagem_jutsu_treino_final">
    <?php msg(1,''.t('personagem_jutsu.treinamento').'',''.t('personagem_jutsu.treinamento_msg').'<br /><br />
            <input class="button" type="button" value="Concluir o Treino >" onclick="this.disabled=true; this.form.submit()" />'); ?>
    </form>
    <?php else: ?>
	<?php
		$t = get_time_diff($dt_end);
	?>
		<script type="text/javascript">
			$(document).ready(function () {
				createTimer(<?php echo $t['h'] ?>, <?php echo $t['m'] ?>, <?php echo $t['s'] ?>, 'cnTimerJutsu', null, null, true);
			});
		</script>
    	<?php msg(1,''.t('personagem_jutsu.treinando').' '. $jutsu['nome_' . Locale::get()] .' ',''.t('personagem_jutsu.conclusao').': <span id="cnTimerJutsu">--:--:--</span>'); ?>
    <?php endif; ?>
  </div>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-9048204353030493";
/* NG - Treino Jutsu Espera */
google_ad_slot = "4739461029";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>