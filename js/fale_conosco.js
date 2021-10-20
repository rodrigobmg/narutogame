function doValidaFale() {
	if(!$("#nome").val().replace(/[\s]/g)) {
		alert("O Nome não pode estar em branco!");
		return false;
	}

	if(!$("#FC_email").val().match(/[_a-zA-Z\d\-\.]+@([_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+)/g)) {
		alert("Por favor digite um e-mail valido!");
		return false;
	}

	if(!$("#mensagem").val().replace(/[\s]/g)) {
		alert("Nenhuma mensagem especificada!");
		return false;
	}

	return true;
}