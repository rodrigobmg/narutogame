<?php
	Recordset::delete('torneio_espera', array(
		'id_player'		=> $basePlayer->id,
		'id_torneio'	=> $basePlayer->getAttribute('id_torneio'),
		'id_player_b'	=> 0
	));
