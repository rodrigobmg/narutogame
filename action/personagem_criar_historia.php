<?php
	header("Content-Type: text/html; charset=utf-8");
	
	if(!is_numeric($_POST['classe'])) {
		$_POST['classe'] = decode($_POST['classe']);
	}
	
	if(!is_numeric($_POST['classe'])) {
		redirect_to("negado");
	}

	$rClasse = Recordset::query("SELECT descricao FROM classe WHERE id=" . (int)$_POST['classe'])->row_array();
	echo nl2br($rClasse['descricao']);
