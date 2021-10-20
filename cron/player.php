<?php
	require('_config.php');

	Recordset::query('DELETE FROM estatistica_player');
	Recordset::query('ALTER TABLE estatistica_player AUTO_INCREMENT=1');

	$qPlayer = Recordset::query("
		SELECT 
			a.nome, 
			a.id AS id_classe,
			(SELECT COUNT(id) FROM player WHERE id_classe=a.id and level > 1) AS total
		FROM
			classe a WHERE a.ativo=1
	");
	
	while($rv = $qPlayer->row_array()) {	
		Recordset::query("
			INSERT INTO
				estatistica_player(id_classe, nome, total) 
			VALUES (" . $rv['id_classe'] . ", '" . $rv['nome'] . "', " . $rv['total'] . ")
		");
	}
