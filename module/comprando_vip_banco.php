<?php
	$vip_field_postkey = $_SESSION['vip_field_postkey'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));
	$vip_field_postkey_value = $_SESSION['vip_field_postkey_value'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));

	$vip_form_id = $_SESSION['vip_form_id'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));
	$vip_form_idb = $_SESSION['vip_form_idb'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));

	$vip_js_function = $_SESSION['vip_js_function'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));
	$vip_js_functionb = $_SESSION['vip_js_functionb'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));
?>
<table width="765" border="0" cellpadding="0" cellspacing="0">
  <tr >
    <td colspan="2"><img src="<?php echo img() ?>bt_confirme_pagamento.jpg" alt="Confirme seu Pagamento - Naruto Game" /></td>
  </tr>
</table>
<br />
<script type="text/javascript">
	function <?= $vip_js_function ?>() {
		
		$("#<?= $vip_form_id ?>").submit();
	}

	function <?= $vip_js_functionb ?>() {
		if(!confirm("Quer realmente cancelar essa transação de compra VIP ?")) {
			return false;
		}
		
		$("#<?= $vip_form_idb ?>").submit();
	}
</script>
<form method="post" action="?acao=comprando_vip_finaliza" id="<?= $vip_form_id ?>" name="<?= $vip_form_id ?>" onsubmit="return false" enctype="multipart/form-data">
  <input type="hidden" name="<?= $vip_field_postkey ?>" value="<?= $vip_field_postkey_value ?>" />
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td class="subtitulo-home">
	  	<table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="55" align="center">&nbsp;</td>
            <td width="650" align="left"><b style="color:#FFFFFF">Confirma&ccedil;&atilde;o de Dep&oacute;sito / Transfer&ecirc;ncia Banc&aacute;ria</b></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <table width="730" border="0" cellpadding="4" cellspacing="0" style="color:#717171">
    <tr>
      <td>Nesta p&aacute;gina, voc&ecirc; ir&aacute; confirmar o pagamento atr&aacute;ves de Dep&oacute;sito em Conta e/ou Transfer&ecirc;ncia Online. <br /></td>
    </tr>
  </table>
  <br />
    <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td height="48" background="http://narutogame.com.br/images/bg_aba.jpg"><table width="765" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="55" align="center">&nbsp;</td>
            <td width="650" align="left"><b style="color:#FFFFFF">Bancos disponiveis para depósito</b></td>
          </tr>
        </table></td>
    </tr>
  </table>

  <table width="95%" border="0" cellpadding="4" cellspacing="0" style="color:#717171">
    <tr>
      <td width="25%"><p><strong style="color:#af9d6b">Banco do Brasil</strong><br />
          Carlos R. da Rocha<br />
          Ag&ecirc;ncia: 0303-4<br />
          Conta: 21568-6<br />
          Conta Corrente<br />
        </p></td>
      <td width="25%"><strong style="color:#af9d6b">Banco Ita&uacute;</strong><br />
        Carlos R. da Rocha<br />
        Ag&ecirc;ncia: 2923<br />
        Conta: 15475-7<br />
        Conta Corrente</td>
      <td width="25%">&nbsp;</td>
      <td width="25%">&nbsp;</td>
    </tr>
  </table>
  <br />
  <br />
  <table width="80%" border="0" cellpadding="4" cellspacing="0"  style="color:#717171">
    <tr >
      <td><b style="font-size:13px; color:#af9d6b">Insira os dados abaixo para comprova&ccedil;&atilde;o do pagamento VIP do Naruto Game</b></td>
    </tr>
    <tr >
      <td><textarea name="mensagem" cols="80%" rows="13" id="mensagem">
Dados de seu Deposito
Digite aqui a data e hora que esta no seu comprovante:
Digite aqui o valor do seu comprovante:
Digite aqui o numero da transa&ccedil;&atilde;o do seu comprovante:
Digite aqui o numero do terminal / caixa do seu comprovante:

Dados de sua Transfer&ecirc;ncia Online
Digite aqui o n&uacute;mero da sua ag&ecirc;ncia:
Digite aqui o n&uacute;mero da sua conta:
Digite aqui o n&uacute;mero do seu comprovante ou identifica&ccedil;&atilde;o:
Digite aqui o endere&ccedil;o da url onde esta o seu comprovante:

Digite aqui o nome do banco que voc&ecirc; depositou:
            </textarea></td>
    <tr >
      <td>&nbsp;</td>
    <tr>
      <td><!--<input type="file" size="70%" name="comprovante" />--></td>
    </tr>
  </table>
  <br />
  <br />
  <table width="765" border="0" cellpadding="2" cellspacing="0">
    <tr>
      <td height="3" align="center"><input type="image" src="<?php echo img() ?>bt_finalizar_compra.gif" onclick="<?= $vip_js_function ?>()" />
        <input type="image" src="<?php echo img() ?>bt_cancelar.gif"  onclick="<?= $vip_js_functionb ?>()" /></td>
    </tr>
  </table>
</form>
<form method="post" action="?acao=comprando_vip_cancela" id="<?= $vip_form_idb ?>" name="<?= $vip_form_idb ?>">
  <input type="hidden" name="<?= $vip_field_postkeyb ?>" value="<?= $vip_field_postkey_valueb ?>" />
</form>
<br />
