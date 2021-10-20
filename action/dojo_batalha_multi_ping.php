<?php
	$redir_script = true;

	if($_POST['_pvpToken'] != $_SESSION['_pvpToken']) {
		redirect_to('negado', null, array('e' => 'token'));
	}

	$batalha			= Recordset::query("SELECT * FROM batalha_multi WHERE id=" . $basePlayer->id_batalha_multi)->row_array();
	$players			= array($batalha['p1'], $batalha['p2'], $batalha['p3'], $batalha['p4']);	
	$enemies			= array($batalha['e1'], $batalha['e2'], $batalha['e3'], $batalha['e4']);
	$equipe				= Recordset::query('SELECT * FROM equipe WHERE id=' . $basePlayer->id_equipe)->row_array();
	$is_attack			= false;
	$hasEnd				= false;
	$is_trigger			= false;
	$arPlayersInstances	= array(); 
	$arEnemyInstances	= array();
	$conv_my			= 0;
	$conv_en			= 0;
	$turnos				= SharedStore::G('_TRL_' . $basePlayer->id, array());

	if(!$batalha['ps1'] && !$batalha['ps1'] && !$batalha['ps1'] && !$batalha['ps1']) {
		$hasEnd = true;
	}

	if(!$batalha['es1'] && !$batalha['es1'] && !$batalha['es1'] && !$batalha['es1']) {
		$hasEnd = true;
	}

	for($f = 1; $f <= 4; $f++) {
		if(on($basePlayer->id, $players)) {
			if($batalha['p' . $f] == $basePlayer->id) {
				$player = $basePlayer;
				$arPlayersInstances[$f] = $basePlayer;
			} else {
				$player = new Player($batalha['p' . $f]);
				$arPlayersInstances[$f] = $player;
			}

			$conv_my	+= $player->getAttribute('conv_calc');
		}
	}
	
	// Convicção --->
		$conv_my	= round($conv_my / 4);

		if($batalha['id_tipo'] == 2) {
			for($f =1; $f <= 4; $f++) {
				$arEnemyInstances[$f]	= gzunserialize($batalha['npc' . $f]);
				$conv_en				+= $arEnemyInstances[$f]->getAttribute('conv_calc');				
			}
			
			$conv_en	= round($conv_en / 4);
		} else {
			try {
				$objekt	= gzunserialize($batalha['npc1']);
				
				if(!method_exists($objekt, 'getAttribute')) {
					trigger_error(print_r($objekt, true), E_USER_ERROR);
					error_log(print_r($objekt, true), E_USER_ERROR);
				}
				
				$conv_en	= gzunserialize($batalha['npc1'])->getAttribute('conv_calc');
			} catch(Exception $e) {
				trigger_error('BMULTI -> ' . $basePlayer->id_batalha_multi, E_USER_WARNING);
				error_log('BMULTI -> ' . $basePlayer->id_batalha_multi, E_USER_WARNING);
				die();	
			}
		}
	// <---
	
	// Gets the battle log
	$_SESSION['multi_log'] = $batalha['log'];
	
	switch($batalha['id_tipo']) {
		case 1:
			$index		= 'p' . $batalha['current_id_p'];
			$index_char	= $batalha['direction'] ? 'e' : 'p';
			$rotation	= 8;
			$pvps_ids	= array(1);
			
			break;

		case 2:
			$index		= 'p' . $batalha['current_id_p'];
			$index_char	= $batalha['direction'] ? 'e' : 'p';
			$rotation	= 8;

			$pvps_ids = array(1, 2, 3, 4);
			
			break;
		
		case 3:
			$index_char	= $batalha['current'] <= 4 ? 'p' : 'e';
			$index		= $index_char . $batalha['current'];
			$rotation	= 8;

			break;
	}
	
	if(($batalha['direction'] == 0 && $batalha[$index] == $basePlayer->id) || ($batalha['direction'] == 1 && $players[$batalha['target'] - 1] == $basePlayer->id)) {
		$myTurn = true;
	} else {
		$myTurn = false;	
	}
	
	if(isset($_POST['action']) && is_numeric($_POST['action'])) {
		if(!$myTurn) {
			die('jalert("'.t('actions.a62').'")');
		}
		
		if(!is_numeric($_POST['item'])) {
			die('jalert("'.t('actions.a86').'")');
		}
		
		if(!$basePlayer->hasItemW($_POST['item']) && !in_array($_POST['item'], array(4, 5, 6, 7))) {
			die('jalert("'.t('actions.a87').'")');
		}

		if($batalha['target'] == "") {
			if(!$_POST['target'] || !on($_POST['target'], array(1, 2, 3, 4, 5, 6, 7, 8))) {
				die('alert("'.t('actions.a88').'")');
			}
		}

		$is_attack		= true;
		$current		= $batalha['current'];
		$current_id_e	= $batalha['current_id_e'];
		$current_id_p	= $batalha['current_id_p'];
		$item			= $basePlayer->getItem($_POST['item']);

		$item->setPlayerInstance($basePlayer);
		$item->parseLevel();
		$item->apply_team_modifiers();

		// Controle dos turnos --->
			if($item->getAttribute('turnos') && isset($turnos[$item->id])) {
				die('jalert("'.t('actions.a65').'");');
			} elseif($item->getAttribute('turnos')) {
				$turnos[$item->id] = $item->getAttribute('turnos');
			}			
		// <---
		
		if($_POST['action'] == 2) { // Buff
			if($batalha['id_tipo'] == 1) {
				$baseEnemy = gzunserialize($batalha['npc1']);
			} elseif($batalha['id_tipo'] == 2) {
				if($batalha['target'] == '') {
					if(!between($_POST['target'], 5, 8)) {
						die('alert("'.t('actions.a88').' [4]")');
					}
				
					if(!$batalha['es' . ($_POST['target'] - 4)]) {
						die('alert("'.t('actions.a89').' [1]")');
					}
					
					$baseEnemy = gzunserialize($batalha['npc' . ($_POST['target'] - 4)]);				
				} else {
					$baseEnemy = gzunserialize($batalha['npc' . $batalha['current_id_e']]);					
				}
			} elseif($batalha['id_tipo'] == 3) {
				//PVP
			}

			$arModifiers	= $basePlayer->getModifiers();
			$mod			= $item->getModifiers();
			$pass			= true;
			
			if($item->getAttribute('id_tipo') == 1) {
				foreach($arModifiers as $mod) {
					$mod_item = Recordset::query("SELECT id_tipo FROM item WHERE id=" . (int)$mod['id'], true)->row_array();
					
					if($mod_item['id_tipo'] == 1) {
						$pass = false;
						
						break;
					}
				}
				
				if($pass) {
					if($basePlayer->removeItem($item)) {
						echo '$("#atki-' . $item->id . '").parent().hide("explode").remove();' . PHP_EOL;
					} else {
						echo '_items[' . $item->id . '].t = ' . ($item->getAttribute('total') - 1) . ' ;' . PHP_EOL;
					}
				}
				
				$o_direction = 0;
			} else {
				// So pode um genjutsu/buff por vez --->
					if(
						!$mod['target_ken']			&&!$mod['target_tai'] 			&& !$mod['target_nin'] 			&& !$mod['target_gen']			&& !$mod['target_agi']			&& !$mod['target_con']			&& 
						!$mod['target_forc']		&& !$mod['target_inte']			&& !$mod['target_res']			&& !$mod['target_atk_fisico']	&& !$mod['target_atk_magico']	&& 
						!$mod['target_def_base']	&& !$mod['target_prec_fisico']	&& !$mod['target_prec_magico']	&& !$mod['target_crit_min']		&& !$mod['target_crit_max']	&& !$mod['target_crit_total'] && !$mod['target_esq_min']	&& !$mod['target_esq_total']	&& !$mod['target_esq_max']	&&
						!$mod['target_esq']			&& !$mod['target_det']			&&!$mod['target_conv']			&& !$mod['target_conc']			&&
						!$mod['target_def_fisico']	&& !$mod['target_def_magico']
					) {	
						$o_direction = 0; // BUFF
					} elseif(
						!$mod['self_ken'] 			&& !$mod['self_tai'] 			&& !$mod['self_nin'] 			&& !$mod['self_gen']			&& !$mod['self_agi']		&& !$mod['self_con']		&& 
						!$mod['self_forc']			&& !$mod['self_inte']			&& !$mod['self_res']			&& !$mod['self_atk_fisico']		&& !$mod['self_atk_magico']	&& 
						!$mod['self_def_base']		&& !$mod['self_prec_fisico']	&& !$mod['self_prec_magico']	&& !$mod['self_crit_min']		&& !$mod['self_crit_max']	&& !$mod['self_crit_total']   && !$mod['self_esq_min']	&& !$mod['self_esq_max'] && !$mod['self_esq_total'] &&
						!$mod['self_esq']			&& !$mod['self_det']			&&!$mod['self_conv']			&& !$mod['self_conc']			&&
						!$mod['self_def_fisico']	&& !$mod['self_def_magico']
					) {
						$o_direction = 1; // GEN
					} else {
						$o_direction = 2; // DUAL
					}				
	
					foreach($arModifiers as $mod) {
						$mod_item = Recordset::query("SELECT id_habilidade, id_tipo FROM item WHERE id=" . (int)$mod['id'], true)->row_array();
	
						if($mod_item['id_habilidade'] && $mod_item['id_tipo'] != 1) { // Somente itens que não tem ordem(qquer coisa diferente de clas e afins)
							switch($o_direction) {
								case 0:
									if(($mod['o_direction'] == 0 && $mod['direction'] == 0) || ($mod['o_direction'] == 2 && $mod['dreiction'] == 2)) {
										$pass = false;
									}
	
									break;
	
								case 1:
									if(($mod['o_direction'] == 1 && $mod['direction'] == 0) || ($mod['o_direction'] == 2 && $mod['direction'] == 0)) {
										$pass = false;
									}
	
									break;
	
								case 2:
									if($mod['o_direction'] == 0 || $mod['o_direction'] == 1 || $mod['o_direction'] == 2) {
										$pass = false;
									}
	
									break;
							}
						}
					}	
				// <---
			}

			if(!$pass) {
				die("jalert('". t('actions.a90') ." [2]!')");
			}
			
			// Salva os dados de turnos
			SharedStore::S('_TRL_' . $basePlayer->id, $turnos);
			
			$item->setPlayerInstance($basePlayer);
			$item->parseLevel();

			// Verifica se tem stats para usar --->
				if($item->consume_sp > $basePlayer->getAttribute('sp')) {
					die('jalert("'. t('actions.a63') .'")');
				}
	
				if($item->consume_sta > $basePlayer->getAttribute('sta')) {
					die('jalert("'. t('actions.a64') .'"")');
				}
			// <---

			// Consumo
			$basePlayer->consumeSP($item->consume_sp);
			$basePlayer->consumeSTA($item->consume_sta);
			
			$basePlayer->addModifier($item, $item->getAttribute('level'), 0, $o_direction);
			$baseEnemy->addModifier($item, $item->getAttribute('level'), 1, $o_direction);			
		} elseif($_POST['action'] == 3) { // Clas, invo e etc
			if(!in_array($item->getAttribute('id_tipo'), array(16, 17, 20, 21, 23, 26))) {
				die('jalert("'. t('actions.a67') .'")');
			}

			// Verifica se tem stats para usar --->
				if($item->consume_sp > $basePlayer->getAttribute('sp')) {
					die('jalert("'. t('actions.a63') .'")');
				}
	
				if($item->consume_sta > $basePlayer->getAttribute('sta')) {
					die('jalert("'. t('actions.a64') .'")');
				}
			// <---

			$arModifiers	= $basePlayer->getModifiers();
			
			foreach($arModifiers as $modifier) {
				if($modifier['id'] == $item->id && $modifier['direction'] == 0) {
					die('jalert("'.t('fight.f20').'")');						
				}
			}

			// Consumo
			$basePlayer->consumeSP($item->consume_sp);
			$basePlayer->consumeSTA($item->consume_sta);		
			
			$basePlayer->addModifier($item, $item->getAttribute('level'), 0);
		} elseif($_POST['action'] == 4) {
			if(!between($_POST['target_h'], 1, 4)) {
				die('jalert("'. t('actions.a91') .' [1]")');
			}
			
			if($item->id_tipo != 24) {
				die('jalert("'. t('actions.a92') .'")');
			}
			
			if($batalha['p' . $_POST['target_h']] == $basePlayer->id) {
				die("jalert('". t('actions.a93') ."')");
			}

			if(!$batalha['ps' . $_POST['target_h']]) {
				die("jalert('". t('actions.a94') ."')");
			}
			
			if($batalha['direction'] == 1) {
				die("jalert('".t('actions.a95')."')");
			}

			if(Player::getFlag('equipe_role', $basePlayer->id) != 1) {
				die("jalert('".t('actions.a267')."')");
			}

			// Verifica se tem stats para usar --->
				if($item->consume_sp > $basePlayer->getAttribute('sp')) {
					die('jalert("'. t('actions.a63') .'")');
				}
	
				if($item->consume_sta > $basePlayer->getAttribute('sta')) {
					die('jalert("'. t('actions.a64') .'")');
				}
			// <---

			// Consumo
			$basePlayer->consumeSP($item->consume_sp);
			$basePlayer->consumeSTA($item->consume_sta);
			
			$p = new Player($batalha['p' . $_POST['target_h']]);
			
			
			// Aplica o bonus de especialização caso ouver --->
				$role_id = Player::getFlag('equipe_role', $basePlayer->id);
				
				switch($role_id) {
					case 0:
						$role0_lvl = Player::getFlag('equipe_role_0_lvl', $basePlayer->id);

						$item->bonus_hp  -= floor(percent($role0_lvl * 10, $item->bonus_hp));
						$item->bonus_sp  -= floor(percent($role0_lvl * 10, $item->bonus_sp));
						$item->bonus_sta -= floor(percent($role0_lvl * 10, $item->bonus_sta));
					
						break;
					
					case 1:
						$role1_lvl = Player::getFlag('equipe_role_1_lvl', $basePlayer->id);
						
						$item->bonus_hp  += floor(percent($role1_lvl * 10, $item->bonus_hp));
						$item->bonus_sp  += floor(percent($role1_lvl * 10, $item->bonus_sp));
						$item->bonus_sta += floor(percent($role1_lvl * 10, $item->bonus_sta));
						
						break;
					
					case 2:
						$role2_lvl = Player::getFlag('equipe_role_2_lvl', $basePlayer->id);

						$item->bonus_hp  -= floor(percent($role2_lvl * 10, $item->bonus_hp));
						$item->bonus_sp  -= floor(percent($role2_lvl * 10, $item->bonus_sp));
						$item->bonus_sta -= floor(percent($role2_lvl * 10, $item->bonus_sta));
					
						break;
					
					case 3:
						$role3_lvl = Player::getFlag('equipe_role_3_lvl', $basePlayer->id);

						$item->bonus_hp  -= floor(percent($role3_lvl * 10, $item->bonus_hp));
						$item->bonus_sp  -= floor(percent($role3_lvl * 10, $item->bonus_sp));
						$item->bonus_sta -= floor(percent($role3_lvl * 10, $item->bonus_sta));
					
						break;
				}
			// <---

			if($item->bonus_hp)  $p->consumeHP(-$item->bonus_hp);
			if($item->bonus_sp)  $p->consumeSP(-$item->bonus_sp);
			if($item->bonus_sta) $p->consumeSTA(-$item->bonus_sta);

			$log = '<span class="pvp_p_name">' . $basePlayer->nome . '</span> '.t('actions.a96').'  <span class="pvp_p_atk">' . $item->nome . 
				   '</span> no jogador <span class="pvp_p_name">' . $p->nome . '</span>';


			// Escolhe o proximo player VIVO que vai atacar --->
				$current_id_p++;
				while(true) {
					if(isset($batalha['ps' . $current_id_p]) && $batalha['ps' . $current_id_p]) {
						break;
					}									

					$current_id_p++;
					
					if($current_id_p > 4) {
						$current_id_p = 1;
					}
				}
			// <---

			// Faz o npc escolher um alvo que esteja VIVO como alvo --->
				while(true) {
					$target = rand(1, 4);
					
					if($batalha['ps' . $target]) {
						break;
					}
				}
			// <---
			
			Recordset::query("
				UPDATE 
					batalha_multi 
				SET 
					direction='1',
					target=" . $target . ",
					current_id_p=" . $current_id_p . ",
					log='" . addslashes($log) . "'
				WHERE
					id=" . $basePlayer->id_batalha_multi);
			
			
			echo "$('.pFrame').css('background-image', 'none');";
			die();
		} elseif($_POST['action'] == 1) { // Ataque normal
			// Define o ataque a ser usado
			$basePlayer->setAtkItem($item);

			switch($batalha['id_tipo']) {
				case 1:
					$baseEnemy = gzunserialize($batalha['npc1']);
					$npc_id = 1;

					// Modificadores --->
						//Player::parseModifiers($baseEnemy);				
						$baseEnemy->atCalc(true);				
					// <---
					
					break;

				case 2:
					if($batalha['direction'] == 0) {
						if($_POST['target'] <= 4) {
							die('jalert("'.t('actions.a97').'")');
						}
					
						$baseEnemy = gzunserialize($batalha['npc' . ($_POST['target'] - 4)]);
						$npc_id = $_POST['target'] - 4;
					} else {
						$baseEnemy = gzunserialize($batalha['npc' . $batalha['current_id_e']]);
						$npc_id = $batalha['current_id_e'];
					}

					// Modificadores --->
						/*if(!$baseEnemy) {
							ob_start();
							var_dump($batalha);
							var_dump($baseEnemy);
							var_dump($basePlayer);
							var_dump($_POST);
							
							$ob_result = ob_get_clean();
							ob_end_clean();
						
							mail('fox.mc.cloud.pro@gmail.com', 'Debug NG', $ob_result);
						}*/
					
						//Player::parseModifiers($baseEnemy);		
						$baseEnemy->atCalc(true);				
					// <---
					
					break;

				case 3:
					$baseEnemy = new Player($batalha[$index_char_rev . $_POST['target']]);
					if($batalha['target'] != "") {
						$is_trigger = true;
					}
					
					break;
			}

			// Aplica o diferencial de convicão
				$basePlayer->setLocalAttribute('less_conv',	(is_a($basePlayer, 'Player') ? $conv_en : $conv_my));
				$baseEnemy->setLocalAttribute('less_conv',	(is_a($basePlayer, 'NPC') ? $conv_my : $conv_en));
			
				$basePlayer->atCalc();
				$baseEnemy->atCalc();
			// <--
			
			$log			= "";
			$has_pvp_action	= false;

			if(!$is_trigger) {
				$fight		= new Fight();
				$fight->is4	= true;
				$fight->id	= $basePlayer->id_batalha_multi;
				
				if($batalha['target'] != "") {
					$fight->addPlayer($baseEnemy);
					$fight->addEnemy($basePlayer);
				} else {
					$fight->addPlayer($basePlayer);
					$fight->addEnemy($baseEnemy);
				}

				switch($batalha['id_tipo']) {
					case 1: // 4x1 NPC
					case 2: // 4x4 NPC
						// Verificações
						if($batalha['direction'] == 0) {
							if($batalha['id_tipo'] == 1) {
								if($_POST['target'] != 1) {
									die('alert("'.t('actions.a88').' [2]")');
								}
							} else {
								if(!between($_POST['target'], 5, 8)) {
									die('alert("'.t('actions.a88').' [3]")');
								}
								
								if(!$batalha['es' . ($_POST['target'] - 4)]) {
									die('alert("'.t('actions.a98').'")');
								}
							}						
						}

						// Ação
						$fight->Process();
						$log .= $fight->log;
						
						// Ações de perder/ganhar --->
							if(
								($basePlayer->hp <= 10 || $basePlayer->sp <= 10 || $basePlayer->sta <= 10) &&
								($baseEnemy->hp <= 10 || $baseEnemy->sp <= 10 || $baseEnemy->sta <= 10)
							) { // Empate

								// Parte do player --->
									if($batalha['direction'] == 0) {
										$ps_id = $batalha['current_id_p'];
									} else {
										$ps_id = $batalha['target'];									
									}

									Recordset::query('UPDATE batalha_multi SET ps' . $ps_id . '=\'0\', target=NULL, `log`="' . addslashes($fight->log) . '" WHERE id=' . $basePlayer->id_batalha_multi);
									$batalha['ps' . $ps_id] = 0;

									// Se o player perdeu
									if(!$batalha['ps1'] && !$batalha['ps2'] && !$batalha['ps3'] && !$batalha['ps4']) {
										Recordset::query('UPDATE batalha_multi SET finished=\'1\', finished_direction=\'1\' WHERE id=' . $basePlayer->id_batalha_multi);
										die();
									}

									while(true) {
										if($batalha['ps' . $current_id_p]) {
											break;
										}									
		
										$current_id_p++;
										
										if($current_id_p > 4) {
											$current_id_p = 1;
										}
									}

	
									Recordset::query('UPDATE batalha_multi SET current_id_p=' . $current_id_p . ' WHERE id=' . $basePlayer->id_batalha_multi);
								// <---
								
								// Parte do inimigo -->
									if($batalha['id_tipo'] == 1) {
										Recordset::query('UPDATE batalha_multi SET log=\'' . addslashes($log) . '\', es1=\'0\', finished=\'1\', finished_direction=\'0\' WHERE id=' . $basePlayer->id_batalha_multi);
										
										die();				
									} else { // Se um npc morre, o  turno é passado para outro player
										if($batalha['direction'] == 0) {
											$es_id = $_POST['target'] - 4;
										} else {
											$es_id = $batalha['current_id_e'];
										}
								
										Recordset::query('UPDATE batalha_multi SET es' . $es_id . '=\'0\', npc' . $es_id . '="' . addslashes(gzserialize($baseEnemy)) . '", `log`="' . addslashes($fight->log) . '", target=NULL WHERE id=' . $basePlayer->id_batalha_multi);
										$batalha['es' . $es_id] = 0;
	
										// Detecta a finalização do combate se todos os npcs perderem --->
											if(!$batalha['es1'] && !$batalha['es2'] && !$batalha['es3'] && !$batalha['es4']) {
												Recordset::query('UPDATE batalha_multi SET finished=\'1\', finished_direction=\'0\' WHERE id=' . $basePlayer->id_batalha_multi);
												die();
											}
										// <---
	
										while(true) {
											if($batalha['es' . $current_id_e]) {
												break;
											}									
			
											$current_id_e++;
											
											if($current_id_e > 4) {
												$current_id_e = 1;
											}
										}
										
										Recordset::query('UPDATE batalha_multi SET current_id_e=' . $current_id_e . ' WHERE id=' . $basePlayer->id_batalha_multi);									
									}								
								// <---
								
							} elseif($basePlayer->hp <= 10 || $basePlayer->sp <= 10 || $basePlayer->sta <= 10) { // Player perde
								if($batalha['direction'] == 0) {
									$ps_id = $batalha['current_id_p'];
								} else {
									$ps_id = $batalha['target'];									
								}
							
								Recordset::query('UPDATE batalha_multi SET ps' . $ps_id . '=\'0\', target=NULL, `log`="' . addslashes($fight->log) . '" WHERE id=' . $basePlayer->id_batalha_multi);
								$batalha['ps' . $ps_id] = 0;

								// Detecta a finalização do combate se todos os players perderem --->
									if(!$batalha['ps1'] && !$batalha['ps2'] && !$batalha['ps3'] && !$batalha['ps4']) {
										Recordset::query('UPDATE batalha_multi SET finished=\'1\', finished_direction=\'1\' WHERE id=' . $basePlayer->id_batalha_multi);
										die();
									}
								// <---
								
								if($batalha['direction'] == 1) {
									//$current_id_p++;
									while(true) {
										if($batalha['ps' . $current_id_p]) {
											break;
										}									
		
										$current_id_p++;
										
										if($current_id_p > 4) {
											$current_id_p = 1;
										}
									}									

	
									Recordset::query('UPDATE batalha_multi SET current_id_p=' . $current_id_p . ' WHERE id=' . $basePlayer->id_batalha_multi);								
								}					
							} elseif($baseEnemy->hp <= 10 || $baseEnemy->sp <= 10 || $baseEnemy->sta <= 10) { // Inimigo perde
								if($batalha['id_tipo'] == 1) {
									Recordset::query('UPDATE batalha_multi SET log=\'' . addslashes($log) . '\', es1=\'0\', finished=\'1\', finished_direction=\'0\' WHERE id=' . $basePlayer->id_batalha_multi);
									
									die();				
								} else { // Se um npc morre, o  turno é passado para outro player
									if($batalha['direction'] == 0) {
										$es_id = $_POST['target'] - 4;
									} else {
										$es_id = $batalha['current_id_e'];
									}
							
								
									Recordset::query('UPDATE batalha_multi SET es' . $es_id . '=\'0\', npc' . $es_id . '="' . addslashes(gzserialize($baseEnemy)) . '", `log`="' . addslashes($fight->log) . '", target=NULL WHERE id=' . $basePlayer->id_batalha_multi);
									$batalha['es' . $es_id] = 0;

									// Detecta a finalização do combate se todos os npcs perderem --->
										if(!$batalha['es1'] && !$batalha['es2'] && !$batalha['es3'] && !$batalha['es4']) {
											Recordset::query('UPDATE batalha_multi SET finished=\'1\', finished_direction=\'0\' WHERE id=' . $basePlayer->id_batalha_multi);
											die();
										}
									// <---

									if($batalha['direction'] == 0) {
										//$current_id_e++;
										while(true) {
											if($batalha['es' . $current_id_e]) {
												break;
											}									
			
											$current_id_e++;
											
											if($current_id_e > 4) {
												$current_id_e = 1;
											}
										}
										
										Recordset::query('UPDATE batalha_multi SET current_id_e=' . $current_id_e . ' WHERE id=' . $basePlayer->id_batalha_multi);									
									}
								}
							}
						// <---
						
						if($batalha['direction'] == 0) { // Se é o meu ataque
							// Escolhe o proximo player VIVO que vai atacar --->
								$current_id_p++;
								while(true) {
									if(isset($batalha['ps' . $current_id_p]) && $batalha['ps' . $current_id_p]) {
										break;
									}									
	
									$current_id_p++;
									
									if($current_id_p > 4) {
										$current_id_p = 1;
									}
								}
							// <---

							// Faz o npc escolher um alvo que esteja VIVO como alvo --->
								while(true) {
									$target = rand(1, 4);
									
									if($batalha['ps' . $target]) {
										break;
									}
								}
							// <---
							
							Recordset::query("
								UPDATE 
									batalha_multi 
								SET 
									direction='1',
									target=" . $target . ",
									current_id_p=" . $current_id_p . ",
									log='" . addslashes($log) . "',
									npc" . $npc_id . "='" . addslashes(gzserialize($baseEnemy)) . "'
								WHERE
									id=" . $basePlayer->id_batalha_multi);
						} else { // Se é o NPC atacando um player
							// Escolhe o proximo player VIVO que vai atacar --->
								$current_id_e++;
								while(true) {
									if(isset($batalha['es' . $current_id_e]) && $batalha['es' . $current_id_e]) {
										break;
									}									
	
									$current_id_e++;
									
									if($current_id_e > 4) {
										$current_id_e = 1;
									}
								}
								
								if($batalha['id_tipo'] == 1) {
									$current_id_e = 1;
								}
							// <---
						
							Recordset::query("
								UPDATE 
									batalha_multi 
								SET 
									direction='0',
									target=NULL,
									current_id_e=" . $current_id_e . ",
									log='" . addslashes($log) . "',
									npc" . $npc_id . "='" . addslashes(gzserialize($baseEnemy)) . "'
								WHERE
									id=" . $basePlayer->id_batalha_multi);
						}
						
						$has_pvp_action = true;
						
						break;
					
					case 3:
					
						break;
				}
			} else { // Os pares são as triggers, ou seja, os inimigos
				$fight->is4	= true;
				$fight->Process();
				
				// UPDATE LOG

					if($batalha['current'] == $rotation) {
						$current = 1;
					} else {
						$current++;
					}
					
				$has_pvp_action = true;
			}
			
			if($has_pvp_action) {
				// Rotaciona os buffs --->
					$basePlayer->rotateModifiers();
					$baseEnemy->rotateModifiers();
					//Player::rotateModifiers($baseEnemy);						
				// <---

				// Rotaciona os turnos dos golpes em cooldown --->
					$new_turnos = array();
					
					foreach($turnos as $k => $v) {
						$turnos[$k]--;
						
						if($turnos[$k] > 0) {
							$new_turnos[$k] = $turnos[$k];
						}
					}
					
					$turnos = $new_turnos;
				// <---
				
				// Salva os dados de turnos
				SharedStore::S('_TRL_' . $basePlayer->id, $new_turnos);
			}
		
		}
		
		die();
	}
?>

$('#cnLog').html('<?php echo preg_replace('/[\r|\n]/s', '', addslashes($_SESSION['multi_log'])) ?>');

$("#cnPMorto1").<?php echo !$batalha['ps1'] ? 'show();' : 'hide()' ?>;
$("#cnPMorto2").<?php echo !$batalha['ps2'] ? 'show();' : 'hide()' ?>;
$("#cnPMorto3").<?php echo !$batalha['ps3'] ? 'show();' : 'hide()' ?>;
$("#cnPMorto4").<?php echo !$batalha['ps4'] ? 'show();' : 'hide()' ?>;

<?php if($batalha['id_tipo'] == 2): ?>
	$("#cnEMorto1").<?php echo !$batalha['es1'] ? 'show();' : 'hide()' ?>;
	$("#cnEMorto2").<?php echo !$batalha['es2'] ? 'show();' : 'hide()' ?>;
	$("#cnEMorto3").<?php echo !$batalha['es3'] ? 'show();' : 'hide()' ?>;
	$("#cnEMorto4").<?php echo !$batalha['es4'] ? 'show();' : 'hide()' ?>;
<?php endif; ?>

<?php if($myTurn): ?>
_canAtk = true;

if(!_pvp_multi_npc_sound) {
	_pvp_multi_npc_sound	= true;
	
	<?php if(isset($_SESSION['usuario']['sound']) && $_SESSION['usuario']['sound']): ?>
		$(document.body).append('<audio autoplay><source src="<?php echo img('media/battle.wav') ?>" type="audio/wav" /></audio>');
	<?php endif; ?>
}
<?php $msg_my_turn = $batalha['id_tipo'] == 1 ? t('actions.a99') : t('actions.a100'); ?>
$('#cnAction').html('<?php echo $batalha['target'] != '' && $players[$batalha['target'] - 1] == $basePlayer->id ? t('actions.a101') : $msg_my_turn ?>');
	<?php if($batalha['target'] == 0): ?>
		$('.eFrame').css('opacity', 1);
	<?php else: ?>
		$('.eFrame').css('opacity', .4);
	<?php endif; ?>
<?php else: ?>
_canAtk 				= false;
_pvp_multi_npc_sound	= false;

$('#cnAction').html('<?php echo t('actions.a102')?>');
$('.eFrame').css('opacity', .4).css('background-image', 'none');
<?php endif; ?>

$('.pFrame').css('opacity', .4);
$('#cn<?php echo $index_char ?>Frame<?php echo $batalha['direction'] ? $batalha['current_id_e'] + 4 : $batalha['current_id_p'] ?>').css('opacity', 1);

<?php if($batalha['target'] != ""): ?>
_cTarget			= '<?php echo $batalha['target'] ?>';	
_cTargetClickable	= false;
<?php else: ?>
	<?php if($batalha['id_tipo'] == 1): ?>
_cTarget			= 1;
	<?php endif ?>
_cTargetClickable	= true;
<?php endif; ?>

<?php if($batalha['target'] != '' && $players[(int)$batalha['target'] - 1] == $basePlayer->id): // Frame no player q ataca ?>
$('#cnpFrame<?php echo $batalha['target'] ?>').css('opacity', 1);
<?php endif; ?>

<?php for($f = 1; $f <= 4; $f++): ?>
	<?php
		$player = $arPlayersInstances[$f];
	?>
	setPValue2(<?php echo $player->hp  <= 0 ? 1 : $player->hp ?>,  (<?php echo $player->max_hp ?> || 1),  "HP",  $("#cnPHP<?php  echo $f ?>"), 1);
	setPValue2(<?php echo $player->sp  <= 0 ? 1 : $player->sp ?>,  (<?php echo $player->max_sp ?> || 1),  "CHK", $("#cnPSP<?php  echo $f ?>"), 1);
	setPValue2(<?php echo $player->sta <= 0 ? 1 : $player->sta ?>, (<?php echo $player->max_sta ?> || 1), "STA", $("#cnPSTA<?php echo $f ?>"), 1);

<?php endfor; ?>

<?php $size = $batalha['id_tipo'] == 1 ? 1 : 4; ?>
<?php for($f = 1; $f <= $size; $f++): ?>
	<?php 
		$enemy = gzunserialize($batalha['npc' . $f]); // PVPCHANGE 
		$arEnemyInstances[$f] = $enemy;
	?>
	setPValue2(<?php echo $enemy->hp  <= 0 ? 1 : $enemy->hp ?>,  (<?php echo $enemy->max_hp ?>  || 1), "HP",  $("#cnEHP<?php  echo $f + 4 ?>"), 1);
	setPValue2(<?php echo $enemy->sp  <= 0 ? 1 : $enemy->sp ?>,  (<?php echo $enemy->max_sp ?>  || 1), "CHK", $("#cnESP<?php  echo $f + 4 ?>"), 1);
	setPValue2(<?php echo $enemy->sta <= 0 ? 1 : $enemy->sta ?>, (<?php echo $enemy->max_sta ?> || 1), "STA", $("#cnESTA<?php echo $f + 4 ?>"), 1);
<?php endfor; ?>

<?php
	// Status dos itens(turnos e afins(ou não tão afins… hmmm) =D)
	$items = $basePlayer->getItems(array(1, 2, 5, 6));
?>

<?php foreach($items as $item): ?>
	<?php
		$item->setPlayerInstance($basePlayer);
	?>
	_items['<?php echo $item->id ?>'].precf		= <?php echo $item->getAttribute('prec_fisico') ?>;
	_items['<?php echo $item->id ?>'].precm		= <?php echo $item->getAttribute('prec_magico') ?>;
	_items['<?php echo $item->id ?>'].pre		= <?php echo $item->getAttribute('precisao') ?>;
	<?php if($item->getAttribute('uso_unico')):	?>
	_items['<?php echo $item->id ?>'].t		= <?php echo $item->getAttribute('qtd') ?>;
	<?php endif; ?>
	<?php if($item->getAttribute('turnos')): ?>
	_items['<?php echo $item->id ?>'].trl	= <?php echo isset($turnos[$item->id]) ? $turnos[$item->id] : 0 ?>;
	<?php endif; ?>
<?php endforeach; ?>

p = {
	atkf:	<?php echo $basePlayer->getAttribute('atk_fisico_calc') ?>,
	atkm:	<?php echo $basePlayer->getAttribute('atk_magico_calc') ?>,
	def:	<?php echo $basePlayer->getAttribute('def_base_calc') ?>,
	deff:	<?php echo $basePlayer->getAttribute('def_fisico_calc') ?>,
	defm:	<?php echo $basePlayer->getAttribute('def_magico_calc') ?>,
	mhp:	<?php echo $basePlayer->getAttribute('max_hp') ?>,
	hp:		<?php echo $basePlayer->getAttribute('hp') ?>,
	msp:	<?php echo $basePlayer->getAttribute('max_sp') ?>,
	sp:		<?php echo $basePlayer->getAttribute('sp') ?>,
	msta:	<?php echo $basePlayer->getAttribute('max_sta') ?>,
	sta:	<?php echo $basePlayer->getAttribute('sta') ?>,
	cmin:	<?php echo $basePlayer->getAttribute('crit_min_calc') ?>,
	cmax:	<?php echo $basePlayer->getAttribute('crit_max_calc') ?>,
	ctotal:	<?php echo $basePlayer->getAttribute('crit_total_calc') ?>,
	emin:	<?php echo $basePlayer->getAttribute('esq_min_calc') ?>,
	emax:	<?php echo $basePlayer->getAttribute('esq_max_calc') ?>,
	etotal:	<?php echo $basePlayer->getAttribute('esq_total_calc') ?>,
	conc:	<?php echo $basePlayer->getAttribute('conc_calc') ?>,
	esquiva: <?php echo $basePlayer->getAttribute('esquiva_calc') ?>
}

<?php for($f = 0; $f <= 1; $f++): ?>
<?php for($i = 1; $i <= sizeof($f ? $arEnemyInstances : $arPlayersInstances); $i++): ?>
<?php
	$var		= $f ? 'e' . $i : 'p' . $i;
	$obj		= $f ? $arEnemyInstances[$i] : $arPlayersInstances[$i];
	$modifiers	= $obj->getModifiers();
	$elementos	= $obj->getElementosA();
	$conc_raw	= $obj->getAttribute('conc_calc');
	
	$obj->setLocalAttribute('less_conv', $f ? $conv_en : $conv_my);
	
	if($f) {
    $obj->parseModifiers();
	}
	
	$obj->atCalc();

	$conc_calc	= $obj->getAttribute('conc_calc');
?>
<?php echo $var ?>mod = [];

var <?php echo $var ?>resumo = 
	'<b class="azul"><?php echo t('formula.atk_fisico')?>:</b> <?php echo $obj->getAttribute('atk_fisico_calc') ?><br />' +
	'<b class="azul"><?php echo t('formula.atk_magico')?>:</b> <?php echo $obj->getAttribute('atk_magico_calc') ?><br />' +
	<?php /*'<b class="azul"><?php echo t('formula.def_base')?>:</b> <?php echo $obj->getAttribute('def_base_calc') ?><br />' +*/ ?>
	
	'<b class="azul"><?php echo t('formula.def_fisico')?>:</b> <?php echo $obj->getAttribute('def_fisico_calc') ?><br />' +
	'<b class="azul"><?php echo t('formula.def_magico')?>:</b> <?php echo $obj->getAttribute('def_magico_calc') ?><br />' +
	
	<?php /*'<b class="azul"><?php echo t('formula.prec_fisico')?>:</b> <?php echo sprintf("%1.2f", $obj->getAttribute('prec_fisico_calc')) ?><br />' +*/?>
	'<b class="azul"><?php echo t('formula.prec_magico')?>:</b> <?php echo sprintf("%1.2f", $obj->getAttribute('prec_magico_calc')) ?><br />' +
	<?php /*'<b class="azul"><?php echo t('formula.det')?>:</b> <?php echo $obj->getAttribute('det_calc') ?> %<br />' +*/?>
	'<b class="azul"><?php echo t('formula.conv')?>:</b> <?php echo $obj->getAttribute('conv_calc') ?> % (Time: <?php echo $f ? $conv_en : $conv_my ?> %)<br />' +
	
	'<b class="azul"><?php echo t('formula.esq')?>:</b> <?php echo sprintf("%1.2f",$obj->getAttribute('esq_calc')) ?> % ( <span class="color_green"><?php echo $f == 0 ? $conv_en + $obj->getAttribute('esq_calc')." %" : $conv_my + $obj->getAttribute('esq_calc')." %" ?></span> - <span class="color_red"><?php echo $f == 0 ? $conv_en. " %" : $conv_my." %" ?></span> ) <br />' +
	'<b class="azul"><?php echo t('formula.esq_total')?>:</b> <span><?php echo $obj->getAttribute('esq_total_calc')?>%</span><br />' +
	
	'<b class="azul"><?php echo t('formula.conc')?>:</b> <?php echo sprintf("%1.2f", $conc_calc) ?> % ( <span class="color_green"><?php echo $conc_raw ?></span> - <span class="color_red"><?php echo $f ? $conv_en : $conv_my ?></span> )<br />' +
	'<b class="azul"><?php echo t('formula.crit_total')?>:</b> <span><?php echo $obj->getAttribute('crit_total_calc')?>%</span><br />' +
	'<b class="azul"><?php echo t('formula2.esquiva')?>:</b> <?php echo $obj->getAttribute('esquiva_calc') ?>%';



<?php echo $var ?>mod.push({
	id:		'A',
	t:		0,
	i:		'layout<?php echo LAYOUT_TEMPLATE?>/stats.png',
	n:		'<?php echo t('jogador_vip.jv34')?>',
	d:		<?php echo $var ?>resumo,
	ub:		true
});

<?php if(sizeof($elementos)): ?>
	<?php echo $var ?>mod.push({
		id:		'B',
		t:		0,
		i:		'layout<?php echo LAYOUT_TEMPLATE?>/elements.png',
		n:		'<?php echo t('actions.a68')?>',
		d:		'<?php echo t('actions.a69')?>',
		ise:	true,
		ub:		true,
		els:	[
		<?php if(!$f || $f && $basePlayer->hasItemW(21365)): ?>
			<?php foreach($elementos as $elemento): ?>
			{
				n: '<?php echo $elemento['nome'] ?>',
				f: [
				<?php
					$fraquezas	= Recordset::query('SELECT a.nome FROM elemento a JOIN elemento_fraqueza b ON b.id_elemento_fraco=a.id WHERE b.id=' . $elemento['id'], true);
				?>
				<?php foreach($fraquezas->result_array() as $fraqueza): ?>
					'<?php echo $fraqueza['nome'] ?>'
				<?php endforeach; ?>
				],
				r: [
				<?php
					$resistencias	= Recordset::query('SELECT a.nome FROM elemento a JOIN elemento_resistencia b ON b.id_elemento_resiste=a.id WHERE b.id=' . $elemento['id'], true);
				?>
				<?php foreach($resistencias->result_array() as $resistencia): ?>
					'<?php echo $resistencia['nome'] ?>'
				<?php endforeach; ?>
				]
			},
			<?php endforeach; ?>
		<?php endif; ?>
		]
	});	
<?php endif; ?>

<?php if(sizeof($modifiers)): ?>
	<?php foreach($modifiers as $mod): ?>
	<?php 
		if($mod['o_direction'] != 2) {
			if($mod['direction'] != $mod['o_direction']) {
				continue;
			}
		}
	?>
	<?php echo $var ?>mod.push({
		<?php
			if(isset($mod['uid']) && $mod['uid']) {
				$item	= new Item($mod['id'], $mod['source_id']);
				$item->apply_enhancemnets();
			} else {
				$item	= new Item($mod['id']);
				$item->setLocalAttribute('level', $mod['level']);
				$item->level = $mod['level'];
				$item->parseLevel();
			}

			if($item->id_tipo == 16 || $item->id_tipo == 21) {
				$item_player	= Recordset::query('SELECT * FROM player_modificadores WHERE id_player=' . $mod['source_id'] . ' AND id_tipo='.$item->id_tipo);

				if($item_player->num_rows) {
					$item_player	= $item_player->row_array();

					foreach($item_player as $k => $v) {
						if($k == 'id_player' || $k == 'id_tipo') {
							continue;
						}

						if ($item->id_tipo == 21 && $k == 'ene') {
							$item->$k	+= !$v ? 0 : (($item->ordem - 1) * 2);
						}else{
							$item->$k	= $v;
							$item->$k	*= $item->ordem;
						}
					}
				}
			}
		
			$desc	= $item->getAttribute('descricao_'.Locale::get());

			if($mod['conv']) {
				$value	= preg_match('/\d+/', $desc, $matches);
				
				if(isset($matches[0])) {
					$desc	= str_replace($matches[0], $mod['det_inc'], $desc);
				}
			}
		?>
		id:	<?php echo $mod['id'] ?>,
		t:	<?php echo $mod['turns'] ?>,
		i:	'layout/<?php echo $mod['image'] ?>',
		n:	'<?php echo $item->getAttribute('nome') ?>',
		ub:	true,
		<?php if($mod['ord']): ?>
		st: true,
		mo: {
			p: true, 
			e: false,
			pm: {
			    self_nin: <?php echo $item->nin ?>,
				self_gen: <?php echo $item->gen ?>,
				self_agi: <?php echo $item->agi ?>,
				self_con: <?php echo $item->con ?>,
				self_forc: <?php echo $item->forc ?>,
				self_ene: <?php echo $item->ene ?>,
				self_inte: <?php echo $item->inte ?>,
				self_res: <?php echo $item->res ?>,
				self_atk_fisico: <?php echo $item->atk_fisico ?>,
				self_atk_magico: <?php echo $item->atk_magico ?>,
				self_def_base: <?php echo $item->def_base ?>,
				self_def_fisico: <?php echo $item->def_fisico ?>,
				self_def_magico: <?php echo $item->def_magico ?>,
				self_prec_fisico: <?php echo $item->prec_fisico ?>,
				self_prec_magico: <?php echo $item->prec_magico ?>,
				self_crit_min: <?php echo $item->crit_min ?>,
				self_crit_max: <?php echo $item->crit_max ?>,
				self_crit_total: <?php echo $item->crit_total ?>,
				self_esq_min: <?php echo $item->esq_min ?>,
				self_esq_max: <?php echo $item->esq_max ?>,
				self_esq_total: <?php echo $item->esq_total ?>,
				self_esq: <?php echo $item->esq ?>,
				self_det: <?php echo $item->det ?>,
				self_conv: <?php echo $item->conv ?>,
				self_conc: <?php echo $item->conc ?>,
			}
		},
		<?php elseif($item->sem_turno): ?>
		st:	true,
		<?php endif; ?>
		<?php if($mod['conv']): ?>
		ise: true,
		d:	'<?php echo $desc ?>',
		<?php else: ?>
		<?php endif; ?>
		<?php if($item->getAttribute('sem_turno') && $item->hasModifiers()): ?>
		<?php
			$modifiers	= $item->getModifiers();
			
			$has_mod_p	= $modifiers['self_ken']		|| $modifiers['self_tai']  		|| $modifiers['self_nin']			|| $modifiers['self_gen']			|| $modifiers['self_agi']			|| $modifiers['self_con'] 	||
						  $modifiers['self_ene']   		|| $modifiers['self_forc']		|| $modifiers['self_inte']			|| $modifiers['self_res']			|| $modifiers['self_atk_fisico']	|| 
						  $modifiers['self_atk_magico']	|| $modifiers['self_def_base']	|| $modifiers['self_prec_fisico']	|| $modifiers['self_prec_magico']	|| $modifiers['self_crit_min']		||
						  $modifiers['self_crit_max']	|| $modifiers['self_crit_total'] || $modifiers['self_esq_min']   	|| $modifiers['self_esq_max'] 		|| $modifiers['self_esq_total']		|| $modifiers['self_esq']	|| $modifiers['self_det']			||
						  $modifiers['self_conv']		|| $modifiers['self_conc']		|| $modifiers['self_def_fisico']	|| $modifiers['self_def_magico'];

			$has_mod_e	= $modifiers['target_ken']			|| $modifiers['target_tai']  		|| $modifiers['target_nin']			|| $modifiers['target_gen']			|| $modifiers['target_agi']			|| $modifiers['target_con'] ||
						  $modifiers['target_ene']   		|| $modifiers['target_forc']		|| $modifiers['target_inte']		|| $modifiers['target_res']			|| $modifiers['target_atk_fisico']	|| 
						  $modifiers['target_atk_magico']	|| $modifiers['target_def_base']	|| $modifiers['target_prec_fisico']	|| $modifiers['target_prec_magico']	|| $modifiers['target_crit_min']	||
						  $modifiers['target_crit_max']		|| $modifiers['target_crit_total']	|| $modifiers['target_esq_min']		|| $modifiers['target_esq_max']		|| $modifiers['target_esq_total']		|| $modifiers['target_esq']			|| $modifiers['target_det']			||
						  $modifiers['target_conv']			|| $modifiers['target_conc']		|| $modifiers['target_def_fisico']	|| $modifiers['target_def_magico'];
		?>
		mo:	{
			p: <?php echo $has_mod_p ? 'true' : 'false' ?>,
			e: <?php echo $has_mod_e ? 'true' : 'false' ?>,
			<?php for($g = 0; $g <= 1; $g++): ?>
				<?php
					if($g && !$has_mod_e || !$g && !$has_mod_p) {
						continue;
					}
				?>
				<?php echo $g ? 'em' : 'pm' ?>:	{
					<?php foreach($modifiers as $k => $v): ?>
					<?php if(strpos($k, !$g ? 'self_' : 'target_') === false) continue; ?>
					<?php echo $k ?>: <?php echo (int)$v ?>,
					<?php endforeach; ?>
				}
			<?php endfor; ?>
		}
		<?php endif; ?>
	});
	<?php endforeach; ?>
<?php endif; ?>

<?php endfor; ?>
<?php endfor; ?>


<?php if($batalha['finished']): ?>
	clearInterval(_pvpTimer);
	_canAtk = false;

	<?php if($batalha['finished_direction'] == 0): ?>
	<?php
		// Boinificação
		$evento = new Recordset('SELECT * FROM evento4 WHERE id=' . $equipe['id_evento4b'], true);
		$evento = $evento->row_array();

		if($basePlayer->dono_equipe) {
			$players = Recordset::query('SELECT id FROM player WHERE id_equipe=' . $basePlayer->id_equipe);
			
			foreach($players->result_array() as $player) {
				// Verifica para não dar pontos
				if(!Recordset::query('SELECT id_player FROM player_evento4 WHERE id_player=' . $player['id'] . ' AND id_evento4=' . $evento['id'])->num_rows) {
					Recordset::query('UPDATE player SET ryou=ryou+' . $evento['ryou'] . ', exp=exp+' . $evento['exp'] . ', treino_total=treino_total+' . $evento['treino'] . ' WHERE id=' . $player['id']);

					mensageiro(NULL, $player['id'], t('actions.a103'), t('actions.a104') .' '. $equipe['nome'] .' '. t('actions.a105') .' '. $evento['nome_'. Locale::get()] . ' !<br /> '. t('actions.a106') .' '. $evento['ryou'] . ' Ryous, ' .' '. $evento['exp'] .' '. t('actions.a42') . ' ' .t('actions.a83'). ' ' . $evento['treino'] .' '. t('actions.a107'), 'team');

					// Recompensa
					Recordset::insert('player_recompensa_log', array(
						'fonte'			=> 'equipe_npc',
						'id_player'		=> $player['id'],
						'recebido'		=> 1,
						'exp'			=> (int)$evento['exp'],
						'ryou'			=> (int)$evento['ryou'],
						'treino_total'	=> (int)$evento['treino']
					));					
				}				

				Recordset::query('INSERT INTO player_evento4(id_equipe, id_player, id_evento4) VALUES(' . $basePlayer->id_equipe . ', ' . $player['id'] . ', ' . $evento['id'] . ')');
			}
			
			Recordset::query('UPDATE equipe SET vitoria=vitoria+1 WHERE id=' . $basePlayer->id_equipe); //id_evento4=0,
			Recordset::query('INSERT INTO equipe_evento4(id_equipe, id_evento4) VALUES(' . $equipe['id']. ', ' . $evento['id'] . ')');
		}
		
		$basePlayer->id_evento4 = $evento['id'];		
		arch_parse(NG_ARCH_NPC_EVENTO4, $basePlayer, NULL, new stdClass(), 1);

		equipe_exp(50);	
		
		//if(!$_SESSION['universal']) {
			Recordset::query('UPDATE player SET id_batalha_multi=0 WHERE id=' . $basePlayer->id);
			
			Recordset::update('equipe', array(
				'id_evento4'	=> 0
			), array(
				'id'			=> $basePlayer->id_equipe
			));
		//}
		
	?>
	$('#cnVitoria').show();
	<?php else: ?>
	<?php
		if($basePlayer->dono_equipe) {
			Recordset::query('UPDATE equipe SET id_evento4=0,derrota=derrota+1 WHERE id=' . $basePlayer->id_equipe);
		}
		
		Recordset::query('UPDATE player SET hospital=\'1\', id_batalha_multi=0 WHERE id=' . $basePlayer->id);
	?>
	$('#cnDerrota').show();	
	<?php endif; ?>

	<?php die() ?>
<?php endif; ?>
