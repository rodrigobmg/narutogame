<?php
	if(isset($_GET['go'])) {
		
	}

	$r = Recordset::query("SELECT xpos, ypos FROM player WHERE id=" . $basePlayer->id)->row_array();
	
	$x = (int)$r['xpos'];
	$y = (int)$r['ypos'];
	
	$map_src = imagecreatefromjpeg("include/map/mapguild.jpg");
	
	header("Content-Type: image/jpeg", NULL, 30);
	imagejpeg($map_src);
