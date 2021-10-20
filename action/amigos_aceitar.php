<?php
	header('Content-Type: application/json');

	$json			= new stdClass();
	$json->messages	= [];
	$json->success	= false;
	$errors			= [];
	
	if(!is_numeric($_POST['id'])) {
		redirect_to('negado');
	}
	
	if(!sizeof($errors)) {
		$json->success	= true;
	
		Recordset::query('INSERT INTO player_friend_lists (id_player,id_friend) VALUES ('.$basePlayer->id.', '.$_POST['id'].')');
		Recordset::query('INSERT INTO player_friend_lists (id_player,id_friend) VALUES ('.$_POST['id'].', '.$basePlayer->id.')');


		Recordset::query("DELETE FROM player_friend_requests WHERE id_player=".$_POST['id']." AND id_friend=".$basePlayer->id);
		
		// Missões diárias de Comprar Sensei
		if($basePlayer->hasMissaoDiariaPlayer(19)->total){
			// Adiciona os contadores nas missões de tempo.
			Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
						 WHERE id_player = ". $basePlayer->id." 
						 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 19) 
						 AND completo = 0 ");
		}
	} else {
		$json->messages	= $errors;
	}

	echo json_encode($json);