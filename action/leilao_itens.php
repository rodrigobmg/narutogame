<?php
	if(isset($_POST['watched']) && $_POST['watched']) {
		$where	= 'id_player_last=' . $basePlayer->id;
		$watch	= true;
	} else {
		$where	= 'id_player=' . $basePlayer->id;
		$watch	= false;
	}
	
	$items	= Recordset::query('
		SELECT 
			id_item,
			TIMEDIFF(end_time, NOW()) AS diff,
			id_player,
			bid,
			buyout,
			last_bid,
			id_player_last,
			total,
			id_player_last,
			(CASE WHEN id_player_last != 0 THEN (SELECT nome FROM player WHERE id=id_player_last) END) AS nome_player
		
		FROM
			leilao
		
		WHERE ' . $where);
?>
<?php if($watch): // Limpa os timers antigos ?>
<script>
	for(var i in _tsw) {
		clearTimer('tsw-tmr-' + i);
	}
	
	_tsw = [];
</script>
<?php endif; ?>
<?php foreach($items->result_array() as $item): ?>
<?php
	if(!$watch) {
		$i = $basePlayer->getItem($item['id_item']);	
	} else {
		$i = new Item($item['id_item']);
	}
?>
<div class="<?php echo $watch ? 'wa' : 'my' ?>-item <?php echo $watch ? ($item['id_player_last'] != $basePlayer->id ? 'wa-item-other' : 'wa-item-my') : '' ?>">
	<img id="i-<?php echo $watch ? 'wa' : 'my' ?>-<?php echo $i->id ?>" src="<?php echo img($i->imagem) ?>" />
	<?php ob_start(); ?>
	<b><?php echo $i->nome ?></b>
	<hr />
	<ul>
		<li>Valor de leilão inicial: <?php echo $item['bid'] ?></li>
		<li>Valor de leilão atual: <?php echo $item['last_bid'] ? $item['last_bid'] : 'Nenhum lance ainda' ?></li>
		<li>Último lance: <?php echo $item['nome_player'] ? $item['nome_player'] : 'Nenhum' ?></li>
		<?php if($item['buyout']): ?>
		<li>Valor de compra: <?php echo $item['buyout'] ?></li>
		<?php endif; ?>
		<li>Tempo restante: <span id="<?php echo $watch ? 'tsw' : 'd' ?>-tmr-<?php echo $i->id ?>">--</span></li>
		<li>Quantidade: <?php echo $item['total'] ?></li>
	</ul>
	<?php generic_tooltip('i-' . ($watch ? 'wa' : 'my') . '-' . $i->id, ob_get_clean()); ?>
</div>
<script>
	<?php echo $watch ? '_tsw' : '_ts' ?>[<?php echo $i->id ?>] = {
		tr: '<?php echo $item['diff'] ?>'
	}
</script>
<?php endforeach; ?>
<?php if($watch): ?>
<script>
	$(document).ready(function() {
		for(var i in _tsw) {
			tmr = _tsw[i].tr.split(':');
			
			createTimer(parseInt(tmr[0]), parseInt(tmr[1]), parseInt(tmr[2]), 'tsw-tmr-' + i, function () {}, 'tsw-tmr-' + i);
		}	
	});
</script>
<?php endif; ?>