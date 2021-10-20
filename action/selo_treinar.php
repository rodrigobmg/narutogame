 <?php
	$id = $_POST['id'];

	$redir_script = true;

	if(!is_numeric($id)) {
		redirect_to("negado", NULL, array('e' => 1));
	}

	$item 	= new Item($id);
	$reqs	= Item::hasRequirement($item, $basePlayer);
	
	if($item->getAttribute('id_tipo') != 20) {
		redirect_to('negado', NULL, array('e' => 3));
	}
	
	if($basePlayer->hasItem($item->id)) {
		redirect_to('selo', NULL, array('existent' => 1));
	}
	
	if($reqs) {
		// Desativa os itens de clã anteriores
		Recordset::update('player_item', array(
			'ativo' => '0'
		), array(
			'id_item_tipo'	=> 20,
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

		redirect_to("selo", "", array("ok" => "2", "h" => $id));
	} else {
		redirect_to("negado", NULL, array('e' => 2));
	}
