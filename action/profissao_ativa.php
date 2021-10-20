<?php
	$json			= new stdClass();
	$messages		= [];
	$json->messages	= [];
	$json->success	= false;
	$active_limit	= 1;
	$current_count	= Player::getFlag('profissao_ativa_vezes', $basePlayer->id);
	$current_level	= Player::getFlag('profissao_nivel', $basePlayer->id);

	if ($current_count >= $active_limit) {
		$messages[]	= t('profissao.errors.active_limit');
	}

	if (!$basePlayer->id_profissao) {
		$messages[]	= t('profissao.errors.not_learned');
	} else {
		$profession	= Recordset::query('SELECT * FROM profissao WHERE id=' . $basePlayer->id_profissao)->row();
		$level		= Player::getFlag('profissao_nivel', $basePlayer->id);

		if ($profession->medico_ativo) {
			$choosen	= Recordset::query('SELECT id,nome FROM player WHERE hospital="1" AND id NOT in(SELECT id_player_alvo FROM profissao_ativa) LIMIT 1');

			if (!$choosen->num_rows) {
				$messages[]	= t('profissao.errors.no_hospital');
			} else {
				$choosen	= $choosen->row();
			}
		} elseif ($profession->cacador_ativo) {
			$choosen	= Recordset::query('
				SELECT
					a.id,
					a.nome

				FROM player a JOIN player_item b ON b.id_player=a.id
				WHERE
					a.hospital="0" AND
					b.id_item=20291 AND
					HOUR(TIMEDIFF(data_uso, NOW())) <= 24 AND
					a.id NOT in(SELECT id_player_alvo FROM profissao_ativa) LIMIT 1
			');

			if (!$choosen->num_rows) {
				$messages[]	= 'Não há jogadores com a camuflagem ativa no momento';
			} else {
				$choosen	= $choosen->row();
			}
		} else {
			$choosen	= Recordset::query('SELECT id,nome FROM player WHERE hospital="0" AND id NOT in(SELECT id_player_alvo FROM profissao_ativa) LIMIT 1')->row();
		}
	}

	if (!sizeof($messages)) {
		$json->success	= true;

		if ($profession->medico_ativo) {
			Recordset::update('player', [
				'less_hp'	=> 0,
				'less_sp'	=> 0,
				'less_sta'	=> 0,
				'hospital'	=> '0'
			], [
				'id'		=> $choosen->id
			]);
		}

		Recordset::insert('profissao_ativa', [
			'id_player'			=> $basePlayer->id,
			'id_player_alvo'	=> $choosen->id,
			'id_profissao'		=> $basePlayer->id_profissao,
			'level'				=> $current_level
		]);

		arch_parse(NG_ARCH_PROFISSAO, $basePlayer);

		switch($basePlayer->id_profissao){
			case 1:
				$titulo = "Curado por um Ninja Médico";
				$mensagem = "Você foi curado pelo ninja médico ". $basePlayer->nome;
			break;	
			case 2:
				$titulo = "Preço de Ramen Reduzido";
				$mensagem = "O ninja cozinheiro ". $basePlayer->nome ." acaba de presentea-lo com um buff que reduz o preço de seus ramens em 20% por 10 minutos.";
			break;
			case 3:
				$titulo = "Armas Fortificadas";
				$mensagem = "O ninja ferreiro ". $basePlayer->nome ." acaba de presentea-lo com um buff que aumenta o dano de suas armas em 20% por 10 minutos.";
			break;
			case 4:
				$titulo = "Camuflagem Prejudicada";
				$mensagem = "O ninja caçador ". $basePlayer->nome ." acaba de anular sua camuflagem ninja durante 10 minutos.";
			break;	
			case 5:
				$titulo = "Jutsus Aprimorados";
				$mensagem = "O ninja instrutor ". $basePlayer->nome ." acaba de presentea-lo com um buff que aumenta o dano de seus jutsus em 10% por 10 minutos.";
			break;
			case 6:
				$titulo = "Experiência em Batalhas";
				$mensagem = "O ninja aventureiro ". $basePlayer->nome ." acaba de presentea-lo com um buff que aumenta em 10% a experiência ganha em batalha por 10 minutos.";
			break;			
		}
		mensageiro($basePlayer->id,$choosen->id,$titulo,$mensagem,"player");

		$basePlayer->setFlag('profissao_ativa_vezes', $current_count + 1);
	} else {
		$json->messages	= $messages;
	}

	echo json_encode($json);
