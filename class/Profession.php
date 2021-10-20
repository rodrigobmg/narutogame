<?php
	class Profession {
		private	static $_log	= '';

		public static function hasRequirement($level, $player, $profession) {
			$ok				= true;
			$req_graduation	= $level + 1;
			$req_level		= Recordset::query('SELECT req_level FROM graduacao WHERE id=' . $req_graduation)->row()->req_level;

			$style_s_ok		= "<span style='text-decoration: line-through'>";
			$style_e_ok		= "</span>";

			$style_s_no		= "<span style='color: #F00'>";
			$style_e_no		= "</span>";

			// Nível da profissão -->
				if ($level > 1) {
					$style_s	= $style_s_ok;
					$style_e	= $style_e_ok;
					
					if(Player::getFlag('profissao_nivel', $player->id) < ($level - 1)) {
						$style_s	= $style_s_no;
						$style_e	= $style_e_no;
						
						$ok			= false;
					}
					
					$log[]	= $style_s . sprintf(t('profissao.requirements.prof_level'), $level - 1) . $style_e;
				}
			// <--

			// Level -->
				$style_s	= $style_s_ok;
				$style_e	= $style_e_ok;
				
				if($player->getAttribute('level') < $req_level) {
					$style_s	= $style_s_no;
					$style_e	= $style_e_no;
					
					$ok			= false;
				}
				
				$log[]	= $style_s . t('classes.c30').' ' . $req_level . ' '.t('classes.c31') . $style_e;
			// <--

			// Gradução -->
				$style_s	= $style_s_ok;
				$style_e	= $style_e_ok;
				
				if($player->getAttribute('id_graduacao') < $req_graduation) {
					$style_s	= $style_s_no;
					$style_e	= $style_e_no;
					
					$ok			= false;
				}
				
				$log[] = $style_s . "".t('classes.c7').": " . graduation_name($player->getAttribute('id_vila'), $req_graduation) . $style_e;
			// <--

			Profession::$_log	= $log;

			return $ok;
		}

		public static function getRequirementLog() {
			return Profession::$_log;
		}
	}
