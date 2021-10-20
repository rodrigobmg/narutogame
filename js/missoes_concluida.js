function doFinalizaMissaso(i, e) {
	$.ajax({
		url: 'index.php?acao=missoes_concluida_finaliza',
		dataType: 'script',
		type: 'post',
		data: {i: i, especial: e ? 1 : 0}
	});

	$('#cnBase').html("<center>Aguarde...</center>");	
}