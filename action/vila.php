<?php
	$redir_script	= true;
	$rank			= Recordset::query("SELECT * FROM ranking WHERE id_player=" . $basePlayer->id)->row_array();
	$vila			= Recordset::query('SELECT * FROM vila WHERE id=' . $basePlayer->id_vila)->row_array();

	if($rank['posicao_vila'] != 1) {
		redirect_to('negado', NULL, array('e' => 1));	
	}
	
	if(!$vila['nivel_ok']) {
		redirect_to('negado', NULL, array('e' => 2));
	}
	
	if(!isset($_POST['item']) || (isset($_POST['item']) && !is_numeric($_POST['item']))) {
		redirect_to('negado', NULL, array('e' => 3));
	}
	
	$item	= Recordset::query('SElECT * FROM item WHERE id_tipo=33 AND id=' . $_POST['item'], true);
	
	if(!$item->num_rows) {
		redirect_to('negado', NULL, array('e' => 4));
	}
	
	if(Recordset::query('SELECT id FROM vila_item WHERE vila_id=' . $vila['id'] . ' AND item_id=' . $_POST['item'])->num_rows) {
		redirect_to('negado', NULL, array('e' => 5));		
	}
	
	$item	= $item->row_array();
	
	if(Vila::hasRequirements($vila, $item)) {
		Recordset::insert('vila_item', array(
			'vila_id'		=> $basePlayer->id_vila,
			'item_id'		=> $item['id'],
			'created_at'	=> array('escape' => false, 'value' => 'NOW()'),
			'player_id'		=> $basePlayer->id
		));
		
		Recordset::update('vila', array(
			'nivel_ok'	=> array('escape' => false, 'value' => 'nivel_ok-1')
		), array(
			'id'		=> $basePlayer->id_vila
		));
		
		redirect_to('vila', NULL, array('ok' => 1));
	} else {
		redirect_to('negado', NULL, array('e' => 6));
	}
