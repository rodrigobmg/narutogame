<?php
	$id = decode($_POST['id']);

	if(!is_numeric($id)) {
		redirect_to("negado");	
	}

	Recordset::insert('guild_forum_topico_post', array(
		'id_guild_forum_topico'	=> $id,
		'id_player'				=> $basePlayer->id,
		'conteudo'				=> htmlentities(check_words($_POST['msg_conteudo']))
	));

	redirect_to("guild_forum_topico", "", array("id" => encode($id) , "created" => 1));
