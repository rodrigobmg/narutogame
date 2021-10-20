<?php
	$redir_script = true;
	$dia_semana = date('N');
	$hora  = date('H');

	if($dia_semana != 6 and $dia_semana != 7 and $dia_semana != 1){
		redirect_to("negado", NULL, array("e" => 31111111111));
	}
	if(($dia_semana == 6 and $hora < 6 )){
		redirect_to("negado", NULL, array("e" => 3));
	}
	if(($dia_semana == 1 and $hora > 6 )){
		redirect_to("negado", NULL, array("e" => 3));
	}
	if(!is_numeric(decode($_POST['id']))) {
		redirect_to("negado", NULL, array("e" => 1));								 
	}

	if($basePlayer->getAttribute('missao_invasao')) {
		redirect_to("negado", NULL, array("e" => 2));
	}
	
	if(!$basePlayer->getAttribute('dono_guild')) {
		redirect_to("negado", NULL, array("e" => 3));
	}

	$id 			= decode($_POST['id']);
	$players		= Recordset::query('SELECT id, nome, HOUR(TIMEDIFF(NOW(), guild_ult_invasao)) AS diff, guild_ult_invasao FROM player WHERE id_guild=' . $basePlayer->id_guild);
	$cant_accept	= array();

	foreach($players->result_array() as $player) {
		if($player['guild_ult_invasao'] && $player['diff'] <= 5 * 24) {
			$cant_accept[]	= $player;
		}
	}

	if(sizeof($cant_accept)) {
		redirect_to("negado", NULL, array("e" => 5));		
	}
	
	if(Recordset::query('SELECT SQL_NO_CACHE * FROM vila_quest WHERE id=' . $id)->row()->id_guild) {
		redirect_to("negado", NULL, array("e" => 4));
	}

	Recordset::query("UPDATE vila_quest SET id_guild=" . $basePlayer->getAttribute('id_guild') . ", data_ins='". date('Y-m-d H:m:s') ."' WHERE id=" . $id);
	
	Recordset::update('guild', array(
		'exp_total'	=> array('escape' => false, 'value' => 'exp_total-45000')
	), array(
		'id'		=> $basePlayer->getAttribute('id_guild')
	));

	foreach($players->result_array() as $player) {
		Recordset::update('player', array(
			'guild_ult_invasao'	=> array('escape' => false, 'value' => 'NOW()')
		), array(
			'id'				=> $player['id']
		));
	}

	redirect_to("missoes_invasao", NULL, array("ok" => 1));
