<?php
	require('_config.php');

	Recordset::query('DELETE FROM ranking_equipe');
	Recordset::query('ALTER TABLE ranking_equipe AUTO_INCREMENT=1');

	$qVila = Recordset::query("SELECT * FROM vila");
	$arVilas = array();

	while($rv = $qVila->row_array()) {
		$qPlayers = Recordset::query("
			SELECT
				a.id,
				b.membros,
				b.level,
				b.vitoria,
				b.derrota,
				pf.evento_vitorias,
				SUM(CASE WHEN bb.morto = '1' THEN 1 ELSE 0 END) AS mortos,
   				SUM(CASE WHEN bb.morto = '0' THEN 1 ELSE 0 END) AS vivos,
				pf.evento_derrotas
			
			FROM
				player a 
					JOIN equipe b ON b.id_player=a.id
					JOIN player_flags as pf ON pf.id_player = a.id
					LEFT JOIN bingo_book_equipe as bb ON b.id = bb.id_equipe
			
			WHERE 
				a.id_vila=" . $rv['id'] . " AND b.removido=0 GROUP BY a.id ");
		
		while($rp = $qPlayers->row_array()) {
			$pontos  = $rp['level'] * 1000; // Level

			$pontos += $rp['vitoria'] * 100; // Vitorias
			$pontos -= $rp['derrota'] * 10; // Derrotas

			$pontos += $rp['evento_vitorias'] * 1000; // Vitorias
			$pontos -= $rp['evento_derrotas'] * 500; // Derrotas

			$pontos += $rp['membros'] * 1000; // Treino

			$pontos  += $rp['mortos'] * 200; // BB

			echo $pontos . PHP_EOL;

			$rq = Recordset::query("
				SELECT
					  id_player,
					  SUM(CASE WHEN id_rank=0 THEN 1 ELSE 0 END) AS lvl_tarefa,
					  SUM(CASE WHEN id_rank=1 THEN 1 ELSE 0 END) AS lvl_d,
					  SUM(CASE WHEN id_rank=2 THEN 1 ELSE 0 END) AS lvl_c,
					  SUM(CASE WHEN id_rank=3 THEN 1 ELSE 0 END) AS lvl_b,
					  SUM(CASE WHEN id_rank=4 THEN 1 ELSE 0 END) AS lvl_a,
					  SUM(CASE WHEN id_rank=5 THEN 1 ELSE 0 END) AS lvl_s
				
				FROM 
					quest a JOIN player_quest b ON b.id_quest = a.id  AND a.equipe=1
				
				WHERE b.id_player=" . $rp['id'])->row_array();

			$rqf = Recordset::query("
				SELECT
					  id_player,
					  SUM(CASE WHEN id_rank=0 THEN 1 ELSE 0 END) AS lvl_tarefa,
					  SUM(CASE WHEN id_rank=1 THEN 1 ELSE 0 END) AS lvl_d,
					  SUM(CASE WHEN id_rank=2 THEN 1 ELSE 0 END) AS lvl_c,
					  SUM(CASE WHEN id_rank=3 THEN 1 ELSE 0 END) AS lvl_b,
					  SUM(CASE WHEN id_rank=4 THEN 1 ELSE 0 END) AS lvl_a,
					  SUM(CASE WHEN id_rank=5 THEN 1 ELSE 0 END) AS lvl_s
				
				FROM 
					quest a JOIN player_quest b ON b.id_quest = a.id  AND a.equipe=1 AND b.falha=1
				
				WHERE b.id_player=" . $rp['id'])->row_array();

			$pontos += $rq['lvl_tarefa'] * 50;
			$pontos += $rq['lvl_d'] * 100;
			$pontos += $rq['lvl_c'] * 150;
			$pontos += $rq['lvl_b'] * 200;
			$pontos += $rq['lvl_a'] * 250;
			$pontos += $rq['lvl_s'] * 300;

			echo $pontos . PHP_EOL;

			$pontos -= $rqf['lvl_tarefa'] * 50;
			$pontos -= $rqf['lvl_d'] * 100;
			$pontos -= $rqf['lvl_c'] * 150;
			$pontos -= $rqf['lvl_b'] * 200;
			$pontos -= $rqf['lvl_a'] * 250;
			$pontos -= $rqf['lvl_s'] * 300;

			echo $pontos . PHP_EOL;
			
			$pontos = absm($pontos);
			
			Recordset::query("INSERT INTO ranking_equipe(id_player, id_vila, pontos, level) 
						 VALUES(" . $rp['id'] . ", " . $rv['id'] . ", " . $pontos . ", ". $rp['level'] .")");
		}

		$qo = Recordset::query("SELECT pontos, id_player FROM ranking_equipe WHERE id_vila=" . $rv['id'] . " ORDER BY pontos DESC");
		$c = 0;

		while($ro = $qo->row_array()) {
			Recordset::query("UPDATE ranking_equipe SET posicao_vila=" . ++$c . " WHERE id_player=" . $ro['id_player']);
		}
	}

	$qPlayer = Recordset::query("SELECT id FROM ranking_equipe ORDER BY pontos DESC");
	$c = 0;

	while($r = $qPlayer->row_array()) {
		Recordset::query("UPDATE ranking_equipe SET posicao_geral=" . ++$c . " WHERE id=" . $r['id']);
	}
	
	//echo "ok";