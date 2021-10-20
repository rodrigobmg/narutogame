<?php
	require('_config.php');

	$q = Recordset::query("SELECT * FROM equipe");
	
	while($r = $q->row_array()) {
		Recordset::query("UPDATE equipe SET membros=(SELECT COUNT(id) FROM player WHERE id_equipe=" . $r['id'] . ") WHERE id=" . $r['id']);
	}
