<?php
	set_time_limit(5);

	$json			= new stdClass();
	$json->errors	= [];
	$json->sucess	= false;
	$errors			= [];
	$messages		= '';

	$basePlayer		= new Player($basePlayer->id);

	$pay_mode		= isset($_POST['pay_mode']) && is_numeric($_POST['pay_mode']) ? $_POST['pay_mode'] : null;
	$weekly			= "";
	$week_full		= true;
	$week_data		= unserialize(Player::getFlag('sorte_ninja_semanal', $basePlayer->id));
	$default_week	= array('1' => false, '2' => false, '3' => false, '4' => false, '5' => false, '6' => false, '7' => false);

	$block_by_time	= false;
	$hour			= date('H');
	$minute			= date('i');
	$rPremio		= null;
	
	if(($hour == 23 && $minute >= 45) || ($hour == 0 && $minute <= 15)) {
		$block_by_time = true;
	}

	if($block_by_time) {
		$errors[]	= t('sorte_ninja.errors.time');
	}

	if(!is_array($week_data)) {
		$week_data	= $default_week;
	}
	
	foreach($week_data as $day => $used) {
		if(!$used) {
			$week_full	= false;
		}
	}
	
	$is_weekly	= false;

	if(isset($_POST['weekly']) && $_POST['weekly']) {
		if($week_full) {
			$week_data	= $default_week;
			$weekly		= ' AND semanal=1';
		} else {
			$errors[]	= t('sorte_ninja.errors.week_empty');
		}

		$ryou		= 6000 - $basePlayer->bonus_vila['mo_sorte_preco_semanal'];
		$coin		= 3;
		$is_weekly	= true;
	} else {
		$week_data[date('N')]	= true;		

		if(Player::getFlag("loteria_uso", $basePlayer->id)) {
			$errors[]	= t('sorte_ninja.errors.already');
		}

		$ryou	= 2000 - $basePlayer->bonus_vila['mo_sorte_preco'];
		$coin	= 1;
	}

	if(!in_array($pay_mode, [0, 1]) || is_null($pay_mode)) {
		$errors[]	= t('sorte_ninja.errors.paymode');
	} else {
		if($pay_mode == 0 && $basePlayer->ryou < $ryou) {
			$errors[]	= t('sorte_ninja.errors.currency');
		} elseif($pay_mode == 1 && $basePlayer->coin < $coin) {
			$errors[]	= t('sorte_ninja.errors.credits');
		}
	}

	if(!sizeof($errors)) {
		if($pay_mode == 0) { // Grana
			Recordset::query("UPDATE player SET ryou=ryou-" . $ryou . " WHERE id=" . $basePlayer->id);
		} elseif($pay_mode == 1) { // Coin
			Recordset::query("UPDATE global.user SET coin=coin-" . $coin . " WHERE id=" . $_SESSION['usuario']['id']);
			usa_coin(21888, $coin);
		}
		
		$basePlayer->setFlag('sorte_ninja_semanal', serialize($week_data));

		$premios	= Recordset::query("SELECT * FROM loteria_premio WHERE removido = 0 AND tipo='sorte' " . $weekly . " ORDER BY RAND()");

		while(true) {
			foreach($premios->result_array() as $premio) {
				if(rand(1, 100) <= $premio['chance']) {
					$rPremio = $premio;
					
					break;
				}
			}

			if($rPremio) {
				break;
			}
		}

		if($rPremio['chance'] == 1) {
			global_message2('msg_global.sorte_ninja_azar', array($basePlayer->nome, $rPremio['nome_' . Locale::get()]));		
		}

		// Itens
		if($rPremio['id_item']) {
			$rItem		= Recordset::query("SELECT nome_".Locale::get()." as nome FROM item WHERE id=" . $rPremio['id_item'], true)->row_array();
			$messages	.= "<strong class='verde'>" . $rItem['nome'] . " x" . $rPremio['mul'] . "</strong><br />";

			if($basePlayer->hasItem($rPremio['id_item'])) {
				Recordset::query("UPDATE player_item SET qtd=qtd + " . $rPremio['mul'] . " WHERE id_player={$basePlayer->id} AND id_item=" . $rPremio['id_item']);
			} else {		
				Recordset::query("INSERT INTO player_item(id_item, id_player, qtd) VALUES(
					{$rPremio['id_item']}, {$basePlayer->id}, {$rPremio['mul']}
				)");
			}
		}
		
		// Ryou
		if($rPremio['ryou']) {
			$messages	.= "<strong class='verde'>" . $rPremio['ryou'] . "</strong> Ryous<br />";

			Recordset::query("UPDATE player SET ryou=ryou + " .$rPremio['ryou'] . " WHERE id=" . $basePlayer->id);
		}

		// Exp
		if($rPremio['exp']) {
			$messages	.= "<strong class='verde'>" . $rPremio['exp'] . "</strong> '".t('geral.pontos_exp')."'<br />";

			Recordset::query("UPDATE player SET exp=exp + " .$rPremio['exp'] . " WHERE id=" . $basePlayer->id);
		}

		// Atributos do player --->
			// Ene
			if($rPremio['ene']) {
				$messages	.= "<strong class='verde'>" . $rPremio['ene'] . "</strong> '".t('item_tooltip.at.pontos_ene')."'<br />";

				Recordset::query("UPDATE player_atributos SET ene=ene + " .$rPremio['ene'] . " WHERE id_player=" . $basePlayer->id);
			}
		
			// Int
			if($rPremio['inte']) {
				$messages	.= "<strong class='verde'>" . $rPremio['inte'] . "</strong> '".t('item_tooltip.at.pontos_int')."'<br />";

				Recordset::query("UPDATE player_atributos SET inte=inte + " .$rPremio['inte'] . " WHERE id_player=" . $basePlayer->id);
			}
		
			// For
			if($rPremio['forc']) {
				$messages	.= "<strong class='verde'>" . $rPremio['forc'] . "</strong> '".t('item_tooltip.at.pontos_for')."'<br />";

				Recordset::query("UPDATE player_atributos SET forc=forc + " .$rPremio['forc'] . " WHERE id_player=" . $basePlayer->id);
			}
		
			// Agi
			if($rPremio['agi']) {
				$messages	.= "<strong class='verde'>" . $rPremio['agi'] . "</strong> '".t('item_tooltip.at.pontos_agi')."'<br />";

				Recordset::query("UPDATE player_atributos SET agi=agi + " .$rPremio['agi'] . " WHERE id_player=" . $basePlayer->id);
			}
		
			// Con
			if($rPremio['con']) {
				$messages	.= "<strong class='verde'>" . $rPremio['con'] . "</strong> '".t('item_tooltip.at.pontos_con')."'<br />";

				Recordset::query("UPDATE player_atributos SET con=con + " .$rPremio['con'] . " WHERE id_player=" . $basePlayer->id);
			}

			// Res
			if($rPremio['res']) {
				$messages	.= "<strong class='verde'>" . $rPremio['res'] . "</strong> '".t('item_tooltip.at.pontos_res')."'<br />";

				Recordset::query("UPDATE player_atributos SET res=res + " .$rPremio['res'] . " WHERE id_player=" . $basePlayer->id);
			}
		
			// Tai
			if($rPremio['tai']) {
				$messages	.= "<strong class='verde'>" . $rPremio['tai'] . "</strong> '".t('item_tooltip.at.pontos_tai')."'<br />";

				Recordset::query("UPDATE player_atributos SET tai=tai + " .$rPremio['tai'] . " WHERE id_player=" . $basePlayer->id);
			}
			
			// ken
			if($rPremio['ken']) {
				$messages	.= "<strong class='verde'>" . $rPremio['ken'] . "</strong> '".t('item_tooltip.at.pontos_ken')."'<br />";

				Recordset::query("UPDATE player_atributos SET ken=ken + " .$rPremio['ken'] . " WHERE id_player=" . $basePlayer->id);
			}
			
			// Nin
			if($rPremio['nin']) {
				$messages	.= "<strong class='verde'>" . $rPremio['nin'] . "</strong> '".t('item_tooltip.at.pontos_nin')."'<br />";

				Recordset::query("UPDATE player_atributos SET nin=nin + " .$rPremio['nin'] . " WHERE id_player=" . $basePlayer->id);
			}
		
			// Gen
			if($rPremio['gen']) {
				$messages	.= "<strong class='verde'>" . $rPremio['gen'] . "</strong> '".t('item_tooltip.at.pontos_gen')."'<br />";

				Recordset::query("UPDATE player_atributos SET gen=gen + " .$rPremio['gen'] . " WHERE id_player=" . $basePlayer->id);
			}
			
			// Critico Minimo
			if((float)$rPremio['crit_min']) {
				$messages	.= "<strong class='verde'>" . $rPremio['crit_min'] . "</strong> '".t('item_tooltip.at.crit_min')."'<br />";

				Recordset::query("UPDATE player_atributos SET crit_min=crit_min + " .$rPremio['crit_min'] . " WHERE id_player=" . $basePlayer->id);
			}
			// Critico Maximo
			if((float)$rPremio['crit_max']) {
				$messages	.= "<strong class='verde'>" . $rPremio['crit_max'] . "</strong> '".t('item_tooltip.at.crit_max')."'<br />";

				Recordset::query("UPDATE player_atributos SET crit_max=crit_max + " .$rPremio['crit_max'] . " WHERE id_player=" . $basePlayer->id);
			}
			// Critico Maximo
			if((float)$rPremio['crit_total']) {
				$messages	.= "<strong class='verde'>" . $rPremio['crit_total'] . "</strong> '".t('item_tooltip.at.crit_total')."'<br />";

				Recordset::query("UPDATE player_atributos SET crit_total=crit_total + " .$rPremio['crit_total'] . " WHERE id_player=" . $basePlayer->id);
			}
			// Esquiva Minimo
			if((float)$rPremio['esq_min']) {
				$messages	.= "<strong class='verde'>" . $rPremio['esq_min'] . "</strong> '".t('item_tooltip.at.esq_min')."'<br />";

				Recordset::query("UPDATE player_atributos SET esq_min=esq_min + " .$rPremio['esq_min'] . " WHERE id_player=" . $basePlayer->id);
			}
			// Esquiva Maximo
			if((float)$rPremio['esq_max']) {
				$messages	.= "<strong class='verde'>" . $rPremio['esq_max'] . "</strong> '".t('item_tooltip.at.esq_max')."'<br />";

				Recordset::query("UPDATE player_atributos SET esq_max=esq_max + " .$rPremio['esq_max'] . " WHERE id_player=" . $basePlayer->id);
			}
			// Esquiva Maximo
			if((float)$rPremio['esq_total']) {
				$messages	.= "<strong class='verde'>" . $rPremio['esq_total'] . "</strong> '".t('item_tooltip.at.esq_total')."'<br />";

				Recordset::query("UPDATE player_atributos SET esq_total=esq_total + " .$rPremio['esq_total'] . " WHERE id_player=" . $basePlayer->id);
			}
			// Concentração
			if((float)$rPremio['conc']) {
				$messages	.= "<strong class='verde'>" . $rPremio['conc'] . "</strong> '".t('item_tooltip.at.conc')."'<br />";

				Recordset::query("UPDATE player_atributos SET conc=conc + " .$rPremio['conc'] . " WHERE id_player=" . $basePlayer->id);
			}
			// Determinação
			if((float)$rPremio['det']) {
				$messages	.= "<strong class='verde'>" . $rPremio['det'] . "</strong> '".t('item_tooltip.at.det')."'<br />";

				Recordset::query("UPDATE player_atributos SET det=det + " .$rPremio['det'] . " WHERE id_player=" . $basePlayer->id);
			}
			// Convicção
			if((float)$rPremio['conv']) {
				$messages	.= "<strong class='verde'>" . $rPremio['conv'] . "</strong> '".t('item_tooltip.at.conv')."'<br />";

				Recordset::query("UPDATE player_atributos SET conv=conv + " .$rPremio['conv'] . " WHERE id_player=" . $basePlayer->id);
			}
			// Esquiva
			if((float)$rPremio['esquiva']) {
				$messages	.= "<strong class='verde'>" . $rPremio['esquiva'] . "</strong> '".t('item_tooltip.at.esquiva')."'<br />";

				Recordset::query("UPDATE player_atributos SET esquiva=esquiva + " .$rPremio['esquiva'] . " WHERE id_player=" . $basePlayer->id);
			}
			// Percepção
			if((float)$rPremio['esq']) {
				$messages	.= "<strong class='verde'>" . $rPremio['esq'] . "</strong> '".t('item_tooltip.at.esq')."'<br />";

				Recordset::query("UPDATE player_atributos SET esq=esq + " .$rPremio['esq'] . " WHERE id_player=" . $basePlayer->id);
			}
			// Treino
			if($rPremio['treino']) {
				$messages	.= "<strong class='verde'>" . $rPremio['treino'] . "</strong> '".t('academia_treinamento.at37')."'<br />";

				Recordset::query("UPDATE player SET treino_total=treino_total + " .$rPremio['treino'] . " WHERE id=" . $basePlayer->id);
			}
		// <---
		
		// Coin
		if($rPremio['coin']) {
			$messages	.= "<strong class='verde'>" . $rPremio['coin'] . "</strong> '".t('geral.creditos')."'<br />";

			Recordset::query("UPDATE global.user SET coin=coin+ " . $rPremio['coin'] . " WHERE id=" . $_SESSION['usuario']['id']);
		}

		Recordset::query("INSERT INTO sorte_ninja_log(id_player, id_premio) VALUES({$basePlayer->id}, {$rPremio['id']})");

		if(!$is_weekly) {
			$basePlayer->setFlag("loteria_uso", 1);		
		}

		if($rPremio['id_item']) {
			// Conquista --->
				$_POST['q']	= isset($_POST['q']) && is_numeric($_POST['q']) ? $_POST['q'] : 1;
			
				arch_parse(NG_ARCH_ITEM_S, $basePlayer, NULL, new Item($rPremio['id_item']), $_POST['q']);			
			// <---		
		}
		// Missões diárias de Sorte Ninja 
		if($basePlayer->hasMissaoDiariaPlayer(3)->total){
			// Adiciona os contadores nas missões de tempo.
			Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
						 WHERE id_player = ". $basePlayer->id." 
						 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 3) 
						 AND completo = 0 ");
		}
		// Conquista --->
			arch_parse(NG_ARCH_SELF, $basePlayer);

			// Item especifico do sorte ninja --->
				$stdItem = new stdClass();
				$stdItem->id = $rPremio['id'];
				
				arch_parse(NG_ARCH_ITEM_SI, $basePlayer, NULL, $stdItem);
			// <---
		// <---

		$json->success	= true;
		$json->slot		= array($rPremio['slot1'], $rPremio['slot2'], $rPremio['slot3'], $rPremio['slot4']);
		$json->today	= date('N');
		$json->message	= $messages;
	} else {
		$json->errors	= $errors;
	}

	echo json_encode($json);
