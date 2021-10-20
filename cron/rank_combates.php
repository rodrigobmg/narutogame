<?php
	date_default_timezone_set('America/Sao_Paulo');
	require('_config.php');

	if((date('d') == 7) || (date('d') == 14) || (date('d') == 21) || (date('d') == 28)) {
		Recordset::query("UPDATE player_batalhas_status set vitorias_semana = 0, vitorias_d_semana = 0, vitorias_f_semana = 0, derrotas_semana = 0, derrotas_npc_semana = 0, empates_semana = 0, fugas_semana = 0");
	} elseif ((date('d') == 1)) {
		Recordset::query("UPDATE player_batalhas_status set vitorias_mes = 0, vitorias_d_mes = 0, vitorias_f_mes = 0, derrotas_mes = 0, derrotas_npc_mes = 0, empates_mes = 0, fugas_mes = 0");
	}

	Recordset::query("UPDATE player_batalhas_status set vitorias = 0, vitorias_d = 0, vitorias_f = 0, derrotas = 0, derrotas_f = 0, derrotas_npc = 0, empates = 0, fugas = 0");
