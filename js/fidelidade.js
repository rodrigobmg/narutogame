function rewardFidelity(day) {
	lock_screen(true);
	$.ajax({
		url:		'?acao=reward_fidelidade',
		data:		{ day: day},
		dataType:	'json',
		type:		'post',
		success:	function (result) {
			if(result.success) {
				location.href	= 'index.php?secao=fidelidade';
			} else {
				lock_screen(false);
				format_error(result);
			}
		}
	});
}
