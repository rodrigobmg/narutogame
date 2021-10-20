<?php

	$_POST['captcha']	= isset($_POST['captcha']) && $_POST['captcha'] ? $_POST['captcha'] : '';
	
	// Puni��o so acima do 15
	if($basePlayer->getAttribute('level') >= 15) {	
		$_dbl = hasFall($basePlayer->getAttribute('id_vila'), 2) ? .5 : 1;
	} else {
		$_dbl = 1;
	}
	
	$max_treino = $basePlayer->getAttribute('max_treino');

	if(!isset($_POST['qtd'])) {
		$_POST['qtd']	= 1;
	}

	if(!is_numeric($_POST['qtd']) || $_POST['qtd'] < 1) {
		redirect_to("negado");	
	} else {
		$_POST['qtd'] = (int)$_POST['qtd'];
	}

	// Verifica o litmite de treino por dia --->
		if($basePlayer->getAttribute('treino_dia') > $max_treino) {
			redirect_to("negado");	
		}
	// <---
	
	// Verificador do captcha --->
		if(isset($_SESSION['_TREINO_CAPTCHA']) && $_SESSION['_TREINO_CAPTCHA'] >= 10) {
			if(!isset($_POST['captcha'])) {
				$_POST['captcha'] = '-1';
			}
		
			if(!captcha_text_validate("captcha_treino", $_POST['captcha'])) {
				redirect_to("academia_treinamento", "", array("c" => 1));
			}
		}
	// <---

	/* SISTEMA NOVO DE TREINO */

	if(in_array($basePlayer->getAttribute('id_classe_tipo'), array(1, 4))) {
		$sp_consume		=  (2 + round($basePlayer->getAttribute('level') / 2)) * $_POST['qtd'];
		$sta_consume	= (6 + $basePlayer->getAttribute('level')) * $_POST['qtd'];
	} elseif(in_array($basePlayer->getAttribute('id_classe_tipo'), array(2, 3))) {
		$sp_consume		= (6 + $basePlayer->getAttribute('level')) * $_POST['qtd'];
		$sta_consume	=  (2 + round($basePlayer->getAttribute('level') / 2)) * $_POST['qtd'];
	} 
	
	$sp_consume		-= percent($basePlayer->bonus_profissao['custo_treino'], $sp_consume);
	$sta_consume	-= percent($basePlayer->bonus_profissao['custo_treino'], $sta_consume);

	$sp_consume		= round($sp_consume);
	$sta_consume	= round($sta_consume);
	
	if($sp_consume > $basePlayer->getAttribute('sp')) {
		redirect_to("academia_treinamento", NULL, array(
			"f" => 1, 
			"t" => isset($_POST['id']) && $_POST['id'] ? encode($_POST['id']) : ''
		));
	}
	
	if($sta_consume > $basePlayer->getAttribute('sta')) {
		redirect_to("academia_treinamento", NULL, array(
			"f"	=> 2, 
			"t"	=> isset($_POST['id']) && $_POST['id'] ? encode($_POST['id']) : ''
		));
	}

	// Pontos vem do banco de acordo com o level -->
		$rPontos = Recordset::query('SELECT treino_bonus, treino_exp FROM level_exp WHERE id=' . $basePlayer->getAttribute('level'), true)->row_array();

		$points	= $rPontos['treino_bonus'] * $_POST['qtd'];
		$exp	= $rPontos['treino_exp'] * $_POST['qtd'];
	// <---

	// Redu��o por morte do npc --->
		$exp	= round($exp * $_dbl);
		$points = round($points * $_dbl);
	// <---

	// Restringe a quantidade de pontos de acordo com o limite de treino --->
		$treino_atual = $basePlayer->getAttribute('treino_dia');
		
		$new_treino = $treino_atual + $points;
		
		if($new_treino > $max_treino) {
			$points -= $new_treino - $max_treino;
		}
	// <---
	/*exp = IFNULL(exp, 0) + $exp ,*/
	Recordset::query("UPDATE player SET 
			treino_total = IFNULL(treino_total, 0) + $points, 
			exp = exp + 0 ,
			less_sp = IFNULL(less_sp, 0) + $sp_consume,
			less_sta = IFNULL(less_sta, 0) + $sta_consume,
			treino_dia = treino_dia + $points
		  
		  WHERE id=" . $basePlayer->id);

	// Regera a chave de criptografia
	$_SESSION['key'] = md5(rand(0, 512384) . rand(0, 512384));

	// Captcha --->	
		if(isset($_POST['captcha']) && $_POST['captcha']) {
			$_SESSION['_TREINO_CAPTCHA'] = 0;
		}
		
		if(!isset($_SESSION['_TREINO_CAPTCHA'])) {
			$_SESSION['_TREINO_CAPTCHA']	 = 0;
		}
		
		$_SESSION['_TREINO_CAPTCHA']++;
	// <---
	// Remover isso quando quiser exp de volta
	$exp = 0;
	$redir_data	= array(
		"p"		=> $points, 
		"e"		=> $exp,
		"t"		=> isset($_POST['id']) && $_POST['id'] ? encode($_POST['id']) : '',
		"sc"	=> $sp_consume, 
		"tc"	=> $sta_consume
	);
	
	redirect_to("academia_treinamento", NULL, $redir_data);
	