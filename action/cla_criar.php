<?php
	$id = decode($_POST[$_SESSION['cl_js_field_name']]);


	if(!is_numeric($id)) {
		redirect_to("negado");	
	}

	// Sem grana ou gradua��o
	if($basePlayer->id_graduacao < 3 || $basePlayer->RYOU < 15000) {
		redirect_to("negado");	
	}

	$q = Recordset::query("INSERT INTO cla(id_player, nome) VALUES(
		{$basePlayer->id}, '" . htmlentities(addslashes(utf8_decode($_POST['nome']))) . "'
	)");

	$id_cla = $q->insert_id();

	Recordset::query("UPDATE player SET id_cla=$id_cla,  ryou=ryou - 15000 WHERE id=" . $basePlayer->id);

	// Regera a chave de criptografia
	$_SESSION['key'] = md5(rand(0, 512384) . rand(0, 512384));

	$redir_script = true;
	redirect_to("cla_detalhe");
