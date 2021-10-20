<?php
	require('_config.php');

	//Recordset::query("DELETE FROM player_batalhas_npc WHERE DATEDIFF(NOW(), data_ins) >= 1");
	Recordset::query('DELETE FROM player_batalhas_npc');
	Recordset::query('ALTER TABLE player_batalhas_npc AUTO_INCREMENT=1');
		
	Recordset::query('UPDATE player_batalhas_npc_mapa SET total=0');
