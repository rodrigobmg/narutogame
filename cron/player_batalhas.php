<?php
	require('_config.php');

	
	//Recordset::query("DELETE FROM player_batalhas WHERE DATEDIFF(NOW(), data_ins) >= 1");
	Recordset::query('DELETE FROM player_batalhas');
	Recordset::query('ALTER TABLE player_batalhas AUTO_INCREMENT=1');

	Recordset::query('DELETE FROM player_batalhas_rnd');
	Recordset::query('ALTER TABLE player_batalhas_rnd AUTO_INCREMENT=1');
