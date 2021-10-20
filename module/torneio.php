<?php
	if(isset($_GET['player']) && $_GET['player'] && is_numeric($_GET['player'])) {
		$player = Recordset::query('SELECT id FROM player WHERE id=' . (int)$_GET['player'] . ' AND removido=0');
		
		if($player->num_rows) {
			$pid	= $player->row()->id;
			$is_pid	= true;
		}
	} else {
		$pid	= $basePlayer->id;
		$is_pid	= false;
	}
	
	$_SESSION['torneio_key']	= md5(date("YmdHis") . rand(1, 32768));
	
	$torneios			= Recordset::query('SELECT * FROM torneio ORDER BY ordem');
	$torneios_player	= Recordset::query('SELECT * FROM torneio_player WHERE id_player=' . $pid);
	//$torneio_player		= Recordset::query('SELECT * FROM torneio_player WHERE participando=\'1\' AND id_player=' . $pid)->row_array();
	$torneio_atual		= Recordset::query('SELECT * FROM torneio WHERE id=' . (int)$basePlayer->getAttribute('id_torneio'), true)->row_array();
	$liberado			= !Player::getFlag('torneio_ganho', $basePlayer->id);
?>
<style>
.torneio-tooltip {
	position: absolute;
	display: none;
	width: 200px;
	border: solid 4px #ff6600;
	padding: 4px;
	background-color: #252525;
	z-index: 101;
	text-align: left;
}
.torneio-tooltip ul, .torneio-tooltip li {
	padding: 0px;
	margin: 0px;
}
.torneio-tooltip ul {
	padding-left: 20px;
}
.d-conquista-grupo {
	display: none;
	width: 696px;
	border: solid 1px #21170d;
	padding: 10px;
	padding-top: 10px;
	padding-bottom: 10px;
	margin-left: 18px;
	margin-top: -9px;
	margin-bottom: 10px;
	position: relative;
	z-index: 1;
}
.grupoConquista {
	display: block;
	clear: left;
}
.conquista-grupo-titulo {
	position: relative;
}
</style>
<script type="text/javascript">
	function abaConquista(i) {
		$(".grupoConquista").hide();
		$("#cGrupo_" + i).show();
	}
	
	$(".conquista-item th.desc").on("mouseover", function () {
		$(".t", $(this)).show();
	})
		.on("mouseout", function () {
		$(".t", $(this)).hide();
	});

	$(".conquista-grupo-titulo").on("mouseover", function () {
		$(".tt", $(this)).show();
	})
						.on("mouseout", function () {
		$(".tt", $(this)).hide();
	});

	function conquistaDetalhe(i) {
		$("#d-conquista-grupo-" + i).toggleClass("show");
		$("#d-conquista-grupo-" + i + " .borda"); //.bg(10);
	}
</script>
<script>
<?php if($basePlayer->getAttribute('id_torneio')): ?>
	function torneioDesistir() {
		jconfirm('<?php echo t('torneios.desistir_torneio');?>', null, function () {
			$('#f-torneio').attr('action', '?acao=torneio_sair')[0]
						   .submit();
		});
	}
	
	function torneioDesafio() {
		$('.b-challenge').attr('disabled', 'disabled');
		
		$('#f-torneio').attr('action', '?acao=torneio_desafio')[0]
					   .submit();	
	}
<?php else: ?>
	function torneioParticipar(v) {
		$('.b-participate').attr('disabled', 'disabled');
		
		$('#f-torneio-h-torneio').val(v);
		$('#f-torneio')[0].submit();
	}
<?php endif; ?>
	function detalheTorneio(o, t) {
		o = $(o);
		
		if(!parseInt(o.attr('shown'))) {
			$('#d-torneio-' + t).hide();
			$('#d-torneio-' + t + '-d').fadeIn('slow');
			
			o.attr('shown', 1);
		} else {
			$('#d-torneio-' + t).fadeIn('slow');
			$('#d-torneio-' + t + '-d').hide();

			o.attr('shown', 0);
		}
	}
	
	function doTorneioTab(o, t) {
		$('#torneio-tab-1').attr('background', '<?php echo img('torneios/bt_torneios_npc.gif') ?>');
		$('#torneio-tab-2').attr('background', '<?php echo img('torneios/bt_torneios_pvp.gif') ?>');
		
		$.cookie('_torneio-tab', t);
		
		switch(parseInt(t)) {
			case 1:
				$('.pvp').hide();
				$('.npc').show();
				
				$(o).attr('background', '<?php echo img('torneios/bt_torneios_npc_on.gif') ?>');
				
				break
			
			case 2:
				$('.pvp').show();
				$('.npc').hide();
				
				$(o).attr('background', '<?php echo img('torneios/bt_torneios_pvp_on.gif') ?>');
			
				break;
		}
	}
	
	<?php if(!$is_pid): ?>
	$(document).ready(function () {
		$('.torneios_divisao').mouseover(function (e) {
			var id = $(this).attr('id').replace(/[^\d]+/, '');
			
			$('#torneio-tooltip-' + id).show()
									   .css('left', e.pageX - $('#pagina').offset().left - 260)
									   .css('top',  e.pageY - 600)
									   .mouseover(function () {
											$('.torneio-tooltip').hide();
									   });
			
			//Console.log(e);
		}).mouseout(function () {
			var id = $(this).attr('id').replace(/[^\d]+/, '');
		
			$('.torneio-tooltip').hide();
		});
	});
	<?php endif; ?>
</script>
<?php if(isset($_GET['e']) && $_GET['e']): ?>
<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/6.png); background-repeat: no-repeat;">
	<b><?php echo t('requerimentos.problema');?></b>
		<?php
			switch($_GET['e']) {
				case 1:
					echo '<p>'.t('requerimentos.nao_tem').'</p>';
					
					break;
			}
		?>
		</div>
	</div>
</div>
<?php endif; ?>
<div class="titulo-secao"><p><?php echo t('titulos.torneio_ninja');?></p></div>
<br />
<?php msg('1',''.t('torneios.titulo2').'', ''.t('torneios.espera_desc2').'');?>

<br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "7345762174";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Torneio -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br /><br />
<table width="730" border="0" cellpadding="0" cellspacing="0" class="with-n-tabs" id="tabs-torneio" data-auto-default="1" >
	<tr>
		<td><a class="button" rel=".npc"><?php echo t('torneios.torneio_npc');?></a></td>
		<td><a class="button" rel=".pvp"><?php echo t('torneios.torneio_pvp');?></a></td>
	</tr>
</table>
<br />
<form id="f-torneio" method="post" action="?acao=torneio_entrar" onsubmit="return false">
	<input type="hidden" name="torneio" id="f-torneio-h-torneio" value="" />
	<input type="hidden" name="torneio_key" id="torneio_key" value="<?php echo $_SESSION['torneio_key'] ?>" />
	<div class="grupoConquista">
		<?php 
		$ccc = 0;
		foreach($torneios->result_array() as $torneio): ?>
		<?php
		$tp		= array();
		$reqs	= true;
		
		foreach($torneios_player->result_array() as $k => $v) {
			if($v['id_torneio'] == $torneio['id']) {
				$tp = $v;
				
				break;
			}
		}
		
		$tooltip = '';

		if($torneio['req_id_graduacao']) {
			$grad	= Recordset::query('SELECT * FROM graduacao WHERE id=' . $torneio['req_id_graduacao'])->row_array();
			$reqs	= $basePlayer->getAttribute('id_graduacao') == $torneio['req_id_graduacao'] ? $reqs : false;
		}
		
		if($torneio['req_id_vila']) {
			$vila	= Recordset::query('SELECT *, nome_'.Locale::get().' AS nome FROM vila WHERE id=' . $torneio['req_id_vila'])->row_array(); 
			$reqs	= $basePlayer->getAttribute('id_vila') == $torneio['req_id_vila'] ? $reqs : false;
		}
		
		if($torneio['req_vitorias_pvp']) {
			$pvp	= $basePlayer->getAttribute('vitorias')  + $basePlayer->getAttribute('vitorias_f');
			$reqs	= $pvp >= $torneio['req_vitorias_pvp'] ? $reqs : false;
		}
		
		if($torneio['req_vitorias_npc']) {
			$npc	= $basePlayer->getAttribute('vitorias_d');
			$reqs	= $npc >= $torneio['req_vitorias_npc'] ? $reqs : false;
		}
		
		if($torneio['req_id_cla']) {
			$cla	= Recordset::query('SELECT * FROM cla WHERE id=' . $torneio['req_id_cla'])->row_array(); 
			$reqs	= $basePlayer->getAttribute('id_cla') == $torneio['req_id_cla'] ? $reqs : false;
		}
		
		if($torneio['req_portao']) {
			$reqs	= $basePlayer->getAttribute('portao') ? $reqs : false;
		}
		
		if($torneio['req_sennin']) {
			$reqs	= $basePlayer->id_sennin ? $reqs : false;
		}
		
		if($torneio['req_level_ini'] && $torneio['req_level_fim']) {
			$reqs	= between($basePlayer->getAttribute('level'), $torneio['req_level_ini'], $torneio['req_level_fim']) ? $reqs : false;
		}
		
		if($torneio['req_id_torneio']) {
			$torneio_rec = Recordset::query('SELECT * FROM torneio_player WHERE id_player=' . $basePlayer->id . ' AND id_torneio=' . $torneio['req_id_torneio'] . ' AND vitorias > 0')->num_rows;
		
			$t		= Recordset::query('SELECT * FROM torneio WHERE id=' . $torneio['req_id_torneio'])->row_array();
			$reqs	= $torneio_rec ? $reqs : false;
		}
		
		if(!$liberado) {
			$reqs	=  false;
		}

		if(Recordset::query('SELECT id FROM torneio_player_torneio WHERE id_torneio=' . $torneio['id'] . ' AND id_player=' . $basePlayer->id)->num_rows) {
			$reqs	= false;
		}
		
		$date1	= true;
		$date2	= true;
		$date3	= true;

		if($torneio['dt_inicio'] && $torneio['dt_fim']) {
			if(!between(date('His'), date('His', strtotime($torneio['dt_inicio'])), date('His', strtotime($torneio['dt_fim'])))) {
				$date1	= false;
			}
		}

		if($torneio['dt_inicio2'] && $torneio['dt_fim2']) {
			if(!between(date('His'), date('His', strtotime($torneio['dt_inicio2'])), date('His', strtotime($torneio['dt_fim2'])))) {
				$date2	= false;
			}
		}

		if($torneio['dt_inicio3'] && $torneio['dt_fim3']) {
			if(!between(date('His'), date('His', strtotime($torneio['dt_inicio3'])), date('His', strtotime($torneio['dt_fim3'])))) {
				$date3	= false;
			}
		}

		if(!$date1 && !$date2 && !$date3) {
			$reqs	= false;
		}

		if($torneio['dias']) {
			if(!on(date('N'), $torneio['dias'])) {
				$reqs	= false;
			}
		}
	?>
		<div style="position: relative; margin-bottom: 10px;" class="<?php echo $torneio['npc'] ? 'npc' : 'pvp' ?>">
			<table border="0" width="730" onclick="javascript:conquistaDetalhe(<?php echo $torneio['id']?>)" class="conquista-grupo <?php echo $tp && $tp['vitorias'] ? 'conquista-grupo-c' : 'conquista-grupo-i'?>" style="z-index: 5; cursor: pointer">
				<tr>
					<td width="160" align="center"></td>
					<td align="center" class="conquista-grupo-titulo">
						<strong style="font-size: 18px;"><?php echo $torneio['nome_' . Locale::Get()] ?></strong><br /><br />
						<?php if ($torneio['dias']): ?>
							<?php foreach (explode(',', $torneio['dias']) as $day): ?>
								<span style="font-size:12px"><?php echo t('daynames.' . $day) ?> às:</span>
							<?php endforeach ?>
						<?php endif ?>
						<br />
						<div style="width: 300px; position: relative; left: 50px; top: 5px">
						<?php if($torneio['dt_inicio'] && $torneio['dt_fim']): ?>
							<div style="width: 70px; float: left">
							<?php echo t('geral.de')?> <span class="verde"><?php echo date("H:i", strtotime($torneio['dt_inicio'])) ?></span><br />
							<?php echo t('geral.ate')?> <span class="laranja"><?php echo date("H:i", strtotime($torneio['dt_fim'])) ?></span><br />	<br />	
							</div>
						<?php endif;?>

						<?php if($torneio['dt_inicio2'] && $torneio['dt_fim2']): ?>
							<div style="width: 70px; float: left">
							<?php echo t('geral.de')?> <span class="verde"><?php echo date("H:i", strtotime($torneio['dt_inicio2'])) ?></span><br />
							<?php echo t('geral.ate')?> <span class="laranja"><?php echo date("H:i", strtotime($torneio['dt_fim2'])) ?></span><br />	<br />	
							</div>
						<?php endif;?>

						<?php if($torneio['dt_inicio3'] && $torneio['dt_fim3']): ?>
							<div style="width: 70px; float: left">
							<?php echo t('geral.de')?> <span class="verde"><?php echo date("H:i", strtotime($torneio['dt_inicio3'])) ?></span><br />
							<?php echo t('geral.ate')?> <span class="laranja"><?php echo date("H:i", strtotime($torneio['dt_fim3'])) ?></span><br />	<br />	
							</div>
						<?php endif;?>
						</div>
						<div class="tt">
							<?php if($torneio['exp'] || $torneio['ryou']): ?>
							<b style="font-size:12px"><?php echo t('geral.recompensa');?></b><br />
							<ul>
								<?php if($torneio['exp']): ?>
								<li><?php echo $torneio['exp'] ?> <?php echo t('geral.pontos_exp');?></li>
								<?php endif; ?>
								<?php if($torneio['ryou']): ?>
								<li><?php echo $torneio['ryou'] ?> ryous</li>
								<?php endif; ?>
								<?php if($torneio['treino']): ?>
								<li><?php echo $torneio['treino'] ?> <?php echo t('academia_treinamento.at37')?></li>
								<?php endif; ?>
							</ul>
							<?php endif; ?>
						</div></td>
					<td width="200" align="center">
						<?php if(!$is_pid): ?>
							<?php if(!$basePlayer->getAttribute('id_torneio') && $reqs): ?>
								<a class="button b-participate"  onclick="torneioParticipar(<?php echo $torneio['id'] ?>)"><?php echo t('botoes.participar_torneio') ?></a>
							<?php elseif(!$basePlayer->getAttribute('id_torneio') && !$reqs): ?>
								<a class="button ui-state-disabled b-participate"><?php echo t('botoes.participar_torneio') ?></a>
							<?php elseif($basePlayer->getAttribute('id_torneio') && $basePlayer->getAttribute('id_torneio') == $torneio['id']): ?>
							<a class="button b-participate" onclick="torneioDesistir()"><?php echo t('botoes.desistir') ?></a>
							<a class="button b-challenge" onclick="torneioDesafio()"><?php echo t('botoes.procurar_oponente') ?></a>
							<?php endif; ?>
						<?php endif; ?>				
					</td>
				</tr>
			</table>
			<?php
			if(LAYOUT_TEMPLATE=="_azul"){
					$bg_color = $tp && $tp['vitorias'] ? "#0a1219" : "#0a1219";
				}else{
					$bg_color = $tp && $tp['vitorias'] ? "#2b180b" : "#140b08";
				}
			?>
			<div id="d-conquista-grupo-<?php echo $torneio['id']?>" class="d-conquista-grupo" align="center" style="background-color: <?php echo $bg_color?>">
				<div class="borda <?php echo $tp && $tp['vitorias'] ? 'conquista-c' : 'conquista-i'?>">
					<table border="0" width="100%" class="conquista-item" cellpadding="6">
						<tr>
							<th class="desc" align="left"> <strong style="font-size: 14px"></strong> </th>
							<td width="310"></td>
						</tr>
						<tr>
							<td align="left" colspan="2">
								<?php /*
								<p class="win2">Estatísticas</p>
								<ul class="win">
									<li>Campeão: <?php echo $tp && $tp['vitorias'] ? $tp['vitorias'] : 0 ?> vezes</li>
									<li>Derrotado: <?php echo $tp && $tp['derrotas'] ? $tp['derrotas'] : 0 ?> vezes</li>
								</ul>
								*/?>
								<b class="win2 laranja"><?php echo t('geral.requerimentos');?></b><br />
								<ul class="win">
									<?php if($torneio['req_id_graduacao']): ?>
									<?php 
										$style		=  $basePlayer->getAttribute('id_graduacao') == $torneio['req_id_graduacao'] ? 'text-decoration: line-through' : 'color: #F00';
										//$tooltip	.= '<li style="' . $style . '">Ser ' . $grad['nome'] . '</li>'; 
									?>
									<li style=" <?php echo $style; ?> "><?php echo t('requerimentos.ser');?> <?php echo graduation_name($basePlayer->id_vila, $torneio['req_id_graduacao'])?></li>
									<?php endif; ?>
									<?php if($torneio['req_id_vila']): ?>
									<?php
										$style		=  $basePlayer->getAttribute('id_vila') == $torneio['req_id_vila'] ? 'text-decoration: line-through' : 'color: #F00';
										//$tooltip	.= '<li style="' . $style . '">Ser da vila ' . $vila['nome'] . '</li>'; 
									?>
									<li style=" <?php echo $style; ?> "><?php echo t('requerimentos.ser_da_vila');?> <?php echo $vila['nome'] ?></li>
									<?php endif; ?>
									<?php if($torneio['req_vitorias_pvp']): ?>
									<?php
										$style		=  $pvp >= $torneio['req_vitorias_pvp'] ? 'text-decoration: line-through' : 'color: #F00';
										//$tooltip	.= '<li style="' . $style . '">Ter ' . $torneio['req_vitorias_pvp'] . ' Vitórias PVP</li>'; 
									?>
									<li style=" <?php echo $style; ?> "><?php echo t('requerimentos.ter');?> <?php echo $torneio['req_vitorias_pvp'] ?> <?php echo t('requerimentos.vitorias_pvp');?></li>
									<?php endif; ?>
									<?php if($torneio['req_vitorias_npc']): ?>
									<?php
										$style		=  $npc >= $torneio['req_vitorias_npc'] ? 'text-decoration: line-through' : 'color: #F00';
										//$tooltip	.= '<li style="' . $style . '">Ter ' . $torneio['req_vitorias_npc'] . ' Vitórias NPC</li>'; 
									?>
									<li style=" <?php echo $style; ?> "><?php echo t('requerimentos.ter');?> <?php echo $torneio['req_vitorias_npc'] ?> <?php echo t('requerimentos.vitorias_npc');?></li>
									<?php endif; ?>
									<?php if($torneio['req_id_cla']): ?>
									<?php 
										$style		=  $basePlayer->getAttribute('id_cla') == $torneio['req_id_cla'] ? 'text-decoration: line-through' : 'color: #F00';
										//$tooltip	.= '<li style="' . $style . '">Ser do clã ' . $cla['nome'] . '</li>'; 
									?>
									<li style=" <?php echo $style; ?> "><?php echo t('requerimentos.ser_do_cla');?> <?php echo $cla['nome'] ?></li>
									<?php endif; ?>
									<?php if($torneio['req_portao']): ?>
									<?php
										$style		=  $basePlayer->portao ? 'text-decoration: line-through' : 'color: #F00';
										$tooltip	.= '<li style="' . $style . '">Ser usuário dos portões de chakra</li>'; 
									?>
									<li style=" <?php echo $style; ?> "><?php echo t('requerimentos.ser_portoes');?></li>
									<?php endif; ?>
									<?php if($torneio['req_sennin']): ?>
									<?php
										$style		=  $basePlayer->sennin ? 'text-decoration: line-through' : 'color: #F00';
										//$tooltip	.= '<li style="' . $style . '">Ser usuário do modo sennin</li>'; 
									?>
									<li style=" <?php echo $style; ?> "><?php echo t('requerimentos.ser_sennin');?></li>
									<?php endif; ?>
									<?php if($torneio['req_level_ini'] && $torneio['req_level_fim']): ?>
									<?php
										$style		=  between($basePlayer->getAttribute('level'), $torneio['req_level_ini'], $torneio['req_level_fim']) ? 'text-decoration: line-through' : 'color: #F00';
										//$tooltip	.= '<li style="' . $style . '">Ser do nível ' . $torneio['req_level_ini'] . ' até o level ' . $torneio['req_level_fim'] . '</li>'; 
									?>
									<?php /*<li style=" <?php echo $style; ?> ">Ser do nível <?php echo $torneio['req_level_ini'] ?> até o level <?php echo $torneio['req_level_fim'] ?></li> */ ?>
									<li style=" <?php echo $style; ?> "><?php echo sprintf(t('requerimentos.ser_nivel'), $torneio['req_level_ini'], $torneio['req_level_fim']) ?></li>
									<?php endif; ?>
									<?php if($torneio['req_id_torneio']): ?>
									<?php
										$style		=  $torneio_rec ? 'text-decoration: line-through' : 'color: #F00';
										//$tooltip	.= '<li style="' . $style . '">Ter sido campeão do torneio: ' . $t['nome'] . '</li>'; 
									?>
									<li style=" <?php echo $style; ?> "><?php echo t('requerimentos.campeao_torneio');?> <?php echo $t['nome'] ?></li>
									<?php endif; ?>
									<?php if(!$liberado): ?>
									<?php 
										//$tooltip	.= '<li style="color: #F00">Você ja venceu um torneio hoje</li>'; 
									?>
									<li style=" <?php echo $style; ?> "><?php echo t('requerimentos.ja_venceu');?></li>
									<?php endif; ?>
									<?php /*if($torneio['dt_inicio'] && $torneio['dt_fim']): ?>
									<?php
										if(!between(date('YmdHis'), date('YmdHis', strtotime($torneio['dt_inicio'])), date('YmdHis', strtotime($torneio['dt_fim'])))) {
											$reqs	= false;
											$style	= 'style="color: #F00"';
										} else {
											$style	= 'style="text-decoration: line-through"';
										}
										
										//$tooltip	.= '<li ' . $style . '">Este torneio vai de ' . date('d/m/Y H:i:s', strtotime($torneio['dt_inicio'])) . ' até ' .  date('d/m/Y H:i:s', strtotime($torneio['dt_fim'])) . '</li>'; 							
									?>
									<li style=" <?php echo $style; ?> "> <?php echo t('torneios.t1')?> <?php echo date('d/m/Y H:i:s', strtotime($torneio['dt_inicio'])) ?> <?php echo t('torneios.t2')?> <?php echo date('d/m/Y H:i:s', strtotime($torneio['dt_fim'])) ?> </li>
									<?php endif; */?>
								</ul>
								<br />
								<b class="win2 verde">Status</b><br />
								<ul class="win">
                                	<li><?php echo sprintf(t('requerimentos.campeao_com'), $torneio['chaves']); ?></li>
									<li><?php echo sprintf(t('requerimentos.atualmente_com'), $tp && $tp['chave'] ? $tp['chave'] - 1 : 0); ?></li>
								</ul>
								<br />
								<?php if($torneio['exp'] || $torneio['ryou']): ?>
									<b style="font-size:12px" class="verde"><?php echo t('geral.recompensa');?></b><br />
									<ul>
										<?php if($torneio['exp']): ?>
										<li><?php echo $torneio['exp'] ?> <?php echo t('geral.pontos_exp');?></li>
										<?php endif; ?>
										<?php if($torneio['ryou']): ?>
										<li><?php echo $torneio['ryou'] ?> ryous</li>
										<?php endif; ?>
										<?php if($torneio['treino']): ?>
										<li><?php echo $torneio['treino'] ?> <?php echo t('academia_treinamento.at37')?></li>
										<?php endif; ?>
									</ul>
								<?php endif; ?>
								</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</form>
<script>
	if(!$.cookie('_torneio-tab')) {
		$.cookie('_torneio-tab', 1);
		
		doTorneioTab($('#torneio-tab-<?php echo $torneio_atual != false && $torneio_atual['npc'] ? 1 : 2 ?>'), <?php echo isset($torneio_atual['npc']) && $torneio_atual['npc'] ? 1 : 2 ?>);
	} else {
		doTorneioTab($('#torneio-tab-' + $.cookie('_torneio-tab')), $.cookie('_torneio-tab'));
	}
</script>
