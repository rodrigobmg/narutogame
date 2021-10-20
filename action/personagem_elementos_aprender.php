<?php
	$id = $_POST[$_SESSION['el_js_field_name']];

	$redir_script = true;
	
	if(!is_numeric($id)) {
		redirect_to("negado", NULL, array('e' => 1));
	}

	/*if($basePlayer->id_classe_tipo == 1) {
		redirect_to("negado", NULL, array('e' => 1.1));
	}*/
	
	$elementos			= Recordset::query('SELECT * FROM elemento ORDER BY id ASC', true);
	$player_elementos	= $basePlayer->getElementos();
	$lvl1_el			= 0;
	$lvl2_el			= 0;

	$connectors			= array();
	$connector_list		= Recordset::query('SELECT * FROM elemento_conexao', true);

	foreach($connector_list->result_array() as $connector) {
		if(!isset($connectors[$connector['id_elemento2']])) {
			$connectors[$connector['id_elemento2']]	= array();
		}

		$connectors[$connector['id_elemento2']][]	= $connector['id_elemento1'];
	}
	
	foreach($player_elementos as $player_elemento) {
		$elemento	= Recordset::query('SELECT nivel FROM elemento WHERE id=' . $player_elemento, true)->row_array();	
		
		if($elemento['nivel'] == 1) {
			$lvl1_el++;	
		} else {
			$lvl2_el++;	
		}
	}
	
	// Malandro tentando conseguir mais de 3 elementos
	if(sizeof($player_elementos) >= 3) {
		redirect_to("negado", NULL, array('e' => 2));
	}
	
	// Analisa os requerimentos --->
		$elemento = Recordset::query("
			SELECT
				a.*, 
				(SELECT nome_".Locale::get()." FROM graduacao WHERE id=a.req_graduacao_a) AS nome_req_graduacao_a,
				(SELECT nome_".Locale::get()." FROM graduacao WHERE id=a.req_graduacao_b) AS nome_req_graduacao_b
			
			FROM 
				elemento a
			WHERE a.id=" . $id);
			
		if(!$elemento->num_rows) {
			redirect_to("negado", NULL, array('e' => 2.1));
		}
		
		$elemento	= $elemento->row_array();
		$campo		= !sizeof($player_elementos) ? "a" : "b";
		
		$haslevel	= $basePlayer->getAttribute('level') >= $elemento['req_level_' . $campo] ? true : false;
		$hasgrad	= $basePlayer->getAttribute('id_graduacao') >= $elemento['req_graduacao_' . $campo] ? true : false;
		$hasel		= in_array($elemento['id'], $player_elementos) ? true : false;
		
		$reqs		= $haslevel && $hasgrad && !$hasel;
	
		if($elemento['nivel'] == 1) {
			$reqs	= $reqs && $lvl1_el < 2;	
		} else {
			$reqs	= $reqs && $lvl1_el == 2 && $lvl2_el < 1;

			foreach($connectors[$elemento['id']] as $connector) {
				$reqs	= $reqs && in_array($connector, $player_elementos);
			}
		}
		
		if(!$reqs) {
			redirect_to("negado", NULL, array('e' => 3));
		}
	// <---
	
	unset($_SESSION['el_js_field_name']);
	
	Recordset::query("INSERT INTO player_elemento(id_player, id_elemento) VALUES(" . $basePlayer->id . ", " . (int)$id . ")");

	// Regera a chave de criptografia
	$_SESSION['key'] = md5(rand(0, 512384) . rand(0, 512384));

	// Conqiusta --->
		arch_parse(NG_ARCH_SELF, $basePlayer);
	// <---

	redirect_to("personagem_elementos", null, array('c' => 1));
