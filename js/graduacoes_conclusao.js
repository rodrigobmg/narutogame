function doFinalizaGraduacao() {
	$.ajax({
		url: 'index.php?acao=graduacoes_final',
		dataType: 'script',
		type: 'post',
		data: {key: $("#key").val(), id: $("#id").val()}
	});

	$('#cnBase').html("<center>Aguarde...</center>");	
}