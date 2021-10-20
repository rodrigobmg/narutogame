<?php
	Recordset::insert('guild_forum_topico', array(
		'id_guild'	=> $basePlayer->getAttribute('id_guild'),
		'id_player'	=> $basePlayer->id,
		'titulo'	=> $_POST['titulo'],
		'conteudo'	=> htmlspecialchars(check_words($_POST['topico_conteudo']))
	));

	redirect_to("guild_forum", "", array("created" => 1));
