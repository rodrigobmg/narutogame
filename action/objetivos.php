<?php
	header('Content-Type: application/json');

	$json			= new stdClass();
	$json->messages	= [];
	$json->success	= false;
	$errors			= [];
	
	if(!is_numeric($_POST['id']) || !is_numeric($_POST['id_player_missao'])) {
		redirect_to('negado');
	}
	$objetivo_padrao = Recordset::query("SELECT * FROM missoes_diarias WHERE id =". $_POST['id'])->row();
	$objetivo_player = Recordset::query("SELECT * FROM player_missao_diarias WHERE id_player=".$basePlayer->id." and  id =". $_POST['id_player_missao'])->row();


	if(!$objetivo_player){
		$errors[]	= 'Você não possui esse objetivo!';
	}

	if($objetivo_player->qtd < $objetivo_padrao->qtd ){
		$errors[]	= 'Esse objetivo não foi concluído.';
	}
	if($objetivo_player->completo){
		$errors[]	= 'Esse objetivo já foi concluído!';	
	}
	
	if(!sizeof($errors)) {
		$json->success	= true;
		
		// Coloca na tabela que o player já recebeu o objetivo.
		Recordset::update('player_missao_diarias', array(
			'completo'			=> 1,
			'data_conclusao'	=> now(true)
		), array(
			'id_player'			=> $basePlayer->id,
			'id'	=> $_POST['id_player_missao']
			
		));
		
		// Adiciona os prêmios ao jogador
		$loteria_premio = Recordset::query("SELECT * FROM loteria_premio WHERE id =". $objetivo_padrao->loteria_premio_id)->row();
		
		// EXP
		if($loteria_premio->exp){
			Recordset::query("UPDATE player SET exp=exp + " .$loteria_premio->exp . " WHERE id=" . $basePlayer->id);
		}
		//EXP
		
		// RYOU
		if($loteria_premio->ryou){
			Recordset::query("UPDATE player SET ryou=ryou + " .$loteria_premio->ryou . " WHERE id=" . $basePlayer->id);
		}
		//RYOU
		
		// Treino
		if($loteria_premio->treino){
			Recordset::query("UPDATE player SET treino_total=treino_total + " .$loteria_premio->treino . " WHERE id=" . $basePlayer->id);
		}
		//Treino
		
		// Créditos
		if($loteria_premio->coin){
			Recordset::query("UPDATE global.user SET coin=coin+ " . $loteria_premio->coin . " WHERE id=" . $_SESSION['usuario']['id']);
		}
		// Créditos
		// Temas
		if($loteria_premio->tema){
			if($loteria_premio->tema && $loteria_premio->vip && !$loteria_premio->id_item){
			// Tema Ultimate Random
			$tema_ultimate_random = Recordset::query("
			select a.id,a.titulo_br,a.titulo_en,b.nome 
			from classe_imagem  a 
			join classe b
			on a.id_classe = b.id
			WHERE a.tema=1 and a.ultimate=1 and a.id not in (select id_imagem from player_imagem_tema WHERE id_usuario=" . $_SESSION['usuario']['id'].") order by rand() limit 1")->row();		

			Recordset::insert('player_imagem_tema', array(
				'id_usuario'	=> $basePlayer->id_usuario,
				'id_imagem'		=> $tema_ultimate_random->id
			));

			Recordset::insert('player_titulo', array(
				'id_player' 	=> $basePlayer->id,
				'id_usuario'	=> $basePlayer->id_usuario,
				'titulo_br'		=> $tema_ultimate_random->titulo_br,
				'titulo_en'		=> $tema_ultimate_random->titulo_en
			));

			global_message2('msg_global.tema_vip_ultimate', array($basePlayer->nome, $tema_ultimate_random->nome));


			}elseif($loteria_premio->tema && !$loteria_premio->vip && !$loteria_premio->id_item){
				// Tema Comum Random	
				// Tema Ultimate Random

				$tema_normal_random = Recordset::query("
				select a.id,a.titulo_br,a.titulo_en,b.nome 
				from classe_imagem  a 
				join classe b
				on a.id_classe = b.id
				WHERE a.tema=1 and a.ultimate=0 and a.id not in (select id_imagem from player_imagem_tema WHERE id_usuario=" . $_SESSION['usuario']['id'].") order by rand() limit 1")->row();		

				Recordset::insert('player_imagem_tema', array(
					'id_usuario'	=> $basePlayer->id_usuario,
					'id_imagem'		=> $tema_normal_random->id
				));

				Recordset::insert('player_titulo', array(
					'id_player' 	=> $basePlayer->id,
					'id_usuario'	=> $basePlayer->id_usuario,
					'titulo_br'		=> $tema_normal_random->titulo_br,
					'titulo_en'		=> $tema_normal_random->titulo_en
				));

				global_message2('msg_global.tema_vip', array($basePlayer->nome, $tema_normal_random->nome));

			}else{
				// Tema qualquer determinado	
				$tema = Recordset::query("select a.id,a.titulo_br,a.titulo_en,b.nome 
											from classe_imagem  a 
											join classe b
											on a.id_classe = b.id
											WHERE a.tema=1 and id=".$loteria_premio->id_item)->row();

				Recordset::insert('player_imagem_tema', array(
					'id_usuario'	=> $basePlayer->id_usuario,
					'id_imagem'		=> $tema->id
				));

				Recordset::insert('player_titulo', array(
					'id_player' 	=> $basePlayer->id,
					'id_usuario'	=> $basePlayer->id_usuario,
					'titulo_br'		=> $tema->titulo_br,
					'titulo_en'		=> $tema->titulo_en
				));	
				if($tema->ultimate) {
					global_message2('msg_global.tema_vip_ultimate', array($basePlayer->nome, $imagem['nome']));
				} else {
					global_message2('msg_global.tema_vip', array($basePlayer->nome, $imagem['nome']));					
				}		
			}
		}
		// Temas
		// Itens Genericos
		if($loteria_premio->id_item && $loteria_premio->mul){
			$basePlayer->addItemW($loteria_premio->id_item, $loteria_premio->mul);
		}
		// Itens Genericos
		// Equipamentos
		if($loteria_premio->equipamento){
			
			/*$drop_success = false;
			while (!$drop_success){
				$equip = Recordset::query("select * from item WHERE id_tipo in (10,11,13,14,15,29,30,31) and req_graduacao= ".$basePlayer->id_graduacao." and item.drop='1' order by rand() limit 1")->row();

				$player_item = Recordset::query("select * from player_item where id_player =". $basePlayer->id . " and id_item =". $equip->id);

				if(!$player_item->num_rows){
					Recordset::query("INSERT INTO player_item (id_player, qtd, id_item) VALUES (".$basePlayer->id.", 1, ".$equip->id.")");
					$drop_success = true;
				}
			}*/
			// Sorteia um item randomico
			$basePlayer->generate_equipment($basePlayer);
			
		}
		// Equipamentos
		// Aprimoramentos
		if($loteria_premio->aprimoramento){
			$aprimoramento = Recordset::query("select * from item WHERE id_tipo in (38) and req_graduacao= ".$basePlayer->id_graduacao." and item.drop='1' order by rand() limit 1")->row();
			$player_aprimoramento = Recordset::query("select count(1) total from player_item WHERE id_player = ".$basePlayer->id." and id_item=". $aprimoramento->id )->row();
			
			
			if($player_aprimoramento->total){
				Recordset::query("UPDATE player_item SET qtd=qtd+1 WHERE id_player = ".$basePlayer->id." AND id_item=".$aprimoramento->id);
			}else{
				Recordset::query("INSERT INTO player_item (id_player, qtd, id_item) VALUES (".$basePlayer->id.", 1, ".$aprimoramento->id.")");

			}

		}
		// Equipamentos
		// Atributos do Player
		// Ene
		if($loteria_premio->ene) {
			Recordset::query("UPDATE player_atributos SET ene=ene + " .$loteria_premio->ene . " WHERE id_player=" . $basePlayer->id);
		}
		// Int
		if($loteria_premio->inte) {
			Recordset::query("UPDATE player_atributos SET inte=inte + " .$loteria_premio->inte . " WHERE id_player=" . $basePlayer->id);
		}

		// For
		if($loteria_premio->forc) {
			Recordset::query("UPDATE player_atributos SET forc=forc + " .$loteria_premio->forc . " WHERE id_player=" . $basePlayer->id);
		}

		// Agi
		if($loteria_premio->agi) {
			Recordset::query("UPDATE player_atributos SET agi=agi + " .$loteria_premio->agi . " WHERE id_player=" . $basePlayer->id);
		}

		// Con
		if($loteria_premio->con) {
			Recordset::query("UPDATE player_atributos SET con=con + " .$loteria_premio->con . " WHERE id_player=" . $basePlayer->id);
		}

		// Res
		if($loteria_premio->res) {
			Recordset::query("UPDATE player_atributos SET res=res + " .$loteria_premio->res . " WHERE id_player=" . $basePlayer->id);
		}

		// Tai
		if($loteria_premio->tai) {
			Recordset::query("UPDATE player_atributos SET tai=tai + " .$loteria_premio->tai . " WHERE id_player=" . $basePlayer->id);
		}

		// ken
		if($loteria_premio->ken) {
			Recordset::query("UPDATE player_atributos SET ken=ken + " .$loteria_premio->ken . " WHERE id_player=" . $basePlayer->id);
		}

		// Nin
		if($loteria_premio->nin) {
			Recordset::query("UPDATE player_atributos SET nin=nin + " .$loteria_premio->nin . " WHERE id_player=" . $basePlayer->id);
		}

		// Gen
		if($loteria_premio->gen) {
			Recordset::query("UPDATE player_atributos SET gen=gen + " .$loteria_premio->gen . " WHERE id_player=" . $basePlayer->id);
		}
		// Atributos do Player
		// Pontos de Bijuu
		if($loteria_premio->bijuu){
			Recordset::query("UPDATE player_flags SET sorte_bijuu=sorte_bijuu + " .$loteria_premio->bijuu . " WHERE id_player=" . $basePlayer->id);
		}
		// Pontos de Bijuu

		
	} else {
		$json->messages	= $errors;
	}

	echo json_encode($json);
