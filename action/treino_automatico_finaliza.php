<?php
	// Puni��o so acima do 15
	if($basePlayer->level >= 15) {	
		$_dbl = hasFall($basePlayer->id_vila, 2) ? .5 : 1;
	} else {
		$_dbl = 1;
	}

	$at_sta = 0;
	$at_sp = 0;

	// Pega o valor de horas de treino --->
		$qHora = Recordset::query("SELECT uso FROM player_item WHERE id_player={$basePlayer->id} AND id_item IN(1028, 1081, 1082)");
		$rHora = $qHora->row_array();
	// <---

	if($_POST['key'] != $_SESSION['vip_field_postkey_value']) {
		redirect_to("negado");	
	}

	unset($_SESSION['vip_field_postkey_value']);

	if(!$rHora['uso']) {
		$rHora = Recordset::query("SELECT treino_tempo AS uso FROM player WHERE id=" . $basePlayer->id)->row_array();
	}

	/*
	// --->
		if(decode($_POST['k']) != $_SESSION['treino_end_key']) {
			redirect_to("negado");
		}
	
		unset($_SESSION['treino_end_key']);
	// <---
	*/

	$id_treino = $basePlayer->id_tipo_treino;
	$hours = $rHora['uso'];
	
	$sp_consume =  (6 + $basePlayer->level);
	$sta_consume = (6 + $basePlayer->level);

	// Pontos vem do banco de acordo com o level -->
		$rPontos = Recordset::query("SELECT treino_bonus, treino_exp FROM level_exp WHERE id=" . $basePlayer->level)->row_array();
		$points = $rPontos['treino_bonus'];
		$exp = 0;
	// <---
	
	$treinado	= 0;
	$pts		= 0;
	$count		= 0;
	
	while($treinado++ < 6 * $hours) {
		$sta	= $basePlayer->getAttribute('max_sta');
		$sp		= $basePlayer->getAttribute('max_sp');
	
		while(true) {
			if($sp_consume > $sp) break;
			if($sta_consume > $sta) break;

			/*
			Recordset::query("UPDATE player SET 
					$field = IFNULL($field, 0) + $points, 
					exp = IFNULL(exp, 0) + $exp
				  
				  WHERE id=" . $basePlayer->id) or die("$field - $id_treino");
			*/
				  
			$sp -= $sp_consume;
			$sta -= $sta_consume;
			
			//echo "- Treinado!\n";
			//flush();
			
			$pts += $points;
			$exp += $rPontos['treino_exp'];
			
			$count++;
		}
		
		//echo "- Treino bloco: " . $treinado . "\n";
		//flush();	
	}

	// Redu��o por morte do npc --->
		$exp = round($exp * $_dbl);
		$pts = round($pts * $_dbl);
	// <---

	// Restringe a quantidade de pontos de acordo com o limite de treino --->
		$max_treino = $basePlayer->getAttribute('max_treino');	
		
		$treino_atual = $basePlayer->getAttribute('treino_dia');
		
		$new_treino = $treino_atual + $pts;
		
		if($new_treino > $max_treino) {
			$pts -= $new_treino - $max_treino;
		}
	// <---
	/*exp = IFNULL(exp, 0) + $exp*/
	Recordset::query("UPDATE player SET 
			treino_total = IFNULL(treino_total, 0) + $pts, 
			exp = exp +0
		  
		  WHERE id=" . $basePlayer->id) or die("$field - $id_treino");

	Recordset::query("UPDATE player SET treinando=NULL, id_tipo_treino=0, treino_dia = treino_dia + $pts WHERE id=" . $basePlayer->id);
	
	redirect_to("personagem_status");
