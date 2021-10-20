<?php
	if(!isset($_POST['bf'])) {
		$_POST['bf'] = '';
	}

	if(!isset($_POST['f'])) {
		$_POST['f'] = '';
	}
	
	if(!(isset($_POST['id'])) || (isset($_POST['id']) && !is_numeric($_POST['id']))) {
		redirect_to('negado', NULL, array('e' => 1));
	}
	
	if($_POST['p'] != encode($basePlayer->id)) {
		redirect_to('negado', NULL, array('e' => 2));
	}
	
	if($basePlayer->hasItem($_POST['id'])) {
		redirect_to("academia_jutsu", NULL, array("existent" => 1, "f" => $_POST['f'], "bf" => $_POST['bf']));			
	}

	$item	= new Item($_POST['id']);
	$item->setPlayerInstance($basePlayer);
	$reqs	= Item::hasRequirement($item, $basePlayer, NULL, array(
		'req_con'	=> true,
		'req_agi'	=> true,
		'req_level' => false
	));
	
	if(!$reqs) {
		redirect_to('negado', NULL, array('e' => 4));
	}
	
	if(($item->getAttribute('consume_sp') * 2) > $basePlayer->getAttribute('sp'))  redirect_to("academia_jutsu", NULL, array("fj" => 1, "tj" => $_POST['id'], "f" => $_POST['f'], "bf" => $_POST['bf']));
	if(($item->getAttribute('consume_sta') * 2) > $basePlayer->getAttribute('sta'))  redirect_to("academia_jutsu", NULL, array("fj" => 2, "tj" => $_POST['id'], "f" => $_POST['f'], "bf" => $_POST['bf']));
	
	$basePlayer->consumeSP($item->getAttribute('consume_sp') * 2);
	$basePlayer->consumeSTA($item->getAttribute('consume_sta') * 2);

	$insert = $basePlayer->addItemW($_POST['id']);

	$div = round($basePlayer->getAttribute('level') / 6); // / 12

	// So ganha exp se for insert --->
	if($insert) {
		$exp = round(rand(10, 15) * 1 * ($div == 0 ? .5 : $div));

		$basePlayer->setAttribute('exp', $basePlayer->getAttribute('exp') + $exp);

		equipe_exp(20);
	} else {
		$exp	= 0;
	}
	// <---

	// Conquista --->
		arch_parse(NG_ARCH_ITEM_N, $basePlayer, NULL, $item, 1);
	// <---

	player_at_check();
	redirect_to("academia_jutsu", NULL, array("j" => $_POST['id'],  "e" => $exp, 'tipo' => $_POST['tipo']));