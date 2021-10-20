<?php
	$_SESSION['mapa_vila_key']	= md5(date('YmdHis') . rand(1, 512384));

	if($basePlayer->getAttribute('dentro_vila')) {
		$redir_script = true;
		
		redirect_to("negado");
	}
	
	if(!$basePlayer->getAttribute('id_vila_atual')) {
		$redir_script = true;
		
		redirect_to("negado");		
	}

	if($basePlayer->hasItem(1494465)){
		$icone_novo = Recordset::query('select id from classe where ativo = 1 order by rand() limit 1')->row_array();
		
		Recordset::update('player_flags', array(
			'id_imagem_camu'		=> (int)$icone_novo['id']
		), array(
			'id_player'	=> $basePlayer->id
		));
	}

	// Pega o Range de movimentação
	$range_movimentacao = $basePlayer->getItem(array(1492918));
	
	
	$coords			= Recordset::query("SELECT xpos, ypos FROM player_posicao WHERE id_player=" . $basePlayer->id)->row_array();
	$exit_coords	= Recordset::query("SELECT x, y FROM local_mapa WHERE mlocal=5 AND id_vila=" . $basePlayer->getAttribute('id_vila_atual'), true)->row_array();
	
	if(isset($coords['xpos']) && isset($coords['ypos']) && ($coords['xpos'] > 20 || $coords['ypos'] > 20)) {
		$r = Recordset::query("SELECT x,y FROM local_mapa WHERE mlocal=5 AND id_vila=" . $basePlayer->getAttribute('id_vila_atual'), true)->row_array();
		
		$coords['xpos'] = (int)$r['x'];
		$coords['ypos'] = (int)$r['y'];
		
		Recordset::update('player_posicao', array(
			'xpos'		=> (int)$r['x'],
			'ypos'		=> (int)$r['y']
		), array(
			'id_player'	=> $basePlayer->id
		));
	}
?>
<style type="text/css">
	.mt-tooltip {
		width: 50px;
		height: 6px;
		background-color: #009;
		border: solid 1px #003;
		position: absolute;
	}
	
	.mt-tooltip div {
		height: 6px;
	}
	
	.ngCoordItem {
		background-image: url(<?php echo img('layout/map_blank2.png') ?>)	
	}
	
	.map-coord-center {
		background-image: URL(<?php echo img('layout/map_me2.png') ?>) !important;
		z-index: 2
	}
	
	.map-coord-hover-near {
		background-image: URL(<?php echo img('layout/map_blue2.png') ?>) !important;
	}
	
	.map-coord-hover-far {
		background-image: URL(<?php echo img('layout/map_orange2.png') ?>) !important;
	}
</style>
<script type="text/javascript">
	var step = 20;
	var detail = 20;
	var _tipMatrix = [];
	var _nM = [];
	var _canDoMapCheck = true;
	var __x = <?= (int)@$coords['xpos'] ?>;
	var __y = <?= (int)@$coords['ypos'] ?>;
	var __hItemClick = false;
	var __idv = <?php echo $basePlayer->getAttribute('id_vila') ?>;
	should_scroll_the_top	= false;

	<?php if(!$basePlayer->id_arena && !$basePlayer->id_exame_chuunin): ?>
	function sairMapaVila() {
		$.ajax({
			url:		'index.php?acao=mapa_posicoes_vila',
			type:		'post',
			data:		{out: 1, x: <?php echo $exit_coords['x'] ?>, y: <?php echo $exit_coords['y'] ?>, key: '<?php echo $_SESSION['mapa_vila_key'] ?>'},
			dataType:	'script'
		});	
	}
	<?php endif ?>

	function _update_nM() {
		$(".mt-tooltip").remove();
		
		for(var i in _nM) {
			var n = _nM[i];
			var w = (n[0] - n[1]) * 50 / n[0];
			var cr = null;
			
			var c = $(document.createElement("DIV"));
			var d = $(document.createElement("DIV"));
			
			if(w < 30 && w >= 15) {
				cr = "#FF9900";
			} else if (w < 15) {
				cr = "#FF0000";
			} else {
				cr = "#066";
			}
			
			d.width(Math.abs(Math.round(w)))
			 .css("background-color", cr);
			
			c.addClass("mt-tooltip")
			 .append(d)
			 .css("left", $("#" + i).offset().left - 10)
			 .css("top",  $("#" + i).offset().top - 6);
			
			$(document.body).append(c);			
		}
	}

	function $C(name, prop) {
		var obj = document.createElement(name);
		
		if(prop) {
			for(var i in prop) {
				obj[i] = prop[i];	
			}
		}
		
		return obj;
	}

	$(document).ready(function () {
		step	= $("#ngmap-container").width() / detail;
		sz		= $("#ngmap-container").width();
		
		var center =  (1 * step) * (detail / 2);
		
		var yc = 0;
		for(var y = 0; y <= (sz - step); y += step) {
			var nm = "y" + yc;
			
			var xc = 0;
			
			for(var x = 0; x <= (sz - step); x += step) {
				var d = $C("DIV");
				
				d.style.position = "absolute";
				d.style.top = y + "px";
				d.style.left = x + "px";				
				d.style.width = d.style.height = step + "px";
				d.style.zIndex = 1;

				d.xcoord = x;
				d.ycoord = y;
				
				d.id = nm + "x" + xc;
				
				d.cx = xc;
				d.cy = yc;

				$(d).attr('cx', xc)
				    .attr('cy', yc);

				// distancia máxima que um jogador pode percorrer no mapa
				d.ondblclick = function () {
					if( Math.abs(__x - this.cx) > <?php echo $range_movimentacao ? 3 : 2 ?> || Math.abs(__y - this.cy) > <?php echo $range_movimentacao ? 3 : 2 ?> ) {
						alert("<?php echo t('bijuus.mapa_longe'); ?>");
						return;
					}
						
					__x = this.cx;
					__y = this.cy;
					
					$.ajax({
						url: 'index.php?acao=mapa_posicoes_vila&go=1',
						type: 'post',
						data: {x: __x, y: __y, key: '<?php echo $_SESSION['mapa_vila_key'] ?>'},
						dataType: 'script'
					});													  

					remapClickCoords();
				}

				d.className = "ngCoordItem";
				
				$('#ngmap-container').append(d);
				
				xc++; //= 20;
			}
			
			yc++; // += 20;
		}
		
		remapClickCoords();
	});
	
	function coordClick() {
		__hItemClick = {a: this.id, b: this};
		doDetailAt(this.id, this);		
	}
	
	function coordHover() {
		if($(this).attr("cx") == __x && $(this).attr("cy") == __y) {
			return;
		}
		
		if(Math.abs($(this).attr("cx") - __x) > <?php echo $range_movimentacao ? 3 : 2 ?> || Math.abs($(this).attr("cy") - __y) > <?php echo $range_movimentacao ? 3 : 2 ?>) {
			$(this).addClass('map-coord-hover-far');
		} else {
			$(this).addClass('map-coord-hover-near');
		}
	}
	
	function coordOut() {
		if($(this).attr("cx") == __x && $(this).attr("cy") == __y) {
			return;
		}
		
		$(this).removeClass('map-coord-hover-near map-coord-hover-far');
	}
	
	function remapClickCoords() {
		$(".ngCoordItem").removeClass('map-coord-center map-coord-hover-near map-coord-hover-far');
		
		$(".ngCoordItem").each(function () {
			if($(this).attr("cx") == __x && $(this).attr("cy") == __y) {
				$(this).addClass('map-coord-center');
					   
				try{ $(this).unbind(coordClick); } catch(e) {};
				try{ $(this).unbind(coordHover); } catch(e) {};
				try{ $(this).unbind(coordOut); } catch(e) {};
			} else {
				$(this).click(coordClick).mouseover(coordHover).mouseout(coordOut);
			}
		});		
	}

	function doDetailAt(ii, o) {
		if(_tipMatrix[ii]) {
			var hHTML = "<table style='width: 280px !important' border='0' cellpadding='0' cellspacing='3'>";
			
			var isLoc = false;
			
			for(var i in _tipMatrix[ii]) {
					if(_tipMatrix[ii][i][3] == 0) continue;
					
					//isLoc = 1;
					
					hHTML += 
					"<tr style='color: #FFF'>" +
					"<td width='16'><img src='<?php echo img() ?>layout/ico_mapa_vila.png'> </td>"+
					"<td width='200'>" + _tipMatrix[ii][i][0] + "</td> " +
					"<td width='60' align='center'>&nbsp;</td>" +
				   "</tr>";
			}
			
			if(!isLoc) {
				// Players
				for(var i in _tipMatrix[ii]) {
						if(_tipMatrix[ii][i][3] == 1) continue;
					
						var bBatalha	= "<img src='<?php echo img() ?>layout/legenda/bt_mapa_batalhar.png' border='0' title='<?php echo t('classes.c61')?>'/>";						
						var img 		= '<?php echo img('layout/sprites/') ?>' + _tipMatrix[ii][i][11] + '.png';
						var	bgcolor		= 'none';

						if(!_tipMatrix[ii][i][10]) { // Não está usando camuflagem
							switch(_tipMatrix[ii][i][7]) {
								case 0: // Neutro
									bgcolor	= 'url(<?php echo img() ?>layout/map_gray.png)';
								
									break;
									
								case 1: // Aliado
									bgcolor	= 'url(<?php echo img() ?>layout/map_green2.png)';
								
									break;
									
								case 2: // Inimigo
									bgcolor = 'url(<?php echo img() ?>layout/map_red2.png)';
								
									break;
	
								case 3:
									bgcolor = 'url(<?php echo img() ?>layout/map_yellow2.png)';
								
									break;
							}							
						} else {
							bgcolor	= 'url(<?php echo img() ?>layout/map_green2.png)';
						}
						
						if(_tipMatrix[ii][i][4] == "<?php echo addslashes(encode($basePlayer->id)) ?>") {
							bgcolor = 'url(<?php echo img() ?>layout/map_me2.png)';
						
							hHTML += 
							"<tr style='color: #FFF'>" +
							"<td width='16' style='background-image: " + bgcolor + "'><img src='" + img +"'> </td>"+
							"<td colspan='3' width='200'>Você está aqui</td> " +
						    "</tr>";							
						} else {
							hHTML += 
							"<tr style='color: #FFF'>" +
							"<td width='16' style='background-image: " + bgcolor + "'><img src='" + img +"'> </td>"+
							"<td width='200'>" + _tipMatrix[ii][i][0] + " [Lvl " + _tipMatrix[ii][i][6] + "]<br />" + _tipMatrix[ii][i][5] + "</td> " +
							"<td width='60' align='center'><a href='javascript:void(0)' title='<?php echo t('classes.c61')?>' onclick=\"doMapBattle('" + _tipMatrix[ii][i][4] + "')\">" +
							bBatalha +
						   "</a><a href='javascript:void(0)' title='<?php echo t('botoes.enviar_mensagem')?>' class=\"message\" data-to=\"" + _tipMatrix[ii][i][0] + "\">" +
						   "<img src='<?php echo img() ?>layout/legenda/bt_mapa_mensagem.png' border='0' alt='<?php echo t('botoes.enviar_mensagem')?>'/></a></td>" +
						   "</tr>";							
						}
				}
			}
			
			hHTML += "</table>";
			
			doTip(hHTML, {o: o, d: false});
		} else {
			doTip("<?php echo t('bijuus.mapa_vazio'); ?>", {o: o, d: true});
		}
	}
	
	function doTip(msg, opt) {
		var d = $(document.createElement("DIV"));
		
		$("._mapTipClass").remove();
		
			d.css("top", $(opt.o).offset().top).css("left", $(opt.o).offset().left + $(opt.o).width() + 3);		

		d.addClass("_mapTipClass")
		 .css("position", "absolute").css("z-index", "3").css("border-color", "#B1B2B4 #434445 #2F3032").css("color", "#FFF").css("padding", "5px")
		 .css("background-color", "#0a0a0a")
		 .css("border-style", "solid")
		 .css("border-width", "2px")
		 .html(msg);
		 
		$('.message', d).on('click', function () {
			$('#chat-v2 input[name=message]').trigger('pvt-switch', [$(this).data('to')]).focus();
		});

		if(opt.d) {
			
		}
		
		$(document.body).append(d);

		<?php //if($_SESSION['universal']): ?>
			var y = opt.o.id.substring(1, opt.o.id.indexOf('x'));
			var x = opt.o.id.substring(opt.o.id.indexOf('x') + 1);
			
			if(x >= 11) {
				var l = $(opt.o).offset().left - 18;
				
				d.css("left", l - d.width());
			}
			
			if(y >= 11) {
				d.css('top', parseInt(d.css('top')) - d.height() - 18);
			}
		<?php //endif ?>
	}

	var __bResponded = true;
	function doMapBattle(i) {
		if(!__bResponded) return;
		
		__bResponded = false;
		lock_screen(true);
		
		$.ajax({
			url: 'index.php?acao=mapa_batalha_vila',
			dataType: 'script',
			type: 'post',
			data: {id: i},
			success: function () {
				lock_screen(false);
				__bResponded = true;
			},
			error: function () {
				lock_screen(false);
				__bResponded = true;
			}
		});		
	}
	
	function updateTipMatrix() {
		$(".ngCoordItem").css('background-image', 'url(<?php echo img('layout/map_blank2.png') ?>)');
		
		for(var i in _tipMatrix) {
			if(!_tipMatrix[i]) continue;
			if(!_tipMatrix[i][0]) continue;
			
			try {
			
			var d = $C('DIV', {className: "matrixTipItem"});
			
			d.style.position = "absolute";
			
			if(!$("#y" + _tipMatrix[i][0][2] + "x" + _tipMatrix[i][0][1]).offset()) {
				continue;
			}
			
			var	obj_coord	= $("#y" + _tipMatrix[i][0][2] + "x" + _tipMatrix[i][0][1]);
			
			d.style.top		= (parseInt(obj_coord.css('top')) + 6) + "px";
			d.style.left	= (parseInt(obj_coord.css('left')) + 7) + "px";			
			d.style.zIndex	= 1;
			
			$(d).data('x', _tipMatrix[i][0][1])
				.data('y', _tipMatrix[i][0][2])
				.on('click', function () {
					var	_	= $(this);
					
					$('#y' + _.data('y') + 'x' + _.data('x')).trigger('click');
				}).on('dblclick', function () {
					var	_	= $(this);
					
					$('#y' + _.data('y') + 'x' + _.data('x')).trigger('dblclick');					
				}).on('mouseover', function () {
					var	_	= $(this);
					
					$('#y' + _.data('y') + 'x' + _.data('x')).trigger('mouseover');					
				}).on('mouseout', function () {
					var	_	= $(this);
					
					$('#y' + _.data('y') + 'x' + _.data('x')).trigger('mouseout');					
				});
			
			if(_tipMatrix[i][0][7] != 3) { // Normal
				/*
				if(!_tipMatrix[i][0][3] && _tipMatrix[i][0][8] == __idv) { // msm vila
					var	img		= '<?php echo img('layout/sprites/') ?>' + _tipMatrix[i][0][11] + '.png';
					var	bgcolor	= 'url(<?php echo img() ?>map_green2.png)';
				} else if(!_tipMatrix[i][0][3] && _tipMatrix[i][0][8] != __idv) { // outra vila
				*/
					var	img		= '<?php echo img('layout/sprites/') ?>' + _tipMatrix[i][0][11] + '.png';
					var	bgcolor	= 'url(<?php echo img() ?>map_blank.png)';
					
					switch(_tipMatrix[i][0][7]) {
						case 0: // Neutro
							var	bgcolor	= 'url(<?php echo img() ?>layout/map_gray.png)';
						
							break;
							
						case 1:
							var	bgcolor	= 'url(<?php echo img() ?>layout/map_green2.png)';
						
							break;
							
						case 2:
							var	bgcolor	= 'url(<?php echo img() ?>layout/map_red2.png)';
						
							break;
		
						case 3:
							var	bgcolor	= 'url(<?php echo img() ?>layout/map_yellow2.png)';
						
							break;
					}					
				//}
			} else { // Bingo Book
				var	img		= '<?php echo img('layout/sprites/') ?>' + _tipMatrix[i][0][11] + '.png';
				var	bgcolor	= 'url(<?php echo img() ?>layout/map_yellow2.png)';
			}
			
			obj_coord.css('background-image', bgcolor);
			
			var is_door = false;

			for(ii in _tipMatrix[i]) {
				if(_tipMatrix[i][ii][3]) {
					img = "<?php echo img() ?>layout/ico_mapa_vila.png";
					
					is_door = true;
					
					obj_coord.css('background-image', 'url(<?php echo img('layout/map_blank2.png') ?>)');
				}
			}
		
			/*
			if(_tipMatrix[i][ii][9] == 1) {
				if(!is_door) {
					var img = '<?php echo img('ico_ninja_renegado.png') ?>';				
				}
			}

			if(_tipMatrix[i][ii][10] == 1) {
				if(!is_door) {
					var img = '<?php echo img('ico_mapa_ninja2.png') ?>';
				}
			}
			*/

			d.innerHTML = "<img src='" + img + "' />";

			$('#ngmap-container').append(d);
			
			// Atualização dinamica das tooltips --->
				if(__hItemClick) {
					doDetailAt(__hItemClick.a, __hItemClick.b);
				}
			// <---
			
			} catch(e) {
				if(console) {
					console.log("matrix error -> " + "#y" + _tipMatrix[i][0][2] + "x" + _tipMatrix[i][0][1]);
					console.log(_tipMatrix[i]);
					console.log(e);
				}
			}
		}
	}
	
	var _pause		= false;
	_mapPlayerCheck = setInterval(function() {
		if(!_canDoMapCheck) return;
		if(_pause) return;
		
		_canDoMapCheck = false;
		
		$.ajax({
			url: 'index.php?acao=mapa_posicoes_vila&detail=' + step,
			dataType: 'script',
			type: 'post',
			data: {key: '<?php echo $_SESSION['mapa_vila_key'] ?>'},
			success: function () {
				_canDoMapCheck	= true;
			},
			error: function () {
				_canDoMapCheck	= true;
			}
		});													  
	}, 2000);

</script>
<div class="titulo-secao"><p><?php echo t('titulos.mapa_vila')?></p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "4950698974";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Mapa Vila -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br /><br /><br />

<?php
	if(isset($_GET['e']) && $_GET['e']) {
		msg('4',''.t('academia_jutsu.problema').'', ''.t('actions.a109').'');

	}
?>

<div id="cnBase">
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td>
		<div id="ngmap-container" style="background-image: url(<?php echo img() ?>layout/mapa/<?php echo $basePlayer->getAttribute('id_vila_atual') ?>.jpg); width: 720px; height: 720px; position: relative; left: 7px;"></div>
      <td><div id="cnPositionList"></div></td>
    </tr>
  </table><br /><br />
</div>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- NG - Mapa Vila -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9166007311868806"
     data-ad-slot="4950698974"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
  	<td width="50%" colspan="2" valign="top"><br />
  		<div class="titulo-home" style="width: 730px"><p><span class="laranja">//</span> <?php echo t('classes.c62')?> .......................................................................</p></div>
  		</td>
  	</tr>
</table>
<br />
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
			<?php echo t('classes.c74')?><br />
        </td>
	</tr>
</table>
