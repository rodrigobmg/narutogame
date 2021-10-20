<?php
	$js_function		= $_SESSION['el_js_func_name'] = "f" . md5(rand(1, 512384));
	$pay_key_1			= $_SESSION['pay_key_1'] = round(rand(1, 999999)); // Coin
	$current_counter	= Player::getFlag('sennin_sair_count', $basePlayer->id);

	if(!$current_counter) {
		$exit_string	= 'sair1';
	} elseif($current_counter == 1) {
		$exit_string	= 'sair2';
	} else {
		$exit_string	= 'sair3';
	}
	
	if(!$basePlayer->id_sennin) {
		/*if(in_array($basePlayer->id_invocacao,array(1,2,3))) {
			$sennins	= Recordset::query('SELECT * FROM sennin WHERE id_invocacao=' . $basePlayer->id_invocacao);				
		} else {
			$sennins	= Recordset::query('SELECT * FROM sennin');	
		}*/
		$sennins	= Recordset::query('SELECT * FROM sennin');	
	} else {
		$sennins	= Recordset::query('SELECT * FROM sennin WHERE id=' . $basePlayer->id_sennin);
	}
?>
<?php if(!$basePlayer->tutorial()->sennin){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 4,
	 
	  steps: [
	  {
		element: ".tutorial-sennin",
		title: "<?php echo t("tutorial.titulos.sennin.1");?>",
		content: "<?php echo t("tutorial.mensagens.sennin.1");?>",
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
		width: 48%;
		padding: 5px;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(e) {
	<?php if($basePlayer->id_sennin): ?>
		$('.b-sennin-desaprender').on('click', function () {
			jconfirm('<?php echo t('mode_sennin.' . $exit_string)?>', null, function () {
				lock_screen(true);
	
				$.ajax({
					url: 'index.php?acao=mode_sennin_sair',
					dataType: 'script',
					type: 'post',
					data: {pm: <?php echo $pay_key_1 ?> }
				});					 
			});		
		});
	<?php else: ?>
		$('.b-sennin-aprender').on('click', function () {
			var	id		= $(this).data('id');
			var	form	= $('#f-sennin');
			
			jconfirm('<?php echo t('mode_sennin.ms2')?>', null, function () {
				lock_screen(true);
				
				$('[name=sennin]', form).val(id);
				form.submit();
			});			
		});
	<?php endif ?>		
	});
</script>
<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p><?php echo t('mode_sennin.ms1')?></p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "8183366979";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Habilidades -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script><br />
<br />
<?php msg('6',''. t('mode_sennin.ms1').'', ''.t('mode_sennin.msg1').'');?>
<?php if(isset($_GET['ok']) && $_GET['ok'] == 1 && isset($_SESSION['mode_sennin_aprendido']) && $_SESSION['mode_sennin_aprendido']): ?>
	<?php msg('5',t('mode_sennin.parabens'), sprintf(t('mode_sennin.parabens1'), Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM sennin WHERE id=' . $_SESSION['mode_sennin_aprendido'])->row()->nome))?>
	<?php
		$_SESSION['mode_sennin_aprendido']	= NULL;
	?>
<?php endif ?>

<?php if(isset($_GET['ok']) && $_GET['ok'] == 2 && isset($_GET['h']) && $basePlayer->hasItem($_GET['h'])): ?>
	<?php msg('4',t('mode_sennin.parabens'), sprintf(t('mode_sennin.parabens2'), $basePlayer->getItem($_GET['h'])->getAttribute('nome')) .'!');?>
<?php endif; ?>
<?php
	if(isset($_GET['existent'])) {
		msg('3',t('mode_sennin.problema').'!', t('mode_sennin.ja_tem'));
	}
?>
<?php if($basePlayer->id_sennin): ?>
    <table width="730" border="0" cellpadding="0" cellspacing="2" id="sennin-<?php echo $basePlayer->id_sennin ?>">
      <tr>
        <td align="left"><p>
        <br />
        <div align="right">
			<a class="button b-sennin-desaprender"><?php echo t('botoes.desfazer') ?></a>
        </div>
        </p>
        <br />
			<script type="text/javascript"><!--
			google_ad_client = "ca-pub-9048204353030493";
			/* NG - Mode Sennin */
			google_ad_slot = "5742937575";
			google_ad_width = 728;
			google_ad_height = 90;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
        </td>
      </tr>
    </table>
<?php else: ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="43" class="subtitulo-home tutorial-sennin">
			<table width="730" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="82" align="center">&nbsp;</td>
					<td width="219" align="center" class="bold_branco"><?php echo $basePlayer->id_sennin ? t('mode_sennin.ms4') : t('mode_sennin.ms5') ?></td>
	
					<td width="429" align="left">
						<select <?php echo $basePlayer->getAttribute('id_sennin') ? "disabled='disabled'" : "" ?> id="s-sennin" class="with-n-tabs">
							<?php foreach($sennins->result_array() as $sennin): ?>
								<option <?php echo $basePlayer->getAttribute('id_sennin') == $sennin['id'] ? "selected='selected'" : "" ?> value=".sennin-<?php echo $sennin['id'] ?>"><?php echo $sennin['nome_' . Locale::Get()] ?></option>
							<?php
								$dvDescs[$sennin['id']] = $sennin['descricao_' . Locale::Get()];
							?>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php foreach($sennins->result_array() as $sennin): ?>
	<tr class="sennin-<?php echo $sennin['id'] ?>">
		<td colspan="2">
			<br />
			<?php echo $sennin['descricao_' . Locale::Get()] ?>
			<br />
		</td>
	</tr>
	<tr class="sennin-<?php echo $sennin['id'] ?>">
		<td colspan="2" align="center">
			<br />
			<?php if(!$basePlayer->id_selo && !$basePlayer->portao): ?>
				<a data-id="<?php echo $sennin['id'] ?>" class="button b-sennin-aprender"><?php echo t('botoes.aprender_sennin') ?></a>
			<?php endif ?>
		</td>
	</tr>
	<?php endforeach ?>
</table>
<form method="post" id="f-sennin" action="?acao=mode_sennin_entrar">
	<input type="hidden" name="sennin" />
</form>
<?php endif; ?>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
	<td height="49" class="subtitulo-home">
		<table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
		  <tr>
			<td width="75" align="center">&nbsp;</td>
			<td width="172" align="center"><?php echo t('geral.nome')?></td>
			<td width="123" align="center"><?php echo t('geral.requerimentos')?></td>
			<td width="268" align="center"><?php echo t('geral.bonus')?></td>
			<td width="92" align="center"><?php echo t('geral.status')?></td>
		  </tr>
		</table>
	</td>
  </tr>
</table>
<?php foreach($sennins->result_array() as $sennin): ?>
<table width="730" border="0" cellpadding="0" cellspacing="0" class="sennin-<?php echo $sennin['id'] ?>">
	<?php
    	$items = Recordset::query('
			SELECT
				a.id
				
			FROM 
				item a JOIN graduacao b ON a.req_graduacao = b.id
				JOIN item_tipo c ON c.id=a.id_tipo
			WHERE 
				a.id_tipo=26 AND 
				c.equipamento=0 AND
				a.id_sennin=' . $sennin['id'] . '
			
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
		<td width="75" align="center">
		<div class="img-lateral-dojo2">
			<img src="<?php echo img('layout/mode_sennin/'. $sennin['id'].'/'.$i->getAttribute('ordem').'.png') ?>"  style="margin-top:5px"/>
		</div>	
		</td>
		<td width="172" align="center">
			<strong class="amarelo" style="font-size:13px"><?php echo $i->getAttribute('nome_' . Locale::get()) ?></strong>
			
		</td>
		<td width="123" align="center">
			<img id="i-item-<?php echo $i->id ?>" src="<?php echo img('layout/requer.gif') ?>" style="cursor: pointer" />
			<?php echo generic_tooltip('i-item-' . $i->id, Item::getRequirementLog()) ?>
		</td>
		<td width="268" height="34" align="center" class="bonus-text">
			<?php if($i->getAttribute('nin')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('nin') ?></strong> <img src="<?php echo img('layout/icones/nin.png') ?>" /><br />Ninjutsu 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('tai')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('tai') ?> <img src="<?php echo img('layout/icones/tai.png') ?>" /></strong><br />Taijutsu 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('ken')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('ken') ?> <img src="<?php echo img('layout/icones/ken.png') ?>" /></strong><br />Bukijutsu 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('gen')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('gen') ?></strong> <img src="<?php echo img('layout/icones/gen.png') ?>" /><br />Genjutsu 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('ene')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('ene') ?></strong> <img src="<?php echo img('layout/icones/ene.png') ?>" /><br />Energia 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('forc')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('forc') ?></strong> <img src="<?php echo img('layout/icones/forc.png') ?>" /><br />Força
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('inte')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('inte') ?></strong> <img src="<?php echo img('layout/icones/inte.png') ?>" /><br />Inteligência
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('con')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('con') ?></strong> <img src="<?php echo img('layout/icones/conhe.png') ?>" /><br />Selo 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('agi')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('agi') ?></strong> <img src="<?php echo img('layout/icones/agi.png') ?>" /><br />Agilidade 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('res')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('res') ?></strong> <img src="<?php echo img('layout/icones/defense.png') ?>" /><br />Resistência
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('atk_fisico')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('atk_fisico') ?></strong> <img src="<?php echo img('layout/icones/atk_fisico.png') ?>" /><br />Ataque ( Tai / Buk )
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('atk_magico')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('atk_magico') ?></strong> <img src="<?php echo img('layout/icones/atk_magico.png') ?>" /><br />Ataque ( Nin / Gen )
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('bonus_hp')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('bonus_hp') ?></strong> <img src="<?php echo img('layout/icones/p_hp.png') ?>" /><br />HP
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('bonus_sp')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('bonus_sp') ?></strong> <img src="<?php echo img('layout/icones/p_chakra.png') ?>" /><br />Chakra
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('bonus_sta')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('bonus_sta') ?></strong> <img src="<?php echo img('layout/icones/p_stamina.png') ?>" /><br />Stamina
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('def_base')): ?>
			<p>
			<strong class="verde" style="font-size:13px"><?php echo $i->getAttribute('def_base') ?></strong> <img src="<?php echo img('layout/icones/shield.png') ?>" /><br />Defesa Base 
			</p>
			<?php endif; ?>
		</td>
		<td width="92" align="center">
			<?php if($reqs && !$basePlayer->hasItem($i->id)): ?>
			<form id="f-sennin-<?php echo $i->id ?>" method="post" action="?acao=mode_sennin_treinar">
				<input type="hidden" name="id" value="<?php echo $i->id ?>" />
				<a class="button" data-trigger-form="1"><?php echo t('botoes.treinar') ?></a>

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
<?php endforeach ?>
</div>
