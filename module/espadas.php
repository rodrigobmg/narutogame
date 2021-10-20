<?php
	$vip1 = false;
	$vip2 = false;
	$vip3 = false;
	
	if($basePlayer->hasItem(21757)){
		$vip1 = true;
	}
	
	if($basePlayer->hasItem(21758)){
		$vip2 = true;
	}
	
	if($basePlayer->hasItem(21759)){
		$vip3 = true;
	}
?>
<?php if(!$basePlayer->tutorial()->espadas){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 13,
	 
	  steps: [
	  {
		element: ".msg_gai",
		title: "<?php echo t("tutorial.titulos.espadas.1");?>",
		content: "<?php echo t("tutorial.mensagens.espadas.1");?>",
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
<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p><?php echo t('menus.espadas')?></p></div>
<?php msg('3',''.t('bijuus.b8').'', ''.t('bijuus.b9').'');?>
<br/>
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
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="20" align="center">&nbsp;</td>
          <td width="710" align="center" class="bold_branco"><?php echo t('bijuus.b4')?></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="730" border="0" cellpadding="4" cellspacing="1">
        <tr class="cor_sim">
          <td height="40" colspan="10" align="center">
           <?php
				
            $query = Recordset::query("
				SELECT
					*
				FROM
					player_flags
				WHERE 
					id_player = ". $basePlayer->id .";
				
				");
			
            $rPontos = $query->row_array();
    			
        	?>
          		<p  class="p_style">
                	<?php echo t('bijuus.b5')?>
                    <br /><br />
                    <?php echo t('bijuus.b6')?> <strong class="verde"><?php echo $rPontos['sorte_bijuu'];?> <?php echo t('atributos.a21')?></strong>
                </p>
          </td>
        </tr>
        <tr class="cor_nao laranja">
          <td width="73" height="40" align="center">1 ~ 10</td>
          <td width="73" align="center">11 ~ 20</td>
          <td width="73" align="center">21 ~ 30</td>
          <td width="73" align="center">31 ~ 40</td>
          <td width="73" align="center">41 ~ 50</td>
          <td width="73" align="center">51 ~ 60</td>
          <td width="73" align="center">61 ~ 70</td>
          <td width="73" align="center">71 ~ 80</td>
          <td width="73" align="center">81 ~ 90</td>
          <td width="73" align="center">91 ~ 100</td>
        </tr>
        <tr class="cor_sim">
            <?php

			$query = Recordset::query("
				SELECT
					SUM(CASE WHEN sorte_bijuu BETWEEN 1 AND 10 THEN 1 ELSE 0 END) AS a,
					SUM(CASE WHEN sorte_bijuu BETWEEN 11 AND 20 THEN 1 ELSE 0 END) AS b,
					SUM(CASE WHEN sorte_bijuu BETWEEN 21 AND 30 THEN 1 ELSE 0 END) AS c,
					SUM(CASE WHEN sorte_bijuu BETWEEN 31 AND 40 THEN 1 ELSE 0 END) AS d,
					SUM(CASE WHEN sorte_bijuu BETWEEN 41 AND 50 THEN 1 ELSE 0 END) AS e,
					SUM(CASE WHEN sorte_bijuu BETWEEN 51 AND 60 THEN 1 ELSE 0 END) AS f,
					SUM(CASE WHEN sorte_bijuu BETWEEN 61 AND 70 THEN 1 ELSE 0 END) AS g,
					SUM(CASE WHEN sorte_bijuu BETWEEN 71 AND 80 THEN 1 ELSE 0 END) AS h,
					SUM(CASE WHEN sorte_bijuu BETWEEN 81 AND 90 THEN 1 ELSE 0 END) AS i,
					SUM(CASE WHEN sorte_bijuu BETWEEN 91 AND 100 THEN 1 ELSE 0 END) AS j,
					SUM(CASE WHEN sorte_bijuu BETWEEN 101 AND 110 THEN 1 ELSE 0 END) AS l,
					SUM(CASE WHEN sorte_bijuu BETWEEN 111 AND 120 THEN 1 ELSE 0 END) AS m,
					SUM(CASE WHEN sorte_bijuu BETWEEN 121 AND 130 THEN 1 ELSE 0 END) AS n,
					SUM(CASE WHEN sorte_bijuu BETWEEN 131 AND 140 THEN 1 ELSE 0 END) AS o,
					SUM(CASE WHEN sorte_bijuu BETWEEN 141 AND 150 THEN 1 ELSE 0 END) AS p,
					SUM(CASE WHEN sorte_bijuu BETWEEN 151 AND 160 THEN 1 ELSE 0 END) AS q,
					SUM(CASE WHEN sorte_bijuu BETWEEN 161 AND 170 THEN 1 ELSE 0 END) AS r,
					SUM(CASE WHEN sorte_bijuu BETWEEN 171 AND 180 THEN 1 ELSE 0 END) AS s,
					SUM(CASE WHEN sorte_bijuu BETWEEN 181 AND 190 THEN 1 ELSE 0 END) AS t,
					SUM(CASE WHEN sorte_bijuu BETWEEN 191 AND 200 THEN 1 ELSE 0 END) AS u
					
				FROM
					player_flags;
				
				");
				
            
			foreach($query->result_array() as $r) {
    			
        	?>
          <td align="center"><?php echo $r['a'];?><br /><?php echo t('bijuus.b7')?></td>
          <td align="center"><?php echo $r['b'];?><br /><?php echo t('bijuus.b7')?></td>
          <td align="center"><?php echo $r['c'];?><br /><?php echo t('bijuus.b7')?></td>
          <td align="center"><?php echo $r['d'];?><br /><?php echo t('bijuus.b7')?></td>
          <td align="center"><?php echo $r['e'];?><br /><?php echo t('bijuus.b7')?></td>
          <td align="center"><?php echo $r['f'];?><br /><?php echo t('bijuus.b7')?></td>
          <td align="center"><?php echo $r['g'];?><br /><?php echo t('bijuus.b7')?></td>
          <td align="center"><?php echo $r['h'];?><br /><?php echo t('bijuus.b7')?></td>
          <td align="center"><?php echo $r['i'];?><br /><?php echo t('bijuus.b7')?></td>
          <td align="center"><?php echo $r['j'];?><br /><?php echo t('bijuus.b7')?></td>
          	<?php
				}
			?>
        </tr>
        <tr class="cor_nao laranja">
        	<td height="40" align="center">101 ~ 110</td>
        	<td align="center">111 ~ 120</td>
        	<td align="center">121 ~ 130</td>
        	<td align="center">131 ~ 140</td>
        	<td align="center">141 ~ 150</td>
        	<td align="center">151 ~ 160</td>
        	<td align="center">161 ~ 170</td>
        	<td align="center">171 ~ 180</td>
        	<td align="center">181 ~ 190</td>
        	<td align="center">191 ~ 200</td>
       	</tr>
        <tr class="cor_sim">
        	<?php
				
			foreach($query->result_array() as $r) {
    			
        	?>
        	<td align="center"><?php echo $r['l'];?><br />
        		<?php echo t('bijuus.b7')?></td>
        	<td align="center"><?php echo $r['m'];?><br />
        		<?php echo t('bijuus.b7')?></td>
        	<td align="center"><?php echo $r['n'];?><br />
        		<?php echo t('bijuus.b7')?></td>
        	<td align="center"><?php echo $r['o'];?><br />
        		<?php echo t('bijuus.b7')?></td>
        	<td align="center"><?php echo $r['p'];?><br />
        		<?php echo t('bijuus.b7')?></td>
        	<td align="center"><?php echo $r['q'];?><br />
        		<?php echo t('bijuus.b7')?></td>
        	<td align="center"><?php echo $r['r'];?><br />
        		<?php echo t('bijuus.b7')?></td>
        	<td align="center"><?php echo $r['s'];?><br />
        		<?php echo t('bijuus.b7')?></td>
        	<td align="center"><?php echo $r['t'];?><br />
        		<?php echo t('bijuus.b7')?></td>
        	<td align="center"><?php echo $r['u'];?><br />
        		<?php echo t('bijuus.b7')?></td>
        	<?php
				}
			?>
       	</tr>
      </table>
<br />
</td>
  </tr>
  <tr>
  	<td align="center">
<table width="730" border="0" align="left" cellpadding="4" cellspacing="0" class="claObj" id="tbCla_<?php echo encode(isset($rCla['id'])); ?>">
	<!--
	<tr>
	  <td width="48" align="center" background="<?php echo img() ?>bg_sessao2.jpg">&nbsp;</td>
	  <td width="297" align="center" background="<?php echo img() ?>bg_fundo.jpg"><b style="color:#ffffff">Nome</b></td>
	  <td width="219" height="34" align="center" background="<?php echo img() ?>bg_fundo.jpg"><b style="color:#ffffff">Bonus</b></td>
	</tr>-->
	<?php
		$qItem = Recordset::query("
			SELECT
				a.id,
				a.nome_". Locale::get()." as nome,
				a.imagem,
				a.descricao_". Locale::get()." as descricao,
				a.req_level,
				a.req_graduacao,
				b.nome_".Locale::get()." AS nome_req_graduacao,
				a.bonus_hp,
				a.bonus_sp,
				a.bonus_sta,
				a.req_level,
				a.req_graduacao,
				a.ordem,
				a.coin,
				a.tai,
				a.ken,
				a.nin,
				a.gen,
				a.agi,
				a.con,
				a.forc,
				a.ene,
				a.res,
				a.inte
			FROM 
				item a JOIN graduacao b ON a.req_graduacao = b.id
				JOIN item_tipo c ON c.id=a.id_tipo
			WHERE a.id_tipo=39
			ORDER by a.ordem
		");
		
		$c = 0;
		while($rItem = $qItem->row_array()) {
			$bg		= ++$c % 2 ? "class='cor_sim'" : "class='cor_nao'";			
	?>
	<tr>
	  <td <?php echo $bg ?> width="510"><img src="<?php echo img() ?>layout<?php echo LAYOUT_TEMPLATE?>/bijuus/<?php echo $rItem['id'] ?>.jpg" /></td>
	  <td width="30%" height="34" align="center" <?php echo $bg ?>>
		  <?php if($rItem['nin']): ?>
          <p style="float: left; width:44%; padding: 5px">
            <strong class="verde" style="font-size:13px"><?php echo $rItem['nin'] ?>%</strong> <img src="<?php echo img() ?>layout/icones/nin.png" /><br /><?php echo t('at.nin')?> 
          </p>
          <?php endif; ?>
          <?php if($rItem['tai']): ?>
          <p style="float: left; width:44%; padding: 5px">
            <strong class="verde" style="font-size:13px"><?php echo $rItem['tai'] ?>%</strong> <img src="<?php echo img() ?>layout/icones/tai.png" /><br /><?php echo t('at.tai')?>  
          </p>
          <?php endif; ?>
		  <?php if($rItem['ken']): ?>
          <p style="float: left; width:44%; padding: 5px">
            <strong class="verde" style="font-size:13px"><?php echo $rItem['ken'] ?>%</strong> <img src="<?php echo img() ?>layout/icones/ken.png" /><br /><?php echo t('at.ken')?>  
          </p>
          <?php endif; ?>
          <?php if($rItem['gen']): ?>
          <p style="float: left; width:44%; padding: 5px">
            <strong class="verde" style="font-size:13px"><?php echo $rItem['gen'] ?>%</strong> <img src="<?php echo img() ?>layout/icones/gen.png" /><br /><?php echo t('at.gen')?>  
          </p>
          <?php endif; ?>
          <?php if($rItem['ene']): ?>
          <p style="float: left; width:44%; padding: 5px">
            <strong class="verde" style="font-size:13px"><?php echo $rItem['ene'] ?>%</strong> <img src="<?php echo img() ?>layout/icones/ene.png" /><br /><?php echo t('at.ene')?>  
          </p>
          <?php endif; ?>
          <?php if($rItem['forc']): ?>
          <p style="float: left; width:44%; padding: 5px">
            <strong class="verde" style="font-size:13px"><?php echo $rItem['forc'] ?>%</strong> <img src="<?php echo img() ?>layout/icones/forc.png" /><br /><?php echo t('at.for')?>  
          </p>
          <?php endif; ?>
          <?php if($rItem['inte']): ?>
          <p style="float: left; width:44%; padding: 5px">
            <strong class="verde" style="font-size:13px"><?php echo $rItem['inte'] ?>%</strong> <img src="<?php echo img() ?>layout/icones/inte.png" /><br /><?php echo t('at.int')?>  
          </p>
          <?php endif; ?>
          <?php if($rItem['con']): ?>
          <p style="float: left; width:44%; padding: 5px">
            <strong class="verde" style="font-size:13px"><?php echo $rItem['con'] ?>%</strong> <img src="<?php echo img() ?>layout/icones/conhe.png" /><br /><?php echo t('at.con')?>  
          </p>
          <?php endif; ?>
          <?php if($rItem['agi']): ?>
          <p style="float: left; width:44%; padding: 5px">
            <strong class="verde" style="font-size:13px"><?php echo $rItem['agi'] ?>%</strong> <img src="<?php echo img() ?>layout/icones/agi.png" /><br /><?php echo t('at.agi')?>  
          </p>
          <?php endif; ?>
          <?php if($rItem['res']): ?>
          <p style="float: left; width:44%; padding: 5px">
            <strong class="verde" style="font-size:13px"><?php echo $rItem['res'] ?>%</strong> <img src="<?php echo img() ?>layout/icones/defense.png" /><br /><?php echo t('at.res')?>  
          </p>
          <?php endif; ?>
          <?php if($rItem['bonus_hp']): ?>
          <p style="float: left; width:44%; padding: 5px">
            <strong class="verde" style="font-size:13px"><?php echo $rItem['bonus_hp'] ?>%</strong> <img src="<?php echo img() ?>layout/icones/p_hp.png" /><br /><?php echo t('formula.hp')?>  
          </p>
          <?php endif; ?>
          <?php if($rItem['bonus_sp']): ?>
          <p style="float: left; width:44%; padding: 5px">
            <strong class="verde" style="font-size:13px"><?php echo $rItem['bonus_sp'] ?>%</strong> <img src="<?php echo img() ?>layout/icones/p_chakra.png" /><br /><?php echo t('formula.sp')?> 
          </p>
          <?php endif; ?>
          <?php if($rItem['bonus_sta']): ?>
          <p style="float: left; width:44%; padding: 5px">
            <strong class="verde" style="font-size:13px"><?php echo $rItem['bonus_sta'] ?>%</strong> <img src="<?php echo img() ?>layout/icones/p_stamina.png" /><br /><?php echo t('formula.sta')?> 
          </p>
          <?php endif; ?>
		  <br />
		  <br />
		  	<?php
				$player	= Recordset::query('
					SELECT
						a.id,
						a.level,
						a.nome,
						c.xpos,
						c.ypos,
						d.nome_'.Locale::get().' AS nome_vila,
						a.id_vila_atual,
						a.dentro_vila,
						e.nome_'.Locale::get().' AS nome_vila_atual
					
					FROM 
						player a JOIN player_item b ON b.id_player=a.id
						LEFT JOIN player_posicao c ON c.id_player=a.id
						JOIN vila d ON d.id=a.id_vila
						LEFT JOIN vila e ON e.id=a.id_vila_atual
					
					WHERE
						b.id_item=' . $rItem['id']);
			?>
		  	<?php if($player->num_rows && ($vip1 || $vip2 || $vip3)): ?>
				<?php
					$player	= $player->row_array();
				
					$level	= $player['level'];
					$name	= $player['nome'];
					$place	= $player['nome_vila'];
					$x		= $player['xpos'] * 22;
					$y		= $player['ypos'] * 22;
					
					if($player['dentro_vila']) {
						$map	= t('geral.nao');
					} else {
						if($player['id_vila_atual']) {
							$map	= $player['nome_vila_atual'];
						} else {
							$map	= t('bijuus.mundi');
						}
					}
				?>
				<br /><br />
				<a class="button" data-auto-modal="bijuu-<?php echo $rItem['id'] ?>"><?php echo t('botoes.dados_player') ?></a>
				<div style="display: none" id="bijuu-<?php echo $rItem['id'] ?>">
					<?php if($vip1 || $vip2 || $vip3): ?>
						<?php echo t('bijuus.player') ?>:
						<?php if($vip3): ?>
							<?php echo player_online($player['id'], true) ?>
						<?php endif ?>
						<?php echo $name ?> - Lvl. <?php echo $level ?>
						<br />
					<?php endif ?>
					<?php if($vip2 || $vip3): ?>
						<?php echo t('bijuus.mapa_origem') ?>: <?php echo $place ?><br />
						<?php echo t('bijuus.no_mapa') ?>: <?php echo $map ?><br />
					<?php endif ?>
					<?php if($vip3 && !$player['id_vila_atual']): ?>
						<?php echo t('bijuus.coords') ?>: X: <?php echo $x ?> / Y: <?php echo $y ?><br />
					<?php endif ?>
				</div>
			<?php endif ?>
	  </td>
	</tr>
	<tr height="4"></tr>
<?php
		}	
?>
  </table>
</div>