<?php
	$torneio_player	= Recordset::query('SELECT * FROM torneio_player WHERE participando=\'1\' AND id_player=' . $basePlayer->id)->row_array();
	$torneio		= Recordset::query('SELECT * FROM torneio WHERE id=' . $basePlayer->getAttribute('id_torneio'), true)->row_array();

	Fight::cleanup();
	
	if($basePlayer->missao_comum) {
		redirect_to('negado');	
	}
	
	if($torneio['npc']) { // NPC
		// Criar o npc --->
			$npc = 	new NPC(NULL, $basePlayer, NPC_TORNEIO, $torneio['npc_dificuldade']);
			$npc->clearModifiers();
		// <---

		$_SESSION['_MOD_BASE']	= 'B';

		switch($torneio['npc_dificuldade']) {
			case 'hard':
				$npc->setLocalAttribute('tai_raw', $npc->getAttribute('tai_raw') + percent(0, $npc->getAttribute('tai_raw')));
				$npc->setLocalAttribute('ken_raw', $npc->getAttribute('ken_raw') + percent(0, $npc->getAttribute('ken_raw')));
				$npc->setLocalAttribute('nin_raw', $npc->getAttribute('nin_raw') + percent(0, $npc->getAttribute('nin_raw')));
				$npc->setLocalAttribute('gen_raw', $npc->getAttribute('gen_raw') + percent(0, $npc->getAttribute('gen_raw')));
			
				$npc->setLocalAttribute('agi_raw', $npc->getAttribute('agi_raw') + percent(0, $npc->getAttribute('agi_raw')));
				$npc->setLocalAttribute('con_raw', $npc->getAttribute('con_raw') + percent(0, $npc->getAttribute('con_raw')));

				$npc->setLocalAttribute('ene_raw', $npc->getAttribute('ene_raw') + percent(0, $npc->getAttribute('ene_raw')));
				$npc->setLocalAttribute('res_raw', $npc->getAttribute('res_raw') + percent(0, $npc->getAttribute('res_raw')));

				$npc->setLocalAttribute('int_raw', $npc->getAttribute('int_raw') + percent(0, $npc->getAttribute('int_raw')));
				$npc->setLocalAttribute('for_raw', $npc->getAttribute('for_raw') + percent(0, $npc->getAttribute('for_raw')));
			
				break;
			
			case 'ogro':
				$porta	= false;
				$selos	= false;
				$invoc	= false;
				$invos	= false;
				$sennin	= false;
				
				$rand	= rand(0, 2);
			
				// Modificadores para os npcs ogros --->
					switch($basePlayer->id_graduacao) {
						case 1:
						case 2: // Genin
							$max_cla		= 1;
							$max_portao		= 1;
							$max_invocacao	= 1;
							
							$rand			= rand(0, 1);
						
							break;
						
						case 3: // Chunin
							$max_cla		= 2;
							$max_selo		= 1;
							$max_portao		= 3;
							$max_invocacao	= 2;
						
							break;
						
						case 4: // Jounin
							$max_cla		= 3;
							$max_selo		= 1;
							$max_portao		= 4;
							$max_invocacao	= 3;
						
							break;
						
						case 5: // ANBU
							$max_cla		= 4;
							$max_selo		= 2;
							$max_portao		= 6;
							$max_invocacao	= 4;
						
							break;
							
						case 6: // Sannin
						case 7: // Kage
							$max_cla		= 5;
							$max_selo		= 3;
							$max_portao		= 8;
							$max_invocacao	= 5;
						
							break;
					}
					
					if(isset($max_portao)) {
						$porta	= Recordset::query('SELECT id, id_cla, id_invocacao, id_selo FROM item WHERE id_tipo=17 AND ordem <=' . $max_portao . ' ORDER BY ordem DESC', true)->result_array();
					}
					
					if(isset($max_selo)) {
						$selos	= Recordset::query('SELECT id, id_cla, id_invocacao, id_selo FROM item WHERE id_tipo=20 AND ordem <=' . $max_selo . ' ORDER BY ordem DESC', true)->result_array();
					}
					
					if($max_invocacao) {
						$invoc	= Recordset::query('SELECT id, id_cla, id_invocacao, id_selo FROM item WHERE id_tipo=21 AND ordem <=' . $max_invocacao . ' ORDER BY ordem DESC', true)->result_array();
						$invos	= Recordset::query('SELECT id, id_cla, id_invocacao, id_selo FROM item WHERE id_tipo=21 AND id_invocacao=1', true)->result_array();
					}
					
					if($max_cla) {
						$clas	= Recordset::query('SELECT id, id_cla, id_invocacao, id_selo FROM item WHERE id_tipo=16 AND ordem <=' . $max_cla . ' ORDER BY ordem DESC', true)->result_array();
					}
				
					// Sennin --->
						$sennin	= Recordset::query('SELECT id FROM item WHERE id_tipo=21 AND id_invocacao=1 LIMIT 1', true)->result_array();
					// <---	
				// <---

				$npc->setLocalAttribute('tai_raw', $npc->getAttribute('tai_raw') + percent(0, $npc->getAttribute('tai_raw')));
				$npc->setLocalAttribute('ken_raw', $npc->getAttribute('ken_raw') + percent(0, $npc->getAttribute('ken_raw')));
				$npc->setLocalAttribute('nin_raw', $npc->getAttribute('nin_raw') + percent(0, $npc->getAttribute('nin_raw')));
				$npc->setLocalAttribute('gen_raw', $npc->getAttribute('gen_raw') + percent(0, $npc->getAttribute('gen_raw')));
			
				$npc->setLocalAttribute('agi_raw', $npc->getAttribute('agi_raw') + percent(0, $npc->getAttribute('agi_raw')));
				$npc->setLocalAttribute('con_raw', $npc->getAttribute('con_raw') + percent(0, $npc->getAttribute('con_raw')));

				$npc->setLocalAttribute('ene_raw', $npc->getAttribute('ene_raw') + percent(0, $npc->getAttribute('ene_raw')));
				$npc->setLocalAttribute('res_raw', $npc->getAttribute('res_raw') + percent(0, $npc->getAttribute('res_raw')));

				$npc->setLocalAttribute('int_raw', $npc->getAttribute('int_raw') + percent(0, $npc->getAttribute('int_raw')));
				$npc->setLocalAttribute('for_raw', $npc->getAttribute('for_raw') + percent(0, $npc->getAttribute('for_raw')));
				
				$source = NULL;

				switch($rand) {
					case 0: // NPC com portão + invocacao + selo
						
						if($porta) {
							$porta	= $porta[rand(0, sizeof($porta) - 1)];
							
							$npc->addModifier($porta['id'], 1, 0, $source, 0);
							$npc->setLocalAttribute('portao', true);
							$npc->setLocalAttribute('id_portao_atual', $porta['id']);

							$npc->portao			= true;
							$npc->id_portao_atual	= $porta['id'];
						}

						if($invoc) {
							$invoc	= $invoc[rand(0, sizeof($invoc) - 1)];
	
							$npc->addModifier($invoc['id'], 1, 0, $source, 0);
							$npc->setLocalAttribute('id_invocacao', $invoc['id_invocacao']);

							$npc->id_invocacao			= $invoc['id_invocacao'];
							$npc->id_invocacao_atual	= $invoc['id'];
						}
												
						break;
					
					case 1: // NPC com cla + invocação + selo						
						if($invoc) {
							$invoc	= $invoc[rand(0, sizeof($invoc) - 1)];
	
							$npc->addModifier($invoc['id'], 1, 0, $source, 0);
							$npc->setLocalAttribute('id_invocacao', $invoc['id_invocacao']);

							$npc->id_invocacao			= $invoc['id_invocacao'];
							$npc->id_invocacao_atual	= $invoc['id'];
						}
						
						if($clas) {
							$clas	= $clas[rand( 0, sizeof($clas)  - 1)];
	
							$npc->addModifier($clas['id'], 1, 0, $source, 0);
							$npc->setLocalAttribute('id_cla', $clas['id_cla']);
							$npc->setLocalAttribute('id_cla_atual', $clas['id']);

							$npc->id_cla		= $clas['id_cla'];
							$npc->id_cla_atual	= $clas['id'];
						}
						
						if($selos) {
							$selos	= $selos[rand(0, sizeof($selos) - 1)];
	
							$npc->addModifier($selos['id'], 1, 0, $source, 0);
							$npc->setLocalAttribute('id_selo', $selos['id_selo']);

							$npc->id_selo		= $selos['id_selo'];
							$npc->id_selo_atual	= $selos['id'];
						}					

						break;
					
					case 2: // NPC Sennin
						if($invos) {
							$invos	= $invos[rand(0, sizeof($invos) - 1)];
	
							$npc->addModifier($invos['id'], 1, 0, $source, 0);
							$npc->setLocalAttribute('id_invocacao', $invos['id_invocacao']);

							$npc->id_invocacao			= $invos['id_invocacao'];
							$npc->id_invocacao_atual	= $invos['id'];
						}

						if($clas) {
							$clas	= $clas[rand( 0, sizeof($clas)  - 1)];
	
							$npc->addModifier($clas['id'], 1, 0, $source, 0);
							$npc->setLocalAttribute('id_cla', $clas['id_cla']);
							$npc->setLocalAttribute('id_cla_atual', $clas['id']);

							$npc->id_cla		= $clas['id_cla'];
							$npc->id_cla_atual	= $clas['id'];
						}
						
						if($sennin) {
							$sennin	= $sennin[rand( 0, sizeof($sennin)  - 1)];
	
							$npc->addModifier($sennin['id'], 1, 0, $source, 0);
							$npc->setLocalAttribute('sennin', true);

							$npc->sennin	= true;
						}

						break;
				}
			
				break;
		}
		
		$npc->parseModifiers();
		$npc->atCalc();
		
		$batalha = Recordset::insert('batalha', array(
			'id_tipo'		=> 1,
			'id_player'		=> $basePlayer->id,
			'current_atk'	=> 1,
			'data_atk'		=> date('Y-m-d H:i:s')
		));

		$basePlayer->setAttribute('id_batalha', $batalha);
		$npc->id_batalha	= $batalha;
		
		SharedStore::S('_BATALHA_' . $basePlayer->id, serialize($npc));
		
		Recordset::insert('torneio_espera', array(
			'id_player'		=> $basePlayer->id,
			'id_player_b'	=> 1,
			'id_batalha'	=> $batalha,
			'id_torneio'	=> $basePlayer->getAttribute('id_torneio'),
			'chave'			=> $torneio_player['chave'],
			'npc'			=> 1
		));

		redirect_to('dojo_batalha_lutador');
	} else { // PVP
		$disponiveis 	= Recordset::query('
			SELECT 
				* 
			FROM 
				torneio_espera 
			WHERE 
				id_torneio=' . $basePlayer->getAttribute('id_torneio') . ' AND
				chave=' . $torneio_player['chave'] . ' AND
				id_player_b=0
		');
		
		if(!$disponiveis->num_rows) { // Sala de espera
			Recordset::insert('torneio_espera', array(
				'id_player'		=> $basePlayer->id,
				'id_torneio'	=> $basePlayer->getAttribute('id_torneio'),
				'chave'			=> $torneio_player['chave']
			));
			
			redirect_to('torneio_espera');
		} else { // batalha
			// verifica eu x eu, remove registro fdp e poe o maluco na espera --->
				if($disponiveis->row()->id_player == $basePlayer->id) {
					Recordset::delete('torneio_espera', array(
						'id_player'		=> $basePlayer->id,
						'id_torneio'	=> $basePlayer->getAttribute('id_torneio'),
						'id_player_b'	=> 0
					));
					
					Recordset::insert('torneio_espera', array(
						'id_player'		=> $basePlayer->id,
						'id_torneio'	=> $basePlayer->getAttribute('id_torneio'),
						'chave'			=> $torneio_player['chave']
					));
					
					redirect_to('torneio_espera');
					die();
				}
			// <---
		
			$espera = $disponiveis->row_array();
			
			$pp = new Player($espera['id_player']);
			
			Fight::cleanup($pp->id);
			
			$bID = Recordset::insert('batalha', array(
				'id_tipo'		=> 2,
				'id_player'		=> $espera['id_player'],
				'id_playerb'	=> $basePlayer->id,
				'current_atk'	=> $espera['id_player'],
				'enemy'			=> $basePlayer->id,
				'data_atk'		=> date('Y-m-d H:i:s')
			));

			/*
				'hp_a'			=> $pp->getAttribute('hp'),
				'sp_a'			=> $pp->getAttribute('sp'),
				'sta_a'			=> $pp->getAttribute('sta'),
				'hp_b'			=> $basePlayer->getAttribute('hp'),
				'sp_b'			=> $basePlayer->getAttribute('sp'),
				'sta_b'			=> $basePlayer->getAttribute('sta')
			*/
	
			Recordset::update('torneio_espera', array(
				'id_player_b'	=> $basePlayer->id,
				'id_batalha'	=> $bID
			), array(
				'id_torneio'	=> $espera['id_torneio'],
				'id_player'		=> $espera['id_player'],
				'chave'			=> $torneio_player['chave']
			));
			
			Recordset::update('player', array(
				'id_batalha'	=> $bID
			), array(
				'id'		=> $basePlayer->id
			));
	
			Recordset::update('player', array(
				'id_batalha'	=> $bID
			), array(
				'id'		=> $espera['id_player']
			));
	
			// Registro de batalha --->
				Recordset::insert('batalha_log_acao', array(
					'id_player'		=> $basePlayer->id,
					'id_playerb'	=> $espera['id_player'],
					'id_batalha'	=> $bID
				));
	
				Recordset::insert('batalha_log_acao', array(
					'id_player'		=> $basePlayer->id,
					'id_playerb'	=> $espera['id_player'],
					'id_batalha'	=> $bID
				));			
			// <---
	
			redirect_to('dojo_batalha_pvp');
		}	
	}
