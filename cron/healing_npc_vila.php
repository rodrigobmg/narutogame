<?php
	require('_config.php');

	$qNPC = Recordset::query("SELECT id, hp, sp, sta FROM npc_vila");
	
	while($r = $qNPC->row_array()) {
		$hp =  (int)percent(50, $r['hp']);
		$sp =  (int)percent(50, $r['sp']);
		$sta = (int)percent(50, $r['sta']);
		
		Recordset::query("UPDATE npc_vila SET less_hp=less_hp - $hp, less_sp=less_sp - $sp, less_sta=less_sta - $sta WHERE id=" . $r['id']);
	}
	
	Recordset::query("UPDATE npc_vila SET less_hp=0  WHERE less_hp < 0");
	Recordset::query("UPDATE npc_vila SET less_sp=0  WHERE less_sp < 0");
	Recordset::query("UPDATE npc_vila SET less_sta=0 WHERE less_sta < 0");
