<?php
	define('NPC_DOJO', 1); //K
	define('NPC_INTERATIVA', 2); //K
	define('NPC_EQUIPE', 3); //K
	define('NPC_VILA', 4);
	define('NPC_EVENTO', 5); //Kd
	define('NPC_TORNEIO', 6); //K
	define('NPC_DIARIA', 7); //K
	define('NPC_MAPA_RND', 8); //K
	define('NPC_EVENTO4', 9); //K
	define('NPC_EVENTO_GLOBAL', 10); //K
	define('NPC_EVENTO_H', 11); //K
	define('NPC_DIARIA2', 12); //K
	define('NPC_GUERRA', 13);
	define('NPC_GUERRA_S', 14);
	define('NPC_SENSEI', 15);

	class NPC {
		use AttributeCalculationTrait;
		use ModifiersTrait;

		public	$id		= 0;

		public	$npc_vila		= false;
		public	$npc_evento		= false;
		public	$npc_diaria		= false;
		public	$npc_diaria2	= false;
		public	$npc_missao		= false;
		public	$npc_torneio	= false;
		public	$npc_mapa_rnd	= false;
		public	$npc_evento4	= false;
		public	$npc_evento_h	= false;
		public	$npc_sensei		= false;
		public	$npc_guerra		= false;
		public	$npc_guerra_s	= false;
		public	$npc_evento_global = false;
		public	$elementos		= array();
		public	$drops			= array();
		
		public	$batalha_multi_pos	= 0;
		public	$batalha_multi_id	= 0;
		public	$batalha_id			= 0;
		
		private	$id_player		= 0;
		private	$id_equipe		= 0;
		public  $id_evento		= 0;
		private $npc_vila_at	= array();
		
		private	$items			= array();
		public	$items_id		= array();
		
		public	$sennin					= false;
		public	$id_sennin				= false;
		public	$id_cla					= 0;
		public	$id_cla_atual			= 0;
		public	$id_selo				= 0;
		public	$id_selo_atual			= 0;
		public	$id_invocacao			= 0;
		public	$id_invocacao_atual		= 0;
		public	$portao					= false;
		public	$id_portao_atual		= 0;

		public	static	$droppable_types	= [1,2,4,9,18,38];
		
		function __construct(int|null $id, Player &$player, $tipo = NPC_DOJO, $ai_level = 0, $extra = null) {
			if(!$player) {
				throw new Exception('Can\'t create instance, no player was specified');
			}

			$this->atl = array(
				'less_hp'   => 0,
				'less_sp'   => 0,
				'less_sta'  => 0,
				'id_vila'   => 0,
				'less_conv' => 0
			);

			$grad	= $player->getAttribute('id_graduacao') == 1 ? 2 : $player->getAttribute('id_graduacao');
			$bonus	= Recordset::query('SELECT * FROM level_exp WHERE id=' . $player->getAttribute('level'), true)->row_array();
			
			$this->id_player	= $player->id;
			$this->id_equipe	= $player->id_equipe;
			$this->id_evento	= $player->getAttribute('id_evento');

			// Tem drop ?
			if(has_chance(100) && !($tipo == NPC_TORNEIO || $tipo == NPC_VILA || $tipo == NPC_GUERRA || $tipo == NPC_GUERRA_S || $tipo == NPC_SENSEI)) {
				if (in_array($player->id_classe_tipo, [1, 4])) {
					$where	= ' AND req_tai=1';
				} else {
					$where	= ' AND req_nin=1';
				}

				$item	= Recordset::query('
					SELECT
						id,
						nome_' . Locale::get() . ' AS nome,
						drop_rate,
						drop_total

					FROM
						item

					WHERE
						`drop`=\'1\' AND
						removido =\'0\' AND
						id_tipo IN(' . implode(',', NPC::$droppable_types) . ') AND
						req_graduacao=' . $player->id_graduacao . $where . '

					ORDER BY RAND() LIMIT 1')->row_array();

				if(has_chance($item['drop_rate'])) {
					$this->drops[]	= array('nome' => $item['nome'], 'id_item' => $item['id'], 'total' => $item['drop_total']);
				}
			}
			
			if(!$id) {
				$classe			= Recordset::query('SELECT * FROM classe WHERE ativo=1 ORDER BY RAND() LIMIT 1')->row_array();
				$total_images	= Recordset::query('SELECT COUNT(id) AS total FROM classe_imagem WHERE ativo="sim" AND id_classe=' . $classe['id'])->row()->total;
			
				$npc_info	= array(
					'id'				=> $classe['id'],
					'nome'				=> $classe['nome'],
					'level'				=> $player->getAttribute('level'),
					'ryou'				=> $bonus['ryou'],
					'exp'				=> $bonus['exp_dojo_npc'],
					'id_classe_tipo'	=> rand(1, 4),
					'imagem'			=> 'layout'.LAYOUT_TEMPLATE.'/profile/' . $classe['id'] . '/' . rand(1, $total_images) . (LAYOUT_TEMPLATE=="_azul" ? '.jpg' : '.png')
				);
				
				$this->setLocalAttribute('id_classe', $classe['id']);
				
				if($tipo == NPC_TORNEIO) {
					$this->npc_torneio	= true;
				} elseif($tipo == NPC_MAPA_RND) {
					$this->npc_mapa_rnd	= true;
					
					//echo '// WILLSORT' . PHP_EOL;
				} elseif($tipo == NPC_EVENTO4) {
					$this->npc_evento4 = true;
				}
			} else {
				$this->setLocalAttribute('id_classe', $player->getAttribute('id_classe'));
			
				switch($tipo) {
					case NPC_INTERATIVA:
					case NPC_EQUIPE:
					case NPC_DIARIA:
					case NPC_DIARIA2:
						if($tipo == NPC_DIARIA) {
							$this->npc_diaria	= true;
						} elseif($tipo == NPC_DIARIA2) {
							$this->npc_diaria2	= true;
						} else {
							$this->npc_missao	= true;
						}
						
						$npc_info	= Recordset::query('
							SELECT
								id,
								nome_'.Locale::get().' AS nome,
								level,
								exp,
								ryou,
								multi,
								id_classe_tipo
							
							FROM 
								npc
							WHERE
								id=' . $id)->row_array();
						
						$npc_info['imagem']			= 'layout'.LAYOUT_TEMPLATE.'/npc/' . $id . (LAYOUT_TEMPLATE=="_azul" ? '.jpg' : '.png');
						$npc_info['id_classe_tipo']	= $player->getAttribute('id_classe_tipo');
						
						break;
					
					case NPC_VILA;
						$this->npc_vila	= true;
						
						$npc_vila = Recordset::query('
							SELECT 
								*,
								\'ogro\' AS dificuldade 
							FROM 
								npc_vila 
								
							WHERE 
								id=' . $id)->row_array();
						
						$npc_info	= array(
							'id'				=> $id,
							'nome'				=> $npc_vila['nome'],
							'level'				=> $player->getAttribute('level'),
							'ryou'				=> $bonus['ryou'],
							'exp'				=> $bonus['exp_dojo_npc'],
							'id_classe_tipo'	=> rand(1, 4),
							'imagem'			=> 'layout/guardioes/combate/' . $id . '.png',
							'dificuldade'		=> 'ogro'
						);
					
						break;
					
					case NPC_EVENTO:
					case NPC_EVENTO_GLOBAL:
					case NPC_EVENTO_H:
						if($tipo == NPC_EVENTO) {
							$this->npc_evento	= true;
						} elseif($tipo == NPC_EVENTO_GLOBAL) {
							$this->id_evento = $player->eventoGlobal();
							$this->npc_evento_global = true;	
						} else {
							$this->npc_evento_h	= true;
						}
						
						$npc		= Recordset::query('SELECT * FROM evento_npc WHERE id=' . $id, true)->row_array();
						$npc_info	= array(
							'id'				=> $id,
							'nome'				=> $npc['nome_'. Locale::get()],
							'level'				=> $player->getAttribute('level') + ($tipo == NPC_EVENTO_GLOBAL ? 3 : 0),
							'ryou'				=> $bonus['ryou'],
							'exp'				=> $bonus['exp_dojo_npc'],
							'id_classe_tipo'	=> rand(1, 4),
							'imagem'			=> 'layout/npc_evento/combate/' . $id . '.png'
						);
						
						$npc_info['imagem']	= 'layout'.LAYOUT_TEMPLATE.'/npc_evento/combate/' . $id . (LAYOUT_TEMPLATE=="_azul" ? '.jpg' : '.png');
						
						if($this->npc_evento_h) {
							$npc_info['imagem']	= 'layout'.LAYOUT_TEMPLATE.'/npc_evento/combate/' . $id . (LAYOUT_TEMPLATE=="_azul" ? '.jpg' : '.png');							
						}

						break;

					case NPC_GUERRA:
					case NPC_GUERRA_S:
						$this->npc_guerra	= true;

						if ($tipo == NPC_GUERRA_S) {
							$this->npc_guerra_s	= true;
						}

						$npc				= Recordset::query('SELECT * FROM guerra_ninja_npcs WHERE id=' . $id)->row_array();
						$npc_info			= [
							'id'				=> $id,
							'nome'				=> $npc['nome_'. Locale::get()],
							'level'				=> $player->getAttribute('level'),
							'ryou'				=> $bonus['ryou'],
							'exp'				=> $bonus['exp_dojo_npc'],
							'id_classe_tipo'	=> rand(1, 4),
							'imagem'			=> 'layout/npc/npc_guerra/' . $npc['icone'] . '.png'
						];
						
						break;
					case NPC_SENSEI:
						$this->npc_sensei	= true;

						$classe			= Recordset::query('SELECT * FROM classe WHERE ativo=1 AND id='. $id)->row_array();
						$total_images	= Recordset::query('SELECT COUNT(id) AS total FROM classe_imagem WHERE ativo="sim" AND id_classe=' . $classe['id'])->row()->total;
				
						$npc_info			= [
							'id'				=> $classe['id'],
							'nome'				=> $classe['nome'],
							'level'				=> $player->getAttribute('level'),
							'ryou'				=> 0,
							'exp'				=> 0,
							'id_classe_tipo'	=> rand(1, 4),
							'imagem'			=> 'layout'.LAYOUT_TEMPLATE.'/profile/' . $classe['id'] . '/' . rand(1, $total_images) . (LAYOUT_TEMPLATE=="_azul" ? '.jpg' : '.png')
						];
						
						break;	
				}
			}

			$this->id		= $id ? $id : $npc_info['id'];
			$forceInversion	= false;
			
			if($this->npc_vila) {
				$npc_at = $npc_vila;
			} else {
				$ar_ats_p		= ['tai_raw', 'ken_raw', 'nin_raw', 'gen_raw', 'agi_raw', 'con_raw', 'ene_raw', 'int_raw', 'for_raw', 'res_raw','conc_raw2','esq_raw2','conv_raw2','det_raw2','esquiva_raw2'];
				$ar_ats_n		= ['tai', 'ken', 'nin', 'gen', 'agi', 'con', 'ene', 'inte', 'forc', 'res','conc', 'esq', 'conv', 'det','esquiva'];

				if($player->level <= 15) { # NPC mais fácil para os níveis baixos
					$dificulties	= array(40, 60, 80);
				} elseif($player->level >= 16 AND $player->level <= 30) {
					$dificulties	= array(60, 80, 100);
				} elseif($player->level >= 31 AND $player->level <= 45) {
					$dificulties	= array(80,100,120);
				}else{
					$dificulties	= array(100,120,140);
				}
				/*
				1- Dano bruto
				2- Dano Misto com Critico
				3- Defesa alta com convicção
				4 - Dano e Percepção

				*/
				switch($npc_info['id_classe_tipo']){
					case 1:
						$distributions	= [
							[35, 5, 0, 0, 15, 0, 30, 0, 15, 5, 35,35,35,35,35],
							[20, 0, 0, 0, 10, 0, 30, 0, 40, 5, 35,35,35,35,35],
							[15, 0, 0, 0, 10, 0, 30, 0, 15, 40, 35,35,35,35,35],
							[15, 0, 0, 0, 40, 0, 30, 0, 10, 10, 35,35,35,35,35]
						];

					break;
					
					case 2:
						$forceInversion	= true;
						$distributions	= [
							[0, 0, 35, 5, 0, 15, 30, 15, 0, 5, 35,35,35,35,35],
							[0, 0, 20, 0, 0, 10, 30, 40, 0, 5, 35,35,35,35,35],
							[0, 0, 15, 0, 0, 10, 30, 15, 0, 40, 35,35,35,35,35],
							[0, 0, 15, 0, 0, 40, 30, 10, 0, 10, 35,35,35,35,35]
						];

					break;
					
					case 3:
						$forceInversion	= true;
						$distributions	= [
							[0, 0, 5, 35, 0, 15, 30, 15, 0, 5, 35,35,35,35,35],
							[0, 0, 0, 20, 0, 10, 30, 40, 0, 5, 35,35,35,35,35],
							[0, 0, 0, 15, 0, 10, 30, 15, 0, 40, 35,35,35,35,35],
							[0, 0, 0, 15, 0, 40, 30, 10, 0, 10, 35,35,35,35,35]
						];

					break;
					
					case 4:
						$distributions	= [
							[5, 35, 0, 0, 15, 0, 30, 0, 15, 5, 35,35,35,35,35],
							[0, 20, 0, 0, 10, 0, 30, 0, 40, 5, 35,35,35,35,35],
							[0, 15, 0, 0, 10, 0, 30, 0, 15, 40, 35,35,35,35,35],
							[0, 15, 0, 0, 40, 0, 30, 0, 10, 10, 35,35,35,35,35]
						];

					break;
					
				
				}

				// Calcula a quantidade de pontos
				$cb = function ($a, $p, &$o) {
					foreach($a as $v) {
						
						$o += $p->getAttribute($v);
					}	
				};
				
				$cb($ar_ats_p, $player, $total_points);

				if ($this->npc_guerra) {
					if ($extra->etapa == 1) {
						$dificulty	= 80;
					} elseif($extra->etapa == 2) {
						$dificulty	= 100;
					} elseif($extra->etapa == 3) {
						$dificulty	= 120;
					} elseif($extra->etapa == 4) {
						$dificulty	= 150;
					}
				}elseif($this->npc_sensei){
					$dificulty = ($extra % 5 == 0) ? 100 + $extra * 6 : 100 +  $extra * 3;
				} else {
					$dificulty	= $dificulties[rand(0, sizeof($dificulties) - 1)];
				}

				$total_points	= percent($dificulty, $total_points);
				$distribution	= $distributions[rand(0, sizeof($distributions) - 1)];
				$npc_at			= array();

				if ($_SESSION['universal']) {
					/*print($dificulty).'-----';
					print_r($total_points);*/
				}
				
				// Aplica os pontos nos atributos
				$cb = function ($a, $p, $d, &$o) {
					foreach($a as $k => $v) {
						//echo "doing $k [$d[$k]] $v with total $p<br />";
						$o[$v] = percent($d[$k], $p);
					}
				};

				$cb($ar_ats_n, $total_points, $distribution, $npc_at);
				if ($_SESSION['universal']) {
					//print_r($npc_at);
				}
			}
			
			// NPCS de evento
			if($this->npc_evento) {
				$npc_ser = Recordset::query('SELECT npc_info FROM evento_npc_equipe WHERE id_equipe=' . $this->id_equipe . ' AND id_evento_npc=' . $this->id . ' AND id_evento=' . $this->id_evento)->row_array();
				
				if($npc_ser['npc_info']) {
          $npc_at                     = unserialize($npc_ser['npc_info']);
          $npc_info['level']          = $npc_at['level'];
          $npc_info['id_classe_tipo']	= $npc_at['id_classe_tipo'];
				} else {
					if($player->getAttribute('id_graduacao') < 4){	
						$npc_at['tai']	*= 4;
						$npc_at['ken']	*= 4;
						$npc_at['nin']	*= 4;
						$npc_at['gen']	*= 4;
						$npc_at['agi']	*= 1;
						$npc_at['ene']	*= 4;
						$npc_at['con']	*= 1;
						$npc_at['inte']	*= 1;
						$npc_at['forc']	*= 1;
						$npc_at['res'] 	*= 1;
					} elseif($player->getAttribute('id_graduacao') == 4) {
						$npc_at['tai']  *= 4;
						$npc_at['ken']	*= 4;
						$npc_at['nin']	*= 4;
						$npc_at['gen']	*= 4;
						$npc_at['agi']	*= 1;
						$npc_at['ene']	*= 6;
						$npc_at['con']	*= 1;
						$npc_at['inte']	*= 2;
						$npc_at['forc']	*= 2;
						$npc_at['res']	*= 2;
					} elseif($player->getAttribute('id_graduacao') == 5) {
						$npc_at['tai']	*= 5;
						$npc_at['ken']	*= 5;
						$npc_at['nin']	*= 5;
						$npc_at['gen']	*= 5;
						$npc_at['agi']	*= 2;
						$npc_at['ene']	*= 6;
						$npc_at['con']	*= 2;
						$npc_at['inte']	*= 2;
						$npc_at['forc']	*= 2;
						$npc_at['res']	*= 2;
					} elseif($player->getAttribute('id_graduacao') == 6) {
						$npc_at['tai']	*= 5;
						$npc_at['ken']	*= 5;
						$npc_at['nin']	*= 5;
						$npc_at['gen']	*= 5;
						$npc_at['agi']	*= 2;
						$npc_at['ene']	*= 7;
						$npc_at['con']	*= 2;
						$npc_at['inte']	*= 2;
						$npc_at['forc']	*= 2;
						$npc_at['res']	*= 2;
					} elseif($player->getAttribute('id_graduacao') == 7) {
						$npc_at['tai']	*= 6;
						$npc_at['ken']	*= 6;
						$npc_at['nin']	*= 6;
						$npc_at['gen']	*= 6;
						$npc_at['agi']	*= 2;
						$npc_at['ene']	*= 8;
						$npc_at['con']	*= 2;
						$npc_at['inte']	*= 2;
						$npc_at['forc']	*= 2;
						$npc_at['res']	*= 2;
					}
				}

				if(!$npc_ser['npc_info']) {
					Recordset::update('evento_npc_equipe', [
						'npc_info'		=> serialize(array_merge($npc_at, $npc_info))
					], [
						'id_equipe'		=> $this->id_equipe,
						'id_evento_npc'	=> $this->id,
						'id_evento'		=> $this->id_evento
					]);
				}
			}
			
			// Coisas do dojo(icones laterais e afins) --->
			$this->setLocalAttribute('id_cla', 0);
			$this->setLocalAttribute('id_selo', 0);
			$this->setLocalAttribute('id_invocacao', 0);
			$this->setLocalAttribute('portao', 0);
			$this->setLocalAttribute('sennin', 0);
			$this->setLocalAttribute('id_sennin', 0);
			$this->setLocalAttribute('id_cla_atual', 0);
			$this->setLocalAttribute('id_portao_atual', 0);
			$this->setLocalAttribute('nome_sennin', '');
			$this->setLocalAttribute('nome_portao', '');
			// <---
			
			$this->setLocalAttribute('tai_raw', $npc_at['tai'] ? $npc_at['tai'] : 0);
			$this->setLocalAttribute('ken_raw', $npc_at['ken'] ? $npc_at['ken'] : 0);
			$this->setLocalAttribute('nin_raw', $npc_at['nin'] ? $npc_at['nin'] : 0);
			$this->setLocalAttribute('gen_raw', $npc_at['gen'] ? $npc_at['gen'] : 0);
			$this->setLocalAttribute('agi_raw', $npc_at['agi'] ? $npc_at['agi'] : 0);
			$this->setLocalAttribute('con_raw', $npc_at['con'] ? $npc_at['con'] : 0);
			$this->setLocalAttribute('ene_raw', $npc_at['ene'] ? $npc_at['ene'] : 0);
			$this->setLocalAttribute('ene_raw2', $npc_at['ene'] ? $npc_at['ene'] : 0);
			$this->setLocalAttribute('int_raw', $npc_at['inte'] ? $npc_at['inte'] : 0);
			$this->setLocalAttribute('for_raw', $npc_at['forc'] ? $npc_at['forc'] : 0);
			$this->setLocalAttribute('res_raw', $npc_at['res'] ? $npc_at['res'] : 0);
			$this->setLocalAttribute('conc_raw2', $npc_at['conc'] ? $npc_at['conc'] : 0);
			$this->setLocalAttribute('esq_raw2', $npc_at['esq'] ? $npc_at['esq'] : 0);
			$this->setLocalAttribute('conv_raw2', $npc_at['conv'] ? $npc_at['conv'] : 0);
			$this->setLocalAttribute('esquiva_raw2', $npc_at['esquiva'] ? $npc_at['esquiva'] : 0);
			$this->setLocalAttribute('det_raw2', $npc_at['det'] ? $npc_at['det'] : 0);

			// #CX
			$this->tai_raw		= $npc_at['tai'];
			$this->ken_raw		= $npc_at['ken'];
			$this->nin_raw		= $npc_at['nin'];
			$this->gen_raw		= $npc_at['gen'];
			$this->agi_raw		= $npc_at['agi'];
			$this->con_raw		= $npc_at['con'];
			$this->ene_raw		= $npc_at['ene'];
			$this->int_raw		= $npc_at['inte'];
			$this->for_raw		= $npc_at['forc'];
			$this->res_raw		= $npc_at['res'];
			$this->conc_raw2	= $npc_at['conc'];
			$this->esq_raw2		= $npc_at['esq'];
			$this->esquiva_raw2	= $npc_at['esquiva'];
			$this->det_raw2		= $npc_at['det'];

			$this->setLocalAttribute('ryou', $npc_info['ryou']);
			$this->setLocalAttribute('nome', $npc_info['nome']);
			$this->setLocalAttribute('exp', $npc_info['exp']);
			$this->setLocalAttribute('imagem', $npc_info['imagem']);
			$this->setLocalAttribute('id_classe_tipo', $npc_info['id_classe_tipo']);
			$this->setLocalAttribute('level', $player->getAttribute('level'));
			
			// base --->
				$this->setLocalAttribute('tai_item', 0);
				$this->setLocalAttribute('ken_item', 0);
				$this->setLocalAttribute('nin_item', 0);
				$this->setLocalAttribute('gen_item', 0);
				$this->setLocalAttribute('agi_item', 0);
				$this->setLocalAttribute('con_item', 0);
				$this->setLocalAttribute('ene_item', 0);
				$this->setLocalAttribute('for_item', 0);
				$this->setLocalAttribute('int_item', 0);
				$this->setLocalAttribute('res_item', 0);
	
				$this->setLocalAttribute('hp_item'			, 0);
				$this->setLocalAttribute('sp_item'			, 0);
				$this->setLocalAttribute('sta_item'			, 0);
				$this->setLocalAttribute('atk_fisico_item'	, 0);
				$this->setLocalAttribute('atk_magico_item'	, 0);
				$this->setLocalAttribute('def_fisico_item'	, 0);
				$this->setLocalAttribute('def_magico_item'	, 0);
				$this->setLocalAttribute('def_base_item'	, 0);
				$this->setLocalAttribute('prec_fisico_item'	, 0);
				$this->setLocalAttribute('prec_magico_item'	, 0);
				$this->setLocalAttribute('crit_min_item'	, 0);
				$this->setLocalAttribute('crit_max_item'	, 0);
				$this->setLocalAttribute('crit_total_item'	, 0);
				$this->setLocalAttribute('esq_min_item'		, 0);
				$this->setLocalAttribute('esq_max_item'		, 0);
				$this->setLocalAttribute('esq_total_item'	, 0);
				$this->setLocalAttribute('esq_item'			, 0);
				$this->setLocalAttribute('det_item'			, 0);
				$this->setLocalAttribute('conv_item'		, 0);
				$this->setLocalAttribute('esquiva_item'		, 0);
				$this->setLocalAttribute('conc_item'		, 0);
	
				$this->setLocalAttribute('tai_arv', 0);
				$this->setLocalAttribute('ken_arv', 0);
				$this->setLocalAttribute('nin_arv', 0);
				$this->setLocalAttribute('gen_arv', 0);
				$this->setLocalAttribute('agi_arv', 0);
				$this->setLocalAttribute('con_arv', 0);
				$this->setLocalAttribute('ene_arv', 0);
				$this->setLocalAttribute('for_arv', 0);
				$this->setLocalAttribute('int_arv', 0);
				$this->setLocalAttribute('res_arv', 0);
	
				$this->setLocalAttribute('hp_arv'			, 0);
				$this->setLocalAttribute('sp_arv'			, 0);
				$this->setLocalAttribute('sta_arv'			, 0);
				$this->setLocalAttribute('atk_fisico_arv'	, 0);
				$this->setLocalAttribute('atk_magico_arv'	, 0);
				$this->setLocalAttribute('def_fisico_arv'	, 0);
				$this->setLocalAttribute('def_magico_arv'	, 0);
				$this->setLocalAttribute('def_base_arv'		, 0);
				$this->setLocalAttribute('prec_fisico_arv'	, 0);
				$this->setLocalAttribute('prec_magico_arv'	, 0);
				$this->setLocalAttribute('crit_min_arv'		, 0);
				$this->setLocalAttribute('crit_max_arv'		, 0);
				$this->setLocalAttribute('crit_total_arv'	, 0);
				$this->setLocalAttribute('esq_min_arv'		, 0);
				$this->setLocalAttribute('esq_max_arv'		, 0);
				$this->setLocalAttribute('esq_total_arv'	, 0);
				$this->setLocalAttribute('esq_arv'			, 0);
				$this->setLocalAttribute('det_arv'			, 0);
				$this->setLocalAttribute('conv_arv'			, 0);
				$this->setLocalAttribute('esquiva_arv'		, 0);
				$this->setLocalAttribute('conc_arv'			, 0);

				$this->setLocalAttribute('crit_min_raw', 0);
				$this->setLocalAttribute('crit_max_raw', 0);
				$this->setLocalAttribute('crit_total_raw', 0);
				$this->setLocalAttribute('esq_min_raw', 0);
				$this->setLocalAttribute('esq_max_raw', 0);
				$this->setLocalAttribute('esq_total_raw', 0);
				$this->setLocalAttribute('esq_raw', 0);
				$this->setLocalAttribute('det_raw', 0);
				$this->setLocalAttribute('conv_raw', 0);
				$this->setLocalAttribute('esquiva_raw', 0);
				$this->setLocalAttribute('conc_raw', 0);
	
				
			// <---
			
			if($player->level <= 5) { # NPC mais fácil para os níveis baixos

				$items = Recordset::query('
					SELECT id AS id_npc_base_item, req_level FROM item WHERE id_tipo=5 AND req_level BETWEEN ' . ($player->level-10) . ' AND ' . ($player->level + 1) . ' AND id_habilidade='. $npc_info['id_classe_tipo'] .' AND arvore_nivel =0 AND sem_turno = 0
					UNION
					SELECT id AS id_npc_base_item, req_level FROM item WHERE id_tipo=6 AND  id_habilidade is not null AND arvore_nivel =0 AND sem_turno = 0
					UNION
					SELECT id AS id_npc_base_item, req_level FROM item WHERE id_tipo=2 AND req_level BETWEEN ' . ($player->level-10) . ' AND ' . ($player->level + 1) . ' AND id_habilidade='. ($npc_info['id_classe_tipo']==2 || $npc_info['id_classe_tipo']==3 ? 2 : 1) .' AND arvore_nivel =0 AND sem_turno = 0
					ORDER BY req_level DESC
				');
			}else{
				
				$items = Recordset::query('
					SELECT id AS id_npc_base_item, req_level FROM item WHERE id_tipo=5 AND req_level BETWEEN ' . ($player->level-10) . ' AND ' . ($player->level + 5) . ' AND id_habilidade='. $npc_info['id_classe_tipo'] .' AND arvore_nivel =0 AND sem_turno = 0
					UNION
					SELECT id AS id_npc_base_item, req_level FROM item WHERE id_tipo=6 AND  id_habilidade is not null AND arvore_nivel =0 AND sem_turno = 0
					UNION
					SELECT id AS id_npc_base_item, req_level FROM item WHERE id_tipo=2 AND req_level BETWEEN ' . ($player->level-10) . ' AND ' . ($player->level + 5) . ' AND id_habilidade='. ($npc_info['id_classe_tipo']==2 || $npc_info['id_classe_tipo']==3 ? 2 : 1) .' AND arvore_nivel =0 AND sem_turno = 0
					ORDER BY req_level DESC
				');
				
			}	

			/*
			if(!$ai_level) {
				switch($npc_at['dificuldade']) {
					case 'normal':	$ai_level = 0; break;
					case 'hard':	$ai_level = 1; break;
					case 'ogro':	$ai_level = 2; break;
				}
			}
			*/			
			
			foreach($items->result_array() as $item) {
				$it = new Item($item['id_npc_base_item'], NULL, NULL, false, $forceInversion);
				
				$it->setLocalAttribute('consume_hp', ceil($it->getAttribute('consume_hp')  / 1.5));
				$it->setLocalAttribute('consume_sp', ceil($it->getAttribute('consume_sp')  / 1.5));
				$it->setLocalAttribute('consume_sta', ceil($it->getAttribute('consume_sta') / 1.5));
				
				/*echo "<pre>";
				print_r($it->getAttribute('nome_br').'---- Crakra:'.$it->getAttribute('consume_sp').'----- Stamina:'.$it->getAttribute('consume_sta'));
				*/
				$it->setLocalAttribute('level_liberado', 1);
								
				$this->items_id[$it->id]	= 1;
				$it->setLocalAttribute('precisao', 100);
				
				/*
				if($ai_level == 1) { // NPC HARD
					$opt = rand(1, 2);
				} elseif($ai_level == 2) { // NPC OGRO
					$opt = 2;
				} else { // NPC NORMAL e de qualquer outra parte do jogo
					$opt = rand(0, 2);					
				}
				
				switch($opt) {
					case 2: // Golpe critico
						$it->setLocalAttribute('level_liberado', 1);
						$it->setLocalAttribute('precisao', 100);
						
						break;
					
					case 1: // Golpe com acerto, sem critico
						$it->setLocalAttribute('level_liberado', 0);
						$it->setLocalAttribute('precisao', 100);
					
						break;
					
					case 0: // Golpe com erro
						$it->setLocalAttribute('level_liberado', 0);
						$it->setLocalAttribute('precisao', 80);
					
						break;
				}
				*/

				$this->items[] = $it;
			}

			if($this->npc_vila) {
				$vila_items	= Recordset::query('SELECT SUM(a.esq_min) AS hp_bonus FROM item a JOIN vila_item b ON b.item_id=a.id WHERE b.vila_id=' . $npc_at['id_vila']);
				
				$this->npc_vila_at			= $npc_at;
				$this->npc_vila_at['hp']	+= percent($vila_items->row()->hp_bonus, $this->npc_vila_at['hp']);
			}
						
			$this->do_key_mapping();
			$this->parseModifiers();
			$this->atCalc();
			
			// Altera os dados de HP/SP/STA do npc de vila
			if($this->npc_evento) {
				$less 				= Recordset::query('SELECT * FROM evento_npc_equipe WHERE id_equipe=' . $this->id_equipe . ' AND id_evento_npc=' . $this->id . ' AND id_evento=' . $this->id_evento)->row_array();
				$evento_especial	= Recordset::query('SELECT npc_especial FROM evento WHERE id=' . $this->id_evento)->row()->npc_especial;
				
				if($evento_especial) {
					$this->setLocalAttribute('less_hp',  $this->getAttribute('hp')  - 1);
					$this->setLocalAttribute('less_sp',  $this->getAttribute('sp')  - 1);
					$this->setLocalAttribute('less_sta', $this->getAttribute('sta') - 1);
				} else {
					$this->setLocalAttribute('less_hp',  $less['less_hp']);
					$this->setLocalAttribute('less_sp',  $less['less_sp']);
					$this->setLocalAttribute('less_sta', $less['less_sta']);
					
					$this->setLocalAttribute('hp',  $this->getAttribute('hp')  - $less['less_hp']);
					$this->setLocalAttribute('sp',  $this->getAttribute('sp')  - $less['less_sp']);
					$this->setLocalAttribute('sta', $this->getAttribute('sta') - $less['less_sta']);
				}
			}

			if ($this->npc_guerra_s) {
				$this->update_npc_war_stats();
			}
		}
		
		static function getNPCFromPerimeter($x1, $y1, $l, $quest = "", $quest_diaria = NULL) {
			
			$qNPC = "
				SELECT
					id
				
				 FROM 
					npc
				 WHERE
				 	$x1 BETWEEN x1 AND x2 AND
					$y1 BETWEEN y1 AND y2 AND
					quest = 0 AND
					level >= " . ($l - 5);
			
			if($quest) {
				$qNPC .= "
					UNION
				
					SELECT
						id
					
					 FROM 
						npc FORCE KEY(PRIMARY)
					 WHERE
						$x1 BETWEEN x1 AND x2 AND
						$y1 BETWEEN y1 AND y2 AND
						id IN(SELECT id_npc FROM quest_npc_item WHERE id_quest=" . $quest . ") 
						#AND level BETWEEN " . ($l - 5) . " AND " . ($l + 5);
			}
			
			if ($quest_diaria) {
				$qNPC .= "
					UNION
				
					SELECT
						id
					
					 FROM 
						npc FORCE KEY(PRIMARY)
					 WHERE
						$x1 BETWEEN x1 AND x2 AND
						$y1 BETWEEN y1 AND y2 AND
						id IN(SELECT id_npc FROM quest_guild_npc_item WHERE id_quest_guild=" . $quest_diaria . ") 
						#AND level BETWEEN " . ($l - 5) . " AND " . ($l + 5);
			}
			
			$rNPC = Recordset::query($qNPC . " ORDER BY RAND() LIMIT 1")->row_array();
			
			return isset($rNPC['id']) && $rNPC['id'] ? $rNPC['id'] : false;
		}

		static function getNPCFromPerimeterE($x1, $y1, $id) {
			
			$qNPC = "
				SELECT
					id
				
				 FROM 
					npc
				 WHERE
				 	$x1 BETWEEN x1 AND x2 AND
					$y1 BETWEEN y1 AND y2 AND
					id=" . $id;

			$rNPC = Recordset::query($qNPC, true)->row_array();
			
			return isset($rNPC['id']) && $rNPC['id'] ? $rNPC['id'] : false;
		}

		function getATKItem() {
			$x = 0;
			
			while(true) {
				$noUse = false;
				$i = $this->items[round(rand(0, sizeof($this->items) - 1))];

				if($i->hasAttribute('consume_hp') && $i->getAttribute('consume_hp') > absm($this->getAttribute('hp'))) {
					$noUse = true;
				}

				if($i->hasAttribute('consume_sp') && $i->getAttribute('consume_sp') > absm($this->getAttribute('sp'))) {
					$noUse = true;
				}

				if($i->hasAttribute('consume_sta') && $i->getAttribute('consume_sta') > absm($this->getAttribute('sta'))) {
					$noUse = true;
				}
				
				if(!$noUse) {
					return $i;
					break;
				}
				
				if(++$x == 250) {
					/*
					$nullObj = new Item(1);
					$nullObj->skipping = true;
					
					return $nullObj;
					*/

					$item			= new Item(rand(4, 5));
					$item->precisao	= 100;
					$item->setLocalAttribute('precisao', 100);

					return $item;
					
					break;
				}
			}
		}
		
		function removeItem($item) { }
		
		function consumeHP($value) {
			$this->setLocalAttribute('hp', $this->getAttribute('hp') - $value);
			$this->setLocalAttribute('less_hp', $this->getAttribute('less_hp') + $value);
			
			if($this->npc_evento) {
				Recordset::update('evento_npc_equipe', array(
					'less_hp'		=> array('escape' => false, 'value' => 'less_hp+' . $value),
				), array(
					'id_equipe'		=> $this->id_equipe,
					'id_evento_npc'	=> $this->id,
					'id_evento'		=> $this->id_evento
				));
			}
			
			if($this->npc_vila) {
				Recordset::update('npc_vila', array(
					'less_hp'	=> array('escape' => false, 'value' => 'less_hp+' . $value)
				), array(
					'id'	=> $this->id
				));
				
				$this->npc_vila_at['less_hp'] += $value;
			}

			if ($this->npc_guerra_s) {
				Recordset::update('guerra_ninja_npcs', array(
					'less_hp'	=> array('escape' => false, 'value' => 'less_hp+' . $value)
				), array(
					'id'	=> $this->id
				));

				$this->update_npc_war_stats();
			}
		}
		
		function consumeSP($value) {
			$this->setLocalAttribute('sp', $this->getAttribute('sp') - $value);
			$this->setLocalAttribute('less_sp', $this->getAttribute('less_sp') + $value);

			if($this->npc_evento) {
				Recordset::update('evento_npc_equipe', array(
					'less_sp'		=> array('escape' => false, 'value' => 'less_sp+' . $value),
				), array(
					'id_equipe'		=> $this->id_equipe,
					'id_evento_npc'	=> $this->id,
					'id_evento'		=> $this->id_evento
				));	
			}

			if($this->npc_vila) {
				Recordset::update('npc_vila', array(
					'less_sp'	=> array('escape' => false, 'value' => 'less_sp+' . $value)
				), array(
					'id'	=> $this->id
				));
				
				$this->npc_vila_at['less_sp'] += $value;
			}

			if ($this->npc_guerra_s) {
				Recordset::update('guerra_ninja_npcs', array(
					'less_sp'	=> array('escape' => false, 'value' => 'less_sp+' . $value)
				), array(
					'id'	=> $this->id
				));

				$this->update_npc_war_stats();
			}
		}

		function consumeSTA($value) {
			$this->setLocalAttribute('sta', $this->getAttribute('sta') - $value);
			$this->setLocalAttribute('less_sta', $this->getAttribute('less_sta') + $value);

			if($this->npc_evento) {
				Recordset::update('evento_npc_equipe', array(
					'less_sta'		=> array('escape' => false, 'value' => 'less_sta+' . $value),
				), array(
					'id_equipe'		=> $this->id_equipe,
					'id_evento_npc'	=> $this->id,
					'id_evento'		=> $this->id_evento
				));				
			}

			if($this->npc_vila) {
				Recordset::update('npc_vila', array(
					'less_sta'	=> array('escape' => false, 'value' => 'less_sta+' . $value)
				), array(
					'id'	=> $this->id
				));
				
				$this->npc_vila_at['less_sta'] += $value;
			}

			if ($this->npc_guerra_s) {
				Recordset::update('guerra_ninja_npcs', array(
					'less_sta'	=> array('escape' => false, 'value' => 'less_sta+' . $value)
				), array(
					'id'	=> $this->id
				));

				$this->update_npc_war_stats();
			}
		}
		
		function Loss() {
			if($this->npc_evento) {
				Recordset::update('evento_npc_equipe', array(
					'morto'	=> '1'
				), array(
					'id_equipe'		=> $this->id_equipe,
					'id_evento_npc'	=> $this->id,
					'id_evento'		=> $this->id_evento
				));	
			} elseif($this->npc_evento_global) {
				Recordset::update('evento_npc_evento', array(
					'morto_global'	=> '1'
				), array(
					'id_evento_npc'	=> $this->id,
					'id_evento'		=> $this->id_evento
				));					
			}
		}
		
		function kai() {
			
		}	

		function getElementosA() {
			return $this->elementos;
		}
		
		function hasItem($k) {
			if(is_array($k)) {
				$ok = false;
				
				foreach($k as $v) {
					if(isset($this->items_id[$v])) {
						$ok = true;
						break;
					}
				}
				
				return $ok;
			} else {
				return isset($this->items_id[$k]) ? true : false;
			}
		}

		function getAttribute($k) {
			if(!isset($this->atl_keys[$k])) {
				if(!isset($this->at_keys[$k])) {
					if(!isset($this->ats_keys[$k])) {
						print_r($this->atl_keys);
						throw new Exception('NPC Attribute "' . $k . '" not found');
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

		function setLocalAttribute($k, $v) {
			if(!isset($this->atl_keys[$k])) {
				$this->atl_keys[$k] = 1;
			}
		
			$this->atl[$k] = $v;

			$this->$k = $v;
		}
		
		function hasAttribute($k) {
			if(isset($this->atl_keys[$k]) || isset($this->at_keys[$k]) || isset($this->ats_keys[$k])) {
				return true;
			} else {
				return false;
			}
		
		}

		function do_key_mapping() {
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

		function update_npc_war_stats() {
			$npc	= Recordset::query('SELECT total_hp, less_hp, less_sp, less_sta FROM guerra_ninja_npcs WHERE id=' . $this->id)->row();

			$this->less_hp	= $npc->less_hp;
			$this->less_sp	= $npc->less_sp;
			$this->less_sta	= $npc->less_sta;

			$this->max_hp	= $npc->total_hp;
			$this->max_sp	= $npc->total_hp * 10;
			$this->max_sta	= $npc->total_hp * 10;

			$this->setLocalAttribute('less_hp',  $npc->less_hp);
			$this->setLocalAttribute('less_sp',  $npc->less_sp);
			$this->setLocalAttribute('less_sta', $npc->less_sta);

			$this->setLocalAttribute('max_hp',  $this->max_hp);
			$this->setLocalAttribute('max_sp',  $this->max_sp);
			$this->setLocalAttribute('max_sta', $this->max_sta);

			$this->setLocalAttribute('hp',  $this->max_hp  - $npc->less_hp);
			$this->setLocalAttribute('sp',  $this->max_sp  - $npc->less_sp);
			$this->setLocalAttribute('sta', $this->max_sta - $npc->less_sta);
		}
	}
