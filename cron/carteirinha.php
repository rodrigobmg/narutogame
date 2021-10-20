<?php
	require('_config.php');

	define('ROOT', '/web/narutogame.com.br/');

	set_time_limit(0);
	error_reporting(E_ALL);

	$_base = dirname(__FILE__) . "/";

	$qPlayer = Recordset::query("SELECT id FROM player WHERE removido=0 AND level > 5 ORDER BY id ASC");
	
	$total = $qPlayer->num_rows;
	
	echo "<pre>+ Rows: " . $total . "\n";
	
	$c = 0;
	while($rPlayerA = $qPlayer->row_array()) {
		echo "- Generating " . ++$c . " of $total ... \n";
		
		flush();
		$basePlayer = new stdClass();
		
		$rPlayer = Recordset::query("
			SELECT
				a.nome,
				a.level,
				a.derrotas,
				a.empates,
				a.id_classe,
				(a.vitorias + a.vitorias_f + vitorias_d) as vitoriasTotal,
				a.vitorias,
				b.nome_".Locale::get()." AS nome_graduacao,
				c.nome_".Locale::get()." AS nome_vila,
				d.posicao_geral AS rank_g,
				d.posicao_vila AS rank_v,
				d.pontos,
				a.id_classe_tipo,
				cl.imagem,
				u.vip,
				d.quest_d,
				d.quest_c,
				d.quest_b,
				d.quest_a,
				d.quest_s,
				e.titulo_".Locale::get()." AS titulo,
				a.fugas,
				f.posicao_geral AS posicao_conquista
			FROM
				player a JOIN graduacao b ON b.id=a.id_graduacao
				JOIN vila c ON c.id=a.id_vila
				JOIN classe cl ON cl.id = a.id_classe
				LEFT JOIN ranking d ON d.id_player=a.id
				JOIN global.user u ON u.id = a.id_usuario
				LEFT JOIN player_titulo e ON e.id=a.id_titulo
				LEFT JOIN ranking_conquista f ON f.id_player=a.id
			
			WHERE a.id=" . $rPlayerA['id'] . "")->row_array();

		$basePlayer->nome = $rPlayer['nome'];
		$basePlayer->id_classe = $rPlayer['id_classe'];
		$basePlayer->level = $rPlayer['level'];
		$basePlayer->vila = $rPlayer['nome_vila'];
		$basePlayer->graduacao = $rPlayer['nome_graduacao'];
		$basePlayer->rank_v = $rPlayer['rank_v'];
		$basePlayer->rank_g = $rPlayer['rank_g'];
		$basePlayer->vitoriasTotal = $rPlayer['vitoriasTotal'];
		$basePlayer->vitorias = $rPlayer['vitorias'];
		$basePlayer->derrotas = $rPlayer['derrotas'];
		$basePlayer->pontos = $rPlayer['pontos'];
		$basePlayer->fugas = $rPlayer['fugas'];
		$basePlayer->empates = $rPlayer['empates'];
		$basePlayer->imagem = $rPlayer['imagem'];
		$basePlayer->vip = $rPlayer['vip'];
		$basePlayer->titulo = $rPlayer['titulo'];
		$basePlayer->posicao_conquista = $rPlayer['posicao_conquista'];
	
		$basePlayer->quest_d = $rPlayer['quest_d'];
		$basePlayer->quest_c = $rPlayer['quest_c'];
		$basePlayer->quest_b = $rPlayer['quest_b'];
		$basePlayer->quest_a = $rPlayer['quest_a'];
		$basePlayer->quest_s = $rPlayer['quest_s'];
	
		switch($rPlayer['id_classe_tipo']){
			
			case 2:
				$tipoClasse = 'Ninjutsu';
				break;
			
			case 1:
				$tipoClasse = 'Taijutsu';
				break;
				
			case 3:
				$tipoClasse = 'Genjutsu';
				break;
			
			case 4:
				$tipoClasse = 'Bukijutsu';
				break;
			
		}

		$img = imagecreatefrompng(ROOT."/images/layout/carteirinha/images/carteirinha_fundo.png");
		$avatar = imagecreatefrompng(ROOT."/images/layout/criacao/pequenas/".$basePlayer->id_classe.".png");	
		
		// Paleta --->
			$c_black = imagecolorallocatealpha($img, 52, 38, 25, 0);
			$c_white = imagecolorallocatealpha($img, 255, 255, 255, 0);
			$c_gray = imagecolorallocatealpha($img, 142, 141, 141, 0);
			$c_azul = imagecolorallocatealpha($img, 2, 208, 209, 0);
			$c_marrom = imagecolorallocatealpha($img, 46, 32, 23, 0);
			$c_marrom2 = imagecolorallocatealpha($img, 7, 68, 186, 0);
		// <---
		
		// Foto do Avatar
		//imagecopyresampled($img, $avatar, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		if(is_resource($avatar)) {
			imagecopy($img, $avatar, 5, 60, 0, 0, 129, 126);
		}
		
		// Nome do player e titulo
		imagettftext($img, 12, 0, 20, 17, $c_marrom, $_base . "carteirinha/ttffonts/tahoma.ttf", $basePlayer->nome);
		imagettftext($img, 9, 0, 20, 29, $c_marrom, $_base . "carteirinha/ttffonts/tahoma.ttf", $basePlayer->titulo);
		
		//Vila
		imagettftext($img, 9, 0, 42, 222, $c_white, $_base . "carteirinha/ttffonts/tahoma.ttf", $basePlayer->vila);
		
		// Graduacao
		imagettftext($img, 9, 0, 222, 222, $c_white, $_base . "carteirinha/ttffonts/tahoma.ttf", $basePlayer->graduacao);
		
		// Classe
		imagettftext($img, 9, 0, 308, 222, $c_white, $_base . "carteirinha/ttffonts/tahoma.ttf", $tipoClasse);

		// Nível
		imagettftext($img, 9, 0, 125, 222, $c_white, $_base . "carteirinha/ttffonts/tahoma.ttf", "Level " . $basePlayer->level);

		// Missoes
		imagettftext($img, 8, 0, 238, 183, $c_azul, $_base . "carteirinha/ttffonts/tahoma.ttf", (int)$basePlayer->quest_d);
		imagettftext($img, 8, 0, 265, 183, $c_azul, $_base . "carteirinha/ttffonts/tahoma.ttf", (int)$basePlayer->quest_c);
		imagettftext($img, 8, 0, 294, 183, $c_azul, $_base . "carteirinha/ttffonts/tahoma.ttf", (int)$basePlayer->quest_b);
		imagettftext($img, 8, 0, 323, 183, $c_azul, $_base . "carteirinha/ttffonts/tahoma.ttf", (int)$basePlayer->quest_a);
		imagettftext($img, 8, 0, 352, 183, $c_azul, $_base . "carteirinha/ttffonts/tahoma.ttf", (int)$basePlayer->quest_s);
		
		// Rankings
		imagettftext($img, 8, 0, 319, 105, $c_azul, $_base . "carteirinha/ttffonts/tahoma.ttf", $basePlayer->rank_g . "°");
		imagettftext($img, 8, 0, 309, 127,  $c_azul, $_base . "carteirinha/ttffonts/tahoma.ttf", $basePlayer->rank_v . "°");
		
		// Vitorias , Derrotas e Empates
		imagettftext($img, 8, 0, 190, 151, $c_azul, $_base . "carteirinha/ttffonts/tahoma.ttf", $basePlayer->fugas);
		imagettftext($img, 8, 0, 289, 81, $c_azul, $_base . "carteirinha/ttffonts/tahoma.ttf", $basePlayer->pontos);
		imagettftext($img, 8, 0, 199, 82, $c_azul, $_base . "carteirinha/ttffonts/tahoma.ttf", $basePlayer->vitoriasTotal);
		imagettftext($img, 8, 0, 206, 127, $c_azul, $_base . "carteirinha/ttffonts/tahoma.ttf", $basePlayer->empates);
		imagettftext($img, 8, 0, 206, 105, $c_azul, $_base . "carteirinha/ttffonts/tahoma.ttf", $basePlayer->derrotas);
		imagettftext($img, 8, 0, 345, 151, $c_azul, $_base . "carteirinha/ttffonts/tahoma.ttf", $basePlayer->posicao_conquista . "°");
		
		//imagesavealpha($img, true);
		imagejpeg($img, ROOT."/images/carteirinha/" . $rPlayerA['id'] . ".jpg", 95);
		
		imagedestroy($img);
	}
	
	echo "Generation OK";
	
	flush();
