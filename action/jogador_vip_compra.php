<?php
	$id				= (int)decode($_POST[$_SESSION['vip_field_id']]);
	$nome			= $_POST[$_SESSION['vip_field_cnome']];
	$id_vila		= decode($_POST[$_SESSION['vip_field_cvila']]);
	$classe			= decode($_POST[$_SESSION['vip_field_cclasse']]);
	$classe_tipo	= decode($_POST[$_SESSION['vip_field_cclasse_tipo']]);
	$postkey		= $_POST[$_SESSION['vip_field_postkey']];
	$no_at_check	= false;
	
	if(!is_numeric($id)) {
		redirect_to("negado");
	}
	
	if($postkey != $_SESSION['vip_field_postkey_value']) {
		redirect_to("negado");
	}
	
	$i = new Item($id);
	
	
	$redir_script = true;
	
	$first_free	= [1024,1797,20205,1025,2018,1747];
	$zero_flags	= @unserialize(Player::getFlag('zero_coin_ids', $basePlayer->id));
	
	if(!$zero_flags) {
		$zero_flags = array();
	}

	if(in_array($i->id, $first_free) && !in_array($i->id, $zero_flags)) {
		$zero_flags[]	= $i->id;

		$i->setLocalAttribute('coin', 0);
	}
	
	//usa_coin($id, $i->getAttribute('coin'));

	if($basePlayer->getAttribute('coin') < $i->getAttribute('coin')) {
		redirect_to("jogador_vip", NULL, array("e" => 1));	
	}
	
	switch($id) {
		case 1007:
		case 1008:
		case 1009: // Tickets do hospital
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			
			break;
		
		case 1024: // Trocar de nome
			$nome = substr(preg_replace('/[^\w]/is', '', $nome), 0, 18);
			
			if(Recordset::query("SELECT id_player FROM player_nome WHERE nome='" . addslashes($nome) . "'")->num_rows) {
				redirect_to("jogador_vip", NULL, array("e" => 2));	
			} else {
				if(strlen($nome) > 14 || strlen($nome) < 3) {
					redirect_to("jogador_vip", NULL, array("e" => 9));
				}				
				
				gasta_coin($i->getAttribute('coin'),1024, $basePlayer->nome);
				Recordset::query("UPDATE player SET nome='" . addslashes($nome) . "' WHERE id=" . $basePlayer->id);
				Recordset::query("UPDATE player_nome SET nome='" . addslashes($nome) . "' WHERE id_player=" . $basePlayer->id);
			}
		
			break;			
		 case 1025: // Trocar de vila
			
			// Verifica se a vila é inicial 
			$vila_inicial	= Recordset::query("SELECT id FROM vila WHERE id=". $id_vila ." and inicial = '1'")->row_array();
			if(!$vila_inicial['id']){
				redirect_to("jogador_vip", NULL, array("e" => 98));
			}
			
			if($basePlayer->id_missao && $basePlayer->missao_equipe) {
				redirect_to("jogador_vip", NULL, array("e" => 3));
			}

			$village	= Recordset::query('SELECT id_cons_defesa, id_cons_vila, id_cons_guerra FROM vila WHERE id=' . $basePlayer->id_vila)->row_array();
			
			if($basePlayer == $village['id_cons_defesa'] || $basePlayer == $village['id_cons_vila'] || $basePlayer == $village['id_cons_guerra']) {
				redirect_to("jogador_vip", NULL, array("e" => 11));				
			}		 

		 	if($basePlayer->getAttribute('id_guild')) {
				redirect_to("jogador_vip", NULL, array("e" => 6));
			}
			
			if($basePlayer->level>10) {
				redirect_to("jogador_vip", NULL, array("e" => 10));
			}
		 
		 	if($basePlayer->getAttribute('id_equipe')) {
				$equipe = Recordset::query("SELECT * FROM equipe WHERE id=" . (int)$basePlayer->getAttribute('id_equipe'))->row_array();
				
				if($equipe['membros'] > 1) {
					redirect_to("jogador_vip", NULL, array("e" => 5));
				}

				//redirect_to("jogador_vip", NULL, array("e" => 5));
				
				/*
				if($basePlayer->dono_equipe) {
					Recordset::query("UPDATE player SET id_equipe=NULL WHERE id_equipe=" . $basePlayer->id_equipe . " AND id !=" . $basePlayer->id);
					Recordset::query("UPDATE equipe SET membros=1 WHERE id=" . $basePlayer->id_equipe);
				} else {
					Recordset::query("UPDATE equipe SET membros=membros-1 WHERE id=" . $basePlayer->id_equipe);
					Recordset::query("UPDATE player SET id_equipe=NULL WHERE id=" . $basePlayer->id);
				}
				*/
			}
			
			if($basePlayer->getAttribute('missao_invasao')) {
				redirect_to("jogador_vip", NULL, array("e" => 4));
			}

			Recordset::update('vila', ['id_kage' => 0], ['id_kage' => $basePlayer->id]);
			Recordset::update('vila', ['id_cons_guerra' => 0], ['id_cons_guerra' => $basePlayer->id]);
			Recordset::update('vila', ['id_cons_defesa' => 0], ['id_cons_defesa' => $basePlayer->id]);
			Recordset::update('vila', ['id_cons_vila' => 0], ['id_cons_vila' => $basePlayer->id]);

			gasta_coin($i->getAttribute('coin'),1025, $basePlayer->id_vila);
		 	Recordset::query("UPDATE player SET id_vila=$id_vila, id_vila_atual=$id_vila WHERE id=" . $basePlayer->id);
			
		 	break;
			
		case 1026: // Quest Helper
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
		case 1492918: // Range de movimentação no mapa
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
		case 1494334: // Estudo Ninja Extra
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
		case 1494335: // Respecar total as melhorias das vilas ( apenas Kages)
			
			// Recupero o level da vila
			$vila = Recordset::query("select id,nivel_vila, id_kage from vila WHERE id = ". $basePlayer->id_vila)->result_array();
			
			if($basePlayer->id <> $vila[0]['id_kage'] or $basePlayer->id_vila <> $vila[0]['id']) {
				redirect_to("jogador_vip", NULL, array("e" => 12));
			}
		
			
			// Deleto os itens aprendidos por outros kages
			Recordset::query("delete from vila_item WHERE vila_id = ". $basePlayer->id_vila);
		
			// Reseto as melhorias
			Recordset::query("update vila set nivel_ok = ". $vila[0]['nivel_vila']." WHERE id = ". $basePlayer->id_vila);
			
			gasta_coin($i->getAttribute('coin'),$id);
		
			break;	
		case 1492919: // Remove as travas dos jogadores de equipe
			Recordset::query("DELETE FROM player_expulso WHERE tipo='equipe' AND id_player=" . $basePlayer->id);
			gasta_coin($i->getAttribute('coin'),1492919);
			
			break;
		case 1492920: // Remove as travas dos jogadores de guild
			Recordset::query("DELETE FROM player_expulso WHERE tipo='guild' AND id_player=" . $basePlayer->id);
			gasta_coin($i->getAttribute('coin'),1492920);
			
			break;	
		case 1027: // Bingo Book
		case 1079:
		case 1080:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
		
		case 1014: // Troca de coin por ryou
			gasta_coin($i->getAttribute('coin'),1014);
			Recordset::query("UPDATE player SET ryou=ryou+1000 WHERE id=" . $basePlayer->id);
			
			break;
		
		case 1028: // Treino automatico
		case 1081:
		case 1082:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
		
		case 1083: // Extensor do treino
		case 1494:
		case 1495:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
		
		case 1747: // Redistribuir pontos
			if($basePlayer->treino_tempo_jutsu) {
				redirect_to("jogador_vip", NULL, array("e" => 8));
			}
		
			gasta_coin($i->getAttribute('coin'),1747);

			Recordset::update('player', [
				'treino_gasto'	=> 0,
				'ken'			=> 0,
				'tai'			=> 0,
				'nin'			=> 0,
				'gen'			=> 0,
				'agi'			=> 0,
				'con'			=> 0,
				'forc'			=> 0,
				'inte'			=> 0,
				'ene'			=> 0,
				'res'			=> 0,
				'conc'			=> 0,
				'esq'			=> 0,
				'conv'			=> 0,
				'det'			=> 0
			], [
				'id'	=> $basePlayer->id
			]);

			$basePlayer	= new Player($basePlayer->id);
 			
 			player_at_check();
 			
 			/*
 			$its = new Recordset('SELECT id_item FROM player_item WHERE level_liberado=\'1\' AND removido=\'0\' AND id_player=' . $basePlayer->id);
			Recordset::query('UPDATE player SET total_pt_nin_gasto=0, total_pt_gen_gasto=0, total_pt_tai_gasto=0 WHERE id=' . $basePlayer->id);
 			
 			foreach($its->result_array() as $it) {
 				$it = $basePlayer->getItem($it['id_item']); //new Item($it['id_item'], $basePlayer->id);
 			
				Recordset::query('UPDATE player SET total_pt_' . $it->campo_base . '_gasto=total_pt_' . $it->campo_base . '_gasto+1 WHERE id=' . $basePlayer->id);
 			}
 			*/
 			
			break;
		
		case 1797:
			if(Recordset::query("SELECT id FROM classe WHERE ativo=1 AND id=" . addslashes((int)$classe))->num_rows) {
				gasta_coin($i->getAttribute('coin'),1797);
				
				Recordset::query("UPDATE player SET id_classe=" . (int)$classe . " WHERE id=" . $basePlayer->id);
				Recordset::query("DELETE FROM player_imagem WHERE id_player=" . $basePlayer->id);
				
				// Nova Vantagem Vip
				if($basePlayer->hasItem(array(22953))):
					Recordset::query("INSERT INTO player_classe (id_usuario,id_player, id_classe) VALUES (". $_SESSION['usuario']['id'] .",". $basePlayer->id .",". (int)$classe .")");
				endif;
				
			} else {
				redirect_to("negado");
			}
		
			break;
			
		case 1860: // Extensor do treino de jutsus
		case 1861:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
			
		case 1862: // Limites de level do mapa
		case 1863:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
		
		case 1875: // Não levar punição
		case 1876:
		case 1877:
		case 1878:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;

		case 1867: // teletransporte
		case 1868:
		case 1869:
		case 1870:
		case 1871:
		case 1872:
		case 1873:
		case 1874:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;

		case 2019: // Bingo Book
		case 2020:
		case 2021:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
			
		case 2018: // Redistribuir arvore
			if($basePlayer->treino_tempo_jutsu) {
				redirect_to("jogador_vip", NULL, array("e" => 8));
			}

			gasta_coin($i->getAttribute('coin'),2018);
			
			//Recordset::query("DELETE FROM player_item WHERE id_item IN(SELECT id FROM item WHERE id_tipo=25) AND id_player=" . $basePlayer->id);
			$items = Recordset::query('SELECT a.*, b.preco FROM player_item a JOIN item b ON a.id_item=b.id AND b.id_tipo=25 WHERE a.id_player=' . $basePlayer->id);
			
			foreach($items->result_array() as $item) {
				Recordset::query('UPDATE player SET ryou=ryou+' . $item['preco'] . ' WHERE id=' . $basePlayer->id);
				Recordset::query('DELETE FROM player_item WHERE id=' . $item['id']);
			}
			
			Recordset::query("UPDATE player SET arvore_gasto=0 WHERE id=" . $basePlayer->id);
 		
			break;
		
		case 20205: // trocar classe
			$has_el	= $basePlayer->id_classe_tipo != 1 && sizeof($basePlayer->getElementos());
		
			if($has_el && $classe_tipo == 1) {
				redirect_to('negado');				
			}
		
			if(!(int)$classe_tipo) {
				redirect_to('negado');
			}
			
			$qClasse = new Recordset('SELECT * FROM classe_tipo WHERE id=' . (int)$classe_tipo);
			
			if(!$qClasse->num_rows) {
				redirect_to('negado');
			}

			gasta_coin($i->getAttribute('coin'),20205);
			
			Recordset::query('UPDATE player SET id_classe_tipo=' . $classe_tipo . ' WHERE id=' . $basePlayer->id);
			
			$basePlayer = new Player($basePlayer->id);
			
 			player_at_check();
 			
 			/*
 			$its = new Recordset('SELECT id_item FROM player_item_level WHERE level_liberado=\'1\' AND id_player=' . $basePlayer->id);
			Recordset::query('UPDATE player SET total_pt_nin_gasto=0, total_pt_gen_gasto=0, total_pt_tai_gasto=0 WHERE id=' . $basePlayer->id);
 			
 			foreach($its->result_array() as $it) {
 				$it = new Item($it['id_item'], $basePlayer->id);
 			
				Recordset::query('UPDATE player SET total_pt_' . $it->campo_base_t . '_gasto=total_pt_' . $it->campo_base_t . '_gasto+1 WHERE id=' . $basePlayer->id);
 			}*/
		
			break;
		
		case 20249: // Memoria ninja
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
		
		case 20265: // Quest helper de eventos
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;

		case 20290:
		case 20291:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
		
		case 20313:
		case 20314:
		case 20315:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
			
		case 20316: // redistribuir pontos treino
		case 20317:
		case 20318:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;

		case 21757: // radar bijuu
		case 21758:
		case 21759:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;

		case 21760:
		case 21761:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
			
		case 22953: // Memorizar Personagens
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
			
		case 20523: // Especialização equipe
			$exp = 0;
			
			for($f = 0; $f <= 6; $f++) {
				$current	= Player::getFlag('equipe_role_' . $f . '_lvl', $basePlayer->id);
				$lvl_exp	= array(
					'0'	=> 0,
					'1' => 2100,
					'2' => 4200,
					'3' => 6300,
					'4' => 8400,
					'5' => 14000
				);
			
				for($x = $current; $x >= 0; $x--) {
					$exp += $lvl_exp[$x];
				}

				$basePlayer->setFlag('equipe_role_' . $f . '_lvl', 0);
			}
			
			$basePlayer->setAttribute('exp_equipe_dia_total', $basePlayer->getAttribute('exp_equipe_dia_total') + $exp);
		
			gasta_coin($i->getAttribute('coin'),20523);
		
			break;

		case 20794: // Quest Helper diaria guild
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
			
		case 21365: // Ver elemento de Ninjas em Combate
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
			
		case 21387:
		
			Recordset::update('global.user', array(
				'vip_char_slots'	=> array('escape' => false, 'value' => 'vip_char_slots+1')
			), array(
				'id'				=> $_SESSION['usuario']['id']
			));
			
			gasta_coin($i->getAttribute('coin'),21387);
		
			break;
		case 21485: // Treino Estendido de Jutsus
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
		
		case 21770:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			unset($_SESSION['_vip_heal_check_' . $basePlayer->id]);
			
			break;
		
		case 21873:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			break;
		
		case 21879:
			if($basePlayer->hasItem(21879)) {
				gasta_coin($i->getAttribute('coin'),21879);				
			} else {
				$basePlayer->addItem($id, 1, 1);
				usa_coin($id, $i->getAttribute('coin'));				
			}

			Recordset::update('player_item', array(
				'uso'		=> array('escape' => false, 'value' => 'uso+' . $i->getAttribute('vezes_dia'))
			), array(
				'id_player'	=> $basePlayer->id,
				'id_item'	=> 21879
			));
		
			break;

		case 21880: // espionagem equipamento
		case 21881:
		case 21882:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));

			break;
		
		case 22650: // Enhancements
		case 22651:
		case 22652:
		case 22653:
		case 22654:
		case 22655:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			
			break;

		case 22780:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			
			break;

		case 22888:
		case 1494465:
			$basePlayer->addItem($id, 1, 1);
			usa_coin($id, $i->getAttribute('coin'));
			
			break;
	}

	// Conquista --->
		$itemVIP = new stdClass();
		$itemVIP->id = $id;
	
		arch_parse(NG_ARCH_ITEM_V, $basePlayer, NULL, $itemVIP, 1);
	// <---
	
	// Verificador de itens --->
		if(!$no_at_check) {
			player_at_check();
		}
	// <---
	// Missões diárias de Compra de Itens Vips
	if($basePlayer->hasMissaoDiariaPlayer(11)->total){
		// Adiciona os contadores nas missões de tempo.
		Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
					 WHERE id_player = ". $basePlayer->id." 
					 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 11) 
					 AND completo = 0 ");
}

	if(isset($_SESSION['_vip_heal_check_' . $basePlayer->id])) {
		unset($_SESSION['_vip_heal_check_' . $basePlayer->id]);
	}
	
	$basePlayer->setFlag('zero_coin_ids', serialize($zero_flags));
	redirect_to("jogador_vip", NULL, array("ok" => encode($id)));	
