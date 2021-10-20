<?php
	$redir_script = true;

	if(!isset($_POST['id']) || (isset($_POST['id']) && !is_numeric($_POST['id']))) {
		redirect_to("negado", NULL, array("e" => 1));
	}

	if($_SESSION['missoes_key'] != @$_POST['missoes_key'] || !$_SESSION['missoes_key']) {
		die("jalert('Chave inválida! A página será recarregada', null, function () {location.reload()})");
	}

	// Ja fez a miss�o
	if(Recordset::query("SELECT id FROM player_quest WHERE id_player=" . $basePlayer->id . " AND id_vila=" . $basePlayer->getAttribute('id_vila_atual') . " AND id_quest=" . (int)$_POST['id'])->num_rows) {
		redirect_to("negado", NULL, array("e" => 3));
	}
	

	$quest = Recordset::query('SELECT * FROM quest WHERE id=' . (int)$_POST['id'], true);

	// Condi��o para o caso do float bug [o typecast da query acima resolveria, mas por garantia]
	if(!$quest->num_rows) {
		redirect_to("negado", NULL, array("e" => 4));
	}

	$r = $quest->row_array();
	

	//Define limite de missões por graduação
	$limite_graduacao  = unserialize(LIMITE_MISSOES);
	$limite_graduacao =  $limite_graduacao[$basePlayer->id_graduacao];

	if($r['id_vila'] && $r['id_vila'] != $basePlayer->id_vila_atual) {
		redirect_to("negado", NULL, array("e" => 4.5));
	}


	if($r['equipe'] && $basePlayer->getAttribute('id_vila') != $basePlayer->getAttribute('id_vila_atual')) {
		redirect_to("negado", NULL, array("e" => 4.6));
	}

	if($r['level'] && $r['level'] > $basePlayer->level) {
		redirect_to("negado", NULL, array("e" => 4.7));
	}

	if($r['id_graduacao'] && $r['id_graduacao'] > $basePlayer->id_graduacao) {
		redirect_to("negado", NULL, array("e" => 4.8));
	}
	/*
	if($r['especial'] && $basePlayer->getAttribute('id_vila') != $basePlayer->getAttribute('id_vila_atual')) {
		redirect_to("negado", NULL, array("e" => 4.7));
	}
	*/

	// Esta em vila especial e est� tentando acessar uma miss�o de vila comum
	/*if(!$r['id_vila'] && between($basePlayer->getAttribute('id_vila'), 9, 13)) {
		redirect_to("negado", NULL, array("e" => 4.8));
	}*/

	// Se j� estiver em miss�o especial
	if($r['especial'] && $basePlayer->getAttribute('id_missao_especial')) {
		redirect_to("negado", NULL, array("e" => 2));
	}

	if($basePlayer->getAttribute('id_missao')) {
		$player_quest = Recordset::query('SELECT * FROM quest WHERE id=' . $basePlayer->getAttribute('id_missao'))->row_array();

		// tentar injetar um id de miss�o de tempo
		if(!$player_quest['interativa']) {
			redirect_to("negado", NULL, array("e" => 6));
		}

		// se oq eu quero fazer n�o for especial
		if(!$r['especial']) {
			redirect_to("negado", NULL, array("e" => 6.5));
		}
	}

	// Verifica o multiplicador enviado --->
		if($r['id_rank'] == 0) {
			$_POST['m'] = 1;
		}

		if((int)$r['duracao'] && !$r['equipe']) {
			if(!on($_POST['m'], array(1, 2, 3, 4, 5))) {
				redirect_to("negado", NULL, array("e" => 6));
			} else {
				$d_field = $_POST['m'] == 1 ? "": $_POST['m'];

				switch($_POST['m']) {
					case 1:
						$mul = 1;
						$injection_field = "";

						break;
					case 2:
						$mul = 2;
						$injection_field = "2";

						break;
					case 3:
						$mul = 4;
						$injection_field = "3";

						break;
					case 4:
						$mul = 8;
						$injection_field = "4";

						break;
					case 5:
						$mul = 12;
						$injection_field = "5";

						break;

					default:
						$mul = 0;
				}

				// Resolve o injection de id de multiplicador -->
					$rInjector = Recordset::query("SELECT duracao" . $injection_field . " AS time FROM quest WHERE id=" . (int)$_POST['id'], true)->row_array();

					if(!(int)$rInjector['time']) {
						redirect_to('negado', NULL, array('e' => 7));
					}
				// <--

				if($basePlayer->getAttribute('level') >= 15) {
					$fall = hasFall($basePlayer->getAttribute('id_vila'), 4);
				} else {
					$fall = false;
				}

				$hour	= substr($r['duracao' . $d_field], 0, 2) * ($fall ? 2 : 1);
				$minute	= substr($r['duracao' . $d_field], 2, 2)  * ($fall ? 2 : 1);
				$secs	= substr($r['duracao' . $d_field], 4, 2) * ($fall ? 2 : 1);
			}
		} else {
			$mul = 1;
		}
	// <---

	// Miss�o em equipe -->
		// Miss�o de equipe sem ter equipe
		if($r['equipe'] && !$basePlayer->getAttribute('id_equipe')) {
			redirect_to('negado', NULL, array('e' => 8));
		} elseif($r['equipe'] && $basePlayer->getAttribute('id_equipe')) {
			$membros	= Recordset::query('SELECT id FROM player WHERE id_equipe=' . $basePlayer->getAttribute('id_equipe'));
			$equipe		= Recordset::query('SELECT * FROM equipe WHERE id=' . $basePlayer->getAttribute('id_equipe'))->row_array();

			if($equipe['membros'] < 4) {
				redirect_to('negado', NULL, array('e' => 9));
			}
		}
	// <--

	if($r['interativa']) {
		$qNPC 		= Recordset::query("SELECT * FROM quest_npc_item WHERE id_quest=" . (int)$_POST['id'], true);
		$conclusao	= date("Y-m-d H:i:s", strtotime("+24 hours"));

		if($r['equipe']) {
			if($qNPC->num_rows != 4) {
				echo "alert('".t('actions.a229')."')";
				die();
			}

			foreach($qNPC->result_array() as $rNPC) {
				$player = $membros->row_array();

				Recordset::insert('player_quest', array(
					'id_player'			=> $player['id'],
					'id_equipe'			=> $basePlayer->id_equipe,
					'id_vila'			=> $r['id_vila'] ? $basePlayer->getAttribute('id_vila_atual') : 0,
					'id_quest'			=> $_POST['id'],
					'data_ins'			=> date('Y-m-d H:i:s'),
					'data_conclusao'	=> $conclusao,
					'user_ua'			=> $_SERVER['HTTP_USER_AGENT']
				));

				Recordset::insert('equipe_quest_npc', array(
					'id_player'			=> $player['id'],
					'id_equipe'			=> $basePlayer->getAttribute('id_equipe'),
					'id_player_quest'	=> $_POST['id'],
					'id_npc'			=> $rNPC['id_npc']
				));
			}

      Recordset::update('player', array(
				'id_missao'	=> $_POST['id']
			), array(
				'id_equipe'	=> $basePlayer->getAttribute('id_equipe')
			));
		} else {
			$id_player_quest = Recordset::insert('player_quest', array(
				'id_player'			=> $basePlayer->id,
				'id_vila'			=> $r['id_vila'] ? $basePlayer->getAttribute('id_vila_atual') : 0,
				'id_quest'			=> $_POST['id'],
				'data_ins'			=> date('Y-m-d H:i:s'),
				'multiplicador'		=> $mul,
				'data_conclusao'	=> $conclusao,
				'user_ua'			=> $_SERVER['HTTP_USER_AGENT']
			));

			foreach($qNPC->result_array() as $rNPC) {
				Recordset::insert('player_quest_npc_item', array(
					'id_player'			=> $basePlayer->id,
					'id_player_quest'	=> $_POST['id'],
					'id_npc'			=> $rNPC['id_npc'],
					'id_item'			=> $rNPC['id_item']
				));
			}

		}
	} else {
		$time		= strtotime("+{$hour} hours +{$minute} minutes +{$secs} seconds");

		if($r['id_rank'] && $basePlayer->bonus_vila['sk_missao_tempo']) { // reduz o tempo da miss�o exceto para tarefas
			$time	= strtotime('-' . $basePlayer->bonus_vila['sk_missao_tempo'] . ' minutes', $time);
		}

		$conclusao = date("Y-m-d H:i:s", $time);

		$id_player_quest = Recordset::insert('player_quest', array(
			'id_player'				=> $basePlayer->id,
			'id_quest'				=> $_POST['id'],
			'id_vila'				=> $r['id_vila'] ? $basePlayer->getAttribute('id_vila_atual') : 0,
			'data_ins'				=> date('Y-m-d H:i:s'),
			'data_conclusao'		=> $conclusao,
			'multiplicador'			=> $mul,
			'user_ua'				=> $_SERVER['HTTP_USER_AGENT']
		));
	}

	if(!$r['especial']) {
		$basePlayer->setAttribute('id_missao', $_POST['id']);
	} else {
		$basePlayer->setAttribute('id_missao_especial', $_POST['id']);
	}

	if(!$r['interativa']) {
		echo 'location.href=\'?secao=missoes_espera\'';
	} else {
		if($r['especial']) {
			echo 'location.href=\'?secao=missoes_status&especial\'';
		} else {
			echo 'location.href=\'?secao=missoes_status\'';
		}
	}
