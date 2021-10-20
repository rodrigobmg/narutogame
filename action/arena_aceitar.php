<?php
	$json			= new stdClass();
	$current_arena	= Recordset::query('SELECT a.*, b.nome_' . Locale::get() . ' AS vila FROM arena a JOIN vila b ON b.id=a.vila_id WHERE NOW() BETWEEN DATE_ADD(a.data_inicio, INTERVAL -10 MINUTE) AND a.data_fim AND finalizado=0');
	
	if(!$current_arena->num_rows) {
		$json->redirect	= '?secao=negado&e=1';
	} else {
		$current_arena	= $current_arena->row_array();
		
		if(now() >= strtotime('-10 minute', strtotime($current_arena['data_inicio'])) && now() <= strtotime($current_arena['data_inicio'])) {
			Recordset::update('player', array(
				'id_arena'		=> $current_arena['id'],
				'dentro_vila'	=> 1,
				'id_vila_atual'	=> $current_arena['vila_id']
			), array(
				'id'			=> $basePlayer->id
			));
			
			Recordset::update('player_posicao', array(
				'xpos'		=> rand(1, 19),
				'ypos'		=> rand(1, 19)
			), array(
				'id_player'	=> $basePlayer->id
			));
			
			Recordset::update('arena', array(
				'inscritos'	=> array('escape' => false, 'value' => 'inscritos+1')
			), array(
				'id'		=> $current_arena['id']
			));
			
			$json->redirect	= '?secao=arena_espera';
		} else {
			$json->redirect	= '?secao=negado&e=2';
		}
	}
	
	echo json_encode($json);
