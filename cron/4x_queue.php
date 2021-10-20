<?php
	require '_config.php';

  check_for_lock(__FILE__);

  define('ALLOW_SHARED_STORE', true);	

	define('QUEUE_TABLE', 'batalha_4x_fila');
	define('DELETE_TABLE', 'batalha_4x_fila_sair');
	define('QUEUE_SIZE', 4);
	define('MAX_RETRIES', 30);
	define('AVG_TOLERANCE', 2);

	function _make_object($c, $instance) {
		$out					= new stdClass();
		$out->id				= $instance->id;
		$out->level				= $instance->level;
		
		$out->hp				= new stdClass();
		$out->hp->max			= $instance->max_hp;
		$out->hp->current		= $instance->hp;

		$out->sp				= new stdClass();
		$out->sp->max			= $instance->max_sp;
		$out->sp->current		= $instance->sp;

		$out->sta				= new stdClass();
		$out->sta->max			= $instance->max_sta;
		$out->sta->current		= $instance->sta;
		
		$out->name				= $instance->nome;
		$out->headline			= $instance->getAttribute('nome_titulo');
		$out->graduation		= graduation_name($instance->getAttribute('id_vila'), $instance->getAttribute('id_graduacao'));
		
		$out->crits				= new stdClass();
		$out->crits->min		= $instance->getAttribute('crit_min_calc');
		$out->crits->max		= $instance->getAttribute('crit_max_calc');
		$out->crits->original	= $instance->getAttribute('conc_calc');
		$out->crits->current	= $instance->getAttribute('conc_calc');
		$out->crits->total		= $instance->getAttribute('max_crit_hits');
		$out->crits->used		= 0;
		
		$out->esqs				= new stdClass();
		$out->esqs->min			= $instance->getAttribute('esq_min_calc');
		$out->esqs->min			= $instance->getAttribute('esq_max_calc');
		$out->esqs->original	= $instance->getAttribute('esq_calc');
		$out->esqs->current		= $instance->getAttribute('esq_calc');
		$out->esqs->total		= $instance->getAttribute('max_esq_hits');
		$out->esqs->used		= 0;
		
		$out->atks				= new stdClass();
		$out->atks->f			= $instance->getAttribute('atk_fisico_calc');
		$out->atks->m			= $instance->getAttribute('atk_magico_calc');

		$out->precs				= new stdClass();
		$out->precs->f			= $instance->getAttribute('prec_fisico_calc');
		$out->precs->m			= $instance->getAttribute('prec_magico_calc');

		$out->def				= $instance->getAttribute('def_base_calc');
		$out->deff				= $instance->getAttribute('def_fisico_calc');
		$out->defm				= $instance->getAttribute('def_magico_calc');
		$out->det				= $instance->getAttribute('det_calc');
		$out->conv				= $instance->getAttribute('conv_calc');
		
		$out->mods				= array();
		$out->alive				= true;
		$out->elements			= $instance->getElementos();
		
		$out->role_id		= Player::getFlag('equipe_role', $instance->id);
		
		if($out->role_id != "") {
			$out->role_lvl		= Player::getFlag('equipe_role_' . $out->role_id . '_lvl', $instance->id);
		} else {
			$out->role_lvl		= NULL;
		}
		
		return $out;
	}

	while(true) {
		$players_to_unqueue	= Recordset::query('SELECT * FROM ' . DELETE_TABLE);
		
		if($players_to_unqueue->num_rows) {
			foreach($players_to_unqueue->result_array() as $item) {
				$q_player = Recordset::query('SELECT id_player FROM ' . QUEUE_TABLE . ' WHERE id=' . $item['id_queue']);
		
				if ($q_player->num_rows) {
					$player  = $q_player->row()->id_player;
				
					Recordset::delete(DELETE_TABLE, array('id' => $item['id']));
					Recordset::delete(QUEUE_TABLE, array('id' => $item['id_queue']));
					
					Recordset::update('player', array(
						'id_random_queue'  => 0
					), array(
						'id'        => $player
					));
				}
			}
		}
		
		$players_to_queue	= Recordset::query('SELECT COUNT(id) AS total FROM ' . QUEUE_TABLE);
		
		if($players_to_queue->row()->total >= QUEUE_SIZE * 2) {
			$team1			= array();
			$ips			= array();
			$retries		= 0;
			$match			= false;
			$players		= Recordset::query('SELECT * FROM ' . QUEUE_TABLE . ' ORDER BY RAND() LIMIT ' . QUEUE_SIZE);
			$team1_average	= 0;
			
			foreach($players->result_array() as $player) {
				$team1[]		= $player['id_player'];
				$ips[]			= $player['ip'];

				$team1_average	+= $player['level'];
			}

			$team1_average	/= QUEUE_SIZE;
			
			while($retries++ <= MAX_RETRIES) {
				$players		= Recordset::query('SELECT * FROM ' . QUEUE_TABLE . ' WHERE id_player NOT IN(' . join(',', $team1) . ') AND ip NOT IN(' . join(',', $ips) . ') ORDER BY RAND() LIMIT ' . QUEUE_SIZE);
				
				if($players->num_rows < QUEUE_SIZE) {
					continue;
				}
				
				$team2			= array();
				$team2_average	= 0;
				
				foreach($players->result_array() as $player) {
					$team2[]		= $player['id_player'];
					$team2_average	+= $player['level'];
				}
				
				$team2_average	/= QUEUE_SIZE;
				
				if(between($team2_average, $team1_average - AVG_TOLERANCE, $team1_average + AVG_TOLERANCE)) {
					$match	= true;
					
					break;
				}
			}
			
			if($match) {
				echo "- MATCH FOUND\n";
				
				$ids			= array();	
				$queue_ids		= array();			
				$instances_p	= array();
				$instances_e	= array();
				$conv_p			= 0;
				$conv_e			= 0;
				$data			= array(
					'data_atk'		=> array('escape' => false, 'value' => 'NOW()'),
					'current'		=> 1,
					'id_equipe_a'	=> -1,
					'id_equipe_b'	=> -2
				);

				$counter	= 1;
				foreach($team1 as $id) {
					$instance						= new Player($id);
					$instance->clearModifiers();
					
					$instances_p['p' . $counter]	= $instance;
					$data['p' . $counter++]			= _make_object($counter, $instance);
					$conv_p							+= $instance->getAttribute('conv_calc');

					$ids[]							= $id;
					$queue_ids[]					= $instance->id_random_queue;
				}
	
				$counter	= 1;
				foreach($team2 as $id) {
					$instance						= new Player($id);
					$instance->clearModifiers();
	
					$instances_p['e' . $counter]	= $instance;
					$data['e' . $counter++]			= _make_object($counter, $instance);
					$conv_e							+= $instance->getAttribute('conv_calc');

					$ids[]							= $id;
					$queue_ids[]					= $instance->id_random_queue;
				}

				$conv_e				= $conv_p / QUEUE_SIZE;
				$conv_p				= $conv_e / QUEUE_SIZE;
				$data['range_a']	= $team1_average;
				$data['range_b']	= $team2_average;

				foreach($instances_p as $_ => $instance) {
					$data[$_]->crits->original	= $instance->getAttribute('conc_calc');
					$data[$_]->esqs->original	= $instance->getAttribute('esq_calc');
					$data[$_]->crits_esqs_red	= $conv_e;				
				
					$instance->setLocalAttribute('less_conv', $conv_e);
					$instance->atCalc();
					
					$data[$_]->conv_team		= $conv_p;
					
					$data[$_]->crits->min		= $instance->getAttribute('crit_min_calc');
					$data[$_]->crits->max		= $instance->getAttribute('crit_max_calc');
					$data[$_]->crits->current	= $instance->getAttribute('conc_calc');
					$data[$_]->crits->total		= $instance->getAttribute('max_crit_hits');

					$data[$_]->esqs->min		= $instance->getAttribute('esq_min_calc');
					$data[$_]->esqs->min		= $instance->getAttribute('esq_max_calc');
					$data[$_]->esqs->current	= $instance->getAttribute('esq_calc');
					$data[$_]->esqs->total		= $instance->getAttribute('max_esq_hits');
					
					$data[$_]					= serialize($data[$_]);
				}

				foreach($instances_e as $_ => $instance) {
					$data[$_]->crits->original	= $instance->getAttribute('conc_calc');
					$data[$_]->esqs->original	= $instance->getAttribute('esq_calc');
					$data[$_]->crits_esqs_red	= $conv_p;				

					$instance->setLocalAttribute('less_conv', $conv_p);
					$instance->atCalc();

					$data[$_]->conv_team		= $conv_e;
	
					$data[$_]->crits->min		= $instance->getAttribute('crit_min_calc');
					$data[$_]->crits->max		= $instance->getAttribute('crit_max_calc');
					$data[$_]->crits->current	= $instance->getAttribute('conc_calc');
					$data[$_]->crits->total		= $instance->getAttribute('max_crit_hits');

					$data[$_]->esqs->min		= $instance->getAttribute('esq_min_calc');
					$data[$_]->esqs->min		= $instance->getAttribute('esq_max_calc');
					$data[$_]->esqs->current	= $instance->getAttribute('esq_calc');
					$data[$_]->esqs->total		= $instance->getAttribute('max_esq_hits');

					$data[$_]					= serialize($data[$_]);
				}

				$battle_id	= Recordset::insert('batalha_multi_pvp', $data);

				Recordset::update('player', array(
					'id_batalha_multi_pvp'	=> $battle_id,
					'id_sala_multi_pvp'		=> 0
				), array(
					'id'					=> array('escape' => false, 'mode' => 'in', 'value' => join(',', $ids))
				));
				
				foreach($ids as $_ => $id) {
					Recordset::delete(DELETE_TABLE, array(
						'id_queue'	=> $queue_ids[$_]
					));					

					Recordset::delete(QUEUE_TABLE, array(
						'id_player'	=> $id
					));
					
					// Limpeza de flags de batalha(ciritos, esquivas e sua mãe)
					SharedStore::S('_TRL_' . $id, array());
					SharedStore::S('_PVP_ITEMS_' . $id, array());
					SharedStore::S('_USED_HEAL_' . $id, false);
					SharedStore::S('_MODIFIERS_PLAYER_' . $id, array());
				}
			} else {
				echo "- MATCH NOT FOUND\n";
			}
		} else {
			echo "- NOT ENOUGH QUEUED PLAYERS [" . $players_to_queue->row()->total . "/" . (QUEUE_SIZE * 2) . "]\n";
		}
		
		usleep(100000);
	}
