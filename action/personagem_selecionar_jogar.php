<?php 
	// Comente aqui
	/*if(!$_SESSION['universal']){
		die('Você não pode logar!');
	}*/

	// Comente aqui
	if(!isset($_POST['id'])) {
		redirect_to("negado", NULL, array("e" => 2));
	} 

	$qPlayer = Recordset::query("
	SELECT 
		a.id,
		a.id_usuario, 
		a.nome,
		a.id_graduacao,
		b.sorte_bijuu,
		b.sorte_bijuu_dia,
		a.id_batalha,
		a.id_vila,
		a.id_classe,
		a.banido,
		pbs.id_player as id_player_existe,
		f.id_player as fidelity
	FROM 
		player a 
		
		LEFT JOIN player_flags b ON b.id_player=a.id
		LEFT JOIN player_batalhas_status AS pbs ON pbs.id_player=a.id
		LEFT JOIN player_fidelity AS f ON f.id_player=a.id 
	
	WHERE 
		a.id=" . (int)decode($_POST['id']) . " AND a.id_usuario=" . (int)$_SESSION['usuario']['id']);
	
	if(!$qPlayer->num_rows) {
		redirect_to("negado", NULL, array("e" => 1));	
	} else {
		$rPlayer = $rp = $qPlayer->row_array();

		if($rPlayer['banido']){
			redirect_to("negado", NULL, array("e" => 2));
		}
		
		if($_SESSION['basePlayer']) { // Se tiver um player logado, então marca sem atividade
			Recordset::update('player_posicao', array(
				'ult_atividade'	=> NULL
			), array(
				'id_player'	=> $basePlayer->id			
			));		
		}
		
		unset($_SESSION['dojoEnemy']);

		//die(print_r($rp));
		$_SESSION['basePlayer']				= decode($_POST['id']);	
		$_SESSION['playerName']				= $rPlayer['nome'];
		$_SESSION['player_posicao_check']	= false;
		$_SESSION['key']					= md5(rand(0, 512384) . rand(0, 512384));
		$_SESSION['estudo_ninja_to_key']	= md5('YmdHis');
		$_SESSION['ninja_shop_key']			= "";
		$_SESSION['missoes_key']			= "";
		$_SESSION['personagem_imagem_key']	= "";
		$_SESSION['torneio_key']			= "";
		
		if($rPlayer['id_batalha']) {
			$rBatalha = Recordset::query("SELECT a.id_batalha, b.id_player, b.id_playerb FROM player a JOIN batalha b ON b.id=a.id_batalha WHERE b.id_tipo IN(2,4,6) AND a.id=" . $_SESSION['basePlayer'])->row_array();
			
			if($rBatalha['id_batalha']) {
				$vencedor	= $rBatalha['id_player'] == $rPlayer['id'] ? $rBatalha['id_playerb'] : $rBatalha['id_player'];
				
				Recordset::query("UPDATE batalha SET finalizada=1, pvp_wo=1, vencedor=" . $vencedor . " WHERE id=" . $rPlayer['id_batalha']);
			}
		}

		//die($rp['sorte_bijuu'] . " --> " . $rp['sorte_bijuu_dia'] . " ---> " . date('Y-m-d'));
		// Para a Fidelidade
		if(!$rp['fidelity']) {
			Recordset::query("INSERT INTO player_fidelity(id_player,day, created_at) VALUES (". $rp['id'] .",1,'".now(true)."')");
		}
		
		// Para os ranks diários
		if($rp['id_player_existe'] == "") {
			Recordset::query("INSERT INTO player_batalhas_status(id_player,id_usuario,id_vila,id_classe, id_graduacao) VALUES (". $rp['id'] .",". $rp['id_usuario'] .",". $rp['id_vila'] .",". $rp['id_classe'] .", ". $rp['id_graduacao'] .")");
		}else{
			Recordset::query("UPDATE player_batalhas_status SET id_usuario = ". $rp['id_usuario'] .", id_vila = ". $rp['id_vila'] .", id_classe = ". $rp['id_classe'] .", id_graduacao = ". $rp['id_graduacao'] ." WHERE id_player =  ". $rp['id'] ." ");
		}
		
		if($rp['sorte_bijuu'] == "") { // Novo personagem, não tem na player_flags
			Recordset::query('INSERT INTO player_flags(id_player, sorte_bijuu_dia, sorte_bijuu) VALUES(' . $rp['id'] . ', CURDATE(),10)');

			Recordset::insert('player_item', [
				'id_player'	=> $rp['id'],
				'id_item'	=> 1859,
				'qtd'		=> 5
			]);
		} else { // Player ja tem dados na player flags
			if($rp['sorte_bijuu'] <= 200 && $rp['sorte_bijuu_dia'] != date('Y-m-d')) {
				$dt = date('Y-m-d');
				Recordset::query('UPDATE player_flags SET sorte_bijuu=sorte_bijuu+1, sorte_bijuu_dia=\'' . $dt . '\' WHERE id_player=' . $_SESSION['basePlayer']);
			}
		}
		
		// Adicionado para a Ariane
		$ip = Recordset::query("select INET_ATON('".$remote_ip."') as ip")->row_array();

		Recordset::insert('player_login', [
			'id_usuario'	=> $rp['id_usuario'],
			'id_player'		=> $rp['id'],
			'ip'			=> $ip['ip']
		]);
		
		redirect_to("personagem_status");
	}
