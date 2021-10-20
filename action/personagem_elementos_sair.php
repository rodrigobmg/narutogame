<?php
	$redir_script	= true;
	$current_count	= Player::getFlag('elemento_sair_count', $basePlayer->id);
	$id				= $_POST['id'];

	if(!is_numeric($id)) {
		redirect_to("negado", NULL, array('e' => 1));
	}

	if($_POST['pm'] != $_SESSION['pay_key_1']) {
		redirect_to("negado", NULL, array('e' => 2));
	}
	
	$player_elementos	= $basePlayer->getElementos();
	$lvl1_el			= 0;
	$lvl2_el			= 0;

	foreach($player_elementos as $player_elemento) {
		$elemento	= Recordset::query('SELECT nivel FROM elemento WHERE id=' . $player_elemento, true)->row_array();	
		
		if($elemento['nivel'] == 1) {
			$lvl1_el++;	
		} else {
			$lvl2_el++;	
		}
	}

	$elemento = Recordset::query("
		SELECT
			a.*, 
			(SELECT nome_".Locale::get()." FROM graduacao WHERE id=a.req_graduacao_a) AS nome_req_graduacao_a,
			(SELECT nome_".Locale::get()." FROM graduacao WHERE id=a.req_graduacao_b) AS nome_req_graduacao_b
		
		FROM 
			elemento a
		WHERE a.id=" . $id);
		
	if(!$elemento->num_rows) {
		redirect_to("negado", NULL, array('e' => 3));
	}
	
	$elemento	= $elemento->row_array();
	
	if($elemento['nivel'] == 1 && $lvl2_el > 0) {
		redirect_to("negado", NULL, array('e' => 4));		
	}

	if($current_count == 1) {
		if($basePlayer->getAttribute('ryou') < 1000) {
			redirect_to('vantagens');
		}

		$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') - 1000);
	} elseif($current_count > 1) {
		if($basePlayer->getAttribute('coin') < 2) { // Sem coin (aviso: o if tem q ter um die)
			redirect_to("vantagens");
		}

		// Gasta os créditos --->
			Recordset::update('global.user', array(
				'coin'	=> array('escape' => false, 'value' => 'coin-2')
			), array(
				'id'	=> $_SESSION['usuario']['id'])
			);
		
			usa_coin(1012, 2);
		// <---
	}
	
	Recordset::delete('player_elemento', array(
		'id_player'		=> $basePlayer->id,
		'id_elemento'	=> $id
	));

	// Remove os itens que dependem de elemento --->
		$fields = array('id_elemento', 'id_elemento2');
		
		foreach($fields as $field) {
			$items = Recordset::query('SELECT id FROM item WHERE ' . $field . '=' . $id, true);
			
			foreach($items->result_array() as $item) {
				if($basePlayer->hasItem($item['id'])) {
					$basePlayer->getitem($item['id'])->setAttribute('removido', '1');
				}
			}
		}
	// <---
	
	$basePlayer->setFlag('elemento_sair_count', $current_count + 1);
	player_at_check();

	redirect_to('personagem_elementos', null, array('s' => 1));
