function ObjetivoPremio(id, id2) {
	lock_screen(true);
	$.ajax({
		url:		'?acao=objetivos',
		data:		{ id: id, id_player_missao: id2},
		dataType:	'json',
		type:		'post',
		success:	function (result) {
			if(result.success) {
				location.href	= 'index.php?secao=objetivos&sucess=1&id='+id;
			} else {
				lock_screen(false);
				format_error(result);
			}
		}
	});
}
