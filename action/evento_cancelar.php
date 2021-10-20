<?php
	$postkey = $_POST[$_SESSION['ev_field_postkey']];
	
	if($postkey != $_SESSION['ev_field_postkey_value']) {
		redirect_to("negado");
	}
	
	Recordset::update('player', array(
		'id_evento'	=> 0
	), array(
		'id_equipe'	=> $basePlayer->id_equipe
	));
	