<?php
	$map = imagecreatefromjpeg("/srv/www/ng/include/map/vila/" . $basePlayer->id_vila_atual . ".jpg");
	imagejpeg($map, NULL, 40);
