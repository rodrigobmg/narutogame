<?php
	header("Content-type: text/html; charset=utf-8");
	
	$qExistente = Recordset::query("SELECT id FROM batalha_sala WHERE id_vila={$basePlayer->id_vila} AND nome='" . addslashes(trim($_POST['nome'])) . "'");
	
	if($qExistente->num_rows) {
		echo "jalert('".t('actions.a30')."')";
		die();	
	}

	if($basePlayer->hp < $basePlayer->max_hp / 2 || $basePlayer->sp < $basePlayer->max_sp / 2 || $basePlayer->sta < $basePlayer->max_sta / 2) {
		echo "jalert('".t('actions.a31')."');";
		die();
	}
	
	$_SESSION['_pvpITEMS'] = array();
	$same_level				= isset($_POST['same-level']) && $_POST['same-level'] ? 1 : 0;
	
	$qSala = Recordset::query("INSERT INTO batalha_sala(id_player, id_vila, nome, mesmo_nivel) VALUES(" . $basePlayer->id . ", " .
				$basePlayer->id_vila_atual . ", '" . addslashes(trim($_POST['nome'])) . "', " . $same_level . ")");
	
	$id_sala = $qSala->insert_id();
	
	Recordset::query("UPDATE player SET id_sala = $id_sala WHERE id = " . $basePlayer->id);
	
	//echo "alert('Anúncio de batalha criado com sucesso. Você agora entrará em modo de espera.');\n";
	echo "location.href='?secao=dojo_batalha_espera'";
