<?php
	require('promocoes/action_mailer.php');

	header("Content-Type: text/javascript; charset=utf-8");

	$nome = $_SESSION['usuario']['nome'];
	$token = md5($_SESSION['usuario']['email']);

	eval("\$email = \"" . addslashes(file_get_contents("mailtemplates/cadastro.php")) . "\";");

	class AtivacaoMailTemplate extends ActionMailer { 
		public $host		= 'mail.narutogame.com.br';
		public $port		= 25;
		public $username	= 'contato+narutogame.com.br';
		public $password	= 'Carlos@2021';
		public $from		= 'contato@narutogame.com.br';
		public $from_name	= 'Contato Naruto Game';

		function send($subject, $to, $content) {
			$this->deliver('', $subject, $to, $content);
		}
	}
	
	$template	= new AtivacaoMailTemplate();
	$template->send(t('actions.a1'), $_SESSION['usuario']['email'], $email);

	// E-Mail --->
		/*
		$path = realpath(dirname(__FILE__) . '/../class/');
		set_include_path(get_include_path() . ':' . $path);

		require_once 'Zend/Mail.php';
		require_once 'Zend/Mail/Transport/Smtp.php';

		$config = array (
		  'auth' => 'login',
		  'username' => 'contato+narutogame.com.br',
		  'password' => 'Carlos@2021'
		);		

		$mailTransport = new Zend_Mail_Transport_Smtp('mail.narutogame.com.br', $config);		
		$mailMessage = new Zend_Mail('utf-8');
		
		$mailMessage->setFrom('contato@narutogame.com.br', 'Contato - Naruto Game');
		$mailMessage->addTo($_SESSION['usuario']['email'], $nome);
		
		$mailMessage->setBodyHtml($email, 'utf-8');
		$mailMessage->setSubject(t('actions.a1'));
		
		$mailMessage->send($mailTransport);
		*/
	// <---

	/*
	mail($_SESSION['usuario']['email'], t('actions.a1'), $email, 
		 "From: ".t('actions.a2')." <contato@narutogame.com.br>\r\n".
		 "Content-Type: text/html; charset=iso-8859-1");
	*/
?>
alert("<?php echo t('actions.a3')?>");