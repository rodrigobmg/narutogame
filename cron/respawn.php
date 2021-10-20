<?php
	require('_config.php');

	/*
	$qNPC = Recordset::query("SELECT id, total FROM npc");

	while($rNPC = $qNPC->row_array()) {
		$nTotal = Recordset::query("SELECT COUNT(id) AS mx FROM npc_mapa WHERE id_npc=" . $rNPC['id'])->row_array();

		if($nTotal['mx'] < $rNPC['total']) {
			$total = absm($rNPC['total'] - $nTotal['mx']);
			
			for($f = 0; $f < $total; $f++) {
				Recordset::query("INSERT INTO npc_mapa(id_npc) VALUES(" . $rNPC['id'] . ")");
			}
		}
	}*/
