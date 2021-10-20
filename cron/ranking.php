<?php
	require('_config.php');

	set_time_limit(0);

	Recordset::query('DELETE FROM ranking');
	Recordset::query('ALTER TABLE ranking AUTO_INCREMENT=1');

	$qVila = Recordset::query("SELECT * FROM vila WHERE inicial='1'");

	$arVilas = array();
	$arGraduacoes = array(
		1 => 2000,
		2 => 4000,
		3 => 6000,
		4 => 8000,
		5 => 10000,
		6 => 12000,
		7 => 14000
	);

	while($rv = $qVila->row_array()) {
		$qPlayers = Recordset::query("
			SELECT
				a.id,
				a.id_graduacao,
				a.id_classe,
				a.nome,
				a.level,
				a.vitorias,
				a.vitorias_f,
				a.vitorias_d,
				a.vitorias_rnd,
				a.empates,
				a.derrotas,
				a.derrotas_f,
				a.derrotas_npc,
				a.derrotas_rnd,
				a.fugas,
				a.treino_total,
				a.vila_quest_vitorias,
				a.vila_quest_derrotas,

			 	pf.guild_quest_feitas,

				(SELECT SUM(CASE WHEN bb.morto = '1' THEN 1 ELSE 0 END) FROM bingo_book bb WHERE bb.id_player = a.id) as total_morto,
				(SELECT SUM(CASE WHEN bb.morto = '0' THEN 1 ELSE 0 END) FROM bingo_book bb WHERE bb.id_player_alvo = a.id) as total_vivo,
                (SELECT SUM(CASE WHEN tp.vitorias >= 1 AND t.npc = '0' THEN vitorias ELSE 0 END) FROM torneio_player tp JOIN torneio t ON t.id = tp.id_torneio WHERE tp.id_player = a.id) as total_torneio_pvp,
                (SELECT SUM(CASE WHEN tp.vitorias >= 1 AND t.npc = '1' THEN vitorias ELSE 0 END) FROM torneio_player tp JOIN torneio t ON t.id = tp.id_torneio WHERE tp.id_player = a.id) as total_torneio_npc,

				b.tarefa AS lvl_tarefa,
				b.quest_d AS lvl_d,
				b.quest_c AS lvl_c,
				b.quest_b AS lvl_b,
				b.quest_a AS lvl_a,
				b.quest_s AS lvl_s,

				pt.titulo_br,
				pt.titulo_en

			FROM
				player a

			JOIN player_quest_status b ON b.id_player = a.id
			JOIN player_flags pf ON pf.id_player = a.id
			LEFT JOIN player_titulo pt ON pt.id = a.id_titulo

			WHERE
				level >= 5  AND
				removido  = 0 AND
				banido = 0 AND
				id_vila=" . $rv['id']
		);

		while($rp = $qPlayers->row_array()) {
			$pontos  = $rp['level'] * 1000; // Level
			$pontos += $arGraduacoes[$rp['id_graduacao']]; // Gradua��o

			$pontos += $rp['vitorias_f'] * 60; // Vitorias Mapa PVP
			$pontos -= $rp['derrotas_f'] * 30; // Derrotas Mapa PVP

			$pontos += $rp['vitorias'] * 30; // Vitorias Dojo PVP
			$pontos -= $rp['derrotas'] * 15; // Derrotas Dojo PVP

			$pontos += $rp['vitorias_d'] * 5; // Vitorias Dojo NPC
			$pontos -= $rp['derrotas_npc'] * 5; // Vitorias Dojo NPC

			//$pontos += $rp['vitorias_rnd'] * 30; // Vitorias
			//$pontos -= $rp['derrotas_rnd'] * 15; // Derrotas

			//$pontos += $rp['empates'] * 25; // Vitorias dojo
			$pontos -= $rp['fugas'] 				* 50; // Fugas
			$pontos += $rp['treino_total'] 			/ 100; // Treino
			$pontos += $rp['vila_quest_vitorias'] 	* 1000; // Treino
			$pontos += $rp['vila_quest_derrotas'] 	* 100; // Treino
			$pontos += $rp['total_morto'] 			* 1000;
			$pontos += $rp['total_vivo'] 			* 250;			
			//$pontos += $rp['total_torneio_pvp'] 	* 1000;
			$pontos += $rp['total_torneio_npc'] 	* 500;

			$rq = $rp;

			$pontos += $rp['lvl_tarefa'] 			* 25;
			$pontos += $rp['lvl_d'] 				* 50;
			$pontos += $rp['lvl_c'] 				* 75;
			$pontos += $rp['lvl_b'] 				* 100;
			$pontos += $rp['lvl_a'] 				* 125;
			$pontos += $rp['lvl_s'] 				* 150;
			$pontos += $rp['guild_quest_feitas'] 	* 75;

			$pontos = absm($pontos);


			Recordset::query("INSERT INTO ranking(id_player, id_vila, pontos, level, id_classe, nome, quest_d, quest_c, quest_b, quest_a, quest_s, titulo_br,titulo_en, id_graduacao)
						 VALUES(" . $rp['id'] . ", " . $rv['id'] . ", " . $pontos . ",". $rp['level'] .",".$rp['id_classe'].",'". addslashes($rp['nome'])."',
						 " . (int)$rq['lvl_d'] .", " . (int)$rq['lvl_c'] . ", " . (int)$rq['lvl_b'] . ", " . (int)$rq['lvl_a'] . ", " . (int)$rq['lvl_s'] . ", '" . addslashes($rp['titulo_br']) . "', '" . addslashes($rp['titulo_en']) . "', " . $rp['id_graduacao'] . ")");
		}

		$qo = Recordset::query("SELECT pontos, id_player FROM ranking WHERE id_vila=" . $rv['id'] . " ORDER BY pontos DESC");

		$c = 0;
		while($ro = $qo->row_array()) {
			Recordset::query("UPDATE ranking SET posicao_vila=" . ++$c . " WHERE id_player=" . $ro['id_player']);

			if($c == 1) {
				Recordset::update('vila', array(
					'id_kage'	=> $ro['id_player']
				), array(
					'id'		=> $rv['id']
				));
			}
		}
	}

	$qPlayer = Recordset::query("SELECT id FROM ranking ORDER BY pontos DESC");
	$c = 0;

	while($r = $qPlayer->row_array()) {
		Recordset::query("UPDATE ranking SET posicao_geral=" . ++$c . " WHERE id=" . $r['id']);
	}
