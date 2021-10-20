<?php
	require_once('../include/db.php');
	require_once('../include/generic.php');
	require_once('fb.php');
		
	/*
	if(!isset($_GET['p'])) {
		die('Jogador não especificado');
	}

	if(!isset($_GET['a'])) {
		die('Conquista não especificada');
	}
	
	$ach	= Recordset::query('
		SELECT
			a.nome AS player,
			c.nome AS conquista,
			c.pontos
		
		FROM
			player a JOIN conquista_grupo_item b ON b.id_player=a.id 
			JOIN conquista_grupo c ON c.id=b.id_conquista_grupo
		
		wHERE
			a.id=' . (int)$_GET['p'] . ' AND b.id_conquista_grupo=' . (int)$_GET['a']);
	
	if(!$ach->num_rows) {
		die('Conquista não encontrada');
	}*/

	ob_start();
	print_r($_SESSION);
	print_r($_POST);
	print_r($_GET);
	
	file_put_contents('out.txt', ob_get_clean());

	if(!isset($_GET['a'])) {
		die('Conquista não especificada');
	}
	
	$ach	= Recordset::query('SELECT * FROM conquista_grupo WHERE id=' . (int)$_GET['a'], true);
	
	if(!$ach->num_rows) {
		die('Conquista inválida');
	}
	
	$ach	= $ach->row_array();
	
?><html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml">
  <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# game: http://ogp.me/ns/game#">
    <meta property="fb:app_id" content="<?php echo FB_APP_ID ?>"/>
    <meta property="og:type" content="game.achievement"/>
    <meta property="og:url" content="<?php echo ('http://narutogame.com.br/fb/achievement.php?a=' . $ach['id']) ?>"/>
    <meta property="og:title" content="<?php echo $ach['nome'] ?>"/>
    <meta property="og:description" content="ACHIEVEMENT_DESCRIPTON"/>
    <meta property="og:image" content="http://narutogame.com.br/fb/ach.png"/>
    <title>Naruto Game - Conquistas</title>
  </head>
  <body>
    Promotional content for the Achievement.  
    This is the landing page where a user will be directed after
    clicking on the achievement story.
  </body>
</html>