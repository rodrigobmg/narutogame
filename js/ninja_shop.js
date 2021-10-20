var _win;
var _m = [];
var _nb = [];

function doCompra(id, c, t, tt, v) {
	if(tt && tt == 2) {
		if(!confirm("Se você comprar este item substituira a outra arma de curto alcançe no seu inventário.\nQuer continuar a compra?")) {
			return false;	
		}
	}

	if(!t) {
		if(!$("#pm_" + c).val()) {
			alert("Você precisa escolher uma forma de pagar o item!");
			return false;
		}

		var pm = $("#pm_" + c).val();

		if(!$("#tQtd" + c).val().match(/^([1-9]|[1-9][0-9]+)$/)) {
			alert("Quantidade inválida!");
			return false;
		}
		
		$("#bCompra_" + c).animate({opacity: .4}, 100);
		$("#bCompra_" + c).attr("disabled", true);
		
		var qq = $("#tQtd" + c).val();
	} else {
		var qq = 1;
		var pm = document.getElementById("pm_" + id).value;
	}
	
	$.ajax({
		url: "?acao=ninja_shop_compra",
		dataType: 'script',
		type: 'post',
		data: {id: id, q: qq, c: c, pm: pm, v: v, ninja_shop_key: __ninja_shop_key},
		success: function () {
			$("#bCompra_" + c).animate({opacity: 1}, 100);
			$("#bCompra_" + c).attr("disabled", false);
		}
	});
}

/*
// Sistema de equiopamentos
$(document).ready(function () {
	$(".itemDropBox").each(function () {
		var r = this.id.match(/cnDropZone_(.+)$/i);
		var _this = this;
		
		$(this).droppable({
			 accept: "." + r[1], 
			 tolerance: 'touch',
			 activate: function () {
				$(_this).toggleClass("itemDropBoxActive");
			 },
			 deactivate: function () {
				$(_this).toggleClass("itemDropBoxActive");
			 },
			 drop: function(event, ui) { // Quando dropa
				$(ui.draggable).css("position", "absolute");
				
				//$(ui.draggable.className, $("#cnCorpoP")).delete();
				$("#cnCorpoP").prepend(ui.draggable);
				
				$(ui.draggable).css("margin-top", parseInt($(this).css("margin-top")) + 1);
				$(ui.draggable).css("margin-left", parseInt($(this).css("margin-left")) - 87);
				$(ui.draggable).css("z-index", 100);
				$(ui.draggable).css("top", "").css("left", "");
				
				$(ui.draggable).draggable('destroy');
	
				$(ui.draggable).animate({opacity: 1}, 100);
				
				doCompra(ui.draggable.attr("id"), 1, true);
			}
		});
		
		$("." + r[1]).each(function () {
		   if(!$(this).attr("equipado")) {
				$(this).draggable({revert: 'invalid',
					start: function () {
						$("." + r[1], $("#cnCorpoP")).animate({opacity: .2}, 100);
					},
					stop: function () {
						$("." + r[1], $("#cnCorpoP")).animate({opacity: 1}, 100);				
					}
				});
			}
			
			return true;
		});
		
		// Tooltips
		$("." + r[1]).mouseover(function(e) {
			if(e.target.isdown) return;
										 
			_win = document.createElement('DIV');
			_win.className = "pvpAtkDetailPopup";
			
			var id = $(e.target).attr('id');
			
			var lvlColor = _ll < _m[id]['level'] ? "style='color: #F00'" : "";
			var grdColor = _gg < _m[id]['rg'] ? "style='color: #F00'" : "";
			var mnyColor = _rr < _m[id]['preco'] ? "; color: #F00" : "";
			var coiColor = _co < _m[id]['coin'] ? "; color: #F00" : "";
			
			if(_m[id]['rc']) {
				var claColor = _cc != _m[id]['rc'] ? "style='color: #F00'" : "";
			} else {
				var claColor = null;
			}
			
			if(parseInt(_m[id]['rp'])) {
				var porColor = !_po ? "style='color: #F00'" : "";
				var msgPortao = _po && _m[id]['rp'] ? "OK" : "Necess&aacute;rio";
			} else {
				var msgPortao = "Nenhum";
				var porColor = null;
			}

			if(lvlColor || grdColor || mnyColor || claColor || coiColor || porColor) {
				$(e.target).draggable('destroy');
			}
			
			try {
			_win.innerHTML = "<b style='position: relative; float: left'>" + _m[id]['nome'] + "</b>" + 
							 "<b style='position: relative; float: right" + (!_m[id]['pag_m'] ? mnyColor : coiColor) + "'>" + 
							 "<img src='images/topo/p_ryou.png' align='absmiddle' /> " +
							 (!_m[id]['pag_m'] ? _m[id]['preco'] : _m[id]['coin']) + "</b>" + 
							 "<br /><br />" + _m[id]['descricao'] +
							"<hr /><b>Requerimentos:</b><br /><br />" + 
							 "<span " + lvlColor + "><b>Level:</b> " + _m[id]['level'] + "</span><br />" +
							 "<span " + grdColor + "><b>Graduação:</b> " + _g[_m[id]['rg']] + "</span><br />" +
							 (_m[id]['rc'] ? "<span " + claColor + "><b>Clã:</b> " + _c[_m[id]['rc']] + "</span><br />" : "") + 
							 (_m[id]['rp'] ? "<span " + porColor + "><b>Portão:</b> " + msgPortao + "</span><br />" : "") + 
							 "<hr /><b>Atributos adicionais:</b><br /><br />" + 
							 "<img src='images/topo/attack.png' />  Taijutsu: " + 	_m[id]['tai'] +
							 " / <img src='images/topo/attack.png' /> Ninjutsu: " + _m[id]['nin'] + "<br />" +
							 "<img src='images/topo/attack.png' /> Genjutsu: " + 	_m[id]['gen'] +  
							 " / <img src='images/topo/agi.png' /> Agilidade: " + 	_m[id]['agi'] + "<br />" +
							 "<img src='images/topo/conhe.png' /> Selo: " + _m[id]['con'] +
							 " / <img src='images/topo/forc.png' /> Força: " + 		_m[id]['for'] + "<br />" +
							 "<img src='images/topo/ene.png' /> Energia: " +		_m[id]['ene'] +
							 " / <img src='images/topo/inte.png'/> Inteligência: " + _m[id]['int'] +
							 "<hr /><b><img src='images/topo/p_hp.png' /> +" + _m[id]['hp'] + "</b>" +
							 " / <b><img src='images/topo/p_chakra.png' /> +" + _m[id]['sp'] + "</b>" +
							 " / <b><img src='images/topo/p_stamina.png' /> +" + _m[id]['sta'] + "</b>";
			} catch (e) {
				console.log(e);
			}
			_win.style.left = (e.pageX  + 8) + "px";
			_win.style.top = (e.pageY + 16) + "px";

			document.getElementsByTagName("BODY")[0].appendChild(_win);

			$(_win).animate({ opacity: 0 }, 10);
			$(_win).animate({ opacity: .9 }, 200);
		});
	
		$("." + r[1]).mouseout(function(e) {
			try {
				document.getElementsByTagName("BODY")[0].removeChild(_win);
			} catch (e) { }
		});

		$("." + r[1]).mousedown(function(e) {
			e.target.isdown = true;
			try {
				document.getElementsByTagName("BODY")[0].removeChild(_win);
			} catch (e) { }
		});

		$("." + r[1]).mouseup(function(e) {
			e.target.isdown = false;
			try {
				document.getElementsByTagName("BODY")[0].removeChild(_win);
			} catch (e) { }
		});

		return true;
	});
});
*/


function showEquip(i) {
	$(".cnETab").hide();
	document.getElementById("cne_" + i).style.display = "";
}

function listItemGroups() {
	//$("#slItemGroup").load("?acao=ninja_shop_lista", {t: $("#slItemTipo").val(), c: $("#slItemCla").val(), g: $("#sItemGrad").val()});
	$("#slItemGroup").load("?acao=ninja_shop_lista", {c: $("#slItemCla").val(), g: $("#sItemGrad").val()});
	$(".itemOriginObject").hide();
}

function selectItemGrupo() {
	$(".itemOriginObject").hide();
	$(".grp" + $("#slItemGroup").val()).show();
}