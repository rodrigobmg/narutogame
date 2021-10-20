<?php
	if($_POST[$_SESSION['vip_field_postkey']] != $_SESSION['vip_field_postkey_value']) {
		redirect_to("negado");
	}

	$plano = decode($_POST[$_SESSION['vip_field_plano']]);
	$pagto = decode($_POST[$_SESSION['vip_field_pagto']]);

	if(!is_numeric($plano)) {
		redirect_to("negado");
	}

	if(!is_numeric($pagto)) {
		redirect_to("negado");
	}

	$rPlano 	= Recordset::query("SELECT * FROM coin WHERE id=" . (int)$plano)->row_array();
	$transid	= Recordset::insert('coin_compra', array(
		'id_coin'		=> $plano,
		'id_pagamento'	=> $pagto,
		'id_usuario'	=> $_SESSION['usuario']['id']
	));
	$enc_str	= str_replace('%', 'Ç', urlencode(salt_encrypt(VIP_ENC_KEY_ID . ':' . $transid . ':' . $_SESSION['usuario']['id'], VIP_ENC_KEY)));

	switch($pagto) {
		case 1: // Transferencia
			Recordset::query("UPDATE global.user SET pay_lock=" . (int)$pagto . " WHERE id=" . $_SESSION['usuario']['id']);
?>
<script type="text/javascript">
	if(opener) {
		opener.location="?secao=comprando_vip_banco";		
	}
	
	window.close();
</script>
<?php
			break;
			
			
		case 2: // PagSeguro
		
		$qPlano = Recordset::query("SELECT * FROM coin WHERE id=" . (int)$plano);
		$rPlano = $qPlano->row_array();
?>
<script type="text/javascript" src="js/jquery.js"></script>
<body>
<form id="fPagSeguro" name="fPagSeguro" action="https://pagseguro.uol.com.br/security/webpagamentos/webpagto.aspx" method="post">
<input type="hidden" name="email_cobranca" value="carlosrrocha@gmail.com" />
<input type="hidden" name="tipo" value="CP" />
<input type="hidden" name="moeda" value="BRL" />
<input type="hidden" name="item_id_1" value="<?php echo encode($rPlano['id']) ?>" />
<input type="hidden" name="item_descr_1" value="<?php echo VIP_INFO_PREFIX . ' - ' . $rPlano['titulo'] ?>" />
<input type="hidden" name="item_quant_1" value="1" />
<input type="hidden" name="item_valor_1" value="<?php echo str_replace(".", "", sprintf("%1.2f", $rPlano['valor'])) ?>" />
<input type="hidden" name="ref_transacao" value="<?php echo $enc_str ?>" />
</form>
<script type="text/javascript">
		if(opener) {
			opener.location.href = "?secao=home";
		}
		
		$('#fPagSeguro').submit();
</script>

</body>
<?php
			//redirect_to("comprando_vip_pag_seguro");
			
			break;
			
		case 3: // PayPal DOLAR

		$qPlano = Recordset::query("SELECT * FROM coin WHERE id=" . (int)$plano);
		$rPlano = $qPlano->row_array();
?>
<script type="text/javascript" src="js/jquery.js"></script>

<form id="fPaypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
  <input type="hidden" name="cmd" value="_xclick">
  <input type="hidden" name="currency_code" value="USD">
  <input type="hidden" name="business" value="contato@w4dev.com.br">
  <input type="hidden" name="item_name" value="<?php echo $rPlano['titulo'] ?>">
  <input type="hidden" name="item_number" value="<?php echo encode($rPlano['id']) ?>">
  <input type="hidden" name="amount" value="<?php echo sprintf("%1.2f", $rPlano['valor_us']) ?>">
  <input type="hidden" name="first_name" value="<?php echo $_SESSION['usuario']['nome'] ?>">
  <input type="hidden" name="email" value="<?php echo $_SESSION['usuario']['email'] ?>">
  <input type="hidden" name="invoice" value="<?php echo $_SESSION['usuario']['id'] ?>:<?php echo $_SESSION['key'] ?>:<?php echo $transid ?>" />
  <input style="display: none" type="image" name="submit" border="0" src="https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online">
</form>
<script type="text/javascript">
		if(opener) {
			opener.location.href = "?secao=home";			
		}

		$('#fPaypal').submit();
</script>

</body>
<?php
			break;

		case 4: // PayPal EURO
		$qPlano = Recordset::query("SELECT * FROM coin WHERE id=" . (int)$plano);
		$rPlano = $qPlano->row_array();
?>
<script type="text/javascript" src="js/jquery.js"></script>

<form id="fPaypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
  <input type="hidden" name="cmd" value="_xclick">
  <input type="hidden" name="currency_code" value="EUR">
  <input type="hidden" name="business" value="contato@w4dev.com.br">
  <input type="hidden" name="item_name" value="<?php echo $rPlano['titulo'] ?>">
  <input type="hidden" name="item_number" value="<?php echo encode($rPlano['id']) ?>">
  <input type="hidden" name="amount" value="<?php echo sprintf("%1.2f", $rPlano['valor_eur']) ?>">
  <input type="hidden" name="first_name" value="<?php echo $_SESSION['usuario']['nome'] ?>">
  <input type="hidden" name="email" value="<?php echo $_SESSION['usuario']['email'] ?>">
  <input type="hidden" name="invoice" value="<?php echo $_SESSION['usuario']['id'] ?>:<?php echo $_SESSION['key'] ?>:<?php echo $transid ?>" />
  <input style="display: none" type="image" name="submit" border="0" src="https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online">
</form>
<script type="text/javascript">
		if(opener) {
			opener.location.href = "?secao=home";			
		}

		$('#fPaypal').submit();
</script>
<?php
			break;

		case 5: // PayPal BRL
		$qPlano = Recordset::query("SELECT * FROM coin WHERE id=" . (int)$plano);
		$rPlano = $qPlano->row_array();
?>
<script type="text/javascript" src="js/jquery.js"></script>

<form id="fPaypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
  <input type="hidden" name="cmd" value="_xclick">
  <input type="hidden" name="currency_code" value="BRL">
  <input type="hidden" name="business" value="contato@w4dev.com.br">
  <input type="hidden" name="item_name" value="<?php echo $rPlano['titulo'] ?>">
  <input type="hidden" name="item_number" value="<?php echo encode($rPlano['id']) ?>">
  <input type="hidden" name="amount" value="<?php echo sprintf("%1.2f", $rPlano['valor']) ?>">
  <input type="hidden" name="first_name" value="<?php echo $_SESSION['usuario']['nome'] ?>">
  <input type="hidden" name="email" value="<?php echo $_SESSION['usuario']['email'] ?>">
  <input type="hidden" name="invoice" value="<?php echo $_SESSION['usuario']['id'] ?>:<?php echo $_SESSION['key'] ?>:<?php echo $transid ?>" />
  <input style="display: none" type="image" name="submit" border="0" src="https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif" alt="PayPal - The safer, easier way to pay online">
</form>
<script type="text/javascript">
		if(opener) {
			opener.location.href = "?secao=home";			
		}
		
		$('#fPaypal').submit();
</script>
</body>
<?php
			break;

	}
