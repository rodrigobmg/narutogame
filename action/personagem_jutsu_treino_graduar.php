<?php
	$redir_script = true;

	$postkey = $_POST[$_SESSION['jut_field_postkey']];
	
	if(!$basePlayer->hasItem($_POST['id'])) {
		redirect_to('negado', NULL, array('e' => 1));
	}
	
	if($postkey != $_SESSION['jut_field_postkey_value']) {
		redirect_to('negado', NULL, array('e' => 2));
	}
	
	$item = $basePlayer->getItem($_POST['id']);
	
	// exp do jutsu --->
		switch($item->getAttribute('req_graduacao')) {
			case 1:
			case 2:
				$jexp_lvl2 = 6000;
				$jexp_lvl3 = 12000;
				
				break;
				
			case 3:
				$jexp_lvl2 = 7000;
				$jexp_lvl3 = 14000;
				
				break;
				
			case 4:
				$jexp_lvl2 = 8000;
				$jexp_lvl3 = 16000;
				
				break;
				
			case 5:
				$jexp_lvl2 = 9000;
				$jexp_lvl3 = 18000;
				
				break;
				
			case 6:
				$jexp_lvl2 = 10000;
				$jexp_lvl3 = 20000;
				
				break;
				
		}
		
		if($item->getAttribute('level') == 1) {
			$jexp_lvl_atual = $jexp_lvl2;
		} else {
			$jexp_lvl_atual = $jexp_lvl3;		
		}
		
	// <---

	if(!$item->getAttribute('level_liberado')) {
		redirect_to('negado', NULL, array('e' => 3));
	}

	if($item->getAttribute('exp') < $jexp_lvl_atual) {
		redirect_to('negado', NULL, array('e' => 4));
	} else {
		$item->setAttribute('exp', $item->getAttribute('exp') - $jexp_lvl_atual);
		$item->setAttribute('level', $item->getAttribute('level') + 1);

		redirect_to('personagem_jutsu', NULL, array(
			'j' => $_POST['id'], 
			'l' => $item->getAttribute('level')
		));
	}
