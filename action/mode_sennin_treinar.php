 <?php
	$redir_script = true;

	if(!isset($_POST['id']) || (isset($_POST['id']) && !is_numeric($_POST['id']))) {
		redirect_to("negado", NULL, array('e' => 1));
	}

	$id		= $_POST['id'];
	$item 	= new Item($id);
	$reqs	= Item::hasRequirement($item, $basePlayer);
	
	if($item->getAttribute('id_tipo') != 26) {
		redirect_to('negado', NULL, array('e' => 3));
	}
	
	if($basePlayer->hasItem($item->id)) {
		redirect_to('mode_sennin', NULL, array('existent' => 1));
	}
	
	if($reqs) {
		// Desativa os itens de clã anteriores
		Recordset::update('player_item', array(
			'ativo' => '0'
		), array(
			'id_item_tipo'	=> 26,
			'id_player'		=> $basePlayer->id
		));
		
		// Adiciona o novo item
		$basePlayer->addItemW($item->id);

		// Marca o novo item como ativo
		Recordset::update('player_item', array(
			'ativo' => '1'
		), array(
			'id_item'		=> $item->id,
			'id_player'		=> $basePlayer->id
		));

		// Conquista --->
			arch_parse(NG_ARCH_ITEM_N, $basePlayer, NULL, $item, 1);
		// <---

		redirect_to("mode_sennin", "", array("ok" => "1", "h" => $id));
	} else {
		redirect_to("negado", NULL, array('e' => 2));
	}
