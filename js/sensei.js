function ActiveSensei(sensei,mensagem) {
	jconfirm(mensagem, null, function () {
		lock_screen(true);
		$.ajax({
			url:		'?acao=sensei',
			data:		{ sensei: sensei},
			dataType:	'json',
			type:		'post',
			success:	function (result) {
				if(result.success) {
					location.href	= 'index.php?secao=sensei';
				} else {
					lock_screen(false);
					format_error(result);
				}
			}
		});
	});	
}
function BuySensei(sensei) {
	jconfirm("Você tem certeza que gostaria de gastar seus créditos ou ryous nessa compra?", null, function () {
		lock_screen(true);
		$.ajax({
			url:		'?acao=buy_sensei',
			data:		{ sensei: sensei},
			dataType:	'json',
			type:		'post',
			success:	function (result) {
				if(result.success) {
					location.href	= 'index.php?secao=sensei';
				} else {
					lock_screen(false);
					format_error(result);
				}
			}
		});
	});	
}
