<?php
	require('_config.php');

	$qBatalhaMulti = Recordset::query('SELECT * FROM batalha_multi WHERE finished=\'0\' AND MINUTE(TIMEDIFF(NOW(), last_atk)) > 1');
	
	while($r = $qBatalhaMulti->row_array()) {
		echo "- BID: " . $r['id'] . "\n";
		if($r['id_tipo'] == 1 || $r['id_tipo'] == 2) {
			$current_id_p = $r['direction'] ? $r['target'] : $r['current_id_p'];
			$current_id_e = $r['current_id_e'];
			
			Recordset::query('UPDATE batalha_multi SET ps' . $current_id_p . '=\'0\' WHERE id=' . $r['id']);
			$r['ps' . $current_id_p] = 0;
			
			if(!$r['ps1'] && !$r['ps2'] && !$r['ps3'] && !$r['ps4']) {
				Recordset::query('UPDATE batalha_multi SET finished=\'1\', finished_direction=\'1\' WHERE id=' . $r['id']);
				continue;
			}
			
			if($r['direction'] == 0) { // A vez do player
				$current_id_p++;
				while(true) {
					echo "CHOOSING...";
					if($r['ps' . $current_id_p]) {
						break;
					}									

					$current_id_p++;
					
					if($current_id_p > 4) {
						$current_id_p = 1;
					}
				}
				
				Recordset::query("UPDATE batalha_multi SET current_id_p=" . $current_id_p . " WHERE id=" . $r['id']);
			} else { // A vez do NPC
				$current_id_p++;

				while(true) {
					echo "CHOOSING...";
					if($r['ps' . $current_id_p]) {
						break;
					}

					$current_id_p++;
					
					if($current_id_p > 4) {
						$current_id_p = 1;
					}
				}
				
				Recordset::query("UPDATE batalha_multi SET target=" . $current_id_p . ", current_id_p=" . $current_id_p . " WHERE id=" . $r['id']);
			}			
		}
	}
