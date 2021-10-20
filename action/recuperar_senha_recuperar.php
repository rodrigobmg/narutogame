<?php
	header("Content-type: text/javascript; charset=utf-8");

	$redir_script = true;

	if(strtolower($_POST['txtCaptchaRecuperar']) != strtolower($_SESSION["securimage_recuperar_senha"])) {
		if(isset($_POST['r']) && $_POST['r']) {
			redirect_to("", "", array("e" => "1", "secao" => "recuperar_senha", "token" => $_POST['token'], "u" => $_POST['u']));			
		} else {
			redirect_to("", "", array("e" => "1", "secao" => "recuperar_senha"));
		}
		
		die();
	}

	if(!isset($_POST['r']) || (isset($_POST['r']) && !$_POST['r'])) {
		$qUsuario = Recordset::query("SELECT id, `key` AS token FROM global.user WHERE email='" . addslashes($_POST['txtEmailRecuperarSenha']) . "'");
	
		if(!$qUsuario->num_rows) {
			echo "\$('#cnDados').show();";
			echo "\$('#cnMensagem').html(\"E-Mail não encontrado\");";
			
			die();
		}
		
		$rUsuario	= $qUsuario->row_array();
		$new_token	= uniqid(uniqid(), true);

		Recordset::update('global.user', [
			'key'	=> $new_token
		], [
			'id'	=> $rUsuario['id']
		]);

		echo "\$('#cnMensagem').html(\"<div class='msg_gai' >" .
			 "<div class='msg'>" .
			 "<div class='msg_text' style='background:url(". img() ."layout/msg/". $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8)."/1.png); background-repeat: no-repeat;'>" .
			 "<b>".t('actions.a245')."</b>" .
		  	 "<p>".t('actions.a246')."</p>".
              "</div></div></div>\");";

	
		//$senha = intval(rand(10, 512384)) . dechex(intval(rand(10, 512384))) . date("is");
		$token	= $new_token;
		$u		= $rUsuario['id'];
		
		eval("\$email = \"" . addslashes(file_get_contents("mailtemplates/recuperar_senha.php")) . "\";");

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
			
			$mailMessage->setFrom('contato@narutogame.com.br', ''.t('actions.a2').'');
			$mailMessage->addTo($_POST['txtEmailRecuperarSenha']);
			
			$mailMessage->setBodyHtml($email, 'utf-8');
			$mailMessage->setSubject('Recuperar Senha');
			
			$mailMessage->send($mailTransport);
			*/

			require('promocoes/action_mailer.php');
			
			class RecuperarSenhaMailTemplate extends ActionMailer { 
				public $host		= 'mail.narutogame.com.br';
				public $port		= 25;
				public $username	= 'contato@narutogame.com.br';
				public $password	= 'Carlos@2021';
				public $from		= 'contato@narutogame.com.br';
				public $from_name	= 'Recuperação de senha Naruto Game';
		
				function send($subject, $to, $content) {
					$this->deliver('', $subject, $to, $content);
				}
			}
			
			$template 	= new RecuperarSenhaMailTemplate();
			$template->send('Recuperar Senha', $_POST['txtEmailRecuperarSenha'], $email);
		// <---
	} else {
		if(Recordset::query("SELECT id FROM global.user WHERE id=" . (int)$_POST['u'] . " AND `key`='" . addslashes($_POST['token']) . "'")->num_rows) {
			Recordset::query("UPDATE global.user SET `password`=" . mysql_compat_password($_POST['r_senha']) . " WHERE id=" . (int)$_POST['u']);
		}
		
		echo "alert('".t('actions.a247')."');";
		
		redirect_to("");
	}
