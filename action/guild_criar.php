<?php
	header('Content-type: text/javascript; charset=UTF-8');
	
	$redir_script = true;

	if($basePlayer->getAttribute('id_guild')) {
		redirect_to("negado");	
	}

	// Verifica se a guild ja existe e padrão dos nomes --->
		if(Recordset::query("SELECT id FROM guild WHERE nome='" . addslashes($_POST['nome']) . "'")->num_rows) {
			die("jalert('".t('actions.a168')."')");
		}
		
		if(!preg_match("/^[a-zA-Z\d]+([\s]{1}|)[a-zA-Z\d]+$/i", $_POST['nome'])) {
			die('jalert("'.t('actions.a169').'")');
		}

		if(strlen($_POST['nome']) > 25 || strlen($_POST['nome']) < 3) {
			die('jalert("'.t('actions.a170').'")');
		}		
	// <---

	$coin = false;

	if($_POST[$_SESSION['cl_js_field_name']] == $_SESSION['pay_key_0']) { // Grana
		// Sem grana ou graduação
		if($basePlayer->getAttribute('id_graduacao') < ($basePlayer->bonus_vila['mo_guild_grad'] ? 2 : 3) || $basePlayer->getAttribute('ryou') < 15000) {
			die('jalert("'.t('actions.a171').'")');
		}
	} elseif($_POST[$_SESSION['cl_js_field_name']] == $_SESSION['pay_key_1']) { // Coin
		if($basePlayer->getAttribute('coin') < 3) {
			redirect_to("vantagens");
		} else {
			$coin = true;
			
			// Bloqueio de level
			if($basePlayer->getAttribute('level') < 15) {
				die('jalert("'.t('actions.a171').'")');
			}
			
			usa_coin(1013, 3);
			Recordset::query("UPDATE global.user SET coin=coin-3 WHERE id=" . $_SESSION['usuario']['id']);	
		}
	} else {
		redirect_to("negado");
	}
	
	$_POST['nome'] = preg_replace('/[^\w\s]/is', '', $_POST['nome']);

	$id_guild = Recordset::insert('guild', array(
		'id_player'	=> $basePlayer->id,
		'nome'		=> htmlspecialchars($_POST['nome'])
	));

	if(!$coin) {
		$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') - 15000);
	}

	$basePlayer->setAttribute('id_guild', $id_guild);

	Recordset::insert('guild_esquadrao', [
		'id_guild'	=> $id_guild,
		'id_player'	=> $basePlayer->id,
		'posicao'	=> 1,
		'esquadrao'	=> 1
	]);

	// Regera a chave de criptografia
	$_SESSION['key'] = md5(rand(0, 512384) . rand(0, 512384));

	$redir_script = true;
	redirect_to("guild_detalhe");
