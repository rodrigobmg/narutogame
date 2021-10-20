<?php
	$id = $_POST['id'];

	$redir_script = true;

	if(!is_numeric($id)) {
		redirect_to("negado", NULL, array('e' => 1));
	}

	$item 	= new Item($id);
	$reqs	= Item::hasRequirement($item, $basePlayer);
	
	if($item->getAttribute('id_tipo') != 17) {
		redirect_to('negado', NULL, array('e' => 3));
	}
	
	if($basePlayer->hasItem($item->id)) {
		redirect_to('portoes', NULL, array('existent' => 1));
	}
	
	if($reqs) {
		// Adiciona o novo item
		$basePlayer->addItemW($item->id);

		// Conquista --->
			arch_parse(NG_ARCH_ITEM_N, $basePlayer, NULL, $item, 1);
		// <---

		redirect_to("portoes", "", array("ok" => "1", "h" => $id));
	} else {
		redirect_to("negado", NULL, array('e' => 2));
	}
