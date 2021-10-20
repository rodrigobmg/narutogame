<?php
	$redir_script	= true;
	$current_count	= Player::getFlag('portao_sair_count', $basePlayer->id);

	if($_POST['pm'] != $_SESSION['pay_key_1']) {
		redirect_to("negado");
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

		// Gasta os cr�ditos --->
			Recordset::update('global.user', array(
				'coin'	=> array('escape' => false, 'value' => 'coin-2')
			), array(
				'id'	=> $_SESSION['usuario']['id'])
			);
		
			usa_coin(1011, 2);
		// <---
	}

	Recordset::update('player_item', array(
		'removido'	=> '1',
		'ativo'		=> '0'
	), array(
		'id_player'		=> $basePlayer->id,
		'id_item_tipo'	=> 17
	));

	$basePlayer->setFlag('portao_sair_count', $current_count + 1);
	
	player_at_check();

	redirect_to('portoes');
