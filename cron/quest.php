<?php
	require('_config.php');

die();

	$query = Recordset::query("SELECT * FROM player_quest WHERE completa IS NULL AND data_conclusao <= NOW() AND id_quest NOT IN(SELECT id FROM quest WHERE interativa !=0)");

	while($r = $query->row_array()) {
		$rq = Recordset::query("SELECT * FROM quest WHERE id={$r['id_quest']}")->row_array();

		if(!$r['multiplicador']) {
			$r['multiplicador'] = 1;
		}

		$exp = (int)$rq['exp'] * $r['multiplicador'];
		$ryou = (int)$rq['ryou'] * $r['multiplicador'];

		Recordset::query("UPDATE player SET ryou = IFNULL(ryou, 0) + " . $ryou . ", exp = IFNULL(exp, 0) + " . $exp . " WHERE id=" . $r['id_player']);
		Recordset::query("UPDATE player_quest SET completa=1 WHERE id=" . $r['id']);

		// Atualiza o rank das miss�es -->
			if(!Recordset::query("SELECT id FROM player_quest_status WHERE id_player=" . $r['id_player'])->num_rows) {
				echo "INS\n";

				Recordset::query("INSERT INTO player_quest_status(id_player) VALUES(" . $r['id_player'] . ")");
			}

			switch($rq['id_rank']) {
				case 5:
					$field = "quest_s";
				
					break;
					
				case 4:
					$field = "quest_a";
				
					break;
					
				case 3:
					$field = "quest_b";
				
					break;
					
				case 2:
					$field = "quest_c";
				
					break;
					
				case 1:
					$field = "quest_d";
				
					break;
				
				default:
					$field = "tarefa";
			}

			/*
			$fp = fopen("/web/narutogame/cron/log.txt", "a+");
			fwrite($fp, "UPDATE player_quest_status SET $field = $field + 1 WHERE id_player=" . $r['id_player'] . "\n");
			fclose($fp);
			*/

			Recordset::query("UPDATE player_quest_status SET $field = $field + 1 WHERE id_player=" . $r['id_player']);
		// <---
	}
