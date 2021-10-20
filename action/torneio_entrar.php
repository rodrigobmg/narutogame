<?php
  $liberado	= !Player::getFlag('torneio_ganho', $basePlayer->id);

  if($basePlayer->getAttribute('id_torneio'))	{
    redirect_to('negado', NULL, array('e' => 1));
  }

  if($_SESSION['torneio_key'] != $_POST['torneio_key'] || !$_SESSION['torneio_key']) {
    redirect_to('negado', NULL, array('e' => 1));
  }

  if(!is_numeric($_POST['torneio'])) {
    redirect_to('negado', NULL, array('e' => 2));
  }
	
	// Verifica se o torneio existe
	if(!Recordset::query('SELECT * FROM torneio WHERE id=' . $_POST['torneio'], true)->num_rows) {
		redirect_to('negado', NULL, array('e' => 3));
	}

	$torneio	= Recordset::query('SELECT * FROM torneio WHERE id=' . $_POST['torneio'], true)->row_array();	
	$reqs 		= true;

	if(!$liberado) {
		$reqs	= false;
	}

	if($torneio['req_id_graduacao']) {
		$reqs	= $basePlayer->id_graduacao == $torneio['req_id_graduacao'] ? $reqs : false;
	}

	if($torneio['req_id_vila']) {
		$reqs	= $basePlayer->id_vila == $torneio['req_id_vila'] ? $reqs : false;
	}

	if($torneio['req_vitorias_pvp']) {
		$pvp	= $basePlayer->vitorias  + $basePlayer->vitorias_f;
		$reqs	= $pvp >= $torneio['req_vitorias_pvp'] ? $reqs : false;
	}
	
	if($torneio['req_vitorias_npc']) {
		$npc	= $basePlayer->vitorias_d;
		$reqs	= $pvp >= $torneio['req_vitorias_pvp'] ? $reqs : false;
	}
	
	if($torneio['req_id_cla']) {
		$reqs	= $basePlayer->id_cla == $torneio['req_id_cla'] ? $reqs : false;
	}
	
	if($torneio['req_portao']) {
		$reqs	= $basePlayer->portao ? $reqs : false;
	}
	
	if($torneio['req_sennin']) {
		$reqs	= $basePlayer->id_sennin ? $reqs : false;
	}
	
	if($torneio['req_level_ini'] && $torneio['req_level_fim']) {
		$reqs	= between($basePlayer->level, $torneio['req_level_ini'], $torneio['req_level_fim']) ? $reqs : false;
	}
	
	if($torneio['req_id_torneio']) {
		$t		= Recordset::query('SELECT * FROM torneio WHERE id=' . $torneio['req_id_torneio'], true)->row_array();
		$reqs	= Recordset::query('SELECT * FROM torneio_player WHERE id_player=' . $basePlayer->id . ' AND id_torneio=' . $torneio['req_id_torneio'] . ' AND vitorias > 0')->num_rows ? $reqs : false;
	}

	$date1	= true;
	$date2	= true;
	$date3	= true;

	if($torneio['dt_inicio'] && $torneio['dt_fim']) {
		$date1	= between(date('His'), date('His', strtotime($torneio['dt_inicio'])), date('His', strtotime($torneio['dt_fim'])));
	}

	if($torneio['dt_inicio2'] && $torneio['dt_fim2']) {
		$date2	= between(date('His'), date('His', strtotime($torneio['dt_inicio2'])), date('His', strtotime($torneio['dt_fim2'])));
	}

	if($torneio['dt_inicio3'] && $torneio['dt_fim3']) {
		$date3	= between(date('His'), date('His', strtotime($torneio['dt_inicio3'])), date('His', strtotime($torneio['dt_fim3'])));
	}

	if(!$date1 && !$date2 && !$date3) {
		$reqs	= false;
	}

	if($torneio['dias']) {
		if(!on(date('N'), $torneio['dias'])) {
			$reqs	= false;
		}
	}

	if(Recordset::query('SELECT id FROM torneio_player_torneio WHERE id_torneio=' . $torneio['id'] . ' AND id_player=' . $basePlayer->id)->num_rows) {
		$reqs	= false;
	}
	
	if(!$reqs) {
		redirect_to('torneio', NULL, array('e' => 1));
		die();
	}
	
	$torneio_reg = Recordset::query('SELECT * FROM torneio_player WHERE id_player=' . $basePlayer->id . ' AND id_torneio=' . $_POST['torneio']);
	
	if(!$torneio_reg->num_rows) {
		Recordset::insert('torneio_player', array(
			'id_player'		=> $basePlayer->id,
			'id_torneio'	=> $_POST['torneio'],
			'participando'	=> 1
		));
	} else {
		// Garante que o manolo não vai estar em dois torneios
		Recordset::update('torneio_player', 
			array('participando' => 0), 
			array('id_player' => $basePlayer->id)
		);
		
		// Marca o torneio do manodo caso ele ja tenha participado antes
		Recordset::update('torneio_player', 
			array('participando' => 1), 
			array(
				'id_player' 	=> $basePlayer->id,
				'id_torneio'	=> $_POST['torneio']
			)
		);
	}
	
	redirect_to('torneio');
