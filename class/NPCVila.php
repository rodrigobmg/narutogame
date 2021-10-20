<?php
	class NPCVila {
		private $items = "";
		public $nome_graduacao;
		public $npc = true;

		private $less_sp = 0;
		private $less_hp = 0;
		private $less_sta = 0;	
	
		function __construct($id_vila, $mlocal) {
			$this->items = array();
			$this->buffs = array();

			$qEnemy = Recordset::query("SELECT * FROM npc_vila WHERE mlocal=" . $mlocal . " AND id_vila=" . $id_vila);
			$rEnemy = $qEnemy->row_array();

			$this->id = $rEnemy['id'];
			$this->name = $rEnemy['nome'];
			$this->ryou = 0;
			$this->exp = 0;
			$this->npc_vila = 1;
			$this->id_vila = $rEnemy['id_vila'];
			$this->mlocal = $rEnemy['mlocal'];

			$this->MAX_HP = $this->HP = $rEnemy['hp'];
			$this->MAX_SP = $this->SP = $rEnemy['sp'];
			$this->MAX_STA = $this->STA = $rEnemy['sta'];

			$this->H_HP = $rEnemy['hp'];
			$this->H_SP = $rEnemy['sp'];
			$this->H_STA = $rEnemy['sta'];

			$this->level = 60;

			// Somatoria dos bonus dos itens --->
				$qItems = Recordset::query("
					SELECT * FROM (SELECT id, id_tipo FROM item WHERE id_tipo = 1 AND req_level <= ({$this->level} + 10) ORDER BY req_level DESC LIMIT 2 ) AS a
					UNION
					SELECT * FROM (SELECT id, id_tipo FROM item WHERE id_tipo = 2 AND req_level <= ({$this->level} + 10) ORDER BY req_level DESC LIMIT 2 ) AS b
					UNION
					SELECT * FROM (SELECT id, id_tipo FROM item WHERE id_tipo = 3 AND req_level <= ({$this->level} + 10) ORDER BY req_level DESC LIMIT 1 ) AS c
					UNION
					SELECT * FROM (SELECT id, id_tipo FROM item WHERE id_tipo = 5 AND req_level <= ({$this->level} + 10) ORDER BY req_level DESC LIMIT 100 ) AS d
					UNION
					SELECT * FROM (SELECT id, id_tipo FROM item WHERE id_tipo = 6 AND req_level <= ({$this->level} + 10) AND id NOT IN(4, 5, 6, 7) ORDER BY req_level DESC LIMIT 2 ) AS e
				");

				while($item = $qItems->row_array()) {
					$this->items[] = $item['id'];
				}
			// <---
			
			// Rejusta os multiplicadores --->
				$this->TAI = $this->TAI_P = $rEnemy['tai'];
				$this->KEN = $this->KEN_P = $rEnemy['ken'];
				$this->NIN = $this->NIN_P = $rEnemy['nin'];
				$this->GEN = $this->GEN_P = $rEnemy['gen'];
				$this->AGI = $this->AGI_P = $rEnemy['agi'];
				$this->ENE = $this->ENE_P = $rEnemy['ene'];
				$this->CON = $this->CON_P = $rEnemy['con'];
				$this->INT = $this->INT_P = $rEnemy['inte'];
				$this->FOR = $this->FOR_P = $rEnemy['forc'];
				$this->RES = $this->RES_P = $rEnemy['res'];
			// <---
			
			$this->less_hp = $rEnemy['less_hp'];
			$this->less_sp = $rEnemy['less_sp'];
			$this->less_sta = $rEnemy['less_sta'];
			
			$this->atCalc(true);
		}
		
		function getATKItem() {
			$x = 0;

			while(true) {
				$noUse = false;
				$i = new Item($this->items[( round(rand(0, sizeof($this->items) - 1)) )]);

				$i->HP_CONSUME  = ceil($i->HP_CONSUME  / 1.5);
				$i->SP_CONSUME  = ceil($i->SP_CONSUME  / 1.5);
				$i->STA_CONSUME = ceil($i->STA_CONSUME / 1.5);
				
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
				
				if(++$x == 250) {
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

			Recordset::query("UPDATE npc_vila  SET less_hp = less_hp + " . $value . " WHERE id=" . $this->id);

			$this->less_hp += $value;
		}
		
		function consumeSP($value) {
			$this->SP -= $value;

			Recordset::query("UPDATE npc_vila  SET less_sp = less_sp + " . $value . " WHERE id=" . $this->id);

			$this->less_sp += $value;
		}

		function consumeSTA($value) {
			$this->STA -= $value;
			
			Recordset::query("UPDATE npc_vila  SET less_sta = less_sta + " . $value . " WHERE id=" . $this->id);
			
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
			// Trata os genjutsus --->
				$buffs = $this->getBuffs();
			
				/*
				$this->NIN -= (int)$buffs['nin'];
				$this->GEN -= (int)$buffs['gen'];
				$this->TAI -= (int)$buffs['tai'];
				$this->AGI -= (int)$buffs['agi'];
				$this->CON -= (int)$buffs['con'];
				*/
			// <---
			
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
		
		function kai() {
			
		}

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
			$this->CRIT      = 0; // Critico
			$this->ESQ       = 0; // Esquiva
			$this->DEF_EXTRA = 0; // Defesa extra			
		}

		function atCalc($mxRecalc = false) {
			
			// Calculos --->
			$this->CH_DEF_BASE = round(($this->RES) / 2 + ($this->AGI + $this->CON) / 6); 
			$this->ATK_MAGICO = round(($this->INT) / 2 + ($this->CON) / 6);
			$this->ATK_FISICO = round( ($this->FOR) / 2 + ($this->AGI) / 6);
			// <---	
			
			
			/*
			$this->CH_DEF_BASE = round($this->RES);
			$this->ATK_MAGICO = round($this->INT);
			$this->ATK_FISICO = round($this->FOR);
			*/	

			// Aplica os valores dos modificadores --->
				$this->ATK_MAGICO  += $this->ATK_F;
				$this->ATK_FISICO  += $this->ATK_M;
				$this->CH_DEF_BASE += $this->DEF_BASE;
				

			// <---
			
			$this->HP = $this->H_HP;
			$this->SP = $this->H_SP;
			$this->STA = $this->H_STA;
		
			$this->HP -= $this->less_hp;
			$this->SP -= $this->less_sp;
			$this->STA -= $this->less_sta;
			
		}		
	}
