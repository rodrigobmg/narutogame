<?php
	require('_config.php');

	//Recordset::query("TRUNCATE TABLE estatistica_vila");
	$qForum = Recordset::query("
		select 
			*
		from 
			vila_forum_topico 
		WHERE 
			ult_resposta <= DATE_ADD(NOW(), INTERVAL -2 DAY) AND 
			respostas < 20 AND   
			removido = '0' AND      
			fixo = '0' AND
			(likes-unlikes) < 20"
	);
	
	while($rv = $qForum->row_array()) {
		Recordset::query("UPDATE vila_forum_topico SET removido = '1' WHERE id = ". $rv['id'] ."");
	}
