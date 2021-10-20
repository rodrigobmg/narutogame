<?php
	require('_config.php');

	set_time_limit(0);

	$players = new Recordset("
		SELECT
			a.id,
			a.id_vila,
			a.id_guild,
			a.id_equipe
		
		FROM
			player a
		
		WHERE 
			a.removido=0 AND a.id_graduacao >= 4
	");
	
	foreach($players->result_array() as $player) {
		$counter = 1;
		
		while($counter <= 1) {
			$where			= "";			
			$inimigos		= Recordset::query("SELECT * FROM bingo_book WHERE id_player=" . $player['id']); // isso tem  que estar aqui pois pode sim ocorrer de trazer o mesmo cara duas vezes abaixo
			$inimigo_atual	= Recordset::query("SELECT a.id FROM player a WHERE a.id_vila !=" . $player['id_vila'] . " AND a.removido=0 AND a.level > 15 AND DATEDIFF(CURDATE(), ult_atividade) < 3 $where ORDER BY RAND() LIMIT 1");
			$inimigo_atual	= $inimigo_atual->row_array();
			$ja_inimigo		= false;
			
			foreach($inimigos->result_array() as $inimigo) {
				if($inimigo['id_player_alvo'] == $inimigo_atual['id']) {
					$ja_inimigo = true;
				}
			}
			
			if(!$ja_inimigo) {
				Recordset::query("INSERT INTO bingo_book(id_player, id_player_alvo, ryou, exp, pt_treino) VALUES(" . $player['id'] . ", " . $inimigo_atual['id'] . ", 10000, 5000, 5000)");

				$counter++;	
			}
		}
	}
