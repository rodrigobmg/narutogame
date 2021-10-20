function doHospitalQuartoCura(p) {
	$.ajax({
		url: 'index.php?acao=hospital_quarto_curar',
		type: 'post',
		data: {c: p ? p : ''},
		dataType: 'script'
	});		
}