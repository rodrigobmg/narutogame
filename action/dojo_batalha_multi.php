<?php
	$rBatalha = Recordset::query("SELECT * FROM batalha_multi WHERE id=" . $basePlayer->id_batalha)->row_array();
	
	if($rBatalha['p' . ($rBatalha['current'] + 1)] == $basePlayer->id) {
		$myTurn = true;
	} else {
		$myTurn = false;	
	}
	
	if($myTurn) {
		echo "_canAtk = true;";
	}
