<?php
	require('_config.php');
	require(ROOT . '/include/conquista.php');
	

	$current_arena	= Recordset::query('SELECT a.*, b.nome_' . Locale::get() . ' AS vila, MINUTE(TIMEDIFF(NOW(), data_inicio)) AS min FROM arena a JOIN vila b ON b.id=a.vila_id WHERE NOW() BETWEEN DATE_ADD(a.data_inicio, INTERVAL -10 MINUTE) AND DATE_ADD(a.data_fim, INTERVAL 2 MINUTE)');

	if(!$current_arena->num_rows) {
		die();	
	}
	
	$current_arena	= $current_arena->row_array();
	$players		= Recordset::query('SELECT id, id_arena, nome, dentro_vila, MINUTE(TIMEDIFF(NOW(), ult_atividade)) AS diff FROM player WHERE id_arena=' . $current_arena['id']);
	
	foreach($players->result_array() as $player) {
		if($player['diff'] > 3) {
			Recordset::update('player', array(
				'id_arena'			=> 0,
				'id_batalha'		=> 0,
				'derrotas_arena'	=> array('escape' => false, 'value' => 'derrotas_arena+1'),
				'dentro_vila'		=> 1,
				'id_vila_atual'		=> array('escape' => false, 'value' => 'id_vila')
			), array(
				'id'				=> $player['id']
			));
			
			Recordset::update('player_posicao', array(
				'dentro_vila'		=> 1,
				'vila_atual'		=> array('escape' => false, 'value' => 'vila')
			), array(
				'id_player'			=> $player['id']
			));
		}
	}
	
	$players->repeat();
	
	if(now() >= strtotime($current_arena['data_inicio'])) {
		echo "INICIO";
		
		if(now() >= strtotime($current_arena['data_fim'])) {
			$data	= array(
				'finalizado'	=> 1
			);
			
			if($players->num_rows == 1) {
				$player				= $players->row_array();
				$data['player_id']	= $player['id'];

				Recordset::update('player', array(
					'ryou'			=> array('escape' => false, 'value' => 'ryou + ' . $current_arena['ryous']),
					'exp'			=> array('escape' => false, 'value' => 'exp + ' . $current_arena['experiencia']),
				), array(
					'id'			=> $player['id']
				));
				
				// Conquista --->
					$basePlayer	= new Player($player['id']);
					arch_parse(NG_ARCH_ARENA, $basePlayer, 1, $current_arena['vila_id']);
				//			
				
				global_message('O jogador <span>"' . $player['nome'] . '"</span> ganhou a arena.');
			} else {
				global_message('A arena terminou sem um jogador vitorioso');
			}
			
			Recordset::update('player', array(
				'id_arena'		=> 0,
				'dentro_vila'	=> 1,
				'id_vila_atual'	=> array('escape' => false, 'value' => 'id_vila')
			), array(
				'id_arena'	=> $current_arena['id']
			));

			Recordset::update('arena', $data, array(
				'id'			=> $current_arena['id']
			));
			
			echo "FINALIZADA1";
		} else {
			if($players->num_rows == 1) {
				$player	= $players->row_array();
				
				global_message('O jogador "' . $player['nome'] . '" ganhou a arena.');
	
				Recordset::update('player', array(
					'id_arena'		=> 0,
					'dentro_vila'	=> 1,
					'id_vila_atual'	=> array('escape' => false, 'value' => 'id_vila'),
					'ryou'			=> array('escape' => false, 'value' => 'ryou + ' . $current_arena['ryous']),
					'exp'			=> array('escape' => false, 'value' => 'exp + ' . $current_arena['experiencia']),
				), array(
					'id'			=> $player['id']
				));
				
				Recordset::update('arena', array(
					'finalizado'	=> 1,
					'player_id'		=> $player['id']
				), array(
					'id'			=> $current_arena['id']
				));

				// Conquista --->
					$basePlayer	= new Player($player['id']);
					arch_parse(NG_ARCH_ARENA, $basePlayer, 1, $current_arena['vila_id']);
				//			

				// Recompensa
				Recordset::insert('player_recompensa_log', array(
					'fonte'			=> 'arena',
					'id_player'		=> $player['id'],
					'recebido'		=> 1,
					'exp'			=> $current_arena['experiencia'],
					'ryou'			=> $current_arena['ryous']
				));					

				echo "FINALIZADA2";
			}
		}
	} else {
		global_message('A arena irá iniciar em ' . $current_arena['min'] . ' minuto(s)');	
	}
