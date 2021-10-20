<?php
	$_SESSION['cl_js_func_name']	= $js_function		= "f" . md5(rand(1, 512384));
	$_SESSION['cl_js_field_name']	= $js_field_name	= "f" . md5(rand(0, 512384));
	$_SESSION['cl_js_funcb_name']	= $js_functionb	= "fb" . md5(rand(1, 512384));

	$pay_key_0 = $_SESSION['pay_key_0'] = round(rand(1, 999999)); // Grana
	$pay_key_1 = $_SESSION['pay_key_1'] = round(rand(1, 999999)); // Coin
?>
<script type="text/javascript">
	function <?php echo $js_function ?>() {
		if(!$("#nome").val().replace(/[\s]+$/)) {
			alert("<?php echo t('equipe_criar.ec1')?>");
			return false;
		}
		
		if($("#nome").val().length <=2) {
			alert("<?php echo t('equipe_criar.ec9')?>");
			return false;
		}

		if(!$("#<?php echo $js_field_name ?>").val()) {
			alert("<?php echo t('equipe_criar.ec2')?>");
			return false;
		}
		
		$.ajax({
			url: 'index.php?acao=equipe_criar_final',
			dataType: 'script',
			type: 'post',
			data: $("#fCriarEquipe").serialize()
		});		
	}

	function <?php echo $js_functionb ?>(km) {
		$("#<?php echo $js_field_name ?>").val(!km ? '<?php echo $pay_key_0 ?>' : '<?php echo $pay_key_1 ?>');
	}
</script>
<div class="titulo-secao"><p><?php echo t('equipe_criar.ec3')?></p></div>
<div id="HOTWordsTxt" name="HOTWordsTxt">
<form name="fCriarEquipe" id="fCriarEquipe" onsubmit="return false;">
<input type="hidden" name="<?php echo $js_field_name ?>" id="<?php echo $js_field_name ?>" value="" />

<?php
	if(isset($_GET['e']) && $_GET['e']) {
		msg(1, ''.t('academia_jutsu.problema').'',''.t('equipe_criar.ec4').'');
	}	

	if(isset($_GET['p']) && $_GET['p']) {
		msg(1, ''.t('academia_jutsu.problema').'',''.t('equipe_criar.ec5').'');
	}	
?>

	<?php msg(3, ''.t('equipe_criar.ec6').'',''.t('equipe_criar.ec7').''); ?>        
    <br />
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
<br /><br />
        <table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
  <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="75" align="center">&nbsp;</td>
                <td width="295" align="left"><b style="color:#FFFFFF"><?php echo t('geral.criar_equipe')?></b></td>
                <td width="360" align="left"><b style="color:#FFFFFF"><?php echo t('geral.requerimentos')?></b></td>
			</tr>
            </table></td>
          </tr>
      </table>
        <table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr >
            <td width="82" rowspan="4" align="center">&nbsp;</td>
            <td width="304" align="left"><p><b><?php echo t('geral.nome')?></b></p></td>
            <td width="237" rowspan="4" align="left" nowrap="nowrap"><input type="radio" name="pm_mode" onclick="<?php echo $js_functionb ?>(0)" id="pm_ryou" />
              <?php echo t('equipe_criar.ec8')?><br />
              <input type="radio" name="pm_mode" onclick="<?php echo $js_functionb ?>(1)" />
              3 <?php echo t('geral.creditos')?> </td>
            <td width="142" rowspan="4" align="center" id="cnCriarCla">
            <a class="button" onclick="<?php echo $js_function ?>()"><?php echo t('botoes.criar_equipe')?></a>
            </td>
          </tr>
          <tr >
            <td align="left"><input name="nome" type="text" id="nome" size="35" maxlength="25" /></td>
          </tr>
        </table>
</form>
</div>
