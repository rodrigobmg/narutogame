var _dojoTimer = 0;

$(document).ready(function () {
	_dojoTimer = setInterval(function () {
		$.ajax({
			url: 'index.php?acao=dojo_batalha_espera',
			dataType: 'script',
			type: 'post',
			data: {method: 1}
		});
	}, 5000);
});

function doCancelarBatalha() {
	$.ajax({
		url: 'index.php?acao=dojo_batalha_espera_cancela',
		type: 'post',
		dataType: 'script'
	});	
}

$(window).unload(function (e) {
	if(isBrowserClosed()) {
		$.ajax({
			url: 'index.php?acao=dojo_batalha_espera_cancela',
			type: 'post',
			dataType: 'script',
			async: false
		});
	}
});