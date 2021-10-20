<pre>
<?php
	require('_config.php');
 
 	$qM = Recordset::query("SELECT id FROM vila_forum_topico WHERE (DATEDIFF(NOW(), data_ins)) > 31");
	
	echo "Topicos:" . $qM->row_array() . "\n";
	
	while($rM = $qM->row_array()) {
		$q = Recordset::query("DELETE FROM vila_forum_topico_post WHERE id_vila_forum_topico=" . $rM['id']);
		$t += $q->affected_rows;
	}
	
	echo "Registros de post: $t\n";
	
	Recordset::query("DELETE FROM vila_forum_topico WHERE (DATEDIFF(NOW(), data_ins)) > 31");
?>
</pre>
