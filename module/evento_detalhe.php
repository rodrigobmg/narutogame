<style>
.morto {
	filter: alpha(opacity=10);
	opacity: .1;
	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=10)";
}
</style>
<?php
	$redir_script = true;

	$js_function			= $_SESSION['ev_js_func_name'] = "f" . md5(rand(1, 512384));
	$js_functionb			= $_SESSION['ev_js_func_nameb'] = "f" . md5(rand(1, 512384));
	$ev_field_postkey		= $_SESSION['ev_field_postkey'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));
	$ev_field_postkey_value	= $_SESSION['ev_field_postkey_value'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));

	if(isset($_GET['id']) && $_GET['id']) {
		$id = decode($_GET['id']);
		
		if(!is_numeric($id)) {
			redirect_to("negado");
		}
	} else {
		$id = (int)$basePlayer->id_evento;
	}
	
	if(!$id) {
		redirect_to('negado');
	}	

	$rEvento	= Recordset::query("SELECT a.*, (SELECT COUNT(id) FROM evento_npc_evento WHERE id_evento=a.id) AS npc_total FROM evento a WHERE a.id=" . $id, true)->row_array();
?>
<div class="titulo-secao"><p><?php echo t('evento4.e1')?></p></div>
<div id="cnBase" class="direita">
	<?php //echo nl2br($rEvento['descricao']) ?>
	<?php if($basePlayer->id_evento == $id): ?>
	<?php
		$players	= Recordset::query('SELECT GROUP_CONCAT(id) AS players FROM player WHERE id_equipe=' . $basePlayer->id_equipe)->row()->players;
		$rNPCMorto	= Recordset::query("
			SELECT 
				 COUNT(a.id) AS total
			FROM 
				 evento_npc_evento a JOIN evento_npc_equipe b ON b.id_evento_npc=a.id_evento_npc
			WHERE
				 a.id_evento=" . $id . " AND
				 b.id_evento=" . $id ." AND
				 b.morto='1' AND 
				 b.id_equipe=" . $basePlayer->id_equipe)->row_array();	
	?>
              	 <div class="msg_gai">
                    <div class="msg">
							<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
                            <b><?php echo t('evento_detalhe.e1')?></b>
							<p>
								<?php if($rNPCMorto['total'] >= $rEvento['npc_total']): ?>
										<?php echo t('evento_detalhe.e2')?>.        
										<script type="text/javascript">
											function <?php echo $js_function?>() {
												$.ajax({
													url: "?acao=evento_final",
													type: "post",
													data: {<?php echo $ev_field_postkey ?>:"<?php echo $ev_field_postkey_value ?>"},
													success: function () {
														location.href='?secao=personagem_status'
													}
												});
											}
										</script>
										<?php //if($basePlayer->dono_equipe): ?>
										<br/><br/>
										<a class="button" onclick="<?php echo $js_function?>()"><?php echo t('botoes.finalizar_rec')?></a>
										
										<?php //endif; ?>
								<?php else: ?>
									<?php echo t('evento_detalhe.e3')?>
								   
										<script type="text/javascript">
											function <?php echo $js_functionb ?>() {
												if(!confirm('<?php echo t('evento_detalhe.e4')?>')) {
													return;
												}
												
												$.ajax({
													url: "?acao=evento_cancelar",
													type: "post",
													data: {<?php echo $ev_field_postkey ?>:"<?php echo $ev_field_postkey_value ?>"},
													success: function () {
														location.href='?secao=personagem_status'
													}
												});
											}
										</script>
										<?php if($basePlayer->dono_equipe || $basePlayer->sub_equipe): ?>
										<?php echo t('evento_detalhe.e5')?>
										<a class="button" onclick="<?php echo $js_functionb ?>()"><?php echo t('botoes.cancelar_evento')?></a>
										<?php endif; ?>
								<?php endif; ?>
			</p></div>
		         </div>
                </div>
    <?php else: ?>
    	
<?php msg(($id == 86 ? "pascoa":1),' ' . $rEvento['nome_'. Locale::get()] . ' ',''. nl2br($rEvento['descricao_'. Locale::get()]).''); ?>
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
<?php endif; ?>
    <br/><br/>
<?php if($id!=86){?>	
	<table width="730" class="claObj" border="0" cellpadding="4" cellspacing="0" id="tbCla_<?php echo encode(1) ?>">
	<!--
	<tr>
	  <td width="48" align="center" background="<?php echo img() ?>bg_sessao2.jpg">&nbsp;</td>
	  <td width="297" align="center" background="<?php echo img() ?>bg_fundo.jpg"><b style="color:#ffffff">Nome</b></td>
	  <td width="219" height="34" align="center" background="<?php echo img() ?>bg_fundo.jpg"><b style="color:#ffffff">Bonus</b></td>
	</tr>-->
	<?php 
		$c		= 0;
		$qNPC	= Recordset::query("
			SELECT 
				b.*,
				b.nome_".Locale::get()." AS nome ,
				b.descricao_".Locale::get()." AS descricao,
				(SELECT morto FROM evento_npc_equipe WHERE id_equipe=" . $basePlayer->id_equipe . " AND id_evento_npc=a.id_evento_npc AND id_evento=" . (int)$basePlayer->id_evento . ") AS morto 
		
			FROM 
				evento_npc_evento a JOIN evento_npc b ON b.id=a.id_evento_npc
		
			WHERE 
				a.id_evento=" . $id); 
	?>
	<?php foreach($qNPC->result_array() as $rNPC): ?>
	<?php
		if($rNPC['morto']){ 
				$morto = "class='morto'";
		} else {
			$morto = "";	
		}		

		$bg = ++$c % 2 ? "class='cor_sim'" : "class='cor_nao'";			
	?>
	<tr <?php echo $bg ?>>
	  <td width="510"><img src="<?php echo img() ?>layout<?php echo LAYOUT_TEMPLATE?>/npc_evento/<?php echo $rNPC['id'] ?>.png" <?php echo $morto;?> /></td>
	  <td width="30%" height="34" align="center" <?php echo $bg ?>>
		  <p style="float: left; width:44%; padding: 5px">
			<b class="verde" style="font-size:14px;"><?php echo percent(200, $basePlayer->getAttribute('tai_calc')) ?></b> <img src="<?php echo img('layout/icones/tai.png') ?>" /><br /> <?php echo t('at.tai')?>
		  </p>
		  <p style="float: left; width:44%; padding: 5px">
			<b class="verde" style="font-size:14px;"><?php echo percent(200, $basePlayer->getAttribute('ken_calc')) ?></b> <img src="<?php echo img('layout/icones/ken.png') ?>" /><br /> <?php echo t('at.ken')?>
		  </p>
		  <p style="float: left; width:44%; padding: 5px">
			<b class="verde" style="font-size:14px;"><?php echo percent(150, $basePlayer->getAttribute('for_calc')) ?></b> <img src="<?php echo img('layout/icones/forc.png') ?>" /><br /> <?php echo t('at.for')?>
		  </p>
		  <p style="float: left; width:44%; padding: 5px">
			<b class="verde" style="font-size:14px;"><?php echo percent(200, $basePlayer->getAttribute('gen_calc')) ?></b> <img src="<?php echo img('layout/icones/gen.png') ?>" /><br /> <?php echo t('at.gen')?>
		  </p>
		  <p style="float: left; width:44%; padding: 5px">
			<b class="verde" style="font-size:14px;"><?php echo percent(200, $basePlayer->getAttribute('con_calc')) ?></b> <img src="<?php echo img('layout/icones/conhe.png') ?>" /><br /> <?php echo t('at.con')?> 
		  </p>
          <p style="float: left; width:44%; padding: 5px">
			<b class="verde" style="font-size:14px;"><?php echo percent(200, $basePlayer->getAttribute('nin_calc')) ?></b> <img src="<?php echo img('layout/icones/nin.png') ?>" /><br /> <?php echo t('at.nin')?> 
		  </p>
          <p style="float: left; width:44%; padding: 5px">
			<b class="verde" style="font-size:14px;"><?php echo percent(200, $basePlayer->getAttribute('agi_calc')) ?></b> <img src="<?php echo img('layout/icones/agi.png') ?>" /><br /> <?php echo t('at.agi')?> 
		  </p>
          <p style="float: left; width:44%; padding: 5px">
			<b class="verde" style="font-size:14px;"><?php echo percent(150, $basePlayer->getAttribute('int_calc')) ?></b> <img src="<?php echo img('layout/icones/inte.png') ?>" /><br /> <?php echo t('at.int')?> 
		  </p>
		  <p style="float: left; width:44%; padding: 5px">
			<b class="verde" style="font-size:14px;"><?php echo percent(200, $basePlayer->getAttribute('res_calc')) ?></b> <img src="<?php echo img('layout/icones/shield.png') ?>" /><br /> <?php echo t('at.res')?> 
		  </p>
		  <p style="float: left; width:44%; padding: 5px">
			<b class="verde" style="font-size:14px;"><?php echo percent(200, $basePlayer->getAttribute('ene_calc')) ?></b> <img src="<?php echo img('layout/icones/ene.png') ?>" /><br /> <?php echo t('at.ene')?> 
		  </p>
	  </td>
	</tr>
	<tr >
		<td colspan="2" <?php echo $bg ?>><?php echo $rNPC['descricao'] ?></td>
	</tr>
    <tr height="4"></tr>
	<?php endforeach; ?>
	</table>
<?php } else {	?>
<div>
	<?php 
	$qNPC = Recordset::query("
		SELECT 
			b.*, 
			(SELECT morto FROM evento_npc_equipe WHERE id_equipe=" . (int)$basePlayer->id_equipe . " AND id_evento_npc=a.id_evento_npc AND id_evento=" . (int)$basePlayer->id_evento . ") AS morto 
		
		FROM 
			evento_npc_evento a JOIN evento_npc b ON b.id=a.id_evento_npc
		
		WHERE 
			a.id_evento=" . $id);
		$c		= 0;
		while($rNPC = $qNPC->row_array()): 
		 
	
		if ($rNPC['morto']){ 
			$morto = "class='morto'";
			
		}else{
			$morto = "";	
		}
	
			$bg = ++$c % 2 ? "bgcolor='#413625'" : "bgcolor='#251a13'";			
	?>
		
		
			<div style="float: left; width: 185px; height: 246px">
				<img src="<?php echo img() ?>layout/npc_evento/combate/<?php echo $rNPC['id'] ?>.jpg" <?php echo$morto;?> />
			</div>
	<?php endwhile; ?>		
		</div>
<?php } ?>

</div>