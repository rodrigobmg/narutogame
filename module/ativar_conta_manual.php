<div class="titulo-secao"><p><?php echo t('ativar_conta_manual.acm1')?></p></div>
<div id="cnBase" class="direita">
<div id="cnDados">
<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
		<form name="fAtivarContaManual" id="fAtivarContaManual" onsubmit="return false;">
			<p>
				<?php echo t('ativar_conta_manual.acm2')?><br /><br />
				<strong class="laranja" style="font-size:13px"><?php echo t('ativar_conta_manual.acm3')?></strong><input name="txtEmailConta" type="text" size="40" />
				<br /><br /><a class="button" onclick="doAtivarContaManual()"><?php echo t('botoes.ativar_minha_conta')?></a>
			</p>
		  </form>	
		</div>		
	</div>
</div>
</div>
<div id="cnMensagem"></div>
</div>