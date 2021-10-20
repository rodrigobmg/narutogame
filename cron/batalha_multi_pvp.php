<?php
	require '_config.php';

	/*
	if(!isset($_SERVER['argv'][1])) {
		die('cancel');
	}
	*/

	$battles	= Recordset::query('SELECT * FROM batalha_multi_pvp WHERE finished=0 AND MINUTE(TIMEDIFF(NOW(), data_atk)) > 1');
	
	foreach($battles->result_array() as $battle_instance) {
		$counter_e	= 2;
		$counter_p	= 1;
		$update		= array('data_atk' => now(true));
		$pvp_size	= 0;
		
		for($f = 0; $f <= 1; $f++) {
			$key		= $f ? 'e' : 'p';

			for($i = 1; $i <= PVPT_MAX_TURNS / 2; $i++) {
				if($battle_instance[$key . $i]) {

					$object	= unserialize($battle_instance[$key . $i]);
					$pvp_size++;
					
					if(!$f) {
						$counter	= $counter_p;
						$counter_p	+= 2;
					} else {
						$counter	= $counter_e;
						$counter_e	+= 2;
					}
					
					$players[$object->id]			= $object;
					$players_by_order[$counter]		=& $players[$object->id];					
				}
			}
		}

		if($pvp_size != PVPT_MAX_TURNS) {
			define('PVPT_MAX_TURNS_OVERRIDE', $pvp_size);
		}

		echo "PVP SIZE: " . $pvp_size . "\n";
		
		if($battle_instance['target']) {
			$players_by_order[$battle_instance['target']]->alive	= false;

			Recordset::update('player_flags', array(
				'ult_inatividade'	=> date('Y-m-d H:i:s')
			), array(
				'id_player'			=>	$players_by_order[$battle_instance['target']]->id
			));
		} else {
			$players_by_order[$battle_instance['current']]->alive	= false;

			Recordset::update('player_flags', array(
				'ult_inatividade'	=> date('Y-m-d H:i:s')
			), array(
				'id_player'			=>	$players_by_order[$battle_instance['current']]->id
			));
		}
		
		$result	= pvp_do_turn_rotation($battle_instance, $players_by_order, true);
		
		if($result['finished']) {
			$update['winner']	= $result['winner'];
			$update['finished']	= 1;
		}

		if($result['next']) {
			$update['current']	= $result['next'];
			$update['target']	= 0;
		}
		
		foreach(odds() as $_ => $odd) {
			$update['p' . ($_ + 1)]	= serialize($players_by_order[$odd]);
		}

		foreach(evens() as $_ => $even) {
			$update['e' . ($_ + 1)]	= serialize($players_by_order[$even]);
		}
		
		$update['data_atk']	= date('Y-m-d H:i:s');
		$battle_instance	= Recordset::query('SELECT *, MINUTE(TIMEDIFF(NOW(), data_atk)) AS diff FROM batalha_multi_pvp WHERE id=' . $battle_instance['id'])->row_array();

		# Should check on retrieval if the battle have changed
		if($battle_instance['diff'] > 1) {
			Recordset::update('batalha_multi_pvp', $update, array(
				'id'	=> $battle_instance['id']
			));
		}
	}