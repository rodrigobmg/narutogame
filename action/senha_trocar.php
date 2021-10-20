<?php
	$rU	= Recordset::query("SELECT * FROM global.user WHERE id=" . $_SESSION['usuario']['id'])->row_array();


	if(!$rU['password']) { // Ou seja, é conta do facebook ja que não tem senha
		redirect_to("negado", NULL, array("e" => "2"));
	}

	if(!is_numeric(decode($_POST[$_SESSION['el_js_field_name']]))) {
		redirect_to("negado", NULL, array("e" => "3"));	
	}

	if(!Recordset::query("SELECT id FROM global.user WHERE id={$_SESSION['usuario']['id']} AND `password`=" . mysql_compat_password($_POST['senha_atual']))->num_rows) {
		redirect_to("senha_trocar", NULL, array("e" => "1"));
	} else {
		Recordset::query("UPDATE global.user SET `password`=" . mysql_compat_password($_POST['senha']) . " WHERE id=" . $_SESSION['usuario']['id']);	

		redirect_to("senha_trocar", NULL, array("ok" => ""));
	}
