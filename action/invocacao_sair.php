<?php
	$redir_script	= true;
	$current_count	= Player::getFlag('invocacao_sair_count', $basePlayer->id);

	if($_POST['pm'] != $_SESSION['pay_key_1']) {
		redirect_to('negado');
	}

	if($basePlayer->getAttribute('sennin')) {
		if($basePlayer->getAttribute('sennin') != 5){
			redirect_to('negado');
		}
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
		
			usa_coin(1047, 2);
		// <---
	}
	
	// Remove a invocação	
	$basePlayer->setAttribute('id_invocacao', 0);
	$basePlayer->id_invocacao	= 0;
	
	$basePlayer->setFlag('invocacao_sair_count', $current_count + 1);

	Recordset::delete('player_modificadores', [
		'id_tipo'	=> 21,
		'id_player'	=> $basePlayer->id
	]);

	player_at_check();
	
	redirect_to('invocacao');
