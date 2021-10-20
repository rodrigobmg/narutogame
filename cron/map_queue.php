<?php
	require '_config.php';

  check_for_lock(__FILE__);

  define('ALLOW_SHARED_STORE', true);

	while(true) {
		$battles	= Recordset::query('SELECT * FROM batalha_fila');

		echo "- FOUND " . $battles->num_rows . "\n";

		foreach ($battles->result_array() as $battle) {
			echo "+ RUNNING " . $battle['id'] . "\n";

			$already_on_battle	= false;
			$current_queue		= $battle['id'];

			/*
			if(
				Recordset::query('SELECT COUNT(id) AS total FROM player_batalhas WHERE id_player=' . $battle['id_player']  . ' AND id_playerb=' . $battle['id_playerb'])->row()->total >= 5 ||
				Recordset::query('SELECT COUNT(id) AS total FROM player_batalhas WHERE id_player=' . $battle['id_playerb'] . ' AND id_playerb=' . $battle['id_player'])->row()->total >= 5
			) {
				Recordset::delete('batalha_fila', ['id' => $current_queue]);

				echo "- LIMIT EXCEEDED\n";
				continue;
			}
			*/

			$players	= Recordset::query('SELECT id_batalha FROM player WHERE id IN(' . $battle['id_player'] . ', ' . $battle['id_playerb'] . ')');

			unset($battle['id']);

			foreach ($players->result_array() as $player) {
				if($player['id_batalha']) {
					$already_on_battle	= true;
					break;
				}
			}

			if(!$already_on_battle) {
				$id	= Recordset::insert('batalha', $battle);

				Recordset::update('player', [
					'id_batalha'	=> $id
				], [
					'id'	=> ['escape' => false, 'mode' => 'in', 'value' => $battle['id_player'] . ', ' . $battle['id_playerb']]
				]);

				Recordset::insert('player_batalhas', array(
					'id_tipo'		=> 4,
					'id_player'		=> $battle['id_player'],
					'id_playerb'	=> $battle['id_playerb']
				));

				Recordset::insert('batalha_log_acao', array(
					'id_player'		=> $battle['id_player'],
					'id_playerb'	=> $battle['id_playerb'],
					'id_batalha'	=> $id
				));

				Recordset::insert('batalha_log_acao', array(
					'id_player'		=> $battle['id_playerb'],
					'id_playerb'	=> $battle['id_player'],
					'id_batalha'	=> $id
				));

			}

			SharedStore::S('_TRL_' . $battle['id_player'], array());
			SharedStore::S('_PVP_ITEMS_' . $battle['id_player'], array());
			SharedStore::S('_USED_HEAL_' . $battle['id_player'], false);
			SharedStore::S('_MODIFIERS_PLAYER_' . $battle['id_player'], false);

			SharedStore::S('_TRL_' . $battle['id_playerb'], array());
			SharedStore::S('_PVP_ITEMS_' . $battle['id_playerb'], array());
			SharedStore::S('_USED_HEAL_' . $battle['id_playerb'], false);
			SharedStore::S('_MODIFIERS_PLAYER_' . $battle['id_playerb'], false);

			// 200ms
			Recordset::delete('batalha_fila', ['id' => $current_queue]);
			usleep(200 * 100);
		}

		usleep(10000);
	}
