<?php
	if(isset($_POST['item']) && $basePlayer->hasItem($_POST['item'])) {
		Recordset::update('player_item', array(
			'dojo_ativo'	=> isset($_POST['active']) && $_POST['active'] ? 1 : 0
		), array(
			'id_player'		=> $basePlayer->id,
			'id_item'		=> $_POST['item']
		));
	}
