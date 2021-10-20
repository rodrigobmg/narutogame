<?php
	$js_function		= $_SESSION['el_js_func_name'] = "f" . md5(rand(1, 512384));
	$pay_key_1			= $_SESSION['pay_key_1'] = round(rand(1, 999999)); // Coin
	$current_counter	= Player::getFlag('portao_sair_count', $basePlayer->id);

	if(!$current_counter) {
		$exit_string	= 'sair1';
	} elseif($current_counter == 1) {
		$exit_string	= 'sair2';
	} else {
		$exit_string	= 'sair3';
	}
?>
<?php if(!$basePlayer->tutorial()->portoes){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 6,
	 
	  steps: [
	  {
		element: ".tutorial-portoes",
		title: "<?php echo t("tutorial.titulos.portoes.1");?>",
		content: "<?php echo t("tutorial.mensagens.portoes.1");?>",
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
<style>
	.bonus-text p b {
		color: #af9d6b; 
		font-size:14px;
	}
	
	.bonus-text p {
		float: left; 
		width: 46%;
		padding: 5px;
	}
</style>
<script type="text/javascript">
	var portao = <?php echo $basePlayer->getAttribute('portao') ? "true" : "false" ?>;
	
	function doTreinaPortao(id) {
		if(!portao) {
			jconfirm('<?php echo t('portoes.p1')?>', null, function () {
				lock_screen(true);

				$('#f-portao-' + id)[0].submit();			
			});
		} else {
			lock_screen(true);
			$('#f-portao-' + id)[0].submit();
		}
	}
	
	function <?php echo $js_function ?>() {
		jconfirm('<?php echo t('portoes.' . $exit_string)?>', null, function () {
			lock_screen(true);

			$.ajax({
				url: 'index.php?acao=portoes_sair',
				dataType: 'script',
				type: 'post',
				data: {pm: <?php echo $pay_key_1 ?> }
			});		
		});
	}
</script>
<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p><?php echo t('portoes.p3')?></p></div>
<?php if(isset($_GET['ok']) && $_GET['ok'] == 1 && isset($_GET['h']) && $basePlayer->hasItem($_GET['h'])): ?>
<!-- Mensagem nos Topos das Seções -->
<?php msg('3',''.t('portoes.p4').'', ''.t('portoes.p5').' '. $basePlayer->getItem($_GET['h'])->getAttribute('nome_'. Locale::get()) .'!');?>
<!-- Mensagem nos Topos das Seções -->	
<?php endif; ?>
<?php
	if(isset($_GET['existent'])) {
		msg('4',''.t('portoes.p5').'', ''.t('portoes.p6').'');			
	}
?>
    <table width="730" border="0" cellpadding="0" cellspacing="2">
      <tr>
        <td align="left"><p>
        <?php if($basePlayer->getAttribute('portao')): ?>
        <div align="center">
	        <input type="button" class="button" value="<?php echo t('botoes.desfazer')?>" onclick="<?php echo $js_function ?>()" border="0"/>
        </div>
		<br />

        <?php endif; ?>
        </p>
		<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "8183366979";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Habilidades -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
        </td>
      </tr>
    </table>
    
    <br />
	<table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="49"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="49" class="subtitulo-home tutorial-portoes"><table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
              <tr>
                <td width="90" align="center">&nbsp;</td>
                <td width="172" align="center"><?php echo t('geral.nome'); ?></td>
                <td width="138" align="center"><?php echo t('geral.requerimentos'); ?></td>
                <td width="238" align="center"><?php echo t('geral.bonus'); ?></td>
                <td width="92" align="center"><?php echo t('geral.status'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>

<table width="730" border="0" cellpadding="0" cellspacing="0">
	<?php
    	$items = Recordset::query('
			SELECT
				a.id,
				a.ordem
				
			FROM 
				item a JOIN graduacao b ON a.req_graduacao = b.id
				JOIN item_tipo c ON c.id=a.id_tipo
			WHERE a.id_tipo=17 AND c.equipamento=0		
			
			ORDER BY ordem ASC
		', true);
		
		$color_counter = 0;
	?>
	<?php foreach($items->result_array() as $item): ?>
	<?php
		$i		= new Item($item['id']);
		$bg		= ++$color_counter % 2 ? "class='cor_sim'" : "class='cor_nao'";
		$reqs	= Item::hasRequirement($i, $basePlayer);
	?>
    <tr <?php echo $bg ?>>
		<td width="90" align="center">
		<div class="img-lateral-dojo2">
				<img src="<?php echo img('layout/portoes/'.$i->getAttribute('ordem').'.gif')?>" width="53" height="53" style="margin-top:5px"/>
		</div>		
		<td width="172" align="center">
			<strong class="amarelo" style="font-size:13px"><?php echo $i->getAttribute('nome_'. Locale::get()) ?></strong>			
		</td>
		<td width="138" align="center">
			<img id="i-item-<?php echo $i->id ?>" src="<?php echo img('layout/requer.gif') ?>" style="cursor: pointer" />
			<?php echo generic_tooltip('i-item-' . $i->id, Item::getRequirementLog()) ?>
		</td>
		<td width="238" height="34" align="center" class="bonus-text">
			<?php if($i->getAttribute('nin')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('nin') ?></strong> <img src="<?php echo img('layout/icones/nin.png') ?>" /><br /><?php echo t('at.nin'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('tai')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('tai') ?></strong> <img src="<?php echo img('layout/icones/tai.png') ?>" /><br /><?php echo t('at.tai'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('ken')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('ken') ?></strong> <img src="<?php echo img('layout/icones/ken.png') ?>" /><br /><?php echo t('at.ken'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('gen')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('gen') ?></strong> <img src="<?php echo img('layout/icones/gen.png') ?>" /><br /><?php echo t('at.gen'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('ene')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('ene') ?></strong> <img src="<?php echo img('layout/icones/ene.png') ?>" /><br /><?php echo t('at.ene'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('forc')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('forc') ?></strong> <img src="<?php echo img('layout/icones/forc.png') ?>" /><br /><?php echo t('at.for'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('inte')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('inte') ?></strong> <img src="<?php echo img('layout/icones/inte.png') ?>" /><br /><?php echo t('at.int'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('con')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('con') ?></strong> <img src="<?php echo img('layout/icones/conhe.png') ?>" /><br /><?php echo t('at.con'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('agi')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('agi') ?></strong> <img src="<?php echo img('layout/icones/agi.png') ?>" /><br /><?php echo t('at.agi'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('res')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('res') ?></strong> <img src="<?php echo img('layout/icones/defense.png') ?>" /><br /><?php echo t('at.res'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('def_base')): ?>
			<p>
			<strong class="verde" style="font-size:13px"><?php echo $i->getAttribute('def_base') ?></strong> <img src="<?php echo img('layout/icones/shield.png') ?>" /><br /><?php echo t('formula.def_base'); ?> 
			</p>
			<?php endif; ?>

			<?php if($i->det): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->det ?>%</strong> <img src="<?php echo img('layout/icones/deter.png') ?>" /><br /><?php echo t('formula.det'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->conc): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->conc ?>%</strong> <img src="<?php echo img('layout/icones/target2.png') ?>" /><br /><?php echo t('formula.conc'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->conv): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->conv ?>%</strong> <img src="<?php echo img('layout/icones/convic.png') ?>" /><br /><?php echo t('formula.conv'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->esq): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->esq ?>%</strong> <img src="<?php echo img('layout/icones/esquiva.png') ?>" /><br /><?php echo t('formula.esq'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->atk_fisico): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->atk_fisico ?></strong> <img src="<?php echo img('layout/icones/atk_fisico.png') ?>" /><br /><?php echo t('formula.atk_fisico'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->atk_magico): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->atk_magico ?></strong> <img src="<?php echo img('layout/icones/atk_magico.png') ?>" /><br /><?php echo t('formula.atk_magico'); ?> 
			</p>
			<?php endif; ?>

			<?php if($i->prec_fisico): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->prec_fisico ?></strong> <img src="<?php echo img('layout/icones/prec_tai.png') ?>" /><br /><?php echo t('formula.prec_fisico'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->prec_magico): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->prec_magico ?></strong> <img src="<?php echo img('layout/icones/prec_nin_gen.png') ?>" /><br /><?php echo t('formula.prec_magico'); ?>
			</p>
			<?php endif; ?>
			<?php if($i->crit_min): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->crit_min ?></strong> <img src="<?php echo img('layout/icones/p_stamina.png') ?>" /><br /><?php echo t('formula.crit_min'); ?> 
			</p>
			<?php endif; ?>
			<?php if($i->crit_max): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->crit_max ?></strong><br /> <img src="<?php echo img('layout/icones/p_stamina.png') ?>" /><?php echo t('formula.crit_max'); ?> 
			</p>
			<?php endif; ?>			
			
			<?php if($i->getAttribute('consume_hp')): ?>
			<p>
			<strong class="verde" style="font-size:13px">- <?php echo $i->getAttribute('consume_hp') ?></strong> <img src="<?php echo img('layout/icones/p_hp.png') ?>" /><br />HP 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('bonus_sp')): ?>
			<p>
			<strong class="verde" style="font-size:13px"> <?php echo $i->getAttribute('bonus_sp') ?></strong> <img src="<?php echo img('layout/icones/p_chakra.png') ?>" /><br />Chakra 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('bonus_sta')): ?>
			<p>
			<strong class="verde" style="font-size:13px"> <?php echo $i->getAttribute('bonus_sta') ?></strong> <img src="<?php echo img('layout/icones/p_stamina.png') ?>" /><br />Stamina 
			</p>
			<?php endif; ?>
		</td>
		<td width="92" align="center">
			<?php if($reqs && !$basePlayer->hasItem($i->id)): ?>
			<form id="f-portao-<?php echo $i->id ?>" method="post" action="?acao=portoes_treinar" onsubmit="return false">
				<input type="hidden" name="id" value="<?php echo $i->id ?>" />
				<a class="button" onclick="doTreinaPortao(<?php echo $i->id ?>)"><?php echo t('botoes.treinar') ?></a>
			</form>
			<?php else: ?>
				<?php if($basePlayer->hasItem($i->id)): ?>
				<a class="button ui-state-green"><?php echo t('botoes.treinado') ?></a>
				<?php else: ?>
				<a class="button ui-state-disabled"><?php echo t('botoes.treinar') ?></a>
				<?php endif; ?>
			<?php endif; ?>
		</td>
    </tr>
	<tr height="4"></tr>
	<?php endforeach; ?>
</table>
</div>
