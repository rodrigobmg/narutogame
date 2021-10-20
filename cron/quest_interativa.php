<?php
	require '_config.php';

	// Nota se a missão for completada e o cara não clicar no concluir, foda-se, ele não vai ganhar a missão.

	$players		= Recordset::query('
		SELECT 
			a.id,
			a.id_missao,
			a.id_equipe,
			b.equipe,
			b.id_rank
		
		FROM 
			player a JOIN quest b ON b.id=a.id_missao 
		
		WHERE 
			a.id_missao !=0
			AND b.interativa=1
			AND b.especial=0
	');
	
	foreach($players->result_array() as $player) {
		echo "- PLAYER .. ";
		
		$team		= $player['equipe'] ? (int)$player['id_equipe'] : 0;
		$last_quest	= Recordset::query('
			SELECT 
				*
			
			FROM 
				player_quest
			
			WHERE
				id_player=' . $player['id'] . '
				AND id_quest=' . $player['id_missao'] . '
				AND id_equipe=' . $team . '
			
			ORDER BY id DESC LIMIT 1
		')->row_array();
		
		if(strtotime('+0 minutes') >= strtotime($last_quest['data_conclusao'])) {
			echo "DIFF\n";
			
			Recordset::update('player', array(
				'id_missao'	=> 0
			), array(
				'id'		=> $player['id']
			));
			
			Recordset::update('player_quest', array(
				'falha'		=> 1
			), array(
				'id_player'	=> $player['id'],
				'id_equipe'	=> $team,
				'id_quest'	=> $player['id_missao']
			));

			switch($player['id_rank']) {
				case 5:
					$field = "falha_s";
				
					break;
					
				case 4:
					$field = "falha_a";
				
					break;
					
				case 3:
					$field = "falha_b";
				
					break;
					
				case 2:
					$field = "falha_c";
				
					break;
					
				case 1:
					$field = "falha_d";
				
					break;				
			}	

			Recordset::update('player_quest_status', array(
				$field		=> array('escape' => false, 'value' => $field . ' + 1')
			), array(
				'id_player'	=> $player['id']
			));
			
			// Remove os itens dropados da quest (Apr 30, 2013)  --->	
				$quest_items = Recordset::query('SELECT * FROM quest_npc_item WHERE id_quest=' . $player['id_missao'], true);
				
				foreach($quest_items->result_array() as $quest_item) {
					if($quest_item['id_item']) {
						$item = Recordset::query('SELECT id_tipo FROM item WHERE id=' . $quest_item['id_item'])->row_array();
						
						if($item['id_tipo'] != 27) { // Essa porra aqui que não deixa deletar outros tipos de itens
							continue;
						}
		
						Recordset::delete('player_item', array(
							'id_player'	=> $player['id'],
							'id_item'	=> $quest_item['id_item']
						));
					}			
				}
			// <---
		} else {
			echo "OK\n";			
		}
	}

