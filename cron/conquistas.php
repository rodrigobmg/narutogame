<?php
	require('_config.php');
	
	// Processa um grupo de conquistas e premia o mané --->
		$q = Recordset::query('SELECT * FROM conquista_grupo', true);
		
		$players	= Recordset::query('
			SELECT
				id,
				nome
			
			FROM
				player
			
			WHERE
				id=1
		');
		
		foreach($players->result_array() as $player) {
			$basePlayer			= new stdClass();
			$basePlayer->id		= $player['id'];
			$basePlayer->nome	= $player['nome'];
		
			// Trazer todos os grupos de uma vez é BEM mais prático --->
				$raw_grupos	= Recordset::query('SELECT id_conquista_grupo FROM conquista_grupo_item WHERE id_player=' . $basePlayer->id);
				$grupos		= array();
				
				foreach($raw_grupos->result_array() as $grupo) {
					$grupos[]	= $grupo['id_conquista_grupo'];
				}
			// <---
			
			foreach($q->result_array() as $r) {
				if(in_array($r['id'], $grupos)) { // Se ja completou o grupo, vai pro próximo
					continue;
				}
				
				$ok	= true;
				$qq	= Recordset::query('SELECT id, req_qtd FROM conquista WHERE id_grupo=' . $r['id'], true);
				
				foreach($qq->result_array() as $rq) {
					if(!Recordset::query("SELECT id FROM conquista_item WHERE id_player=" . $basePlayer->id . " AND id_conquista=" . $rq['id'] . " AND qtd=" . $rq['req_qtd'])->num_rows) {
						$ok = false;
						
						break;
					}
				}
				
				if($ok) {
					/*
					fb_post_to_feed('Meu jogador ' . $basePlayer->nome . ' conseguiu a conquista "' . addslashes($r['nome']) . '"', array(
						'link'		=> 'http://narutogame.com.br',
						'picture'	=> 'http://narutogame.com.br/images/conquista2.jpg'
					));
					*/

					Recordset::query("INSERT INTO conquista_grupo_item(id_player, id_conquista_grupo) VALUES(" . $basePlayer->id . ", " . $r['id'] . ")");
					
					echo 'GANHOU ' . $basePlayer->id . PHP_EOL;
					continue;
					
					$msg = array();
					
					// Itens
					if($r['id_item']) {
						if($basePlayer->hasItemW($r['id_item'])) {
							Recordset::update('player_item', array(
								'qtd'		=> array('escape'	=> false, 'value' => 'qtd+' . $r['mul'])
							), array(
								'id_player'	=> $basePlayer->id,
								'id_item'	=> $r['id_item']
							));
						} else {
							Recordset::insert('player_item', array(
								'id_item'	=> $r['id_item'],
								'id_player'	=> $basePlayer->id,
								'mul'		=> $r['mul']
							));
						}
						
						$rItem = Recordset::query("SELECT nome FROM item WHERE id=" . $r['id_item'], true)->row_array();
						$msg[] = $r['mul'] . "x " . $rItem['nome'];						
					}
					
					// Ryou
					if($r['ryou']) {
						$msg[] = $r['ryou'] . " Ryous";
						
						Recordset::query("UPDATE player SET ryou=ryou + " .$r['ryou'] . " WHERE id=" . $basePlayer->id);
					}
				
					// Exp
					if($r['exp']) {
						$msg[] = $r['exp'] . " Pontos de experiência";
						
						Recordset::query("UPDATE player SET exp=exp + " .$r['exp'] . " WHERE id=" . $basePlayer->id);
					}
				
					// Ene
					if($r['ene']) {
						$msg[] = $r['ene'] . " Pontos de energia";
						
						Recordset::query("UPDATE player_atributos SET ene=ene + " .$r['ene'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// For
					if($r['forc']) {
						$msg[] = $r['forc'] . " Pontos de for&ccedil;a";
						
						Recordset::query("UPDATE player_atributos SET forc=forc + " .$r['forc'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// Int
					if($r['inte']) {
						$msg[] = $r['inte'] . " Pontos de inteligência";
						
						Recordset::query("UPDATE player_atributos SET inte=inte + " .$r['inte'] . " WHERE id_player=" . $basePlayer->id);
					}
					
					// RES
					if($r['res']) {
						$msg[] = $r['inte'] . " Pontos de resistência";
						
						Recordset::query("UPDATE player_atributos SET res=res + " .$r['res'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// Agi
					if($r['agi']) {
						$msg[] = $r['agi'] . " Pontos de agilidade";
						
						Recordset::query("UPDATE player_atributos SET agi=agi + " .$r['agi'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// Con
	
					if($r['con']) {
						$msg[] = $r['con'] . " Pontos de selo";
						
						Recordset::query("UPDATE player_atributos SET con=con + " .$r['con'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// Tai
					if($r['tai']) {
						$msg[] = $r['tai'] . " Pontos de taijutsu";
						
						Recordset::query("UPDATE player_atributos SET tai=tai + " .$r['tai'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// Nin
					if($r['nin']) {
						$msg[] = $r['nin'] . " Pontos de ninjutsu";
						
						Recordset::query("UPDATE player_atributos SET nin=nin + " .$r['nin'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// Gen
					if($r['gen']) {
						$msg[] = $r['gen'] . " Pontos de genjutsu";
						
						Recordset::query("UPDATE player_atributos SET gen=gen + " .$r['gen'] . " WHERE id_player=" . $basePlayer->id);
					}
				
					// Coin
					if($r['coin']) {
						$msg[] = $r['coin'] . " Créditos VIP";
						
						Recordset::query("UPDATE global.user SET coin=coin+ " . $r['coin'] . " WHERE id=" . $_SESSION['usuario']['id']);
					}
					
					// Titulo
					if($r['titulo']) {
						$msg[] = "Titulo Ninja: " . $r['titulo'];
						
						Recordset::query("INSERT INTO player_titulo(id_player, id_usuario,titulo) VALUES(" . $basePlayer->id . ", " . $basePlayer->id_usuario . ", '" . addslashes($r['titulo']) . "')");
					}

					$msg	= 'Você concluiu a conquista: ' . addslashes($r['nome']) . ' em ' . date("d/m/Y \a\s\ H:m:s") . " e ganhou as seguintes recompensas:\n" . join($msg, "\n");
					mensageiro(NULL, $basePlayer->id, 'Conquista: ' . addslashes($r['nome']), $msg, 'achiv');
				}
			}		
		}
