<?php
	require('_config.php');

	Recordset::query("UPDATE player_flags SET hospital_count=0,equipe_exp_shop=0, dojo_treino_vezes=0, pvp_treino_vezes=0, torneio_ganho='0', missao_total_dia=0"); //treino_jutsu_exp_dia=0, 
