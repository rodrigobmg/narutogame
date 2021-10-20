<?php
	header("Content-Type: application/json");

	$json			= new stdClass();
	$json->info		= array();
	$x				= (int)$_POST['x'];
	$y				= (int)$_POST['y'];
	$npc_equipe 	= false;
	$npc_diaria		= false;
	$npc_diaria2	= false;
	$can_move		= true;
	$curpos			= Recordset::query("SELECT xpos, ypos FROM player_posicao WHERE id_player=" . $basePlayer->id)->row_array();
 
	//	($curpos['xpos'] + $x < 0) || ($curpos['ypos'] + $y < 0) ||
	//	($curpos['xpos'] + $x > 131) || ($curpos['ypos'] + $y > 98)

	if(	abs($x) > 5 || abs($y) > 5) {
		
		$json->not_moved	= true;
		$can_move			= false;
	}

	
	if($can_move) {
		Recordset::update('player_posicao', array(
			'xpos'		=> array('escape' => false, 'value' => 'xpos + ' .  (int)$x),
			'ypos'		=> array('escape' => false, 'value' => 'ypos + ' .  (int)$y)
		), array(
			'id_player'	=> $basePlayer->id
		));

		$x			= ($curpos['xpos'] + (int)$x) * 22;
		$y			= ($curpos['ypos'] + (int)$y) * 22;
		
		$vilas		= Recordset::query('SELECT id, xpos, ypos FROM vila', true);
		$is_vila	= false;
		
		foreach($vilas->result_array() as $vila) {
			if($vila['xpos'] == $x && $vila['ypos'] == $y) {
				$is_vila = true;
				
				break;
			}
		}
		
		if($is_vila) {
			$local	= Recordset::query('SELECT x, y FROM local_mapa WHERE mlocal=5 AND id_vila=' . $vila['id'])->row_array();
			$xinc	= has_chance(50) ? 1 : -1;
			$yinc	= has_chance(50) ? 1 : -1;
			
			$hp = false;
			$sp = false;
			$sta = false;

			if($basePlayer->hp < ($basePlayer->max_hp / 2)) {
				$hp = true;
			}
			if($basePlayer->sta < ($basePlayer->max_sta / 2)) {
				$sta = true;
			}
			if($basePlayer->sp < ($basePlayer->max_sp / 2)) {
				$sp = true;
			}
			
			if($hp || $sta || $sp){
				Recordset::update('player', array(
					'less_hp'	=> $hp ? ($basePlayer->max_hp / 2)-5 : $basePlayer->less_hp,
					'less_sta'	=> $sta ? ($basePlayer->max_sta / 2)-5 : $basePlayer->less_sta,
					'less_sp'	=> $sp ? ($basePlayer->max_sp / 2)-5 : $basePlayer->less_sp 
				), array(
					'id'	=> $basePlayer->id
				));
			}

			Recordset::update("player", array(
				'id_vila_atual' => $vila['id']
			), array(
				"id"			=> $basePlayer->id
			));
			
			Recordset::update('player_posicao', array(
				'xpos'			=> $local['x'] + $xinc,
				'ypos'			=> $local['y'] + $yinc	
			), array(
				'id_player'		=> $basePlayer->id
			));
			
			$json->redirect	= '?secao=mapa_vila';
		} else {
			$json->info[]	= "ENTRY COND";
			$npc			= false;
		
			if($basePlayer->id_missao && $basePlayer->missao_equipe) {
				$json->info[]	= "OPT0";
				$npc 			= Recordset::query("SELECT id_npc FROM equipe_quest_npc WHERE id_player=" . $basePlayer->id . 
								   " AND id_player_quest=" . (int)$basePlayer->id_missao . " AND id_equipe=" . $basePlayer->id_equipe .
								   " AND (qtd < 1 OR qtd IS NULL)")->row_array();
				
				if(isset($npc['id_npc']) && $npc['id_npc']) {
					$npc		= NPC::getNPCFromPerimeterE($x, $y, $npc['id_npc']);
					$npc_equipe = $npc ? true : false;
				} else {
					$npc		= false;
				}
				
			} elseif($basePlayer->id_missao && !$basePlayer->missao_equipe) {
				$json->info[]	= "OPT1";
				$npc			= NPC::getNPCFromPerimeter($x, $y, $basePlayer->level, $basePlayer->id_missao);
			}
			
			if(!$npc) {
				$json->info[]	= "OPT2";
				$npc			= NPC::getNPCFromPerimeter($x, $y, $basePlayer->level);
			}
			
			if($basePlayer->id_missao_guild && !$npc) {
				$npc		= NPC::getNPCFromPerimeter($x, $y, $basePlayer->level, NULL, $basePlayer->id_missao_guild);
				$npc_diaria	= $npc ? true : false;
			}

			if($basePlayer->id_missao_guild2 && !$npc) {
				$npc			= NPC::getNPCFromPerimeter($x, $y, $basePlayer->level, NULL, $basePlayer->id_missao_guild2);
				$npc_diaria2	= $npc ? true : false;
			}
			
			// Verifica se o npc de uma vila especifica --->
				if($npc) {
					$json->info[]	= "HAS NPC";
					$npc_data		= Recordset::query('SELECT id_vila FROM npc WHERE id=' . $npc, true)->row_array();
					
					if($npc_data['id_vila'] && $npc_data['id_vila'] != $basePlayer->id_vila_atual) {
						$npc	= false;
					}
				}
			// <---
							
			if($npc && !$basePlayer->id_batalha) { // Existe NPcs no perimetro e não to em batalha ?
				$json->info[]	= "WILL CREATE";
				// Limpeza das variaveis --->
					Fight::cleanup();
				// <---
				
				// Inicia uma batalha contra NPC -->
          $player_instance = new Player($basePlayer->id);

          if ($npc_equipe) {
            $npc = new NPC($npc, $player_instance, NPC_EQUIPE);
          } elseif($npc_diaria) {
            $npc = new NPC($npc, $player_instance, NPC_DIARIA);
          } elseif($npc_diaria2) {
            $npc = new NPC($npc, $player_instance, NPC_DIARIA2);
          } else {
            $npc = new NPC($npc, $player_instance, NPC_INTERATIVA);
          }

					$batalha	= Recordset::insert('batalha', array(
						'id_tipo'		=> 3,
						'id_player'		=> $basePlayer->id,
						'current_atk'	=> 1,
						'data_atk'		=> array('escape' => false, 'value' => 'NOW()')
					));
					
					$npc->batalha_id	= $batalha;
					$npc->parseModifiers();
					$npc->atCalc();
					
					SharedStore::S('_BATALHA_' . $basePlayer->id, serialize($npc));
					
					Recordset::update("player", array(
						"id_batalha" => $batalha
					), array(
						"id" => $basePlayer->id
					));
					
					$json->redirect	= '?secao=dojo_batalha_lutador';
				// <---
			}
			
			if (!$basePlayer->id_batalha) {
				$_SESSION['_random_npc_map']		= false;
				$_SESSION['_random_npc_map_ins']	= false;
				$_SESSION['_random_npc_map_msg']	= false;
				
				$total_npcs	= Recordset::query('SELECT total FROM player_batalhas_npc_mapa WHERE id_player=' . $basePlayer->id);
				
				if(!$total_npcs->num_rows) {
					Recordset::insert('player_batalhas_npc_mapa', array(
						'id_player'	=> $basePlayer->id
					));
					
					$total_npcs->repeat();
				}
				if(!$basePlayer->id_missao AND !$basePlayer->id_missao_guild){
					if(has_chance($_SESSION['universal'] ? 100 : 10)) { // Optimization, or something else =)
						$basePlayer	= new Player($basePlayer->id);

						if($total_npcs->row()->total < (10 + $basePlayer->bonus_vila['dojo_limite_npc_mapa'])) {
							$_SESSION['_random_npc_map']		= true;
							$_SESSION['_random_npc_map_msg']	= true;
						}
					}
				}
			}
		}	
	}

	echo json_encode($json);
