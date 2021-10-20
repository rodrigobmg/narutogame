<?php
	$json			= new stdClass();
	$messages		= [];
	$json->messages	= [];
	$json->success	= false;

	if (isset($_POST['learn'])) {
		if ($basePlayer->id_profissao) {
			$messages[]	= t('profissao.errors.already');
		} else {
			if (isset($_POST['profession']) && is_numeric($_POST['profession'])) {
				$profession	= Recordset::query('SELECT * FROM profissao WHERE id=' . $_POST['profession']);

				if (!$profession->num_rows) {
					$messages[]	= t('profissao.errors.invalid');
				} else {
					$profession	= $profession->row();
				}
			} else {
				$messages[]	= t('profissao.errors.invalid');
			}
		}

		if (!sizeof($messages)) {
			$json->success	= true;

			$basePlayer->setAttribute('id_profissao', $profession->id);
		}
	}

	if (isset($_POST['unlearn'])) {
		$current_count	= Player::getFlag('profissao_sair_count', $basePlayer->id);
		
		if($current_count == 1) {
			if($basePlayer->getAttribute('ryou') < 1000) {
				$messages[]	= t('profissao.errors.no_ryou');
			}
		} elseif($current_count > 1) {
			if($basePlayer->getAttribute('coin') < 2) { // Sem coin (aviso: o if tem q ter um die)
				$messages[]	= t('profissao.errors.no_coin');
			}
		}

		if (!sizeof($messages)) {
			if ($current_count == 1) {
				$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') - 1000);
			} elseif ($current_count > 1) {
				// Gasta os créditos --->
					Recordset::update('global.user', array(
						'coin'	=> array('escape' => false, 'value' => 'coin-2')
					), array(
						'id'	=> $_SESSION['usuario']['id'])
					);
				
					usa_coin(22952, 2);
				// <---
			}

			$basePlayer->setAttribute('id_profissao', 0);
			$basePlayer->setFlag('profissao_nivel', 0);
			$basePlayer->setFlag('profissao_ativa_vezes', 0);
			$basePlayer->setFlag('profissao_sair_count', $current_count + 1);

			$json->success	= true;
		}
	}

	$json->messages	= $messages;
	echo json_encode($json);
