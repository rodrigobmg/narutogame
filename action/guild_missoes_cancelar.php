<?php
	if($basePlayer->getAttribute('id_missao_guild2') && isset($_POST['guild']) && $_POST['guild']) {
		
		if(!$basePlayer->getAttribute('dono_guild')) {
			redirect_to('negado', NULL, array('e' => 1));
		}

		Recordset::update('guild_quest_npc_item', array(
			'falha'				=> '1'
		), array(
			'id_guild'			=> $basePlayer->getAttribute('id_guild'),
			'id_quest_guild'	=> $basePlayer->getAttribute('id_missao_guild2')
		));
	
		Recordset::update('guild', array(
			'id_quest_guild'	=> 0
		), array(
			'id'				=> $basePlayer->getAttribute('id_guild')
		));
		
		redirect_to('personagem_status');		
	} else {
		if(!$basePlayer->getAttribute('id_missao_guild')) {
			redirect_to('negado', NULL, array('e' => 2));
		}

		// Removção dos itens dos players --->
			$quest			= Recordset::query('SELECT * FROM quest_guild WHERE id=' . $basePlayer->getAttribute('id_missao_guild'), true)->row_array();
			$quest_items	= Recordset::query('
				SELECT 
					a.*,
					b.nome_' . Locale::get() . ' AS npc_nome,
					c.nome_' . Locale::get() . ' AS item_nome 
				
				FROM 
					quest_guild_npc_item a LEFT JOIN npc b ON b.id=a.id_npc
					LEFT JOIN item c ON c.id=a.id_item
				
				WHERE 
					a.id_quest_guild=' . $quest['id'], true);
			
			if($quest['tipo'] == 'solo') {
				foreach($quest_items->result_array() as $quest_item) {
					if($quest_item['id_item']) {
						$item = Recordset::query('SELECT id_tipo FROM item WHERE id=' . $quest_item['id_item'])->row_array();
						
						if($item['id_tipo'] != 27) {
							continue;
						}
			
						Recordset::delete('player_item', array(
							'id_player'	=> $basePlayer->id,
							'id_item'	=> $quest_item['id_item']
						));
					}			
				}			
			}
		// <---
		
		Recordset::update('player_quest_guild_npc_item', array(
			'falha'				=> '1'
		), array(
			'id_player'			=> $basePlayer->id,
			'id_quest_guild'	=> $basePlayer->getAttribute('id_missao_guild')
		));
	
		$basePlayer->setAttribute('id_missao_guild', 0);
		
		redirect_to('personagem_status');		
	}
