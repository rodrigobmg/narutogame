<?php
	require('_config.php');

	$playersBanidos = Recordset::query("SELECT id_player FROM player_flags WHERE wo_looses >= 5");
	$round = Recordset::query("select valor from flags where nome='round'")->result_array();
	
	while($playerBanido = $playersBanidos->row_array()) {
		$playersPunicoes = Recordset::query("select * from player_banido where id_player = ".$playerBanido['id_player']." and tipo = 'PuniçãoPVP' order by id desc");
		$contador_ban = 1;

		while($playerPunicao = $playersPunicoes->row_array()) {	
			// O elemento já tem uma punição ativa, então deixa quieto esse puto.
			if($playerPunicao['liberado'] == 0){
				$contador_ban = 0;
				break;
			}

			$contador_ban = $contador_ban + 1;
		}

		// Saberei qts vezes o elemento já tomou ban e vou decidir o que vou fazer com ele.
		if($contador_ban){
			if($contador_ban == 1){
				Recordset::query("
					insert into player_banido 
					(id_player, tipo, motivo, round, data_banido, data_liberado)
					VALUES
					(".$playerBanido['id_player'].", 'PuniçãoPVP', 'Você foi punido por 3 horas sem PVP por inativar repetidamente e não pode duelar PVP.',".$round[0]['valor'].", now(), DATE_ADD(NOW(), INTERVAL 3 HOUR))
				");
				
			} elseif ($contador_ban == 2){
				Recordset::query("
					insert into player_banido 
					(id_player, tipo, motivo, round, data_banido, data_liberado)
					VALUES
					(".$playerBanido['id_player'].", 'PuniçãoPVP', 'Você foi punido por 6 horas sem PVP por inativar repetidamente e não pode duelar PVP.',".$round[0]['valor'].", now(), DATE_ADD(NOW(), INTERVAL 6 HOUR))
				");
				
			} elseif ($contador_ban == 3){
				Recordset::query("
					insert into player_banido 
					(id_player, tipo, motivo, round, data_banido, data_liberado)
					VALUES
					(".$playerBanido['id_player'].", 'PuniçãoPVP', 'Você foi punido por 24 horas sem PVP por inativar repetidamente e não pode duelar PVP.',".$round[0]['valor'].", now(), DATE_ADD(NOW(), INTERVAL 24 HOUR))
				");
				
			} elseif ($contador_ban == 4){
				Recordset::query("
					insert into player_banido 
					(id_player, tipo, motivo, round, data_banido, data_liberado)
					VALUES
					(".$playerBanido['id_player'].", 'PuniçãoPVP', 'Você foi punido por 3 dias sem PVP por inativar repetidamente e não pode duelar PVP.',".$round[0]['valor'].", now(), DATE_ADD(NOW(), INTERVAL 3 DAY))
				");
			} else {
				Recordset::query("
								insert into player_banido 
								(id_player, tipo, motivo, round, data_banido, data_liberado)
								VALUES
								(".$playerBanido['id_player'].", 'PuniçãoPVP', 'Você foi punido por 7 dias sem PVP por inativar repetidamente e não pode duelar PVP.',".$round[0]['valor'].", now(), DATE_ADD(NOW(), INTERVAL 7 DAY))
							");
			}

			// Ajusta a credibilidade do jogador
			Recordset::query("UPDATE player SET credibilidade = 0 WHERE id=". $playerBanido['id_player']);
		}
	}
