<?php
	header('Content-Type: application/json');

	$json			= new stdClass();
	$json->success	= false;
	$json->messages	= [];
	$errors			= [];
	$quests			= [];
	$durations		= [];
	$times			= [];

	$has_queue	= $basePlayer->getVIPItem(22888);

	if($_SESSION['missoes_key'] != $_POST['key']) {
		$errors[]	= t('missoes_fila.errors.invalid_key');
	}
    //Define limite de missões por graduação
	$limite_graduacao  = unserialize(LIMITE_MISSOES);
	$limite_graduacao =  $limite_graduacao[$basePlayer->id_graduacao];

	if(!$has_queue) {
		$errors[]	= t('missoes_fila.errors.advantage');
	}

	if(isset($_POST['quests']) && is_array($_POST['quests'])) {
		foreach ($_POST['quests'] as $key => $quest) {
			if(is_numeric($quest) && isset($_POST['durations'][$key]) && is_numeric($_POST['durations'][$key])) {
				$quest_data	= Recordset::query('SELECT * FROM quest WHERE id=' . $quest, true);

				if($quest_data->num_rows) {
					$quest_data		= $quest_data->row_array();
					$duration		= $_POST['durations'][$key];
					$duration_field	= 'duracao' . ($duration == 1 ? '' : $duration);

					if(in_array($quest, $quests)) {
						$errors[]	= t('missoes_fila.errors.existent');
					}

					if(Recordset::query('SELECT id FROM player_quest WHERE id_player=' . $basePlayer->id . ' AND id_quest=' . $quest)->num_rows) {
						$errors[]	= t('missoes_fila.errors.invalid');
						break;
					}

					if($quest_data['interativa']) {
						$errors[]	= t('missoes_fila.errors.invalid');
						break;
					}

					if(!$quest_data[$duration_field]) {
						$errors[]	= t('missoes_fila.errors.invalid_time');
						break;
					}

					if($quest_data['id_vila'] && $quest_data['id_vila'] != $basePlayer->id_vila_atual) {
						$errors[]	= t('missoes_fila.errors.requriements');
						break;
					}

					if($quest_data['level'] && $quest_data['level'] > $basePlayer->level) {
						$errors[]	= t('missoes_fila.errors.requriements');
						break;
					}

					if($quest_data['id_graduacao'] && $quest_data['id_graduacao'] > $basePlayer->id_graduacao) {
						$errors[]	= t('missoes_fila.errors.requriements');
						break;
					}

					$quests[]		= $quest;
					$durations[]	= $duration;
					$times[]		= $quest_data[$duration_field];
				}
			}
		}
	}

	if(!sizeof($quests)) {
		$errors[]	= t('missoes_fila.errors.invalid');
	}

	if(!sizeof($errors)) {
		$json->success	= true;
		$serialized		= [];

		if($basePlayer->getAttribute('level') >= 15) {
			$fall = hasFall($basePlayer->getAttribute('id_vila'), 4);
		} else {
			$fall = false;
		}

		$time	= now();

		foreach ($quests as $key => $value) {
			$serialized[]	= ['quest' => $value, 'duration' => $durations[$key], 'village' => $basePlayer->id_vila_atual];

			$hour			= substr($times[$key], 0, 2) * ($fall ? 2 : 1);
			$minute			= substr($times[$key], 2, 2) * ($fall ? 2 : 1);
			$secs			= substr($times[$key], 4, 2) * ($fall ? 2 : 1);
			$time			= strtotime('+' . $hour . ' hours, +' . $minute . ' minutes, +' . $secs . ' seconds', $time);

			if($basePlayer->bonus_vila['sk_missao_tempo']) {
				$time	= strtotime('-' . $basePlayer->bonus_vila['sk_missao_tempo'] . ' minutes', $time);
			}
		}

		$serialized['finishing_time']	= date('Y-m-d H:i:s', $time);

		$basePlayer->setFlag('missao_tempo_vip', serialize($serialized));
		$basePlayer->setAttribute('id_missao', -1);
	} else {
		$json->messages	= $errors;
	}

	echo json_encode($json);
