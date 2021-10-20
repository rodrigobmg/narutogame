<?php
	$tipos			= array(1, 2, 4, 9, 10, 11, 12, 13, 14, 15, 29, 30, 31, 38);
	$weapon_limit	= 40 + ($basePlayer->hasItem(22780) ? 10 : 0);

	if(!isset($_POST['id']) || !isset($_POST['q']) || (
		isset($_POST['id']) &&  isset($_POST['q']) &&
		!is_numeric($_POST['id']) || !is_numeric($_POST['q'])
	)) {
		redirect_to('negado', NULL, array('e' => 1));
	}
	
	if($_SESSION['ninja_shop_key'] != $_POST['ninja_shop_key'] || !$_SESSION['ninja_shop_key']) {
		die("jalert('".t('actions.a230')."', null, function () {location.reload()})");
	}
	
	$item	= new Item($_POST['id']);
	$item->setPlayerInstance($basePlayer);
	
	$reqs	= Item::hasRequirement($item, $basePlayer, NULL, array(
		'coin'		=> true,
		'preco'		=> true,
		'req_tai'	=> true,
		'req_nin'	=> true
	));

	if(!$reqs){
		die('jalert("'.t('actions.a231').'")');
	}
	
	if(!in_array($item->id_tipo, $tipos)) {
		die('jalert("'.t('actions.a232').'")');
	}

	if(in_array($item->id_tipo, array(2, 4, 10, 11, 12, 13, 14, 15, 29, 30, 31))) {
		$_POST['q'] = 1;
		
		if($basePlayer->hasItem($item->id)) {
			die('jalert("'.t('actions.a233').'")');
		}
	} else {
		if($_POST['q'] <= 0) {
			redirect_to('negado', NULL, array('e' => 2));
		}
	}

	$total	= $basePlayer->hasItem($item->id) ? $basePlayer->getItem($item->id)->total : 0;
	$v		= $item->id_tipo == 9 ? 1 : 3;
	
	// Como esse script é compartilhado com o ramen shop, a variavel v
	// define que npc base deve ser usado pra calcular a perda
	if($basePlayer->getAttribute('level') >= 15) {
		$_dbl = hasFall($basePlayer->getAttribute('id_vila'), $v) ? 2 : 1;
		$fall = $_dbl > 1 ? true : false;
	} else {
		$_dbl = 1;
	}
	
	// Aplica a punição do gaurdião no item
	$item->setLocalAttribute('coin', $item->coin * $_dbl);
	$item->setLocalAttribute('preco', $item->preco * $_dbl);
	
	$limit	= 20;
	
	// No caso dos aprimoramentos quem define o limite é o proprio item
	if($item->id_tipo == 38) {
		$limit	= $item->vezes_dia;
		
		if(($total + $_POST['q']) > $limit) {
			die('jalert("'.sprintf(t('actions.a268'), $limit).'");');
		}
		
		if(!$item->id_vila_reputacao) { // Aprimoramentos por ponto pvp e npc
			$pvp_discount	= 0;
	
			if($basePlayer->hasItem(array(22653, 22654, 22655)) && $vip_pvp = $basePlayer->getVIPItem(array(22653, 22654, 22655))) {
				$pvp_discount	= $vip_pvp['vezes'];
			}
			
			$pvp_points_avail	= $basePlayer->ponto_batalha - $basePlayer->ponto_batalha_gasto;			
			$needed_pvp_ponits	= (($item->coin * $_dbl)  - percent($pvp_discount, $item->coin * $_dbl))  * $_POST['q'];
			
			if($item->coin && $item->preco) {
				$needed_pvp_ponits	= (($item->preco * $_dbl) - percent($pvp_discount, $item->preco * $_dbl)) * $_POST['q'];
				$needed_vip			= ($item->coin * $_dbl) * $_POST['q'];
				
				if($basePlayer->coin < $needed_vip || $pvp_points_avail < $needed_pvp_ponits) {
					die('jalert("'.t('actions.a271').'");');				
				}
				
				Recordset::update('player', array(
					'ponto_batalha_gasto'	=> array('escape' => false, 'value' => 'ponto_batalha_gasto+' . $needed_pvp_ponits)
				), array(
					'id'	=> $basePlayer->id
				));
				
				gasta_coin($needed_vip, $item->id);
			} elseif($item->coin) {
				if($pvp_points_avail < $needed_pvp_ponits) {
					die('jalert("'.t('actions.a270').'");');
				}
	
				Recordset::update('player', array(
					'ponto_batalha_gasto'	=> array('escape' => false, 'value' => 'ponto_batalha_gasto+' . $needed_pvp_ponits)
				), array(
					'id'	=> $basePlayer->id
				));
			}
			
			Recordset::update('player_item', array(
				'data_ins'	=> array('escape' => false, 'value' => 'NOW()')
			), array(
				'id_player'	=> $basePlayer->id,
				'id_item'	=> $item->id
			));
		} else {
			if(($item->getAttribute('preco') * $_POST['q']) > $basePlayer->getAttribute('ryou')) {
				die('jalert("'.t('actions.a235').'")');
			}

			Recordset::update('player', array(
				'ryou'	=> array('escape' => false, 'value' => 'ryou - ' . ($item->getAttribute('preco') * $_POST['q']))
			), array(
				'id'	=> $basePlayer->id
			));
		}

		$basePlayer->addItemW($item->id, $_POST['q']);
	} else {
		// Qualquer item que não seja ramen VIP tem limite de 20
		if(($total + $_POST['q']) > $limit && ($item->id_tipo != 9 && $item->coin == 0) && $item->id_tipo != 1) {
			die('jalert("'.t('actions.a234').'");');
		}
		
		if($item->id_tipo == 2 && !isset($_POST['pm'])) {
			$_POST['pm']	= 1;	
		}
		
		if($item->preco && $_POST['pm'] == 0) { // Ryou
			if(($item->getAttribute('preco') * $_POST['q']) > $basePlayer->getAttribute('ryou')) {
				die('jalert("'.t('actions.a235').'")');
			}
			
			$pay_mode	= 0;
			$uni		= $_POST['q'];
		} elseif($item->getAttribute('coin') && $_POST['pm'] == 1) { // Coin
			$pay_mode	= 1;
			$uni		= $_POST['q'];
	
			if($item->id_tipo == 9) {
				$_POST['q']	= 20;
				$uni		= 1;
			}
	
			if(($item->getAttribute('coin') * $uni) > $basePlayer->coin) {
				die('jalert("'.t('actions.a236').'")');
			}			
	
			usa_coin($item->id, $item->getAttribute('coin'));
		} else {
			die('jalert("'.t('actions.a237').'")');
		}
		
		if($item->id_tipo == 1) {
			$bought = Player::getFlag('armas_semanais', $basePlayer->id);
			
			if(($bought + $_POST['q']) > $weapon_limit) {
				die('jalert("'.t('actions.a238').'")');
			} else {
				$bought += $_POST['q'];
			}
			
			$basePlayer->setFlag('armas_semanais', $bought);
		}
		
		$basePlayer->addItem($_POST['id'], $_POST['q'], $pay_mode, $_dbl, $uni);
		
		// Conquista --->
			arch_parse(NG_ARCH_ITEM_N, $basePlayer, NULL, $item, $_POST['q']);
		// <---
	
		// Exp da equipe --->
			if($basePlayer->getAttribute('id_equipe')) {
				$exp_m_max	= $_POST['q'] > 20 ? 20 : $_POST['q'];
				$exp_m_max	= $exp_m_max + Player::getFlag('equipe_exp_shop', $basePlayer->id) > 20 ? (20 - Player::getFlag('equipe_exp_shop', $basePlayer->id)) : $exp_m_max;
				
				if($exp_m_max > 0) {
					equipe_exp(5 * $exp_m_max);	
					$basePlayer->setFlag('equipe_exp_shop', Player::getFlag('equipe_exp_shop', $basePlayer->id) + $exp_m_max);
				}
			}
		
		if($item->id_tipo == 9){
			
			// Missões diárias de Compra de Temas
				if($basePlayer->hasMissaoDiariaPlayer(12)->total){
					// Adiciona os contadores nas missões de tempo.
					Recordset::query("UPDATE player_missao_diarias set qtd = qtd + ".$_POST['q']." 
								 WHERE id_player = ". $basePlayer->id." 
								 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 12) 
								 AND completo = 0 ");
				}
		}
		if($item->id_tipo == 1){
			
			// Missões diárias de Compra de Temas
				if($basePlayer->hasMissaoDiariaPlayer(13)->total){
					// Adiciona os contadores nas missões de tempo.
					Recordset::query("UPDATE player_missao_diarias set qtd = qtd + ".$_POST['q']." 
								 WHERE id_player = ". $basePlayer->id." 
								 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 13) 
								 AND completo = 0 ");
				}
		}
		if($item->id_tipo == 2){
			
			// Missões diárias de Compra de Temas
				if($basePlayer->hasMissaoDiariaPlayer(14)->total){
					// Adiciona os contadores nas missões de tempo.
					Recordset::query("UPDATE player_missao_diarias set qtd = qtd + ".$_POST['q']." 
								 WHERE id_player = ". $basePlayer->id." 
								 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 14) 
								 AND completo = 0 ");
				}
		}
		// <---
	
		// Devolve a grana do cara da arma anterior e remove ela --->
			/*
			if($item->id_tipo == 2) {
				$ryou	= 0;
			
				foreach($basePlayer->getItems() as $i) {
					if($i->id_tipo == 2 && $i->id != $item->id) {
						$basePlayer->removeItem($i);
						
						if($i->preco) {
							$ryou	+= floor($i->preco / 2);
						}
						
						echo "$('#d-inventario-" . $i->id . "').html('Nenhum');" . PHP_EOL;
					}
				}
				
				if($ryou) {
					$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') + $ryou);
				}
			}
			*/
		// <--		
	}
?>
jalert('<?php echo t('actions.a239')?>');
$('#cnPRYt').html('<?php echo $basePlayer->getAttribute('ryou') ?>');
<?php if($basePlayer->getItem($item->id)->id_tipo == 38): ?>
	$('#d-inventario-<?php echo $item->id ?>').html('<?php echo Recordset::query('SELECT qtd FROM player_item WHERE id_player=' . $basePlayer->id . ' AND id_item=' . $item->id)->row()->qtd ?>');
<?php else: ?>
	$('#d-inventario-<?php echo $item->id ?>').html('x<?php echo Recordset::query('SELECT qtd FROM player_item WHERE id_player=' . $basePlayer->id . ' AND id_item=' . $item->id)->row()->qtd ?>');
<?php endif ?>
$('#d-player-creditos').html('<?php echo $basePlayer->getAttribute('coin') ?>');

<?php if($item->id_tipo == 38): ?>
<?php
	$player_data	= Recordset::query('SELECT ponto_batalha, ponto_batalha_gasto FROM player WHERE id=' . $basePlayer->id)->row_array();
?>

$('.npc-points-data .avail').html(<?php echo $player_data['ponto_batalha'] - $player_data['ponto_batalha_gasto'] ?>);
$('.npc-points-data .spent').html(<?php echo $player_data['ponto_batalha_gasto'] ?>);
$('.npc-points-data .total').html(<?php echo $player_data['ponto_batalha'] ?>);
<?php endif ?>
