<?php
	$redir_script = true;

	if($basePlayer->credibilidade == 0) {
		redirect_to("personagem_status");
	}

	if(isset($_GET['e']) && isset($_SERVER['HTTP_REFERER']) && preg_match("/mapa_vila/", $_SERVER['HTTP_REFERER'])) { // Sai da vila
		// Quando estou dentro do mapa da vila e vou para o mapa mundi
		// Limites de 50% (NAO VALIDO PRA QUANDO SE ESTA EM VILA INIMIGA) --->
			$dipl = Player::diplOf($basePlayer->id_vila, $basePlayer->id_vila_atual);
			
			if($dipl != 2 && $basePlayer->dentro_vila && !$basePlayer->id_arena) {
				if($basePlayer->hp < ($basePlayer->max_hp / 2)) {
					redirect_to("mapa_vila", NULL, array("e" => 1));
					//die("alert('Você está muito fraco para lutar, recupere seus atributos e tente novamente!')");
				}
			
				if($basePlayer->sp < ($basePlayer->max_sp / 2)) {
					redirect_to("mapa_vila", NULL, array("e" => 1));
					//die("alert('Você está muito fraco para lutar, recupere seus atributos e tente novamente!')");
				}
			
				if($basePlayer->sta < ($basePlayer->max_sta / 2)) {
					redirect_to("mapa_vila", NULL, array("e" => 1));
					//die("alert('Você está muito fraco para lutar, recupere seus atributos e tente novamente!')");
				}
			}
		// <---
		
		if(!$basePlayer->id_vila_atual) {
			redirect_to("mapa");
		}
	


		$vila	= Recordset::query("SELECT * FROM vila WHERE id=" . $basePlayer->getAttribute('id_vila_atual'), true)->row_array();
		$xinc	= (has_chance(50) ? 1 : -1) * 22;
		$yinc	= (has_chance(50) ? 1 : -1) * 22;

		$basePlayer->setAttribute('id_vila_atual', 0);
		
		Recordset::update('player_posicao', array(
			'xpos'	=> ($vila['xpos'] + $xinc) / 22,
			'ypos'	=> ($vila['ypos'] + $yinc) / 22
		), array(
			'id_player'	=> $basePlayer->id
		));
		
		redirect_to("mapa");
		
		die();
	} elseif(isset($_GET['e'])) {
		redirect_to("negado");
	}

	if(isset($_GET['i'])) { // Entrar na vila
		$r = Recordset::query("SELECT x,y FROM local_mapa WHERE mlocal=5 AND id_vila=" . (int)$_GET['i'])->row_array();

		Recordset::query("UPDATE player SET id_vila_atual=" . (int)$_GET['i'] . ", dentro_vila='0' WHERE id=" . $basePlayer->id);
		Recordset::update('player_posicao', array(
			'xpos'	=> $r['xpos'],
			'ypos'	=> $r['ypos']
		), array(
			'id_player'	=> $basePlayer->id
		));

		redirect_to("mapa_vila");
		die();
	}

	if(isset($_GET['v'])) { // Ir para o mapa da vila

		if($basePlayer->hp < ($basePlayer->max_hp / 2)) {
			redirect_to("personagem_status", NULL, array("e" => 1));
			//die("alert('Você está muito fraco para lutar, recupere seus atributos e tente novamente!')");
		}
	
		if($basePlayer->sp < ($basePlayer->max_sp / 2)) {
			redirect_to("personagem_status", NULL, array("e" => 1));
			//die("alert('Você está muito fraco para lutar, recupere seus atributos e tente novamente!')");
		}
	
		if($basePlayer->sta < ($basePlayer->max_sta / 2)) {
			redirect_to("personagem_status", NULL, array("e" => 1));
			//die("alert('Você está muito fraco para lutar, recupere seus atributos e tente novamente!')");
		}

		Recordset::query("UPDATE player SET dentro_vila='0' WHERE id=" . $basePlayer->id);
		
		redirect_to("mapa_vila");
		
		die();
	}
