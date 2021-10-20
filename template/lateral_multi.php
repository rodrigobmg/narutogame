<?php
	if($tpl_player->id == $basePlayer->id) {
		$instance	= $basePlayer;
	} else {
		$instance	= new Player($tpl_player->id);
	}

	$mods	= $instance->getModifiers();
	$types	= [16, 17, 20, 21, 23, 39];
	$unique	= [23, 39];
	
	$props	= [
		16	=> 'id_cla',
		20	=> 'id_selo',
		21	=> 'id_invocacao',
		26	=> 'id_sennin'
	];

	$images	= [
		16	=> 'layout/clas/%id/%order.png',
		17	=> 'layout/portoes/%order.gif',
		20	=> 'layout/selos/%id/%order.gif',
		21	=> 'layout/invocacoes/%id/%order.png',
		23	=> 'layout/bijuus-batalha/%item.png',
		26	=> 'layout/mode_sennin/%id/%order.png',
		39	=> 'layout/bijuus-batalha/%item.png'
	];
?>
<div class="player-utility">
	<?php foreach ($types as $type): ?>
		<?php
			$tpl_items	= $instance->getItems($type);
			$have	= false;
			$order	= 1;

			if(!sizeof($tpl_items)) {
				continue;
			}

			foreach ($tpl_items as $item) {
				foreach ($mods as $mod) {
					if ($mod['id'] == $item->id && $mod['direction'] == 0) {
						$have	= false;
						$order	= $item->ordem;

						break 2;
					}
				}
			}

			if(isset($props[$type])) {
				$prop	= $instance->{$props[$type]};
			} else {
				$prop	= '';
			}

			$image	= $images[$type];
			$image	= str_replace('%order', $order, $image);
			$image	= str_replace('%item', $item->id, $image);
			$image	= str_replace('%id', $prop, $image);
		?>
		<div class="utility" id="player-utility-<?php echo $type ?>">
			<div class="current" style="<?php echo !$have ? 'opacity: .3' : '' ?>">
				<img src="<?php echo img($image) ?>" />
			</div>
			<?php if (!$tpl_is_enemy): ?>
				<div class="selector" style="width: <?php echo sizeof($tpl_items) * 39 ?>px">
					<?php foreach ($tpl_items as $item): ?>
						<div class="atk atk-buff atk-utility" data-id="<?php echo $item->id ?>" data-type="<?php echo $item->id_tipo ?>">
						<?php if (in_array($type, $unique)): ?>
							<img class="icon" src="<?php echo img($image) ?>" width="36" />
						<?php else: ?>
							<img class="icon" src="<?php echo img('layout/' . $item->imagem) ?>" width="36" />
						<?php endif ?>
						</div>
					<?php endforeach ?>
				</div>
			<?php endif ?>
		</div>
	<?php endforeach ?>
</div>