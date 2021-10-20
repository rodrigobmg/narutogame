<?php
	$redir_script = true;

	if ($basePlayer->id_missao == -1) {
		$serialized	= unserialize(Player::getFlag('missao_tempo_vip', $basePlayer->id));
		$exp		= 0;
		$rep		= 0;
		$ryou		= 0;
		$village	= $basePlayer->id_vila_atual;

		if (now() < strtotime($serialized['finishing_time'])) {
			redirect_to('negado', NULL, array('e' => 11));
		}

		foreach ($serialized as $key => $value) {
			if(!is_numeric($key)) {
				continue;
			}

			$quest			= Recordset::query('SELECT * FROM quest WHERE id=' . $value['quest'])->row_array();
			$quest['ryou']	+= percent($basePlayer->getAttribute('inc_ryou') + $basePlayer->bonus_vila['sk_missao_ryou'], $quest['ryou']);
			$quest['exp']	+= percent($basePlayer->bonus_vila['sk_missao_exp'], $quest['exp']);

			switch($value['duration']) {
				case 1:
					$mul = 1;

					break;
				case 2:
					$mul = 2;

					break;
				case 3:
					$mul = 4;

					break;
				case 4:
					$mul = 8;

					break;
				case 5:
					$mul = 12;

					break;
			}

			$exp	+= $quest['exp'] * $mul;
			$rep	+= $quest['reputacao'] * $mul;
			$ryou	+= $quest['ryou'] * $mul;

			Recordset::insert('player_quest', [
				'id_player'			=> $basePlayer->id,
				'id_quest'			=> $quest['id'],
				'id_vila'			=> 0, //isset($value['village']) ? $value['village'] : $basePlayer->id_vila_atual,
				'completa'			=> 1,
				'finalizada'		=> 1,
				'data_conclusao'	=> now(true),
				'multiplicador'		=> $value['duration']
			]);

			equipe_exp(round(percent(3, $exp)));

			if($quest['id_rank']) {
				$field	= ['d', 'c', 'b', 'a', 's'];
				$field	= 'quest_' . $field[$quest['id_rank'] - 1];
			} else {
				$field	= 'tarefa';
			}
			
			// Missões diárias de Tempo 
			if($basePlayer->hasMissaoDiariaPlayer(1)->total){
				// Adiciona os contadores nas missões de tempo.
				Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
							 WHERE id_player = ". $basePlayer->id." 
							 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 1) 
							 AND completo = 0 ");
			}

			Recordset::update('player_quest_status', [
				$field		=> ['escape' => false, 'value' => $field . ' + 1']
			], [
				'id_player'	=> $basePlayer->id
			]);

			$village	= isset($value['village']) ? $value['village'] : $basePlayer->id_vila_atual;
			
		}

		$basePlayer->setAttribute('exp', $basePlayer->getAttribute('exp') + $exp);
		$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') + $ryou);

		//reputacao($basePlayer->id, $village, $rep);

		$basePlayer->setAttribute('id_missao', 0);
	} else {
		if(!is_numeric($_POST['i'])) {
			redirect_to('negado', NULL, array('e' => 1));
		}

		if(isset($_POST['especial']) && $_POST['especial']) {
			if($_POST['i'] != $basePlayer->getAttribute('id_missao_especial')) {
				redirect_to('negado', NULL, array('e' => 2));
			}

			$especial = true;
		} else {
			if($_POST['i'] != $basePlayer->getAttribute('id_missao')) {
				redirect_to('negado', NULL, array('e' => 3));
			}

			$especial = false;
		}

		$quest 			= Recordset::query('SELECT * FROM quest WHERE id=' . $_POST['i'], true)->row_array();
		$player_quest	= Recordset::query('SELECT * FROM player_quest WHERE id_quest=' . $_POST['i'] . ' AND id_player=' . $basePlayer->id)->row_array();

		if(!$player_quest['completa'] && !$player_quest['finalizada']) {
			redirect_to('negado', NULL, array('e' => 4));
		}

		// Conquista --->
			arch_parse(NG_ARCH_QUEST, $basePlayer);
			arch_parse(NG_ARCH_SELF, $basePlayer);
		// <---

		// Remove os itens
		if(!$quest['interativa'] && !$quest['equipe']) {
			Recordset::delete('player_quest_npc_item', array(
				'id_player'			=> $basePlayer->id,
				'id_player_quest'	=> $quest['id']
			));
		}

		// Total de missões diárias.
		if(!$quest['equipe'] && !$quest['especial'] && $quest['id_graduacao']!=1) {
			//Recordset::query("UPDATE player_flags set missao_total_dia = missao_total_dia + 1 WHERE id_player = ". $basePlayer->id);
		}

		if($quest['equipe']) {
			equipe_exp(round(percent(5, $quest['exp'])));
		} else {
			equipe_exp(round(percent(3, $quest['exp'] * $player_quest['multiplicador'])));
		}

		if(!$especial) {
			$basePlayer->setAttribute('id_missao', 0);
		} else {
			$basePlayer->setAttribute('id_missao_especial', 0);
		}

		if(!$quest['equipe'] and !$quest['interativa'] and !$quest['especial']){
			// Missões diárias de Tempo 
			if($basePlayer->hasMissaoDiariaPlayer(1)->total){
				// Adiciona os contadores nas missões de tempo.
				Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
							 WHERE id_player = ". $basePlayer->id." 
							 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 1) 
							 AND completo = 0 ");
			}
		}

		if(!$quest['equipe'] and $quest['interativa'] and !$quest['especial']){
			// Missões diárias Interativas 
			if($basePlayer->hasMissaoDiariaPlayer(2)->total){
				// Adiciona os contadores nas missões interativas.
				Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
							 WHERE id_player = ". $basePlayer->id." 
							 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 2) 
							 AND completo = 0 ");
			}
		}
		
	}

	if ($quest['id_graduacao'] ==1) {
		redirect_to('licoes');
	} else {
		redirect_to('personagem_status');
	}
