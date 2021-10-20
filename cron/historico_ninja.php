<?php
	error_reporting(E_ALL ^ E_NOTICE);
	$use_master = true;
	
	require('_config.php');

	$insert_db = 'narutoga_prod';
	$select_db = 'narutoga_prod';
	
	echo "+ INIT\n";
	echo "+ WILL USE $insert_db FOR INSERTION GETTING DATA FROM -> $select_db\n";

	// Recordset::query('DELETE FROM historico_ninja');
	// Recordset::query('ALTER TABLE historico_ninja AUTO_INCREMENT=1');

	$players = Recordset::query('SELECT a.* FROM ' . $select_db . '.player a WHERE a.level > 5 AND a.removido=0 AND a.banido = 0');
	
	echo "+ WILL DO " . $players->num_rows . "\n";
	
	while($p = $players->row_array()) {
		
		$quest = Recordset::query('SELECT * FROM ' . $select_db . '.player_quest_status WHERE id_player=' . $p['id'])->row_array();
		$ranking = Recordset::query('SELECT * FROM ' . $select_db . '.ranking WHERE id_player=' . $p['id'])->row_array();
		
		$portao = Recordset::query('SELECT id FROM ' . $select_db . '.player_item WHERE id_player=' . $p['id'] . ' AND id_item IN(SELECT id FROM item WHERE id_tipo=17)')->num_rows ? 1 : 0;
		$sennin = Recordset::query('SELECT id FROM ' . $select_db . '.player_item WHERE id_player=' . $p['id'] . ' AND id_item IN(SELECT id FROM item WHERE id_tipo=26)')->num_rows ? 1 : 0;
		
		$elementos = array();
		$qElementos = Recordset::query('SELECT * FROM ' . $select_db . '.player_elemento WHERE id_player=' . $p['id']);
		
		while($r = $qElementos->row_array()) {
			$elementos[] = $r['id_elemento'];
		}
		
		$insert = array(
		  'id' 				=> 'NULL',
		  'id_usuario'		=> $p['id_usuario'],
		  'round'			=> 29,
		  'premio'			=> 0,
		  'id_player'		=> $p['id'],
		  'id_graduacao'	=> (int)$p['id_graduacao'],
		  'id_vila'			=> (int)$p['id_vila'],
		  'id_classe'		=> (int)$p['id_classe'],
		  'id_cla'			=> (int)$p['id_cla'],
		  'id_invocacao'	=> (int)$p['id_invocacao'],
		  'id_selo'			=> (int)$p['id_selo'],
		  'id_portao'		=> $portao,
		  'id_sennin'		=> $sennin,
		  'level'			=> $p['level'],
		  'nome'			=> $p['nome'],
		  'score'			=> (int)$ranking['pontos'],
		  'posicao_geral'	=> (int)$ranking['posicao_geral'],
		  'posicao_vila'	=> (int)$ranking['posicao_vila'],
		  'quest_d'			=> (int)$quest['quest_d'],
		  'quest_c'			=> (int)$quest['quest_c'],
		  'quest_b'			=> (int)$quest['quest_b'],
		  'quest_a'			=> (int)$quest['quest_a'],
		  'quest_s'			=> (int)$quest['quest_s'],
		  'vitorias_pvp'	=> (int)$p['vitorias'] + (int)$p['vitorias_f'],
		  'vitorias_npc'	=> (int)$p['vitorias_d'],
		  'fugas'			=> (int)$p['fugas'],
		  'derrotas'		=> (int)$p['derrotas'],
		  'derrotas_npc'	=> (int)$p['derrotas_npc'],
		  'empates'			=> (int)$p['empates'],
		  'treino_total'	=> (int)$p['treino_total'],
		  'elemento1'		=> (int)$elementos[0],
		  'elemento2'		=> (int)$elementos[1]
		);

		$tmp_insert = $insert;
		$insert = array();
		
		foreach($tmp_insert as $k => $v) {
			if(is_string($v) && $v == "NULL") {
				$insert[$k] = 'NULL';
			} else {
				$insert[$k] = '\'' . addslashes($v) . '\'';
			}
		}
		
		$insert = implode(',', $insert);

		Recordset::query('INSERT INTO ' . $insert_db . '.historico_ninja VALUES (' . $insert . ')');
	}

	echo "+ END\n";
