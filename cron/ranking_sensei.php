<?php
	require('_config.php');

	set_time_limit(0);
	
	Recordset::query('DELETE FROM ranking_sensei');
	Recordset::query('ALTER TABLE ranking_sensei AUTO_INCREMENT=1');

	$qSensei = Recordset::query("SELECT * FROM sensei");

	while($rs = $qSensei->row_array()) {
		$qPlayers = Recordset::query("
			SELECT
				a.id,
				a.id_graduacao,
				a.id_classe,
				a.id_vila,
				a.nome,
				a.level,				
				pt.titulo_br,
				pt.titulo_en,
				b.wins,
				b.losses,
				b.draws,
				b.desafio,
				b.id_sensei
			
			FROM
				player a 
			
			JOIN player_sensei_desafios b ON b.id_player = a.id
			LEFT JOIN player_titulo pt ON pt.id = a.id_titulo
			
			WHERE 
				a.level > 1  AND
				a.removido  = 0 AND
				a.banido = 0 AND
				b.id_sensei = ". $rs['id']
				
		);

		while($rp = $qPlayers->row_array()) {
			$pontos  = $rp['wins'] * 50; // Level
			$pontos += $rp['draws'] * 10; // Derrotas Mapa PVP
			$pontos += $rp['desafio'] * 10000; // Derrotas Mapa PVP		

			$rq = $rp;

			$pontos = absm($pontos);

			Recordset::query("INSERT INTO ranking_sensei(id_sensei,id_player, pontos, level,id_vila, id_classe, nome, wins, losses, draws, desafio, titulo_br,titulo_en, id_graduacao) 
						 VALUES(" . $rs['id'] . "," . $rp['id'] . "," . $pontos . ",". $rp['level'] .",". $rp['id_vila'] .",".$rp['id_classe'].",'". addslashes($rp['nome'])."',
						 " . (int)$rq['wins'] .", " . (int)$rq['losses'] . ", " . (int)$rq['draws'] . ", " . (int)$rq['desafio'] . ",'" . addslashes($rp['titulo_br']) . "', '" . addslashes($rp['titulo_en']) . "', " . $rp['id_graduacao'] . ")");
		}

		$qo = Recordset::query("SELECT pontos, id_player,id_sensei FROM ranking_sensei WHERE id_sensei=" . $rs['id'] . " ORDER BY pontos DESC");
		$c = 0;

		while($ro = $qo->row_array()) {
			Recordset::query("UPDATE ranking_sensei SET posicao_sensei=" . ++$c . " WHERE id_sensei=".$ro['id_sensei']." AND id_player=" . $ro['id_player']);
		}
	}

	$qPlayer = Recordset::query("SELECT id FROM ranking_sensei ORDER BY pontos DESC");
	$c = 0;

	while($r = $qPlayer->row_array()) {
		Recordset::query("UPDATE ranking_sensei SET posicao_geral=" . ++$c . " WHERE id=" . $r['id']);
	}
