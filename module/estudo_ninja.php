<?php
  $points_avail = Player::getFlag('estudo_ninja_pontos', $basePlayer->id) - Player::getFlag('estudo_ninja_pontos_gasto', $basePlayer->id);

  $_SESSION['fk_estudo_ninja'] = md5(date('YmdHis') . rand(1, 512384));
?>
<div class="titulo-secao"><p><?php echo t('estudo_ninja.e1')?></p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "2416034977";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Estudo -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<?php if(!$basePlayer->tutorial()->estudo){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 9,
	 
	  steps: [
	  {
		element: ".msg_gai",
		title: "<?php echo t("tutorial.titulos.estudo.1");?>",
		content: "<?php echo t("tutorial.mensagens.estudo.1");?>",
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
<?php if(isset($_SESSION['estudo_ninja_msg']) && $_SESSION['estudo_ninja_msg']): ?>
	<?php
		$item = new Recordset('SELECT * FROM estudo_ninja WHERE id=' . $_SESSION['estudo_ninja_msg'], true);
		$item = $item->row_array();		
	?>
<!-- Mensagem nos Topos das Seções -->
	<?php msg('1',''.t('estudo_ninja.e2').'', ''.t('estudo_ninja.e3').': <span class="verde">'. $item['nome_'.Locale::get()].'');?>
<!-- Mensagem nos Topos das Seções -->

<?php endif; ?>
<br/><br/>
<!-- Mensagem nos Topos das Seções -->
	<?php msg('2',''.t('estudo_ninja.e1').'', ''. sprintf(t('estudo_ninja.e4'),$points_avail));?><br/>
<!-- Mensagem nos Topos das Seções -->
<div align="center">
	<?php if(between(date('Hi'), '2345', '2359') || between(date('Hi'), '0000', '0015')): ?>
	<?php echo t('geral.g29')?>
	<?php else: ?>
	<a class="button" onclick="javascript:estudo_ninja()"><?php echo t('botoes.comecar_o_estudo_ninja') ?></a>

	<?php endif; ?>
</div>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="90" align="center">&nbsp;</td>
        <td width="300" align="left"><b style="color:#FFFFFF"><?php echo t('geral.premio_descricao')?></b></td>
          <td width="240" align="center"><b style="color:#FFFFFF"><?php echo t('geral.seus_pontos_requeridos')?></b></td>
        <td width="100" align="center"></td>
      </tr>
    </table></td>
  </tr>
</table>
<script type="text/javascript">
	function doEstudoNinjaTroca(i) {
		$('#f-troca-h-item').val(i);
		$('#f-troca')[0].submit();
	}
</script>
<form method="post" id="f-troca" action="?acao=estudo_ninja_trocar" onsubmit="return false">
<input type="hidden" name="key" value="<?php echo $_SESSION['fk_estudo_ninja'] ?>" />
<input type="hidden" id="f-troca-h-item" name="item" value="0" />
<table width="730" border="0" cellpadding="2" cellspacing="0">
	<?php 
		$trocas = new Recordset('SELECT * FROM estudo_ninja ORDER BY pontos ASC', true);
		$c		= 0;
	?>
	<?php foreach($trocas->result_array() as $troca):
			$bg		= ++$c % 2 ? "class='cor_sim'" : "class='cor_nao'";
			
			if($troca['coin']) {
				if($basePlayer->getUFlag('estudo_ninja_troca_' . $troca['id'], 0)) {
					$avail = false;
				} else {
					$avail = true;
				}
			} else {
				$avail = true;
			}
	 ?>
		<tr <?php echo  $bg ?>>
		<td width="90" align="center">
			<div class="img-lateral-dojo2">
				<img src="<?php echo img()?>layout/estudo_ninja/premios/<?php echo $troca['id'] ?>.png" alt="<?php echo $troca['nome_'. Locale::get()] ?>" width="53" height="53"  style="margin-top:5px" />
			</div>	
		</td>
		<td width="300" align="left">
			<b style="font-size:13px;" class="amarelo"><?php echo $troca['nome_'. Locale::get()] ?></b><br />
		    <?php echo $troca['descricao_'. Locale::get()] ?>
		    <?php if($troca['coin']): ?>
		    <br /><br />
			<?php echo t('estudo_ninja.e5')?>
		    <?php endif; ?>
		</td>
		
		<td width="240" align="center"><?php echo $points_avail ?> <?php echo t('atributos.a14')?> <b class="verde"><?php echo $troca['pontos'] ?> <?php echo t('geral.pontos')?></b> </td>
		<td width="100" align="center">
			<?php if($points_avail >= $troca['pontos'] && $avail): ?>
			<a class="button" onclick="doEstudoNinjaTroca(<?php echo $troca['id'] ?>)"><?php echo t('botoes.trocar') ?></a>
			<?php else: ?>
			<a class="button ui-state-disabled"><?php echo t('botoes.trocar') ?></a>
			<?php endif; ?>
		</td>
		</tr>
		<tr height="4"></tr>
  <?php endforeach; ?>
</table>
</form>
<br />
<?php
	$_SESSION['estudo_ninja_msg'] = false;
?>
