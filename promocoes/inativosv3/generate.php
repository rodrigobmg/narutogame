<?php
	define('PROMOCAO', 5);

	header('Content-Type: image/jpeg');

	require('../../include/db.php');
	require('../../class/Recordset.php');
	require('../baseintencoder.php');

	$uuid		= BaseIntEncoder::decode(filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING)) - 1000000;
	$user		= Recordset::query('SELECT * FROM global.user WHERE email="' . addslashes(filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL)) . '" AND id=' . (int)$uuid);
	
	if(!$user->num_rows) {
		die('Inválido :(');	
	}

	$promocao	= Recordset::query('SELECT * FROM promocao_usuario WHERE promocao_id=' . PROMOCAO . ' AND usuario_id=' . $user->row()->id);
	
	if(!$promocao->num_rows) {
		die('Não cadastrado na promoção :(');	
	}

	if(!$promocao->row()->visto_em) {
		Recordset::update('promocao_usuario', array(
			'visto_em'		=> array('escape' => false, 'value' => 'NOW()'),
			'visto'			=> 1
		), array(
			'usuario_id'	=> $user->row()->id,
			'promocao_id'	=> PROMOCAO
		));
	}

	$img			= imagecreatefromjpeg('03.jpg');
	$font_file		= realpath(dirname(__FILE__) . '/../../include/ttffonts/tahoma.ttf');
	$white			= imagecolorallocate($img, 255, 255, 255);
	$text_width		= (strlen($_GET['code']) - 1) * 7;
	$image_width	= imagesx($img);
	
	imagettftext($img, 9, 0, $image_width / 2 - $text_width / 2 + 40, 72, $white, $font_file, $_GET['code']);
	
	imagejpeg($img, NULL, 90);
