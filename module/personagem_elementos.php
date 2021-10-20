<?php
	$_SESSION['el_js_func_name']	= $js_function = "f" . md5(rand(1, 512384));
	$_SESSION['el_js_field_name']	= $js_field_name = "f" . md5(rand(1, 512384));

	$js_functionb					= $_SESSION['el_js_func_nameb'] = "f" . md5(rand(1, 512384));
	$pay_key_1						= $_SESSION['pay_key_1'] = round(rand(1, 999999)); // Coin

	$elementos			= Recordset::query('SELECT * FROM elemento ORDER BY id ASC', true);
	$player_elementos	= $basePlayer->getElementos();

	$connectors			= array();
	$connectors_i		= array();
	$connector_list		= Recordset::query('SELECT * FROM elemento_conexao', true);
	$current_counter	= Player::getFlag('elemento_sair_count', $basePlayer->id);

	if(!$current_counter) {
		$exit_string	= 'mudar_elemento1';
	} elseif($current_counter == 1) {
		$exit_string	= 'mudar_elemento2';
	} else {
		$exit_string	= 'mudar_elemento3';
	}

	foreach($connector_list->result_array() as $connector) {
		if(!isset($connectors[$connector['id_elemento2']])) {
			$connectors[$connector['id_elemento2']]	= array();
		}

		$connectors[$connector['id_elemento2']][]	= $connector['id_elemento1'];
	}
	
	$connector_list		= Recordset::query('SELECT * FROM elemento_conexao ORDER BY id_elemento1 ASC', true);

	foreach($connector_list->result_array() as $connector) {
		$connectors_i[$connector['id_elemento1']][]	= $connector['id_elemento2'];

		if(!isset($connectors_i[$connector['id_elemento1']])) {
			$connectors_i[$connector['id_elemento1']]	= array();
		}
	}

	$lvl1_el	= 0;
	$lvl2_el	= 0;
	
	foreach($player_elementos as $player_elemento) {
		$elemento	= Recordset::query('SELECT nivel FROM elemento WHERE id=' . $player_elemento, true)->row_array();	
		
		if($elemento['nivel'] == 1) {
			$lvl1_el++;	
		} else {
			$lvl2_el++;	
		}
	}
?>
<?php if(!$basePlayer->tutorial()->elementos){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 8,
	 
	  steps: [
	  {
		element: "#bg_elements",
		title: "<?php echo t("tutorial.titulos.elementos.1");?>",
		content: "<?php echo t("tutorial.mensagens.elementos.1");?>",
		placement: "top"
	  },{
		element: "#bg_elements",
		title: "<?php echo t("tutorial.titulos.elementos.2");?>",
		content: "<?php echo t("tutorial.mensagens.elementos.2");?>",
		placement: "top"
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
<style type="text/css">
	#bg_elements{
		width: 765px;
		height: 709px;
		background-repeat: none;	
		background-image:url('images/layout_azul/bg_elementos.png');	
	}
	#elements {
		position: relative;
		
	}

	#elements, #elements-container {
		position: absolute;
		width: 730px;
		height: 730px;
		z-index: 3;
	}

	#elements #connector, #elements #connector-hover {
		width: 730px;
		height: 730px;
		position: absolute;
		top: 0px;
		left: 0px;
		z-index: 1;
	}
	
	#elements #connector-hover {
		z-index: 2;
	}

	#elements-container .el {
		position: absolute;
		width: 48px;
		height: 48px;
		border-radius: 32px;
		cursor: pointer;
		background-repeat: no-repeat
	}

	#elements-container .el-hover{
		
	}
	#elements-container .el-learned {
		margin-top: -2px;
		margin-left: -2px;
		box-shadow: 0px 0px 10px #2b1a10
	}
</style>
<?php if($_SESSION['usuario']['msg_vip']){?>
<script type="text/javascript">
	head.ready(function () {
	$(document).ready(function() {
	if(!$.cookie("elementos")){
	$("#dialog").dialog({ 
		width: 410,
		height: 480, 
		title: '<?php echo t('personagem_elementos.pop_up_title'); ?>', 
		modal: true,
		close: function(){
			$.cookie("elementos", "foo", { expires: 1 });
		}
	
		});
	}
	});
	});
</script>
<?php }?>
<script type="text/javascript">
	$(document).ready(function () {
		var PI				= Math.PI;
		var DELTA1			= Math.PI / (5 / 2);
		var DELTA2			= Math.PI / (10 / 2);
		var RADIUS1			= 120;
		var RADIUS2			= 270;
		var CENTER_X		= $('#elements-container').width() / 2;
		var CENTER_Y		= $('#elements-container').height() / 2;
		var	positions		= [[], []];
		var connectors_i	= [<?php echo join(',', array_map(function ($data) {
			return '[' . join(',', $data) . ']';
		}, $connectors_i)); ?>];
		var connectors		= [<?php echo join(',', array_map(function ($data) {
			return '[' . join(',', $data) . ']';
		}, $connectors)) ?>];

		<?php
			$ids		= array();
			$base_ids	= array();

			$lvl1		= array();
			$lvl2		= array();
			$ids1		= array();
			$ids2		= array();
			$base_ids1	= array();
			$base_ids2	= array();
			$my_ids		= array();
			
			foreach($player_elementos as $player_elemento) {
				$my_ids[]	= $player_elemento;
			}
		
			foreach($elementos->result_array() as $elemento) {
				$rnd_id	= '"elem-' . uniqid() . '"';
				
				if($elemento['nivel'] == 1) {
					$lvl1[]			= '"#' . $elemento['cor'] . '"';
					$ids1[]			= $rnd_id;
					$base_ids1[]	= $elemento['id'];
				} else {
					$lvl2[]			= '"#' . $elemento['cor'] . '"';	
					$ids2[]			= $rnd_id;
					$base_ids2[]	= $elemento['id'];
				}
				
				$ids[]		= $rnd_id;
				$base_ids[]	= $elemento['id'];
			}
		?>

		var colors1		= [<?php echo join(',', $lvl1) ?>];
		var colors2		= [<?php echo join(',', $lvl2) ?>];
		var el_ids1		= [<?php echo join(',', $ids1) ?>];
		var el_ids2		= [<?php echo join(',', $ids2) ?>];
		var base_ids1	= [<?php echo join(',', $base_ids1) ?>];
		var base_ids2	= [<?php echo join(',', $base_ids2) ?>];
		var my_ids		= [<?php echo join(',', $my_ids) ?>];
		var _gc			= 0;

		function draw_circle(delta, radius, colors, el_ids, ids, p) {
			var	c	= 0;

			for(var angle = PI*2; angle > 0; angle-=delta) {
				var	d			= $(document.createElement('DIV'));
				var	y			= CENTER_Y + Math.cos(angle + PI) * radius;
				var x			= CENTER_X + Math.sin(angle + PI) * radius;
				var has_color	= false;
				var id			= p == 0 ? base_ids1[c] : base_ids2[c];
				
				d
					.addClass('el')
					.attr('id', el_ids[c])
					.data('color', colors[c])
					.data('pos', c)
					.data('gpos', _gc)
					.data('id', id)
					.css('background-image', 'url(<?php echo img('layout/elementos/') ?>' + (_gc+1) + 'w.png)')
					.on('mouseover', function () {
						var	_	= $(this);
						
						
						var	ctx			= $('#connector-hover')[0].getContext('2d');	
						
						_.addClass('el-hover').css('background-color', _.data('color'));
						
						if(p == 0) {
							var	cns			= connectors_i[_.data('pos')];
							var	e_coords	= positions[0][_.data('pos')];
							
							for(var i in cns) {
								var	i_coords	= positions[1][cns[i]-6];

								ctx.beginPath();

								ctx.shadowOffsetX	= 0;
								ctx.shadowOffsetY	= 0;
								
								//ctx.shadowColor	= '#FFF';
								//ctx.shadowBlur	= 6;
								ctx.strokeStyle	= colors2[cns[i]-6];
								ctx.lineWidth	= 3;
				
								ctx.moveTo(e_coords[0], e_coords[1]);
								ctx.lineTo(i_coords[0], i_coords[1]);
				
								ctx.stroke();
																
								var target	= $('#' + el_ids2[cns[i]-6]);
								
								target.addClass('el-hover').css('background-color', target.data('color'));								
							}
						} else {
							var	e_coords	= positions[1][_.data('pos')];
							
							for(var h=0; h <= 1; h++) {
								var	i_coords	= positions[0][connectors[_.data('pos')][h] - 1];
				
								ctx.beginPath();

								ctx.shadowOffsetX	= 0;
								ctx.shadowOffsetY	= 0;
								
								//ctx.shadowColor	= '#FFF';
								//ctx.shadowBlur	= 6;
								ctx.strokeStyle	= colors1[connectors[_.data('pos')][h] - 1];
								ctx.lineWidth	= 3;
				
								ctx.moveTo(e_coords[0], e_coords[1]);
								ctx.lineTo(i_coords[0], i_coords[1]);
				
								ctx.stroke();
																
								var target	= $('#' + el_ids1[connectors[_.data('pos')][h] - 1]);
								
								target.addClass('el-hover').css('background-color', target.data('color'));
							}
						}
					})
					.on('mouseout', function () {
						$('#connector-hover')[0].getContext('2d').clearRect(0, 0, 730, 730);
						$('#elements-container .el').each(function () {
							var	_	= $(this);
							
							if(_.hasClass('el-hover')) {
								_.removeClass('el-hover');
								
								if(!_.hasClass('el-learned')) {
									_.css('background-color', '#301a0e');
								}
							}
						});
					})
					.on('click', function () {
						var	_	= $(this);
						
						if(parseInt(_.data('learnable'))) {
							jconfirm('<?php echo t('geral.m7')?>', null, function () {
								$.ajax({
									url: 'index.php?acao=personagem_elementos_aprender',
									dataType: 'script',
									type: 'post',
									data: {<?php echo $js_field_name ?>: _.data('id')}
								});								
							});
						} else if(parseInt(_.data('unlearnable'))) {
							jconfirm('<?php echo t('personagem_elementos.' . $exit_string); ?>', null, function () {
										 
									$.ajax({
										url: 'index.php?acao=personagem_elementos_sair',
										dataType: 'script',
										type: 'post',
										data: {pm: <?php echo $pay_key_1 ?>, id: _.data('id') }
									});
							});
						}
					});

				$('#elements-container').append(d);

				d.css({
					top:	y - d.height() / 2,
					left:	x - d.width() / 2
				});
				
				for(var g in my_ids) {
					if(my_ids[g] == ids[c]) {
						has_color	= true;
					}
				}
				
				if(has_color) {
					d.css({backgroundColor: colors[c]}).data('have', 1).addClass('el-learned');
					d.css('background-image', d.css('background-image').replace('w.png', 'w.png'));
				} else {
					d.css({backgroundColor: '#301a0e'});
				}
				
				c++;
				_gc++;

				positions[p].push([x, y]);
			}
		}

		draw_circle(DELTA1, RADIUS1, colors1, el_ids1, base_ids1, 0);
		draw_circle(DELTA2, RADIUS2, colors2, el_ids2, base_ids2, 1);

		// Canvas
		var	ctx	= $('#connector')[0].getContext('2d');

		for(var i=0; i < connectors.length; i++) {
			var	e_coords	= positions[1][i];

			for(var h=0; h <= 1; h++) {
				var	i_coords	= positions[0][connectors[i][h] - 1];
				var has_color	= false;

				ctx.beginPath();
				
				for(var g in my_ids) {
					if(my_ids[g] == connectors[i][h]) {
						has_color	= true;
					}
				}
				
				if(has_color) {
					ctx.strokeStyle	= colors1[connectors[i][h] - 1];
				} else {
					ctx.strokeStyle	= '#301a0e';
				}
				
				ctx.lineWidth	= 3;

				ctx.moveTo(e_coords[0], e_coords[1]);
				ctx.lineTo(i_coords[0], i_coords[1]);

				ctx.stroke();
			}
		}
		
		$('#elements-container .ex_tooltip').attr('is_tooltip', 0);
		
		updateTooltips();
	});
</script>
<div id="HOTWordsTxt" name="HOTWordsTxt">
	<div class="titulo-secao"><p><?php echo t('titulos.elementos') ?></p></div>
	<!-- Mensagem nos Topos das Seções -->
	<?php msg('1',''. t('personagem_elementos.msg_titulo') .'', ''. t('personagem_elementos.msg_desc') .'');?>
	<!-- Mensagem nos Topos das Seções -->
	<br/>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<!-- NG - Habilidades -->
	<ins class="adsbygoogle"
		 style="display:inline-block;width:728px;height:90px"
		 data-ad-client="ca-pub-9166007311868806"
		 data-ad-slot="8183366979"></ins>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script>
	<?php  if(isset($_GET['c']) && is_numeric($_GET['c'])): ?>
		<?php msg('2',''. t('personagem_elementos.novo_elemento1') .'',''. t('personagem_elementos.novo_elemento2') .'');?>
	<?php endif ?>
	<?php if(isset($_GET['s']) && is_numeric($_GET['s'])): ?>
		<?php msg('3',''. t('personagem_elementos.desaprender1') .'',''. t('personagem_elementos.desaprender2') .'');?>	
	<?php endif ?>
	<div class="break"></div>
	<div id="bg_elements">
		<div id="elements">
			<div id="elements-container">
				<?php foreach($ids as $k => $v): ?>
				<?php ob_start() ?>
					<?php
						$campo		= sizeof($player_elementos) ? "b" : "a";
						$elemento	= Recordset::query('
							SELECT
								a.*, 
								(SELECT id FROM graduacao WHERE id=a.req_graduacao_a) AS nome_req_graduacao_a,
								(SELECT id FROM graduacao WHERE id=a.req_graduacao_b) AS nome_req_graduacao_b
							
							FROM 
								elemento a WHERE id=' . $base_ids[$k])->row_array();
					
						$grd_color		= $basePlayer->getAttribute('id_graduacao') >= $elemento['req_graduacao_' . $campo] ? "style='text-decoration: line-through'" : "style='color: #fd2a2a'";
						$lvl_color		= $basePlayer->getAttribute('level') >= $elemento['req_level_' . $campo] ? "style='text-decoration: line-through'" : "style='color: #fd2a2a'";
						//$gate_color		= ( $basePlayer->id_classe_tipo == 2 || $basePlayer->id_classe_tipo == 3 ) ? "style='text-decoration: line-through'" : "style='color: #fd2a2a'";
						
						$haslevel		= $basePlayer->getAttribute('level') >= $elemento['req_level_' . $campo] ? true : false;
						$hasgrad		= $basePlayer->getAttribute('id_graduacao') >= $elemento['req_graduacao_' . $campo] ? true : false;
						$hasel			= in_array($elemento['id'], $player_elementos) ? true : false;
						
						$reqs			= !$hasel && $hasgrad && $haslevel;
						$unlearnable	= true;
						
						if($elemento['nivel'] == 1) {
							$reqs	= $reqs && $lvl1_el < 2;
							
							if($lvl2_el >= 1 || !$hasel) {
								$unlearnable	= false;
							}
						} else {
							if(sizeof($player_elementos) != 3) {
								$unlearnable	= false;
							} else {
								$unlearnable	= in_array($elemento['id'], $player_elementos);
							}
						
							$reqs	= $reqs && $lvl1_el == 2 && $lvl2_el < 1;
							
							foreach($connectors[$elemento['id']] as $connector) {
								$reqs	= $reqs && in_array($connector, $player_elementos);
							}
						}
						
						/*if($basePlayer->id_classe_tipo == 1 || $basePlayer->id_classe_tipo == 4) {
							$reqs	= false;
						}*/
					?>
					<b class="bold_bege"><?php echo $elemento['nome'] ?></b><br /><br />
					<?php echo $elemento['descricao_' . Locale::get()] ?>
					<hr />
					<b><?php echo t('geral.requerimentos'); ?></b><br /><br />
					<?php echo t('personagem_elementos.saiba'); ?>
					<ul>
						<li <?php echo $lvl_color ?>>
							&bull; <?php echo t('requerimentos.ser_level') ?> <?php echo $elemento['req_level_' . $campo] ?>
						</li>
						<li <?php echo $grd_color ?>>
							&bull; <?php echo t('requerimentos.ser') ?> <?php echo graduation_name($basePlayer->id_vila, $elemento['nome_req_graduacao_' . $campo])?>
						</li>
						<?php /*
						<li <?php echo $gate_color ?>>
							&bull; <?php echo t('requerimentos.no_taijutsu') ?>
						</li>
						*/?>
					</ul>
					<?php if($reqs): ?>
					<hr />
					<p><?php echo t('botoes.clique_aprender') ?></p>
					<?php elseif($unlearnable): ?>
					<hr />
					<p><?php echo t('botoes.clique_remover') ?></p>
					<?php endif ?>
				<?php generic_tooltip(str_replace('"', '', $v), ob_get_clean()); ?>
				<?php if($reqs): ?>
					<script type="text/javascript">
						$(document).ready(function () {
							$('#<?php echo str_replace('"', '', $v) ?>').data('learnable', 1);
						});
					</script>
				<?php elseif($unlearnable): ?>
					<script type="text/javascript">
						$(document).ready(function () {
							$('#<?php echo str_replace('"', '', $v) ?>').data('unlearnable', 1);
						});
					</script>
				<?php endif ?>
				<?php endforeach ?>
			</div>
			<canvas id="connector" width="730" height="730"></canvas>
			<canvas id="connector-hover" width="730" height="730"></canvas>
		</div>
	</div>
</div>
