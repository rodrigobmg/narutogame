<?php
	header('Content-Type: application/json');

	if(isset($_GET['a'])) {
		$_POST	= $_GET;
	}

	if(!$basePlayer->id_batalha_multi_pvp) {
		return;
	}
	
	function _do_status_update(&$players) {
		$conv_p		= 0;
		$conv_e		= 0;
		$instances	= array();

		foreach(odds() as $_ => $odd) {
			$instances[$odd]	= new Player($players[$odd]->id);
			$conv_p				+= $instances[$odd]->getAttribute('conv_calc');
		}
		
		foreach(evens() as $_ => $even) {
			$instances[$even]	= new Player($players[$even]->id);
			$conv_e				+= $instances[$even]->getAttribute('conv_calc');
		}

		$conv_e	= $conv_e / ((defined('PVPT_MAX_TURNS_OVERRIDE') ? PVPT_MAX_TURNS_OVERRIDE : PVPT_MAX_TURNS) / 2);
		$conv_p	= $conv_p / ((defined('PVPT_MAX_TURNS_OVERRIDE') ? PVPT_MAX_TURNS_OVERRIDE : PVPT_MAX_TURNS) / 2);

		foreach(odds() as $_ => $odd) {
			$instance						= $instances[$odd];
			$players[$odd]->crits->original	= $instance->getAttribute('conc_calc');
			$players[$odd]->esqs->original	= $instance->getAttribute('esq_calc');
			$players[$odd]->crits_esqs_red	= $conv_e;

			$instance->setLocalAttribute('less_conv', $conv_e);
			$instance->atCalc();

			$players[$odd]->conv_team		= $conv_p;
			
			$players[$odd]->crits->min		= $instance->getAttribute('crit_min_calc');
			$players[$odd]->crits->max		= $instance->getAttribute('crit_max_calc');
			$players[$odd]->crits->max		= $instance->getAttribute('crit_total_calc');
			$players[$odd]->crits->current	= $instance->getAttribute('conc_calc');
			$players[$odd]->crits->total	= $instance->getAttribute('max_crit_hits');

			$players[$odd]->esqs->min		= $instance->getAttribute('esq_min_calc');
			$players[$odd]->esqs->min		= $instance->getAttribute('esq_max_calc');
			$players[$odd]->esqs->min		= $instance->getAttribute('esq_total_calc');
			$players[$odd]->esqs->current	= $instance->getAttribute('esq_calc');			
			$players[$odd]->esqs->total		= $instance->getAttribute('max_esq_hits');
		}

		foreach(evens() as $_ => $even) {
			$instance							= $instances[$even];
			$players[$even]->crits->original	= $instance->getAttribute('conc_calc');
			$players[$even]->esqs->original		= $instance->getAttribute('esq_calc');
			$players[$even]->crits_esqs_red		= $conv_p;

			$instance->setLocalAttribute('less_conv', $conv_p);
			$instance->atCalc();
			
			$players[$even]->conv_team		= $conv_e;
			
			$players[$even]->crits->min		= $instance->getAttribute('crit_min_calc');
			$players[$even]->crits->max		= $instance->getAttribute('crit_max_calc');
			$players[$even]->crits->total	= $instance->getAttribute('crit_total_calc');
			$players[$even]->crits->current	= $instance->getAttribute('conc_calc');
			$players[$even]->crits->total	= $instance->getAttribute('max_crit_hits');

			$players[$even]->esqs->min		= $instance->getAttribute('esq_min_calc');
			$players[$even]->esqs->min		= $instance->getAttribute('esq_max_calc');
			$players[$even]->esqs->total	= $instance->getAttribute('esq_total_calc');
			$players[$even]->esqs->current	= $instance->getAttribute('esq_calc');		
			$players[$even]->esqs->total	= $instance->getAttribute('max_esq_hits');		
		}
		
		foreach($instances as $_ => $player) {
			$players[$_]->mods			= array();
			$players[$_]->esqs->used	= Fight::getUsedEsqs($player, $player->id_batalha_multi_pvp, false, true);
			$players[$_]->crits->used	= Fight::getUsedCrits($player, $player->id_batalha_multi_pvp, false, true);

			$players[$_]->atks->f		= $player->getAttribute('atk_fisico_calc');
			$players[$_]->atks->m		= $player->getAttribute('atk_magico_calc');
			$players[$_]->esquiva		= $player->getAttribute('esquiva_calc');
			
			$players[$_]->precs->f		= $player->getAttribute('prec_fisico_calc');
			$players[$_]->precs->m		= $player->getAttribute('prec_magico_calc');
			$players[$_]->def			= $player->getAttribute('def_base_calc');
			$players[$_]->deff			= $player->getAttribute('def_fisico_calc');
			$players[$_]->defm			= $player->getAttribute('def_magico_calc');

			// REMOVE
			$players[$_]->hp->max		= $player->max_hp;
			$players[$_]->hp->current	= $player->hp;
			$players[$_]->sp->max		= $player->max_sp;
			$players[$_]->sp->current	= $player->sp;
			$players[$_]->sta->max		= $player->max_sta;
			$players[$_]->sta->current	= $player->sta;									
		
			foreach($player->getModifiers() as $modifier) {
				if($modifier['direction'] != $modifier['o_direction']) { // Doesn't show buffs the were applied to an enemy
					continue;
				}

				$mod			= new stdClass();
				$item			= Recordset::query('SELECT * FROM item WHERE id=' . $modifier['id'], true)->row_array();
				$mod->name		= $item['nome_' . Locale::get()];
				$mod->type		= $item['id_tipo'];
				$mod->id		= $modifier['id'];
				$mod->mo		= new stdClass();
				$mod->mo->p		= false;
				$mod->mo->e		= false;
				$mod->parents	= array();

				if(isset($modifier['uid']) && $modifier['uid']) {
					$item_instance	= new Item($modifier['id'], $modifier['source_id']);
					$item_instance->apply_enhancemnets();
				} else {				
					$item_instance	= new Item($modifier['id']);
					$item_instance->level = $modifier['level'];
					$item_instance->setLocalAttribute('level', $modifier['level']);
					$item_instance->parseLevel();
				}

				$mod->img		= img(in_array($item_instance->id_tipo, [23, 39]) ? 'layout/bijuus-batalha/' . $item_instance->id . '.png' : 'layout/' . $item_instance->imagem);
				
				if((isset($modifier['conv']) && $modifier['conv']) || $modifier['ord']) { // Conviction & Clan
					$mod->turns			= 'infinity';
					$mod->multiplier	= $modifier['ord'] ? 'clan' : 'percent';
					
					if(in_array($item_instance->id_tipo, [23, 39])) {
						$mod->multiplier	= 'percent';
					}
					
					if($modifier['ord']) {
						$where		= '';
						
						if($item_instance->id_tipo == 16) {
							$where	= ' AND id_cla=' . $item_instance->id_cla;
						}

						if($item_instance->id_tipo == 21) {
							$where	= ' AND id_invocacao=' . $item_instance->id_invocacao;
						}

						if($item_instance->id_tipo == 20) {
							$where	= ' AND id_selo=' . $item_instance->id_selo;
						}
						
						$parents	= Recordset::query('SELECT id FROM item WHERE 1=1 ' . $where . ' AND id_tipo=' . $item_instance->id_tipo . ' AND ordem <=' . $item_instance->ordem, true);
						
						foreach($parents->result_array() as $parent) {
							$mod->parents[]	= $parent['id'];
						}
					}
					
					if($modifier['conv']) {
						$bonuses	= array(
							'ken','tai', 'nin', 'gen', 'agi', 'ene', 'con', 'inte',
							'forc', 'res', 'atk_fisico', 'atk_magico', 'def_base', 'def_fisico', 'def_magico',
							'prec_fisico', 'prec_magico', 'crit_min', 'crit_max', 'crit_total','esq_min',
							'esq_max', 'esq_total','esq', 'det', 'conv','esquiva', 'conc', 'bonus_hp', 'bonus_sp', 'bonus_sta'
						);
						
						foreach($bonuses as $bonus) {
							if($item_instance->$bonus > 0) {
								$item_instance->$bonus	= $modifier['det_inc'];
							}
						}
					} elseif($item_instance->id_tipo == 16 || $item_instance->id_tipo == 21) { // Clan defined attributes
						$item_player	= Recordset::query('SELECT * FROM player_modificadores WHERE id_player=' . $modifier['source_id'] . ' AND id_tipo='.$item_instance->id_tipo);

						if($item_player->num_rows) {
							$item_player	= $item_player->row_array();

							foreach($item_player as $k => $v) {
								if($k == 'id_player' || $k == 'id_tipo') {
									continue;
								}

								if ($item_instance->id_tipo == 21 && $k == 'ene') {
									$item_instance->$k	+= !$v ? 0 : (($item_instance->ordem - 1) * 2);
								} else {
									$item_instance->$k	= $v;
									$item_instance->$k	*= $item_instance->ordem;
								}
							}
						}						
					}
					
					$mod->mo->p					= new stdClass();
				    $mod->mo->p->ken			= (float)$item_instance->ken;
					$mod->mo->p->tai			= (float)$item_instance->tai;
					$mod->mo->p->nin			= (float)$item_instance->nin;
					$mod->mo->p->gen			= (float)$item_instance->gen;
					$mod->mo->p->agi			= (float)$item_instance->agi;
					$mod->mo->p->con			= (float)$item_instance->con;
					$mod->mo->p->forc			= (float)$item_instance->forc;
					$mod->mo->p->ene			= (float)$item_instance->ene;
					$mod->mo->p->inte			= (float)$item_instance->inte;
					$mod->mo->p->res			= (float)$item_instance->res;
					$mod->mo->p->atk_fisico		= (float)$item_instance->atk_fisico;
					$mod->mo->p->atk_magico		= (float)$item_instance->atk_magico;
					$mod->mo->p->def_base		= (float)$item_instance->def_base;
					$mod->mo->p->def_fisico		= (float)$item_instance->def_fisico;
					$mod->mo->p->def_magico		= (float)$item_instance->def_magico;
					$mod->mo->p->prec_fisico	= (float)$item_instance->prec_fisico;
					$mod->mo->p->prec_magico	= (float)$item_instance->prec_magico;
					$mod->mo->p->crit_min		= (float)$item_instance->crit_min;
					$mod->mo->p->crit_max		= (float)$item_instance->crit_max;
					$mod->mo->p->crit_total		= (float)$item_instance->crit_total;
					$mod->mo->p->esq_min		= (float)$item_instance->esq_min;
					$mod->mo->p->esq_max		= (float)$item_instance->esq_max;
					$mod->mo->p->esq_total		= (float)$item_instance->esq_total;
					$mod->mo->p->esq			= (float)$item_instance->esq;
					$mod->mo->p->det			= (float)$item_instance->det;
					$mod->mo->p->conv			= (float)$item_instance->conv;
					$mod->mo->p->esquiva		= (float)$item_instance->esquiva;
					$mod->mo->p->conc			= (float)$item_instance->conc;
					$mod->mo->p->bonus_hp		= (float)$item_instance->bonus_hp;
					$mod->mo->p->bonus_sp		= (float)$item_instance->bonus_sp;
					$mod->mo->p->bonus_sta		= (float)$item_instance->bonus_sta;
				} elseif($item_instance->sem_turno && $item_instance->hasModifiers()) { // Normal
					$mod->turns			= $modifier['turns'];
					$mod->multiplier	= !$item_instance->tipo_bonus ? "percent" : "fixed";

					$modifiers	= $item_instance->getModifiers();
					
					$has_mod_p	= $modifiers['self_ken']		||$modifiers['self_tai']  		|| $modifiers['self_nin']			|| $modifiers['self_gen']			|| $modifiers['self_agi']			|| $modifiers['self_con'] ||
								  $modifiers['self_ene']   		|| $modifiers['self_forc']		|| $modifiers['self_inte']			|| $modifiers['self_res']			|| $modifiers['self_atk_fisico'] || 
								  $modifiers['self_atk_magico']	|| $modifiers['self_def_base']	|| $modifiers['self_prec_fisico']	|| $modifiers['self_prec_magico']	|| $modifiers['self_crit_min'] ||
								  $modifiers['self_crit_max']	||  $modifiers['self_crit_total']	|| $modifiers['self_esq_min']  || $modifiers['self_esq_max'] 		|| $modifiers['self_esq_total']		|| $modifiers['self_esq']			|| $modifiers['self_det']	||
								  $modifiers['self_conv']	|| $modifiers['self_esquiva']	|| $modifiers['self_conc']		|| $modifiers['self_def_fisico']	|| $modifiers['self_def_magico'];
		
					$has_mod_e	= $modifiers['target_ken']  		||$modifiers['target_tai']  		|| $modifiers['target_nin']			|| $modifiers['target_gen']			|| $modifiers['target_agi']			|| $modifiers['target_con'] ||
								  $modifiers['target_ene']   		|| $modifiers['target_forc']		|| $modifiers['target_inte']		|| $modifiers['target_res']			|| $modifiers['target_atk_fisico'] || 
								  $modifiers['target_atk_magico']	|| $modifiers['target_def_base']	|| $modifiers['target_prec_fisico']	|| $modifiers['target_prec_magico']	|| $modifiers['target_crit_min'] ||
								  $modifiers['target_crit_max']		|| $modifiers['target_crit_total']	|| $modifiers['target_esq_min']		|| $modifiers['target_esq_max']		|| $modifiers['target_esq_total']  || $modifiers['target_esq']			|| $modifiers['target_det']	||
								  $modifiers['target_esquiva']			|| $modifiers['target_conv']			|| $modifiers['target_conc']		|| $modifiers['target_def_fisico']	|| $modifiers['target_def_magico'];
					
					if($has_mod_e || $has_mod_p) {
						for($g = 0; $g <= 1; $g++) {
							if($g && !$has_mod_e || !$g && !$has_mod_p) {
								continue;
							}
							
							$delta	= new stdClass();
							
							foreach($modifiers as $__ => $v) {
								if(strpos($__, !$g ? 'self_' : 'target_') === false) {
									continue;
								}
								
								$property			= preg_replace('/target_|self_/i', '', $__);
								$delta->$property	= (int)$v;
							}
							
							if($g) {
								$mod->mo->e	= $delta;
							} else {
								$mod->mo->p	= $delta;
							}
						}						
					}
				} else {
					continue;
				}
				
				$players[$_]->mods[]	= $mod;
			}			
		}
	}
	
	function _update_conviction_of(&$instance, $players, $battle) {
		$instance->setLocalAttribute('less_conv', $instance->id_equipe == $battle['id_equipe_a'] ? $players[2]->conv_team : $players[1]->conv_team);
		$instance->atCalc();		
	}
	
	$json					= new stdClass();
	$json->finished			= false;
	$json->players			= array();
	$json->invalid_action	= false;
	$json->invalid_key		= false;
	$json->not_my_action	= false;
	$json->hide_panel		= false;
	$json->invalid_item		= false;
	$json->messages			= array();
	
	$battle					= Recordset::query('SELECT * FROM batalha_multi_pvp WHERE id=' . $basePlayer->id_batalha_multi_pvp)->row_array();
	$players				= array();
	$players_by_order		= array();
	$battle_key				= 'PVP_MULTI_FIGHT_' . $basePlayer->id_batalha_multi_pvp;
	$item_turn_key			= 'PVP_MULTI_TRL_' . $basePlayer->id . '_' . $battle['id'];
	$turns					= SharedStore::G($item_turn_key, array());
	
	if($basePlayer->id_random_queue) {
		$pvp_size	= 0;
	
		for($f = 0; $f <= 1; $f++) {
			$key	= $f ? 'e' : 'p';
			
			for($i = 1; $i <= 4; $i++) {
				if($battle[$key . $i]) {
					$pvp_size++;
					
					$object	= unserialize($battle[$key . $i]);
					
					if($object->id == $basePlayer->id) {
						if($f == 0) {
							$basePlayer->id_equipe	= -1;
						} else {
							$basePlayer->id_equipe	= -2;							
						}
					}
				}
			}
		}

		define('PVPT_MAX_TURNS_OVERRIDE', $pvp_size);
	}

	$counter_e	= 2;
	$counter_p	= 1;
	
	
	for($f = 0; $f <= 1; $f++) {
		$key	= $f ? 'e' : 'p';
		
		for($i = 1; $i <= (defined('PVPT_MAX_TURNS_OVERRIDE') ? PVPT_MAX_TURNS_OVERRIDE : PVPT_MAX_TURNS) / 2; $i++) {
			$object		= unserialize($battle[$key . $i]);
			
			if(!$f) {
				$counter	= $counter_p;
				$counter_p	+= 2;
			} else {
				$counter	= $counter_e;
				$counter_e	+= 2;
			}
			
			$players[$object->id]			= $object;
			$players_by_order[$counter]		=& $players[$object->id];
		}
	}
	
	if($battle['target']) {
		$who_should_attack			= $players_by_order[$battle['target']];
		
		$json->who_is_attacking		= $players_by_order[$battle['current']];
		$json->who_is_attacked		= $players_by_order[$battle['target']];
	} else {
		$who_should_attack			= $players_by_order[$battle['current']];
		
		$json->who_is_attacking		= $players_by_order[$battle['current']];
		$json->who_is_attacked		= NULL;
	}

	$is_my_action			= $who_should_attack->id == $basePlayer->id;
	$ignore_turn_rotation	= false;
	$json->hide_panel		= !$is_my_action;
	$json->turns			= $turns;

	_update_conviction_of($basePlayer, $players_by_order, $battle);
	//_do_status_update($players_by_order); // UNCOMMENT

	if(!isset($_POST['key']) || (isset($_POST['key']) && $_POST['key'] != $_SESSION['multi_pvp_key'])) {
		$json->invalid_key	= true;
	} else {
		if($battle['finished']) {
			$exp			= 0;
			$ryou			= 0;
			$update_extra	= array();
			
			$log	= array(
				'id_equipe'				=> $battle['id_equipe_a'],
				'id_equipe_b'			=> $battle['id_equipe_b'],
				'id_batalha_multi_pvp'	=> $battle['id']
			);

			if($basePlayer->id_random_queue) {
				$rnd_count	= Recordset::query('SELECT COUNT(id) AS total FROM player_batalhas_rnd WHERE id_player=' . $basePlayer->id)->row()->total;
			}

			if($battle['winner']) { # Victory
				$lost	= $battle['winner'] == $battle['id_equipe_a'] ? $battle['id_equipe_b'] : $battle['id_equipe_a'];
			
				if($battle['winner'] == $basePlayer->id_equipe) {
					$log['vitoria']		= $battle['winner'];

					guild_objetivo_exp($basePlayer, 11);
					vila_objetivo_exp($basePlayer, 11);
					
					if($basePlayer->id_random_queue) {
						$range				= $battle['range_' . ($battle['id_equipe_a'] == $battle['winner'] ? 'b' : 'a')];
						$exp				= Recordset::query('SELECT exp_mapa_pvp AS exp  FROM level_exp WHERE id=' . $range, true)->row()->exp;
						$ryou				= Recordset::query('SELECT ryou  FROM level_exp WHERE id=' 	. $range, true)->row()->ryou;
						
						// Adicionado para ajudar no 4x4 random
						$exp = $exp * 2;
						$ryou = $ryou * 4;
						
						$json->finished		= msg(6, t('random.win.title'), sprintf(t('random.win.msg'), $ryou, $exp), true);
						
						
						
						$update_extra['vitorias_rnd']	= array('escape' => false, 'value' => 'vitorias_rnd + 1');							
						
						
						Recordset::insert('player_batalhas_rnd', array(
							'id_player'	=> $basePlayer->id
						));
						// Missões diárias de batalha 4x4
						if($basePlayer->hasMissaoDiariaPlayer(22)->total){
							// Adiciona os contadores nos torneios npc
							Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
										 WHERE id_player = ". $basePlayer->id." 
										 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 22) 
										 AND completo = 0 ");
						}

					} else {
						$exp			= $battle['range_' . ($battle['id_equipe_a'] == $battle['winner'] ? 'b' : 'a')] * 20;
						$ryou			= $battle['range_' . ($battle['id_equipe_a'] == $battle['winner'] ? 'b' : 'a')] * 5;
						
						$json->finished	= msg(6, t('equipe.pvp.finished.title_win'), sprintf(t('equipe.pvp.finished.win_msg'), $ryou, $exp), true);
						
						arch_parse(NG_ARCH_4XPVP, null, $lost);						
					}
				} else {
					if($basePlayer->id_random_queue) {
						$json->finished					= msg(5, t('random.loss.title'), t('random.loss.msg'), true);

						$update_extra['derrotas_rnd']	= array('escape' => false, 'value' => 'derrotas_rnd + 1');
						
					} else {
						$json->finished		= msg(5, t('equipe.pvp.finished.title_loss'), t('equipe.pvp.finished.loss_msg'), true);
					}
				}
			} else { # Draw
				if($basePlayer->id_random_queue) {
					$json->finished					= msg(1, t('random.tied.title'), t('random.tied.msg'), true);

					$update_extra['empates_rnd']	= array('escape' => false, 'value' => 'empates_rnd + 1');
					
				} else {
					$json->finished		= msg(6, t('equipe.pvp.finished.title_draw'), t('equipe.pvp.finished.draw_msg'), true);
				}
			}

			$update	= array(
				'exp'					=> array('escape' => false, 'value' => 'exp  + ' . $exp),
				'ryou'					=> array('escape' => false, 'value' => 'ryou + ' . $ryou),
				'id_batalha_multi_pvp'	=> 0,
				'id_random_queue'		=> 0,
				'hospital'				=> $players[$basePlayer->id]->alive ? 0 : 1
			);

			Recordset::update('player', array_merge($update, $update_extra), array(
				'id'	=> $basePlayer->id
			));
			
			// Recompensa log
			Recordset::insert('player_recompensa_log', array(
				'id_player'	=> $basePlayer->id,
				'fonte'		=> $basePlayer->id_random_queue ? 'random' : 'equipe_pvp',
				'ryou'		=> $ryou,
				'exp'		=> $exp,
				'recebido'	=> 1
			));
			
			if(!Recordset::query('SELECT id FROM equipe_pvp_log WHERE id_batalha_multi_pvp=' . $battle['id'])->num_rows) {
				Recordset::insert('equipe_pvp_log', $log);

				if($battle['winner']) {
					Recordset::update('equipe', array(
						'vitoria_pvp'	=> array('escape' => false, 'value' => 'vitoria_pvp + 1')
					), array(
						'id'			=> $battle['winner']
					));

					Recordset::update('equipe', array(
						'derrota_pvp'	=> array('escape' => false, 'value' => 'derrota_pvp + 1')
					), array(
						'id'			=> $battle['winner'] == $battle['id_equipe_a'] ? $battle['id_equipe_b'] : $battle['id_equipe_a']
					));
				} else {
					Recordset::update('equipe', array(
						'empate_pvp'	=> array('escape' => false, 'value' => 'empate_pvp + 1')
					), array(
						'id'			=> array('escape' => false, 'mode' => 'in', 'value' => $battle['id_equipe_a'] . ', ' . $battle['id_equipe_b'])
					));
				}
			}
		}
	
		if(isset($_POST['action'])) {
			$action_was_valid	= false;
			$update				= array();
			
			if($is_my_action) {
				if(!isset($_POST['item']) || (isset($_POST['item']) && !$basePlayer->hasItem($_POST['item']))) {
					$json->invalid_item	= true;
				} else {
					$item = $basePlayer->getItem($_POST['item']);
					$item->setPlayerInstance($basePlayer);
					$item->parseLevel();
					$item->apply_team_modifiers();
	
					// Verifica se tem stats para usar --->
						if($_POST['action'] != 5) { // Not Kinjutsus
							$consume_hp		= $item->consume_hp;
							$consume_sp		= $item->consume_sp;
							$consume_sta	= $item->consume_sta;
						} else {
							$consume_hp		= percent($item->consume_hp,	$basePlayer->max_hp);
							$consume_sp		= percent($item->consume_sp,	$basePlayer->max_sp);
							$consume_sta	= percent($item->consume_sta,	$basePlayer->max_sta);							
						}

						if($consume_hp > $basePlayer->getAttribute('hp')) {
							$json->messages[]	= t('actions.a265');
						}

						if($consume_sp > $basePlayer->getAttribute('sp')) {
							$json->messages[]	= t('actions.a63');
						}
			
						if($consume_sta > $basePlayer->getAttribute('sta')) {
							$json->messages[]	= t('actions.a64');
						}
					// <---

					// Item turn control + item type validation --->
						if(in_array($_POST['action'], array(1, 3, 4))) { // Normal, buff, medicinal attack
							if($item->getAttribute('turnos') && isset($turns[$item->id])) {
								$json->messages[]	= t('actions.a65');
							}
						}						

						if(
							($_POST['action'] == 1 && !in_array($item->id_tipo, array(2, 5, 6))) || ($_POST['action'] == 4 && $item->id_tipo != 24) ||
							($_POST['action'] == 2 && !in_array($item->id_tipo, array(1, 5, 39))) || ($_POST['action'] == 3 && !in_array($item->id_tipo, array(16, 17, 20, 21, 23, 26, 39))) ||
							($_POST['action'] == 5 && $item->id_tipo != 37)
						) {
							$json->messages[]	= t('actions.a92');
						}
					// <---
					
					if($_POST['action'] != 5) { // Not Kinjutsus
						if(!$battle['target']) { // Defines if the selected target is valid depending on the attack type
							if(in_array($_POST['action'], array(1, 2, 3))) {
								$allowed_condition	= $battle['current'] % 2;
							} else {
								$allowed_condition	= !($battle['current'] % 2);
							}
						
							$allowed_ids	= [];
							
							for($f = 1; $f <= (defined('PVPT_MAX_TURNS_OVERRIDE') ? PVPT_MAX_TURNS_OVERRIDE : PVPT_MAX_TURNS) / 2; $f++) {
								if($allowed_condition) { // Pair are E
									$counter	= $f * 2;
								} else { // Odds are P
									$counter	= ($f == 1 ? 1 : ($f * 2) - 1);
								}
	
								if($players_by_order[$counter]->alive) {
									$allowed_ids[]	= $counter;
								}
							}
							
							if(!isset($_POST['target']) || (isset($_POST['target']) && !is_numeric($_POST['target'])) || (isset($_POST['target']) && !in_array($_POST['target'], $allowed_ids))) {
								$json->messages[]	= 'Jogador selecionado inválido para essa ação';
							}
						}						
					}
					
					if(!sizeof($json->messages)) { // No fucking error returned
						if($item->getAttribute('turnos')) {
							$turns[$item->id]	= (int)$item->getAttribute('turnos');							
						}

						if($_POST['action'] == 1) { // Normal attack
							$action_was_valid	= true;
						
							if($battle['target']) {
								$fight		= SharedStore::G($battle_key);
								$baseEnemy	= new Player($players_by_order[$battle['current']]->id);

								_update_conviction_of($baseEnemy, $players_by_order, $battle);

								if(!$fight->_player->getAtkItem()->flight) {
									$item2 = $baseEnemy->getItem($fight->_player->getAtkItem()->id);
									$item2->setPlayerInstance($baseEnemy);
									$item2->parseLevel();
									
									$baseEnemy->setAtkItem($item2);
									$fight->addPlayer($baseEnemy);
								}

								$basePlayer->setAtkItem($item);
								$fight->addEnemy($basePlayer);
								$fight->Process();
				
								$basePlayer->rotateModifiers();
								$baseEnemy->rotateModifiers();
								
								$players[$basePlayer->id]->hp->max			= $basePlayer->max_hp;
								$players[$basePlayer->id]->hp->current		= $basePlayer->hp;
								$players[$basePlayer->id]->sp->max			= $basePlayer->max_sp;
								$players[$basePlayer->id]->sp->current		= $basePlayer->sp;
								$players[$basePlayer->id]->sta->max			= $basePlayer->max_sta;
								$players[$basePlayer->id]->sta->current		= $basePlayer->sta;

								if($basePlayer->sp <= 10 || $basePlayer->hp <= 10 || $basePlayer->sta <= 10) {
									$players[$basePlayer->id]->alive	= false;
								}

								$players[$baseEnemy->id]->hp->max			= $baseEnemy->max_hp;
								$players[$baseEnemy->id]->hp->current		= $baseEnemy->hp;
								$players[$baseEnemy->id]->sp->max			= $baseEnemy->max_sp;
								$players[$baseEnemy->id]->sp->current		= $baseEnemy->sp;
								$players[$baseEnemy->id]->sta->max			= $baseEnemy->max_sta;
								$players[$baseEnemy->id]->sta->current		= $baseEnemy->sta;

								if($baseEnemy->sp <= 10 || $baseEnemy->hp <= 10 || $baseEnemy->sta <= 10) {
									$players[$baseEnemy->id]->alive	= false;
								}
								
								$result			= pvp_do_turn_rotation($battle, $players_by_order);
								$update['log']	= $battle['log'] . $fight->log;
								$battle['log']	= $update['log'];
								
								if($result['finished']) {
									$update['winner']	= $result['winner'];
									$update['finished']	= 1;
								}

								if($result['next']) {
									//if(!defined('PVPT_MAX_TURNS_OVERRIDE') || (defined('PVPT_MAX_TURNS_OVERRIDE') && defined('PVPT_MAX_TURNS_OVERRIDE') > 2)) {
									if($basePlayer->id_random_queue_type == 4 || !$basePlayer->id_random_queue) {
										$update['current']	= $result['next'];
									}
									
									$update['target']	= 0;
								}
								
								_do_status_update($players_by_order);
							} else {
								SharedStore::D($battle_key); // First time in the history of the game that this one is used =O!
							
								$basePlayer->setAtkItem($item);
							
								$update['target']	= $_POST['target'];
								$fight				= new Fight();
								$fight->id			= $basePlayer->id_batalha_multi_pvp;
								$fight->is4xpvp		= true;
								$fight->addPlayer($basePlayer);
								
								SharedStore::S($battle_key, $fight);
							}
						} elseif($_POST['action'] == 2) { // Buff/Debuff
							$modifiers	= $basePlayer->getModifiers();
							$pass		= true;
							
							if($item->id_tipo == 1) { // Weapons
								foreach($modifiers as $mod) {
									$mod_item = Recordset::query("SELECT id_tipo FROM item WHERE id=" . (int)$mod['id'], true)->row_array();
									
									if($mod_item['id_tipo'] == 1 && $mod['direction'] == $mod['o_direction']) {
										$pass = false;
										
										break;
									}
								}
								
								if($pass) {
									if($basePlayer->removeItem($item)) {
										if(!isset($json->remove)) {
											$json->remove	= array();
										}
										
										$json->remove[]	= $item->id;
									} else {
										if(!isset($json->quantities)) {
											$json->quantities	= array();
										}
										
										$json->quantities[]	= array('id' => $item->id, 'quantity' => $item->qtd - 1);
									}
								}
								
								$o_direction = 0;
							} else {
								$mod	= $item->getModifiers();
							
								// Only one buff type -->
									if(
										!$mod['target_ken'] 		&& !$mod['target_tai'] 			&& !$mod['target_nin'] 			&& !$mod['target_gen']			&& !$mod['target_agi']			&& !$mod['target_con']	&& 
										!$mod['target_forc']		&& !$mod['target_inte']			&& !$mod['target_res']			&& !$mod['target_atk_fisico']	&& !$mod['target_atk_magico']	&& 
										!$mod['target_def_base']	&& !$mod['target_prec_fisico']	&& !$mod['target_prec_magico']	&& !$mod['target_crit_min']		&& !$mod['target_crit_max']	&& !$mod['target_crit_total'] && !$mod['target_esq_min']	&& !$mod['target_esq_total'] && !$mod['target_esq_max']	&&
										!$mod['target_esq']			&& !$mod['target_det']			&&!$mod['target_conv']			&&!$mod['target_esquiva']     && !$mod['target_conc']	&&
										!$mod['target_def_fisico']	&& !$mod['target_def_magico']
									) {	
										$o_direction = 0; // BUFF
									} elseif(
										!$mod['self_ken'] 			&& !$mod['self_tai'] 			&& !$mod['self_nin'] 			&& !$mod['self_gen']		&& !$mod['self_agi']		&& !$mod['self_con']		&& 
										!$mod['self_forc']			&& !$mod['self_inte']			&& !$mod['self_res']			&& !$mod['self_atk_fisico']	&& !$mod['self_atk_magico']	&& 
										!$mod['self_def_base']		&& !$mod['self_prec_fisico']	&& !$mod['self_prec_magico']	&& !$mod['self_crit_min']	&& !$mod['self_crit_max']	&& !$mod['self_crit_total'] && !$mod['self_esq_min']  && !$mod['self_esq_max'] && !$mod['self_esq_total'] &&
										!$mod['self_esq']			&& !$mod['self_det']			&&!$mod['self_conv']			&&!$mod['self_esquiva'] && !$mod['self_conc']	&&
										!$mod['self_def_fisico']	&& !$mod['self_def_magico']
									) {
										$o_direction = 1; // GEN
									} else {
										$o_direction = 2; // DUAL
									}				
					
									foreach($modifiers as $mod) {
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
								// <--
							}
				
							if(!$pass) {
								$json->messages[]	= t('actions.a90') . '[2]';
							}
							
							if(!sizeof($json->messages)) {
								$baseEnemy				= $battle['target'] ? new Player($players_by_order[$battle['current']]->id) : new Player($players_by_order[$_POST['target']]->id);

								$action_was_valid		= true;
								$ignore_turn_rotation	= true;

								$basePlayer->consumeSP($item->consume_sp);
								$basePlayer->consumeSTA($item->consume_sta);
								
								$basePlayer->addModifier($item, $item->getAttribute('level'), 0, $o_direction);
								$baseEnemy->addModifier($item, $item->getAttribute('level'), 1, $o_direction);			

								$update['log']	= $battle['log'] . '<div class="buff"><b class="pvp_p_name">' . $basePlayer->nome . '</b> usa <span class="pvp_p_atk">' . $item->nome . '</span></div>';

								_do_status_update($players_by_order);
							}
						} elseif($_POST['action'] == 3) { // Clan and others
							if(!in_array($item->id_tipo, array(16, 17, 20, 21, 23, 26, 39))) {
								$json->messages[]	= t('actions.a67');
							}

							$modifiers	= $basePlayer->getModifiers();
							
							foreach($modifiers as $modifier) {
								if($modifier['id'] == $item->id && $modifier['direction'] == 0) {
									$json->messages[]	= t('fight.f20');
								}
							}
				
							if(!sizeof($json->messages)) {
								$basePlayer->consumeHP($item->consume_hp);
								$basePlayer->consumeSP($item->consume_sp);
								$basePlayer->consumeSTA($item->consume_sta);
								
								$basePlayer->addModifier($item, $item->getAttribute('level'), 0);

								$players[$basePlayer->id]->hp->max		= $basePlayer->max_hp;
								$players[$basePlayer->id]->hp->current	= $basePlayer->hp;
								$players[$basePlayer->id]->sp->max		= $basePlayer->max_sp;
								$players[$basePlayer->id]->sp->current	= $basePlayer->sp;
								$players[$basePlayer->id]->sta->max		= $basePlayer->max_sta;
								$players[$basePlayer->id]->sta->current	= $basePlayer->sta;
								
								_do_status_update($players_by_order);

								$action_was_valid		= true;
								$ignore_turn_rotation	= true;
							}							
						} elseif($_POST['action'] == 4) { // Medic
							if($item->id_tipo != 24 || $battle['target']) {
								$json->messages[]	= t('actions.a92');
							}

							if(Player::getFlag('equipe_role', $basePlayer->id) != 1) {
								$json->messages[]	= t('actions.a267');
							}
							
							if($players_by_order[$_POST['target']]->id == $basePlayer->id) {
								$json->messages[]	= t('actions.a93');
							}
				
							if(!$_POST['target']) {
								$json->messages[]	= t('actions.a94');
							}
							
							if(!sizeof($json->messages)) {
								$basePlayer->consumeSP($item->consume_sp);
								$basePlayer->consumeSTA($item->consume_sta);

								$friend	= new Player($players_by_order[$_POST['target']]->id);
													
								if($item->bonus_hp)  $friend->consumeHP(-$item->bonus_hp);
								if($item->bonus_sp)  $friend->consumeSP(-$item->bonus_sp);
								if($item->bonus_sta) $friend->consumeSTA(-$item->bonus_sta);	

								$players[$basePlayer->id]->hp->max		= $basePlayer->max_hp;
								$players[$basePlayer->id]->hp->current	= $basePlayer->hp;
								$players[$basePlayer->id]->sp->max		= $basePlayer->max_sp;
								$players[$basePlayer->id]->sp->current	= $basePlayer->sp;
								$players[$basePlayer->id]->sta->max		= $basePlayer->max_sta;
								$players[$basePlayer->id]->sta->current	= $basePlayer->sta;

								$players[$friend->id]->hp->max			= $friend->max_hp;
								$players[$friend->id]->hp->current		= $friend->hp;
								$players[$friend->id]->sp->max			= $friend->max_sp;
								$players[$friend->id]->sp->current		= $friend->sp;
								$players[$friend->id]->sta->max			= $friend->max_sta;
								$players[$friend->id]->sta->current		= $friend->sta;

								$result			= pvp_do_turn_rotation($battle, $players_by_order);
								$update['log']	=	$battle['log'] . '<span class="pvp_p_name">' . $basePlayer->nome . '</span> '.t('fight.f13').'  <span class="pvp_p_atk">' . $item->nome . 
													'</span> no <span class="pvp_p_name">' . $friend->nome . '</span> e recupera <span class="verde">' . $item->bonus_hp . ' de vida</span><br /><hr />';
								$battle['log']	= $update['log'];
								
								if($result['next']) {
									$update['current']	= $result['next'];
									$update['target']	= 0;
								}
								
								$action_was_valid	= true;

								_do_status_update($players_by_order);
							}
						} elseif($_POST['action'] == 5) { // Kinjutsu
							if($item->id_tipo != 37 || $battle['target']) {
								$json->messages[]	= t('actions.a92');
							}
							
							if(Player::getFlag('equipe_role', $basePlayer->id) != 4) {
								$json->messages[]	= t('actions.a267');								
							}
							
							if($basePlayer->id_equipe == $battle['id_equipe_a']) {
								$friendlies	= odds();
								$enemies	= evens();
							} else {
								$friendlies	= evens();
								$enemies	= odds();
							}
							
							if($item->tipo_bonus) { // Only enemies
								if(!$item->bonus_treino) { // One
									if(
										!isset($_POST['enemy']) || (isset($_POST['enemy']) && !is_numeric($_POST['enemy'])) ||
										(isset($_POST['enemy']) && is_numeric($_POST['enemy']) && !in_array($_POST['enemy'], $enemies))
									) {
										$json->messages[]	= t('actions.a263');
									} else {
										if(!$players_by_order[$_POST['enemy']]->alive) {
											$json->messages[]	= t('actions.a266');
										}										
									}
								}
							} else { // Only friendly
								if(!$item->bonus_treino) { // One
									if(
										!isset($_POST['friendly']) || (isset($_POST['friendly']) && !is_numeric($_POST['friendly'])) ||
										(isset($_POST['friendly']) && is_numeric($_POST['friendly']) && !in_array($_POST['friendly'], $friendlies))
									) {
										$json->messages[]	= t('actions.a261');
									} else {
										if($item->defensivo && $players_by_order[$_POST['friendly']]->alive) {
											$json->messages[]	= t('actions.a262');
										} elseif(!$item->defensivo && !$players_by_order[$_POST['friendly']]->alive) {
											$json->messages[]	= t('actions.a275');											
										}
										
										if($players_by_order[$_POST['friendly']]->id == $basePlayer->id) {
											$json->messages[]	= t('actions.a93');
										}
									}
								} else { // All
									$has_a_dead	= false;
								
									foreach($friendlies as $friendly) {
										if(!$players_by_order[$friendly]->alive) {
											$has_a_dead	= true;
										}
									}
									
									if(!$has_a_dead) {
										$json->messages[]	= t('actions.a264');
									}
								}
							}
							
							if(!sizeof($json->messages)) {
								$enemies_to_loop	= array();
								$friends_to_loop	= array();
								$only_dead			= $item->defensivo ? true : false;
							
								if($item->tipo_bonus) { // Enemies
									if(!$item->bonus_treino) { // One
										$enemies_to_loop[]	= $_POST['enemy'];
									} else {
										$enemies_to_loop	= $enemies;
									}
								} else {
									if(!$item->bonus_treino) { // One
										$friends_to_loop[]	= $_POST['friendly'];
									} else {
										$friends_to_loop	= $friendlies;
									}									
								}

								$basePlayer->consumeHP($consume_hp);
								$basePlayer->consumeSP($consume_sp);
								$basePlayer->consumeSTA($consume_sta);

								// Current player 
									$players[$basePlayer->id]->hp->max		= $basePlayer->max_hp;
									$players[$basePlayer->id]->hp->current	= $basePlayer->hp;
									$players[$basePlayer->id]->sp->max		= $basePlayer->max_sp;
									$players[$basePlayer->id]->sp->current	= $basePlayer->sp;
									$players[$basePlayer->id]->sta->max		= $basePlayer->max_sta;
									$players[$basePlayer->id]->sta->current	= $basePlayer->sta;
	
									if($basePlayer->sp <= 10 || $basePlayer->hp <= 10 || $basePlayer->sta <= 10) {
										$players[$basePlayer->id]->alive	= false;
									}
								// <--
								
								$instances	= array();
								
								foreach($enemies_to_loop as $enemy) {
									$instance		= new Player($players_by_order[$enemy]->id);
									$instances[]	= $instance;
									
									$bonus_hp		= percent($item->bonus_hp, $instance->max_hp);
									$bonus_sp		= percent($item->bonus_sp, $instance->max_sp);
									$bonus_sta		= percent($item->bonus_sta, $instance->max_sta);

									$instance->consumeHP($bonus_hp);
									$instance->consumeSP($bonus_sp);
									$instance->consumeSTA($bonus_sta);
								}
								
								foreach($friends_to_loop as $friend) {
									// Can't apply on himself(only happen when using a team-wide technique)
									if($players_by_order[$friend] == $basePlayer->id) {
										continue;
									}

									if($only_dead && $players_by_order[$friend]->alive) {
										continue;
									}
									
									if($only_dead) {
										$players_by_order[$friend]->alive	= true;
									}
									
									$instance		= new Player($players_by_order[$friend]->id);
									$instances[]	= $instance;

									$bonus_hp		= percent($item->bonus_hp, $instance->max_hp);
									$bonus_sp		= percent($item->bonus_sp, $instance->max_sp);
									$bonus_sta		= percent($item->bonus_sta, $instance->max_sta);
									
									$instance->consumeHP(-$bonus_hp);
									$instance->consumeSP(-$bonus_sp);
									$instance->consumeSTA(-$bonus_sta);
								}
								
								foreach($instances as $instance) {
									$instance								= new Player($instance->id);

									$players[$instance->id]->hp->max		= $instance->max_hp;
									$players[$instance->id]->hp->current	= $instance->hp;
									$players[$instance->id]->sp->max		= $instance->max_sp;
									$players[$instance->id]->sp->current	= $instance->sp;
									$players[$instance->id]->sta->max		= $instance->max_sta;
									$players[$instance->id]->sta->current	= $instance->sta;									

									// Mark as dead
									if($instance->sp <= 10 || $instance->hp <= 10 || $instance->sta <= 10) {
										$players[$instance->id]->alive	= false;
									}
								}
								
								$action_was_valid		= true;

								$result			= pvp_do_turn_rotation($battle, $players_by_order);
								$update['log']	=	$battle['log'] . '<span class="pvp_p_name">' . $basePlayer->nome . '</span> '.t('fight.f13').'  <span class="pvp_p_atk">' . $item->nome . 
													'</span><br /><hr />';
								$battle['log']	= $update['log'];

								if($result['finished']) {
									$update['winner']	= $result['winner'];
									$update['finished']	= 1;
								} 
								
								if($result['next']) {
									$update['current']	= $result['next'];
									$update['target']	= 0;
								}

								$turns[$item->id]	= 999999;								
								$action_was_valid	= true;

								_do_status_update($players_by_order);
							}
						} else {
							$json->invalid_action	= true;
						}
					}
					
					if($action_was_valid) {
						// Turn rotation --->
							if(!$ignore_turn_rotation) {
								$new_turns	= array();
								
								foreach($turns as $k => $v) {
									$turns[$k]--;
									
									if($turns[$k] > 0) {
										$new_turns[$k] = $turns[$k];
									}
								}
								
								$turns = $new_turns;
								SharedStore::S($item_turn_key, $new_turns);								
							} else {
								SharedStore::S($item_turn_key, $turns);
							}
						// <---
					
						foreach(odds() as $_ => $odd) {
							$update['p' . ($_ + 1)]	= serialize($players_by_order[$odd]);
						}

						foreach(evens() as $_ => $even) {
							$update['e' . ($_ + 1)]	= serialize($players_by_order[$even]);
						}
						
						$update['data_atk']	= now(true);
						
						Recordset::update('batalha_multi_pvp', $update, array(
							'id'	=> $basePlayer->id_batalha_multi_pvp
						));
					}
				}
			} else {
				$json->not_my_action	= true;
			}
		}
	}

	$json->players		= $players;
	$json->log			= $battle['log'];

	$json->me			= new stdClass();	
	$json->me->atkf		= $players[$basePlayer->id]->atks->f;
	$json->me->atkm		= $players[$basePlayer->id]->atks->m;
	$json->me->cmin		= $players[$basePlayer->id]->crits->min;
	$json->me->cmax		= $players[$basePlayer->id]->crits->max;
	$json->me->crit		= $players[$basePlayer->id]->crits->current;
	$json->me->def		= $players[$basePlayer->id]->def;
	$json->me->id		= $basePlayer->id;

	$json->items		= new stdClass();
	$items 				= $basePlayer->getItems(array(5));
	
	foreach($items as $item) {
		$item->setPlayerInstance($basePlayer);
		$item->parseLevel();

		$json->items->{$item->id}				= new stdClass();
		
		$json->items->{$item->id}->precision	= $item->getAttribute('precisao');
	}


	echo json_encode($json);
