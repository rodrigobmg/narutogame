<?php
	$id = decode($_POST['id']);

	if(!is_numeric($id)) {
		redirect_to("negado");	
	}

	if(!cani_post_reply($id)){
		redirect_to('negado');		
	}

	if(get_player_bloqueio($basePlayer->id)) {
		redirect_to('negado');
	}
	
	if(!($basePlayer->vip || $basePlayer->level > 14)) {
		redirect_to('negado');		
	}

	$kage			= 0;
	$cons_guerra	= 0;
	$cons_def		= 0;
	$cons_vila		= 0;
	$vila			= Recordset::query('SELECT * FROM vila WHERE id=' . $basePlayer->id_vila)->row_array();
	$rTopico		= Recordset::query("SELECT * FROM vila_forum_topico WHERE id=" . (int)$id)->row_array();

	// Se o id do topico for e outra liva da negado (vai ser um mlagre isso aki acontecer .. mas por precaução)
	if($rTopico['id_vila'] != $basePlayer->id_vila) {
		redirect_to("negado", null, array('e' => 2));
	}

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
		) VALUES (
			$id,
			{$basePlayer->id},
			{$_SESSION['usuario']['id']},
			'" . addslashes(htmlspecialchars(check_words($_POST['msg_conteudo']))) . "',
			" . $kage . ",
			" . $cons_vila . ",
			" . $cons_def . ",
			" . $cons_guerra . "			
		)");
	
	Recordset::query("UPDATE vila_forum_topico SET respostas=respostas+1, ult_resposta=NOW(), ult_resposta_id=" . $basePlayer->id . " WHERE id=" . $id);

	redirect_to("vila_forum_topico", "", array("id" => encode($id) , "created" => 1));
