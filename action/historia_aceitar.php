<?php
	$redir_script	= true;
	$saga			= decode($_POST['saga']);
	$npc			= decode($_POST['npc']);
	
	if(!is_numeric($saga) || !is_numeric($npc)) {
		redirect_to('negado', NULL, array('e' => 1));		
	}
	
	$evento		= Recordset::query('SELECT * FROM evento WHERE historia=1 AND id=' . $saga);
	$npc_morto	= Recordset::query('SELECT * FROM evento_player_npc WHERE id_evento=' . $saga . ' AND id_npc=' . $npc . " AND id_player=" . $basePlayer->id)->num_rows;
	
	if(!$evento->num_rows) {
		redirect_to('negado', NULL, array('e' => 2));
	}
	
	if($npc_morto) {
		redirect_to('negado', NULL, array('e' => 3));
	}

	$evento_npc	= Recordset::query('SELECT * FROM evento_npc_evento WHERE id_evento_npc=' . $npc . ' AND id_evento=' . $saga)->row_array();
	$has_reqs	= true;

	if($evento_npc['historia_req_graduacao'] && $basePlayer->id_graduacao < $evento_npc['historia_req_graduacao']) {
		$has_reqs	= false;
	}

	if($evento_npc['historia_req_npc']) {
		$req_npc_morto	= Recordset::query('SELECT * FROM evento_player_npc WHERE id_npc=' . $evento_npc['historia_req_npc'] . " AND id_player=" . $basePlayer->id)->num_rows;

		if(!$req_npc_morto) {
			$has_reqs = false;	
		}
	}
	
	if(!$has_reqs) {
		redirect_to('negado', NULL, array('e' => 4));		
	}

	Fight::cleanup();
	
	$batalha = Recordset::insert('batalha', array(
		'id_tipo'		=> 1,
		'id_player'		=> $basePlayer->id,
		'current_atk'	=> 1,
		'data_atk'		=> array('escape' => false, 'value' => 'NOW()')
	));

	$npc				= new NPC($npc, $basePlayer, NPC_EVENTO_H);
	$npc->id_evento		= $saga;
	$npc->batalha_id	= $batalha;

	$basePlayer->setAttribute('id_batalha', $batalha);

	SharedStore::S('_BATALHA_' . $basePlayer->id, serialize($npc));

	redirect_to("dojo_batalha_lutador");
