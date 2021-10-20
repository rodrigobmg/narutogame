<?php
	die();
	require('_config.php');

	//$q = Recordset::query("SELECT * FROM player_quest_status ORDER BY id_player ASC");
	$q = Recordset::query("SELECT id FROM player WHERE removido=0");
	
	echo "RUN " . $q->num_rows . "\n";
	
	while($r = $q->row_array()) {
		$qq = Recordset::query("SELECT a.id_rank,b.falha,b.finalizada, b.completa FROM quest a JOIN player_quest b ON b.id_quest=a.id AND b.id_player=" . $r['id'] . " WHERE a.especial=0");
		
		if(!Recordset::query("SELECT id FROM player_quest_status WHERE id_player=" . $r['id'])->num_rows) {
			Recordset::query("INSERT INTO player_quest_status(id_player) VALUES(" . $r['id'] . ")");
		} else {
			Recordset::query("
				UPDATE player_quest_status
				SET 
					quest_d=0,quest_c=0,quest_b=0,quest_a=0,quest_s=0, 
					falha_d=0,falha_c=0,falha_b=0,falha_a=0,falha_s=0,
					tarefa=0
				WHERE id_player=" . $r['id']
			);
		}
		
		$upd_fields = array();
		while($rr = $qq->row_array()) {
			if(!$rr['completa']) {
				continue;
			}			
			
			switch($rr['id_rank']) {
				case 5:
					$field =  "quest_s";
					$fieldb = "falha_s";
				
					break;
					
				case 4:
					$field  = "quest_a";
					$fieldb = "falha_a";
				
					break;
					
				case 3:
					$field  = "quest_b";
					$fieldb = "falha_b";
				
					break;
					
				case 2:
					$field  = "quest_c";
					$fieldb = "falha_c";
				
					break;
					
				case 1:
					$field  = "quest_d";
					$fieldb = "falha_d";
				
					break;
				
				default:
					$field = "tarefa";
					
			}
			
			if($rr['falha']) {
				$upd_fields[$fieldb] += 1;
				//Recordset::query("UPDATE player_quest_status SET $fieldb = $fieldb + 1 WHERE id_player=" . $r['id_player']);
			} else {
				$upd_fields[$field] += 1;
				//Recordset::query("UPDATE player_quest_status SET $field = $field + 1 WHERE id_player=" . $r['id_player']);
			}

		}
		
		$join = array();
		foreach($upd_fields as $k => $v) {
			$join[] = $k . "=" . $v;
		}

		//echo "UPDATE player_quest_status SET " . implode(",", $join) . " WHERE id_player=" . $r['id'] . "\n";

		Recordset::query("UPDATE player_quest_status SET " . implode(",", $join) . " WHERE id_player=" . $r['id']);

		if(++$cc == 100) {
			echo "K";
			$cc = 0;

			flush();
		}

		if($cc == 500) {
			echo "\n";	
		}
	}
?>
OK
