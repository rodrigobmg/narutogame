<?php
	Recordset::query("INSERT INTO equipe_forum_topico(id_equipe, id_player, titulo, conteudo) VALUES(
		{$basePlayer->id_equipe}, {$basePlayer->id}, '" . addslashes(htmlentities($_POST['titulo'])) . "', '" . 
		addslashes(htmlentities(check_words($_POST['topico_conteudo']))) . "'
	)");

	redirect_to("equipe_forum", "", array("created" => 1));
