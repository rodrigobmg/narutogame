<?php
	$basePlayer = new stdClass();
	
	$rPlayer = Recordset::query("
		SELECT
			a.nome,
			a.level,
			a.derrotas,
			a.empates,
			a.id_classe,
			(a.vitorias + a.vitorias_f + vitorias_d) as vitoriasTotal,
			b.nome_".Locale::get()." AS nome_graduacao,
			c.nome_".Locale::get()." AS nome_vila,
			d.posicao_geral AS rank_g,
			d.posicao_vila AS rank_v,
			cl.id_classe_tipo,
			cl.imagem,
			u.vip,
			d.quest_d,
			d.quest_c,
			d.quest_b,
			d.quest_a,
			d.quest_s
		FROM
			player a JOIN graduacao b ON b.id=a.id_graduacao
			JOIN vila c ON c.id=a.id_vila
			JOIN classe cl ON cl.id = a.id_classe
			JOIN ranking d ON d.id_player=a.id
			JOIN global.user u ON u.id = a.id_usuario
		
		WHERE id_player=" . (int)$_GET['i'])->row_array();
	
	$basePlayer->nome = $rPlayer['nome'];
	$basePlayer->level = $rPlayer['level'];
	$basePlayer->vila = $rPlayer['nome_vila'];
	$basePlayer->graduacao = $rPlayer['nome_graduacao'];
	$basePlayer->rank_v = $rPlayer['rank_v'];
	$basePlayer->rank_g = $rPlayer['rank_g'];
	$basePlayer->vitoriasTotal = $rPlayer['vitoriasTotal'];
	$basePlayer->derrotas = $rPlayer['derrotas'];
	$basePlayer->empates = $rPlayer['empates'];
	$basePlayer->imagem = $rPlayer['imagem'];
	$basePlayer->vip = $rPlayer['vip'];

	$basePlayer->quest_d = $rPlayer['quest_d'];
	$basePlayer->quest_c = $rPlayer['quest_c'];
	$basePlayer->quest_b = $rPlayer['quest_b'];
	$basePlayer->quest_a = $rPlayer['quest_a'];
	$basePlayer->quest_s = $rPlayer['quest_s'];

	switch($rPlayer['id_classe_tipo']){
		
		case 1:
			$tipoClasse = 'Taijutsu';
			break;
		
		case 2:
			$tipoClasse = 'Ninjutsu';
			break;
			
		case 3:
			$tipoClasse = 'Genjutsu';
			break;
		
		case 4:
			$tipoClasse = 'Bukijutsu';
			break;
		
	}

	$img = imagecreatefrompng("include/carteirinha/bg.png");
	$avatar = imagecreatefromjpeg("http://img.narutogame.com.br/".$basePlayer->imagem);
	
	
	if($basePlayer->vip) {
		$barra = imagecreatefrompng("include/carteirinha/barra_vip.png");
	} else {
		$barra = imagecreatefrompng("include/carteirinha/barra_nao_vip.png");		
	}
	
	// Paleta --->
		$c_black = imagecolorallocatealpha($img, 0, 0, 0, 0);
	// <---
	
	// Desenha a barra
	imagecopy($img, $barra, 10, 10, 0, 0, 360, 32);
	
	// Foto do Avatar
	//imagecopyresampled($img, $avatar, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	imagecopy($img, $avatar, 20, 48, 0, 0, 100, 100);
	
	// Nome do player e Level
	imagettftext($img, 16, 0, 16, 34, $c_black, "include/ttffonts/tahoma.ttf", $basePlayer->nome ." - Lvl ". $basePlayer->level);
	
	//Vila
	imagettftext($img, 12, 0, 216, 85, $c_black, "include/ttffonts/tahoma.ttf", $basePlayer->graduacao);
	
	// Graduacao
	imagettftext($img, 12, 0, 167, 67, $c_black, "include/ttffonts/tahoma.ttf", $basePlayer->vila);
	
	// Classe
	imagettftext($img, 12, 0, 187, 103, $c_black, "include/ttffonts/tahoma.ttf", $tipoClasse);
	
	// Rankings
	imagettftext($img, 12, 0, 229, 121, $c_black, "include/ttffonts/tahoma.ttf", $basePlayer->rank_v);
	imagettftext($img, 12, 0, 239, 139, $c_black, "include/ttffonts/tahoma.ttf", $basePlayer->rank_g);
	
	// Vitorias , Derrotas e Empates
	imagettftext($img, 11, 0, 80, 175, $c_black, "include/ttffonts/tahoma.ttf", $basePlayer->vitoriasTotal);
	imagettftext($img, 11, 0, 87, 192, $c_black, "include/ttffonts/tahoma.ttf", $basePlayer->empates);
	imagettftext($img, 11, 0, 87, 208, $c_black, "include/ttffonts/tahoma.ttf", $basePlayer->derrotas);

	// Missoes
	imagettftext($img, 11, 0, 155, 197, $c_black, "include/ttffonts/tahoma.ttf", $basePlayer->quest_d);
	imagettftext($img, 11, 0, 198, 197, $c_black, "include/ttffonts/tahoma.ttf", $basePlayer->quest_c);
	imagettftext($img, 11, 0, 241, 197, $c_black, "include/ttffonts/tahoma.ttf", $basePlayer->quest_b);
	imagettftext($img, 11, 0, 286, 197, $c_black, "include/ttffonts/tahoma.ttf", $basePlayer->quest_a);
	imagettftext($img, 11, 0, 329, 197, $c_black, "include/ttffonts/tahoma.ttf", $basePlayer->quest_s);
	
	header("Content-Type: image/jpeg");
	imagejpeg($img, NULL, 70);

?>