<?php
	$redir_script = true;

	if($basePlayer->getAttribute('id_equipe')) {
		redirect_to("negado");	
	}

	// Verifica consistencia do nome da equipe --->
		if(Recordset::query("SELECT id FROM equipe WHERE nome='" . addslashes($_POST['nome']) . "'")->num_rows) {
			die('jalert("'.t('actions.a122').'");');
		}

		if(!preg_match("/^[a-zA-Z\d]+([\s]{1}|)[a-zA-Z\d]+$/i", $_POST['nome'])) {
			die('jalert("'.t('actions.a123').'")');
		}
		
		if(strlen($_POST['nome']) > 25 || strlen($_POST['nome']) < 3) {
			die('jalert("'.t('actions.a124').'")');
		}
	// <---

	$coin	= false;
	$vip	= 0;

	if($_POST[$_SESSION['cl_js_field_name']] == $_SESSION['pay_key_0']) { // Grana
		// Sem grana ou graduação
		if($basePlayer->getAttribute('id_graduacao') < 3 || $basePlayer->getAttribute('ryou') < 2000) {
			redirect_to("equipe_criar", NULL, array("e" => 1));	
		}
	} elseif($_POST[$_SESSION['cl_js_field_name']] == $_SESSION['pay_key_1']) { // Coin
		// Sem coin
		if($basePlayer->getAttribute('coin') < 3) {
			redirect_to("vantagens");
		} else {
			$coin	= true;
			$vip	= 1;
			
			usa_coin(1404, 3);

			Recordset::update('global.user', array(
				'coin'	=> array('escape' => false, 'value' => 'coin-3')
			), array(
				'id'	=> $_SESSION['usuario']['id']
			));	
		}
	} else {
		redirect_to("negado");
	}

	$id_equipe = Recordset::insert('equipe', array(
		'nome'		=> $_POST['nome'],
		'vip'		=> $vip,
		'id_player'	=> $basePlayer->id
	));

	if(!$coin) {
		$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') - 2000);
	}

	$basePlayer->setAttribute('id_equipe', $id_equipe);

	redirect_to("equipe_detalhe");
	
?>	