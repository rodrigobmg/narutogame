<?php
	$finalize			= true;
	$today				= date('N');

	if(isset($_POST['guild']) && $_POST['guild'] && $basePlayer->getAttribute('id_missao_guild2')) {
		if(!$basePlayer->getAttribute('dono_guild')) {
			redirect_to('negado', NULL, array('e' => 1));
		}

		$quest	= Recordset::query('SELECT * FROM quest_guild WHERE id=' . $basePlayer->getAttribute('id_missao_guild2'), true)->row_array();
	} else {
		if(!$basePlayer->getAttribute('id_missao_guild')) {
			redirect_to('negado', NULL, array('e' => 2));
		}

		$quest	= Recordset::query('SELECT * FROM quest_guild WHERE id=' . $basePlayer->getAttribute('id_missao_guild'), true)->row_array();
	}

	$quest_items		= Recordset::query('
		SELECT
			a.*,
			b.nome_' . Locale::get() . ' AS npc_nome,
			c.nome_' . Locale::get() . ' AS item_nome

		FROM
			quest_guild_npc_item a LEFT JOIN npc b ON b.id=a.id_npc
			LEFT JOIN item c ON c.id=a.id_item

		WHERE
			a.id_quest_guild=' . $quest['id'], true);

	foreach($quest_items->result_array() as $quest_item) {
		if($quest['tipo'] == 'guild') {
			$my_item = Recordset::query('
				SELECT
					*
				FROM
					guild_quest_npc_item

				WHERE
					id_guild=' . $basePlayer->getAttribute('id_guild') . '
					AND id_quest_guild=' . $basePlayer->getAttribute('id_missao_guild2') . '
					AND id_npc=' . $quest_item['id_npc'] . '
					AND id_item=' . $quest_item['id_item'] . '
			')->row_array();
		} else {
			$my_item = Recordset::query('
				SELECT
					*
				FROM
					player_quest_guild_npc_item

				WHERE
					id_player=' . $basePlayer->id . '
					AND id_quest_guild=' . $basePlayer->getAttribute('id_missao_guild') . '
					AND id_npc=' . $quest_item['id_npc'] . '
					AND id_item=' . $quest_item['id_item'] . '
			')->row_array();
		}

		$finalize	= $quest_item['npc_total']  - $my_item['npc_total']  > 0 ? false : $finalize;
		$finalize	= $quest_item['item_total'] - $my_item['item_total'] > 0 ? false : $finalize;
	}

	if(!$finalize) {
		redirect_to('negado', NULL, array('e' => 3));
	}

	if($quest['exp_guild']) {
		guild_exp($quest['exp_guild'], $quest['tipo'] == 'solo' ? false : true);
	}

	if($quest['tipo'] == 'solo') {
		$quest_log	= Recordset::query('SELECT * FROM guild_missao_log WHERE id_guild=' . $basePlayer->id_guild . ' AND id_player=' . $basePlayer->id);

		if (!$quest_log->num_rows) {
			Recordset::insert('guild_missao_log', [
				'id_player'		=> $basePlayer->id,
				'id_guild'		=> $basePlayer->id_guild,
				'missoes_solo'	=> 1,
				'exp'			=> (int)$quest['exp_guild']
			]);
		} else {
			Recordset::update('guild_missao_log', [
				'missoes_solo'	=> ['escape' => false, 'value' => 'missoes_solo + 1'],
				'exp'			=> ['escape' => false, 'value' => 'exp + ' . (int)$quest['exp_guild']]
			], [
				'id_player'		=> $basePlayer->id,
				'id_guild'		=> $basePlayer->id_guild
			]);
		}
		
		//Recordset::query("UPDATE player_flags set missao_total_dia = missao_total_dia + 1 WHERE id_player = ". $basePlayer->id);

		if($quest['exp']) {
			$basePlayer->setAttribute('exp', $basePlayer->getAttribute('exp') + $quest['exp']);
		}

		if($quest['ryou']) {
			$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') + $quest['ryou']);
		}

		if($quest['reputacao']) {
			//reputacao($basePlayer->id, $basePlayer->id_vila, $quest['reputacao']);
		}

		// Recompensa
		Recordset::insert('player_recompensa_log', array(
			'fonte'			=> 'guild_solo',
			'id_player'		=> $basePlayer->id,
			'recebido'		=> 1,
			'exp'			=> $quest['exp'],
			'ryou'			=> $quest['ryou'],
			'reputacao'		=> $quest['reputacao']
		));

		// Remove os itens --->
		foreach($quest_items->result_array() as $quest_item) {
			if($quest_item['id_item']) {
				$item = Recordset::query('SELECT id_tipo FROM item WHERE id=' . $quest_item['id_item'])->row_array();

				if($item['id_tipo'] != 27) {
					continue;
				}

				Recordset::delete('player_item', array(
					'id_player'	=> $basePlayer->id,
					'id_item'	=> $quest_item['id_item']
				));
			}
		}
		// <---

		$basePlayer->setAttribute('id_missao_guild', 0);

		// Atualiza o total de missões diarias do player
		Recordset::update('player_flags', array(
			'guild_quest_feitas'	=> array('escape' => false, 'value' => 'guild_quest_feitas+1')
		), array(
			'id_player'		=> $basePlayer->id
		));

		// Atualiza o contador de missões diarias
		Recordset::update('guild', array(
			'diarias'	=> array('escape' => false, 'value' => 'diarias+1')
		), array(
			'id'		=> $basePlayer->getAttribute('id_guild')
		));
	} else {
		$players	= Recordset::query('SELECT id,id_usuario,id_vila FROM player WHERE id_guild=' . $basePlayer->getAttribute('id_guild'));

		Recordset::update('guild', array(
			'id_quest_guild'	=> 0
		), array(
			'id'				=> $basePlayer->getAttribute('id_guild')
		));

		if($quest['exp']) {
			Recordset::update('player', array(
				'exp'		=> array('escape' => false, 'value' => 'exp+' . $quest['exp'])
			), array(
				'id_guild'	=> $basePlayer->getAttribute('id_guild')
			));
		}

		if($quest['ryou']) {
			foreach($players->result_array() as $player) {
				Recordset::update('player', array(
					'ryou'	=> array('escape' => false, 'value' => 'ryou+' . $quest['ryou'])
				), array(
					'id'	=> $player['id']
				));

				if($quest['reputacao']) {
					//reputacao($player['id'], $player['id_vila'], $quest['reputacao']);
				}
			}
		}

		foreach($players->result_array() as $player) {
			// Recompensa
			Recordset::insert('player_recompensa_log', array(
				'fonte'			=> 'guild_grupo',
				'id_player'		=> $player['id'],
				'recebido'		=> 1,
				'exp'			=> $quest['exp'],
				'ryou'			=> $quest['ryou'],
				'reputacao'		=> $quest['reputacao']
			));
		}

		// Atualiza o contador de missões diarias
		Recordset::update('guild', array(
			'diarias2'	=> array('escape' => false, 'value' => 'diarias2+1')
		), array(
			'id'		=> $basePlayer->getAttribute('id_guild')
		));
	}

	redirect_to('personagem_status');
