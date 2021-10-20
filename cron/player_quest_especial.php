<?php
	require('_config.php');

	set_time_limit(0);

	$q = Recordset::query("SELECT * FROM player_quest WHERE id_quest IN(225,226,227,228,229,230,237,238,239,240) AND (completa=1 OR falha=1 OR finalizada =1)");

	while($r = $q->row_array()) {
		Recordset::query("DELETE FROM player_quest_npc_item WHERE id_player=" . $r['id_player'] . " AND id_player_quest=" . $r['id_quest']);
	}

	Recordset::query("DELETE FROM player_quest WHERE id_quest IN(225,226,227,228,229,230,237,238,239,240) AND completa=1");
	Recordset::query("DELETE FROM player_quest WHERE id_quest IN(225,226,227,228,229,230,237,238,239,240) AND falha=1");
	Recordset::query("DELETE FROM player_quest WHERE id_quest IN(225,226,227,228,229,230,237,238,239,240) AND finalizada=1");
