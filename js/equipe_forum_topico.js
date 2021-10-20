function doResposta() {
	if(!$("#msg_conteudo").val().replace(/[\s]+$/)) {
		alert("Nenhum conteúdo especificado");
		return false;
	}

	$("#bPostar").attr("disabled", true);
	$("#fResposta")[0].submit();
}