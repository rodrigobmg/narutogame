<?php
	/*
	set_time_limit(0);

	if($_GET['f']) {
		require("../include/db.php");
		require("../include/generic.php");
	} else {
		require("/web/narutogame/include/db.php");
		require("/web/narutogame/include/generic.php");		
	}


1º - Identificar jogadores com 2 clãs ou mais e remover todos.
2º - Identificar jogadores com 2 invocações ou mais e remover todas
3º - Identificar jogadores com 2 Selos ou mais e remover todos
4º - Identificar jogadores com 2 Especialização ou mais e remover todas
5º - Identificar jogadores com jutsus repitidos e deleta-los.
6º - Se possivel fazer uma cron que faça o seguinte: Analise os players que possuam coisas que combinadas não podem, exemplo: Portão + Clã


	echo "- Start\n";
	flush();

	$qPlayer = Recordset::query("SELECT id, id_cla, id_especializacao, id_selo, id_invocacao FROM player WHERE level >=15 AND removido=0");

	echo "- Query OK [" . $qPlayer->num_rows . "]\n";
	flush();

	while($rPlayer = $qPlayer->row_array()) {
		echo "K";		
		flush();
		
		if(++$ccc == 100) {
			echo "\n" ;
			$ccc = 0;
		}
		
		// Remove itens de cla --->
			$qItems = Recordset::query("SELECT id, id_cla FROM item WHERE id_cla IS NOT NULL");
			
			while($rItem = $qItems->row_array()) {
				if($rItem['id_cla'] != $rPlayer['id_cla']) {
					Recordset::query("DELETE FROM player_item WHERE id_player=" . $rPlayer['id'] . " AND id_item=" . $rItem['id']);
				}
				
			}
		// <--

		// Remove itens de invocacao --->
			$qItems = Recordset::query("SELECT id, id_invocacao FROM item WHERE id_invocacao IS NOT NULL");
			
			while($rItem = $qItems->row_array()) {
				if($rItem['id_invocacao'] != $rPlayer['id_invocacao']) {
					Recordset::query("DELETE FROM player_item WHERE id_player=" . $rPlayer['id'] . " AND id_item=" . $rItem['id']);
				}
				
			}
		// <--

		// Remove itens de selo --->
			$qItems = Recordset::query("SELECT id, id_selo FROM item WHERE id_selo IS NOT NULL");
			
			while($rItem = $qItems->row_array()) {
				if($rItem['id_selo'] != $rPlayer['id_selo']) {
					Recordset::query("DELETE FROM player_item WHERE id_player=" . $rPlayer['id'] . " AND id_item=" . $rItem['id']);
				}
				
			}
		// <--

		// Remove itens de invocação --->
			$qItems = Recordset::query("SELECT id, id_invocacao FROM item WHERE id_invocacao IS NOT NULL");
			
			while($rItem = $qItems->row_array()) {
				if($rItem['id_invocacao'] != $rPlayer['id_invocacao']) {
					Recordset::query("DELETE FROM player_item WHERE id_player=" . $rPlayer['id'] . " AND id_item=" . $rItem['id']);
				}
				
			}
		// <--

		// Parte pesada.. de verdade
		
		$qItems = Recordset::query("SELECT id_item, COUNT(id) AS total FROM player_item WHERE id_player=" . $rPlayer['id']);
		
		while($rItem = $qItems->row_array()) {
			if($rItem['total'] > 1) {
				$bqItems = Recordset::query("SELECT id FROM player_item WHERE id_player=" . $rPlayer['id'] . " AND id_item=" . $rItem['id_item']);
				
				$c = 0;
				while($c++ < $rItem['total']) {
					$brItem = $bqItems->row_array();
					
					Recordset::query("DELETE FROM player_item WHERE id=" . $brItem['id']);
				}
			}
		}
	}
	
	*/