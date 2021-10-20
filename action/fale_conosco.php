<?
	if($_POST['txtCaptchaFaleConosco'] != $_SESSION['securimage_fale_conosco']) {
		redirect_to("", "", array("e" => "1", "secao" => "fale_conosco"));
		die();
	}

	/*
	$EM = new Email();
	*/
	
	$Msg = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Contato NG</title>
		</head>
		<body>
		<b>Assunto:</b> ' . htmlentities($_POST['assunto']) . '<br />
		<b>Nome:</b> ' . htmlentities($_POST['nome']) . '<br />
		<b>E-Mail:</b> ' . htmlentities($_POST['FC_email']) . '<br />
		<b>Mensagem:</b><br />
		' . nl2br(htmlentities($_POST['mensagem'])) . '
		</body>
		</html>
	';

	$MsgB = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Contato NG</title>
		</head>
		<body>
			Agradecemos por entrar em contato conosco, estaremos analisando a sua mensagem e responderemos em breve.<br />
			Grato, Equipe Naruto Game.
		</body>
		</html>
	';

	// E-Mail --->		
		$path = realpath(dirname(__FILE__) . '/../class/');
		set_include_path(get_include_path() . ':' . $path);

		require_once 'Zend/Mail.php';
		require_once 'Zend/Mail/Transport/Smtp.php';

		$config = array (
	      'auth' => 'login',
	      'username' => 'contato+narutogame.com.br',
	      'password' => 'Carlos@2021'
		);		

		$mailTransport = new Zend_Mail_Transport_Smtp('mail.animaniaclub.com.br', $config);		
		
		// Mensagem para gente --->
			$mailMessage = new Zend_Mail();
			
			$mailMessage->setFrom('contato@narutogame.com.br', 'Contato - Naruto Game');
			$mailMessage->addTo('contato@narutogame.com.br', 'Fale Conosco');
			
			$mailMessage->setBodyHtml($email);
			$mailMessage->setSubject('Fale conosco');
			
			$mailMessage->send($Msg);
		// <---

		// Mensagem para o player --->
			$mailMessage = new Zend_Mail();
			
			$mailMessage->setFrom('contato@narutogame.com.br', 'Contato - Naruto Game');
			$mailMessage->addTo($_POST['email'], $_POST['nome']);
			
			$mailMessage->setBodyHtml($MsgB);
			$mailMessage->setSubject('Fale conosco');
			
			$mailMessage->send($mailTransport);
		// <---
	// <---

	/*
	$Nome_Remetente = 'Contato - NarutoGame';
	$Email_Remetente = 'contato@narutogame.com.br';
	$Nome_Destinatario = 'Contato - NarutoGame';
	$Email_Destinatario = 'contato@narutogame.com.br';
	$Assunto = htmlentities($_POST['assunto']) . ' - Formulario do Site';
	
	$EM->Envia_Email($Nome_Remetente, $Email_Remetente, $Nome_Destinatario, $Email_Destinatario, $Assunto, $Msg, htmlentities($_POST['nome']), $_POST['FC_email']);
	$EM->Envia_Email($Nome_Remetente, $Email_Remetente, htmlentities($_POST['nome']), $_POST['FC_email'], "Fale conosco NarutoGame", $MsgB);
	*/
	
	redirect_to("", "", array("ok" => "1", "secao" => "fale_conosco"));
?>