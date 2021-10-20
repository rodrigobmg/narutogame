<?php if(!$basePlayer->tutorial()->ramen){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 23,
	 
	  steps: [
	  {
		element: ".subtitulo-home",
		title: "<?php echo t("tutorial.titulos.academia.1");?>",
		content: "<?php echo t("tutorial.mensagens.academia.1");?>",
		placement: "top"
	  },{
		element: ".inventory-container",
		title: "<?php echo t("tutorial.titulos.academia.2");?>",
		content: "<?php echo t("tutorial.mensagens.academia.2");?>",
		placement: "right"
	  }
	]});
	//Renicia o Tour
	tour.restart();
	// Initialize the tour
	tour.init(true);
	// Start the tour
	tour.start(true);
});
</script>	
<?php } ?>
<?php
	Player::moveLocal($basePlayer->id, 1, $basePlayer->id_vila_atual);

	$_SESSION['ninja_shop_key']	= md5(date("YmdHis") . rand(1, 32768));

	$fall = false;
	$fall_timer = false;
	
	// Punição so acima do 15
	if($basePlayer->getAttribute('level') >= 15) {
		$_dbl 		= hasFall($basePlayer->getAttribute('id_vila'), 1) ? 2 : 1;
		$fall_timer	= get_fall_time($basePlayer->getAttribute('id_vila'), 1);
		$fall 		= $_dbl > 1 ? true : false;
	} else {
		$_dbl = 1;
	}	
?>
<?php if($_SESSION['usuario']['msg_vip']){?>
<script type="text/javascript">
	 head.ready(function () {
		$(document).ready(function() {
		if(!$.cookie("ramen_shop")){
			$("#dialog").dialog({ 
			width: 520,
			height: 430, 
			title: '<?php echo t('ramen_shop.pop_up_title'); ?>', 
			modal: true,
			close: function(){
				$.cookie("ramen_shop", "foo", { expires: 1 });
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
						
						if(i.c) {
							$('#d-item-preco-c-' + id).html((v * i.c) + ' Créditos VIP');
						}
						
						if(i.r) {
							$('#d-item-preco-r-' + id).html('RY$ ' + (v * i.r));
						}
					}
				})
			});
		});
	

		$('.b-buy').bind('click', function () {
			var id = $(this).attr('rel');

			var	_cb	= function () {
				$.ajax({
					url:		'?acao=ninja_shop_compra',
					data:		$('#f-item-' + id).serialize() + "&ninja_shop_key=" + __ninja_shop_key,
					type:		'post',
					dataType:	'script'
				});
			}

			if(_items[id].c) {
				jconfirm('Você está prestes a gastar créditos VIP, continuar?', null, _cb);
			} else {
				_cb.apply();
			}
		});
	});
</script>
<div id="dialog" style="display:none">
	<div style="background:url(<?php echo img()?>layout/popup/Ramen.png); background-repeat:no-repeat; width:470px !important; height: 380px !important;">
		<div style="position:relative; width:280px; top:80px; padding-left: 18px;">
			
			<b><a href="index.php?secao=vantagens" class="linksSite3" style="font-size:16px"><?php echo t('ranks.vantagens_titulo'); ?></a></b><br /><br />
			<ul style="margin:0; padding:0;">
				<li style="margin-bottom:5px">
					<b><a href="index.php?secao=vantagens" class="linksSite3"><?php echo t('ramen_shop.pop1'); ?></a></b><br />
                    <?php echo t('ramen_shop.pop2'); ?>
				</li>
				<li style="margin-bottom:5px">
					<b><a href="index.php?secao=vantagens" class="linksSite3"><?php echo t('ramen_shop.pop3'); ?></a></b><br />
                    <?php echo t('ramen_shop.pop4'); ?>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="titulo-secao"><p><?php echo t('titulos.ramen_shop'); ?></p></div><br />

<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "4671497379";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Ramen -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script><br />
<br />

<?php if($fall && $basePlayer->getAttribute('level') >= 15): ?>
	<?php msg('3', t('ramen_shop.construcao'), t('ramen_shop.construcao2'));?>
	<br /><br />
<?php endif; ?>
<?php
	$tipos = array(9);
?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="subtitulo-home">
			<table width="730" border="0" cellpadding="4" cellspacing="0" class="bold_branco">
				<tr>
				<td width="60" align="center">&nbsp;</td>
				<td width="240" align="center"><?php echo t('geral.item'); ?></td>
				<td width="80" align="center"><?php echo t('geral.requerimentos'); ?></td>
				<td width="80" align="center"><?php echo t('geral.valor'); ?></td>
				<td width="90" align="center"><?php echo t('geral.quantidade'); ?></td>
				<td width="80" align="center"><?php echo t('geral.inventario'); ?></td>
				<td width="100" align="center"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table border="0" width="730" cellspacing="0" cellpadding="4">

<?php foreach($tipos as $tipo): ?>
	<?php
		if(($basePlayer->id_vila_atual == $basePlayer->id_vila) || verifica_diplomacia($basePlayer->id_vila, $basePlayer->id_vila_atual)) {
			$items	= Recordset::query('SELECT id, req_level FROM item WHERE `drop`=\'0\' AND id_tipo=9 AND id_vila_reputacao=0 ORDER BY req_level ASC', true);
		} else {
			$items	= Recordset::query('SELECT id, req_level FROM item WHERE `drop`=\'0\' AND id_tipo=9 AND id_vila_reputacao=' . $basePlayer->getAttribute('id_vila_atual') . ' ORDER BY req_level ASC', true);
		}
		
		$cn	= 0;
		$cp = 0;
	?>
	<?php foreach($items->result_array() as $item):
		 
	?>
	<?php
		$i		= new Item($item['id']);	
		$reqs	= Item::hasRequirement($i, $basePlayer, NULL, array(
			'coin'	=> true,
			'preco'	=> true
		));
		
		
		$cn++;
	?>
	<tr class="tr-item tr-item-<?php echo $tipo ?> <?php echo ($i->req_sensei_battle ? 'cor_sensei' : ($cn % 2 ? 'cor_sim' : 'cor_nao')) ?>">
		<td width="60">
			<div class="img-lateral-dojo2"><img src="<?php echo img('layout/'.$i->getAttribute('imagem')) ?>" id="i-item-<?php echo $i->id ?>" width="53" height="53"  style="margin-top:5px"/></div>
			<?php echo bonus_tooltip('i-item-' . $i->id, $i) ?>
		</td>
		<td align="left" width="240">
			<strong class="amarelo" style="font-size:13px"><?php echo $i->getAttribute('nome_'.Locale::get()) ?></strong>
			<br />
			<br />
			<?php echo $i->getAttribute('descricao_'.Locale::get()) ?>
		</td>
		<td width="80">
			<img id="i-item-reqs-<?php echo $i->id ?>" src="<?php echo img('layout/requer.gif') ?>" />
			<?php echo generic_tooltip('i-item-reqs-' . $i->id, Item::getRequirementLog()) ?>
		</td>
		<td align="center" width="80">
			<form id="f-item-<?php echo $i->id ?>">
			<input type="hidden" name="id" value="<?php echo $i->id ?>" />
			<input type="hidden" name="q" id="h-item-qty-<?php echo $i->id ?>" value="<?php echo $i->getAttribute('coin') ? 20 : 1 ?>" />
			<?php if($i->getAttribute('preco') && $i->getAttribute('coin')): ?>
				<input type="radio" name="pm" value="0" /> <span id="d-item-preco-r-<?php echo $i->id ?>">RY$ <?php echo $i->getAttribute('preco') * $_dbl ?></span><br />
				<input type="radio" name="pm" value="1" /> <span id="d-item-preco-c-<?php echo $i->id ?>"><?php echo $i->getAttribute('coin') ?> <?php echo t('geral.creditos'); ?> VIP</span>
			<?php elseif($i->getAttribute('preco') && !$i->getAttribute('coin')): ?>
				<input type="hidden" name="pm" value="0" />
				<span id="d-item-preco-r-<?php echo $i->id ?>">RY$ <?php echo $i->getAttribute('preco') * $_dbl ?></span>
			<?php elseif(!$i->getAttribute('preco') && $i->getAttribute('coin')): ?>
				<input type="hidden" name="pm" value="1" />
				<span id="d-item-preco-c-<?php echo $i->id ?>"><?php echo $i->getAttribute('coin') * $_dbl ?> <?php echo t('geral.creditos'); ?> VIP</span>
			<?php endif; ?>
			</form>
			<script type="text/javascript">
				_items[<?php echo $i->id ?>] = {
					r:	<?php echo $i->getAttribute('preco') * $_dbl ?>,
					c:	<?php echo $i->getAttribute('coin') ?>
				}
			</script>
		</td>
		<td width="90">
			<?php if($i->getAttribute('coin')): ?>
			<input type="hidden" value="1" id="t-item-qty-<?php echo $i->id ?>" />
			<?php echo t('templates.t68') ?>!
			<?php else: ?>
			<input type="text" value="1" id="t-item-qty-<?php echo $i->id ?>" class="t-item-qty" />
			<?php endif; ?>
		</td>
		<td id="d-inventario-<?php echo $i->id ?>" width="80">
			<?php if($basePlayer->hasItem($i->id)): ?>
			x<?php echo $basePlayer->getItem($i->id)->getAttribute('qtd') ?>
			<?php else: ?>
			<?php echo t('templates.t69') ?>
			<?php endif; ?>
		</td>
		<td width="100">
			<?php if($reqs): ?>
				<a class="button b-buy" rel="<?php echo $i->id ?>"><?php echo t('botoes.comprar') ?></a>
			<?php else: ?>
				<a class="button ui-state-disabled"><?php echo t('botoes.comprar') ?></a>		
			<?php endif; ?>
		</td>
	</tr>
	<tr height="4"></tr>
	<?php endforeach; ?>
<?php endforeach; ?>
</table>
<?php if ($fall_timer): ?>
	<script type="text/javascript">
		createTimer(<?php echo $fall_timer->format('%H') ?>, <?php echo $fall_timer->format('%i') ?>, <?php echo $fall_timer->format('%s') ?>, 'd-penality-timer');
	</script>
<?php endif ?>