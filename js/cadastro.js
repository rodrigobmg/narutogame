	function validarCadastro() {
	var frm = $F('fCadastro');
	
	if(!frm.nome.value.match(/^[\w\'\s]+$/i)) {
		alert("Campo 'Nome' com valor inválido! Verifique o dado e tente novamente.");	
		return false;
	}

	if(frm.email.value != frm.email_confirmacao.value){
		alert("Campo 'E-Mail & Confirmar E_Mail' devem ser iguais.");
		return false;
	}
	if(!frm.email.value.match(/^[_\w\-\.]+@([_\w\-]+(\.[_\w\-]+)+)$/im)) {
		alert("Campo 'E-Mail' com valor inválido! Verifique o dado e tente novamente.");	
		return false;
	}
	if(!frm.email_confirmacao.value.match(/^[_\w\-\.]+@([_\w\-]+(\.[_\w\-]+)+)$/im)) {
		alert("Campo 'E-Mail' com valor inválido! Verifique o dado e tente novamente.");	
		return false;
	}
	/*
	if(frm.cep.value) {
		if(!frm.cep.value.match(/[0-9]{8}/)) {
			alert("Campo 'CEP' com valor inválido! Verifique o dado e tente novamente.");	
			return false;
		}
	}

	if(frm.endereco.value) {
		if(!frm.endereco.value.match(/[\w\']+/)) {
			alert("Campo 'Endereço' com valor inválido! Verifique o dado e tente novamente.");	
			return false;
		}
	}

	/*
	if(frm.bairro.value) {
		if(!frm.bairro.value.match(/[\w\']+/)) {
			alert("Campo 'Bairro' com valor inválido! Verifique o dado e tente novamente.");	
			return false;
		}
	}
	

	if(frm.cidade.value) {
		if(!frm.cidade.value.match(/[\w\']+/)) {
			alert("Campo 'Cidade' com valor inválido! Verifique o dado e tente novamente.");	
			return false;
		}
	}
	*/
	if(!frm.senha.value.match(/[\w\_\.]{6,}/i)) {
		alert("Campo 'Senha' com valor inválido!\nNo mínimo são requridos 6 caractéres e os caractéres permitidos são: 0-9 A-Z _ .");
		return false;
	}
	
	if(frm.senha.value != frm.confirma_senha.value) {
		alert("Campos 'Senha' e 'Confirma senha' com valores diferentes! Verifique os dados etente novamente.");
		return false;
	}

	if(frm.captcha.value.length != 5) {
		alert("Campo de verificação necessita de 5 caractéres!");
		return false;
	}

	if(!frm.aceite.checked) {
		alert("Para prosseguir com o cadastro você deve aceitar o contrato.");
		return false;
	}
	if(!frm.aceite2.checked) {
		alert("Para prosseguir com o cadastro você deve aceitar os termos de uso.");
		return false;
	}
	if(!frm.aceite3.checked) {
		alert("Para prosseguir com o cadastro você deve aceitar à política de privacidade.");
		return false;
	}
	if(!frm.aceite4.checked) {
		alert("Para prosseguir com o cadastro você deve aceitar as Regras e Punições.");
		return false;
	}

	lock_screen(true);

	$.ajax({
		url: 'index.php?acao=cadastro_cadastrar',
		type: 'post',
		data: $("#fCadastro").serialize(),
		dataType: 'script',
		success:	function () {
			lock_screen(false);
		}
	});
}