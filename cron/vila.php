<?php
	require('_config.php');

	Recordset::query('DELETE FROM estatistica_vila');
	Recordset::query('ALTER TABLE estatistica_vila AUTO_INCREMENT=1');
	
	$qVila = Recordset::query("
		SELECT p.id_vila, COUNT( p.id_vila ) AS total
		FROM player AS p						
		GROUP BY p.id_vila
		ORDER BY total DESC
	");
	
	while($rv = $qVila->row_array()) {
		Recordset::query("INSERT INTO estatistica_vila(id_vila, total_players) 
						 VALUES(" . $rv['id_vila'] . ", " . $rv['total'] . ")");
	}
