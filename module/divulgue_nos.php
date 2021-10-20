<?php
	$rParceiros = Recordset::query("SELECT * FROM parceiros WHERE ativo = 1 ORDER BY id");
?>
<div class="titulo-secao"><p><?php echo t('divulgue_nos.d1')?></p></div>
<table width="730" border="0" cellpadding="4" cellspacing="0">
	<tr >
		<td align="center"><embed height="90" width="728" src="<?php echo img() ?>layout/banners/banner_728x90.swf" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash"></embed>
			<br />
			(<?php echo t('divulgue_nos.d8')?> 728x90 pixels)</td>
	</tr>
	<tr >
		<td align="center"><textarea name="textarea" id="textarea" cols="60" rows="5"><embed height="90" width="728" src="<?php echo img() ?>layout/banners/banner_728x90.swf" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash"></embed>
		</textarea></td>
	</tr>
	<tr >
    	<td align="center">&nbsp;</td>
   	</tr>
    <tr >
    	<td align="center"><img src="<?php echo img() ?>layout/banners/Banner2.jpg" border="0" alt="<?php echo t('divulgue_nos.d3')?>"/><br />
   		(<?php echo t('divulgue_nos.d8')?> 728x90 pixels)</td>
   	</tr>
    <tr >
      <td align="center"><textarea name="textarea4" id="textarea4" cols="60" rows="5"><a href="http://narutogame.com.br/index.php?secao=cadastro&ref=divulgue-nos" class="linksSite" title="<?php echo t('divulgue_nos.d3')?>" target="_blank"><img src="<?php echo img() ?>banners/Banner2.jpg" alt="<?php echo t('divulgue_nos.d3')?>" border="0"/></a></textarea></td>
    </tr>
    <tr >
      <td align="center">&nbsp;</td>
    </tr>
    <tr >
      <td align="center"><img src="<?php echo img() ?>layout/banners/naruto-game-post.jpg" border="0" alt="<?php echo t('divulgue_nos.d3')?>"/><br />
        (<?php echo t('divulgue_nos.d8')?> 470x275 pixels)</td>
    </tr>
    <tr >
      <td align="center"><textarea name="textarea3" id="textarea3" cols="60" rows="5"><a href="http://narutogame.com.br/index.php?secao=cadastro&ref=divulgue-nos" class="linksSite" title="<?php echo t('divulgue_nos.d3')?>" target="_blank"><img src="<?php echo img() ?>layout/banners/naruto-game-post.jpg" alt="<?php echo t('divulgue_nos.d3')?>" border="0"/></a></textarea></td>
    </tr>
    <tr >
      <td align="center">&nbsp;</td>
    </tr>
    <tr >
    	<td align="center"><img src="<?php echo img() ?>layout/banners/narutogame.gif" border="0" alt="Naruto Game"/><br />
    		(<?php echo t('divulgue_nos.d8')?> 88x31 pixels)</td>
   	</tr>
    <tr >
    	<td align="center"><textarea name="textarea2" id="textarea2" cols="60" rows="5"><a href="http://narutogame.com.br/index.php?secao=cadastro&amp;ref=divulgue-nos" class="linksSite" title="<?php echo t('divulgue_nos.d3')?>" target="_blank"><img src="<?php echo img() ?>layout/banners/narutogame.gif" alt="<?php echo t('divulgue_nos.d3')?>" border="0"/></a></textarea></td>
   	</tr>
    <tr >
    	<td align="center">&nbsp;</td>
   	</tr>
    <tr >
    	<td align="center">&nbsp;</td>
   	</tr>
</table>