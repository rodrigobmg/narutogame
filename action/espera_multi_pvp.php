<?php
	$json				= new stdClass();
	$json->accepted		= $basePlayer->id_batalha_multi_pvp ? true : false;
	$json->cancelled	= $basePlayer->id_sala_multi_pvp ? false : true;
	
	if($basePlayer->dono_equipe && isset($_POST['cancel'])) {
		Recordset::delete('batalha_multi_pvp_espera', array(
			'id_equipe'	=> $basePlayer->id_equipe
		));

		Recordset::update('player', array(
			'id_sala_multi_pvp'	=> 0
		), array(
			'id_equipe'			=> $basePlayer->id_equipe
		));
	}
	
	echo json_encode($json);