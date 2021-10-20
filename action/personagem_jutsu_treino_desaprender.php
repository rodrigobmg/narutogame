<?php
	$redir_script = true;

	if(!$basePlayer->hasItem($_POST['id'])) {
		redirect_to('negado', NULL, array('e' => 1));
	}
	
	if($basePlayer->getAttribute('coin')< 2) {
		redirect_to('negado', NULL, array('e' => 2));
	}
	
	$item = $basePlayer->getItem($_POST['id']);
	
	if($item->getAttribute('level_liberado')) {
		$item->setAttribute('level_liberado', 0);
		
		Recordset::query('UPDATE player SET total_pt_' . $item->campo_base_t . '_gasto=total_pt_' . $item->campo_base_t . '_gasto-1 WHERE id=' . $basePlayer->id);
		
		gasta_coin(2);
		
		redirect_to('personagem_jutsu');
	} else {
		redirect_to('negado');
	}
