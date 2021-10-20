var __g_habil = 1;

function doHabilidade(id, obj) {
	__g_habil = id;

	$(".trTipoJutso").attr("background", __site + "/images/bt_aba.gif");
	
	$("#trTipoJutsu" + id).attr("background", __site + "/images/bt_aba2.gif");
	$("#jutsuTab" + id).show();

	$('.tr-jutsu').hide();
	$('.tr-jutsu-' + id).show();
	
	if($('#s-filter').val()) {
		setFiltro($('#s-filter').val(), true);
	}
}

function doJutsu(id) {
	$F('fTreino').id.value = id;
	$F('fTreino').submit();
}

function treinamentoDesc(v) {
	$(".habilidade").hide();
	$(document.getElementById("cnHabil_" + v)).show();
}

function setFiltro(v, noh) {
	if(!noh) {
		doHabilidade(__g_habil);
	}

	if(v) {
		$(".tr-jutsu-filtro").each(function () {
			if(!$(this).hasClass('tr-jutsu-filtro-' + v)) {
				$(this).hide();
			}
		});	
	} else {
		$(".tr-jutsu-filtro").show();
	}


	/*
	if(!v) {
		$(".tr-jutsu-filtro").show();
	} else {
		$(".tr-jutsu-filtro").hide();	
		$(".tr-jutsu-filtro-" + v).show();
	}
	*/
}