<?php
	error_reporting(E_ALL ^ E_NOTICE);
	$use_master = true;
	
	require('_config.php');

	$insert_db = 'narutoga_prod';
	$select_db = 'narutoga_prod';
	
	//echo "+ INIT\n";
	//echo "+ WILL USE $insert_db FOR INSERTION GETTING DATA FROM -> $select_db\n";

	//Recordset::query('DELETE FROM historico_equipe');
	//Recordset::query('ALTER TABLE historico_equipe AUTO_INCREMENT=1');

	$equipes = Recordset::query('SELECT a.* FROM ' . $select_db . '.equipe a WHERE a.level > 5 AND a.removido=0');
	//echo "+ WILL DO " . $equipes->num_rows . "\n";
	
	while($p = $equipes->row_array()) {

		$ranking = Recordset::query('SELECT * FROM ' . $select_db . '.ranking_equipe as re JOIN equipe as e ON re.id_player = e.id_player  WHERE e.id_player=' . $p['id_player'])->row_array();

		$insert = array(
		  'id' 				=> 'NULL',
		  'id_vila'			=> (int)$ranking['id_vila'],
		  'id_equipe'		=> $p['id'],
		  'id_players'		=> 'NULL',
		  'round'			=> 29,
		  'premio'			=> 0,
		  'nome_equipe'		=> $p['nome'],
		  'level_equipe'	=> $p['level'],
		  'score_equipe'	=> (int)$ranking['pontos'],
		  'posicao_geral'	=> (int)$ranking['posicao_geral'],
		  'posicao_vila'	=> (int)$ranking['posicao_vila']
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

		$q = Recordset::query('INSERT INTO ' . $insert_db . '.historico_equipe VALUES (' . $insert . ')');
		$id	= $q->insert_id();
		
		$players = Recordset::query('SELECT * FROM ' . $select_db . '.player WHERE id_equipe=' . $p['id']);
		$players2 = [];
		
		while($p2 = $players->row_array()) {
			$players2[] = $p2['id']; 
		}

		Recordset::query('UPDATE historico_equipe SET id_players = "'. implode(',',$players2).'" WHERE id='. $id);
	}
