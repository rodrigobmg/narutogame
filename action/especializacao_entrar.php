<?php
	$id = decode($_POST['id']);

	$redir_script = true;

	if(!is_numeric($id)) {
		redirect_to("negado", NULL, array("t" => 1));	
	}

	if($basePlayer->id_especializacao) {
		redirect_to("negado", NULL, array("t" => 2));	
	}
	
	Recordset::query("UPDATE player SET id_especializacao=" . (int)$id . " WHERE id=" . $basePlayer->id);
	redirect_to("especializacao", "", array("ok" => "1"));
