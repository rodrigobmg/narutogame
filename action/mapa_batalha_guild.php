<?php
	sleep(2);

	$cID = decode($_POST['id']);
	
	if(!is_numeric($cID)) {
		redirect_to("negado");	
	}

	// Player da mesma organiza��o n�o batalha --->
		if($basePlayer->id_guild) {
			$rGuild = Recordset::query("SELECT id_guild FROM player WHERE id=" . (int)$cID)->row_array();
			
			if($rGuild['id_guild'] == $basePlayer->id_guild) {
				echo "alert('".t('actions.a200')."')";
				die();
			}
		}
	// <---
	
	// BLoqueia a batalha com players que est�o em batalha ou que ja perderam --->
		$rBatalha = Recordset::query("SELECT id_batalha FROM player WHERE id=" . (int)$cID)->row_array();
		
		if($rBatalha['id_batalha']) {
			echo "alert('".t('actions.a207')."')";
			die();
		}
		
		$rBatalhaPerdida = Recordset::query("SELECT perdedor FROM guild_batalha_player WHERE id_player=" . $cID . 
														 " AND id_guild_batalha=" . $basePlayer->id_batalha_guild)->row_array();
		
		if($rBatalhaPerdida['perdedor']) {
			echo "alert('".t('actions.a207')."')";
			die();
		}
	// <---

	if(Recordset::query("SELECT id FROM player WHERE id=" . $cID . " AND id_batalha IS NULL")->num_rows) {
		$qBatalha = Recordset::query("INSERT INTO batalha(id_tipo, id_player, id_playerb, current_atk, enemy, data_atk) VALUES(
			5, {$basePlayer->id}, $cID, 1, $cID, NOW())");
		
		$bID = $qBatalha->insert_id();

		Recordset::query("UPDATE player SET id_batalha=" . $bID . " WHERE id={$basePlayer->id}");
		Recordset::query("UPDATE player SET id_batalha=" . $bID . ", id_sala = NULL WHERE id={$cID}");
	
		echo "location.href='?secao=dojo_batalha_pvp'";
	} else {
		echo "alert('".t('actions.a206')."');";
	}
