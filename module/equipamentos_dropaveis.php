<?php
	$armors	= Recordset::query('SELECT * FROM item_tipo WHERE equipamento=1', true);	
?>
<div class="titulo-secao"><p><?php echo t('menus.equipamentos_dropaveis2')?></p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "4531896570";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Equipamentos -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/><br/>
<div style="width:730px; heigth:106px; cursor:pointer" class="with-n-tabs" id="tabs-eqp-ninja" data-auto-default="1">
	<?php foreach($armors->result_array() as $tipo): ?>
		<?php if($tipo['id'] == 29 || $tipo['id'] == 2) continue; ?>
		<?php
			$item_tipo = Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM item_tipo WHERE id=' . $tipo['id'], true)->row_array();
			
			echo '<a rel="#armor-' . $tipo['id'] . '" class="button" style="margin:5px">' . $item_tipo['nome'] ."</a>";
		?>
	<?php endforeach; ?>
	
</div>
<div style="clear:both"></div><br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
	<td class="subtitulo-home">
		<table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
		  <tr>
			<td width="250" align="center"><?php echo t('geral.nome')?></td>
			<td width="100" align="center"><?php echo t('geral.requerimentos')?></td>
			<td width="100" align="center">Chance</td>
			<td width="100" align="center">&nbsp;</td>
		  </tr>
		</table>
	</td>
  </tr>
</table>
<?php foreach($armors->result_array() as $tipo): ?>
	<?php
		if($tipo['id'] == 29 || $tipo['id'] == 2) {
			continue;	
		}	
	?>
	<table id="armor-<?php echo $tipo['id'] ?>" border="0" width="730" cellspacing="0" cellpadding="0">
		<?php
			$tipo	= $tipo['id'];
		
			$items	= Recordset::query('SELECT id,drop_rate FROM item WHERE `drop`=\'1\' AND id_tipo=' . $tipo . ' AND req_graduacao='.$basePlayer->id_graduacao , true);

	
			$cn	= 0;
		?>
		<?php foreach($items->result_array() as $item): ?>
		<?php
			$i		= new Item($item['id']);
			$i->setPlayerInstance($basePlayer);

			$reqs	= Item::hasRequirement($i, $basePlayer, NULL, array(
				'coin'		=> true,
				'req_tai'	=> false,
				'req_buk'	=> false,
				'req_nin'	=> false,
				'req_gen'	=> false,
				'preco'		=> true
			));
			
			$cn++;
		?>
		<tr class="tr-item tr-item-<?php echo $tipo ?> <?php echo $cn % 2 ? 'cor_sim' : 'cor_nao' ?>">
			<td align="center" width="250">
				<span id="i-item-<?php echo $i->id ?>" class="amarelo" data-absolute="1" style="cursor: pointer; font-weight:bold; font-size: 13px"><?php echo $i->nome ?></span>
				<?php echo bonus_tooltip('i-item-' . $i->id, $i, false, '<img class="icon" src="' . img(str_replace('%vila', $basePlayer->id_vila, $i->imagem)) .'" style="position: absolute; top: 0px; left: 0px"') ?>
				<br />
				<?php echo $i->descricao ?>
			</td>
			<td width="100">
				<img id="i-item-reqs-<?php echo $i->id ?>" src="<?php echo img('layout/requer.gif') ?>" />
				<?php echo generic_tooltip('i-item-reqs-' . $i->id, Item::getRequirementLog()) ?>
			</td>
			<td width="100">
				<?php echo floor($item['drop_rate']) ?>%
			</td>
			<td width="100">
				<?php if($basePlayer->hasItemW($i->id)): ?>
				<a class="button ui-state-green"><?php echo t('botoes.drop')?></a>

				<?php else: ?>
				<a class="button ui-state-red"><?php echo t('botoes.no_drop')?></a>
			
				<?php endif; ?>
			</td>
		</tr>
		<tr height="4"></tr>
		<?php endforeach; ?>
	</table>
<?php endforeach; ?>