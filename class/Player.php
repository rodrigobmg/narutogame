<?php
	class Player {
		use AttributeCalculationTrait;
		use ModifiersTrait;

		public $id = NULL;

		//new --->
		private	$_atkItem	= NULL;

		public	$at_keys	= NULL;
		public	$ats_keys	= NULL;
		public	$atl_keys	= NULL;

		public	$_items		= array();

		public	$bonus_vila	= array(
			'ns_dano_longo'			=> 0,
			'ns_dano_curto'			=> 0,
			'ns_preco'				=> 0,
			'ramen_preco'			=> 0,
			'sk_missao_tempo'		=> 0,
			'sk_missao_ryou'		=> 0,
			'sk_missao_exp'			=> 0,
			'hospital_preco'		=> 0,
			'hospital_vida'			=> 0,
			'dojo_ryou_npc'			=> 0,
			'dojo_exp_npc'			=> 0,
			'dojo_ryou_pvp'			=> 0,
			'dojo_exp_pvp'			=> 0,
			'dojo_limite_npc_mapa'	=> 0,
			'dojo_limite_npc_dojo'	=> 0,
			'mapa_ryou'				=> 0,
			'mapa_exp'				=> 0,
			'mo_hp_npc_vila'		=> 0,
			'mo_slot'				=> 0,
			'mo_ninja_shop'			=> 0,
			'mo_guild_grad'			=> 0,
			'mo_sorte_preco'		=> 0,
			'mo_sorte_preco_semanal'=> 0
		);

		public $bonus_profissao	= [
			'bb_recompensa'		=> 0,
			'custo_treino'		=> 0,
			'bb_cacador'		=> 0,
			'ramen_cura'		=> 0,
			'ns_preco_curto'	=> 0
		];

		// nova implementação dos itens
		public	$_ar_items			= array();
		public	$_ar_items_mine		= array();
		public	$_ar_items_mineb	= array();
		public	$_ar_items_types	= array();
		public	$_ar_items_typesb	= array();
		public	$_ar_items_typei	= array();

		public	$pt_livre	= array();
		public	$reputacoes	= array(
			'1'		=> 5,
			'2'		=> 5,
			'3'		=> 5,
			'4'		=> 5,
			'5'		=> 5,
			'6'		=> 5,
			'6'		=> 5,
			'8'		=> 5,
			'9'		=> 5,
			'10'	=> 5,
			'11'	=> 5,
			'12'	=> 5,
			'13'	=> 5
		);
		// <---

		private static $_instance = NULL;

		// CX --->
		public	$nome				= '';
		public	$id_usuario			= 0;

		public	$id_titulo				= 0;
		public	$nome_titulo			= '';

		public	$inc_treino				= 0;
		public	$inc_ryou				= 0;
		public	$inc_exp				= 0;
		public	$inc_turno				= 0;

		public	$less_consume_sp		= 0;
		public	$less_consume_sta		= 0;

		public	$nome_cla				= '';
		public	$nome_selo				= '';
		public	$nome_invocacao			= '';

		public	$missao_interativa		= false;
		public	$missao_comum			= false;
		public	$missao_equipe			= false;
		public	$missao_invasao			= false;
		public	$missao_invasao_vila	= 0;
		public	$missao_invasao_npc		= 0;

		public	$hp						= 0;
		public	$sp						= 0;
		public	$sta					= 0;
		public	$max_hp					= 0;
		public	$max_sp					= 0;
		public	$max_sta				= 0;
		public	$vila_atual_inicial		= false;
		public	$dono_equipe			= 0;
		public	$sub_equipe				= 0;
		public	$id_evento4				= 0;
		public	$id_profissao_ativa		= 0;

		private	$_profession_cache		= null;
		// <---

		/**
		 * Atribui os dados do jogador na classe a partir do ID
		 *
		 * @param int $id
		 */
		function __construct($id, $quick = false, $id_classe_tipo = NULL) {
			$this->quick	= $quick;

			if(!is_numeric($id)) {
				if($_SESSION['universal']) {
					//print_r(debug_backtrace());
				} else {
					session_destroy();

					redirect_to("negado", NULL, array("ee" => $id));
				}

			}

			if(!$quick) {
				$player = Recordset::query($this->getPlayerView() . 'a.id=' . $id);



				if(!$player->num_rows) {
					unset($_SESSION);

					die("Desculpe-nos a inconveniência, mas um erro interno foi detectado. Tente novamente mais tarde! [$id]" .$player->sql);
				}
			} else {
				$player = Recordset::query($this->getQuickPlayerView((int)$id_classe_tipo), true);
			}

			$rPlayer = $player->row_array();

			if(!$quick) {
				$class_at	= Recordset::query('SELECT * FROM classe_tipo WHERE id=' . $rPlayer['id_classe_tipo'])->row_array();

				$rPlayer['agi_c']	= $class_at['agi'];
				$rPlayer['con_c']	= $class_at['con'];
				$rPlayer['for_c']	= $class_at['forc'];

				//if($_SESSION['universal']) {
					$rPlayer['ene_c']	= $class_at['ene'];
				//} else {
					//$rPlayer['ene_c']	= $class_at['ene'] + (3 * $rPlayer['level']);
				//}

				$rPlayer['int_c']	= $class_at['inte'];
				$rPlayer['tai_c']	= $class_at['tai'];
				$rPlayer['ken_c']	= $class_at['ken'];
				$rPlayer['nin_c']	= $class_at['nin'];
				$rPlayer['gen_c']	= $class_at['gen'];
				$rPlayer['res_c']	= $class_at['res'];
				$rPlayer['conc_c']	= $class_at['conc'];
				$rPlayer['esq_c']	= $class_at['esq'];
				$rPlayer['conv_c']	= $class_at['conv'];
				$rPlayer['esquiva_c']	= $class_at['esquiva'];
				$rPlayer['det_c']	= $class_at['det'];
				$rPlayer['base_f']	= $class_at['campo_base'];
			}

			// Destravamento para hospital + batalha
			if($rPlayer['hospital'] && $rPlayer['id_batalha']) {
				Recordset::update('player', array(
					'id_batalha'	=> 0
				), array(
					'id'			=> $id
				));

				$rPlayer['id_batalha'] = 0;
			}

			$this->at				= $rPlayer;
			$this->id_usuario		= $rPlayer['id_usuario'];
			$this->id_missao_guild2	= 0;

			$this->setLocalAttribute('bijuu', false);
			$this->setLocalAttribute('nome_bijuu', '');

			$this->setLocalAttribute('mist_sword', false);
			$this->setLocalAttribute('mist_sword_name', '');

			$this->setLocalAttribute('nome_cla', '');
			$this->setLocalAttribute('nome_selo', '');
			$this->setLocalAttribute('nome_invocacao', '');

			$this->setLocalAttribute('sennin', false);
			$this->setLocalAttribute('portao', false);
			$this->setLocalAttribute('missao_invasao', false);
			$this->setLocalAttribute('missao_equipe', false);

			$this->setLocalAttribute('nome_guild', '');
			$this->setLocalAttribute('dono_guild', false);
			$this->setLocalAttribute('id_evento4', 0);
			$this->setLocalAttribute('id_missao_guild2', 0);

			$this->setLocalAttribute('nome_equipe', '');
			$this->setLocalAttribute('dono_equipe', false);
			$this->setLocalAttribute('sub_equipe', false);

			if($rPlayer['id_vila_atual']) {
				$vila	= Recordset::query('SELECT nome_' . Locale::get() . ' AS nome, inicial FROM vila WHERE id=' . $rPlayer['id_vila_atual'])->row_array();

				$this->setLocalAttribute('nome_vila_atual', $vila['nome']);

				$this->nome_vila_atual		= $vila['nome'];
				$this->vila_atual_inicial	= $vila['inicial'] ? true : false;
			} else {
				$this->setLocalAttribute('nome_vila_atual', '');
			}

			if($rPlayer['id_cla']) {
				$cla	= Recordset::query('SELECT nome FROM cla WHERE id=' . $rPlayer['id_cla'], true)->row_array();

				$this->setLocalAttribute('nome_cla', $cla['nome']);
				$this->nome_cla	= $cla['nome'];
			}

			if($rPlayer['id_selo']) {
				$selo	= Recordset::query('SELECT nome_br, nome_en FROM selo WHERE id=' . $rPlayer['id_selo'], true)->row_array();

				$this->setLocalAttribute('nome_selo', $selo['nome_' . Locale::get()]);
				$this->nome_selo	= $selo['nome_' . Locale::get()];
			}

			if($rPlayer['id_invocacao']) {
				$invocacao	= Recordset::query('SELECT nome_br, nome_en FROM invocacao WHERE id=' . $rPlayer['id_invocacao'], true)->row()->{'nome_' . Locale::get()};

				$this->setLocalAttribute('nome_invocacao', $invocacao);
				$this->nome_invocacao	= $invocacao;
			}

			if($rPlayer['id_equipe']) {
				$equipe	= Recordset::query('SELECT nome, id_player,id_playerb, id_evento4 FROM equipe WHERE id=' . $rPlayer['id_equipe'])->row_array();

				$this->setLocalAttribute('nome_equipe', $equipe['nome']);
				$this->setLocalAttribute('dono_equipe', $equipe['id_player'] == $rPlayer['id']);
				$this->setLocalAttribute('sub_equipe', $equipe['id_playerb'] == $rPlayer['id']);
				$this->setLocalAttribute('id_evento4', $equipe['id_evento4']);

				$this->nome_equipe	= $equipe['nome'];
				$this->dono_equipe	= $equipe['id_player'] == $rPlayer['id'];
				$this->sub_equipe	= $equipe['id_playerb'] == $rPlayer['id'];
				$this->id_evento4	= $equipe['id_evento4'];
			}

			if($rPlayer['id_guild']) {
				$guild	= Recordset::query('SELECT nome, id_player, id_quest_guild FROM guild WHERE id=' . $rPlayer['id_guild'])->row_array();

				$this->setLocalAttribute('nome_guild', $guild['nome']);
				$this->setLocalAttribute('dono_guild', $guild['id_player'] == $rPlayer['id']);
				$this->setLocalAttribute('id_missao_guild2', $guild['id_quest_guild']);

				$this->nome_guild		= $guild['nome'];
				$this->dono_guild		= $guild['id_player'] == $rPlayer['id'];
				$this->id_missao_guild2	= $guild['id_quest_guild'];
			}

			// compat --->
			$this->id					= $id;
			$this->nome					= $rPlayer['nome'];
			$this->nome_vila			= $rPlayer['nome_vila'];
			$this->id_vila				= $rPlayer['id_vila'];
			$this->id_vila_atual		= $rPlayer['id_vila_atual'];
			$this->nome_classe			= $rPlayer['nome_classe'];
			$this->id_classe			= $rPlayer['id_classe'];
			$this->id_cla				= $rPlayer['id_cla'];
			$this->id_selo				= $rPlayer['id_selo'];
			$this->id_profissao			= $rPlayer['id_profissao'];
			$this->id_invocacao 		= $rPlayer['id_invocacao'];
			$this->id_guild				= $rPlayer['id_guild'];
			$this->id_equipe			= $rPlayer['id_equipe'];
			$this->id_evento			= $rPlayer['id_evento'];
			$this->id_graduacao			= $rPlayer['id_graduacao'];
			$this->id_sensei			= $rPlayer['id_sensei'];
			$this->id_tipo_treino		= $rPlayer['id_tipo_treino'];
			$this->id_tipo_treino_jutsu	= $rPlayer['id_tipo_treino_jutsu'];
			$this->id_jutsu_treino		= $rPlayer['id_jutsu_treino'];
			$this->id_batalha_externa	= $rPlayer['id_batalha_externa'];
			$this->id_batalha			= $rPlayer['id_batalha'];
			$this->id_batalha_multi		= $rPlayer['id_batalha_multi'];
			$this->id_sala 				= $rPlayer['id_sala'];
			$this->id_missao			= $rPlayer['id_missao'];
			$this->id_missao_guild		= $rPlayer['id_missao_guild'];
			$this->id_classe_tipo		= $rPlayer['id_classe_tipo'];
			$this->id_sennin			= $rPlayer['id_sennin'];
			$this->imagem				= $rPlayer['imagem'];
			$this->level				= $rPlayer['level'];
			$this->ult_atividade		= $rPlayer['ult_atividade'];
			$this->exp					= $rPlayer['exp'];
			$this->trava_pagto			= $rPlayer['trava_pagto'];
			$this->vip					= $rPlayer['vip'];
			$this->treino_dia			= $rPlayer['treino_dia'];
			$this->treinando			= $rPlayer['treinando'];
			$this->treino_tempo_jutsu	= $rPlayer['treino_tempo_jutsu'] ? $rPlayer['treino_tempo_jutsu'] : 0;
			$this->arvore_gasto			= $rPlayer['arvore_gasto'];
			$this->exp_equipe_dia		= $rPlayer['exp_equipe_dia'];
			$this->exp_equipe_dia_total	= $rPlayer['exp_equipe_dia_total'];
			$this->id_arena				= $rPlayer['id_arena'];
			$this->id_exame_chuunin		= $rPlayer['id_exame_chuunin'];
			$this->exame_chuunin_etapa	= $rPlayer['exame_chuunin_etapa'];
			$this->id_missao_especial	= $rPlayer['id_missao_especial'];
			$this->id_batalha_multi_pvp	= $rPlayer['id_batalha_multi_pvp'];
			$this->id_sala_multi_pvp	= $rPlayer['id_sala_multi_pvp'];
			$this->id_random_queue		= $rPlayer['id_random_queue'];
			$this->id_random_queue_type	= $rPlayer['id_random_queue_type'];
			$this->credibilidade		= $rPlayer['credibilidade'];

			$this->bijuu				= false;
			$this->nome_bijuu			= '';

			$this->mist_sword			= false;
			$this->mist_sword_name		= '';

			/*
			$this->total_pt_tai_gasto	= $rPlayer['total_pt_tai_gasto'];
			$this->total_pt_nin_gasto	= $rPlayer['total_pt_nin_gasto'];
			$this->total_pt_gen_gasto	= $rPlayer['total_pt_gen_gasto'];
			*/

			$this->ip					= $rPlayer['ip'];
			$this->dentro_vila			= $rPlayer['dentro_vila'];
			$this->nome_graduacao		= $rPlayer['nome_graduacao'];
			$this->hospital				= $rPlayer['hospital'];

			$this->vitorias				= $rPlayer['vitorias'];
			$this->vitorias_f			= $rPlayer['vitorias_f'];
			$this->vitorias_d			= $rPlayer['vitorias_d'];
			$this->vitorias_rnd			= $rPlayer['vitorias_rnd'];
			$this->vitorias_exame		= $rPlayer['vitorias_exame'];
			$this->derrotas				= $rPlayer['derrotas'];
			$this->derrotas_f			= $rPlayer['derrotas_f'];
			$this->derrotas_npc			= $rPlayer['derrotas_npc'];
			$this->derrotas_rnd			= $rPlayer['derrotas_rnd'];
			$this->derrotas_exame		= $rPlayer['derrotas_exame'];
			$this->empates				= $rPlayer['empates'];
			$this->empates_npc				= $rPlayer['empates_npc'];
			$this->empates_rnd			= $rPlayer['empates_rnd'];
			$this->fugas				= $rPlayer['fugas'];
			$this->vila_ranking			= $rPlayer['vila_ranking'];

			$this->treino_total			= $rPlayer['treino_total'];
			$this->treino_gasto			= $rPlayer['treino_gasto'];
			$this->ryou					= $rPlayer['ryou'];
			$this->coin					= $rPlayer['coin'];

			$this->ponto_batalha		= $rPlayer['ponto_batalha'];
			$this->ponto_batalha_gasto	= $rPlayer['ponto_batalha_gasto'];
			$this->last_healed_at		= $rPlayer['last_healed_at'];

			$this->less_hp				= $rPlayer['less_hp'];
			$this->less_sp				= $rPlayer['less_sp'];
			$this->less_sta				= $rPlayer['less_sta'];

			$guerra_ninja	= Recordset::query('SELECT * FROM guerra_ninja WHERE NOW() BETWEEN data_inicio AND data_fim AND finalizado=0');
			$is_war			= false;

			if ($guerra_ninja->num_rows) {
				$guerra_ninja	= $guerra_ninja->row();

				if ($guerra_ninja->akatsuki && in_array($this->id_vila, [6,7,8])) {
					$is_war	= true;
				}

				if (!$guerra_ninja->akatsuki && !in_array($this->id_vila, [6,7,8])) {
					$is_war	= true;
				}
			}

			if ($is_war) {
				$this->id_guerra_ninja	= $guerra_ninja->id;
				$this->guerra_ninja		= $guerra_ninja;
			} else {
				$this->id_guerra_ninja	= 0;
			}

			// <---

			$this->setLocalAttribute('tipo_batalha', 0);

			if($rPlayer['id_batalha']) {
				$rBatalhaTipo = new Recordset("SELECT SQL_NO_CACHE a.id, a.src FROM batalha_tipo a JOIN batalha b ON a.id=b.id_tipo AND b.id=" . (int)$rPlayer['id_batalha']);
				$rBatalhaTipo = $rBatalhaTipo->row_array();

				$this->setLocalAttribute('tipo_batalha', $rBatalhaTipo['id']);
			}


			// Titulo --->
				$this->setLocalAttribute('nome_titulo', '');

				if($rPlayer['id_titulo']) {
					$rTitulo = Recordset::query("SELECT SQL_NO_CACHE * FROM player_titulo WHERE id_usuario=" . $this->id_usuario . " AND id=" . (int)$rPlayer['id_titulo'])->row_array();

					$this->setLocalAttribute('nome_titulo', $rTitulo['titulo_' . Locale::get()]);

					$this->id_titulo	= $rTitulo['id'];
					$this->nome_titulo	= $rTitulo['titulo_' . Locale::get()];

					player_titulo_grad($this->nome_titulo, $this);
					$this->setLocalAttribute('nome_titulo', $this->nome_titulo);
				}
			// <---

			// Atributos basicos --->
				if($this->id) {
					$player_at_fixo = Recordset::query('SELECT SQL_NO_CACHE * FROM player_atributos WHERE id_player=' . $this->id);

					if(!$player_at_fixo->num_rows) {
						Recordset::insert('player_atributos', array(
							'id_player'	=> $this->id
						));

						$player_at_fixo->repeat();
					}

					$player_at_fixo = $player_at_fixo->row_array();
				} else {
					$player_at_fixo = array(
						'id'		=> 0,
						'id_player'	=> 0,
						'tai'		=> 0,
						'ken'		=> 0,
						'nin'		=> 0,
						'gen'		=> 0,
						'agi'		=> 0,
						'con'		=> 0,
						'ene'		=> 0,
						'inte'		=> 0,
						'forc'		=> 0,
						'res'		=> 0,
						'crit_min'	=> 0,
						'crit_max'	=> 0,
						'crit_total' => 0,
						'esq_min'	=> 0,
						'esq_max'	=> 0,
						'esq_total' => 0,
						'esq'		=> 0,
						'conc'		=> 0,
						'det'		=> 0,
						'conv'		=> 0,
						'esquiva'   => 0
					);
				}

				$raw_at = array(
					'tai'	=> $rPlayer['tai_p']  + $rPlayer['tai_c'] 	+ $player_at_fixo['tai'],
					'ken'	=> $rPlayer['ken_p']  + $rPlayer['ken_c'] 	+ $player_at_fixo['ken'],
					'nin'	=> $rPlayer['nin_p']  + $rPlayer['nin_c'] 	+ $player_at_fixo['nin'],
					'gen'	=> $rPlayer['gen_p']  + $rPlayer['gen_c'] 	+ $player_at_fixo['gen'],
					'agi'	=> $rPlayer['agi_p']  + $rPlayer['agi_c'] 	+ $player_at_fixo['agi'],
					'con'	=> $rPlayer['con_p']  + $rPlayer['con_c'] 	+ $player_at_fixo['con'],
					'ene'	=> $rPlayer['ene_p']  + $rPlayer['ene_c'] 	+ $player_at_fixo['ene'],
					'ene2'	=> $rPlayer['ene_c']  + $player_at_fixo['ene'],
					'int'	=> $rPlayer['int_p']  + $rPlayer['int_c'] 	+ $player_at_fixo['inte'],
					'for'	=> $rPlayer['for_p']  + $rPlayer['for_c'] 	+ $player_at_fixo['forc'],
					'res'	=> $rPlayer['res_p']  + $rPlayer['res_c'] 	+ $player_at_fixo['res'],
					'conc2'	=> $rPlayer['conc_p'] + $rPlayer['conc_c'],
					'esq2'	=> $rPlayer['esq_p']  + $rPlayer['esq_c'],
					'conv2'	=> $rPlayer['conv_p'] + $rPlayer['conv_c'],
					'esquiva2'	=> $rPlayer['esquiva_p'] + $rPlayer['esquiva_c'],
					'det2'	=> $rPlayer['det_p']  + $rPlayer['det_c']
				);

				$this->setLocalAttribute('tai_raw', $raw_at['tai']);
				$this->setLocalAttribute('ken_raw', $raw_at['ken']);
				$this->setLocalAttribute('nin_raw', $raw_at['nin']);
				$this->setLocalAttribute('gen_raw', $raw_at['gen']);
				$this->setLocalAttribute('agi_raw', $raw_at['agi']);
				$this->setLocalAttribute('con_raw', $raw_at['con']);
				$this->setLocalAttribute('ene_raw', $raw_at['ene']);
				$this->setLocalAttribute('ene_raw2', $raw_at['ene2']);
				$this->setLocalAttribute('conc_raw2', $raw_at['conc2']);
				$this->setLocalAttribute('esq_raw2', $raw_at['esq2']);
				$this->setLocalAttribute('conv_raw2', $raw_at['conv2']);
				$this->setLocalAttribute('esquiva_raw2', $raw_at['esquiva2']);
				$this->setLocalAttribute('det_raw2', $raw_at['det2']);
				$this->setLocalAttribute('int_raw', $raw_at['int']);
				$this->setLocalAttribute('for_raw', $raw_at['for']);
				$this->setLocalAttribute('res_raw', $raw_at['res']);

				$this->setLocalAttribute('crit_min_raw', $player_at_fixo['crit_min']);
				$this->setLocalAttribute('crit_max_raw', $player_at_fixo['crit_max']);
				$this->setLocalAttribute('crit_total_raw', $player_at_fixo['crit_total']);
				$this->setLocalAttribute('esq_min_raw', $player_at_fixo['esq_min']);
				$this->setLocalAttribute('esq_max_raw', $player_at_fixo['esq_max']);
				$this->setLocalAttribute('esq_total_raw', $player_at_fixo['esq_total']);
				$this->setLocalAttribute('esq_raw', $player_at_fixo['esq']);
				$this->setLocalAttribute('conc_raw', $player_at_fixo['conc']);
				$this->setLocalAttribute('det_raw', $player_at_fixo['det']);
				$this->setLocalAttribute('conv_raw', $player_at_fixo['conv']);
				$this->setLocalAttribute('esquiva_raw', $player_at_fixo['esquiva']);

				#CX
				$this->tai_raw		= $raw_at['tai'];
				$this->ken_raw		= $raw_at['ken'];
				$this->nin_raw		= $raw_at['nin'];
				$this->gen_raw		= $raw_at['gen'];
				$this->agi_raw		= $raw_at['agi'];
				$this->con_raw		= $raw_at['con'];
				$this->ene_raw		= $raw_at['ene'];
				$this->ene_raw2		= $raw_at['ene2'];
				$this->int_raw		= $raw_at['int'];
				$this->for_raw		= $raw_at['for'];
				$this->res_raw		= $raw_at['res'];
				$this->conc_raw2	= $raw_at['conc2'];
				$this->esq_raw2		= $raw_at['esq2'];
				$this->conv_raw2	= $raw_at['conv2'];
				$this->esquiva_raw2	= $raw_at['esquiva2'];
				$this->det_raw2		= $raw_at['det2'];

				$this->at['tai']	= $rPlayer['tai_p'];
				$this->at['ken']	= $rPlayer['ken_p'];
				$this->at['nin']	= $rPlayer['nin_p'];
				$this->at['gen']	= $rPlayer['gen_p'];
				$this->at['agi']	= $rPlayer['agi_p'];
				$this->at['con']	= $rPlayer['con_p'];
				$this->at['ene']	= $rPlayer['ene_p'];
				$this->at['int']	= $rPlayer['int_p'];
				$this->at['for']	= $rPlayer['for_p'];
				$this->at['inte']	= $rPlayer['int_p'];
				$this->at['forc']	= $rPlayer['for_p'];
				$this->at['res']	= $rPlayer['res_p'];
				$this->at['conc']	= $rPlayer['conc_p'];
				$this->at['esq']	= $rPlayer['esq_p'];
				$this->at['conv']	= $rPlayer['conv_p'];
				$this->at['esquiva'] = $rPlayer['esquiva_p'];
				$this->at['det']	= $rPlayer['det_p'];

				$this->setLocalAttribute('inc_treino', 0);
				$this->setLocalAttribute('inc_ryou', 0);
				$this->setLocalAttribute('inc_exp', 0);
				$this->setLocalAttribute('inc_turno', 0);
			// <---


			// PRE Projeto do sistema de missão independente ms que ser pras condições da versão 1 c/travamento --->
				$this->setLocalAttribute('missao_interativa', false);
				$this->setLocalAttribute('missao_comum', false);

				if($rPlayer['id_missao']) {
					if ($rPlayer['id_missao'] == -1) {
						$this->setLocalAttribute('missao_comum', true);
						$this->missao_comum		= true;
					} else {
						$qQuest = Recordset::query('SELECT interativa, equipe FROM quest WHERE id=' . $rPlayer['id_missao'], true);

						if ($qQuest->num_rows) {
							$rQuest = $qQuest->row_array();

							if($rQuest['equipe']) {
								$this->setLocalAttribute('missao_equipe', true);
								$this->missao_equipe	= true;
							}

							if($rQuest['interativa']) {
								$this->setLocalAttribute('missao_interativa', true);
								$this->missao_interativa	= true;
							} else {
								$this->setLocalAttribute('missao_comum', true);
								$this->missao_comum		= true;
							}
						}
					}
				}
			// <---

			$this->arItems = array();

			// Somatoria dos bonus dos itens equipaveis --->
				$this->rebuildItems();

				$items = $items_arv = array(
					'tai'			=> 0,
					'ken'			=> 0,
					'nin'			=> 0,
					'gen'			=> 0,
					'agi'			=> 0,
					'con'			=> 0,
					'ene'			=> 0,
					'for'			=> 0,
					'int'			=> 0,
					'res'			=> 0,

					'hp'			=> 0,
					'sp'			=> 0,
					'sta'			=> 0,
					'atk_fisico'	=> 0,
					'atk_magico'	=> 0,
					'def_fisico'	=> 0,
					'def_magico'	=> 0,
					'def_base'		=> 0,
					'prec_fisico'	=> 0,
					'prec_magico'	=> 0,
					'crit_min'		=> 0,
					'crit_max'		=> 0,
					'crit_total'	=> 0,
					'esq_min'		=> 0,
					'esq_max'		=> 0,
					'esq_total'		=> 0,
					'esq'			=> 0,
					'det'			=> 0,
					'conv'			=> 0,
					'esquiva'		=> 0,
					'conc'			=> 0
				);

				$this->setLocalAttribute('id_cla_atual', 0);
				$this->setLocalAttribute('id_portao_atual', 0);
				$this->setLocalAttribute('nome_sennin', '');
				$this->setLocalAttribute('nome_portao', '');

				$sennin_ordem	= 0;
				$portao_ordem	= 0;

				$items_to_loopb	= array_concat_by_keys($this->_ar_items_types, 10,11,12,13,14,15,29);
				$items_to_loop	= array_concat_by_keys($this->_ar_items_typesb, 16,17,20,21,23,25,26,30,31);

				
				if(!sizeof($items_to_loop)) {
					$items_to_loop	= array();
				} else {
					$items_query	= Recordset::query('SELECT * FROM item WHERE id IN(' . implode(',', $items_to_loop) . ')');
					$items_data		= array();

					foreach($items_query->result_array() as $item) {
						$items_data[$item['id']]	= $item;
					}
				}
				// Começo do for de clas,invocações e afins
				foreach($items_to_loop as $item) {
					$mine_item	= $this->_ar_items_mine[$item];
					$item_data	= $items_data[$item];
					$ok			= false;

					// sennin
					if($item_data['id_tipo'] == 26) {
						$this->setLocalAttribute('sennin', true);

						if($item_data['ordem'] > $sennin_ordem) {
							$this->setLocalAttribute('nome_sennin', $item_data['nome_'. Locale::get()]);

							$this->nome_sennin	= $item_data['nome_'. Locale::get()];
							$sennin_ordem		= $item_data['ordem'];
						}
					}

					// bijuu
					if($item_data['id_tipo'] == 23) {
						$this->setLocalAttribute('bijuu', true);
						$this->setLocalAttribute('nome_bijuu', $item_data['nome_'. Locale::get()]);

						$this->nome_bijuu	= $item_data['nome_'. Locale::get()];
						$this->bijuu		= true;
					}

					// espadas
					if($item_data['id_tipo'] == 39) {
						$this->setLocalAttribute('mist_sword', true);
						$this->setLocalAttribute('mist_sword_name', $item_data['nome_'. Locale::get()]);

						$this->mist_sword_name	= $item_data['nome_'. Locale::get()];
						$this->mist_sword		= true;
					}

					//portao
					if($item_data['id_tipo'] == 17) {
						$this->setLocalAttribute('portao', true);

						if ($item_data['ordem'] > $portao_ordem) {
							$this->setLocalAttribute('id_portao_atual', $item_data['id']);
							$this->setLocalAttribute('nome_portao', $item_data['nome_'. Locale::get()]);

							$this->id_portao_atual	= $item_data['id'];
							$this->nome_portao		= $item_data['nome_'. Locale::get()];
							$portao_ordem			= $item_data['ordem'];
						}
					}

					if(in_array($item_data['id_tipo'], array(16, 17)) && $mine_item['ativo']) {
						if($item_data['id_tipo'] == 16) {
							$this->setLocalAttribute('id_cla_atual', $item_data['id']);

							$this->id_cla_atual			= $item_data['id'];
						} elseif($item_data['id_tipo'] == 20) {
							$this->id_selo_atual		= $item_data['id'];
						} elseif($item_data['id_tipo'] == 21) {
							$this->id_invocacao_atual	= $item_data['id'];
						}
					}elseif(in_array($item_data['id_tipo'], array(25)) && $mine_item['equipado']) {
						$ok = true;
					}
		
					if(!$ok) {
						continue;
					}

					$ats_type		= $item_data['tipo_bonus'];

					// Esses aqui são calculados somente na AtCalc --->
						$atk_fisico		= $item_data['atk_fisico'];
						$atk_magico		= $item_data['atk_magico'];
						$def_fisico		= $item_data['def_fisico'];
						$def_magico		= $item_data['def_magico'];
						$def_base		= $item_data['def_base'];
					// <---

					// Atributos variaveis(fixo/porcentagem --->
					$tai			= $item_data['tai'];
					$ken			= $item_data['ken'];
					$nin			= $item_data['nin'];
					$gen			= $item_data['gen'];
					$agi			= $item_data['agi'];
					$con			= $item_data['con'];
					$ene			= $item_data['ene'];
					$for			= $item_data['forc'];
					$int			= $item_data['inte'];
					$res			= $item_data['res'];
					// <---

					$prec_fisico	= $item_data['prec_fisico'];
					$prec_magico	= $item_data['prec_magico'];
					$crit_min		= $item_data['crit_min'];
					$crit_max		= $item_data['crit_max'];
					$crit_total		= $item_data['crit_total'];
					$esq_min		= $item_data['esq_min'];
					$esq_max		= $item_data['esq_max'];
					$esq_total		= $item_data['esq_total'];
					$esq			= $item_data['esq'];
					$det			= $item_data['det'];
					$conv			= $item_data['conv'];
					$esquiva		= $item_data['esquiva'];
					$conc			= $item_data['conc'];
					$hp				= $item_data['bonus_hp'];
					$sp				= $item_data['bonus_sp'];
					$sta			= $item_data['bonus_sta'];

					if($item_data['id_tipo'] == 25) {
						$items_arv['tai']			+= $tai;
						$items_arv['ken']			+= $ken;
						$items_arv['nin']			+= $nin;
						$items_arv['gen']			+= $gen;
						$items_arv['agi']			+= $agi;
						$items_arv['con']			+= $con;
						$items_arv['ene']			+= $ene;
						$items_arv['for']			+= $for;
						$items_arv['int']			+= $int;
						$items_arv['res']			+= $res;

						$items_arv['hp']			+= $hp;
						$items_arv['sp']			+= $sp;
						$items_arv['sta']			+= $sta;
						$items_arv['prec_fisico']	+= $prec_fisico;
						$items_arv['prec_magico']	+= $prec_magico;
						$items_arv['crit_min']		+= $crit_min;
						$items_arv['crit_max']		+= $crit_max;
						$items_arv['crit_total']	+= $crit_total;
						$items_arv['esq_min']		+= $esq_min;
						$items_arv['esq_max']		+= $esq_max;
						$items_arv['esq_total']		+= $esq_total;
						$items_arv['esq']			+= $esq;
						$items_arv['det']			+= $det;
						$items_arv['conv']			+= $conv;
						$items_arv['esquiva']		+= $esquiva;
						$items_arv['conc']			+= $conc;

						if(!$ats_type) {
							$this->_calc_atp['atk_fisico_item']		+= $atk_fisico;
							$this->_calc_atp['atk_magico_item']		+= $atk_magico;
							$this->_calc_atp['def_fisico_item']		+= $def_fisico;
							$this->_calc_atp['def_magico_item']		+= $def_magico;
							$this->_calc_atp['def_base_item']		+= $def_base;
						} else {
							$this->_calc_at['atk_fisico_item']		+= $atk_fisico;
							$this->_calc_at['atk_magico_item']		+= $atk_magico;
							$this->_calc_at['def_fisico_item']		+= $def_fisico;
							$this->_calc_at['def_magico_item']		+= $def_magico;
							$this->_calc_at['def_base_item']		+= $def_base;
						}

						$this->inc_treino		+= $item_data['bonus_treino'];
						$this->inc_turno		+= $item_data['turnos'];
						$this->inc_ryou			+= $item_data['ryou'];

						$this->less_consume_sp	+= $item_data['consume_sp'];
						$this->less_consume_sta	+= $item_data['consume_sta'];

						// Novos Bõnus arvore R10 -->
							if($item_data['req_con']) {
								$this->bonus_vila['ns_dano_curto']	+= $item_data['req_con'];
							}

							if($item_data['req_tai']) {
								$this->bonus_vila['ns_dano_longo']		+= $item_data['req_tai'];
							}

							if($item_data['req_int']) {
								$this->bonus_vila['dojo_limite_npc_mapa']	+= $item_data['req_int'];
							}

							if($item_data['req_for']) {
								$this->bonus_vila['dojo_limite_npc_dojo']	+= $item_data['req_for'];
							}

							if($item_data['req_agi']) {
								$this->bonus_vila['sk_missao_tempo']	+= $item_data['req_agi'];
							}
						// <--
					} else {
						$items['tai']			+= $tai;
						$items['ken']			+= $ken;
						$items['nin']			+= $nin;
						$items['gen']			+= $gen;
						$items['agi']			+= $agi;
						$items['con']			+= $con;
						$items['ene']			+= $ene;
						$items['for']			+= $for;
						$items['int']			+= $int;
						$items['res']			+= $res;
						$items['hp']			+= $hp;
						$items['sp']			+= $sp;
						$items['sta']			+= $sta;
						$items['prec_fisico']	+= $prec_fisico;
						$items['prec_magico']	+= $prec_magico;
						$items['crit_min']		+= $crit_min;
						$items['crit_max']		+= $crit_max;
						$items['crit_total']	+= $crit_total;
						$items['esq_min']		+= $esq_min;
						$items['esq_max']		+= $esq_max;
						$items['esq_total']		+= $esq_total;
						$items['esq']			+= $esq;
						$items['det']			+= $det;
						$items['conv']			+= $conv;
						$items['esquiva']		+= $esquiva;
						$items['conc']			+= $conc;

						if(!$ats_type) {
							$this->_calc_atp['atk_fisico_arv']		+= $atk_fisico;
							$this->_calc_atp['atk_magico_arv']		+= $atk_magico;
							$this->_calc_atp['def_fisico_arv']		+= $def_fisico;
							$this->_calc_atp['def_magico_arv']		+= $def_magico;
							$this->_calc_atp['def_base_arv']		+= $def_base;
						} else {
							$this->_calc_at['atk_fisico_arv']		+= $atk_fisico;
							$this->_calc_at['atk_magico_arv']		+= $atk_magico;
							$this->_calc_at['def_fisico_arv']		+= $def_fisico;
							$this->_calc_at['def_magico_arv']		+= $def_magico;
							$this->_calc_at['def_base_arv']			+= $def_base;
						}
					}
				}
				// Fim do For
			
				// Começo do For dos Equipamentos
				if(!sizeof($items_to_loopb)) {
					$items_to_loopb	= array();
				} else {
					if($items_to_loopb){
						$items_query	= Recordset::query('SELECT * FROM player_item_atributos WHERE id_player_item IN(' . implode(',', $items_to_loopb) . ')');
					}else{				
						$items_query	= Recordset::query('SELECT * FROM item WHERE id IN(' . implode(',', $items_to_loopb) . ')');
					}
					$items_data		= array();
					
					foreach($items_query->result_array() as $item) {
						$items_data[$item['id_player_item']]	= $item;
					}
				}
				foreach($items_to_loopb as $item) {
					$mine_item	= $this->_ar_items_mineb[$item];
					$item_data	= $items_data[$item];
					
					$ok			= false;
					
					if($mine_item['equipado']) {
						$ok = true;
					}
					
					if(!$ok) {
						continue;
					}
					$ats_type		= 1;
					
				// Esses aqui são calculados somente na AtCalc --->
					$atk_fisico		= $item_data['atk_fisico'];
					$atk_magico		= $item_data['atk_magico'];
					$def_fisico		= $item_data['def_fisico'];
					$def_magico		= $item_data['def_magico'];
					$def_base		= $item_data['def_base'];
					// <---

					// Atributos variaveis(fixo/porcentagem --->
					$tai			= $item_data['tai'];
					$ken			= $item_data['ken'];
					$nin			= $item_data['nin'];
					$gen			= $item_data['gen'];
					$agi			= $item_data['agi'];
					$con			= $item_data['con'];
					$ene			= $item_data['ene'];
					$for			= $item_data['forc'];
					$int			= $item_data['inte'];
					$res			= $item_data['res'];
					// <---

					$prec_fisico	= $item_data['prec_fisico'];
					$prec_magico	= $item_data['prec_magico'];
					$crit_min		= $item_data['crit_min'];
					$crit_max		= $item_data['crit_max'];
					$crit_total		= $item_data['crit_total'];
					$esq_min		= $item_data['esq_min'];
					$esq_max		= $item_data['esq_max'];
					$esq_total		= $item_data['esq_total'];
					$esq			= $item_data['esq'];
					$det			= $item_data['det'];
					$conv			= $item_data['conv'];
					$esquiva		= $item_data['esquiva'];
					$conc			= $item_data['conc'];
					$hp				= $item_data['bonus_hp'];
					$sp				= $item_data['bonus_sp'];
					$sta			= $item_data['bonus_sta'];

					
					$items['tai']			+= $tai;
					$items['ken']			+= $ken;
					$items['nin']			+= $nin;
					$items['gen']			+= $gen;
					$items['agi']			+= $agi;
					$items['con']			+= $con;
					$items['ene']			+= $ene;
					$items['for']			+= $for;
					$items['int']			+= $int;
					$items['res']			+= $res;
					$items['hp']			+= $hp;
					$items['sp']			+= $sp;
					$items['sta']			+= $sta;
					$items['atk_fisico']	+= $atk_fisico;
					$items['atk_magico']	+= $atk_magico;
					$items['def_fisico']	+= $def_fisico;
					$items['def_magico']	+= $def_magico;
					$items['def_base']		+= $def_base;
					$items['prec_fisico']	+= $prec_fisico;
					$items['prec_magico']	+= $prec_magico;
					$items['crit_min']		+= $crit_min;
					$items['crit_max']		+= $crit_max;
					$items['crit_total']	+= $crit_total;
					$items['esq_min']		+= $esq_min;
					$items['esq_max']		+= $esq_max;
					$items['esq_total']		+= $esq_total;
					$items['esq']			+= $esq;
					$items['det']			+= $det;
					$items['conv']			+= $conv;
					$items['esquiva']		+= $esquiva;
					$items['conc']			+= $conc;
					
					if(!$ats_type) {
						$this->_calc_atp['atk_fisico_item']		+= $atk_fisico;
						$this->_calc_atp['atk_magico_item']		+= $atk_magico;
						$this->_calc_atp['def_fisico_item']		+= $def_fisico;
						$this->_calc_atp['def_magico_item']		+= $def_magico;
						$this->_calc_atp['def_base_item']		+= $def_base;
					} else {
						$this->_calc_at['atk_fisico_item']		+= $atk_fisico;
						$this->_calc_at['atk_magico_item']		+= $atk_magico;
						$this->_calc_at['def_fisico_item']		+= $def_fisico;
						$this->_calc_at['def_magico_item']		+= $def_magico;
						$this->_calc_at['def_base_item']		+= $def_base;
					}
					
				}	
				// Fim do For dos Equipamentos

				// Aplica os atributos de item --->
					$this->setLocalAttribute('tai_item', $items['tai']);
					$this->setLocalAttribute('ken_item', $items['ken']);
					$this->setLocalAttribute('nin_item', $items['nin']);
					$this->setLocalAttribute('gen_item', $items['gen']);
					$this->setLocalAttribute('agi_item', $items['agi']);
					$this->setLocalAttribute('con_item', $items['con']);
					$this->setLocalAttribute('ene_item', $items['ene']);
					$this->setLocalAttribute('for_item', $items['for']);
					$this->setLocalAttribute('int_item', $items['int']);
					$this->setLocalAttribute('res_item', $items['res']);

					$this->setLocalAttribute('hp_item'				, $items['hp']);
					$this->setLocalAttribute('sp_item'				, $items['sp']);
					$this->setLocalAttribute('sta_item'				, $items['sta']);
					$this->setLocalAttribute('atk_fisico_item'		, $items['atk_fisico']);
					$this->setLocalAttribute('atk_magico_item'		, $items['atk_magico']);
					$this->setLocalAttribute('def_fisico_item'		, $items['def_fisico']);
					$this->setLocalAttribute('def_magico_item'		, $items['def_magico']);
					$this->setLocalAttribute('def_base_item'		, $items['def_base']);
					$this->setLocalAttribute('prec_fisico_item'		, $items['prec_fisico']);
					$this->setLocalAttribute('prec_magico_item'		, $items['prec_magico']);
					$this->setLocalAttribute('crit_min_item'		, $items['crit_min']);
					$this->setLocalAttribute('crit_max_item'		, $items['crit_max']);
					$this->setLocalAttribute('crit_total_item'		, $items['crit_total']);
					$this->setLocalAttribute('esq_min_item'			, $items['esq_min']);
					$this->setLocalAttribute('esq_max_item'			, $items['esq_max']);
					$this->setLocalAttribute('esq_total_item'		, $items['esq_total']);
					$this->setLocalAttribute('esq_item'				, $items['esq']);
					$this->setLocalAttribute('det_item'				, $items['det']);
					$this->setLocalAttribute('conv_item'			, $items['conv']);
					$this->setLocalAttribute('esquiva_item'			, $items['esquiva']);
					$this->setLocalAttribute('conc_item'			, $items['conc']);

				// <---

				// Aplica atributos da arvore --->
					$this->setLocalAttribute('tai_arv', $items_arv['tai']);
					$this->setLocalAttribute('ken_arv', $items_arv['ken']);
					$this->setLocalAttribute('nin_arv', $items_arv['nin']);
					$this->setLocalAttribute('gen_arv', $items_arv['gen']);
					$this->setLocalAttribute('agi_arv', $items_arv['agi']);
					$this->setLocalAttribute('con_arv', $items_arv['con']);
					$this->setLocalAttribute('ene_arv', $items_arv['ene']);
					$this->setLocalAttribute('for_arv', $items_arv['for']);
					$this->setLocalAttribute('int_arv', $items_arv['int']);
					$this->setLocalAttribute('res_arv', $items_arv['res']);

					$this->setLocalAttribute('hp_arv'				, $items_arv['hp']);
					$this->setLocalAttribute('sp_arv'				, $items_arv['sp']);
					$this->setLocalAttribute('sta_arv'				, $items_arv['sta']);
					$this->setLocalAttribute('atk_fisico_arv'		, $items_arv['atk_fisico']);
					$this->setLocalAttribute('atk_magico_arv'		, $items_arv['atk_magico']);
					$this->setLocalAttribute('def_fisico_arv'		, $items_arv['def_fisico']);
					$this->setLocalAttribute('def_magico_arv'		, $items_arv['def_magico']);
					$this->setLocalAttribute('def_base_arv'			, $items_arv['def_base']);
					$this->setLocalAttribute('prec_fisico_arv'		, $items_arv['prec_fisico']);
					$this->setLocalAttribute('prec_magico_arv'		, $items_arv['prec_magico']);
					$this->setLocalAttribute('crit_min_arv'			, $items_arv['crit_min']);
					$this->setLocalAttribute('crit_max_arv'			, $items_arv['crit_max']);
					$this->setLocalAttribute('crit_total_arv'		, $items_arv['crit_total']);
					$this->setLocalAttribute('esq_min_arv'			, $items_arv['esq_min']);
					$this->setLocalAttribute('esq_max_arv'			, $items_arv['esq_max']);
					$this->setLocalAttribute('esq_total_arv'		, $items_arv['esq_total']);
					$this->setLocalAttribute('esq_arv'				, $items_arv['esq']);
					$this->setLocalAttribute('det_arv'				, $items_arv['det']);
					$this->setLocalAttribute('conv_arv'				, $items_arv['conv']);
					$this->setLocalAttribute('esquiva_arv'			, $items_arv['esquiva']);
					$this->setLocalAttribute('conc_arv'				, $items_arv['conc']);
				// <---
			// <---

			// Missões de invasão --->
				if($rPlayer['id_guild']) {
					$missao_invasao = Recordset::query("SELECT SQL_NO_CACHE id, id_npc_vila, id_vila FROM vila_quest WHERE id_guild=" . $rPlayer['id_guild']);

					// aka, o palhaço TEM uma missao de invasão
					if($missao_invasao->num_rows) {
						$missao_invasao = $missao_invasao->row_array();

						$this->setLocalAttribute('missao_invasao', $missao_invasao['id']);
						$this->setLocalAttribute('missao_invasao_vila', $missao_invasao['id_vila']);
						$this->setLocalAttribute('missao_invasao_npc', $missao_invasao['id_npc_vila']);
					}
				}
			// <---

			// Sistema de torneios --->
				$this->setLocalAttribute('id_torneio', 0);
				$this->setLocalAttribute('id_torneio_espera', 0);

				$this->id_torneio			= 0;
				$this->id_torneio_espera	= 0;

				if($this->id) {
					$torneio = Recordset::query('SELECT SQL_NO_CACHE * FROM torneio_player WHERE id_player=' . $this->id . ' AND participando=\'1\'');

					if($torneio->num_rows) {
						$torneio		= $torneio->row_array();
						$torneio_espera = Recordset::query('SELECT SQL_NO_CACHE * FROM torneio_espera WHERE id_player_b=0 AND id_torneio=' . $torneio['id_torneio'] . ' AND id_player=' . $this->id);

						if($torneio_espera->num_rows) {
							$torneio_espera = $torneio_espera->row_array();

							$this->setLocalAttribute('id_torneio_espera', $torneio_espera['id']);

							$this->id_torneio_espera	= $torneio_espera['id'];
						}

						$this->setLocalAttribute('id_torneio', $torneio['id_torneio']);

						$this->id_torneio	= $torneio['id_torneio'];
					}
				}
			// <---

			$this->do_key_mapping();

			// Default modifiers --->
			$this->setLocalAttribute('tai_mod', 0);
			$this->setLocalAttribute('ken_mod', 0);
			$this->setLocalAttribute('nin_mod', 0);
			$this->setLocalAttribute('gen_mod', 0);
			$this->setLocalAttribute('agi_mod', 0);
			$this->setLocalAttribute('con_mod', 0);
			$this->setLocalAttribute('ene_mod', 0);
			$this->setLocalAttribute('for_mod', 0);
			$this->setLocalAttribute('int_mod', 0);
			$this->setLocalAttribute('res_mod', 0);
			$this->setLocalAttribute('conc_mod', 0);
			$this->setLocalAttribute('esq_mod', 0);
			$this->setLocalAttribute('conv_mod', 0);
			$this->setLocalAttribute('esquiva_mod', 0);
			$this->setLocalAttribute('det_mod', 0);

			$this->setLocalAttribute('atk_fisico_mod', 0);
			$this->setLocalAttribute('atk_magico_mod', 0);
			$this->setLocalAttribute('def_fisico_mod', 0);
			$this->setLocalAttribute('def_magico_mod', 0);
			$this->setLocalAttribute('def_base_mod', 0);
			// <---

			$this->parseModifiers();
			$this->atCalc();

			// Se for for criação rapida(aka listar todos os players por exemplo) --->
			if(!$quick) {
				// Treino --->
					$dias_treino = array(
						1	=> 3,
						2	=> 4,
						3	=> 5,
						4	=> 6,
						5	=> 0,
						6	=> 1,
						7	=> 2
					);

					$max_treino			= 4000 + (($this->id_graduacao <= 2 ? 0 : $this->id_graduacao - 2) * 1000);
					$max_treino_jutsu	= 4000;

					//Removido a vantagem de créditos.
					//$max_treino += round(percent(45, $max_treino));

					// VIP
					if($this->hasItem(1495)) {
						$max_treino += round(percent(45, $max_treino));
					} elseif($this->hasItem(1494)) {
						$max_treino += round(percent(30, $max_treino));
					} elseif($this->hasItem(1083)) {
						$max_treino += round(percent(15, $max_treino));
					}
					

					// Arvore e itens
					$max_treino += $this->inc_treino;
					$max_treino += $max_treino * $dias_treino[date('N')];

					$this->setLocalAttribute('max_treino', $max_treino);

					$this->setLocalAttribute('inc_treino', $this->inc_treino);
					$this->setLocalAttribute('inc_turno', $this->inc_turno);
					$this->setLocalAttribute('inc_ryou', $this->inc_ryou);

					// Treino de jutsu -->
						$max_treino_jutsu	= 3000 + ($this->hasItem(array(21485)) ? 1000: 0);
						$max_treino_jutsu 	+= $max_treino_jutsu * $dias_treino[date('N')];

						$this->setLocalAttribute('max_treino_jutsu', $max_treino_jutsu);
					// <--
				// <---

				// Tabela de posições --->
					if(!isset($_SESSION['player_posicao_check']) || (isset($_SESSION['player_posicao_check']) && $_SESSION['player_posicao_check'])) {
						if(!Recordset::query("SELECT SQL_NO_CACHE * FROM player_posicao WHERE id_player=" . $this->id)->num_rows) {
							Recordset::insert('player_posicao', array(
								'id_player'	=> $this->id,
								'xpos'		=> 0,
								'ypos'		=> 0,
								'vila'		=> $this->id_vila,
								'nome'		=> $this->nome,
								'batalha'	=> $this->id_batalha,
								'nivel'		=> $this->level
							));
						}

						$_SESSION['player_posicao_check'] = true;
					}
				// <--

				// Nome do player (no worries, um varchar(50) indexado ocupa pouco espaço) --->
					if(!isset($_SESSION['playerName']) || (isset($_SESSION['playerName']) && $_SESSION['playerName'] != $rPlayer['nome'])) {
						Recordset::insert('player_nome', array(
							'id_player'	=>	$this->id,
							'nome'		=>	$rPlayer['nome']
						), array(
							'nome'		=>	$rPlayer['nome']
						));

						$_SESSION['playerName'] = $rPlayer['nome'];
					}
				// <---

				// Reputação --->
					$reputacoes	= Recordset::query('SELECT * FROM player_reputacao WHERE id_player=' . $this->id);

					foreach ($reputacoes->result_array() as $reputacao) {
						$this->reputacoes[$reputacao['id_vila']]		= $reputacao['id_reputacao'];
					}

					foreach($this->reputacoes as $k => $v) {
						$this->reputacoes_nome[$k]	= Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM reputacao WHERE id=' . $v, true)->row()->nome;
					}
				// <---

				if($this->id_vila_atual == 0 && $this->dentro_vila) {
					Recordset::update('player', array(
						'id_vila_atual'	=> $this->id_vila
					), array(
						'id'			=> $this->id
					));

					$this->setLocalAttribute('id_vila_atual', $this->id_vila);
				}

				SharedStore::S('P_INT_' . $this->id, (string)$this->getAttribute('int_calc'));
				SharedStore::S('P_FOR_' . $this->id, (string)$this->getAttribute('for_calc'));

				// Bonus das vilas --->
					$items	= Recordset::query('SELECT b.* FROM vila_item a JOIN item b ON b.id=a.item_id WHERE a.vila_id=' . $this->getAttribute('id_vila'));

					foreach($items->result_array() as $item) {
						if($item['tai']) {
							$this->bonus_vila['ns_dano_longo']		+= $item['tai'];
						}

						if($item['nin']) {
							$this->bonus_vila['ns_preco']		+= $item['nin'];
						}

						if($item['gen']) {
							$this->bonus_vila['ramen_preco']	+= $item['gen'];
						}

						if($item['agi']) {
							$this->bonus_vila['sk_missao_tempo']	+= $item['agi'];
						}

						if($item['con']) {
							$this->bonus_vila['sk_missao_ryou']	+= $item['con'];
						}

						if($item['forc']) {
							$this->bonus_vila['sk_missao_exp']	+= $item['forc'];
						}
						if($item['crit_total']) {
							$this->bonus_vila['hospital_preco']	+= $item['crit_total'];
						}
						if($item['crit_max']) {
							$this->bonus_vila['hospital_preco']	+= $item['crit_max'];
						}

						if($item['crit_min']) {
							$this->bonus_vila['hospital_vida']	+= $item['crit_min'];
						}

						if($item['ene']) {
							$this->bonus_vila['dojo_ryou_npc']	+= $item['ene'];
						}

						if($item['inte']) {
							$this->bonus_vila['dojo_exp_npc']	+= $item['inte'];
						}

						if($item['res']) {
							$this->bonus_vila['dojo_ryou_pvp']	+= $item['res'];
						}

						if($item['atk_fisico']) {
							$this->bonus_vila['dojo_exp_pvp']	+= $item['atk_fisico'];
						}

						if($item['atk_magico']) {
							$this->bonus_vila['dojo_limite_npc_mapa']	+= $item['atk_magico'];
						}

						if($item['def_base']) {
							$this->bonus_vila['dojo_limite_npc_dojo']	+= $item['def_base'];
						}

						if($item['prec_fisico']) {
							$this->bonus_vila['mapa_ryou']	+= $item['prec_fisico'];
						}

						if($item['prec_magico']) {
							$this->bonus_vila['mapa_exp']	+= $item['prec_magico'];
						}

						if($item['esq_min']) {
							$this->bonus_vila['mo_hp_npc_vila']	+= $item['esq_min'];
						}

						if($item['esq_max']) {
							$this->bonus_vila['mo_slot']	+= $item['esq_max'];
						}
						if($item['esq_total']) {
							$this->bonus_vila['mo_slot']	+= $item['esq_total'];
						}
						if($item['esq']) {
							$this->bonus_vila['mo_ninja_shop']	+= $item['esq'];
						}

						if($item['det']) {
							$this->bonus_vila['mo_guild_grad']	+= $item['det'];
						}

						if($item['conv']) {
							$this->bonus_vila['mo_sorte_preco']	+= $item['conv'];
						}

						if($item['conc']) {
							$this->bonus_vila['mo_sorte_preco_semanal']	+= $item['conc'];
						}

						if($item['req_con']) {
							$this->bonus_vila['ns_dano_curto']	+= $item['req_con'];
						}
					}
				// <---

				$_SESSION['healing_' . $this->id]	= [
					'hp'	=> $this->max_hp,
					'sp'	=> $this->max_sp,
					'sta'	=> $this->max_sta
				];

				$played	= Recordset::query('SELECT minutes FROM played_time WHERE id_player=' . $this->id);

				if(!$played->num_rows) {
					$this->played_minutes	= 0;
					$this->played_hours		= 0;

					Recordset::insert('played_time', ['id_player' => $this->id]);
				} else {
					$this->played_minutes	= $played->row()->minutes;
					$this->played_hours		= floor($this->played_minutes / 60);
				}
			}

			if ($this->id_profissao) {
				$profession	= Recordset::query('SELECT * FROM profissao WHERE id=' . $this->id_profissao)->row();
				$level		= Player::getFlag('profissao_nivel', $this->id);

				if ($level >= 1) {
					if ($profession->medico_passivo) {
						$this->bonus_vila['hospital_vida']		+= 10 + (5 * ($level - 1));
					}

					if ($profession->cozinheiro_passivo) {
						$this->bonus_profissao['ramen_cura']	+= 10 + (5 * ($level - 1));
					}

					if ($profession->ferreiro_passivo) {
						$this->bonus_profissao['ns_preco_curto']+= 10 + (5 * ($level - 1));
					}

					if ($profession->cacador_passivo) {
						$this->bonus_profissao['bb_recompensa']	+= 3 * $level;
					}

					if ($profession->instrutor_passivo) {
						$this->bonus_profissao['custo_treino']	+= 20 + (5 * ($level - 1));
					}

					if ($profession->aventureiro_passivo) {
						$this->bonus_vila['sk_missao_tempo']	+= 3 * $level;
					}
				}
			}
		}

		/**
		 * Define o item que será usado no ataque
		 *
		 * @param int $itemID
		 */
		function setATKItem($itemID) {
			$this->_atkItem = $itemID;
		}

		/**
		 * Enter description here...
		 *
		 * @param int $id id do item
		 * @return Item Classe com os dados do item
		 */
		function getATKItem($id = NULL) {
			//echo "GETTING - $id - {$this->_atkItem} - ";
			if(!$id) $id = $this->_atkItem;

			if(is_a($id, "stdClass") || is_a($id, "Item")) {
				return $id;
			}

			$i = new Item($id, $this->id);
			$i->setPlayerInstance($this);
			$i->parseLevel();

			return $i;
		}

		/**
		 * Pega todos os itens do personagem
		 *
		 * @param int, array, string $id_tipo id do tipo do item
		 * @return array Array com os itens do personagem
		 */
		function getItems($id_tipo = NULL, $group = false, $filter = NULL) {
			$tp = $id_tipo;

			$where = "";
			if($id_tipo) {
				//if(is_array($id_tipo)) $id_tipo = implode(",", $id_tipo);

				if(is_array($id_tipo)) {
					$where .= " AND id_tipo IN(" . join(',' , $id_tipo) . ")";
				} else {
					$where .= " AND id_tipo IN(" . $id_tipo . ")";
				}
			}

			$result = array();

			if(!$filter) {
				$tipos = array();

				if(!is_array($id_tipo) && $id_tipo) {
					$tipos = array($id_tipo);
				} elseif (is_array($id_tipo)) {
					$tipos = $id_tipo;
				}

				// AAAA
				foreach($this->_items as $k => $item) {
					if(sizeof($tipos)) {
						if(in_array($this->_ar_items_typei[$k], $tipos)) {
							$this->_check_and_instance_item($k);
							$result[$k] = $this->_items[$k];
						}
					} else {
						$this->_check_and_instance_item($k);
						$result[$k] = $this->_items[$k];
					}
				}
			} else {
				$items = $this->getItemView() . " b.turnos IS NULL AND removido='0' AND id_player=" . $this->id .
									  " $where $filter" . ($group ? " GROUP BY id" : "");

				$items = Recordset::query($items);

				foreach($items->result_array() as $item) {
					$this->_check_and_instance_item($item['id']);
					$result[$item['id']] = $this->_items[$item['id']]; //new Item($rItem['id'], $this->id);
				}
			}

			$add = false;

			if(is_array($tp) && on(6, $tp)) {
				$add = true;
			} elseif($tp == 6) {
				$add = true;
			}

			if($add) {
				$result['a'] = new Item(4, $this->id);
				$result['b'] = new Item(5, $this->id);
				$result['c'] = new Item(6, $this->id);
				$result['d'] = new Item(7, $this->id);
			}

			// Buff do guerra ninja -->
			if((is_array($tp) && on(41, $tp)) || $tp == 41) {
				$village	= Recordset::query('SELECT buff_guerra FROM vila WHERE id=' . $this->id_vila)->row();

				if ($village->buff_guerra) {
					if (in_array($this->id_vila, [1, 2, 3, 4, 5])) {
						$result['e']	= new Item(22950, $this->id);
					} else {
						$result['f']	= new Item(22951, $this->id);
					}
				}
			}
			// <--

			return $result;
		}

		function addItemW($item, $total = 1) {
			$item = Recordset::query('SELECT * FROM item WHERE id=' . (int)$item, true)->row_array();

			if(Recordset::query('SELECT id FROM player_item FORCE KEY(idx_player_item) WHERE id_item=' . $item['id'] . ' AND id_player=' . $this->id)->num_rows) {
				if(on($item['id_tipo'], array(1, 8, 9, 38))) {
					Recordset::query('UPDATE player_item SET qtd = qtd + ' . (int)$total . ' WHERE id_item=' . $item['id'] . ' AND id_player=' . $this->id);
				} else {
					Recordset::query('UPDATE player_item SET removido=\'0\' WHERE id_player=' . $this->id . ' AND id_item=' . $item['id']);
				}

				return false;
			} else {
				Recordset::query('INSERT INTO player_item(id_player, id_item, qtd) VALUES(' . $this->id . ', ' . $item['id'] . ', ' . (int)$total . ')');

				return true;
			}
		}

		/**
		 * Adiciona um item ao inventário do player - pm = 1 => ryou
		 *
		 * @param int $itemID
		 */
		function addItem($itemID, $amount = 1, $pm = 0, $_dbl = 1, $f_amount = false) {
			if($amount < 1) {
				die("Injection detected at: player.addItem." . __LINE__);
			}

			$item		= Recordset::query('SELECT * FROM item WHERE id=' . (int)$itemID, true)->row_array();
			$qItem		= Recordset::query('SELECT id FROM player_item FORCE KEY(idx_player_item) WHERE id_item=' . $item['id'] . ' AND id_player=' . $this->id);
			$is_insert	= false;

			if($qItem->num_rows) {
				if(on($item['id_tipo'], array(1, 2, 4, 9, 10, 11, 12, 13, 14, 15 , 29, 30, 31))) {
					Recordset::update('player_item', array(
						'qtd'		=> array('escape' => false, 'value' => 'qtd + ' . (int)$amount)
					), array(
						'id_item'	=> $item['id'],
						'id_player'	=> $this->id
					));
				} else {
					Recordset::update('player_item', array(
						'removido'	=> '0'
					), array(
						'id_item'	=> $item['id'],
						'id_player'	=> $this->id
					));
				}

				$rItem	= $qItem->row_array();
				$id		= $rItem['id'];
			} else {
				$id = Recordset::insert('player_item', array(
					'id_item'	=> $item['id'],
					'id_player'	=> $this->id,
					'qtd'		=> (int)$amount
				));

				$is_insert = true;
			}

			$tmpItem = new Item($item['id'], $this->id);

			if($tmpItem->id_tipo == 25) {
				$tmpItem->setAttribute('equipado', 1);
			} else {
				$tmpItem->setPlayerInstance($this);
			}

			if($f_amount) {
				$amount = $f_amount;
			}

			if($pm == 0 && $item['preco']) {
				$this->setAttribute('ryou', $this->getAttribute('ryou') - (($tmpItem->preco * $_dbl) * (int)$amount));
			} elseif($item['coin']) {
				Recordset::update('global.user', array(
					'coin'	=> array('escape' => false, 'value' => 'coin-' . (($item['coin'] * $_dbl) * (int)$amount) )
				), array(
					'id'	=> $_SESSION['usuario']['id']
				));
			}

			$this->__construct($this->id);

			return $is_insert;
		}

		/**
		 * Remove um item do inventário
		 *
		 * @param Item $item
		 */
		function removeItem(Item $item, $qtd = 1) {
			if(!$item->privateID) {
				die("ID Privado não encontrado!");
			}

			$i = $this->getItem($item->id);

			if($i->id_tipo == 5) { //  || $item->id_tipo == 4
				Recordset::update('player_item', array(
					'removido'	=> '1'
				), array(
					'id_player'	=> $this->id,
					'id_item'	=> $i->id
				));

				unset($this->_items[$item->id]);

				return true;
			} else {
				$i->setAttribute('qtd', $i->getAttribute('qtd') - $qtd);

				if($i->getAttribute('qtd') <= 0) {
					Recordset::query("DELETE FROM player_item WHERE id=" . $item->uid);

					unset($this->_items[$item->id]);

					return true;
				} else {
					return false;
				}
			}
		}

		function hasItemW ($id) {
			return isset($this->_items[$id]);
		}

		function hasItem($id, $time = NULL, $uso = NULL) {
			// itens padrão
			if(in_array($id, array(4, 5, 6, 7))) {
				return true;
			}

			// Buff do guerra ninja -->
				$village	= Recordset::query('SELECT buff_guerra FROM vila WHERE id=' . $this->id_vila)->row();

				if (in_array($id, [22950, 22951]) && $village->buff_guerra) {
					return true;
				}
			// <--

			// item vip + data_uso
			if($time) {
				$qItem = Recordset::query("
					SELECT id
					FROM coin_log FORCE KEY(idx_player_item_data)
					WHERE {$time[0]}(DATEDIFF(NOW(), data_uso)) >= {$time[1]} AND id_item IN(" . $id . ") AND id_player=" . $this->id);

				return $qItem->num_rows ? true : false;
			} else {
				if(is_array($id)) {
					$has = false;

					foreach($id as $i) {
						if(isset($this->_items[$i])) {
							$has = true;
						}
					}

					return $has;
				} else {
					if(!isset($this->_items[$id])) {
						return false;
					}
				}

				if($uso) {
					$this->_check_and_instance_item($id);
					return $this->_items[$id]->uso < $uso ? true : false;
				}

				return true;
			}
		}

		/*function getItem($id) {
			if(is_array($id)) {
				foreach($id as $item) {
					if(isset($this->_items[$item])) {

						$this->_check_and_instance_item($item);
						return $this->_items[$item];
					}
				}

				return false;
			} else {
				if(in_array($id, array(4, 5, 6, 7))) {
					return new Item($id, $this->id);
				} else {
					// Buff do guerra ninja -->
						$village	= Recordset::query('SELECT buff_guerra FROM vila WHERE id=' . $this->id_vila)->row();

						if (in_array($id, [22950, 22951]) && $village->buff_guerra) {
							return new Item($id, $this->id);
						}
					// <--

					$this->_check_and_instance_item($id);

					$has	= isset($this->_items[$id]) ? $this->_items[$id] : NULL;

					if(is_null($has)) {
						$item	= Recordset::query('SELECT id FROM player_item WHERE id_player=' . $this->id . ' AND id_item=' . (int)$id);

						if($item->num_rows) {
							return new Item($id, $this->id);
						} else {
							return NULL;
						}
					} else {
						return $has;
					}
				}
			}
		}*/
		function getItem($id, $ignore = NULL) {
			if($ignore){
				$item = new Item($id, $this->id, NULL, NULL, NULL, $this->id);
				if($item){
					return $item;
				}else{
					return NULL;	
				}
			}
			if(is_array($id)) {
				foreach($id as $item) {
					if(isset($this->_items[$item])) {
						$this->_check_and_instance_item($item);
						return $this->_items[$item];
					}
				}
				
				return false;
			} else {
				
				if(in_array($id, array(4, 5, 6, 7))) {
					return new Item($id, $this->id, NULL, NULL, NULL, $this->id);
				} else {
					// Buff do guerra ninja -->
						$village	= Recordset::query('SELECT buff_guerra FROM vila WHERE id=' . $this->id_vila)->row();

						if (in_array($id, [22950, 22951]) && $village->buff_guerra) {
							return new Item($id, $this->id);
						}
					// <--

					$this->_check_and_instance_item($id);

					$has	= isset($this->_items[$id]) ? $this->_items[$id] : NULL;
					
					if(is_null($has)) {
						$item	= Recordset::query('SELECT id FROM player_item WHERE id_player=' . $this->id . ' AND id_item=' . (int)$id);
						
						if($item->num_rows) {
							return new Item($id, $this->id);
						} else {
							return NULL;
						}
					} else {
					
						return $has;
					}
				}
			}		
		}

		function getVIPItem($id) {
			if (is_array($id)) {
				$id = join(",", $id);
			} else {
				$id = (int)$id;
			}

			$qItem = Recordset::query("
				SELECT SQL_NO_CACHE
					a.uso,
					a.id,
					a.id_item,
					b.turnos AS vezes
				FROM
					player_item a JOIN item b ON b.id=a.id_item

				WHERE
					a.id_item IN(" . $id . ") AND
					a.id_player={$this->id}

				ORDER BY b.ordem2 DESC
			");

			$rItem = $qItem->row_array();

			return $rItem;
		}

		function useVIPItem($a, $m = 0) {
			if($m == 0) {
				Recordset::update('player_item', array(
					'uso'	=> array('escape' => false, 'value' => 'uso+1')
				), array(
					'id'	=> (int)$a['id']
				));
			}
		}

		function consumeHP($value) {
			$value = (int)$value;

			if($value < 0) {
				if(($this->getAttribute('hp') + abs($value)) > $this->getAttribute('max_hp')) {
					$this->setAttribute('less_hp', 0);
					$this->setLocalAttribute('hp', $this->getAttribute('max_hp'));
				} else {
					$this->setAttribute('less_hp', $this->getAttribute('less_hp') - abs($value));
					$this->setLocalAttribute('hp', $this->getAttribute('hp') + abs($value));
				}
			} else {
				if(($this->getAttribute('hp') - $value) < 0) {
					$value = $this->getAttribute('hp');
				}

				$this->setAttribute('less_hp', $this->getAttribute('less_hp') + abs($value));
				$this->setLocalAttribute('hp', $this->getAttribute('hp') - $value);
			}
		}

		function consumeSP($value) {
			$value = (int)$value;

			if($value < 0) {
				if(($this->getAttribute('sp') + abs($value)) > $this->getAttribute('max_sp')) {
					$this->setAttribute('less_sp', 0);
					$this->setLocalAttribute('sp', $this->getAttribute('max_sp'));
				} else {
					$this->setAttribute('less_sp', $this->getAttribute('less_sp') - abs($value));
					$this->setLocalAttribute('sp', $this->getAttribute('sp') + $value);
				}
			} else {
				if(($this->getAttribute('sp') - $value) < 0) {
					$value = $this->getAttribute('sp');
				}

				$this->setAttribute('less_sp', $this->getAttribute('less_sp') + abs($value));
				$this->setLocalAttribute('sp', $this->getAttribute('sp') - $value);
			}
		}

		function consumeSTA($value) {
			$value = (int)$value;

			if($value < 0) {
				if(($this->getAttribute('sta') + abs($value)) > $this->getAttribute('max_sta')) {
					$this->setAttribute('less_sta', 0);
					$this->setLocalAttribute('sta', $this->getAttribute('max_sta'));
				} else {
					$this->setAttribute('less_sta', $this->getAttribute('less_sta') - abs($value));
					$this->setLocalAttribute('sta', $this->getAttribute('sta') + $value);
				}
			} else {
				if(($this->getAttribute('sta') - $value) < 0) {
					$value = $this->getAttribute('sta');
				}

				$this->setAttribute('less_sta', $this->getAttribute('less_sta') + abs($value));
				$this->setLocalAttribute('sta', $this->getAttribute('sta') - $value);
			}
		}

		public static function getNextLevel($level = NULL) {

			$r = new Recordset("SELECT exp FROM level_exp WHERE id=" . $level, true);
			$r = $r->row_array();

			return (int)$r['exp'];
		}

		function Win($loc = 0, $nc = false) {
			if($this->id_batalha_guild) {
				Recordset::query("UPDATE guild_batalha_player SET vencedor=1 WHERE id_player=" . $this->id . " AND id_guild_batalha=" . $this->id_batalha_guild);
			}

			$this->b_win = true;

			if(!$nc) {
				if(!$loc) {
					Recordset::query("UPDATE player SET id_batalha=NULL, vitorias = IFNULL(vitorias, 0) + 1 WHERE id= " . $this->id);
				} elseif($loc == 1) {
					Recordset::query("UPDATE player SET id_batalha=NULL, vitorias_f = IFNULL(vitorias_f, 0) + 1 WHERE id= " . $this->id);
				} else {
					Recordset::query("UPDATE player SET id_batalha=NULL, vitorias_d = IFNULL(vitorias_d, 0) + 1 WHERE id= " . $this->id);
				}
			}

			Recordset::query("UPDATE batalha SET vencedor=" . $this->id . " WHERE id=" . $this->id_batalha);

			$this->clearnFlag();
			$this->clearModifiers();

			SharedStore::S("_MODIFIERS_TURNS_PLAYER_" . $this->id, false);
			SharedStore::S("_MODIFIERS_ITEM_HEAL_PLAYER_" . $this->id, false);
		}

		function Loss($npc = false) {
			$this->clearnFlag();
			$this->clearModifiers();

			SharedStore::S("_MODIFIERS_TURNS_PLAYER_" . $this->id, false);
			SharedStore::S("_MODIFIERS_ITEM_HEAL_PLAYER_" . $this->id, false);

			if($this->id_batalha_guild) {
				Recordset::query("UPDATE guild_batalha_player SET perdedor=1 WHERE id_player=" . $this->id . " AND id_guild_batalha=" . $this->id_batalha_guild);
			}

			if($npc) {
				Recordset::query("UPDATE player SET id_batalha=NULL, derrotas_npc=derrotas_npc+1 WHERE id= " . $this->id);
			} else {
				Recordset::query("UPDATE player SET id_batalha=NULL, derrotas = IFNULL(derrotas, 0) + 1 WHERE id= " . $this->id);
			}

		}

		function Tied() {
			$this->clearnFlag();
			$this->clearModifiers();

			SharedStore::S("_MODIFIERS_TURNS_PLAYER_" . $this->id, false);
			SharedStore::S("_MODIFIERS_ITEM_HEAL_PLAYER_" . $this->id, false);

			Recordset::query("UPDATE player SET id_batalha=NULL, empates = IFNULL(empates, 0) + 1 WHERE id= " . $this->id);
		}

		function kai() {

		}

		public static function getPlayerView($id_classe_tipo = NULL) {
			return "
				SELECT SQL_NO_CACHE
				  a.id AS id,
				  a.id_usuario AS id_usuario,
				  a.id_sensei AS id_sensei,
				  a.id_vila AS id_vila,
				  a.id_classe AS id_classe,
				  a.id_batalha AS id_batalha,
				  a.id_batalha_multi AS id_batalha_multi,
				  a.id_vila_atual AS id_vila_atual,
				  a.exp_equipe_dia_total,

				  a.id_sala AS id_sala,
				  a.id_missao AS id_missao,
				  d.id AS id_graduacao,
				  a.id_titulo,
				  a.id_guild,

				  #h.nome AS nome_guild,
				  #(CASE WHEN h.id_player = a.id THEN 1 ELSE 0 END) AS dono_guild,

				  a.id_cla,
				  #i.nome AS nome_cla,

				  a.id_selo,
				  a.id_profissao,
				  #j.nome AS nome_selo,

				  a.id_invocacao,
				  #m.nome AS nome_invocacao,

				  (CASE WHEN a.id_vila=6 THEN
				  	d.nome_ak_" . Locale::get() . "
				   ELSE
				   	d.nome_" . Locale::get() . "
				   END
				  ) AS nome_graduacao,
				  b.nome AS nome_classe,
				  c.nome_" . Locale::get() . " AS nome_vila,
				  b.imagem AS imagem,
				  a.nome AS nome,
				  IFNULL(a.level,  0) AS level,
				  IFNULL(a.exp,  0) AS exp,
				  IFNULL(a.less_hp,  0) AS less_hp,
				  IFNULL(a.less_sp,  0) AS less_sp,
				  IFNULL(a.less_sta,  0) AS less_sta,
				  IFNULL(a.ryou,  0) AS ryou,
				  a.hospital AS hospital,
				  a.vitorias AS vitorias,
				  a.vitorias_f AS vitorias_f,
				  a.vitorias_d AS vitorias_d,
				  a.derrotas AS derrotas,
				  a.derrotas_f AS derrotas_f,
				  a.derrotas_npc,
				  a.empates AS empates,
				  a.empates_npc AS empates_npc,
				  a.id_classe_tipo,
				  a.id_cla,

				  z.coin,
				  z.pay_lock AS trava_pagto,
				  z.vip,
				  z.ip,

				  a.treino_dia,
				  a.treinando,
				  a.id_tipo_treino,
				  a.id_batalha_externa,
				  a.id_equipe,
				  a.id_evento,

				  #g.nome AS nome_equipe,
				  #(CASE WHEN g.id_player = a.id THEN 1 ELSE 0 END) AS dono_equipe,

				  k.posicao_vila AS vila_ranking,

				  a.dentro_vila,
				  a.tai AS  tai_p,
				  a.ken AS ken_p,
				  a.nin AS  nin_p,
				  a.gen AS  gen_p,
				  a.agi AS  agi_p,
				  a.con AS  con_p,
				  a.ene AS  ene_p,
				  a.inte AS int_p,
				  a.forc AS for_p,
				  a.res  AS res_p,
				  a.conc  AS conc_p,
				  a.esq  AS esq_p,
				  a.conv  AS conv_p,
				  a.det  AS det_p,
				  a.esquiva  AS esquiva_p,

				  0 AS agi_c,
				  0 AS con_c,
				  0 AS for_c,
				  0 AS ene_c,
				  0	AS int_c,
				  0 AS tai_c,
				  0 AS ken_c,
				  0 AS nin_c,
				  0 AS gen_c,
				  0 AS res_c,
				  0 AS conc_c,
				  0 AS esq_c,
				  0 AS conv_c,
				  0 AS det_c,
				  0 AS esquiva_c,
				  '' AS base_f,

				  a.treino_gasto,
				  a.treino_total,
				  a.fugas,
				  a.treino_tempo_jutsu,
				  a.id_tipo_treino_jutsu,
				  a.id_jutsu_treino,
				  a.arvore_gasto,
				  a.exp_equipe_dia,
				  a.exp_guild_dia,
				  a.exp_guild_dia_total,
				  a.banido,

				  #a.total_pt_tai_gasto,
				  #a.total_pt_nin_gasto,
				  #a.total_pt_gen_gasto,

				  #g.id_evento4,

				  a.id_missao_especial,
				  a.id_missao_guild,
				  a.vila_quest_vitorias,
				  a.id_sennin,
				  a.id_exame_chuunin,
				  a.exame_chuunin_etapa,
				  vitorias_exame,
				  derrotas_exame,
				  a.id_arena,
				  vitorias_arena,
				  derrotas_arena,
				  a.id_batalha_multi_pvp,
				  a.id_sala_multi_pvp,

					ponto_batalha,
					ponto_batalha_gasto,
					a.id_random_queue,
					a.id_random_queue_type,

					a.vitorias_rnd,
					a.derrotas_rnd,
					a.empates_rnd,
					a.ult_atividade,
					a.last_healed_at,
					a.credibilidade

				from
				  player a join classe b on b.id=a.id_classe
				  join vila c on c.id = a.id_vila
				  join graduacao d on d.id = a.id_graduacao
				  #JOIN classe_tipo f ON f.id=" . ($id_classe_tipo ? $id_classe_tipo : 'a.id_classe_tipo') . "
				  #LEFT JOIN equipe g ON g.id=a.id_equipe AND g.removido=0
				  #LEFT JOIN guild h ON h.id=a.id_guild AND h.removido='0'
				  LEFT JOIN ranking k ON k.id_player=a.id

				  #LEFT JOIN cla i ON i.id=a.id_cla
				  #LEFT JOIN selo j ON j.id=a.id_selo
				  #LEFT JOIN invocacao m ON m.id=a.id_invocacao

				  JOIN global.user z ON z.id=a.id_usuario
				WHERE ";
		}

		function getQuickPlayerView($id_classe_tipo) {
			return "
				SELECT
					0 AS id_usuario,
					0 AS tai_p,
					0 AS ken_p,
					0 AS nin_p,
					0 AS gen_p,
					0 AS agi_p,
					0 AS con_p,
					0 AS ene_p,
					0 AS int_p,
					0 AS for_p,
					0 AS res_p,
					0 AS conc_p,
					0 AS esq_p,
					0 AS conv_p,
					0 AS det_p,
					0 AS esquiva_p,
					(f.agi) AS agi_c,
					(f.con) AS con_c,
					(f.forc) AS for_c,
					(f.ene  + (3 * 1)) AS ene_c,
					(f.inte) AS int_c,
					(f.tai) AS tai_c,
					(f.ken) AS ken_c,
					(f.nin) AS nin_c,
					(f.gen) AS gen_c,
					(f.res) AS res_c,
					(f.conc) AS conc_c,
					(f.esq) AS esq_c,
					(f.conv) AS conv_c,
					(f.det) AS det_c,
					(f.esquiva) AS esquiva_c,
					0 AS less_hp,
					0 AS less_sp,
					0 AS less_sta,
					'' AS nome,
					'' AS nome_vila,
					0 AS id_vila,
					0 AS hospital,
					0 AS id_vila_atual,
					'' AS nome_classe,
					0 AS id_classe,
					0 as id_cla,
					'' AS nome_cla,
					0 AS id_selo,
					'' AS nome_selo,
					0 AS id_profissao,
					0 AS id_invocacao,
					'' AS nome_invocacao,
					'' AS imagem,
					1 AS level,
					0 AS `exp`,
					NULL as trava_pagto,
					0 AS treino_dia,
					0 AS vip,
					NULL as treinando,
					NULL as treino_tempo_jutsu,
					0 AS id_tipo_treino,
					0 AS id_tipo_treino_jutsu,
					0 AS id_titulo,
					0 AS id_evento,
					0 AS id_jutsu_treino,
					0 AS id_equipe,
					0 AS dono_equipe,
					0 AS sub_equipe,
					0 AS dono_guild,
					'' AS nome_equipe,
					'' AS nome_guild,
					0 AS id_evento4,
					0 AS id_batalha_externa,
					0 AS exp_equipe_dia,
					0 AS arvore_gasto,
					#0 AS total_pt_tai_gasto,
					#0 AS total_pt_nin_gasto,
					#0 AS total_pt_gen_gasto,
					0 AS exp_equipe_dia_total,
					0 AS dentro_vila,
					0 AS ip,
					0 AS id_graduacao,
					0 AS id_sensei,
					'' AS nome_graduacao,
					0 AS id_batalha,
					0 AS id_batalha_multi,
					0 AS id_sala,
					0 AS id_missao,
					0 AS vitorias,
					0 AS vitorias_f,
					0 AS vitorias_d,
					0 AS derrotas,
					0 AS derrotas_f,
					0 AS derrotas_npc,
					0 AS empates,
					0 AS empates_npc,
					0 AS fugas,
					0 AS vila_ranking,
					0 AS treino_total,
					0 AS treino_gasto,
					0 AS id_guild,
					0 AS id_missao_guild,
					0 AS ryou,
					0 AS coin,
					" . $id_classe_tipo . " AS id_classe_tipo,
					0 AS id_sennin,
					0 AS id_exame_chuunin,
					0 AS exame_chuunin_etapa,
					0 AS vitorias_exame,
					0 AS derrotas_exame,
					0 AS id_arena,
					0 AS vitorias_arena,
					0 AS derrotas_arena,
					0 AS id_missao_especial,
					0 AS id_batalha_multi_pvp,
					0 AS id_sala_multi_pvp,
					0 AS ponto_batalha,
					0 AS ponto_batalha_gasto,
					0 AS id_random_queue,
					0 AS vitorias_rnd,
					0 AS derrotas_rnd,
					0 AS empates_rnd,
					NULL as ult_atividade,
					NULL as last_healed_at,
					0 AS id_random_queue_type,
					0 AS credibilidade

				FROM
					classe_tipo f
				WHERE
					f.id=" . $id_classe_tipo;

		}

		function getItemView() {
			return "
				SELECT SQL_NO_CACHE
					a.id AS id,
					a.imagem,
					a.id_tipo AS id_tipo,
					a.id_cla AS id_cla,
					b.equipado AS equipado,
					b.id_player AS id_player,
					a.nome AS nome,
					a.preco AS preco,
					a.req_level AS req_level,
					b.turnos AS turnos,
					a.bonus_hp AS bonus_hp,
					a.bonus_sp AS bonus_sp,
					a.bonus_sta AS bonus_sta,
					a.consume_hp AS consume_hp,
					a.consume_sp AS consume_sp,
					a.consume_sta AS consume_sta,
					a.uso_unico AS uso_unico,
					a.vezes_dia AS vezes_dia,
					a.tempo_espera AS tempo_espera,
					a.ryou,
					a.bonus_treino,
					a.turnos,
					a.tai,
					a.ken,
					a.nin,
					a.gen,
					a.agi,
					a.con,
					a.inte,
					a.forc,
					a.ene,
					a.res,
					a.conc,
					a.esq,
					a.conv,
					a.det,
					a.tipo_bonus

				FROM
					item a JOIN player_item b ON b.id_item = a.id
					JOIN item_tipo c ON c.id = a.id_tipo
					LEFT JOIN habilidade d ON d.id = a.id_habilidade
					#JOIN player e on e.id = b.id_player

				WHERE
			";
		}

		function getElementos() {
			$tmp = StaticCache::get('player.getelementos.' . $this->id);

			if(!$tmp) {
				$elementos = Recordset::query("SELECT id_elemento FROM player_elemento WHERE id_player=" . $this->id);

				$tmp = array();
				foreach($elementos->result_array() as $elemento) {
					$tmp[] = $elemento['id_elemento'];
				}

				StaticCache::store('player.getelementos.' . $this->id, $tmp);
			}

			return $tmp;
		}

		function getElementosA() {
			$elementos = Recordset::query("
				SELECT
					   a.*
				FROM
					 elemento a JOIN player_elemento b ON a.id=b.id_elemento

				WHERE
					 id_player=" . $this->id);

			$tmp = array();
			foreach($elementos->result_array() as $elemento) {
				$tmp[] = $elemento;
			}

			return $tmp;
		}

		function clearnFlag() { }

		public static function getPtTotal($v) {
			if($v == 0) {
				return 0;
			} else {
				$levels		= Recordset::query('SELECT * FROM treino_exp', true);
				$last_level	= 0;

				foreach($levels->result_array() as $level) {
					if($level['ponto_total'] <= $v) {
						$last_level	= $level['id'];
					} else {
						break;
					}
				}

				return $last_level;
			}
		}

		function getPt($v) {
			if ($v === 0) {
				return 0;
			}

			$r = Recordset::query("SELECT ponto_total AS f FROM treino_exp WHERE id=" . (int)$v, true);
			$r = $r->row_array();

			return $r['f'];
		}

		function getPtM($v) {
			$r = new Recordset("SELECT ponto_exp AS f FROM treino_exp WHERE id=" . (int)$v, true);
			$r = $r->row_array();

			return $r['f'];
		}


		function getPlayerRemoved($id) {
			$player = Recordset::query('SELECT * FROM player WHERE id='. $id);
			return $player->row();
		}

		function player_jutsus_count_level() {
			$jutsus_l1	= 0;
			$jutsus_l2	= 0;
			$jutsus_l3	= 0;

			foreach($this->getItems(5) as $item) {
				$level	= 1;

				if($item->aprimoramento && sizeof($item->aprimoramento) > 0) {
					$level	= sizeof($item->aprimoramento);
				}

				switch($level) {
					case 1:
						$jutsus_l1++;
						break;

					case 2:
						$jutsus_l2++;

						break;

					case 3:
						$jutsus_l3++;

						break;
				}
			}

			$array = [
				"1" => $jutsus_l1,
				"2" => $jutsus_l2,
				"3" => $jutsus_l3
			];

			return $array;
		}

		function tutorial(){
			$tutorial = Recordset::query('SELECT * FROM player_tutorial WHERE id_player='. $this->id);
			return $tutorial->row();
		}
		function sensei($sensei){
			$sensei = Recordset::query('SELECT * FROM player_sensei WHERE id_sensei = '. $sensei .' AND id_usuario='. $this->id_usuario);
			return $sensei->num_rows;
		}

		function sensei_count($sensei){
			$sensei_count = Recordset::query('SELECT count(id) as total from player where id_sensei ='. $sensei);
			return $sensei_count->row();
		}

		public static function diplOf($vila, $vilab) {
			$is_war	= false;
			$guerra_ninja	= Recordset::query('SELECT * FROM guerra_ninja WHERE NOW() BETWEEN data_inicio AND data_fim AND finalizado=0');

			if ($guerra_ninja->num_rows) {
				$guerra	= $guerra_ninja->row();
				$is_war	= true;
			}

			if ($is_war && !in_array($vilab, [9,10,11,12])) {
				if ($guerra->akatsuki) {
					if (in_array($vila, [6, 7, 8]) && in_array($vilab, [6, 7, 8])) {
						return 1;
					} else {
						if (in_array($vila, [1, 2, 3, 4, 5]) && in_array($vilab, [1, 2, 3, 4, 5])) {
							return 1;
						} else {
							return 2;
						}
					}
				} else {
					if (in_array($vila, [1, 2, 3, 4, 5]) && in_array($vilab, [1, 2, 3, 4, 5])) {
						return 1;
					} else {
						if (in_array($vila, [6, 7, 8]) && in_array($vilab, [6, 7, 8])) {
							return 1;
						} else {
							return 2;
						}
					}
				}
			}

			if($vila == $vilab) {
				return 1;
			}

			if(Player::eventoGlobal() && $vila != 6 && $vilab != 6) {
				return 1;
			}

			
			if(between($vila, 9, 13) || between($vilab, 9, 13)) {
				return 1;
			}

			$dipl	= Recordset::query("SELECT dipl FROM diplomacia WHERE id_vila=" . $vila . " AND id_vilab=" . (int)$vilab);

			return $dipl->num_rows ? (int)$dipl->row()->dipl : 1;
		}

		public static function moveTo($p, $x, $y) {
			Recordset::update('player_posicao', array(
				'xpos'	=> (int)$x,
				'ypos'	=> (int)$y
			), array(
				'id_player'	=> $p
			));
		}

		public static function dentroLocal($p) {
            $r	= Recordset::query("SELECT id_vila, id_vila_atual FROM player WHERE id=" . (int)$p)->row_array();
            $qp = Recordset::query("SELECT xpos, ypos FROM player_posicao WHERE id_player=" . (int)$p);

            $rb	= $qp->row_array();

            if(!$qp->num_rows) {
                Recordset::query('INSERT INTO player_posicao(id_player) VALUES(' . $p . ')');
                $rb	= array('xpos' => 0, 'ypos' => 0);
            }

            $r = array_merge($r, $rb);
            $dipl = Player::diplOf($r['id_vila'], $r['id_vila_atual']);

            if($dipl !== 2) { // Exceto inimigos
                $qLocal = Recordset::query("SELECT id, mlocal, href, acao FROM local_mapa WHERE x=" . (int)$r['xpos'] . " AND y=" . (int)$r['ypos'] . " AND id_vila=" . (int)$r['id_vila_atual']);

                if($qLocal->num_rows) {
                    return $qLocal->row_array();
                }
            } else { // Se for portão da vila
                $q_local = Recordset::query("SELECT id, mlocal, href, acao FROM local_mapa WHERE x=" . (int)$r['xpos'] . " AND y=" . (int)$r['ypos'] . " AND id_vila=" . (int)$r['id_vila_atual']);

                if ($q_local->num_rows) {
                    $local = $q_local->row_array();

                    if($local['mlocal'] == 5) {
                        return $local;
                    } elseif($local['mlocal']) { // É inimigo e esta em um local onde tem npc
                        $q_npc = Recordset::query("SELECT id FROM npc_vila WHERE mlocal=" . $local['mlocal'] . " AND id_vila=" . $r['id_vila_atual']);

                        return array(
                            "enemy" => $local['mlocal'],
                            "mlocal" => $local['mlocal'],
                            "npc" => ($q_npc->num_rows ? $q_npc->row()->id : null)
                        );
                    }
                }
            }

            return false;
		}

		public static function moveLocal($p, $id, $vila = NULL) {
			if($vila) {
				$rb = Recordset::query("SELECT x, y FROM local_mapa WHERE id_vila=" . $vila . " AND mlocal=" . (int)$id, true)->row_array();
			} else {
				$rb = Recordset::query("SELECT x, y FROM local_mapa WHERE id=" . (int)$id, true)->row_array();
			}

			Player::moveTo($p, $rb['x'], $rb['y']);
		}

    public static function getFlag($flag_name, $id) {
      $q = Recordset::query("SELECT $flag_name FROM player_flags WHERE id_player=" . $id);

      if (!$q->num_rows) {
        Recordset::insert('player_flags', array(
          'id_player'  => $id
        ));

        $q->repeat();
      }

      return $q->row()->$flag_name;
    }

    function setFlag($f, $v) {
      if(Recordset::query("SELECT id FROM player_flags WHERE id_player=" . $this->id)->num_rows) {
        Recordset::query("UPDATE player_flags SET $f='" . addslashes($v) . "' WHERE id_player=" . $this->id);
      } else {
        Recordset::query("INSERT INTO player_flags(id_player, $f) VALUES({$this->id}, $v)");
      }
    }

    function hasMissaoDiariaPlayer($tipo_missao){
			$playerMissao	= Recordset::query('select count(1) total from player_missao_diarias WHERE id_missao_diaria in (select id from missoes_diarias WHERE tipo='.$tipo_missao.') AND completo=0 AND id_player='. $this->id)->row();
			return $playerMissao;
		}
		function getUFlag($key, $default = NULL) {
			$q = new Recordset('SELECT * FROM global.user_flags WHERE id_user=' . $_SESSION['usuario']['id'] . ' AND flag=\'' . addslashes($key) . '\'');

			if($q->num_rows) {
				return $q->row()->value;
			} else {
				if(is_null($default)) {
					$default = 'NULL';
				} else {
					$default = '\'' . addslashes($default) . '\'';
				}

				Recordset::query('INSERT INTO global.user_flags(id_user, flag, value) VALUES(
					' . $_SESSION['usuario']['id'] . ', \'' . addslashes($key) . '\', ' . $default . '
				)');

				return $default;
			}
		}

		function setUFlag($key, $value) {
			$q = new Recordset('SELECT * FROM global.user_flags WHERE id_user=' . $_SESSION['usuario']['id'] . ' AND flag=\'' . addslashes($key) . '\'');

			if(is_null($value)) {
				$value = 'NULL';
			} else {
				$value = '\'' . $value . '\'';
			}

			if($q->num_rows) {
				Recordset::query('UPDATE global.user_flags SET value=' . $value . ' WHERE id_user=' . $_SESSION['usuario']['id'] . ' AND flag=\'' . addslashes($key) . '\'');
			} else {
				Recordset::query('INSERT INTO global.user_flags(id_user, flag, value) VALUES(
					' . $_SESSION['usuario']['id'] . ', \'' . addslashes($key) . '\', ' . $value . '
				)');
			}
		}

		function in_item_array($id) {
			foreach($this->arItems as $k => $item) {
				if($k == $id) {
					return true;
				}
			}

			return false;
		}

		function rebuildItems() {
			$this->_ar_items_types	= array();
			$this->_ar_items_typesb	= array();
			$this->_ar_items_typei	= array();
			$this->_ar_items_mine	= array();
			$this->_ar_items_mineb	= array();
			$this->_ar_items		= array();
			$this->_items			= array();

			// nova implementação --->
				$ar_mine_items		= Recordset::query("SELECT * FROM player_item a WHERE a.removido='0' AND a.id_player=" . $this->id);

				foreach($ar_mine_items->result_array() as $mine_item) {
					if(!isset($this->_ar_items_types[$mine_item['id_item_tipo']])) {
						
						$this->_ar_items_types[$mine_item['id_item_tipo']] = array();
					}

					$this->_ar_items_types[$mine_item['id_item_tipo']][]	= $mine_item['id'];
					$this->_ar_items_typesb[$mine_item['id_item_tipo']][]	= $mine_item['id_item'];
					$this->_ar_items_typei[$mine_item['id_item']]			= $mine_item['id_item_tipo'];
					$this->_ar_items_mine[$mine_item['id_item']]			= $mine_item;
					$this->_ar_items_mineb[$mine_item['id']]					= $mine_item;
					$this->_ar_items[$mine_item['id_item']]					= true;
					$this->_items[$mine_item['id_item']]					= true;
					
				}
			// <---
		}


		function getAttribute($k) {
			if($k == 'ryou') {
				return Recordset::query('SELECT `ryou` FROM player WHERE id=' . $this->id)->row()->ryou;
			}

			if($k == 'exp') {
				return Recordset::query('SELECT `exp` FROM player WHERE id=' . $this->id)->row()->exp;
			}

			if ($k === "hp") {

			}

			if(!isset($this->atl_keys[$k])) {
				if(!isset($this->at_keys[$k])) {
					if(!isset($this->ats_keys[$k])) {
						throw new Exception('Player Attribute "' . $k . '" not found');
					} else {
						return $this->ats[$k];
					}
				} else {
					return $this->at[$k];
				}
			} else {
				return $this->atl[$k];
			}
		}

		function setAttribute($k, $v, $no_update = false) {
			if(!isset($this->at_keys[$k])) {
				throw new Exception('Player Attribute "' . $k . '" not found');
			}

			$this->at[$k]	= $v;

			if(isset($_SESSION['universal']) && $_SESSION['universal']) {
				$this->$k		= $v;
			}

			if(!$no_update) {
				Recordset::update('player', array(
					$k 		=> $v
				), array(
					'id'	=> $this->id
				));
			}
		}

		function setLocalAttribute($k, $v) {
			if(!isset($this->atl_keys[$k])) {
				$this->atl_keys[$k] = 1;
			}

			$this->atl[$k]	= $v;
			$this->$k		= $v;
		}

		function playerRemoved($id){
			$playerRemoved	= Recordset::query('SELECT removido,banido, ult_atividade FROM player WHERE id=' . $id)->row();
			return $playerRemoved;
		}

		function hasAttribute($k) {
			if(isset($this->atl_keys[$k]) || isset($this->at_keys[$k]) || isset($this->ats_keys[$k])) {
				return true;
			} else {
				return false;
			}

		}

		private function do_key_mapping() {
			foreach($this->ats as $k => $v) {
				$this->ats_keys[$k] = 1;
			}

			foreach($this->at as $k => $v) {
				$this->at_keys[$k] = 1;
			}

			foreach($this->atl as $k => $v) {
				$this->atl_keys[$k] = 1;
			}
		}

		public static function setInstance(&$o) {
			Player::$_instance =& $o;
		}

		public static function getInstance() {
			return Player::$_instance;
		}

		function resetJutsu() {
			$key	= $this->id . '_jutsus';

			$_SESSION[$key]	= NULL;
		}

		function getJutsu($id = NULL, $only_check = false) {
			$key	= $this->id . '_jutsus';

			if(!(isset($_SESSION[$key]) && $_SESSION[$key])) {
				$_SESSION[$key]	= array();

				$jutsus	= Recordset::query('SELECT * FROM player_item_jutsu WHERE removido=\'0\' AND id_player=' . $this->id);

				foreach($jutsus->result_array() as $k => $v) {
					$_SESSION[$key][$v['id_item']]	= 1;
				}
			}

			if($id) {
				if($id == 4 || $id == 5 || $id == 6 || $id == 7) {
					return $only_check ? true : new Item($id);
				}

				if(!isset($_SESSION[$key][$id])) {
					return false;
				} else {
					$data	= $_SESSION[$key][$id];

					if(!$only_check) {
						if(is_numeric($data)) {
							$object					= new Item($id, $this->id);
							$_SESSION[$key][$id]	= gzserialize($data);

							return $object;
						} else {
							return gzunserialize($data);
						}
					} else {
						return true;
					}
				}
			} else {
				$data	= array();

				foreach($_SESSION[$key] as $k => $v) {
					if(is_numeric($v)) {
						$object				= new Item($k, $this->id);
						$_SESSION[$key][$k]	= gzserialize($object);

						$data[$k]			= $object;
					} else {
						$data[$k]			= gzunserialize($v);
					}
				}

				return $data;
			}
		}

		function addJutsu($id) {
			$key	= $this->id . '_jutsus';

			if(!is_numeric($id)) {
				$id	= $id->id;
			}

			$_SESSION[$key][$id]	= 1;

			if(Recordset::query('SELECT id_player FROM player_item_jutsu WHERE id_player=' . $this->id . ' AND id_item=' . $id)->num_rows) {
				Recordset::update('player_item_jutsu', array(
					'removido'	=> '0'
				), array(
					'id_player'	=> $this->id,
					'id_item'	=> $id
				));
			} else {
				Recordset::insert('player_item_jutsu', array(
					'id_player'	=> $this->id,
					'id_item'	=> $id
				));

				return true;
			}
		}

		function removeJutsu($id) {
			$key	= $this->id . '_jutsus';

			if(!is_numeric($id)) {
				$id	= $id->id;
			}

			Recordset::update('player_item_jutsu', array(
				'removido'	=> '1'
			), array(
				'id_player'	=> $this->id,
				'id_item'	=> $id
			));

			unset($_SESSION[$key][$id]);
		}
		
		function generate_equipment($player, $rarity_fragment = NULL, $slot = NULL) {

			$rarities	= [
				'comum',
				'raro',
				'epico',
				'lendario'
			];

			$slots	= [
				'head',
				'shoulder',
				'chest',
				'neck',
				'hand',
				'leggings'
			];
			$slots_quantity	= [
				'head'			=> [10],
				'shoulder'		=> [15],
				'chest'			=> [11],
				'neck'			=> [29],
				'hand'			=> [13],
				'leggings'		=> [14]
				
			];
			$slots_id_tipo	= [
				'head'			=> [236],
				'shoulder'		=> [309],
				'chest'			=> [305],
				'neck'			=> [306],
				'hand'			=> [307],
				'leggings'		=> [308]
				
			];
			$slots_imagem	= [
				'head'			=> [1],
				'shoulder'		=> [10],
				'chest'			=> [10],
				'neck'			=> [2],
				'hand'			=> [10],
				'leggings'		=> [10]
				
			];

			$attributes_by_slot	= [
				'head'		=> [],
				'shoulder'	=> [],
				'chest'		=> [],
				'neck'		=> [],
				'hand'		=> [],
				'leggings'	=> []
			];

			$ignore_sums	= ['cooldown_reduction', 'for_stamina', 'npc_battle_count'];

			$choosen_slot	= $slots[$slot ? $slot-1 : rand(0, sizeof($slots) - 1)];

			$rarity_drop_by_graduation	= [
				1	=> [
					'comum'	=> 94,
					'raro'		=> 5,
					'epico'		=> 1,
					'lendario'	=> 0
				],
				2	=> [
					'comum'	=> 84,
					'raro'		=> 10,
					'epico'		=> 5,
					'lendario'	=> 1
				],
				3	=> [
					'comum'	=> 60,
					'raro'		=> 20,
					'epico'		=> 10,
					'lendario'	=> 5
				],
				4	=> [
					'comum'	=> 50,
					'raro'		=> 40,
					'epico'		=> 15,
					'lendario'	=> 10
				],
				5	=> [
					'comum'	=> 35,
					'raro'		=> 50,
					'epico'		=> 25,
					'lendario'	=> 15
				],
				6	=> [
					'comum'	=> 20,
					'raro'		=> 60,
					'epico'		=> 30,
					'lendario'	=> 20
				],
				7	=> [
					'comum'	=> 20,
					'raro'		=> 60,
					'epico'		=> 30,
					'lendario'	=> 20
				]
			];

			$bonuses_by_rarity	= [
				'comum'		=> [1],
				'raro'		=> [2],
				'epico'		=> [3],
				'lendario'	=> [4]
			];

			$additional_by_graduation	= [
				'comum'		=> 1,
				'raro'		=> 1,
				'epico'		=> 1,
				'lendario'	=> 1
			];

			$additional_chance_by_graduation	= [
				[15, 35, 50, 5, 1],
				[15, 30, 45, 10, 1],
				[20, 25, 40, 15, 1],
				[20, 20, 35, 20, 1],
				[25, 15, 30, 25, 1],
				[25, 10, 25, 30, 1],
				[30, 10, 25, 30, 1]
			];

			$choosables	= [
				'cooldown_reduction'		=> 'cooldown_reduction_id',
				'technique_attack_increase'	=> 'technique_attack_increase_id',
				'technique_mana_reduction'	=> 'technique_mana_reduction_id',
				'technique_crit_increase'	=> 'technique_crit_increase_id',
				'technique_zero_mana'		=> 'technique_zero_mana_id'
			];

			$bases	= [
				 [
					[
						'tai'							=> [1, 2],
						'ken'							=> [1, 2],
						'nin'							=> [1, 2],
						'gen'							=> [1, 2],
						'forc'							=> [1, 2],
						'inte'							=> [1, 2],
						'agi'							=> [1, 2],
						'con'							=> [1, 2],
						'res'							=> [1, 2],
						'ene'							=> [1, 1],
						'atk_fisico'					=> [1, 1],
						'def_fisico'					=> [1, 1],
						'atk_magico'					=> [1, 1],
						'def_magico'					=> [1, 1],
						//'prec_fisico'					=> [1, 2],
						'prec_magico'					=> [1, 2],
						'conc'							=> [1, 2],
						'crit_total'					=> [1, 2],
						'esq'							=> [1, 2],
						'esq_total'						=> [1, 2],
						'conv'							=> [1, 2],
						'det'							=> [1, 2],
						'esquiva'						=> [1, 1],
						'bonus_hp'						=> [1, 2],
						'bonus_sp'						=> [1, 2],
						'bonus_sta'						=> [1, 2]
					], 					[
						'tai'							=> [1, 2],
						'ken'							=> [1, 2],
						'nin'							=> [1, 2],
						'gen'							=> [1, 2],
						'forc'							=> [1, 2],
						'inte'							=> [1, 2],
						'agi'							=> [1, 2],
						'con'							=> [1, 2],
						'res'							=> [1, 2],
						'ene'							=> [1, 1],
						'atk_fisico'					=> [1, 1],
						'def_fisico'					=> [1, 1],
						'atk_magico'					=> [1, 1],
						'def_magico'					=> [1, 1],
						//'prec_fisico'					=> [1, 2],
						'prec_magico'					=> [1, 2],
						'conc'							=> [1, 2],
						'crit_total'					=> [1, 2],
						'esq'							=> [1, 2],
						'esq_total'						=> [1, 2],
						'conv'							=> [1, 2],
						'det'							=> [1, 2],
						'esquiva'						=> [1, 1],
						'bonus_hp'						=> [1, 2],
						'bonus_sp'						=> [1, 2],
						'bonus_sta'						=> [1, 2]
					], [
						'tai'							=> [1, 3],
						'ken'							=> [1, 3],
						'nin'							=> [1, 3],
						'gen'							=> [1, 3],
						'forc'							=> [1, 3],
						'inte'							=> [1, 3],
						'agi'							=> [1, 3],
						'con'							=> [1, 3],
						'res'							=> [1, 3],
						'ene'							=> [1, 2],
						'atk_fisico'					=> [1, 2],
						'def_fisico'					=> [1, 2],
						'atk_magico'					=> [1, 2],
						'def_magico'					=> [1, 2],
						//'prec_fisico'					=> [1, 3],
						'prec_magico'					=> [1, 3],
						'conc'							=> [1, 3],
						'crit_total'					=> [1, 3],
						'esq'							=> [1, 3],
						'esq_total'						=> [1, 3],
						'conv'							=> [1, 3],
						'det'							=> [1, 3],
						'esquiva'						=> [1, 2],
						'bonus_hp'						=> [1, 3],
						'bonus_sp'						=> [1, 3],
						'bonus_sta'						=> [1, 3]
					], [
						'tai'							=> [1, 4],
						'ken'							=> [1, 4],
						'nin'							=> [1, 4],
						'gen'							=> [1, 4],
						'forc'							=> [1, 4],
						'inte'							=> [1, 4],
						'agi'							=> [1, 4],
						'con'							=> [1, 4],
						'res'							=> [1, 4],
						'ene'							=> [1, 2],
						'atk_fisico'					=> [1, 2],
						'def_fisico'					=> [1, 2],
						'atk_magico'					=> [1, 2],
						'def_magico'					=> [1, 2],
						//'prec_fisico'					=> [1, 4],
						'prec_magico'					=> [1, 4],
						'conc'							=> [1, 4],
						'crit_total'					=> [1, 4],
						'esq'							=> [1, 4],
						'esq_total'						=> [1, 4],
						'conv'							=> [1, 4],
						'det'							=> [1, 4],
						'esquiva'						=> [1, 2],
						'bonus_hp'						=> [1, 4],
						'bonus_sp'						=> [1, 4],
						'bonus_sta'						=> [1, 4]
					], [
						'tai'							=> [1, 5],
						'ken'							=> [1, 5],
						'nin'							=> [1, 5],
						'gen'							=> [1, 5],
						'forc'							=> [1, 5],
						'inte'							=> [1, 5],
						'agi'							=> [1, 5],
						'con'							=> [1, 5],
						'res'							=> [1, 5],
						'ene'							=> [1, 3],
						'atk_fisico'					=> [1, 3],
						'def_fisico'					=> [1, 3],
						'atk_magico'					=> [1, 3],
						'def_magico'					=> [1, 3],
						//'prec_fisico'					=> [1, 5],
						'prec_magico'					=> [1, 5],
						'conc'							=> [1, 5],
						'crit_total'					=> [1, 5],
						'esq'							=> [1, 5],
						'esq_total'						=> [1, 5],
						'conv'							=> [1, 5],
						'det'							=> [1, 5],
						'esquiva'						=> [1, 3],
						'bonus_hp'						=> [1, 5],
						'bonus_sp'						=> [1, 5],
						'bonus_sta'						=> [1, 5]
					], [
						'tai'							=> [1, 6],
						'ken'							=> [1, 6],
						'nin'							=> [1, 6],
						'gen'							=> [1, 6],
						'forc'							=> [1, 6],
						'inte'							=> [1, 6],
						'agi'							=> [1, 6],
						'con'							=> [1, 6],
						'res'							=> [1, 6],
						'ene'							=> [1, 6],
						'atk_fisico'					=> [1, 3],
						'def_fisico'					=> [1, 3],
						'atk_magico'					=> [1, 3],
						'def_magico'					=> [1, 3],
						//'prec_fisico'					=> [1, 6],
						'prec_magico'					=> [1, 6],
						'conc'							=> [1, 6],
						'crit_total'					=> [1, 6],
						'esq'							=> [1, 6],
						'esq_total'						=> [1, 6],
						'conv'							=> [1, 6],
						'det'							=> [1, 6],
						'esquiva'						=> [1, 3],
						'bonus_hp'						=> [1, 6],
						'bonus_sp'						=> [1, 6],
						'bonus_sta'						=> [1, 6]
					], [
						'tai'							=> [1, 7],
						'ken'							=> [1, 7],
						'nin'							=> [1, 7],
						'gen'							=> [1, 7],
						'forc'							=> [1, 7],
						'inte'							=> [1, 7],
						'agi'							=> [1, 7],
						'con'							=> [1, 7],
						'res'							=> [1, 7],
						'ene'							=> [1, 4],
						'atk_fisico'					=> [1, 4],
						'def_fisico'					=> [1, 4],
						'atk_magico'					=> [1, 4],
						'def_magico'					=> [1, 4],
						//'prec_fisico'					=> [1, 7],
						'prec_magico'					=> [1, 7],
						'conc'							=> [1, 7],
						'crit_total'					=> [1, 7],
						'esq'							=> [1, 7],
						'esq_total'						=> [1, 7],
						'conv'							=> [1, 7],
						'det'							=> [1, 7],
						'esquiva'						=> [1, 4],
						'bonus_hp'						=> [1, 7],
						'bonus_sp'						=> [1, 7],
						'bonus_sta'						=> [1, 7]
					]
				]
			];

			$values					= [];
			$current_grad			= $this->id_graduacao;

			$rarity_base			= $rarity_drop_by_graduation[$current_grad];
			$rarity_choosen_name	= '';
			$have_extras			= false;
			
			if(is_null($rarity_fragment)){
				while(true) {
					$rarity_choosen_id	= 0;
	
					foreach ($rarity_base as $rarity => $chance) {
						if(rand(1, 100) <= $chance) {
							$rarity_choosen_name	= $rarity;
							
							break 2;
						}
	
						$rarity_choosen_id++;
					}
				}
			}else{
				switch($rarity_fragment){
					case 0:
						$rarity_choosen_name = "comum";
						$rarity_choosen_id = 0;
					break;
					case 1:
						$rarity_choosen_name = "raro";
						$rarity_choosen_id = 1;
					break;
					case 2:
						$rarity_choosen_name = "epico";
						$rarity_choosen_id = 2;
					break;
					case 3:
						$rarity_choosen_name = "lendario";
						$rarity_choosen_id = 3;
					break;
				}	
			}
			

			foreach ($bases as $block => $base) {

				$attribute_counter	= $bonuses_by_rarity[$rarity_choosen_name][$block];
				$choosen_attributes	= $base[$current_grad - 1];
				$extras				= $additional_chance_by_graduation[$current_grad - 1];
				$extra_chance		= $extras[$block];

				if(rand(1, 100) <= $extra_chance && !$have_extras) {
					$attribute_counter	+= $extras[4];
					$have_extras		= true;
				}

				if ($attribute_counter) {
					while(true) {
						foreach ($base[$current_grad - 1] as $attribute => $value) {
							
							if(in_array($attribute, $attributes_by_slot[$choosen_slot])) {
								continue;
							}

							if(isset($values[$attribute])) {
								continue;
							}

							if(rand(1, 100) > 10) {
								continue;
							}

							if(!in_array($attribute, $ignore_sums)) {
								/*if($rarity_choosen_id == 2) {
									$value[0]++;
								}

								if($rarity_choosen_id == 3 || $rarity_choosen_id == 4) {
									$value[0]	+= 2;
								}*/

								//$values[$attribute]	= rand($value[0], $value[1] + (($rarity_choosen_id) * 2));
								//$values[$attribute]	= rand($value[0], $value[1]);
								$values[$attribute]	= rand($value[0]*10, $value[1]*10)/10;
							} else {
								//$values[$attribute]	= rand($value[0], $value[1]);
								$values[$attribute]	= rand($value[0]*10, $value[1]*10)/10;
							}

							if (isset($choosables[$attribute])) {
								$items_query	= Recordset::query('SELECT id FROM items WHERE item_type_id=1 AND id NOT IN(112, 113)', true)->result_array();

								//$values[$attribute]					= 1;
								$values[$choosables[$attribute]]	= $items_query[rand(0, sizeof($items_query) - 1)]['id'];
							}

							$attribute_counter--;

							if(!$attribute_counter) {
								break 2;
							}
						}
					}
				}
			}
			// Adiciona o Item novo na Player Item.
			$id = Recordset::insert('player_item', array(
				'id_player'		=> $this->id,
				'id_item'		=> $slots_id_tipo[$choosen_slot][0],
				'id_item_tipo' 	=> $slots_quantity[$choosen_slot][0],
				'qtd'			=> 1,
				'raridade'		=> $rarity_choosen_name
			));
			//$player_item->slot_name	= $slot ? $slot : $choosen_slot;
			
			Recordset::insert('player_item_atributos', array(
				'id_player_item'	=> $id,
				'req_graduacao'		=> $this->id_graduacao,
				'nome'				=> 	t('equipamentos.1.' . $slots_quantity[$choosen_slot][0]) .' '. ($slots_quantity[$choosen_slot][0] != 11 && $slots_quantity[$choosen_slot][0] != 29 ? t('equipamentos.raridadeb.'.$rarity_choosen_name) : t('equipamentos.raridade.'.$rarity_choosen_name)),
				'imagem'			=> rand(1,$slots_imagem[$choosen_slot][0]),
				'have_extra' 		=> $have_extras ? 1 : 0
			));
			foreach ($values as $property => $value) {
				Recordset::update('player_item_atributos', array(
					$property					=> $value
				), array(
					'id_player_item'			=> $id
				));	
			}
			if($rarity_choosen_name == 'lendario'){
				global_message("O Player <span>".$player->nome."</span> dropou um equipamento <b class='equip_lendario'>LENDÁRIO</b>");
			}

			//return $player_item;
		}
		function add_classe($id_classe = NULL){
			if($this->classe_liberada($id_classe)){
				$classe		= Recordset::query("SELECT id FROM player_classe WHERE id_usuario = ".$this->id_usuario." AND id_player= ".$this->id." AND id_classe=". $id_classe)->row();
				
				return $classe->id;
				/*$classe		= Recordset::query("SELECT estrela FROM classe WHERE id=". $id_classe)->row();
				$fragmento  = Recordset::query("SELECT recrutar FROM estrelas WHERE id=". $classe->estrela)->row();
				 
				$player_item = Recordset::query("SELECT * FROM player_item WHERE id_player=". $this->id." AND id_classe=". $id_classe);
				if($player_item->num_rows){
					Recordset::update('player_item', array(
						'qtd'		=> array('escape' => false, 'value' => 'qtd + ' . ceil($fragmento->recrutar / 3))
					), array(
						'id_classe'	=> $id_classe,
						'id_player'	=> $this->id
					));
					
					return $player_item->row()->id;
				}else{
					
					$insert = Recordset::insert('player_item', array(
						'id_player'		=> $this->id,
						'id_item_tipo'  => 44,
						'id_item'  		=> 264,
						'id_classe'		=> $id_classe,
						'qtd'			=> ceil($fragmento->recrutar / 3)
					));
					
					return $insert;
				}*/
			}else{
				$insert = Recordset::insert('player_classe', array(
					'id_usuario'	=> $this->id_usuario,
					'id_player'		=> $this->id,
					'id_classe'		=> $id_classe
				));
				
				return $insert;
			}
		}

		public static function classe_liberada($id_classe){
			$liberado = Recordset::query("SELECT * FROM player_classe WHERE id_usuario =". $_SESSION['usuario']['id'] . " AND id_classe = ". $id_classe)->num_rows;
			return $liberado;	
		}

		function packs_limited($id_pack){
			$packs = Recordset::query("SELECT * FROM packs_log WHERE id_usuario =". $this->id_usuario."  AND id_pack=". $id_pack)->num_rows;
			return $packs;
		}
		
		public static function eventoGlobal() {
			$evento = Recordset::query('
						SELECT
							id
						FROM
							evento
						WHERE
							NOW() >= dt_inicio AND
							NOW() <= dt_fim AND global=1 AND
							ativo=1
					');

			return $evento->num_rows ? $evento->row()->id : false;
		}

		function playerBanido($id_player) {
			$player_banido = Recordset::query('
						SELECT
							*
						FROM
							player_banido
						WHERE
							id_player = '. $id_player.' ORDER BY id DESC LIMIT 1');

			return $player_banido->row_array();
		}

		// Novas funções para gerenciamento ods itens
		private function _check_and_instance_item($item) {
			if(isset($this->_items[$item])) {
				// tem o item, mas não foi instanciado
				if($this->_items[$item] === true) {
					$this->_items[$item] = new Item($item, $this->id);
				}
			}
		}
	}
