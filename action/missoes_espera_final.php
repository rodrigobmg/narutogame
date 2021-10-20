<?php
	die();

	header("Content-type: text/javascript; charset=utf-8");

	$query = Recordset::query("SELECT * FROM player_quest WHERE completa IS NULL AND id_player={$basePlayer->id}");
	
	while($r = $query->row_array()) {
		if(date("YmdHis", strtotime($r['data_conclusao'])) <= date("YmdHis")) {
			$rq = Recordset::query("SELECT * FROM quest WHERE id={$r['id_quest']}")->row_array();
			
			// Levelamento de conclusao --->
				$exp = $rq['exp'];

				Recordset::query("UPDATE player SET ryou = IFNULL(ryou, 0) + " . (int)$rq['ryou'] . ", exp = IFNULL(exp, 0) + " . $exp . " WHERE id=" . $basePlayer->id);
			// <---
		
			Recordset::query("UPDATE player_quest SET completa=1 WHERE id=" . $r['id']);
		}	
	}

	// Reset session key
	$_SESSION['key'] = md5(rand(0, 512384) . rand(0, 512384));

	echo "location.href='?secao=missoes_espera'";
