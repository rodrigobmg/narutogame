<style>
	.atk {
		width: 48px;
		height: 48px;
		padding: 2px;
		margin: 2px;
		float: left;
		position:relative;
		left: -2px;
		cursor: pointer;
	}
	.atk-2{
		background-color: #0f0f0f;	
	}
	.atk-3{
		background-color: #0f0f0f;	
	}
	.atk-1{
		background-color: #0f0f0f;	
	}
</style>
<div id="actionbar-container">
	<?php if (!$evento4): ?>
		<div id="painel-pvp-flight"></div>
		<?php if ($stat_losing || $stat_winning || $batalha['bingo_book']): ?>
			<?php $has_bb_message	= false ?>
			<div id="painel-pvp-star">
				<div>
					<img src="<?php echo img('layout/combate/estrela.png') ?>" id="i-special-stat" />
					<?php ob_start() ?>
					<?php if ($stat_losing): ?>
						<b class="azul">Arqui-Inimigo</b><br /><br />
						<?php echo t('actions.a285') ?>
					<?php endif ?>
					<?php if ($stat_winning): ?>
						<b class="azul">Arqui-Inimigo</b><br /><br />
						<?php echo t('actions.a284') ?>
					<?php endif ?>
					
					<?php if(is_bingo_book($baseEnemy, $basePlayer, true, false)): ?>
						<br /><br /><b class="azul">Bingo Book</b><br /><br />
						<?php $has_bb_message	= true ?>
						<?php echo t('actions.a286') ?>
					<?php endif ?>
					<?php if(is_bingo_book($basePlayer, $baseEnemy, true, false) && !$has_bb_message): ?>
						<br /><br /><b class="azul">Bingo Book</b><br /><br />
						<?php echo t('actions.a287') ?>
					<?php endif ?>
					<?php echo generic_tooltip('i-special-stat', ob_get_clean()) ?>
				</div>
			</div>
		<?php endif ?>
	<?php endif ?>
	<div id="d-actionbar">
		<div class="titulo-secao3">
			<p><?php echo t('jogador_vip.jv35')?></p>
			<div class="icon_bar">
				<div class="pvp-atk-filter" data-filter="tai">Tai</div>
				<div class="pvp-atk-filter" data-filter="ken">Buk</div>
				<div class="pvp-atk-filter" data-filter="nin">Nin</div>
				<div class="pvp-atk-filter" data-filter="gen">Gen</div>
				<div class="pvp-atk-filter" data-filter="nt"><?php echo t('personagem_jutsu.buffs') ?></div>
				<div class="pvp-atk-filter" data-filter="neardist"><?php echo t('geral.g49')?></div>
				<?php if($basePlayer->id_batalha_multi): ?>
					<div class="pvp-atk-filter" data-filter="medicinal">Medicinal</div>
					<?php if($basePlayer->id_cla): ?>
					<div class="pvp-atk-filter" data-filter="clan">Clãs</div>
					<?php endif; ?>
					<?php if($basePlayer->getAttribute('portao')): ?>
					<div class="pvp-atk-filter" data-filter="gate"><?php echo t('geral.g50')?></div>
					<?php endif; ?>
					<?php if($basePlayer->id_selo): ?>
					<div class="pvp-atk-filter" data-filter="seal"><?php echo t('geral.g51')?></div>
					<?php endif; ?>
					<?php if($basePlayer->id_invocacao): ?>
					<div class="pvp-atk-filter" data-filter="summon"><?php echo t('geral.g52')?></div>
					<?php endif; ?>
					<?php if($basePlayer->sennin): ?>
					<div class="pvp-atk-filter" data-filter="sennin">M. Sennin</div>
					<?php endif; ?>
					<?php if($basePlayer->bijuu): ?>
					<div class="pvp-atk-filter" data-filter="bijuu">Bijuus</div>
					<?php endif; ?>
					<?php if($basePlayer->mist_sword): ?>
					<div class="pvp-atk-filter" data-filter="swords">Espadas da Névoa</div>
					<?php endif; ?>
				<?php endif ?>
			</div>
		</div>

	<?php
		$items 			= $basePlayer->getItems(array(1, 2, 5, 6, 16, 17, 20, 21, 23, 24, 26, 39, 41));
		$sorted_items	= array();
		$float_inc		= 0.001;

		foreach($items as $item) {
			if(!$item->dojo_ativo) {
				continue;
			}

			$item->setPlayerInstance($basePlayer);
			$item->parseLevel();
			
			if($basePlayer->id_batalha_multi) {
				$item->apply_team_modifiers();
			}
			
			if(in_array($item->id_habilidade, array(2,3))) {
				$sorted_items[(string)($item->atk_magico + $float_inc)]	= $item;
				//echo ($item->atk_magico + $float_inc) . "\n";
			} else {
				$sorted_items[(string)($item->atk_fisico + $float_inc)]	= $item;				
				//echo ($item->atk_fisico + $float_inc) . "\n";
			}

			$float_inc	+= 0.001;			
		}
		
		ksort($sorted_items);

		//print_r(array_keys($sorted_items));
	?>
	<div id="attack-list">
		<?php foreach($sorted_items as $item): ?>
			<?php
				// Armas não equipadas
				if($item->id_tipo == 2 && !$item->equipado) {
					continue;
				}
				
				$group	= "";
				$level	= "";
				$role	= "";

				switch($item->id_tipo) {
					case 1:
					case 2:
						$role	= 'neardist';

						/*
						if($item->sem_turno) {
							$role .= ' atk-nt';
						}
						*/
						
						break;
				
					case 24:
						$role	= 'medicinal';
						
						break;
					case 16:
					case 17:
					case 20:
					case 21:
					case 23:
					case 26:
						$group	= $item->id_tipo;
						$level	= $item->ordem;
					
						switch($item->id_tipo) {
							case 16:
								$role	= 'clan';
							
								break;

							case 17:
								$role	= 'gate';
							
								break;

							case 20:
								$role	= 'seal';
							
								break;

							case 21:
								$role	= 'summon';
							
								break;

							case 23:
								$role	= 'bijuu';
							
								break;

							case 26:
								$role	= 'sennin';
							
								break;

							case 39:
								$role	= 'swords';
							
								break;
						}
						
						$role	.= ' atk-buff';
					
						break;
					
					case 5:
					case 6:
					case 41:
						if($item->sem_turno) {
							$role .= ' atk-nt';
						} else {
							switch($item->id_habilidade) {
								case 1:	$role	= 'tai';	break;
								case 2:	$role	= 'nin';	break;
								case 3:	$role	= 'gen';	break;
								case 4:	$role	= 'ken';	break;
							}							
						}

						break;
				}
			?>
	
			<?php if($item->id_tipo == 2 && !$item->equipado) continue; ?>
			<?php if($basePlayer->id_batalha_multi && in_array($item->id_tipo, array(16, 17, 20, 21, 23, 24, 26, 39, 41))): ?>
			<div class="atk atk-<?php echo $item->id_tipo ?> atk-<?php echo $role ?>">
				<img id="atki-<?php echo $item->id ?>" src="<?php echo img("layout/".$item->imagem) ?>" role="<?php echo $item->id ?>" width="48" />
			</div>		
			<?php endif; ?>
			<?php if(!in_array($item->id_tipo, array(1, 2, 5, 6, 41))) continue; ?>
			<div class="atk atk-<?php echo in_array($item->id_tipo, array(1, 2)) ? '0' : $item->getAttribute('id_habilidade') ?> atk-<?php echo $role ?>" style="z-index:10000">
				<div class="atki-<?php echo $item->id ?>" style="position: absolute; text-align: center; width: 48px; height: 48px; top: 9px; font-size: 31px; z-index:-1"></div>
				<img id="atki-<?php echo $item->id ?>" src="<?php echo img("layout/".$item->getAttribute('imagem')) ?>" role="<?php echo $item->id ?>" width="48"  />
			</div>
		<?php endforeach; ?>
		<div class="break"></div>
	</div>
	<div class="break"></div>
</div>
	<div class="bottom"></div>
	<div class="break"></div>
</div>

<script>
	var _items	= [];
	
	<?php foreach($items as $item): ?>
	<?php
		//$item->setPlayerInstance($basePlayer);
		//$item->parseLevel();
	?>
	_items['<?php echo $item->id ?>'] = <?php echo painel_pvp_item_js($item) ?>;
	<?php endforeach; ?>
</script>