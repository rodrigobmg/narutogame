<?php if(!$basePlayer->tutorial()->vip){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 29,
	 
	  steps: [
	  {
		element: "#tutorial-1024",
		title: "<?php echo t("tutorial.titulos.vip.1");?>",
		content: "<?php echo t("tutorial.mensagens.vip.1");?>",
		placement: "top"
	  },{
		element: "#tutorial-1495",
		title: "<?php echo t("tutorial.titulos.vip.2");?>",
		content: "<?php echo t("tutorial.mensagens.vip.2");?>",
		placement: "top"
	  },{
		element: "#tutorial-20318",
		title: "<?php echo t("tutorial.titulos.vip.3");?>",
		content: "<?php echo t("tutorial.mensagens.vip.3");?>",
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
<?php
	$vip_field_postkey			= $_SESSION['vip_field_postkey'] 		= "f" . uniqid();
	$vip_field_postkey_value	= $_SESSION['vip_field_postkey_value']	= "f" . uniqid();
	
	$vip_form_id				= $_SESSION['vip_form_id']		= "f" . uniqid();
	$vip_js_function			= $_SESSION['vip_js_function']	= "f" . uniqid();
	$vip_js_functionb			= $_SESSION['vip_js_functionb']	= "f" . uniqid();
	$vip_js_functionm			= $_SESSION['vip_js_functionm']	= "f" . uniqid();

	$vip_field_id				= $_SESSION['vip_field_id']				= "f" . uniqid();
	$vip_field_cnome			= $_SESSION['vip_field_cnome']			= "f" . uniqid();
	$vip_field_cvila			= $_SESSION['vip_field_cvila']			= "f" . uniqid();
	$vip_field_cclasse			= $_SESSION['vip_field_cclasse']		= "f" . uniqid();
	$vip_field_cclasse_m		= $_SESSION['vip_field_cclasse_m']		= "f" . uniqid();
	$vip_field_cclasse_tipo		= $_SESSION['vip_field_cclasse_tipo']	= "f" . uniqid();

	$has_el						= ($basePlayer->id_classe_tipo != 1 || $basePlayer->id_classe_tipo != 4) && sizeof($basePlayer->getElementos());

	$first_free	= [1024,1797,20205,1025,2018,1747];
	$zero_flags	= @unserialize(Player::getFlag('zero_coin_ids', $basePlayer->id));
	
	if(!$zero_flags) {
		$zero_flags = array();
	}
	
	$cc		= 0;
	$equipe = Recordset::query("SELECT * FROM equipe WHERE id=" . (int)$basePlayer->id_equipe)->row_array();
	$items	= Recordset::query('
		SELECT 
			*
		
		FROM 
			item 
		
		WHERE
			id_tipo IN(18,19) AND
			req_graduacao=1 AND
			removido="0"
		
		ORDER BY
			ordem ASC, ordem2 ASC
	');
?>
<div class="titulo-secao"><p><?php echo t('jogador_vip.jv6')?></p></div>

<script type="text/javascript">
	function <?php echo $vip_js_function ?>(id, o, x) {
		if(id == "<?php echo encode(1025) ?>") {
			if(o && x) {
				alert("<?php echo t('jogador_vip.jv1')?>");
				return;
			}

			<?php if(isset($equipe['membros']) && $equipe['membros'] > 1): ?>
			if(o && !x) {
				alert("<?php echo t('jogador_vip.jv2')?>");
				return;
			}
			<?php endif; ?>

			if(x && !o) {
				alert("<?php echo t('jogador_vip.jv3')?>");
				return;
			}		
		} else if(id == "<?php echo encode(2018) ?>" || id == "<?php echo encode(1747) ?>") {
			if(!confirm("<?php echo t('jogador_vip.jv4')?>")) {
				return false;
			}
		}
		
		if(id == "<?php echo encode(1024) ?>") {
			if(!$('#e<?php echo $vip_field_cnome ?>').val().match(/^[\w]+$/i)) {
				alert('<?php echo t('jogador_vip.jv5')?>');
				return;
			}
		}
		
		jconfirm('Você está prestes a gastar créditos VIP, continuar?', null, function () {
			$("#<?php echo $vip_field_id ?>").val(id);
			$("#<?php echo $vip_field_cnome ?>").val($('#e<?php echo $vip_field_cnome ?>').val());
			$("#<?php echo $vip_field_cvila ?>").val($('#e<?php echo $vip_field_cvila ?>').val());
			$("#<?php echo $vip_field_cclasse ?>").val($('#e<?php echo $vip_field_cclasse ?>').val());
			$("#<?php echo $vip_field_cclasse_tipo ?>").val($('#e<?php echo $vip_field_cclasse_tipo ?>').val());
			
			$("#<?php echo $vip_form_id ?>").submit();			
		});
	}

	function <?php echo $vip_js_functionb ?>(id, o) {
		$("#<?php echo $vip_field_id ?>").val(id);
		
		$("#<?php echo $vip_field_cclasse_m ?>").val($('#e<?php echo $vip_field_cclasse_m ?>').val());
		$("#<?php echo $vip_form_id ?>").attr("action", "?acao=jogador_vip_uso").submit();
	}
</script>
<form name="<?php echo $vip_form_id ?>" id="<?php echo $vip_form_id ?>" method="post" action="?acao=jogador_vip_compra">
	<input type="hidden" name="<?php echo $vip_field_id ?>" id="<?php echo $vip_field_id ?>" />
	<input type="hidden" name="<?php echo $vip_field_cnome ?>" id="<?php echo $vip_field_cnome ?>" />
	<input type="hidden" name="<?php echo $vip_field_cvila ?>" id="<?php echo $vip_field_cvila ?>" />
	<input type="hidden" name="<?php echo $vip_field_cclasse ?>" id="<?php echo $vip_field_cclasse ?>" />
	<input type="hidden" name="<?php echo $vip_field_cclasse_m ?>" id="<?php echo $vip_field_cclasse_m ?>" />
	<input type="hidden" name="<?php echo $vip_field_cclasse_tipo ?>" id="<?php echo $vip_field_cclasse_tipo ?>" />
    <input type="hidden" name="<?php echo $vip_field_postkey ?>" id="<?php echo $vip_field_postkey ?>" value="<?php echo $vip_field_postkey_value ?>" />
</form>
<?php
	if(isset($_GET['ok']) && $_GET['ok']) {
		$item = Recordset::query("SELECT nome_" . Locale::get() . " FROM item WHERE id=" . (int)decode($_GET['ok']))->row()->{'nome_' . Locale::get()};
		
		msg('6',''.t('jogador_vip.jv7').'', ''.t('jogador_vip.jv8').' <span class="verde">'. $item .'</span>');

	}

	if(isset($_GET['active']) && $_GET['active'] && $basePlayer->hasItemW(decode($_GET['active']))) {
		$item = $basePlayer->getItem(decode($_GET['active']));

		msg('2',''.t('jogador_vip.jv9').'', ''.t('jogador_vip.jv10').' <span class="verde">' . $item->nome . '</span>');
	}
	
	$msg = '';
	if(isset($_GET['e'])) {
		switch($_GET['e']) {
			case 1:
				$msg = t('jogador_vip.jv11');
			
				break;
	
			case 2:
				$msg = t('jogador_vip.jv12');
			
				break;
			
			case 3:
				$msg = t('jogador_vip.jv13');
			
				break;
				
			case 4:
				$msg = t('jogador_vip.jv14');
			
				break;
			
			case 5:
				$msg = t('jogador_vip.jv15');
			
				break;
	
			case 6:
				$msg = t('jogador_vip.jv16');
			
				break;
			case 7:
				$msg = t('jogador_vip.jv17');
				
				break;
			
			case 8:
				$msg = t('jogador_vip.jv18');
				
				break;

			case 9:
				$msg = t('jogador_vip.jv37'); // Nome > 14 && < 3
				
				break;
				
			case 10:
				$msg = ('Seu nível não é compativel com o requerimento minimo ou máximo para este item!');
				
				break;

			case 11:
				$msg = ('Você não pode trocar de vila se você for um dos conselheiros da vila!');
				
				break;
			case 12:
				$msg = ('Somente o Kage da vila pode usar essa vantagem vip');
				break;
				
			case 98:
				$msg = ('Você não pode mudar para uma vila neutra');
				break;
			case 99:
				$msg = ('Os Ranges só podem ser ativos a partir do 7 dia de round.');	
				break;
		}
	}
	
	if($msg) {
		msg('4',''.t('academia_treinamento.at15').'', ''.$msg.'');		
	}
?>    
	
<?php msg('4',''.t('jogador_vip.jv6').'', ''.t('jogador_vip.jv19').'<br /><br />'.t('jogador_vip.jv20').' <span class="verde">'. $basePlayer->getAttribute("coin") .' '.t('jogador_vip.jv22').'</span> '.t('jogador_vip.jv21').':<br />');?>      
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="49" class="subtitulo-home">
			<table width="730" border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td width="75">&nbsp;</td>
			<td width="417" align="left"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b><b style="color:#FFFFFF"> / <?php echo t('geral.descricao')?></b></td>
			<td width="132"><b style="color:#FFFFFF"><?php echo t('geral.creditos')?></b></td>
			<td width="90">&nbsp;</td>
			</tr>
			</table>
		</td>
	</tr>
</table>

<table width="730" border="0" cellpadding="4" cellspacing="0">
<?php foreach($items->result_array() as $item): ?>
	<?php
		$items_to_disable	= array($item['id']);
		$validade			= array($item['id']);
		$validity_extra		= NULL;
		$color				= $cc++ % 2 ? "class='cor_sim'" : "class='cor_nao'";
		
		if($item['req_item']) {
			$items_to_disable	= array_merge($items_to_disable, explode(',', $item['req_item']));
			$$validity_extra	= explode(',', $item['req_item']);
		}
		
		$validade	= item_validade($validade, $validity_extra);
	?>
	<tr <?php echo $color ?> id="<?php echo "tutorial-".$item['id'] ?>">
		<td width="75" height="3" align="center" class="bg_td_redondo_left" >
			<div class="img-lateral-dojo2"><img src="<?php echo img('layout/icon_vip/' . $item['id'] . '.jpg') ?>" width="53" height="53"  style="margin-top:5px"/></div>
		</td>
		<td width="447" align="left">
			<b class="amarelo" style="font-size:13px"><?php echo $item['nome_' . Locale::get()] ?></b><br />
			<?php echo $item['descricao_' . Locale::get()] ?>
			<?php if($item['id'] == 1024): ?>
				<input name="e<?php echo $vip_field_cnome ?>" type="text" id="e<?php echo $vip_field_cnome ?>" maxlength="14" />
			<?php elseif($item['id'] == 1025): ?>
				<select name="e<?php echo $vip_field_cvila ?>" id="e<?php echo $vip_field_cvila ?>">
				<?php
					$vilas = Recordset::query("SELECT * FROM vila WHERE inicial='1'", true);
					
					foreach($vilas->result_array() as $vila) {
						echo "<option value='" . encode($vila['id']) . "'>" . $vila['nome_' . Locale::get()] . "</option>";	
					}
				?>
				</select>
			<?php elseif($item['id'] == 20205): ?>
				<select name="e<?php echo $vip_field_cclasse_tipo ?>" id="e<?php echo $vip_field_cclasse_tipo ?>">
					<?php $qClasses = new Recordset('SELECT * FROM classe_tipo WHERE id !=' . $basePlayer->id_classe_tipo); ?>
					<?php foreach($qClasses->result_array() as $classe): ?>
						<?php if($has_el && ( $classe['id'] == 1 || $classe['id'] == 4)) continue; ?>
						<option value="<?php echo encode($classe['id']) ?>"><?php echo $classe['nome'] ?></option>
					<?php endforeach; ?>
				</select>

				<?php if($has_el): ?>
					<br />
					<br />
					<b><?php echo t('jogador_vip.jv38') ?></b>
				<?php endif ?>
			<?php elseif($item['id'] == 1797): ?>
				<select name="e<?php echo $vip_field_cclasse ?>" id="e<?php echo $vip_field_cclasse ?>">
					<?php $qClasses = new Recordset('SELECT * FROM classe WHERE id !=' . $basePlayer->id_classe); ?>
					<?php foreach($qClasses->result_array() as $classe): ?>
						<option value="<?php echo encode($classe['id']) ?>"><?php echo $classe['nome'] ?></option>
					<?php endforeach; ?>					
				</select>
			<?php elseif($item['id'] == 22953 && $basePlayer->hasItem(array(22953))): ?>
				<?php $qClasses_m = Recordset::query('SELECT pc.id_classe, c.nome FROM player_classe as pc JOIN classe as c ON pc.id_classe = c.id WHERE pc.id_classe !=' . $basePlayer->id_classe .' AND pc.id_player = '. $basePlayer->id); ?>
				<?php if($qClasses_m->num_rows){ ?>
				<select name="e<?php echo $vip_field_cclasse_m ?>" id="e<?php echo $vip_field_cclasse_m ?>">
					<?php foreach($qClasses_m->result_array() as $classe_m): ?>
						<option value="<?php echo encode($classe_m['id_classe']) ?>"><?php echo $classe_m['nome'] ?></option>
					<?php endforeach; ?>					
				</select>
				<?php }?>
			<?php endif ?>
		</td>
		<td width="132">
			<?php if (in_array($item['id'], $first_free) && !in_array($item['id'], $zero_flags)): ?>
				<span class="verde"><?php echo t('jogador_vip.first_free') ?></span>
			<?php else: ?>
				<?php if($item['coin'] > 0):?>
					<?php echo $item['coin'] ?> <?php echo t('jogador_vip.jv22')?>				
				<?php else:?>
					<span class="verde">Gratuito</span>
				<?php endif;?>	
			<?php endif ?>
		</td>
		<td width="90" align="center" class="bg_td_redondo_right">
			<?php if($basePlayer->hasItem($items_to_disable) && !$item['req_level']): ?>
					<a class="button ui-state-disabled"><?php echo t('botoes.comprar')?></a>
				<?php if($item['cooldown'] && $basePlayer->hasItem($item['id'])): ?>
					<br /><br />
					<?php if(!gHasItemW($item['id'], $basePlayer->id, NULL, $item['cooldown'] * 24)): ?>
						<?php if($item['id']==22953){ ?>
							<?php if($qClasses_m->num_rows){?>
								<a href="javascript:<?php echo $vip_js_functionb ?>('<?php echo encode($item['id']) ?>')" class="button"><?php echo $item['id'] == 22953 ? t('botoes.trocar') : t('botoes.ativar')?></a>
							<?php }?>	
						<?php }else{ ?>
								<a href="javascript:<?php echo $vip_js_functionb ?>('<?php echo encode($item['id']) ?>')" class="button"><?php echo $item['id'] == 22953 ? t('botoes.trocar') : t('botoes.ativar')?></a>
						<?php }?>	
					<?php else: ?>
						<a class="button ui-state-disabled"><?php echo $item['id'] == 22953 ? t('botoes.trocar') : t('botoes.ativar')?></a>
						<div>
							<?php
								$ult_uso	= Recordset::query('SELECT data_uso FROM player_item WHERE id_player=' . $basePlayer->id . ' AND id_item=' . $item['id'])->row()->data_uso;
								$diff		= get_time_diff(date('Y-m-d H:i:s', strtotime('+' . ($item['cooldown'] * 24) . ' hour', strtotime($ult_uso))));
								
								$uso = array(
									'd'	=> floor($diff['h'] / 24),
									'h'	=> $diff['h'] % 24,
									'm' => $diff['m']
								);		
							?>
							<?php echo t('jogador_vip.jv23')?> <?php echo $uso['d'] ?> <?php echo t('jogador_vip.jv24')?>, <?php echo $uso['h'] ?> <?php echo t('jogador_vip.jv25')?> <?php echo $uso['m'] ?> <?php echo t('jogador_vip.jv26')?><br />
						</div>						
					<?php endif ?>
				<?php endif ?>
			<?php else: ?>
				<a class="button" href="javascript:<?php echo $vip_js_function ?>('<?php echo encode($item['id']) ?>')"><?php echo t('botoes.comprar')?></a>
			<?php endif; ?>
			<?php if($validade): ?>
				<br /><?php echo t('jogador_vip.jv28')?>: <?php echo $validade ?> <?php echo t('jogador_vip.jv27')?>
			<?php endif; ?>
		</td>
	</tr>
	<tr height="4">
	</tr>
<?php endforeach ?>
</table>
