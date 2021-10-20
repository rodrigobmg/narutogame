<?php
	header('Content-Type: application/json');

	$json			= new stdClass();
	$json->messages	= [];
	$json->success	= false;
	$errors			= [];
	
	if(!is_numeric($_POST['id'])) {
		redirect_to('negado');
	}
	
	if(!sizeof($errors)) {
		$json->success	= true;

		Recordset::query('INSERT INTO player_friend_requests (id_player,id_friend) VALUES ('.$basePlayer->id.', '.$_POST['id'].')');
		
		// Envia mensagem para a pessoa que solicitamos amizade
		mensageiro($basePlayer->id,$_POST['id'],t('amigos.mensageiro_title'),t('amigos.mensageiro_description').' '.$basePlayer->nome,"amigos");
		/*
		Recordset::insert('chat', array(
			'channel'	=> 'private',
			'from'		=> $basePlayer->id,
			'object_id'	=> $_POST['id'],
			'message'	=> t('amigos.mensageiro_description') .' '. $basePlayer->nome,
			'when'		=> microtime(true)
		));	
		*/
	} else {
		$json->messages	= $errors;
	}

	echo json_encode($json);