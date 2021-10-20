<?php
	require('lib/hybridauth/Hybrid/Auth.php');

	if(!isset($_GET['provider'])) {
		
	} else {
		switch($_GET['provider']) {
			case 'facebook':
				$hybridauth = new Hybrid_Auth( 'lib/hybridauth/config.php' );	
				$adapter 	= $hybridauth->authenticate( "facebook", array(
					''
				) );
				print_r($adapter->getUserProfile());
				die();
	
				break;
			
			default:
				header('Location: /?secao=negado');
				die();
			
				break;
		}
	}