<?php
	$redir_script	= true;	
	$id				= $id_evento4 = decode($_POST['id']);
	
	if(!is_numeric($id)) {
		redirect_to("negado");		
	}
	
	if(!$basePlayer->getAttribute('dono_equipe')) {
		redirect_to("negado");
	}
	
	if($basePlayer->id_evento4) {
		redirect_to("negado");
	}

	$qEvento	= Recordset::query("SELECT * FROM evento4 WHERE id=" . (int)$id, true);
	$equipe		= Recordset::query('SELECT * FROM equipe WHERE id=' . $basePlayer->getAttribute('id_equipe'))->row_array();

	// Verifica se é um evento válido
	if(!$qEvento->num_rows) {
		redirect_to("negado", NULL, array("e" => 1));
	}
	
	$rEvento = $qEvento->row_array();

	// Verifica se o evento especificado ja foi concluido -->
		$ja_concluido			= Recordset::query('SELECT * FROM equipe_evento4 WHERE id_equipe=' . $basePlayer->getAttribute('id_equipe') . ' AND id_evento4=' . $rEvento['id'])->num_rows;
	
		if(!( $basePlayer->dono_equipe && $equipe['level'] >= $rEvento['req_equipe_level'] && !$basePlayer->getAttribute('id_evento4') && !$ja_concluido)) {
			redirect_to("negado", NULL, array("e" => 2));
		}
	// <---


	$equipe_status = get_equipe_status($basePlayer->id_equipe);
	$msg = "";
	
	if($equipe_status['players'] != 4) {
		die('jalert("'.t('actions.a143').'", null, function () { location.reload() })');
	}
	
	for($f = 0; $f <= 3; $f++) {
		if(!$equipe_status[$f]['activity']) {
			$msg .= "- ".t('actions.a144')." " . addslashes($equipe_status[$f]['name']) .' '. t('actions.a145')."\n";
		}

		if($equipe_status[$f]['battle']) {
			$msg .= "- ".t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a146')."\n";
		}

		if($equipe_status[$f]['quest']) {
			$msg .= "- ".t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a147')."\n";
		}

		if($equipe_status[$f]['nomap']) {
			$msg .= "- ".t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a148')."\n";
		}

		if($equipe_status[$f]['hospital']) {
			$msg .= "- ".t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a150')."\n";
		}

		if($equipe_status[$f]['tjutsu']) {
			$msg .= "- ".t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a151')."\n";
		}

		if($equipe_status[$f]['train']) {
			$msg .= "- ".t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a152')."\n";
		}
		
		$ips[] = $equipe_status[$f]['ip'];
	}
	
	if(sizeof(array_unique($ips)) < 4) {
		$msg .= t('actions.a153');
	}
	
	if($msg && !$_SESSION['universal']) {
		die('alert("'.t('actions.a154').':\n\n' . preg_replace("/[\n]/s", "\\n", $msg) . '"); location.reload()');
	}

	for($f = 0; $f <= 3; $f++) {
		$p = new Player($equipe_status[$f]['id']);
		$p->clearModifiers();
	}
	
	switch($rEvento['dificuldade']) {
		case 'normal':
			$ai_level = 0;
		
			break;

		case 'hard':
			$ai_level = 1;
		
			break;

		case 'ogro':
			$ai_level = 2;
		
			break;
	}
	
	$TAI = 0;
	$KEN = 0;
	$NIN = 0;
	$GEN = 0;
	$AGI = 0;
	$ENE = 0;
	$CON = 0;
	$RES = 0;
	$INT = 0;
	$FOR = 0;
	
	$max_grad	= 0;
	
	// Faz somatoria dos atribustos dos players fdps --->
		$players = new Recordset('SELECT id FROM player WHERE id_equipe=' . $basePlayer->getAttribute('id_equipe'));
		
		foreach($players->result_array() as $player) {
			$p = new Player($player['id']);
			
			$TAI += $p->getAttribute('tai_calc');
			$KEN += $p->getAttribute('ken_calc');
			$NIN += $p->getAttribute('nin_calc');
			$GEN += $p->getAttribute('gen_calc');
			$AGI += $p->getAttribute('agi_calc');
			$ENE += $p->getAttribute('ene_calc');
			$CON += $p->getAttribute('con_calc');
			$RES += $p->getAttribute('res_calc');
			$INT += $p->getAttribute('int_calc');
			$FOR += $p->getAttribute('for_calc');
			
			if($p->id_graduacao > $max_grad) {
				$max_grad = $p->id_graduacao;
			}
			
			if( $p->hp < ($p->max_hp / 2) || $p->sp < ($p->max_sp / 2) || $p->sta < ($p->max_sta / 2) ) {
				die('alert("'.t('actions.a155').'"); location.reload()');
			}
			
			/*if( $p->id_vila_atual >= 9 ) {
				die('jalert("'.t('actions.a156').'")');
			}*/

			if( !$p->id_vila_atual ) {
				die('jalert("'.t('actions.a157').'")');
			}
		}

		foreach($players->result_array() as $player) {
			Fight::cleanup($player['id']);
		}
	// <---

	Recordset::query('UPDATE equipe SET id_evento4=' . $rEvento['id'] . ', id_evento4b=' . $rEvento['id'] . ' WHERE id=' . $basePlayer->getAttribute('id_equipe'));

	// Modificadores para os npcs ogros --->
		$porta = Recordset::query('SELECT id FROM item WHERE id_tipo=20', true)->result_array();
		$selos = Recordset::query('SELECT id FROM item WHERE id_tipo=20', true)->result_array();
		$invoc = Recordset::query('SELECT id FROM item WHERE id_tipo=21', true)->result_array();
		$clas  = Recordset::query('SELECT id FROM item WHERE id_tipo=16', true)->result_array();
	
		// Sennin --->
			$invos  = Recordset::query('SELECT id FROM item WHERE id_tipo=21 AND id_invocacao=1', true)->result_array();
			$sennin = Recordset::query('SELECT id FROM item WHERE id_tipo=21 AND id_invocacao=1', true)->result_array();
		// <---	
	// <---
	
	switch($rEvento['tipo']) {
		case '4x1': // 4x1 NPC
			$npc		= new NPC(NULL, $basePlayer, NPC_EVENTO4);
			$npc_data	= Recordset::query('SELECT * FROM evento4_npc WHERE id_evento4=' . $rEvento['id'], true)->row_array();
			$npc_gid	= md5(rand(1, 512384) . date('YmdHis'));

			$TAI = $npc->getAttribute('tai_calc') * 6;
			$KEN = $npc->getAttribute('ken_calc') * 6;
			$NIN = $npc->getAttribute('nin_calc') * 6;
			$GEN = $npc->getAttribute('gen_calc') * 6;
			$AGI = floor($npc->getAttribute('agi_calc') * 1.5);
			$CON = floor($npc->getAttribute('con_calc') * 1.5);
			$ENE = floor($npc->getAttribute('ene_calc') * 2.5);
			$INT = floor($npc->getAttribute('int_calc') * 1.5);
			$FOR = floor($npc->getAttribute('for_calc') * 1.5);
			$RES = floor($npc->getAttribute('res_calc') * 1.5);
			
			switch($ai_level) {
				case 1: // HARD
					$TAI += percent(25, $TAI);
					$KEN += percent(25, $KEN);
					$NIN += percent(25, $NIN);
					$GEN += percent(25, $GEN);
					$AGI += percent(25, $AGI);
					$CON += percent(25, $CON);
					$ENE += percent(25, $ENE);
					$RES += percent(25, $RES);
					$INT += percent(15, $INT);
					$FOR += percent(15, $FOR);
				
					break;
				
				case 2: // OGRO
					$TAI += percent(40,  $TAI);
					$KEN += percent(40, $KEN);
					$NIN += percent(40,  $NIN);
					$GEN += percent(40,  $GEN);
					$AGI += percent(40,  $AGI);
					$CON += percent(40,  $CON);
					$ENE += percent(40,  $ENE);
					$RES += percent(40,  $RES);
					$INT += percent(30,  $INT);
					$FOR += percent(30,  $FOR);
					
					/*
					switch(rand(0, 2)) {
						case 0: // NPC com portão + invocacao + selo
						
							Player::addModifier($porta[rand(0, sizeof($porta) - 1)]['id'], 1, 0, $npc1);
							Player::addModifier($invoc[rand(0, sizeof($invoc) - 1)]['id'], 1, 0, $npc1);
							Player::addModifier($selos[rand(0, sizeof($selos) - 1)]['id'], 1, 0, $npc1);
							
							break;
						
						case 1: // NPC com cla + invocação + selo

							Player::addModifier($clas[rand( 0, sizeof($clas)  - 1)]['id'], 1, 0, $npc1);
							Player::addModifier($invoc[rand(0, sizeof($invoc) - 1)]['id'], 1, 0, $npc1);
							Player::addModifier($selos[rand(0, sizeof($selos) - 1)]['id'], 1, 0, $npc1);
						
							break;
						
						case 2: // NPC Sennin
							Player::addModifier($invos[rand( 0, sizeof($invos)  - 1)]['id'], 1, 0, $npc1);
							Player::addModifier($sennin[rand(0, sizeof($sennin) - 1)]['id'], 1, 0, $npc1);
						
							break;
					}
					*/
				
					break;
			}

			$batalha = Recordset::insert('batalha_multi', array(
				'id_tipo'	=> 1,
				'p1'		=> $equipe_status[0]['id'],
				'p2'		=> $equipe_status[1]['id'],
				'p3'		=> $equipe_status[2]['id'],
				'p4'		=> $equipe_status[3]['id']
			));

			$npc->id				= $npc_gid;
			$npc->batalha_multi_pos = 1;
			$npc->batalha_multi_id	= $batalha;
			
			$npc->setLocalAttribute('tai_raw', $TAI);
			$npc->setLocalAttribute('ken_raw', $KEN);
			$npc->setLocalAttribute('nin_raw', $NIN);
			$npc->setLocalAttribute('gen_raw', $GEN);
			$npc->setLocalAttribute('agi_raw', $AGI);
			$npc->setLocalAttribute('con_raw', $CON);
			$npc->setLocalAttribute('ene_raw', $ENE);
			$npc->setLocalAttribute('res_raw', $RES);
			$npc->setLocalAttribute('int_raw', $INT);
			$npc->setLocalAttribute('for_raw', $FOR);
			
			$npc->setLocalAttribute('nome',   $npc_data['nome']);
			$npc->setLocalAttribute('imagem', $npc_data['imagem']);

			$npc->atCalc();
			
			Recordset::update('batalha_multi', array(
				'npc1'	=> gzserialize($npc)
			), array(
				'id'	=> $batalha
			));
			
			Recordset::update('player', array(
				'id_batalha_multi'	=> $batalha
			), array(
				'id_equipe'			=> $basePlayer->id_equipe
			));
			
			break;
		
		case '4x4': // 4x4 NPC
			$npcs = array();

			$qNPC 		= new Recordset('SELECT * FROM evento4_npc WHERE id_evento4=' . $rEvento['id'], true);
			$npcs_data	= Recordset::query('SELECT * FROM evento4_npc WHERE id_evento4=' . $rEvento['id'], true);
			$batalha 	= Recordset::insert('batalha_multi', array(
				'id_tipo'	=> 2,
				'p1'		=> $equipe_status[0]['id'],
				'p2'		=> $equipe_status[1]['id'],
				'p3'		=> $equipe_status[2]['id'],
				'p4'		=> $equipe_status[3]['id']
			));

			foreach($qNPC->result_array() as $f => $rNPC) {
				$tp_npc		= rand(1,3);
				$npc_gid	= md5(rand(1, 512384) . date('YmdHis'));
				$npc_data	= $npcs_data->row_array();
				$npcs[$f]	= new NPC(NULL, $basePlayer, NPC_EVENTO4);

				$TAI = $npcs[$f]->getAttribute('tai_calc') * 2;
				$KEN = $npcs[$f]->getAttribute('ken_calc') * 2;
				$NIN = $npcs[$f]->getAttribute('nin_calc') * 2;
				$GEN = $npcs[$f]->getAttribute('gen_calc') * 2;
				$AGI = floor($npcs[$f]->getAttribute('agi_calc') * 1.5);
				$CON = floor($npcs[$f]->getAttribute('con_calc') * 1.5);
				$INT = floor($npcs[$f]->getAttribute('int_calc') * 1.5);
				$FOR = floor($npcs[$f]->getAttribute('for_calc') * 1.5);

				$ENE = $npcs[$f]->getAttribute('ene_calc');
				$RES = $npcs[$f]->getAttribute('res_calc');

				switch($ai_level) {
					case 1: // HARD
						$TAI += percent(25, $TAI);
						$KEN += percent(25, $KEN);
						$NIN += percent(25, $NIN);
						$GEN += percent(25, $GEN);					
						$AGI += percent(25, $AGI);
						$CON += percent(25, $CON);
						$INT += percent(25, $INT);
						$FOR += percent(25, $FOR);

						$ENE += percent(25, $ENE);
						$RES += percent(25, $RES);
					
						break;
					
					case 2: // OGRO
						$TAI += percent(50, $TAI);
						$KEN += percent(50, $KEN);
						$NIN += percent(50, $NIN);
						$GEN += percent(50, $GEN);					
						$AGI += percent(50, $AGI);
						$CON += percent(50, $CON);
						$INT += percent(50, $INT);
						$FOR += percent(50, $FOR);
	
						$ENE += percent(25, $ENE);
						$RES += percent(50, $RES);
					
						break;
				}

				$npcs[$f]->gid					= $npc_gid;
				$npcs[$f]->batalha_multi_pos	= $f;
				$npcs[$f]->batalha_multi_id		= $batalha;

				$npcs[$f]->setLocalAttribute('tai_raw', $TAI);
				$npcs[$f]->setLocalAttribute('ken_raw', $KEN);
				$npcs[$f]->setLocalAttribute('nin_raw', $NIN);
				$npcs[$f]->setLocalAttribute('gen_raw', $GEN);
				$npcs[$f]->setLocalAttribute('agi_raw', $AGI);
				$npcs[$f]->setLocalAttribute('con_raw', $CON);
				$npcs[$f]->setLocalAttribute('ene_raw', $ENE);
				$npcs[$f]->setLocalAttribute('res_raw', $RES);
				$npcs[$f]->setLocalAttribute('int_raw', $INT);
				$npcs[$f]->setLocalAttribute('for_raw', $FOR);
				
				$npcs[$f]->setLocalAttribute('nome',   $npc_data['nome']);
				$npcs[$f]->setLocalAttribute('imagem', $npc_data['imagem']);
	
				$npcs[$f]->atCalc();
			}

			Recordset::update('batalha_multi', array(
				'npc1'		=> gzserialize($npcs[0]),
				'npc2'		=> gzserialize($npcs[1]),
				'npc3'		=> gzserialize($npcs[2]),
				'npc4'		=> gzserialize($npcs[3])
			), array(
				'id'		=> $batalha
			));
			
			Recordset::update('player', array(
				'id_batalha_multi'	=> $batalha
			), array(
				'id_equipe'			=> $basePlayer->id_equipe
			));
			
			/*
			$q = Recordset::query('INSERT INTO batalha_multi(id_tipo, p1, p2, p3, p4, npc1, npc2, npc3, npc4) VALUES(
				2, ' . $equipe_status[0]['id'] . ', ' . $equipe_status[1]['id'] . ', ' . $equipe_status[2]['id'] . ', ' . $equipe_status[3]['id'] . ',
				"' . addslashes(serialize($npcs[0])) . '", "' . addslashes(serialize($npcs[1])) . '", "' . addslashes(serialize($npcs[2])) . '", "' . addslashes(serialize($npcs[3])) . '"
			)');
			
			$id = $q->insert_id();
			
			Recordset::query('UPDATE player SET id_batalha_multi=' . $id . ' WHERE id_equipe=' . $basePlayer->id_equipe);
			*/
		
			break;		
	}
	
	//Recordset::query('UPDATE equipe SET id_evento4=' . $id_evento4 . ' WHERE id=' . $basePlayer->id_equipe);
	
	redirect_to('dojo_batalha_multi');	
