<?php
	function sorte_ninja_premio($id, $player, $user, $ignore_coin = false, $type= '') {
		$options_at		= array();
		$options_user	= array();
		$options_player	= array();
		$log			= array();

		$sorte_ninja	= Recordset::query('SELECT * FROM loteria_premio WHERE id=' . $id, true)->row_array();

		// Itens
		if($sorte_ninja['id_item']) {
			if(Recordset::query('SELECT id FROM player_item WHERE id_player=' . $player . ' AND id_item=' . $sorte_ninja['id_item'])->num_rows) {
				Recordset::update('player_item', array(
					'qtd'		=> array('escape' => false, 'value' => 'qtd+' .  $sorte_ninja['mul'])
				), array(
					'id_player'	=> $player,
					'id_item'	=> $sorte_ninja['id_item']
				));			
			} else {
				Recordset::insert('player_item', array(
					'id_player'	=> $player,
					'id_item'	=> $sorte_ninja['id_item'],
					'qtd'		=> $sorte_ninja['mul']
				));
			}
		}
		
		// User --->		
			if($sorte_ninja['coin'] && !$ignore_coin) {
				$options_user['coin']	= array('escape' => false, 'value' => 'coin + ' . $sorte_ninja['coin']);
				$log['coin']			= $sorte_ninja['coin'];
			}
	
			if($sorte_ninja['ryou']) {
				$options_player['ryou']	= array('escape' => false, 'value' => 'ryou + ' . $sorte_ninja['ryou']);
			}
		// <---
		
		// Atributos do player --->
			if($sorte_ninja['tai']) {
				$options_at['tai']	= array('escape' => false, 'value' => 'tai + ' . $sorte_ninja['tai']);
			}
			
			if($sorte_ninja['ken']) {
				$options_at['ken']	= array('escape' => false, 'value' => 'ken + ' . $sorte_ninja['ken']);
			}
		
			if($sorte_ninja['nin']) {
				$options_at['nin']	= array('escape' => false, 'value' => 'nin + ' . $sorte_ninja['nin']);
			}
		
			if($sorte_ninja['gen']) {
				$options_at['gen']	= array('escape' => false, 'value' => 'gen + ' . $sorte_ninja['gen']);
			}			
		
			if($sorte_ninja['agi']) {
				$options_at['agi']	= array('escape' => false, 'value' => 'agi + ' . $sorte_ninja['agi']);
			}
		
			if($sorte_ninja['con']) {
				$options_at['con']	= array('escape' => false, 'value' => 'con + ' . $sorte_ninja['con']);
			}
		
			if($sorte_ninja['ene']) {
				$options_at['ene']	= array('escape' => false, 'value' => 'ene + ' . $sorte_ninja['ene']);
			}
		
			if($sorte_ninja['forc']) {
				$options_at['forc']	= array('escape' => false, 'value' => 'forc + ' . $sorte_ninja['forc']);
			}

			if($sorte_ninja['inte']) {
				$options_at['inte']	= array('escape' => false, 'value' => 'inte + ' . $sorte_ninja['inte']);
			}

			if($sorte_ninja['res']) {
				$options_at['res']	= array('escape' => false, 'value' => 'res + ' . $sorte_ninja['res']);
			}		
		// <---	
	
		// Player -->
			if($sorte_ninja['treino']) {
				$options_player['treino_total']	= array('escape' => false, 'value' => 'treino_total + ' . $sorte_ninja['treino']);
			}

			if($sorte_ninja['exp']) {
				$options_player['exp']	= array('escape' => false, 'value' => 'exp + ' . $sorte_ninja['exp']);
			}
		// <--
		
		if(sizeof($options_at)) {
			Recordset::update('player_atributos', $options_at, array(
				'id_player'	=> $player
			));
		}
		
		if(sizeof($options_player)) {
			Recordset::update('player', $options_player, array(
				'id'	=> $player
			));			
		}

		if(sizeof($options_user)) {
			Recordset::update('global.user', $options_user, array(
				'id'	=> $user
			));			
		}

		$log['id_player']		= $player;
		$log['recebido']		= 1;
		$log['exp']				= (int)$sorte_ninja['exp'];
		$log['ryou']			= (int)$sorte_ninja['ryou'];
		$log['treino_total']	= (int)$sorte_ninja['treino'];
		$log['tai']				= (int)$sorte_ninja['tai'];
		$log['ken']				= (int)$sorte_ninja['ken'];
		$log['nin']				= (int)$sorte_ninja['nin'];
		$log['gen']				= (int)$sorte_ninja['gen'];
		$log['agi']				= (int)$sorte_ninja['agi'];
		$log['con']				= (int)$sorte_ninja['con'];
		$log['ene']				= (int)$sorte_ninja['ene'];
		$log['forc']			= (int)$sorte_ninja['forc'];
		$log['inte']			= (int)$sorte_ninja['inte'];
		$log['res']				= (int)$sorte_ninja['res'];
		$log['id_item']			= (int)$sorte_ninja['id_item'];
		$log['qtd_item']		= (int)$sorte_ninja['mul'];
		
		if($type == 'historia') {
			$log['fonte']			= 'historia';
		
			// Recompensa
			Recordset::insert('player_recompensa_log', $log);
		}
	}
	
	function sorte_ninja_tooltip($id) {
		$sorte_ninja	= Recordset::query('SELECT * FROM loteria_premio WHERE id=' . $id, false)->row_array();
		$premios_lista	= "";
		
		// Itens
		if($sorte_ninja['id_item']) {
			$item			= Recordset::query("SELECT nome FROM item WHERE id=" . $sorte_ninja['id_item'], true)->row_array();
			$premios_lista .= "<li>" . $item['nome'] . " x" . $sorte_ninja['mul'] . "</li>";
		}
		
		if($sorte_ninja['ryou']) {
			$premios_lista .= "<li>" . $sorte_ninja['ryou'] . " Ryou</li>";
		}

		if($sorte_ninja['exp']) {
			$premios_lista .= "<li>" . $sorte_ninja['exp'] . " " . t('academia_treinamento.at21')."</li>";
		}

		if($sorte_ninja['tai']) {
			$premios_lista .= "<li>" . $sorte_ninja['tai'] . " " . t('at.pontos_tai')."</li>";
		}
		if($sorte_ninja['ken']) {
			$premios_lista .= "<li>" . $sorte_ninja['ken'] . " " . t('at.pontos_ken')."</li>";
		}

		if($sorte_ninja['nin']) {
			$premios_lista .= "<li>" . $sorte_ninja['nin'] . " " . t('at.pontos_nin')."</li>";
		}

		if($sorte_ninja['gen']) {
			$premios_lista .= "<li>" . $sorte_ninja['gen'] . " " . t('at.pontos_gen')."</li>";
		}

		if($sorte_ninja['agi']) {
			$premios_lista .= "<li>" . $sorte_ninja['agi'] . " " . t('at.pontos_agi')."</li>";
		}

		if($sorte_ninja['con']) {
			$premios_lista .= "<li>" . $sorte_ninja['con'] . " " . t('at.pontos_con')."</li>";
		}

		if($sorte_ninja['ene']) {
			$premios_lista .= "<li>" . $sorte_ninja['ene'] . " " . t('at.pontos_ene')."</li>";
		}

		if($sorte_ninja['inte']) {
			$premios_lista .= "<li>" . $sorte_ninja['inte'] . " " . t('at.pontos_int')."</li>";
		}

		if($sorte_ninja['forc']) {
			$premios_lista .= "<li>" . $sorte_ninja['forc'] . " " . t('at.pontos_for')."</li>";
		}

		if($sorte_ninja['res']) {
			$premios_lista .= "<li>" . $sorte_ninja['res'] . " " . t('at.pontos_res')."</li>";
		}

		if($sorte_ninja['treino']) {
			$premios_lista .= "<li>" . $sorte_ninja['treino'] . " " . t('academia_treinamento.at37')."</li>";
		}

		if($sorte_ninja['coin']) {
			$premios_lista .= "<li>" . $sorte_ninja['coin'] . " ". t('geral.creditos')."</li>";
		}
		
		return "<ul>" . $premios_lista . "</ul>";
	}

