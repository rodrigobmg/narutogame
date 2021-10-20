<?php
	$redir_script = true;

	// Verifica se o item existe
	if(!$basePlayer->hasItem($_POST['id'])) {
		redirect_to('negado', NULL, array('e' => 1));
	}
	
	$item = $basePlayer->getItem($_POST['id']);
	
	// Verifica se o tipo é válido
	if(!in_array($item->getAttribute('id_tipo'), array(5, 24))) {
		redirect_to('negado', NULL, array('e' => 2));	
	}
	
	// Verifica se ja foi liberado(abas)
	if(!$item->getAttribute('level_liberado')) {
		if(!$basePlayer->pt_livre[$item->getAttribute('id_habilidade')]) {
			redirect_to('negado', NULL, array('e' => 4));
		}
		
		$item->setAttribute('level_liberado', 1);
		
		//Recordset::query('UPDATE player_item SET level_liberado=\'1\' WHERE id_player=' . $basePlayer->id . ' AND id_item=' . (int)$id);
		Recordset::query('UPDATE player SET total_pt_' . $item->getAttribute('campo_base') . '_gasto=total_pt_' . $item->getAttribute('campo_base') . '_gasto+1 WHERE id=' . $basePlayer->id);
		
		redirect_to('personagem_jutsu');
	} else {
		redirect_to('negado', NULL, array('e' => 3));
	}
