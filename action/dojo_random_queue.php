<?php
	header('Content-Type: application/json');

	$json			= new stdClass();
	$json->success	= false;
	$json->messages	= array();

	$last_rnd_inactivity = Player::getFlag('ult_inatividade', $basePlayer->id);
	$can_battle_rnd			= true;
	$today					= date('N');
	$is_day					= ($today == 4 || $today == 2) || $_SESSION['universal'];
	

	if($basePlayer->id_graduacao <=  1) {
		$can_battle_rnd	= false;
	}

	if($basePlayer->credibilidade == 0) {
		$can_battle_rnd	= false;
	}

	if($last_rnd_inactivity != '') {
		$diff	= Recordset::query('SELECT HOUR(TIMEDIFF(NOW(), ult_Inatividade)) AS diff FROM player_flags WHERE id_player=' . $basePlayer->id)->row()->diff;
	
		// Já passou 1h ?
		if($diff < 1) {
			$can_battle_rnd	= false;
		}
	}	
	
	// 4x4
	if(isset($_POST['queue4x']) && $_POST['queue4x'] && !$basePlayer->id_random_queue && $can_battle_rnd && $is_day) {
		if(!($basePlayer->hp < $basePlayer->max_hp / 2 || $basePlayer->sp < $basePlayer->max_sp / 2 || $basePlayer->sta < $basePlayer->max_sta / 2 || $basePlayer->level < 5)) {
			$id	= Recordset::insert('batalha_4x_fila', array(
				'id_player'	=> $basePlayer->id,
				'level'		=> $basePlayer->level,
				'ip'		=> array('escape' => false, 'value' => 'INET_ATON("' . $_SERVER['REMOTE_ADDR'] . '")')
			));
		
			Recordset::update('player', array(
				'id_random_queue'		=> $id,
				'id_random_queue_type'	=> 4
			), array(
				'id'					=> $basePlayer->id
			));
			
			$json->success	= true;
		}
	}

	// 1x1
	if(isset($_POST['queue1x']) && $_POST['queue1x'] && !$basePlayer->id_random_queue && $can_battle_rnd) {
		if(!($basePlayer->hp < $basePlayer->max_hp / 2 || $basePlayer->sp < $basePlayer->max_sp / 2 || $basePlayer->sta < $basePlayer->max_sta / 2)) {
			$id	= Recordset::insert('batalha_1x_fila', array(
				'id_player'	=> $basePlayer->id,
				'level'		=> $basePlayer->level,
				'ip'		=> array('escape' => false, 'value' => 'INET_ATON("' . $_SERVER['REMOTE_ADDR'] . '")')
			));
		
			Recordset::update('player', array(
				'id_random_queue'		=> $id,
				'id_random_queue_type'	=> 1
			), array(
				'id'					=> $basePlayer->id
			));
			
			$json->success	= true;
		}
	}
	
	if(isset($_POST['dequeue']) && !$basePlayer->id_batalha_multi_pvp) {
		Recordset::insert('batalha_' . $basePlayer->id_random_queue_type . 'x_fila_sair', array(
			'id_queue'	=> $basePlayer->id_random_queue
		));
		
		$json->success	= true;
	}
	
	if(isset($_POST['check_location'])) {
		$json->location	= false;
	
		if($basePlayer->id_batalha_multi_pvp) {
			$json->location	= 'dojo_batalha_multi_pvp';
		}

		if($basePlayer->id_batalha) {
			$json->location	= 'dojo_batalha_pvp';
		}
		
		if(!$basePlayer->id_random_queue) {
			$json->location	= 'personagem_status';			
		}
	
		$json->success	= true;
	}
	
	echo json_encode($json);
