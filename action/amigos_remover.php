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
		
		Recordset::query("DELETE FROM player_friend_lists WHERE id_player=".$basePlayer->id." AND id_friend=".$_POST['id']);
		Recordset::query("DELETE FROM player_friend_lists WHERE id_player=".$_POST['id']." AND id_friend=".$basePlayer->id);
		
	} else {
		$json->messages	= $errors;
	}

	echo json_encode($json);