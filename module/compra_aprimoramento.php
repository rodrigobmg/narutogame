<?php
	Player::moveLocal($basePlayer->id, 3, $basePlayer->id_vila_atual);
	
	$_SESSION['ninja_shop_key']	= md5(date("YmdHis") . rand(1, 32768));
	
	$fall 			= false;
	$fall_timer		= false;

	$pvp_discount	= 0;

	if($basePlayer->hasItem(array(22653, 22654, 22655)) && $vip_pvp = $basePlayer->getVIPItem(array(22653, 22654, 22655))) {
		$pvp_discount	= $vip_pvp['vezes'];
	}
	
	// Punição so acima do 15
	if($basePlayer->getAttribute('level') >= 15) {
		$_dbl		= hasFall($basePlayer->getAttribute('id_vila'), 3) ? 2 : 1;
		$fall_timer	= get_fall_time($basePlayer->getAttribute('id_vila'), 3);
		$fall		= $_dbl > 1 ? true : false;
	} else {
		$_dbl = 1;
	}
?>
<style>
	.ninja-shop-tab {
		background-image: url(<?php echo img('bg_360x50.jpg') ?>);
		height: 48px;
		cursor: pointer
	}
</style>
<?php 
if($_SESSION['usuario']['msg_vip']){?>
<script type="text/javascript">
	 head.ready(function () {
		$(document).ready(function() {
		if(!$.cookie("aprimoramentos")){
			$("#dialog").dialog({ 
				width: 540,
				height: 380, 
				title: '<?php echo t('ninja_shop.pop_up8'); ?>', 
				modal: true,
				close: function(){
					$.cookie("aprimoramentos", "foo", { expires: 1 });
				}

				});
		}
		});
	});
</script>
<?php }?>
<script type="text/javascript">
	var _items = [];
	var __ninja_shop_key	= "<?php echo $_SESSION['ninja_shop_key'] ?>";
	
	$(document).ready(function() {
		head.ready(function () {
			$('.t-item-qty').each(function () {
				var val		= this.value;
				var id		= this.id.replace(/[^\d]+/, '');
			
				$(this).spinit({
					height:		30,
					width:		50,
					min:		1,
					max:		20,
					initValue:	val,
					callback: function (v) {
						var i = _items[id];
						
						$('#h-item-qty-' + id).val(v);

						if(i.a) {
							if(i.c && i.r) {
								$('#d-item-preco-cr-' + id).html(sprintf('<?php echo t('item_tooltip.reqs.ponto_misto') ?>', (v * i.r), (v * i.c)));
							} else {
								$('#d-item-preco-r-' + id).html((v * i.c) + ' <?php echo t('item_tooltip.reqs.ponto_batalha') ?>');
							}
						} else {
							if(i.c) {
								$('#d-item-preco-c-' + id).html((v * i.c) + ' Créditos VIP');
							}
							
							if(i.r) {
								$('#d-item-preco-r-' + id).html('RY$ ' + (v * i.r));
							}							
						}
					}
				})
			});
		});

		$('.b-buy').bind('click', function () {
			var id = $(this).attr('rel');

			function __buy_cb() {
				$.ajax({
					url:		'?acao=ninja_shop_compra',
					data:		$('#f-item-' + id).serialize() + "&ninja_shop_key=" + __ninja_shop_key,
					type:		'post',
					dataType:	'script'
				});				
			}
			
			if(parseInt($(this).data('confirmation'))) {
				jconfirm('Você tem certeza que quer comprar esse item?', null, function () {
					__buy_cb();
				});
			} else {
				__buy_cb();
			}
		});
		
		$('.ninja-shop-tab').bind('click', function () {
			var id = $(this).attr('rel');
		
			$(".tr-item").hide();
			$(".tr-item-" + id).show();
			
			$('.ninja-shop-tab').css('background-image', 'url(<?php echo img('bg_360x50.jpg') ?>)');
			//$(this).attr('background-image', '<?php echo img('') ?>');
		});
		
		$($('.ninja-shop-tab')[0]).trigger('click');
	});
</script>
<div class="titulo-secao"><p><?php echo t('menus.compra_aprimoramento') ?></p></div>
<?php
	$total	= 40;
?>
<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/5.png); background-repeat: no-repeat;">
		<p>
			<b><?php echo t('votacao.v17') ?></b><br /><br />
			<span class="cinza" style="font-size:13px"><?php echo t('votacao.v18') ?></span><br /><br />
			<div style="width:430px; font-size:13px; font-weight: bold" class="cinza npc-points-data">
				<span class="azul"><?php echo t('votacao.v19') ?>:</span>
				<?php echo t('votacao.v22') ?>: <span class="verde avail"> <?php echo ($basePlayer->ponto_batalha - $basePlayer->ponto_batalha_gasto) ?> </span> <span class="cinza">/</span>
				<?php echo t('votacao.v21') ?>: <span class="laranja spent"><?php echo $basePlayer->ponto_batalha_gasto ?></span> <span class="cinza">/</span>
				<?php echo t('votacao.v23') ?>: <span class="azul total"><?php echo $basePlayer->ponto_batalha ?> </span>
			</div>
		</p>
	  </div>		
	</div>
</div>	
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "8811941374";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- DBZ- Ranking -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script><br />

<?php if($fall && $basePlayer->level >= 15): ?>
<?php msg('3',''.t('ninja_shop.recon1').'', ''.t('ninja_shop.recon2').'');?>
	
	<br /><br />
<?php endif; ?>
<?php
	$tipos = array(38);
?>
<div id="dialog" style="display:none">
	<div style="background:url(<?php echo img()?>layout/popup/Ninja-Shop.png); background-repeat:no-repeat; width:515px !important; height: 330px !important;">
		<div style="position:relative; width:280px; top:70px; margin-left: 230px;">
			
			<b><a href="index.php?secao=vantagens" class="linksSite3" style="font-size:16px"><?php echo t('ninja_shop.pop_up8'); ?></a></b><br /><br />
			<ul style="margin:0; padding:0;">
				<li style="margin-bottom:5px">
					<b><a href="index.php?secao=vantagens" class="linksSite3"><?php echo t('ninja_shop.pop_up9'); ?></a></b><br />
					<?php echo t('ninja_shop.pop_up10'); ?>
				</li>
			</ul>
		</div>
	</div>
</div>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0" class="with-n-tabs" id="tabs-compra-aprimoramento" data-auto-default="1">
	<tr>
	<?php for($f = 1; $f <= 5; $f++): ?>
		<td><a class="button" rel=".tr-slot-<?php echo $f ?>">Slot <?php echo $f ?></a></td>
	<?php endfor; ?>
	</tr>
</table>
<br />

<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="subtitulo-home">
			<table width="730" border="0" cellpadding="4" cellspacing="0" class="bold_branco">
				<tr>
				<td width="60" align="center">&nbsp;</td>
				<td width="260" align="center">Item</td>
				<td width="80" align="center"><?php echo t('geral.requerimentos'); ?></td>
				<td width="80" align="center"><?php echo t('geral.valor'); ?></td>
				<td width="90" align="center"><?php echo t('geral.quantidade'); ?></td>
				<td width="80" align="center"><?php echo t('geral.inventario'); ?></td>
				<td width="80" align="center"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?php foreach($tipos as $tipo): ?>
<div id="<?php echo $tipo ?>">
	<table border="0" width="730" cellspacing="0" cellpadding="2">
		<?php
			if(($basePlayer->id_vila_atual == $basePlayer->id_vila) || verifica_diplomacia($basePlayer->id_vila, $basePlayer->id_vila_atual)) {
				$items	= Recordset::query('SELECT id FROM item WHERE `drop`=\'0\' AND id_vila_reputacao=0 AND id_tipo=' . $tipo . ' ORDER BY req_level, req_graduacao', true);		
			} else {
				$items	= Recordset::query('SELECT id FROM item WHERE `drop`=\'0\' AND id_vila_reputacao=' . $basePlayer->id_vila_atual . ' AND id_tipo=' . $tipo . ' ORDER BY req_level, req_graduacao', true);
			}
			
			$cn	= 0;
			$cp = 0;
		?>
		<?php foreach($items->result_array() as $item): 
			  $pontilhado = ++$cp % 2 ? "pontilhado-listagem.jpg" : "pontilhado-listagem1.jpg";	
		?>
		<?php
			$i		= new Item($item['id']);
			$i->setPlayerInstance($basePlayer);
			$i->parseLevel();
			
			$reqs	= Item::hasRequirement($i, $basePlayer, NULL, array(
				'coin'	=> true,
				'preco'	=> true
			));
			
			if($tipo == 38 && !$i->id_vila_reputacao) {
				$avail_pvp	= $basePlayer->ponto_batalha - $basePlayer->ponto_batalha_gasto;
			
				if($i->coin && $i->preco) {
					if($basePlayer->coin < $i->coin || $avail_pvp < ($i->preco - $pvp_discount)) {
						$reqs	= false;
					}
				} elseif($i->coin) {
					if($avail_pvp < (($i->coin * $_dbl) - percent($pvp_discount, ($i->coin * $_dbl)))) {
						$reqs	= false;
					}					
				}
			}
			
			$cn++;
		?>
		<tr class="tr-item tr-item-<?php echo $tipo ?> <?php echo $tipo == 38 ? 'tr-slot-' . $i->tempo_espera : '' ?>" style="background-color: <?php echo $cn % 2 ? '#251a13' : '#413625' ?>">
			<td width="60">
				<div class="img-lateral-dojo2"><img src="<?php echo img('layout/'.$i->getAttribute('imagem')) ?>" id="i-item-<?php echo $i->id ?>" width="53" height="53"  style="margin-top:5px"/></div>
				<?php
					ob_start();

					if($tipo == 38) {
						enhance_tooltip('i-item-' . $i->id, $i, true, false, $basePlayer);
					} else {
						bonus_tooltip('i-item-' . $i->id, $i);
					}

					echo str_replace('updateTooltips();', '', ob_get_clean());
				?>
			</td>
			<td align="left" width="260">
				<strong class="amarelo" style="font-size:13px"><?php echo $i->getAttribute('nome_'.Locale::get()) ?></strong>
				<br />
				<img src="<?php echo img(); ?>layout/interno/<?php echo $pontilhado;?>" style="width:200px; height:12px;"/>
				<br />
				<?php echo $i->getAttribute('descricao_'. Locale::Get()) ?>
			</td>
			<td width="80">
				<img id="i-item-reqs-<?php echo $i->id ?>" src="<?php echo img('layout/requer.gif') ?>" />
				<?php
					ob_start();
					generic_tooltip('i-item-reqs-' . $i->id, Item::getRequirementLog());
					echo str_replace('updateTooltips();', '', ob_get_clean());
				?>
			</td>
			<td align="center" width="80">
				<form id="f-item-<?php echo $i->id ?>">
				<input type="hidden" name="id" value="<?php echo $i->id ?>" />
				<input type="hidden" name="q" id="h-item-qty-<?php echo $i->id ?>" value="<?php echo $i->id_tipo == 4 || $i->id_tipo == 2 || $i->id_tipo == 38 ? 1 : '' ?>" />
				<?php if($i->preco && $i->coin): ?>
					<?php if($i->id_tipo == 38 && !$i->id_vila_reputacao): ?>
						<span id="d-item-preco-cr-<?php echo $i->id ?>"><?php echo sprintf(t('item_tooltip.reqs.ponto_misto'), $i->preco * $_dbl, $i->coin * $_dbl); ?></span>					
					<?php else: ?>
						<?php if($i->id_tipo != 2):?>
							<input type="radio" name="pm" value="0" /> <span id="d-item-preco-r-<?php echo $i->id ?>">RY$ <?php echo $i->getAttribute('preco') * $_dbl ?></span><br />
							<input type="radio" name="pm" value="1" /> <span id="d-item-preco-c-<?php echo $i->id ?>"><?php echo $i->getAttribute('coin') * $_dbl ?> <?php echo t('topo.vip'); ?></span>
						<?php else:?>
							<span id="d-item-preco-c-<?php echo $i->id ?>"><?php echo $i->getAttribute('coin') * $_dbl ?> <?php echo t('topo.vip'); ?></span>
						<?php endif;?>	
					<?php endif;?>	
				<?php elseif($i->preco && !$i->coin): ?>
					<input type="hidden" name="pm" value="0" />
					<span id="d-item-preco-r-<?php echo $i->id ?>">RY$ <?php echo $i->preco * $_dbl ?></span>
				<?php elseif(!$i->preco && $i->coin): ?>
					<input type="hidden" name="pm" value="1" />
					<?php if($i->id_tipo == 38 && !$i->id_vila_reputacao): ?>
						<span id="d-item-preco-c-<?php echo $i->id ?>"><?php echo ($i->coin * $_dbl) - percent($pvp_discount, ($i->coin * $_dbl)) ?> <?php echo t('item_tooltip.reqs.ponto_batalha') ?></span>
					<?php else: ?>
						<span id="d-item-preco-c-<?php echo $i->id ?>"><?php echo $i->coin * $_dbl ?> <?php echo t('topo.vip'); ?></span>
					<?php endif ?>
				<?php endif; ?>
				</form>
				<script type="text/javascript">
					<?php if($i->id_tipo == 38 && !$i->id_vila_reputacao): ?>
						_items[<?php echo $i->id ?>] = {
							r:	0,
							c:	<?php echo ($i->coin * $_dbl) - percent($pvp_discount, ($i->coin * $_dbl)) ?>,
							a:	true
						}
					<?php else: ?>
						_items[<?php echo $i->id ?>] = {
							r:	<?php echo ($i->preco * $_dbl) ?>,
							c:	<?php echo $i->coin * $_dbl ?>
						}
					<?php endif; ?>
				</script>
			</td>
			<td width="90">
				<?php if($i->id_tipo == 1): ?>
					<input type="text" value="1" id="t-item-qty-<?php echo $i->id ?>" class="t-item-qty" />
				<?php elseif($i->id_tipo == 38): ?>
					<input type="hidden" value="1" id="t-item-qty-<?php echo $i->id ?>" />
					x1
				<?php else: ?>
					x1
				<?php endif; ?>
			</td>
			<td width="80">
				<span  id="d-inventario-<?php echo $i->id ?>">
				<?php if($basePlayer->hasItem($i->id)): ?>
					<?php if($i->id_tipo == 38): ?>
						<?php echo $basePlayer->getItem($i->id)->total ?>
					<?php else: ?>
						x<?php echo $basePlayer->getItem($i->id)->total ?>
					<?php endif; ?>
				<?php else: ?>
					<?php echo t('equipe_detalhe.e51'); ?>
				<?php endif; ?>
				</span>
				<?php if($i->id_tipo == 38): ?>
					de <?php echo $i->vezes_dia ?>
				<?php endif ?>
			</td>
			<td width="80">
				<?php if($reqs): ?>
				<a class="button b-buy" <?php echo $i->id_tipo == 38 ? 'data-confirmation="1"' : '' ?> rel="<?php echo $i->id ?>"><?php echo t('botoes.comprar') ?></a>
				<?php else: ?>
				<a class="button ui-state-disabled"><?php echo t('botoes.comprar') ?></a>		
				<?php endif; ?>
			</td>
		</tr>
		<tr height="4" class="<?php echo $tipo == 38 ? 'tr-slot-' . $i->tempo_espera : '' ?>"></tr>
		<?php endforeach; ?>
	</table>
</div>
<?php endforeach; ?>
<script type="text/javascript">
	updateTooltips();
</script>
<?php if ($fall_timer): ?>
	<script type="text/javascript">
		createTimer(<?php echo $fall_timer->format('%H') ?>, <?php echo $fall_timer->format('%i') ?>, <?php echo $fall_timer->format('%s') ?>, 'd-penality-timer');
	</script>
<?php endif ?>