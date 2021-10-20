<?php
	$redir_script	= true;
	$t				= $_POST['t'];
	$postkey		= $_POST[$_SESSION['jut_field_postkey']];
	
	if(!$basePlayer->hasItem($_POST['id'])) {
		
		redirect_to('negado', NULL, array('e' => 1));
	}

	if($postkey != $_SESSION['jut_field_postkey_value']) {
		redirect_to('negado', NULL, array('e' => 2));
	}
	
	if(Player::getFlag('treino_jutsu_exp_dia', $basePlayer->id) >= $basePlayer->getAttribute('max_treino_jutsu')) {
		redirect_to('treino_jutsu');
	}

	if($t < 1 || $t > 3) {
		redirect_to('negado', NULL, array('e' => 3));
			die();
	} elseif ($t == 2) {
		$h = '1.00';
		if(!$basePlayer->hasItem(1860) && !$basePlayer->hasItem(1861)) {
			redirect_to('negado', NULL, array('e' => 4));
			die();
		}
	} elseif ($t == 3) {
		$h = '1.30';
		if(!$basePlayer->hasItem(array(1861))) {
			redirect_to('negado', NULL, array('e' => 5));
			die();
		}		
	} else {
		$h = '00.30';
	}
	
	Recordset::query("UPDATE player SET id_tipo_treino_jutsu=$t, treino_tempo_jutsu=DATE_ADD(NOW(), INTERVAL $h HOUR_MINUTE ), id_jutsu_treino={$_POST['id']} WHERE id=" . $basePlayer->id);
	redirect_to('personagem_jutsu_treino');
