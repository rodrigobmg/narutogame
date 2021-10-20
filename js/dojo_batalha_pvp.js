var _isPVP = true;
var _pvpTimer = 0;
var _pvpRequestR = true;

$(document).ready(function () {
	_pvpTimer = setInterval(function () {
		if(!_pvpRequestR) return;
		
		_pvpRequestR = false;
		
		$.ajax({
			url: 'index.php?acao=dojo_batalha_lutar',
			dataType: 'script',
			type: 'post',
			data: {_pvpToken: _pToken},
			success: function () {
				_pvpRequestR = true;
			},
			error: function () {
				_pvpRequestR = true;
			}
		});
	}, 5000);
});

function doAttack(id, opt) {
	_pvpRequestR = false;
	
	_canAtk = opt ? true : false;
	var action = opt || "";

	$.ajax({
		url: 'index.php?acao=dojo_batalha_lutar',
		type: 'post',
		data: {itemID: id, action: action, _pvpToken: _pToken},
		dataType: 'script',
		success: function () {
			_pvpRequestR = true;
		},
		error: function () {
			_pvpRequestR = true;
		}
	});	
}

$(window).unload(function (e) {
	if(isBrowserClosed()) {
		$.ajax({
			url: 'index.php?acao=dojo_batalha_lutar',
			type: 'post',
			data: {action: 2, _pvpToken: _pToken},
			dataType: 'script',
			async: false
		});
	}
});

function doAtkFlight() {
	if(!_pvpRequestR) return;
	
	_pvpRequestR = false;
	
	$.ajax({
		url: 'index.php?acao=dojo_batalha_lutar',
		type: 'post',
		data: {action: 1, flight: 1, _pvpToken: _pToken},
		success: function () {
			_pvpRequestR = true;
		},
		error:	function () {
			_pvpRequestR = true;		
		},
		dataType: 'script'
	});
}