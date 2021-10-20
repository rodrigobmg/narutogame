<?php
	require('_config.php');

	echo "- INIT\n- Limpeza das bijuus\n";

	// Remove as bijuus
	Recordset::query("DELETE FROM player_item WHERE id_item IN(SELECT id FROM item WHERE id_tipo=39 )");

	// Etapas de sorteio --->
		$interval = 10;

		$range_count = 0;
		//$range_start = 110;
		$range_start = 210;
		$range_end = $range_start - $interval + 1;
	// <---

	$bijuus = new Recordset("SELECT id FROM item WHERE id_tipo=39  ORDER BY RAND()");
	$bijuus = $bijuus->result_array();

	$bijuu_counter = 0;
	$count = 0;

	$bijuu = $bijuus[$bijuu_counter++];

	while(true) {
		$range_start -= $interval;
		$range_end -= $interval;

		echo '- Loop do range ' . $range_start . ' até ' . $range_end . "\n";
		echo "- Selecionado players... [";

		if($range_end < 1) {
			break;
		}

		while($count++ < 5) {
			if($count == 5) {
				echo "] Nenhum player\n";

				$count = 0;

				break;
			}

			echo $count;

			// Traz os player com orange de sorte especificado
			$players = new Recordset('
				SELECT
					a.id,
					b.sorte_bijuu

				FROM
					player a JOIN player_flags b ON b.id_player=a.id

				WHERE
					a.level >= 15 AND 
					a.removido = 0 AND
					b.sorte_bijuu BETWEEN ' . $range_end . ' AND ' . $range_start . '

				ORDER BY RAND() LIMIT 1000');

			if($bijuu_counter == 8) {
				break 2;
			}

			foreach($players->result_array() as $player) {
				// Se o cara tiver bijuu, não ganha espada da nevoa
				if(Recordset::query('SELECT id FROM player_item WHERE id_player=' . $player['id'] . ' AND id_item_tipo=23')->num_rows) {
					continue;
				}

				// Se o player passar no teste
				//if(rand(1, 100) <= $player['sorte_bijuu'] ) {
					$flag = new Recordset("SELECT espadas FROM player_flags WHERE id_player=" . $player['id']);

					// Faz alocação na tabela de flags caso ele nã tenha
					if(!$flag->num_rows) {
						Recordset::query("INSERT INTO player_flags(id_player) VALUES(" . $player['id'] . ")");
					} else {
						$flag = $flag->row_array();
						$flag = @unserialize($flag['espadas']);
					}

					// caso o valor deserializado não seja array
					if(!is_array($flag)) {
						$flag = array();
					}

					// Verifica se o cara ja tem uma bijuu atualmente, aka, ganhou na propria cron, se sim, vai pro proximo -->
					if(Recordset::query("SELECT id FROM player_item WHERE id_item IN(SELECT id FROM item WHERE id_tipo=39 ) AND id_player=" . $player['id'])->num_rows) {
						continue;
					}
					// <--

					// ok, o cara não tem bijuu, porem, verifica se ele ja não ganhou a atual --->
					if(!in_array($bijuu['id'], $flag)) {
						$flag[] = $bijuu['id'];

						echo "\n + Ganhador " . $player['id'];

						Recordset::query("UPDATE player_flags SET sorte_bijuu=1, espadas='" . addslashes(serialize($flag)) . "' WHERE id_player=" . $player['id']);
						Recordset::query("INSERT INTO player_item(id_player, id_item) VALUES(" . $player['id'] . ", " . $bijuu['id'] . ")");

						// Para o loop infinito, indo para a proxima bijuu
						$bijuu = $bijuus[$bijuu_counter++];
						$count = 0;
						break;
					}
					// <---
				//}
			}
		}
	}

	echo "- Concluído";
