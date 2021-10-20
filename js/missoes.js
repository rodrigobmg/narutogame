function doAceitaQuest(id, m, key) {
	m = m ? $('#m_' + m).val() : "";
	
$.ajax({
		url: 'index.php?acao=missoes_aceitar',
		dataType: 'script',
		type: 'post',
		data: {id: id, m: m, missoes_key: key},
		success: function (e) {
			//alert(e);	
		}
	});

	$('#cnBase').html("<center>Aguarde...</center>");
}

function doQuestCalc(m) {
	var v = $("#v_" + m).val();
	var e = $("#e_" + m).val();
	var o = $('#m_' + m).val();
	
	var mul = 1;

	switch(parseInt(o)) {
		case 1:
			mul = 1;
			
			break;
		case 2:
			mul = 2;
			
			break;
		case 3:
			mul = 4;
			
			break;
		case 4:
			mul = 8;
			
			break;
		case 5:
			mul = 12;
			
			break;
	}
	
	$("#vv_" + m).html("RY$ " + Math.round(v * mul));
	$("#ee_" + m).html(Math.round(e * mul) + " pontos de experi&ecirc;ncia");
}

function doMissaoTipo(tp, obj) {
	__missao_tipo = tp;
	
	doMissaoRank("D");
	
	if(obj) {
		$(".tMissaoSelB").attr("background", "http://narutogame.com.br/images/bt_aba.gif");
		$(obj).attr("background", "http://narutogame.com.br/images/bt_aba2.gif");
	}
}

function doMissaoRank(rank, obj) {
	$(".missao").hide();
	$(".missao_rank_" + rank + "_" + __missao_tipo).show();
	
	if(obj) {
		$(".tMissaoSel").attr("background", "http://narutogame.com.br/images/bt_aba.gif");
		$(obj).attr("background", "http://narutogame.com.br/images/bt_aba2.gif");
	}
}

function doMissaoRankOld(rank, obj) {
	$(".tMissaoBloco").hide();
	$("#tMissao_" + rank).show();
}