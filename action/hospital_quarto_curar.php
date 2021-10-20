<?php
	header("Content-type: text/javascript; charset=utf-8");
	
	$redir_script	= true;
	$data			= [
		'hospital'	=> '0',
		'less_sp'	=> 0,
		'less_hp'	=> 0,
		'less_sta'	=> 0
	];
	
	if($_POST['c'] == 1) {
		if(!$basePlayer->hasItem(array(1007, 1008, 1009))) {
			redirect_to("negado");
		}
		
		$i = $basePlayer->getVIPItem(array(1007, 1008, 1009));
		
		if($i['uso'] >= $i['vezes']) {
			echo "alert('".t('actions.a175')."');";
			echo "$('#bHospitalQuartoCura').attr('disabled', false);";
			
			die();
		}
		
		$basePlayer->useVIPItem($i);

		echo "jalert('".t('actions.a277')."', null, function () { location.href = '?secao=personagem_status' });";
	} elseif($_POST['c'] == 2) {
		 if($i	= $basePlayer->getItem(22733)) {
		 	$basePlayer->removeItem($i);

			echo "jalert('".t('actions.a277')."', null, function () { location.href = '?secao=personagem_status' });";
		 } else {
		 	redirect_to("negado");
		 }
	} else {
		/*switch($basePlayer->id_graduacao) {
			case 1:
				$healValue = 35;
				break;
			case 2:
				$healValue = 35;
				break;
			case 3:
				$healValue = 105;
				break;
			case 4:
				$healValue = 175;
				break;
			case 5:
				$healValue = 245;
				break;
			case 6:
				$healValue = 315;
				break;
			case 7:
				$healValue = 385;
				break;
		}
    */

    $healValue	= 20 * $basePlayer->getAttribute('level');
    $healValue	-= percent($basePlayer->bonus_vila['hospital_preco'], $healValue);

		if($basePlayer->getAttribute('ryou') < $healValue) {
			echo "jalert('".t('actions.a176')."');";
			echo "lock_screen(false)";
			die();
		} else {
			$healValue	= absm($basePlayer->getAttribute('ryou') - $healValue);
			
			$basePlayer->setFlag('hospital_count', Player::getFlag('hospital_count', $basePlayer->id) + 1);
			$data['ryou']	= $healValue;
		}

		echo "jalert('".t('actions.a177')."', null, function () { location.href = '?secao=personagem_status' });";
	}

	Recordset::update('player', $data, [
		'id'		=> $basePlayer->id
	]);
