function doAceitaGraduacao() {
	$.ajax({
		url: 'index.php?acao=graduacoes_graduar',
		dataType: 'script',
		type: 'post',
		data: {KEY: $('#_tmp').val() },
		success: function (e) {
			//alert(e);	
		}
	});

	$('#cnBase').html("<center>Aguarde...</center>");
}