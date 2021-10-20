<?php
	header("Content-type: text/javascript; charset=utf-8");

	if($_POST['KEY'] != $_SESSION['graduacaoKEY']) {
		redirect_to('negado', NULL, array('e' => 1));
	}

	$next_grad	= $basePlayer->getAttribute('id_graduacao') + 1;
	$r			= Recordset::query("SELECT duracao FROM graduacao WHERE id=" . $next_grad, true)->row_array();

	if(!Graduation::hasRequirement($basePlayer, $next_grad)) {
		redirect_to('negado', NULL, array('e' => 1));
	}

	Recordset::update('player', array(
		'id_graduacao'	=> array('escape' => false, 'value' => 'id_graduacao + 1')
	), array(
		'id'			=> $basePlayer->id
	));
	
	if($basePlayer->hasMissaoDiariaPlayer(5)->total){
		// Adiciona os contadores nas Missões de Graduação
		Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
					 WHERE id_player = ". $basePlayer->id." 
					 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 5) 
					 AND completo = 0 ");
	}

	equipe_exp(300);

	// Conqiusta --->
		$basePlayer->__construct($basePlayer->id);

		arch_parse(NG_ARCH_SELF, $basePlayer);
	// <---
	
	echo "location.href='?secao=graduacoes&finished'";