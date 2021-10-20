<?php
	require('_config.php');

	Recordset::query("UPDATE player_flags SET dojo_treino_vezes=0, pvp_treino_vezes=0, torneio_ganho='0', missao_total_dia=0");
