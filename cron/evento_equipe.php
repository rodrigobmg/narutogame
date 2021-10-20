<?php
	require('_config.php');

	// Processa os novos eventos --->
		$qEventos = Recordset::query("SELECT * FROM evento WHERE dt_inicio < NOW() AND iniciado=0 AND removido=0 AND ativo=1");
		
		while($rEvento = $qEventos->row_array()) {
			
			if($rEvento['global']!=1){
				
				$qNPC = Recordset::query("SELECT * FROM evento_npc_evento WHERE id_evento=" . $rEvento['id']);
				
				// Posiciona os npcs
				while($rNPC = $qNPC->row_array()) {
					$rndX = round(rand(1, 155)) * 22;
					$rndY = round(rand(1, 90)) * 22;
					
					Recordset::query("UPDATE evento_npc SET xpos=$rndX, ypos=$rndY WHERE id=" . $rNPC['id_evento_npc']);
				}
			}
			
			Recordset::query("UPDATE evento SET iniciado=1 WHERE id=" . $rEvento['id']);
		}
	// <---
	
	// Processa as finalizações dos eventos globais --->
		$qEventos = Recordset::query('SELECT * FROM evento WHERE dt_fim <= NOW() AND global=1 AND ativo=1 AND finalizado=0');
		
		while($rEvento = $qEventos->row_array()) {
			
			Recordset::query("UPDATE evento SET finalizado=1 WHERE id = ". $rEvento['id'] ."");
			
			$total_npcs = Recordset::query('
				SELECT SUM(CASE WHEN morto_global=1 THEN 1 ELSE 0 END) AS morto, SUM(1) as total FROM evento_npc_evento WHERE id_evento=
			' . $rEvento['id'])->row_array();
			
			if($total_npcs['morto'] >= $total_npcs['total']) {
				Recordset::query('UPDATE vila SET vitoria_global=vitoria_global+1 WHERE id != 6');	
				Recordset::query('UPDATE vila SET derrota_global=derrota_global+1 WHERE id = 6');				
			} else {
				Recordset::query('UPDATE vila SET vitoria_global=vitoria_global+1 WHERE id = 6');				
				Recordset::query('UPDATE vila SET derrota_global=derrota_global+1 WHERE id != 6');	
			}
		}
	// <--
	
	// Processa os eventos que vão ser finalizados agora e adiciona recorrencia pra oss que tem
		$qEventos = Recordset::query("SELECT * FROM evento WHERE dt_fim <= NOW() AND iniciado=1 AND finalizado=0 AND removido=0 AND ativo=1 AND global=0 AND historia=0");
		
		while($rEvento = $qEventos->row_array()) {
			Recordset::query("UPDATE evento SET finalizado=1 WHERE id=" . $rEvento['id']);

			// Fode os players que não clicaram no botão --->
				$q = Recordset::query("SELECT id FROM player WHERE id_evento=" . $rEvento['id']);
				
				while($r = $q->row_array()) {
					Recordset::query('UPDATE player SET id_evento=0 WHERE id=' . $r['id']);
					Recordset::query('UPDATE player_flags SET evento_derrotas=evento_derrotas+1 WHERE id_player=' . $r['id']);
					
					mensageiro(NULL, $r['id'], 'Naruto Game: Eventos', 'Você falhou no evento', 'team');
				}
			// <---
			
			// Cria o evento recorrente caso o atual possua --->
				if($rEvento['recorrente']) {
					$q = Recordset::query("INSERT INTO evento(nome_br, nome_en, descricao_br, descricao_en, dt_inicio, dt_fim, recorrente, ativo, ryou, exp, treino, id_item, qtd, titulo) 
								 SELECT nome_br, nome_en, descricao_br, descricao_en, dt_inicio, dt_fim, recorrente, ativo, ryou, exp, treino, id_item, qtd, titulo FROM evento WHERE id=" . $rEvento['id']);
					
					$nEvento = $q->insert_id();
					
					Recordset::query("UPDATE evento SET 
								 	dt_inicio='" . date("Y-m-d H:i:s", strtotime("+ {$rEvento['recorrente']} DAY", strtotime($rEvento['dt_inicio']))) . "', 
								 	dt_fim='" . date("Y-m-d H:i:s", strtotime("+ {$rEvento['recorrente']} DAY", strtotime($rEvento['dt_fim']))) . "' 
								 WHERE id=" . $nEvento);
					
					Recordset::query("INSERT INTO evento_npc_evento(id_evento, id_evento_npc)
								 SELECT " . $nEvento . " AS id_evento, id_evento_npc FROM evento_npc_evento WHERE id_evento=" . $rEvento['id']);
				}
			// <---
		}
	// <---
