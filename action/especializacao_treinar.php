<?php
	$id = decode($_POST['id']);

	$redir_script = true;

	if(!is_numeric($id)) {
		redirect_to("negado", NULL, array("t" => 1));	
	}

	$rItem = Recordset::query("
		SELECT
			a.req_level,
			a.req_graduacao,
			a.coin,
			a.ordem
		FROM 
			item a
		WHERE a.id_tipo=22 AND a.id_especializacao={$basePlayer->id_especializacao} AND id=$id
	")->row_array();

	// Verifica se o item ja existe (bug das abas) --->
		if(Recordset::query("SELECT id FROM player_item WHERE id_player={$basePlayer->id} AND id_item=" . $id)->num_rows) {
			redirect_to("especializacao", NULL, array("existent" => 1));
		}
	// <---

	$reqGrad = $basePlayer->id_graduacao >= $rItem['req_graduacao'] ? true : false;
	$reqLevel = $basePlayer->level >= $rItem['req_level'] ? true : false;
	
	if($reqGrad && $reqLevel) {
		$it = new Item($id);

		if($it->COIN && $it->RYOU) { // Grana e Coin
			$p_verif = 2;		
		} elseif($it->COIN && !$it->RYOU) { // So coin
			$p_verif = 0;
		} elseif(!$it->COIN && $it->RYOU) { // So grana
			$p_verif = 1;
		}

		if(!is_numeric($p_verif)) {
			echo "alert('".t('actions.a130')."')";
			die();
		}
		
		if($_POST['pm'] == $_SESSION['pay_key_0'] && on($p_verif, array(2, 1))) { // Grana
			$pay_mode = 0;
		
			// Checagem de dinheiro --->
				if($it->RYOU > $basePlayer->RYOU) {
					redirect_to("especializacao", NULL, array("err_ryou" => 1));
					die();
				}
			// <---			
		} elseif($_POST['pm'] == $_SESSION['pay_key_1'] && on($p_verif, array(2, 0))) { // Coin
			$pay_mode = 1;
			
			// Checagem de coin --->
				if($it->COIN > $basePlayer->COIN) {
					redirect_to("vantagens");
					die();
				}
			// <---		
		} else { // Malandro
			echo "\$('#cnMensagem').html(\"" . malandro("area de coin do ninja shop") . "\")";
			die();
		}

		if($pay_mode == 1) {
			usa_coin($it->id, $it->COIN);
			
			Recordset::query("UPDATE global.user SET coin=coin - {$it->COIN} WHERE id=" . $_SESSION['usuario']['id']);
		} else {
			Recordset::query("UPDATE player SET ryou=ryou - {$it->RYOU} WHERE id=" . $basePlayer->id);
		}
	

		$maxOrdem = Recordset::query("SELECT MAX(ordem) AS mx FROM item WHERE id_tipo=22 AND id IN(SELECT id_item FROM player_item WHERE id_player={$basePlayer->id})")->row_array();
		
		for($f = (int)++$maxOrdem['mx']; $f < $rItem['ordem']; $f++) {
			$i = Recordset::query("SELECT id FROM item WHERE id_tipo=22 AND id_especializacao={$basePlayer->id_especializacao} AND ordem=" . $f)->row_array();
			
			Recordset::query("INSERT INTO player_item(id_player, id_item) VALUES(
				{$basePlayer->id}, {$i['id']}
			)");
		}
		Recordset::query("UPDATE player_item SET equipado=0 WHERE id_item IN(SELECT id FROM item WHERE id_especializacao={$basePlayer->id_especializacao} AND id_tipo=22) AND id_player={$basePlayer->id}");
		
		Recordset::query("INSERT INTO player_item(id_player, id_item, equipado) VALUES(
			{$basePlayer->id}, $id, 1
		)"); # 1
		
		// Conquista --->
			arch_parse(NG_ARCH_ITEM_N, $basePlayer, NULL, new Item($id), 1);
		// <---

		redirect_to("especializacao", "", array("ok" => "2", "h" => encode($id)));
	} else {
		redirect_to("negado", NULL, array("t" => 2));		
	}
