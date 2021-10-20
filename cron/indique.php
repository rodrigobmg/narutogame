<?php
	require('_config.php');

	$qUsuarios = Recordset::query("SELECT id, id_ref, vip, active FROM global.user WHERE id_ref != 0");
	
	while($rUsuario = $qUsuarios->row_array()) {
		if(!$rUsuario['active']) {
			continue;
		}
		
		if(!Recordset::query("SELECT * FROM global.user_ref_given WHERE id_user=" . $rUsuario['id'])->num_rows) {
			Recordset::query("INSERT INTO global.user_ref_given(id_user) VALUES(" . $rUsuario['id'] . ")");
		}
		
		$rRef = Recordset::query("SELECT * FROM global.user_ref_given WHERE id_user=" . $rUsuario['id'] )->row_array();
		
		// Se não marcou bonus vip e o usuário se tornou vip da bonus
		if(!$rRef['vip'] && $rUsuario['vip']) {
			Recordset::query("UPDATE global.user SET coin=coin+2 WHERE id=" . $rUsuario['id_ref']);
			Recordset::query("UPDATE global.user_ref_given SET vip=1 WHERE id_user=" . $rUsuario['id']);

			echo "[1] BONUS PARA " . $rUsuario['id_ref'] . " -> CANCELA " . $rUsuario['id'] . "<br />";
		}
		
		$hasPlayer = false;
		
		$qPlayers = Recordset::query("SELECT * FROM player WHERE id_usuario=" . $rUsuario['id']);
		while($rPlayer = $qPlayers->row_array()) {
			if($rPlayer['level'] >= 10) {
				$hasPlayer = true;
				
				break;
			}
		}
		
		if(!$rRef['ryou'] && $hasPlayer) {
			Recordset::query("UPDATE player SET ryou=ryou+1000 WHERE id_usuario=" . $rUsuario['id_ref']);
			Recordset::query("UPDATE global.user_ref_given SET ryou=1 WHERE id_user=" . $rUsuario['id']);
			
			echo "[2] BONUS PARA " . $rUsuario['id_ref'] . " -> CANCELA " . $rUsuario['id'] . "<br />";
		}
	}
