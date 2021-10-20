var _pvpTimer		= 0;
var _pvpRequestR	= true;
var _xc				= false;
var	_canAtk			= true;

$(document).ready(function () {
	_pvpTimer = setInterval(function () {
		if(!_pvpRequestR) return;
		
		_pvpRequestR = false;
		
		var dt = {_pvpToken: _pToken};	
		
		$.ajax({
			url: 'index.php?acao=dojo_lutador_lutar' + (_xc ? '&XDEBUG_PROFILE' : ''),
			dataType: 'script',
			type: 'post',
			data: dt,
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
	if(!_canAtk) return;

	var action = opt || 1;

	if(!_xc) {
		var dt = {itemID: id, action: action};
	} else {
		var dt = {itemID: id, action: action, XDEBUG_PROFILE: 1	}
	}
	
	dt._pvpToken	= _pToken;
	_canAtk			= false;
	
	$.ajax({
		url: 'index.php?acao=dojo_lutador_lutar',
		type: 'post',
		data: dt,
		success: function () {
			_canAtk = true;
		},
		error:	function () {
			_canAtk = true;		
		},
		dataType: 'script'
	});
}

function doAtkFlight() {
	if(!_canAtk) return;
	
	_canAtk = false;
	
	$.ajax({
		url: 'index.php?acao=dojo_lutador_lutar',
		type: 'post',
		data: {action: 1, flight: 1, _pvpToken: _pToken},
		success: function () {
			_canAtk = true;
		},
		error:	function () {
			_canAtk = true;		
		},
		dataType: 'script'
	});	
}
