<?php
	$json			= new stdClass();
	$json->content	= null;
	$json->sucess	= false;
	$json->messages	= array();
	
	$allowed_types	= array(5);

	$l1_atks_total	= 0;
	$l2_atks_total	= 0;
	$l3_atks_total	= 0;
	$l4_atks_total	= 0;
	$l5_atks_total	= 0;
	$atks_total		= 0;

	$enhance_count		= Player::getFlag('ponto_aprimoramento', $basePlayer->id);
	//$pvp_points_avail	= $basePlayer->ponto_batalha - $basePlayer->ponto_batalha_gasto;
	$pvp_discount		= 0;

	if($basePlayer->hasItem(array(22653, 22654, 22655)) && $vip_pvp = $basePlayer->getVIPItem(array(22653, 22654, 22655))) {
		$pvp_discount	= $vip_pvp['vezes'];
	}

	foreach($basePlayer->getItems(5) as $item) {
		if($item->aprimoramento) {
			for($f = sizeof($item->aprimoramento); $f >= 1; $f--) {
				$var	= 'l' . $f . '_atks_total';
				$$var++;
				$atks_total++;
			}
		}
	}

	if(
		isset($_POST['list']) && $_POST['list'] &&
		isset($_POST['slot']) && is_numeric($_POST['slot']) &&
		isset($_POST['target']) && is_numeric($_POST['target'])
	) {
		$json->items	= array();
		$target			= Recordset::query('
			SELECT
				a.id,
				a.id_item,
				a.aprimoramento,
				b.sem_turno,
				b.def_base,
				b.id_elemento,
				b.req_graduacao,
				b.atk_fisico,
				b.atk_magico
			
			FROM
				player_item a JOIN item b ON b.id=a.id_item
			
			WHERE
				a.id=' . $_POST['target'] . ' AND
				a.id_player=' . $basePlayer->id)->row_array();

		$target2	= new Item($target['id_item'], $basePlayer->id);
		$target2->setPlayerInstance($basePlayer);

		$items			= Recordset::query('
			SELECT
				a.id,
				a.nome_' . Locale::get() . ' AS name,
				a.imagem AS image,
				a.tempo_espera AS slot,
				a.sem_turno,
				a.def_base,
				a.id_elemento,
				a.defensivo,
				a.preco,
				a.coin,
				a.req_graduacao,
				a.atk_fisico,
				a.atk_magico,
				a.drop
	
			FROM
				item a
			
			WHERE
				a.id_tipo=38'); // AND `drop`="0"

		foreach($items->result_array() as $item) {
			$item['objekt']		= 'i' . uniqid();
			$item['equippable']	= !$item['defensivo'] || $item['defensivo'] == $_POST['slot'];
			$item['tooltip']	= enhance_tooltip($item['objekt'], $item['id'], $_POST['slot'] == $item['slot'], true, $basePlayer, $_POST['slot'], $target, isset($current_enhancer[$_POST['slot']]));
			$item['bis']		= $_POST['slot'] == $item['slot'];
			$current_enhancer	= @unserialize($target['aprimoramento']);
			
			if((int)$item['sem_turno'] && (int)$target['sem_turno']) {
				$enhance_dir	= 'debuff';
				$target_dir		= 'debuff';

				$target_mod		= Recordset::query('SELECT * FROM item_modificador WHERE id_item=' . $target['id_item'], true);
				$enhance_mod	= Recordset::query('SELECT * FROM item_modificador WHERE id_item=' . $item['id'], true);

				if($target_mod->num_rows && $enhance_mod->num_rows) {
					foreach($target_mod->row_array() as $k => $v) {
						if(preg_match('/self_/', $k) && $v) {
							$target_dir	= 'buff';
							break;
						}
					}

					foreach($enhance_mod->row_array() as $k => $v) {
						if(preg_match('/self_/', $k) && $v) {
							$enhance_dir	= 'buff';
							break;
						}
					}

					if($enhance_dir != $target_dir) {
						$item['equippable']	= false;
					}					

					/*
					if ($_SESSION['universal']) {
						echo $item['name'] . "\n";
						echo $target_dir . "\n";
						echo $enhance_dir . "\n";
						echo "\n ------------------ \n";
					}
					*/
				}
			}

			if ($item['drop']) {
				if(!$basePlayer->hasItem($item['id'])) {
					$item['equippable']	= false;
				}
			}
			
			if((int)$target['sem_turno'] != (int)$item['sem_turno']) {
				$item['equippable']	= false;

				//if($_SESSION['universal']) { echo "R2"; };
			}

			if(($item['def_base'] && !$target['def_base']) || (!$item['def_base'] && $target['def_base'])) {
				$item['equippable']	= false;

				//if($_SESSION['universal']) { echo "R3"; };
			}

			if(($item['atk_fisico'] && $target2->base_f != 'atk_fisico') || ($item['atk_magico'] && $target2->base_f != 'atk_magico')) {
				$item['equippable']	= false;

				//if($_SESSION['universal']) { echo "R3"; };
			}

			if($item['id_elemento'] && !$target['id_elemento']) {
				$item['equippable']	= false;

				//if($_SESSION['universal']) { echo "R4"; };
			}

			if($target['req_graduacao'] == 1) {
				$target['req_graduacao']	= 2;
			}

			if($item['req_graduacao']  > $target['req_graduacao']) {
				$item['equippable']	= false;
	
				//if($_SESSION['universal']) { echo $target['req_graduacao'] . '->' . $item['req_graduacao'] . '//'; };
			}

			if (!$item['equippable']) {
				continue;
			}

			if(($enhance_count < $_POST['slot']) && !isset($current_enhancer[$_POST['slot']])) {
				$item['equippable']	= false;
			}

			$needed_pvp_ponits	= $item['coin'] - percent($pvp_discount, $item['coin']);

			/*if($pvp_points_avail < $needed_pvp_ponits && isset($current_enhancer[$_POST['slot']])) {
				$item['equippable']	= false;
			}*/
			
			unset($item['sem_turno']);
			unset($item['def_base']);
			unset($item['id_elemento']);
			unset($item['defensivo']);
			unset($item['req_graduacao']);
			
			$json->items[]		= $item;
		}
	}

	if(
		isset($_POST['equip']) && $_POST['equip'] &&
		isset($_POST['uid']) && is_numeric($_POST['uid']) &&
		isset($_POST['target']) && is_numeric($_POST['target']) &&
		isset($_POST['slot']) && in_array($_POST['slot'], array(1, 2, 3, 4, 5))
	) {
		$item		= Recordset::query('
			SELECT
				a.id,
				a.id_item,
				a.aprimoramento,
				b.sem_turno,
				b.def_base,
				b.id_elemento,
				b.req_graduacao

			FROM
				player_item a JOIN item b ON b.id=a.id_item
			
			WHERE
				a.id=' . $_POST['target'] . ' AND
				a.id_player=' . $basePlayer->id);

		$enhancer	= Recordset::query('
			SELECT
				a.id,
				a.sem_turno,
				a.def_base,
				a.id_elemento,
				a.atk_fisico,
				a.atk_magico,
				a.req_graduacao,
				a.coin
			
			FROM
				item a
			
			WHERE
				a.id=' . $_POST['uid'] . ' AND
				(defensivo IS NULL OR defensivo=' . $_POST['slot'] . ')');
		
		if($item->num_rows && $enhancer->num_rows) {
		 	$item		= $item->row_array();	
		 	$enhancer	= $enhancer->row_array();
		 	
		 	if((int)$enhancer['sem_turno'] != (int)$item['sem_turno']) {
				$json->messages[]	= 'Esse aprimoramento não pode ser aplicado nesse item';
		 	}

			if($item['sem_turno']) {
				$src_mod		= Recordset::query('SELECT * FROM item_modificador WHERE id_item=' . $item['id_item'], true);
				$enhance_mod	= Recordset::query('SELECT * FROM item_modificador WHERE id_item=' . $enhancer['id'], true);
				$ignore			= false;

				foreach($src_mod->row_array() as $_ => $modifier) {
					if(preg_match('/self_/', $_) && $modifier) {
						$mod_dir	= 0;
						break;
					}

					if(preg_match('/target_/', $_) && $modifier) {
						$mod_dir	= 1;
						break;
					}
				}
				
				if($enhance_mod->num_rows) {
					foreach($enhance_mod->row_array() as $_ => $modifier) {
						if(preg_match('/self_/', $_) && $modifier) {
							$enhance_dir	= 0;
							break;
						}
	
						if(preg_match('/target_/', $_) && $modifier) {
							$enhance_dir	= 1;
							break;
						}
					}
				} else {
					$enhance_dir	= 0;
					$ignore			= true;
				}
				
				if($mod_dir != $enhance_dir && !$ignore) {
					$json->messages[]	= 'Esse aprimoramento não pode ser aplicado nesse buff/debuff';
				}
			} else {
				if($item['def_base'] && ($enhancer['atk_fisico'] || $enhancer['atk_magico'])) {
					$json->messages[]	= 'Não é possível mesclar aprimoramentos de defesa em técnicas de ataque e vice-versa';
				}					

				if($enhancer['id_elemento'] && !$item['id_elemento']) {
					$json->messages[]	= 'Aprimoramentos elementais só podem ser aplicados em técnicas elementais';
				}
			}

			$l1_atks	= 0;
			$l2_atks	= 0;
			$l3_atks	= 0;
			$l4_atks	= 0;
			$l5_atks	= 0;
			
			switch($_POST['slot']) {
				case 2:
					$l1_atks	= 5;
				
					break;

				case 3:
					$l2_atks	= 13;
				
					break;

				case 4:
					$l3_atks	= 22;
				
					break;

				case 5:
					$l4_atks	= 30;
				
					break;
			}

			$atks		= $l1_atks + $l2_atks + $l3_atks + $l4_atks;
			$slot_data	= @unserialize($item['aprimoramento']);
			$has		= array(false, false, false, false, false);
			$can_equip	= true;
			
			for($f = 1; $f < $_POST['slot']; $f++) {
				if(!isset($slot_data[$f]) || (isset($slot_data[$f]) && !$slot_data[$f])) {
					$can_equip	= false;
				}
			}
			
			if(!$can_equip) {
				$json->messages[]	= 'Você não pode equipar esse aprimoramento até que todos os slots anteriores estejam preenchidos';
			}

			if(($enhance_count < $_POST['slot']) && !isset($slot_data[$_POST['slot']])) {
				$json->messages[]	= 'Você não pode equipar esse aprimoramento até você tenha um ponto de aprimoramento disponível';
			}

			$needed_pvp_ponits	= $enhancer['coin'] - percent($pvp_discount, $enhancer['coin']);

			/*if($pvp_points_avail < $needed_pvp_ponits && isset($slot_data[$_POST['slot']])) {
				$json->messages[]	= 'Você não pode equipar esse aprimoramento até você tenha pontos de batalha suficientes';
			}*/

			if($item['req_graduacao'] == 1) {
				$item['req_graduacao']	= 2;
			}

			if($enhancer['req_graduacao'] - 1 > $item['req_graduacao']) {
				$json->messages[]	= 'O aprimoramento escolhido tem uma graduação muito alta para técnica';
			}
		 	
		 	if(!sizeof($json->messages)) {
				if(!is_array($slot_data)) {
					$slot_data	= array();
				}
				
				// Existent enhancement
				if(isset($slot_data[$_POST['slot']])) {
					Recordset::update('player_item', array(
						'equipado'	=> array('escape' => false, 'value' => 'equipado-1')
					), array(
						'id_item'	=> $slot_data[$_POST['slot']],
						'id_player'	=> $basePlayer->id
					));

					Recordset::update('player', [
						'ponto_batalha_gasto'	=> ['escape' => false, 'value' => 'ponto_batalha_gasto + ' . $needed_pvp_ponits]
					], [
						'id'					=> $basePlayer->id
					]);
				} else { // Decrease count
					$basePlayer->setFlag('ponto_aprimoramento', Player::getFlag('ponto_aprimoramento', $basePlayer->id) - $_POST['slot']);
				}
				
				$slot_data[$_POST['slot']]	= $enhancer['id'];
	
				// Update enhance counter
				Recordset::update('player_item', array(
					'aprimoramento'	=> serialize($slot_data)
				), array(
					'id'			=> $_POST['target']
				));
				
				// Update enhance counter
				Recordset::update('player_item', array(
					'equipado'		=> array('escape' => false, 'value' => 'equipado+1')
				), array(
					'id'			=> $_POST['uid']
				));			 	
		 	}
		} else {
			$json->messages[]	= 'Item inválido';
		}
	}
	
	
	echo json_encode($json);
