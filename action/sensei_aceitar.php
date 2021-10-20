<?php
	header('Content-Type: application/json');

	$json			= new stdClass();
	$json->messages	= [];
	$json->success	= false;
	$errors			= [];
	$redir_script	= true;

	$npc			= decode($_POST['npc']);
	$player_sensei 	= Recordset::query("SELECT * FROM player_sensei_desafios WHERE id_player=" . $basePlayer->id . " AND id_sensei = ". $basePlayer->id_sensei)->row_array();
	$sensei 		= Recordset::query("SELECT * FROM sensei WHERE id = ". $basePlayer->id_sensei)->row_array();
	
	if(!$sensei) {
		redirect_to('negado', NULL, array('e' => 2));
	}
	// Limites de 50% --->
	if($basePlayer->getAttribute('hp') < ($basePlayer->getAttribute('max_hp') / 2)) {
		$errors[]	= t('actions.a109');
	}

	if($basePlayer->getAttribute('sp') < ($basePlayer->getAttribute('max_sp') / 2)) {
		$errors[]	= t('actions.a109');
	}

	if($basePlayer->getAttribute('sta') < ($basePlayer->getAttribute('max_sta') / 2)) {
		$errors[]	= t('actions.a109');
	}
	if(!sizeof($errors)) {
		$json->success	= true;
		Fight::cleanup();
		
		// Escolhe o NPC da Vez.
		/*$sensei_npc 	= explode(",",$sensei['id_npcs']);
		$sensei_npc 	= $sensei_npc[array_rand($sensei_npc)];
		$sensei_npc		= ($player_sensei && $player_sensei['desafio'] % 5 == 0) ? $sensei['id_boss'] : $sensei_npc;*/
		
		// Qual é o desafio?
		$desafio = $player_sensei ? $player_sensei['desafio'] : 0;
		
		$batalha = Recordset::insert('batalha', array(
			'id_tipo'		=> 8,
			'id_player'		=> $basePlayer->id,
			'current_atk'	=> 1,
			'data_atk'		=> array('escape' => false, 'value' => 'NOW()')
		));
		
		// Instancia o NPC
		$baseEnemy = new NPC($npc, $basePlayer, NPC_SENSEI, false, $desafio);
		$baseEnemy->batalha_id	= $batalha;
	
		$basePlayer->setAttribute('id_batalha', $batalha);
		SharedStore::S('_BATALHA_' . $basePlayer->id, serialize($baseEnemy));
	
		//redirect_to("dojo_batalha_lutador");
	} else {
		$json->messages	= $errors;
	}

	echo json_encode($json);
