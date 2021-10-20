<?php
	$json			= new stdClass();
	$current_exam	= Recordset::query('SELECT * FROM exame_chuunin WHERE NOW() >= DATE_ADD(data_inicio, INTERVAL -20 MINUTE) AND finalizado=0 AND etapa1=' . ($basePlayer->exame_chuunin_etapa == 1 ? 1 : 0) . ' AND id_graduacao=' . $basePlayer->id_graduacao);
	
	if(!$current_exam->num_rows) {
		$json->redirect	= '?secao=negado&e=1';
	} else {
		$current_exam	= $current_exam->row_array();
	
		if(now() >= strtotime('-20 minute', strtotime($current_exam['data_inicio'])) && now() <= strtotime($current_exam['data_inicio'])) {
			$update	= [
				'id_exame_chuunin'	=> $current_exam['id'],
				'dentro_vila'		=> 1,
				'id_vila_atual'		=> $basePlayer->exame_chuunin_etapa == 1 ? 28 : 29
			];

			if ($current_exam['etapa1']) {
				$update['exame_chuunin_etapa']	= 1;
			}

			Recordset::update('player', $update, array(
				'id'				=> $basePlayer->id
			));
			
			Recordset::update('player_posicao', array(
				'xpos'		=> rand(1, 19),
				'ypos'		=> rand(1, 19)
			), array(
				'id_player'	=> $basePlayer->id
			));
			
			Recordset::update('exame_chuunin', array(
				'inscritos'	=> array('escape' => false, 'value' => 'inscritos+1')
			), array(
				'id'		=> $current_exam['id']
			));
			
			$json->redirect	= '?secao=exame_espera';
		} else {
			$json->redirect	= '?secao=negado&e=2';
		}
	}
	
	echo json_encode($json);
