var __isCreateSpecial = false;

function doSelecionaVila(id) {
	$('#cnVilas').hide();
	$('#cnClasses').html("Aguarde...");
	
	$.ajax({
		url: 'index.php?acao=personagem_criar_vila',
		type: 'post',
		data: {vila: id},
		success: function (e) { 
			$('#cnClasses').html(e) 
		}
	});
}

function showVila(id, o) {
	$(".imgVila").animate({opacity: .5}, 100);
	$(o).animate({opacity: 1}, 300);
	
	$(".dVila").hide();
	$("#cnVila" + id).show();
}

function doTrocaVila() {
	$('#cnVilas').show();
	$('#cnClasses').html("&nbsp;");
}

var _ps_o = null;
var _ps_id = null;

function doSelecionaClasse(o, id) {
	if(!o) {
		return;
	}

	_ps_o = o;
	_ps_id = id;

	$(".imgPers").animate({opacity: .2}, 100);
	$(o).animate({opacity: 1}, 300);
	
	var attr = _arCL[id];

	__isCreateSpecial = parseInt(attr.especial);
	
	$(".cLocks").animate({opacity: 1}, 1, function () {
		$(document.getElementById("l" + id)).animate({opacity: .2}, 100);
	});
	
	ct = $('#classe_tipo').val();
	
	setPValue2(attr['HP_'   + ct], attr['mx'], "Vida", $("#cnAtHP"));
	setPValue2(attr['SP_'   + ct], attr['mx'], "Chakra", $("#cnAtSP"));
	setPValue2(attr['STA_'  + ct], attr['mx'], "Stamina", $("#cnAtSTA"));
	setPValue2(attr['AGI_'  + ct], attr['mx'], "Agilidade", $("#cnAtAgi"));
	setPValue2(attr['CON_'  + ct], attr['mx'], "Selo", $("#cnAtCon"));
	setPValue2(attr['ENE_'  + ct], attr['mx'], "Energia", $("#cnAtEne"));
	setPValue2(attr['TAI_'  + ct], attr['mx'], "Taijutsu", $("#cnAtTai"));
	setPValue2(attr['KEN_'  + ct], attr['mx'], "Bukijutsu", $("#cnAtKen"));
	setPValue2(attr['NIN_'  + ct], attr['mx'], "Ninjutsu", $("#cnAtNin"));
	setPValue2(attr['GEN_'  + ct], attr['mx'], "Genjutsu", $("#cnAtGen"));
	setPValue2(attr['RES_'  + ct], attr['mx'], "Resistência", $("#cnAtRes"));
	setPValue2(attr['FORC_' + ct], attr['mx'], "Força", $("#cnAtFor"));
	setPValue2(attr['INTE_' + ct], attr['mx'], "Inteligência", $("#cnAtInt"));
	
	if(_use_big) {
		$("#imgP").attr("src" , __site + "/images/criacao/grandes/" + id + '.jpg');
	} else {
		$("#imgP").attr("src" , __site +"/images/" + attr.imagem);
	}
	
	$("#cnNomeP").html(attr.nome);
	$("#cnVilaP").html(attr.vila);
	$("#cnHistP").html("Carregando...");
	
	$F("fPersonagem").id_classe.value = id;
	
	$.ajax({
		url: 'index.php?acao=personagem_criar_historia',
		type: 'post',
		data: {classe: id},
		success: function (e) { 
			$('#cnHistP').html(e) 
		}
	});
}

function doTrocaClasse() {
	$('#cnClasses').show();
	$('#cnPersonagem').html("&nbsp;");
}


function doCriarPersonagem() {
	var frm = $F('fPersonagem');
	
	if(!frm.id_classe.value) {
		alert("Selecione um personagem antes de prosseguir!");	
		return false;
	}

	/*
	if(!frm.nome.value.match(/^[\w\d_*.]+$/i)) {
		alert("Nome de personagem inválido!");	
		return false;
	} */

	if(__isCreateSpecial	) {
		if(!confirm("Esse personagem é especial e para utiliza-lo serão necessários 2 créditos. Deseja continua ?")) {
			return;
		}
	}
	
	if(!frm.nome.value.match(/^[\w]+$/i)) {
		alert('Nome inválido. O nome do seu personagem so pode conter letras sem acentos e numeros, sem espaços ou outros caractéres exceto underline!');
		return;	
	}
	
	$.ajax({
		url: 'index.php?acao=personagem_criar_final',
		type: 'post',
		data: $('#fPersonagem').serialize(),
		success: function (e) {
			if(e == "0") {
				alert("Já existe um personagem com o nome escolhido. Por favor escolha outro nome e tente novamente.");
				return;
			} else if(e == "1") {
				alert("O nome do personagem contém caractéres inválidos ou é muito grande!");
				return;
			} else if(e == "2") {
				alert("Tipo de classe inválida");
				return;
			} else {
				$('#cnBase').html(e) 
			}
		}
	});
}