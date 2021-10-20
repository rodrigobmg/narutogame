<?php
	require('_config.php');

	// Solo --->	
		$players	= Recordset::query('
			SELECT
				a.id,
				a.id_missao_guild
			
			FROM
				player a JOIN guild b ON b.id=a.id_guild
				
			WHERE
				id_missao_guild !=0 AND
				id_missao_guild IS NOT NULL AND
				id_guild!=0
		');
		
		foreach($players->result_array() as $player) {
			$item	= Recordset::query('
				SELECT
					conclusao,
					falha
				
				FROM 
					player_quest_guild_npc_item 
				
				WHERE 
					id_quest_guild=' . $player['id_missao_guild'] . ' AND
					id_player=' . $player['id'])->row_array();
			
			if($item['falha']) { // Nâo é pra acontecer.. maaaaaaaaaaaaaaas
				continue;	
			}
			
			if(strtotime('+0 minute') >= strtotime($item['conclusao'])) {
				echo "+ ENDING {$player['id']}\n";
	
				Recordset::update('player_quest_guild_npc_item', array(
					'falha'	=> '1'
				), array(
					'id_quest_guild'	=> $player['id_missao_guild'],
					'id_player'			=> $player['id']
				));
				
				Recordset::update('player', array(
					'id_missao_guild'	=> 0
				), array(
					'id'				=> $player['id']
				));
				
				// Remove os itens do player --->
					$quest_items	= Recordset::query('
						SELECT 
							a.*,
							b.nome_' . Locale::get() . ' AS npc_nome,
							c.nome_' . Locale::get() . ' AS item_nome 
						
						FROM 
							quest_guild_npc_item a LEFT JOIN npc b ON b.id=a.id_npc
							LEFT JOIN item c ON c.id=a.id_item
						
						WHERE 
							a.id_quest_guild=' . $player['id_missao_guild'], true);
					
					foreach($quest_items->result_array() as $quest_item) {
						if($quest_item['id_item']) {
							$item = Recordset::query('SELECT id_tipo FROM item WHERE id=' . $quest_item['id_item'])->row_array();
							
							if($item['id_tipo'] != 27) {
								continue;
							}
				
							Recordset::delete('player_item', array(
								'id_player'	=> $player['id'],
								'id_item'	=> $quest_item['id_item']
							));
						}			
					}			
				// <---
			}
		}
	// <---
	
	// Grupo --->
		$guilds	= Recordset::query('
			SELECT
				id,
				id_quest_guild
			
			FROM
				guild
			
			WHERE
				id_quest_guild !=0
		');
		
		foreach($guilds->result_array() as $guild) {
			$item	= Recordset::query('
				SELECT
					conclusao,
					falha
				
				FROM 
					guild_quest_npc_item 
				
				WHERE 
					id_quest_guild=' . $guild['id_quest_guild'] . ' AND
					id_guild=' . $guild['id'])->row_array();

			if($item['falha']) { // Nâo é pra acontecer.. maaaaaaaaaaaaaaas
				continue;	
			}

			if(strtotime('+0 minute') >= strtotime($item['conclusao'])) {
				echo "+ ENDING {$guild['id']}\n";

				Recordset::update('guild_quest_npc_item', array(
					'falha'	=> '1'
				), array(
					'id_quest_guild'	=> $guild['id_quest_guild'],
					'id_guild'			=> $guild['id']
				));
				
				Recordset::update('guild', array(
					'id_quest_guild'	=> 0
				), array(
					'id'				=> $guild['id']
				));
			}
		}
	// <---
	
	