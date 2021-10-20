<?php
	require '_config.php';

  check_for_lock(__FILE__);

  define('ALLOW_SHARED_STORE', true);	
	
	define('QUEUE_TABLE', 'batalha_1x_fila');
	define('DELETE_TABLE', 'batalha_1x_fila_sair');
	define('QUEUE_SIZE', 1);
	define('MAX_RETRIES', 30);
	define('AVG_TOLERANCE', 0);

	while(true) {
		$players_to_unqueue	= Recordset::query('SELECT * FROM ' . DELETE_TABLE);
		
		if($players_to_unqueue->num_rows) {
			foreach($players_to_unqueue->result_array() as $item) {
				$player	= Recordset::query('SELECT id_player FROM ' . QUEUE_TABLE . ' WHERE id=' . $item['id_queue']);
			
				Recordset::delete(DELETE_TABLE, array('id' => $item['id']));
				Recordset::delete(QUEUE_TABLE, array('id' => $item['id_queue']));
				
				if($player->num_rows) {
					$player	= $player->row()->id_player;

					Recordset::update('player', array(
						'id_random_queue'	=> 0
					), array(
						'id'				=> $player
					));
				}
			}
		}
		
		$players_to_queue	= Recordset::query('SELECT COUNT(id) AS total FROM ' . QUEUE_TABLE);
		
		if($players_to_queue->row()->total >= QUEUE_SIZE * 2) {
			$team1			= array();
			$ips			= array();
			$ids			= array();
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
			$base_retry		= 0;
			
			while($base_retry < 1) {
				while($retries++ <= MAX_RETRIES) {
					$players		= Recordset::query('SELECT * FROM ' . QUEUE_TABLE . ' FORCE KEY(idx_id_player) WHERE id_player NOT IN(' . join(',', $team1) . ') AND ip NOT IN(' . join(',', $ips) . ') ORDER BY RAND() LIMIT ' . QUEUE_SIZE);
					
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
					
					if(between($team2_average, $team1_average - (AVG_TOLERANCE + $base_retry), $team1_average + (AVG_TOLERANCE + $base_retry))) {
						$match	= true;
						
						break 2;
					}
				}

				$base_retry++;				
			}
			
			if($match) {
				$instances	= [];

				echo "- MATCH FOUND\n";

				foreach($team1 as $id) {
					$ids[]			= $id;
					$instance		= new Player($id);
					$instance->clearModifiers();

					$queue_ids[]	= $instance->id_random_queue;
					$instances[]	= $instance;
				}
	
				foreach($team2 as $id) {
					$ids[]			= $id;
					$instance		= new Player($id);
					$instance->clearModifiers();

					$queue_ids[]	= $instance->id_random_queue;
					$instances[]	= $instance;
				}

				if(
					Recordset::query('SELECT COUNT(id) AS total FROM player_batalhas WHERE id_tipo=2 AND id_player=' . $ids[0] . ' AND id_playerb=' . $ids[1])->row()->total >= 2 ||
					Recordset::query('SELECT COUNT(id) AS total FROM player_batalhas WHERE id_tipo=2 AND id_player=' . $ids[1] . ' AND id_playerb=' . $ids[0])->row()->total >= 2
				) {
					echo "- LIMIT EXCEEDED\n";
					continue;
				}

				$data		= [
					'data_atk'		=> now(true),
					'id_tipo'		=> 2,
					'id_player'		=> $ids[0],
					'id_playerb'	=> $ids[1],
					'level_a'		=> $instances[0]->level,
					'level_b'		=> $instances[1]->level,
					'current_atk'	=> $ids[0],
					'enemy'			=> $ids[1]
				];

				$battle_id	= Recordset::insert('batalha', $data);

				Recordset::update('player', array(
					'id_batalha'	=> $battle_id,
				), array(
					'id'			=> array('escape' => false, 'mode' => 'in', 'value' => join(',', $ids))
				));
				
				Recordset::insert('player_batalhas', array(
					'id_tipo'		=> 2,
					'id_player'		=> $ids[1],
					'id_playerb'	=> $ids[0]
				));
				
				Recordset::insert('batalha_log_acao', array(
					'id_player'		=> $ids[0],
					'id_playerb'	=> $ids[1],
					'id_batalha'	=> $battle_id
				));

				Recordset::insert('batalha_log_acao', array(
					'id_player'		=> $ids[1],
					'id_playerb'	=> $ids[0],
					'id_batalha'	=> $battle_id
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
