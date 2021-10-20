<?php
	require('_config.php');

	set_time_limit(0);

	Recordset::query('DELETE FROM ranking_estudo_ninja');
	Recordset::query('ALTER TABLE ranking_estudo_ninja AUTO_INCREMENT=1');
	
	$q = Recordset::query("
						select 
							   p.id, p.nome, p.id_vila, p.id_classe, p.level, f.estudo_ninja_pontos,
							   pt.titulo_br, pt.titulo_en
						from 
							 player as p     
						
						JOIN player_flags as f on p.id = f.id_player
						LEFT JOIN player_titulo pt ON pt.id = p.id_titulo
						WHERE p.level > 5 AND
							  p.removido = 0 AND
							  p.banido = 0 
						
						ORDER BY 5 desc;    
					");

	echo "BEGIN -> " . $q->num_rows . "\n";
	flush();

	while($r = $q->row_array()) {
			Recordset::query("
			INSERT INTO ranking_estudo_ninja(
				id_player,
				id_vila,
				level,
				id_classe,
				pontos_estudo_ninja,
				nome,
				titulo_br,
				titulo_en
			) VALUES(
				" . $r['id'] . ",
				" . $r['id_vila'] . ",
				" . $r['level'] . ",
				" . $r['id_classe'] . ",
				" . $r['estudo_ninja_pontos'] . ",
				'" . addslashes($r['nome']) . "',
				'" . addslashes($r['titulo_br']) . "',
				'" . addslashes($r['titulo_en']) . "'
			)");
	}

	echo "SORT STEP 1\n";
	flush();

	// Organiza os pontos -->
		$q = Recordset::query("SELECT id FROM ranking_estudo_ninja ORDER BY pontos_estudo_ninja DESC");
		
		$c = 0;
		while($r = $q->row_array()) {
			Recordset::query("UPDATE ranking_estudo_ninja SET posicao_geral=" . ++$c . " WHERE id=" . $r['id']);
		}
	// <---

	echo "SORT STEP 2\n";
	flush();

	// Organiza os pontos por vila --->
		$q = Recordset::query("SELECT id FROM vila");
		
		while($r = $q->row_array()) {
			$qq = Recordset::query("SELECT id FROM ranking_estudo_ninja WHERE id_vila=" . $r['id'] . " ORDER BY pontos_estudo_ninja DESC");
			
			$c = 0;
			while($rr = $qq->row_array()) {
				Recordset::query("UPDATE ranking_estudo_ninja SET posicao_vila=" . ++$c . " WHERE id=" . $rr['id']);
			}
		}
	// <---
