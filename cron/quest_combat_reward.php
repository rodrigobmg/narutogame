<?php
	date_default_timezone_set('America/Sao_Paulo');
	require('_config.php');
	
	if((date('d') == 6) || (date('d') == 13) || (date('d') == 20) || (date('d') == 27)) {
		// Pega a missão de combate ativa no momente - diaria
		$quest_combat_semanal = Recordset::query("SELECT * FROM player_quest_combat WHERE periodo='semanal' AND finished = 0")->result_array();
		if($quest_combat_semanal){
			// Trás as informações sobre a missão ativa
			$quest_combat 		 = Recordset::query("SELECT * FROM quest_combat WHERE id=".$quest_combat_semanal[0]['id_quest_combat'])->result_array();
			// Trás informações do jogador melhor colocado em relação a missão ativa
			$player_rank 		 = Recordset::query("SELECT * FROM player_batalhas_status ORDER BY ".$quest_combat[0]['tipo']." DESC LIMIT 1")->result_array();
			// Faz a bonificação do jogador!
			Recordset::query("UPDATE global.user SET coin = coin + ".$quest_combat[0]['credits']." WHERE id=".$player_rank[0]['id_usuario']);
			Recordset::query("UPDATE player SET ryou = ryou + ".$quest_combat[0]['ryou']." WHERE id=".$player_rank[0]['id_player']);
			// Marca a missão como completa
			Recordset::query("UPDATE player_quest_combat SET id_player = ". $player_rank[0]['id_player'] . ", finished = 1 WHERE id=". $quest_combat_semanal[0]['id']);
			// Marca a missão na plauqer quest status
			Recordset::query("UPDATE player_quest_status SET quest_combate_semanal = quest_combate_semanal +1 WHERE id_player=". $player_rank[0]['id_player']);

		}
	}elseif ((date('d') == 1)){
		// Pega a missão de combate ativa no momente - diaria
		$quest_combat_mensal = Recordset::query("SELECT * FROM player_quest_combat WHERE periodo='mensal' AND finished = 0")->result_array();
		if($quest_combat_mensal){
			// Trás as informações sobre a missão ativa
			$quest_combat 		 = Recordset::query("SELECT * FROM quest_combat WHERE id=".$quest_combat_mensal[0]['id_quest_combat'])->result_array();
			// Trás informações do jogador melhor colocado em relação a missão ativa
			$player_rank 		 = Recordset::query("SELECT * FROM player_batalhas_status ORDER BY ".$quest_combat[0]['tipo']." DESC LIMIT 1")->result_array();
			// Faz a bonificação do jogador!
			Recordset::query("UPDATE global.user SET coin = coin + ".$quest_combat[0]['credits']." WHERE id=".$player_rank[0]['id_usuario']);
			// Marca a missão como completa
			Recordset::query("UPDATE player_quest_combat SET id_player = ". $player_rank[0]['id_player'] . ", finished = 1 WHERE id=". $quest_combat_mensal[0]['id']);
			Recordset::query("UPDATE player_quest_status SET quest_combate_mensal = quest_combate_mensal +1 WHERE id_player=". $player_rank[0]['id_player']);

		}
	}
		// Pega a missão de combate ativa no momente - diaria
		$quest_combat_diario = Recordset::query("SELECT * FROM player_quest_combat WHERE periodo='diario' AND finished = 0")->result_array();
		if($quest_combat_diario){
			// Trás as informações sobre a missão ativa
			$quest_combat 		 = Recordset::query("SELECT * FROM quest_combat WHERE id=".$quest_combat_diario[0]['id_quest_combat'])->result_array();
			// Trás informações do jogador melhor colocado em relação a missão ativa
			$player_rank 		 = Recordset::query("SELECT * FROM player_batalhas_status ORDER BY ".$quest_combat[0]['tipo']." DESC LIMIT 1")->result_array();
			// Faz a bonificação do jogador!
			Recordset::query("UPDATE player SET ryou = ryou + ".$quest_combat[0]['ryou']." WHERE id=".$player_rank[0]['id_player']);
			// Marca a missão como completa
			Recordset::query("UPDATE player_quest_combat SET id_player = ". $player_rank[0]['id_player'] . ", finished = 1 WHERE id=". $quest_combat_diario[0]['id']);
			Recordset::query("UPDATE player_quest_status SET quest_combate_diario = quest_combate_diario +1 WHERE id_player=". $player_rank[0]['id_player']);

		}
