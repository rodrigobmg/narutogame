<?php
	$redir_script	= true;
	$current_count	= Player::getFlag('cla_sair_count', $basePlayer->id);

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
		
			usa_coin(1010, 2);
		// <---
	}

	$basePlayer->setAttribute('id_cla', 0);
	$basePlayer->id_cla	= 0;
	
	$basePlayer->setFlag('cla_sair_count', $current_count + 1);

	Recordset::delete('player_modificadores', [
		'id_tipo'	=> 16,
		'id_player'	=> $basePlayer->id
	]);

	player_at_check();

	redirect_to('clas');
