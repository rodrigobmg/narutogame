<?php
	$consumivel		= array(8, 9, 4);
	$tipos			= array(9, 4, 8, 1, 27, 40);
	$usavel_batalha	= array();
	$uso_total_perg = 5;
	$types_shown	= [];

	// JSON
	$json			= new stdClass();
	$json->success	= false;
	$json->messages	= [];
	$json->redirect	= null;
	$errors			= [];

	switch($_POST['mode']) {
		case 0: // Mostrar inventaro
			foreach($tipos as $tipo) {
				$items = $basePlayer->getItems($tipo, true);
?>
<?php foreach ($items as $item): ?>
	<?php if (!isset($types_shown[$item->id_tipo])): ?>
		<?php $types_shown[$item->id_tipo]	= true ?>
		<div class="section">
			<?php echo Recordset::query('SELECT nome_' . Locale::get() . ' AS name FROM item_tipo WHERE id=' . $item->id_tipo)->row()->name; ?>
		</div>
	<?php endif ?>
	<?php
		// Ramen agora precisa da injeção do player por causa da passsiva cozinheiro
		if (in_array($item->id_tipo, [9, 1, 2])) {
			$item->setPlayerInstance($basePlayer);
		}


		$id		= "i-inventory-item-" . $item->id;
		$id2	= "i" . $id;
	?>
	<div class="item" id="<?php echo $id ?>" data-price="<?php echo $item->preco ?>" data-name="<?php echo $item->nome ?>" data-sell="<?php echo $item->id_tipo == 1 ? 1 : '' ?>" data-id="<?php echo $item->id ?>" data-quantity="<?php echo $item->total ?>" data-usable="<?php echo in_array($item->id_tipo, $consumivel) ?>">
		<img id="<?php echo $id2 ?>" src="<?php echo img('layout/' . $item->imagem) ?>" width="38" title="<?php echo $item->nome ?>" name="<?php echo $item->nome ?>" />
		<?php if($item->getAttribute('uso_unico') || $item->drop): ?>
			<?php if ($item->drop || in_array($item->id_tipo, [1, 9])): ?>
				<div class="qty" data-tooltip-float="1">x <?php echo $item->total ?></div>
			<?php else: ?>
				<div class="qty" data-tooltip-float="1">x <?php echo 5 - $item->uso ?></div>
			<?php endif ?>
		<?php endif ?>
		<?php echo bonus_tooltip($id2, $item) ?>
	</div>
<?php endforeach ?>
<?php
			}
				
			break;		
		case 1: // Usar item
			if($basePlayer->getAttribute('id_batalha') || $basePlayer->getAttribute('id_batalha_multi')) {
				$errors[]	= 'Você não pode usar itens durante a batalha!';
			} elseif($basePlayer->getAttribute('hospital')) {
				$errors[]	= 'Você não pode usar itens quando esta no hospital!';
			} else {
				if(!is_numeric($_POST['id'])) {
					redirect_to("negado");
				}
	
				$it	= $basePlayer->getItem($_POST['id']);
				
				if(!on($it->getAttribute('id_tipo'), array(8, 9, 4))) {
					die();
				}

		      	if($basePlayer->exp >= Player::getNextLevel($basePlayer->level)) {
		      		$errors[]	= t('actions.a182');
				}

				if ($it->id_tipo == 9) {
					$it->setPlayerInstance($basePlayer);
				}
				
				if($it->getAttribute('id_tipo') != 4) {
					if(
						$basePlayer->getAttribute('hp')  >= $basePlayer->getAttribute('max_hp') &&
						$basePlayer->getAttribute('sp')  >= $basePlayer->getAttribute('max_sp') &&
						$basePlayer->getAttribute('sta') >= $basePlayer->getAttribute('max_sta')
					) {
						$errors[]	= t('actions.a280');
					}

					if(!sizeof($errors)) {
						if($it->getAttribute('consume_hp'))  $basePlayer->consumeHP($it->getAttribute('consume_hp'));
						if($it->getAttribute('consume_sp'))  $basePlayer->consumeSP($it->getAttribute('consume_sp'));
						if($it->getAttribute('consume_sta')) $basePlayer->consumeSTA($it->getAttribute('consume_sta'));

						$basePlayer->removeItem($it);
					}
				} else if($it->id_tipo == 4) {
					if($basePlayer->credibilidade == 0) {
						$errors[]	= 'Você não pode ir ao mapa com punição.';
					}
					if($basePlayer->id_graduacao < 2) {
						$errors[]	= t('actions.a178');
					}
					if($basePlayer->id_arena > 0){
						$errors[]	= t('actions.a251');
					}
				
					if($basePlayer->id_missao && $basePlayer->missao_comum) {
						$errors[]	= t('actions.a179');
					}

					if($basePlayer->treinando) {
						$errors[]	= t('actions.a180');
					}

					if($basePlayer->treino_tempo_jutsu) {
						$errors[]	= t('actions.a181');
					}

					if($basePlayer->id_sala_multi_pvp || $basePlayer->id_random_queue) {
						$errors[]	= t('actions.a276');
					}

					if(!$it->drop && $it->getAttribute('uso') >= $uso_total_perg) {
						$errors[]	= t('actions.a289');
					}

					$vila	= $it->bonus_hp;

					if($vila != $basePlayer->id_vila) {
						if($basePlayer->hp < ($basePlayer->max_hp / 2)) {
							$errors[]	= t('actions.a183');
						}
					
						if($basePlayer->sp < ($basePlayer->max_sp / 2)) {
							$errors[]	= t('actions.a184');
						}
					
						if($basePlayer->sta < ($basePlayer->max_sta / 2)) {
							$errors[]	= t('actions.a185');
						}
					}

					if($vila == $basePlayer->id_vila_atual) {
						$errors[]	= t('actions.a279');
					}

			      	if(!sizeof($errors)) {
						Recordset::update('player', array(
							'dentro_vila'	=> 0,
							'id_vila_atual'	=> $vila
						), array(
							'id'			=> $basePlayer->id
						));
						
						$data	= array(
							'vila_atual'	=> $vila,
							'dentro_vila'	=> 0					
						);
						
						if(!$basePlayer->id_vila_atual) {
							$data['xpos']	= 9;
							$data['ypos']	= 19;
						}
						
						Recordset::update('player_posicao', $data, array(
							'id_player'		=> $basePlayer->id
						));
						
						Recordset::update('player_item', array(
							'uso'		=> array('escape' => false, 'value' => 'uso + 1')
						), array(
							'id_player'	=> $basePlayer->id,
							'id_item'	=> $it->id
						));
						
						$script_redirect = true;

						if($it->drop) {
							$basePlayer->removeItem($it);
						}

						$json->redirect	= '?secao=mapa_vila';
			      	}
				}
			}
			
			$basePlayer->atCalc();

			$json->hp			= new stdClass();
			$json->hp->current	= $basePlayer->getAttribute('hp');
			$json->hp->max		= $basePlayer->getAttribute('max_hp');

			$json->sp			= new stdClass();
			$json->sp->current	= $basePlayer->getAttribute('sp');
			$json->sp->max		= $basePlayer->getAttribute('max_sp');

			$json->sta			= new stdClass();
			$json->sta->current	= $basePlayer->getAttribute('sta');
			$json->sta->max		= $basePlayer->getAttribute('max_sta');

			$json->messages		= $errors;
			$json->success		= sizeof($errors) ? false : true;

			die(json_encode($json));
		
			break;
		
		case 2:
			if(!isset($_POST['item']) || (isset($_POST['item']) & !is_numeric($_POST['item']))) {
				die();
			}
			
			$player_item	= Recordset::query('SELECT * FROM player_item WHERE id_item_tipo=1 AND id_item=' . $_POST['item'] . ' AND id_player=' . $basePlayer->id);
			
			if(!$player_item->num_rows) {
				die();				
			}
			
			$player_item	= $player_item->row_array();
			$item			= Recordset::query('SELECT preco FROM item WHERE id=' . $_POST['item'], true)->row_array();
			
			$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') + (($item['preco'] * $player_item['qtd']) / 2));
			
			Recordset::delete('player_item', array(
				'id_player'	=> $basePlayer->id,
				'id_item'	=> $_POST['item']
			));

			$json->success	= true;
			$json->ryou		= $basePlayer->getAttribute('ryou');

			die(json_encode($json));
			
			break;
	}
