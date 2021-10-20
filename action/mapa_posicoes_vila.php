<?php
	if($basePlayer->dentro_vila) {
		$redir_script = true;
		
		redirect_to("personagem_status");	
	}

	if(isset($_POST['out']) && $_POST['out']) {
		$dipl = Player::diplOf($basePlayer->id_vila, $basePlayer->id_vila_atual);
		
		if($dipl == 2) {
			$coords = Recordset::query('SELECT xpos, ypos FROM vila WHERE id=' . $basePlayer->id_vila_atual)->row_array();
			
			Recordset::update('player', array(
				'id_vila_atual'	=> 0
			), array(
				'id'		=> $basePlayer->id
			));
			
			Recordset::update('player_posicao', array(
				'xpos'		=> $coords['xpos'],
				'ypos'		=> $coords['ypos']
			), array(
				'id_player'	=> $basePlayer->id
			));
			
			die("location.href='?secao=mapa'");
		} else {
			Recordset::update('player', array(
				'dentro_vila'	=> '1'
			), array(
				'id'		=> $basePlayer->id
			));

			die("location.href='?secao=personagem_status'");
		}
	}

	if(!isset($_POST['key']) || (isset($_POST['key']) && $_POST['key'] != $_SESSION['mapa_vila_key'])) {
		die('jalert("Dados inválidos!", null, function () { location.reload() })');
	}

	if(isset($_GET['go']) && $_GET['go']) {
		Recordset::update('player_posicao', array(
			'xpos'		=> $_POST['x'],
			'ypos'		=> $_POST['y']
		), array(
			'id_player'	=> $basePlayer->id
		));

		$local = Player::dentroLocal($basePlayer->id);
		
		// Se o cara TEM missao de invasão e foi retornado um npc na corrdenada q ele ta
		if($basePlayer->missao_invasao && isset($local['enemy']) && $local['enemy']) {
			// Se o NPC for o que ele tem q matar, go porrada --->
			if($local['npc'] == $basePlayer->missao_invasao_npc) {
				$npc_vila	= Recordset::query('SELECT * FROM npc_vila WHERE id_vila=' . $basePlayer->id_vila_atual . ' AND mlocal=' . $local['mlocal'])->row_array();
				$npc		= new NPC($npc_vila['id'], new Player($basePlayer->id), NPC_VILA);
				
				Fight::cleanup();
				
				if(!$npc_vila['batalha']) {
					SharedStore::S('_BATALHA_' . $basePlayer->id, serialize($npc));
							
					$batalha = Recordset::insert('batalha', array(
						'id_tipo'		=> 3,
						'id_player'		=> $basePlayer->id,
						'current_atk'	=> 1,
						'data_atk'		=> date('Y-m-d H:i:s')
					));

					Recordset::update('player', array(
						'id_batalha'	=> $batalha
					), array(
						'id'		=> $basePlayer->id
					));
					
					Recordset::update('npc_vila', array(
						'batalha'			=> '1',
						'id_player_batalha'	=> $batalha
					), array(
						'id'				=> $npc->id
					));
					
					
				}
			}
			
			die();
		}
		
		if($local && !isset($local['enemy'])) {
			if($local['acao']) {
				if(preg_match("/msie/i", $_SERVER['HTTP_USER_AGENT'])) {
					echo "ieGoto('index.php?acao=" . $local['acao'] . "');";	
				} else {
					echo "location.href='?acao=" . $local['acao'] . "'";
				}
			} else {
				Recordset::update('player', array(
					'dentro_vila'	=> '1'
				), array(
					'id'		=> $basePlayer->id
				));
			
				echo "location.href='?secao=" . $local['href'] . "'";
			}
		}

		die();
	}

	// Valor forçado -->
		$_GET['detail'] = 1;
	// <---

	$where		= '';

	if ($basePlayer->id_exame_chuunin) {
		$where	.= " AND a.id_exame_chuunin=" . $basePlayer->id_exame_chuunin;
	}

	$arPlayers	= array();
	$arVilas	= array();
	$players	= "SELECT 
					a.id, 
					a.nome, 
					b.xpos, 
					b.ypos, 
					a.id_vila, 
					a.level,
					id_classe AS classe
				 FROM 
				 	player a JOIN player_posicao b ON b.id_player=a.id
				WHERE 
					a.id_batalha=0 AND 
					a.hospital='0' AND
					a.id_vila_atual=" . $basePlayer->id_vila_atual . " AND 
					a.id_vila=" . $basePlayer->id_vila . " AND
					a.ult_atividade > DATE_ADD(NOW(), INTERVAL -2 MINUTE) AND
					a.dentro_vila='0' " . $where . "
					LIMIT 50";
					
	$players	= Recordset::query($players);
	$playerse	= "SELECT 
					a.id, 
					a.nome, 
					b.xpos, 
					b.ypos, 
					a.id_vila, 
					a.level,
					id_classe AS classe
				 FROM 
				 	player a JOIN player_posicao b ON b.id_player=a.id
				WHERE 
					a.id_batalha=0 AND 
					a.hospital='0' AND
					a.id_vila_atual=" . $basePlayer->id_vila_atual . " AND 
					a.id_vila != " . $basePlayer->id_vila . " AND
					a.ult_atividade > DATE_ADD(NOW(), INTERVAL -2 MINUTE) AND
					a.dentro_vila='0' " . $where . "
					LIMIT 50";

	$playerse = Recordset::query($playerse);

	// Gera a lista de vilas --->
		$vilas = Recordset::query("SELECT id, nome_" . Locale::get() . " AS nome FROM vila", true);
		
		foreach($vilas->result_array() as $vila) {
			$arVilas[$vila['id']] = $vila['nome'];
		}
	// <---

	echo "$('.matrixTipItem').remove();\n_tipMatrix = [];\n";	

	// Players da mesma vila --->
		foreach($players->result_array() as $player) {

			$cx = round($player['xpos'] / $_GET['detail']);
			$cy = round($player['ypos'] / $_GET['detail']);
	
			$player['local']				= "0";
			$arPlayers["y{$cy}x{$cx}"][]	= $player;
		}
	// <---

	// Players de outra vila --->
		foreach($playerse->result_array() as $player) {
			$cx = round($player['xpos'] / $_GET['detail']);
			$cy = round($player['ypos'] / $_GET['detail']);
	
			$player['local']				= "0";
			$arPlayers["y{$cy}x{$cx}"][]	= $player;
		}
	// <---

	// Cria o mapeamento dos locais --->
		$locais = Recordset::query("SELECT * FROM local_mapa WHERE id_vila=" . $basePlayer->id_vila_atual, true);
	
		foreach($locais->result_array() as $local) {
			$cx = $local['x'];
			$cy = $local['y'];

			$local['local']					= "1";
			$local['level']					= 0;
			$arPlayers["y{$cy}x{$cx}"][]	= $local;
		}
	// <---

	foreach($arPlayers as $k => $root) {
		echo "if(!_tipMatrix['{$k}']) _tipMatrix['{$k}'] = [];\n";
		
		foreach($root as $r) {
			$dipl	= 0;

			/*
			if($r['id']) {
				$another_battle	= Recordset::query('
					SELECT
						id
					
					FROM
						player_batalhas
					
					WHERE
						(id_player=' . $basePlayer->id . ' AND id_playerb=' . $r['id'] . ') 
						#OR (id_player=' . $r['id'] . ' AND id_playerb=' . $basePlayer->id . ')) 
						AND HOUR(TIMEDIFF(NOW(), data_ins)) <= 1');
				
				if($another_battle->num_rows > 1) {
					continue;
				}
			}
			*/
		
			if(!$r['local']) {
				$cx = round($r['xpos'] / $_GET['detail']);
				$cy = round($r['ypos'] / $_GET['detail']);
			} else {
				$cx = $r['x'];
				$cy = $r['y'];				
			}
			
			$id = encode($r['id']);

			if(!isset($r['mlocal'])) {
				$bingo_book			= is_bingo_book_player($basePlayer->id, $r['id'], false);
				$bingo_book_vila	= is_bingo_book_vila($basePlayer->id_vila, $r['id'], false);
				$bingo_book_guild	= is_bingo_book_guild($basePlayer, $r['id'], false);
				$bingo_book_equipe	= is_bingo_book_equipe($basePlayer, $r['id'], false);

				if($bingo_book || $bingo_book_vila || $bingo_book_guild || $bingo_book_equipe) {
					$dipl = 3;
				} else {
					$dipl = Player::diplOf($basePlayer->id_vila, $r['id_vila']);
				}
			}

			if(gHasItemW(20290, $r['id'], NULL, 24)) {
				$renegado 		= 1;
			} else {
				$renegado = 0;
			}

			$camuflado = 0;

			if(gHasItemW(20291, $r['id'], NULL, 24)) {
				$active_professions		= Recordset::query('SELECT * FROM profissao_ativa WHERE id_profissao=4 AND id_player_alvo=' . $r['id'])->result_array();
				$has_active_profession	= false;

				foreach ($active_professions as $active_profession) {
					$now		= now();
					$limit		= strtotime('+10 minute', strtotime($active_profession['data_ins']));

					if ($now < $limit) {
						$has_active_profession	= true;
					}
				}

				if (!$has_active_profession) {
					$camuflado 		= 1;
					$dipl			= 1;
					$r['id_vila']	= $basePlayer->id_vila;
				}
			}
			
			// Para os itens que forem vilas
			if(!$r['level']) {
				$r['level'] = 0;
			}
			
			if(isset($r['nome_br'])) {
				$r['nome']	= $r['nome_' . Locale::get()];	
			}
			
			if($r['level'] > ($basePlayer->level + 7) && !$basePlayer->id_arena && !$basePlayer->id_exame_chuunin) {
				$r['nome'] = '??';
				$r['level'] = '??';
				$vila = '??????????';
			}else{
				$vila = addslashes($arVilas[$r['id_vila']]);
			}
			
			if(!isset($r['classe'])) {
				$r['classe']	= 0;
			}
			
			if($basePlayer->id_arena || $basePlayer->id_exame_chuunin) {
				$dipl	= 2;
			}
			$player_item_camuflado = Recordset::query('select count(1) item_camuflagem from player_item where id_item = 1494465 and id_player = ' . $r['id'])->row_array();
			$tema = Recordset::query("SELECT b.imagem, b.id_classe, b.tema, b.id FROM player_imagem a JOIN classe_imagem b ON b.id=a.id_classe_imagem WHERE a.id_player=" . $r['id']);
			if($player_item_camuflado['item_camuflagem']){
				$icone_novo = Recordset::query('select id_imagem_camu from player_flags where id_player = '.$r['id'])->row_array();
				$icone = $icone_novo['id_imagem_camu'];
			}else{
				$icone = $r['classe'] . ($tema->num_rows && $tema->row()->tema ? '-' . $tema->row()->imagem : '');
			}
			
			
			echo "_tipMatrix['y{$cy}x{$cx}'].push(['" . addslashes($r['nome']) . "', '$cx', '$cy', {$r['local']}, '$id', '" . $vila . "', '" . $r['level'] . "', {$dipl}, " . (int)$r['id_vila'] . ", " . $renegado . ", " . $camuflado . ", '" . $icone . "']);\n";
		}
	}

	// NPC da vila --->
		$npcs = Recordset::query("
			SELECT
				a.id, 
				a.hp, 
				a.less_hp,
				b.x,
				b.y
			FROM 
				npc_vila a JOIN local_mapa b ON b.id_vila=a.id_vila AND b.mlocal=a.mlocal
							 
			WHERE a.id_vila=" . $basePlayer->id_vila_atual);
		
		foreach($npcs->result_array() as $npc) {
			echo "_nM['y{$npc['y']}x{$npc['x']}'] = [{$npc['hp']}, {$npc['less_hp']}];\n";
		}
	// <---

	echo "_update_nM();\nupdateTipMatrix();\n";
