<?php
	require('_config.php');

	set_time_limit(0);

	Recordset::query('DELETE FROM ranking_4x4');
	Recordset::query('ALTER TABLE ranking_4x4 AUTO_INCREMENT=1');

	$qVila = Recordset::query("SELECT * FROM vila WHERE inicial='1'");
	$arVilas = array();

	while($rv = $qVila->row_array()) {
		$qPlayers = Recordset::query("
			SELECT
				a.id,
				a.id_graduacao,
				a.id_classe,
				a.nome,
				a.level,
				a.vitorias_rnd,
				a.derrotas_rnd,
				pt.titulo_br,
				pt.titulo_en
			FROM
				player a
			LEFT JOIN player_titulo pt ON pt.id = a.id_titulo
			WHERE
				level > 5  AND
				removido  = 0 AND
				banido = 0 AND
				id_vila=" . $rv['id']

		);

		$pontos = 0;
		while($rp = $qPlayers->row_array()) {
			$pontos += $rp['vitorias_rnd'] * 30; // Vitorias
			$pontos -= $rp['derrotas_rnd'] * 10; // Derrotas
			$rq = $rp;
			$pontos = absm($pontos);

			Recordset::query("INSERT INTO ranking_4x4(id_player, id_vila, pontos, level, id_classe, nome, titulo_br,titulo_en, id_graduacao)
						 VALUES(" . $rp['id'] . ", " . $rv['id'] . ", " . $pontos . ",". $rp['level'] .",".$rp['id_classe'].",'". addslashes($rp['nome'])."',
						 '" . addslashes($rp['titulo_br']) . "', '" . addslashes($rp['titulo_en']) . "', " . $rp['id_graduacao'] . ")");
		}

		$qo = Recordset::query("SELECT pontos, id_player FROM ranking_4x4 WHERE id_vila=" . $rv['id'] . " ORDER BY pontos DESC");
		$c = 0;

		while($ro = $qo->row_array()) {
			Recordset::query("UPDATE ranking_4x4 SET posicao_vila=" . ++$c . " WHERE id_player=" . $ro['id_player']);

			if($c == 1) {
				Recordset::update('vila', array(
					'id_kage'	=> $ro['id_player']
				), array(
					'id'		=> $rv['id']
				));
			}
		}
	}

	$qPlayer = Recordset::query("SELECT id FROM ranking_4x4 ORDER BY pontos DESC");
	$c = 0;

	while($r = $qPlayer->row_array()) {
		Recordset::query("UPDATE ranking_4x4 SET posicao_geral=" . ++$c . " WHERE id=" . $r['id']);
	}
