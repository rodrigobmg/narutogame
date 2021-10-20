<?php
	$id = decode($_POST['id']);
	
	$redir_script = true;
	
	if(!is_numeric($id)) {
		redirect_to("negado", NULL, array("e" => 1));
	}

	$r = Recordset::query("
		SELECT
			b.*,
			c.id AS has_vote
		FROM
			vila_forum_topico_post a JOIN vila_forum_topico b ON b.id=a.id_vila_forum_topico
			LEFT JOIN vila_forum_topico_post_voto c ON c.id_vila_forum_topico_post=a.id AND c.id_player=" . $basePlayer->id . "
		
		WHERE 
			a.id=" . $id)->row_array();

	if($r['has_vote']) {
		redirect_to("negado", NULL, array("e" => 2));
	}
	
	if($r['id_vila'] != $basePlayer->id_vila) {
		redirect_to("negado", NULL, array("e" => 3));		
	}

	if($_POST['vote']) {
		$field = "likes";
		$is_like = 1;
	} else {
		$field = "unlikes";
		$is_like = 0;
	}

	Recordset::query("INSERT INTO vila_forum_topico_post_voto(id_vila_forum_topico_post, id_player, is_like) VALUES(
		". $id . ", " . $basePlayer->id . ", '" . $is_like . "' 
	)");
	
	Recordset::query("UPDATE vila_forum_topico_post SET $field=$field + 1 WHERE id=" . $id);
	Recordset::query("UPDATE vila_forum_topico SET $field=$field + 1 WHERE id=" . $r['id']);
