function enviaAtivacao(id) {
	$.ajax({
		url: 'index.php?acao=ativacao_enviar',
		type: 'post',
		data: {uid: id},
		dataType: 'script'
	});
}