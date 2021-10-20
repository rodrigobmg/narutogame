<?php
	header("Content-Type: application/json");

	$json			= new stdClass();
	$json->places	= array();
	$json->players	= array();
	$json->qr		= array();


	if($basePlayer->dentro_vila) {
		$json->redirect	= '?secao=personagem_status';
		
		die(json_encode($json));
	}

	if(!isset($_POST['key']) || (isset($_POST['key']) && $_POST['key'] != $_SESSION['mapa_mundi_key'])) {
		$json->invalid_key	= true;

		die(json_encode($json));
	}

	if($basePlayer->id_vila_atual) {
		$json->redirect	= '?secao=mapa_vila';
	} else {
		if($basePlayer->id_batalha) {
			$b = Recordset::query("SELECT id_tipo FROM batalha WHERE id=" . $basePlayer->id_batalha)->row_array();
			
			if($b['id_tipo'] == 3) { // NPC
				$json->redirect	= '?secao=dojo_batalha_lutador';
			} else {
				$json->redirect	= '?secao=dojo_batalha_pvp';
			}
		} else {
			// Cache das vilas para evitar queries extras --->
				$q_vilas	= Recordset::query('SELECT * FROM vila', true);
				$vilas		= array();
				
				foreach($q_vilas->result_array() as $vila) {
					$vilas[$vila['id']]	= $vila['nome_' . Locale::get()];
				}
			// <---
			
			$pos		= Recordset::query("SELECT xpos, ypos FROM player_posicao WHERE id_player=" . $basePlayer->id)->row_array();
		
			$xs			= ($pos['xpos'] - $_POST['cx']) * 22;
			$xe			= ($pos['xpos'] + $_POST['cx']) * 22;
			$ys			= ($pos['ypos'] - $_POST['cy']) * 22;
			$ye			= ($pos['ypos'] + $_POST['cy']) * 22;

			$json->info = array(
				'xs'	=> $xs,
				'xe'	=> $xe,
				'ys'	=> $ys,
				'ye'	=> $ye,
			);
		
			$places = Recordset::query("SELECT id, nome_" . Locale::get() . " AS nome, xpos, ypos FROM vila FORCE KEY(idx_pos) WHERE xpos BETWEEN $xs AND $xe AND ypos BETWEEN $ys AND $ye");		
			
			foreach($places->result_array() as $place) {
				$json->places[]	= array(
					'name'			=> $place['nome'],
					'x'				=> $_POST['cx'] + (($place['xpos'] / 22) - $pos['xpos']),
					'y'				=> $_POST['cy'] + (($place['ypos'] / 22) - $pos['ypos']),
					'type'			=> "place"
				);
			}

			$players	= Recordset::query("
				SELECT 
					id_player AS player, 
					nome, 
					xpos, 
					ypos, 
					vila, 
					classe,
					nivel AS level,
					ult_atividade AS updated_at,
					missao,
					missao_interativa,
					missao_guild,
					evento
				FROM 
					player_posicao
				WHERE 
					batalha=0 AND 
					vila_atual=0 AND
					dentro_vila='0' AND
					xpos BETWEEN ($xs / 22) AND ($xe / 22) AND 
					ypos BETWEEN ($ys / 22) AND ($ye / 22) AND
					ult_atividade > DATE_ADD(NOW(), INTERVAL -2 MINUTE)");
		
			foreach($players->result_array() as $player) {
				$bb	= is_bingo_book($basePlayer->id, $player['player'], true);

				if(
					($player['missao_interativa']  && !$basePlayer->id_missao && !$bb) ||
					(!$player['missao_interativa'] && $basePlayer->id_missao  && !$bb)
				) {
					// Tira o jogador do mapa quando esta em missao interativa
					continue;
				}

				if(
					($player['missao_guild']  && !$basePlayer->id_missao_guild && !$bb) ||
					(!$player['missao_guild'] && $basePlayer->id_missao_guild  && !$bb)
				) {
					continue;
				}

				if(
					($player['evento']  && !$basePlayer->id_evento && !$bb) ||
					(!$player['evento'] && $basePlayer->id_evento  && !$bb)
				) {
					continue;
				}

				/*
				$another_battle	= Recordset::query('
					SELECT
						id
					
					FROM
						player_batalhas
					
					WHERE
						(id_player=' . $basePlayer->id . ' AND id_playerb=' . $player['player'] . ') 
						#OR (id_player=' . $player['player'] . ' AND id_playerb=' . $basePlayer->id . ')) 
						AND HOUR(TIMEDIFF(NOW(), data_ins)) <= 1');
				
				if($another_battle->num_rows > 1) {
					continue;
				}
				*/				
				
				$dipl				= Player::diplOf($basePlayer->id_vila, $player['vila']);

				if(gHasItemW(20291, $player['player'], NULL, 24)) {
					$active_professions		= Recordset::query('SELECT * FROM profissao_ativa WHERE id_profissao=4 AND id_player_alvo=' . $player['player'])->result_array();
					$has_active_profession	= false;

					foreach ($active_professions as $active_profession) {
						$now		= now();
						$limit		= strtotime('+10 minute', strtotime($active_profession['data_ins']));

						if ($now < $limit) {
							$has_active_profession	= true;
						}
					}

					if (!$has_active_profession) {
						$dipl			= 1;
						$player['vila']	= $basePlayer->id_vila;
					}
				}
				
				if($player['level'] - $basePlayer->level >= 7) {
					$player['nome']		= '??';
					$player['level']	= '??';
					$vila 				= '??????????';
				}else{
					$vila 				= $vilas[$player['vila']];
				}
				
				$tema 				= Recordset::query("SELECT b.imagem, b.id_classe, b.tema, b.id FROM player_imagem a JOIN classe_imagem b ON b.id=a.id_classe_imagem WHERE a.id_player=" . $player['player']);

				$player_item_camuflado = Recordset::query('select count(1) item_camuflagem from player_item where id_item = 1494465 and id_player = ' . $player['player'])->row_array();
				
			
				if($player_item_camuflado['item_camuflagem']){
					$icone_novo = Recordset::query('select id_imagem_camu from player_flags where id_player = '.$player['player'])->row_array();
					$icone = $icone_novo['id_imagem_camu'];
				}else{
					$icone = $player['classe'] . ($tema->num_rows && $tema->row()->tema ? '-' . $tema->row()->imagem : '');
				}
				
				$bingo_book			= is_bingo_book_player($basePlayer->id, $player['player'], false);
				$bingo_book_vila	= is_bingo_book_vila($basePlayer->id_vila, $player['player'], false);
				
				if($basePlayer->id_guild) {
					$bingo_book_guild	= is_bingo_book_guild($basePlayer, $player['player'], false);
				} else {
					$bingo_book_guild	= false;
				}

				if($basePlayer->id_equipe) {
					$bingo_book_equipe	= is_bingo_book_equipe($basePlayer, $player['player'], false);
				} else {
					$bingo_book_equipe	= false;
				}
				
				$json->players[]	= array(
					'id'			=> salt_encrypt($player['player'], $_SESSION['mapa_mundi_key']),
					'name'			=> $player['nome'],
					'world'			=> $vila,
					'enemy'			=> $dipl == 2,
					'level'			=> $player['level'],
					/*'klass'			=> $player['classe'] . ($tema->num_rows && $tema->row()->tema ? '-' . $tema->row()->imagem : ''),*/
					'klass'			=> $icone,
					'x'				=> $_POST['cx'] + ($player['xpos'] - $pos['xpos']),
					'y'				=> $_POST['cy'] + ($player['ypos'] - $pos['ypos']),
					'type'			=> "player",
					'dipl'			=> $dipl,
					'target'		=>  $bingo_book || $bingo_book_vila || $bingo_book_guild || $bingo_book_equipe ? true : false
				);
			}
			
			// Loop dos npc de evento --->
				if($basePlayer->id_evento) {
					$npcs_evento = Recordset::query("
						SELECT 
							a.id, 
							a.nome_".Locale::get()." AS nome, 
							a.xpos, 
							a.ypos,
							b.id_evento
						FROM 
							evento_npc a JOIN evento_npc_evento b ON b.id_evento_npc=a.id AND b.id_evento=" . $basePlayer->id_evento . "
							JOIN evento_npc_equipe c ON c.id_evento_npc=a.id AND c.id_equipe=" . $basePlayer->id_equipe . " AND c.id_evento=" . $basePlayer->id_evento . "
						WHERE 
							c.morto=0
							AND xpos BETWEEN $xs AND $xe AND ypos BETWEEN $ys AND $ye");
					
					foreach($npcs_evento->result_array() as $npc_evento) {
						$json->players[]	= array(
							'id'		=> salt_encrypt($npc_evento['id'], $_SESSION['mapa_mundi_key']),
							'raw_id'	=> $npc_evento['id'],
							'name'		=> $npc_evento['nome'],
							'enemy'		=> true,
							'level'		=> '??',
							'x'			=> $_POST['cx'] + ($npc_evento['xpos'] / 22 - $pos['xpos']),
							'y'			=> $_POST['cy'] + ($npc_evento['ypos'] / 22 - $pos['ypos']),
							'type'		=> "npc"
						);
					}
				}				
			// <---	
			
			// Evento global(invasão zetsu)		
				$evento_global = Player::eventoGlobal();
				
				if($evento_global && !($basePlayer->id_missao || $basePlayer->id_evento || $basePlayer->id_missao_guild)) {
					$qEventoNPCGlobal = Recordset::query("
						SELECT 
							a.id,
							a.nome_" . Locale::get() . " AS nome,
							a.xpos,
							a.ypos,
                            b.id_evento
							
						FROM 
							evento_npc a JOIN evento_npc_evento b ON b.id_evento_npc=a.id AND b.id_evento=" . $evento_global . "
						
						WHERE
                        	b.morto_global=0 AND
							b.batalha_global=0 AND
							xpos BETWEEN $xs AND $xe AND ypos BETWEEN $ys AND $ye
						
						LIMIT 50
					");
					
					foreach($qEventoNPCGlobal->result_array() as $npc_evento) {
						$json->players[]	= array(
							'id'		=> salt_encrypt($npc_evento['id'], $_SESSION['mapa_mundi_key']),
							'raw_id'	=> 184,
							'name'		=> $npc_evento['nome'],
							'enemy'		=> true,
							'level'		=> '??',
							'x'			=> $_POST['cx'] + round($npc_evento['xpos'] / 22 - $pos['xpos']),
							'y'			=> $_POST['cy'] + round($npc_evento['ypos'] / 22 - $pos['ypos']),
							'type'		=> "npc",
							'invasion'	=> true
						);
					}
				}
			//
		}
		
		// Quest Helper -->
			//if($basePlayer->id_missao && !$basePlayer->missao_invasao && gHasItem(1026)) {				
			if($basePlayer->id_missao) {
				$qNPC = false;
			
				if($basePlayer->missao_equipe) {
					$qnnpc = Recordset::query("
						SELECT 
							id_npc 
						FROM 
							equipe_quest_npc 
						
						WHERE 
							id_player=" . $basePlayer->id . " AND 
							id_player_quest=". (int)$basePlayer->id_missao . " AND
							id_equipe=" . $basePlayer->id_equipe . " AND 
							(qtd < 1 OR qtd IS NULL)");
					
					if($qnnpc->num_rows) {
						$nnpc = $qnnpc->row_array();
						$qNPC = Recordset::query("SELECT id_npc FROM quest_npc_item WHERE id_npc=" . $nnpc['id_npc'], true);
					}
				} else {
					$qNPC = Recordset::query("SELECT id_npc FROM quest_npc_item WHERE id_quest=" . $basePlayer->id_missao, true);
				}
				
				if($qNPC) {
					foreach($qNPC->result_array() as $rNPC) {
						$rq = Recordset::query("SELECT x1, y1, x2, y2 FROM npc WHERE id=" . $rNPC['id_npc'], true)->row_array();
						
						$json->qr[]	= array2obj(array(
							'x1'	=> $rq['x1'] / 22,
							'x2'	=> $rq['x2'] / 22,
							'y1'	=> $rq['y1'] / 22,
							'y2'	=> $rq['y2'] / 22
						));
					}				
				}
			}
			
			if(($basePlayer->id_missao_guild || $basePlayer->id_missao_guild2)) { //  && ghasItem(20794)
				for($f = 0; $f <= 1; $f++) {
					if($f == 0 && !$basePlayer->id_missao_guild2) {
						continue;	
					}

					if($f == 1 && !$basePlayer->id_missao_guild) {
						continue;	
					}
					
					$quest			= $f ? $basePlayer->id_missao_guild : $basePlayer->id_missao_guild2;
					$quest_items	= Recordset::query('
						SELECT 
							a.*,
							b.nome_' . Locale::get() . ' AS npc_nome,
							c.nome_' . Locale::get() . ' AS item_nome,
							b.x1,
							b.y1,
							b.x2,
							b.y2
	
						FROM 
							quest_guild_npc_item a LEFT JOIN npc b ON b.id=a.id_npc
							LEFT JOIN item c ON c.id=a.id_item
	
						WHERE 
							a.id_quest_guild=' . $quest, true);
	
					foreach($quest_items->result_array() as $quest_item) {
						$json->qr[]	= array2obj(array(
							'x1'	=> $quest_item['x1'] / 22,
							'x2'	=> $quest_item['x2'] / 22,
							'y1'	=> $quest_item['y1'] / 22,
							'y2'	=> $quest_item['y2'] / 22
						));
					}
				}
			}
		// <---
	}
	
	if(isset($_SESSION['_random_npc_map_msg']) && isset($_SESSION['_random_npc_map']) && $_SESSION['_random_npc_map_msg'] && $_SESSION['_random_npc_map']) {
		$json->invites[]	= array2obj(array(
			'npc'	=> true,
			'cid'	=> salt_encrypt(1, $_SESSION['mapa_mundi_key'])
		));
		
		//$_SESSION['_random_npc_map_msg'] = false;
	}

	// Loop de correção das coordenadas do quest helper
		foreach($json->qr as $k => $v) {
			$v->x1	= $_POST['cx'] + ($v->x1 - $pos['xpos']);
			$v->x2	= $_POST['cx'] + ($v->x2 - $pos['xpos']);
			$v->y1	= $_POST['cy'] + ($v->y1 - $pos['ypos']);
			$v->y2	= $_POST['cy'] + ($v->y2 - $pos['ypos']);
		}
	// <---

	$json->player		= new stdClass();
	$json->player->x	= isset($pos['xpos']) ? $pos['xpos'] : 0;
	$json->player->y	= isset($pos['ypos']) ? $pos['ypos'] : 0;
	$json->player->id	= salt_encrypt($basePlayer->id, $_SESSION['mapa_mundi_key']);

	if ($basePlayer->id_guerra_ninja && !$basePlayer->missao_interativa && !$basePlayer->id_missao_guild && !$basePlayer->id_missao_guild2) {
		$war_npcs	= Recordset::query("
			SELECT
				*

			FROM
				guerra_ninja_npcs

			WHERE
				mapa=1
				AND morto=0
				AND batalha=0
				AND xpos BETWEEN ($xs) AND ($xe) 
				AND ypos BETWEEN ($ys) AND ($ye)
				AND akatsuki=" . $basePlayer->guerra_ninja->akatsuki);

		foreach($war_npcs->result_array() as $npc) {
			$json->players[]	= [
				'id'		=> salt_encrypt($npc['id'], $_SESSION['mapa_mundi_key']),
				'raw_id'	=> $npc['id'],
				'name'		=> $npc['nome_' . Locale::get()],
				'enemy'		=> true,
				'level'		=> '??',
				'x'			=> $_POST['cx'] + round($npc['xpos'] / 22 - $pos['xpos']),
				'y'			=> $_POST['cy'] + round($npc['ypos'] / 22 - $pos['ypos']),
				'type'		=> "npc",
				'war'		=> true,
				'icon'		=> $npc['icone']
			];
		}
	}

	echo json_encode($json);
