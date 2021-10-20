<?php
	if($basePlayer->id_batalha) {
		$b = Recordset::query("SELECT id_tipo FROM batalha WHERE id=" . $basePlayer->id_batalha)->row_array();
		
		if($b['id_tipo'] == 3) { // NPC
			echo "clearInterval(_mapPlayerCheck);";
			echo "location.href='?secao=dojo_batalha_lutador'";
		} else {
			echo "clearInterval(_mapPlayerCheck);";
			echo "location.href='?secao=dojo_batalha_pvp'";
		}
	} else {
		$rp = Recordset::query("SELECT xpos, ypos FROM player WHERE id=" . $basePlayer->id)->row_array();
	
		if($_POST) $_GET = $_POST;
	
		$xpos = (int)$rp['xpos'];
		$ypos = (int)$rp['ypos'];
	
		$map_size = 440;
	
		$xs = $xpos - ($map_size / 2);
		$ys = $ypos - ($map_size / 2);
	
		$xe = $xpos + ($map_size / 2);
		$ye = $ypos + ($map_size / 2);
		
		$qPlayers = "SELECT a.id, a.nome, a.xpos, a.ypos, a.id_vila, b.nome AS guild_nome, b.id AS guild
					 FROM 
					 	player a JOIN guild b ON b.id=a.id_guild
						JOIN guild_batalha_player c ON c.id_player=a.id AND id_guild_batalha={$basePlayer->id_batalha_guild}
					 WHERE 
					 	a.id IN(SELECT id_player FROM guild_batalha_player WHERE id_guild_batalha={$basePlayer->id_batalha_guild}) 
						AND a.id != {$basePlayer->id}
						AND a.id_batalha IS NULL
						AND a.hospital IS NULL
						AND c.perdedor=0";

		$qPlayers = Recordset::query($qPlayers);
	
		$rp = Recordset::query("SELECT xpos, ypos FROM player WHERE id=" . $basePlayer->id)->row_array();
		
		echo "$('.matrixTipItem').remove();\n_tipMatrix = [];\n";
		
		$arPlayers = array();
	
		while($r = $qPlayers->row_array()) {
			$cx = round(($r['xpos'] - 220) / $_GET['detail']);
			$cy = round(($r['ypos'] - 220) / $_GET['detail']);
	
			$arPlayers["y{$cy}x{$cx}"][] = $r;
		}
	
		foreach($arPlayers as $k => $root) {
			echo "if(!_tipMatrix['{$k}']) _tipMatrix['{$k}'] = [];\n";
			
			foreach($root as $r) {
				$cx = round(($r['xpos'] - 220) / $_GET['detail']);
				$cy = round(($r['ypos'] - 220) / $_GET['detail']);
				
				$id = encode($r['id']);
				
				echo "_tipMatrix['y{$cy}x{$cx}'].push(['" . addslashes($r['nome']) . "', '$cx', '$cy', {$r['guild']}, '$id', '" . $r['guild_nome'] . "']);\n";
			}
	
		}
	
		echo "updateTipMatrix();\n";
	}
