<?php
	header("Content-type: text/javascript; charset=utf-8");

	sleep(1);
	$msgB			= false;

	$cID = decode($_POST['id']);

	if(!is_numeric($cID) || $basePlayer->dentro_vila) {
		redirect_to("negado");
	}

	$pp 			= new Player($cID);
	$skip_check		= false;
	$eventos_vila	= Recordset::query('SELECT id, tipo FROM evento_vila WHERE iniciado=1');
	$arena			= $basePlayer->id_arena;
	$exame			= $basePlayer->id_exame_chuunin;
	$invasao		= false;

	if($basePlayer->id_graduacao >= 2) {
		$isBingoBookVila = is_bingo_book_vila($basePlayer->id_vila, $cID, false);
	} else {
		$isBingoBookVila = false;
	}

	if($basePlayer->id_graduacao >= 4) {
		$isBingoBook = is_bingo_book_player($basePlayer->id, $cID, false);
	} else {
		$isBingoBook = false;
	}

	// BB de guild e euipe não tem limite de gruduação -->
		if($basePlayer->id_guild && !$isBingoBook) {
			$isBingoBook = is_bingo_book_guild($basePlayer, $cID, false);
		}

		if($basePlayer->id_equipe && !$isBingoBook) {
			$isBingoBook = is_bingo_book_equipe($basePlayer, $cID, false);
		}
	// <--
	// Comentando para haver duelos em vilas neutras 
	
	if(!$basePlayer->vila_atual_inicial && $basePlayer->id_vila_atual != 13 && !$arena && !$exame) {
		die('jalert("Não é possível batalha na vila atual")');
	}
	
	if($eventos_vila->num_rows) {
		foreach($eventos_vila->result_array() as $evento_vila) {
			switch($evento_vila['tipo']) {
				case 'bijuu';	$evento_item_tipo	= '34'; break;
				case 'armas';	$evento_item_tipo	= '35'; break;
				case 'espadas';	$evento_item_tipo	= '36'; break;
			}

			$evento_item	= Recordset::query('
				SELECT
					a.id

				FROM
					player_item a JOIN item b ON b.id=a.id_item

				WHERE
					a.id_player=' . $pp->id . ' AND
					a.id_item_tipo=' . $evento_item_tipo . '

				LIMIT 1
			');

			if($evento_item->num_rows) {
				$skip_check	= true;
				break;
			}
		}
	}

	if($skip_check && !$isBingoBook && !$isBingoBookVila) {
		$lvlDiff	= 3;

		if(!Recordset::query("SELECT id FROM player WHERE id=$cID AND level BETWEEN " . ($basePlayer->getAttribute('level') - $lvlDiff) . " AND " . ($basePlayer->level + $lvlDiff))->num_rows) {
			echo "jalert('".t('actions.a198')."')";

			die();
		}
	}

	$check	= true;
	$check	= gHasItemW(20290, $pp->id, NULL, 24) ? false : true;
	$check	= gHasItemW(20290, $basePlayer->id, NULL, 24) ? false : true;

	if(!$skip_check && !$arena && !$exame) {
		if(!$isBingoBookVila && !$isBingoBook && !$arena && !$exame) {		
			if((int)$basePlayer->id_vila != (int)$basePlayer->id_vila_atual) { // Invasores
				if(gHasItemW(1863, $cID, NULL, 24)) {
					$lvlDiff = 1;
					$msgB = true;
				} else {
					// Se eu tiver o extensor de range
					if(gHasItemW(1862, $basePlayer->id, NULL, 24)) {
						$lvlDiff = 5;
					} else {
						$lvlDiff = 3;
					}
				}


				if(!Recordset::query("SELECT id FROM player WHERE id=$cID AND level BETWEEN " . ($basePlayer->getAttribute('level') - $lvlDiff) . " AND " . ($basePlayer->level + $lvlDiff))->num_rows) {
					if($msgB) {
						echo "jalert('".t('actions.a197')."')";
					} else {
						echo "jalert('".t('actions.a198')."')";
					}

					die();
				}
			} else { // Defensores
				if($pp->missao_invasao_vila == $basePlayer->id_vila) {
					// Mudei por causa dos multis! ---- Depois o fabio vê!
					$lvlDiff	= 8;
					$invasao	= true;
				}else{
					if(gHasItemW(1863, $cID, NULL, 24)) {
						$lvlDiff = 1;
						$msgB = true;
					} else {
						// Se eu tiver o extensor de range
						if(gHasItemW(1862, $basePlayer->id, NULL, 24)) {
							$lvlDiff = 5;
						} else {
							$lvlDiff = 3;
						}
					}
				}


				if(!Recordset::query("SELECT id FROM player WHERE id=$cID AND level BETWEEN " . ($basePlayer->level - $lvlDiff) . " AND " . ($basePlayer->level + $lvlDiff))->num_rows) {
					if($msgB) {
						echo "jalert('".t('actions.a197')."')";
					} else {
						echo "jalert('".t('actions.a198')."')";
					}

					die();
				}
			}
		}
	}

	if(!$isBingoBookVila && !$isBingoBook && !$arena && !$exame) {
		// Verifica se o player pode efetuar a luta --->
			$rTotalB = Recordset::query("SELECT COUNT(id) AS total FROM player_batalhas WHERE id_tipo = 4 AND id_player=" . $basePlayer->id . " AND id_playerb=" . $cID)->row_array();

			if($rTotalB['total'] >= 5) {
				echo "jalert('".t('actions.a203')."')";
				die();
			}
		// <---

		// GANK -->
			$gank = false;
			if($pp->id_vila != $pp->id_vila_atual and $basePlayer->id_vila != $basePlayer->id_vila_atual){
				$gank = true;
			}
			if($basePlayer->id_vila == $basePlayer->id_vila_atual){
				$gank = true;
			}
		
			if(!$gank) {
				if($pp->hp < $pp->max_hp / 2) {
					echo "jalert('".t('actions.a204')."');";
					die();
				}

				if($pp->sp < $pp->max_sp / 2) {
					echo "jalert('".t('actions.a204')."');";
					die();
				}

				if($pp->sta < $pp->max_sta / 2) {
					echo "jalert('".t('actions.a204')."');";
					die();
				}
			}
		// <---

		if($check) {
			// Verifica a diplomacia --->
				$dipl = Player::diplOf($basePlayer->id_vila, $pp->id_vila);

				if($dipl != 2) {
					echo "jalert('".t('actions.a201')."');";
					die();
				}
			// <---
		}

		// Player da mesma organização/equipe não batalha --->
			if($basePlayer->id_guild && $pp->id_guild == $basePlayer->id_guild) {
				echo "jalert('".t('actions.a200')."')";
				die();
			}

			if($basePlayer->id_equipe && $pp->id_equipe == $basePlayer->id_equipe) {
				echo "jalert('".t('actions.a202')."')";
				die();
			}
		// <---
	}

	$baseEnemy		= $pp;
	/*if($_SESSION['universal']){*/
		
		$last_battle	= Recordset::query('SELECT id_player, ip_a, data_atk FROM batalha WHERE id_playerb=' . $baseEnemy->id . ' ORDER BY id DESC LIMIT 1');
		
		
		if ($last_battle->num_rows && !$basePlayer->id_arena && !$basePlayer->id_exame_chuunin) {
			$last_battle	= $last_battle->row();
			
			if ($last_battle->ip_a == ip2long($_SERVER['REMOTE_ADDR']) || $last_battle->id_player == $basePlayer->id) {

				$diff	= get_time_difference($last_battle->data_atk, now(true));
				
				if($diff['hours'] == 0 && $diff['minutes'] < 5) {
					die("alert('Uma conta conectada com esse ip já batalhou com esse jogador antes!');");
				}
			}
		}
	/*}*/

	if(!$_SESSION['universal']) {
		if($pp->ip == $basePlayer->ip) {
			echo "jalert('".t('actions.a199')."')";
			die();
		}
	}

	// Verifica se o manolo ainda está no mapa da vila --->
	if($pp->dentro_vila || !$pp->id_vila_atual) {
		die('alert("'.t('actions.a205').'")');
	}

	if(!Recordset::query("SELECT id FROM player WHERE id=" . $cID . " AND id_batalha=0")->num_rows) {
		die("alert('".t('actions.a206')."');");
	}

	if ($basePlayer->id_exame_chuunin && $basePlayer->exame_chuunin_etapa == 1) {
		$items	= Recordset::query('SELECT COUNT(DISTINCT(a.id_item)) AS total FROM player_item a JOIN player b ON b.id=a.id_player WHERE id_item_tipo=40 AND b.id_exame_chuunin=' . $basePlayer->id_exame_chuunin);

		if($items->row()->total < 2) {
			die("alert('Não há mais pergaminhos dispoíveis para terminar o exame. O exame será finalizado!');");
		}

		$exam	= Recordset::query('SELECT HOUR(TIMEDIFF(NOW(), data_inicio)) AS hours FROM exame_chuunin WHERE id=' . $basePlayer->id_exame_chuunin)->row();

		if ($exam->hours >= 1) {
			die("alert('Tempo do exame excedido. O exame será finalizado!');");
		}
	}

	if(!Recordset::query('
			SELECT

				*

			FROM
				batalha_fila
			WHERE
			(id_player=' . $basePlayer->id . ' AND id_playerb=' . $baseEnemy->id . ') OR
			(id_player=' . $baseEnemy->id . ' AND id_playerb=' . $basePlayer->id . ')')->num_rows
	) {
		Recordset::insert('batalha_fila', [
			'id_tipo'		=> 4,
			'id_playerb'	=> $baseEnemy->id,
			'id_player'		=> $basePlayer->id,
			'level_b'		=> $baseEnemy->level,
			'level_a'		=> $basePlayer->level,
			'current_atk'	=> $basePlayer->id,
			'enemy'			=> $baseEnemy->id,
			'data_atk'		=> array('escape' => false, 'value' => 'NOW()'),
			'ip_a'			=> $basePlayer->ip,
			'ip_b'			=> $baseEnemy->ip,
			'vila_a'		=> $basePlayer->id_vila == $basePlayer->id_vila_atual ? 1 : 0,
			'vila_b'		=> $baseEnemy->id_vila == $baseEnemy->id_vila_atual ? 1 : 0,	
			'bingo_book'	=> $isBingoBook || $isBingoBookVila ? 1 : 0,
			'invasao'		=> $invasao ? 1 : 0
		]);
	} else {
		echo "alert('".t('actions.a206')."');";
	}

	/*
	if(!SharedStore::G($shared_key)) {
		SharedStore::S($shared_key, 1);

		$baseEnemy	= $pp;
		$batalha	= Recordset::insert('batalha', array(
			'id_tipo'		=> 4,
			'id_playerb'	=> $baseEnemy->id,
			'id_player'		=> $basePlayer->id,
			'current_atk'	=> $basePlayer->id,
			'enemy'			=> $baseEnemy->id,
			'data_atk'		=> array('escape' => false, 'value' => 'NOW()'),
			'ip_a'			=> $basePlayer->ip,
			'ip_b'			=> $baseEnemy->ip,
			'bingo_book'	=> $isBingoBook ? 1 : 0
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

		$basePlayer->setAttribute('id_batalha', $batalha);
		$baseEnemy->setAttribute('id_batalha', $batalha);

		SharedStore::D($shared_key);
	} else {
		echo "alert('".t('actions.a206')."');";
	}
	*/
