<?php
	$json			= new stdClass();
	$json->success	= false;
	$json->messages	= array();

	if($basePlayer->id_graduacao < 2) {
		$json->messages[]	= 'Você precisa ser no mínimo Genin para votar';
	}

	$can_vote			= true; //in_array(date('d'), array(1, 15));
	$allowed_players	= array();
	$types_to_ignore	= array();
	$voted				= Recordset::query('SELECT id_player_eleito, voto FROM vila_voto WHERE id_usuario=' . $_SESSION['usuario']['id']);
	$players_to_vote	= Recordset::query('
		SELECT
			* 
		
		FROM 
			ranking 
		
		WHERE
			id_vila=' . $basePlayer->id_vila . ' AND
			posicao_vila BETWEEN 2 AND 25 AND
			id_player NOT IN(
				SELECT
					id_player_eleito

				FROM
					vila_voto

				WHERE
					id_vila=' . $basePlayer->id_vila . ' AND
					id_usuario=' . $_SESSION['usuario']['id'] . ')
		
		ORDER BY posicao_vila');
	
	foreach($players_to_vote->result_array() as $player) {
		$allowed_players[]	= $player['id_player'];
	}

	foreach($voted->result_array() as $vote) {
		$types_to_ignore[]	= $vote['voto'];
	}

	if(!in_array($_POST['player'], $allowed_players)) {
		$json->messages[]	= 'Você ja votou nesse jogador';
	}

	if(!in_array($_POST['vote'], array('vila','defesa','guerra'))) {
		$json->messages[]	= 'Voto inválido';
	} else {
		if(in_array($_POST['vote'], $types_to_ignore)) {
			$json->messages[]	= 'Você já votou em um jogador para esse cargo';
		}		
	}
	
	if(!sizeof($json->messages)) {
		$json->success	= true;
		
		Recordset::insert('vila_voto', array(
			'id_usuario'		=> $_SESSION['usuario']['id'],
			'id_vila'			=> $basePlayer->id_vila,
			'id_player_eleito'	=> $_POST['player'],
			'voto'				=> $_POST['vote']
		));
	}
	
	echo json_encode($json);
