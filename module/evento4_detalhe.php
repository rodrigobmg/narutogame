<style>
.morto {
	filter: alpha(opacity=10);
	opacity: .1;
	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=10)";
}
</style>
<?php
	$redir_script = true;

	if($_GET['id']) {
		$id = decode($_GET['id']);
		
		if(!is_numeric($id)) {
			redirect_to("negado");
		}
	} else {
		$id = (int)$basePlayer->evento4;
	}
	
	$rEvento = Recordset::query("SELECT a.* FROM evento4 a WHERE a.id=" . $id, true)->row_array();
	
	$equipe = new Recordset('SELECT * FROM equipe WHERE id=' . (int)$basePlayer->id_equipe);
	$equipe = $equipe->row_array();
?>
<div class="titulo-secao"><p><?php echo t('evento4.e1')?></p></div>
<br />
<div id="cnBase" class="direita">
    <?php msg(2,''. $rEvento['nome_'. Locale::get()].'', ''. nl2br($rEvento['descricao_'. Locale::get()].'')); ?>
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "2775961778";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Equipe -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
    <br/><br/>
    <table width="765" class="claObj" border="0" cellpadding="4" cellspacing="0">
<?php 

	$qNPC = Recordset::query("
		SELECT 
			a.* 
		
		FROM 
			evento4_npc a
		
		WHERE 
			a.id_evento4=" . $id);

	while($rNPC = $qNPC->row_array()): 
		$cor	 = ++$c % 2 ? "class='cor_sim'" : "class='cor_nao'";	
	?>
	<tr <?php echo $cor ?>>
	  <td colspan="2" style="font-size: 14px" class="amarelo"><?php echo $rNPC['nome'] ?></td>
	</tr>
	<tr>
	  <td width="510"><img src="<?php echo img() ?>evento4/<?php echo $rNPC['id_classe'] ?>.png" /></td>
	  <td height="34" align="center">
		<?php
			switch($rEvento['dificuldade']) {
				case 'normal':
					$percent = 100;
					
					break;
					
				case 'hard':
					$percent = 150;
					
					break;
					
				case 'ogro':
					$percent = 200;
					
					break;
					
			}
		?>
          <p style="float: left; width:50%">
            <b style="color:#af9d6b; font-size:14px;"><?php echo percent($percent, $basePlayer->NIN) ?></b><br /><?php echo t('at.nin')?> <img src="<?php echo img('layout/icones/nin.png') ?>" />
          </p>
          <p style="float: left; width:50%">
            <b style="color:#af9d6b; font-size:14px;"><?php echo percent($percent, $basePlayer->TAI) ?></b><br /><?php echo t('at.tai')?> <img src="<?php echo img('layout/icones/tai.png') ?>" />
          </p>
		  <p style="float: left; width:50%">
            <b style="color:#af9d6b; font-size:14px;"><?php echo percent($percent, $basePlayer->KEN) ?></b><br /><?php echo t('at.ken')?> <img src="<?php echo img('layout/icones/ken.png') ?>" />
          </p>
          <p style="float: left; width:50%">
            <b style="color:#af9d6b; font-size:14px;"><?php echo percent($percent, $basePlayer->GEN) ?></b><br /><?php echo t('at.gen')?> <img src="<?php echo img('layout/icones/gen.png') ?>" />
          </p>
          <p style="float: left; width:50%">
            <b style="color:#af9d6b; font-size:14px;"><?php echo percent($percent, $basePlayer->ENE) ?></b><br /><?php echo t('at.ene')?> <img src="<?php echo img('layout/icones/ene.png') ?>" />
          </p>
          <p style="float: left; width:50%">
            <b style="color:#af9d6b; font-size:14px;"><?php echo percent($percent, $basePlayer->FOR) ?></b><br /><?php echo t('at.for')?> <img src="<?php echo img('layout/icones/forc.png') ?>" />
          </p>
          <p style="float: left; width:50%">
            <b style="color:#af9d6b; font-size:14px;"><?php echo percent($percent, $basePlayer->INT) ?></b><br /><?php echo t('at.int')?> <img src="<?php echo img('layout/icones/inte.png') ?>" />
          </p>
          <p style="float: left; width:50%">
            <b style="color:#af9d6b; font-size:14px;"><?php echo percent($percent, $basePlayer->CON) ?></b><br /><?php echo t('at.con')?> <img src="<?php echo img('layout/icones/conhe.png') ?>" />
          </p>
          <p style="float: left; width:50%">
            <b style="color:#af9d6b; font-size:14px;"><?php echo percent($percent, $basePlayer->AGI) ?></b><br /><?php echo t('at.agi')?> <img src="<?php echo img('layout/icones/agi.png') ?>" />
          </p>
          <p style="float: left; width:50%">
            <b style="color:#af9d6b; font-size:14px;"><?php echo percent($percent, $basePlayer->RES) ?></b><br /><?php echo t('at.res')?> <img src="<?php echo img('layout/icones/shield.png') ?>" />
          </p>
	  </td>
	</tr>
	<tr >
		<td colspan="2"><?php echo $rNPC['descricao_'. Locale::get()] ?></td>
	</tr>
<?php endwhile; ?>
  </table>

</div>