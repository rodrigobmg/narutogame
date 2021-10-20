<?php
	$redir_script	= true;
	$current_count	= Player::getFlag('selo_sair_count', $basePlayer->id);

	if($_POST['pm'] != $_SESSION['pay_key_1']) {
		redirect_to("negado");
	}

	if($current_count == 1) {
		if($basePlayer->getAttribute('ryou') < 1000) { // Sem coin (aviso: o if tem q ter um die)
			redirect_to("vantagens");
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
		
			usa_coin(1046, 2);
		// <---
	}

	// Remove o selo
	$basePlayer->setAttribute('id_selo', 0);
	$basePlayer->id_selo	= 0;

	$basePlayer->setFlag('selo_sair_count', $current_count + 1);
	
	player_at_check();
	
	redirect_to('selo');
