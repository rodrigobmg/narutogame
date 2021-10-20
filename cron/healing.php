<?php
	require('_config.php');
	
	/*
	$qPlayer = Recordset::query("
		SELECT 
			w.*,
			b.hp AS hp_heal,
			b.sp AS sp_heal,
			b.sta AS sta_heal,
			id_batalha
		FROM (
		SELECT a.id, a.less_hp, a.less_sp, a.less_sta, a.hospital, a.id_batalha FROM player a WHERE a.less_hp != 0 AND id_batalha=0 AND id_batalha_multi=0
		UNION
		SELECT a.id, a.less_hp, a.less_sp, a.less_sta, a.hospital, a.id_batalha FROM player a WHERE a.less_sp != 0 AND id_batalha=0 AND id_batalha_multi=0
		UNION
		SELECT a.id, a.less_hp, a.less_sp, a.less_sta, a.hospital, a.id_batalha FROM player a WHERE a.less_sta != 0 AND id_batalha=0 AND id_batalha_multi=0
		) w LEFT JOIN player_healing b ON w.id=b.id_player
		GROUP BY w.id;	
	");
	*/

	$players	= Recordset::query('
		SELECT
			b.id_player AS id,
			b.hp AS hp_heal,
			b.sp AS sp_heal,
			b.sta AS sta_heal,
			a.less_hp,
			a.less_sta,
			a.less_sp,
			a.hospital,
			a.id_batalha
		FROM
			player a #FORCE KEY(idx_healing_new) 
			JOIN player_healing b ON a.id=b.id_player
		
		WHERE
			a.id_batalha=0 AND
			a.id_batalha_multi=0 AND
			a.id_batalha_multi_pvp=0 AND
			(
				a.less_sp!=0 OR
				a.less_hp!=0 OR
				a.less_sta!=0
			)	
	');

	echo "+ Begin: ~" . $players->num_rows . "\n\n";

	$sz			= $players->num_rows;
	$curPlayer	= new stdClass();
	$cc			= 0;
	
	foreach($players->result_array() as $player) {
		if($player['id_batalha']) {
			echo "- IGNORED\n";
			continue;
		}

		if(!$player['hp_heal']) {
			$player['hp_heal']	= 50;
		}

		if(!$player['sp_heal']) {
			$player['sp_heal']	= 50;
		}

		if(!$player['sta_heal']) {
			$player['sta_heal']	= 50;
		}

		$hp_heal = (int)$player['hp_heal'];
		$sp_heal = (int)$player['sp_heal'];
		$sta_heal = (int)$player['sta_heal'];
		
		if($player['hospital']) {
			$hp_heal	*= 4;
			$sp_heal	*= 4;
			$sta_heal	*= 4;
		}
		
		//echo "\t-HPR: " . absm($player['less_hp'] - $hp_heal) . "\n";
		//echo "\t-SPR: " . absm($player['less_sp'] - $sp_heal) . "\n";
		//echo "\t-STR: " . absm($player['less_sta'] - $sta_heal) . "\n";
		
		$arSQL = array();
		
		if($player['less_hp']) {
			$arSQL[]	= "less_hp=" . absm($player['less_hp'] - $hp_heal);
			//Recordset::query("UPDATE player SET less_hp=" . absm($player['less_hp'] - $hp_heal) . " WHERE id=" . $player['id']);
		}

		if($player['less_sp']) {
			$arSQL[]	= "less_sp=" . absm($player['less_sp'] - $sp_heal);
			//Recordset::query("UPDATE player SET less_sp=" . absm($player['less_sp'] - $sp_heal) . " WHERE id=" . $player['id']);
		}

		if($player['less_sta']) {
			$arSQL[]	= "less_sta=" . absm($player['less_sta'] - $sta_heal);
			//Recordset::query("UPDATE player SET less_sta=" . absm($player['less_sta'] - $sta_heal) . " WHERE id=" . $player['id']);
		}
		
		if(absm($player['less_hp'] - $hp_heal) <= 0 &&
			absm($player['less_sp'] - $sp_heal) <= 0 &&
			absm($player['less_sta'] - $sta_heal) <= 0 &&
			$player['hospital']) {
			
			//echo "\t-H: OK\n";
			
			$arSQL[] = "hospital='1'";
			
			//Recordset::query("UPDATE player SET hospital=NULL WHERE id=" . $player['id']);
		}
		
		echo "UPDATE " . ++$cc . " OF $sz [$player[id]]\n";
		
		if(sizeof($arSQL)) {
			Recordset::query("UPDATE player SET " . implode(",", $arSQL) . " WHERE id=" . $player['id']);
		}
	}
	
	$q = Recordset::query('SELECT id FROM player WHERE less_sp=0 AND less_hp=0 AND less_sta=0 AND hospital=\'1\'');
	
	while($r = $q->row_array()) {
		Recordset::query('UPDATE player SET hospital=\'0\' WHERE id=' . $r['id']);
	}
