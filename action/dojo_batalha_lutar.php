<?php
	set_time_limit(120);
	ignore_user_abort();

	$redir_script	= true;
	$pvp_items		= SharedStore::G('_PVP_ITEMeS_' . $basePlayer->id, array());

	if(isset($_POST['begin']) && $_POST['begin']) {
		$_POST['bid'] = decode($_POST['bid']);

		if(!is_numeric($_POST['bid'])) {
			die('jalert("'.t('fight.f16').'")');
		}

		$sala = Recordset::query("SELECT * FROM batalha_sala WHERE id=" . $_POST['bid']);

		if(!$sala->num_rows) {
			echo "jalert('".t('actions.a32')."')";
			die();
		}

		$basePlayer	= new Player($basePlayer->id);
		$baseEnemy	= new Player($sala->row()->id_player);

		// Se ja batalhei com o player listado, não lista, pra evitar multi --->
		/*	$another_battle	= Recordset::query('
				SELECT
					id

				FROM
					player_batalhas

				WHERE
					(id_player=' . $basePlayer->id . ' AND id_playerb=' . $baseEnemy->id . ') OR
					(id_player=' . $baseEnemy->id . ' AND id_playerb=' . $basePlayer->id . ')');

			if($another_battle->num_rows) {
				echo "alert('".t('actions.a33')."')";
				die();
			}*/
		// <---

		if(!$_SESSION['universal']) {
			if($baseEnemy->ip == $basePlayer->ip) {
				echo "alert('".t('actions.a33')."')";
				die();
			}
		}

		if(!Recordset::query('SELECT id FROM player WHERE id=' . $baseEnemy->id . ' AND level BETWEEN ' . ($basePlayer->level - 3) . " AND " . ($basePlayer->level + 3))->num_rows) {
			echo "jalert('".t('actions.a34')."')";
			die();
		}

		// Verifica se o player pode efetuar a luta --->
			/*
			$batalhas = Recordset::query('SELECT COUNT(id) AS total FROM player_batalhas WHERE id_player=' . $basePlayer->id . ' AND id_playerb=' . $baseEnemy->id);

			if($batalhas->row()->total >= 5) {
				echo "jalert('".t('actions.a35')."')";
				die();
			}
			*/
		// <---

		// Player da mesma organização não batalha --->
			/*
			if($basePlayer->id_guild && $basePlayer->id_guild == $baseEnemy->id_guild) {
				echo "jalert('".t('actions.a36')."')";
				die();
			}
			*/
		// <---

		$player_data	= [
			$basePlayer->id	=> [
				'hp'	=> $basePlayer->getAttribute('less_hp'),
				'sp'	=> $basePlayer->getAttribute('less_sp'),
				'sta'	=> $basePlayer->getAttribute('less_sta')
			],
			$baseEnemy->id	=> [
				'hp'	=> $baseEnemy->getAttribute('less_hp'),
				'sp'	=> $baseEnemy->getAttribute('less_sp'),
				'sta'	=> $baseEnemy->getAttribute('less_sta')
			]
		];

		$batalha = Recordset::insert('batalha', array(
			'id_tipo'		=> 2,
			'id_player'		=> $basePlayer->id,
			'id_playerb'	=> $baseEnemy->id,
			'current_atk'	=> $basePlayer->id,
			'enemy'			=> $baseEnemy->id,
			'data_atk'		=> array('escape' => false, 'value' => 'NOW()'),
			'ip_a'			=> $basePlayer->ip,
			'ip_b'			=> $baseEnemy->ip,
			'level_a'		=> $basePlayer->level,
			'level_b'		=> $baseEnemy->level,
			'player_datab'	=> serialize($player_data),
			'treinamento'	=> 1
		));

		Fight::cleanup();
		Fight::cleanup($baseEnemy->id);

		// Registrador de batalha -->
			Recordset::insert('player_batalhas', array(
				'id_player'		=> $basePlayer->id,
				'id_playerb'	=> $baseEnemy->id
			));
		// <---

		// Registro de batalha --->
			Recordset::insert('batalha_log_acao', array(
				'id_player'		=> $baseEnemy->id,
				'id_playerb'	=> $basePlayer->id,
				'id_batalha'	=> $batalha
			));

			Recordset::insert('batalha_log_acao', array(
				'id_player'		=> $basePlayer->id,
				'id_playerb'	=> $baseEnemy->id,
				'id_batalha'	=> $batalha
			));
		// <---

		Recordset::update('player', array(
			'id_batalha'	=> $batalha,
			'id_sala'		=> 0
		), array(
			'id'			=> array('mode' => 'in', 'value' => $baseEnemy->id . ',' . $basePlayer->id, 'escape' => false)
		));

		Recordset::delete('batalha_sala', array(
			'id'	=> $_POST['bid']
		));

		SharedStore::S('_BATALHA_PVP_' . $batalha, gzserialize(new Fight($batalha)));

		redirect_to('dojo_batalha_pvp');
	}

	if(!isset($_POST['_pvpToken']) || (isset($_POST['_pvpToken']) && $_POST['_pvpToken'] != $_SESSION['_pvpToken'])) {
		if(!isset($_SESSION['_pvpToken'])) {
			error_log('batalha PVP ' . $basePlayer->id_batalha . 'UA --> ' . $_SERVER['HTTP_USER_AGENT'], E_USER_NOTICE);
		}

		die('jalert("'.t('actions.a37').', '.t('actions.a38').'", null, function () { location.reload() })');
	}

	if(!$basePlayer->id_batalha) {
		die('clearInterval(_pvpTimer); jalert("'.t('actions.a38').'", null, function() { location.reload() })');
	}

	function penalize($player, $batalha) {
		$last	= new DateTime($batalha['data_atk']);
		$now	= new DateTime();
		$diff	= $now->diff($last);

		$field	= $batalha['id_player'] == $player->id ? 'limite_tempo_a' : 'limite_tempo_b';

		if($diff->i >= 1 || $diff->s > 40) {
			Recordset::update('batalha', [
				$field	=> ['escape' => false, 'value' => $field . ' + 1']
			], [
				'id'	=> $batalha['id']
			]);
		}
	}

	// Timer do NPC --->
		$batalha		= Recordset::query('SELECT id, id_tipo, id_player, id_playerb, data_atk, finalizada, current_atk, limite_tempo_a, limite_tempo_b FROM batalha WHERE id=' . $basePlayer->id_batalha)->row_array();
		$penalize_field	= $batalha[$batalha['current_atk'] == $batalha['id_player'] ? 'limite_tempo_a' : 'limite_tempo_b'];
		$time			= 60 - ($penalize_field * 20);
		$future			= strtotime('+' . ($time < 20 ? 20 : $time) . ' seconds', strtotime($batalha['data_atk']));

		//if(!$_SESSION['universal']) {
			if(now() > $future && !$batalha['finalizada']) {
				if(!method_exists($basePlayer, 'getAttribute')) {
					$basePlayer = new Player($basePlayer->id);
				}

				$enemy_id	= $basePlayer->id == $batalha['id_player'] ? $batalha['id_playerb'] : $batalha['id_player'];

				Recordset::update('batalha', array(
					'finalizada'	=> 1,
					'vencedor'		=> $batalha['current_atk'] != $basePlayer->id ? $basePlayer->id : $enemy_id,
					'pvp_wo'		=> 1,
					'data_fim'		=> date('Y-m-d H:i:s')
				), array(
					'id'			=> $batalha['id']
				));

				Recordset::update('batalha_log_acao', array(
					'data_fim'		=> date('Y-m-d H:i:s'),
					'vencedor'		=> $batalha['current_atk'] != $basePlayer->id ? $basePlayer->id : $enemy_id,
					'empate'		=> 0,
					'fuga'			=> 0
				), array(
					'id_batalha'	=> $batalha['id']
				));

				$process	= true;

				// Forçar limpeza das batalhas
					Fight::cleanup();
					Fight::cleanup($enemy_id);
				// <---
			}
		//}
	// <---

	$batalha	= Recordset::query('SELECT * FROM batalha WHERE id=' . $basePlayer->id_batalha)->row_array();
	$process	= false;

	if(isset($_POST['itemID']) || isset($_POST['action']) || isset($_POST['flight']) || $batalha['finalizada'] || $batalha['do_updates'] || $batalha['do_updatesb']) {
		if($batalha['do_updates'] && $batalha['enemy'] == $basePlayer->id) {
			Recordset::update('batalha', array(
				'do_updates'	=> 0
			), array(
				'id'			=> $batalha['id']
			));
		} elseif($batalha['do_updatesb'] && $batalha['enemy'] != $basePlayer->id) {
			Recordset::update('batalha', array(
				'do_updatesb'	=> 0
			), array(
				'id'			=> $batalha['id']
			));
		}

		$process = true;
	}
?>
<?php if($process): ?>
	<?php
		if(!method_exists($basePlayer, 'getAttribute')) {
			$basePlayer = new Player($basePlayer->id);
		}

		$item		= isset($_POST['itemID']) && $basePlayer->hasItem($_POST['itemID']) ? $_POST['itemID'] : 0;
		$baseEnemy	= $batalha['id_player'] == $basePlayer->id ? new Player($batalha['id_playerb']) : new Player($batalha['id_player']);
		$turnos		= SharedStore::G('_TRL_' . $basePlayer->id, array());
		$fight		= gzunserialize(SharedStore::G('_BATALHA_PVP_' . $basePlayer->getAttribute('id_batalha')));
		$bonus		= Recordset::query('SELECT * FROM level_exp WHERE id=' . $baseEnemy->getAttribute('level'))->row_array();
		$dipl		= Player::diplOf($basePlayer->getAttribute('id_vila'), $baseEnemy->getAttribute('id_vila'));
		$titulo		= '';

		$conv_my	= percentf(85,$basePlayer->getAttribute('conv_calc'));
		$conv_en	= percentf(85,$baseEnemy->getAttribute('conv_calc'));

		$basePlayer->setLocalAttribute('less_conv', $baseEnemy->getAttribute('conv_calc'));
		$baseEnemy->setLocalAttribute('less_conv', $basePlayer->getAttribute('conv_calc'));

		$basePlayer->atCalc();
		$baseEnemy->atCalc();

		if(!$fight) {
			$fight = new Fight($batalha['id']);
			SharedStore::S('_BATALHA_PVP_' . $batalha['id'], gzserialize($fight));
		}

		if(!$fight->id) {
			$fight->id	= $batalha['id'];
		}

		if($batalha['finalizada']) {
			$_SESSION['has_used_nt_atk']	= false;
			$msg							= '';
			$msg2							= '';
			$vitoria						= false;
			$derrota						= false;
			$cvitoria						= false;
			$cderrota						= false;
			$dropped						= array();
			$dropped2						= array();
			$torneio						= false;
			$is_bingo_book					= $batalha['bingo_book'];
			
			if(!$batalha['treinamento'] && !$is_bingo_book){
				
				// Dropa item No pvp
				$droppable_types= [1,2,4,9,18,38];
				if (in_array($basePlayer->id_classe_tipo, [1, 4])) {
					$where	= ' AND req_tai=1';
				} else {
					$where	= ' AND req_nin=1';
				}
				
				$item	= Recordset::query('
							SELECT
								id,
								nome_' . Locale::get() . ' AS nome,
								drop_rate,
								drop_total

							FROM
								item

							WHERE
								`drop`=\'1\' AND
								removido =\'0\' AND
								id_tipo IN(' . implode(',',$droppable_types) . ') AND
								req_graduacao=' . $basePlayer->id_graduacao . $where . '

							ORDER BY RAND() LIMIT 1')->row_array();

				if(has_chance($item['drop_rate'])) {
					$dropped2[]	= array('nome' => $item['nome'], 'id_item' => $item['id'], 'total' => $item['drop_total']);
				}
				// Se o NPC dropou itens, verifica e adiciona no player
				if(sizeof($dropped2)) {
					$msg2 .= '<br /><br />'.t('actions.a84').':<ul>';

					foreach($dropped2 as $drop) {
						if (!$drop['id_item']) {
							continue;
						}

						$item	= Recordset::query('SELECT nome_'.Locale::get().' AS nome FROM item WHERE id=' . $drop['id_item'], true)->row_array();
						$msg2	.= '<li class="laranja">' . $drop['total'] . 'x ' . $item['nome'] . '</li>';

						$basePlayer->addItemW($drop['id_item'], $drop['total']);

						$item	= new Item($drop['id_item']);

						// Conquista --->
							arch_parse(NG_ARCH_ITEM_N, $basePlayer, NULL, $item,1);
						// <---
					}

					$msg2 .= '</ul>';
				}
				// Se não dropou itens normais eu vou tentar dropar uns equipamentos para os manolos.
				if(rand(1, 100) <= 20){

					// Sorteia um item randomico
					$basePlayer->generate_equipment($basePlayer);

					// Retorna o ultimo item dropado para o jogador
					$player_item	= Recordset::query('SELECT id FROM player_item WHERE id_player='. $basePlayer->id.' and id_item_tipo in (10,11,12,13,14,15,29) order by id desc limit 1')->row_array();

					// Retorna o nome e informações do item
					$player_item_atributo	= Recordset::query('SELECT nome FROM player_item_atributos WHERE id_player_item='. $player_item['id'])->row_array();

					// Adiciona a mensagem do drop para o maroto
					$msg2 .= '<br /><br />'.t('actions.a84').':<ul>';
					$msg2	.= '<li class="laranja">' . $player_item_atributo['nome'] . '</li>';
					$msg2 .= '</ul>';
				}
				
			}
			if($basePlayer->id_torneio) {
				$torneio	= torneio_batalha($basePlayer->id, $basePlayer->getAttribute('id_batalha'));
			}

			if($basePlayer->getAttribute('id_vila_atual') && $basePlayer->getAttribute('dentro_vila')) {
				//$exp	= $bonus['exp_dojo_pvp'];
				$exp	= $bonus['exp_mapa_pvp'];
			} else {
				$exp	= $bonus['exp_mapa_pvp'];
			}

			$ryou			= $bonus['ryou'];
			//$is_bingo_book	= is_bingo_book($basePlayer, $baseEnemy, $batalha['bingo_book'], true);
			//$is_bingo_book	= is_bingo_book($basePlayer, $baseEnemy, $batalha['bingo_book'], true);

			$stat			= Recordset::query('SELECT * FROM player_batalhas_log WHERE id_player=' . $basePlayer->id . ' AND id_playerb=' . $baseEnemy->id);

			if($stat->num_rows && (!$basePlayer->id_arena && !$basePlayer->id_exame_chuunin && !$basePlayer->id_torneio && !(!$basePlayer->id_random_queue && $batalha['id_tipo'] == 2))) {
				$stat_losing	= $stat->row()->vitorias - $stat->row()->derrotas <= -10;
			} else {
				$stat_losing	= false;
			}

			if($batalha['finalizada'] && $batalha['vencedor'] == $basePlayer->id && $stat_losing) {
				$ryou	+= percent(30, $ryou);
				$exp	+= percent(30, $exp);
			}

			// Batalha normal de dojo não da mais exp nem ryou, somente se for a random
			if(!$basePlayer->id_random_queue && $batalha['id_tipo'] == 2) {
				$exp	= 0;
				$ryou	= 0;
			}

			if($batalha['empate']) {
				$msg	= t('actions.a39');
				$titulo	= t('actions.a40');

				if(!$torneio && !$basePlayer->id_exame_chuunin) {
					if($basePlayer->id_arena) {
						$exp	= 0;
						$ryou	= 0;
						$basePlayer->setAttribute('id_arena', 0);
						$basePlayer->setAttribute('derrotas_arena', $basePlayer->getAttribute('derrotas_arena') + 1);
					}

					if(!$is_bingo_book && !(!$basePlayer->id_random_queue && $batalha['id_tipo'] == 2)) {
						$basePlayer->setAttribute('empates', $basePlayer->getAttribute('empates') + 1);

						//Adicionado para o Rank Diario
						Recordset::query("UPDATE player_batalhas_status SET empates = empates + 1, empates_semana = empates_semana + 1, empates_mes = empates_mes + 1, empates_geral = empates_geral + 1 WHERE id_player = ". $basePlayer->id ."");

						// Recompensa log
						Recordset::insert('player_recompensa_log', array(
							'fonte'		=> 'pvp',
							'id_player'	=> $basePlayer->id,
							'exp'		=> floor(@($exp / 6)),
							'ryou'		=> floor(@($ryou / 2)),
							'recebido'	=> 1
						));

						$basePlayer->setAttribute('exp',  $basePlayer->getAttribute('exp')  + floor(@($exp / 6)));
						$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') + floor(@($ryou / 2)));
					} else {
						$exp	= 0;
						$ryou	= 0;
					}

					//$msg .= t('actions.a41').' RY$ ' . floor(@($ryou / 2)) . ' '.t('geral.e').' ' . floor(@($exp / 6)) .' '. t('actions.a42').'<br />';
					$msg .= t('actions.a41').' RY$ ' . floor(@($ryou / 2)) .  t('actions.a42').'<br />';
					if(!$batalha['treinamento']){
						$msg .= $msg2;
					}
				}

				if ($basePlayer->id_exame_chuunin) {
					$msg .= '<br />'.t('actions.a48') . ' <a href="?secao=mapa_vila" class="linkTopo">'.t('actions.a49').'</a>';

					$basePlayer->setAttribute('less_hp', 0);
					$basePlayer->setAttribute('less_sp', 0);
					$basePlayer->setAttribute('less_sta', 0);
				} else {
					$basePlayer->setAttribute('hospital', '1');
					$basePlayer->setAttribute('id_vila_atual', $basePlayer->getAttribute('id_vila'));
					$basePlayer->setAttribute('dentro_vila', '1');

					// Atualiza para o mapa
					Recordset::update('player_posicao', array(
						'hospital'	=> 1
					), array(
						'id_player'	=> $basePlayer->id
					));

					hospital_vila_cura();

					$msg .= '<br />'.t('actions.a43').'</a>';
				}
			} elseif($batalha['finalizada']) {
				if($is_bingo_book) {
					$exp		= 0;
					$ryou		= 0;
					$cvitoria	= false;
					$cderrota	= false;
				}

				if($batalha['flight_id']) {
					if($batalha['vencedor'] == $basePlayer->id) { // Eu ganhei(fuga)
						$msg		= t('actions.a44');
						$titulo		= t('actions.a45');

						$vitoria	= true;
						$cvitoria	= false;

						$exp		= $exp 	? @floor($exp / 2)  : 0;
						$ryou		= $ryou ? @floor($ryou / 2) : 0;
					} else {
						if($basePlayer->getAttribute('id_vila_atual') && $basePlayer->getAttribute('dentro_vila')) {
							$exp	= 0; //$bonus['exp_dojo_pvp'];
						} else {
							$exp	= 0; //$bonus['exp_mapa_pvp'];
						}

						$titulo	= t('actions.a46').' ';
						$msg	= ' '.t('actions.a47') .' '. $exp .' '. t('actions.a42').'!';

						$basePlayer->setAttribute('fugas', $basePlayer->getAttribute('fugas') + 1);

						//Adicionado para o Rank Diario
						Recordset::query("UPDATE player_batalhas_status SET fugas = fugas + 1, fugas_semana = fugas_semana + 1, fugas_mes = fugas_mes + 1, fugas_geral = fugas_geral + 1 WHERE id_player = ". $basePlayer->id ."");

						// Recompensa log
						Recordset::insert('player_recompensa_log', array(
							'fonte'		=> 'pvp',
							'id_player'	=> $basePlayer->id,
							'exp'		=> $exp,
							'recebido'	=> 1
						));

						$basePlayer->setAttribute('exp', $basePlayer->getAttribute('exp') + $exp);
						if(!$batalha['treinamento']){
							$msg .= $msg2;
						}
						if($batalha['id_tipo'] == 2) {
							$msg .= '<br /><br />'.t('actions.a48').' <a href="?secao=dojo" class="linkTopo">Dojo</a>';
						} else {
							if($basePlayer->getAttribute('id_vila_atual')) {
								$msg .= '<br /><br/>'.t('actions.a48').' <a href="?secao=mapa_vila" class="linkTopo">'.t('actions.a49').'</a>';
							} else {
								$msg .= '<br /><br/>'.t('actions.a48').' <a href="?secao=mapa" class="linkTopo">'.t('actions.a50').'</a>';
							}
						}
					}
				} elseif($batalha['pvp_wo']) {
					if($batalha['vencedor'] == $basePlayer->id) { // Eu ganhei(inatividade)
						$msg		= t('actions.a51');
						$titulo		= t('actions.a52');

						$vitoria	= true;
						$cvitoria	= true;
						
						Recordset::query("UPDATE player_flags set wo_wins = wo_wins + 1 
									 WHERE id_player = ". $basePlayer->id);
					} else {
						$msg 		= t('actions.a53');
						$titulo		= t('actions.a54');

						$derrota	= true;
						$cderrota	= true;

						$exp		= 0;
						$ryou		= 0;
						
						Recordset::query("UPDATE player_flags set wo_looses = wo_looses + 1 
									 WHERE id_player = ". $basePlayer->id);

						$basePlayer->setAttribute('less_hp', $basePlayer->getAttribute('max_hp'));
						$basePlayer->setAttribute('less_sp', $basePlayer->getAttribute('max_sp'));
						$basePlayer->setAttribute('less_sta', $basePlayer->getAttribute('max_sta'));
					}

					/*
					if($batalha['id_tipo'] == 2) {
						$msg .= 'Voltar ao <a href="?secao=dojo">Dojo</a>';
					} else {
						if($basePlayer->getAtttribute('id_vila_atual')) {
							$msg .= 'Voltar ao <a href="?secao=mapa_vila">Mapa Vila</a>';
						} else {
							$msg .= 'Voltar ao <a href="?secao=mapa">Mapa</a>';
						}
					}
					*/
				} else {
					if($batalha['vencedor'] == $basePlayer->id) { // Eu ganhei
						$titulo		= t('actions.a55');
						$msg		=  '';
						$vitoria	= true;
						$cvitoria	= true;
					} else { // Eu perdi
						$derrota	= true;
						$cderrota	= true;

						$msg	= t('actions.a56');
						$titulo	= t('actions.a57');
					}
				}

				if($basePlayer->id_arena || $basePlayer->id_exame_chuunin) {
					$cderrota	= false;
				}

				if($vitoria) {
					// Recompensa de exp/ryou --->
						if(!$torneio) {
							if($basePlayer->dentro_vila) {
								$exp	+= percent($basePlayer->bonus_vila['dojo_exp_pvp'], $exp);
								$ryou	+= percent($basePlayer->bonus_vila['dojo_ryou_pvp'], $ryou);
								
								if($basePlayer->hasMissaoDiariaPlayer(8)->total){
									// Adiciona os contadores nas Missões de Dojo PVP
									Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
												 WHERE id_player = ". $basePlayer->id." 
												 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 8) 
												 AND completo = 0 ");
								}
							} else {
								$exp	+= percent($basePlayer->bonus_vila['mapa_exp'], $exp);
								$ryou	+= percent($basePlayer->bonus_vila['mapa_ryou'], $ryou);
								
								if($basePlayer->hasMissaoDiariaPlayer(9)->total){
									// Adiciona os contadores nas Missões de Dojo PVP
									Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
												 WHERE id_player = ". $basePlayer->id." 
												 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 9) 
												 AND completo = 0 ");
								}
							}

							if($basePlayer->id_arena || $basePlayer->id_exame_chuunin) {
								$exp		= 0;
								$ryou		= 0;
								$cvitoria	= false;
							}

							// Recompensa log
							Recordset::insert('player_recompensa_log', array(
								'fonte'		=> 'pvp',
								'id_player'	=> $basePlayer->id,
								'exp'		=> $exp,
								'ryou'		=> $ryou,
								'recebido'	=> 1
							));

							$basePlayer->setAttribute('exp',  $basePlayer->getAttribute('exp')  + $exp);
							$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') + $ryou);

							$msg .= t('actions.a41').' RY$ ' . $ryou . ' '.t('geral.e').' ' . $exp .' '. t('actions.a42').'<br />';
							if(!$batalha['treinamento']){
								$msg .= $msg2;
							}

							// Exp da vila caso o inimigo seja um atacante --->
								if(
									$basePlayer->id_vila_atual == $basePlayer->id_vila &&
									!$basePlayer->dentro_vila &&
									$baseEnemy->getAttribute('missao_invasao') &&
									$baseEnemy->getAttribute('missao_invasao_vila') == $basePlayer->id_vila
								) {
									vila_exp(10);
								}
							// <---
						}
					// <---
					if(!$batalha['treinamento']){
						equipe_exp(30);
					}
					// Exp equipe
					
					// Missão de matar players de outra vila
					if($basePlayer->getAttribute('id_vila_atual') && $basePlayer->getAttribute('id_missao_especial') && $dipl == 2 && !$torneio && $batalha['id_tipo'] == 4 && $cvitoria && $basePlayer->getAttribute('id_arena')==0 && !$is_bingo_book) { // é inimigo
							Recordset::update('player_quest_npc_item', array(
								'npc_total'			=> array('escape' => false, 'value' => 'npc_total+1'),
							), array(
								'id_player'			=> $basePlayer->id,
								'id_player_quest'	=> $basePlayer->getAttribute('id_missao_especial')
							));
					}

					// Se for inimigo da reputação --->
						if($dipl == 2) {
							//reputacao($basePlayer->id, $basePlayer->getAttribute('id_vila'), 10);
						}
					// <---

					// Torneio --->
					if($basePlayer->getAttribute('id_torneio')) {
						if($torneio) {
							torneio_win($basePlayer->id, $basePlayer->getAttribute('id_batalha'));
							// Missões diárias de Torneio PVP
							if($basePlayer->hasMissaoDiariaPlayer(21)->total){
								// Adiciona os contadores nos torneios npc
								Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
											 WHERE id_player = ". $basePlayer->id." 
											 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 21) 
											 AND completo = 0 ");
							}

							$cvitoria	= false;
						}
					}
					// <---

					if($cvitoria && !$is_bingo_book && !(!$basePlayer->id_random_queue && $batalha['id_tipo'] == 2)) {
						if($basePlayer->getAttribute('dentro_vila')) {
							$basePlayer->setAttribute('vitorias', $basePlayer->getAttribute('vitorias') + 1);

							//Adicionado para o Rank Diario
							Recordset::query("UPDATE player_batalhas_status SET vitorias = vitorias + 1, vitorias_semana = vitorias_semana + 1, vitorias_mes = vitorias_mes + 1, vitorias_geral = vitorias_geral + 1 WHERE id_player = ". $basePlayer->id ."");

						} else {
							$basePlayer->setAttribute('vitorias_f', $basePlayer->getAttribute('vitorias_f') + 1);

							//Adicionado para o Rank Diario
							Recordset::query("UPDATE player_batalhas_status SET vitorias_f = vitorias_f + 1, vitorias_f_semana = vitorias_f_semana + 1, vitorias_f_mes = vitorias_f_mes + 1, vitorias_f_geral = vitorias_f_geral + 1 WHERE id_player = ". $basePlayer->id ."");
						}
					}
					if($batalha['id_tipo'] == 2) {
						$msg .= '<br /><br/>'.t('actions.a48').' <a href="?secao=dojo" class="linkTopo">Dojo</a>';
					} else {
						if($basePlayer->getAttribute('id_vila_atual')) {
							$msg .= '<br /><br/>'.t('actions.a48').' <a href="?secao=mapa_vila" class="linkTopo">'.t('actions.a49').'</a>';
						} else {
							$msg .= '<br /><br/>'.t('actions.a48').' <a href="?secao=mapa" class="linkTopo">'.t('actions.a50').'</a>';
						}
					}

					$template	= msg(2,'' . $titulo . '','%msg', true);

					// Conquista --->
						if($basePlayer->id_vila_atual && !$basePlayer->dentro_vila) {
							arch_parse(NG_ARCH_PVP_VILA, $basePlayer, $baseEnemy);
						} elseif($basePlayer->id_vila_atual && $basePlayer->dentro_vila) {
							arch_parse(NG_ARCH_PVP_DOJO, $basePlayer, $baseEnemy);
						} elseif(!$basePlayer->id_vila_atual) {
							arch_parse(NG_ARCH_PVP_MAPA, $basePlayer, $baseEnemy);
						}
					// <---

					if($basePlayer->id_arena) {
						$basePlayer->setAttribute('vitorias_arena', $basePlayer->getAttribute('vitorias_arena') + 1);

						Recordset::insert('arena_log', array(
							'arena_id'		=> $basePlayer->id_arena,
							'player_id'		=> $basePlayer->id,
							'inimigo_id'	=> $baseEnemy->id
						));

						arch_parse(NG_ARCH_ARENA, $basePlayer, 2, $basePlayer->id_vila_atual);

						//global_message(sprintf(t('arena.msg.battle'), $basePlayer->nome, $baseEnemy->nome));
					}

					// Matar o Kage da vila --->
						$enemy_ranking	= Recordset::query('SELECT posicao_vila FROM ranking WHERE id_player=' . $baseEnemy->id);

						if($enemy_ranking->num_rows) {
							if($enemy_ranking->row()->posicao_vila == 1) {
								global_message2('msg_global.kage', array($baseEnemy->nome, $baseEnemy->nome_vila, $basePlayer->nome));
							}
						}
					// <---

					// Alerta de derrotar um inigimo com bijuuu --->
						$enemy_bijuu	= Recordset::query('
							SELECT
								a.nome_' . Locale::get() . ' AS nome

							FROM
								item a JOIN player_item b ON b.id_item=a.id

							WHERE
								b.id_item_tipo=31');

						if($enemy_bijuu->num_rows) {
							global_message2('global_msg.bijuu', array($basePlayer->nome, $enemy_bijuu->row()->nome, $baseEnemy->nome));
						}
					// <---

					// Recompensa de bingo book
					if (is_bingo_book_guild($basePlayer, $baseEnemy)) {
						guild_objetivo_exp($basePlayer, 5);
					}

					if (is_bingo_book_player($basePlayer, $baseEnemy)) {
						vila_objetivo_exp($basePlayer, 5);
					}

					// Bingo book
					bingo_book_morto($basePlayer, $baseEnemy, false);
				}

				if(($derrota || $batalha['empate']) && !$basePlayer->id_exame_chuunin) {
					$basePlayer->setAttribute('hospital', '1');
					$basePlayer->setAttribute('id_arena', '0');
					$basePlayer->setAttribute('dentro_vila', '1');
					$basePlayer->setAttribute('id_vila_atual', $basePlayer->getAttribute('id_vila'));

					if($basePlayer->id_arena) {
						$basePlayer->setAttribute('derrotas_arena', $basePlayer->getAttribute('derrotas_arena') + 1);
					}

					// Atualiza para o mapa
					Recordset::update('player_posicao', array(
						'hospital'	=> 1
					), array(
						'id_player'	=> $basePlayer->id
					));

					hospital_vila_cura();
				}

				if($derrota) {
					if(!$batalha['treinamento']){
						$msg .= $msg2;
					}
					if(!$basePlayer->id_random_queue && $batalha['id_tipo'] == 2) {
						$msg .= '<br /><br />'.t('actions.a59').' <a href="?secao=dojo" class="linkTopo">Dojo</a>';
					} else {
						if ($basePlayer->id_exame_chuunin) {
							if ($basePlayer->exame_chuunin_etapa == 2) {
								$msg .= '<br /><br/>'.t('actions.a59').' <a href="?secao=personagem_status" class="linkTopo">Status</a>';
							} else {
								$msg .= '<br /><br />'.t('actions.a59').' <a href="?secao=mapa_vila" class="linkTopo">'.t('actions.a49').'</a>';
							}
						} else {
							$msg .= '<br /><br />'.t('actions.a59').' <a href="?secao=hospital_quarto" class="linkTopo">'.t('actions.a58').'</a>';
						}

						if($cderrota && !$is_bingo_book) {
							if ($batalha['id_tipo'] == 4) {
								$basePlayer->setAttribute('derrotas_f', $basePlayer->getAttribute('derrotas_f') + 1);

								//Adicionado para o Rank Diario
								Recordset::query("UPDATE player_batalhas_status SET derrotas_f = derrotas_f + 1, derrotas_f_semana = derrotas_f_semana + 1, derrotas_f_mes = derrotas_f_mes + 1, derrotas_f_geral = derrotas_f_geral + 1 WHERE id_player = ". $basePlayer->id);
							} else {
								$basePlayer->setAttribute('derrotas', $basePlayer->getAttribute('derrotas') + 1);

								//Adicionado para o Rank Diario
								Recordset::query("UPDATE player_batalhas_status SET derrotas = derrotas + 1, derrotas_semana = derrotas_semana + 1, derrotas_mes = derrotas_mes + 1, derrotas_geral = derrotas_geral + 1 WHERE id_player = ". $basePlayer->id);
							}
						}
					}

					// Torneio --->
					if($basePlayer->getAttribute('id_torneio')) {
						if($torneio) {
							torneio_loss($basePlayer->id, $basePlayer->getAttribute('id_batalha'));

							$cderrota	= false;
						}
					}
					// <---
				}

				// Exame chuunin
				if ($basePlayer->id_exame_chuunin) {
					if ($vitoria) {
						if ($basePlayer->exame_chuunin_etapa == 1) {
							$p_item_sky		= $basePlayer->getItem(22916);
							$p_item_earth	= $basePlayer->getItem(22917);

							$e_item_sky		= $baseEnemy->getItem(22916);
							$e_item_earth	= $baseEnemy->getItem(22917);

							$choosen_item	= false;

							if (!$p_item_sky && $e_item_sky) {
								$choosen_item	= $e_item_sky;
							} elseif(!$p_item_earth && $e_item_earth) {
								$choosen_item	= $e_item_earth;
							}

							if ($choosen_item) {
								$is_step2	= false;

								if ($p_item_sky && $choosen_item->id == 22917 || $p_item_earth && $choosen_item->id == 22916) {
									$exam	= Recordset::query('SELECT * FROM exame_chuunin WHERE id=' . $basePlayer->id_exame_chuunin, true)->row_array();

									Recordset::update('player', [
										'exame_chuunin_etapa'	=> 2,
										'id_vila_atual'			=> $basePlayer->id_vila,
										'id_exame_chuunin'		=> 0,
										'ryou'					=> ['escape' => false, 'value' => 'ryou + ' . $exam['ryous']],
										'treino_total'			=> ['escape' => false, 'value' => 'treino_total + ' . $exam['treino']]
									], [
										'id'	=> $basePlayer->id
									]);

									Recordset::insert('player_exame', [
										'id_exame_chuunin'	=> $basePlayer->id_exame_chuunin,
										'id_player'			=> $basePlayer->id
									]);

									$is_step2	= true;

									arch_parse(NG_ARCH_EXAME, $basePlayer);
								}

								if ($is_step2) {
									Recordset::delete('player_item', ['id' => $choosen_item->uid]);

									if ($p_item_sky) {
										Recordset::delete('player_item', ['id' => $p_item_sky->uid]);
									}

									if ($p_item_earth) {
										Recordset::delete('player_item', ['id' => $p_item_earth->uid]);
									}
								} else {
									Recordset::update('player_item', [
										'id_player'	=> $basePlayer->id
									], [
										'id'		=> $choosen_item->uid
									]);
								}

								$msg	.=	'<br />' . t('exame.msg_item') . '&nbsp;' . $choosen_item->nome;

								if ($is_step2) {
									$msg	.= "<br /><br />" . t('exame.msg_step');
								}
							}
						}
					} else {
						if ($basePlayer->exame_chuunin_etapa == 2) {
							Recordset::update('player', [
								'exame_chuunin_etapa'	=> 1,
								'id_vila_atual'			=> $basePlayer->id_vila,
								'dentro_vila'			=> 1,
								'id_exame_chuunin'		=> 0
							], [
								'id'	=> $basePlayer->id
							]);
						}
					}

					Recordset::update('player', [
						'less_sta'	=> 0,
						'less_sp'	=> 0,
						'less_hp'	=> 0
					], [
						'id'	=> $basePlayer->id
					]);
				}
			}

			// Se não ganhou
			if(!$vitoria) {
				$template	= msg(4,'' . $titulo . '','%msg', true);
			}

			// Torneio = não da pontos
			if(!$torneio && !(!$basePlayer->id_random_queue && $batalha['id_tipo'] == 2)) {
				if($vitoria && $cvitoria) {
					//$msg	.= sprintf(t('actions.a272'), 2);

					/*Recordset::update('player', array(
						'ponto_batalha'	=> array('escape' => false, 'value' => 'ponto_batalha + 2')
					), array(
						'id'		=> $basePlayer->id
					));*/
				} else {
					if(!$batalha['flight_id'] && !$batalha['empate'] && $cderrota) {
						//$msg	.= sprintf(t('actions.a272'), 1);

						/*Recordset::update('player', array(
							'ponto_batalha'	=> array('escape' => false, 'value' => 'ponto_batalha + 1')
						), array(
							'id'		=> $basePlayer->id
						));*/
					}
				}

				// Ponto extra de treino mapa pvp
				if(Player::getFlag("pvp_treino_vezes", $basePlayer->id) <= 60 && $batalha['id_tipo'] == 4) {
					$basePlayer->setFlag("pvp_treino_vezes", (int)Player::getFlag("pvp_treino_vezes", $basePlayer->id) + 1);

					Recordset::query("UPDATE player SET treino_total=treino_total + 50 WHERE id=" . $basePlayer->id);

					$msg .= "<br /> ".t('actions.a61')."";
				}

				// ponto treino dojo rnd
				//if($vitoria && $basePlayer->id_random_queue && $batalha['id_tipo'] == 2 && $basePlayer->level > 15) {
				if($basePlayer->id_random_queue && $batalha['id_tipo'] == 2 && $basePlayer->level > 15) {
					Recordset::update('player', [
						'treino_total'	=> ['escape' => false, 'value' => 'treino_total+50']
					], [
						'id'			=> $basePlayer->id
					]);

					$msg .= "<br /> " . t('actions.a61');
				}

				$enemy_has_item	= false;
				$i_have_item	= false;
				$evento_vila	= Recordset::query('SELECT id, tipo, nome_br, nome_en FROM evento_vila WHERE iniciado=1 ORDER BY RAND()');

				if($evento_vila->num_rows && $basePlayer->id_vila != $baseEnemy->id_vila && $vitoria) {
					switch($evento_vila->row()->tipo) {
						case 'bijuu';	$evento_item_tipo	= '34'; break;
						case 'armas';	$evento_item_tipo	= '35'; break;
						case 'espadas';	$evento_item_tipo	= '36'; break;
					}

					$evento_item		= Recordset::query('
						SELECT
							a.id,
							b.nome_' . Locale::get() . ' AS nome

						FROM
							player_item a JOIN item b ON b.id=a.id_item

						WHERE
							a.id_player=' . $baseEnemy->id . ' AND
							a.id_item_tipo=' . $evento_item_tipo . '

						ORDER BY RAND() LIMIT 1
					');

					$evento_item_mine	= Recordset::query('
						SELECT
							a.id,
							b.nome_' . Locale::get() . ' AS nome

						FROM
							player_item a JOIN item b ON b.id=a.id_item

						WHERE
							a.id_player=' . $basePlayer->id . ' AND
							a.id_item_tipo=' . $evento_item_tipo . '

						ORDER BY RAND() LIMIT 1
					');

					if($evento_item->num_rows) {
						$enemy_has_item	= 1;
						$evento_item	= $evento_item->row_array();
					}

					if($evento_item_mine->num_rows) {
						$i_have_item	= true;
					}
				}

				if(has_chance(100) && $enemy_has_item && !$i_have_item && $cvitoria) {
					$msg	.= '<br />'.t('fight.f18').' <b>' . $evento_item['nome'] . '</b>';

					Recordset::update('player_item', array(
						'id_player'	=> $basePlayer->id
					), array(
						'id'		=> $evento_item['id']
					));

					guild_objetivo_exp($basePlayer, 4);
					vila_objetivo_exp($basePlayer, 4);

					global_message2('msg_global.evento_vila_item', array($basePlayer->nome, $evento_item['nome']));

					$total	= Recordset::query('SELECT COUNT(id) AS total FROM item WHERE id_tipo=' . $evento_item_tipo, true)->row()->total;
					$have	= Recordset::query('SELECT SUM(CASE WHEN (SELECT aa.id_vila FROM player aa WHERE aa.id=a.id_player) = ' . $basePlayer->id_vila . ' THEN 1 ELSE 0 END) AS total FROM player_item a WHERE id_item_tipo=' . $evento_item_tipo)->row()->total;

					if($have >= $total) {
						global_message2('msg_global.evento_vila', array($basePlayer->nome_vila, $evento_vila['nome_' . Locale::get()]));

						Recordset::update('evento_vila', array(
							'finalizado'	=> 1
						), array(
							'id'			=> $evento_vila->row()->id
						));
					}
				}
			}

			echo "$('#cnFinal').html('" . scriptslashes(str_replace('%msg', $msg, $template)) . "');";
			echo "$('#d-actionbar').html(''); clearInterval(_pvpTimer);";

			$pvp_items	= array();

			// Limpa os dados da batalha na memória --->
				SharedStore::D('_BATALHA_PVP_' . $batalha['id'], 300);
			// <--

			// Esse é o objetivo de "batalhas", então, ganhar, perder ou empatar, vai contar
			if(!$torneio && !$basePlayer->id_arena && !(!$basePlayer->id_random_queue && $batalha['id_tipo'] == 2)) {
				guild_objetivo_exp($basePlayer, 2);
				vila_objetivo_exp($basePlayer, 2);
			}

			if($vitoria && !$torneio && !$basePlayer->id_arena && !(!$basePlayer->id_random_queue && $batalha['id_tipo'] == 2)) {
				if ($baseEnemy->hasItem([1459,1460,1461,1462,1463,1464,1465,1466,1467,1468])) {
					guild_objetivo_exp($basePlayer, 1);
					vila_objetivo_exp($basePlayer, 1);
				}

				if (Player::diplOf($basePlayer->id_vila_atual, $basePlayer->id_vila) == 2) {
					guild_objetivo_exp($basePlayer, 3);
					vila_objetivo_exp($basePlayer, 3);
				}

				if (!$basePlayer->dentro_vila && !$basePlayer->id_vila_atual) {
					guild_objetivo_exp($basePlayer, 7);
					vila_objetivo_exp($basePlayer, 7);
				}

				if ($baseEnemy->hasItem([22726,22727,22728,22729,22730,22731,22732])) {
					guild_objetivo_exp($basePlayer, 9);
					vila_objetivo_exp($basePlayer, 9);
				}

				if(gHasItemW(20291, $baseEnemy->id, NULL, 24)) {
					guild_objetivo_exp($basePlayer, 10);
					vila_objetivo_exp($basePlayer, 10);
				}

				/*if(gHasItemW(1862, $baseEnemy->id, NULL, 24)) {
					vila_objetivo_exp($basePlayer, 12);
				}*/

				if(gHasItemW(1863, $baseEnemy->id, NULL, 24)) {
					vila_objetivo_exp($basePlayer, 13);
				}
			}

			// Log
			if (!(!$basePlayer->id_random_queue && $batalha['id_tipo'] == 2)) {
				if($batalha['empate']) {
					batalha_log($basePlayer->id, $baseEnemy->id, false, true);
					//batalha_log($baseEnemy->id, $basePlayer->id, false, true);
				} else {
					batalha_log($basePlayer->id, $baseEnemy->id, $basePlayer->id == $batalha['vencedor'], false);
					//batalha_log($baseEnemy->id, $basePlayer->id, $baseEnemy->id == $batalha['vencedor'], false);
				}
			}

			// Batalha normal de dojo não vai pro hospital e restaura os stats
			if(!$basePlayer->id_random_queue && $batalha['id_tipo'] == 2) {
				$data	= unserialize($batalha['player_datab']);
				$data	= $data[$basePlayer->id];

				$basePlayer->setAttribute('hospital', 0);
				$basePlayer->setAttribute('less_hp',  (int)$data['hp']);
				$basePlayer->setAttribute('less_sp',  (int)$data['sp']);
				$basePlayer->setAttribute('less_sta', (int)$data['sta']);
			}

			$basePlayer->setAttribute('id_batalha', 0);
			$basePlayer->setAttribute('id_random_queue', 0);
		}

		if((isset($_POST['action']) && !$item && !isset($_POST['flight'])) || (isset($_POST['flight']) && $item)) {
			die('jalert("Ação inválida")');
		} elseif(isset($_POST['action']) && ($item && !isset($_POST['flight']) || !$item && isset($_POST['flight'])) && !$batalha['finalizada']) {
			if($item) {
				$item = $basePlayer->getItem($item);
				$item->setPlayerInstance($basePlayer);
				$item->parseLevel();
			}

			if($batalha['current_atk'] != $basePlayer->id) {
				die('jalert("'.t('actions.a62').'")');
			}

			Recordset::update('batalha', array(
				'do_updates'	=> 1,
				'do_updatesb'	=> 1
			), array(
				'id'			=> $batalha['id']
			));

			if($_POST['action'] == 1) {
				if(isset($_POST['flight'])) {
					/*
					$item				= new Item(1);
					$item->flight		= true;
					$item->consume_sp	= 30;
					$item->consume_sta	= 30;
					*/
					die('jalert("Função Retirada do jogo")');
				} else {
					// Verifica se tem stats para usar --->
						if($item->consume_sp > $basePlayer->getAttribute('sp')) {
							die('jalert("'.t('actions.a63').'")');
						}

						if($item->consume_sta > $basePlayer->getAttribute('sta')) {
							die('jalert("'.t('actions.a64').'")');
						}
					// <---
				}

				// Controle dos turnos --->
					if($item->getAttribute('turnos') && isset($turnos[$item->id])) {
						die('jalert("'.t('actions.a65').'");');
					} elseif($item->getAttribute('turnos')) {
						$turnos[$item->id] = $item->getAttribute('turnos');
					}

					// Rotaciona os turnos dos golpes em cooldown --->
						$new_turnos = array();

						foreach($turnos as $k => $v) {
							$turnos[$k]--;

							if($turnos[$k] > 0) {
								$new_turnos[$k] = $turnos[$k];
							}
						}

						$turnos = $new_turnos;
					// <---

					// Salva os dados de turnos
					SharedStore::S('_TRL_' . $basePlayer->id, $new_turnos);
				// <---

				$_SESSION['has_used_nt_atk'] = false;

				$basePlayer->setAtkItem($item);

				$lb		= false;
				$data	= array(
					'data_atk'	=> date('Y-m-d H:i:s')
				);

				if($batalha['enemy'] == $basePlayer->id) {
					// Infelizmente precisa disso aki pra recalcular a precisão apos levar um gen
						if(!$fight->_player->getAtkItem()->flight) {
							$item2 = $baseEnemy->getItem($fight->_player->getAtkItem()->id);
							$item2->setPlayerInstance($baseEnemy);
							$item2->parseLevel();

							$baseEnemy->setAtkItem($item2);
							$fight->addPlayer($baseEnemy);
						}
					//

					$fight->addEnemy($basePlayer);

					$fight->Process();

					$basePlayer->rotateModifiers();
					$baseEnemy->rotateModifiers();

					$p	= $fight->_player;
					$e	= $fight->_enemy;

					// Update feliz --->
						$data['pvp_log']	= $batalha['pvp_log'] . $fight->log;

						if($fight->flight) { // Fuga
							$data['flight_id']	= $fight->flightId;
							$data['vencedor']	= $fight->flightId == $basePlayer->id ? $baseEnemy->id : $basePlayer->id;
							$data['finalizada']	= 1;

							$lb	= true;
						} else { // Condições normais
							if(
							($p->getAttribute('hp') < 10 || $p->getAttribute('sp') < 10 || $p->getAttribute('sta') < 10) &&
							($e->getAttribute('hp') < 10 || $e->getAttribute('sp') < 10 || $e->getAttribute('sta') < 10)
							  ) { // Empate
								$data['empate']		= 1;
								$data['finalizada']	= 1;
								$data['data_fim']	= date('Y-m-d H:i:s');

								$lb	= true;
							} elseif($e->getAttribute('hp') < 10 || $e->getAttribute('sp') < 10 || $e->getAttribute('sta') < 10) { // Vitória
								$data['vencedor']	= $p->id;
								$data['finalizada']	= 1;
								$data['data_fim']	= date('Y-m-d H:i:s');

								$lb	= true;
							} elseif($p->getAttribute('hp') < 10 || $p->getAttribute('sp') < 10 || $p->getAttribute('sta') < 10) { // Derrota
								$data['vencedor']	= $e->id;
								$data['finalizada']	= 1;
								$data['data_fim']	= date('Y-m-d H:i:s');

								$lb	= true;
							}
						}

						if(isset($data['finalizada']) && $data['finalizada']) { //!!!
							$player_data	= array(
								$p->id		=> array(
									'hp'	=> $p->getAttribute('hp'),
									'sp'	=> $p->getAttribute('sp'),
									'sta'	=> $p->getAttribute('sta')
								),
								$e->id		=> array(
									'hp'	=> $e->getAttribute('hp'),
									'sp'	=> $e->getAttribute('sp'),
									'sta'	=> $e->getAttribute('sta')
								)
							);

							$data['player_data']	= serialize($player_data);
						}
					// <--
				} else {
					$fight->addPlayer($basePlayer);
				}

				$data = array_merge($data, array('current_atk' => $batalha['current_atk'] == $basePlayer->id ? $baseEnemy->id : $basePlayer->id));

				Recordset::update('batalha', $data, array(
					'id'	=> $basePlayer->getAttribute('id_batalha')
				));

				if($lb) {
					Recordset::update('batalha_log_acao', array(
						'data_fim'		=> date('Y-m-d H:i:s'),
						'log'			=> $data['pvp_log'],
						'vencedor'		=> isset($data['vencedor']) ? $data['vencedor'] : 0,
						'empate'		=> isset($data['empate']) ? '1' : 0,
						'fuga'			=> isset($data['flight_id']) && $data['flight_id'] ? 1 : 0
					), array(
						'id_batalha'	=> $batalha['id']
					));
				}

				SharedStore::S('_BATALHA_PVP_' . $basePlayer->getAttribute('id_batalha'), gzserialize($fight));

				// Contator de exp de golpe no fim da batalha
				if(!in_array($item->id, array(4, 5, 6, 7)) && !$item->flight) {
					$pvp_items[$item->id] = 1;
				}

				penalize($basePlayer, $batalha);
			} elseif($_POST['action'] == 2 && $item) { // Buffs/Gens
				if($item->getAttribute('turnos') && isset($turnos[$item->id])) {
					die('jalert("'.t('actions.a65').'");');
				}

				Recordset::update('batalha', array(
					'do_updates'	=> 1,
					'do_updatesb'	=> 1
				), array(
					'id'			=> $batalha['id']
				));

				$arModifiers	= $basePlayer->getModifiers();
				$mod			= $item->getModifiers();
				$pass			= true;

				if($item->getAttribute('id_tipo') == 1) {
					foreach($arModifiers as $mod) {
						$mod_item = Recordset::query("SELECT id_tipo FROM item WHERE id=" . (int)$mod['id'], true)->row_array();

						if(($mod['o_direction'] == 0 && $mod['direction'] == 0) && $mod_item['id_tipo'] == 1) {
							$pass = false;

							break;
						}
					}

					if($pass) {
						if($basePlayer->removeItem($item)) {
							echo '$("#atki-' . $item->id . '").parent().hide("explode").remove();' . PHP_EOL;
						} else {
							echo '_items[' . $item->id . '].t = ' . ($item->getAttribute('total') - 1) . ' ;' . PHP_EOL;
						}
					}

					$o_direction = 0;
				} else {
					if(isset($_SESSION['has_used_nt_atk']) && $_SESSION['has_used_nt_atk'] && $item->id_tipo != 41) {
						die('jalert("'.t('actions.a66').'")');
					}

					// So pode um genjutsu/buff por vez --->
						if(
							!$mod['target_ken'] &&!$mod['target_tai'] && !$mod['target_nin'] && !$mod['target_gen'] && !$mod['target_agi'] && !$mod['target_con'] && !$mod['target_forc'] && !$mod['target_inte'] && !$mod['target_res'] && !$mod['target_atk_fisico'] && !$mod['target_atk_magico']	&&
							!$mod['target_def_base'] && !$mod['target_prec_fisico']	&& !$mod['target_prec_magico']	&& !$mod['target_crit_min']	&& !$mod['target_crit_max'] && !$mod['target_crit_total'] &&
							!$mod['target_esq_min']	&& !$mod['target_esq_max'] && !$mod['target_esq_total']	&& !$mod['target_def_fisico'] && !$mod['target_def_magico'] && !$mod['target_esq']	&& !$mod['target_det'] &&!$mod['target_conv'] && !$mod['target_conc'] && !$mod['target_esquiva']
						) {
							$o_direction = 0; // BUFF
						} elseif(
							!$mod['self_ken'] && !$mod['self_tai'] && !$mod['self_nin'] && !$mod['self_gen'] && !$mod['self_agi'] && !$mod['self_con'] && !$mod['self_forc'] && !$mod['self_inte'] && !$mod['self_res'] && !$mod['self_atk_fisico']	&& !$mod['self_atk_magico']	&&
							!$mod['self_def_base'] && !$mod['self_prec_fisico']	&& !$mod['self_prec_magico'] && !$mod['self_crit_min'] && !$mod['self_crit_max'] && !$mod['self_crit_total']	&&
							!$mod['self_esq_min'] && !$mod['self_esq_max'] && !$mod['self_esq_total']		&& !$mod['self_def_fisico'] && !$mod['self_def_magico'] && !$mod['self_esq'] && !$mod['self_det'] && !$mod['self_conv'] && !$mod['self_conc'] && !$mod['self_esquiva']
						) {
							$o_direction = 1; // GEN
						} else {
							$o_direction = 2; // DUAL
						}

						foreach($arModifiers as $mod) {
							$mod_item = Recordset::query("SELECT id_habilidade, id_tipo FROM item WHERE id=" . (int)$mod['id'], true)->row_array();

							if($mod_item['id_habilidade'] && $mod_item['id_tipo'] != 1) { // Somente itens que não tem ordem(qquer coisa diferente de clas e afins)
								switch($o_direction) {
									case 0:
										if(($mod['o_direction'] == 0 && $mod['direction'] == 0) || ($mod['o_direction'] == 2 && $mod['dreiction'] == 2)) {
											$pass = false;
										}

										break;

									case 1:
										if(($mod['o_direction'] == 1 && $mod['direction'] == 0) || ($mod['o_direction'] == 2 && $mod['direction'] == 0)) {
											$pass = false;
										}

										break;

									case 2:
										if($mod['o_direction'] == 0 || $mod['o_direction'] == 1 || $mod['o_direction'] == 2) {
											$pass = false;
										}

										break;
								}
							}
						}
					// <---
				}

				if(!$pass && $item->id_tipo != 41) {
					die("jalert('".t('actions.a65')."[2]!')");
				}

				// Atualização de turnos caso o item tenha
				if($item->getAttribute('turnos')) {
					$turnos[$item->id] = $item->getAttribute('turnos');
				}
				// Salva os dados de turnos
				SharedStore::S('_TRL_' . $basePlayer->id, $turnos);

				if(!in_array($item->getAttribute('id_tipo'), [1, 41])) {
					$_SESSION['has_used_nt_atk']	= true;
				}

				// Verifica se tem stats para usar --->
					if($item->consume_sp > $basePlayer->getAttribute('sp')) {
						die('jalert("'.t('actions.a63').'")');
					}

					if($item->consume_sta > $basePlayer->getAttribute('sta')) {
						die('jalert("'.t('actions.a64').'")');
					}
				// <---

				// Consumo
				$basePlayer->consumeSP($item->consume_sp);
				$basePlayer->consumeSTA($item->consume_sta);

				$basePlayer->addModifier($item, $item->getAttribute('level'), 0, $o_direction);
				$baseEnemy->addModifier($item, $item->getAttribute('level'), 1, $o_direction);

				Recordset::update('batalha', array(
					'pvp_log'	=> $batalha['pvp_log'] . '<div class="buff"><b class="pvp_p_name">' . $basePlayer->nome . '</b> usa <span class="pvp_p_atk">' . $item->nome . '</span></div>'
				), array(
					'id'		=> $basePlayer->id_batalha
				));

				echo "\n$('#cnLog').scrollTop(100000);\n";

				// Contador de uso para dar exp no fim da luta
				$pvp_items[$item->id] = 1;
			} elseif($_POST['action'] == 3 && $item) { // Clas e etc
				if(!in_array($item->getAttribute('id_tipo'), array(16, 17, 20, 21, 23, 26, 39))) {
					die('jalert("'.t('actions.a67').'")');
				}

				// Verifica se tem stats para usar --->
					if($item->consume_sp > $basePlayer->getAttribute('sp')) {
						die('jalert("'.t('actions.a63').'")');
					}

					if($item->consume_sta > $basePlayer->getAttribute('sta')) {
						die('jalert("'.t('actions.a64').'")');
					}
				// <---

				Recordset::update('batalha', array(
					'do_updates'	=> 1,
					'do_updatesb'	=> 1
				), array(
					'id'			=> $batalha['id']
				));

				// Consumo
				$basePlayer->consumeHP($item->consume_hp);
				$basePlayer->consumeSP($item->consume_sp);
				$basePlayer->consumeSTA($item->consume_sta);

				$basePlayer->addModifier($item, $item->getAttribute('level'), 0);
			} elseif($_POST['action'] == 4 && $item) { // Merdicinal =D

			}
		}

		$items = $basePlayer->getItems(array(1, 2, 5, 6));
	?>
	<?php foreach($items as $item): ?>
		<?php
			if(!$item->dojo_ativo) {
				continue;
			}

			$item->setPlayerInstance($basePlayer);
		?>
		_items['<?php echo $item->id ?>'].precf		= <?php echo $item->getAttribute('prec_fisico') ?>;
		_items['<?php echo $item->id ?>'].precm		= <?php echo $item->getAttribute('prec_magico') ?>;
		_items['<?php echo $item->id ?>'].pre		= <?php echo $item->getAttribute('precisao') ?>;
		<?php if($item->getAttribute('uso_unico')):	?>
		_items['<?php echo $item->id ?>'].t		= <?php echo $item->getAttribute('qtd') ?>;
		<?php endif; ?>
		<?php if($item->getAttribute('turnos')): ?>
		_items['<?php echo $item->id ?>'].trl	= <?php echo isset($turnos[$item->id]) ? $turnos[$item->id] : 0 ?>;
		<?php endif; ?>
	<?php endforeach; ?>
	_nt_disabled = [];
	<?php for($f = 0; $f <= 1; $f++): ?>
	<?php
		$var		= $f ? 'e' : 'p';
		$obj		= $f ? $baseEnemy : $basePlayer;
		$modifiers	= $obj->getModifiers();
		$elementos	= $obj->getElementosA();
	?>

	<?php if($f == 1 && sizeof($modifiers)): ?>
		<?php foreach($modifiers as $modifier): ?>
			<?php if($modifier['direction'] == 0 && $modifier['ord']): ?>
				<?php
					$cur_ord_item	= Recordset::query('SELECT * FROM item WHERE id=' . $modifier['id'], true)->row_array();
					$ord_ext		= 'png';

					if($cur_ord_item['id_tipo'] == 23 || $cur_ord_item['id_tipo'] == 39) {
						continue;
					}

					switch((int)$cur_ord_item['id_tipo']) {
						case 20:
							$ord_type	= 'selos';
							$ord_class	= $cur_ord_item['id_selo'];
							$ord_ext	= 'gif';

							break;
						case 21:
							$ord_type	= 'invocacoes';
							$ord_class	= $cur_ord_item['id_invocacao'];

							break;
						case 26:
							$ord_type	= 'mode_sennin';
							$ord_class	= $cur_ord_item['id_sennin'];

							break;
						case 16:
							$ord_type	= 'clas';
							$ord_class	= $cur_ord_item['id_cla'];

							break;
						case 17:
							$ord_type	= 'portoes';
							$ord_class	= '';
							$ord_ext	= 'gif';

							break;
					}
				?>
				$('#i-ebuff-<?php echo $cur_ord_item['id_tipo'] ?>').attr('src', '<?php echo img('layout/' . $ord_type . '/' . $ord_class . '/' . $cur_ord_item['ordem'] . '.' . $ord_ext) ?>');
			<?php endif ?>
		<?php endforeach ?>
	<?php endif ?>

	<?php echo $var ?> = {
		atkf:	<?php echo $obj->getAttribute('atk_fisico_calc') ?>,
		atkm:	<?php echo $obj->getAttribute('atk_magico_calc') ?>,
		def:	<?php echo $obj->getAttribute('def_base_calc') ?>,
		deff:	<?php echo $obj->getAttribute('def_fisico_calc') ?>,
		defm:	<?php echo $obj->getAttribute('def_magico_calc') ?>,
		mhp:	<?php echo $obj->getAttribute('max_hp') ?>,
		hp:		<?php echo $obj->getAttribute('hp') ?>,
		msp:	<?php echo $obj->getAttribute('max_sp') ?>,
		sp:		<?php echo $obj->getAttribute('sp') ?>,
		msta:	<?php echo $obj->getAttribute('max_sta') ?>,
		sta:	<?php echo $obj->getAttribute('sta') ?>,
		cmin:	<?php echo $obj->getAttribute('crit_min_calc') ?>,
		cmax:	<?php echo $obj->getAttribute('crit_max_calc') ?>,
		ctotal:	<?php echo $obj->getAttribute('crit_total_calc') ?>,
		emin:	<?php echo $obj->getAttribute('esq_min_calc') ?>,
		emax:	<?php echo $obj->getAttribute('esq_max_calc') ?>,
		etotal:	<?php echo $obj->getAttribute('esq_total_calc') ?>,
		conc:	<?php echo $obj->getAttribute('conc_calc') ?>,
		esquiva:<?php echo $obj->getAttribute('esquiva_calc') ?>
	};

	<?php echo $var ?>mod = [];

	var <?php echo $var ?>resumo =
		'<b class="azul"><?php echo t('formula.atk_fisico')?>:</b> <?php echo $obj->getAttribute('atk_fisico_calc') ?><br />' +
		'<b class="azul"><?php echo t('formula.atk_magico')?>:</b> <?php echo $obj->getAttribute('atk_magico_calc') ?><br />' +
		<?php /*'<b class="azul"><?php echo t('formula.def_base')?>:</b> <?php echo $obj->getAttribute('def_base_calc') ?><br />' +*/ ?>

		'<b class="azul"><?php echo t('formula.def_fisico')?>:</b> <?php echo $obj->getAttribute('def_fisico_calc') ?><br />' +
		'<b class="azul"><?php echo t('formula.def_magico')?>:</b> <?php echo $obj->getAttribute('def_magico_calc') ?><br />' +

		<?php /*'<b class="azul"><?php echo t('formula.prec_fisico')?>:</b> <?php echo $obj->getAttribute('prec_fisico_calc') ?><br />' +*/?>
		'<b class="azul"><?php echo t('formula.prec_magico')?>:</b> <?php echo $obj->getAttribute('prec_magico_calc') ?><br />' +
		'<b class="azul"><?php echo t('formula.det')?>:</b> <?php echo $obj->getAttribute('det_calc') ?> %<br />' +
		'<b class="azul"><?php echo t('formula.conv')?>:</b> <?php echo $obj->getAttribute('conv_calc') ?> %<br />' +
		'<b class="azul"><?php echo t('formula.esq')?>:</b> <?php echo sprintf("%1.2f",$obj->getAttribute('esq_calc')) ?> % ( <span class="color_green"><?php echo $f == 0 ? $conv_en + $obj->getAttribute('esq_calc')." %" : $conv_my + $obj->getAttribute('esq_calc')." %" ?></span> - <span class="color_red"><?php echo $f == 0 ? $conv_en. " %" : $conv_my." %" ?></span> ) <br />' +
		'<b class="azul"><?php echo t('formula.esq_total')?>:</b> <span><?php echo $obj->getAttribute('esq_total_calc')?> %</span><br />' +
		'<b class="azul"><?php echo t('formula.conc')?>:</b> <?php echo sprintf("%1.2f", $obj->getAttribute('conc_calc')) ?> % ( <span class="color_green"><?php echo $f == 0 ? $conv_en + $obj->getAttribute('conc_calc')." %" : $conv_my + $obj->getAttribute('conc_calc')." %" ?></span> - <span class="color_red"><?php echo $f == 0 ? $conv_en. " %" : $conv_my." %" ?></span> )<br />' +
		'<b class="azul"><?php echo t('formula.crit_total')?>:</b> <span><?php echo $obj->getAttribute('crit_total_calc')?>%</span><br />' +
		'<b class="azul"><?php echo t('formula.esquiva')?>:</b> <span><?php echo $obj->getAttribute('esquiva_calc')?>%</span><br />';


	<?php echo $var ?>mod.push({
		id:		'A',
		t:		0,
		i:		'layout<?php echo LAYOUT_TEMPLATE?>/stats.png',
		n:		'<?php echo t('jogador_vip.jv34')?>',
		d:		<?php echo $var ?>resumo,
		ub:		true
	});

	<?php if ($obj->id_exame_chuunin): ?>
		<?php
			$sky		= $obj->getItem(22916);
			$earth		= $obj->getItem(22917);

			$choosen	= $sky ? $sky : $earth;
		?>
		<?php if ($choosen): ?>
			<?php echo $var ?>mod.push({
				id:		'A',
				t:		0,
				i:		'<?php echo 'layout/' . $choosen->imagem ?>',
				n:		'<?php echo $choosen->nome ?>',
				d:		'',
				ub:		true
			});
		<?php endif ?>
	<?php endif ?>

	<?php if(sizeof($elementos)): ?>
		<?php echo $var ?>mod.push({
			id:		'B',
			t:		0,
			i:		'layout<?php echo LAYOUT_TEMPLATE?>/elements.png',
			n:		'<?php echo t('actions.a68')?>',
			d:		'<?php echo t('actions.a69')?>',
			ise:	true,
			ub:		true,
			els:	[
			<?php if(!$f || $f && $basePlayer->hasItem(21365)): ?>
				<?php foreach($elementos as $elemento): ?>
				{
					n: '<?php echo $elemento['nome'] ?>',
					f: [
					<?php
						$fraquezas	= Recordset::query('SELECT a.nome FROM elemento a JOIN elemento_fraqueza b ON b.id_elemento_fraco=a.id WHERE b.id=' . $elemento['id'], true);
					?>
					<?php foreach($fraquezas->result_array() as $fraqueza): ?>
						'<?php echo $fraqueza['nome'] ?>'
					<?php endforeach; ?>
					],
					r: [
					<?php
						$resistencias	= Recordset::query('SELECT a.nome FROM elemento a JOIN elemento_resistencia b ON b.id_elemento_resiste=a.id WHERE b.id=' . $elemento['id'], true);
					?>
					<?php foreach($resistencias->result_array() as $resistencia): ?>
						'<?php echo $resistencia['nome'] ?>'
					<?php endforeach; ?>
					]
				},
				<?php endforeach; ?>
			<?php endif; ?>
			]
		});
	<?php endif; ?>

	<?php if(sizeof($modifiers)): ?>
		<?php foreach($modifiers as $mod): ?>
		<?php
			if($mod['o_direction'] != 2) {
				if($mod['direction'] != $mod['o_direction']) {
					continue;
				}
			}
		?>
		<?php if($mod['ord']): // Clas e etc ?>
			<?php
				$ord_item		= new Item($mod['id']);
				$ord_items		= Recordset::query('SELECT id, nome_'.Locale::get().' AS nome FROM item WHERE id_tipo=' . $ord_item->getAttribute('id_tipo') . ' AND ordem <=' . $ord_item->getAttribute('ordem'), true);
			?>
			<?php if($f == 0): // Esse loop so acontece para o player ?>
			<?php foreach($ord_items->result_array() as $ord): ?>
				$('#i-nt-<?php echo $ord['id'] ?>').css('opacity', .4);
				_nt_disabled.push(<?php echo $ord['id'] ?>);
			<?php endforeach ?>
			<?php endif ?>

			$('#i-<?php echo $var ?>buff-<?php echo $ord_item->getAttribute('id_tipo') ?>').css('opacity', 1);
		<?php else: ?>
		<?php echo $var ?>mod.push({
			<?php
				if($mod['uid']) {
					$item	= new Item($mod['id'], $mod['source_id']);
					$item->apply_enhancemnets();
				} else {
					$item	= new Item($mod['id']);
				}

				$desc	= $item->getAttribute('descricao_'.Locale::get());

				if($mod['conv']) {
					$value	= preg_match('/\d+/', $desc, $matches);

					if(isset($matches[0])) {
						$desc	= str_replace($matches[0], $mod['det_inc'], $desc);
					}
				}
			?>
			id:	<?php echo $mod['id'] ?>,
			t:	<?php echo $mod['turns'] ?>,
			i:	'layout/<?php echo $mod['image'] ?>',
			n:	'<?php echo $item->getAttribute('nome_'.Locale::get()) ?>',
			ub:	true,
			<?php if($mod['conv']): ?>
			ise: true,
			d:	'<?php echo $desc ?>',
			<?php else: ?>
			st:	true,
			<?php endif; ?>
			<?php if($item->getAttribute('sem_turno') && $item->hasModifiers()): ?>
			<?php
				$modifiers	= $item->getModifiers();

				$has_mod_p	= $modifiers['self_ken'] || $modifiers['self_tai'] || $modifiers['self_nin'] || 			$modifiers['self_gen'] || $modifiers['self_agi'] || $modifiers['self_con'] || $modifiers['self_ene'] || $modifiers['self_forc'] || $modifiers['self_inte']	|| $modifiers['self_res'] || $modifiers['self_atk_fisico'] || $modifiers['self_atk_magico'] || $modifiers['self_def_base']	|| $modifiers['self_prec_fisico'] || $modifiers['self_prec_magico']|| $modifiers['self_crit_min'] || $modifiers['self_crit_max'] || $modifiers['self_crit_total']	|| $modifiers['self_esq_min'] || $modifiers['self_esq_max'] || $modifiers['self_esq_total'] || $modifiers['self_esq'] || $modifiers['self_det'] || $modifiers['self_conv'] || $modifiers['self_conc'] || $modifiers['self_esquiva'];

				$has_mod_e	= $modifiers['target_ken'] || $modifiers['target_tai'] || $modifiers['target_nin']	|| $modifiers['target_gen']	|| $modifiers['target_agi'] || $modifiers['target_con'] || $modifiers['target_ene'] || $modifiers['target_forc'] || $modifiers['target_inte'] || $modifiers['target_res'] || $modifiers['target_atk_fisico'] || $modifiers['target_atk_magico'] || $modifiers['target_def_base'] || $modifiers['target_prec_fisico'] || $modifiers['target_prec_magico']	|| $modifiers['target_crit_min'] || $modifiers['target_crit_max'] || $modifiers['target_crit_total'] || $modifiers['target_esq_min']	|| $modifiers['target_esq_max'] ||  $modifiers['target_esq_total'] || $modifiers['target_esq'] || $modifiers['target_det'] || $modifiers['target_conv'] || $modifiers['target_conc'] || $modifiers['target_esquiva'];
			?>
			mo:	{
				p: <?php echo $has_mod_p ? 'true' : 'false' ?>,
				e: <?php echo $has_mod_e ? 'true' : 'false' ?>,
				<?php for($g = 0; $g <= 1; $g++): ?>
					<?php
						if($g && !$has_mod_e || !$g && !$has_mod_p) {
							continue;
						}
					?>
					<?php echo $g ? 'em' : 'pm' ?>:	{
						<?php foreach($modifiers as $k => $v): ?>
						<?php if(strpos($k, !$g ? 'self_' : 'target_') === false) continue; ?>
						<?php echo $k ?>: <?php echo (int)$v ?>,
						<?php endforeach; ?>
					}
				<?php endfor; ?>
			}
			<?php endif; ?>
		});
		<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php endfor; ?>
	<?php for($f = 0; $f <= 1; $f++): ?>
	$('#<?php echo $f == 1 ? 'd-eesq-count'  : 'd-pesq-count' ?>').html('<span><?php  echo (int)($f == 1 ? $fight->hasEsqPoint($baseEnemy, true)  : $fight->hasEsqPoint($basePlayer, true)) ?></span>');
	$('#<?php echo $f == 1 ? 'd-ecrit-count' : 'd-pcrit-count' ?>').html('<span><?php echo (int)($f == 1 ? $fight->hasCritPoint($baseEnemy, true) : $fight->hasCritPoint($basePlayer, true)) ?></span>');
	<?php endfor; ?>

	<?php if($batalha['finalizada']): //!!! ?>
		<?php
			$player_data	= unserialize($batalha['player_data']);
		?>

		p.hp	= <?php echo isset($player_data[$basePlayer->id]) ? $player_data[$basePlayer->id]['hp'] : 0 ?>;
		p.sp	= <?php echo isset($player_data[$basePlayer->id]) ? $player_data[$basePlayer->id]['sp'] : 0 ?>;
		p.sta	= <?php echo isset($player_data[$basePlayer->id]) ? $player_data[$basePlayer->id]['sta'] : 0 ?>;

		e.hp	= <?php echo isset($player_data[$baseEnemy->id]) ? $player_data[$baseEnemy->id]['hp'] : 0 ?>;
		e.sp	= <?php echo isset($player_data[$baseEnemy->id]) ? $player_data[$baseEnemy->id]['sp'] : 0 ?>;
		e.sta	= <?php echo isset($player_data[$baseEnemy->id]) ? $player_data[$baseEnemy->id]['sta'] : 0 ?>;
	<?php endif ?>

	setPValue2(p.hp,  (p.mhp  || 1), "<?php echo t('formula.hp')?>",	$("#cnPHP"),  1);
	setPValue2(p.sp,  (p.msp  || 1), "Chakra",	$("#cnPSP"),  1);
	setPValue2(p.sta, (p.msta || 1), "Stamina",	$("#cnPSTA"), 1);

	setPValue2(e.hp,  (e.mhp  || 1), "<?php echo t('formula.hp')?>",	$("#cnEHP"),  1);
	setPValue2(e.sp,  (e.msp  || 1), "Chakra",	$("#cnESP"),  1);
	setPValue2(e.sta, (e.msta || 1), "Stamina",	$("#cnESTA"), 1);

	numericAnimateTo(<?php echo $basePlayer->getAttribute('fight_power') ?>, '', '#ninjap-p');
	numericAnimateTo(<?php echo $baseEnemy->getAttribute('fight_power') ?>, '', '#ninjap-e');
<?php endif; ?>

$('#cnLog').html('<?php echo addslashes(preg_replace('/[\r\n]/s', '',$batalha['pvp_log'])) ?>');
$('#cnPVPLog').html('<?php echo $batalha['current_atk'] == $basePlayer->id ? t('actions.a70') : t('actions.a71') ?>');
$('#cnLog').scrollTop(100000);

<?php
	$diff	= get_time_difference(now(), $future);
?>

__timer_m	= <?php echo $diff['minutes'] ?>;
__timer_s	= <?php echo $diff['seconds'] ?>;

<?php if($batalha['current_atk'] == $basePlayer->id): ?>
$('#d-atk-timer').css('color', '#D61818').css('background-image', 'url(<?php echo img('layout/combate/d-seta-b.png') ?>)');

<?php if(isset($_SESSION['usuario']['sound']) && $_SESSION['usuario']['sound']): ?>
if(!__can_atk) {
	$(document.body).append('<audio autoplay><source src="<?php echo img('media/battle.wav') ?>" type="audio/wav" /></audio>');
}
<?php endif ?>

__can_atk	= true;
<?php else: ?>
$('#d-atk-timer').css('color', '#2EAB30').css('background-image', 'url(<?php echo img('layout/combate/d-seta-p.png') ?>)');
__can_atk	= false;

<?php endif ?>
<?php SharedStore::S('_PVP_ITEMS_' . $basePlayer->id, $pvp_items) ?>
/*
<?php print_r($pvp_items) ?>
*/
