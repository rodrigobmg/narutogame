<?php
	require('_config.php');
	require(ROOT . '/include/conquista.php');
	
	// Inicia eventos e ja cria os futuros --->
		$eventos	= Recordset::query('SELECT * FROM evento_vila WHERE iniciado=0 AND data_inicio <= NOW() AND finalizado=0');
		
		foreach($eventos->result_array() as $evento) {
			switch($evento['tipo']) {
				case 'bijuu';	$tipo	= '34'; break;
				case 'armas';	$tipo	= '35'; break;
				case 'espadas';	$tipo	= '36'; break;
			}
			
			$items	= Recordset::query('SELECT id FROM item WHERE id_tipo=' . $tipo);
			$vilas	= Recordset::query('SELECT id FROM vila WHERE inicial="1" ORDER BY RAND()');
			
			Recordset::delete('player_item', array(
				'id_item_tipo'	=> $tipo
			));
			
			$players		= array(0);
			$given			= array();
			$given_total	= 0;

			// Distribui igual mente
			while($given_total < $items->num_rows) {
				if($vilas->eof()) {
					break;	
				}
				
				$item		= $items->row()->id;
				$vila		= $vilas->row_array();
				$items->next_row();
				
				echo 'GIVE ' . $vila['id'] . ' -> ';

				$player		= Recordset::query('
					SELECT
						id
					FROM
						player
					
					WHERE
						level > 5 AND DAY(DATEDIFF(NOW(), ult_atividade)) < 2 AND
						ult_atividade IS NOT NULL AND
						id_vila=' . $vila['id'] . ' AND
						id NOT IN(' . join(',', $players) . ')
					
					ORDER BY RAND()
					LIMIT 1');
				
				if(!$player->num_rows) {
					echo "NO\n";
					
					continue;
				}
				
				$player	= $player->row()->id;
				echo $player . "\n";

				Recordset::insert('player_item', array(
					'id_player'	=> $player,
					'id_item'	=> $item,
					'qtd'		=> 1
				));
				
				$players[]	= $player;
				$given_total++;
			}
			
			// Randomiza distribuição pros que sobraram
			while($given_total < $items->num_rows) {
				$item		= $items->row()->id;
				$items->next_row();

				$player		= Recordset::query('
					SELECT
						id
					FROM
						player
					
					WHERE
						level > 5 AND DAY(DATEDIFF(NOW(), ult_atividade)) < 2 AND
						ult_atividade IS NOT NULL AND
						id NOT IN(' . join(',', $players) . ')
					
					ORDER BY RAND()
					LIMIT 1')->row()->id;

				echo 'GIVE RANDOM ' . $player . "\n";

				Recordset::insert('player_item', array(
					'id_player'	=> $player,
					'id_item'	=> $item,
					'qtd'		=> 1
				));
				
				$players[]	= $player;
				$given_total++;
			}			

			echo "GT -> {$given_total}/{$items->num_rows}\n";
			
			Recordset::update('evento_vila', array(
				'iniciado'	=> 1
			), array(
				'id'		=> $evento['id']
			));
			
			// Inserção do próximo evento --->
				unset($evento['id']);
				$evento['data_inicio']	= date('Y-m-d H:i:s', strtotime('+45 days', strtotime($evento['data_inicio'])));
				$evento['data_fim']		= date('Y-m-d H:i:s', strtotime('+45 days', strtotime($evento['data_fim'])));
			
				Recordset::insert('evento_vila', $evento);
			// <---
		}
	// <---
	
	// Controla a inatividade dos itens de eventos iniciados --->
		$eventos	= Recordset::query('SELECT * FROM evento_vila WHERE iniciado=1');
		
		foreach($eventos->result_array() as $evento) {
			switch($evento['tipo']) {
				case 'bijuu';	$tipo	= '34'; break;
				case 'armas';	$tipo	= '35'; break;
				case 'espadas';	$tipo	= '36'; break;
			}

			$items	= Recordset::query('SELECT id, id_player FROM player_item WHERE id_item_tipo=' . $tipo);
			
			foreach($items->result_array() as $item) {
				$ok				= true;
				$ult_batalha	= Recordset::query('
					SELECT
						DATEDIFF(NOW(), data_ins) AS diff_d,
						HOUR(TIMEDIFF(NOW(), data_ins)) AS diff_h
					
					FROM
						batalha
					
					WHERE
						id_player=' . $item['id_player'] . '
					
					ORDER BY data_ins DESC
					LIMIT 1
				');
				
				if(!$ult_batalha->num_rows) { // não batalhou
					$ok	= false;
				} else {
					if($ult_batalha->row()->diff_h > 6) { // mais q 6 hrs
						$ok	= false;	
					} 
				}
				
				if(!$ok) { // Repassa o item pra outro trouxa, dgo, player =)
					$player		= Recordset::query('
						SELECT
							id
						FROM
							player
						
						WHERE
							level > 5 AND DAY(DATEDIFF(NOW(), ult_atividade)) < 2 AND
							ult_atividade IS NOT NULL AND
							id != ' . $item['id_player'] . '
						
						ORDER BY RAND()
						LIMIT 1')->row()->id;
	
					echo 'RE-SORT FROM ' . $item['id_player'] . ' TO ' . $player . "\n";
					
					Recordset::update('player_item', array(
						'id_player'	=> $player
					), array(
						'id'		=> $item['id']
					));
				}
			}
		};
	// <---
	
	// Finaliza eventos --->
		$eventos	= Recordset::query('SELECT * FROM evento_vila WHERE iniciado=1');
		//$eventos	= Recordset::query('SELECT * FROM evento_vila WHERE iniciado=1 AND (data_fim <= NOW() OR finalizado=1)');
		
		echo "+ Verificando finalização\n";
		
		foreach($eventos->result_array() as $evento) {
			switch($evento['tipo']) {
				case 'bijuu';	$tipo	= '34'; break;
				case 'armas';	$tipo	= '35'; break;
				case 'espadas';	$tipo	= '36'; break;
			}

			$ok		= false;
			$items	= Recordset::query('SELECT id FROM item WHERE id_tipo=' . $tipo);
			$vilas	= Recordset::query('
				SELECT
					a.id,
					(
						SELECT
							COUNT(aa.id )
						FROM 
							player_item aa JOIN player b ON b.id=aa.id_player
						
						WHERE
							aa.id_item_tipo=' . $tipo . ' AND
							b.id_vila=a.id
					) AS total
				FROM
					vila a
				
				WHERE
					a.inicial="1"			
			');
			
			foreach($vilas->result_array() as $vila) {
				echo $vila['id'] . ' -> ' . $vila['total'] . ' [' . $items->num_rows . ']' . PHP_EOL;
				
				if($vila['total'] >= $items->num_rows) { // ganhou
					vila_exp($evento['exp'], $vila['id']);
					
					// Da a vitória para a vila
					Recordset::update('vila', array(
						'evento_global'	=> array('escape' => false, 'value' => 'evento_global+1')
					), array(
						'id'			=> $vila['id']
					));
					
					// Log
					Recordset::insert('vila_log', array(
						'evento_id'	=> $evento['id'],
						'vila_id'	=> $vila['id']
					));

					// Conquista -->
						$players	= Recordset::query('SELECT DISTINCT(a.id) AS id FROM player a JOIN player_item b ON b.id_player=a.id WHERE a.id_vila=' . $vila['id'] . ' AND b.id_item_tipo=' . $tipo);

						foreach($players->result_array() as $player) {
							$basePlayer			= new stdClass();
							$basePlayer->id 	= $player['id'];

							arch_parse(NG_ARCH_EVENTO_VILA, $basePlayer);
						}
					// <--
					
					$ok	= true;
				}
			}
			
			if($ok || now() >= strtotime($evento['data_fim'])) {
				echo "\n\n+ FINALIZADO";
				
				Recordset::delete('player_item', array(
					'id_item_tipo'	=> $tipo
				));
				
				Recordset::update('evento_vila', array(
					'iniciado'		=> 0,
					'finalizado'	=> 1
				), array(
					'id'		=> $evento['id']
				));
			}
		}
	// <---
