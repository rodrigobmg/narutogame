<?php
	$id = decode($_POST['id']);
	$id_treino = 0;
	
	switch($id) {
		case 1028:
			if(!$basePlayer->hasItem(array(1028, 1081, 1082))) {
				redirect_to("negado");
			}
		
			$date = date("Y-m-d H:i:s", strtotime("+30 minutes"));
			Recordset::query("UPDATE player SET treinando='" . $date . "' WHERE id=" . $basePlayer->id);
		
			$uso = 1;
		
			break;

		case 1081:
			if(!$basePlayer->hasItem(array(1081, 1082))) {
				redirect_to("negado");
			}
		
			$date = date("Y-m-d H:i:s", strtotime("+1 hour"));
			Recordset::query("UPDATE player SET treinando='" . $date . "' WHERE id=" . $basePlayer->id);
		
			$uso = 2;
		
			break;

		case 1082:
			if(!$basePlayer->hasItem(array(1082))) {
				redirect_to("negado");
			}
		
			$date = date("Y-m-d H:i:s", strtotime("+1 hour 30 minutes"));
			Recordset::query("UPDATE player SET treinando='" . $date . "' WHERE id=" . $basePlayer->id);
		
			$uso = 3;
		
			break;
		
		default:
			redirect_to("negado");
		
			break;
	}

	Recordset::query("UPDATE player SET id_tipo_treino=" . $id_treino . ", treino_tempo=" . (int)$uso . " WHERE id=" . $basePlayer->id);
	Recordset::query("UPDATE player_item SET uso=" . (int)$uso . " WHERE id_item IN(1028, 1081, 1082) AND id_player=" . $basePlayer->id);

	redirect_to("treino_automatico");
