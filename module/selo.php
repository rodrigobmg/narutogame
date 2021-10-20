<?php
	$selos				= Recordset::query('SELECT * FROM selo', true);
	$js_function		= $_SESSION['el_js_func_name'] = "f" . md5(rand(1, 512384));
	$pay_key_1			= $_SESSION['pay_key_1'] = round(rand(1, 999999)); // Coin
	$dvDescs			= array();
	$current_counter	= Player::getFlag('selo_sair_count', $basePlayer->id);

	if(!$current_counter) {
		$exit_string	= 'sair1';
	} elseif($current_counter == 1) {
		$exit_string	= 'sair2';
	} else {
		$exit_string	= 'sair3';
	}
?>
<?php if(!$basePlayer->tutorial()->selo){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 5,
	 
	  steps: [
	  {
		element: ".tutorial-selo",
		title: "<?php echo t("tutorial.titulos.selo.1");?>",
		content: "<?php echo t("tutorial.mensagens.selo.1");?>",
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
		padding: 5px
	}
</style>
<script type="text/javascript">
	function mudaSelo(){
		var v = document.getElementById("selo").value;
		
		$(".seloObj").hide();
		
		document.getElementById("dvSelo_" + v).style.display = "block";
		document.getElementById("tbSelo_" + v).style.display = "block";
	}
	
	function entrarSelo() {
		if(confirm("<?php echo t('clas.c12')?>")) {
			
			$("#btEntrarSelo").attr("disabled", true);
			
			$.ajax({
				url: 'index.php?acao=selo_entrar',
				dataType: 'script',
				type: 'post',
				data: {id: document.getElementById("selo").value }
			});
		}
	}
	
	function <?php echo $js_function ?>() {
		jconfirm("<?php echo t('selos.' . $exit_string)?>", null, function () {
			lock_screen(true);

			$.ajax({
				url: 'index.php?acao=selo_sair',
				dataType: 'script',
				type: 'post',
				data: {pm: <?php echo $pay_key_1 ?> }
			});
		});
	}
</script>
<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p><?php echo t('titulos.selo'); ?></p></div><br />
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
<br/><br/>
<?php if(isset($_GET['ok']) && $_GET['ok'] == 1): ?>

<?php msg('1',''.t('academia_jutsu.parabens').'', ''.t('selos.possui').''. $basePlayer->nome_selo .'!');?>
	
<?php elseif(isset($_GET['ok']) && $_GET['ok'] == 2 && isset($_GET['h']) && $basePlayer->hasItem($_GET['h'])): ?>

<?php msg('2',''.t('academia_jutsu.parabens').'', ''.t('selos.aprendeu').' '. $basePlayer->getItem($_GET['h'])->getAttribute('nome') .'!');?>

<?php endif; ?>
<?php
	if(isset($_GET['existent'])) {

msg('3',''.t('academia_jutsu.parabens').'', ''.t('selos.treinou').'');
	}
?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="49" class="subtitulo-home tutorial-selo"><table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
                <tr>
                  <td width="82" align="center">&nbsp;</td>
                  <td width="219" align="center"><?= $basePlayer->id_selo ? "".t('selos.tem').": " : "".t('selos.escolha').": " ?></td>

                  <td width="429" align="left">
                  		 <select <?php echo $basePlayer->id_selo ? "disabled='disabled'" : "" ?> name="selo" id="selo" onchange="mudaSelo();">
						<?php
                            $dvDescs = array();
                            $qSelo = Recordset::query("SELECT * FROM selo");
                            
                            while($rSelo = $qSelo->row_array()) {
                        ?>
                            <option <?= $basePlayer->id_selo == $rSelo['id'] ? "selected='selected'" : "" ?> value="<?= $rSelo['id'] ?>"><?= $rSelo['nome_'.Locale::get()] ?></option>
                        <?php
                                $dvDescs[$rSelo['id']] = $rSelo['descricao_'.Locale::get()];
                            }
                        ?>
                      </select>
                  </td>
                </tr>
            </table></td>
          </tr>
      </table>
    <table width="730" border="0" cellpadding="0" cellspacing="0">
        <tr id="desc_Sharingan">
          <td height="34" colspan="3" align="left" >
          <?php
          	foreach($dvDescs as $k => $d) {
		  ?>
		  <div id="dvSelo_<?= $k ?>" class="seloObj p_style" style="display: none">
          	<?= $d ?>
          </div>
		  <?php	
			}
		  ?>
          </td>
        </tr>
        <tr>
          <td height="34" colspan="3" align="center">
         <?php if(!$basePlayer->getAttribute('id_selo') && !$basePlayer->id_sennin && !$basePlayer->portao): ?>
			  <a class="button" onclick="entrarSelo()"><?php echo t('botoes.aceitar_selo') ?></a>
         <?php elseif(!$basePlayer->getAttribute('id_selo') && $basePlayer->id_sennin): ?>
			 <a class="button ui-state-disabled"><?php echo t('botoes.aceitar_selo') ?></a>
         <?php elseif($basePlayer->getAttribute('id_selo')): ?>
			<a class="button" onclick="<?php echo $js_function ?>()"><?php echo t('botoes.rejeitar_selo') ?></a>
         <?php endif; ?>
         </td>
        </tr>
    </table>
    
<br />
 <table width="730" border="0" cellpadding="0" cellspacing="0" >
      <tr>
        <td height="49"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
              <tr>
                <td width="80" align="center">&nbsp;</td>
                <td width="182" align="center"><?php echo t('geral.nome') ?></td>
                <td width="138" align="center"><?php echo t('geral.requerimentos') ?></td>
                <td width="273" align="center">Bônus</td>
                <td width="92" align="center">Status</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
<?php foreach($selos->result_array() as $selo): ?>
<table style="display: none" width="730" class="seloObj" border="0" cellpadding="0" cellspacing="0" id="tbSelo_<?php echo $selo['id'] ?>">
	<?php
		if($basePlayer->id_selo && $selo['id'] != $basePlayer->id_selo) {
			echo '</table>';
			
			continue;
		}
	
    	$items = Recordset::query('
			SELECT
				a.id,
				a.ordem
				
			FROM 
				item a JOIN graduacao b ON a.req_graduacao = b.id
				JOIN item_tipo c ON c.id=a.id_tipo
			WHERE a.id_tipo=20 AND a.id_selo='. $selo['id'] . ' AND c.equipamento=0		
			
			ORDER BY ordem ASC
		');
		
		$color_counter = 0;
	?>
	<?php foreach($items->result_array() as $item): ?>
	<?php
		$i		= new Item($item['id']);
		$bg		= ++$color_counter % 2 ? "class='cor_sim'" : "class='cor_nao'";
		$reqs	= Item::hasRequirement($i, $basePlayer);
	?>
    <tr <?php echo $bg ?>>
		<td width="80"  align="center">
			<div class="img-lateral-dojo2">
				<img src="<?php echo img('layout/selos/'. $selo['id'].'/'.$i->getAttribute('ordem').'.gif')?>" width="53" height="53" style="margin-top:5px"/>
			</div>	
		</td>
		<td width="182" align="center"><strong class="amarelo" style="font-size:13px"><?php echo $i->getAttribute('nome_'.Locale::get()) ?></strong></td>
		<td width="138" align="center">
			<img id="i-item-<?php echo $i->id ?>" src="<?php echo img('layout/requer.gif') ?>" style="cursor: pointer" />
			<?php echo generic_tooltip('i-item-' . $i->id, Item::getRequirementLog()) ?>
		</td>
		<td width="273" height="34" align="center" class="bonus-text">
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
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('ene') ?></strong> <img src="<?php echo img('layout/icones/ene.png') ?>" /><br /><?php echo t('at.ene') ?> 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('forc')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('forc') ?></strong> <img src="<?php echo img('layout/icones/forc.png') ?>" /><br /><?php echo t('at.for') ?>
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('inte')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('inte') ?></strong> <img src="<?php echo img('layout/icones/inte.png') ?>" /><br /><?php echo t('at.int') ?>
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('con')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('con') ?></strong> <img src="<?php echo img('layout/icones/conhe.png') ?>" /><br /><?php echo t('at.con') ?> 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('agi')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('agi') ?></strong> <img src="<?php echo img('layout/icones/agi.png') ?>" /><br /><?php echo t('at.agi') ?> 
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('res')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('res') ?></strong> <img src="<?php echo img('layout/icones/defense.png') ?>" /><br /><?php echo t('at.res') ?>
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('atk_fisico')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('atk_fisico') ?></strong> <img src="<?php echo img('layout/icones/atk_fisico.png') ?>" /><br /><?php echo t('formula.atk_fisico') ?>
			</p>
			<?php endif; ?>
			<?php if($i->getAttribute('atk_magico')): ?>
			<p>
			<strong class="verde" style="font-size:13px">+ <?php echo $i->getAttribute('atk_magico') ?></strong> <img src="<?php echo img('layout/icones/atk_magico.png') ?>" /><br /><?php echo t('formula.atk_magico') ?>
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
		</td>
		<td width="92" align="center">
			<?php if($reqs && !$basePlayer->hasItem($i->id)): ?>
			<form method="post" action="?acao=selo_treinar">
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
<?php endforeach; ?>
</div>
<script type="text/javascript">
$(document).ready(function () {
	mudaSelo();
});
</script>
