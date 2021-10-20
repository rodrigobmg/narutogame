<?php
	require 'class/SMTP_Validate.php';

	header("Content-Type: text/javascript; charset=utf-8");

	// Validações --->
		
		$error = "";
		
		if(!preg_match("/^[_\w\-\.]+@([_\w\-]+(\.[_\w\-]+)+)$/i", $_POST['email'])) {
			$error .= "<b class='laranja'>&bull; ".t('actions.a15').".</b><br />";
		} else {
			/*
			$validator				= new SMTP_validateEmail();
			$validator->nameservers	= array('mail.narutogame.com.br');
			$result					= $validator->validate(array($_POST['email']), 'contato@narutogame.com.br');
			$result					= $result[$_POST['email']];

			if(!$result) {
				$error .= "<b class='laranja'>&bull; Não conseguimos validar esse e-mail. Por favor insira um e-mail válido</b><br />";				
			}
			*/
		}
		
		// Verifica se o email ja está cadastrado
		if(Recordset::query("SELECT id FROM global.user WHERE email='" . addslashes($_POST['email']) . "'")->num_rows) {
			$error .= "<b class='laranja'>&bull; ".t('actions.a16').".</b><br />";
		}
		
		if(!preg_match("/^[\w\'\s]+$/i", $_POST['nome'])) {
			$error .= "<b class='laranja'>&bull; ".t('actions.a17').".</b><br />";
		}
		
		/*
		if($_POST['cep']) {
			if(!preg_match("/[0-9]{8}/", $_POST['cep'])) {
				$error .= "&bull; Campo 'CEP' com valor inválido! Verifique o dado e tente novamente.<br />";
			}
		}
		
		if($_POST['endereco']) {
			if(!preg_match("/[\w\']+/", $_POST['endereco'])) {
				$error .= "&bull; Campo 'Endereço' com valor inválido! Verifique o dado e tente novamente.<br />";
			}
		}
	
	
		if($_POST['bairro']) {
			if(!preg_match("/[\w\']+/", $_POST['bairro'])) {
				$erro .= "&bull; Campo 'Bairro' com valor inválido! Verifique o dado e tente novamente.<br />";
			}
		}
		
	
		if($_POST['cidade']) {
			if(!preg_match("/[\w\']+/", $_POST['cidade'])) {
				$error .= "&bull; Campo 'Cidade' com valor inválido! Verifique o dado e tente novamente.<br />";
			}
		}
		*/
		
		if(!preg_match("/[\w\_\']{6,}/i", $_POST['senha'])) {
			$error .= "<b class='laranja'>&bull; ".t('actions.a18').".</b><br />";
		}
		
		if($_POST['senha'] != $_POST['confirma_senha']) {
			$error .= "<b class='laranja'>&bull; ".t('actions.a19').".</b><br />";
		}
	
		if(strlen($_POST['captcha']) != 5) {
			$error .= "<b class='laranja'>&bull; ".t('actions.a20')."</b><br />";
		}

		if( (!isset($_SESSION['securimage_code_value']) || !isset($_POST['captcha'])) || 
			
			(isset($_SESSION['securimage_code_value']) &&  isset($_POST['captcha']) && 
			(strtolower($_POST['captcha']) != strtolower($_SESSION['securimage_code_value'])))
		  ) {
			$error .= "<b class='laranja'>&bull; ".t('actions.a21')."</b><br />";
		}

		if(!$_POST['aceite']) {
			$error .= "<b class='laranja'>&bull; ".t('actions.a22').".</b><br />";
		}
		if(!$_POST['aceite2']) {
			$error .= "<b class='laranja'>&bull; ".t('actions.a23').".</b><br />";
		}
		if(!$_POST['aceite3']) {
			$error .= "<b class='laranja'>&bull; ".t('actions.a24').".</b><br />";
		}
		if(!$_POST['aceite4']) {
			$error .= "<b class='laranja'>&bull; ".t('actions.a25').".</b><br />";
		}
	// <---
	
	echo "\$('#cnMensagem').removeClass();";
	
	if($error) {
		echo "\$('#cnMensagem').html(\"<b class='laranja'>".t('actions.a26').":<blockquote>$error</blockquote></b>\");";
		echo "\$('#cnMensagem').addClass('areaErro');";
	} else {
		$nome	= $_POST['nome'];
		$token	= md5($_POST['email']);
		
		Recordset::insert('global.user', array(
			'password'		=> array('escape' => false, 'value' => mysql_compat_password($_POST['senha'])),
			'email'			=> $_POST['email'],
			'name'			=> $_POST['nome'],
			'sex'			=> $_POST['sexo'],
			'id_country'	=> $_POST['pais'],
			'key'			=> $token,
			'layout'		=> 'r10',
			'ref'			=> isset($_SESSION['ref']) && $_SESSION['ref'] ? $_SESSION['ref'] : 0,
			'id_ref'		=> isset($_SESSION['user_ref']) && $_SESSION['user_ref'] ? $_SESSION['user_ref'] : 0,
			'id_game'		=> 1
		));
		
		eval("\$email = \"" . addslashes(file_get_contents("mailtemplates/cadastro.php")) . "\";");
	
		// E-Mail --->		
			require('promocoes/action_mailer.php');
			
			class CadastroMailTemplate extends ActionMailer { 
				public $host		= 'mail.narutogame.com.br';
				public $port		= 587;
				public $username	= 'contato@narutogame.com.br';
				public $password	= 'Carlos@2021';
				public $from		= 'contato@narutogame.com.br';
				public $from_name	= 'Bem vindo ao Naruto Game';
		
				function send($subject, $to, $content) {
					$this->deliver('', $subject, $to, $content);
				}
			}
			
			$template 	= new CadastroMailTemplate();
			$template->send(t('actions.a29'), $_POST['email'], $email);
			
		// <---
		echo "\$('#cnMensagem').html(\"<div class='msg_gai'><div class='msg'><div class='msg_text' style='background:url(http://narutogame.com.br/images/layout/msg/5/1.png); background-repeat: no-repeat;'><b>".t('actions.a27')."</b><p>".t('actions.a28')."</p></div></div></div>\");";
		echo "\$('#cadastro').html('&nbsp;');";
	}
