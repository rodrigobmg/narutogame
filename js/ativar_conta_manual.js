function doAtivarContaManual() {
	$.ajax({
		url: 'index.php?acao=ativar_conta_manual_ativar',
		dataType: 'script',
		type: 'post',
		data: $('#fAtivarContaManual').serialize()
	});

	$('#cnDados').hide();
	$('#cnMensagem').html("<center>Aguarde...</center>");	
}