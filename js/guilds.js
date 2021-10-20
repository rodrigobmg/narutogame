function entrarGuild(id) {
	lock_screen(true);

	$.ajax({
		url: 		'index.php?acao=guild_entrar',
		dataType:	'script',
		type:		'post',
		data: 		{id: id},
		dataType:	'json',
		success:	function (e) {
			lock_screen(false);

			if (e.success) {
				$('#b-entrar-guild-' + id).attr('src', __site + '/images/bt_participar_sml_off.gif')
			} else {
				format_error(e);
			}
		}
	});
}
function sendRequest(id) {
	lock_screen(true);

	$.ajax({
		url: 		'index.php?acao=amigos_requests',
		dataType:	'script',
		type:		'post',
		data: 		{id: id},
		dataType:	'json',
		success:	function (e) {
			lock_screen(false);

			if (e.success) {
				location.href = 'index.php?secao=lista_amigos';
			} else {
				format_error(e);
			}
		}
	});
}
function amigoNegar(id) {
	lock_screen(true);

	$.ajax({
		url: 		'index.php?acao=amigos_negar',
		dataType:	'script',
		type:		'post',
		data: 		{id: id},
		dataType:	'json',
		success:	function (e) {
			lock_screen(false);

			if (e.success) {
				location.href = 'index.php?secao=lista_amigos';
			} else {
				format_error(e);
			}
		}
	});
}
function amigoAceitar(id) {
	lock_screen(true);

	$.ajax({
		url: 		'index.php?acao=amigos_aceitar',
		dataType:	'script',
		type:		'post',
		data: 		{id: id},
		dataType:	'json',
		success:	function (e) {
			lock_screen(false);

			if (e.success) {
				location.href = 'index.php?secao=amigos';
			} else {
				format_error(e);
			}
		}
	});
}
function amigoRemover(id) {
	lock_screen(true);

	$.ajax({
		url: 		'index.php?acao=amigos_remover',
		dataType:	'script',
		type:		'post',
		data: 		{id: id},
		dataType:	'json',
		success:	function (e) {
			lock_screen(false);

			if (e.success) {
				location.href = 'index.php?secao=amigos';
			} else {
				format_error(e);
			}
		}
	});
}
function playerProfile(id) {
	window.open("?acao=profile&id=" + id, "", "width=1100,height=850,statusbar=no,menubar=no,toolbar=no,scrollbars=yes,resizable=yes");
}
