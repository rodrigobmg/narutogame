<?php
	$json			= new stdClass();
	$messages		= [];
	$json->messages	= [];
	$json->success	= false;

	$next_level		= Player::getFlag('profissao_nivel', $basePlayer->id) + 1;

	if (!$basePlayer->id_profissao) {
		$messages[]	= t('profissao.errors.not_learned');
	} else {
		$profession	= Recordset::query('SELECT * FROM profissao WHERE id=' . $basePlayer->id_profissao, true)->row_array();

		if (!Profession::hasRequirement($next_level, $basePlayer, $profession)) {
			$messages[]	= t('profissao.errors.requirements');
		}

		if (isset($_POST['level']) && is_numeric($_POST['level'])) {
			if ($_POST['level'] > $next_level) {
				$messages[]	= t('profissao.errors.level_invalid');
			}
		} else {
			$messages[]	= t('profissao.errors.level_invalid');
		}
	}

	if (!sizeof($messages)) {
		$json->success	= true;
		$basePlayer->setFlag('profissao_nivel', $next_level);
	} else {
		$json->messages	= $messages;
	}

	echo json_encode($json);
