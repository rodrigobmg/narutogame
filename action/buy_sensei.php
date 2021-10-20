<?php
	header('Content-Type: application/json');

	$json			= new stdClass();
	$json->messages	= [];
	$json->success	= false;
	$errors			= [];
	
	if(!is_numeric($_POST['sensei'])) {
		redirect_to('negado');
	}
	
	$sensei = Recordset::query("SELECT * FROM sensei WHERE id =". $_POST['sensei'])->row();

	if(!$sensei) {
		$errors[]	= "O Sensei que você tentou ativar não existe!";
	}

	if(!$basePlayer->sensei($sensei->id)) {
		if($sensei->coin) {
			if($basePlayer->getAttribute('coin') < $sensei->coin) {
				$errors[]	= "Você não tem créditos suficientes para essa compra.";
			}
		}

		if($sensei->ryou) {
			if($basePlayer->getAttribute('ryou') < $sensei->ryou) {
				$errors[]	= "Você não tem Ryous suficientes para essa compra.";
			}
		}
	}else{
		$errors[]	= "Você já tem esse Sensei Liberado.";
	}

	if(!sizeof($errors)) {
		$json->success	= true;
		
		// Gasta os créditos
		if($sensei->coin) {
			gasta_coin($sensei->coin,3333);
		}	
		
		// Gasta os ryous
		if($sensei->ryou) {
			Recordset::update('player', array(
				'ryou'	=> array('escape' => false, 'value' => 'ryou - ' . $sensei->ryou)
			), array(
				'id'	=> $basePlayer->id
			));
		}	

		// Marca o Sensei na player 
		Recordset::insert('player_sensei', [
			'id_usuario'	=> $_SESSION['usuario']['id'],
			'id_sensei'		=> $sensei->id,
			'data_completo'	=> now(true)
		]);

		// Missões diárias de Comprar Sensei
		if($basePlayer->hasMissaoDiariaPlayer(18)->total) {
			// Adiciona os contadores nas missões de tempo.
			Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
						 WHERE id_player = ". $basePlayer->id." 
						 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 18) 
						 AND completo = 0 ");
		}
		
	} else {
		$json->messages	= $errors;
	}

	echo json_encode($json);
