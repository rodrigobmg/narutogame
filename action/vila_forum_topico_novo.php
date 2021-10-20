<?php
	if(get_player_bloqueio($basePlayer->id)) {
		redirect_to('negado');	
	}

	$can_create_fixed	= false;
	$vila				= Recordset::query('SELECT id_cons_vila, id_kage, id_cons_guerra, id_cons_defesa FROM vila WHERE id=' . $basePlayer->id_vila)->row_array();

	if($vila['id_kage'] == $basePlayer->id || $vila['id_cons_vila'] == $basePlayer->id ||
	   $vila['id_cons_guerra'] == $basePlayer->id || $vila['id_cons_defesa'] == $basePlayer->id ||
	   $_SESSION['universal']
	) {
	
		$can_create_fixed	= true;	   
	}

	if($can_create_fixed && isset($_POST['fixed']) && $_POST['fixed']) {
		$fixo = 1;
	} else {
		$fixo = 0;
	}

	$kage			= 0;
	$cons_guerra	= 0;
	$cons_def		= 0;
	$cons_vila		= 0;

	if($vila['id_kage'] == $basePlayer->id) {
		$kage	= 1;
	}

	if($vila['id_cons_guerra'] == $basePlayer->id) {
		$cons_guerra	= 1;
	}

	if($vila['id_cons_defesa'] == $basePlayer->id) {
		$cons_def	= 1;
	}

	if($vila['id_cons_vila'] == $basePlayer->id) {
		$cons_vila	= 1;
	}

	$query = Recordset::query("
		INSERT INTO vila_forum_topico(
			id_vila,
			id_player,
			id_usuario,
			titulo,
			fixo,
			ult_resposta
		) VALUES (
			{$basePlayer->id_vila},
			{$basePlayer->id},
			{$_SESSION['usuario']['id']} ,
			'" . addslashes(htmlspecialchars($_POST['titulo'])) . "',
			'" . $fixo . "',
			NOW()
		)");

	$id	= $query->insert_id();
	
	Recordset::query("
		INSERT INTO vila_forum_topico_post(
			id_vila_forum_topico,
			id_player,
			id_usuario,
			conteudo,
			kage,
			cons_vila,
			cons_def,
			cons_guerra
		) VALUES(
			" . $id . ",
			" . $basePlayer->id . ",
			" . $_SESSION['usuario']['id']. ",
			'" . addslashes(htmlspecialchars(check_words($_POST['topico_conteudo']))) . "',
			" . $kage . ",
			" . $cons_vila . ",
			" . $cons_def . ",
			" . $cons_guerra . "
		)");

	redirect_to("vila_forum", "", array("created" => 1));
