<?php
	header('Content-Type: application/json');

	$id						= $_POST['id'];
	$attributes				= isset($_POST['attributes']) && is_array($_POST['attributes']) ? $_POST['attributes'] : [];
	$json					= new stdClass();
	$json->success			= false;
	$json->messages			= [];
	$errors					= [];
	$max_points_formula		= 0;
	$max_points				= 0;
	$allowed				= [];
	$allowed_formula		= [];
	$spent_points			= 0;
	$spent_points_formula	= 0;
	$max					= [];
	$max_formula			= [];
	$insert					= [];
	$formulas				= [
		'conv',
		'conc',
		'det',
		'esq'
	];

	$ats	= [
		'ene',	
		'tai',
		'ken',
		'nin',
		'gen',
		'agi',
		'con',
		'forc',
		'inte',
		'res',
		'esq',
		'det',
		'conv',
		'conc'
	];

	if(!is_numeric($id)) {
		redirect_to("negado");	
	}

	$total_points		= $max_points + $max_points_formula;
	$distribute_data	= Recordset::query('SELECT * FROM invocacao WHERE id=' . $id, true)->row();
	$base_item			= Recordset::query('SELECT * FROM item WHERE id_tipo=21 AND ordem=1 AND id_invocacao=' . $id, true)->row();		

	foreach ($ats as $at) {
		if($distribute_data->$at) {
			if(in_array($at, $formulas)) {
				$max_formula[$at]	= $distribute_data->$at;
				$max_points_formula	+= $base_item->$at;
				$allowed_formula[]	= $at;
			} else {
				$max[$at]			= $distribute_data->$at;
				$max_points			+= $base_item->$at;
				$allowed[]			= $at;
			}
		}
	}

	if($basePlayer->getAttribute('id_invocacao')) {
		$errors[]	= t('invocacoes.errors.already');
	}

	$has_denied_error	= false;

	foreach($attributes as $at => $value) {
		if($distribute_data->$at) {
			if(in_array($at, $formulas)) {
				$spent_points_formula	+= $value;

				if($value > $max_formula[$at]) {
					$errors[]			= t('invocacoes.errors.maxed');
					$has_denied_error	= true;
				}
			} else {
				$spent_points			+= $value;

				if($value > $max[$at]) {
					$errors[]			= t('invocacoes.errors.maxed');
					$has_denied_error	= true;
				}
			}

			$insert[$at]	= $value;
		} else {
			$errors[]			= t('invocacoes.errors.denied');
			$has_denied_error	= true;
			break;
		}
	}

	if(!$has_denied_error) {
		if($spent_points < $max_points || $spent_points_formula < $max_points_formula) {
			$errors[]	= t('invocacoes.errors.lacking');
		} elseif($spent_points > $max_points || $spent_points_formula > $max_points_formula) {
			$errors[]	= t('invocacoes.errors.total');
		}
	}

	//$errors[]	= 'yay -> SP: ' . $spent_points . ' / ' . $max_points . ' --- SPF: ' . $spent_points_formula . ' / ' . $max_points_formula;
	
	if(!sizeof($errors)) {
		$json->success	= true;

		if(Recordset::query('SELECT id FROM invocacao WHERE id=' . (int)$id, true)->num_rows) {
			$basePlayer->setAttribute('id_invocacao', $id);
		} else {
			redirect_to("negado");
		}
		
		// Missões diárias de Aceitar Invocação
		if($basePlayer->hasMissaoDiariaPlayer(16)->total){
			// Adiciona os contadores nas missões de tempo.
			Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
						 WHERE id_player = ". $basePlayer->id." 
						 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 16) 
						 AND completo = 0 ");
		}

		// Conquista --->
			arch_parse(NG_ARCH_SELF, $basePlayer);
		// <---

		$base					= Recordset::query('SELECT * FROM item WHERE id_tipo=21 AND ordem=1', true)->row_array();
		$insert['id_player']	= $basePlayer->id;
		$insert['id_tipo']		= 21;

		Recordset::insert('player_modificadores', $insert);
	} else {
		$json->messages	= $errors;
	}

	echo json_encode($json);
