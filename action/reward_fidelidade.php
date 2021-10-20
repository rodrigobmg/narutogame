<?php 
	header('Content-Type: application/json');

	$json			= new stdClass();
	$json->messages	= [];
	$json->success	= false;
	$errors			= [];
	
	if(!is_numeric($_POST['day'])) {
		redirect_to('negado');
	}
	
	$player_fidelity 	= Recordset::query("SELECT * FROM player_fidelity WHERE id_player=".$basePlayer->id)->row();
	
	if($player_fidelity->day != $_POST['day']){
		$errors[]	= t('fidelity.errors.day');
	}
	
	if($player_fidelity->reward){
		$errors[]	= t('fidelity.errors.reward');
	}

	if(!sizeof($errors)) {
		$json->success	= true;
		
		// Marca na tabela que o player ganhou uma recompensa 
		Recordset::update('player_fidelity', array(
			'reward'		=> 1,
			'reward_at'		=> now(true)
		), array(
			'id_player'	=> $basePlayer->id
		));
		
		switch($player_fidelity->day){
			case 1:
				Recordset::query("UPDATE player SET ryou=ryou + 100 WHERE id=" . $basePlayer->id);
				Recordset::query("UPDATE player_flags SET fidelidade_points=fidelidade_points+1 WHERE id_player=" . $basePlayer->id);
			break;
			case 2:
				Recordset::query("UPDATE player SET ryou=ryou + 200 WHERE id=" . $basePlayer->id);
				Recordset::query("UPDATE player_flags SET fidelidade_points=fidelidade_points+1 WHERE id_player=" . $basePlayer->id);
			break;
			case 3:
				Recordset::query("UPDATE player SET ryou=ryou + 300 WHERE id=" . $basePlayer->id);
				Recordset::query("UPDATE player_flags SET fidelidade_points=fidelidade_points+1 WHERE id_player=" . $basePlayer->id);

			break;
			case 4:
				Recordset::query("UPDATE player SET ryou=ryou + 400 WHERE id=" . $basePlayer->id);
				Recordset::query("UPDATE player_flags SET fidelidade_points=fidelidade_points+1 WHERE id_player=" . $basePlayer->id);
			break;
			case 5:
				Recordset::query("UPDATE player SET ryou=ryou + 500 WHERE id=" . $basePlayer->id);
				Recordset::query("UPDATE player_flags SET fidelidade_points=fidelidade_points+1 WHERE id_player=" . $basePlayer->id);

			break;
			case 6:
				if($basePlayer->hasItem(759)) {
				Recordset::query("UPDATE player_item SET qtd=qtd + 5 WHERE id_player={$basePlayer->id} AND id_item=759");
				} else {		
					Recordset::query("INSERT INTO player_item(id_item, id_player, qtd) VALUES(
						759, {$basePlayer->id}, 5
					)");
				}
				Recordset::query("UPDATE player_flags SET fidelidade_points=fidelidade_points+1 WHERE id_player=" . $basePlayer->id);
			break;
			case 7:
				/*Recordset::query("UPDATE player_flags SET sorte_bijuu=sorte_bijuu + 5 WHERE id_player=" . $basePlayer->id);*/
				Recordset::query("UPDATE player SET ryou=ryou + 1000 WHERE id=" . $basePlayer->id);
				Recordset::query("UPDATE player_flags SET fidelidade_points=fidelidade_points+2 WHERE id_player=" . $basePlayer->id);
			break;
			case 8:
				//Verifica se é o prêmio de crédito.
				/*$user_stats = Recordset::query("SELECT credits FROM global.user_ref_given WHERE id_user=".$basePlayer->id_usuario)->row();

				if(!$user_stats){
					Recordset::query("UPDATE global.user SET coin=coin + 1 WHERE id=" . $basePlayer->id_usuario);
					Recordset::query("INSERT INTO global.user_ref_given(id_user,credits) VALUES(" . $basePlayer->id_usuario . ", '". now(true) ."')");
					
				}else{
					if(!$user_stats->credits || strtotime(date('Y-m-d H:i:s')) >= strtotime($user_stats->credits . "+7 days")){
						Recordset::query("UPDATE global.user SET coin=coin + 1 WHERE id=" . $basePlayer->id_usuario);
						Recordset::query("UPDATE global.user_ref_given SET credits='". now(true) ."' WHERE id_user=" . $basePlayer->id_usuario);
					}
				}*/
				Recordset::query("UPDATE player SET ryou=ryou + 1500 WHERE id=" . $basePlayer->id);
				Recordset::query("UPDATE player_flags SET fidelidade_points=fidelidade_points+3 WHERE id_player=" . $basePlayer->id);
				
			break;
		}
	} else {
		$json->messages	= $errors;
	}

	echo json_encode($json);
