function doCancelaMissao(i) {
	if(confirm("Você que realmente cancelar essa missão ? Todo o seu tempo gasto até agora será perdido.")) {
		$.ajax({
			url: 'index.php?acao=missoes_cancelar',
			dataType: 'script',
			type: 'post',
			data: i ? {especial: 1} : {}
		});		
	}
}
