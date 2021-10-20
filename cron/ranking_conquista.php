<?php
	require('_config.php');

	set_time_limit(0);

	Recordset::query('DELETE FROM ranking_conquista');
	Recordset::query('ALTER TABLE ranking_conquista AUTO_INCREMENT=1');

	$q = Recordset::query("
		SELECT 
			a.id, a.nome, a.id_vila, a.level, a.id_classe, pt.titulo_br, pt.titulo_en
			FROM player a
			LEFT JOIN player_titulo pt ON pt.id = a.id_titulo
			WHERE a.level >= 5 AND a.removido=0 AND a.banido = 0
	");

	echo "BEGIN -> " . $q->num_rows . "\n";
	flush();

	$c = 0;

	//foreach($q->result_array() as $r) {
	while($r = $q->row_array()) {
		$qc	= Recordset::query("SELECT SUM(pontos) AS total FROM conquista_grupo WHERE id IN(SELECT id_conquista_grupo FROM conquista_grupo_item WHERE id_player=" . $r['id'] . ")");
		$rc = $qc->row_array();
		
		if($rc['total']) {
			/*
			Recordset::insert('ranking_conquista', array(
				'id_player'	=> $r['id'],
				'id_vila'	=> $r['id_vila'],
				'level'		=> $r['level'],
				'id_classe'	=> $r['id_classe'],
				'pontos'	=> $rc['total'],
				'nome'		=> $r['nome'],
				'titulo_br'	=> $r['titulo_br'],
				'titulo_en'	=> $r['titulo_en']
			));
			*/

			Recordset::query('
			INSERT INTO ranking_conquista(
				id_player,
				id_vila,
				level,
				id_classe,
				pontos,
				nome,
				titulo_br,
				titulo_en
			) VALUES(
				'.$r['id'].',
				'.$r['id_vila'].',
				'.$r['level'].',
				'.$r['id_classe'].',
				'.$rc['total'].',
				"'.addslashes($r['nome']).'",
				"'.addslashes($r['titulo_br']).'",
				"'.addslashes($r['titulo_en']).'"
			)');
		}

		if($c++ == 1000) {
			echo memory_get_usage() / 1024 . "Kb\n";
			$c = 0;
		}
	}

	echo "SORT STEP 1\n";
	flush();

	// Organiza os pontos -->
		$q = Recordset::query("SELECT id FROM ranking_conquista ORDER BY pontos DESC");
		
		$c = 0;
		while($r = $q->row_array()) {
			Recordset::query("UPDATE ranking_conquista SET posicao_geral=" . ++$c . " WHERE id=" . $r['id']);
		}
	// <---

	echo "SORT STEP 2\n";
	flush();

	// Organiza os pontos por vila --->
		$q = Recordset::query("SELECT id FROM vila");
		
		while($r = $q->row_array()) {
			$qq = Recordset::query("SELECT id FROM ranking_conquista WHERE id_vila=" . $r['id'] . " ORDER BY pontos DESC");
			
			$c = 0;
			while($rr = $qq->row_array()) {
				Recordset::query("UPDATE ranking_conquista SET posicao_vila=" . ++$c . " WHERE id=" . $rr['id']);
			}			
		}
	// <---
