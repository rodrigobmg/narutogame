<?php
	require '_config.php';
	require(ROOT . '/include/conquista.php');

	$exams	= Recordset::query('SELECT *, HOUR(TIMEDIFF(NOW(), data_inicio)) AS hours FROM exame_chuunin WHERE NOW() >= data_inicio AND finalizado=0');

	foreach ($exams->result_array() as $exam) {
		echo "Exame -> {$exam['id']}\n";

		if ($exam['etapa1']) {
			// Distribui os itens
			if (date('YmdHi') == date('YmdHi', strtotime($exam['data_inicio']))) {
				// Volta os ids pra 1 de todos que estão com 2
				Recordset::update('player', [
					'exame_chuunin_etapa'	=> 1
				], [
					'exame_chuunin_etapa'	=> 2
				]);

				$players	= Recordset::query('SELECT id FROM player WHERE id_exame_chuunin=' . $exam['id']);

				$split		= $players->num_rows / 2;
				$counter	= 0;

				if (is_float($split)) { // Divisão não equilibrada
					echo "- Divisão randomica\n";

					$perfect_split	= round($split);

					foreach ($players->result_array() as $player) {
						if ($counter < $perfect_split) {
							$id	= 22916;
						} elseif($counter >= $perfect_split && $counter <= ($perfect_split * 2)) {
							$id	= 22917;
						} else {
							$rand	= [22916, 22917];
							$id		= $rand[rand(0, 1)];
						}

						Recordset::insert('player_item', [
							'id_player'	=> $player['id'],
							'id_item'	=> $id,
							'qtd'		=> 1
						]);

						$counter++;
					}
				} else { // Divisão perfeita
					echo "- Divisão normal\n";

					foreach ($players->result_array() as $player) {
						if ($counter < $split) {
							$id	= 22916;
						} else {
							$id	= 22917;
						}

						Recordset::insert('player_item', [
							'id_player'	=> $player['id'],
							'id_item'	=> $id,
							'qtd'		=> 1
						]);

						$counter++;
					}
				}
			}

			// Remove os caras da primeira etapa
			if ($exam['hours'] >= 1) {
				echo "Finalização por tempo";

				$players	= Recordset::query('SELECT id, id_batalha, id_vila FROM player WHERE id_exame_chuunin=' . $exam['id'] . ' AND exame_chuunin_etapa=1');
				$battle		= false;

				foreach ($players->result_array() as $player) {
					if ($player['id_batalha']) {
						$battle	= true;
						continue;
					}

					$item_count	= Recordset::query('SELECT b.id, b.id_vila FROM player_item a JOIN player b ON b.id=a.id_player WHERE a.id_player=' . $player['id'] . ' AND a.id_item_tipo=40');

					if ($item_count->num_rows < 2) {
						Recordset::update('player', [
							'exame_chuunin_etapa'	=> 1,
							'id_exame_chuunin'		=> 0,
							'derrotas_exame'		=> ['escape' => false, 'value' => 'derrotas_exame+1'],
							'id_vila_atual'			=> $player['id_vila'],
							'dentro_vila'			=> 1
						], [
							'id'					=> $player['id']
						]);

						Recordset::delete('player_item', [
							'id_item_tipo'	=> 40,
							'id_player'		=> $player['id']
						]);
					}
				}

				// Até todos os trouxas sairem de batalha
				if(!$battle) {
					Recordset::update('exame_chuunin', [
						'finalizado'	=> 1
					], [
						'id'			=> $exam['id']
					]);
				}
			} else {
				echo "Exame em primeira etapa, mas não passou o tempo\n";

				$items	= Recordset::query('SELECT COUNT(DISTINCT(a.id_item)) AS total FROM player_item a JOIN player b ON b.id=a.id_player WHERE id_item_tipo=40 AND b.id_exame_chuunin=' . $exam['id']);

				if($items->row()->total < 2) {
					echo "Nâo tem mais pergaminhos\n";

					Recordset::update('player', [
						'id_vila_atual'			=> ['escape' => false, 'value' => 'id_vila'],
						'dentro_vila'			=> 1,
						'id_exame_chuunin'		=> 0,
						'exame_chuunin_etapa'	=> 1
					], [
						'id_exame_chuunin'		=> $exam['id']
					]);

					Recordset::delete('player_item', [
						'id_item_tipo'	=> 40
					]);

					Recordset::update('exame_chuunin', [
						'finalizado'	=> 1
					], [
						'id'			=> $exam['id']
					]);
				}
			}
		} else {
			/*
			if ($exam['inscritos'] <= 1) {
				echo "Inscritos insuficientes";
				continue;
			}
			*/

			// Vencedor
			$players	= Recordset::query('SELECT id, id_vila FROM player WHERE id_exame_chuunin=' . $exam['id']);
			$update		= [
				'finalizado'	=> 1
			];

			if ($players->num_rows == 1) {
				echo "Com vencedor";

				$player					= $players->row_array();
				$update['id_vencedor']	= $player['id'];

				Recordset::update('player', [
					'exame_chuunin_etapa'	=> 1,
					'id_exame_chuunin'		=> 0,
					'id_vila_atual'			=> $player['id_vila'],
					'dentro_vila'			=> 1,
					'vitorias_exame'		=> ['escape' => false, 'value' => 'vitorias_exame+1'],
					'ryou'					=> ['escape' => false, 'value' => 'ryou+' . $exam['ryous']],
					'treino_total'			=> ['escape' => false, 'value' => 'treino_total+' . $exam['treino']]
				], [
					'id'					=> $player['id']
				]);

				$basePlayer							= new stdClass();
				$basePlayer->id_graduacao			= $exam['id_graduacao'];
				$basePlayer->id_exame_chuunin		= $exam['id'];
				$basePlayer->exame_chuunin_etapa	= 2;
				$basePlayer->id						= $player['id'];

				arch_parse(NG_ARCH_EXAME, $basePlayer);
			} else {
				echo "Sem vencedores " . $players->num_rows;
			}

			// Finaliza o evento se ouver só um cara sobrando ou caso ninguem tenha entrado
			if ($players->num_rows == 1 || !$exam['inscritos']) {
				Recordset::update('exame_chuunin', $update, [
					'id'			=> $exam['id']
				]);
			}
		}
	}

	echo "\n";