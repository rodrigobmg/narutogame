<?php
	if(!$mine_rewards) {
		exit();
	}
	
	$mine_rewards	= Recordset::query('SELECT * FROM player_recompensa_log WHERE recebido=0 AND id_player=' . $basePlayer->id);

	foreach($mine_rewards->result_array() as $reward) {
		if($reward['coin']) {
			Recordset::update('global.user', array(
				'coin'	=> array('escape' => false, 'value' => 'coin+' . $reward['coin'])
			), array(
				'id'	=> $basePlayer->id_usuario
			));
		}

		if($reward['ryou'] || $reward['exp'] || $reward['treino'] || $reward['treino_total']) {
			$treino	= $basePlayer->getAttribute('treino_dia') - $reward['treino'];
			$treino	= $treino < 0 ? 0 : $treino;
		
			Recordset::update('player', array(
				'ryou'			=> array('escape' => false, 'value' => 'ryou+' . $reward['ryou']),
				'exp'			=> array('escape' => false, 'value' => 'exp+' . $reward['exp']),
				'treino_dia'	=> $treino,
				'treino_total'	=> ['escape' => false, 'value' => 'treino_total + ' . $reward['treino_total']]
			), array(
				'id'	=> $basePlayer->id
			));
			
			$basePlayer->setLocalAttribute('treino_dia', $treino);
		}

		if($reward['reputacao']) {
			//reputacao($basePlayer->id, $basePlayer->id_vila, $reward['reputacao']);
		}
		
		if(
			$reward['ken'] ||  $reward['tai'] || $reward['nin'] || $reward['gen'] || $reward['ene'] ||
			$reward['inte'] || $reward['forc'] || $reward['agi'] || $reward['con'] || $reward['res']
		) {
			Recordset::update('player_atributos', array(
				'tai'	=> array('escape' => false, 'value' => 'tai  + ' . $reward['tai']),
				'ken'	=> array('escape' => false, 'value' => 'ken  + ' . $reward['ken']),
				'nin'	=> array('escape' => false, 'value' => 'nin  + ' . $reward['nin']),
				'gen'	=> array('escape' => false, 'value' => 'gen  + ' . $reward['gen']),
				'ene'	=> array('escape' => false, 'value' => 'ene  + ' . $reward['ene']),
				'inte'	=> array('escape' => false, 'value' => 'inte + ' . $reward['inte']),
				'forc'	=> array('escape' => false, 'value' => 'forc + ' . $reward['forc']),
				'agi'	=> array('escape' => false, 'value' => 'agi  + ' . $reward['agi']),
				'con'	=> array('escape' => false, 'value' => 'con  + ' . $reward['con']),
				'res'	=> array('escape' => false, 'value' => 'res  + ' . $reward['res'])
			), array(
				'id_player'	=> $basePlayer->id
			));
		}

		if($reward['id_item'] && $reward['qtd_item']) {
			$player_item	= Recordset::query('SELECT id FROM player_item WHERE id_player=' . $basePlayer->id . ' AND id_item=' . $reward['id_item']);
		
			if($player_item->num_rows) {
				Recordset::update('player_item', array(
					'qtd'		=> array('escape' => false, 'value' => 'qtd + ' . $reward['qtd_item'])
				), array(
					'id'		=> $player_item->row()->id
				));
			} else {
				Recordset::insert('player_item', array(
					'id_player'	=> $basePlayer->id,
					'id_item'	=> $reward['id_item'],
					'qtd'		=> $reward['qtd_item']
				));
			}
		}
	
		Recordset::update('player_recompensa_log', array(
			'recebido'		=> 1,
			'data_recebido'	=> array('escape' => false, 'value' => 'NOW()')
		), array(
			'id'		=> $reward['id']
		));		
	}
