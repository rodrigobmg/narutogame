<?php
	/**
	* Graduation
	*/
	class Graduation {
		private	static	$quest_status	= array();
		private static	$_requirementLog			= array();

		static function hasRequirement($player, $grad) {
			$ok			= true;
			$log		= array();
			$grad		= Recordset::query('SELECT * FROM graduacao WHERE id=' . $grad)->row_array();

			$style_s_ok = "<span style='text-decoration: line-through'>";
			$style_e_ok = "</span>";

			$style_s_no = "<span style='color: #F00'>";
			$style_e_no = "</span>";

			if(!isset(Graduation::$quest_status[$player->id])) {
				Graduation::$quest_status[$player->id] = Recordset::query('SELECT * FROM player_quest_status WHERE id_player=' . $player->id)->row_array();
			}
			
			if($grad['req_level']) {
				$style_s	= $style_s_ok;
				$style_e	= $style_e_ok;

				if($player->getAttribute('level') < $grad['req_level']) {
					$style_s	= $style_s_no;
					$style_e	= $style_e_no;

					$ok			= false;
				}

				$log[]	= $style_s . ''.t('classes.c30').' ' . $grad['req_level'] . ' '.t('classes.c31').'' . $style_e;
			}
			
			// Atributos --->
				$ats	= array(
					'req_tai'	=> array('tai_raw', 'Taijutsu'),
					'req_ken'	=> array('ken_raw', 'Bukijutsu'),
					'req_nin'	=> array('nin_raw', 'Ninjutsu'),
					'req_gen'	=> array('gen_raw', 'Genjutsu'),
					'req_agi'	=> array('agi_raw', t('at.agi')),
					'req_con'	=> array('con_raw', t('at.con')),
					'req_for'	=> array('for_raw', t('at.for')),
					'req_int'	=> array('int_raw', t('at.int')),
					'req_res'	=> array('res_raw', t('at.res')),
				);

				foreach($ats as $k => $v) {
					if($grad[$k]) {
						$style_s	= $style_s_ok;
						$style_e	= $style_e_ok;

						if($player->getAttribute($v[0]) < $grad[$k]) {
							$style_s	= $style_s_no;
							$style_e	= $style_e_no;

							$ok			= false;
						}

						$log[]	= $style_s . ''.t('requerimentos.ter').' ' . $grad[$k] . ' '.t('classes.c32').' ' . $v[1] . $style_e;
					}
				}
			// <---

			// Contagem normal de missões --->
				$missoes = array(
					'req_tarefas'	=> array('tarefa',  t('menus.tarefas')),
					'req_quest_d'	=> array('quest_d', t('menus.missoes').' Rank D'),
					'req_quest_c'	=> array('quest_c', t('menus.missoes').' Rank C'),
					'req_quest_b'	=> array('quest_b', t('menus.missoes').' Rank B'),
					'req_quest_a'	=> array('quest_a', t('menus.missoes').' Rank A'),
					'req_quest_s'	=> array('quest_s', t('menus.missoes').' Rank S')
				);

				foreach($missoes as $k => $v) {
					if($grad[$k]) {
						$style_s	= $style_s_ok;
						$style_e	= $style_e_ok;
						$data		= Graduation::$quest_status[$player->id];

						if(array_key_exists($v[0], $data) && $data[$v[0]] < $grad[$k]) {
							$style_s	= $style_s_no;
							$style_e	= $style_e_no;

							$ok			= false;
						}

						$log[]	= $style_s . ''.t('classes.c33').' ' . $grad[$k] . ' ' . $v[1] . ' '.t('classes.c34').'' . $style_e;
					}
				}
			// <---

			if($grad['req_equipe_lvl']) {
				$style_s	= $style_s_ok;
				$style_e	= $style_e_ok;

				if($player->getAttribute('id_equipe')) {
					$equipe	= Recordset::query('SELECT * FROM equipe WHERE id=' . $player->getAttribute('id_equipe'))->row_array();

					if($equipe['level'] < $grad['req_equipe_lvl']) {
						$style_s	= $style_s_no;
						$style_e	= $style_e_no;

						$ok			= false;
					}
				} else {
					$style_s	= $style_s_no;
					$style_e	= $style_e_no;

					$ok			= false;
				}

				$log[]	= $style_s . ''.t('classes.c35').' ' . $grad['req_equipe_lvl'] . ' '.t('classes.c31').'' . $style_e;
			}

			if($grad['req_mod']) {
				$style_s	= $style_s_ok;
				$style_e	= $style_e_ok;

				if(!$player->getAttribute('id_cla') && !$player->getAttribute('portao') && !$player->getAttribute('sennin') && !$player->getAttribute('id_invocacao')) {
					$style_s	= $style_s_no;
					$style_e	= $style_e_no;

					$ok			= false;
				}

				$log[]	= $style_s . ''.t('classes.c36').'' . $style_e;
			}

			if($grad['req_id_torneios']) {
				$style_s	= $style_s_ok;
				$style_e	= $style_e_ok;

				$torneios		= Recordset::query('
					SELECT
						a.nome_'.Locale::get().' AS nome,
						b.vitorias,
						a.npc
					FROM
						torneio a LEFT JOIN torneio_player b ON b.id_torneio=a.id AND b.id_player=' . $player->id . '
					WHERE
						a.id IN(' . $grad['req_id_torneios'] . ')
					');
				$torneio_win	= false;
				$torneios_nome	= '';

				foreach($torneios->result_array() as $torneio) {
					if($torneio['vitorias']) {
						$torneio_win	= true;
					}

					$torneios_nome	.= '<li>' . $torneio['nome'] . ($torneio['npc'] ? ' (NPC)' : ' (PVP)') . '</li>';
				}

				if(!$torneio_win) {
					$style_s	= $style_s_no;
					$style_e	= $style_e_no;

					$ok			= false;
				}

				$log[]	= $style_s . ''.t('classes.c37').': <ul>' . $torneios_nome . '</ul>' . $style_e;
			}

			// Valida quests de equipe --->
				$quests	= array(
					'req_quest_s_equipe'	=> array('5', t('menus.missoes').' Rank S '. t('classes.c38').''),
					'req_quest_a_equipe'	=> array('4', t('menus.missoes').' Rank A '. t('classes.c38').''),
					'req_quest_b_equipe'	=> array('3', t('menus.missoes').' Rank B '. t('classes.c38').''),
					'req_quest_c_equipe'	=> array('2', t('menus.missoes').' Rank C '. t('classes.c38').''),
					'req_quest_d_equipe'	=> array('1', t('menus.missoes').' Rank D '. t('classes.c38').''),
					'req_quest_invasao'		=> array('',  t('menus.missoes').' '. t('classes.c39').'')
				);

				foreach($quests as $k => $v) {
					if($grad[$k]) {
						if($k == 'req_quest_invasao') {
							$total_quests	= array(
								'total'	=> $player->getAttribute('vila_quest_vitorias')
							);
						} else {
							$where = 'a.id_rank = ' . $v[0] . ' AND a.equipe=\'1\' AND';

							$total_quests	= Recordset::query('
								SELECT
									SUM(1) AS total,
									a.id

								FROM
									quest a JOIN player_quest b ON b.id_quest=a.id AND b.completa=\'1\'

								WHERE ' . $where . ' b.id_player=' . $player->id)->row_array();

						}

						$style_s	= $style_s_ok;
						$style_e	= $style_e_ok;

						if($total_quests['total'] < $grad[$k]) {
							$style_s	= $style_s_no;
							$style_e	= $style_e_no;

							$ok			= false;
						}

						$log[]	= $style_s . ''. t('classes.c40').' ' . $grad[$k] . ' ' . $v[1] . $style_e;
					}
				}
			// <---

			// Contagem de golpes --->
				$jutsus	= array(
					'req_jutsus'		=> array('jutsus_l1', t('classes.c41')),
					'req_jutsus_lvl2'	=> array('jutsus_l2', t('classes.c42')),
					'req_jutsus_lvl3'	=> array('jutsus_l3', t('classes.c43'))
				);

				$jutsus_l1	= 0;
				$jutsus_l2	= 0;
				$jutsus_l3	= 0;
				$jutsus_l4	= 0;
				$jutsus_l5	= 0;

				if($grad['req_jutsus'] || $grad['req_jutsus_lvl2'] || $grad['req_jutsus_lvl3']) {
					foreach($player->getItems(5) as $item) {
						//switch($item->getAttribute('level')) {
						$level	= 1;

						if($item->aprimoramento && sizeof($item->aprimoramento) > 0) {
							$level	= sizeof($item->aprimoramento);
						}

						switch($level) {
							case 1:
								$jutsus_l1++;

								break;

							case 2:
								$jutsus_l1++;
								$jutsus_l2++;

								break;

							case 3:
								$jutsus_l1++;
								$jutsus_l2++;
								$jutsus_l3++;

								break;

							case 4:
								$jutsus_l1++;
								$jutsus_l2++;
								$jutsus_l3++;
								$jutsus_l4++;

								break;

							case 5:
								$jutsus_l1++;
								$jutsus_l2++;
								$jutsus_l3++;
								$jutsus_l4++;
								$jutsus_l5++;

								break;
						}
					}

					foreach ($jutsus as $k => $v) {
						if(!$grad[$k]) {
							continue;
						}

						$style_s	= $style_s_ok;
						$style_e	= $style_e_ok;

						$dynamic_var = $v[0];

						if($grad[$k] && $$dynamic_var < $grad[$k]) {
							$style_s	= $style_s_no;
							$style_e	= $style_e_no;

							$ok			= false;
						}

						$log[]	= $style_s . 'Ter ' . $grad[$k] . ' ' . $v[1] . $style_e;
					}
				}
			// <---

			if($grad['req_bingo_book']) {
				$style_s	= $style_s_ok;
				$style_e	= $style_e_ok;

				$alvos	= Recordset::query('SELECT COUNT(id) AS total FROM bingo_book WHERE morto=\'1\' AND id_player=' . $player->id)->row_array();

				if($alvos['total'] < $grad['req_bingo_book']) {
					$style_s	= $style_s_no;
					$style_e	= $style_e_no;

					$ok			= false;
				}

				$log[]	= $style_s . ''.t('classes.c44').' ' . $grad['req_bingo_book'] . ' '.t('classes.c45').'' . $style_e;
			}
			
			if($grad['req_vitorias'] || $grad['req_batalhas_pvp']) {
				
				//$qtd_dias_jogado = Recordset::query('select DATEDIFF(now(), data_ins) qtd_dias_jogado from player_flags where id_player = ' . $player->id)->row_array();
				
				$style_s	= $style_s_ok;
				$style_e	= $style_e_ok;
				$count		= $player->getAttribute('vitorias_f') + $player->getAttribute('empates') + $player->getAttribute('derrotas_f');

				if(($count < $grad['req_batalhas_pvp'])){
					$style_s	= $style_s_no;
					$style_e	= $style_e_no;

					$ok			= false;
				}

				$log[]	= $style_s . ''.t('requerimentos.ter').' ' . $grad['req_batalhas_pvp'] . ' '.t('requerimentos.batalhas_pvp'). $style_e;
			}
			if($grad['req_vitorias_d']) {
				$style_s	= $style_s_ok;
				$style_e	= $style_e_ok;

				if($player->getAttribute('vitorias_d') < $grad['req_vitorias_d']) {
					$style_s	= $style_s_no;
					$style_e	= $style_e_no;

					$ok			= false;
				}

				$log[]	= $style_s . ''.t('requerimentos.ter').' ' . $grad['req_vitorias_d'] . ' '.t('requerimentos.vitorias_npc').'' . $style_e;
			}

			if($grad['req_batalhas_npc']) {
				$style_s	= $style_s_ok;
				$style_e	= $style_e_ok;
				$count		= $player->getAttribute('vitorias_d') + $player->getAttribute('derrotas_npc');

				if($count < $grad['req_batalhas_npc']) {
					$style_s	= $style_s_no;
					$style_e	= $style_e_no;

					$ok			= false;
				}

				$log[]	= $style_s . ''.t('requerimentos.ter').' ' . $grad['req_batalhas_npc'] . ' '.t('requerimentos.batalhas_npc').'' . $style_e;
			}

			if($grad['req_invocacao_ordem'] || ($grad['req_cla_ordem'] && $grad['req_portao_ordem'])) {
				$cla_lvl	= 0;
				$por_lvl	= 0;
				$inv_lvl	= 0;

				foreach($player->getItems(array(16, 17, 21)) as $item) {
					switch($item->getAttribute('id_tipo')) {
						case 16:
							if($item->getAttribute('ordem') > $cla_lvl) {
								$cla_lvl	= $item->getAttribute('ordem');
							}

							break;

						case 17:
							if($item->getAttribute('ordem') > $por_lvl) {
								$por_lvl	= $item->getAttribute('ordem');
							}

							break;

						case 21:
							if($item->getAttribute('ordem') > $inv_lvl) {
								$inv_lvl	= $item->getAttribute('ordem');
							}

							break;
					}
				}

				if($grad['req_invocacao_ordem']) {
					$style_s	= $style_s_ok;
					$style_e	= $style_e_ok;

					if($inv_lvl < $grad['req_invocacao_ordem'] || !$player->getAttribute('id_invocacao')) {
						$style_s	= $style_s_no;
						$style_e	= $style_e_no;

						$ok			= false;
					}

					$log[]	= $style_s . ''.t('classes.c46').' '. $grad['req_invocacao_ordem'] . $style_e;
				}

				if($grad['req_cla_ordem'] && $grad['req_portao_ordem']) {
					$style_s	= $style_s_ok;
					$style_e	= $style_e_ok;

					if((!$player->getAttribute('portao') && !$player->getAttribute('id_cla')) ||
					   ($player->getAttribute('portao') && $por_lvl < $grad['req_portao_ordem']) ||
					   ($player->getAttribute('id_cla') && $cla_lvl < $grad['req_cla_ordem'])) {

						$style_s	= $style_s_no;
						$style_e	= $style_e_no;

						$ok			= false;
					}

					$log[]	= $style_s . ''.t('classes.c47').' ' . $grad['req_cla_ordem'] . ' '.t('classes.c48').' ' . $grad['req_portao_ordem'] . $style_e;
				}
			}
			if($grad['req_experiencia_ninja']) {
				$style_s				= $style_s_ok;
				$style_e				= $style_e_ok;


				if($player->getAttribute('experience_ninja') < $grad['req_experiencia_ninja']) {
					$style_s	= $style_s_no;
					$style_e	= $style_e_no;

					$ok			= false;
				}

				$log[]	= $style_s . ''.t('requerimentos.ter').' ' . $grad['req_experiencia_ninja'] . ' '.t('requerimentos.experiencia').'' . $style_e;
			}
			Graduation::$_requirementLog = $log;

			return $ok;
		}

		static function getRequirementLog() {
			return Graduation::$_requirementLog;
		}
	}
