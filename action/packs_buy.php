<?php	
    header('Content-Type: application/json');

	$json			= new stdClass();
	$json->messages	= [];
	$json->success	= false;
	$errors			= [];
	
	if(!is_numeric($_POST['id'])) {
		redirect_to('negado');
	}
	if(!is_numeric($_POST['type'])) {
		redirect_to('negado');
	}
	
	if($_SESSION['universal']){
		$pack = Recordset::query("SELECT * FROM packs WHERE (NOW() BETWEEN data_ini AND data_fim || data_ini is NULL)  AND id = ". $_POST['id'])->row();
	}else{
		$pack = Recordset::query("SELECT * FROM packs WHERE (NOW() BETWEEN data_ini AND data_fim || data_ini is NULL)  AND ativo = 1 AND id = ". $_POST['id'])->row();
	}
	
	
	if(!$pack){
		$errors[]	= "O Pacote que você tentou comprar não existe ou não esta ativo!";
	}else{
		// Verificar se o pacote tem limite disponivel para compra
		
		//Verifica se o jogador tem o requerido para comprar o pacote
		if($_POST['type'] == 1){
			if($basePlayer->getAttribute('ryou') < $pack->ryou){
				$errors[]	= "Você não tem Ryous suficientes para essa compra.";
			}
		}elseif($_POST['type'] == 2){
			if($basePlayer->getAttribute('coin') < $pack->coin){
				$errors[]	= "Você não tem créditos suficientes para essa compra.";
			}
			
		}else{
			if(Player::getFlag("fidelidade_points", $basePlayer->id) < $pack->fidelidade){
				$errors[]	= "Você não tem pontos de fidelidade suficientes para essa compra.";
			}
		}

		// Verifica o limite diário
		if($pack->limite && $basePlayer->packs_limited($pack->id) >= $pack->limite){
			$errors[]	= "Você já alcançou o limite diário desse pacote!";
		}
		
	}
	if(!sizeof($errors)) {
		$json->success	= true;
		$vip = false;
		$ryou = false;
		$fidelidade = false;
		
		// Gasta os créditos
		if($_POST['type'] == 2){
			gasta_coin($pack->coin,3333);
			$vip = true;
		}elseif($_POST['type'] == 1){	
		// Gasta os ryous
			Recordset::update('player', array(
				'ryou'	=> array('escape' => false, 'value' => 'ryou - ' . $pack->ryou)
			), array(
				'id'	=> $basePlayer->id
			));
			$ryou = true;
		}else{
			Recordset::update('player_flags', array(
				'fidelidade_points'	=> array('escape' => false, 'value' => 'fidelidade_points - ' . $pack->fidelidade)
			), array(
				'id_player'	=> $basePlayer->id
			));
			$fidelidade = true;
		}
		// Conquista --->
			arch_parse(NG_ARCH_PACOTES, $basePlayer, NULL, NULL, 1);
		// <---
		
		// Gera finalmente todos os itens para o jogador, já que ele passou por todas as verificações
		// Primeiro passo, Quantos prêmios o pacote tem?
		
		// Array de Prêmios
		$premios_array = array();
		// Array de Tipo de Prêmios
		$tipos_array = array();
		
		for ($i = 1; $i <= ( $pack->bonus  ? $pack->qtd + 1 : $pack->qtd ); $i++) {
			
			// Verifica se tem bonus
			if($pack->bonus && $i == ($pack->qtd + 1)){
				switch($pack->bonus){
					case "comida":
						$id_tipo = 9;
					break;
					case "arma":
						$id_tipo = 2;
						$item =  Recordset::query("SELECT * FROM item WHERE id_tipo=". $id_tipo ." AND id = ". $pack->bonus_item)->row();
						
					break;
					case "equipamento":
						$id_tipo = 10;
					break;
					case "fragmento":
						$id_tipo = 44;
					break;
					case "experiencia":
						$id_tipo = 42;
					case "treinamento":
						$id_tipo = 47;
					break;
					case "moeda":
						$id_tipo = 43;
					break;
					case "personagem":
						$id_tipo = 45;
						$item =  Recordset::query("SELECT * FROM item WHERE id_tipo=". $id_tipo ." AND req_item = ". $pack->bonus_item)->row();
					break;
					case "bijuu":
						$id_tipo = 48;
					break;
				}
			}else{
				$id_tipo_pack = "";
				$id_item_pack = "";
				
				if($pack->id_tipos){
					$id_tipo_pack = " AND id_tipo in (".$pack->id_tipos.")";
				}
				if($pack->id_items){
					$id_item_pack = " AND id in (".$pack->id_items.")";
				}
				
				$item =  Recordset::query("SELECT * FROM item WHERE 1=1 AND req_graduacao<=".$basePlayer->id_graduacao." ". $id_tipo_pack ." ".$id_item_pack."  ORDER BY RAND() LIMIT 1")->row();
			}
			//Verifica a chance do item caso tenha
			if($item->drop_rate > 0 && $i < ($pack->qtd + 1) && !$pack->bonus){
				$rand = rand(1,100);
				
				if($rand > $item->drop_rate){
					// Precisa refazer o item	
					$i--;
					continue;
				}
			}
			// Dá a premiação
			
			//Prêmio de Arma  ( 1 )
			if($item->id_tipo==1){
				$basePlayer->addItemW($item->id, 1);
				
				//Adiciona o ID do prêmio para o log
				array_push($premios_array, $item->id);
				//Adiciona o ID do prêmio para o log
				array_push($tipos_array, 1);
			}
			
			//Prêmio de Arma  ( 1 )
			if($item->id_tipo==2){
				if(!$pack->bonus){
					$basePlayer->addItemW($item->id, 1);
				
					//Adiciona o ID do prêmio para o log
					array_push($premios_array, $item->id);
					//Adiciona o ID do prêmio para o log
					array_push($tipos_array, 2);
				}else{
					$rand = rand(1,100);
					
					if($rand <= $item->drop_rate){
						$basePlayer->addItemW($item->id, 1);
				
						//Adiciona o ID do prêmio para o log
						array_push($premios_array, $item->id);
						//Adiciona o ID do prêmio para o log
						array_push($tipos_array, 2);
					}
				}
				
			}
			
			//Prêmio de Comida  ( 1 )
			if($item->id_tipo==9){
				$basePlayer->addItemW($item->id, 1);
				
				//Adiciona o ID do prêmio para o log
				array_push($premios_array, $item->id);
				//Adiciona o ID do prêmio para o log
				array_push($tipos_array, 9);
			}
			
			//Prêmio de Experiencia  ( usa como ramen )
			if($item->id_tipo==42){
				//$basePlayer->addItemW($item->id, 1);
				Recordset::query("UPDATE player SET exp=exp + " . $item->preco . " WHERE id=" . $basePlayer->id);
				
				
				//Adiciona o ID do prêmio para o log
				array_push($premios_array, $item->id);
				//Adiciona o ID do prêmio para o log
				array_push($tipos_array, 42);
			}
			
			//Prêmio de Ryous ( Inseri na User )
			if($item->id_tipo==43){
				Recordset::query("UPDATE player SET ryou=ryou + " . $item->preco . " WHERE id=" . $basePlayer->id);
				
				//Adiciona o ID do prêmio para o log
				array_push($premios_array, $item->id);
				//Adiciona o ID do prêmio para o log
				array_push($tipos_array, 43);
			}
			
			//Prêmio de Treino
			if($item->id_tipo==47){
				//$basePlayer->addItemW($item->id, 1);
				Recordset::query("UPDATE player SET treino_total=treino_total + " . $item->preco . " WHERE id=" . $basePlayer->id);
				
				
				//Adiciona o ID do prêmio para o log
				array_push($premios_array, $item->id);
				//Adiciona o ID do prêmio para o log
				array_push($tipos_array, 47);
			}
			//Prêmio de Bijuu
			if($item->id_tipo==48){
				Recordset::query("UPDATE player_flags SET sorte_bijuu=sorte_bijuu + " . $item->preco . " WHERE id_player=" . $basePlayer->id);
				
				
				//Adiciona o ID do prêmio para o log
				array_push($premios_array, $item->id);
				//Adiciona o ID do prêmio para o log
				array_push($tipos_array, 48);
			}
			//Prêmio de Arma ( Qtd = Durabilidade )
			
			//Prêmio de Fragmento de Classe (Fixo 3)
			if($item->id_tipo==44){
				$personagens = Recordset::query("SELECT * FROM classe ". ($pack->id_classe ? "WHERE id in (". $pack->id_classe .")" : "") ." ORDER BY RAND () LIMIT 1" )->row();
				$basePlayer->addItemP($item->id, $personagens->id);
				
				//Retorna o ID do prèmio
				$player_item = Recordset::query("SELECT * FROM player_item WHERE id_player=".  $basePlayer->id ." AND  id_item = ". $item->id ." AND id_classe= ". $personagens->id)->row();

				//Adiciona o ID do prêmio para o log
				array_push($premios_array, $player_item->id);
				//Adiciona o ID do prêmio para o log
				array_push($tipos_array, 44);
			}
			// Prêmio de Personagem
			if($item->id_tipo==45){
				
				if(!$pack->bonus){
					if(is_null($pack->id_classe)){
						//$id_classe = Recordset::query("SELECT id FROM classe WHERE inicial = 0 AND ativo = 1 ORDER BY RAND() LIMIT 1")->row()->id;
						
						//Adiciona o ID do prêmio para o log
						array_push($tipos_array,  45);
						
						$classe = $basePlayer->add_classe($pack->bonus_item);
					}else{
						$array = explode(",",$pack->id_classe);
						$classe = rand(1, count($array)) - 1;
						
						//Adiciona o ID do prêmio para o log
						array_push($tipos_array, 45);
						
						$classe = $basePlayer->add_classe($array[$classe]);
					}
					//Adiciona o ID do prêmio para o log
					array_push($premios_array, $classe);
				}else{
					$rand = rand(1,100);
					
					if($rand <= $item->drop_rate){
						if(is_null($pack->id_classe)){
							//$id_classe = Recordset::query("SELECT id FROM classe WHERE inicial = 0 AND ativo = 1 ORDER BY RAND() LIMIT 1")->row()->id;

							//Adiciona o ID do prêmio para o log
							array_push($tipos_array,  45);

							$classe = $basePlayer->add_classe($item->req_item);
						}else{
							$array = explode(",",$pack->id_classe);
							$classe = rand(1, count($array)) - 1;

							//Adiciona o ID do prêmio para o log
							array_push($tipos_array, 45);

							$classe = $basePlayer->add_classe($array[$classe]);
						}
						//Adiciona o ID do prêmio para o log
						array_push($premios_array, $classe);
					}
				}
				
			}
			//Prêmio de Equipamento ( Escolha de raridade )
			if($item->id_tipo==10){
				//Equipamento totalmente Randomico
				if(is_null($pack->equip) && is_null($pack->id_classe)){
					$basePlayer->generate_equipment($basePlayer);
					
				// Equipamento por raridade e tipo randomico
				}elseif($pack->equip && is_null($pack->id_classe)){
					$array = explode(",",$pack->equip);
					$raridade = rand(1, count($array)) - 1;
					
					$basePlayer->generate_equipment($basePlayer, $array[$raridade], NULL);
					
				// Equipamento com raridade randomica e tipo definido
				}elseif(is_null($pack->equip) && $pack->id_classe){
					$basePlayer->generate_equipment($basePlayer,NULL, $pack->id_classe);
				
				// Equipamento com raridade e tipo definido
				}else{
					$array = explode(",",$pack->equip);
					$raridade = rand(1, count($array)) - 1;
					
					$basePlayer->generate_equipment($basePlayer, $array[$raridade], $pack->id_classe);
				}
				
				//Retorna o ID do prèmio
				$player_item = Recordset::query("SELECT * FROM player_item WHERE id_player=".  $basePlayer->id ." AND id_item in(236,305,306,307,308,309) ORDER BY id desc LIMIT 1" )->row();

				//Adiciona o ID do prêmio para o log
				array_push($premios_array, $player_item->id);
				//Adiciona o ID do prêmio para o log
				array_push($tipos_array, 10);
			}
		}
		// Adiciona no Log tudo que o jogador conseguiu!
		$insert = Recordset::insert('packs_log', array(
			'id_player'			=> $basePlayer->id,
			'id_usuario'		=> $basePlayer->id_usuario,
			'id_pack'			=> $pack->id,
			'ryou'				=> $ryou ? $pack->ryou : 0,
			'coin'				=> $vip ? $pack->coin : 0,
			'fidelidade'		=> $fidelidade ? $pack->fidelidade : 0,
			'premios'			=> implode(",",$premios_array),
			'tipos'				=> implode(",",$tipos_array)
		));
		$json->logs	= $insert;
		
	} else {
		$json->messages	= $errors;
	}

	echo json_encode($json);
    
?>  
