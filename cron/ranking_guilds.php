<?php
	require('_config.php');

	Recordset::query('DELETE FROM ranking_guild');
	Recordset::query('ALTER TABLE ranking_guild AUTO_INCREMENT=1');

	$qVila = Recordset::query("SELECT * FROM vila");
	$arVilas = array();

	while($rv = $qVila->row_array()) {
		$qPlayers = Recordset::query("
			SELECT
				a.id,
				b.vitorias,
				b.derrotas,
				b.diarias,
				b.diarias2,
				b.level,
				SUM(CASE WHEN bb.morto = '1' THEN 1 ELSE 0 END) AS mortos,
   				SUM(CASE WHEN bb.morto = '0' THEN 1 ELSE 0 END) AS vivos,
				b.nome AS guild_nome			
			FROM
				player a 
				JOIN guild b ON b.id_player=a.id
				LEFT JOIN bingo_book_guild as bb ON b.id = bb.id_guild
			
			WHERE 
				a.id_vila=" . $rv['id'] . " and b.removido='0'  GROUP BY a.id "
		);

		while($rp = $qPlayers->row_array()) {
			$pontos  = $rp['vitorias'] * 1000;
			$pontos  += $rp['derrotas'] * 100;
			$pontos  += $rp['diarias'] * 75;
			$pontos  += $rp['diarias2'] * 500;
			$pontos  += $rp['mortos'] * 200;

			$pontos = absm($pontos);

			Recordset::query("INSERT INTO ranking_guild(id_player, id_vila, pontos, nome_guild, level) 
						 VALUES(" . $rp['id'] . ", " . $rv['id'] . ", " . $pontos . ", '" . addslashes($rp['guild_nome']) . "', " . $rp['level'] . ")");
		}

		$qo = Recordset::query("SELECT pontos, id_player FROM ranking_guild WHERE id_vila=" . $rv['id'] . " ORDER BY pontos DESC");
		$c = 0;

		while($ro = $qo->row_array()) {
			Recordset::query("UPDATE ranking_guild SET posicao_vila=" . ++$c . " WHERE id_player=" . $ro['id_player']);
		}
	}
	
	$qPlayer = Recordset::query("SELECT id FROM ranking_guild ORDER BY pontos DESC");
	$c = 0;

	while($r = $qPlayer->row_array()) {
		Recordset::query("UPDATE ranking_guild SET posicao_geral=" . ++$c . " WHERE id=" . $r['id']);
	}
