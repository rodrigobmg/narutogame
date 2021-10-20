<?php
	require('_config.php');

	set_time_limit(0);
	
	$guilds = new Recordset("
		SELECT
			g.*		
		FROM
			guild g
		
		WHERE 
			g.removido='0' AND g.level > 4
	");
	
	foreach($guilds->result_array() as $guild) {
		echo "+ GUILD " . $guild['id'] . "\n";
		$counter = 1;
		
		while($counter <= 1) {
			echo "+ CHOOSE\n";
			
			$where			= "";			
			$inimigos		= Recordset::query("SELECT * FROM bingo_book_guild WHERE id_guild=" . $guild['id']); // isso tem  que estar aqui pois pode sim ocorrer de trazer o mesmo cara duas vezes abaixo
			$inimigo_atual	= Recordset::query("
				SELECT 
					a.id
				
				FROM 
					player a 
				
				WHERE 
					a.removido=0 AND 
					id_guild != ".$guild['id']." AND
					a.level >= 15 AND
					DATEDIFF(CURDATE(), ult_atividade) < 3 
					$where 
				
				ORDER BY RAND() LIMIT 1");
				
			$inimigo_atual	= $inimigo_atual->row_array();
			$ja_inimigo		= false;
			
			foreach($inimigos->result_array() as $inimigo) {
				if($inimigo['id_player_alvo'] == $inimigo_atual['id']) {
					$ja_inimigo = true;
				}
			}
			
			if(!$ja_inimigo) {
				echo "+ INSERT\n";
				//mysql_query("INSERT INTO bingo_book_guild(id_guild, id_player_alvo, exp) VALUES(" . $guild['id'] . ", " . $inimigo_atual['id'] . ", 1000)");
	
				$counter++;	
			}
		}
	}
?>