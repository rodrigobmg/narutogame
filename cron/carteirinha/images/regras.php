<?php
	if($_SESSION['logado']) {
		if(!$basePlayer) {
			$auth = array(
				"home",
				"logoff",
				"personagem_criar",
				"personagem_selecionar",
				"rank_ninjas",
				"rank_equipes",
				"historia",
				"manual",
				"halldafama",
				"faq",
				"camisetas",
				"divulgue_nos",
				"parceiros",
				"fale_conosco",
				"regras_punicoes",
				"termos_uso",
				"politica_privacidade",
				"aviso_legal",
				"personagem_deletar",
				"personagem_selecionar_jogar",
				"personagem_criar_vila",
				"personagem_criar_historia",
				"personagem_criar_final"
			);
		} else {
			if($basePlayer->id_sala) {
				$auth = array(
					"logoff",
					"dojo_batalha_espera",
					"dojo_batalha_espera_cancela",
					"dojo_batalha_lutar",
					"inventario",
					"personagem_status",
					"historia",
					"manual",
					"halldafama",
					"faq",
					"camisetas",
					"divulgue_nos",
					"parceiros",
					"fale_conosco",
					"regras_punicoes",
					"termos_uso",
					"politica_privacidade",
					"aviso_legal"
				);
			} elseif($basePlayer->id_batalha) {
				$auth = array(
					"logoff",
					"dojo_batalha_espera",
					"inventario",
					"personagem_status",
					"historia",
					"manual",
					"halldafama",
					"faq",
					"camisetas",
					"divulgue_nos",
					"parceiros",
					"fale_conosco",
					"regras_punicoes",
					"termos_uso",
					"politica_privacidade",
					"aviso_legal"
				);
				
				$rTipoBatalha = Recordset::query("SELECT id_tipo FROM batalha WHERE id=" . $basePlayer->id_batalha)->row_array();
				
				if(on($rTipoBatalha['id_tipo'], array(1,3))) {
					$auth[] = "dojo_batalha_lutador";
					$auth[] = "dojo_lutador_lutar";
				} else {
					$auth[] = "dojo_batalha_lutar";
					$auth[] = "dojo_batalha_pvp";
				}
			}
			
			if($basePlayer->hospital) {
				$auth = array(
					"home",
					"logoff",
					"hospital_quarto",
					"personagem_status",
					"hospital_quarto_curar",
					"mensagens",
					"mensagens_bloqueio",
					"mensagens_enviar",
					"mensagens_excluir",
					"mensagens_ler",
					"historia",
					"manual",
					"halldafama",
					"faq",
					"camisetas",
					"divulgue_nos",
					"parceiros",
					"fale_conosco",
					"regras_punicoes",
					"termos_uso",
					"politica_privacidade",
					"aviso_legal",
					"senha_trocar"
				);
			}
	
			if($basePlayer->id_missao && !$basePlayer->missao[0]['interativa'] && !$basePlayer->missao[0]['quest']) {
				$auth = array(
					"home",
					"logoff",
					"personagem_status",
					"personagem_jutsu",
					"missoes_espera",
					"historia",
					"manual",
					"halldafama",
					"faq",
					"camisetas",
					"mensagens",
					"mensagens_bloqueio",
					"mensagens_enviar",
					"mensagens_excluir",
					"mensagens_ler",
					"divulgue_nos",
					"parceiros",
					"rank_ninjas",
					"rank_equipes",
					"fale_conosco",
					"regras_punicoes",
					"termos_uso",
					"politica_privacidade",
					"aviso_legal",
					"missoes_cancelar",
					"missoes_espera_final",
					"missoes_concluida",
					"missoes_concluida_finaliza",
					"senha_trocar"
				);
			}
			
			if($basePlayer->graduando) {
				$auth = array(
					"logoff",
					"personagem_status",
					"historia",
					"manual",
					"halldafama",
					"faq",
					"camisetas",
					"divulgue_nos",
					"parceiros",
					"fale_conosco",
					"regras_punicoes",
					"termos_uso",
					"politica_privacidade",
					"aviso_legal",
					"mensagens",
					"mensagens_bloqueio",
					"mensagens_enviar",
					"mensagens_excluir",
					"mensagens_ler",
					"graduacoes_espera",
					"graduacoes_cancela",
					"graduacoes_espera_final",
					"graduacoes_conclusao",
					"graduacoes_final",
					"senha_trocar"
				);
			}
			
			 if(!($basePlayer->id_batalha || ($basePlayer->missao && !$basePlayer->missao[0]['interativa'] && !$basePlayer->missao[0]['quest']) || $basePlayer->hospital || $basePlayer->id_sala || ($basePlayer->id_missao && !$basePlayer->missao[0]['interativa'] && !$basePlayer->missao[0]['quest']) || $basePlayer->graduando || $basePlayer->treinando || $basePlayer->id_batalha_externa)) {
				$auth = array(
					"home",
					"logoff",
					"personagem_status",
					"personagem_jutsu",
					"personagem_elementos",
					"personagem_elementos_aprender",
					"personagem_elementos_final",
					"portoes",
					"portoes_treinar",
					"clas",
					"especializacao",
					"selo",
					"selo_entrar",
					"selo_treinar",
					"selo_sair",
					"invocacao",
					"invocacao_entrar",
					"invocacao_treinar",
					"invocacao_sair",
					"equipe",
					"guilds",
					"bijuus",
					"guild_detalhe",
					//"guild_detalhe_desafio",
					//"guild_desafio",
					//"guild_desafio_marcar",
					//"guild_desafio_aceitar_canelar",
					//"guild_desafio_espera",
					//"guild_desafio_espera_atualizar",
					
					"dojo_batalha_lutar",
					
					"rank_ninjas",
					"rank_equipes",
					"historia",
					"manual",
					"halldafama",
					"faq",
					"camisetas",
					"divulgue_nos",
					"parceiros",
					"fale_conosco",
					"regras_punicoes",
					"termos_uso",
					"politica_privacidade",
					"aviso_legal",
					"profile",
					"senha_trocar"
				);
				
				if($basePlayer->portao) {
					$auth[] = "portoes_sair";	
				}
				
				if(method_exists($basePlayer, "getElementos")) {
					if(sizeof($basePlayer->getElementos())) {
						$auth[] = "personagem_elementos_sair";
					}
				}
				
				if($basePlayer->id_guild) {
					$auht[] = "guild_criado";
					$auth[] = "guild_forum";
					$auth[] = "guild_forum_topico";
					$auth[] = "guild_forum_topico_novo";
					$auth[] = "guild_forum_topico_responder";
				} else {
					$auth[] = "guild_criar";
					$auth[] = "guild_entrar";
				}
				
				if($basePlayer->id_vila_atual == $basePlayer->id_vila) {
					$auth[] = "vila_forum";
					$auth[] = "vila_forum_topico";
					$auth[] = "vila_forum_topico_novo";
					$auth[] = "vila_forum_topico_responder";
				}
				
				if($basePlayer->id_vila_atual) {
					//"dojo_batalha_lutador",
					$authb = array(
						"academia_treinamento",
						"academia_jutsu",
						"graduacoes",
						"graduacoes_graduar",
						"dojo",
						"ninja_shop",
						"ramen_shop",
						"dojo_batalha_listar",
						"dojo_lutador_criar",
						"dojo_lutador_lutar",
						
						
						"academia_treinamento_jutsu",
						"academia_treinamento_treinar",
						"academia_treinamento_auto",
						"graduacoes_final",
						"graduacoes_graduar",
						"graduacoes_espera_final",
						"missoes_aceitar",
						"dojo_batalha_criar",
						
						"ninja_shop_compra",
						"ninja_shop_lista",
						"senha_trocar"
					);
					//	"dojo_batalha_pvp",
					
					// Se tiver cl�
					if(!$basePlayer->id_cla) {
						$auth[] = "cla_entrar";	
					} else {
						$auth[] = "cla_treinar";
						$auth[] = "cla_sair";
					}

					if(!$basePlayer->id_especializacao) {
						$auth[] = "especializacao_entrar";	
					} else {
						$auth[] = "especializacao_treinar";
						$auth[] = "especializacao_sair";
					}

					
					$auth = array_merge($auth, $authb);
				}
				
				if($basePlayer->id_graduacao != 1) {
					// Miss�es interatiavas e de quest
					if($basePlayer->id_missao && ($basePlayer->missao[0]['interativa'] || $basePlayer->missao[0]['quest'])) {
						$auth[] = "missoes_status";
						$auth[] = "missoes_cancelar";
						$auth[] = "missoes_concluida_finaliza";
					} else {
						$auth[] = "missoes";
						$auth[] = "missoes_equipe";
						$auth[] = "missoes_especiais";
					}

					$auth[] = "mapa_vila";
					
					if(!$basePlayer->id_vila_atual) {
						$auth[] = "mapa";
						$auth[] = "mapa_posicoes";
						$auth[] = "mapa_batalha";
						$auth[] = "mapa_image";			
					}

					if(!$basePlayer->dentro_vila) {
						$auth[] = "mapa_posicoes_vila";
						$auth[] = "mapa_image_vila";
						$auth[] = "mapa_batalha_vila";						
					}


				} else {
					$auth[] = "licoes";				
				}

				$auth[] = "mensagens";
				$auth[] = "mensagens_bloqueio";
				$auth[] = "inventario";
				$auth[] = "mensagens_enviar";
				$auth[] = "mensagens_ler";
				$auth[] = "mensagens_excluir";
			}
			
			if(!$basePlayer->id_batalha && $basePlayer->id_vila_atual) {
				$auth[] = "personagem_selecionar_jogar";
				$auth[] = "personagem_deletar";
				$auth[] = "personagem_selecionar";
			}

			$auth[] = "inventario";
		}
		
		if($basePlayer->treinando) {
			$auth[] = "personagem_status";	
			$auth[] = "personagem_status";	
			$auth[] = "treino_automatico";
			$auth[] = "treino_automatico_finaliza";
			$auth[] = "mensagens";
			$auth[] = "mensagens_enviar";
			$auth[] = "mensagens_ler";
			$auth[] = "mensagens_excluir";
			$auth[] = "mensagens_bloqueio";
			$auth[] = "inventario";
			$auth[] = "senha_trocar";
			$auth[] = "home";
		}
		
		if(method_exists($basePlayer, "getNextLevel")) {
			if($basePlayer->exp >= Player::getNextLevel($basePlayer->level)) {
				$auth[] = "proximo_nivel";
			}
		}
		
		if($basePlayer->id_equipe) {
			$auth[] = "equipe_detalhe";
			$auth[] = "equipe_forum";
			$auth[] = "equipe_forum_topico";
			$auth[] = "equipe_forum_topico_novo";
			$auth[] = "equipe_forum_topico_responder";
		} else {
			$auth[] = "equipe_detalhe";
			$auth[] = "equipe_participar";
			$auth[] = "equipe_participar_final";
			$auth[] = "equipe_criar";
			$auth[] = "equipe_criar_final";
		}
		
		if($basePlayer->id_batalha_externa) {
			$auth[] = "home";
			$auth[] = "senha_trocar";
			$auth[] = "mensagens";
			$auth[] = "mensagens_enviar";
			$auth[] = "mensagens_ler";
			$auth[] = "mensagens_excluir";
			$auth[] = "mensagens_bloqueio";
			$auth[] = "inventario";
			$auth[] = "personagem_status";
		
			// Batalha de guilds --->
				/*
				if($basePlayer->id_batalha_externa == 1) {
					$auth[] = "guild_desafio_status";
					$auth[] = "guild_desafio_status_atualizar";
					
					if(!$basePlayer->hospital && !$basePlayer->batalha_guild_bloqueio) {
						$auth[] = "guild_desafio_mapa";
						$auth[] = "mapa_image_guild";
						$auth[] = "mapa_posicoes_guild";
						$auth[] = "mapa_batalha_guild";
					}
				}*/
			// <---
		}
	
		$auth[] = "jogador_vip";
		$auth[] = "jogador_vip_compra";
		$auth[] = "comprando_vip";
		$auth[] = "confirma_pgto_bco";
		$auth[] = "ler_noticia_comentar";
		$auth[] = "home_ranking";
		$auth[] = "comprando_vip_compra";
		$auth[] = "comprando_vip_plano";
		$auth[] = "manual";
		$auth[] = "halldafama";
		$auth[] = "faq";
		$auth[] = "camisetas";
		$auth[] = "fale_conosco";
		$auth[] = "regras_punicoes";
		$auth[] = "termos_uso";
		$auth[] = "politica_privacidade";
		$auth[] = "aviso_legal";
		$auth[] = "usuario_dados";
		$auth[] = "usuario_dados_editar";
		$auth[] = "senha_trocar";
		
		$auth[] = "diplomacia";
		
		if($basePlayer->vila_ranking && $basePlayer->vila_ranking <= 3) {
			$auth[] = "diplomacia_votar";
		}
		
		if($basePlayer->trava_pagto) {
			$auth[] = "comprando_vip_banco";
			$auth[] = "comprando_vip_cancela";
			$auth[] = "comprando_vip_finaliza";
		}
	} else {
		$auth = array(
			"home",
			"cadastro",
			"ativar_conta_manual",
			"recuperar_senha",
			"login",
			"historia",

			"manual",
			"halldafama",
			"faq",
			"camisetas",
			"divulgue_nos",
			"parceiros",
			"fale_conosco",
			"regras_punicoes",
			"termos_uso",
			"politica_privacidade",
			"aviso_legal",
			"ativacao_ativado",
			"ativacao_enviar",
			"ativacao_erro",
			"ativar",
			"ativar_conta_manual_ativar",
			"recuperar_senha_recuperar",
			"captcha",
			"cadastro_cadastrar",
			"home_ranking"
		);
	}
	
	if($basePlayer->hospital) {
		$auth[] = "hospital_quarto";
		$auth[] = "hospital_quarto_curar";
	}

	$auth[] = "negado";
	$auth[] = "atualizador";
	$auth[] = "ler_noticia";
	$auth[] = "dojo_batalha_mensagem";
	$auth[] = "captcha";
	$auth[] = "profile";
	$auth[] = "vantagens";
	$auth[] = "link";
	$auth[] = "logoff";
	$auth[] = "carteirinha";

	$section = $basePlayer->id_batalha ? "dojo_batalha_mensagem" : "negado";

	if($is_action && !in_array($_GET['acao'], $auth)) {
		// BUCETA DE IF FDP SO PRA MERDA DO MAPA E AS REQ ASYNCRONAS --->
			if($_GET['acao'] == "mapa_posicoes" && $basePlayer->id_vila_atual) {
				$redir_script = true;

				// PATCH DE CORNO -->
					$r = Recordset::query("SELECT x,y FROM local_mapa WHERE mlocal=5 AND id_vila=" . $basePlayer->id_vila_atual)->row_array();

					Recordset::query("UPDATE player SET xpos=" . (int)$r['x'] . ", ypos=" . (int)$r['y'] . " WHERE id=" . $basePlayer->id);
				// <---

				redirect_to("mapa_vila");
			}
		// <---

		if(headers_sent()) {
			echo "<script>location.href='?secao={$section}'</script>";
		} else {
			if($_SERVER['HTTP_X_REQUESTED_WITH']) {
				$redir_script = true;
			}

			redirect_to($section);
		}
	} elseif (!$is_action && !in_array($_GET['secao'], $auth)) {
		if(headers_sent()) {
			echo "<script>location.href='?secao={$section}'</script>";
		} else {
			redirect_to($section, array("type" => "1"));
		}
	}
