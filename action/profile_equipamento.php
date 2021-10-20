<?php
	$_GET['id']		= decode($_GET['id']);
	$redir_script	= true;
	
	if(!is_numeric($_GET['id'])) {
		redirect_to("negado");
	}
	
	$curPlayer						= new Player($_GET['id']);
	$i 								= $basePlayer->getVIPItem(array(21880, 21881, 21882));
	$armors							= Recordset::query('SELECT * FROM item_tipo WHERE equipamento=1', true);
	$pieces							= array();
	$my_armor						= array();

	$attributes						= array('req_graduacao', 'bonus_hp', 'bonus_sp', 'bonus_sta', 'atk_fisico', 'atk_magico', 'def_magico','def_fisico', 'tai','ken', 'nin', 'gen', 'agi', 'con', 'ene', 'inte', 'forc', 'res', 'def_base', 'prec_fisico', 'prec_magico', 'esq_min', 'esq_max','esq_total','crit_min', 'crit_max', 'crit_total','esq', 'det', 'conv', 'conc','esquiva');
	$attributes_description			= array();
	
	foreach($attributes as $attribute) {
		$description						= tb('formula.' . $attribute);
		$attributes_description[$attribute]	= $description ? $description : tb('at.' . $attribute);
	}
	
	foreach($armors->result_array() as $armor) {
		$item					= Recordset::static_query('SELECT id, id_item FROM player_item WHERE id_player=' . $curPlayer->id . ' AND id_item_tipo=' . $armor['id'] . ' AND equipado=1');
		$my_armor[$armor['id']]	= $item->num_rows ? $item->row()->id : 0;
		$pieces[$armor['id']]	= $armor['nome_' . Locale::get()];
	}
	
	$names	=	array(
		2	=> array(16, 170, 2),
		10	=> array(103, 87, 1),
		11	=> array(95, 117, 2),
		13	=> array(35, 208, 1),
		14	=> array(100, 414, 2),
		15	=> array(130, 570, 2),
		29	=> array(16, 515, 1),
	);

	$colors	= array(
		'comum'		=> '#b4b4b4',
		'raro'		=> '#265ec1',
		'epico'		=> '#df42b6',
		'lendario'	=> '#d77810',
		'set'		=> '#489C33'
	);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo t('templates.t59')?> - Naruto Game</title>
<link href="css/html<?php echo LAYOUT_TEMPLATE?>.css" rel="stylesheet" type="text/css"/>
<link href="css/layout<?php echo LAYOUT_TEMPLATE?>.css" rel="stylesheet" type="text/css"/>
<style>
	#armor-container {
		width: 765px;
		height: 616px;
		background-image:url(<?php echo img('layout/equipamentos/fundo.jpg') ?>);
		background-repeat:no-repeat;
		position: relative
	}
	
	.armor-item {
		position: absolute;
		cursor: pointer;
	}
	
	.armor-name {
		position: absolute;
		font-size: 11px !important
	}

	.armor-tooltip blockquote {
		margin: 0px;
		margin-left: 20px
	}

	.armor-tooltip .level {
		margin-top: 8px;
		font-size: 11px;
	}

	.armor-tooltip .level .right {
		float: right	
	}
	
	.armor-tooltip {
		position: absolute;
		z-index: 10000000;		
		-moz-border-bottom-colors: none;
		-moz-border-image: none;
		-moz-border-left-colors: none;
		-moz-border-right-colors: none;
		-moz-border-top-colors: none;
		background-color: #0a0a0a;
		background-position: 1px 1px;
		background-repeat: no-repeat;
		border-color: #B1B2B4 #434445 #2F3032;
		border-radius: 3px 3px 3px 3px;
		border-style: solid;
		border-width: 2px;
		display: inline-block;
		overflow: hidden;
		padding: 4px;
		width: 274px;
		line-height: 11px !important
	}
	
	.armor-item-ns {
		margin: 2px;
		float: left;
	}
	
	.name{
		width: 274px;
		height: 31px;
		background: url('http://cdzgame.com.br/images/bg_tooltip.jpg');	
		font-size: 14px;
		text-align: center;
		padding-top: 13px;
	}
	.desc{
		width: 274px;
		height: 99px;
		position: relative;
		top: 10px;
		
	}
	.a-atributos{
		width: 190px;
		float: left;
	}
	.a-atributos p, #tb-tooltip p, .a-set p {
		font-size: 12px;
		padding: 7px 0px 0px 5px;
		margin: 0px;	
	}
	.cinza{
		color: #626262;	
	}
	.verde{
		color: #9ac93f !important;	
	}
	
	.broken {
		color: #FF3300 !important
	}
	
	.vermelho {
		color: #900 !important
	}
	
	.armor-tooltip hr{
		border: 1px solid #181818;	
	}
	.a-armor{
		width: 80px;
		float: left;
	}
</style>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/global.js?c=<?php echo filemtime(ROOT . '/js/global.js') ?>"></script>
<script type="text/javascript">
<?php ob_start() ?>
	var _items				= [];
	var	_equips				= [];
	var	_grad				= [];
	var	_grad_ak			= [];
	
	<?php foreach(Recordset::query('SELECT * FROM graduacao', true)->result_array() as $grad): ?>
		_grad[<?php echo $grad['id'] ?>]	= '<?php echo $grad['nome_' . Locale::get()] ?>';
		_grad_ak[<?php echo $grad['id'] ?>]	= '<?php echo $grad['nome_ak_' . Locale::get()] ?>';
	<?php endforeach ?>

	$(document).ready(function() {
		var _descriptions		= [];
		var _percent			= [];
		var	_special			= [];
		var	_exclude			= ['req_graduacao', 'req_level'];

		<?php foreach($attributes_description as $k => $v): ?>
		_descriptions['<?php echo $k ?>']	= '<?php echo addslashes($v) ?>';
		<?php endforeach ?>
		
		<?php
			$items	= Recordset::query('SELECT id, id_item, equipado, raridade,id_item_tipo FROM player_item WHERE id_item_tipo IN(2,10,11,13,14,15,29) AND id_player=' . $curPlayer->id);
		?>
		<?php foreach($items->result_array() as $item): ?>
			<?php
				if($item['id_item_tipo']==2){
					$ats	= Recordset::query('SELECT req_level, req_graduacao, id,raridade, nome_' . Locale::get() . ' AS nome, ordem, preco, imagem,nome_' . Locale::get() . ' AS nome, raridade, ' . join(',', $attributes) . ', id_tipo FROM item WHERE id=' . $item['id_item'], true)->row_array();

				}else{
					$ats	= Recordset::query('SELECT nome, ' . join(',', $attributes) . ' FROM player_item_atributos WHERE id_player_item=' . $item['id'])->row_array();
				}
			?>
			_equips['<?php echo $item['id'] ?>']	= {
					id:				'<?php echo $item['id_item'] ?>',
					name:			'<?php echo $ats['nome'] ?>',
					description:	'<?php echo $ats['nome'] ?>',
					raridade:		'<?php echo $item['raridade'] ?>',
					at: {
						<?php foreach($attributes as $v): ?>
						<?php echo $v ?>: <?php echo $ats[$v]  == "" ? 0 : $ats[$v]  ?>,
						<?php endforeach ?>
						nil:	 null
					}
				};
		<?php endforeach ?>

		function _refresh() {
			$('.armor-item, .armor-item-ns').each(function () {
				if(this._with_tooltip) {
					return;	
				}
				
				this._with_tooltip	= true;
				
				var	_	= $(this);
				
				_.on('mouseover', function () {
					var	i	= _equips[_.data('id')];
					
					if(!i) { // nothing equipped? lets gtfo
						return;	
					}

					var d					= $(document.createElement('DIV')).addClass('armor-tooltip');
					var	at_html				= '<table border="0" width="100%" cellspacing="0" cellpanding="0" id="tb-tooltip">';
					var set_html			= '';
					var req_html			= '';
					var	is_comparison		= _.hasClass('armor-item-ns') && parseInt($('.armor-item-' + _.data('type')).data('id'));
					var comparison_value	= '';
					var comparison_sum		= 0;
					var	text_class			= '';
					var level_style			= '';
					
					if((i.at.req_level - i.at.nivel_red) > <?php echo $curPlayer->level ?>) {
						level_style	= 'color: #FF0000';
					}
					
					$(document.body).append(d);
					
					d.css({
						left:	_.offset().left + _.width(),
						top:	_.offset().top
					});
					
					for(var f in i.at) {
						if(is_comparison) {
							var	comparison_target	= _equips[$('.armor-item-' + _.data('type')).data('id')];
							var	cval				= parseFloat(i.at[f]);
							var	tval				= parseFloat(comparison_target.at[f]);

							if(!cval && !tval) {
								continue;
							} else if(tval > cval) {
								comparison_value	= '<img src="<?php echo img('layout/down.png') ?>">' + (cval - tval);
								comparison_sum		= cval - tval;
								text_class			= 'vermelho';
							} else if(tval < cval) {
								comparison_value	= '<img src="<?php echo img('layout/up.png') ?>">' + (cval - tval);
								comparison_sum		= cval - tval;
								text_class			= 'verde';
							} else if(tval == cval) {
								comparison_value	= '<img src="<?php echo img('layout/eql.png') ?>"> Igual';
								comparison_sum		= 0;
								text_class			= '';
							}
						} else {
							comparison_value	= '';
							comparison_sum		= i.at[f];
							text_class			= 'verde';
							
							if(!parseFloat(i.at[f])) {
								continue;
							}
						}

						if(comparison_sum == 0) {
							comparison_sum	= 'Mesmo valor ';
						}
						
						var	percent_text	= '';
						
						for(var h in _percent) {
							if(_percent[h] == f) {
								percent_text	= '%';
								break;
							}
						}

						if(in_array(f, _exclude)) {
							continue;
						} else {							
							at_html	+=	'<tr><td><p class="cinza"><span class="' + text_class + '">' +
										(!isNaN(comparison_sum) && comparison_sum > 0 ? '+' : '' ) + eqp_nf(comparison_sum) + percent_text + '</span> <?php echo t('missoes.de')?> ' + _descriptions[f] +
										'</p></td></tr>';
						}
					}
					
					at_html	+= '</table>';

					var	reqs	= _.data('reqs').split('|');
					
					if(parseInt(reqs[0])) {
						if(reqs[0] > <?php echo $curPlayer->level ?>) {
							req_html	+= '<span class="vermelho" style="float: left"><?php echo t('geral.requer') ?> Level ' + reqs[0] + '</span>';
						} else {
							req_html	+= '<span class="verde" style="float: left"><?php echo t('geral.requer') ?> Level ' + reqs[0] + '</span>';
						}
					}

					if(reqs[1] > <?php echo $curPlayer->id_graduacao ?>) {
						req_html	+= '<span class="vermelho" style="float: right"><?php echo t('classes.c7') ?> ' + <?php echo $curPlayer->id_vila == 6 ? '_grad_ak' : '_grad' ?>[reqs[1]] + '</span>';
					} else {
						req_html	+= '<span class="verde" style="float: right"><?php echo t('classes.c7') ?> ' + <?php echo $curPlayer->id_vila == 6 ? '_grad_ak' : '_grad' ?>[reqs[1]] + '</span>';
					}
					
					d.html('<div class="name" style="color: ' + _.data('color') + '">' + i.name + '</div>' + at_html + '<br /><br />' + req_html);
				});
				
				_.on('mouseout', function () {
					$('.armor-tooltip').remove();
				});
			});
		}

		function eqp_nf(v) {
			var v2	= v.toString().split('.');
			
			if(v2.length > 1) {
				if(parseInt(v2[1]) > 0) {
					return v2[0] + ',' + v2[1];
				} else {
					return parseInt(v2[0]);
				}
			} else {
				return v;
			}
		}
		
		_refresh();
	});
</script>
</head>
<body>
<div class="titulo-secao"><p><?php echo t('classes.c50')?></p></div>
<br />
<div align="center"><?php echo t('templates.t62')?> <?php echo $i['vezes'] - $i['uso'] ?> <?php echo t('templates.t63')?>.</div>
<?php
	if(!$basePlayer->hasItem(array(21880, 21881, 21882))) {
		echo "<div align='center'>".t('templates.t60')."</div>";
	} else {
    
        if($i['uso'] >= $i['vezes']) {
			echo "<div align='center'>".t('templates.t61')."</div>";
		} else {
			$basePlayer->useVIPItem($i);
			$anti	= anti_espionagem($curPlayer->id);
?>
<?php if(!$anti): ?>
	<div id="armor-container">
		<?php foreach($armors->result_array() as $armor): ?>
		<?php
			if($my_armor[$armor['id']]) {
				if($armor['id'] != 2) {
					$item		= Recordset::query('select * from player_item as pi JOIN player_item_atributos as pa ON pi.id = pa.id_player_item where pi.id = '. $my_armor[$armor['id']], true)->row_array();
				}else{
					$item		= Recordset::query('SELECT * FROM item WHERE id=(SELECT id_item FROM player_item WHERE id=' . $my_armor[$armor['id']] . ')', true)->row_array();
				}
				$color		= $colors[$item['raridade']];
				$reqs		= 0 . "|" . $item['req_graduacao'];
			} else {
				$color	= "";	
			}
		
			if($armor['id'] == 2) {
				$bg = ($my_armor[$armor['id']] ? 'layout/equipamentos/' . $armor['id'] . '/'.$item['id'].'.png' : 'layout/equipamentos/' . $armor['id'] . '/n.png');
			} else {
				if($armor['id'] == 10){
					$bg		= ($my_armor[$armor['id']] ? 'layout/equipamentos/' . $item['id_item_tipo'] . '/' . $item['raridade'][0].'-'. $basePlayer->id_vila .'-'. $item['imagem'] . '.png' : 'layout/equipamentos/' . $armor['id'] . '/n-'.$basePlayer->id_vila.'.png');
				}else{
					$bg		= ($my_armor[$armor['id']] ? 'layout/equipamentos/' . $item['id_item_tipo'] . '/' . $item['raridade'][0].'-'. $item['imagem'] . '.png' : 'layout/equipamentos/' . $armor['id'] . '/n.png');
				}
				
			}
			
			$name	= $names[$armor['id']];			
		?>
		<div class="armor-name armor-name-<?php echo $armor['id'] ?>" style="<?php echo $name[2] == 1 ? 'left' : 'right' ?>: <?php echo $name[0] ?>px; top: <?php echo $name[1] ?>px; color: <?php echo $color ?>; font-size: 14px">
			<?php if($my_armor[$armor['id']]): ?>
				<?php echo $armor['id'] != 2 ? $item['nome'] : $item['nome_' . Locale::get()]?>
			<?php endif ?>
		</div>
		<div	class="armor-item armor-item-<?php echo $armor['id'] ?>"
				style="top: <?php echo $armor['pagey'] ?>px; left: <?php echo $armor['pagex'] ?>px; width: <?php echo $armor['w'] ?>px; height: <?php echo $armor['h'] ?>px; background-image: url(<?php echo img($bg) ?>)"
				data-type="<?php echo $armor['id'] ?>" data-id="<?php echo $my_armor[$armor['id']] ? $my_armor[$armor['id']] : '' ?>"
				data-color="<?php echo $color ?>"
				data-reqs="<?php echo $my_armor[$armor['id']] ? $reqs : '' ?>">
		</div>
	<?php endforeach ?>
	</div>
	<?php else: ?>
		<div align="center"><?php echo t('actions.a249') ?></div>
	<?php endif ?>
<?php
		}
	}
?>
<br />
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td>
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- NG - Profile -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9166007311868806"
     data-ad-slot="9322022979"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
		</td>
	</tr>
	<tr>
		<td align="center">
			<br />
			<span style="padding-right: 20px">
			<input type="button" onclick="opener.location.href='?secao=mensagens&amp;msg=<?php echo addslashes($curPlayer->nome) ?>'; window.close()" value="<?php echo t('botoes.enviar_mensagem')?>"/>
			<input type="button" onclick="window.close()" src="<?php echo img(); ?>bt_cancelar.gif" value="<?php echo t('botoes.cancelar')?>" border="0" />
			</span>
		</td>
	</tr>
</table>
</body>
</html>