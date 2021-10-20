<?php
	$redir_script = true;
	
	$id = decode($_POST['id']);
	
	if(!is_numeric($id)) {
		redirect_to("negado");		
	}
	if($basePlayer->id_graduacao < 3){
		redirect_to("negado");
	}
	if($basePlayer->id_evento) {
		redirect_to("negado");
	}

	$players		= Recordset::query('SELECT id FROM player WHERE id_equipe=' . $basePlayer->id_equipe);
	$players_ids	= array();
	
	foreach($players->result_array() as $player) {
		$players_ids[] = $player['id'];
	}

	$qEvento	= Recordset::query("SELECT * FROM evento WHERE historia=0 AND global=0 AND id=" . (int)$id, true);
	$conc		= Recordset::query('SELECT id_player FROM evento_player WHERE id_player IN(' . join(',', $players_ids) .') AND id_evento=' . $id)->num_rows;

	// Verifica se é um evento válido
	if(!$qEvento->num_rows || $conc) {
		redirect_to("negado", NULL, array("e" => 1));
	}
	
	$rEvento = $qEvento->row_array();

	// Verifica se o evento especificado está na data -->
		if(!$_SESSION['universal']){
			if(!(strtotime($rEvento['dt_inicio']) <= strtotime("+0 minute")) && strtotime($rEvento['dt_fim']) > strtotime("+0 minute")) {
				redirect_to("negado", NULL, array("e" => 2));
			}
		}
	// <---

	// Verifica se a equipe ja fez o evento --->    	
		$qNPCs = new Recordset("SELECT SUM(CASE WHEN morto=1 THEN 1 ELSE 0 END) AS total_morto, COUNT(id) AS total FROM evento_npc_equipe WHERE id_evento=" . $rEvento['id'] . " AND id_equipe=" . (int)$basePlayer->id_equipe);
		$rNPCs = $qNPCs->row_array();
		
		$participado = $rNPCs['total'] ? true : false;
		
		if($participado) {
			redirect_to("negado", NULL, array("e" => 3));
		}
	// <---
	
	// Insere os npcs --->
		$qNPC = Recordset::query("SELECT * FROM evento_npc_evento WHERE id_evento=" . $id, true);
		
		foreach($qNPC->result_array() as $rNPC) {
			Recordset::insert('evento_npc_equipe', array(
				'id_evento_npc'	=> $rNPC['id_evento_npc'],
				'id_equipe'		=> $basePlayer->id_equipe,
				'id_evento'		=> $id
			));
		}
	// <---

	// Marca que o player ja fez o evento assim, o cara não pode fazer de novo se trocar de equipe --->
		foreach($players->result_array() as $player) {
			Recordset::insert('evento_player', array(
				'id_evento'	=> $id,
				'id_player'	=> $player['id']
			));
		}
	// <---
	
	Recordset::update('player', array(
		'id_evento'	=> $id
	), array(
		'id_equipe'	=> $basePlayer->id_equipe
	));

	redirect_to("evento_detalhe");
?>