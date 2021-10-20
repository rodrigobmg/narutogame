function doNovoTopico() {
	if(!$("#titulo").val().replace(/[\s]+$/)) {
		alert("Nenhum título especificado");
		return false;
	}

	if(!$("#topico_conteudo").val().replace(/[\s]+$/)) {
		alert("Nenhum conteúdo especificado");
		return false;
	}

	$("#bNovoTopico").attr("disabled", true);
	$("#fCriarTopico")[0].submit();
}