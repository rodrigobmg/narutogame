<?php
	if(!between($_POST['role'], 0, 7)) {
		die(1);
	}
	
	$_POST['role'] = $_POST['role'] == 7 ? 'NULL' : "'" . (int)$_POST['role'] . "'";

	
	Recordset::query('UPDATE player_flags SET equipe_role=' . $_POST['role'] . ' WHERE id_player=' . $basePlayer->id);
