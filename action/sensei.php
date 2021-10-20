<?php
	header('Content-Type: application/json');

	$json			= new stdClass();
	$json->messages	= [];
	$json->success	= false;
	$errors			= [];
	$current_count	= Player::getFlag('sensei_sair_count', $basePlayer->id);
	
	if(!is_numeric($_POST['sensei'])) {
		redirect_to('negado');
	}
	$sensei = Recordset::query("SELECT * FROM sensei WHERE id =". $_POST['sensei'])->row();

	if(!$sensei){
		$errors[]	= t("tutorial.erro1");
	}

	if($sensei->vip || $sensei->coin || $sensei->ryou){
		if(!$basePlayer->sensei($sensei->id) && !$basePlayer->getAttribute('vip')){
			$errors[]	= t("tutorial.erro2");
		}
	}
	//Remove o custo de trocar de sensei
	if($current_count == 1) {
		if($basePlayer->getAttribute('ryou') < 1000) { // Sem coin (aviso: o if tem q ter um die)
			$errors[]	= t("tutorial.erro3");
		}else{

			$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') - 1000);
		}
	} elseif($current_count > 1) {
		if($basePlayer->getAttribute('coin') < 2) { // Sem coin (aviso: o if tem q ter um die)
			$errors[]	= t("tutorial.erro4");
		}else{

		// Gasta os créditos --->
			Recordset::update('global.user', array(
				'coin'	=> array('escape' => false, 'value' => 'coin-2')
			), array(
				'id'	=> $_SESSION['usuario']['id'])
			);
		
			usa_coin(1010, 2);
		// <---
		}
	}

	if(!sizeof($errors)) {
		$json->success	= true;
		
		// Adiciona o marcador
		$basePlayer->setFlag('sensei_sair_count', $current_count + 1);
		
		// Marca o Sensei na player 
		Recordset::update('player', array(
			'id_sensei'		=> $sensei->id
		), array(
			'id'	=> $basePlayer->id
		));
	} else {
		$json->messages	= $errors;
	}

	echo json_encode($json);
