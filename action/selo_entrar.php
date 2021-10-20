<?php
	$id = $_POST['id'];

	$redir_script = true;

	if(!is_numeric($id)) {
		redirect_to("negado");	
	}

	if($basePlayer->getAttribute('id_selo')) {
		redirect_to("negado");	
	}

	if($basePlayer->portao) {
		redirect_to("negado");
	}

	if($basePlayer->id_sennin) {
		redirect_to("negado");
	}

	if(Recordset::query('SELECT id FROM selo WHERE id=' . $id, true)->num_rows) {
		$basePlayer->setAttribute('id_selo', $id);
	} else {
		redirect_to("negado");	
	}
	// Missões diárias de Aceitar Selo
	if($basePlayer->hasMissaoDiariaPlayer(17)->total){
		// Adiciona os contadores nas missões de tempo.
		Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
					 WHERE id_player = ". $basePlayer->id." 
					 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 17) 
					 AND completo = 0 ");
	}
	// Conquista --->
		arch_parse(NG_ARCH_SELF, $basePlayer);
	// <---

	redirect_to("selo", "", array("ok" => "1"));
