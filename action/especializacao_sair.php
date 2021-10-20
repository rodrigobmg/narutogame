<?
	$redir_script = true;

	if($_POST['pm'] != $_SESSION['pay_key_1']) {
		redirect_to("negado", NULL, array("nc" => 1));
	}
	
	if($basePlayer->COIN < 2) { // Sem coin (aviso: o if tem q ter um die)
		redirect_to("vantagens");
	}
	
	//Recordset::query("DELETE FROM player_item WHERE id_item IN(SELECT id FROM item WHERE id_especializacao=" . $basePlayer->id_especializacao . ") AND id_player=" . $basePlayer->id);
	Recordset::query("UPDATE player SET id_especializacao=NULL WHERE id=" . $basePlayer->id);
	
	usa_coin(1456, 2);
	Recordset::query("UPDATE global.user SET coin=coin-2 WHERE id=" . $_SESSION['usuario']['id']);

	// Verificador de itens --->
		player_at_check();
	// <---

	redirect_to("especializacao");
