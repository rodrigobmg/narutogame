<?php
	require '_config.php';
	
	$hour	= (int)date('H');
	
	if($hour == 0) { // Início
		Recordset::query('DELETE FROM vila_voto');
	} else if($hour == 23) {
		$villages	= Recordset::query('SELECT id FROM vila WHERE id <= 12');
	
		foreach($villages->result_array() as $village) {
			//$players_to_vote	= Recordset::query('SELECT * FROM ranking WHERE id_vila=' . $village['id'] . ' AND posicao_vila BETWEEN 2 AND 25 ORDER BY posicao_vila');
			$kage				= Recordset::query('SELECT * FROM ranking WHERE id_vila=' . $village['id'] . ' AND posicao_vila=1')->row_array();
			$vote_village		= false;
			$vote_defense		= false;
			$vote_war			= false;
			
			$voted_village	= Recordset::query('
				SELECT * FROM ranking WHERE id_player=(
					SELECT id_player_eleito FROM (
						SELECT
							SUM(1),
							id_player_eleito 
						
						FROM
							vila_voto
						
						WHERE
							id_vila=' . $village['id'] . ' AND
							id_player_eleito != ' . $kage['id_player'] . ' AND
							voto="vila"
						
						GROUP BY 2 ORDER BY 1 DESC LIMIT 1
					) w
				)	
			');
		
			if($voted_village->num_rows) {
				$vote_village	= $voted_village->row_array();
			}
		
			$voted_defense	= Recordset::query('
				SELECT * FROM ranking WHERE id_player=(
					SELECT id_player_eleito FROM (
						SELECT
							SUM(1),
							id_player_eleito 
						
						FROM
							vila_voto
						
						WHERE
							id_vila=' . $village['id'] . ' AND
							id_player_eleito != ' . $kage['id_player'] . ' AND
							voto="defesa"' . 
							($vote_village ? ' AND id_player_eleito!=' . $vote_village['id_player'] : '') . '
						
						GROUP BY 2 ORDER BY 1 DESC LIMIT 1
					) w
				)	
			');
		
			if($voted_defense->num_rows) {
				$vote_defense	= $voted_defense->row_array();
			}
		
			$voted_war	= Recordset::query('
				SELECT * FROM ranking WHERE id_player=(
					SELECT id_player_eleito FROM (
						SELECT
							SUM(1),
							id_player_eleito 
						
						FROM
							vila_voto
						
						WHERE
							id_vila=' . $village['id'] . ' AND
							id_player_eleito != ' . $kage['id_player'] . ' AND
							voto="guerra" ' . 
							($vote_village ? ' AND id_player_eleito!=' . $vote_village['id_player'] : '') .
							($vote_defense ? ' AND id_player_eleito!=' . $vote_defense['id_player'] : '') . '
						
						GROUP BY 2 ORDER BY 1 DESC LIMIT 1
					) w
				)	
			');
			
			echo $voted_war->sql;
			
			if($voted_war->num_rows) {
				$vote_war	= $voted_war->row_array();
			}
			
			$data	= array(
				'id_kage'			=> $kage['id_player'],
				'id_cons_vila'		=> $vote_village ? $vote_village['id_player'] : 0,
				'id_cons_defesa'	=> $vote_defense ? $vote_defense['id_player'] : 0,
				'id_cons_guerra'	=> $vote_war ? $vote_war['id_player'] : 0				
			);
			
			Recordset::update('vila', $data, array(
				'id'				=> $village['id']
			));
		}
	}