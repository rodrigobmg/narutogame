<?php 
	$redir_script = true;

	// Se a data atual for menor do  que a de termino (aka tentando usar url)
	if(date('YmdHis') < date('YmdHis', strtotime($basePlayer->treino_tempo_jutsu))) {
		redirect_to('negado', NULL, array('e' => 1));
	} else {
		switch($basePlayer->id_tipo_treino_jutsu) {
			case 1:
				$exp = 1000;
			
				break;	
				
			case 2:
				$exp = 2000;
			
				break;	
				
			case 3:
				$exp = 3000;
				
				break;
			
			default:
				redirect_to('negado', NULL, array('detail' => $basePlayer->id_tipo_treino_jutsu));
			
				break;	
		}
		
		$exp_wasted	= Player::getFlag('treino_jutsu_exp_dia', $basePlayer->id);
		$exp_avail	= $basePlayer->getAttribute('max_treino_jutsu') - $exp_wasted;
		$exp		= $exp > $exp_avail ? $exp_avail : $exp;
		$item		= $basePlayer->getItem($basePlayer->id_jutsu_treino);

		$basePlayer->setFlag('treino_jutsu_exp_dia', $exp_wasted + $exp);
		
		$item->setAttribute('exp', $item->getAttribute('exp') + $exp);
		
		Recordset::query("UPDATE player SET id_tipo_treino_jutsu=0, treino_tempo_jutsu=NULL, id_jutsu_treino=0 WHERE id=" . $basePlayer->id);

		redirect_to("personagem_jutsu");
	}
