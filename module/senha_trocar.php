<?php
	$rU 				= Recordset::query("SELECT * FROM global.user WHERE id=" . $_SESSION['usuario']['id'])->row_array();
	$_SESSION['el_js_func_name'] = $js_function = "f" . md5(rand(1, 512384));
	$_SESSION['el_js_field_name'] = $js_field_name = "f" . md5(rand(1, 512384));
?>
<script type="text/javascript">
function <?= $js_function ?>() {
	if((!$('#senha_atual').val() || !$('#senha').val() || !$('#senhab').val())) {
		alert("<?php echo t('senha_trocar.preencher_campos'); ?>");
		return false;
	}
	
	if($('#senha').val() != $('#senhab').val()) {
		alert("<?php echo t('senha_trocar.senhas_incorretas'); ?>");
		return false;
	}
	
	$('#fSenha')[0].submit();
}
</script>

<div class="titulo-secao"><p><?php echo t('titulos.troque_senha'); ?></p></div>
<br />
<div id="cnBase" class="direita">
<?php
	if(isset($_GET['ok'])) {
		msg('5',''.t('senha_trocar.parabens').'', ''.t('senha_trocar.senha_trocada').'');
	}
	if(isset($_GET['e'])) {
		msg('4',''.t('senha_trocar.problema').'', ''.t('senha_trocar.senha_invalida').'.');		
	}
?>
  <form action="?acao=senha_trocar" method="post" name="fSenha" id="fSenha" onsubmit="return false">
    <input type="hidden" name="<?= $js_field_name ?>" value="<?= encode(1) ?>" />
<!-- Mensagem nos Topos das Seções -->
	<?php msg('6',''.t('senha_trocar.msg_titulo').'', ''.t('senha_trocar.msg_desc').'');?>
<!-- Mensagem nos Topos das Seções -->
<?php if($rU['password']): ?>
    <table width="730" border="0" cellpadding="3" id="cadastro">
    	<tr>
    		<td width="33%" align="left"><span class="cinza" style="font-size: 13px"><?php echo t('senha_trocar.senha_atual'); ?></span><br />
    			<input name="senha_atual" type="password" id="senha_atual" style="width: 210px;" />
			</td>
    		<td width="33%" align="left"><span class="laranja" style="font-size: 13px"><?php echo t('senha_trocar.nova_senha'); ?></span><br />
    			<input name="senha" type="password" id="senha" style="width: 210px;"/>
    		</td>
    		<td width="33%" align="left"><span class="laranja" style="font-size: 13px"><?php echo t('senha_trocar.redigitar_senha'); ?></span><br />
    			<input name="senhab" type="password" id="senhab"  style="width: 210px;"/>
    		</td>
    		</tr>
    	<tr>
    		<td colspan="3"></td>
    		</tr>
    	<tr>
    		<td colspan="3" align="center"><br />
    			<br /><input role="button" class="button" type="button" onclick="<?= $js_function ?>()" value="<?php echo t('senha_trocar.alterar_senha'); ?>" /></td>
    		</tr>
    	</table>
    <br />
  </form>
</div>
<?php else: ?>
	<strong>Como sua conta está somente vinculada ao Facebook você não utiliza senha dentro do jogo, apenas sua senha do Facebook.</strong>
<?php endif ?>
