<?php
	require('_config.php');

	$playersBanidos = Recordset::query("SELECT id,id_player FROM player_banido WHERE tipo = 'PuniçãoPVP' and liberado = 0 and now() >= data_liberado");
	
	while($playerBanido = $playersBanidos->row_array()) {
		// Libera a punição na player_banido
		Recordset::query("update player_banido set liberado = 1 where id = ". $playerBanido['id']);
		
		// Zera o contador de inatividade da player_flags
		Recordset::query("UPDATE player_flags SET wo_looses = 0 WHERE id_player=". $playerBanido['id_player']);
		
		// Ajusta a credibilidade do jogador na player
		Recordset::query("UPDATE player SET credibilidade = 100 WHERE id=". $playerBanido['id_player']);
	}
