function doCancelaMissao() {
	if(confirm("Você que realmente cancelar essa missão ? Todo o seu tempo gasto até agora será perdido.")) {
		$.ajax({
			url: 'index.php?acao=missoes_cancelar',
			dataType: 'script',
			type: 'post',
			data: {}
		});		
	}
}

$(document).ready(function () {
	var title	= document.title;
	
	if(_should_pause) {
		return;	
	}
	
	_i = setInterval(function () {
		_s--;
		
		if(_s < 0) {
			_s = 59;
			_m--;
		}
		
		if(_m < 0) {
			_m = 59;
			_s =59;
			
			_h--;
		}
		
		if(_h | _m | _s) {
			var	timer	= (_h < 10 ? "0" + _h : _h ) + ":" + ( _m < 10 ? "0" + _m : _m ) + ":" + ( _s < 10 ? "0" + _s : _s);

			$("#cnTImer").html(timer);
			document.title = '[' + timer + '] ' + title;
		} else {
				clearInterval(_i);
				
				$("#cnTimer").html("Aguarde...");
				document.title = '[Aguarde...] ' + title;
			
				_r = setInterval(function () {
					location.reload();
	
					clearInterval(_r);
				}, 3000);
		}
	}, 1000);		
});