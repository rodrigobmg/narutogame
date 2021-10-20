<?php
	if(!isset($_POST['id']) || (isset($_POST['id']) && !is_numeric($_POST['id']))) {
		redirect_to("negado");
	}

	$item 			= new Item($_POST['id']);	
	$ignored_ats	= [
		'req_level'	=> 1,
		'req_con'	=> 1,
		'req_tai'	=> 1,
		'req_agi'	=> 1,
		'req_for'	=> 1,
		'req_int'	=> 1
	];
	
	if($item->getAttribute('id_tipo') != 25) {
		redirect_to("negado");		
	}
	
	if(!$item->hasRequirement($item, $basePlayer, NULL, $ignored_ats)) {
		redirect_to("negado");
	} else {
		if ($basePlayer->hasItem($item->id)) {
			redirect_to("negado");
		}

		if($item->getAttribute('coin')) {
			$basePlayer->addItem($_POST['id'], 1, 1);
		} else {
			$basePlayer->addItem($_POST['id'], 1, 0);			
		}
		
		// Conquista --->
		arch_parse(NG_ARCH_ITEM_N, $basePlayer, NULL, $item, 1);
		// <---

		// Aprendendo nível
		if($item->getAttribute('arvore_pai')) {
			Recordset::query("UPDATE player_item SET equipado=0 WHERE id_player=" . $basePlayer->id . " AND id_item=" . $item->getAttribute('arvore_pai'));
		}
		
		$basePlayer->resetJutsu();
		
		Recordset::query("UPDATE player SET arvore_gasto=arvore_gasto+1 WHERE id=" . $basePlayer->id);
		
		redirect_to("arvore_talento");
	}
