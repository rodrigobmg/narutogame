<?php
	if($basePlayer->id_sennin || !is_numeric($_POST['sennin'])) {
		redirect_to('negado', NULL, array('e' => 1));	
	}
	
	$sennin	= Recordset::query('SELECT id, id_invocacao FROM sennin WHERE id=' . (int)$_POST['sennin']);
	
	if(!$sennin->num_rows) {
		redirect_to('negado', NULL, array('e' => 2));
	}

	/*if($basePlayer->id_invocacao != $sennin->row()->id_invocacao) {
		redirect_to('negado', NULL, array('e' => 3));
	}*/

	if($basePlayer->id_selo) {
		redirect_to('negado', NULL, array('e' => 4));
	}

	if($basePlayer->portao) {
		redirect_to('negado', NULL, array('e' => 5));		
	}
	
	$basePlayer->setAttribute('id_sennin', $_POST['sennin']);
	
	$_SESSION['mode_sennin_aprendido']	= $_POST['sennin'];
	
	redirect_to('mode_sennin', NULL, array('ok' => 1));
