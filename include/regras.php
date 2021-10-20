<?php
	function regras_validate() {
		global $is_action;
		global $auth;
		global $regras_valido;
		global $basePlayer;

		if(!($is_action  && in_array($_GET['acao'], $auth))
		&& !(!$is_action && in_array($_GET['secao'], $auth))) {			
			
			/*
			if(!$basePlayer) {
				$rPlayer = Recordset::query("
					SELECT 
						a.id_batalha, 
						b.id_tipo AS id_tipo_batalha, 
						a.hospital,
						c.id_tipo AS id_batalha_multi_tipo
					FROM 
						player a LEFT JOIN batalha b ON b.id=a.id_batalha 
						LEFT JOIN batalha_multi c ON c.id=a.id_batalha_multi
					
					WHERE a.id=" . (int)$_SESSION['basePlayer'])->row_array();
				
				
				$basePlayer = new stdClass();
				$basePlayer->id_batalha = (int)$rPlayer['id_batalha'];
				$basePlayer->id_batalha_multi = (int)$rPlayer['id_batalha_multi'];
				$basePlayer->tipo_batalha = (int)$rPlayer['id_tipo_batalha'];
				$basePlayer->hospital = (int)$rPlayer['hospital'];
				
				$is_action = true;
			}

			if($is_action) {
				$redir = true;	
			}			
			
			if($basePlayer) {
				$id_batalha			= isset($basePlayer->id_batalha) ? $basePlayer->id_batalha : $basePlayer->getAttribute('id_batalha');
				$id_batalha_multi	= isset($basePlayer->id_batalha_multi) ? $basePlayer->id_batalha_multi : $basePlayer->getAttribute('id_batalha_multi');
				$hospital			= isset($basePlayer->hospital) ? $basePlayer->hospital : $basePlayer->getAttribute('hospital');
			
				if($id_batalha) {
					$tipo_batalha = isset($basePlayer->getAttribute('tipo_batalha')) && tipo_batalha ? $basePlayer->tipo_batalha : $basePlayer->getAttribute('tipo_batalha');
				
					if( on($basePlayer->getAttribute('tipo_batalha'), array(1, 3 ,6)) ) {
						redirect_to("dojo_batalha_lutador", "", "", $redir);
					} else {
						redirect_to("dojo_batalha_pvp", "", "", $redir);			
					}
				} elseif($id_batalha_multi) {
					redirect_to("dojo_batalha_multi", "", "", $redir);
				}
				
				if($hospital) {
					redirect_to("hospital_quarto", "", "", $redir);
				}
			}
			*/
			
			$redir_script = true;
			
			if($basePlayer) {
				if($basePlayer->getAttribute('id_batalha')) {
					/*if($_SESSION['universal']){
						die($basePlayer->getAttribute('tipo_batalha'));
					}*/
					if(on($basePlayer->getAttribute('tipo_batalha'), array(1, 3, 6, 7, 8)) ) {
						redirect_to("dojo_batalha_lutador", "", array('other_redir' => 1), true);
					} else {
						redirect_to("dojo_batalha_pvp", "", array('other_redir' => 1), true);			
					}
				} elseif($basePlayer->getAttribute('id_batalha_multi')) {
					redirect_to("dojo_batalha_multi", "", array('other_redir' => 1), true);
				}
			}
			
			$regras_valido = false;
		}
	}

	function regras_ext_menu_callback($matches) {
		return urlencode(encode($matches[1]));
	}

  $auth = [];
  $menu = [];
	$global_menu_top = [];

	// Subir de nivel é hard coded --->
		if($basePlayer && ($basePlayer->exp >= Player::getNextLevel($basePlayer->level) && !$basePlayer->id_batalha )) {
			if(Player::getFlag('level_page_seen', $basePlayer->id)) {
				if(method_exists($basePlayer, 'getAttribute')) {
					$basePlayer	= new Player($basePlayer->id);
				}

				$rest_exp		= intval($basePlayer->exp - Player::getNextLevel($basePlayer->level));
				$torneio_player	= Recordset::query('SELECT * FROM torneio_player WHERE participando=\'1\' AND id_player=' . $basePlayer->id);

				$basePlayer->level++;
				$basePlayer->setAttribute('level', $basePlayer->level);

				Recordset::update('player', [
					'less_hp'	=> 0,
					'less_sp'	=> 0,
					'less_sta'	=> 0,
					'exp'		=> $rest_exp
				], [
					'id'		=> $basePlayer->id
				]);

				// Conqiusta --->
					arch_parse(NG_ARCH_SELF, $basePlayer);
				// <---
				
				equipe_exp(200);				

				if($torneio_player->num_rows) {
					$torneio	= Recordset::query('SELECT * FROM torneio WHERE id=' . $torneio_player->row()->id_torneio, true)->row_array();
					
					if(($basePlayer->level + 1) > $torneio['req_level_fim'] || $basePlayer->id_graduacao != $torneio['req_id_graduacao']) {
						Recordset::update('torneio_player', array(
							'participando'	=> 0
						), array(
							'id_player'		=> $basePlayer->id,
							'id_torneio'	=> $torneio['id']
						));
						
						Recordset::delete('torneio_espera', array(
							'id_player'		=> $basePlayer->id,
							'id_torneio'	=> $torneio['id']
						));
					}
				}
			} else {
				$auth[]			= "proximo_nivel";
				$proximo_nivel	= true;
			}
		}
	// <---

	// Seções --->
		$qCat			= Recordset::query("SELECT *, src_".Locale::get()." as src FROM menu_categoria ORDER BY ordem ASC", true);
		$qMenu			= Recordset::query("SELECT * FROM menu ORDER BY id_menu_categoria ASC, ordem ASC", true);
		$menus			= array();
		$combat_menu	= null;
		
		foreach($qMenu->result_array() as $m) {
			if(!isset($menus[$m['id_menu_categoria']])) {
				$menus[$m['id_menu_categoria']] = array();
			}
			
			$menus[$m['id_menu_categoria']][] = $m;
		}

		foreach($qCat->result_array() as $rCat) {
			if(!isset($menus[$rCat['id']])) {
				continue;	
			}

			$category_item		= [
				'id'	=> $rCat['id'], 
				'img'	=> $rCat['src'],
				'item'	=> $rCat['nome'], 
				'items'	=> []
			];

			if(menu_conds($rCat, $basePlayer)) {
				foreach($menus[$rCat['id']] as $rMenu) {
					if(menu_conds($rMenu, $basePlayer)) {
						if(strpos($rMenu['href'], "&") === false) {
							$auth[] = $rMenu['href'];
						} else {
							$auth[] = substr($rMenu['href'], 0, strpos($rMenu['href'], "&"));
						}
						
						if(!isset($rMenu['externo'])) {
							$rMenu['externo'] = 0;
						}
						
						if(!$rMenu['externo']) {
							$rMenu['href'] = preg_replace_callback("/\:([\w]+)/si", 'regras_ext_menu_callback', $rMenu['href']);
						}
						
						if(!$rMenu['oculto']) {
							$category_item['items'][] = array("item" => t($rMenu['titulo']), "href" => $rMenu['href'], "externo" => $rMenu['externo']);
						}
					}
				}
				
				$menu[] = $category_item;
			}

			if($basePlayer) {
				if(
					$category_item['id'] == 10 ||
					($category_item['id'] == 15 && $basePlayer->id_vila_atual)	|| // Some o continente
					($category_item['id'] == 11 && !$basePlayer->id_vila_atual) || // Some o vila atual
					($category_item['id'] == 14 && !$basePlayer->hospital) || // Some o hospital
					($category_item['id'] == 12 && $basePlayer->hospital) // Some o combates
				) {
					continue;
				}				
			}

			$global_menu_top[]	= $category_item;

			if($category_item['id'] == 12) {
				$combat_menu	=& $global_menu_top[sizeof($global_menu_top) - 1];
			}
		}
	// <---
	
	// Actions --->
		//$qAct = Recordset::query("SELECT * FROM menu_acao");
		//while($rAct = $qAct->row_array()) {
			
		$qAct = Recordset::query("SELECT * FROM menu_acao", true);
		foreach($qAct->result_array() as $rAct) {
			if(menu_conds($rAct, $basePlayer)) {
				$auth[] = $rAct['href'];
			}
		}
	// <---

	// Porrada no mapa mundi
	if($basePlayer && !$basePlayer->getAttribute('dentro_vila') && $basePlayer->getAttribute('id_batalha')) {
		if( on($basePlayer->getAttribute('tipo_batalha'), array(1, 3, 6, 7, 8)) ) {

			$subitems	= array(
				array(
					"item" => t('menus.dojo_npc'), 
					"href" => "dojo_batalha_lutador" 
				),
				array(
					"item" => t('menus.dojo_fugir'), 
					"href" => "javascript:doAtkFlight()",
					"externo" => 1
				)
			);

			$auth[] = "dojo_batalha_lutador";
			$auth[] = "dojo_lutador_lutar";
			$menu[] = array(
				"id"	=> 12, 
				"img"	=> (Locale::get()=="br" ? "combates.png" : "combates2.png"),
				"item"	=> "Combates", 
				"items"	=> $subitems
			);

			$combat_menu['items']	= $subitems;
		} else {
			$subitems	= array(
				array(
					"item" => t('menus.dojo_pvp'), 
					"href" => "dojo_batalha_pvp"
				),
				array(
					"item" => t('menus.dojo_fugir'), 
					"href" => "javascript:doAtkFlight()",
					"externo" => 1
				)
			);

			$auth[] = "dojo_batalha_pvp";
			$auth[] = "dojo_batalha_lutar";
			$menu[] = array(
				"id"	=> 12, 
				"img"	=> (Locale::get()=="br" ? "combates.png" : "combates2.png"),
				"item"	=> "Combates", 
				"items"	=> $subitems
			);

			$combat_menu['items']	= $subitems;
		}
	} elseif($basePlayer && $basePlayer->getAttribute('dentro_vila') && $basePlayer->getAttribute('id_batalha')) { // condição especial para vila neutra
		if( on($basePlayer->getAttribute('tipo_batalha'), array(1,8)) && on($basePlayer->id_vila_atual, array(1,2,3,4,5,6,7,8,9, 10, 11, 12,13))) {
			$subitems	= array(
				array(
					"item" => t('menus.dojo_npc'), 
					"href" => "dojo_batalha_lutador" 
				),
				array(
					"item" => t('menus.dojo_fugir'), 
					"href" => "javascript:doAtkFlight()",
					"externo" => 1
				)
			);

			$auth[] = "dojo_batalha_lutador";
			$auth[] = "dojo_lutador_lutar";
			$menu[] = array(
				"id"	=> 12, 
				"img"	=> (Locale::get()=="br" ? "combates.png" : "combates2.png"),
				"item"	=> "Combates", 
				"items"	=> $subitems
			);

			$combat_menu['items']	= $subitems;
		}
	}
	
	$auth[] = "negado";

	if($_SESSION['universal']) {
		$auth[]	= 'cache';
	}
