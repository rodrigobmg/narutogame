<?php
	date_default_timezone_set('America/Sao_Paulo');
	require('_config.php');
	
	if((date('d') == 7) || (date('d') == 14) || (date('d') == 21) || (date('d') == 28)) {
		$quest_combat_semanal = Recordset::query("SELECT id,periodo FROM quest_combat WHERE periodo='semanal' ORDER BY RAND() LIMIT 1")->result_array();
		Recordset::query('INSERT INTO player_quest_combat (id_quest_combat,periodo) VALUES ('.$quest_combat_semanal[0]['id'].',"'.$quest_combat_semanal[0]['periodo'].'")');
	} elseif ((date('d') == 2)) {
		$quest_combat_mensal = Recordset::query("SELECT id,periodo FROM quest_combat WHERE periodo='mensal' ORDER BY RAND() LIMIT 1")->result_array();
		Recordset::query('INSERT INTO player_quest_combat (id_quest_combat,periodo) VALUES ('.$quest_combat_mensal[0]['id'].',"'.$quest_combat_mensal[0]['periodo'].'")');
	}

	$quest_combat_diario = Recordset::query("SELECT id,periodo FROM quest_combat WHERE periodo='diario' ORDER BY RAND() LIMIT 1")->result_array();
	Recordset::query('INSERT INTO player_quest_combat (id_quest_combat,periodo) VALUES ('.$quest_combat_diario[0]['id'].',"'.$quest_combat_diario[0]['periodo'].'")');
