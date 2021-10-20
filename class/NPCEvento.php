<?php
	class NPCEvento {
		public $nome_graduacao;
		public $npc = true;
		public $npc_evento = true;

		private $less_sp = 0;
		private $less_hp = 0;
		private $less_sta = 0;	

		public $items = array();
		public $buffs = array();
	
		function __construct($id, &$player, $id_evento) {
			$grad = $player->id_graduacao == 1 ? 2 : $player->id_graduacao;

			$qEnemy = Recordset::query("SELECT * FROM evento_npc WHERE id=" . $id);
			$rEnemy = $qEnemy->row_array();

			$npc_info = Recordset::query("
			SELECT
				id,
				nome,
				level,
				exp,
				ryou,
				multi,
				id_classe_tipo
			
			FROM 
				npc
			WHERE
				id=$id
			ORDER BY RAND()")->row_array();

			$npc_at = Recordset::query('
			SELECT 
				* 
			
			FROM 
				npc_base 
			
			WHERE 
				' . $player->level . ' BETWEEN lvl_s AND lvl_e 
				 ORDER BY RAND() LIMIT 1')->row_array();
			
			/*Gambis do evento de pasco*/
			if($id_evento==86){
				$this->TAI = $this->TAI_P = 2;
				$this->KEN = $this->KEN_P = 2;
				$this->NIN = $this->NIN_P = 2;
				$this->GEN = $this->NIN_P = 2;
				$this->AGI = $this->AGI_P = 2;
				$this->ENE = $this->ENE_P = 2;
				$this->CON = $this->CON_P = 2;
				$this->INT = $this->INT_P = 2;
				$this->FOR = $this->FOR_P = 2;
				$this->RES = $this->RES_P = 2;
			}else{
				if($grad < 4){	
					$this->TAI = $this->TAI_P = $npc_at['tai']  * 16;
					$this->KEN = $this->KEN_P = $npc_at['ken']  * 16;
					$this->NIN = $this->NIN_P = $npc_at['nin']  * 16;
					$this->GEN = $this->NIN_P = $npc_at['gen']  * 16;
					$this->AGI = $this->AGI_P = $npc_at['agi']  * 1;
					$this->ENE = $this->ENE_P = $npc_at['ene']  * 1;
					$this->CON = $this->CON_P = $npc_at['con']  * 1;
					$this->INT = $this->INT_P = $npc_at['inte'] * 1;
					$this->FOR = $this->FOR_P = $npc_at['forc'] * 1;
					$this->RES = $this->RES_P = $npc_at['res']  * 1;
				}else if($grad == 4){
					$this->TAI = $this->TAI_P = $npc_at['tai']  * 24;
					$this->KEN = $this->KEN_P = $npc_at['ken']  * 24;
					$this->NIN = $this->NIN_P = $npc_at['nin']  * 24;
					$this->GEN = $this->NIN_P = $npc_at['gen']  * 24;
					$this->AGI = $this->AGI_P = $npc_at['agi']  * 2;
					$this->ENE = $this->ENE_P = $npc_at['ene']  * 2;
					$this->CON = $this->CON_P = $npc_at['con']  * 2;
					$this->INT = $this->INT_P = $npc_at['inte'] * 2;
					$this->FOR = $this->FOR_P = $npc_at['forc'] * 2;
					$this->RES = $this->RES_P = $npc_at['res']  * 2;
				}else if($grad == 5){
					$this->TAI = $this->TAI_P = $npc_at['tai']  * 28;
					$this->KEN = $this->KEN_P = $npc_at['ken']  * 28;
					$this->NIN = $this->NIN_P = $npc_at['nin']  * 28;
					$this->GEN = $this->NIN_P = $npc_at['gen']  * 28;
					$this->AGI = $this->AGI_P = $npc_at['agi']  * 3;
					$this->ENE = $this->ENE_P = $npc_at['ene']  * 3;
					$this->CON = $this->CON_P = $npc_at['con']  * 3;
					$this->INT = $this->INT_P = $npc_at['inte'] * 3;
					$this->FOR = $this->FOR_P = $npc_at['forc'] * 3;
					$this->RES = $this->RES_P = $npc_at['res']  * 3;
				}else if($grad == 6){
					$this->TAI = $this->TAI_P = $npc_at['tai']  * 32;
					$this->KEN = $this->KEN_P = $npc_at['ken']  * 32;
					$this->NIN = $this->NIN_P = $npc_at['nin']  * 32;
					$this->GEN = $this->NIN_P = $npc_at['gen']  * 32;
					$this->AGI = $this->AGI_P = $npc_at['agi']  * 4;
					$this->ENE = $this->ENE_P = $npc_at['ene']  * 4;
					$this->CON = $this->CON_P = $npc_at['con']  * 4;
					$this->INT = $this->INT_P = $npc_at['inte'] * 4;
					$this->FOR = $this->FOR_P = $npc_at['forc'] * 4;
					$this->RES = $this->RES_P = $npc_at['res']  * 4;
				}else if($grad == 7){
					$this->TAI = $this->TAI_P = $npc_at['tai']  * 36;
					$this->KEN = $this->KEN_P = $npc_at['ken']  * 36;
					$this->NIN = $this->NIN_P = $npc_at['nin']  * 36;
					$this->GEN = $this->NIN_P = $npc_at['gen']  * 36;
					$this->AGI = $this->AGI_P = $npc_at['agi']  * 5;
					$this->ENE = $this->ENE_P = $npc_at['ene']  * 5;
					$this->CON = $this->CON_P = $npc_at['con']  * 5;
					$this->INT = $this->INT_P = $npc_at['inte'] * 5;
					$this->FOR = $this->FOR_P = $npc_at['forc'] * 5;
					$this->RES = $this->RES_P = $npc_at['res']  * 5;
				}									
			}

			$items = Recordset::query('SELECT id_npc_base_item FROM npc_base_item WHERE id_npc_base=' . $npc_at['id']);
			$ai_level = null;

			if(!$ai_level) {
				switch($npc_at['dificuldade']) {
					case 'normal':	$ai_level = 0; break;
					case 'hard':	$ai_level = 1; break;
					case 'ogro':	$ai_level = 2; break;
				}
			}			
			
			foreach($items->result_array() as $item) {
				$it = new Item($item['id_npc_base_item']);
				
				$it->HP_CONSUME  = ceil($it->HP_CONSUME  / 1.5);
				$it->SP_CONSUME  = ceil($it->SP_CONSUME  / 1.5);
				$it->STA_CONSUME = ceil($it->STA_CONSUME / 1.5);
				
				if($ai_level == 1) { // NPC HARD
					$opt = rand(1, 2);
				} elseif($ai_level == 2) { // NPC OGRO
					$opt = 2;
				} else { // NPC NORMAL e de qualquer outra parte do jogo
					$opt = rand(0, 2);
				}
				
				switch($opt) {
					case 2: // Golpe critico
						$p = new stdClass();
						$p->AGI = $it->req_agi * 200;
						$p->CON = $it->req_con * 200;
						
						$it->setPlayerInstance($p);
						
						break;
					
					case 1: // Golpe com acerto, sem critico
						$p = new stdClass();
						$p->AGI = $it->req_agi;
						$p->CON = $it->req_con;
						
						$it->setPlayerInstance($p);
					
						break;
					
					case 0: // Golpe com erro
						$p = new stdClass();
						$p->AGI = $it->req_agi - percent($it->req_agi, 20);
						$p->CON = $it->req_con - percent($it->req_con, 20);
						
						$it->setPlayerInstance($p);
					
						break;
				}

				$this->items[] = $it;
			}
			
			$this->id = $rEnemy['id'];
			$this->id_player = $player->id;
			$this->id_evento = $id_evento;
			$this->name = $rEnemy['nome'];
			$this->ryou = 0;
			$this->exp = 0;
			$this->npc_evento = 1;
			
			$rLess = Recordset::query("SELECT less_sp, less_sta, less_hp FROM evento_npc_player WHERE id_evento=" . $this->id_evento . " AND id_player=" . $this->id_player . " AND id_evento_npc=" . $this->id)->row_array();
			
			$this->less_hp  = (int)$rLess['less_hp'];
			$this->less_sp  = (int)$rLess['less_sp'];
			$this->less_sta = (int)$rLess['less_sta'];

			$this->atCalc();
		}
		
		function getATKItem() {
			$x = 0;

			while(true) {
				$noUse = false;
				$i = $this->items[( round(rand(0, sizeof($this->items) - 1)) )];

				if($i->HP_CONSUME > absm($this->HP)) {
					$noUse = true;
				}

				if($i->SP_CONSUME > absm($this->SP)) {
					$noUse = true;
				}

				if($i->STA_CONSUME > absm($this->STA)) {
					$noUse = true;
				}
				
				if(!$noUse) {
					return $i;
					break;
				}
				
				if(++$x === 250) {
					$nullObj = new stdClass();
					$nullObj->skipping = true;
					
					return $nullObj;
					break;
				}
			}
		}
		
		function removeItem($item) { }
		
		function consumeHP($value) {
			$this->HP -= $value;

			Recordset::query("UPDATE evento_npc_player SET less_hp = less_hp + " . $value . " WHERE id_player=" . $this->id_player . " AND id_evento_npc=" . $this->id . " AND id_evento=" . $this->id_evento);

			$this->less_hp += $value;
		}
		
		function consumeSP($value) {
			$this->SP -= $value;

			Recordset::query("UPDATE evento_npc_player SET less_sp = less_sp + " . $value . " WHERE id_player=" . $this->id_player . " AND id_evento_npc=" . $this->id . " AND id_evento=" . $this->id_evento);

			$this->less_sp += $value;
		}

		function consumeSTA($value) {
			$this->STA -= $value;
			
			Recordset::query("UPDATE evento_npc_player SET less_sta = less_sta + " . $value . " WHERE id_player=" . $this->id_player . " AND id_evento_npc=" . $this->id . " AND id_evento=" . $this->id_evento);
			
			$this->less_sta += $value;
		}
		
		function addBuff($i) {
			$this->buffs[] = $i;
		}
		
		function getBuffs() {
			$ra = array();
			foreach($this->buffs as $b) {
				$ra['gen'] += $b->GEN;
				$ra['tai'] += $b->TAI;
				$ra['ken'] += $b->KEN;
				$ra['nin'] += $b->NIN;
				$ra['agi'] += $b->AGI;
				$ra['con'] += $b->CON;
			}
			
			return $ra;
		}
		
		function updateBuffs() {
			$buffs = $this->getBuffs();
			
			// Ajustes dos negativos -->
				$this->NIN = absm($this->NIN);
				$this->GEN = absm($this->GEN);
				$this->TAI = absm($this->TAI);
				$this->KEN = absm($this->KEN);
				$this->AGI = absm($this->AGI);
				$this->CON = absm($this->CON);
			// <---

			foreach($this->buffs as $k => $b) {
				$b->turnos--;
				
				if($b->turnos == 0) {
					unset($this->buffs[$k]);
				}
			}
		}
		
		function clearBuffs() {
			$this->buffs = array();
		}
		
		function kai() { }

		function atRestore() {
			$this->TAI = $this->TAI_P;
			$this->KEN = $this->KEN_P;
			$this->NIN = $this->NIN_P;
			$this->GEN = $this->NIN_P;
			$this->AGI = $this->AGI_P;
			$this->ENE = $this->ENE_P;
			$this->CON = $this->CON_P;
			$this->INT = $this->INT_P;
			$this->FOR = $this->FOR_P;
			$this->RES = $this->RES_P;

			$this->ATK_F     = 0;
			$this->ATK_M     = 0;
			$this->DEF_BASE  = 0;
				
		}
		
		function atCalc($less = true) {
			// Calculos de HP/SP e STA --->
				$this->HP = ($this->ENE * 8);
				$this->SP =  (($this->ENE * 8) + ($this->NIN + $this->GEN) * 8);
				$this->SP =  (($this->ENE * 8) + ($this->TAI) * 8);
				
				/*
				if($_GET['debug']) {
					echo "HP: " . $this->HP . "<br />";
					echo "SP: " . $this->SP . "<br />";
					echo "STA: " . $this->STA . "<br />-------------<br />";
				}
				*/
				
				// Necessário porcausa do fdp do carlos e buceta da pagina de status -->
					$this->HP_ESP_PC  = percent($this->HP,  $this->HP_ESP_P);
					$this->SP_ESP_PC  = percent($this->SP,  $this->SP_ESP_P);
					$this->STA_ESP_PC = percent($this->STA, $this->STA_ESP_P);
				// <---
					
				$this->HP  += percent($this->HP,  $this->HP_ESP_P);
				$this->SP  += percent($this->SP,  $this->SP_ESP_P);
				$this->STA += percent($this->STA, $this->STA_ESP_P);
				
				/*
				if($_GET['debug']) {
					echo "HP: " . $this->HP_ESP_P . "<br />";
					echo "SP: " . $this->SP_ESP_P . "<br />";
					echo "STA: " . $this->STA_ESP_P . "<br />-------------<br />";
				}
				*/
				
				$this->HP  += $this->HP_IT  + $this->HP_ESP;
				$this->SP  += $this->SP_IT  + $this->SP_ESP;
				$this->STA += $this->STA_IT + $this->STA_ESP;
				
				/*
				if($_GET['debug']) {
					echo "HP: " . $this->HP_ESP . "<br />";
					echo "SP: " . $this->SP_ESP . "<br />";
					echo "STA: " . $this->STA_ESP . "<br />-------------<br />";
				}
				*/
							// <---	

			/*$this->CH_DEF_BASE = round($this->RES); // Defesa base

			$this->ATK_MAGICO = round($this->INT);
			$this->ATK_FISICO = round($this->FOR);*/
			
			/* Formulas Velhas 
			$this->CH_DEF_BASE = round(($this->RES) / 2 + ($this->AGI + $this->CON) / 6); 
			$this->ATK_MAGICO = round(($this->INT) / 2 + ($this->CON) / 6);
			$this->ATK_FISICO = round( ($this->FOR) / 2 + ($this->AGI) / 6);
			
			*/
			
			// Novas Formulas
			$this->CH_DEF_BASE = round(($this->RES + $this->TAI + $this->NIN + $this->GEN + $this->KEN) / 4); 
			$this->ATK_MAGICO = round(($this->NIN + $this->GEN) / 2 + ($this->INT + $this->CON) / 4);
			$this->ATK_FISICO = round( ($this->TAI + $this->KEN) / 2 + ($this->AGI + $this->FOR) / 4);
			
			// Aplica os valores dos modificadores --->
				$this->CH_DEF_BASE += $this->DEF_BASE; // Def base

				$this->ATK_MAGICO  += $this->ATK_M;
				$this->ATK_FISICO  += $this->ATK_F;				
			// <---

			// Bonus diretos de modificadores --->
				$this->HP  += $this->INC_MAX_HP;
				$this->SP  += $this->INC_MAX_SP;
				$this->STA += $this->INC_MAX_STA;
			// <--
			
			$this->MAX_HP = $this->HP;
			$this->MAX_SP = $this->SP;
			$this->MAX_STA = $this->STA;
			
			$this->HP -= $this->less_hp;
			$this->SP -= $this->less_sp;
			$this->STA -= $this->less_sta;
		}
		
		function setBatalha($lock) {
			Recordset::query("UPDATE evento_npc_player SET batalha=" . ($lock ? 1 : 0) . " WHERE id_player=" . $this->id_player . " AND id_evento_npc=" . $this->id . " AND id_evento=" . $this->id_evento);
		}
		
		function getBatalha() {
			$r = Recordset::query("SELECT batalha FROM evento_npc_player WHERE id_player=" . $this->id_player . " AND id_evento_npc=" . $this->id . " AND id_evento=" . $this->id_evento)->row_array();
			
			return $r['batalha'];
		}
		
		function Loss() {
			Recordset::query("UPDATE evento_npc_player SET morto=1 WHERE id_player=" . $this->id_player	 . " AND id_evento_npc=" . $this->id . " AND id_evento=" . $this->id_evento);
		}
	}
