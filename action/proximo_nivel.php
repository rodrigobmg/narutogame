<?php
	header("Content-Type: text/html; charset=utf-8");

	if($basePlayer->exp < Player::getNextLevel($basePlayer->level)) {
		ajaxDeny();
	}

	$rest_exp = intval($basePlayer->exp - Player::getNextLevel($basePlayer->level));
	Recordset::query("UPDATE player SET exp = $rest_exp, level = level + 1 WHERE id=" . $basePlayer->id);
	
	$basePlayer	= new Player($basePlayer->id);
	
	// Conqiusta --->
		arch_parse(NG_ARCH_SELF, $basePlayer);
	// <---
	
	equipe_exp(200);
	
	// Tira os atributos negativos do player quando ele subir de nível
	Recordset::update('player', array(
		'less_hp'	=> 0,
		'less_sp'	=> 0,
		'less_sta'	=> 0
	), array(
		'id'		=> $basePlayer->id
	));

	$torneio_player	= Recordset::query('SELECT * FROM torneio_player WHERE participando=\'1\' AND id_player=' . $basePlayer->id);
	
	if($torneio_player->num_rows) {
		$torneio	= Recordset::query('SELECT * FROM torneio WHERE id=' . $torneio_player->row()->id_torneio, true)->row_array();
		
		if(($basePlayer->level + 1) > $torneio['req_level_fim'] || $basePlayer->id_graduacao != $torneio['req_id_graduacao']) {
			Recordset::update('torneio_player', array(
				'participando'	=> 0
			), array(
				'id_player'		=> $basePlayer->id,
				'id_torneio'	=> $torneio['id']
			));
			
			Recordset::delete('torneio_espera', array(
				'id_player'		=> $basePlayer->id,
				'id_torneio'	=> $torneio['id']
			));
		}
	}

	//$basePlayer->setFlag('level_page_seen', 1);
?>
location.href="?secao=personagem_status";