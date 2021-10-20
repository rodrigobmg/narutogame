<?php
	$json			= new stdClass();
	$current_arena	= Recordset::query('SELECT a.*, b.nome_' . Locale::get() . ' AS vila FROM arena a JOIN vila b ON b.id=a.vila_id WHERE NOW() BETWEEN DATE_ADD(a.data_inicio, INTERVAL -10 MINUTE) AND a.data_fim AND finalizado=0')->row_array();
	$started		= now() >= strtotime($current_arena['data_inicio']);

	if($started) {
		$basePlayer->setAttribute('dentro_vila', 0);
		
		$json->redirect	= '?secao=mapa_vila';	
	}

	echo json_encode($json);