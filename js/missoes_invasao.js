function doAceitaQuestInvasao(id) {
	$.ajax({
		url: 'index.php?acao=missoes_invasao_aceitar',
		dataType: 'script',
		type: 'post',
		data: {id: id},
		success: function (e) {
			//alert(e);	
		}
	});

	$('#cnBase').html("<center>Aguarde...</center>");	
}