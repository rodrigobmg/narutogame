<?php
	define("NG_ARCH_SELF", 0);
	
	define("NG_ARCH_PVP_DOJO", 1);
	define("NG_ARCH_PVP_VILA", 2);
	define("NG_ARCH_PVP_MAPA", 3);

	define("NG_ARCH_NPC_DOJO", 10);
	define("NG_ARCH_NPC_VILA", 11);
	define("NG_ARCH_NPC_MAPA", 12);
	define("NG_ARCH_NPC_EQUI", 13);
	define("NG_ARCH_NPC_EVENTO", 14);
	define("NG_ARCH_NPC_EVENTO4", 15);
	define("NG_ARCH_NPC_EVENTO_ESPECIAL", 16);
	define("NG_ARCH_NPC_MODO_H", 17);
	define("NG_ARCH_NPC_SENSEI", 18);

	define("NG_ARCH_ITEM_N", 20);
	define("NG_ARCH_ITEM_V", 21);
	define("NG_ARCH_ITEM_S", 22);
	define("NG_ARCH_ITEM_SI", 23);
	
	define("NG_ARCH_WALK", 30);
	
	define("NG_ARCH_QUEST", 40);

	define("NG_ARCH_TORNEIO", 50);
	define('NG_ARCH_ARENA', 60);

	define('NG_ARCH_4XPVP', 70);

	define('NG_ARCH_EVENTO_VILA', 80);

	define('NG_ARCH_TEMA_VIP', 90);

	define("NG_ARCH_EXAME", 100);

	define("NG_ARCH_GUERRA", 120);
	define("NG_ARCH_PROFISSAO", 140);
	define("NG_ARCH_PACOTES", 200);
	
	function arch_parse($arch_type, $playerData = NULL, $enemyData = NULL, $itemData = NULL, $qtd = 1) {
		if (!$_SESSION['universal']) {
			//return;
		}

		global $basePlayer;
		
		$ok			= false;
		//$items		= arch_build_items(is_numeric($playerData) ? $playerData : $playerData->id);
		
		switch($arch_type) {
			case NG_ARCH_TORNEIO:
				$ok = false;
				
				$conquistas	= Recordset::query('SELECT * FROM conquista WHERE is_torneio=\'1\' AND req_torneio=' . $itemData, true);
				
				foreach($conquistas->result_array() as $conquista) {
					$item = Recordset::query('SELECT * FROM conquista_item WHERE completa=0 AND id_player=' . $playerData . ' AND id_conquista=' . $conquista['id']);
					
					// Não tem registro
					if(!$item->num_rows) {
						Recordset::insert('conquista_item', array(
							'id_player'		=> $playerData,
							'id_conquista'	=> $conquista['id'],
							'qtd'			=> $qtd
						));
						
						$item->repeat();
					}
					
					// Completou ? próximo
					if(!$item->row()->completa) {
						continue;
					} else {
						if($item->row()->qtd + $qtd >= $conquista['req_qtd']) {
							$completa = 1;
						}
						
						Recordset::update('conquista_item', array(
							'qtd'		=> array('value' => 'qtd+1', 'escape' => false),
							'completa'	=> $completa
						), array(
							'id_player'		=> $playerData->id,
							'id_conquista'	=> $conquista['id'],
						));
					
					}
				}
				
				
				break;
			case NG_ARCH_QUEST:
				$conquistas	= Recordset::query('SELECT * FROM conquista WHERE is_quest != 0', true);
				
				foreach($conquistas->result_array() as $conquista) {
					 if(Recordset::query("SELECT id_conquista FROM conquista_item WHERE completa=1 AND id_conquista=" . $conquista['id'] . " AND id_player=" . $playerData->id)->num_rows) {
						 continue;
					 }

					switch($conquista['is_quest']) {
						case 1: // id unico
							$quest	= Recordset::query("SELECT id_quest, 1 AS total FROM player_quest WHERE id_quest=" . $conquista['req_id_quest'] . " AND id_player=" . $playerData->id);
							break;

						case 2:
							$quest	= Recordset::query("SELECT quest_d AS total FROM player_quest_status WHERE id_player=" . $playerData->id);
						
							break;

						case 3:
							$quest	= Recordset::query("SELECT quest_c AS total FROM player_quest_status WHERE id_player=" . $playerData->id);						
						
							break;

						case 4:
							$quest	= Recordset::query("SELECT quest_b AS total FROM player_quest_status WHERE id_player=" . $playerData->id);
						
							break;

						case 5:
							$quest	= Recordset::query("SELECT quest_a AS total FROM player_quest_status WHERE id_player=" . $playerData->id);
						
							break;

						case 6:
							$quest	= Recordset::query("SELECT quest_s AS total FROM player_quest_status WHERE id_player=" . $playerData->id);
						
							break;
						case 7:
							$quest	= Recordset::query("SELECT quest_combate_diario AS total FROM player_quest_status WHERE id_player=" . $playerData->id);
						
							break;
						case 8:
							$quest	= Recordset::query("SELECT quest_combate_semanal AS total FROM player_quest_status WHERE id_player=" . $playerData->id);
						
							break;		
						case 9:
							$quest	= Recordset::query("SELECT quest_combate_mensal AS total FROM player_quest_status WHERE id_player=" . $playerData->id);
							break;	
					}

					if($quest->num_rows) {
						arch_set($conquista['id'], $quest->row()->total, true);
					}
				}

				break;
			
			case NG_ARCH_ITEM_N:
			case NG_ARCH_ITEM_V:
			case NG_ARCH_ITEM_S:
			case NG_ARCH_ITEM_SI:
			
				switch($arch_type) {
					case NG_ARCH_ITEM_N:
						$item_type = 1;
						break;
					
					case NG_ARCH_ITEM_S:
						$item_type = 2;
						break;
						
					case NG_ARCH_ITEM_V:				
						$item_type = 3;
						break;

					case NG_ARCH_ITEM_SI:				
						$item_type = 4;
						break;
				}

				$qCon = Recordset::query("SELECT * FROM conquista WHERE is_item = " . $item_type . " AND id NOT IN(SELECT id_conquista FROM conquista_item WHERE completa=1 AND id_player=" . $playerData->id . ")");
				
				foreach($qCon->result_array() as $rCon) {
					$ok = true;
					
					if($itemData->id != $rCon['req_id_item']) {
						$ok = false;
					}
					
					// Pegar um item estando em uma determinada vila
					if($rCon['req_id_vila']) {
						if($playerData->id_vila_atual != $rCon['req_id_vila']) {
							$ok = false;
						}
					}

					if(isset($rCon['req_id_vila2']) && $rCon['req_id_vila2']) {
						if($playerData->id_vila != $rCon['req_id_vila2']) {
							$ok = false;
						}
					}

					if(isset($rCon['data_inicio']) && $rCon['data_inicio'] && !between(now(), strtotime($rCon['data_inicio']), strtotime($rCon['data_fim']))) {
						$ok	= false;
					}
					
					if($ok) {
						arch_set($rCon['id'], $qtd);
					}
				}
				
				break;
			
			case NG_ARCH_WALK:
				$qCon = Recordset::query("SELECT * FROM conquista WHERE is_walk != 0 AND id NOT IN(SELECT id_conquista FROM conquista_item WHERE completa=1 AND id_player=" . $playerData->id . ")");
				
				foreach($qCon->result_array() as $rCon) {
					$ok = false;
					
					if($playerData->id_vila_atual == $rCon['req_id_vila']) {
						$ok = true;
					}
					
					if($ok) {
						arch_set($rCon['id'], 1);
					}
				}

				break;
			
			case NG_ARCH_NPC_DOJO:
			case NG_ARCH_NPC_VILA:
			case NG_ARCH_NPC_MAPA:
			case NG_ARCH_NPC_EQUI:
			case NG_ARCH_NPC_EVENTO:
			case NG_ARCH_NPC_EVENTO4:
			case NG_ARCH_NPC_EVENTO_ESPECIAL:
			case NG_ARCH_NPC_MODO_H:
			case NG_ARCH_NPC_SENSEI:
				
				switch($arch_type) {
					case NG_ARCH_NPC_DOJO:
						$npc_type	= 1;
						break;
					
					case NG_ARCH_NPC_EQUI:
						$npc_type	= 2;
						break;
						
					case NG_ARCH_NPC_VILA:
						$npc_type	= 3;
						break;
					
					case NG_ARCH_NPC_MAPA:
						$npc_type	= 4;
						break;
					
					case NG_ARCH_NPC_EVENTO:
						$npc_type	= 5;
						break;

					case NG_ARCH_NPC_EVENTO4:
						$npc_type	= 6;
						break;
					
					case NG_ARCH_NPC_EVENTO_ESPECIAL:
						$npc_type	= 7;
						break;

					case NG_ARCH_NPC_MODO_H:
						$npc_type	= 8;
						break;
					case NG_ARCH_NPC_SENSEI:
						$npc_type	= 9;
						break;	
				}
			
				$qCon = Recordset::query("SELECT * FROM conquista WHERE is_npc = $npc_type", true);

				foreach($qCon->result_array() as $rCon) {
					 if(Recordset::query("SELECT id_conquista FROM conquista_item WHERE completa=1 AND id_conquista=" . $rCon['id'] . " AND id_player=" . $playerData->id)->num_rows) {
						 continue;
					 }					
					
					$ok = true;
					
					if($arch_type != NG_ARCH_NPC_DOJO && $arch_type != NG_ARCH_NPC_EVENTO4 && $arch_type != NG_ARCH_NPC_EVENTO_ESPECIAL && $arch_type != NG_ARCH_NPC_SENSEI) {
						if($enemyData->id != $rCon['req_id_npc']) {
							$ok = false;
						}
					}
					
					//Requer Sensei
					if($arch_type == NG_ARCH_NPC_SENSEI) {
						if($rCon['req_sensei']){
							if($playerData->id_sensei != $rCon['req_sensei']) {
								$ok = false;
							}	
						}else{
							
							// Verificar se é uma luta valida
							$q_desafio_sensei_atual = Recordset::query("select desafio from player_sensei_desafios where id_player= ". $playerData->id." ORDER BY desafio DESC LIMIT 1");
							$r_desafio_sensei_atual = $q_desafio_sensei_atual->row_array();

							$q_desafio_conquista_atual = Recordset::query("SELECT qtd FROM conquista_item WHERE id_conquista=" . $rCon['id'] . " AND id_player=" . $playerData->id);
							$r_desafio_conquista_atual = $q_desafio_conquista_atual->row_array();
		
							if($q_desafio_sensei_atual->num_rows && $q_desafio_conquista_atual->num_rows && $r_desafio_sensei_atual['desafio'] <= $r_desafio_conquista_atual['qtd']) {
								$ok = false;
							}
						}
					}
					
					if($arch_type == NG_ARCH_NPC_EVENTO4) {
						if($playerData->id_evento4 != $rCon['req_evento4']) {
							$ok = false;
						}
					}
					
					if($arch_type == NG_ARCH_NPC_EVENTO_ESPECIAL) {
						if($playerData->id_evento != $rCon['req_evento4']) {
							$ok = false;
						}						
					}
					
					if($rCon['req_id_classe']) {
						if($enemyData->id_classe != $rCon['req_id_classe']) {
							$ok = false;
						}
					}

					// Pegar um item estando em uma determinada vila
					if($rCon['req_id_vila']) {
						if($playerData->id_vila_atual != $rCon['req_id_vila']) {
							$ok = false;
						}
					}

					if(isset($rCon['data_inicio']) && $rCon['data_inicio'] && !between(now(), strtotime($rCon['data_inicio']), strtotime($rCon['data_fim']))) {
						$ok	= false;
					}

					if($ok) {
						arch_set($rCon['id'], 1, NULL, $playerData ? $playerData->id : NULL);
					}
				}			
			
				break;

			case NG_ARCH_SELF:
			case NG_ARCH_PVP_DOJO:
			case NG_ARCH_PVP_VILA:
			case NG_ARCH_PVP_MAPA:
				switch($arch_type) {
					case NG_ARCH_PVP_DOJO:
						$player_type = "1,4";
					
						break;
					case NG_ARCH_PVP_VILA:
						$player_type = "3,4";
					
						break;
					case NG_ARCH_PVP_MAPA:				
						$player_type = "2,4";
					
						break;
					
					default:
						$player_type = "4";
				}

				if($arch_type == NG_ARCH_SELF) {
					//echo "//CONDEQ\n";

					$conquistas = Recordset::query("SELECT * FROM conquista WHERE is_self = 1 AND id NOT IN(SELECT id_conquista FROM conquista_item WHERE completa=1 AND id_player=" . $playerData->id . ")");
					$base		= new Player($playerData->id);
				} else {
					$conquistas = Recordset::query("SELECT * FROM conquista FORCE INDEX(idx_is_player) WHERE is_player IN($player_type) AND id NOT IN(SELECT id_conquista FROM conquista_item WHERE completa=1 AND id_player=" . $playerData->id . ")");
					$base		= $enemyData;				
				}
				
				//echo "//CONDIN [TOTAL: {$conquistas->num_rows}]<br />\n";
								
				foreach($conquistas->result_array() as $conquista) {
					$ok = true;
					
					//echo '//CONDLOOP<br />' . PHP_EOL;
					if($conquista['req_bingo_book']) {
						$target	= Recordset::query('SELECT id FROM bingo_book WHERE morto="0" AND id_player=' . $playerData->id . ' AND id_player_alvo=' . $base->id);
						
						if(!$target->num_rows) {
							$ok	= false;
						}
					}

					if($conquista['req_bingo_book_guild']) {
						if(!$playerData->id_guild) {
							$ok	= false;
						} else {
							$target	= Recordset::query('SELECT id FROM bingo_book_guild WHERE morto="0" AND id_guild=' . $playerData->id_guild . ' AND id_player_alvo=' . $base->id);
							
							if(!$target->num_rows) {
								$ok	= false;
							}							
						}
					}
					

					if(isset($conquista['req_id_vila2']) && $conquista['req_id_vila2']) {
						if($base->id_vila != $conquista['req_id_vila2']) {
							$ok = false;
						}
					}
					
					//Requer Layout
					if(isset($conquista['req_layout']) && $conquista['req_layout']) {
						$target	= Recordset::query('SELECT layout FROM global.user WHERE id=' . $base->id_usuario)->row();
						$round = 'r'.$conquista['req_layout'];
						if($target->layout != $round) {
							$ok = false;
						}
					}
					
					//Requer Especialização de Equipe
					if(isset($conquista['req_id_especializacao']) && $conquista['req_id_especializacao']) {
						$target	= Recordset::query('SELECT equipe_role_'.($conquista['req_id_especializacao']-1).'_lvl as level FROM player_flags WHERE id_player=' . $base->id)->row();
						if($target->level < 5) {
							$ok = false;
						}
					}
					
					//Requer Tutorial
					
					if(isset($conquista['req_tutorial']) && $conquista['req_tutorial']) {
						$target	= Recordset::query('SELECT tutorial FROM player_flags WHERE id_player=' . $base->id)->row();
						if($target->tutorial != $conquista['req_tutorial']) {
							$ok = false;
						}
					}
					
					// Requer vila
					if($conquista['req_id_vila']) {
						if($base->id_vila_atual != $conquista['req_id_vila']) {
							//echo "xxxxxxxxxxxxx1";
							
							$ok = false;
						}
					}

					// Graduação
					if($conquista['req_id_graduacao']) {
						if($base->id_graduacao < $conquista['req_id_graduacao']) {
							//echo "xxxxxxxxxxxxx2";
							
							$ok = false;
						}
					}

					// Requer item
					if($conquista['req_id_item'] && !$conquista['req_id_especializacao']) {
						if(!$base->hasItemW($conquista['req_id_item'])) {
							//echo "xxxxxxxxxxxxx3";
							
							$ok = false;
						}
					}
				
					// Requer cla
					if($conquista['req_id_cla']) {
						if($base->id_cla != $conquista['req_id_cla']) {
							//echo "xxxxxxxxxxxxx4";
							
							$ok = false;
						}
					}

					// Requer classe
					if($conquista['req_id_classe']) {
						if($base->id_classe != $conquista['req_id_classe']) {
							//echo "xxxxxxxxxxxxx5";
							
							$ok = false;
						}
					}

					// Requer level
					if($conquista['req_level']) {
						if($base->level < $conquista['req_level']) {
							//echo "xxxxxxxxxxxxx6";
							
							$ok = false;
						}
					}
				
					// Requer especialização
					/*if($conquista['req_id_especializacao']) {
						if($base->id_especializacao != $conquista['req_id_especializacao']) {
							//echo "xxxxxxxxxxxxx7";
							
							$ok = false;
						}
					}*/

					// Requer invacação
					if($conquista['req_id_invocacao']) {
						if($base->id_invocacao != $conquista['req_id_invocacao']) {
							//echo "xxxxxxxxxxxxx8";
							
							$ok = false;
						}
					}
					
					// Requer selo
					if($conquista['req_id_selo']) {
						if($base->id_selo != $conquista['req_id_selo']) {
							//echo "xxxxxxxxxxxxx9";
							
							$ok = false;
						}
					}
					
					// Requer um elemento
					if($conquista['req_elemento_a'] && !$conquista['req_elemento_b']) {
						if(!in_array($conquista['req_elemento_a'], $base->getElementos())) {
							//echo "xxxxxxxxxxxxx10";
							
							$ok = false;
						}
					}

					// Requer DOIS elemento
					if($conquista['req_elemento_a'] && $conquista['req_elemento_b']) {
						if(!in_array($conquista['req_elemento_a'], $base->getElementos()) && !in_array($conquista['req_elemento_b'], $base->getElementos())) {
							//echo "xxxxxxxxxxxxx11";
							
							$ok = false;
						}
					}

					// Verificacoes extras pra quando e o proprio player
					//echo "//aaaaaaaaaaaaa"  . $conquista['id'] . "\n";
					if($arch_type == NG_ARCH_SELF) {
						
						if($conquista['req_ryou']) {
							//echo "//xxxxxxxxxxxxxxxx\n";
							if($base->ryou < $conquista['req_ryou']) {
								$ok = false;
							}
						}

						if($conquista['req_coin']) {
							//echo "//yyyyyyyyyy\n";
							if($base->coin < $conquista['req_coin']) {
								$ok = false;
							}
						}

						if($conquista['req_hp']) {
							if($base->max_hp < $conquista['req_hp']) {
								$ok = false;
							}
						}

						if($conquista['req_sp']) {
							if($base->max_sp < $conquista['req_sp']) {
								$ok = false;
							}
						}

						if($conquista['req_sta']) {
							if($base->max_sta < $conquista['req_sta']) {
								$ok = false;
							}
						}

						// Requer taijutsu
						if($conquista['req_tai']) {
							if($base->tai_raw < $conquista['req_tai']) {
								$ok = false;
							}
						}

						// Requer Bukijutsu
						if($conquista['req_ken']) {
							if($base->ken_raw < $conquista['req_ken']) {
								$ok = false;
							}
						}

						// Requer ninjutsu
						if($conquista['req_nin']) {
							if($base->nin_raw < $conquista['req_nin']) {
								$ok = false;
							}
						}

						// Requer genjutsu
						if($conquista['req_gen']) {
							if($base->gen_raw < $conquista['req_gen']) {
								$ok = false;
							}
						}

						// Requer energia
						if($conquista['req_ene']) {
							if($base->ene_raw < $conquista['req_ene']) {
								$ok = false;
							}
						}

						// Requer aglidade
						if($conquista['req_agi']) {
							if($base->agi_raw < $conquista['req_agi']) {
								$ok = false;
							}
						}

						// Requer concentração
						if($conquista['req_con']) {
							if($base->con_raw < $conquista['req_con']) {
								$ok = false;
							}
						}

						// Requer força
						if($conquista['req_for']) {
							if($base->for_raw < $conquista['req_for']) {
								$ok = false;
							}
						}
						// Requer inteligencia
						if($conquista['req_int']) {
							if($base->int_raw < $conquista['req_int']) {
								$ok = false;
							}
						}
						
						// Requer resistencia
						if($conquista['req_res']) {
							if($base->res_raw < $conquista['req_res']) {
								$ok = false;
							}
						}

					}
					/*if($_SESSION['universal'] && $conquista['id']==192030){
						print_r($ok);
						die();
					}*/
					if(isset($conquista['data_inicio']) && $conquista['data_inicio'] && !between(now(), strtotime($conquista['data_inicio']), strtotime($conquista['data_fim']))) {
						$ok	= false;
					}
					
					
					if($ok) {
						//echo "// CONDPASS {$conquista['id']} --> {$conquista['nome']}<br />\n";
						arch_set($conquista['id'], $qtd, NULL, $playerData->id);
					}
				}
			
				break;
			
			case NG_ARCH_ARENA:
				$conquistas	= Recordset::query('SELECT * FROM conquista WHERE is_arena="1"');
				
				foreach($conquistas->result_array() as $conquista) {
					$ok	= true;
					
					if($conquista['req_arena_vila'] && $itemData != $conquista['req_arena_vila']) {
						$ok	= false;	
					}
					

					if($conquista['req_arena_total'] && $enemyData != 1) {
						$ok	= false;	
					}

					if($conquista['req_arena_players'] && $enemyData != 2) {
						$ok	= false;	
					}
					
					/*
					if($conquista['req_arena_total']) {
						$total	= Recordset::query('SELECT COUNT(id) AS total FROM arena WHERE player_id=' . $playerData->id)->row()->total;
						
						if($total < $conquista['req_arena_total']) {
							$ok	= false;	
						}
					}
					
					if($conquista['req_arena_players']) {
						$total	= Recordset::query('SELECT COUNT(id) AS total FROM arena_log WHERE player_id=' . $playerData->id)->row()->total;
						
						if($total < $conquista['req_arena_players']) {
							$ok	= false;	
						}						
					}
					*/

					if(isset($conquista['data_inicio']) && $conquista['data_inicio'] && !between(now(), strtotime($conquista['data_inicio']), strtotime($conquista['data_fim']))) {
						$ok	= false;
					}
					
					if($ok) {
						arch_set($conquista['id'], $qtd);	
					}
				}
			
				break;

			case NG_ARCH_EXAME:
				$conquistas	= Recordset::query('SELECT * FROM conquista WHERE is_exame="1"');
				
				foreach($conquistas->result_array() as $conquista) {
					$ok	= true;

					if ($conquista['req_id_graduacao'] != $playerData->id_graduacao) {
						$ok	= false;
					}

					$exam	= Recordset::query('SELECT * FROM exame_chuunin WHERE id=' . $playerData->id_exame_chuunin, true)->row_array();
					
					if ($conquista['req_exame_etapa']) {
						if ($conquista['req_exame_etapa'] == $playerData->exame_chuunin_etapa) {
							if ($conquista['req_exame_etapa'] == 1 && $exam['etapa1'] != 1) {
								$ok	= false;
							}
						} else {
							$ok	= false;
						}
					}
					
					if($ok) {
						arch_set($conquista['id'], 1);	
					}
				}
			
				break;

			case NG_ARCH_GUERRA:
				$conquistas	= Recordset::query('SELECT * FROM conquista WHERE is_guerra="1"');
				
				foreach($conquistas->result_array() as $conquista) {
					$ok	= true;

					if ($conquista['req_fim_guerra']) {
						if (!isset($playerData->fim_guerra)) {
							$ok	= false;
						}
					}

					if ($conquista['req_npc_guerra'] && $enemyData && !$enemyData->npc_guerra) {
						$ok	= false;
					}

					if($ok) {
						arch_set($conquista['id'], 1);	
					}
				}
			
				break;
			
			case NG_ARCH_4XPVP:
				$achievements	= Recordset::query('SELECT * FROM conquista WHERE is4xpvp="1"', true);
				
				foreach($achievements->result_array() as $achievement) {
					$ok			= true;
					$team		= Recordset::query('
						SELECT
							a.*,
							((SELECT SUM(level) FROM player WHERE id_equipe=a.id) / 4) AS average,
							(SELECT id_vila FROM player WHERE id=a.id_player) AS id_vila
						
						FROM
							equipe a
						
						WHERE
							a.id=' . $enemyData)->row_array();
					
					if($achievement['req_media_nivel'] && $team['average'] < $achievement['req_media_nivel']) {
						$ok	= false;
					}

					if($achievement['req_nivel_equipe'] && $team['level'] < $achievement['req_nivel_equipe']) {
						$ok	= false;
					}

					if($achievement['req_vila_equipe'] && $team['id_vila'] != $achievement['req_vila_equipe']) {
						$ok	= false;
					}

					if(isset($achievement['data_inicio']) && $achievement['data_inicio'] && !between(now(), strtotime($achievement['data_inicio']), strtotime($achievement['data_fim']))) {
						$ok	= false;
					}
					
					if($ok) {
						arch_set($achievement['id'], 1);
					}					
				}
				
				break;

			case NG_ARCH_EVENTO_VILA:
				$achievements	= Recordset::query('SELECT * FROM conquista WHERE is_evento_global="1"', true);
				
				foreach($achievements->result_array() as $achievement) {
					$ok		= true;
					$count	= 0;
					$items	= Recordset::query('SELECT COUNT(id) AS total FROM player_item WHERE id_player=' . $playerData->id . ' AND id_item_tipo=' . $achievement['req_id_item_tipo']);

					if($items->num_rows) {
						$count	= $items->row()->total;
					} else {
						$ok	= false;
					}

					if(isset($achievement['data_inicio']) && $achievement['data_inicio'] && !between(now(), strtotime($achievement['data_inicio']), strtotime($achievement['data_fim']))) {
						$ok	= false;
					}

					if($ok) {
						arch_set($achievement['id'], $count);
					}
				}

				break;

			case NG_ARCH_TEMA_VIP:
				$achievements	= Recordset::query('SELECT * FROM conquista WHERE is_theme="1"', true);

				foreach($achievements->result_array() as $achievement) {
					$ok	= true;

					if($achievement['req_id_npc'] && !$itemData->ultimate) {
						$ok	= false;
					}

					if ($achievement['req_id_classe_imagem']  && $itemData->id != $achievement['req_id_classe_imagem']) {
						$ok	= false;
					}

					if(isset($achievement['data_inicio']) && $achievement['data_inicio'] && !between(now(), strtotime($achievement['data_inicio']), strtotime($achievement['data_fim']))) {
						$ok	= false;
					}

					if($ok) {
						arch_set($achievement['id'], 1);
					}
				}

				break;
				
			case NG_ARCH_PACOTES:
				$achievements	= Recordset::query('SELECT * FROM conquista WHERE is_pack="1"', true);

				foreach($achievements->result_array() as $achievement) {
					$ok	= true;

					/*if($achievement['req_id_npc'] && !$itemData->ultimate) {
						$ok	= false;
					}

					if ($achievement['req_id_classe_imagem']  && $itemData->id != $achievement['req_id_classe_imagem']) {
						$ok	= false;
					}*/

					if(isset($achievement['data_inicio']) && $achievement['data_inicio'] && !between(now(), strtotime($achievement['data_inicio']), strtotime($achievement['data_fim']))) {
						$ok	= false;
					}

					if($ok) {
						arch_set($achievement['id'], 1);
					}
				}

				break;


			case NG_ARCH_PROFISSAO:
				$achievements	= Recordset::query('SELECT * FROM conquista WHERE is_profissao="1"', true);

				foreach($achievements->result_array() as $achievement) {
					$ok	= true;

					if($achievement['req_id_profissao'] && $playerData->id_profissao != $achievement['req_id_profissao']) {
						$ok	= false;
					}

					if($ok) {
						arch_set($achievement['id'], 1);
					}
				}

				break;
		}
	}
	
	function arch_set($arch_id, $qtd = 1, $is_quest = false, $playerData = NULL) {
		if (!$_SESSION['universal']) {
			//return;
		}
		global $basePlayer;
		$player_id = $playerData ? $playerData : $basePlayer->id;		
		
		if(!(int)$qtd) {
			return;
		}	
		
		if(Recordset::query("SELECT id FROM conquista_item WHERE id_player=" . $player_id . " AND id_conquista=" . $arch_id)->num_rows) {
			if($is_quest) {
				Recordset::update('conquista_item', array(
					'qtd'			=> (int)$qtd
				), array(
					'id_player'		=> $player_id,
					'id_conquista'	=> $arch_id
				));
			} else {
				Recordset::update('conquista_item', array(
					'qtd'			=> array('escape' => false, 'value' => 'qtd+' . (int)$qtd)
				), array(
					'id_player'		=> $player_id,
					'id_conquista'	=> $arch_id
				));
			}			
		} else {
			Recordset::insert('conquista_item', array(
				'id_player'		=> $player_id,
				'id_conquista'	=> $arch_id,
				'qtd'			=> (int)$qtd
			));
		}
		
		$rCon	= Recordset::query("SELECT req_qtd, nome_" . Locale::get() . " AS nome FROM conquista WHERE id=" . $arch_id, true)->row_array();
		$rItem	= Recordset::query("SELECT id, qtd FROM conquista_item WHERE id_conquista=" . $arch_id . " AND id_player=" . $player_id)->row_array();
		
		if($rItem['qtd'] >= $rCon['req_qtd']) {
			if($rCon['req_qtd'] == '') {
				$rCon['req_qtd'] = 1;
			}
			
			Recordset::update('conquista_item', array(
				'completa'	=> '1',
				'qtd'		=> array('escape' => false, 'value' => $rCon['req_qtd'])
			), array(
				'id'		=> $rItem['id']
			));
		}
		
		arch_check();
	}
	
	function arch_check() {
		if (!$_SESSION['universal']) {
			//return;
		}		
		global $basePlayer;
				
		// Processa um grupo de conquistas e premia o mané --->
			$q = Recordset::query('SELECT * FROM conquista_grupo', true);
			
			// Trazer todos os grupos de uma vez é BEM mais prático --->
				$raw_grupos	= Recordset::query('SELECT id_conquista_grupo FROM conquista_grupo_item WHERE id_player=' . $basePlayer->id);
				$grupos		= array();
				
				foreach($raw_grupos->result_array() as $grupo) {
					$grupos[]	= $grupo['id_conquista_grupo'];
				}
			// <---
			
			foreach($q->result_array() as $r) {
				if(in_array($r['id'], $grupos)) { // Se ja completou o grupo, vai pro próximo
					continue;
				}
				
				$ok	= true;
				$qq	= Recordset::query('SELECT id, req_qtd FROM conquista WHERE id_grupo=' . $r['id'], true);
				
				foreach($qq->result_array() as $rq) {
					if(!Recordset::query("SELECT id FROM conquista_item WHERE id_player=" . $basePlayer->id . " AND id_conquista=" . $rq['id'] . " AND qtd=" . $rq['req_qtd'])->num_rows) {
						$ok = false;
						
						break;
					}
				}
				
				if($ok) {
					if(function_exists('fb_post_to_feed')) {
						fb_post_to_feed(t('conquistas.c42') . $basePlayer->nome . ' conseguiu a conquista "' . addslashes($r['nome_'. Locale::get()]) . '"', array(
							'link'		=> 'http://narutogame.com.br',
							'picture'	=> 'http://narutogame.com.br/images/conquista2.jpg'
						));
					}
				
					$msg = array();
					
					// Itens
					if($r['id_item']) {
						if($basePlayer->hasItemW($r['id_item'])) {
							Recordset::update('player_item', array(
								'qtd'		=> array('escape'	=> false, 'value' => 'qtd+' . $r['mul'])
							), array(
								'id_player'	=> $basePlayer->id,
								'id_item'	=> $r['id_item']
							));
						} else {
							Recordset::insert('player_item', array(
								'id_item'	=> $r['id_item'],
								'id_player'	=> $basePlayer->id,
								'qtd'		=> $r['mul']
							));
						}
						
						$rItem = Recordset::query("SELECT nome_" . Locale::get() . " AS nome FROM item WHERE id=" . $r['id_item'], true)->row_array();
						$msg[] = $r['mul'] . "x " . $rItem['nome'];						
					}
					
					// Ryou
					if($r['ryou']) {
						$msg[] = $r['ryou'] . " Ryous";
						
						Recordset::query("UPDATE player SET ryou=ryou + " .$r['ryou'] . " WHERE id=" . $basePlayer->id);
					}
				
					// Exp
					if($r['exp']) {
						$msg[] = $r['exp'] . t('geral.pontos_exp');
						
						Recordset::query("UPDATE player SET exp=exp + " .$r['exp'] . " WHERE id=" . $basePlayer->id);
					}
				
					// Ene
					if($r['ene']) {
						$msg[] = $r['ene'] . t('conquistas.c3');
						
						Recordset::query("UPDATE player_atributos SET ene=ene + " .$r['ene'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// For
					if($r['forc']) {
						$msg[] = $r['forc'] . t('conquistas.c6');
						
						Recordset::query("UPDATE player_atributos SET forc=forc + " .$r['forc'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// Int
					if($r['inte']) {
						$msg[] = $r['inte'] . t('conquistas.c4');
						
						Recordset::query("UPDATE player_atributos SET inte=inte + " .$r['inte'] . " WHERE id_player=" . $basePlayer->id);
					}
					
					// RES
					if($r['res']) {
						$msg[] = $r['res'] . t('conquistas.c5');
						
						Recordset::query("UPDATE player_atributos SET res=res + " .$r['res'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// Agi
					if($r['agi']) {
						$msg[] = $r['agi'] . t('conquistas.c7');
						
						Recordset::query("UPDATE player_atributos SET agi=agi + " .$r['agi'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// Con

					if($r['con']) {
						$msg[] = $r['con'] . t('conquistas.c8');
						
						Recordset::query("UPDATE player_atributos SET con=con + " .$r['con'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// Tai
					if($r['tai']) {
						$msg[] = $r['tai'] . t('conquistas.c9');
						
						Recordset::query("UPDATE player_atributos SET tai=tai + " .$r['tai'] . " WHERE id_player=" . $basePlayer->id);
					}
					// ken
					if($r['ken']) {
						$msg[] = $r['ken'] . t('conquistas.c91');
						
						Recordset::query("UPDATE player_atributos SET ken=ken + " .$r['ken'] . " WHERE id_player=" . $basePlayer->id);
					}
				
				
					// Nin
					if($r['nin']) {
						$msg[] = $r['nin'] . t('conquistas.c10');
						
						Recordset::query("UPDATE player_atributos SET nin=nin + " .$r['nin'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// Gen
					if($r['gen']) {
						$msg[] = $r['gen'] . t('conquistas.c11');
						
						Recordset::query("UPDATE player_atributos SET gen=gen + " .$r['gen'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// Coin
					if($r['coin']) {
						$msg[] = $r['coin'] . t('conquistas.c12');
						
						Recordset::query("UPDATE global.user SET coin=coin+ " . $r['coin'] . " WHERE id=" . $_SESSION['usuario']['id']);
					}
					
					// Titulo
					if($r['titulo_br'] || $r['titulo_en']) {
						$msg[] = t('conquistas.c13') .": ". $r['titulo_'. Locale::get()];
						
						Recordset::query("INSERT INTO player_titulo(id_player, id_usuario, titulo_br, titulo_en) VALUES(" . $basePlayer->id . "," . $basePlayer->id_usuario . " ,'" . addslashes($r['titulo_br']) . "', '" . addslashes($r['titulo_en']) . "')");
					}

					$msg	= t('conquistas.c40') . "&nbsp;&nbsp;" . $r['nome_'.Locale::get()] . "&nbsp;" . t('geral.em') . date(" d/m/Y H:m:s ") . t('conquistas.c41') . join("\n", $msg);

					mensageiro(NULL, $basePlayer->id, t('conquistas.c1') . ' ' . $r['nome_'.Locale::get()], $msg, 'achiv');
					Recordset::query("INSERT INTO conquista_grupo_item(id_player, id_conquista_grupo) VALUES(" . $basePlayer->id . ", " . $r['id'] . ")");
				}
			}
		// <---		
	}
