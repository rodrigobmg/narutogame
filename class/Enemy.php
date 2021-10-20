<?php
	class Enemy {
		static $level_inc;
		
		private $less_sp = 0;
		private $less_hp = 0;
		private $less_sta = 0;

		public $items = array();
		public $buffs = array();
		public $npcb = true;
	
		function __construct(&$player, $ai_level = NULL) {
			$grad = $player->id_graduacao == 1 ? 2 : $player->id_graduacao;
			
			$npc_info = Recordset::query("
			SELECT
				b.id AS id_classe,
				b.nome AS nome_classe,
				b.nome AS nome,
				b.id_vila,
				b.imagem
			
			FROM 
				classe b
			WHERE b.ativo=1 ORDER BY RAND() LIMIT 1" )->row_array();

			$npc_at = Recordset::query('
			SELECT 
				* 
			
			FROM 
				npc_base 
			
			WHERE 
				' . $player->level . ' BETWEEN lvl_s AND lvl_e ORDER BY RAND() LIMIT 1')->row_array(); //AND id_graduacao=' . $grad . '

			$this->TAI = $this->TAI_P = $npc_at['tai'];
			$this->KEN = $this->KEN_P = $npc_at['ken'];
			$this->NIN = $this->NIN_P = $npc_at['nin'];
			$this->GEN = $this->NIN_P = $npc_at['gen'];
			$this->AGI = $this->AGI_P = $npc_at['agi'];
			$this->ENE = $this->ENE_P = $npc_at['ene'];
			$this->CON = $this->CON_P = $npc_at['con'];
			$this->INT = $this->INT_P = $npc_at['inte'];
			$this->FOR = $this->FOR_P = $npc_at['forc'];
			$this->RES = $this->RES_P = $npc_at['res'];
			$this->ESQ = $this->ESQ_P = $npc_at['esq'];
			
			$this->name			= $npc_info['nome'];
			$this->vila			= $npc_info['nome_vila'];
			$this->nome_classe	= $npc_info['nome_classe'];
			$this->id_classe	= $npc_info['id_classe'];
			$this->imagem		= $npc_info['imagem'];
			
			$this->level		= $player->level;

			$this->atCalc();
			
			$items = Recordset::query('SELECT id_npc_base_item FROM npc_base_item WHERE id_npc_base=' . $npc_at['id']);

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
						$p->AGI				= $it->req_agi * 200;
						$p->CON				= $it->req_con * 200;
						
						$it->level_liberado	= 1;
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

			
			$this->ATKS = $this->ATKS2;
			$this->DEFS = $this->DEFS2;			
		}
		
		function getATKItem($atkE = NULL) {
			while(true) {
				$noUse = false;
				$i = $this->items[round(rand(0, sizeof($this->items) - 1))];
				
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
					$nullObj->SP_CONSUME = 2;
					$nullObj->STA_CONSUME = 2;
					
					return $nullObj;
					break;
				}
			}
		}
		
		function removeItem($item) { }
		
		function consumeHP($value) {
			$this->HP -= $value;
			
			$this->less_hp += $value;
		}
		
		function consumeSP($value) {
			$this->SP -= $value;
			
			$this->less_sp += $value;
		}

		function consumeSTA($value) {
			$this->STA -= $value;
			
			$this->less_sta += $value;
		}
		
		function kai() {
			
		}
		
		function atRestore() {
			$this->TAI = $this->TAI_P;
			$this->NIN = $this->NIN_P;
			$this->GEN = $this->NIN_P;
			$this->AGI = $this->AGI_P;
			$this->ENE = $this->ENE_P;
			$this->CON = $this->CON_P;
			$this->INT = $this->INT_P;
			$this->FOR = $this->FOR_P;
			$this->RES = $this->RES_P;
			$this->ESQ = $this->ESQ_P;

			$this->ATK_F     = 0;
			$this->ATK_M     = 0;
			$this->DEF_BASE  = 0;
			$this->CRIT      = 0; // Critico
			$this->ESQ       = 0; // Esquiva
			$this->DEF_EXTRA = 0; // Defesa extra			
		}
		
		function atCalc($mxRecalc = false) {
			// Calculos de HP/SP e STA --->
				$this->HP = ($this->ENE * 8);
				$this->SP = ($this->ENE ) * 8 + ($this->NIN + $this->GEN) * 6;
				$this->STA = ($this->ENE ) * 8 + ($this->TAI + $this->KEN) * 6;			
			
			
			/*
			$this->CH_DEF_BASE = round($this->RES);
			$this->ATK_MAGICO = round($this->INT);
			$this->ATK_FISICO = round($this->FOR);
			*/
			
			$this->CH_DEF_BASE = round(($this->RES) / 2 + ($this->AGI + $this->CON) / 6); 
			$this->ATK_MAGICO = round(($this->INT) / 2 + ($this->CON) / 6);
			$this->ATK_FISICO = round( ($this->FOR) / 2 + ($this->AGI) / 6);		

			// Aplica os valores dos modificadores --->
				$this->ATK_MAGICO  += $this->ATK_F;
				$this->ATK_FISICO  += $this->ATK_M;
				$this->CH_DEF_BASE += $this->DEF_BASE;
				
				$this->CH_CRIT += $this->CRIT; // Critico
				$this->CH_ESQ  += $this->ESQ; // Esquiva
				$this->CH_DEF  += $this->DEF_EXTRA; // Defesa extra
			// <---
			
			//if($mxRecalc) {
				$this->MAX_HP = $this->HP;
				$this->MAX_SP = $this->SP = $this->SP;
				$this->MAX_STA = $this->STA = $this->STA;
			//}
			
			$this->HP -= $this->less_hp;
			$this->SP -= $this->less_sp;
			$this->STA -= $this->less_sta;
		}
	}
?>