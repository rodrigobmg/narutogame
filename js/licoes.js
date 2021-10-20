function doAceitaQuest(id,key) {
	$.ajax({
		url: 'index.php?acao=missoes_aceitar',
		dataType: 'script',
		type: 'post',
		data: {id: id, missoes_key: key},
		success: function (e) {
			//alert(e);	
		}
	});

	$('#cnBase').html("<center>Aguarde...</center>");
}