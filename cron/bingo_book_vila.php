<?php
	require('_config.php');

	set_time_limit(0);
	
	//Marca os sobreviventes
	Recordset::query("UPDATE bingo_book_vila SET sobrevivente = 1 WHERE morto = '0'");
		
	$vilas = new Recordset("
		SELECT
			id
		FROM
			vila
		
		WHERE 
			inicial = '1'
	");
	
	foreach($vilas->result_array() as $vila) {
		$inimigos		= Recordset::query("select id_player,posicao_vila from ranking WHERE id_vila not in (". $vila['id'] .") AND id_player not in (SELECT id_player_alvo FROM bingo_book_vila WHERE morto='0' AND id_vila=". $vila['id'] .") AND  posicao_vila <= 10 ORDER BY rand() LIMIT 2");
		
		foreach($inimigos->result_array() as $inimigo) {
			$base = 750 - $inimigo['posicao_vila'] * 25;

			Recordset::query("INSERT INTO bingo_book_vila(id_vila, id_player_alvo, exp) VALUES(" . $vila['id'] . ", " . $inimigo['id_player'] . ", ". $base .")");
		}
	}
