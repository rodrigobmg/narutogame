<?php
	define('IS_DEAUTH', true);

	// Force FB signed request parse
	$_GET['connect-with-fb']	= true;

	require_once('../include/db.php');
	require_once('../class/Recordset.php');
	require_once('../include/generic.php');
	require_once('../include/fb-sdk/facebook.php');
	require_once('fb.php');
	
	ob_start();
	print_r($_SESSION);
	print_r($_POST);
	print_r($_GET);
	print_r($fb_user);
	
	$fp	= fopen('out.txt', 'a+');
	fwrite($fp, ob_get_clean());
	fclose($fp);

	if(isset($fb_user) && $fb_user) {
		Recordset::update('global.user', array(
			'has_fb'			=> '0',
			'fb_access_token'	=> '',
			'fb_uid'			=> 0
		), array(
			'fb_uid'			=> $fb_user
		));	
	}
	
