<?php
	if($_POST[$_SESSION['vip_field_postkey']] != $_SESSION['vip_field_postkey_value']) {
		redirect_to("negado");
	}

	$file = $_SESSION['usuario']['id'] . md5(rand(1, 9999999)) . $_FILES['comprovante']['name'];
	
	move_uploaded_file($_FILES['comprovante']['tmp_name'], "/WEB/html/narutogame/upload/comprovantes/" . $file);

	Recordset::query("UPDATE coin_compra SET texto_comprovante='" . addslashes($_POST['mensagem']) . "', img_comprovante='" . addslashes($file) . "'
				 WHERE processado=0 AND liberado=0 AND id_usuario=" . $_SESSION['usuario']['id'] . " 
				 AND id_pagamento=" . $basePlayer->trava_pagto);


	Recordset::query("UPDATE global.user SET pay_lock=0 WHERE id=" . $_SESSION['usuario']['id']);

	$Nome_Remetente = 'Contato - NarutoGame';
	$Email_Remetente = 'contato@narutogame.com.br';
	$Nome_Destinatario = 'Pagamento - NarutoGame';
	$Email_Destinatario = 'pagamento@narutogame.com.br';
	$Assunto = "Comprovante postado";


	$Msg = "Comprovante postado pelo usu�rio: " . $_SESSION['usuario']['id'] . "<br /><br />" . nl2br($_POST['mensagem']);

	$EM = new Email();
	$EM->Envia_Email($Nome_Remetente, $Email_Remetente, $Nome_Destinatario, $Email_Destinatario, $Assunto, $Msg, htmlentities($_POST['nome']), $_POST['FC_email']);


	redirect_to("comprando_vip", NULL, array("d" => 1));
