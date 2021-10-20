<?php
	$_SESSION['_pvpITEMS']		= array();
	$_SESSION['mapa_mundi_key']	= uniqid('', true);

	$total_npcs	= Recordset::query('SELECT total FROM player_batalhas_npc_mapa WHERE id_player=' . $basePlayer->id);

	if($total_npcs->num_rows) {
		$total_npcs	= $total_npcs->row()->total;
	} else {
		$total_npcs	= 0;
	}

	$npc_limit	= 10 + $basePlayer->bonus_vila['dojo_limite_npc_mapa'];
	
	$posfix	= false;
	$pos	= Recordset::query("SELECT xpos, ypos FROM player_posicao WHERE id_player=" . $basePlayer->id);
	
	if (!$pos->num_rows) {
		Recordset::insert('player_posicao', [
			'id_player'	=> $basePlayer->id,
			'xpos'		=> 0,
			'ypos'		=> 0
		]);

		$pos	= $pos->repeat()->row_array();
	} else {
		$pos	= $pos->row_array();
	}

	if($pos['xpos'] > 159) {
		Recordset::update('player_posicao', array(
			'xpos'		=> 159
		), array(
			'id_player'	=> $basePlayer->id
		));
		
		$posfix	= true;	
	}

	if($pos['xpos'] < 0) {
		Recordset::update('player_posicao', array(
			'xpos'		=> 0
		), array(
			'id_player'	=> $basePlayer->id
		));
		
		$posfix	= true;	
	}

	if($pos['ypos'] > 95) {
		Recordset::update('player_posicao', array(
			'ypos'		=> 95
		), array(
			'id_player'	=> $basePlayer->id
		));
		
		$posfix	= true;	
	}

	if($pos['ypos'] < 0) {
		Recordset::update('player_posicao', array(
			'ypos'		=> 0
		), array(
			'id_player'	=> $basePlayer->id
		));
		
		$posfix	= true;	
	}
	
	if($posfix) {
		$pos	= Recordset::query("SELECT xpos, ypos FROM player_posicao WHERE id_player=" . $basePlayer->id)->row_array();		
	}
?>
<style type="text/css">
	.map-coord {
		position: absolute;
		z-index: 2;
		background-image: URL(<?php echo img('layout/map_blank.png') ?>) !important
	}
	
	.qr {
		background-color: #00F !important;
		opacity: 0.2;		
	}

	.map-coord-center {
		background-image: URL(<?php echo img('layout/map_me.png') ?>) !important;
		z-index: 2
	}

	.map-coord-hover-near, .map-coord-hover-far {
	}

	.map-coord-hover-near {
		background-image: URL(<?php echo img('layout/map_blue.png') ?>) !important;
	}

	.map-coord-hover-far {
		background-image: URL(<?php echo img('layout/map_orange.png') ?>) !important;
	}
	
	.map-player, .map-place, .map-place-bar {
		position: absolute;
		z-index: 1;
	}
	
	.map-tip {

		display: none;
		z-index: 5 !important;
		color: #FFF;
		text-align: left;
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
		overflow: hidden;
		padding: 1px;
		width: 250px
	}
	
	.map-tip table {
		margin-top: 5px
	}
	
	.map-tip .i {
		width: 18px
	}
	
	.map-tip .n {
		text-align: left	
	}
	
	.map-place-bar {
		position: absolute;
		width: 50px;
		height: 6px;
		background-color: #333
	}

	.map-place-bar .fill {
		margin: 1px;
		height: 4px;
	}
	
	.map-invite {
		width: 208px;
		height: 112px;
		background-color: #333;
		color: #FFF;
		z-index: 999;
		padding: 10px;
		/*
		position: fixed;
		left: 20px
		*/
		position: absolute;
		right: 10px;
	}

	.map-invite .title {
		background-color: #174050;
		color: #FFF;
		padding: 4px;
		margin-left: -9px;
		margin-right: -9px;
		margin-top: -9px;
		margin-bottom: 10px;
		font-size: 13px;
		font-weight: bold;
	}
	.map-invite a {
		cursor: pointer	
	}
	
	.map-coord-with-enemy {
		background-image: URL(<?php echo img('layout/map_red.png') ?>) !important;
	}

	.map-coord-with-target {
		background-image: URL(<?php echo img('layout/map_yellow.png') ?>) !important;
	}
	
	.map-coord-with-ally {
		background-image: URL(<?php echo img('layout/map_green.png') ?>) !important;		
	}
	
	.map-coord-with-neutral {
		background-image: URL(<?php echo img('layout/map_gray.png') ?>) !important;
	}
	
	.map-coord-with-npc {
		background-image: URL(<?php echo img('layout/map_purple.png') ?>) !important;		
	}
	
	.map-player {
		z-index: 4	
	}
</style>
<div class="titulo-secao"><p><?php echo t('menus.mundi')?></p></div><br />
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- NG - Mapa Mundi -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9166007311868806"
     data-ad-slot="7904165370"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<br /><br /><br /><br />

<div id="cnBase">
	<div id="ngmap" style="width: 726px; height: 638px; bottom:10px; position: relative; background-image: URL('<?php echo img('layout/mapa/mapbase.jpg') ?>'); background-repeat: no-repeat; background-position: -<?php echo $pos['xpos'] * 22 ?>px -<?php echo $pos['ypos'] * 22 ?>px"></div>
</div>
<div style="clear:both"></div>
<div class="titulo-home" style="width: 765px">
	<p><span class="laranja">//</span> <?php echo t('classes.c62')?> ...............................................................................</p></div>
 <table width="730" border="0" cellpadding="0" cellspacing="0">
   <tr>
     <td width="6%" align="center"><img src="<?php echo img() ?>layout/legenda/1.jpg" border="0" alt="<?php echo t('classes.c63')?>"/>&nbsp;&nbsp;</td>
     <td width="19%" align="left"><?php echo t('classes.c63')?><br />
       ( <?php echo t('classes.c68')?> )</td>
     <td width="6%" align="center"><img src="<?php echo img() ?>layout/legenda/0.jpg" border="0" alt="<?php echo t('classes.c64')?>"/>&nbsp;&nbsp;</td>
     <td width="19%" align="left"><?php echo t('classes.c64')?><br />
       ( <?php echo t('classes.c69')?> )</td>
     <td width="6%" align="center"><img src="<?php echo img() ?>layout/legenda/2.jpg" border="0" alt="<?php echo t('classes.c65')?>"/>&nbsp;&nbsp;<br /></td>
     <td width="19%" align="left"><?php echo t('classes.c65')?><br />
       ( <?php echo t('classes.c70')?> )</td>
     <td width="6%" align="center"><img src="<?php echo img() ?>layout/legenda/5.jpg" border="0" alt="<?php echo t('classes.c66')?>"/>&nbsp;&nbsp;</td>
     <td width="19%" align="left"><?php echo t('classes.c66')?><br />
       ( <?php echo t('classes.c72')?> )</td>
   </tr>
   <tr>
     <td width="6%" align="center" ><img src="<?php echo img() ?>layout/legenda/4.jpg" border="0" alt="<?php echo t('classes.c67')?>"/>&nbsp;&nbsp;</td>
     <td width="19%" align="left" ><?php echo t('classes.c67')?><br />
       ( <?php echo t('classes.c71')?> )</td>
     <td width="6%" align="center" ><img src="<?php echo img() ?>layout/legenda/bt_mapa_mensagem.png" border="0" alt="<?php echo t('botoes.enviar_mensagem')?>"/>&nbsp;</td>
     <td width="13%" align="left" ><?php echo t('botoes.enviar_mensagem')?></td>
     <td width="6%" align="center" ><img src="<?php echo img() ?>layout/legenda/bt_mapa_batalhar.png" border="0" title="<?php echo t('classes.c61')?>"/>&nbsp;&nbsp;</td>
     <td width="19%" align="left" ><?php echo t('classes.c61')?></td>
     <td width="6%" align="center" ><img src="<?php echo img() ?>layout/ico_mapa_vila.png" border="0" alt="<?php echo t('geral.vila')?>" />&nbsp;&nbsp;</td>
     <td width="13%" align="left" ><?php echo t('classes.c73')?></td>
   </tr>
   <tr>
     <td colspan="8" align="center" ><br />
       <?php echo t('classes.c74')?><br /></td>
   </tr>
 </table>
<br />
<div class="titulo-home" style="width: 730px; text-align: left">
	<p><span class="laranja">//</span> <?php echo t('mapa.limite_npc')?> ................................................................</p>
</div>
<?php echo barra_exp3($total_npcs, $npc_limit, 730, $total_npcs . ' de '. $npc_limit, "#2C531D", "#537F3D", 2, "", true) ?>
<br />
<script type="text/javascript">
	var _dbg_stop	= false;

	(function () {
		var	cur_x		= <?php echo (int)$pos['xpos'] ?>;
		var cur_y		= <?php echo (int)$pos['ypos'] ?>;
		
		var coord_x		= 22;
		var coord_y		= 22;
		
		var	coords_x	= parseInt($('#ngmap').width() / coord_x);
		var	coords_y	= parseInt($('#ngmap').height() / coord_y);
		
		var center_x	= parseInt(coords_x / 2);
		var center_y	= parseInt(coords_y / 2);
		
		var map			= $('#ngmap');
		var map_player_id	= 0;

		var map_max_y	= 95;
		var map_max_x	= 159;
		
		// Tooltip --->
			var is_tooltip_shown	= false;
			var tooltip_items		= [];
			var tooltip				= $(document.createElement('DIV')).addClass('map-tip');
			var tooltip_x			= 0;
			var tooltip_y			= 0;
			
			map.append(tooltip);
		// <---
		
		for(var x = 0; x < coords_x; x++) {
			for(var y = 0; y < coords_y; y++) {
				var d	= $(document.createElement('DIV')).addClass('map-coord').attr('id', 'coord-' + x + ':' + y);

				$("#ngmap").append(d);
				
				d.css('top', y * coord_y)
				 .css('left', x * coord_x)
				 .data('x', x)
				 .data('y', y);

				if(x == center_x && y == center_y) {
					d.addClass('map-coord-center');
				}
			}
		}
		
		var center_node	= $('#ngmap .map-coord-center');
		
		$("#ngmap .map-coord")
			.css('width', coord_x)
			.css('height', coord_y)
			.on('mouseover', function () {
				var _ 			= $(this);
				var spacing_x	= parseInt(_.data('x') - center_node.data('x'));
				var spacing_y	= parseInt(_.data('y') - center_node.data('y'));
				
				if(_.hasClass('map-coord-center')) {
					return;
				}
				
				_.removeClass('map-coord-hover-near map-coord-hover-far');

				if(
					Math.abs(spacing_x) > 5 || Math.abs(spacing_y) > 5 || 
					cur_x + spacing_x < 0 || cur_y + spacing_y < 0 ||
					(cur_x + spacing_x) > map_max_x || (cur_y + spacing_y) > map_max_y
				) {
					_.addClass('map-coord-hover-far');
				} else {
					_.addClass('map-coord-hover-near');					
				}
			}).on('mouseout', function () {
				var _ = $(this);
				
				if(_.hasClass('map-coord-center')) {
					return;	
				}

				_.removeClass('map-coord-hover-near map-coord-hover-far');
			}).on('dblclick', function () {
				var _ 			= $(this);
				var spacing_x	= parseInt(_.data('x') - center_node.data('x'));
				var spacing_y	= parseInt(_.data('y') - center_node.data('y'));
				
				if(
					Math.abs(spacing_x) > 5 || Math.abs(spacing_y) > 5 ||
					cur_x + spacing_x < 0 || cur_y + spacing_y < 0 ||
					cur_x + spacing_x > map_max_x || cur_y + spacing_y > map_max_y
				) {
					return;	
				}
				
				<?php //if($_SESSION['universal']): ?>
					cur_x	+= spacing_x;
					cur_y	+= spacing_y;

					__update_map_image();
				<?php //endif ?>
				
				$.ajax({
					url:		'?acao=mapa_image',
					data:		{x: spacing_x, y: spacing_y},
					dataType:	'json',
					type:		'post',
					success:	function(e) {
						if(e.not_moved) {
							jalert('<?php echo t('classes.c75')?>');	
						}

						if(e.redirect) {
							clearInterval(_map_check_iv);
							location.href = e.redirect;
							
							return;
						}
						
						if(e.reload) {
							location.reload();
							return;	
						}
						
						if(e.is_random_npc) {
							jconfirm('<?php echo t('actions.a195')?>', '<?php echo t('actions.a196')?>', function () {
								$.ajax({
									url:		'?acao=mapa_batalha',
									data:		{tp: 3, id: e.is_random_npc},
									type:		'post',
									dataType:	'script'
								});
							});
						}
					},
					error:	function () {
						jalert('<?php echo t('classes.c78')?>', null, function () {
						});
					}
				});
				
				__update_tooltip(false);
			}).on('click', function () {
				var _ 			= $(this);
				
				tooltip_x		= _.data('x');
				tooltip_y		= _.data('y');

				__update_tooltip(true);
				__update_tooltip();
			});
		
		function __update_map_image() {
			$("#ngmap").css("background-position", (-(cur_x * coord_x)) + "px " + (-(cur_y * coord_y)) + "px");
		}
		
		function __update_tooltip(show) {
			if(show === true) {
				$('.map-tip').show();
				
				return;
			} else if(show === false) {
				$('.map-tip').hide();
				
				return;
			}
			
			var cx		= (tooltip_x + (cur_x - center_x));
			var cy		= (tooltip_y + (cur_y - center_y));

			if(cx < 0 || cy < 0 || cx > map_max_x || cy > map_max_y) {
				var	html	= '<div align="center" style="padding: 6px"><?php echo t('classes.c79')?></div>';
			} else {
				var html	= '<b style="color: #EDD781; font-size: 14px"><?php echo t('geral.coordenada')?> [X: ' + (cx * 22) + ' / Y: ' + (cy * 22) + ']</b><br />' +
							  '<table border="0" cellpadding="4" cellspacing="0"  style="width: 240px !important">';
				var items	= tooltip_items[tooltip_x + ":" + tooltip_y];
				
				if(items && items.length) {
					for(var i = 0; i < items.length; i++) {
						var item	= items[i];
						
						if(item.type == "place") {
							html	+=	'<tr><td class="i"><img src="<?php echo img('layout/ico_mapa_vila.png') ?>" /></td>' +
										'<td colspan="3" class="n">' + item.name + '</td></tr>';
						} else if(item.type == "npc") {
							var	battle_type	= 2;
							var	sprite_path	= '<?php echo img('layout/npc/sprites/') ?>' + item.raw_id + '.png';
							
							if(item.invasion) {
								battle_type	= 4;
							}

							if (item.war) {
								battle_type	= 5;
								sprite_path	= '<?php echo img('layout/npc/sprites_guerra/') ?>' + item.icon + '.png';
							}
							
							html	+=	'<tr><td class="i" style="background-color: #AB2DE8"><img src="' + sprite_path + '" /></td>' +
										'<td class="n">' + item.name + '<br />Level: ' + item.level + '</td>' +
										'<td class="i" colspan="3"><a href="javascript:;" class="map-battle-link" data-tp="' + battle_type +  '" data-id="' + item.id + '"><img src="<?php echo img('layout/legenda/bt_mapa_batalhar.png') ?>" /></a></td>' +
										'</tr><tr><td colspan="9" style="border: 1px dotted #204276; padding: 0px; font-size: 1px"></td></tr>';							
						} else {
							var	color	= "background-color: ";
							
							if(item.id == '<?php echo salt_encrypt($basePlayer->id, $_SESSION['mapa_mundi_key']) ?>') {
								html	+=	'<tr><td class="i" style="' + color + '"><img src="<?php echo img('/layout/sprites/') ?>' + item.klass + '.png" /></td>' +
											'<td colspan="6" class="n">Você está aqui</td>' +
											'</tr><tr><td colspan="9" style="border: 1px dotted #204276; padding: 0px; font-size: 1px"></td></tr>';								
							} else {
								if(item.target) {
									color	+= '#D5D575';
								} else if(item.dipl == 0) {
									color	+= '#666';
								} else if(item.dipl == 1) {
									color	+= '#238C00';
								} else if(item.dipl == 2) {
									color	+= '#DB3C3C';								
								}
								
								html	+=	'<tr><td class="i" style="' + color + '"><img src="<?php echo img('/layout/sprites/') ?>' + item.klass + '.png" /></td>' +
											'<td class="n">' + item.name + '<br />Level: ' + item.level + '<br /><?php echo t('geral.vila')?>: ' + item.world + '</td>' +
											'<td class="i"><a href="javascript:;" class="map-battle-link" data-id="' + item.id + '"><img src="<?php echo img('layout/legenda/bt_mapa_batalhar.png') ?>" /></a></td>' +
											'<td class="i"><a href="javascript:;" data-to="' + item.name + '" class="message"><img src="<?php echo img('layout/legenda/bt_mapa_mensagem.png') ?>" /></a></td>' +
											'<td class="i"><a href="javascript:;" class="map-chat-link" data-name="' + item.name + '"><img src="<?php echo img('layout/legenda/bt_mapa_whisper.png') ?>" /></a></td>' +
											'</tr><tr><td colspan="9" style="border: 1px dotted #204276; padding: 0px; font-size: 1px"></td></tr>';								
							}							
						}
					}
				} else {
					html += "<tr><td><?php echo t('classes.c81')?></td></tr>";	
				}
				
				html	+= "</table>";
			}
			
			tooltip.html(html);
			
			$('.message', tooltip).on('click', function () {
				$('#chat-v2 input[name=message]').trigger('pvt-switch', [$(this).data('to')]).focus();
			});
			
			$('.map-chat-link', tooltip).on('click', function () {
				if(!parseInt($.cookie('chat_expanded'))) {
					$('#chat-window-bar').trigger('click');
				};
				
				$('#chat-window input[name=message]').val("@" + $(this).data('name') + " ").focus();
			});
			
			$('.map-battle-link', tooltip).on('click', function () {
				var	_		= $(this);
				var	data	= {id: _.data('id')};
				
				if(_.data('tp')) {
					data['tp']	= _.data('tp');	
				}
				
				$.ajax({
					url:		'?acao=mapa_batalha',
					data:		data,
					type:		'post',
					dataType:	'script'
				});
				
				__update_tooltip(false);
			});
			
			var end_x	= coords_x * coord_x;
			var end_y	= coords_y * coord_y;
			var tx		= tooltip_x * coord_y;
			var ty		= tooltip_y * coord_y;
			
			if(tx + coord_x + tooltip.width() > end_x) {
				tooltip.css('left', tx - tooltip.width());
			} else {
				tooltip.css('left', tx + coord_x);
			}
			
			if(ty + coord_y + tooltip.height() > end_y) {
				tooltip.css('top', ty - tooltip.height());
			} else {
				tooltip.css('top', ty + coord_y);
			}
		}
		
		function __update_qr(all_qr) {
			$('.map-coord', map).removeClass('qr');
			
			for(var i in all_qr) {
				var qr_coords	= all_qr[i];
				
				for(var x = qr_coords.x1; x <= qr_coords.x2; x++) {
					for(var y = qr_coords.y1; y <= qr_coords.y2; y++) {
						try {
							$(document.getElementById("coord-" + x + ":" + y)).addClass('qr');
						} catch (e) {}
					}
				}				
			}
		}
		
		function __update_invites(iv) {
			var	bottom	= 0;
			
			$('.map-invite').remove();
			
			for(var i in iv) {
				var invite	= iv[i];
				var d		= $(document.createElement('DIV')).addClass('map-invite');
				var a_link	= $(document.createElement('A')).html('<?php echo t('botoes.aceitar') ?>').addClass('button');
				var c_link	= $(document.createElement('A')).html('<?php echo t('botoes.recusar') ?>').css('float', 'right').addClass('button ui-state-red');
				
				//$(document.body).append(d);
				$('#ngmap').append(d);
				
				c_link.on('click', function () {
					$.ajax({
						url:		'?acao=mapa_batalha',
						data:		{id: $(this).data('id'), 'delete': 1},
						type:		'post',
						dataType:	'script'
					});
					
					d.remove();
				}).data('id', invite.cid);

				a_link.on('click', function () {
					$.ajax({
						url:		'?acao=mapa_batalha',
						data:		{id: $(this).data('id'), tp: 3},
						type:		'post',
						dataType:	'script'
					});
					
					d.remove();
				}).data('id', invite.cid);
				
				d.html('<div class="title"><?php echo t('actions.a196') ?></div><?php echo t('actions.a195') ?><br /><br />')
				 .append(a_link)
				 .append(c_link)
				 .css('top', d.outerHeight() * bottom++ + $('#chat-window').height());
			}
		}
		
		var _map_check_iv	= setInterval(function () {
			if(_dbg_stop) {
				return;	
			}
			
			$.ajax({
				url:		'?acao=mapa_posicoes',
				data:		{key: "<?php echo $_SESSION['mapa_mundi_key'] ?>", cx: center_x, cy: center_y},
				dataType:	'json',
				type:		'post',
				success:	function (e) {
					if(e.redirect) {
						clearInterval(_map_check_iv);
						location.href = e.redirect;
						
						return;
					}
					
					if(e.invalid_key) {
						clearInterval(_map_check_iv);
						
						jalert("<?php echo t('classes.c80')?>", null, function () {
							location.reload();
						});
						
						return;
					}
					
					<?php //if(!$_SESSION['universal']): ?>
						//cur_x			= parseInt(e.player.x);
						//cur_y			= parseInt(e.player.y);
					<?php //endif ?>
					
					map_player_id	= e.player.id;
					tooltip_items	= [];
					
					$('.map-player, .map-place', map).remove();
					$('.map-place-bar', map).remove();
					$('.map-coord').removeClass('map-coord-with-enemy map-coord-with-target map-coord-with-ally map-coord-with-neutral map-coord-with-npc');

					for(var i = 0; i < e.places.length; i++) {
						var place	= e.places[i];
						var d		= $(document.createElement('IMG')).addClass('map-place');
						
						d.attr('src', '<?php echo img('layout/ico_mapa_vila.png') ?>')
						 .css('left', place.x * coord_x)
						 .css('top', place.y * coord_y);

						if(!tooltip_items[place.x  + ":" + place.y]) {
							tooltip_items[place.x  + ":" + place.y] = []
						}
						
						tooltip_items[place.x  + ":" + place.y].push(place);
						
						map.append(d);
					}
					
					for(var i = 0; i < e.players.length; i++) {
						var player	= e.players[i];
						var d		= $(document.createElement('IMG')).addClass('map-player');
						
						if(player.type == 'player') {
							var	sprite_path	= '<?php echo img('/layout/sprites/') ?>' + player.klass + '.png';
						} else {
							var	sprite_path	= '<?php echo img('layout/npc/sprites/') ?>' + player.raw_id + '.png';

							if (player.war) {
								sprite_path	= '<?php echo img('layout/npc/sprites_guerra/') ?>' + player.icon + '.png';
							}
						}

						d.attr('src', sprite_path)
						 .css('left', player.x * coord_x)
						 .css('top', player.y * coord_y)
						 .data('player', player.id)
						 .data('x', player.x)
						 .data('y', player.y);
						
						if(player.x == tooltip_x && player.y == tooltip_y) {
							tooltip_items.push(player);
						}

						if(!tooltip_items[player.x  + ":" + player.y]) {
							tooltip_items[player.x  + ":" + player.y] = []
						}
						
						tooltip_items[player.x  + ":" + player.y].push(player);
						
						var now_coord	= $(document.getElementById('coord-' + player.x + ':' + player.y));
						
						d.on('click', function () {
							var	_		= $(this);
							var coord	= $(document.getElementById('coord-' + _.data('x') + ':' + _.data('y')));
							
							coord.trigger('click');
						});

						d.on('dblclick', function () {
							var	_		= $(this);
							var coord	= $(document.getElementById('coord-' + _.data('x') + ':' + _.data('y')));
							
							coord.trigger('dblclick');
						});
						
						if(player.id != '<?php echo salt_encrypt($basePlayer->id, $_SESSION['mapa_mundi_key']) ?>') {
							if(player.enemy && !now_coord.hasClass('map-coord-with-enemy')) {
								now_coord.addClass('map-coord-with-enemy');
							}
	
							if(player.target && !now_coord.hasClass('map-coord-with-target')) {
								now_coord.addClass('map-coord-with-target');
							}
							
							if(player.type == 'npc') {
								now_coord.addClass('map-coord-with-npc');
							}
							
							if(!player.target) {
								if(player.dipl == 1) {
									now_coord.addClass('map-coord-with-ally');							
								} else if(player.dipl == 0) {
									now_coord.addClass('map-coord-with-neutral');
								}
							}
						}
						
						map.append(d);
					}

					__update_tooltip();
					<?php //if(!$_SESSION['universal']): ?>
						//__update_map_image();
					<?php //endif ?>
					__update_qr(e.qr || []);
					__update_invites(e.invites);
				},
				error:	function (e) {
					eval(e.responseText);
				}
			});
		}, 2000);		
	})();
</script>
