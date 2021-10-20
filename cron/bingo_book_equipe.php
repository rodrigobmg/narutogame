<?php
	require('_config.php');

	set_time_limit(0);
	
	$guilds = new Recordset("
		SELECT
			g.*		
		FROM
			equipe g
		
		WHERE 
			g.removido='0' AND g.level > 4
	");
	
	foreach($guilds->result_array() as $guild) {
		echo "+ TEAM " . $guild['id'] . "\n";

		$where			= " AND a.id NOT IN(1,2,3,4,18)"; // Excluir os ids de GM		
		$enemies		= Recordset::query("SELECT GROUP_CONCAT(id_player_alvo) AS e1 FROM bingo_book_equipe WHERE id_equipe=" . $guild['id'])->row();

		if($enemies->e1) {
			$where		.= ' AND id NOT IN(' . $enemies->e1 . ')';
		}

		$start	= microtime(true);

		$new_enemies	= Recordset::query("
			SELECT
				a.id

			FROM
				player a

			WHERE
				a.id_equipe !=" . $guild['id'] . " AND
				a.removido=0 AND
				a.level >= 10 AND
				DATEDIFF(CURDATE(), ult_atividade) < 3
				$where

			ORDER BY RAND() LIMIT 1");

		$counter	= 1;
		$insert		= [
			'id_equipe'	=> $guild['id'],
			'ryou'				=> 2000,
			'exp'				=> 1000,
			'pt_treino'			=> 2000
		];

		foreach ($new_enemies->result_array() as $new_enemy) {
			$insert['id_player_alvo' . ($counter == 1 ? '' : $counter)]	= $new_enemy['id'];
			$counter++;
		}

		Recordset::insert('bingo_book_equipe', $insert);

		echo "INSERT " . (microtime(true) - $start) . "\n";

	}
