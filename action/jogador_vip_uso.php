<?php
	$redir_script = true;
	
	$classe_m	= decode($_POST[$_SESSION['vip_field_cclasse_m']]);
	
	$id = (int)decode($_POST[$_SESSION['vip_field_id']]);
	$postkey = $_POST[$_SESSION['vip_field_postkey']];
	

	if(!is_numeric($id)) {
		redirect_to("negado");
	}
	
	if($postkey != $_SESSION['vip_field_postkey_value']) {
		redirect_to("negado");
	}

	$item_key	= 'usage_rev1_' . $_SESSION['basePlayer'] . '_' . $id;
	$times		= array(
		1863	=> 22,
		1862	=> 22,
		1875	=> 22,
		1876	=> 7,
		1877	=> 7,
		1878	=> 7,
		20290	=> 21,
		20291	=> 21,
		22953	=> 1	
	);

	if(isset($_SESSION[$item_key])) {
		unset($_SESSION[$item_key]);
	}	

	switch($id) {
		case 1862: // Limites de level do mapa
		case 1863:
			
			$qDias = Recordset::query("
							select DATEDIFF(
								   now(),
								   date_add(valor,INTERVAL -90 DAY)      
								   ) dias 
							from flags where id = 2
			")->row_array();

			if($qDias['dias'] < 6){
				redirect_to("jogador_vip", NULL, array("e" => 99));	
			}
		case 1875:
		case 1876:
		case 1877:
		case 1878:
		
		case 20290:
		case 20291:
			if(gHasItemW($id, $basePlayer->id, NULL, $times[$id] * 24)) {
				redirect_to('negado');	
			}
		
			Recordset::query("UPDATE player_item SET data_uso=NOW() WHERE id_player=" . $basePlayer->id . " AND id_item=" . $id);
			Recordset::query("INSERT INTO log_vips (id_player, id_item ) VALUES  (". $basePlayer->id ." , ". $id .")");

			break;		
		case 22953:
			if(gHasItemW($id, $basePlayer->id, NULL, $times[$id] * 24)) {
				redirect_to('negado');	
			}
			Recordset::query("UPDATE player SET id_classe=" . (int)$classe_m . " WHERE id=" . $basePlayer->id);
			Recordset::query("INSERT INTO log_vips (id_player, id_item ) VALUES  (". $basePlayer->id ." , ". $id .")");
			Recordset::query("UPDATE player_item SET data_uso=NOW() WHERE id_player=" . $basePlayer->id . " AND id_item=" . $id);
			Recordset::query("DELETE FROM player_imagem WHERE id_player=" . $basePlayer->id);
			
			break;
		default:

			redirect_to("negado");
	}

	redirect_to("jogador_vip", NULL, array("active" => encode($id)));		
