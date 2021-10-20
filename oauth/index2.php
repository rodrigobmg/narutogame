<?php
	session_start();

	define('CONF_FILE', dirname(__FILE__).'/lib/opauth/config.php');
	define('OPAUTH_LIB_DIR', dirname(__FILE__).'/lib/opauth/');

	require CONF_FILE;
	require OPAUTH_LIB_DIR.'Opauth.php';
	
	if(isset($_GET['provider'])) {
		$_SERVER['REQUEST_URI']	= '/strategy/' . $_GET['provider'];
		
		//$config['path']	= '/auth/';
		
		$opauth = new Opauth( $config );
	} else if(isset($_GET['callback'])) {
		die('aaaa');
	} else if(isset($_GET['auth_result'])) {
		$_SERVER['REQUEST_URI']	= '/oauth/facebook/int_callback?code=' . $_GET['code'];
		$opauth = new Opauth($config, false);
		
		var_dump($_GET);
		var_dump($_POST);
		var_dump($_SESSION);
		var_dump($opauth);
		
		$opauth->callback();
		
		switch($opauth->env['callback_transport']){	
			case 'session':
			var_dump($_SESSION);
				$response = $_SESSION['opauth'];
				unset($_SESSION['opauth']);
				
				break;
			case 'post':
				$response = unserialize(base64_decode( $_POST['opauth'] ));
				
				break;
			case 'get':
				$response = unserialize(base64_decode( $_GET['opauth'] ));
				
				break;
			default:
				echo '<strong style="color: red;">Error: </strong>Unsupported callback_transport.'."<br>\n";
				
				break;
		}
		
		echo $response;
	}
