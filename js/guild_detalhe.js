function claDescricao() {
	if(!$("#descricao").val().replace(/[\s]+$/, "")) {
		jalert("A descrição não pode ficar em branco");
		return false;	
	}
	
	$("#fClaDescricao")[0].submit();
}