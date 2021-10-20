<?php
	require('_config.php');

	$q = Recordset::query("SELECT *, HOUR(TIMEDIFF(NOW(), tempo_derrota)) AS diff FROM npc_vila WHERE tempo_derrota IS NOT NULL");
	
	// Remove as punições a cada 24 horas
	while($r = $q->row_array()) {
		if($r['diff'] >= 24) {
			echo "$r[id_vila] -> $r[mlocal]\n";
			
			Recordset::query("UPDATE local_mapa SET reduzido=0 WHERE id_vila=" . $r['id_vila'] . " AND mlocal=" . $r['mlocal']);
			Recordset::query("UPDATE npc_vila SET tempo_derrota=NULL WHERE id=" . $r['id']);
		}
	}
