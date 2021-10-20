<?php
	if(!isset($_SESSION['usuario']['id'])) {
		redirect_to('negado');	
	}
?>
<div class="titulo-secao"><p><?php echo t('ativacao_enviar.ae1');?></p></div>
<div id="cnMensagem"></div>
<div id="cnForm">
<form name="fCadastro" id="fCadastro" method="post" onsubmit="return false">
<?php msg('1',''. t('ativacao_enviar.ae2') .'', ''. t('ativacao_enviar.ae3') .' <a href="javascript:void(0)" onclick="enviaAtivacao('. $_SESSION['usuario']['id'] .')"><b>'.t('ativacao_enviar.ae4').'</b></a>.<br /><br />'. t('ativacao_enviar.ae5') .'');?>
</form>
</div>