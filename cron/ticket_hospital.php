<?php
	require('_config.php');
	
	//Recordset::query("DELETE FROM player_item WHERE id_item=1007 AND HOUR(TIMEDIFF(NOW(), data_ins)) >= 1");
	//Recordset::query("DELETE FROM player_item WHERE id_item=1008 AND HOUR(TIMEDIFF(NOW(), data_ins)) >= 2");
	//Recordset::query("DELETE FROM player_item WHERE id_item=1009 AND HOUR(TIMEDIFF(NOW(), data_ins)) >= 3");
	
	Recordset::query("UPDATE player_item SET uso=0 WHERE id_item IN(1007,1008,1009,1027,1079,1080,1028,1081,1082,1867,1868,1869,1870,1871,1872,1873,1874,2019,2020,2021,20313,20314,20315,21461, 21464, 21465,21466,21880,21881,21882)");

	// Reseta quando a cron roda de segunda pra terça(aka terça 0:00)
	if(date('N') == 5) {
		Recordset::query("UPDATE player SET treino_dia=0");
		Recordset::query('UPDATE player_flags SET treino_jutsu_exp_dia=0');
	}
	
	/*
	Recordset::query("DELETE FROM player_item WHERE id=1008 AND DATEDIFF(NOW(), data_ins) > 2");
	Recordset::query("DELETE FROM player_item WHERE id=1009 AND DATEDIFF(NOW(), data_ins) > 3");
	*/
