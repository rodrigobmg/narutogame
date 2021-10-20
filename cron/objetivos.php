<?php
	require('_config.php');

	set_time_limit(0);

	$diario = Recordset::query("
			SELECT
				id
			FROM
				missoes_diarias

			WHERE 
				status = 1 and periodo='Diário' ORDER BY rand() LIMIT 1
		")->result_array();

	$semanal = Recordset::query("
			SELECT
				id
			FROM
				missoes_diarias

			WHERE 
				status = 1 and periodo='Semanal' ORDER BY rand() LIMIT 1
		")->result_array();

	$players = new Recordset("
		SELECT
			x.id,
			x.id_usuario,
			(select count(1) from player_missao_diarias a join missoes_diarias b on a.id_missao_diaria = b.id WHERE a.id_player = x.id and b.periodo='Diário' and a.completo=0) diario_total,    
			(select count(1) from player_missao_diarias a join missoes_diarias b on a.id_missao_diaria = b.id WHERE a.id_player = x.id and b.periodo='Semanal' and a.completo=0) semana_total

		FROM
			player x

		WHERE 
			x.removido=0 AND x.level >= 5");

	foreach($players->result_array() as $player) {

		if(date('w') == 0){
			if($player['semana_total'] < 3){
				// Premio semanal
				Recordset::query("INSERT INTO player_missao_diarias(id_usuario, id_player, id_missao_diaria) VALUES(" . $player['id_usuario'] . ", " .$player['id'] . ",".$semanal[0]['id'].")");
			}
		}
		if($player['diario_total'] < 3){
			// Premio Diário
			Recordset::query("INSERT INTO player_missao_diarias(id_usuario, id_player, id_missao_diaria) VALUES(" . $player['id_usuario'] . ", " .$player['id'] . ",".$diario[0]['id'].")");
			
		}
	
	}
