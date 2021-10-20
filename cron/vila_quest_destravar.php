<?php
	require('_config.php');

	$npcs	= Recordset::query('SELECT * FROM npc_vila WHERE batalha=1');
	
	foreach($npcs->result_array() as $npc) {
		$should_zero	= false;
	
		if(!$npc['id_player_batalha']) {
			$should_zero	= true;
		} else {
			$battle	= Recordset::query('SELECT * FROM batalha WHERE id=' . $npc['id_player_batalha']);
			
			if(!$battle->num_rows) {
				$should_zero	= true;
			} else {
				if($battle->row()->finalizada) {
					$should_zero	= true;
				} else {
					$player	= Recordset::query('SELECT id FROM player WHERE id_batalha=' . $battle->row()->id);
					
					if(!$player->num_rows) {
						$should_zero	= true;
					}
				}
			}
		}
		
		if($should_zero) {
			echo "CHANGED\n";
		
			Recordset::update('npc_vila', array(
				'batalha'			=> 0,
				'id_player_batalha'	=> 0
			), array(
				'id'				=> $npc['id']
			));
		}
	}