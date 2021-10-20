function doSelecionaPersonagem(o, id, attr) {
	$(".imgPers").animate({opacity: .5}, 100);
	$(o).animate({opacity: 1}, 300);

	setPValue2(attr.hp, (attr.mhp || 1), "", $("#cnAtHP"));
	setPValue2(attr.sp, (attr.msp || 1), "", $("#cnAtSP"));
	setPValue2(attr.sta, (attr.msta || 1), "", $("#cnAtSTA"));
	
	$("#imgP").html($(o).data('image'));
	
	$("#cnNinjaP").html(attr.nome);
	$("#cnVilaP").html(attr.vila);
	$("#cnLevelP").html(attr.level);
	$("#cnGraduacaoP").html(attr.g);
	$("#cnRyouP").html(attr.ryou);
	
	$F('fPersonagem').id.value = id;
}

function doJogar() {
	var frm = $F('fPersonagem');
	
	if(!frm.id.value) {
		alert("Nenhum personagem selecionado!");
		return false;
	}
	
	//$("#cnData").html("Aguarde...");
	frm.submit();
}

function doDeletar() {
	var frm = $F('fPersonagem');
	
	if(frm.missao_equipe) {
		alert("Você não pode fazer essa operação enquanto estiver em missão de equipes!");
		
		return false;
	}
	
	if(!frm.id.value) {
		alert("Nenhum personagem selecionado!");
		return false;
	}
	
	if(!confirm("Você quer realmente deletar esse ninja ?")) {
		return false;
	}

	$.ajax({
		url: 'index.php?acao=personagem_deletar',
		dataType: 'script',
		type: 'post',
		data: {id: frm.id.value}
	});		

	$("#cnBase").html("<center>Aguarde...</center>");
}

$(document).ready(function () {
	$('.imgPers:first').trigger('click');	
});