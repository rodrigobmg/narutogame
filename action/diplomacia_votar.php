<?php
	if($_POST['key'] != $_SESSION['dipl_key']) {
		redirect_to("negado", NULL, array("e" => 1));
	}

	/*if($basePlayer->vila_ranking > 25) {
		redirect_to("negado", NULL, array("e" => 2));
	}*/

	if($basePlayer->id_graduacao < 3) {
		redirect_to("negado", NULL, array("e" => 2));
	}
	
	if(date('Hi') == '2359') {
		redirect_to("negado", NULL, array("e" => 3));
	}

	$_POST['dipl'] = decode($_POST['dipl']) - 1;
	$_POST['vila'] = decode($_POST['vila']);
	
	$q = Recordset::query("SELECT * FROM diplomacia_voto WHERE id_vila=" . $basePlayer->id_vila . " AND id_vilab=" . addslashes($_POST['vila']) . " AND id_usuario=" . $_SESSION['usuario']['id']);
	
	if(!$q->num_rows) {
		Recordset::query("INSERT INTO diplomacia_voto(id_player, id_vila, id_vilab, dipl, posicao, id_usuario) VALUES(
			{$basePlayer->id}, {$basePlayer->id_vila}, {$_POST['vila']}, {$_POST['dipl']}, " . (int)$basePlayer->vila_ranking . ", {$_SESSION['usuario']['id']}
		)");
	}
		
	/*
	$qDipl = Recordset::query("
		SELECT id, dipl FROM diplomacia_voto WHERE id_vila={$basePlayer->id_vila} AND id_vilab={$_POST['vila']}
		UNION
		SELECT id, dipl FROM diplomacia_voto WHERE id_vilab={$basePlayer->id_vila} AND id_vila={$_POST['vila']}
	");

	if($qDipl->num_rows == 2) { // Votação terminada
		$arResults = array();
	
		while($rDipl = $qDipl->row_array()) {
			$arResults[$rDipl['dipl']][] = array($rDipl['dipl']);
		}
		
		$final = max($arResults);
		
		Recordset::query("INSERT INTO diplomacia(id_vila, id_vilab, dipl) VALUES({$basePlayer->id_vila}, {$_POST['vila']}, " . $final[0][0] . ")");
		Recordset::query("INSERT INTO diplomacia(id_vilab, id_vila, dipl) VALUES({$basePlayer->id_vila}, {$_POST['vila']}, " . $final[0][0] . ")");
	}
	*/
	
	$_SESSION['dipl_key'] = md5(rand(1,512384));
	
	redirect_to("diplomacia");
