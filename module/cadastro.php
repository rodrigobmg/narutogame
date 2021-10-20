<form name="fCadastro" id="fCadastro" method="post" onsubmit="return false">
<div class="titulo-secao"><p><?php echo t('cadastro.ca1')?></p></div>
<?php msg('1',''.t('cadastro.ca2').'', ''.t('cadastro.ca3').'');?>
<div id="cnMensagem"></div>
<div id="cnCadastro" style="float:left; clear:left">
  <table width="730" border="0" cellpadding="3" cellspacing="0" id="cadastro">
	<tr>
	  <td align="left">* <?php echo t('cadastro.ca4')?></td>
	  <td width="50%" colspan="4" align="left">&nbsp;</td>
	</tr>
	<tr>
	  <td colspan="5" align="left"><input name="nome" type="text" id="nome" maxlength="40" style="width: 716px"/></td>
	</tr>
	<tr>
	  <td align="left">* Email </td>
	  <td colspan="4" align="left">* <?php echo t('cadastro.ca5')?> </td>
	</tr>
	<tr>
	  <td align="left"><input name="email" type="text" id="email"  style="width: 350px;" maxlength="50" /></td>
	  <td colspan="4" align="left"><input name="email_confirmacao" type="text" id="email_confirmacao" style="width: 350px;" maxlength="50" /></td>
	</tr>
	<tr>
	  <td width="50%" align="left">* <?php echo t('cadastro.ca6')?></td>
	  <td colspan="4" align="left">* <?php echo t('cadastro.ca7')?></td>
	</tr>
	<tr>
	  <td align="left"><input autocomplete="off" name="senha" type="password" id="senha" style="width: 350px;" maxlength="50" /></td>
	  <td colspan="4" align="left"><input autocomplete="off" name="confirma_senha" type="password" id="confirma_senha" style="width: 350px;" maxlength="50" /></td>
	</tr>
	<tr>
	  <td align="left">*<?php echo t('cadastro.ca8')?></td>
	  <td colspan="4" align="left">*<?php echo t('cadastro.ca9')?></td>
	</tr>
	<tr>
	  <td width="50%" align="left">
	  <select name="pais" style="width: 350px;">
		<?php
			$qPais = Recordset::query("SELECT * FROM cadastro_pais");
			
			while($rPais = $qPais->row_array()) {
				$sel = $rPais['id'] == 23 ? "selected='selected'" : "";
				
				echo "<option value='" . $rPais['id'] . "' $sel>" . $rPais['nome'] . "</option>";
			}
		?>
	  </select></td>
	  <td colspan="4" align="left">
		  <select name="sexo" id="sexo" style="width: 350px;">
			<option value="0"><?php echo t('cadastro.ca10')?></option>
			<option value="1"><?php echo t('cadastro.ca11')?></option>
		  </select>
	  </td>
	</tr>
	<tr>
	  <td colspan="5" align="left">&nbsp;</td>
	</tr>
	<tr>
	  <td height="10" colspan="5" align="left"></td>
	</tr>
	<tr>
	  <td colspan="5" align="left"><strong><?php echo t('cadastro.ca12')?></strong></td>
	</tr>
	<tr>
	  <td height="10" colspan="5" align="left">
		<textarea name="textarea" id="textarea" style="width: 716px" rows="12" readonly="readonly">
		<?php echo t('cadastro.ca13')?>
		</textarea>
	   </td>
	</tr>
	<tr>
	  <td colspan="5" align="left"><input type="checkbox" name="aceite" id="aceite" />
		<strong><?php echo t('cadastro.ca14')?> </strong><strong><a href="index.php?secao=termos_uso" target="_blank" class="linkTopo"><?php echo t('cadastro.ca15')?></a></strong></td>
	</tr>
	<tr>
	  <td colspan="5" align="left"><input type="checkbox" name="aceite2" id="aceite2" />
		<strong><?php echo t('cadastro.ca14')?> </strong><strong><a href="index.php?secao=politica_privacidade" target="_blank" class="linkTopo"><?php echo t('cadastro.ca16')?></a></strong></td>
	</tr>
	<tr>
	  <td colspan="5" align="left"><input type="checkbox" name="aceite3" id="aceite3" />
		<strong><?php echo t('cadastro.ca14')?> <a href="index.php?secao=regras_punicoes" target="_blank" class="linkTopo"><?php echo t('cadastro.ca17')?></a></strong></td>
	</tr>
	<tr>
	  <td colspan="5" align="left"><input type="checkbox" name="aceite4" id="aceite4" />
		<strong><span dir="ltr" id=":2aq"><span dir="ltr" id=":284"><?php echo t('cadastro.ca18')?></span></span></strong></td>
	</tr>
	<tr>
	  <td height="10" colspan="5" align="left"></td>
	</tr>
	<tr>
	  <td colspan="5" align="left">* <?php echo t('cadastro.ca19')?></td>
	</tr>
	<tr>
	  <td colspan="5" valign="middle" align="left"><img src="index.php?acao=captcha&amp;_cache=<?= date("YmdHis") ?>"  />
		<input name="captcha" type="text" id="captcha" size="5" maxlength="5" style="position:absolute; margin-top:1px; margin-left:2px;"/>
		<br />
		<?php echo t('cadastro.ca20')?></td>
	</tr>
	<tr>
	  <td colspan="5" align="center"><a class="button" onclick="validarCadastro()"><?php echo t('botoes.cadastrar')?></a></td>
	</tr>
	<tr>
	  <td colspan="5" align="right">&nbsp;</td>
	</tr>
  </table>
</div>
</form>