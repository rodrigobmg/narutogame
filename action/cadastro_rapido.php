<?php
	header('Content-Type: application/json');

	$json			= new stdClass();
	$json->success	= false;
	$json->messages	= [];
	$errors			= [];

	if(isset($_POST['email']) && isset($_POST['email_confirmacao']) && isset($_POST['senha']) && isset($_POST['confirma_senha']) && isset($_POST['captcha'])) {
		if (!preg_match('/^[_\w\-\.]+@([_\w\-]+(\.[_\w\-]+)+)$/i', $_POST['email'])) {
			$errors[]	= t('actions.a15');
		} else {
			if(Recordset::query("SELECT id FROM global.user WHERE email='" . addslashes($_POST['email']) . "'")->num_rows) {
				$errors[]	= t('actions.a16');
			}
		}

		if(!preg_match("/[\w\_\']{6,}/i", $_POST['senha'])) {
			$errors[]	= t('actions.a18');
		}
		
		if($_POST['senha'] != $_POST['confirma_senha']) {
			$errors[]	= t('actions.a19');
		}

		if(
			(!isset($_SESSION['securimage_code_value_quick']) || !isset($_POST['captcha'])) || 
			(isset($_SESSION['securimage_code_value_quick']) &&  isset($_POST['captcha']) && 
			(strtolower($_POST['captcha']) != strtolower($_SESSION['securimage_code_value_quick'])))
		) {
			$errors[]	= t('actions.a21');
		}
	} else {
		$errors[]	= 'Dados inválidos';
	}

	if(!isset($_POST['aceite'])) {
		$errors[] = t('actions.a22');
	}

	if(!sizeof($errors)) {
		$json->success	= true;
		$token			= md5($_POST['email']);
		$nome			= '';

		$id	= Recordset::insert('global.user', array(
			'password'		=> array('escape' => false, 'value' => mysql_compat_password($_POST['senha'])),
			'email'			=> $_POST['email'],
			'key'			=> $token,
			'ref'			=> isset($_SESSION['ref']) && $_SESSION['ref'] ? $_SESSION['ref'] : 0,
			'id_ref'		=> isset($_SESSION['user_ref']) && $_SESSION['user_ref'] ? $_SESSION['user_ref'] : 0,
			'id_game'		=> 1,
			'active'		=> 1,
			'ip'			=> ['escape' => false, 'value' => 'INET_ATON("' . $remote_ip . '")'],
			'last_activity'	=> now(true),
			'lang'			=> $_SESSION['lang'],
			'layout'		=> 'r10'
		));

		eval("\$email = \"" . addslashes(file_get_contents("mailtemplates/cadastro.php")) . "\";");
	
		// E-Mail --->		
			require('promocoes/action_mailer.php');
			
			class CadastroMailTemplate extends ActionMailer { 
				public $host		= 'narutogame.com.br';
				public $port		= 25;
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

		if(!Recordset::query("SELECT id_game FROM global.user_game_sid WHERE id_game=1 AND id_user=" . $id)->num_rows) {
			Recordset::insert('global.user_game_sid', [
				'sid'		=> session_id(),
				'id_user'	=> $id,
				'id_game'	=> 1
			]);
		} else {
			Recordset::update('global.user_game_sid', [
				'sid'		=> session_id()
			], [
				'id_game'	=> 1,
				'id_user'	=> $id
			]);
		}

		$_SESSION['logado']				= true;
		$_SESSION['key']				= md5(rand(0, 512384) . rand(0, 512384));
		$_SESSION['usuario']			= array();
		$_SESSION['usuario']['id']		= $id;
		$_SESSION['usuario']['nome']	= '';
		$_SESSION['usuario']['email']	= $_POST['email'];
		$_SESSION['usuario']['vip']		= 0;
		$_SESSION['usuario']['msg_vip']	= 1;
		$_SESSION['usuario']['sound']	= 1;
		$_SESSION['usuario']['lang']	= $_SESSION['lang'];
	} else {
		$json->messages	= $errors;
	}

	echo json_encode($json);