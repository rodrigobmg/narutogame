function BuyPacks(id, type) {
	jconfirm("Você tem certeza que gostaria de gastar seus créditos ou ryous nessa compra?", null, function () {
		lock_screen(true);
		$.ajax({
			url:		'?acao=packs_buy',
			data:		{ id: id, type: type},
			dataType:	'json',
			type:		'post',
			success:	function (result) {
				if(result.success) {
					location.href	= 'index.php?secao=packs&premio=' + result.logs;
				} else {
					lock_screen(false);
					format_error(result);
				}
			}
		});
	});	
}

