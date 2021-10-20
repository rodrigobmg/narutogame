<?php
	header('Content-Type: application/json');

	$json			= new stdClass();
	$json->messages	= [];
	$json->success	= false;
	$errors			= [];

	if(!is_numeric($_POST['id'])) {
		redirect_to('negado');
	}
	
	if($basePlayer->level < 15) {
		$errors[]	= t('actions.a162');
	}

	$guild	= Recordset::query('SELECT * FROM guild WHERE id=' . $_POST['id']);

	if(!$guild->num_rows) {
		$errors[]	= t('actions.a163');
	}
	
	if(Recordset::query("SELECT * FROM player_expulso WHERE id_player=" . $basePlayer->id . " AND id_objeto=" . $_POST['id'] . " AND tipo='guild'")->num_rows) {
		$errors[]	= t('actions.a164');
	}

	if(!sizeof($errors)) {
		$json->success	= true;

		if(!Recordset::query("SELECT id FROM guild_pendencia WHERE id_guild=" . $_POST['id'] . " AND id_player=" . $basePlayer->id)->num_rows) {
			Recordset::insert('guild_pendencia', array(
				'id_player'	=> $basePlayer->id,
				'id_guild'	=> $_POST['id']
			));
		}
	} else {
		$json->messages	= $errors;
	}

	echo json_encode($json);
