<?php
	$id = decode($_POST['id']);

	if(!is_numeric($id)) {
		redirect_to("negado");	
	}

	Recordset::query("INSERT INTO equipe_forum_topico_post(id_equipe_forum_topico, id_player, conteudo) VALUES(
		$id, {$basePlayer->id}, '" . addslashes(htmlentities(check_words($_POST['msg_conteudo']))) . "'
	)");

	redirect_to("equipe_forum_topico", "", array("id" => encode($id) , "created" => 1));
