<?php
	require('_config.php');

	$torneios = Recordset::query('
		SELECT
			*

		FROM
			torneio

		WHERE
			dt_fim  IS NOT NULL OR 
			dt_fim2 IS NOT NULL OR
			dt_fim3 IS NOT NULL
	');
	
	while($torneio = $torneios->row_array()) {
		if($torneio['dias'] && !on(date('N'), $torneio['dias'])) {
			continue;
		}

		$finish	= false;

		$date1s	= date('Hi', strtotime($torneio['dt_inicio']));
		$date2s	= date('Hi', strtotime($torneio['dt_inicio2']));
		$date3s	= date('Hi', strtotime($torneio['dt_inicio3']));

		$date1e	= date('Hi', strtotime($torneio['dt_fim']));
		$date2e	= date('Hi', strtotime($torneio['dt_fim2']));
		$date3e	= date('Hi', strtotime($torneio['dt_fim3']));

		$now	= date('Hi', strtotime('-1 minute'));

		if($torneio['dt_fim']) {
			if(between($now, $date1s, $date1e)) {
				$finish	= true;
			}
		}

		if($torneio['dt_fim2']) {
			if(between($now, $date2s, $date2e)) {
				$finish	= true;
			}
		}

		if($torneio['dt_fim3']) {
			if(between($now, $date3s, $date3e)) {
				$finish	= true;
			}
		}

		if($finish) {
			$players	= Recordset::query('SELECT * FROM torneio_player WHERE participando=\'1\' AND id_torneio=' . $torneio['id']);

			while($player = $players->row_array()) {
				Recordset::query('UPDATE torneio_player SET derrotas=derrotas+1, participando=\'0\' WHERE id_torneio=' . $torneio['id'] . ' AND id_player=' . $player['id_player']);
				Recordset::query('DELETE FROM torneio_espera WHERE id_player=' . $player['id_player'] . ' AND id_torneio=' . $torneio['id']);
			}
		}
	}
