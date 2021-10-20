<?php
	$vip_field_postkey = $_SESSION['vip_field_postkey'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));
	$vip_field_postkey_value = $_SESSION['vip_field_postkey_value'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));

	$vip_field_pagto = $_SESSION['vip_field_pagto'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));
	$vip_field_plano = $_SESSION['vip_field_plano'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));

	$vip_form_id = $_SESSION['vip_form_id'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));
	$vip_js_function = $_SESSION['vip_js_function'] = "f" . md5(round(rand(1, 99999)) . round(rand(1, 99999)));

	$is_dbl	= Recordset::query('SELECT * FROM coin_dobro WHERE NOW() BETWEEN data_ini AND data_fim');
?>

<div class="titulo-secao">
	<p><?php echo t('comprando_vip.cv1')?></p>
</div>
<script type="text/javascript">
	function <?= $vip_js_function ?>() {
		if(!$("input[name='<?= $vip_field_plano ?>']:checked").val()) {
			alert("<?php echo t('comprando_vip.cv2')?>");
			return false;
		}

		if(!$("input[name='<?= $vip_field_pagto ?>']:checked").val()) {
			alert("<?php echo t('comprando_vip.cv3')?>");
			return false;
		}
		
		if(!confirm("<?php echo t('comprando_vip.cv4')?>")) {
			return false;
		}
	}
	
	function getPlano(o) {
		$("#cnPlano").html("<?php echo t('comprando_vip.cv5')?>");
		
		$.ajax({
			url: "?acao=comprando_vip_plano",
			data: {o: o},
			type: "post",
			success: function (e) {
				$("#cnPlano").html(e);
			}
		})
	}
</script>
<?
	if(isset($_GET['d']) && $_GET['d']) {
   ?>
<!-- Mensagem nos Topos das Seções -->
	<?php msg('2',''.t('comprando_vip.cv6').'', ''.t('comprando_vip.cv7').'');?>
<!-- Mensagem nos Topos das Seções -->
<?		
	}
?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="55" align="center">&nbsp;</td>
					<td width="650" align="left"><b style="color:#FFFFFF"><?php echo t('comprando_vip.cv8')?></b></td>
				</tr>
			</table></td>
	</tr>
</table>
<table width="730" border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td align="left">
        	<p class="p_style">
        		<?php echo t('comprando_vip.cv9')?> :<br />
			</p>
			<table width="730" border="0" cellpadding="5" cellspacing="5" >
				<tr>
					<td width="33%" valign="top" class="cor_sim"><b class="laranja" style="font-size:13px"><?php echo t('comprando_vip.cv11')?> Genin </b><br />
						<?php echo t('comprando_vip.cv10')?>: R$ 10,00 <br />
						<?php echo t('comprando_vip.cv13')?>: 10 <?php echo t('comprando_vip.cv14')?> + <?php echo t('comprando_vip.cv12')?> </td>
					<td width="33%" valign="top" class="cor_sim"><b class="laranja" style="font-size:13px"><?php echo t('comprando_vip.cv11')?> Chunin </b><br />
						<?php echo t('comprando_vip.cv10')?>: R$ 20,00 <br />
						<?php echo t('comprando_vip.cv13')?>: 22 <?php echo t('comprando_vip.cv14')?> + <?php echo t('comprando_vip.cv12')?></td>
					<td width="33%" valign="top" class="cor_sim"><b class="laranja" style="font-size:13px"><?php echo t('comprando_vip.cv11')?> Jounin </b><br />
						<?php echo t('comprando_vip.cv10')?>: R$ 30,00 <br />
						<?php echo t('comprando_vip.cv13')?>: 35 <?php echo t('comprando_vip.cv14')?> + <?php echo t('comprando_vip.cv12')?></td>
				</tr>
			</table>
			<br />
			<table width="730" border="0" cellpadding="5" cellspacing="5">
				<tr>
					<td width="33%" valign="top" class="cor_sim"><b class="laranja" style="font-size:13px"><?php echo t('comprando_vip.cv11')?> ANBU </b><br />
						<?php echo t('comprando_vip.cv10')?>: R$ 40,00 <br />
						<?php echo t('comprando_vip.cv13')?>: 49 <?php echo t('comprando_vip.cv14')?> + <?php echo t('comprando_vip.cv12')?></td>
					<td width="33%" valign="top" class="cor_sim"><b class="laranja" style="font-size:13px"><?php echo t('comprando_vip.cv11')?> Sanin </b><br />
						<?php echo t('comprando_vip.cv10')?>: R$ 50,00 <br />
						<?php echo t('comprando_vip.cv13')?>: 64 <?php echo t('comprando_vip.cv14')?> + <?php echo t('comprando_vip.cv12')?></td>
					<td width="33%" valign="top" class="cor_sim"><b class="laranja" style="font-size:13px"><?php echo t('comprando_vip.cv11')?> Kage </b><br />
						<?php echo t('comprando_vip.cv10')?>: R$ 100,00 <br />
						<?php echo t('comprando_vip.cv13')?>: 154 <?php echo t('comprando_vip.cv14')?> + <?php echo t('comprando_vip.cv12')?></td>
				</tr>
			</table>
				<p class="p_style"><br />
				<?php echo t('comprando_vip.cv15')?>
				<!-- INICIO CODIGO PAGSEGURO --> 
			</p>
			<br />
			<p align="center"><img src="<?php echo img() ?>layout/btnPreferenciaCartoesBR_665x55.gif" title="Este site aceita doações com Visa, MasterCard, Diners, American Express, Hipercard, Aura, Bradesco, Itaú, Unibanco, Banco do Brasil, Banco Real, saldo em conta PagSeguro e boleto." border="0"></p>
			<br />			
			<!-- FINAL CODIGO PAGSEGURO --> 
			<p class="verde p_style"><?php echo t('comprando_vip.cv16')?></p>
			<p class="vermelho p_style">Se você quer receber seus créditos NA HORA que o pagamento é aprovado, use o PagSeguro, só use PayPal caso esteja disposto a esperar entre a hora da aprovação até 7 dias úteis.</p>
		</td>
	</tr>
</table>
<br />
<?php if($is_dbl->num_rows): ?>
<div style="width: 730px; height: 200px; background-image: url(<?php echo img('layout/banner-dobro-' . Locale::get() . '.jpg') ?>); position: relative">
	<span style="color: white; font-size: 18px; left: 310px; bottom: 38px; position: absolute">
		<?php echo date('d/m/Y', strtotime($is_dbl->row()->data_fim)) ?>
		às
		<?php echo date('H:i:s', strtotime($is_dbl->row()->data_fim)) ?>
	</span>
</div>
<br />
<br />
<?php endif ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="subtitulo-home">
			<table width="730" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="55" align="center">&nbsp;</td>
					<td width="650" align="left"><b style="color:#FFFFFF"><?php echo t('comprando_vip.cv17')?></b></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<form method="post" action="?acao=comprando_vip_compra" id="<?= $vip_form_id ?>" name="<?= $vip_form_id ?>" onsubmit="return <?= $vip_js_function ?>();">
	<input type="hidden" name="<?= $vip_field_postkey ?>" value="<?= $vip_field_postkey_value ?>" />
	<table width="730" border="0" cellpadding="2" cellspacing="0">
    <?php /*
    <!--
    <tr class="cor_nao">
      <td width="90" height="3" align="center"><img src="<?php echo img() ?>estrela.gif" width="16" height="16" /></td>
      <td width="235">Transfêrencia Banc&aacute;ria / Deposito Bancário</td>
      <td width="320">Ita&uacute;, BB, Bradesco e Nossa Caixa</td>
      <td width="95" align="center"><input type="radio" name="<?= $vip_field_pagto ?>" id="<?= $vip_field_pagto ?>" value="<?= encode(1) ?>" onclick="getPlano(1)" /></td>
    </tr>
    -->
    */
    ?>
		<tr class="cor_sim">
			<td height="3" align="center" ><img src="<?php echo img() ?>layout/estrela.gif" alt="" width="16" height="16" /></td>
			<td height="35" >PagSeguro</td>
			<td ><?php echo t('comprando_vip.cv18')?></td>
			<td align="center" ><input type="radio" name="<?= $vip_field_pagto ?>" id="<?= $vip_field_pagto ?>" value="<?= encode(2) ?>" onclick="getPlano(1)" /></td>
		</tr>
		<tr height="4"></tr>
		<tr class="cor_nao">
			<td height="3" align="center"><img src="<?php echo img() ?>layout/estrela.gif" alt="" width="16" height="16" /></td>
			<td height="35" class="cor_nao">PayPal Dolar</td>
			<td><?php echo t('comprando_vip.cv19')?></td>
			<td align="center"><input type="radio" name="<?= $vip_field_pagto ?>" id="<?= $vip_field_pagto ?>" value="<?= encode(3) ?>" onclick="getPlano(2)" /></td>
		</tr>
		<tr height="4"></tr>
		<tr class="cor_sim">
			<td height="3" align="center" ><img src="<?php echo img() ?>layout/estrela.gif" alt="" width="16" height="16" /></td>
			<td height="35" >PayPal Euro</td>
			<td ><?php echo t('comprando_vip.cv19')?></td>
			<td align="center" ><input type="radio" name="<?= $vip_field_pagto ?>" id="<?= $vip_field_pagto ?>" value="<?= encode(4) ?>" onclick="getPlano(3)" /></td>
		</tr>
		<tr class="cor_sim">
			<td height="3" align="center" class="cor_nao" ><img src="<?php echo img() ?>layout/estrela.gif" alt="" width="16" height="16" /></td>
			<td height="35" class="cor_nao" >PayPal BRL</td>
			<td class="cor_nao" ><?php echo t('comprando_vip.cv19')?></td>
			<td align="center" class="cor_nao" ><input type="radio" name="<?= $vip_field_pagto ?>" id="<?= $vip_field_pagto ?>" value="<?= encode(5) ?>" onclick="getPlano(4)" /></td>
		</tr>
	</table>
	<br />
	<br />
	<div id="cnPlano">
		<span class="laranja" style="text-align:center">
			<?php echo t('comprando_vip.cv3')?>
		</span>
	</div>
	<br />
	<table width="730" border="0" cellpadding="2" cellspacing="0">
		<tr>
			<td height="3" align="center"><a class="button" data-trigger-form="1"><?php echo t('botoes.finalizar_doacao')?></a></td>
		</tr>
	</table>
</form>
