<?php set_time_limit(0); ?>
<pre>
<?php
	require('_config.php');

	echo "BEGIN\n";
	flush();

	Recordset::query("UPDATE player SET id_vila_atual=id_vila, dentro_vila=1, id_batalha=NULL");
	echo "PLAYERS OK\n";
	
	Recordset::query("
	INSERT INTO batalha_log(
		id_tipo,
		id_player,
		id_playerb,
		enemy,
		current_atk,
		pvp_log,
		pvp_wo,
		flight_id,
		vencedor,
		data_ins,
		data_atk,
		finalizada,
		empate,
		hp_a,
		hp_b,
		sp_a,
		sp_b
	)
	
	SELECT
		id_tipo,
		id_player,
		id_playerb,
		enemy,
		current_atk,
		pvp_log,
		pvp_wo,
		flight_id,
		vencedor,
		data_ins,
		data_atk,
		finalizada,
		empate,
		hp_a,
		hp_b,
		sp_a,
		sp_b		
	FROM
		batalha");

	echo "INSERT OK\n";
	flush();

	Recordset::query("TRUNCATE TABLE batalha");

	echo "TRUNCATE OK\n";
	flush();
	
	echo "ALL DONE";
	flush();
?>
</pre>