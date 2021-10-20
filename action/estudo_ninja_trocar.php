<?php
	$basePlayer = new Player($basePlayer->id);
	
	$points_wasted = Player::getFlag('estudo_ninja_pontos_gasto', $basePlayer->id);
	$points_avail = Player::getFlag('estudo_ninja_pontos', $basePlayer->id) - $points_wasted;

	if(!$_SESSION['fk_estudo_ninja']) {
		redirect_to('negado', NULL, array('e' => 1));
	}

	if($_POST['key'] != $_SESSION['fk_estudo_ninja']) {
		redirect_to('negado', NULL, array('e' => 2));
	}
	
	if(!is_numeric($_POST['item'])) {
		redirect_to('negado', NULL, array('e' => 3));
	}
	
	$item = new Recordset('SELECT* FROM estudo_ninja WHERE id=' . (int)$_POST['item']);
	
	if($item->num_rows) {
		$item = $item->row_array();
		
		if($item['pontos'] > $points_avail) {
			redirect_to('negado', NULL, array('e' => 4));
		}
		
		if($item['coin'] && $basePlayer->getUFlag('estudo_ninja_troca_' . $item['id'])) {
			redirect_to('negado', NULL, array('e' => 5));		
		}
		
		// Itens
		if($item['id_item']) {
			if($basePlayer->hasItem($item['id_item'])) {
				Recordset::query("UPDATE player_item SET qtd=qtd + " . $item['mul'] . " WHERE id_player={$basePlayer->id} AND id_item=" . $item['id_item']);
			} else {
				Recordset::query("INSERT INTO player_item(id_item, id_player, qtd) VALUES(
					{$item['id_item']}, {$basePlayer->id}, {$item['mul']}
				)");
			}
		}
		
		// Ryou
		if($item['ryou']) {
			Recordset::query("UPDATE player SET ryou=ryou + " .$item['ryou'] . " WHERE id=" . $basePlayer->id);
		}
	
		// Exp
		if($item['exp']) {
			Recordset::query("UPDATE player SET exp=exp + " .$item['exp'] . " WHERE id=" . $basePlayer->id);
		}
	
		// Atributos do player --->
		// Ene
		if($item['ene']) {
			Recordset::query("UPDATE player_atributos SET ene=ene + " .$item['ene'] . " WHERE id_player=" . $basePlayer->id);
		}
	
		// Int
		if($item['inte']) {
			Recordset::query("UPDATE player_atributos SET inte=inte + " .$item['inte'] . " WHERE id_player=" . $basePlayer->id);
		}
	
		// For
		if($item['forc']) {
			Recordset::query("UPDATE player_atributos SET forc=forc + " .$item['forc'] . " WHERE id_player=" . $basePlayer->id);
		}
	
		// Agi
		if($item['agi']) {
			Recordset::query("UPDATE player_atributos SET agi=agi + " .$item['agi'] . " WHERE id_player=" . $basePlayer->id);
		}
	
		// Con
		if($item['con']) {
			Recordset::query("UPDATE player_atributos SET con=con + " .$item['con'] . " WHERE id_player=" . $basePlayer->id);
		}
	
		// Tai
		if($item['tai']) {
			Recordset::query("UPDATE player_atributos SET tai=tai + " .$item['tai'] . " WHERE id_player=" . $basePlayer->id);
		}
		// KEN
		if($item['ken']) {
			Recordset::query("UPDATE player_atributos SET ken=ken + " .$item['ken'] . " WHERE id_player=" . $basePlayer->id);
		}
		
		// Res
		if($item['res']) {
			Recordset::query("UPDATE player_atributos SET res=res + " .$item['res'] . " WHERE id_player=" . $basePlayer->id);
		}
		
		// Nin
		if($item['nin']) {
			Recordset::query("UPDATE player_atributos SET nin=nin + " .$item['nin'] . " WHERE id_player=" . $basePlayer->id);
		}
	
		// Gen
		if($item['gen']) {
			Recordset::query("UPDATE player_atributos SET gen=gen + " .$item['gen'] . " WHERE id_player=" . $basePlayer->id);
		}
	
		// Treino
		if($item['treino']) {
			Recordset::query("UPDATE player SET treino_total=treino_total + " .$item['treino'] . " WHERE id=" . $basePlayer->id);
		}
		// <---
		
		// Coin
		if($item['coin']) {
			Recordset::query("UPDATE global.user SET coin=coin+ " . $item['coin'] . " WHERE id=" . $_SESSION['usuario']['id']);
			
			$basePlayer->setUFlag('estudo_ninja_troca_'. $item['id'], 1);
		}
		
		// pontos bijuu
		if($item['bijuu']) {
			$basePlayer->setFlag('sorte_bijuu', Player::getFlag('sorte_bijuu', $basePlayer->id) + $item['bijuu']);
		}
		// Recompensa log
		Recordset::insert('player_recompensa_log', array(
			'fonte'		=> 'estudo_ninja',
			'id_player'	=> $basePlayer->id,
			'exp'		=> $item['exp'],
			'tai'		=> $item['tai'],
			'ken'		=> $item['ken'],
			'nin'		=> $item['nin'],
			'gen'		=> $item['gen'],
			'ene'		=> $item['ene'],
			'forc'		=> $item['forc'],
			'inte'		=> $item['inte'],
			'agi'		=> $item['agi'],
			'res'		=> $item['res'],
			'con'		=> $item['con'],
			'coin'		=> $item['coin'],
			'treino'	=> $item['treino'],
			'id_item'	=> $item['id_item'],
			'recebido'	=> 1
		));
		$_SESSION['estudo_ninja_msg'] = $item['id'];
		$basePlayer->setFlag('estudo_ninja_pontos_gasto', ($points_wasted + $item['pontos']));
	} else {
		redirect_to('negado', NULL, array('e' => 5));
	}
	
	redirect_to('estudo_ninja');
