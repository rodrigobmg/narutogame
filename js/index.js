function $F(formName) {
	var frm = document.forms[formName];
	if(!frm) return false;

	frm.getSelection = function (dwObject, sFormat) {
		var nodes = frm[dwObject];
		var list = Array();
		
		if(nodes[0]) { // NodeList
			for(var i = 0; i < nodes.length; i++) {
				if(nodes[i].checked) list.push(nodes[i].value);
			}
		} else { // Single
			if(nodes.checked) list.push(nodes.value);
		}
		
		if(sFormat == 0 || !sFormat)
			return list.join(",");
		else if(sFormat == 1)
			return list;
	}
	
	frm.removeSelection = function (dwObject) {
		var nodes = frm[dwObject];
		
		if(nodes[0]) {
			for(var i = 0; i < nodes.length; i++) {
				nodes[i].checked = false;
			}
		} else {
			nodes.checked = false;	
		}
	}
	
	frm.hasSelection = function(dwObject) {
		if(this.getSelection(dwObject).length)
			return true;
		else
			return false;
	}

	return frm;
}

function indexLogin() {
	var frm = $F("fIndexLogin");
	
	if(!frm.email.value.match(/facebook/i) && !frm.email.value.match(/^[_\w\-\.]+@([_\w\-]+(\.[_\w\-]+)+)$/im)) {
		alert("O E-Mail digitado é inválido! Verifique o e-mail digitado e tente novamente.");
		return false;
	}
	
	if(!frm.senha.value) {
		alert("Nenhuma senha especificada!");
		return false;
	}

	if (!$('#fIndexLogin [name=cookie]').val()) {
		alert("Falha ao efetuar login, tente novamente");
		return;
	}
	
	frm.submit();
}

