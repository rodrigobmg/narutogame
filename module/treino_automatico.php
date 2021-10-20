<?php
	$vip_field_postkey_value = $_SESSION['vip_field_postkey_value'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));	

	$dt_end = strtotime($basePlayer->treinando);
?>
<script type="text/javascript">
	var _h = <?= date("H", strtotime(date("Y-m-d") . " 00:00:00") + ($dt_end - strtotime("+0 minute"))) ?>;
	var _m = <?= date("i", $dt_end - strtotime("+0 minute")) ?>;
	var _s = <?= date("s", $dt_end - strtotime("+0 minute")) ?>;
	
	var _r = 0;
	var _i = 0;
</script>

<div class="titulo-secao"><p><?php echo t('titulos.treinamento_ninja');?></p></div>
<br />
<div id="cnBase" class="direita">
	<?php if(strtotime("+0 minute") > $dt_end) : // Ja completou o treino ?>
	<div class="msg_gai">
		<div class="msg">
        	<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
    			<b><?php echo t('treinamentos.treino_concluido');?></b> 
				<p><?php echo t('treinamentos.treino_msg_ok');?><br /></p>
      			<br />
      			<form method="post" action="?acao=treino_automatico_finaliza">
        			<input type="hidden" name="key" value="<?= $vip_field_postkey_value ?>" />
        			<input class="button" type="submit" value="<?php echo t('botoes.finalizar');?>" />
      			</form>
    		</div>
    	</div>
	</div>
  <?php else: ?>
  	<div class="msg_gai">
    	<div class="msg">
    		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
    			<b><?php echo t('treinamentos.treino_andamento');?></b>
				<p><?php echo t('treinamentos.treino_msg_curso');?> <span id="cnTimer">--:--:--</span><br /></p>
      			<form method="post" action="?acao=treino_automatico_cancela">
        			<input type="hidden" name="key" value="<?= $vip_field_postkey_value ?>" />
        			<input class="button" type="submit" value="<?php echo t('botoes.cancelar');?>" />
      			</form>
			</div>
		</div>
	</div>
  <?php endif; ?>
</div>
