<pre>
<?php
	require('_config.php');


	$q = Recordset::query("
		SELECT id, id_player, id_item FROM player_item WHERE DATEDIFF(NOW(), data_ins) >= 150
	");
	
	while($r = $q->row_array()) {
		Recordset::query("DELETE FROM player_item WHERE id_player=" . $r['id_player'] . " AND id_item=" . $r['id_item']);
		Recordset::query("DELETE FROM coin_log WHERE id_player=" . $r['id_player'] . " AND id_item=" . $r['id_item']);
		
		echo "D: " . $r['id'] . "\n";
	}
?>
</pre>