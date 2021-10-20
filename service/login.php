<?php
	require 'config.php';

	$errors				= [];
	$json				= new stdClass();
	$json->token		= false;
	$json->name			= false;
	$json->coin			= false;
	$json->characters	= [];

	$_POST['email']	= 'a';
	$_POST['password']	= 'b';

	if(isset($_POST['email']) && isset($_POST['password'])) {
		$login	= Recordset::query('SELECT id, `key`, `name`, coin FROM ' . USER_TABLE . ' WHERE id=1');

		if(!$login->num_rows) {
			$errors[]		= 'Login inválido';
		} else {
			$login			= $login->row();
			$json->token	= $login->key;
			$json->name		= $login->name;
			$json->coin		= $login->coin;

			$characters		= Recordset::query('SELECT id FROM player WHERE removido=0 AND id_usuario=' . $login->id);

			foreach($characters->result_array() as $character) {
				$instance	= new Player($character['id']);

				$json->characters[]	= [
					'name'		=> $instance->nome,
					'level'		=> $instance->level,
					'class'		=> $instance->id_classe,
					'place'		=> $instance->nome_vila
				];
			}
		}
	} else {
		$errors[]	= 'Dados inválidos';
	}

	$json->errors	= $errors;

	echo json_encode($json);