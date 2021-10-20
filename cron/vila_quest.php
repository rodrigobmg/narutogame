<?php
	date_default_timezone_set('America/Sao_Paulo');

	require('_config.php');

	$q = Recordset::query("SELECT * FROM vila_quest WHERE id_guild != 0");

	while($r = $q->row_array()) {
		Recordset::query("UPDATE vila_quest SET id_guild=0, data_ins=NULL WHERE id=" . $r['id']);
		Recordset::query("UPDATE player SET vila_quest_derrotas = vila_quest_derrotas + 1 WHERE id_guild=" . $r['id_guild']);
		Recordset::query("UPDATE guild SET derrotas = derrotas + 1 WHERE id=" . $r['id_guild']);
		Recordset::query("UPDATE vila SET vitorias = vitorias + 1 WHERE id=" . $r['id_vila']);

		$vila	= Recordset::query('SELECT id_vila FROM player WHERE id_guild=' . $r['id_guild'] . ' LIMIT 1')->row()->id_vila;

		// Vai tirar o npc e o manolo da batalha
		if($r['id_guild']) {
			Recordset::query('UPDATE npc_vila SET batalha=0 WHERE id=' . $r['id_npc_vila']);

			$npc = Recordset::query('SELECT * FROM npc_vila WHERE id=' . $r['id_npc_vila'])->row_array();

			// tem um manolo em batalha
			if($npc['id_player_batalha']) {
				$batalha = Recordset::query('SELECT * FROM batalha WHERE id=' . $npc['id_player_batalha'])->row_array();

				if($batalha['id']) {
					Recordset::query('UPDATE player SET id_batalha=NULL WHERE id_player=' . $batalha['id_player']);
				}
			}
		}
	}

	// Toda Segunda 6h libera o npc geral(ui!)
	if(date("N") == 1 && date("H") == "6") {
		Recordset::query("UPDATE npc_vila SET batalha=0, tempo_derrota=NULL, less_hp=0, less_sp=0, less_sta=0, morto=0");
	}
