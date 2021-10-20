<div class="titulo-secao"><p><?php echo t('titulos.esqueci_senha') ?></p></div>
<?php
	if(isset($_GET['e']) && $_GET['e']) {
		switch($_GET['e']) {
			case 1:
				$msg = "".t('recupera_senha.cod_ninja')."";
			
				break;
		}
		
		msg('4',''.t('senha_trocar.problema').'', ''.$msg.'');
			
	}	
?>
<div id="cnBase">
  <?php
		$change_pass = false;
	
		if(isset($_GET['token']) && isset($_GET['u']) && $_GET['token'] && $_GET['u']) {
			if(Recordset::query("SELECT id FROM global.user WHERE id=" . (int)$_GET['u'] . " AND `key`='" . addslashes($_GET['token']) . "'")->num_rows) {
				$change_pass = true;
			}
		}
    
		if(!$change_pass) {
	?>
	<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/3.png); background-repeat: no-repeat;">
		   <form name="fRecuperaSenha" id="fRecuperaSenha" onsubmit="return false;">
			<p>
				<?php echo t('recupera_senha.msg');?><br /><br />
				<strong class="laranja"><?php echo t('recupera_senha.digite_email');?><br />
				<input name="txtEmailRecuperarSenha" type="text" style="width: 200px" /><br /><br />
				<?php echo t('recupera_senha.codigo_ver');?><br />
				<input name="txtCaptchaRecuperar" type="text" id="txtCaptchaRecuperar" style="width: 200px" /><span style="position: absolute; margin-top: -3px; margin-left: 10px;"><img src="?acao=captcha&amp;ss=1&amp;cache=<?php echo date("YmdHis")?>" /></span><br /><br />

				<input type="button" class="button" value="Recuperar Senha" onclick="doRecuperaSenha()" />
			</p>
		  </form>	
		</div>		
	</div>
</div>
  <div id="cnDados" >
  	
    <p class="p_style" align="left"></p><br />

      <table width="765" border="0" cellpadding="0" cellspacing="0" id="cadastro">
        <tr>
          <td width="50%" align="left">
          	<br />
          </strong></td>
          <td width="50%" colspan="2" align="left"><strong class="verde">
          </strong></td>
          </tr>
        <tr>
          <td width="50%" align="left"></td>
          <td width="25%" align="left"></td>
          <td width="25%" align="left"></td>
          </tr>
        <tr>
        	<td height="10" colspan="3" align="left">&nbsp;</td>
        	</tr>
        <tr>
          <td colspan="3" align="center"><br /></td>
        </tr>
      </table>
    </form>
  </div>
  <?php
		} else {
	?>
  <script type="text/javascript">
        	function doSenha() {
				if($("#r_senha").val().length < 6) {
					alert("<?php echo t('recupera_senha.senha_minima');?>");
					return;
				}
				
				if($("#r_senha").val() != $("#r_senhab").val()) {
					alert("<?php echo t('recupera_senha.senha_dif');?>");
					return;
				}
				
				$.ajax({
					type: "post",
					url: "?acao=recuperar_senha_recuperar",
					data: $("#fSenha").serialize(),
					dataType: "script"
				});
			}
        </script>
  <div id="cnDados">
	<?php msg('6',''.t('recupera_senha.senha_ok').'', ''.t('recupera_senha.senha_ok2').'');?>
    <br />
    <form id="fSenha" method="post" onsubmit="return false;" style="float:left; clear:both">
      <input type="hidden" name="token" value="<?php echo $_GET['token'] ?>" />
      <input type="hidden" name="u" value="<?php echo $_GET['u'] ?>" />
      <input type="hidden" name="r" value="1" />
      <table width="765" border="0" cellpadding="0" cellspacing="0" id="cadastro">
        <tr>
          <td width="33%" align="left"><b class="verde"<?php echo t('recupera_senha.nova_senha');?>></b><br /><br /></td>
          <td width="33%" align="left"><b class="laranja"><?php echo t('recupera_senha.confirme_senha');?></b><br /></td>
          <td colspan="2" align="left"><b class="azul"><?php echo t('recupera_senha.cod_segur');?></b><br /></td>
          </tr>
        <tr>
          <td width="33%" align="left"><input type="password" id="r_senha" name="r_senha" /></td>
          <td width="33%" align="left"><input type="password" id="r_senhab" name="r_senhab" /></td>
          <td width="15%" align="left"><input name="txtCaptchaRecuperar" type="text" id="txtCaptchaRecuperar2" style="width: 116px" /></td>
          <td width="15%" align="left"><img src="?acao=captcha&amp;ss=<?= urlencode(encode("securimage_recuperar_senha")) ?>&amp;cache=<?= date("YmdHis")?>" /></td>
        </tr>
        <tr>
        	<td colspan="4" align="center"><br /><input type="submit" class="button" value="Confirmar alteração >" onclick="doSenha()" /></td>
        	</tr>
      </table>
    </form>
  </div>
  <?php
		}
	?>
  <div id="cnMensagem"></div>
</div>
