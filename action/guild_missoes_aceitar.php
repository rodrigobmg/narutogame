<?php
	$redir_script		= true;
	$today				= date('N');
	$quests				= Recordset::query('SELECT * FROM quest_guild', true);
	$current_quest		= Recordset::query('SELECT * FROM quest_guild WHERE ativo = 1 AND id=' . (int)$_POST['quest']);
	$guild				= Recordset::query('SELECT * FROM guild WHERE id=' . $basePlayer->getAttribute('id_guild'))->row_array();
	$total_quests_avail = array();
	$quests_made		= Recordset::query('SELECT id_quest_guild FROM player_quest_guild_npc_item WHERE id_player=' . $basePlayer->id . ' GROUP BY id_quest_guild')->result_array();
	$quests_made2		= Recordset::query('SELECT id_quest_guild, falha FROM guild_quest_npc_item WHERE id_guild=' . $basePlayer->getAttribute('id_guild') . ' GROUP BY id_quest_guild')->result_array();

	$quests_made		= array_merge($quests_made, $quests_made2);

	if(!$current_quest->num_rows) {
		die('jalert("Missão inválida")');
	}

	$current_quest	= $current_quest->row_array();
	$quest_items	= Recordset::query('SELECT * FROM quest_guild_npc_item WHERE id_quest_guild=' . $_POST['quest'] , true);

	// ok, c&p madito da module =( --->
		foreach($quests->result_array() as $quest) {
			foreach($quests_made as $quest_made) {
				if($quest_made['id_quest_guild'] == $quest['id']) {
					continue 2;
				}
			}

			$ar_quests[] = $quest['id'];
		}

		if(!in_array($_POST['quest'], $ar_quests)) {
			die('jalert("'.t('actions.a158').'")');
		}
	// <---

	if($current_quest['tipo'] == 'solo') {
		$basePlayer->setAttribute('id_missao_guild', $_POST['quest']);

		$conclusao	= strtotime('+24 hour');

		foreach($quest_items->result_array() as $quest_item) {
			Recordset::insert('player_quest_guild_npc_item', array(
				'id_player'			=> $basePlayer->id,
				'id_quest_guild'	=> $_POST['quest'],
				'id_npc'			=> $quest_item['id_npc'],
				'id_item'			=> $quest_item['id_item'],
				'conclusao'			=> date('Y-m-d H:i:s', $conclusao)
			));
		}

		redirect_to('guild_missoes_status');
	} else {
		$conclusao	= strtotime('+72 hour');

		if(!$basePlayer->getAttribute('dono_guild')) {
			die('jalert("'.t('actions.a160').'")');
		}

		if($basePlayer->getAttribute('id_missao_guild2')) {
			die('jalert("'.t('actions.a159').'")');
		}

		if($guild['membros'] != 9) {
			die('jalert("'.t('actions.a161').'")');
		}

		Recordset::update('guild', array(
			'id_quest_guild'	=> $current_quest['id']
		), array(
			'id'				=> $basePlayer->getAttribute('id_guild')
		));

		foreach($quest_items->result_array() as $quest_item) {
			Recordset::insert('guild_quest_npc_item', array(
				'id_guild'			=> $basePlayer->getAttribute('id_guild'),
				'id_quest_guild'	=> $_POST['quest'],
				'id_npc'			=> $quest_item['id_npc'],
				'id_item'			=> $quest_item['id_item'],
				'falha'				=> 0,
				'conclusao'			=> date('Y-m-d H:i:s', $conclusao)
			));
		}

		redirect_to('guild_missoes_status_guild');
	}
