<pre>
<?php
	require('_config.php');
	/*
	$quests	= Recordset::query('SELECT * FROM quest WHERE id_vila > 8');
	
	foreach($quests->result_array() as $quest) {
		echo '+ Quest ' . $quest['id'] . ' --> Vila ' . $quest['id_vila'] . PHP_EOL;
	
		if($quest['interativa']) {
			$player_quests	= Recordset::query('
				SELECT * 
				FROM 
					player_quest 
				WHERE 
					(falha=1 OR finalizada=1) AND
					id_vila='	. $quest['id_vila'] . ' AND 
					id_quest='	. $quest['id']);
		} else {
			$player_quests	= Recordset::query('
				SELECT * 
				FROM 
					player_quest 
				WHERE
					(falha=1 OR completa=1) AND
					id_vila='	. $quest['id_vila'] . ' AND 
					id_quest='	. $quest['id']);
		}
		
		echo '+ Total ' . $player_quests->num_rows . PHP_EOL;
		
		foreach($player_quests->result_array() as $player_quest) {
			echo '- DEL' . PHP_EOL;
			// Remove os itens do interativa
			if($quest['interativa']) {
				Recordset::delete('player_quest_npc_item', array(
					'id_player'			=> $player_quest['id_player'],
					'id_player_quest'	=> $player_quest['id_quest']					
				));
			}
			
			// Remove a quest em si
			Recordset::delete('player_quest', array(
				'id_player'	=> $player_quest['id_player'],
				'id_quest'	=> $player_quest['id_quest']
			));
			
			// Remove o player da quest, pois pra ele ter chegado nas condições acima, ele ja recebeu a recompensa
			Recordset::update('player', array(
				'id_missao'	=> 0
			), array(
				$player_quest['id_player']
			));
		}
	}*/
	
	Recordset::query('UPDATE player_flags SET armas_semanais=0');