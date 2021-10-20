<?php
	require '_config.php';
	require(ROOT . '/include/conquista.php');

	$wars	= Recordset::query('SELECT * FROM guerra_ninja WHERE NOW() BETWEEN data_inicio AND data_fim AND finalizado=0');

	foreach ($wars->result_array() as $war) {
		if (!$war['iniciado']) {
			echo "- Não iniciado\n";

			Recordset::update('guerra_ninja_npcs', [
				'xpos'		=> ['escape' => false, 'value' => 'FLOOR(RAND() * 241) * 22'],
				'ypos'		=> ['escape' => false, 'value' => 'FLOOR(RAND() * 144) * 22'],
				'morto'		=> 0,
				'mapa'		=> 0,
				'batalha'	=> 0,
				'less_hp'	=> 0,
				'less_sp'	=> 0,
				'less_sta'	=> 0
			], [
				'akatsuki'	=> $war['akatsuki']
			]);

			Recordset::update('guerra_ninja', [
				'iniciado'	=> 1
			], [
				'id'		=> $war['id']
			]);

			global_message2('guerra_ninja.cron.' . ($war['akatsuki'] ? 'akatsuki' : 'aliado') . '.etapa1');
		} else {
			echo "- Já iniciado\n";
		}

		echo "- Etapa {$war['etapa']}\n";

		switch ($war['etapa']) {
			case 1:
			case 2:
				$total_from_table	= Recordset::query('SELECT SUM(CASE WHEN morto=1 THEN 1 ELSE 0 END) AS dead, SUM(1) AS total FROM guerra_ninja_npcs WHERE etapa=1 AND akatsuki=' . $war['akatsuki'])->row_array();
				$total_from_table2	= Recordset::query('SELECT SUM(CASE WHEN morto=1 THEN 1 ELSE 0 END) AS dead, SUM(1) AS total FROM guerra_ninja_npcs WHERE etapa=2 AND akatsuki=' . $war['akatsuki'])->row_array();

				echo "- NPCs Etapa 1 -> Mortos: {$total_from_table['dead']} / Total: {$total_from_table['total']}\n";
				echo "- NPCs Etapa 2 -> Mortos: {$total_from_table2['dead']} / Total: {$total_from_table2['total']}\n";

				// Passou da meta dos npcs, libera etapa 2
				if ($total_from_table['dead'] >= $total_from_table['total'] / 2 && $war['etapa'] == 1) {
					global_message2('guerra_ninja.cron.' . ($war['akatsuki'] ? 'akatsuki' : 'aliado') . '.etapa2');

					Recordset::update('guerra_ninja_npcs', [
						'mapa'	=> 1
					], [
						'etapa'		=> 2,
						'akatsuki'	=> $war['akatsuki']
					]);

					Recordset::update('guerra_ninja', [
						'etapa'	=> 2
					], [
						'id'	=> $war['id']
					]);
				}

				// Se matar todos da etapa 2, passa pra 3
				if ($total_from_table2['dead'] >= $total_from_table2['total'] && $total_from_table['dead'] >= $total_from_table['total']) {
					global_message2('guerra_ninja.cron.' . ($war['akatsuki'] ? 'akatsuki' : 'aliado') . '.etapa3');

					Recordset::update('guerra_ninja_npcs', [
						'mapa'	=> 1
					], [
						'etapa'		=> 3,
						'akatsuki'	=> $war['akatsuki']
					]);

					Recordset::update('guerra_ninja', [
						'etapa'	=> 3
					], [
						'id'	=> $war['id']
					]);
				}

				// Se os npcs da primeira etapa ainda estiverem vivos
				if ($total_from_table['dead'] < $total_from_table['total']) {
					$total_in_map		= Recordset::query('SELECT COUNT(id) AS total FROM guerra_ninja_npcs WHERE etapa=1 AND mapa=1 AND morto=0 AND akatsuki=' . $war['akatsuki'])->row()->total;
					$need_in_map		= 500 - $total_in_map;

					echo "- NPCs Etapa 1 - Npcs faltando $need_in_map\n";

					if ($total_in_map < 500) {
						$npcs_to_free	= Recordset::query('SELECT id FROM guerra_ninja_npcs WHERE etapa=1 AND mapa=0 AND akatsuki=' . $war['akatsuki'] . ' LIMIT ' . $need_in_map);

						foreach ($npcs_to_free->result_array() as $npc) {
							Recordset::update('guerra_ninja_npcs', [
								'mapa'	=> 1
							], [
								'id'	=> $npc['id']
							]);
						}
					}
				}

				break;

			case 3:
				$total_from_table	= Recordset::query('SELECT SUM(CASE WHEN morto=1 THEN 1 ELSE 0 END) AS dead, SUM(1) AS total FROM guerra_ninja_npcs WHERE etapa=3 AND akatsuki=' . $war['akatsuki'])->row_array();

				echo "- NPCs Etapa 3 - Npcs {$total_from_table['dead']} de {$total_from_table['total']}\n";

				if ($total_from_table['dead'] >= $total_from_table['total']) {
					$start		= new DateTime($war['data_inicio']);
					$now		= new DateTime();
					$interval	= $start->diff($now);

					Recordset::update('guerra_ninja', [
						'etapa'				=> 4,
						'etapa3_vitoria'	=> 1,
						'etapa3_data'		=> ['escape' => false, 'value' => 'NOW()']
					], [
						'id'			=> $war['id']
					]);

					// Se matou a etapa 3 antes de 12h vai pra 4
					if ($interval->format('%h') <= 11) {
						global_message2('guerra_ninja.cron.' . ($war['akatsuki'] ? 'akatsuki' : 'aliado') . '.etapa4');

						Recordset::update('guerra_ninja_npcs', [
							'mapa'	=> 1
						], [
							'etapa'		=> 4,
							'akatsuki'	=> $war['akatsuki']
						]);
					} else {
						Recordset::update('guerra_ninja', [
							'finalizado'	=> 1
						], [
							'id'			=> $war['id']
						]);
					}
				}


				break;

			case 4:
				$total_from_table	= Recordset::query('SELECT SUM(CASE WHEN morto=1 THEN 1 ELSE 0 END) AS dead, SUM(1) AS total FROM guerra_ninja_npcs WHERE etapa=4 AND akatsuki=' . $war['akatsuki'])->row_array();

				if ($total_from_table['dead'] >= $total_from_table['total']) {
					Recordset::update('guerra_ninja', [
						'etapa4_vitoria'	=> 1,
						'finalizado'		=> 1
					], [
						'id'				=> $war['id']
					]);
				}


				break;
		}
	}

	// Eventos pendentes de término
	$pendings	= Recordset::query('SELECT * FROM guerra_ninja WHERE finalizado=0 AND NOW() >= data_fim');

	foreach ($pendings->result_array() as $pending) {
		Recordset::update('guerra_ninja', [
			'finalizado'	=> 1
		], [
			'id'			=> $pending['id']
		]);
	}

	// Hora das recompensas bitches
	$rewardables	= Recordset::query('SELECT * FROM guerra_ninja WHERE finalizado=1 AND recompensa=0');

	foreach ($rewardables->result_array() as $rewardable) {
		$got_reward	= false;

		if ($rewardable['etapa4_vitoria']) {
			$got_reward	= true;

			global_message2('guerra_ninja.cron.' . ($rewardable['akatsuki'] ? 'akatsuki' : 'aliado') . '.vitoria3');
		} elseif($rewardable['etapa3_vitoria']) {
			$got_reward	= true;

			global_message2('guerra_ninja.cron.' . ($rewardable['akatsuki'] ? 'akatsuki' : 'aliado') . '.vitoria4');
		}

		if ($got_reward) {
			// Recompensa da vila -->
				if ($rewardable['akatsuki']) {
					$villages	= [6, 7, 8];
				} else {
					$villages	= [1, 2, 3, 4, 5];
				}

				foreach ($villages as $village) {
					vila_exp($rewardable['exp_vila'], $village);
					
					Recordset::update('vila', [
						'buff_guerra'	=> ['escape' => false, 'value' => 'NOW()']
					], [
						'id'			=> $village
					]);
				}
			// <--

			// Títulos -->
				$players	= Recordset::query('SELECT distinct(gp.id_player), p.id_usuario FROM guerra_ninja_npc_player as gp JOIN player as p ON gp.id_player = p.id WHERE gp.id_guerra_ninja=' . $rewardable['id']);

				// Etapa 3
				foreach ($players->result_array() as $player) {
					Recordset::insert('player_titulo', [
						'id_usuario' => $player['id_usuario'],
						'id_player'	 => $player['id_player'],
						'titulo_br'	 => $rewardable['akatsuki'] ? 'O Mal Reinará!' : 'O Bem nunca perderá!',
						'titulo_en'	 => $rewardable['akatsuki'] ? 'The Evil Will Reign!' : 'The good will never lose!'
					]);
				}

				// Etapa 4
				if ($rewardable['etapa4_vitoria']) {
					foreach ($players->result_array() as $player) {
						Recordset::insert('player_titulo', [
							'id_usuario' => $player['id_usuario'],
							'id_player'	=> $player['id_player'],
							'titulo_br'	=> $rewardable['akatsuki'] ? 'Nós Criamos um Novo Mundo!' : 'Nós Salvamos o Mundo!',
							'titulo_en'	=> $rewardable['akatsuki'] ? 'We Have Created a New World!' : 'We Have Save The World!'
						]);
					}
				}

				foreach ($players->result_array() as $player) {
					$basePlayer				= new stdClass();
					$basePlayer->id			= $player['id_player'];
					$basePlayer->fim_guerra	= true;

					arch_parse(NG_ARCH_GUERRA, $basePlayer);
				}
			// <--
		} else {
			global_message2('guerra_ninja.cron.' . ($rewardable['akatsuki'] ? 'akatsuki' : 'aliado') . '.derrota');

			if ($rewardable['akatsuki']) {
				$villages	= [1, 2, 3, 4, 5];
			} else {
				$villages	= [6, 7, 8];
			}

			foreach ($villages as $village) {
				Recordset::update('vila', [
					'buff_guerra'	=> ['escape' => false, 'value' => 'NOW()']
				], [
					'id'			=> $village
				]);
			}
		}

		Recordset::update('guerra_ninja', [
			'recompensa'	=> 1
		], [
			'id'			=> $rewardable['id']
		]);

		// Atualização da tabela do ranking -->
			Recordset::query('TRUNCATE TABLE ranking_guerra_ninja');

			$villages	= Recordset::query('SELECT id FROM vila WHERE inicial="1"');
			$players	= Recordset::query('
				SELECT
					SUM(CASE WHEN a.vitoria THEN 1 ELSE 0 END) AS mortos,
					b.id,
					b.level,
					b.id_vila,
					b.id_graduacao,
					b.id_classe,
					b.nome
				FROM
					guerra_ninja_npc_player a JOIN player b ON a.id_player=b.id
				GROUP BY 2
			');

			foreach ($players->result_array() as $player) {
				Recordset::insert('ranking_guerra_ninja', [
					'nome'			=> $player['nome'],
					'id_player'		=> $player['id'],
					'id_graduacao'	=> $player['id_graduacao'],
					'id_vila'		=> $player['id_vila'],
					'id_classe'		=> $player['id_classe'],
					'pontos'		=> $player['mortos'],
					'level'			=> $player['level']
				]);
			}

			foreach ($villages->result_array() as $village) {
				$players	= Recordset::query('SELECT id FROM ranking_guerra_ninja WHERE id_vila=' . $village['id'] . ' ORDER BY pontos DESC');
				$counter	= 1;

				foreach ($players->result_array() as $player) {
					Recordset::update('ranking_guerra_ninja', [
						'posicao_vila'	=> $counter++
					], [
						'id'			=> $player['id']
					]);
				}
			}

			$players	= Recordset::query('SELECT id FROM ranking_guerra_ninja ORDER BY pontos DESC');
			$counter	= 1;

			foreach ($players->result_array() as $player) {
				Recordset::update('ranking_guerra_ninja', [
					'posicao_geral'	=> $counter++
				], [
					'id'			=> $player['id']
				]);
			}
		// <--
	}

	// Remoção do buff
	$villages	= Recordset::query('SELECT DATEDIFF(NOW(), buff_guerra) AS diff, id FROM vila WHERE inicial="1"');

	foreach ($villages->result_array() as $village) {
		if ($village['diff'] > 6) {
			Recordset::update('vila', [
				'buff_guerra'	=> NULL
			], [
				'id'			=> $village['id']
			]);
		}
	}