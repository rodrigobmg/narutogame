<?php
	require '_config.php';
	set_time_limit(0);

	$player_fidelities = Recordset::query('SELECT * FROM player_fidelity WHERE (day != 1 or reward_at is not null )');
	
	foreach ($player_fidelities->result_array() as $player_fidelity) {
		if($player_fidelity['reward']==1){
			Recordset::update('player_fidelity', [
				'day'		 => $player_fidelity['day']==8 ? 1 : $player_fidelity['day']+1,
				'reward'	 => 0,
				'reward_at'	 => NULL,
				'created_at' => $player_fidelity['day']==8 ? date('Y-m-d H:i:s') : $player_fidelity['created_at']
			], [
				'id_player'	=> $player_fidelity['id_player']
				
			]);
			
		}else{
			Recordset::update('player_fidelity', [
				'day'			=> 1,
				'created_at'	=> date('Y-m-d H:i:s')
			], [
				'id_player'	=> $player_fidelity['id_player']
				
			]);
		}		
		
	}

