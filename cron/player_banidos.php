<?php
	require('_config.php');

	$playersBanidos = Recordset::query("SELECT * FROM player_banido WHERE liberado=0 AND tipo = 'Temporário' AND now() > data_liberado");
	
	while($playerBanido = $playersBanidos->row_array()) {

		//Libera o cara 
		Recordset::query("UPDATE player_banido SET liberado = 1 WHERE id_player=". $playerBanido['id_player']);
		
		//Tira o Ban da Player
		Recordset::query("UPDATE player SET banido = 0 WHERE id=". $playerBanido['id_player']);
	}
