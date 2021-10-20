<?php
	$json			= new stdClass();
	$current_arena	= Recordset::query('SELECT * FROM exame_chuunin WHERE id=' . $basePlayer->id_exame_chuunin)->row_array();
	$started		= now() >= strtotime($current_arena['data_inicio']);

	if($started) {
		$basePlayer->setAttribute('dentro_vila', 0);
		$json->redirect	= '?secao=mapa_vila';
	}

	echo json_encode($json);