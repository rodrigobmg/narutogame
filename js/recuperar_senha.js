function doRecuperaSenha() {
	$.ajax({
		url: 'index.php?acao=recuperar_senha_recuperar',
		dataType: 'script',
		type: 'post',
		data: $('#fRecuperaSenha').serialize()
	});

	$('#cnDados').hide();
	$('#cnMensagem').html("<center>Aguarde...</center>");	
}