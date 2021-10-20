<?php
	Recordset::update('torneio_player', 
		array('participando' => 0),
		array('id_player' => $basePlayer->id)
	);

	Recordset::update('torneio_player', 
		array(
			'derrotas'	=> array('value' => 'derrotas+1', 'escape' => false),
			'chave'		=> 1
		),
		array(
			'id_player'		=> $basePlayer->id,
			'id_torneio'	=> $basePlayer->getAttribute('id_torneio')
		)
	);
	
	redirect_to('torneio');
