<?php
	class Fight {
		public	$_player	= NULL;
		public	$_enemy		= NULL;
		public	$log		= '';

		public	$is4			= false;
		public	$id				= NULL;
		public	$is4xpvp		= false;

		public	$flight		= false;
		public	$flightId	= 0;

		function __construct($id = NULL) {
			$this->id = $id;
		}

		static function cleanup($player = NULL) {
			SharedStore::S('_TRL_' . $_SESSION['basePlayer'], array());
			SharedStore::S('_PVP_ITEMS_' . $_SESSION['basePlayer'], array());
			SharedStore::S('_USED_HEAL_' . $_SESSION['basePlayer'], false);

			$_SESSION['_pvpITEMS']	= array();
			$p = Player::getInstance(); 

			if(!$p) {
				$p = new Player($_SESSION['basePlayer']);
				Player::setInstance($p);
			}

			$p->clearModifiers();

			// Limpa os npcs --->
				$npc = new NPC(null, $p);
				$npc->batalha_multi_pos	= 0;

				$npc->clearModifiers();

				for($f = 1; $f <= 4; $f++) {
					$npc = new NPC(null, $p);
					$npc->batalha_multi_pos	= $f;

					$npc->clearModifiers();
				}
			// <---

			if($player) {
				SharedStore::S('_TRL_' . $player, array());
				SharedStore::S('_PVP_ITEMS_' . $player, array());
				SharedStore::S('_USED_HEAL_' . $player, false);

				$p = new Player($player);
				$p->clearModifiers();
			}
		}

		/**
		 * Adiciona um player na luta
		 *
		 * @param Player $playerObject o jogador
		 */
		function addPlayer(&$playerObject) {
			$this->_player =& $playerObject;
		}

		// FIXME: Alterar o primeiro parametro para tipo Enemy quando a classe for concluída
		/**
		 * Adiciona um inimigo na luta
		 *
		 * @param Player $enemyObject
		 */
		function addEnemy(&$enemyObject) {
			$this->_enemy =& $enemyObject;
		}

		/**
		 * Exibe as mensagens de batalha
		 *
		 * @param int $atkResult Tipo do ataque retornado
		 * @param int $atkDamage Dano total infligido
		 * @param int $atkDivDamage Dano total devidido pelos players
		 * @param boolean $isEnemy Define se é ataque inimigo ou dos players
		 */
		private function _parseATKMessage($p, $atkResult, $atkDamage) {
			switch($atkResult) {
				case 1:
					echo '<b class="pvp_p_name">' . $p->getAttribute('nome') . '</b> <span style="color: #266fa3">'.t('fight.f1').'</span> <span class="pvp_p_damage">'. $atkDamage . ' '.t('fight.f2').'</span><br />';

					break;

				case 2:
					echo '<b class="pvp_p_name">' . $p->getAttribute('nome') . '</b> <span class="verde">'.t('fight.f3').'</span><br />';

					break;

				case 3:
					echo '<b class="pvp_p_name">' . $p->getAttribute('nome') . '</b> <span class="verde">'.t('fight.f4').' </span><span class="pvp_p_damage">' . $atkDamage . ' '.t('fight.f2').'</span><br />';

					break;

				case 4:
					echo '<b class="pvp_p_name">' . $p->getAttribute('nome') . '</b> <span class="verde">'.t('fight.f5').'</span> <span class="pvp_p_damage">' . $atkDamage . ' '.t('fight.f2').'</span><br />';

					break;

				case 5:
					echo '<span class="pvp_esquiva"><b>' . $p->getAttribute('nome') . '</b> <span class="verde">'.t('fight.f6').'</span></span><br />';

					break;
			}
		}

		private function _processATK(&$player, &$enemy, $pATK, $pDEF, $eATK, $eDEF, $baseF, $e = 0, $cA = false, $cD = false, $esquiva = false) {
			$totalDamage = 0;
			if(!$esquiva){

				if($e) { // Esquiva ?
					/*if($_SESSION['universal']) {
						echo '-- DANO_BASE_ANTES [' . $e . '] --<br />' . $pATK[$baseF] . '<br />';
					}*/

					$pATK[$baseF] -= percent($e, $pATK[$baseF]);

					/*if($_SESSION['universal']) {
						echo $pATK[$baseF] . '<br />-- DANO_BASE_DEPOIS --<br />';
					}*/
				}

				if($pATK[$baseF] > $eDEF[$baseF]) { // Dano
					$totalDamage	= round($pATK[$baseF] - $eDEF[$baseF]);
					$resultATK		= 1;

					$enemy->consumeHP($totalDamage);
				} elseif ($pATK[$baseF] == $eDEF[$baseF]) { // ATK = DEF
					$resultATK = 2;
				} else {
					$counterDamage = round(abs(($pATK[$baseF] - $eDEF[$baseF]) / 2));

					if($counterDamage <= 0)  { // Sempre vai haver dano =D
						$counterDamage = 1;
					}

					if($player->getATKItem()->flight) {
						$resultATK = 4;
					} else {
						$resultATK = 3;
					}

					$player->consumeHP($counterDamage);
				}
			}

			if(on($resultATK, "3,4")) {
				$this->_parseATKMessage($enemy, $resultATK, $counterDamage);
			} elseif($resultATK == 5) {
				$this->_parseATKMessage($enemy, $resultATK, $totalDamage);
			} else {
				$this->_parseATKMessage($enemy, $resultATK, $totalDamage);
			}

			return $resultATK;
		}

		static function getUsedCrits($source, $id = "", $is4 = false, $is4xpvp = false) {
			if($is4xpvp) {
				$extra	= '_4x';
			} else {
				$extra	= '';
			}

			$keyb	= '_BTCHM' . ($is4 ? '4_' : '') . '_' . $id . $extra . '_' . (!is_a($source, 'Player') ? 'NPC_' : '') . $source->id;

			return SharedStore::G($keyb, 0);
		}

		static function getUsedEsqs($source, $id = "", $is4 = false, $is4xpvp = false) {
			if($is4xpvp) {
				$extra	= '_4x';
			} else {
				$extra	= '';
			}

			$keyb	= '_BESQM' . ($is4 ? '4_' : '') . '_' . $id . $extra . '_' . (!is_a($source, 'Player') ? 'NPC_' : '') . $source->id;

			return SharedStore::G($keyb, 0);
		}
		/*TESTE*/
		function hasEsquivaPoint($source, $total = false) {
			if(isset($this->is4xpvp) && $this->is4xpvp) {
				$extra	= '_4x';
			} else {
				$extra	= '';
			}

			$key	= '_BESQ1'  . ($this->is4 ? '4_' : '') . '_' . $this->id . $extra . '_' . (!is_a($source, 'Player') ? 'NPC_' : '') . $source->id;
			$keyb	= '_BESQM1' . ($this->is4 ? '4_' : '') . '_' . $this->id . $extra . '_' . (!is_a($source, 'Player') ? 'NPC_' : '') . $source->id;

			$max	= SharedStore::G($key,  NULL);
			$used	= SharedStore::G($keyb, NULL);

			SharedStore::S($key, (string)$source->getAttribute('max_esquiva_hits'));

			if(is_null($used)) {
				SharedStore::S($keyb, '00');

				$max	= $source->getAttribute('max_esquiva_hits');
				$used	= 0;
			}

			//echo '// --> ' . $max . ' --> ' . $used . PHP_EOL;

			if(!$total) {
				return ($max - $used) > 0 ? true : false;
			} else {
				//return $max;
				return ($max - $used) < 0 ? 0 : ($max - $used);
			}
		}

		function useEsquivaPoint(&$source) {
			if(isset($this->is4xpvp) && $this->is4xpvp) {
				$extra	= '_4x';
			} else {
				$extra	= '';
			}

			$key	= '_BESQM1' . ($this->is4 ? '4_' : '') . '_' . $this->id . $extra . '_' . (!is_a($source, 'Player') ? 'NPC_' : '') . $source->id;
			$value	= SharedStore::G($key, 0);

			$value++;

			SharedStore::S($key, (string)$value);
		}
		/* TESTE */
		
		function hasEsqPoint($source, $total = false) {
			if(isset($this->is4xpvp) && $this->is4xpvp) {
				$extra	= '_4x';
			} else {
				$extra	= '';
			}

			$key	= '_BESQ'  . ($this->is4 ? '4_' : '') . '_' . $this->id . $extra . '_' . (!is_a($source, 'Player') ? 'NPC_' : '') . $source->id;
			$keyb	= '_BESQM' . ($this->is4 ? '4_' : '') . '_' . $this->id . $extra . '_' . (!is_a($source, 'Player') ? 'NPC_' : '') . $source->id;

			$max	= SharedStore::G($key,  NULL);
			$used	= SharedStore::G($keyb, NULL);

			SharedStore::S($key, (string)$source->getAttribute('max_esq_hits'));

			if(is_null($used)) {
				SharedStore::S($keyb, '00');

				$max	= $source->getAttribute('max_esq_hits');
				$used	= 0;
			}

			//echo '// --> ' . $max . ' --> ' . $used . PHP_EOL;

			if(!$total) {
				return ($max - $used) > 0 ? true : false;
			} else {
				//return $max;
				return ($max - $used) < 0 ? 0 : ($max - $used);
			}
		}

		function useEsqPoint(&$source) {
			if(isset($this->is4xpvp) && $this->is4xpvp) {
				$extra	= '_4x';
			} else {
				$extra	= '';
			}

			$key	= '_BESQM' . ($this->is4 ? '4_' : '') . '_' . $this->id . $extra . '_' . (!is_a($source, 'Player') ? 'NPC_' : '') . $source->id;
			$value	= SharedStore::G($key, 0);

			$value++;

			SharedStore::S($key, (string)$value);
		}

		function hasCritPoint(&$source, $total = false) {
			if(isset($this->is4xpvp) && $this->is4xpvp) {
				$extra	= '_4x';
			} else {
				$extra	= '';
			}

			$key	= '_BTCH'  . ($this->is4 ? '4_' : '') . '_' . $this->id . $extra . '_' . (!is_a($source, 'Player') ? 'NPC_' : '') . $source->id;
			$keyb	= '_BTCHM' . ($this->is4 ? '4_' : '') . '_' . $this->id . $extra . '_' . (!is_a($source, 'Player') ? 'NPC_' : '') . $source->id;

			$max	= SharedStore::G($key,  NULL);
			$used	= SharedStore::G($keyb, NULL);

			SharedStore::S($key, (string)$source->getAttribute('max_crit_hits'));

			if(is_null($used)) {
				SharedStore::S($keyb, '00');

				$max	= $source->getAttribute('max_crit_hits');
				$used	= 0;
			}

			if(!$total) {
				return ($max - $used) > 0 ? true : false;
			} else {
				//return $max;
				return ($max - $used) < 0 ? 0 : ($max - $used);
			}
		}

		function useCritPoint(&$source) {
			if(isset($this->is4xpvp) && $this->is4xpvp) {
				$extra	= '_4x';
			} else {
				$extra	= '';
			}

			$key	= '_BTCHM' . ($this->is4 ? '4_' : '') . '_' . $this->id . $extra . '_' . (!is_a($source, 'Player') ? 'NPC_' : '') . $source->id;
			$value	= SharedStore::G($key, 0);

			$value++;

			SharedStore::S($key, (string)$value);
		}

		function Process() {
			$playerLog = "";

			ob_start();

			$pDoubleA	= $eDoubleA = $pDoubleD = $eDoubleD = 1;



			// Player
			$pATK		= array('atk_fisico' => 0, 'atk_magico' => 0);
			$pDEF		= array('atk_fisico' => $this->_player->getAttribute('def_fisico_calc'), 'atk_magico' => $this->_player->getAttribute('def_magico_calc'));
			$pIsFlight	= false;

			if(!$this->_player) {
				error_log("Objeto player sem dados =( --> " . $this->id . " --> \n" . print_r($_POST, true) . "\n" . $this->_enemy->getATKItem()->id, E_USER_WARNING);
			}

			// Somente no PVP
			if(is_a($this->_player, 'Player') && is_a($this->_enemy, 'Player')) {
				$this->_player->parseModifiers();
				$this->_player->atCalc();

				$this->_enemy->parseModifiers();
				$this->_enemy->atCalc();
			}

			$pAtkItem	= $this->_player->getATKItem();
			$pERR		= false;
			$pCRI		= false;
			$pESQ		= false;
			$pESQUIVA	= false;

			// Inimigo
			$eATK		= array('atk_fisico' => 0, 'atk_magico' => 0);
			$eDEF		= array('atk_fisico' => $this->_enemy->getAttribute('def_fisico_calc'), 'atk_magico' => $this->_enemy->getAttribute('def_magico_calc'));
			$eIsFlight	= false;
			$eAtkItem	= $this->_enemy->getATKItem();
			$eERR		= false;
			$eCRI		= false;
			$eESQ		= false;
			$eESQUIVA	= false;

			if($this->is4 && is_a($this->_player, 'Player')) {
				$pAtkItem->setPlayerInstance($this->_player);
			}

			if($this->is4 && is_a($this->_enemy, 'Player')) {
				$eAtkItem->setPlayerInstance($this->_enemy);
			}

			// Verifica se foram usadas técnicas elementais --->
				if($pAtkItem->elemento && $eAtkItem->elemento) {
					// Elemento do inimigo é fraco contra o meu
					if($eAtkItem->isWeak($pAtkItem->id_elemento)) {
						//echo "P_FORTE<br/>";

						$pDoubleA	= 1.5;

						// Aumento meu bonus de aprimoramento (aumenta dano)
						if(isset($eAtkItem->el_reduction_values[$pAtkItem->id_elemento])) {
							$pDoubleA	-= $eAtkItem->el_reduction_values[$pAtkItem->id_elemento] / 200;
						}
					}

					if($pAtkItem->isWeak($eAtkItem->id_elemento)) {
						//echo "E_FORTE<br/>";

						$eDoubleA	= 1.5;

						// Aumento meu bonus de aprimoramento (aumenta dano)
						if(isset($pAtkItem->el_reduction_values[$eAtkItem->id_elemento])) {
							$eDoubleA	-= $pAtkItem->el_reduction_values[$eAtkItem->id_elemento] / 200;
						}
					}

					if($pAtkItem->isStrong($eAtkItem->id_elemento)) {
						//echo "E_FRACO<br/>";

						$pDoubleD	= 1.5;

						if(isset($pAtkItem->el_increase_values[$eAtkItem->id_elemento])) {
							$pDoubleA	+= $pAtkItem->el_increase_values[$eAtkItem->id_elemento] / 200;
						}
					}

					if($eAtkItem->isStrong($pAtkItem->id_elemento)) {
						//echo "P_FRACO<br/>";

						$eDoubleD	= 1.5;

						if(isset($eAtkItem->el_increase_values[$pAtkItem->id_elemento])) {
							$eDoubleA	+= $eAtkItem->el_increase_values[$pAtkItem->id_elemento] / 200;
						}
					}

					/*if($_SESSION['universal']){
						print($pDoubleA .'--------'. $eDoubleA);
					}*/
					// ATK
					//$pDoubleA	= $eAtkItem->isWeak($pAtkItem->getAttribute('id_elemento'));
					//$eDoubleA	= $pAtkItem->isWeak($eAtkItem->getAttribute('id_elemento'));

					// DEF
					//$pDoubleD	= $pAtkItem->isStrong($eAtkItem->getAttribute('id_elemento'));
					//$eDoubleD	= $eAtkItem->isStrong($pAtkItem->getAttribute('id_elemento'));

				}
			// <---

			// Verificação base(defesa, ataque e etc) --->
			for($f = 0; $f <= 1; $f++) {
				if($f == 0) {
					$pdef		= '_pDef';
					$isdef		= 'pIsDef';
					$basef		= 'pBaseF';
					$atki		= 'pAtkItem';
					$atk		= 'pATK';
					$def		= 'pDEF';
					$flight		= 'pIsFlight';
					$flightid	= 'pFlightId';
					$err		= 'pERR';

					$obj		=& $this->_player;
				} else {
					$pdef		= '_eDef';
					$isdef		= 'eIsDef';
					$basef		= 'eBaseF';
					$atki		= 'eAtkItem';
					$atk		= 'eATK';
					$def		= 'eDEF';
					$flight		= 'eIsFlight';
					$flightid	= 'eFlightId';
					$err		= 'eERR';

					$obj		=& $this->_enemy;
				}

				$$pdef	= $obj->getAttribute('def_base_calc');
				$$basef	= $$atki->getAttribute('base_f');
				$_atk	=& $$atk;

				// Chance de erro --->
				if($$atki->getAttribute('precisao') < 100) {
					$chance	= rand(1, 100);

					if($chance > $$atki->getAttribute('precisao')) {
						$$err	= true;
					}
				}
				// <--

				if(!$$atki->getAttribute('defensivo')) { // Item normal

					$_atk['atk_fisico'] = $$atki->atk_fisico;
					$_atk['atk_magico'] = $$atki->atk_magico;

					if($_SESSION['universal']) {
						//echo "ATKM: " . $_atk['atk_magico'] . "<br />";
					}

					if(!on($$atki->id, array(4, 5) )) {
						$_atk['atk_fisico'] += $obj->getAttribute('atk_fisico_calc');
						$_atk['atk_magico'] += $obj->getAttribute('atk_magico_calc');
					} else {
						$_atk['atk_fisico'] += percent($obj->getAttribute('atk_fisico_calc'), 100);
						$_atk['atk_magico'] += percent($obj->getAttribute('atk_magico_calc'), 100);
					}

					if($_SESSION['universal']) {
						//echo "ATKM: " . $_atk['atk_magico'] . " / " . $obj->getAttribute('atk_magico_calc') . "<br />";
					}

					$$isdef	= false;

					// Enhancement
					//$$def	= $$pdef;
					//$$def	+= $$atki->def_base;
				} else { // Item defensivo
					$_atk	= array('atk_fisico' => 0, 'atk_magico' => 0);
					$$isdef	= true;

					if(!$$err) {
						$d	=& $$def;

						$d['atk_fisico']	+= $$atki->def_base + $$atki->def_fisico;
						$d['atk_magico']	+= $$atki->def_base + $$atki->def_magico;
					}
				}

				if($$atki->skipping) { // Passando a vez ?
					$_atk = array('atk_fisico' => 0, 'atk_magico' => 0);
					$$def = 0;
				} elseif ($$atki->flight) { // Fuga ?
					$_atk		= array('atk_fisico' => 0, 'atk_magico' => 0);
					$d					=& $$def;
					$d['atk_fisico']	/= 8;
					$d['atk_magico']	/= 8;
					$$flight	= true;
					$$flightid	= $obj->id;
				}

				// Gastos de atributos --->
					if(isset($obj->npc_evento) && $obj->npc_evento && $pIsFlight) {
						continue;
					}

					if($$atki->consume_hp) {
						$obj->consumeHP($$atki->consume_hp);
					}

					if($$atki->consume_sp) {
						$obj->consumeSP($$atki->consume_sp);
					}

					if($$atki->consume_sta) {
						$obj->consumeSTA($$atki->consume_sta);
					}
				// <---

				// Gasta os itens(tipo a shuriken por exemplo) --->
					if($$atki->getAttribute('uso_unico')) {
						$obj->removeItem($$atki);
					}
				// <---
			}
			// <---

			// Rejustes de valores pela regra de elementos --->

				$pATK['atk_magico']	*= $pDoubleA;
				$pATK['atk_fisico']	*= $pDoubleA;

				$eATK['atk_magico']	*= $eDoubleA;
				$eATK['atk_fisico']	*= $eDoubleA;
				/*$pATK['atk_magico']	*= $pDoubleA;
				$pATK['atk_fisico']	*= $pDoubleA;

				//$pDEF[$baseF]				*= $pDoubleD;

				$eATK['atk_magico']	*= $eDoubleA;
				$eATK['atk_fisico']	*= $eDoubleA;*/

				//$eDEF[$baseF]				*= $eDoubleD;

				//echo " ATK -> P: $pDoubleA / E: $eDoubleA<br/>";
				//echo " DEF -> P: $pDoubleD / E: $eDoubleD<br/>";


				//$pATK['atk_magico']  *= ($pDoubleA ? 1.5 : 1);
				//$pATK['atk_fisico']  *= ($pDoubleA ? 1.5 : 1);



				//$pDEF *= ($pDoubleD ? 1.5 : 1);

				//$eATK['atk_magico']  *= ($eDoubleA ? 1.5 : 1);
				//$eATK['atk_fisico']  *= ($eDoubleA ? 1.5 : 1);

				//$eDEF *= ($eDoubleD ? 1.5 : 1);

			// <---

			// Sistema de criticos, magia negra e satanismo --->
			for($f = 0; $f <= 1; $f++) {
				if($f == 0) {
					$atk	= 'pATK';
					$def	= 'pDEF';
					$atki	= 'pAtkItem';
					$err	= 'pERR';
					$crit	= 'pCRI';
					$esq	= 'pESQ';
					$isdef	= 'eIsDef';
					$esquiva = 'pESQUIVA';	

					$obj	=& $this->_player;
					$objb	=& $this->_enemy;
				} else {
					$atk	= 'eATK';
					$def	= 'eDEF';
					$atki	= 'eAtkItem';
					$err	= 'eERR';
					$crit	= 'eCRI';
					$esq	= 'eESQ';
					$esquiva = 'eESQUIVA';
					$isdef	= 'pIsDef';

					$obj	=& $this->_enemy;
					$objb	=& $this->_player;
				}

				$_atk	=& $$atk;
				// Golpe Esquivado ( Ignora ataque )
				if(!$$isdef) {
					$chance = frand(1, 100);
					
					if($chance <= $obj->getAttribute('esquiva_calc') && $this->hasEsquivaPoint($obj)) {
						$$esquiva  = true;
						$this->useEsquivaPoint($obj);
					}
				}

				// Erro do golpe --->
				/*
				if($$atki->getAttribute('precisao') < 100) {
					$chance = rand(1, 100);

					if($chance > $$atki->getAttribute('precisao')) {
						$$err = true;
					}
				}
				*/
				// <---

				//echo PHP_EOL . '/*' . PHP_EOL;
				//echo '-- ENTRY' . get_class($obj);

				// Critico (precisão 100+, level liberado e somente se não errar o golpe) --->
				if(!$$err && $$atki->getAttribute('level_liberado') && $$atki->getAttribute('precisao') >= 100 && $this->hasCritPoint($obj)) {
					//$cmin	= $obj->getAttribute('crit_min_calc') > 100 ? 100 : $obj->getAttribute('crit_min_calc');
					//$cmax	= $obj->getAttribute('crit_max_calc') > 100 ? 100 : $obj->getAttribute('crit_max_calc');
					$ctotal	= $obj->getAttribute('crit_total_calc') > 100 ? 100 : $obj->getAttribute('crit_total_calc');

					$chance = frand(1, 100); //frand(frand($cmin, $cmax), 100);
					//$chance = frand(1, (100 - frand($cmin, $cmax)));


					/*if($_SESSION['universal']) {
						echo '-- CONC-> ' . $obj->getAttribute('conc_calc') . PHP_EOL;
						echo '-- CHANCE-> ' . $chance . PHP_EOL;
					}*/


					// Critico
					//if($chance <= $cmax) {
					//echo '-- CRIT (' . $chance . ' <= ' . $obj->getAttribute('conc_calc') . ' + ' . $$atki->crit_inc . ')-> ';

					if($chance <= $obj->getAttribute('conc_calc') /*+ $$atki->crit_inc*/) {
						$inc	= $ctotal;
						$$crit	= true;

						if($$atki->getAttribute('defensivo')) { // Defesa critica
							$$def	+= ceil(percent($$def, $inc));
						} else { // Ataque crítico
							$_atk['atk_fisico']	+= ceil(percent($_atk['atk_fisico'], $inc));
							$_atk['atk_magico']	+= ceil(percent($_atk['atk_magico'], $inc));
						}

						$this->useCritPoint($obj);
					}
				}
				// <--

				//echo '-- ESQC-> ' . $this->hasEsqPoint($obj, true) . '[' . (int)$this->hasEsqPoint($obj) . ']' . PHP_EOL;

				// Esquiva se não deu erro no golpe(exceto de o inimigo usou defensivo) --->
				if(!$$err && $this->hasEsqPoint($obj) && !$$isdef) {
					
					// Código Inserido pelo Cá //
					//$emin	= $obj->getAttribute('esq_min_calc') > 100 ? 100 : $obj->getAttribute('esq_min_calc');
					//$emax	= $obj->getAttribute('esq_max_calc') > 100 ? 100 : $obj->getAttribute('esq_max_calc');
					$etotal	= $obj->getAttribute('esq_total_calc') > 100 ? 100 : $obj->getAttribute('esq_total_calc');
					// Código Inserido pelo Cá //

					$chance	= rand(1, 100);

					//echo '-- ESQ-> ' . $chance . ' --> ' . $obj->getAttribute('esq_calc') . PHP_EOL;

					if($chance <= $obj->getAttribute('esq_calc')) {

						//Código Inserido pelo Cá
						$dec	= $etotal;
						// Código Inserido pelo Cá //

						/*
						if($_SESSION['universal']) {
							echo '-- ESQ-> ' . $obj->getAttribute('esq_calc') . PHP_EOL;
							echo '-- CHANCE-> ' . $chance . PHP_EOL;
							echo '-- AMORTIZOU '. $dec . PHP_EOL;
						}
						*/

						$$esq	= true;

						$this->useEsqPoint($obj);
					}
				}
				// <---

				// Determinação nova, qualquer player abaixo de 50% ganha
				if($obj->getAttribute('hp') <= percent($obj->getAttribute('max_hp'), 50)) {
					$modifiers		= $obj->getModifiers();
					$has_modifier	= false;

					// Verifica se ja existe o buff applicado, se sim, ignora
					foreach($modifiers as $modifier) {
						if($modifier['conv']) {
							$has_modifier = true;
						}
					}

					if(!$has_modifier) {
						$vila			= $obj->getAttribute('id_vila') ? $obj->getAttribute('id_vila') : $objb->getAttribute('id_vila');
						$buff			= Recordset::query('SELECT SQL_NO_CACHE id FROM item WHERE id_tipo=32 AND id_vila_reputacao=' . $vila . ' ORDER BY RAND()')->row_array();
						$buff			= new Item($buff['id']);
						$buff_source	= NULL;

						$buff->det_inc 	= $obj->getAttribute('det_calc');

						/*
						if($obj->id == 5) {
							$buff['id'] = 21362;
						}

						if($obj->id == 2) {
							$buff['id'] = 21361;
						}
						*/

						$obj->addModifier($buff, 1, 0, $buff_source, 0);
					}
				}
			}
			// <---

			for($f = 0; $f <= 1; $f++) {
				if($f == 0) {
					$atk	= 'pATK';
					$atkb	= 'eATK';
					$def	= 'pDEF';
					$defb	= 'eDEF';
					$atki	= 'pAtkItem';
					$err	= 'pERR';
					$basef	= 'pBaseF';
					$crit	= 'pCRI';
					$esq	= 'eESQ';
					$esquiva = 'eESQUIVA';
					$isdef	= 'pIsDef';

					$obj	=& $this->_player;
					$objb	=& $this->_enemy;
				} else {
					$atk	= 'eATK';
					$atkb	= 'pATK';
					$def	= 'eDEF';
					$defb	= 'pDEF';
					$atki	= 'eAtkItem';
					$err	= 'eERR';
					$basef	= 'eBaseF';
					$crit	= 'eCRI';
					$esq	= 'pESQ';
					$esquiva = 'pESQUIVA';
					$isdef	= 'eIsDef';

					$obj	=& $this->_enemy;
					$objb	=& $this->_player;
				}

				if($$atki->skipping) { // Passar vez
					echo '<b class="pvp_p_name">' . $obj->getAttribute('nome') . '</b> <span class="laranja">'.t('fight.f7').'</span></b>';
				} elseif($$atki->flight) { // Fugir
					echo '<b class="pvp_p_name">' . $obj->getAttribute('nome') . '</b> <span class="laranja">'.t('fight.f8').'</span></b><br />';

					$has_flight = false;

					if(is_a($objb, 'NPC')) {
						$has_flight = true;
					} else {
						if($_SESSION['universal']) {
							$chance	= 1;
						} else {
							$chance	= rand(1, 100);
						}

						if($chance <= 5) {
							$has_flight = true;

							echo '<b class="pvp_p_name">' . $obj->getAttribute('nome') . '</b> <span class="verde">'.t('fight.f9').'</span><br />';
						} else {
							echo '<b class="pvp_p_name">' . $obj->getAttribute('nome') . '</b> <span class="laranja">'.t('fight.f10').'</span><br />';
						}
					}

					if($has_flight) {
						$this->flight	= true;
						$this->flightId	= $obj->id;

						$this->log = ob_get_clean();
						@ob_end_clean();

						return true;
					}
				} else { // Ataque normal
					$msg = '';
					
					if($$esquiva) {	
						$msg .= '<br><b class="pvp_p_name">' . $objb->getAttribute('nome') . '</b> <span class="pvp_amortizacao">Esquiva</span> com sucesso o ataque</span><br />';
					}
					if($$crit) {
						if($$atki->getAttribute('defensivo')) {
							$msg .= '<span class="pvp_criticoD">'.t('fight.f11').'</span>';
						} else {
							$msg .= '<span class="pvp_criticoA">'.t('fight.f12').'</span>';
						}
					}

					if($$esq && !$$esquiva) {
						if($$atki->getAttribute('defensivo')) {
							//$msg .= '<span class="pvp_criticoD">Defesa Crítica</span>';
						} else {
							$msg .= '<span class="pvp_esquiva">'.t('fight.f15').'</span>';
						}
					}

					$atk_id	= 'atk-' . uniqid(uniqid());
					$msg	.= '<script>__dyn_atk["' . $atk_id . '"] = ' . painel_pvp_item_js($$atki) . '</script>';

					echo '<b class="pvp_p_name">' . $obj->getAttribute('nome') . '</b> <span style="color: #266fa3">'.t('fight.f13').'</span> <span class="pvp_p_atk" data-atk="' . $atk_id . '">' . htmlspecialchars($$atki->getAttribute('nome')) . '</span>' . $msg . '<br />';

					if($$err) {
						echo '<b class="pvp_p_name">' . $obj->getAttribute('nome') . '</b> <span class="laranja">'.t('fight.f14').'</span><br />';
					}

					if(!$$esquiva && !$$isdef && !$$err) {
						$resultATK = $this->_processATK($obj, $objb, $$atk, $$def, $$atkb, $$defb, $$basef, $$esq ? $dec : 0, false, false, $$esquiva);

						/*if($_SESSION['universal']) {
							echo "<pre>";

							echo "pATK: " . json_encode($$atk) . "<br />pDEF: {$$def}<br />eATK: " . json_encode($$atkb) . "<br />eDEF: {$$defb}";

							echo "</pre>";
						}*/
					}
				}
			}


			/*

			// Debug --->
				if($_SESSION['universal']) {
					echo "pIsDef:" . (int)$pIsDef . "<br />";
					echo "pIsFlight:" . (int)$pIsFlight . "<br />";
					echo "eIsDef:" . (int)$eIsDef . "<br />";
					echo "eIsFlight:" . (int)$eIsFlight . "<br />";

					echo "<pre>" .
						 "<b style='color:#fff'>Player (" . get_class($this->_player) . ")</b> --->\n" .
						 "PRECISÃO   => CHANCE: $pERRV% / Nº Sorteado: $pERRC\n" .
						 "CRITICO => CHANCE: $pCRITV% / Nº Sorteado: $pCRITC / Incr. de dano: ". $pCRITV/2 ."% ~ 50%) \n" .

						 "<---</pre>";

					echo "<pre>" .
						 "<b style='color:#fff'>Inimigo (" . get_class($this->_enemy) . ")</b> --->\n" .
						 "PRECISÃO   => CHANCE: $eERRV% / Nº Sorteado: $eERRC\n" .
						 "CRITICO => CHANCE: $eCRITV% / Nº Sorteado: $eCRITC / Incr. de dano: ". $eCRITV/2 ."% ~ 50%) \n" .


						 "<---</pre>";

					echo "<pre>" .
						"Player BASE_F: $pAtkItem->BASE_F {F:" . $this->_player->ATK_FISICO . " /M: " . $this->_player->ATK_MAGICO . " } => {F:" . $pAtkItem->ATK_FISICO . " /M: " . $pAtkItem->ATK_MAGICO . "}\n" .
						print_r($pATK, true) .
						"Defesa base: ". print_r($pDEF, true) .
						"</pre>";

					echo "<pre>" .
						"Inimigo BASE_F: $eAtkItem->BASE_F {F:" . $this->_enemy->ATK_FISICO . " /M: " . $this->_enemy->ATK_MAGICO . " } => {F:" . $eAtkItem->ATK_FISICO . " /M: " . $eAtkItem->ATK_MAGICO . "}\n" .
						print_r($eATK, true) .
						"Defesa base: ". print_r($eDEF, true);

					$keya = '_BTCH' . ($this->is4 ? '4_' : '') . '_' . $this->id . '_' . $this->_player->id;
					$keyb = '_BTCH' . ($this->is4 ? '4_' : '') . '_' . $this->id . '_' . $this->_enemy->id;

					echo '<br /><br />';
					echo 'P_CRITP [' . $keya . ']: ' . $this->_player->MAX_CRIT_HITS . ' -> ' . $this->hasCritPoint($this->_player, true);
					echo '<br />';
					echo 'E_CRITP [' . $keyb . ']: ' . $this->_enemy->MAX_CRIT_HITS . ' -> ' . $this->hasCritPoint($this->_enemy, true);
					echo '<br />';

					echo "<br />";

					echo "</pre>";
				}
			// <---
			*/

			$this->log = ob_get_clean();
			$this->log .= '<hr />';

			@ob_end_clean();
		}
	}
?>
