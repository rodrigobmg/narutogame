<?php
	if(isset($_POST['begin']) && $_POST['begin']) {
		$redir_script	= true;

		if($basePlayer->missao_comum) {
			redirect_to('negado');
		}

		$basePlayer	= new Player($basePlayer->id);
		$baseEnemy	= gzunserialize($_SESSION['dojoEnemy']);

		$total		= 5 + $basePlayer->bonus_vila['dojo_limite_npc_dojo'];
		$batalhas	= Recordset::query("SELECT COUNT(id) AS total FROM player_batalhas_npc WHERE id_player=" . $basePlayer->id)->row_array();

		if($batalhas['total'] >= $total) {
			redirect_to('negado', null, array('e' => 'limit'));
		}

		// segurança para ver se o jogador já estava em combate.
		if($basePlayer->id_batalha != 0) {
			die('jalert("'.t('actions.a72').'!", null, function () { location.reload() })');
		}

		Fight::cleanup();

		// Insere a batalha npc na tabela de contagem
		Recordset::insert('player_batalhas_npc', array(
			'id_player'	=> $basePlayer->id
		));

		$batalha		= Recordset::insert('batalha', array(
			'id_player'		=> $basePlayer->id,
			'id_tipo'		=> 1,
			'current_atk'	=> 1,
			'data_atk'		=> array('escape' => false, 'value' => 'NOW()')
		));

		Recordset::update('player', array(
			'id_batalha'	=> $batalha
		), array(
			'id'			=> $basePlayer->id
		));

		$baseEnemy->batalha_id	= $batalha;
		$baseEnemy->parseModifiers();
		$baseEnemy->atCalc();

		SharedStore::S('_BATALHA_' . $basePlayer->id, serialize($baseEnemy));
		unset($_SESSION['dojoEnemy']);

		redirect_to("dojo_batalha_lutador");
	}

	if(!isset($_POST['_pvpToken']) || (isset($_POST['_pvpToken']) && $_POST['_pvpToken'] != $_SESSION['_pvpToken'])) {
		//if(!isset($_SESSION['_pvpToken'])) {
			error_log('batalha NPC ' . $basePlayer->id_batalha . 'UA --> ' . $_SERVER['HTTP_USER_AGENT'], E_USER_NOTICE);
		//}

		die('jalert("'.t('actions.a37').', '.t('actions.a38').'", null, function () { location.reload() })');
	}

	if(!$basePlayer->id_batalha) {
		die('clearInterval(_pvpTimer); jalert("'.t('actions.a38').'", null, function() { location.reload() })');
	}

	$process	= false;

	// Timer do NPC --->
		$batalha	= Recordset::query('SELECT id, data_atk, finalizada, current_atk FROM batalha WHERE id=' . $basePlayer->id_batalha)->row_array();
		$future		= strtotime('+2 minutes, +30 seconds', strtotime($batalha['data_atk']));

		if(!$_SESSION['universal']) {
			if(now() > $future && !$batalha['finalizada']) {
				if(!method_exists($basePlayer, 'getAttribute')) {
					$basePlayer = new Player($basePlayer->id);
				}

				$basePlayer->setAttribute('hospital', 1);
				$basePlayer->setAttribute('less_hp', $basePlayer->getAttribute('max_hp'));
				$basePlayer->setAttribute('id_vila_atual', $basePlayer->getAttribute('id_vila'));
				$basePlayer->setAttribute('dentro_vila', '1');

				Recordset::update('batalha', array(
					'finalizada'	=> 1,
					'vencedor'		=> 0,
					'pvp_wo'		=> 1,
					'data_fim'		=> date('Y-m-d H:i:s')
				), array(
					'id'			=> $batalha['id']
				));

				$process	= true;
			}
		}
	// <---

	$batalha	= Recordset::query('SELECT * FROM batalha WHERE id=' . $basePlayer->id_batalha)->row_array();

	if(isset($_POST['itemID']) || isset($_POST['action']) || isset($_POST['flight']) || $batalha['finalizada']) {
		$process = true;
	}
?>
<?php if($process): ?>
	<?php
		if(!method_exists($basePlayer, 'getAttribute')) {
			$basePlayer = new Player($basePlayer->id);
		}

		$baseEnemy	= unserialize(SharedStore::G('_BATALHA_' . $basePlayer->id));
		$item		= isset($_POST['itemID']) && $basePlayer->hasItem($_POST['itemID']) ? $_POST['itemID'] : 0;
		$turnos		= SharedStore::G('_TRL_' . $basePlayer->id, array());
		$fight		= new Fight($batalha['id']);
		$titulo		= '';
		$conv_my	= percentf(85,$basePlayer->getAttribute('conv_calc'));
		$conv_en	= percentf(85,$baseEnemy->getAttribute('conv_calc'));

		$basePlayer->setLocalAttribute('less_conv', $baseEnemy->getAttribute('conv_calc'));
		$baseEnemy->setLocalAttribute('less_conv', $basePlayer->getAttribute('conv_calc'));

		$baseEnemy->parseModifiers();

		$basePlayer->atCalc();
		$baseEnemy->atCalc();

		if($batalha['finalizada']) {
			$msg		= '';
			$vitoria	= false;
			$derrota	= false;
			$cvitoria	= false;
			$cderrota	= false;
			$dropped	= array();
			$wo			= false;

			if($batalha['empate']) {
				if(!$baseEnemy->npc_torneio) {
					if($batalha['id_tipo']!=8){
						$basePlayer->setAttribute('empates_npc', $basePlayer->getAttribute('empates_npc') + 1);		

						//Adicionado para o Rank Diario
						Recordset::query("UPDATE player_batalhas_status SET empates = empates + 1, empates_semana = empates_semana + 1, empates_mes = empates_mes + 1, empates_geral = empates_geral + 1 WHERE id_player = ". $basePlayer->id ."");
					}else{
						$player_sensei 	= Recordset::query("SELECT * FROM player_sensei_desafios WHERE id_player=" . $basePlayer->id . " AND id_sensei = ". $basePlayer->id_sensei)->row_array();
						if($player_sensei){
							Recordset::query("UPDATE player_sensei_desafios SET draws = draws + 1 WHERE id_player = ".	$basePlayer->id ." AND id_sensei = ". $basePlayer->id_sensei);
						}else{
							$batalha = Recordset::insert('player_sensei_desafios', array(
								'id_player'		=> $basePlayer->id,
								'id_sensei'		=> $basePlayer->id_sensei,
								'draws'			=> 1
							));
						}
					}
				}

				$msg	= t('actions.a39');
				$titulo	= t('actions.a40');

				$basePlayer->setAttribute('hospital', '1');
				$basePlayer->setAttribute('dentro_vila', '1');

				hospital_vila_cura();

				if(!$basePlayer->getAttribute('id_vila_atual')) {
					$basePlayer->setAttribute('id_vila_atual', $basePlayer->getAttribute('id_vila'));
				}

				/*
				$msg .= 'Como recompensa você estará recebendo RY$ ' . floor(@($baseEnemy->getAttribute('ryou') / 2)) . ' e ' . floor(@($baseEnemy->getAttribute('exp') / 2)) . ' pontos de experiência<br /> Voltar ao <a href="?secao=mapa">Mapa</a>';

				$basePlayer->setAttribute('exp',  $basePlayer->getAttribute('exp')  + floor(@($baseEnemy->getAttribute('exp') / 2)));
				$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') + floor(@($baseEnemy->getAttribute('ryou') / 2)));
				*/

				$msg .= '<br />Ir para o <a href="?secao=hospital_quarto" class="linkTopo">'.t('actions.a58').'</a>';
			} elseif($batalha['finalizada']) {
				if($batalha['flight_id']) {
					if($batalha['vencedor'] == $basePlayer->id) { // Eu ganhei(fuga)
						$msg		= t('actions.a44');
						$titulo		= t('actions.a52');

						$vitoria	= true;

						if(!$basePlayer->getAttribute('id_torneio')) {
							$cvitoria	= true;
						}
					} else {
						$exp	= $baseEnemy->getAttribute('exp');
						$titulo	= t('actions.a46');
						$msg	= t('actions.a73').' ';

						if(!$baseEnemy->npc_torneio) {
							$msg .= ' '.t('actions.a74').' '. $exp .' '. t('actions.a42').'.';
						}

						$basePlayer->setAttribute('fugas', $basePlayer->getAttribute('fugas') + 1);

						//Adicionado para o Rank Diario
						Recordset::query("UPDATE player_batalhas_status SET fugas = fugas + 1, fugas_semana = fugas_semana + 1, fugas_mes = fugas_mes + 1, fugas_geral = fugas_geral + 1 WHERE id_player = ". $basePlayer->id ."");

						// Recompensa log
						Recordset::insert('player_recompensa_log', array(
							'fonte'		=> 'npc',
							'id_player'	=> $basePlayer->id,
							'exp'		=> $exp,
							'recebido'	=> 1
						));

						$basePlayer->setAttribute('exp', $basePlayer->getAttribute('exp') + $exp);
					}

					// Na fuga essa porra tira id batalha
					if ($baseEnemy->npc_guerra_s) {
						Recordset::update('guerra_ninja_npcs', [
							'batalha'	=> 0
						], [
							'id'		=> $baseEnemy->id
						]);
					}

					if($batalha['id_tipo'] == 1) {
						$msg .='<br /><br />'.t('actions.a48').' <a href="?secao=dojo" class="linkTopo">Dojo</a>';
					}elseif ($batalha['id_tipo'] == 8){
						$msg .='<br /><br />'.t('actions.a48').' <a href="?secao=desafios" class="linkTopo">Desafio do Sensei</a>';
					} else {
						if($basePlayer->getAttribute('id_vila_atual')) {
							$msg .= '<br /><br />'.t('actions.a48').' <a href="?secao=mapa_vila" class="linkTopo">'.t('actions.a49').'</a>';
						} else {
							$msg .= '<br /><br />'.t('actions.a48').' <a href="?secao=mapa" class="linkTopo">'.t('actions.a50').'</a>';
						}
					}
				} elseif($batalha['pvp_wo']) {
					if($batalha['vencedor'] == $basePlayer->id) { // Eu ganhei(inatividade)
						$msg		= t('actions.a44');
						$titulo		= t('actions.a52');

						$vitoria	= true;

						if(!$basePlayer->getAttribute('id_torneio')) {
							$cvitoria	= true;
						}
					} else {
						$msg 		= t('actions.a53');
						$titulo		= t('actions.a54');

						$derrota	= true;
						$cderrota	= true;
						$wo			= true;
					}

					$basePlayer->setAttribute('id_vila_atual', $basePlayer->getAttribute('id_vila'));
					$basePlayer->setAttribute('dentro_vila', 1);

					$msg .= '<br /><a href="?secao=personagem_status" class="linkTopo">'.t('actions.a75').'</a> '.t('actions.a76');
				} else {
					if($batalha['vencedor'] == $basePlayer->id) { // Eu ganhei
						$msg		= t('actions.a55');
						$vitoria	= true;
						$cvitoria	= true;

						// Bonus vila
						if($basePlayer->id_vila_atual) {
							$baseEnemy->setLocalAttribute('ryou', $baseEnemy->getAttribute('ryou') + percent($basePlayer->bonus_vila['dojo_ryou_npc'], $baseEnemy->getAttribute('ryou')));
							$baseEnemy->setLocalAttribute('exp', $baseEnemy->getAttribute('exp') + percent($basePlayer->bonus_vila['dojo_exp_npc'], $baseEnemy->getAttribute('exp')));
						} else {
							$baseEnemy->setLocalAttribute('ryou', $baseEnemy->getAttribute('ryou') + percent($basePlayer->bonus_vila['mapa_ryou'], $baseEnemy->getAttribute('ryou')));
							$baseEnemy->setLocalAttribute('exp', $baseEnemy->getAttribute('exp') + percent($basePlayer->bonus_vila['mapa_exp'], $baseEnemy->getAttribute('exp')));
						}
					} else { // Eu perdi
						$derrota	= true;

						if(!$baseEnemy->npc_vila && !$baseEnemy->npc_evento && !$baseEnemy->npc_guerra && !$baseEnemy->npc_guerra_s) { // Conta derrota
							$cderrota	= true;
						}

						$msg	= t('actions.a56');
						$titulo	= t('actions.a57');
					}

					// Esse é o objetivo de "batalhas", então, ganhar, perder ou empatar, vai contar
					guild_objetivo_exp($basePlayer, 6);
					vila_objetivo_exp($basePlayer, 6);

					// Log dos caras que lutaram com npcs do guerra ninja
					if ($baseEnemy->npc_guerra) {
						Recordset::insert('guerra_ninja_npc_player', [
							'id_player'				=> $basePlayer->id,
							'id_guerra_ninja'		=> $basePlayer->id_guerra_ninja,
							'id_guerra_ninja_npc'	=> $baseEnemy->id,
							'vitoria'				=> $vitoria ? 1 : 0
						]);

						// Remove as flags dos npcs
						if ($vitoria) {
							Recordset::update('guerra_ninja_npcs', [
								'morto'		=> 1,
								'mapa'		=> 0,
								'batalha'	=> 0
							], [
								'id'		=> $baseEnemy->id
							]);

							Recordset::update('batalha', [
								'vencedor'				=> ['escape' => false, 'value' => 'id_player'],
								'finalizada'			=> 1,
								'data_fim'				=> date('Y-m-d H:i:s')
							], [
								'id_npc_guerra_ninja'	=> $baseEnemy->id
							]);

							arch_parse(NG_ARCH_GUERRA, $basePlayer, $baseEnemy);
						} else {
							Recordset::update('guerra_ninja_npcs', [
								'batalha'	=> 0
							], [
								'id'		=> $baseEnemy->id
							]);
						}
					}
				}

				if($vitoria) {
					// Condições para missões e etc --->
						// Missão itnerativa, missão de equipe ou Missão diária de guild
						if($basePlayer->getAttribute('id_missao') && $baseEnemy->npc_missao || (($basePlayer->getAttribute('id_missao_guild') || $basePlayer->getAttribute('id_missao_guild2')) && ($baseEnemy->npc_diaria || $baseEnemy->npc_diaria2))) {
							if($baseEnemy->npc_missao) {
								// Conquista
								if($basePlayer->getAttribute('missao_equipe')) { // Conquista equipe
									arch_parse(NG_ARCH_NPC_EQUI, $basePlayer, $baseEnemy);

								} else { // Conquista NPC PVP MAPA
									arch_parse(NG_ARCH_NPC_MAPA, $basePlayer, $baseEnemy);
								}

								$needed_items	= Recordset::query('SELECT * FROM quest_npc_item WHERE id_quest=' . $basePlayer->getAttribute('id_missao'), true);
								//on($player->id_quest, array(quets))

								//if($basePlayer->getAttribute('id_missao')=='419'){
								if(on($basePlayer->getAttribute('id_missao'), array(419,420,421,422,423))){
									$drops	= Recordset::query('SELECT * FROM npc_drop WHERE id_npc=' . $baseEnemy->id, true);

								}else{
									$drops	= Recordset::query('SELECT * FROM npc_drop WHERE id_quest=' . $basePlayer->getAttribute('id_missao') . ' AND id_npc=' . $baseEnemy->id, true);
								}

								$needed_items	= $needed_items->result_array();
							} else {
								if($baseEnemy->npc_diaria) {
									$needed_items	= Recordset::query('SELECT * FROM quest_guild_npc_item WHERE id_quest_guild=' . $basePlayer->getAttribute('id_missao_guild'), true);
								} elseif($baseEnemy->npc_diaria2) {
									$is_quest_and_ignore	= true;
									$needed_items			= Recordset::query('SELECT * FROM quest_guild_npc_item WHERE id_quest_guild=' . $basePlayer->getAttribute('id_missao_guild2'), true);
								}

								$drops			= $needed_items;
								$needed_items	= $needed_items->result_array();
							}

							foreach($needed_items as $needed_item) {
								$sets		= array();
								$is_target	= false;
								$is_item	= false;

								if($basePlayer->getAttribute('missao_equipe') && $baseEnemy->npc_missao) { // Missão equipe
									$have = Recordset::query('
										SELECT
											qtd AS npc_total
										FROM
											equipe_quest_npc
										WHERE
											id_player=' . $basePlayer->id . ' AND
											id_equipe=' . $basePlayer->id_equipe . ' AND
											id_player_quest=' . $basePlayer->getAttribute('id_missao') . ' AND
											id_npc=' . $baseEnemy->id)->row_array();

									// Se ja concluiu a quest na qual esse npc pertence, não ganha nenhum exp/ryou
									if(Recordset::query('SELECT id FROM player_quest WHERE id_player=' . $basePlayer->id . ' AND id_equipe!=' . $basePlayer->id_equipe . ' AND id_quest=' . $basePlayer->getAttribute('id_missao'))->num_rows) {
										$baseEnemy->setLocalAttribute('exp', 0);
										$baseEnemy->setLocalAttribute('ryou', 0);
									}
								} elseif($basePlayer->getAttribute('id_missao_guild') && $baseEnemy->npc_diaria) { // Missão diaria da guild
									$have = Recordset::query('
										SELECT
											*
										FROM
											player_quest_guild_npc_item
										WHERE
											id_player=' . $basePlayer->id . ' AND
											id_quest_guild=' . $basePlayer->getAttribute('id_missao_guild') . ' AND
											id_npc=' . $baseEnemy->id . ' AND
											id_item=' . $needed_item['id_item'])->row_array();
								} elseif($basePlayer->getAttribute('id_missao_guild2') && $baseEnemy->npc_diaria2) { // Missão diaria da guild
									$have = Recordset::query('
										SELECT
											*
										FROM
											guild_quest_npc_item
										WHERE
											id_guild=' . $basePlayer->getAttribute('id_guild') . ' AND
											id_quest_guild=' . $basePlayer->getAttribute('id_missao_guild2') . ' AND
											id_npc=' . $baseEnemy->id . ' AND
											id_item=' . $needed_item['id_item'])->row_array();

									$cvitoria = false;
								} else { // Missão normal
									$have = Recordset::query('
										SELECT
											*
										FROM
											player_quest_npc_item
										WHERE
											id_player=' . $basePlayer->id . ' AND
											id_player_quest=' . $basePlayer->getAttribute('id_missao') . ' AND
											id_npc=' . $baseEnemy->id . ' AND
											id_item=' . $needed_item['id_item'])->row_array();
								}

								// É o npc q eu preciso e e não matei o tanto que precisa
								if($needed_item['id_npc'] == $baseEnemy->id && $have['npc_total'] < $needed_item['npc_total']) {
									$is_target			= true;
									$sets['npc_total']	= array('escape' => false, 'value' => 'npc_total+1');											
								} elseif($needed_item['id_npc'] == $baseEnemy->id && $have['npc_total'] >= $needed_item['npc_total']) { // EU ja tenho o tanto que preciso do npc? nao ganha mais ryou nem exp
									$baseEnemy->setLocalAttribute('exp', 0);
									$baseEnemy->setLocalAttribute('ryou', 0);

									// Não conta vitoria depois de ter matado o total(e remove os drops de mapa)
									$cvitoria			= false;
									$baseEnemy->drops	= [];
								}

								// Verifica os drops
								foreach($drops->result_array() as $drop) {
									if(!has_chance($drop['chance'])) {
										continue;
									}

									if($needed_item['id_npc'] == $baseEnemy->id && $drop['id_item'] == $needed_item['id_item'] && $have['item_total'] < $needed_item['item_total']) {
										$dropped[]	= $drop;

										$is_item			= true;
										$sets['item_total']	= array('escape' => false, 'value' => 'item_total+' . $drop['total']);
									}
								}

								if($basePlayer->getAttribute('missao_equipe') && $is_target && $baseEnemy->npc_missao) { // Se for missão de equipe e eu matei o meu npc
									Recordset::update('equipe_quest_npc', array(
										'qtd'	=> 1
									), array(
										'id_player'	=> $basePlayer->id,
										'id_equipe'	=> $basePlayer->getAttribute('id_equipe'),
										'id_npc'	=> $needed_item['id_npc']
									));
								} elseif($basePlayer->getAttribute('id_missao_guild') && ($is_target || $is_item) && $baseEnemy->npc_diaria) { // Se for missão diária
									Recordset::update('player_quest_guild_npc_item', $sets, array(
										'id_player'			=> $basePlayer->id,
										'id_quest_guild'	=> $basePlayer->getAttribute('id_missao_guild'),
										'id_npc'			=> $baseEnemy->id,
										'id_item'			=> $needed_item['id_item']
									));
								} elseif($basePlayer->getAttribute('id_missao_guild2') && ($is_target || $is_item) && $baseEnemy->npc_diaria2) { // Se for missão diária[2]
									Recordset::update('guild_quest_npc_item', $sets, array(
										'id_guild'			=> $basePlayer->getAttribute('id_guild'),
										'id_quest_guild'	=> $basePlayer->getAttribute('id_missao_guild2'),
										'id_npc'			=> $baseEnemy->id,
										'id_item'			=> $needed_item['id_item']
									));
								} elseif($is_item || $is_target) { // Missão normal
									Recordset::update('player_quest_npc_item', $sets, array(
										'id_player'			=> $basePlayer->id,
										'id_player_quest'	=> $basePlayer->getAttribute('id_missao'),
										'id_npc'			=> $baseEnemy->id,
										'id_item'			=> $needed_item['id_item']
									));
								}
							}

							$msg .= t('actions.a41').' RY$ ' . $baseEnemy->getAttribute('ryou') .' '. t('geral.e') . ' ' . $baseEnemy->getAttribute('exp') .' '. t('actions.a42').' <br /><br />'.t('actions.a48').' <a class="linkTopo" href="?secao=mapa">'.t('actions.a77').'</a>';
						}

						// Missão de matar o guardião
						if($basePlayer->getAttribute('missao_invasao') && $baseEnemy->npc_vila) {
							// Conquista --->
								arch_parse(NG_ARCH_NPC_VILA, $basePlayer, $baseEnemy);
							// <---

							$npc_vila	= Recordset::query('SELECT nome, id_vila, mlocal FROM npc_vila WHERE id=' . $baseEnemy->id, true)->row_array();
							$vila		= Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM vila WHERE id=' . $npc_vila['id_vila'], true);

							// Valores fixos de bonus --->
								Recordset::update('player', array(
									'treino_total'			=> array('escape' => false, 'value' => 'treino_total+5000'),
									'exp'					=> array('escape' => false, 'value' => 'exp+10000'),
									'ryou'					=> array('escape' => false, 'value' => 'ryou+20000'),
									'vila_quest_vitorias'	=> array('escape' => false, 'value' => 'vila_quest_vitorias+1')
								), array(
									'id_guild'				=> $basePlayer->getAttribute('id_guild'),
									'id'					=> array('escape' => false, 'mode' => 'not', 'value' => $basePlayer->id)
								));
								$basePlayer->setAttribute('treino_total',  $basePlayer->getAttribute('treino_total')  + 5000);
								$basePlayer->setAttribute('exp',  $basePlayer->getAttribute('exp')  + 10000);
								$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') + 20000);
								$basePlayer->setAttribute('vila_quest_vitorias', $basePlayer->getAttribute('vila_quest_vitorias') + 1);

							// <---

							// Derrota da vila
							Recordset::update('vila', array(
								'derrotas'	=> array('escape' => false, 'value' => 'derrotas+1')
							), array(
								'id'		=> $npc_vila['id_vila']
							));

							//Adiciona o Bonus para a org que derrotar o npc
							vila_exp(250, $basePlayer->id_vila);

							// Adiciona a Vitoria na Vila da Org
							Recordset::update('vila', array(
								'vitorias'	=> array('escape' => false, 'value' => 'vitorias+1')
							), array(
								'id'		=> $basePlayer->id_vila
							));

							// Vitoria da guild
							Recordset::update('guild', array(
								'vitorias'	=> array('escape' => false, 'value' => 'vitorias+1'),
								'npc_morto'	=> '1'
							), array(
								'id'		=> $basePlayer->getAttribute('id_guild')
							));

							// Atualiza o morto do npc e da as derrtoas --->
								Recordset::update('local_mapa', array(
									'reduzido'	=> '1'
								), array(
									'mlocal'	=> $npc_vila['mlocal'],
									'id_vila'	=> $npc_vila['id_vila']
								));

								Recordset::update('npc_vila', array(
									'morto'				=> '1',
									'id_player_batalha'	=> '0',
									'batalha'			=> '0',
									'tempo_derrota'		=> date('Y-m-d H:i:s')
								), array(
									'id'				=> $baseEnemy->id
								));

								Recordset::update('vila_quest', array(
									'id_guild'	=> 0
								), array(
									'id'		=> $basePlayer->getAttribute('missao_invasao')
								));
							// <---

							// Manda mensagens para os membros da guild --->
								$players = new Recordset('SELECT id FROM player WHERE id_guild=' . $basePlayer->getAttribute('id_guild'));

								foreach($players->result_array() as $p) {
									mensageiro(NULL, $p['id'], t('actions.a78'), t('actions.a79'), 'guild');

									// Recompensa log
									Recordset::insert('player_recompensa_log', array(
										'fonte'		=> 'npc_vila',
										'id_player'	=> $p['id'],
										'exp'		=> 10000,
										'ryou'		=> 20000,
										'recebido'	=> 1
									));
								}
							// <---

							$msg .= t('actions.a80').'<br /><br />'.t('actions.a48').' <a class="linkTopo" href="?secao=mapa_vila">'.t('actions.a49').'</a>';

							global_message2('msg_global.guild', array($basePlayer->nome_guild, $npc_vila['nome'], $vila->row()->nome));
						}

						// Evento de equipe
						if($basePlayer->getAttribute('id_evento') && $baseEnemy->npc_evento) {
							$baseEnemy->Loss(true);
							$players	= Recordset::query('SELECT id FROM player WHERE id_equipe=' . $basePlayer->id_equipe .' ORDER BY id DESC');
							foreach($players->result_array() as $player){
								$playerData	= new Player($player['id']);
								// Conquista --->
									arch_parse(NG_ARCH_NPC_EVENTO, $playerData, $baseEnemy);
								// <---
							}
							$msg .= 'Voltar ao <a href="?secao=mapa">Mapa</a>';
						}

						// Evento global
						if($basePlayer->eventoGlobal() && $baseEnemy->npc_evento_global) {
							$baseEnemy->Loss(true);

							if(!Recordset::query('SELECT id FROM evento_global_vitoria WHERE id_player=' . $basePlayer->id . ' AND id_evento=' . $basePlayer->eventoGlobal())->num_rows) {
								Recordset::insert('evento_global_vitoria', array(
									'id_player'	=> $basePlayer->id,
									'id_evento' => $basePlayer->eventoGlobal(),
									'total'		=> 1
								));
							} else {
								Recordset::update('evento_global_vitoria', array(
									'total' => array('value' => 'total+1', 'escape' => false)
								), array(
									'id_player'	=> $basePlayer->id,
									'id_evento' => $basePlayer->eventoGlobal()
								));
							}

							// Conquista --->
								arch_parse(NG_ARCH_NPC_EVENTO, $basePlayer, $baseEnemy);
							// <---

							$msg .= t('actions.a41').' RY$ ' . $baseEnemy->getAttribute('ryou') . ' '.t('geral.e').' ' . $baseEnemy->getAttribute('exp') .' '. t('actions.a42').'.<br /><br />'.t('actions.a48').' <a href="?secao=mapa">'.t('actions.a50').'</a>. ';
						}

						// Torneio --->
						if($baseEnemy->npc_torneio) {
							$torneio = torneio_batalha($basePlayer->id, $basePlayer->getAttribute('id_batalha'));

							if($torneio) {
								torneio_win($basePlayer->id, $basePlayer->getAttribute('id_batalha'), false, true);
								// Missões diárias de Comprar Sensei
								if($basePlayer->hasMissaoDiariaPlayer(20)->total){
									// Adiciona os contadores nos torneios npc
									Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
													WHERE id_player = ". $basePlayer->id." 
													AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 20) 
													AND completo = 0 ");
								}
							}

							$msg		.= ''.t('fight.f19').' <a href="?secao=torneio">'.t('actions.a81').'</a>';
							$cvitoria	= false;
						}
						// <---

						// Drops aleatórios dos npcs --->
						foreach($baseEnemy->drops as $drop) {
							$dropped[]	= $drop;
						}
						// <---

						// NPC de dojo msm
						if(!$baseEnemy->npc_mapa_rnd && !$baseEnemy->npc_missao && !$baseEnemy->npc_diaria && !$baseEnemy->npc_diaria2 && !$baseEnemy->npc_evento && !$baseEnemy->npc_vila && !$baseEnemy->npc_torneio && !$baseEnemy->npc_evento_global && !$baseEnemy->npc_evento_h && !$baseEnemy->npc_guerra) {
							// Conquista --->
								arch_parse(NG_ARCH_NPC_DOJO, $basePlayer, $baseEnemy);
							// <---
							if(!$baseEnemy->npc_sensei){
								$msg .= t('actions.a41').' RY$ ' . $baseEnemy->getAttribute('ryou') . ' '.t('actions.a83') .' ' . $baseEnemy->getAttribute('exp') .' '. t('actions.a42').'<br /><br />'.t('actions.a48').' <a href="?secao=dojo" class="linkTopo">Dojo</a>';
							}else{
								// Regra nova para os fulladores de npc de sensei
								$player_sensei 	= Recordset::query("SELECT * FROM player_sensei_desafios WHERE id_player=" . $basePlayer->id . " AND id_sensei = ". $basePlayer->id_sensei)->row_array();
								
								$player_sensei_maior_vitoria = Recordset::query("select * from player_sensei_desafios where id_player=".$basePlayer->id." order by desafio desc limit 1")->row_array();
								$player_sensei_maior_vitoria = isset($player_sensei_maior_vitoria['desafio']) ? $player_sensei_maior_vitoria['desafio'] : 0;

								$player_sensei_vitoria_atual = $player_sensei ? $player_sensei['desafio'] : 1;
								
								if($player_sensei_vitoria_atual < $player_sensei_maior_vitoria) {
									$cvitoria = false;
								}

								$msg .= t('actions.a41').' RY$ ' . $baseEnemy->getAttribute('ryou') . ' '.t('actions.a83') .' ' . $baseEnemy->getAttribute('exp') .' '. t('actions.a42').'<br /><br />'.t('actions.a48').' <a href="?secao=desafios" class="linkTopo">Desafio do Sensei</a>';

							}
							
							if($basePlayer->hasMissaoDiariaPlayer(7)->total){
								// Adiciona os contadores nas Missões de Graduação
								Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
											 WHERE id_player = ". $basePlayer->id." 
											 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 7) 
											 AND completo = 0 ");
							}

						}
					// <---

					if($baseEnemy->npc_mapa_rnd || $baseEnemy->npc_guerra) {
						$msg .= t('actions.a41').' RY$ ' . $baseEnemy->getAttribute('ryou') . ' '.t('actions.a83').' ' . $baseEnemy->getAttribute('exp') .' '. t('actions.a42').'<br /><br />'.t('actions.a48').' <a href="?secao=mapa" class="linkTopo">'.t('actions.a50').'</a>';
					}

					// NPC modo história
					if($baseEnemy->npc_evento_h) {
						$msg 			.= t('actions.a41').' RY$ ' . $baseEnemy->getAttribute('ryou') . ' '.t('actions.a83').' ' . $baseEnemy->getAttribute('exp') .' '. t('actions.a42').'<br /><br />'.t('actions.a48').' <a class="linkTopo" href="?secao=historia">'.t('actions.a82').'</a>';
						$ignore_coin	= false;

						// Conquista --->
							arch_parse(NG_ARCH_NPC_MODO_H, $basePlayer, $baseEnemy);
						// <---

						if(Recordset::query('SELECT id_usuario FROM evento_player_npc WHERE id_usuario=' . $basePlayer->id_usuario . ' AND id_evento=' . $baseEnemy->id_evento . ' AND id_npc=' . $baseEnemy->id)->num_rows) {
							$ignore_coin	= true;
						}

						Recordset::insert('evento_player_npc', array(
							'id_player'	=> $basePlayer->id,
							'id_usuario'	=> $basePlayer->id_usuario,
							'id_evento'	=> $baseEnemy->id_evento,
							'id_npc'	=> $baseEnemy->id
						));

						// Recompensa --->
							$npcs_saga	= Recordset::query('SELECT id_evento_npc AS id FROM evento_npc_evento WHERE id_evento=' . $baseEnemy->id_evento);
							$all_saga	= true;
							$saga		= Recordset::query('SELECT * FROM evento WHERE id=' . $baseEnemy->id_evento, true)->row_array();

							foreach($npcs_saga->result_array() as $npc_saga) {
								if(!Recordset::query('SELECT id_player FROM evento_player_npc WHERE id_player=' . $basePlayer->id . ' AND id_evento=' . $baseEnemy->id_evento . ' AND id_npc=' . $npc_saga['id'])->num_rows) {
									$all_saga	= false;

									break;
								}
							}

							if($all_saga) {
								sorte_ninja_premio($saga['recompensa'], $basePlayer->id, $basePlayer->id_usuario, $ignore_coin, 'historia');
							}
						// <---
					}
					if(!$baseEnemy->npc_torneio){	
						// Se o NPC dropou itens, verifica e adiciona no player
						if(sizeof($dropped)) {
							$msg .= '<br /><br />'.t('actions.a84').':<ul>';

							foreach($dropped as $drop) {
								if (!$drop['id_item']) {
									continue;
								}

								$item	= Recordset::query('SELECT nome_'.Locale::get().' AS nome FROM item WHERE id=' . $drop['id_item'], true)->row_array();
								$msg	.= '<li class="laranja">' . $drop['total'] . 'x ' . $item['nome'] . '</li>';

								$basePlayer->addItemW($drop['id_item'], $drop['total']);

								$item	= new Item($drop['id_item']);

								// Conquista --->
									arch_parse(NG_ARCH_ITEM_N, $basePlayer, NULL, $item,1);
								// <---
							}

							$msg .= '</ul>';
						}

						// Se não dropou itens normais eu vou tentar dropar uns equipamentos para os manolos.
						if ($cvitoria && rand(1, 100) <=  20) {
	
							// Sorteia um item randomico
							$basePlayer->generate_equipment($basePlayer);

							// Retorna o ultimo item dropado para o jogador
							$player_item	= Recordset::query('SELECT id FROM player_item WHERE id_player='. $basePlayer->id.' and id_item_tipo in (10,11,12,13,14,15,29) order by id desc limit 1')->row_array();

							// Retorna o nome e informações do item
							$player_item_atributo	= Recordset::query('SELECT nome FROM player_item_atributos WHERE id_player_item='. $player_item['id'])->row_array();

							// Adiciona a mensagem do drop para o maroto
							$msg .= '<br /><br />'.t('actions.a84').':<ul>';
							$msg	.= '<li class="laranja">' . $player_item_atributo['nome'] . '</li>';
							$msg .= '</ul>';
						}
						
					}
					if ($baseEnemy->npc_guerra && $vitoria) {
						$cvitoria	= false;
					}
					if($baseEnemy->npc_sensei){
						$cvitoria = true;
					}	
					if($cvitoria && !$baseEnemy->npc_torneio && !$baseEnemy->npc_evento && !$baseEnemy->npc_vila) {
						if($batalha['id_tipo']!=8){
							$basePlayer->setAttribute('vitorias_d', $basePlayer->getAttribute('vitorias_d') + 1);

							/*$msg	.= sprintf(t('actions.a273'), 1);

							/*Recordset::update('player', array(
								'ponto_batalha'	=> array('escape' => false, 'value' => 'ponto_batalha + 1')
							), array(
								'id'		=> $basePlayer->id
							));*/

							//Adicionado para o Rank de Vitorias Diários
							Recordset::query("UPDATE player_batalhas_status SET vitorias_d = vitorias_d + 1,  vitorias_d_semana = vitorias_d_semana + 1,  vitorias_d_mes = vitorias_d_mes + 1,  vitorias_d_geral = vitorias_d_geral + 1 WHERE id_player = ".	$basePlayer->id ."");
						}else{

							$player_sensei 	= Recordset::query("SELECT * FROM player_sensei_desafios WHERE id_player=" . $basePlayer->id . " AND id_sensei = ". $basePlayer->id_sensei)->row_array();
							if($player_sensei){
								Recordset::query("UPDATE player_sensei_desafios SET wins = wins + 1, desafio = desafio + 1 WHERE id_player = ".	$basePlayer->id ." AND id_sensei = ". $basePlayer->id_sensei);
							}else{
								$batalha = Recordset::insert('player_sensei_desafios', array(
									'id_player'		=> $basePlayer->id,
									'id_sensei'		=> $basePlayer->id_sensei,
									'wins'			=> 1,
									'desafio'		=> 1
								));
							}
							// Chama a conquista aqui dos Sensei
							// Conquista --->
								arch_parse(NG_ARCH_NPC_SENSEI, $basePlayer, $baseEnemy);
							// <---
						}
					}

					$template	= msg(2,t('actions.a85'),'%msg', true);

					if(!$baseEnemy->npc_torneio) {
						// Recompensa log
						Recordset::insert('player_recompensa_log', array(
							'fonte'		=> 'npc',
							'id_player'	=> $basePlayer->id,
							'exp'		=> $baseEnemy->getAttribute('exp'),
							'ryou'		=> $baseEnemy->getAttribute('ryou'),
							'recebido'	=> 1
						));

						$basePlayer->setAttribute('exp',  $basePlayer->getAttribute('exp')  + $baseEnemy->getAttribute('exp'));
						$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') + $baseEnemy->getAttribute('ryou'));
					}
				}

				if($derrota) {
					if($baseEnemy->npc_torneio) {
						$cderrota = false;
					}

					// Torneio --->
					if($baseEnemy->npc_torneio) {
						$torneio = torneio_batalha($basePlayer->id, $basePlayer->getAttribute('id_batalha'));

						if($torneio) {
							torneio_loss($basePlayer->id, $basePlayer->getAttribute('id_batalha'), false, true);
						}
					}
					// <---

					if($cderrota && !$baseEnemy->npc_evento && !$baseEnemy->npc_vila) {
						if($batalha['id_tipo']!=8){
							$basePlayer->setAttribute('derrotas_npc', $basePlayer->getAttribute('derrotas_npc') + 1);

							//Adicionado para o Rank Diario
							Recordset::query("UPDATE player_batalhas_status SET derrotas_npc = derrotas_npc + 1, derrotas_npc_semana = derrotas_npc_semana + 1, derrotas_npc_mes = derrotas_npc_mes + 1, derrotas_npc_geral = derrotas_npc_geral + 1 WHERE id_player = ". $basePlayer->id ."");
						}else{
							$player_sensei 	= Recordset::query("SELECT * FROM player_sensei_desafios WHERE id_player=" . $basePlayer->id . " AND id_sensei = ". $basePlayer->id_sensei)->row_array();
							if($player_sensei){
								Recordset::query("UPDATE player_sensei_desafios SET losses = losses + 1 WHERE id_player = ".	$basePlayer->id ." AND id_sensei = ". $basePlayer->id_sensei);
							}else{
								$batalha = Recordset::insert('player_sensei_desafios', array(
									'id_player'		=> $basePlayer->id,
									'id_sensei'		=> $basePlayer->id_sensei,
									'losses'			=> 1
								));
							}
						}
					}

					if(!$wo) {
						$basePlayer->setAttribute('hospital', '1');
						$basePlayer->setAttribute('dentro_vila', '1');

						hospital_vila_cura();
					}

					if(!$basePlayer->getAttribute('id_vila_atual') || $baseEnemy->npc_vila) {
						$basePlayer->setAttribute('id_vila_atual', $basePlayer->getAttribute('id_vila'));
					}

					if(!$wo) {
						$msg .= '<br /><br />'.t('actions.a59').' <a href="?secao=hospital_quarto" class="linkTopo">'.t('actions.a58').'</a>';
					}

					//if(!$baseEnemy->npc_torneio) {
					//	$basePlayer->setAttribute('exp',  $basePlayer->getAttribute('exp')  - $baseEnemy->getAttribute('exp'));
					//}
				}

				if($vitoria) {
					// Exp equipe
					equipe_exp(30);
				}
			}

			// Se não ganhou
			if(!$vitoria) {
				$template	= msg(4,''.$titulo.'','%msg', true);

				if($baseEnemy->npc_vila) { // Tira o npc de vila de combate
					Recordset::update('npc_vila', array(
						'batalha'			=> '0',
						'id_player_batalha' => '0'
					), array(
						'id'				=> $baseEnemy->id
					));
				}

				if($baseEnemy->npc_evento) {
					// Marca o NPC em batalha --->
						Recordset::update('evento_npc_equipe', array(
							'batalha'		=> '0'
						), array(
							'id_equipe'		=> $basePlayer->id_equipe,
							'id_evento_npc'	=> $baseEnemy->id,
							'id_evento'		=> $basePlayer->id_evento
						));
					// <---
				} elseif($baseEnemy->npc_evento_global) {
					Recordset::update('evento_npc_evento', array(
						'batalha_global'		=> '0'
					), array(
						'id_evento_npc'	=> $baseEnemy->id,
						'id_evento'		=> $basePlayer->eventoGlobal()
					));
				}
			}

			$basePlayer->setAttribute('id_batalha', 0);

			echo "$('#cnFinal').html('" . scriptslashes(str_replace('%msg', $msg, $template)) . "<br /><br />');";
			echo "$('#d-actionbar').html(''); clearInterval(_pvpTimer);";

			// Limpa os dados da batalha na memória --->
				SharedStore::D('_BATALHA_' . $basePlayer->id, 0);
			// <--
		}

		if((isset($_POST['action']) && !$item && !isset($_POST['flight'])) || (isset($_POST['flight']) && $item)) {
			die('jalert("Ação inválida")');
		} elseif(isset($_POST['action']) && ($item && !isset($_POST['flight']) || !$item && isset($_POST['flight'])) && !$batalha['finalizada']) {
			if($item) {
				$item = $basePlayer->getItem($item);
				$item->setPlayerInstance($basePlayer);
				$item->parseLevel();
			}

			if($_POST['action'] == 1) {
				if(isset($_POST['flight'])) {
					/*$item			= new Item(1);
					$item->flight	= true;*/
					die('jalert("Função Retirada do jogo")');
				} else {
					if($item->id_tipo == 2 && !$item->equipado) {
						die('jalert("'.t('actions.a252').'")');
					}

					// Verifica se tem stats para usar --->
						if($item->consume_sp > $basePlayer->getAttribute('sp')) {
							die('jalert("'.t('actions.a63').'")');
						}

						if($item->consume_sta > $basePlayer->getAttribute('sta')) {
							die('jalert("'.t('actions.a64').'")');
						}
					// <---
				}

				// Controle dos turnos --->
					if($item->getAttribute('turnos') && isset($turnos[$item->id])) {
						die('jalert("'.t('actions.a65').'");');
					} elseif($item->getAttribute('turnos')) {
						$turnos[$item->id] = $item->getAttribute('turnos');
					}

					// Rotaciona os turnos dos golpes em cooldown --->
						$new_turnos = array();

						foreach($turnos as $k => $v) {
							$turnos[$k]--;

							if($turnos[$k] > 0) {
								$new_turnos[$k] = $turnos[$k];
							}
						}

						$turnos = $new_turnos;
					// <---

					// Salva os dados de turnos
					SharedStore::S('_TRL_' . $basePlayer->id, $new_turnos);
				// <---

				$_SESSION['has_used_nt_atk'] = false;

				$basePlayer->setAtkItem($item);

				$fight->addPlayer($basePlayer);
				$fight->addEnemy($baseEnemy);

				$fight->Process();

				$basePlayer->rotateModifiers();
				$baseEnemy->rotateModifiers();

				$p	= $fight->_player;
				$e	= $fight->_enemy;

				SharedStore::S('_BATALHA_' . $basePlayer->id, serialize($baseEnemy));

	echo "\n/*\n";
	print_r($basePlayer->getModifiers());
	var_dump($baseEnemy->getModifiers());
	echo "\n*/\n";

	// Update feliz --->
					$data = array(
						'pvp_log'	=> $batalha['pvp_log'] . $fight->log,
						'data_atk'	=> date('Y-m-d H:i:s')
					);

					if($fight->flight) { // Fuga
						$data['flight_id']	= $fight->flightId;
						$data['vencedor']	= $fight->flightId == $basePlayer->id ? $baseEnemy->id : $basePlayer->id;
						$data['finalizada']	= 1;
						$data['data_fim']	= date('Y-m-d H:i:s');
					} else { // Condições normais
						if(
							($p->getAttribute('hp') < 10 || $p->getAttribute('sp') < 10 || $p->getAttribute('sta') < 10) &&
							($e->getAttribute('hp') < 10 || $e->getAttribute('sp') < 10 || $e->getAttribute('sta') < 10)
						) { // Empate
							$data['empate']		= 1;
							$data['finalizada']	= 1;
							$data['data_fim']	= date('Y-m-d H:i:s');
						} elseif($baseEnemy->getAttribute('hp') < 10 || $baseEnemy->getAttribute('sp') < 10 || $baseEnemy->getAttribute('sta') < 10) { // Vitória
							$data['vencedor']	= $basePlayer->id;
							$data['finalizada']	= 1;
							$data['data_fim']	= date('Y-m-d H:i:s');
						} elseif($basePlayer->getAttribute('hp') < 10 || $basePlayer->getAttribute('sp') < 10 || $basePlayer->getAttribute('sta') < 10) { // Derrota
							$data['vencedor']	= $baseEnemy->id;
							$data['finalizada']	= 1;
							$data['data_fim']	= date('Y-m-d H:i:s');
						}
					}


					//echo PHP_EOL . '/*' . PHP_EOL;
					//print_r($data);
					//echo PHP_EOL . '*/' . PHP_EOL;

					Recordset::update('batalha', $data, array(
						'id'	=> $basePlayer->getAttribute('id_batalha')
					));
				// <--
			} elseif($_POST['action'] == 2 && $item) { // Buffs/Gens
				if($item->getAttribute('turnos') && isset($turnos[$item->id])) {
					die('jalert("'.t('actions.a65').'");');
				}

				$arModifiers	= $basePlayer->getModifiers();
				$mod			= $item->getModifiers();
				$pass			= true;

				if($item->getAttribute('id_tipo') == 1) {
					foreach($arModifiers as $mod) {
						$mod_item = Recordset::query("SELECT id_tipo FROM item WHERE id=" . (int)$mod['id'], true)->row_array();

						if($mod_item['id_tipo'] == 1) {
							$pass = false;

							break;
						}
					}

					if($pass) {
						if($basePlayer->removeItem($item)) {
							echo '$("#atki-' . $item->id . '").parent().hide("explode").remove();' . PHP_EOL;
						} else {
							echo '_items[' . $item->id . '].t = ' . ($item->getAttribute('total') - 1) . ' ;' . PHP_EOL;
						}
					}

					$o_direction = 0;
				} else {
					if(isset($_SESSION['has_used_nt_atk']) && $_SESSION['has_used_nt_atk'] && $item->id_tipo != 41) {
						die('jalert("'.t('actions.a66').'")');
					}

					// So pode um genjutsu/buff por vez --->
						if(
							!$mod['target_ken'] 		&& !$mod['target_tai'] 		&& !$mod['target_nin'] 			&& !$mod['target_gen']			&& !$mod['target_agi']			&& !$mod['target_con']			&&
							!$mod['target_forc']		&& !$mod['target_inte']			&& !$mod['target_res']			&& !$mod['target_atk_fisico']	&& !$mod['target_atk_magico']	&&
							!$mod['target_def_base']	&& !$mod['target_prec_fisico']	&& !$mod['target_prec_magico']	&& !$mod['target_crit_min']		&& !$mod['target_crit_max']	&& !$mod['target_crit_total']	&& !$mod['target_esq_min']	&& !$mod['target_esq_max'] && !$mod['target_esq_total'] &&
							!$mod['target_esq']			&& !$mod['target_det']			&&!$mod['target_conv']			&& !$mod['target_conc'] && !$mod['target_esquiva'] &&
							!$mod['target_def_fisico']	&& !$mod['target_def_magico']
						) {
							$o_direction = 0; // BUFF
						} elseif(
							!$mod['self_ken'] 			&& !$mod['self_tai'] 			&& !$mod['self_nin'] 			&& !$mod['self_gen']			&& !$mod['self_agi']		&& !$mod['self_con']		&&
							!$mod['self_forc']			&& !$mod['self_inte']			&& !$mod['self_res']			&& !$mod['self_atk_fisico']	&& !$mod['self_atk_magico']	&&
							!$mod['self_def_base']		&& !$mod['self_prec_fisico']	&& !$mod['self_prec_magico']	&& !$mod['self_crit_min']	&& !$mod['self_crit_max']	&& !$mod['self_crit_total'] &&   !$mod['self_esq_min']	&& !$mod['self_esq_max']	&& !$mod['self_esq_total'] &&
							!$mod['self_esq']			&& !$mod['self_det']			&&!$mod['self_conv']			&& !$mod['self_conc']		&& !$mod['self_esquiva']    &&
							!$mod['self_def_fisico']	&& !$mod['self_def_magico']
						) {
							$o_direction = 1; // GEN
						} else {
							$o_direction = 2; // DUAL
						}

						foreach($arModifiers as $mod) {
							$mod_item = Recordset::query("SELECT id_habilidade, id_tipo FROM item WHERE id=" . (int)$mod['id'], true)->row_array();

							if($mod_item['id_habilidade'] && $mod_item['id_tipo'] != 1) { // Somente itens que não tem ordem(qquer coisa diferente de clas e afins)
								switch($o_direction) {
									case 0:
										if(($mod['o_direction'] == 0 && $mod['direction'] == 0) || ($mod['o_direction'] == 2 && $mod['dreiction'] == 2)) {
											$pass = false;
										}

										break;

									case 1:
										if(($mod['o_direction'] == 1 && $mod['direction'] == 0) || ($mod['o_direction'] == 2 && $mod['direction'] == 0)) {
											$pass = false;
										}

										break;

									case 2:
										if($mod['o_direction'] == 0 || $mod['o_direction'] == 1 || $mod['o_direction'] == 2) {
											$pass = false;
										}

										break;
								}
							}
						}
					// <---
				}

				if(!$pass && $item->id_tipo != 41) {
					die("jalert('".t('actions.a66')."[2]!')");
				}

				// Atualização de turnos caso o item tenha
				if($item->getAttribute('turnos')) {
					$turnos[$item->id] = $item->getAttribute('turnos');
				}
				// Salva os dados de turnos
				SharedStore::S('_TRL_' . $basePlayer->id, $turnos);

				if(!in_array($item->getAttribute('id_tipo'), [1, 41])) {
					$_SESSION['has_used_nt_atk']	= true;
				}

				// Verifica se tem stats para usar --->
					if($item->consume_sp > $basePlayer->getAttribute('sp')) {
						die('jalert("'.t('actions.a63').'")');
					}

					if($item->consume_sta > $basePlayer->getAttribute('sta')) {
						die('jalert("'.t('actions.a64').'")');
					}
				// <---

				// Consumo
				$basePlayer->consumeSP($item->consume_sp);
				$basePlayer->consumeSTA($item->consume_sta);

				echo "\n/*\n";
				echo "O_DIRECTION: " . $o_direction;
				echo "\n*/\n";

				$basePlayer->addModifier($item, $item->getAttribute('level'), 0, $o_direction);
				$baseEnemy->addModifier($item, $item->getAttribute('level'), 1, $o_direction);
			} elseif($_POST['action'] == 3 && $item) { // Clas e etc
				$source = NULL;

				if(!in_array($item->getAttribute('id_tipo'), array(16, 17, 20, 21, 23, 26, 39))) {
					die('jalert("'.t('actions.a67').'")');
				}

				// Verifica se tem stats para usar --->
					if($item->consume_sp > $basePlayer->getAttribute('sp')) {
						die('jalert("'.t('actions.a63').'")');
					}

					if($item->consume_sta > $basePlayer->getAttribute('sta')) {
						die('jalert("'.t('actions.a64').'")');
					}
				// <---

				$arModifiers	= $basePlayer->getModifiers();

				foreach($arModifiers as $modifier) {
					if($modifier['id'] == $item->id && $modifier['direction'] == 0) {
						die('jalert("'.t('fight.f20').'")');
					}
				}

				// Consumo
				$basePlayer->consumeHP($item->consume_hp);
				$basePlayer->consumeSP($item->consume_sp);
				$basePlayer->consumeSTA($item->consume_sta);

				$basePlayer->addModifier($item, $item->getAttribute('level'), 0, $source);
			} elseif($_POST['action'] == 4 && $item) { // Merdicinal =D

			}
		}

		$items = $basePlayer->getItems(array(1, 2, 5, 6));
	?>
	<?php foreach($items as $item): ?>
		<?php
			$item->setPlayerInstance($basePlayer);
			$item->apply_enhancemnets();
		?>
		_items['<?php echo $item->id ?>'].precf		= <?php echo $item->getAttribute('prec_fisico') ?>;
		_items['<?php echo $item->id ?>'].precm		= <?php echo $item->getAttribute('prec_magico') ?>;
		_items['<?php echo $item->id ?>'].pre		= <?php echo $item->getAttribute('precisao') ?>;
		<?php if($item->getAttribute('uso_unico')):	?>
		_items['<?php echo $item->id ?>'].t		= <?php echo $item->getAttribute('qtd') ?>;
		<?php endif; ?>
		<?php if($item->getAttribute('turnos')): ?>
		_items['<?php echo $item->id ?>'].trl	= <?php echo isset($turnos[$item->id]) ? $turnos[$item->id] : 0 ?>;
		<?php endif; ?>
	<?php endforeach; ?>
	_nt_disabled = [];
	<?php for($f = 0; $f <= 1; $f++): ?>
	<?php
		$var		= $f ? 'e' : 'p';
		$obj		= $f ? $baseEnemy : $basePlayer;
		$modifiers	= $obj->getModifiers();
		$elementos	= $obj->getElementosA();
	?>
	<?php echo $var ?> = {
		atkf:	<?php echo $obj->getAttribute('atk_fisico_calc') ?>,
		atkm:	<?php echo $obj->getAttribute('atk_magico_calc') ?>,
		def:	<?php echo $obj->getAttribute('def_base_calc') ?>,
		deff:	<?php echo $obj->getAttribute('def_fisico_calc') ?>,
		defm:	<?php echo $obj->getAttribute('def_magico_calc') ?>,
		mhp:	<?php echo $obj->getAttribute('max_hp') ?>,
		hp:		<?php echo $obj->getAttribute('hp') ?>,
		msp:	<?php echo $obj->getAttribute('max_sp') ?>,
		sp:		<?php echo $obj->getAttribute('sp') ?>,
		msta:	<?php echo $obj->getAttribute('max_sta') ?>,
		sta:	<?php echo $obj->getAttribute('sta') ?>,
		cmin:	<?php echo $obj->getAttribute('crit_min_calc') ?>,
		cmax:	<?php echo $obj->getAttribute('crit_max_calc') ?>,
		ctotal:	<?php echo $obj->getAttribute('crit_total_calc') ?>,
		emin:	<?php echo $obj->getAttribute('esq_min_calc') ?>,
		emax:	<?php echo $obj->getAttribute('esq_max_calc') ?>,
		etotal:	<?php echo $obj->getAttribute('esq_total_calc') ?>,
		conc:	<?php echo $obj->getAttribute('conc_calc') ?>
	}

	<?php echo $var ?>mod = [];

	var <?php echo $var ?>resumo =
		'<b class="azul"><?php echo t('formula.atk_fisico')?>:</b> <?php echo $obj->getAttribute('atk_fisico_calc') ?><br />' +
		'<b class="azul"><?php echo t('formula.atk_magico')?>:</b> <?php echo $obj->getAttribute('atk_magico_calc') ?><br />' +
		<?php /*'<b class="azul"><?php echo t('formula.def_base')?>:</b> <?php echo $obj->getAttribute('def_base_calc') ?><br />' +*/ ?>

		'<b class="azul"><?php echo t('formula.def_fisico')?>:</b> <?php echo $obj->getAttribute('def_fisico_calc') ?><br />' +
		'<b class="azul"><?php echo t('formula.def_magico')?>:</b> <?php echo $obj->getAttribute('def_magico_calc') ?><br />' +

		<?php /*'<b class="azul"><?php echo t('formula.prec_fisico')?>:</b> <?php echo $obj->getAttribute('prec_fisico_calc') ?><br />' + */?>
		'<b class="azul"><?php echo t('formula.prec_magico')?>:</b> <?php echo $obj->getAttribute('prec_magico_calc') ?><br />' +
		'<b class="azul"><?php echo t('formula.det')?>:</b> <?php echo $obj->getAttribute('det_calc') ?> %<br />' +
		'<b class="azul"><?php echo t('formula.conv')?>:</b> <?php echo $obj->getAttribute('conv_calc') ?> %<br />' +
		'<b class="azul"><?php echo t('formula.esq')?>:</b> <?php echo sprintf("%1.2f",$obj->getAttribute('esq_calc')) ?> % ( <span class="color_green"><?php echo $f == 0 ? $conv_en + $obj->getAttribute('esq_calc')." %" : $conv_my + $obj->getAttribute('esq_calc')." %" ?></span> - <span class="color_red"><?php echo $f == 0 ? $conv_en. " %" : $conv_my." %" ?></span> ) <br />' +
		'<b class="azul"><?php echo t('formula.esq_total')?>:</b> <span><?php echo $obj->getAttribute('esq_total_calc')?>%</span><br />' +
		'<b class="azul"><?php echo t('formula.conc')?>:</b> <?php echo sprintf("%1.2f", $obj->getAttribute('conc_calc')) ?> % ( <span class="color_green"><?php echo $f == 0 ? $conv_en + $obj->getAttribute('conc_calc')." %" : $conv_my + $obj->getAttribute('conc_calc')." %" ?></span> - <span class="color_red"><?php echo $f == 0 ? $conv_en. " %" : $conv_my." %" ?></span> )<br />' +
		'<b class="azul"><?php echo t('formula.crit_total')?>:</b> <span><?php echo $obj->getAttribute('crit_total_calc')?>%</span><br />'+
		'<b class="azul"><?php echo t('formula2.esquiva')?>:</b> <span><?php echo $obj->getAttribute('esquiva_calc')?>%</span><br />';

	<?php echo $var ?>mod.push({
		id:		'A',
		t:		0,
		i:		'layout<?php echo LAYOUT_TEMPLATE?>/stats.png',
		n:		'<?php echo t('jogador_vip.jv34')?>',
		d:		<?php echo $var ?>resumo,
		ub:		true
	});

	<?php if(sizeof($elementos)): ?>
		<?php echo $var ?>mod.push({
			id:		'B',
			t:		0,
			i:		'layout<?php echo LAYOUT_TEMPLATE?>/elements.png',
			n:		'<?php echo t('actions.a68')?>',
			d:		'<?php echo t('actions.a69')?>',
			ise:	true,
			ub:		true,
			els:	[
			<?php if(!$f || $f && $basePlayer->hasItem(21365)): ?>
				<?php foreach($elementos as $elemento): ?>
				{
					n: '<?php echo $elemento['nome'] ?>',
					f: [
					<?php
						$fraquezas	= Recordset::query('SELECT a.nome FROM elemento a JOIN elemento_fraqueza b ON b.id_elemento_fraco=a.id WHERE b.id=' . $elemento['id'], true);
					?>
					<?php foreach($fraquezas->result_array() as $fraqueza): ?>
						'<?php echo $fraqueza['nome'] ?>'
					<?php endforeach; ?>
					],
					r: [
					<?php
						$resistencias	= Recordset::query('SELECT a.nome FROM elemento a JOIN elemento_resistencia b ON b.id_elemento_resiste=a.id WHERE b.id=' . $elemento['id'], true);
					?>
					<?php foreach($resistencias->result_array() as $resistencia): ?>
						'<?php echo $resistencia['nome'] ?>'
					<?php endforeach; ?>
					]
				},
				<?php endforeach; ?>
			<?php endif; ?>
			]
		});
	<?php endif; ?>
	<?php if(sizeof($modifiers)): ?>
		<?php foreach($modifiers as $mod): ?>
		<?php
			if($mod['o_direction'] != 2) {
				if($mod['direction'] != $mod['o_direction']) {
					continue;
				}
			}
		?>
		<?php if($mod['ord']): // Clas e etc ?>
			<?php
				$ord_item	= new Item($mod['id']);
				$ord_items	= Recordset::query('SELECT id, nome_'.Locale::get().' AS nome FROM item WHERE id_tipo=' . $ord_item->getAttribute('id_tipo') . ' AND ordem <=' . $ord_item->getAttribute('ordem'), true);
			?>
			<?php if($f == 0): // Esse loop so acontece para o player ?>
			<?php foreach($ord_items->result_array() as $ord): ?>
				$('#i-nt-<?php echo $ord['id'] ?>').css('opacity', .4);
				_nt_disabled.push(<?php echo $ord['id'] ?>);
			<?php endforeach ?>
			<?php endif; ?>

			$('#i-<?php echo $var ?>buff-<?php echo $ord_item->getAttribute('id_tipo') ?>').css('opacity', 1);
		<?php else: ?>
		<?php echo $var ?>mod.push({
			<?php
				if($mod['uid']) {
					$item	= new Item($mod['id'], $mod['source_id']);
					$item->apply_enhancemnets();
				} else {
					$item	= new Item($mod['id']);
				}

				$desc	= $item->getAttribute('descricao_'.Locale::get());

				if($mod['conv']) {
					$value	= preg_match('/\d+/', $desc, $matches);

					if(isset($matches[0])) {
						$desc	= str_replace($matches[0], $mod['det_inc'], $desc);
					}
				}
			?>
			id:	<?php echo $mod['id'] ?>,
			t:	<?php echo $mod['turns'] ?>,
			i:	'layout/<?php echo $mod['image'] ?>',
			n:	'<?php echo $item->getAttribute('nome_'.Locale::get()) ?>',
			ub:	true,
			<?php if($mod['conv']): ?>
			ise: true,
			d:	'<?php echo $desc ?>',
			<?php else: ?>
			st:	true,
			<?php endif; ?>
			<?php if($item->getAttribute('sem_turno') && $item->hasModifiers()): ?>
			<?php
				$modifiers	= $item->getModifiers();

				$has_mod_p	= $modifiers['self_ken']  		||$modifiers['self_tai']  		|| $modifiers['self_nin']		|| $modifiers['self_gen']			|| $modifiers['self_agi']			|| $modifiers['self_con'] ||
							  $modifiers['self_ene']   		|| $modifiers['self_forc']		|| $modifiers['self_inte']			|| $modifiers['self_res']			|| $modifiers['self_atk_fisico'] ||
							  $modifiers['self_atk_magico']	|| $modifiers['self_def_base']	|| $modifiers['self_prec_fisico']	|| $modifiers['self_prec_magico']	|| $modifiers['self_crit_min'] ||
							  $modifiers['self_crit_max']	|| $modifiers['self_crit_total']	|| $modifiers['self_esq_min']   ||	$modifiers['self_esq_max'] 		||	$modifiers['self_esq_total']  || $modifiers['self_esq']		|| $modifiers['self_det']			|| $modifiers['self_conv']			|| $modifiers['self_conc'] || $modifiers['self_esquiva'];

				$has_mod_e	= $modifiers['target_ken']  		||$modifiers['target_tai']  		|| $modifiers['target_nin']			|| $modifiers['target_gen']			|| $modifiers['target_agi']			|| $modifiers['target_con'] ||
							  $modifiers['target_ene']   		|| $modifiers['target_forc']		|| $modifiers['target_inte']		|| $modifiers['target_res']			|| $modifiers['target_atk_fisico'] ||
							  $modifiers['target_atk_magico']	|| $modifiers['target_def_base']	|| $modifiers['target_prec_fisico']	|| $modifiers['target_prec_magico']	|| $modifiers['target_crit_min'] ||	  $modifiers['target_crit_max']		|| $modifiers['target_crit_total']		|| $modifiers['target_esq_min']		|| $modifiers['target_esq_max']		|| $modifiers['target_esq_total']     || $modifiers['target_esq']			|| $modifiers['target_det']			|| $modifiers['target_conv']		|| $modifiers['target_conc'] || $modifiers['target_esquiva'];
			?>
			mo:	{
				p: <?php echo $has_mod_p ? 'true' : 'false' ?>,
				e: <?php echo $has_mod_e ? 'true' : 'false' ?>,
				<?php for($g = 0; $g <= 1; $g++): ?>
					<?php
						if($g && !$has_mod_e || !$g && !$has_mod_p) {
							continue;
						}
					?>
					<?php echo $g ? 'em' : 'pm' ?>:	{
						<?php foreach($modifiers as $k => $v): ?>
						<?php if(strpos($k, !$g ? 'self_' : 'target_') === false) continue; ?>
						<?php echo $k ?>: <?php echo (int)$v ?>,
						<?php endforeach; ?>
					}
				<?php endfor; ?>
			}
			<?php endif; ?>
		});
		<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php endfor; ?>
	<?php for($f = 0; $f <= 1; $f++): ?>
	$('#<?php echo $f == 1 ? 'd-eesq-count'  : 'd-pesq-count' ?>').html('<span><?php  echo (int)($f == 1 ? $fight->hasEsqPoint($baseEnemy, true)  : $fight->hasEsqPoint($basePlayer, true)) ?></span>');
	$('#<?php echo $f == 1 ? 'd-ecrit-count' : 'd-pcrit-count' ?>').html('<span><?php echo (int)($f == 1 ? $fight->hasCritPoint($baseEnemy, true) : $fight->hasCritPoint($basePlayer, true)) ?></span>');
	<?php endfor; ?>

	setPValue2(p.hp,  (p.mhp  || 1), "<?php echo t('formula.hp')?>",	$("#cnPHP"),  1);
	setPValue2(p.sp,  (p.msp  || 1), "Chakra",	$("#cnPSP"),  1);
	setPValue2(p.sta, (p.msta || 1), "Stamina",	$("#cnPSTA"), 1);

	setPValue2(e.hp,  (e.mhp  || 1), "<?php echo t('formula.hp')?>",	$("#cnEHP"),  1);
	setPValue2(e.sp,  (e.msp  || 1), "Chakra",	$("#cnESP"),  1);
	setPValue2(e.sta, (e.msta || 1), "Stamina",	$("#cnESTA"), 1);

	numericAnimateTo(<?php echo $basePlayer->getAttribute('fight_power') ?>, '', '#ninjap-p');
	numericAnimateTo(<?php echo $baseEnemy->getAttribute('fight_power') ?>, '', '#ninjap-e');
<?php else: ?>
	<?php
		$baseEnemy	= unserialize(SharedStore::G('_BATALHA_' . $basePlayer->id));
		$baseEnemy->atCalc();
	?>

	setPValue2(<?php echo $baseEnemy->hp ?>,  (<?php echo $baseEnemy->max_hp ?>  || 1), "<?php echo t('formula.hp')?>",	$("#cnEHP"),  1);
	setPValue2(<?php echo $baseEnemy->sp ?>,  (<?php echo $baseEnemy->max_sp ?>  || 1), "Chakra",	$("#cnESP"),  1);
	setPValue2(<?php echo $baseEnemy->sta ?>, (<?php echo $baseEnemy->max_sta ?> || 1), "Stamina",	$("#cnESTA"), 1);
<?php endif; ?>

$('#cnLog').html('<?php echo addslashes(preg_replace('/[\r\n]/s', '', isset($batalha['pvp_log']) ? $batalha['pvp_log'] : '')) ?>');
$('#cnPVPLog').html('<?php echo t('actions.a70')?>');
$('#cnLog').scrollTop(100000);
<?php
	$diff	= get_time_difference(now(), $future);
?>

__timer_m	= <?php echo $diff['minutes'] ?>;
__timer_s	= <?php echo $diff['seconds'] ?>;
