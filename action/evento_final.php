<?php
	$postkey = $_POST[$_SESSION['ev_field_postkey']];
	
	if($postkey != $_SESSION['ev_field_postkey_value']) {
		redirect_to("negado");
	}

	$rEvento	= Recordset::query('SELECT * FROM evento WHERE id=' . $basePlayer->id_evento, true)->row_array();
	$players	= Recordset::query('SELECT id FROM player WHERE id_equipe=' . $basePlayer->id_equipe);

	$msg = t('actions.a141').": " . addslashes($rEvento['nome_' . Locale::get()]) . t('actions.a142').":<br /><br />
				Ryous: " . $rEvento['ryou'] ." <br />
				Exp: " . $rEvento['exp'] . "<br />
				".t('geral.pontos_treino').": " . $rEvento['treino'] . "<br />
				Ramens: ". $rEvento['qtd'] . " Misso Ebi-Ramen";

	foreach($players->result_array() as $player) {
		if($rEvento['id_item']) {
			if(!Recordset::query('SELECT id FROM player_item WHERE id_player=' . $player['id'] . ' AND id_item=' . $rEvento['id_item'])->num_rows){
				Recordset::insert('player_item', array(
					'id_player'	=> $player['id'],
					'id_item'	=> $rEvento['id_item'],
					'qtd'		=> $rEvento['qtd']
				));
			} else {
				Recordset::update('player_item', array(
					'qtd'		=> array('escape' => false, 'value' => 'qtd+' . $rEvento['qtd'])
				), array(
					'id_item'	=> $rEvento['id_item'],
					'id_player'	=> $player['id']
				));
			}
		}
	
		Recordset::insert('mensagem', array(
			'id_para'	=> $player['id'],
			'titulo'	=> 'Naruto Game - Eventos',
			'corpo'		=> htmlspecialchars($msg)
		));
		
		Recordset::update('player_flags', array(
			'evento_vitorias'	=> array('escape' => false, 'value' => 'evento_vitorias+1')
		), array(
			'id_player'			=> $player['id'],
		));

		// Recompensa
		Recordset::insert('player_recompensa_log', array(
			'fonte'			=> 'evento_equipe',
			'id_player'		=> $player['id'],
			'recebido'		=> 1,
			'exp'			=> (int)$rEvento['exp'],
			'ryou'			=> (int)$rEvento['ryou'],
			'id_item'		=> (int)$rEvento['id_item'],
			'qtd_item'		=> (int)$rEvento['qtd']
		));					
	}

	foreach($players->result_array() as $player) {
		$basePlayer	= new Player($player['id']);
		
		arch_parse(NG_ARCH_NPC_EVENTO_ESPECIAL, $basePlayer);
	}
	
	Recordset::update('player', array(
		'id_evento'		=> 0,
		'exp'			=> array('escape' => false, 'value' => 'exp+' . $rEvento['exp']),
		'ryou'			=> array('escape' => false, 'value' => 'ryou+' . $rEvento['ryou']),
		'treino_total'	=> array('escape' => false, 'value' => 'treino_total+' . $rEvento['treino']),
	), array(
		'id_equipe'		=> $basePlayer->id_equipe
	));	

