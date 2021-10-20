<?php
	$redir_script = true;

	if($basePlayer->id_missao == -1) {
		$basePlayer->setAttribute('id_missao', 0);
	} else {
		if(isset($_POST['especial']) && $basePlayer->getAttribute('id_missao_especial')) {
			$quest = Recordset::query('SELECT * FROM quest WHERE id=' . $basePlayer->getAttribute('id_missao_especial'), true)->row_array();	
		} elseif($basePlayer->getAttribute('id_missao')) {
			$quest = Recordset::query('SELECT * FROM quest WHERE id=' . $basePlayer->getAttribute('id_missao'), true)->row_array();
		} else {
			redirect_to('negado', NULL, array('e' => 1));
		}
		
		if($quest['equipe'] && !$basePlayer->getAttribute('dono_equipe') && !$basePlayer->getAttribute('sub_equipe')) {
			redirect_to('negado', NULL, array('e' => 2));
		}
		
		if($quest['equipe']) {
			$ids		= array();
			$players	= Recordset::query('SELECT id FROM player WHERE id_equipe=' . $basePlayer->getAttribute('id_equipe'));
			
			foreach($players->result_array() as $player) {
				$ids[] = $player['id'];
			}

			Recordset::update('player_quest', array(
				'falha'	=> '1'
			), array(
				'id_player'	=> array('escape' => false, 'value' => join(',', $ids), 'mode' => 'in'),
				'id_quest'	=> $basePlayer->getAttribute('id_missao'),
				'id_equipe'	=> $basePlayer->id_equipe
			));

			// remove os registros pra não vir auto concluido
			Recordset::delete('equipe_quest_npc', array(
				'id_equipe'			=> $basePlayer->id_equipe,
				'id_player_quest'	=> $basePlayer->id_missao
			));
			
			Recordset::update('player', array(
				'id_missao'	=> '0'
			), array(
				'id'		=> array('escape' => false, 'value' => join(',', $ids), 'mode' => 'in')
			));		
		} else {
			if($quest['interativa']) {		
				if(!Recordset::query('SELECT id FROM player_quest_status WHERE id_player=' . $basePlayer->id)->num_rows) {
					Roecrdset::insert('player_quest_status', array(
						'id_player'	=> $basePlayer->id
					));
				}
				
				if(!$quest['especial']) {
					switch($quest['id_rank']) {
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
						'id_player'	=> $basePlayer->id
					));
				}
				
				Recordset::update('player_quest', array(
					'falha' 	=> '1'
				), array(
					'id_quest'	=> $quest['id'],
					'id_player'	=> $basePlayer->id
				));
			} else {
				Recordset::delete('player_quest', array(
					'id_quest'	=> $quest['id'],
					'id_player'	=> $basePlayer->id
				));
			}
			
			if($quest['especial']) {
				$basePlayer->setAttribute('id_missao_especial', 0);		
			} else {
				$basePlayer->setAttribute('id_missao', 0);
			}
		}

		// Se a missão for de drop, remove os itens --->
		if($quest['interativa']) {
			$quest_items = Recordset::query('SELECT * FROM quest_npc_item WHERE id_quest=' . $quest['id'], true);
			
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
	}

	echo "location.href = '?secao=personagem_status'";
