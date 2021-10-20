<?php
	$redir_script	= true;
	$cID			= salt_decrypt($_POST['id'], $_SESSION['mapa_mundi_key']);
	$msgB			= false;
	
	if(!is_numeric($cID)) {
		redirect_to("negado");	
	}
	
	if(isset($_POST['delete']) && ($_POST['delete'])) {
		$_SESSION['_random_npc_map_msg']	= false;
		
		die();
	}
	
	// NPC de evento --->
		if(isset($_POST['tp']) && $_POST['tp'] == 2) {
			if(!$basePlayer->getAttribute('id_evento')) {
				redirect_to("negado");
			}
			
			if(!($basePlayer->sp > 20 && $basePlayer->sta > 20 && $basePlayer->hp > 20)) {
				die('jalert("'.t('actions.a274').'")');				
			}

			Fight::cleanup();
			
			// Band-aid --->
				$lider				= Recordset::query('SELECT id_player FROM equipe WHERE id=' . $basePlayer->id_equipe)->row_array();
				$avg				= Recordset::query('SELECT ROUND(AVG(level)) AS media FROM player WHERE id_equipe=' . $basePlayer->id_equipe)->row()->media;

				$instance			= new Player($lider['id_player']);
				$instance->level	= $avg;
				$instance->setLocalAttribute('level', $avg);

				$npc				= new NPC($cID, $instance, NPC_EVENTO);
			// <---
			
			$batalha	= Recordset::query('
				SELECT 
					batalha 
				FROM 
					evento_npc_equipe 
				WHERE 
					id_equipe=' .		$basePlayer->id_equipe . ' AND 
					id_evento_npc=' .	$npc->id . ' 
					AND id_evento=' .	$basePlayer->id_evento);
			
			if($batalha->row()->batalha) {
				die('jalert("'.t('actions.a208').'")');
			}

			$batalha = Recordset::insert('batalha', array(
				'id_tipo'		=> 3,
				'id_player'		=> $basePlayer->id,
				'current_atk'	=> 1,
				'data_atk'		=> date('Y-m-d H:i:s'),
				'npc_tipo'		=> 'equipe'
			));
			
			// Marca o NPC em batalha --->
				Recordset::update('evento_npc_equipe', array(
					'batalha'		=> '1'
				), array(
					'id_equipe'		=> $basePlayer->id_equipe,
					'id_evento_npc'	=> $npc->id,
					'id_evento'		=> $basePlayer->id_evento
				));
			// <---
			
			$basePlayer->setAttribute('id_batalha', $batalha);
			$npc->batalha_id	= $batalha;

			SharedStore::S('_BATALHA_' . $basePlayer->id, serialize($npc));
			
			redirect_to("dojo_batalha_lutador");
		}
	// <---
	
	// NPC Evento global --->
		if(isset($_POST['tp']) && $_POST['tp'] == 4) {
			if(!($basePlayer->sp > 20 && $basePlayer->sta > 20 && $basePlayer->hp > 20)) {
				die('jalert("'.t('actions.a274').'")');				
			}
			
			if($basePlayer->id_vila == 6) {
				die('jalert("Você não pode batalhar com um NPC aliado!")');
			}
			
			$evento_global = $basePlayer->eventoGlobal();
						
			if(!$evento_global) {
				redirect_to("negado");
			}
			
			Fight::cleanup();
			
			$npc		= new NPC($cID, $basePlayer, NPC_EVENTO_GLOBAL);
			$batalha	= Recordset::query('
				SELECT 
					batalha_global
				FROM 
					evento_npc_evento 
				WHERE 
					id_evento_npc=' .	$npc->id . ' 
					AND id_evento=' .	$evento_global);
			
			if($batalha->row()->batalha_global) {
				die('jalert("'.t('actions.a209').'")');
			}
			
			$batalha = Recordset::insert('batalha', array(
				'id_tipo'		=> 7,
				'id_player'		=> $basePlayer->id,
				'current_atk'	=> 1,
				'data_atk'		=> date('Y-m-d H:i:s'),
				'npc_tipo'		=> 'global'
			));
			
			// Marca o NPC em batalha --->
				Recordset::update('evento_npc_evento', array(
					'batalha_global'=> '1'
				), array(
					'id_evento_npc'	=> $npc->id,
					'id_evento'		=> $evento_global
				));
			// <---
			
			$basePlayer->setAttribute('id_batalha', $batalha);

			SharedStore::S('_BATALHA_' . $basePlayer->id, serialize($npc));
			
			redirect_to("dojo_batalha_lutador");
		}
	// <---

	// NPC Random no Mapa --->
		
		if(isset($_POST['tp']) && $_POST['tp'] == 3 && $_SESSION['_random_npc_map']) {
			$batalha_npc_mapa	= Recordset::query('select total from player_batalhas_npc_mapa where id_player=' .	$basePlayer->id);
	
			if($batalha_npc_mapa->row()->total >= 15 + $basePlayer->bonus_vila['dojo_limite_npc_mapa']) {
				die('jalert("Você não pode mais duelar com npcs no mapa hoje!")');
			}
			if(!($basePlayer->sp > 20 && $basePlayer->sta > 20 && $basePlayer->hp > 20)) {
				die('jalert("'.t('actions.a274').'")');				
			}
			
			Fight::cleanup();

			$npc = new NPC(NULL, $basePlayer, NPC_MAPA_RND);
			
			$batalha = Recordset::insert('batalha', array(
				'id_tipo'		=> 3,
				'id_player'		=> $basePlayer->id,
				'data_atk'		=> date('Y-m-d H:i:s'),
				'current_atk'	=> 1,
			));
			
			$basePlayer->setAttribute('id_batalha', $batalha);
			
			$_SESSION['_random_npc_map_msg'] = false;
			
			Recordset::update('player_batalhas_npc_mapa', array(
				'total'		=> array('escape' => false, 'value' => 'total+1')
			), array(
				'id_player'	=> $basePlayer->id
			));

			SharedStore::S('_BATALHA_' . $basePlayer->id, serialize($npc));
			
			redirect_to("dojo_batalha_lutador");
			
			die();
		} elseif(isset($_POST['tp']) && $_POST['tp'] == 3 && !$_SESSION['_random_npc_map']) {
			die('jalert("'.t('actions.a215').'")');
		}
	// <---

	// NPC da guerra ninja -->
		if (isset($_POST['tp']) && $_POST['tp'] == 5 && $basePlayer->id_guerra_ninja) {
			Fight::cleanup();

			$npc_data	= Recordset::query('SELECT etapa FROM guerra_ninja_npcs WHERE id=' . $cID)->row();
			$npc		= new NPC($cID, $basePlayer, ($npc_data->etapa > 1 ? NPC_GUERRA_S : NPC_GUERRA), 0, $basePlayer->guerra_ninja);
			$batalha	= Recordset::insert('batalha', [
				'id_tipo'				=> 3,
				'id_player'				=> $basePlayer->id,
				'data_atk'				=> date('Y-m-d H:i:s'),
				'id_npc_guerra_ninja'	=> $cID,
				'current_atk'			=> 1
			]);
	
			// NPCS Da etapa 3 e 4 são compartilhados		
			if ($npc_data->etapa <= 1) {
				Recordset::update('guerra_ninja_npcs', [
					'batalha'	=> $batalha
				], [
					'id'		=> $cID
				]);
			}

			$basePlayer->setAttribute('id_batalha', $batalha);
			SharedStore::S('_BATALHA_' . $basePlayer->id, serialize($npc));
			
			redirect_to("dojo_batalha_lutador");

			die();
		}
	// <--
	if($basePlayer->getAttribute('id_graduacao') >= 2) {
		$isBingoBookVila = is_bingo_book_vila($basePlayer->id_vila, $cID, false);
	} else {
		$isBingoBookVila = false;
	}
	
	if($basePlayer->getAttribute('id_graduacao') >= 4) {
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

	$pp			= new Player($cID);
	$skip_check	= false;
	$rInimigo	= Recordset::query("
		SELECT level, id_vila_atual, id_guild, id_equipe FROM player WHERE id=$cID
	")->row_array();
	
	$eventos_vila	= Recordset::query('SELECT id, tipo FROM evento_vila WHERE iniciado=1');
	
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
	
	if(!$skip_check) {
		if(!$isBingoBook && !$isBingoBookVila) {
			// Se o inimigo tiver o redutor de range
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
			
			if(($rInimigo['level'] < ($basePlayer->level - $lvlDiff)) || ($rInimigo['level'] > ($basePlayer->level + $lvlDiff))) {
				if($msgB) {
					echo "jalert('".t('actions.a197')."')";				
				} else {
					echo "jalert('".t('actions.a198')."')";
				}
					
				die();
			}

			if($basePlayer->id_missao || $basePlayer->id_evento || $basePlayer->id_missao_guild) {
				echo "jalert('".t('actions.a210')."');";
				die();
			}
		
			if($pp->id_missao || $pp->id_evento || $pp->id_missao_guild) {
				echo "jalert('".t('actions.a211')."');";
				die();
			}
			$ultima_batalha_player = Recordset::query("
			SELECT * FROM batalha 
			WHERE 
				   id_tipo in (3,4) 
			AND 
				  (id_player=".$pp->id." OR id_playerb=".$pp->id.") 
			AND 
				   pvp_wo is null 
			AND 
				   finalizada=1 
			ORDER BY id DESC LIMIT 1

			");
			if($ultima_batalha_player->num_rows){
				$ultima_batalha_player = $ultima_batalha_player->row();
				if($_SESSION['universal']){
					$data_last_battle = strtotime($ultima_batalha_player->data_fim.'+ 10 seconds');	
				}else{
					$data_last_battle = strtotime($ultima_batalha_player->data_fim.'+ 10 seconds');	
				}
				if(strtotime("now") <= $data_last_battle){
					echo "jalert('Esse ninja está com proteção anti-gank por 10 segundos');";
					die();
				}else{
				// GANK -->
					/*if($pp->hp < ($pp->max_hp / 2)) {
						echo "jalert('".t('actions.a204')."');";
						die();
					}

					if($pp->sp < ($pp->max_sp / 2)) {
						echo "jalert('".t('actions.a204')."');";
						die();
					}

					if($pp->sta < ($pp->max_sta / 2)) {
						echo "jalert('".t('actions.a204')."');";
						die();
					}*/
				// <---
				}

			}
			
		}
	
		$check = true;
		
		//$check = gHasItemW(20290, $pp->id, NULL, 24) ? false : true;
		//$check = gHasItemW(20290, $basePlayer->id, NULL, 24) ? false : true;
	
		if($check) {
			// Regra de diplomacia --->
				$dipl = Player::diplOf($basePlayer->id_vila, $pp->id_vila);
				
				if($dipl == 1 && !$isBingoBook && !$isBingoBookVila) {
					echo "jalert('".t('actions.a201')."');";
					die();
				}
			// <---
		}
	
		// Player da mesma organização/equipe não batalha --->
			if($basePlayer->getAttribute('id_guild') && $rInimigo['id_guild'] == $basePlayer->getAttribute('id_guild')) {
				echo "jalert('".t('actions.a200')."')";
				die();
			}
			
			if($basePlayer->getAttribute('id_equipe') && $rInimigo['id_equipe'] == $basePlayer->getAttribute('id_equipe')) {
				echo "jalert('".t('actions.a202')."')";
				die();
			}
		// <---
	}

	if(!$isBingoBook && !$isBingoBookVila) {
		// Verifica se o player pode efetuar a luta(multi) --->
			$total1 = Recordset::query("SELECT COUNT(id) AS total FROM player_batalhas WHERE id_tipo = 4 AND id_player=" . $basePlayer->id . " AND id_playerb=" . $cID)->row()->total;
			$total2 = Recordset::query("SELECT COUNT(id) AS total FROM player_batalhas WHERE id_tipo = 4 AND id_player=" . $cID . " AND id_playerb=" . $basePlayer->id)->row()->total;
			
			if($total1 >= 5 || $total2 >= 5) {
				echo "jalert('".t('actions.a203')."')";
				die();
			}
		// <---
	}

	if($basePlayer->id_vila == $pp->id_vila && !$isBingoBook && !$isBingoBookVila) {
		echo "jalert('".t('actions.a213')."')";
		die();
	}

	// Verifica se o player esta no mapa ainda --->
		if($rInimigo['id_vila_atual']) {
			echo "jalert('".t('actions.a212')."')";
			die();
		}
	// <---

	// Players da mesma conta
		if($pp->id_usuario == $basePlayer->id_usuario) {
			echo "jalert('".t('actions.a214')."')";
			die();		
		}
	// <--
	
	// Proteção de IP -->
		if(!$_SESSION['universal']) {
			if($pp->ip == $basePlayer->ip) {
				echo "jalert('".t('actions.a199')."')";
				die();		
			}
		}
	// <--

	$shared_key	= '_MAPM_' . $cID;

	if(!Recordset::query("SELECT id FROM player WHERE id=" . $cID . " AND id_batalha=0")->num_rows) {
		die("alert('".t('actions.a206')."');");
	}

	$baseEnemy	= $pp;
	//if(!$_SESSION['universal']){
		$last_battle	= Recordset::query('SELECT id_player,ip_a,data_atk FROM batalha WHERE id_playerb=' . $baseEnemy->id . ' ORDER BY id DESC LIMIT 1');
	
		if ($last_battle->num_rows) {
			$last_battle	= $last_battle->row();
	
			if ($last_battle->ip_a == ip2long($_SERVER['REMOTE_ADDR']) || $last_battle->id_player == $basePlayer->id) {
				$diff	= get_time_difference($last_battle->data_atk, now(true));
	
				if($diff['hours'] == 0 && $diff['minutes'] < 5) {
					die("alert('Uma conta conectada com esse ip já batalhou com esse jogador antes!');");
				}
			}
		}
	//}
	
	if(!Recordset::query('
			SELECT

				*

			FROM 
				batalha_fila
			WHERE
			(id_player=' . $basePlayer->id . ' AND id_playerb=' . $baseEnemy->id . ') OR
			(id_player=' . $baseEnemy->id . ' AND id_playerb=' . $basePlayer->id . ')')->num_rows
	) {
		// retorna a posicao do player
		$pos_player		= Recordset::query("SELECT xpos, ypos FROM player_posicao WHERE id_player=" . $basePlayer->id)->row_array();
		$pos_enemy		= Recordset::query("SELECT xpos, ypos FROM player_posicao WHERE id_player=" . $baseEnemy->id)->row_array();
		
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
			'bingo_book'	=> $isBingoBook || $isBingoBookVila ? 1 : 0,
			'posicao_a'		=> $pos_player['xpos'] * 22 .','.$pos_player['ypos'] * 22,
			'posicao_b'		=> $pos_enemy['xpos'] * 22 .','.$pos_enemy['ypos'] * 22
			
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
	
		redirect_to('dojo_batalha_pvp');
	} else {
		echo "jalert('".t('actions.a206')."');";
	}
	*/
