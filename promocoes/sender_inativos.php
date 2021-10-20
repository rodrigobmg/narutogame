<?php
	date_default_timezone_set('America/Sao_Paulo');	
	define('IS_SENDER', true);

	require('../include/db.php');
	require('../class/Recordset.php');
	require('baseintencoder.php');
	require('action_mailer.php');

	$count	= 1;

	/*
	$emails	= Recordset::query('
		SELECT
			a.id,
			a.email
		
		FROM
			global.user a JOIN w4dev.emails_ng b ON b.id_ng=a.id
		
		WHERE
			b.valido=1
			AND a.id NOT IN(SELECT usuario_id FROM narutogame_prod.promocao_usuario WHERE promocao_id=1)
			AND DATEDIFF(NOW(), a.last_activity) >= 100	
	');
	*/

	/*
	$emails	= Recordset::query('
		SELECT
			a.id,
			a.email
		
		FROM
			global.user a
		
		WHERE
			a.id NOT IN(SELECT usuario_id FROM narutogame_prod.promocao_usuario WHERE promocao_id=2)
			AND DATEDIFF(NOW(), a.last_activity) >= 100
	');
	*/

	/*$emails	= Recordset::query('
		SELECT
			a.id,
			a.email
		
		FROM
			global.user a# JOIN w4dev.emails_ng b ON b.id_ng=a.id
		
		WHERE
			#b.valido=1 AND
			DATEDIFF(NOW(), a.last_activity) BETWEEN 90 AND 365
			#AND a.id NOT IN(SELECT usuario_id FROM narutogame_prod.promocao_usuario)
	');*/

	
	$emails	= Recordset::query('
		SELECT
			a.id,
			a.email
		FROM
			global.user a# JOIN w4dev.emails_ng b ON b.id_ng=a.id
		WHERE
			a.email is not null and
			a.vip = 1
	');
	

	echo $emails->num_rows . "\n";
	
	while($email = $emails->row_array()) {
		$cmd	= 'php sender_inativos_thread.php ' . $email['id'] . ' > /dev/null 2>&1 & echo $! >> /tmp/email.pid';
		
		exec('php sender_inativos_thread.php ' . $email['id'] . ' > /dev/null 2>&1 & echo $! >> /tmp/email.pid');
		echo "- " . $email['email'] . "\n";

		if(!($count++ % 1000)) {
			sleep(60);
		}
		
		/*
		$img			= imagecreatefromjpeg('inativos/index_r4_c1.jpg');
		$font_file		= realpath(dirname(__FILE__) . '/../include/ttffonts/tahoma.ttf');
		$white			= imagecolorallocate($img, 255, 255, 255);
		$uuid			= BaseIntEncoder::encode(1000000 + $email['id']);
		$text_width		= (strlen($uuid) - 1) * 7;
		$image_width	= imagesx($img);
		
		imagettftext($img, 9, 0, $image_width / 2 - $text_width / 2, 55, $white, $font_file, $uuid);
		
		imagejpeg($img, NULL, 90);
		//imagejpeg($img, dirname(__FILE__) . '/inativos/data/' . $email['id'] . '.jpg', 90);
		*/
	}
