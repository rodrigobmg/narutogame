<?php
	class Item {
		// new --->

		private	$at		= array();
		private	$ats	= array();
		private	$atl	= array(
			'precisao'	=> 0
		);

		public	$id			= 0;
		public	$uid		= 0;
		public	$id_tipo	= 0;
		public	$id_grupo	= 0;
		public	$skipping	= false;
		public	$elemento	= false;
		public	$flight		= false;

		public	$at_keys	= NULL;
		public	$ats_keys	= NULL;
		public	$atl_keys	= NULL;

		private static	$element_data	= array();
		private static	$element_total	= array();
		private static	$classe_tipo	= array();
		private	static	$key_maps		= NULL;

		private $modifiers		= array();
		private $raw_modifiers	= array();
		private	$is_modifiers_patched	= false;
		// <---

		// CX --->
			public $tai			= 0;
			public $ken			= 0;
			public $nin			= 0;
			public $gen			= 0;
			public $agi			= 0;
			public $ene			= 0;
			public $con			= 0;
			public $int			= 0;
			public $for			= 0;
			public $res			= 0;
			public $esq			= 0;
			public $det			= 0;
			public $conv		= 0;
			public $esquiva		= 0;
			public $conc		= 0;

			public $atk_fisico	= 0;
			public $atk_magico	= 0;
			public $prec_fisico	= 0;
			public $prec_magico	= 0;
			public $crit_min	= 0;
			public $crit_max	= 0;
			public $crit_total	= 0;
			public $esq_min		= 0;
			public $esq_max		= 0;
			public $esq_total	= 0;

			public $def_base	= 0;
			public $def_fisico	= 0;
			public $def_magico	= 0;

			public $sp_consume	= 0;
			public $hp_consume	= 0;
			public $sta_consume	= 0;

			public $bonus_hp	= 0;
			public $bonus_sp	= 0;
			public $bonus_sta	= 0;

			public $req_tai		= 0;
			public $req_ken		= 0;
			public $req_nin		= 0;
			public $req_gen		= 0;
			public $req_for		= 0;
			public $req_int		= 0;
			public $req_con		= 0;
			public $req_ene		= 0;
			public $req_res		= 0;
			public $req_agi		= 0;

			public $req_graduacao	= 0;
			public $req_level		= 0;
			public $req_battle		= 0;
			public $req_sensei_battle		= 0;
			public $req_portao		= 0;
			public $preco			= 0;
			public $coin			= 0;
			public $id_reputacao	= 0;
			public $id_vila_reputacao	= 0;
			public $req_no_selo		= 0;
			public $req_arvore_gasto	= 0;
			public $req_no_cla		= 0;
			public $req_no_portao	= 0;
			public $req_no_sennin	= 0;
			public $req_no_item		= 0;

			public $level			= 0;
			public $level_f			= 0;
			public $qtd				= 0;
			public $uso				= 0;
			public $level_liberado	= 0;
			public $exp				= 0;
			public $total			= 0;
			public $equipado		= 0;
			public $ativo			= 0;
			public $removido		= 0;
			public $evolui			= 0;

			public $req_item		= 0;
			public $id_habilidade	= 0;
			public $id_elemento		= 0;
			public $id_elemento2	= 0;
			public $id_cla			= 0;
			public $id_selo			= 0;
			public $id_invocacao	= 0;
			public $sem_turno		= 0;
			public $defensivo		= 0;
			public $ordem			= 0;

			public $bonus_treino	= 0;
			public $tipo_bonus		= 0;
			public $turnos			= 0;
			public $cooldown		= 0;
			public $ryou			= 0;
			public $venda			= 0;
			public $venda_total		= 0;

			public $nome			= '';
			public $descricao		= '';
			public $imagem			= '';
			public $raridade		= '';
			public $drop			= 0;
			public $playerID		= 0;
			public $dojo_ativo		= 0;
			public $aprimoramento	= NULL;
			public $tempo_espera	= NULL;
			public $vezes_dia		= NULL;

			public $crit_inc		= 0;
			public $crit_inc_raw	= 0;

			public $el_reduction_ids	= array();
			public $el_reduction_values	= array();

			public $el_increase_ids		= array();
			public $el_increase_values	= array();

			public	$force_level	= 0;
		// <---

		public	$have_agi	= 0;
		public	$have_con	= 0;

		public $privateID = false;
		private $_with_inversion	= false;

		static $_requirementLog = array();

		public $_playerInstance = NULL;
		private $_itemData = NULL;

		function __construct($id, $playerID = NULL, $iID = NULL, $playerData = false, $forceInversion = false, $id_classe = NULL) {
			$item = Recordset::query('
					SELECT
						a.*,
						b.campo_base

					FROM
						item a LEFT JOIN habilidade b ON a.id_habilidade=b.id

					WHERE
						a.id=' . (int)$id, true);

			if(!$item->num_rows) {
				throw new Exception('Item "' . $id . '" not found');
			}

			$item				= $item->row_array();
			$this->ats			= $item;
			$this->_itemData	= $item;
			$this->id			= $id;
			$this->id_tipo		= $item['id_tipo'];
			$this->id_sennin	= $item['id_sennin'];

			if($item['id_elemento'] || $item['id_elemento2']) {
				$this->elemento = true;
			}

			$this->setLocalAttribute('nome', $item['nome_'. Locale::get()]);
			$this->setLocalAttribute('descricao', $item['descricao_'. Locale::get()]);

			//
			$this->nome				= $item['nome_'. Locale::get()];
			$this->descricao		= $item['descricao_'. Locale::get()];
			$this->imagem			= $item['imagem'];
			$this->sem_turno		= $item['sem_turno'];
			$this->defensivo		= $item['defensivo'];

			switch ($item['raridade']){

				case "comum":
					$this->raridade = (Locale::get()=="br" ? "Comum" : "Common");
				break;
				case "raro":
					$this->raridade = (Locale::get()=="br" ? "Raro" : "Rare");
				break;
				case "epico":
					$this->raridade = (Locale::get()=="br" ? "Épico" : "Epic");
				break;
				case "lendario":
					$this->raridade = (Locale::get()=="br" ? "Lendário" : "Legendary");
				break;
			}
			//$this->raridade			= $item['raridade'];

			$this->ordem			= $item['ordem'];
			$this->id_grupo			= $item['id_grupo'];

			$this->tipo_bonus		= $item['tipo_bonus'];
			$this->bonus_treino		= $item['bonus_treino'];
			$this->turnos			= $item['turnos'];
			$this->cooldown			= $item['cooldown'];
			$this->ryou				= $item['ryou'];
			$this->drop				= $item['drop'];

			$this->removido_item	= $item['removido'];
			$this->tai				= $item['tai'];
			$this->ken				= $item['ken'];
			$this->nin				= $item['nin'];
			$this->gen				= $item['gen'];
			$this->agi				= $item['agi'];
			$this->ene				= $item['ene'];
			$this->con				= $item['con'];
			$this->inte				= $item['inte'];
			$this->forc				= $item['forc'];
			$this->res				= $item['res'];
			$this->agi				= $item['agi'];
			$this->esq				= (float)$item['esq'];
			$this->det				= (float)$item['det'];
			$this->conv				= (float)$item['conv'];
			$this->esquiva			= (float)$item['esquiva'];
			$this->conc				= (float)$item['conc'];

			$this->atk_fisico		= $item['atk_fisico'];
			$this->atk_magico		= $item['atk_magico'];
			$this->prec_fisico		= (float)$item['prec_fisico'];
			$this->prec_magico		= (float)$item['prec_magico'];
			$this->crit_min			= (float)$item['crit_min'];
			$this->crit_max			= (float)$item['crit_max'];
			$this->crit_total		= (float)$item['crit_total'];
			$this->esq_min			= (float)$item['esq_min'];
			$this->esq_max			= (float)$item['esq_max'];
			$this->esq_total		= (float)$item['esq_total'];

			$this->def_base			= $item['def_base'];
			$this->def_fisico		= $item['def_fisico'];
			$this->def_magico		= $item['def_magico'];

			$this->consume_hp		= $item['consume_hp'];
			$this->consume_sp		= $item['consume_sp'];
			$this->consume_sta		= $item['consume_sta'];

			$this->bonus_hp			= $item['bonus_hp'];
			$this->bonus_sp			= $item['bonus_sp'];
			$this->bonus_sta		= $item['bonus_sta'];

			$this->req_tai			= $item['req_tai'];
			$this->req_ken			= $item['req_ken'];
			$this->req_nin			= $item['req_nin'];
			$this->req_gen			= $item['req_gen'];
			$this->req_for			= $item['req_for'];
			$this->req_agi			= $item['req_agi'];
			$this->req_int			= $item['req_int'];
			$this->req_con			= $item['req_con'];
			$this->req_ene			= $item['req_ene'];
			$this->req_res			= $item['req_res'];
			$this->req_portao		= $item['req_portao'];
			$this->preco			= $item['preco'];
			$this->coin				= $item['coin'];
			$this->id_reputacao		= $item['id_reputacao'];
			$this->id_vila_reputacao= $item['id_vila_reputacao'];
			$this->req_no_selo		= $item['req_no_selo'];
			$this->req_arvore_gasto	= $item['req_arvore_gasto'];
			$this->req_no_cla		= $item['req_no_cla'];
			$this->req_no_portao	= $item['req_no_portao'];
			$this->req_no_sennin	= $item['req_no_sennin'];
			$this->req_no_item		= $item['req_no_item'];

			$this->req_graduacao	= $item['req_graduacao'];
			$this->req_level		= $item['req_level'];
			$this->req_battle		= $item['req_battle'];
			$this->req_sensei_battle		= $item['req_sensei_battle'];

			$this->req_item			= $item['req_item'];
			$this->id_tipo			= $item['id_tipo'];
			$this->id_habilidade	= $item['id_habilidade'];
			$this->id_elemento		= $item['id_elemento'];
			$this->id_elemento2		= $item['id_elemento2'];
			$this->id_cla			= $item['id_cla'];
			$this->id_selo			= $item['id_selo'];
			$this->id_invocacao		= $item['id_invocacao'];
			$this->campo_base		= $item['campo_base'];
			$this->evolui			= $item['evolui'];
			$this->sennin			= $item['sennin'];
			$this->tempo_espera		= $item['tempo_espera'];
			$this->vezes_dia		= $item['vezes_dia'];
			$this->id_especializacao	= $item['id_especializacao'];

			$this->mod_dir_mine		= false;
			$this->mod_dir_enemy	= false;
			//

			if($playerID) {
				if(!isset(Item::$classe_tipo[$playerID])) {
					$rPlayerBase = Recordset::query("
						SELECT
							a.campo_base,
							c.id_classe_tipo

						FROM
							classe_tipo a JOIN player c ON a.id=c.id_classe_tipo

						WHERE
							c.id=" . (int)$playerID . "
					")->row_array();

					Item::$classe_tipo[$playerID] = $rPlayerBase;
				} else {
					$rPlayerBase = Item::$classe_tipo[$playerID];
				}
			}

			if($item['atk_magico']) {
				$this->setLocalAttribute('base_f', 'atk_magico');

				#CX
				$this->base_f	= 'atk_magico';
			} else {
				$this->setLocalAttribute('base_f', 'atk_fisico');

				#CX
				$this->base_f	= 'atk_fisico';
			}

			if($playerID) {
				if($item['defensivo'] && in_array($rPlayerBase['id_classe_tipo'], array(2, 3))) {
					$this->setLocalAttribute('base_f', 'atk_magico');
					$this->base_f	= 'atk_magico';
				} elseif($item['defensivo'] && in_array($rPlayerBase['id_classe_tipo'], array(1, 4))) {
					$this->setLocalAttribute('base_f', 'atk_fisico');
					$this->base_f	= 'atk_fisico';
				}
			}

			//if(in_array($item['id_tipo'], array(10, 11, 12, 13, 14, 15))) {
			if($item['id_tipo'] == 10 || $item['id_tipo'] == 11 || $item['id_tipo'] == 12 ||
			   $item['id_tipo'] == 13 || $item['id_tipo'] == 14 || $item['id_tipo'] == 15) {
				$this->setLocalAttribute('armadura', true);

				#CX
				$this->armadura	= true;
			} else {
				$this->setLocalAttribute('armadura', false);

				#CX
				$this->armadura = true;
			}

			if($playerID) {
				if($iID) {
					$w = " AND id = " . (int)$iID;
				} else {
					$w = "";
				}

				if($playerData && is_array($playerData)) {
					$item_player	= $playerData;
				} else {
					$item_player	= Recordset::query("SELECT id, qtd, equipado, level, level_liberado, exp, removido, ativo, uso, venda, venda_total, dojo_ativo, aprimoramento FROM player_item WHERE id_player = " . (int)$playerID . " AND id_item=" . (int)$id . " $w LIMIT 1")->row_array();
				}

				if(in_array($this->id, array(4, 5, 6, 7, 22950, 22951))) {
					$item_player['id']				= 0;
					$item_player['level']			= 1;
					$item_player['uso']				= 0;
					$item_player['level_liberado']	= 0;
					$item_player['exp']				= 0;
					$item_player['qtd']				= 1;
					$item_player['equipado']		= 0;
					$item_player['ativo']			= 0;
					$item_player['removido']		= 0;
					$item_player['venda']			= 0;
					$item_player['venda_total']		= 0;
					$item_player['dojo_ativo']		= 1;
					$item_player['aprimoramento']	= NULL;
				}

        $this->privateID  = isset($item_player['id']) ? $item_player['id'] : 0;
        $this->playerID   = $playerID;

        $this->at['level']          = isset($item_player['level']) ? $item_player['level'] : 0;
        $this->at['qtd']            = isset($item_player['qtd']) ? $item_player['qtd'] : 0;
        $this->at['uso']            = isset($item_player['uso']) ? $item_player['uso'] : 0;
        $this->at['level_liberado']	= isset($item_player['level_liberado']) ? $item_player['level_liberado'] : 0;
        $this->at['exp']            = isset($item_player['exp']) ? $item_player['exp'] : 0;
        $this->at['total']          = isset($item_player['qtd']) ? $item_player['qtd'] : 0;
        $this->at['equipado']       = isset($item_player['equipado']) ? $item_player['equipado'] : 0;
        $this->at['ativo']          = isset($item_player['ativo']) ? $item_player['ativo'] : 0;
        $this->at['removido']       = isset($item_player['removido']) ? $item_player['removido'] : 0;
        $this->at['dojo_ativo']     = isset($item_player['dojo_ativo']) ? $item_player['dojo_ativo'] : 0;

        $this->uid            = isset($item_player['id']) ? $item_player['id'] : 0;
        $this->level          = isset($item_player['level']) ? $item_player['level'] : 0;
        $this->qtd            = isset($item_player['qtd']) ? $item_player['qtd'] : 0;
        $this->uso            = isset($item_player['uso']) ? $item_player['uso'] : 0;
        $this->level_liberado	= isset($item_player['level_liberado']) ? $item_player['level_liberado'] : 0;
        $this->exp            = isset($item_player['exp']) ? $item_player['exp'] : 0;
        $this->total          = isset($item_player['qtd']) ? $item_player['qtd'] : 0;
        $this->equipado       = isset($item_player['equipado']) ? $item_player['equipado'] : 0;
        $this->ativo          = isset($item_player['ativo']) ? $item_player['ativo'] : 0;
        $this->removido       = isset($item_player['removido']) ? $item_player['removido'] : 0;
        $this->venda          = isset($item_player['venda']) ? $item_player['venda'] : 0;
        $this->venda_total    = isset($item_player['venda_total']) ? $item_player['venda_total'] : 0;
        $this->dojo_ativo     = isset($item_player['dojo_ativo']) ? $item_player['dojo_ativo'] : 0;
        $this->aprimoramento  = @unserialize($item_player['aprimoramento']);
			} else {
				$this->setLocalAttribute('qtd', 1);
				$this->setLocalAttribute('level', 1);
				$this->setLocalAttribute('level_real', 1);
				$this->setLocalAttribute('level_liberado', 0);

				#CX
				$this->qtd				= 1;
				$this->level			= 1;
				$this->level_real		= 1;
				$this->level_liberado	= 0;
			}

			// Inverte o consumo pra os primeiros 4 itens --->
				if(($playerID || $forceInversion) && in_array($this->id, array(4, 5, 6, 7))) {


					$this->setLocalAttribute('base_f', 'atk_fisico');

					$INT = (int)SharedStore::G('P_INT_' . $playerID);
					$FOR = (int)SharedStore::G('P_FOR_' . $playerID);

					$this->setLocalAttribute('id_habilidade', 1);

					if($INT > $FOR || $forceInversion) {
						$tmp_consume				= $item['consume_sta'];
						$tmp_atk					= $item['atk_fisico'];

						$this->ats['consume_sp']	= $tmp_consume;
						$this->ats['consume_sta']	= $item['consume_sp'];

						$this->ats['atk_magico']	= $tmp_atk;
						$this->ats['atk_fisico']	= $item['atk_magico'];

						$this->setLocalAttribute('base_f', 'atk_magico');
						$this->setLocalAttribute('id_habilidade', 2);

						#CX
						$this->consume_sp		= $tmp_consume;
						$this->consume_sta		= $item['consume_sp'];

						$this->atk_magico		= $tmp_atk;
						$this->atk_fisico		= $item['atk_magico'];

						$this->base_f			= 'atk_magico';
						$this->id_habilidade	= 2;
					}

				}
			// <---

			$this->do_key_mapping();
			$this->parseLevel();

			if($this->sem_turno) {
				foreach($this->getModifiers() as $_ => $modifier) {
					if(preg_match('/self_/', $_) && $modifier) {
						$this->mod_dir_mine		= true;
						break;
					}

					if(preg_match('/target_/', $_) && $modifier) {
						$this->mod_dir_enemy	= true;
						break;
					}
				}
			}
		}

		function hasAttribute($k) {
			if(!isset($this->atl_keys[$k])) {
				if(!isset($this->at_keys[$k])) {
					if(!isset($this->ats_keys[$k])) {
						return false;
					} else {
						return true;
					}
				} else {
					return true;
				}
			} else {
				return true;
			}
		}

		function getAttribute($k) {
			if(!isset($this->atl_keys[$k])) {
				if(!isset($this->at_keys[$k])) {
					if(!isset($this->ats_keys[$k])) {
						throw new Exception('Item Attribute "' . $k . '" not found');
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

		function setAttribute($k, $v) {
			if(!isset($this->at[$k])) {
				throw new Exception('Item Attribute "' . $k . '" not found');
			}

			$this->at[$k] = $v;

			if($this->uid) {
				Recordset::update('player_item', array(
					$k 		=> $v
				), array(
					'id'	=> $this->uid
				));
			}
		}

		function setLocalAttribute($k, $v) {
			$this->atl[$k]		= $v;
			$this->atl_keys[$k]	= 1;
		}

		function parseLevel() {
			$this->apply_enhancemnets();
		}

		function apply_enhancemnets() {
			
			if($this->id_tipo == 5){
				$this->consume_hp	= $this->_itemData['consume_hp'];
				$this->consume_sp	= $this->_itemData['consume_sp'];
				$this->consume_sta	= $this->_itemData['consume_sta'];
			}

			if(!$this->id_cla && $this->id_tipo == 5 && $this->_playerInstance && is_a($this->_playerInstance, 'Player')) {
				// Alterei aqui por causa dos portões de chakra
				if($this->id_habilidade == $this->_playerInstance->id_classe_tipo || $this->req_item ){
					$this->consume_sp  = $this->consume_sp;
					$this->consume_sta  = $this->consume_sta;
				}else{
					$this->consume_sp  = $this->consume_sp * 2;
					$this->consume_sta  = $this->consume_sta * 2;
				}
			}else{
				$this->consume_sp  = $this->consume_sp;
				$this->consume_sta  = $this->consume_sta;

			}

			if(!$this->uid || ($this->uid && !$this->aprimoramento) || $this->id_tipo != 5) {

				$this->apply_precision();
				$this->apply_tree();

				return;
			}

			$enhancers		= $this->aprimoramento;
			$precision		= $this->getAttribute('precisao');
			$raw_precision	= $precision;
			$crit_inc		= 0;

			$this->getModifiers();
			$mine_mods			= $this->raw_modifiers;

			$this->el_reduction_ids		= array();
			$this->el_reduction_values	= array();

			$this->el_increase_ids		= array();
			$this->el_increase_values	= array();

			// !
			$this->req_agi		= $this->_itemData['req_agi'];
			$this->req_con		= $this->_itemData['req_con'];

			$this->atk_fisico	= $this->_itemData['atk_fisico'];
			$this->atk_magico	= $this->_itemData['atk_magico'];
			$this->def_base		= $this->_itemData['def_base'];

			$this->turnos		= $this->_itemData['turnos'];
			$this->cooldown		= $this->_itemData['cooldown'];

			foreach($enhancers as $_ => $enhance) {
																			
				$item		= new Item($enhance);
				$half		= $item->tempo_espera != $_;
				$half		= $half ? 2 : 1;
				$modifiers	= $item->getModifiers();
				

				if($this->sem_turno && is_array($modifiers) && sizeof($modifiers)) {
					$ignore		= array('id', 'id_item');

					foreach($modifiers as $_ => $value) {
						if(!$value || in_array($_, $ignore)) {
							continue;
						}

						$mine_mods[$_]	+= $modifiers[$_] / $half;

					}

					$this->modifiers	= $mine_mods;
				}
				$this->consume_hp	+= floor(percent($item->consume_hp / $half, $this->consume_hp));
				$this->consume_sp	+= floor(percent($item->consume_sp / $half, $this->consume_sp));
				$this->consume_sta	+= floor(percent($item->consume_sta / $half, $this->consume_sta));

				if($item->tipo_bonus) {
					$this->def_base		+= floor($item->def_base / $half);
					$this->atk_fisico	+= floor($item->atk_fisico / $half);
					$this->atk_magico	+= floor($item->atk_magico / $half);

					$this->turnos		+= floor($item->cooldown / $half);
					$this->cooldown		+= floor($item->turnos / $half);

					$this->req_agi		+= floor($item->req_con / $half);
					$this->req_con		+= floor($item->req_con / $half);

					$crit_inc			+= floor($item->prec_magico / $half);

					if($item->id_elemento) {
						if($item->id_elemento2 > 0) {
							if(!isset($this->el_reduction_values[$item->id_elemento])) {
								$this->el_reduction_ids[]						= $item->id_elemento;
								$this->el_reduction_values[$item->id_elemento]	= 0;
							}

							$this->el_reduction_values[$item->id_elemento]	+= $item->id_elemento2;
						} else {
							if(!isset($this->el_increase_values[$item->id_elemento])) {
								$this->el_increase_ids[]						= $item->id_elemento;
								$this->el_increase_values[$item->id_elemento]	= 0;
							}

							$this->el_increase_values[$item->id_elemento]	+= abs($item->id_elemento2);
						}
					}
				} else {
					$this->def_base		+= floor(percent($item->def_base / $half, $this->def_base));
					$this->atk_fisico	+= floor(percent($item->atk_fisico / $half, $this->atk_fisico));
					$this->atk_magico	+= floor(percent($item->atk_magico / $half, $this->atk_magico));

					$this->turnos		+= floor(percent($item->turnos / $half, $this->turnos));
					$this->cooldown		+= floor(percent($item->cooldown / $half, $this->cooldown));

					$this->req_agi		+= floor(percent($item->req_con / $half, $this->req_con));
					$this->req_con		+= floor(percent($item->req_con / $half, $this->req_con));

					$crit_inc			+= floor(percent($item->prec_magico / $half, $raw_precision));
				}
			}

			$this->setLocalAttribute('req_agi', $this->req_agi);
			$this->setLocalAttribute('req_con', $this->req_con);
			$this->setLocalAttribute('turnos', $this->turnos);
			$this->setLocalAttribute('cooldown', $this->cooldown);

			$this->apply_precision();
			$this->apply_tree();

			$crit	= $this->req_con > 0 ? $this->req_con : $this->req_agi;

			if($this->getAttribute('precisao') >= 100 && $crit_inc) {
				$this->setLocalAttribute('crit_inc', ceil(as_percent($crit, $crit_inc)));
				$this->setLocalAttribute('crit_inc_raw', $crit_inc);

				$this->crit_inc		= ceil(as_percent($crit, $crit_inc));
				$this->crit_inc_raw	= $crit_inc;
			} else {
				$this->setLocalAttribute('crit_inc', 0);
				$this->setLocalAttribute('crit_inc_raw', 0);

				$this->crit_inc		= 0;
				$this->crit_inc_raw	= 0;
			}
		}

		function apply_team_modifiers() {
			if(!$this->_playerInstance) {
				return;
			}

			$role_id			= Player::getFlag('equipe_role', $this->_playerInstance->id);
			$has_specialization	= false;

			if($role_id == '' || !in_array($this->id_tipo, array(24, 37))) {
				return;
			}

			$role_lvl	= Player::getFlag('equipe_role_' . $role_id . '_lvl', $this->_playerInstance->id);

			if($role_lvl > 0) {
				$item				= Recordset::query('SELECT * FROM item WHERE id_tipo=22 AND id_habilidade=' . $role_id . ' AND ordem=' . $role_lvl, true)->row_array();
				$has_specialization	= true;
			}

			if($has_specialization) {
				$this->consume_hp	+= floor(percent($item['consume_hp'], $this->consume_hp));
				$this->consume_sp	+= floor(percent($item['consume_sp'], $this->consume_sp));
				$this->consume_sta	+= floor(percent($item['consume_sta'], $this->consume_sta));

				// Kinjutsu
				if($this->id_tipo == 37) {
					$this->bonus_hp		+= floor(percent($item['tai'], $this->bonus_hp));
					$this->bonus_sp		+= floor(percent($item['nin'], $this->bonus_sp));
					$this->bonus_sta	+= floor(percent($item['gen'], $this->bonus_sta));

					if($this->bonus_hp > 100) {
						$this->bonus_hp	= 100;
					}

					if($this->bonus_sp > 100) {
						$this->bonus_sp	= 100;
					}

					if($this->bonus_sta > 100) {
						$this->bonus_sta	= 100;
					}
				} else {
					$this->bonus_hp		+= floor(percent($item['bonus_hp'], $this->bonus_hp));
					$this->bonus_sp		+= floor(percent($item['bonus_hp'], $this->bonus_sp));
					$this->bonus_sta	+= floor(percent($item['bonus_hp'], $this->bonus_sta));
				}
			}
		}

		function setPlayerInstance($player) {
			if($this->id_tipo == 38) {
				return;
			}

			$this->_playerInstance = $player;

			if(on($this->id, array(4, 5, 6, 7))) {
				$this->setLocalAttribute('precisao', 100);

				if ($player->id_classe_tipo == 4 && $this->getAttribute('id_habilidade') == 1) {
					$this->setLocalAttribute('id_habilidade', 4);
					$this->id_habilidade	= 4;
				}

				if ($player->id_classe_tipo == 3 && $this->getAttribute('id_habilidade') == 2) {
					$this->setLocalAttribute('id_habilidade', 3);
					$this->id_habilidade	= 3;
				}
			} else {

				// Elementais TAI --->
					if($this->id_elemento && $player->id_classe_tipo == 3) {
						//$this->nome	= "Genjutsu " . $this->_itemData['nome_' . Locale::get()];
						//$this->setLocalAttribute('nome', $this->nome);

						$this->setLocalAttribute('id_habilidade', 3);
						$this->id_habilidade = 3;

						if(!$this->_with_inversion) {
							$this->_itemData['req_gen']		= $this->_itemData['req_nin'];
							$this->_itemData['req_nin']		= 0;

							$this->_with_inversion	= true;
						}

						$this->req_gen	= $this->_itemData['req_gen'];
						$this->req_nin	= 0;

						$this->setLocalAttribute('req_gen', $this->req_gen);
						$this->setLocalAttribute('req_nin', 0);
					}

					/*if($this->defensivo && $player->id_classe_tipo == 4) { // Balanceado
						$req_con	= $this->_itemData['req_con'];
						$req_agi	= $this->_itemData['req_agi'];

						if($req_con) {
							$this->req_con	= 0;
							$this->req_agi	= $req_con;

							$this->setLocalAttribute('req_con', 0);
							$this->setLocalAttribute('req_agi', $req_con);
						}
					}*/
					if($this->id_elemento && ($player->id_classe_tipo == 4)) {

						//$this->nome	= "Taijutsu " . $this->_itemData['nome_' . Locale::get()];
						//$this->setLocalAttribute('nome', $this->nome);

						$this->setLocalAttribute('id_habilidade', 4);
						$this->id_habilidade = 4;

						$this->setLocalAttribute('base_f', 'atk_fisico');
						$this->base_f	= 'atk_fisico';

						if(!$this->_with_inversion) {
							$atkm	= $this->_itemData['atk_magico'];
							$atkf	= $this->_itemData['atk_fisico'];
							$csta	= $this->_itemData['consume_sta'];
							$csp	= $this->_itemData['consume_sp'];

							$this->_itemData['atk_fisico']	= $atkm; //$this->atk_magico;
							$this->_itemData['atk_magico']	= $atkf; //$this->atk_fisico;

							$this->_itemData['consume_sta']	= $csp; //$this->consume_sp;
							$this->_itemData['consume_sp']	= $csta; //$this->consume_sta;
							
							
							$this->_itemData['req_ken']		= $this->_itemData['req_nin']; //$this->req_nin;
							$this->_itemData['req_agi']		= $this->_itemData['req_con']; //$this->req_con;
							$this->_itemData['req_for']		= $this->_itemData['req_int']; //$this->req_int;

							$this->_itemData['req_nin']		= 0;
							$this->_itemData['req_con']		= 0;
							$this->_itemData['req_int']		= 0;

							$this->_with_inversion	= true;
						}

						// Reqs -->
							$this->req_ken	= $this->_itemData['req_ken'];
							$this->req_nin	= 0;

							$this->setLocalAttribute('req_ken', $this->req_ken);
							$this->setLocalAttribute('req_nin', 0);
					

							$this->req_agi	= $this->_itemData['req_agi'];
							$this->req_con	= 0;

							$this->setLocalAttribute('req_agi', $this->req_agi);
							$this->setLocalAttribute('req_con', 0);

							$this->req_for	= $this->_itemData['req_for'];
							$this->req_int	= 0;

							$this->setLocalAttribute('req_for', $this->req_for);
							$this->setLocalAttribute('req_int', 0);
						// <---

						// Ataque -->
							$this->atk_fisico	= $this->_itemData['atk_fisico'];
							$this->atk_magico	= 0;

							$this->setLocalAttribute('atk_fisico', $this->atk_fisico);
							$this->setLocalAttribute('atk_magico', $this->atk_magico);
						// <--

						// Consumo -->
							$this->consume_sp	= $this->_itemData['consume_sp'];
							$this->consume_sta	= $this->_itemData['consume_sta'];

							$this->setLocalAttribute('consume_sp', $this->consume_sp);
							$this->setLocalAttribute('consume_sta', $this->consume_sta);
						
						// <--
					}
					if($this->id_elemento && ($player->id_classe_tipo == 1)) {
						//$this->nome	= "Taijutsu " . $this->_itemData['nome_' . Locale::get()];
						//$this->setLocalAttribute('nome', $this->nome);

						$this->setLocalAttribute('id_habilidade', 1);
						$this->id_habilidade = 1;

						$this->setLocalAttribute('base_f', 'atk_fisico');
						$this->base_f	= 'atk_fisico';

						if(!$this->_with_inversion) {
							$atkm	= $this->_itemData['atk_magico'];
							$atkf	= $this->_itemData['atk_fisico'];
							$csta	= $this->_itemData['consume_sta'];
							$csp	= $this->_itemData['consume_sp'];

							$this->_itemData['atk_fisico']	= $atkm; //$this->atk_magico;
							$this->_itemData['atk_magico']	= $atkf; //$this->atk_fisico;

							$this->_itemData['consume_sta']	= $csp; //$this->consume_sp;
							$this->_itemData['consume_sp']	= $csta; //$this->consume_sta;
							
							
							$this->_itemData['req_tai']		= $this->_itemData['req_nin']; //$this->req_nin;
							$this->_itemData['req_agi']		= $this->_itemData['req_con']; //$this->req_con;
							$this->_itemData['req_for']		= $this->_itemData['req_int']; //$this->req_int;

							$this->_itemData['req_nin']		= 0;
							$this->_itemData['req_con']		= 0;
							$this->_itemData['req_int']		= 0;

							$this->_with_inversion	= true;
						}

						// Reqs -->
							$this->req_tai	= $this->_itemData['req_tai'];
							$this->req_nin	= 0;

							$this->setLocalAttribute('req_tai', $this->req_tai);
							$this->setLocalAttribute('req_nin', 0);
					

							$this->req_agi	= $this->_itemData['req_agi'];
							$this->req_con	= 0;

							$this->setLocalAttribute('req_agi', $this->req_agi);
							$this->setLocalAttribute('req_con', 0);

							$this->req_for	= $this->_itemData['req_for'];
							$this->req_int	= 0;

							$this->setLocalAttribute('req_for', $this->req_for);
							$this->setLocalAttribute('req_int', 0);
						// <---

						// Ataque -->
							$this->atk_fisico	= $this->_itemData['atk_fisico'];
							$this->atk_magico	= 0;

							$this->setLocalAttribute('atk_fisico', $this->atk_fisico);
							$this->setLocalAttribute('atk_magico', $this->atk_magico);
						// <--

						// Consumo -->
							$this->consume_sp	= $this->_itemData['consume_sp'];
							$this->consume_sta	= $this->_itemData['consume_sta'];

							$this->setLocalAttribute('consume_sp', $this->consume_sp);
							$this->setLocalAttribute('consume_sta', $this->consume_sta);
						// <--
					}
				// <---

				if($this->id_tipo == 2) {
					if($this->atk_fisico) {
						$this->atk_fisico	+= percent($player->bonus_vila['ns_dano_curto'], $this->atk_fisico);
					}
					if($this->def_base) {
						$this->def_base	+= percent($player->bonus_vila['ns_dano_curto'], $this->def_base);
					}

					if($this->atk_magico) {
						$this->atk_magico	+= percent($player->bonus_vila['ns_dano_curto'], $this->atk_magico);
					}

					if ($player->bonus_profissao['ns_preco_curto']) {
						$this->setLocalAttribute('preco', $this->getAttribute('preco') - percent($player->bonus_profissao['ns_preco_curto'], $this->getAttribute('preco')));
						$this->setLocalAttribute('coin', $this->getAttribute('coin') - percent($player->bonus_profissao['ns_preco_curto'], $this->getAttribute('coin')));

						$this->preco	= $this->getAttribute('preco');
						$this->coin		= $this->getAttribute('coin');
					}
				}

				if ($this->id_tipo == 9) {
					$this->setLocalAttribute('consume_hp', $this->consume_hp   + percent($player->bonus_profissao['ramen_cura'], $this->consume_hp));
					$this->setLocalAttribute('consume_sp', $this->consume_sp   + percent($player->bonus_profissao['ramen_cura'], $this->consume_hp));
					$this->setLocalAttribute('consume_sta', $this->consume_sta + percent($player->bonus_profissao['ramen_cura'], $this->consume_hp));

					$this->consume_hp	= $this->getAttribute('consume_hp');
					$this->consume_sp	= $this->getAttribute('consume_sp');
					$this->consume_sta	= $this->getAttribute('consume_sta');
				}

				/*
				if ($this->req_item && $player->id_classe_tipo == 4) {
					$req_item	= Recordset::query('SELECT id_tipo FROM item WHERE id=' . $this->req_item)->row();

					if (!$this->_with_inversion && $req_item->id_tipo == 17) {
						$this->_with_inversion	= true;

						$this->req_ken	= $this->req_tai;
						$this->req_tai	= 0;

						$this->setLocalAttribute('req_ken', $this->req_ken);
						$this->setLocalAttribute('req_tai', $this->req_tai);
					}
				}
				*/

				$this->apply_precision();
			}

			if($this->aprimoramento && sizeof($this->aprimoramento) > 0) {
				$this->nome	= $this->_itemData['nome_' . Locale::get()] . ' - Lvl ' . sizeof($this->aprimoramento);
				$this->setLocalAttribute('nome', $this->nome);
			}

			// Aplica os bonus da vila --->
				if($this->id_tipo == 9) {
					$this->preco	= (int)($this->preco - percent($player->bonus_vila['ramen_preco'], $this->preco));
					$this->setLocalAttribute('preco', $this->preco);
				}

				if($this->id_tipo == 2 || $this->id_tipo == 1) {
					$this->preco	= (int)($this->preco - percent($player->bonus_vila['ns_preco'], $this->preco));
					$this->setLocalAttribute('preco', $this->preco);
				}

				if(in_array($this->id_tipo, array(10, 11, 13, 14, 15, 29))) {
					$this->preco	= (int)($this->preco - percent($player->bonus_vila['mo_ninja_shop'], $this->preco));
					$this->setLocalAttribute('preco', $this->preco);
				}
			// <---

			// Aplica os novos modificares de clãs e invocações -->
				$types	= [16, 21];

				foreach ($types as $type) {
					if($this->id_tipo == $type) {
						$mine	= Recordset::query('SELECT * FROM player_modificadores WHERE id_player=' . $player->id . ' AND id_tipo=' . $type);
						$ats	= [
							'tai',
							'ken',
							'nin',
							'gen',
							'agi',
							'con',
							'forc',
							'ene',
							'inte',
							'res',
							'esq',
							'det',
							'conv',
							'esquiva',
							'conc'
						];

						if(!$mine->num_rows) {
							break;
						}

						$mine	= $mine->row_array();

						foreach ($ats as $at) {
							$this->$at	= $mine[$at] * $this->ordem;
							
							$this->setLocalAttribute($at, $this->$at);
						}
					}
				}
			// <---
		}

		function apply_precision() {
			if(!$this->_playerInstance) {
				return;
			}

			if(on($this->id, array(4, 5, 6, 7))) {
				$this->setLocalAttribute('precisao', 100);
			} else {
				$player	=& $this->_playerInstance;

				if($this->getAttribute('base_f') == 'atk_fisico') {
					if(!$this->getAttribute('req_agi')) {
						$precisao	= 100;
					} else {
						// Adiciona mais requerimento de precisão nos golpes que não são do level
						$inc_req_agi 	= $this->getAttribute('req_level') > $player->getAttribute('level') ? $this->getAttribute('req_level') - $player->getAttribute('level') : 0; 
						$precisao		= @(($player->getAttribute('con_calc_mod') + $player->getAttribute('prec_magico_item') + $player->getAttribute('prec_magico_arv') + $player->getAttribute('prec_magico_mod')) * 100 / ($this->getAttribute('req_agi')+$inc_req_agi));
						$this->have_agi	= round($player->getAttribute('con_calc_mod') + $player->getAttribute('prec_magico_item') + $player->getAttribute('prec_magico_arv') + $player->getAttribute('prec_magico_item'));
					}
				} else {
					if(!$this->getAttribute('req_con')) {
						$precisao	= 100;
					} else {
						// Adiciona mais requerimento de precisão nos golpes que não são do level
						$inc_req_con 	= $this->getAttribute('req_level') > $player->getAttribute('level') ? $this->getAttribute('req_level') - $player->getAttribute('level') : 0; 
						$precisao		= @(($player->getAttribute('con_calc_mod') + $player->getAttribute('prec_magico_item') + $player->getAttribute('prec_magico_arv') + $player->getAttribute('prec_magico_mod')) * 100 / ($this->getAttribute('req_con')+$inc_req_con));
						$this->have_con	= round($player->getAttribute('con_calc_mod') + $player->getAttribute('prec_magico_item') + $player->getAttribute('prec_magico_arv') + $player->getAttribute('prec_magico_mod'));
					}
				}

				if($precisao > 100) {
					$precisao = 100;
				}

				$this->setLocalAttribute('precisao', round((float)$precisao, 2));
			}
		}

		function apply_tree() {
			
			if(!$this->_playerInstance) {
				return;
			}

			// Turnos
			$this->turnos	+= $this->_playerInstance->inc_turno;
			$this->turnos	= absm($this->turnos);
			$this->setLocalAttribute('turnos', $this->turnos);
			
			// Consumos
			$this->consume_sp	-= percent($this->_playerInstance->less_consume_sp,  $this->consume_sp);
			$this->consume_sta	-= percent($this->_playerInstance->less_consume_sta, $this->consume_sta);
		}

		function grow($mul, $uses = 1, $divisor = 1) {
			//$campo = $this->BASE_F;

			//$item_exp = round(((10 * $mul) * $uses) / $divisor);
			//$attr_exp = round(((2 * $mul) * $uses)  / $divisor);
			$attr_exp = $item_exp = round($mul * $uses) / $divisor;

			//Recordset::query("UPDATE player SET $campo = $campo + $attr_exp WHERE id={$this->playerID}");
			Recordset::query("UPDATE player_item SET exp = exp + $item_exp WHERE id_item={$this->id} AND id_player={$this->playerID}");

			//return array("exp" => $item_exp, "attr" => $attr_exp);
			return array("exp" => $item_exp, "attr" => $attr_exp);
		}

		function isStrong($id) {
			if(!$this->elemento) return NULL;

			if(Recordset::query('SELECT id FROM elemento_resistencia WHERE id_elemento=' . $this->getAttribute('id_elemento') . ' AND id_elemento_resiste=' . (int)$id, true)->num_rows) {
				return true;
			} else {
				return false;
			}
		}

		function isWeak($id) {
			if(!$this->elemento) return NULL;

			if(Recordset::query('SELECT id FROM elemento_fraqueza WHERE id_elemento=' . $this->getAttribute('id_elemento') . ' AND id_elemento_fraco=' . (int)$id, true)->num_rows) {
				return true;
			} else {
				return false;
			}
		}

		public static function getTreeMaxSort() {
			$item = Recordset::query("SELECT MAX(arvore_ordem) AS arvore_ordem FROM item WHERE id_tipo=25", true);

			return $item->row()->arvore_ordem;
		}

		public static function getTreeByLevel($level) {
			return Recordset::query("SELECT * FROM item WHERE arvore_pai=0 AND arvore_nivel=" . $level, true);
		}

		public static function getTreeNextItemFor($id, $level) {
			return Recordset::query("SELECT * FROM item WHERE arvore_pai=" . $id . " AND arvore_nivel=" . $level, true);
		}

		function hasModifiers() {
			$with_mods	= sizeof($this->modifiers);

			if($this->id_tipo == 1 && !$this->is_modifiers_patched) {
				$with_mods	= false;
			}

			if(!$with_mods) {
				$qMod = new Recordset("SELECT * FROM item_modificador WHERE id_item=" . $this->id, true);

				if($qMod->num_rows) {
					$mod	= $qMod->row_array();

					if($this->_playerInstance && $this->id_tipo == 1) {
						if($mod['self_atk_fisico']) {
							$mod['self_atk_fisico']	+= percent($this->_playerInstance->bonus_vila['ns_dano_longo'], $mod['self_atk_fisico']);
						}
						if($mod['self_def_base']) {
							$mod['self_def_base']	+= percent($this->_playerInstance->bonus_vila['ns_dano_longo'], $mod['self_def_base']);
						}
						if($mod['self_atk_magico']) {
							$mod['self_atk_magico']	+= percent($this->_playerInstance->bonus_vila['ns_dano_longo'], $mod['self_atk_magico']);
						}

						$this->is_modifiers_patched	= true;
					}

					$this->modifiers		= $mod;
					$this->raw_modifiers	= $mod;
				}
			}

			return sizeof($this->modifiers) ? true : false;
		}

		function getModifiers() {
			$this->hasModifiers();

			return $this->modifiers;
		}

		public static function hasRequirement($item, &$player, $old_item = NULL, $ignore_ats = array()) {
			Item::$_requirementLog = array();

			$ok			= true;
			$log		= array();
			$item		= is_numeric($item) ? new Item($item) : $item;

			if(!is_numeric($item)) {
				$item->setPlayerInstance($player);
			}

			$style_s_ok = "<span style='text-decoration: line-through'>";
			$style_e_ok = "</span>";

			$style_s_no = "<span style='color: #F00'>";
			$style_e_no = "</span>";

			$style_s_ok2 = "<div style='text-decoration: line-through'>";
			$style_e_ok2 = "</div>";

			$style_s_no2 = "<div style='color: #F00'>";
			$style_e_no2 = "</div>";

			$d_elementos_field	= 'req_nin';
			$d_elementos		= array();

			if(!isset(Item::$element_total[$player->id])) {
				Item::$element_total[$player->id] = $elementos = $player->getElementos();
			}

			if($item->id_tipo == 38) {
				$ignore_ats['req_agi']	= true;
				$ignore_ats['req_con']	= true;
			}

			/*
			if(!isset(Item::$element_total[$player->id])) {
				Item::$element_total[$player->id] = $elementos = $player->getElementos();

				if(sizeof($elementos) >= 2 && $item->id_elemento) {

					$q_elementos  = new Recordset('SELECT * FROM item WHERE id_elemento IN(' . $elementos[0] . ', ' . $elementos[1] . ')', true);

					$q_elementos1 = new Recordset('SELECT a.* FROM item a JOIN player_item b ON b.id_item=a.id AND b.id_player=' . $player->id . ' WHERE b.removido=\'0\' AND a.id_elemento=' . $elementos[0]);
					$q_elementos2 = new Recordset('SELECT a.* FROM item a JOIN player_item b ON b.id_item=a.id AND b.id_player=' . $player->id . ' WHERE b.removido=\'0\' AND a.id_elemento=' . $elementos[1]);

					foreach($q_elementos->result_array() as $k => $v) {
						if(!isset($d_elementos[$v[$d_elementos_field]])) {
							$d_elementos[$v[$d_elementos_field]] = 0;;
						}

						$d_elementos[$v[$d_elementos_field]]++;
					}

					foreach($d_elementos as $k => $v) {
						$d_elementos[$k] /= 2;
					}

					foreach($q_elementos1->result_array() as $k => $v) {
						$d_elementos[$v[$d_elementos_field]]--;
					}

					foreach($q_elementos2->result_array() as $k => $v) {
						$d_elementos[$v[$d_elementos_field]]--;
					}
				}

				Item::$element_data[$player->id] = $d_elementos;
			}*/

			if($item->removido_item) {
				$ok	= false;
			}

			// Elements --->
				if($item->id_elemento && !isset($ignore_ats['id_elemento']) && $item->id_tipo != 38) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					$elemento_a	= Recordset::query('SELECT nome FROM elemento WHERE id=' . $item->id_elemento, true)->row_array();

					if(on($item->id_elemento, Item::$element_total[$player->id])) {
						if($item->id_elemento2) {
							$elemento_b	= Recordset::query('SELECT nome FROM elemento WHERE id=' . $item->id_elemento2, true)->row_array();

							if(on($item->getAttribute('id_elemento2'), Item::$element_total[$player->id])) {
								$style_s = $style_s_ok;
								$style_e = $style_e_ok;
							} else {
								$ok = false;
							}

							$log[] = $style_s . "".t('classes.c1').": " . $elemento_b['nome'] . $style_e;
						} else {
							$style_s = $style_s_ok;
							$style_e = $style_e_ok;
						}
					} else {
						$ok = false;
					}

					$log[] = $style_s . "".t('classes.c1').": " . $elemento_a['nome'] . $style_e;
				}
				// Adiciona o req level nos jutsus
				/*
				if(sizeof(Item::$element_total[$player->id]) >= 2 && $item->id_elemento && !isset($ignore_ats['id_elemento'])) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					if(isset(Item::$element_data[$player->id][$item->getAttribute($d_elementos_field)]) && !Item::$element_data[$player->id][$item->getAttribute($d_elementos_field)]) {
						$ok		= false;
						$log[]	= $style_s . 'Você já aprendeu o limite de jutsus para esse nível de requerimento' . $style_e;
					} else {
						$style_s = $style_s_ok;
						$style_e = $style_e_ok;
					}

				}
				*/
			// <---

			if(!in_array($item->id_tipo, array(5))) {
				if($item->req_agi && !isset($ignore_ats['req_agi'])) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					if($player->getAttribute('agi_calc') >= $item->req_agi) {
						$style_s = $style_s_ok;
						$style_e = $style_e_ok;
					} else {
						$ok = false;
					}

					$log[] = $style_s . "".t('geral.requer')." " . $item->req_agi . " ".t('classes.c2')."" . $style_e;
				}

				if($item->req_con && !isset($ignore_ats['req_con'])) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					if($player->getAttribute('con_calc') >= $item->req_con) {
						$style_s = $style_s_ok;
						$style_e = $style_e_ok;
					} else {
						$ok = false;
					}

					$log[] = $style_s . "".t('geral.requer')." " . $item->req_con . " ".t('classes.c3')."" . $style_e;
				}
			}

			if($item->req_for && !isset($ignore_ats['req_for'])) {
				$style_s = $style_s_no;
				$style_e = $style_e_no;

				if($player->getAttribute('id_classe_tipo') == 2 || $player->getAttribute('id_classe_tipo') == 3){
					$item->req_for = $item->req_for * 2;
				}

				if($player->getAttribute('for_calc') >= $item->req_for) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$ok = false;
				}

				$log[] = $style_s . "".t('geral.requer')." " . $item->req_for . " ".t('classes.c4')."" . $style_e;
			}

			if($item->req_ene && !isset($ignore_ats['req_ene'])) {
				$style_s = $style_s_no;
				$style_e = $style_e_no;

				if($player->getAttribute('ene_calc') >= $item->req_ene) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$ok = false;
				}

				$log[] = $style_s . "".t('geral.requer')." " . $item->req_ene . " ".t('classes.c5')."" . $style_e;
			}

			if($item->req_res && !isset($ignore_ats['req_res'])) {
				$style_s = $style_s_no;
				$style_e = $style_e_no;

				if($player->getAttribute('res_calc') >= $item->req_res) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$ok = false;
				}

				$log[] = $style_s . "".t('geral.requer')." " . $item->req_res . " ".t('classes.c89')."" . $style_e;
			}

			if($item->req_int && !isset($ignore_ats['req_int'])) {
				$style_s = $style_s_no;
				$style_e = $style_e_no;

				if($player->getAttribute('id_classe_tipo') == 1 || $player->getAttribute('id_classe_tipo') == 4){
					$item->req_int = $item->req_int * 2;
				}

				if($player->getAttribute('int_calc') >= $item->req_int) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$ok = false;
				}

				$log[] = $style_s . "".t('geral.requer')." " . $item->req_int . " ".t('classes.c6')."" . $style_e;
			}
			// Verifica se o jogador não está tentando adquirir buff e debuff de outra classe
			/*if($item->sem_turno && $item->id_tipo==5){
				$style_s = $style_s_no;
				$style_e = $style_e_no;

				if($item->id_habilidade==1){
					$classe_nome = t('classe_tipo.tai');
				}elseif($item->id_habilidade==2){
					$classe_nome = t('classe_tipo.nin');
				}elseif($item->id_habilidade==3){
					$classe_nome = t('classe_tipo.gen');
				}else{
					$classe_nome = t('classe_tipo.ken');
				}

				if($player->getAttribute('id_classe_tipo') == $item->id_habilidade){
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;

				} else {
					$ok = false;
				}
				$log[] = $style_s . "".t('classes.c90')." " . $classe_nome . " " . $style_e;
			}*/
			if($item->req_graduacao && !isset($ignore_ats['req_graduacao'])) {
				$grad = StaticCache::get('item.hasreq.grad_' . $item->req_graduacao);

				if(!$grad) {
					$grad = Recordset::query("SELECT nome_" . Locale::get() . " AS nome, id AS grad_id FROM graduacao WHERE id=" . $item->req_graduacao, true)->row_array();

					StaticCache::store('item.
					q.grad_' . $item->req_graduacao, $grad);
				}

				$style_s = $style_s_no;
				$style_e = $style_e_no;

				if($player->getAttribute('id_graduacao') >= $item->req_graduacao) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$ok = false;
				}

				$log[] = $style_s . "".t('classes.c7').": " . graduation_name($player->getAttribute('id_vila'), $grad['grad_id']) . $style_e;
			}

			if($item->req_level && !isset($ignore_ats['req_level']) && !$item->id_grupo) {
				$style_s = $style_s_no;
				$style_e = $style_e_no;

				if($player->getAttribute('level') >= $item->req_level) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$ok = false;
				}

				$log[] = $style_s . "".t('geral.requer')." Level " . $item->req_level . " ".t('classes.c8')."" . $style_e;
			}
			if($item->req_battle && !isset($ignore_ats['req_battle'])) {
				$style_s = $style_s_no;
				$style_e = $style_e_no;

				$count		= $player->getAttribute('vitorias') + $player->getAttribute('vitorias_f') + $player->getAttribute('derrotas')  + $player->getAttribute('derrotas_f') + $player->getAttribute('empates');

				if($count >= $item->req_battle) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$ok = false;
				}

				$log[] = $style_s . "".t('requerimentos.ter'). " " .  $item->req_battle . " " .t('requerimentos.batalhas_pvp'). " " . $style_e;
			}
			if($item->req_sensei_battle && !isset($ignore_ats['req_sensei_battle'])) {
				$style_s = $style_s_no;
				$style_e = $style_e_no;

				$req_sensei = Recordset::query('SELECT desafio FROM player_sensei_desafios WHERE id_player='. $player->getAttribute('id').' AND id_sensei='. $player->getAttribute('id_sensei'))->result_array();
				$req_sensei = $req_sensei ? $req_sensei[0]['desafio'] : 0;
				if($req_sensei >= $item->req_sensei_battle) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$ok = false;
				}

				$log[] = $style_s . "".t('requerimentos.ter'). " " .  $item->req_sensei_battle . " " .t('requerimentos.batalhas_sensei'). " " . $style_e;
			}

			if($item->req_portao && !isset($ignore_ats['req_portao'])) {
				$style_s = $style_s_no;
				$style_e = $style_e_no;


				if($player->portao) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$ok = false;
				}

				$log[] = $style_s . "".t('classes.c9')."" . $style_e;
			}

			if($item->req_item && !isset($ignore_ats['req_item'])) {
				$style_s = $style_s_no;
				$style_e = $style_e_no;

				$req_item = Recordset::query('SELECT id, nome_br,nome_en, id_tipo, ordem, id_habilidade FROM item WHERE id IN (' . $item->req_item . ')', true);

				$req_log = '';
				foreach($req_item->result_array() as $reqItem) {
					if($reqItem['id_tipo'] == 22) {
						if(Player::getFlag('equipe_role_' . $reqItem['id_habilidade'] . '_lvl', $player->id) >= $reqItem['ordem']) {
							$style_s = $style_s_ok;
							$style_e = $style_e_ok;
						} else {
							$style_s = $style_s_no;
							$style_e = $style_e_no;

							$ok = false;
						}
						$req_log .= $style_s . "".t('classes.c100').": " . $reqItem['nome_'. Locale::get()] . $style_e ."<br />";
					} else {
						if($player->hasItemW($reqItem['id'])) {
							$style_s = $style_s_ok;
							$style_e = $style_e_ok;
						} else {
							$style_s = $style_s_no;
							$style_e = $style_e_no;

							$ok = false;
						}
						$req_log .= $style_s . "".t('classes.c10').": " . $reqItem['nome_'. Locale::get()] . $style_e ."<br />";
					}

				}
				$log[] = "" . $req_log . "";
			}

			if($item->id_tipo == 38) {
				if($item->coin && !isset($ignore_ats['coin'])) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					if($player->pontos_pvp >= $item->coin) {
						$style_s = $style_s_ok;
						$style_e = $style_e_ok;
					} else {
						$ok = false;
					}

					$log[] = $style_s . "".t('geral.requer')." " . $item->preco . " " . t('item_tooltip.reqs.pontos_pvp') . $style_e;
				}

				if($item->preco && !isset($ignore_ats['preco'])) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					if($player->pontos_npc >= $item->preco) {
						$style_s = $style_s_ok;
						$style_e = $style_e_ok;
					} else {
						$ok = false;
					}

					$log[] = $style_s . "".t('geral.requer')." " . $item->preco . " " . t('item_tooltip.reqs.pontos_npc') . $style_e;
				}
			} else {
				if($item->coin && !isset($ignore_ats['coin'])) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					if($player->getAttribute('coin') >= $item->coin) {
						$style_s = $style_s_ok;
						$style_e = $style_e_ok;
					} else {
						$ok = false;
					}

					$log[] = $style_s . "".t('geral.requer')." " . $item->getAttribute('coin') . " ".t('conquistas.c12')."" . $style_e;
				}

				if($item->preco && !isset($ignore_ats['preco'])) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					if($player->getAttribute('ryou') >= $item->preco) {
						$style_s = $style_s_ok;
						$style_e = $style_e_ok;
					} else {
						$ok = false;
					}

					$log[] = $style_s . "".t('geral.requer')." " . $item->preco . " Ryous" . $style_e;
				}
			}

			if($item->req_arvore_gasto && !isset($ignore_ats['req_arvore_gasto'])) {
				$style_s = $style_s_no;
				$style_e = $style_e_no;

				if($player->getAttribute('arvore_gasto') >= $item->req_arvore_gasto) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$ok = false;
				}

				$log[] = $style_s . "".t('geral.requer')." " . $item->req_arvore_gasto . " ".t('classes.c11')."" . $style_e;
			}

			if($item->req_no_item && !isset($ignore_ats['req_no_item'])) {
				$qReqItems	= Recordset::query('SELECT * FROM item WHERE id IN(' . $item->req_no_item . ')', true);
				$reqLog		= '';

				foreach($qReqItems->result_array() as $reqItem) {
					if(!$player->hasItemW($reqItem['id'])) {
						$style_s = $style_s_ok;
						$style_e = $style_e_ok;
					} else {
						$style_s = $style_s_no;
						$style_e = $style_e_no;

						$ok = false;
					}

					$reqLog .= "<li>" . $style_s . $reqItem['nome_'. Locale::get()] . $style_e . "</li>";
				}

				$log[] = " ".t('classes.c12').": <ul>" . $reqLog . "</ul>";
			}

			// Requer pontos --->
			if($item->id_tipo == 25) {
				$pointsLeft = absm(absm($player->getAttribute('level') - 4) - $player->getAttribute('arvore_gasto'));

				if($player->getAttribute('level') >= 65) {
					$pointsLeft = abs(60 - $player->getAttribute('arvore_gasto'));
				}

				if($pointsLeft > 0) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					$ok = false;
				}

				$log[] = $style_s . "".t('classes.c13')."" . $style_e;
			}
			// <---

			if($item->id_cla && !isset($ignore_ats['id_cla'])) {
				$style_s = $style_s_no;
				$style_e = $style_e_no;

				$cla = Recordset::query('SELECT * FROM cla WHERE id=' . $item->id_cla, true)->row_array();

				if($player->getAttribute('id_cla') == $item->id_cla) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$ok = false;
				}

				$log[] = $style_s . "".t('classes.c14')." " . $cla['nome'] . $style_e;
			}

			if($item->id_invocacao && !isset($ignore_ats['id_invocacao'])) {
				$style_s = $style_s_no;
				$style_e = $style_e_no;

				$invocacao = Recordset::query('SELECT * FROM invocacao WHERE id=' . $item->id_invocacao, true)->row_array();

				if($player->getAttribute('id_invocacao') == $item->id_invocacao) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$ok = false;
				}

				$log[] = $style_s . "".t('classes.c15').": " . $invocacao['nome_'. Locale::get()] . $style_e;
			}

			if($item->id_selo && !isset($ignore_ats['id_selo'])) {
				$style_s = $style_s_no;
				$style_e = $style_e_no;

				$selo = Recordset::query('SELECT * FROM selo WHERE id=' . $item->id_selo, true)->row_array();

				if($player->getAttribute('id_selo') == $item->id_selo) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$ok = false;
				}

				$log[] = $style_s . "".t('classes.c16').": " . $selo['nome_'.Locale::get()] . $style_e;
			}

			if($item->id_sennin && !isset($ignore_ats['id_sennin'])) {
				$style_s = $style_s_no;
				$style_e = $style_e_no;

				$sennin = Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM sennin WHERE id=' . $item->id_sennin, true)->row_array();

				if($player->getAttribute('id_sennin') == $item->id_sennin) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$ok = false;
				}

				$log[] = $style_s . "".t('classes.c17').": " . $sennin['nome'] . $style_e;
			}

			if($item->req_no_cla && !isset($ignore_ats['req_no_cla'])) {
				if($player->getAttribute('id_cla')) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					$ok = false;
				} else {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				}

				$log[] = $style_s . ''. t('classes.c18') .' ' . $style_e;
			}

			if($item->req_no_selo && !isset($ignore_ats['req_no_selo'])) {
				if($player->getAttribute('id_selo')) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					$ok = false;
				} else {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				}

				$log[] = $style_s . ''. t('classes.c19') .'' . $style_e;
			}

			if($item->req_no_portao && !isset($ignore_ats['req_portao'])) {
				if($player->getAttribute('portao')) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					$ok = false;
				} else {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				}

				$log[] = $style_s . ''. t('classes.c20') .'' . $style_e;
			}

			if($item->req_no_sennin && !isset($ignore_ats['req_no_sennin'])) {
				if($player->getAttribute('sennin')) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					$ok = false;
				} else {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				}

				$log[] = $style_s . ''. t('classes.c21') .'' . $style_e;
			}

			/*if($item->id_reputacao && $item->id_vila_reputacao && !isset($ignore_ats['req_reputacao'])) {
				if($player->reputacoes[$item->id_vila_reputacao] < $item->id_reputacao) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					$ok = false;
				} else {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				}

				$vila	= Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM vila WHERE id=' . $item->id_vila_reputacao, true)->row_array();
				$rep	= Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM reputacao WHERE id=' . $item->id_reputacao, true)->row_array();

				$log[] = $style_s . ''. t('classes.c22') .' <i>' . $rep['nome'] . '</i> '. t('classes.c23') .': ' . $vila['nome'] . $style_e;
			}

			if($item->id_reputacao && !$item->id_vila_reputacao && !isset($ignore_ats['req_reputacao'])) {
				if($player->id_vila_atual == 6 && $player->reputacoes[6] != 5) {
					$item->id_reputacao -= 5;
				}

				if(@$player->reputacoes[$player->getAttribute('id_vila')] < $item->id_reputacao) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					$ok = false;
				} else {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				}

				if($player->id_vila_atual == 6) {
					$item->id_reputacao -= 5;
				}

				$rep	= StaticCache::get('item.hasreq.rep_' . $item->id_reputacao);

				if(!$rep) {
					$rep	= Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM reputacao WHERE id=' . $item->id_reputacao, true)->row_array();

					StaticCache::store('item.hasreq.rep_' . $item->id_reputacao, $rep);
				}

				$log[] = $style_s . ''. t('classes.c22') .' <i>' . $rep['nome'] . '</i> '. t('classes.c24') .'' . $style_e;
			}*/

			if($item->id_cla && $item->id_tipo == 5) { //  && $_SESSION['universal']
				$ok_local	= false;

				if($item->req_tai && $player->getAttribute('tai_calc') >= $item->req_tai) {
					$ok_local	= true;
				}
				if($item->req_ken && $player->getAttribute('ken_calc') >= $item->req_ken) {
					$ok_local	= true;
				}
				if($item->req_nin && $player->getAttribute('nin_calc') >= $item->req_nin) {
					$ok_local	= true;
				}

				if($item->req_gen && $player->getAttribute('gen_calc') >= $item->req_gen) {
					$ok_local	= true;
				}

				if(!$ok_local) {
					$style_s = $style_s_no2;
					$style_e = $style_e_no2;

					$ok = false;
				} else {
					$style_s = $style_s_ok2;
					$style_e = $style_e_ok2;
				}

				$log[]	= $style_s . ''. t('classes.c25') .':<ul>' .
						  ($item->req_tai ? '<li>' . $item->req_tai . ' '.t('geral.em').' Taijutsu</li>' : '') .
						  ($item->req_ken ? '<li>' . $item->req_ken . ' '.t('geral.em').' Bukijutsu</li>' : '') .
						  ($item->req_nin ? '<li>' . $item->req_nin . ' '.t('geral.em').' Ninjutsu</li>' : '') .
						  ($item->req_gen ? '<li>' . $item->req_gen . ' '.t('geral.em').' Genjutsu</li>' : '') .
						  '</ul>' . $style_e;
			} elseif($item->req_item && $item->id_tipo == 5 && Recordset::query('SELECT id_tipo FROM item WHERE id=' . $item->req_item)->row()->id_tipo == 17) {
				$ok_local	= false;

				if($item->req_tai && $player->getAttribute('tai_calc') >= $item->req_tai) {
					$ok_local	= true;
				}

				if($item->req_ken && $player->getAttribute('ken_calc') >= $item->req_ken) {
					$ok_local	= true;
				}

				if(!$ok_local) {
					$style_s = $style_s_no2;
					$style_e = $style_e_no2;

					$ok = false;
				} else {
					$style_s = $style_s_ok2;
					$style_e = $style_e_ok2;
				}

				$log[]	= $style_s . ''. t('classes.c25') .':<ul>' .
						  ($item->req_tai ? '<li>' . $item->req_tai . ' '.t('geral.em').' Taijutsu</li>' : '') .
						  ($item->req_ken ? '<li>' . $item->req_ken . ' '.t('geral.em').' Bukijutsu</li>' : '') .
						  '</ul>' . $style_e;
			} else {
				if($item->req_tai && !isset($ignore_ats['req_tai'])) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					// Regra para dificultar aprender golpes de outra classe
					if($player->getAttribute('id_classe_tipo') != 1){
						$item->req_tai = $item->req_tai * 2;
					}

					if($player->getAttribute('tai_calc') >= $item->req_tai) {
						$style_s = $style_s_ok;
						$style_e = $style_e_ok;
					} else {
						$ok = false;
					}

					$log[] = $style_s . "".t('geral.requer')." " . $item->req_tai . " ". t('classes.c26') ."" . $style_e;
				}
				if($item->req_ken && !isset($ignore_ats['req_ken'])) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					// Regra para dificultar aprender golpes de outra classe
					if($player->getAttribute('id_classe_tipo') != 4){
						$item->req_ken = $item->req_ken * 2;
					}

					if($player->getAttribute('ken_calc') >= $item->req_ken) {
						$style_s = $style_s_ok;
						$style_e = $style_e_ok;
					} else {
						$ok = false;
					}

					$log[] = $style_s . "".t('geral.requer')." " . $item->req_ken . " ". t('classes.c261') ."" . $style_e;
				}
				if($item->req_nin && !isset($ignore_ats['req_nin'])) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					// Regra para dificultar aprender golpes de outra classe
					if($player->getAttribute('id_classe_tipo') != 2){
						$item->req_nin = $item->req_nin * 2;
					}

					if($player->getAttribute('nin_calc') >= $item->req_nin) {
						$style_s = $style_s_ok;
						$style_e = $style_e_ok;
					} else {
						$ok = false;
					}

					$log[] = $style_s . "".t('geral.requer')." " . $item->req_nin . " ". t('classes.c27') ."" . $style_e;
				}

				if($item->req_gen && !isset($ignore_ats['req_gen'])) {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					// Regra para dificultar aprender golpes de outra classe
					if($player->getAttribute('id_classe_tipo') != 3){
						$item->req_gen = $item->req_gen * 2;
					}

					if($player->getAttribute('gen_calc') >= $item->req_gen) {
						$style_s = $style_s_ok;
						$style_e = $style_e_ok;
					} else {
						$ok = false;
					}

					$log[] = $style_s . "".t('geral.requer')." " . $item->req_gen . " ". t('classes.c28') ."" . $style_e;
				}
			}

			if($old_item) {
				$exp			= array();
				$exp_current	= $old_item->exp;
				$level			= $old_item->level + 1;

				switch($old_item->req_graduacao) {
					case 1:
					case 2:
						$exp[2] = 6000;
						$exp[3] = 12000;

						break;

					case 3:
						$exp[2] = 7000;
						$exp[3] = 14000;

						break;

					case 4:
						$exp[2] = 8000;
						$exp[3] = 16000;

						break;

					case 5:
						$exp[2] = 9000;
						$exp[3] = 18000;

						break;

					case 6:
						$exp[2] = 10000;
						$exp[3] = 20000;

						break;

					case 7:
						$exp[2] = 12000;
						$exp[3] = 24000;

						break;

				}

				if($exp_current >= $exp[$level]) {
					$style_s = $style_s_ok;
					$style_e = $style_e_ok;
				} else {
					$style_s = $style_s_no;
					$style_e = $style_e_no;

					$ok = false;
				}

				$log[] = '<hr />';
				$log[] = $style_s . ''.t('geral.requer').' ' . $exp[$level] . ' '.t('classes.c29').'' . $style_e;

				$log[] = barra_exp3($exp_current, $exp[$level], 220, $exp_current . '/' . $exp[$level], '#2C531D', '#537F3D', 3, '', true);
			}

			Item::$_requirementLog = $log;

			return $ok;
		}

		public static function getRequirementLog() {
			return Item::$_requirementLog;
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
	}
