<?php if(!$basePlayer->tutorial()->golpes){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 16,
	 
	  steps: [
	  {
		element: ".msg_gai",
		title: "<?php echo t("tutorial.titulos.jutsus.4");?>",
		content: "<?php echo t("tutorial.mensagens.jutsus.4");?>",
		placement: "top"
	  },{
		element: ".msg_gai",
		title: "<?php echo t("tutorial.titulos.jutsus.5");?>",
		content: "<?php echo t("tutorial.mensagens.jutsus.5");?>",
		placement: "bottom"
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
	$jut_field_postkey			= $_SESSION['jut_field_postkey'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));
	$jut_field_postkey_value	= $_SESSION['jut_field_postkey_value'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));
	$items						= $basePlayer->getItems();
	$tabs 						= Recordset::query("SELECT * FROM habilidade WHERE id IN(1,2,3,4)", true);
	$enhance_count				= Player::getFlag('ponto_aprimoramento', $basePlayer->id);
	$cron						= cron_next_run(0, '*/2');

	$tab_medicinal		= [];
	$tab_kinjutsu		= [];
	$tab_normal			= [];
	$tab_sem_turno_tai	= '';
	$tab_sem_turno_nin	= '';
	$tab_sem_turno_gen	= '';
	$tab_sem_turno_buk	= '';
	
	$timer	= '<br /><br />';
	
	if($enhance_count) {
		$timer		.= '<span class="verde">' . sprintf(t('enhance_tooltip.can_enhance'), $enhance_count) . '</span>';
	} else {
		$timer		.= '<span class="laranja">' . t('enhance_tooltip.cant_enhance') . '&nbsp;&nbsp;&nbsp;<span id="d-enchance-count-timer">--:--:--</span></span>';
	}
?>
<style>
	.parchment-canvas {
		width: 166px;
		height: 60px;
		position: relative;
		background-image: url(<?php echo img('layout/Pergaminho.png') ?>)
	}

	.parchment-canvas .parchment-container {
		margin-left: 19px;
		padding-top: 11px;
	}
	
	.parchment-canvas .parchment-container .option {
		width: 38px;
		height: 38px;
		float: left;
		margin-left: 3px;
		cursor: pointer;
		text-align: left
	}
	
	.slot-item-container .slot-item {
		float: left;
		margin: 4px;
		position: relative;
	}

	.slot-item-container .slot-item span {
		position: absolute;
		bottom: 0px;
		right: 0px;
		font-size: 10px;
		padding: 4px;
		background-color: #222
	}
</style>
<script type="text/javascript" src="js/academia_treinamento.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$('.chk-jutsu-ativo').on('click', function () {
			var	active	= this.checked ? 1 : 0;
			
			$.ajax({
				url:	'?acao=personagem_jutsu_ativo',
				type:	'post',
				data:	{item: $(this).data('id'), active: active}
			});
		});
		
		$('.parchment-canvas .parchment-container .option').on('click', function () {
			var _		= $(this);
			var slot	= _.data('slot');
			
			if(!parseInt(_.data('filled-before')) && slot != 1) {
				return;					
			}
			
			var	d	= $(document.createElement('DIV')).addClass('slot-item-container');
			
			$(document.body).append(d);
			
			d.html('Aguarde...').dialog({
				title:	'Aprimorar técnica',
				width: 600,
				height: 400,
				modal: true
			});
			
			$.ajax({
				url:		'?acao=personagem_jutsu',
				data:		{list: 1, slot: _.data('slot'), target: _.data('target')},
				dataType:	'json',
				type:		'post',
				success:	function (result) {
					if(result.items && result.items.length) {
						var	equippable_box		= $(document.createElement('DIV')).html('<b class="laranja"></b><div class="break"></div>');

						var	bis_box	= $(document.createElement('DIV')).html('<br /><br /><b class="laranja"></b><div class="clear"></div>');		d.html('').append(equippable_box, '<div class="break"></div>', bis_box);
					
						for(var i in result.items) {
							var it	= result.items[i];
							var box	= $(document.createElement('DIV')).addClass('slot-item');
							
							box.data('cost', it.cost);
							box.data('id', it.id);

							box.append($(document.createElement('IMG')).attr('src', '<?php echo img() ?>/layout/' + it.image).attr('id', it.objekt).attr('data-tooltip-float', 1));
							box.append(it.tooltip).attr('data-item', it.id);

							if (!it.equippable) {
								box.css('opacity', .4).data('unequippable', 1);
							}
							
							if(!it.bis) {
								equippable_box.append(box);
							} else {
								equippable_box.append(box);
							}
						}
						
						d.on('click', '.slot-item', function () {
							var	_this	= $(this);
							
							var	modal	= $(document.createElement('DIV'));
							$(document.body).append(modal);
							
							var	buttons	= { };

							if(!parseInt(_this.data('unequippable'))) {
								buttons['Equipar']	= function () {
									lock_screen(true);
									
									$.ajax({
										url:		'?acao=personagem_jutsu',
										data:		{equip: 1, slot: _.data('slot'), uid: _this.data('item'), target: _.data('target')},
										dataType:	'json',
										type:		'post',
										success:	function (result) {
											lock_screen(false);
											
											if(!result.messages.length) {
												location.reload();
											} else {
												var	messages	= [];
												
												result.messages.forEach(function (message) {
													messages.push('<li>' + message + '</li>');
												});
											
												jalert('Ops, você não pode efetuar essa ação pelos seguintes motivos:<br /><br /><ul>' + (messages.join('')) + '</ul>');
											}
										}
									});
								};

								var	msg	= 'Depois de equipado, você não poderá remover esse aprimoramento, só poderá trocar por outro.';
							} else {
								var	msg	= 'Você não tem ponto(s) de aprimoramento suficientes para essa ação';
							}

							buttons['Fechar']	= function () {
								modal.remove();
							}

							modal.html(msg).dialog({
								title:	'Aprimoramento',
								width:		400,
								height:		180,
								modal:		true,
								buttons:	buttons
							})						
						});
						
						updateTooltips();
					} else {
						d.html("<?php echo addslashes(nl2nothing(t('treino_jutsu.no_enhance_available'))) ?>");
					}
				}
			});
		});
	});
</script>
<div class="titulo-secao"><p><?php echo t('titulos.treinamento_jutsus')?></p></div>
<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
		<p>
			<b><?php echo t('personagem_jutsu.msg_funciona_t') ?></b><br /><br />
			<span class="cinza" style="font-size:13px"><?php echo /*t('personagem_jutsu.msg_funciona_desc')*/ $timer ?></span><br /><br />
			<?php /*<div style="width:430px; font-size:13px; font-weight: bold" class="cinza npc-points-data">
				<span class="azul"><?php echo t('votacao.v19') ?>:</span>
				<?php echo t('votacao.v22') ?>: <span class="verde avail"> <?php echo ($basePlayer->ponto_batalha - $basePlayer->ponto_batalha_gasto) ?> </span> <span class="cinza">/</span>
				<?php echo t('votacao.v21') ?>: <span class="laranja spent"><?php echo $basePlayer->ponto_batalha_gasto ?></span> <span class="cinza">/</span>
				<?php echo t('votacao.v23') ?>: <span class="azul total"><?php echo $basePlayer->ponto_batalha ?> </span>
			</div>*/?>
		</p>
	  </div>		
	</div>
</div>	
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "8043766177";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Treinamento Jutsu -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/><br/>
<div id="cnBase" class="direita">
<?php 
	if(isset($_GET['j']) && isset($_GET['l']) && $_GET['j'] && $_GET['l']) {
		$jutsu = Recordset::query("SELECT nome_" .Locale::get() . " AS nome FROM item WHERE id=" . (int)$_GET['j'], true)->row_array();
		$jutsu = $jutsu['nome'];
		
		msg('1',''.t('academia_jutsu.parabens').'', t('templates.t66').' <b>'.$jutsu.' Level ' . $_GET['l'] . '</b><br />'.t('templates.t67').'.');
	}
?>
<br />
<div class="with-n-tabs" data-auto-default="1" id="tabs-treino-jutsu">
	<?php foreach($tabs->result_array() as $tab): ?>
		<a rel="#tr-habil-<?php echo $tab['id'] ?>" id="tab-button-<?php echo $tab['id'] ?>" class="button"><?php echo $tab['nome'] ?></a>
	<?php endforeach ?>
	<a rel="#tr-habil-sem-turno" class="button" id="tab-button-buff"><?php echo t('personagem_jutsu.buffs') ?></a>
	<a rel="#tr-habil-medicinal" class="button" id="tab-button-med"><?php echo t('equipe_especializacao.ee5') ?></a>
	<a rel="#tr-habil-kinjutsu" class="button" id="tab-button-kin">Kinjutsus</a>
</div><br /><br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td height="49" class="subtitulo-home">
		<table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
		<tr>
			<td width="30"></td>
			<td align="center"><?php echo t('personagem_jutsu.jutsu_desc')?></td>
		</tr>
	    </table>
	</td>
</tr>
</table>
<?php foreach($tabs->result_array() as $tab): ?>
	<?php
		$cp	= 0;
		$cn = 0;
	?>
	<?php foreach($items as $item): ?>
		<?php
			ob_start();
		
			$item	= new Item($item->id, $basePlayer->id);
			$item->setPlayerInstance($basePlayer);
			$item->apply_enhancemnets();
			
			if($item->id_habilidade != $tab['id'] || !in_array($item->getAttribute('id_tipo'), array(5, 24, 37))) {
				continue;	
			}
			
			$cor			= ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
			$sel_agi_req	= '';
			
			// Selo e Agilidade Requeridos
			if(!$item->sem_turno) {
				 if($item->req_con > 0 ){
					// regra para cobrar mais precisão se quiser usar golpes maiores que seu level
					$inc_req_con 	= $item->req_level > $basePlayer->getAttribute('level') ? $item->req_level - $basePlayer->getAttribute('level') : 0;
			
				 	$con			= $basePlayer->getAttribute('con_calc') > $item->req_con + $inc_req_con ? $item->req_con : $basePlayer->getAttribute('prec_magico_calc');				 	
					$sel_agi_req	.= '<div style="float: left">'.t('arena.selo_req').': </div><div style="float: right">' . barra_exp3($basePlayer->getAttribute('prec_magico_calc'), ($item->req_con+$inc_req_con), 132, $con . ' de '. ($item->req_con+$inc_req_con), "#2C531D", "#537F3D", 2, "", true) .
								'</div><div style="clear: both"></div>';
				 }
				 
				 if($item->req_agi > 0){
					// regra para cobrar mais precisão se quiser usar golpes maiores que seu level
					$inc_req_agi 	= $item->req_level > $basePlayer->getAttribute('level') ? $item->req_level - $basePlayer->getAttribute('level') : 0;
				 	$agi			= $basePlayer->getAttribute('con_calc') > $item->req_agi+$inc_req_agi ? $item->req_agi : $basePlayer->getAttribute('prec_magico_calc');
	
					$sel_agi_req	.= '<div style="float: left">'.t('arena.selo_req').': </div><div style="float: right">' . barra_exp3($basePlayer->getAttribute('prec_magico_calc'), ($item->req_agi+$inc_req_agi), 132, $agi . ' de '. ($item->req_agi+$inc_req_agi), "#2C531D", "#537F3D", 2, "", true) .
								'</div><div style="clear: both"></div>';
					 

				 }				 
			}
	
			if($item->sem_turno) {
				$precisao	= '';
			} else {
				$precisao	= '<div style="float: left">'.t('arena.chance_acerto').': </div><div style="float: right">' . barra_exp3($item->getAttribute('precisao'), 100, 132, $item->getAttribute('precisao') . '%', "#2C531D", "#537F3D", 2, "", true) .
							'</div><div style="clear: both"></div>';

				 if($item->getAttribute('precisao') >= 100) {
				 	$req 			= $item->req_agi > 0 ? $item->req_agi : $item->req_con;
					$sel_agi_req	.= '<div style="float: left">Chance Crít. Adicional: </div><div style="float: right">' . barra_exp3($item->crit_inc_raw, $req, 132, $item->crit_inc_raw . ' de '. $req. ' (' . $item->crit_inc . '%)', "#2C531D", "#537F3D", 2, "", true) .
								'</div><div style="clear: both"></div>';					 
				 }
			}
			// <---
			
			$slot_data	= $item->aprimoramento;
		?>
		<tr <?php echo $cor ?>>
			<td width="30" align="right">
				<input data-id="<?php echo $item->id ?>" type="checkbox" class="chk-jutsu-ativo" <?php echo ($item->dojo_ativo ? 'checked="checked"' : '') ?> />
			</td>
			<td width="70" align="center">
				<div class="img-lateral-dojo2">
					<img id="i-item-<?php echo $item->id ?>" src="<?php echo img("layout/".$item->getAttribute('imagem')) ?>" width="53" height="53"  style="margin-top:5px" />
					<?php echo bonus_tooltip('i-item-' . $item->id, $item, NULL, $sel_agi_req . '' . $precisao); ?>
				</div>
			</td>
			<td align="left">
				<b class="amarelo"><?php echo $item->getAttribute('nome') ?></b><br /><br />
				<?php echo $item->getAttribute('descricao_' . Locale::get())?>
			</td>
			<?php if($item->id_tipo == 5): ?>
				<td width="250" align="center">
					<div class="parchment-canvas">
						<div id="parchment-<?php echo $item->id ?>" class="parchment-container">
							<?php for($f = 0; $f <= 2; $f++): ?>
							<div class="option" data-slot="<?php echo $f+1 ?>" data-target="<?php echo $item->uid ?>" data-filled-before="<?php echo $f == 0 ? 1 : isset($slot_data[$f]) ?>">
								<?php if(!isset($slot_data[$f + 1])): ?>
									<img src="<?php echo img('layout/sem_aprimoramento.png') ?>" width="38" />
								<?php else: ?>
									<?php
										$enhancer	= Recordset::query('SELECT tempo_espera, imagem FROM item WHERE id=' . $slot_data[$f + 1], true)->row_array();
										$bis		= $enhancer['tempo_espera'] == ($f+1);
										$id			= 'a' . uniqid();
									?>
									<img style="position: absolute" src="<?php echo img('layout/' . $enhancer['imagem']) ?>" width="38" id="<?php echo $id ?>" data-current="<?php echo $slot_data[$f + 1] ?>" />
									<?php echo enhance_tooltip($id, $slot_data[$f + 1], $bis) ?>
								<?php endif ?>
							</div>
							<?php endfor ?>
						</div>
					</div>
				</td>
			<?php else: ?>
				<td width="250"></td>			
			<?php endif ?>
		</tr>
		<tr height="4"></tr>
		<?php
			if($item->id_tipo == 24) {
				if(!isset($tab_medicinal[$item->req_graduacao])) {
					$tab_medicinal[$item->req_graduacao]	= '';
				}

				$tab_medicinal[$item->req_graduacao]	.= ob_get_clean();
			} elseif($item->id_tipo == 37) {
				if(!isset($tab_kinjutsu[$item->req_graduacao])) {
					$tab_kinjutsu[$item->req_graduacao]	= '';
				}

				$tab_kinjutsu[$item->req_graduacao]		.= ob_get_clean();
			} elseif($item->sem_turno) {
				switch($item->id_habilidade) {
					case 1: $tab_sem_turno_tai .= ob_get_clean(); break;
					case 2: $tab_sem_turno_nin .= ob_get_clean(); break;
					case 3: $tab_sem_turno_gen .= ob_get_clean(); break;
					case 4: $tab_sem_turno_buk .= ob_get_clean(); break;
				}
			} else {
				if(!isset($tab_normal[$item->id_habilidade])) {
					$tab_normal[$item->id_habilidade]	= [];
				}

				if(!isset($tab_normal[$item->id_habilidade][$item->req_graduacao])) {
					$tab_normal[$item->id_habilidade][$item->req_graduacao]	= '';
				}

				$tab_normal[$item->id_habilidade][$item->req_graduacao]	.= ob_get_clean();
			}
		?>
	<?php endforeach ?>
<?php endforeach ?>
<?php if ($tab_normal): ?>
	<?php foreach($tab_normal as $ability => $graduations): ?>
		<table border="0" id="tr-habil-<?php echo $ability ?>" cellpadding="2" cellspacing="0" width="730">
			<?php ksort($graduations) ?>
			<?php foreach($graduations as $graduation => $data): ?>
				<tr><td colspan="6"><div class="titulo-home" style="white-space: nowrap"><p><span class="laranja">//</span><?php echo graduation_name($basePlayer->id_vila, $graduation) ?>................................................................</p></div></td></tr>
				<?php echo $data ?>
			<?php endforeach ?>
		</table>
	<?php endforeach ?>
<?php endif ?>
<?php foreach($tabs->result_array() as $tab): ?>
	<script type="text/javascript">
		if (!$('#tr-habil-<?php echo $tab['id'] ?> tr').length) {
			$('#tab-button-<?php echo $tab['id'] ?>').remove();
		}
	</script>
<?php endforeach ?>
<?php if($tab_medicinal): ?>
	<table border="0" id="tr-habil-medicinal" cellpadding="2" cellspacing="0" width="730">
		<?php foreach($tab_medicinal as $graduation => $data): ?>
			<tr><td colspan="6"><div class="titulo-home" style="white-space: nowrap"><p><span class="laranja">//</span><?php echo graduation_name($basePlayer->id_vila, $graduation) ?>................................................................</p></div></td></tr>
			<?php echo $data ?>
		<?php endforeach ?>
	</table>
<?php else: ?>
	<script type="text/javascript">$('#tab-button-med').remove()</script>
<?php endif ?>
<?php if($tab_kinjutsu): ?>
	<table border="0" id="tr-habil-kinjutsu" cellpadding="2" cellspacing="0" width="730">
		<?php foreach($tab_kinjutsu as $graduation => $data): ?>
			<tr><td colspan="6"><div class="titulo-home" style="white-space: nowrap"><p><span class="laranja">//</span><?php echo graduation_name($basePlayer->id_vila, $graduation) ?>................................................................</p></div></td></tr>
			<?php echo $data ?>
		<?php endforeach ?>
	</table>
<?php else: ?>
	<script type="text/javascript">$('#tab-button-kin').remove()</script>
<?php endif ?>
<?php if($tab_sem_turno_tai || $tab_sem_turno_nin || $tab_sem_turno_gen || $tab_sem_turno_buk): ?>
	<table border="0" id="tr-habil-sem-turno" cellpadding="2" cellspacing="0" width="730">
		<?php if($tab_sem_turno_tai): ?>
			<tr><td colspan="6"><div class="titulo-home"><p><span class="laranja">//</span>TAIJUTSU..........................................................................................</p></div></td></tr>
			<?php echo $tab_sem_turno_tai ?>
		<?php endif ?>

		<?php if($tab_sem_turno_nin): ?>
			<tr><td colspan="6"><div class="titulo-home"><p><span class="laranja">//</span>NINJUTSU..........................................................................................</p></div></td></tr>
			<?php echo $tab_sem_turno_nin ?>
		<?php endif ?>

		<?php if($tab_sem_turno_gen): ?>
			<tr><td colspan="6"><div class="titulo-home"><p><span class="laranja">//</span>GENJUTSU..........................................................................................</p></div></td></tr>
			<?php echo $tab_sem_turno_gen ?>
		<?php endif ?>

		<?php if($tab_sem_turno_buk): ?>
			<tr><td colspan="6"><div class="titulo-home"><p><span class="laranja">//</span>BUKIJUTSU..........................................................................................</p></div></td></tr>
			<?php echo $tab_sem_turno_buk ?>
		<?php endif ?>
	</table>
<?php else: ?>
	<script type="text/javascript">$('#tab-button-buff').remove()</script>
<?php endif ?>
<form name="fTreino" action="?acao=academia_treinamento_jutsu" method="post">
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="f" value="<?php echo isset($_GET['f']) ? $_GET['f'] : '' ?>" />
	<input type="hidden" name="bf" value="<?php echo isset($_GET['bf']) ? $_GET['bf'] : '' ?>" />
	<input type="hidden" name="p" value="<?php echo encode($basePlayer->id) ?>" />
</form>
<?php if($enhance_count < 5): ?>
<script type="text/javascript">
	cronTimer(<?php echo $cron['h'] ?>, <?php echo $cron['m'] ?>, <?php echo $cron['s'] ?>, 0, 2, 'd-enchance-count-timer');
	$('#d-enchance-count-container').show();
</script>
<?php endif ?>
