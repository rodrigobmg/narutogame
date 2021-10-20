<?php
	$_SESSION['cl_js_func_name'] = $js_function = "f" . md5(rand(1, 512384));
	$_SESSION['cl_js_field_name'] = $js_field_name = "f" . md5(rand(0, 512384));
	$_SESSION['cl_js_funcb_name'] = $js_functionb = "fb" . md5(rand(1, 512384));

	$pay_key_0 = $_SESSION['pay_key_0'] = round(rand(1, 999999)); // Grana
	$pay_key_1 = $_SESSION['pay_key_1'] = round(rand(1, 999999)); // Coin
?>
<style type="text/css">
#graduacoes_table {
	text-align:left;
	font-size:11px;
	font-family:tahoma;
}
#graduacoes_table ul {
	margin:0;
	padding:0;
}
#graduacoes_table li {
	line-height:150%;
}
</style>
<script type="text/javascript">
	function <?php echo $js_function ?>() {
		$.ajax({
			url:		'index.php?acao=guild_criar',
			dataType:	'script',
			type:		'post',
			data:		$("#fCriarCla").serialize()
		});		
	}
	
	function <?php echo $js_functionb ?>(km) {
		$("#<?php echo $js_field_name ?>").val(!km ? '<?php echo $pay_key_0 ?>' : '<?php echo $pay_key_1 ?>');
	}
</script>
<div class="titulo-secao"><p><?php echo t('guild_missoes_status_guild.g2')?></p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "2636360978";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Guilds -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/><br/>

<?php if(!$basePlayer->getAttribute('id_guild')): ?>
<form name="fCriarCla" id="fCriarCla" onsubmit="return false;">
<input type="hidden" name="<?php echo $js_field_name ?>" id="<?php echo $js_field_name ?>" value="" />
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="subtitulo-home">
			<table width="730" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="40" align="center">&nbsp;</td>
					<td width="122" align="center"><b style="color:#FFFFFF"><?php echo t('guilds_criar.g1')?></b></td>
					<td width="526" align="left">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="730" border="0" cellpadding="4" cellspacing="0">
      <tr>
        <td width="40" height="34" align="center">&nbsp;</td>
        <td width="336" align="left"><b><?php echo t('geral.nome')?></b><br />        	
        	<input name="nome" type="text" id="nome" size="40" maxlength="25" /></td>
        <td width="192" align="left" nowrap="nowrap">
        	<input type="radio" name="pm_mode" onclick="<?php echo $js_functionb ?>(0)" /> 
        	<?php echo t('guilds_criar.' . ($basePlayer->bonus_vila['mo_guild_grad'] ? 'g4' : 'g2'))?><br />
            <input type="radio" name="pm_mode" onclick="<?php echo $js_functionb ?>(1)" /> 
           <?php echo t('guilds_criar.g3')?></td>
        <td width="72" align="center" id="cnCriarCla">
			<a class="button" onclick="<?php echo $js_function ?>()"><?php echo t('botoes.criar') ?></a>
		</td>
      </tr>
    </table>
</form>
<br />
<?php endif; ?>
