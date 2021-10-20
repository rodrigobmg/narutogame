function cancelaTreinoJutsu() {
	if(confirm("Você que realmente cancelar o treinamento? Todo o seu tempo gasto até agora será perdido.")) {
		$.ajax({
			url: 'index.php?acao=personagem_jutsu_treino_cancelar',
			dataType: 'script',
			type: 'post',
			data: {}
		});		
	}
}