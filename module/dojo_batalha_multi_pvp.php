<div class="titulo-secao titulo-secao-dojo titulo-secao-dojo-3"><p><?php echo t('menus.equipe_batalha_pvp')?></p></div><br /><br />
<?php
	$battle		= Recordset::query('SELECT * FROM batalha_multi_pvp WHERE id=' . $basePlayer->id_batalha_multi_pvp)->row_array();
	$is_1x1		= $basePlayer->id_random_queue && $basePlayer->id_random_queue_type == 1;

	if($basePlayer->id_random_queue) {
		for($f = 0; $f <= 1; $f++) {
			$key	= $f ? 'e' : 'p';
			
			for($i = 1; $i <= 4; $i++) {
				if($battle[$key . $i]) {
					$object	= unserialize($battle[$key . $i]);
					
					if($object->id == $basePlayer->id) {
						if($f == 0) {
							$word_p	= 'p';
							$word_e	= 'e';
							
							$range_p	= $battle['range_a'];
							$range_e	= $battle['range_b'];
						} else {
							$word_p	= 'e';
							$word_e	= 'p';							

							$range_p	= $battle['range_b'];
							$range_e	= $battle['range_a'];
						}
						
						break 2;
					}
				}
			}
		}
	} else {
		$word_p	= $basePlayer->id_equipe == $battle['id_equipe_a'] ? 'p' : 'e';
		$word_e	= $basePlayer->id_equipe == $battle['id_equipe_a'] ? 'e' : 'p';		
	}

	$items 						= $basePlayer->getItems(array(1, 2, 5, 6, 16, 17, 20, 21, 23, 24, 26, 37, 39));
	$sorted_items				= array();
	$float_inc					= 0.001;
	$_SESSION['multi_pvp_key']	= uniqid();
	$with_team_title			= false;

	$mod_properties				= array(
		'tai','ken', 'nin', 'gen', 'agi', 'con', 'forc', 'ene', 'inte', 'res', 'atk_fisico', 'atk_magico', 'def_base', 'def_fisico', 'def_magico',
		'prec_fisico', 'prec_magico', 'crit_min', 'crit_max', 'crit_total','esq_min', 'esq_max', 'esq_total','esq', 'det', 'conv', 'conc','esquiva',
		'bonus_sp', 'bonus_hp', 'bonus_sta'
	);
	
	$mod_properties_percent		= array('conc', 'conv','esquiva');
	$player_role				= '';

	$team_role					= Player::getFlag('equipe_role', $basePlayer->id);
	$team_role_lvl				= Player::getFlag('equipe_role_1_lvl', $basePlayer->id);
	
	switch($basePlayer->id_classe_tipo) {
		case 1:	$player_role	= 'tai';	break;
		case 2:	$player_role	= 'nin';	break;
		case 3:	$player_role	= 'gen';	break;
		case 4:	$player_role	= 'ken';	break;
	}
	
	if($word_p == 'p') {
		$ids_p	= odds();
		$ids_e	= evens();
	} else {
		$ids_p	= evens();
		$ids_e	= odds();
	}
	
	foreach($items as $item) {
		$item->setPlayerInstance($basePlayer);
		
		if(in_array($item->id_habilidade, array(2,3))) {
			$sorted_items[(string)($item->atk_magico + $float_inc)]	= $item;
		} else {
			$sorted_items[(string)($item->atk_fisico + $float_inc)]	= $item;				
		}

		$float_inc	+= 0.001;			
	}
	
	ksort($sorted_items);
?>
<style>
	.pvp-versus-box b.player-name {
		display: block;
	}
	
	.pvp-versus-box .team-title {
		margin-bottom: 10px;
		font-size: 14px;
		color: #ff7d1c !important;
		clear: both
	}

	.player-box {
		float: left;
		margin-bottom: 5px;
		width: 125px
	}
	
	.player-box .player-dead {
		width: 125px;
		height: 280px;
		position: absolute;
		background-color: #000;
		opacity: .6
	}
	
	.player-dead-highlight {
		background-color: #707cff !important
	}

	.pvp-mod-box {
		margin-bottom: 5px;
		height: 44px
	}
	
	.pvp-stat-box, .pvp-role-box, .pvp-buff-box {
		float: left
	}

	.pvp-role-box {
		margin-top: -22px;
		width: 20px;
		height: 20px;
		text-align: left;
	}
	
	.pvp-buff-box {
		width: 100px;
		height: 44px;		
	}
	
	.pvp-buff-box div {
		float: left;
		margin: 0px 2px 2px 0px
	}
	
	.pvp-role-box, .pvp-stat-box {
		float: left;
		margin-right: 2px
	}
	
	.pvp-atk-list .atk {
		float: left;
		margin: 4px;
		cursor: pointer
	}
	
	.enemy-highlight {
		background-color: red;
	}

	.player-highlight {
		background-color: green;
	}

	#pvp-current {
		text-align: center;
		margin-top: 10px
	}	
	
	.player-utility {
		width: 63px;
		margin-right: 5px;
		float: left;
	}

	.player-utility .utility {
		background-image: url(images/layout/bg_habilidades.png);
		width: 63px;
		height: 63px;
		float: left;
		position: relative;
		cursor: pointer;
	}

	.player-utility .utility .current {
		z-index: 2;
		position: relative;		
	}

	.player-utility .utility .current img {
		margin-top: 5px
	}

	.player-utility .selector {
		position: absolute;
		padding: 4px 4px 4px 10px;
		border-radius: 5px;
		background-color: #020f1a;
		top: 50%;
		margin-top: -22px;
		opacity: 0;
		z-index: 1
	}

	.player-utility .selector .atk {
		display: inline-block !important;
	}

	.player-box-1x {
		width: 270px !important
	}

	.player-box-1x .pvp-buff-box {
		width: 173px
	}

	.player-box-1x .player-box-canvas {
		float: left;
		margin-left: 5px;
		width: 195px
	}

	.player-box-r.player-box-1x .player-box-canvas, .player-box-r.player-box-1x .player-utility {
		float: right !important
	}

	.player-box-r.player-box-1x .player-box-canvas {
		margin-left: 0px;
		margin-right: 5px;		
	}

	.player-box-1x .pvp-role-box {
		display: none
	}
</style>
<div id="d-finished-msg" style="margin: 0px auto; width: 730px; margin-bottom: 10px;"></div>
<div id="painel-pvp-multi-container">
	<div id="painel-pvp-multi-players">
		<div class="pvp-versus-box pvp-versus-left">
			<?php if(!$basePlayer->id_random_queue): ?>
				<div class="team-title"><?php echo $basePlayer->nome_equipe ?></div>
			<?php elseif(!$is_1x1): ?>
				<div class="team-title">Equipe Nível <?php echo $range_p ?></div>
			<?php endif ?>
			<?php for($f = 1; $f <= 4; $f++): ?>
				<?php
					$player		= unserialize($battle[$word_p . $f]);
					
					if(!$player) {
						continue;
					}
					
					$role_img	= "layout/equipe_roles/nenhum.png";
					$has_role	= false;

					if($player->role_id != "") {
						$role		= Recordset::query('SELECT id, imagem FROM item WHERE id_tipo=22 AND id_habilidade=' . $player->role_id . ' AND ordem=' . ($player->role_lvl >= 1 ? $player->role_lvl : 1))->row_array();
						$role_img	= 'layout/' . $role['imagem'];
						$has_role	= true;
					}
				?>
				<div class="player-box <?php echo $is_1x1 ? 'player-box-1x' : '' ?> player-box-<?php echo $player->id ?> player-box-player <?php echo $player->alive ? 'player-alive' : '' ?>" data-target-id="<?php echo $ids_p[$f - 1] ?>" data-id="<?php echo $player->id ?>">
					<?php if ($is_1x1): ?>
						<?php
							$tpl_is_enemy	= false;
							$tpl_player		= $player;

							require 'template/lateral_multi.php';
						?>
					<?php endif ?>
					<div class="player-box-canvas">
						<div class="player-dead" style="<?php echo $player->alive ? 'display: none' : ''  ?>" data-target-id="<?php echo $ids_p[$f - 1] ?>" data-id="<?php echo $player->id ?>"></div>
						<div class="pvp-mod-box">
							<div class="pvp-stat-box">
								<img src="<?php echo img('layout/stats.png') ?>" width="20" />
							</div>
							<div class="pvp-buff-box"></div>
							<div class="pvp-role-box">
								<img width="20" style="position: absolute" src="<?php echo img($role_img) ?>" id="i-role-<?php echo $player->id ?>" />
								<?php if($has_role): ?>
									<?php specialization_tooltip($role['id'], 'i-role-' . $player->id, null) ?>
								<?php endif ?>
							</div>
							<div class="break"></div>
						</div>
						<?php if ($is_1x1): ?>
							<?php echo player_imagem_ultimate($player->id); ?>
						<?php else: ?>
							<img src='<?php echo player_imagem($player->id, "pequena"); ?>' width="129" height="126" />
						<?php endif ?>
						<span class="player-name">
							<b class="laranja" style="font-size:13px"><?php echo $player->name ?></b><br />
							<?php echo $player->graduation ?><br />
							Level: <?php echo $player->level ?>
						</span>
						
						<div class="player-status">
							<?php barra_exp5($player->hp->current,  $player->hp->max,  119, 'HP:  ' .  $player->hp->current, "#2C531D", "#537F3D", 3, "id='barHP'", "hp") ?>
							<?php barra_exp5($player->sp->current,  $player->sp->max,  119, 'CHK:  ' . $player->sp->current, "#2C531D", "#537F3D", 3, "id='barSP'", "sp") ?>
							<?php barra_exp5($player->sta->current, $player->sta->max, 119, 'STA: ' .  $player->sta->current,"#2C531D", "#537F3D", 3, "id='barSTA'", "sta") ?>
						</div>
					</div>
				</div>
			<?php endfor ?>
		</div>
		<div id="pvp-separator">
			<div id="pvp-log"><div id="pvp-log-data"></div></div>
			<div id="pvp-current"></div>
		</div>
		<div class="pvp-versus-box pvp-versus-right">
			<?php for($f = 1; $f <= 4; $f++): ?>
				<?php
					$player		= unserialize($battle[$word_e . $f]);

					if(!$player) {
						continue;
					}

					$role_img	= "layout/equipe_roles/nenhum.png";
					$has_role	= false;

					if($player->role_id != "") {
						$role		= Recordset::query('SELECT id, imagem FROM item WHERE id_tipo=22 AND id_habilidade=' . $player->role_id . ' AND ordem=' . ($player->role_lvl >= 1 ? $player->role_lvl : 1))->row_array();
						$role_img	= 'layout/' . $role['imagem'];
						$has_role	= true;
					}
				?>
				<?php if(!$with_team_title && !$basePlayer->id_random_queue): ?>
						<div class="team-title"><?php echo Recordset::query('SELECT nome FROM equipe WHERE id=(SELECT id_equipe FROM player WHERE id=' . $player->id . ')')->row()->nome ?></div>
						<?php $with_team_title	= true;	?>
				<?php endif ?>
				<?php if(!$with_team_title && $basePlayer->id_random_queue && !$is_1x1): ?>
					<div class="team-title" style="color: #26b1f9 !important">Equipe Nível <?php echo $range_e ?></div>
					<?php $with_team_title	= true;	?>
				<?php endif ?>

				<div class="player-box player-box-r <?php echo $is_1x1 ? 'player-box-1x' : '' ?> player-box-<?php echo $player->id ?> player-box-enemy <?php echo $player->alive ? 'player-alive' : '' ?>" data-target-id="<?php echo $ids_e[$f - 1] ?>" data-id="<?php echo $player->id ?>">
					<?php if ($is_1x1): ?>
						<?php
							$tpl_is_enemy	= true;
							$tpl_player		= $player;

							require 'template/lateral_multi.php';
						?>
					<?php endif ?>
					<div class="player-box-canvas">
						<div class="player-dead" style="<?php echo $player->alive ? 'display: none' : ''  ?>" data-target-id="<?php echo $ids_p[$f - 1] ?>" data-id="<?php echo $player->id ?>"></div>
						<div class="pvp-mod-box">
							<div class="pvp-stat-box">
								<img src="<?php echo img('layout/stats.png') ?>" width="20" />
							</div>
							<div class="pvp-buff-box"></div>
							<div class="pvp-role-box">
								<img width="20" style="position: absolute" src="<?php echo img($role_img) ?>" id="i-role-<?php echo $player->id ?>" />
								<?php if($has_role): ?>
									<?php specialization_tooltip($role['id'], 'i-role-' . $player->id, null) ?>
								<?php endif ?>
							</div>
							<div class="break"></div>
						</div>					
						<?php if ($is_1x1): ?>
							<?php echo player_imagem_ultimate($player->id); ?>
						<?php else: ?>
							<img src='<?php echo player_imagem($player->id, "pequena"); ?>' width="129" height="126" />
						<?php endif; ?>
						<span class="player-name">
							<b class="laranja" style="font-size:13px"><?php echo $player->name ?></b><br />
							<?php echo $player->graduation ?><br />
							Level: <?php echo $player->level ?>
						</span>
						<div class="player-status">
							<?php barra_exp5($player->hp->current,  $player->hp->max,  119, 'HP:  ' .  $player->hp->current, "#2C531D", "#537F3D", 3, "id='barHP'", "hp") ?>
							<?php barra_exp5($player->sp->current,  $player->sp->max,  119, 'CHK:  ' . $player->sp->current, "#2C531D", "#537F3D", 3, "id='barSP'", "sp") ?>
							<?php barra_exp5($player->sta->current, $player->sta->max, 119, 'STA: ' .  $player->sta->current,"#2C531D", "#537F3D", 3, "id='barSTA'", "sta") ?>
						</div>
					</div>
				</div>
			<?php endfor ?>
		</div>
		<div class="break"></div>
	</div>
	<div class="break"></div>
	<div class="pvp-atk-bar-container">
		<div class="pvp-atk-bar">
			<div class="titulo-secao3">
				<p><?php echo t('jogador_vip.jv37')?></p>
				<div class="pvp-atk-icons">
					<div class="pvp-atk-filter" data-filter="tai">Tai</div>
					<div class="pvp-atk-filter" data-filter="ken">Buk</div>
					<div class="pvp-atk-filter" data-filter="nin">Nin</div>
					<div class="pvp-atk-filter" data-filter="gen">Gen</div>
					<div class="pvp-atk-filter" data-filter="neardist"><?php echo t('geral.g49')?></div>
					<?php if (!$is_1x1): ?>
						<div class="pvp-atk-filter" data-filter="medicinal">Medicinal</div>
						<div class="pvp-atk-filter" data-filter="kinjutsu">Kinjutsus</div>
						<?php if($basePlayer->id_cla): ?>
						<div class="pvp-atk-filter" data-filter="clan">Clãs</div>
						<?php endif; ?>
						<?php if($basePlayer->getAttribute('portao')): ?>
						<div class="pvp-atk-filter" data-filter="gate"><?php echo t('geral.g50')?></div>
						<?php endif; ?>
						<?php if($basePlayer->id_selo): ?>
						<div class="pvp-atk-filter" data-filter="seal"><?php echo t('geral.g51')?></div>
						<?php endif; ?>
						<?php if($basePlayer->id_invocacao): ?>
						<div class="pvp-atk-filter" data-filter="summon"><?php echo t('geral.g52')?></div>
						<?php endif; ?>
						<?php if($basePlayer->sennin): ?>
						<div class="pvp-atk-filter" data-filter="sennin">M. Sennin</div>
						<?php endif; ?>
						<?php if($basePlayer->bijuu): ?>
						<div class="pvp-atk-filter" data-filter="bijuu">Bijuus</div>
						<?php endif; ?>
					<?php endif ?>
				</div>
			</div>
			<div class="pvp-atk-list">
				<?php foreach($sorted_items as $item): ?>
					<?php
						if(!$item->dojo_ativo) {
							continue;
						}

						if(($team_role == 1 && $item->id_tipo == 37) || ($team_role == 4 && $item->id_tipo == 24)) {
							continue;
						}

						// Armas não equipadas
						if($item->id_tipo == 2 && !$item->equipado) {
							continue;
						}
						
						$group	= "";
						$level	= "";
						$role	= "";
						
						switch($item->id_tipo) {
							case 1:
							case 2:
							case 39:
								$role	= 'neardist';

								if($item->sem_turno) {
									$role .= ' atk-buff';
								}
								
								break;
						
							case 24:
								$role	= 'medicinal';
								
								break;
							case 16:
							case 17:
							case 20:
							case 21:
							case 23:
							case 26:
							case 37:
								$group	= $item->id_tipo;
								$level	= $item->ordem;
							
								switch($item->id_tipo) {
									case 16:
										$role	= 'clan';
									
										break;

									case 17:
										$role	= 'gate';
									
										break;

									case 20:
										$role	= 'seal';
									
										break;

									case 21:
										$role	= 'summon';
									
										break;

									case 23:
										$role	= 'bijuu';
									
										break;

									case 26:
										$role	= 'sennin';
									
										break;

									case 37:
										$role	= 'kinjutsu';
									
										break;
								}
								
								$role	.= ' atk-buff';
							
								break;
							
							case 5:
							case 6:
								switch($item->id_habilidade) {
									case 1:	$role	= 'tai';	break;
									case 2:	$role	= 'nin';	break;
									case 3:	$role	= 'gen';	break;
									case 4:	$role	= 'ken';	break;
								}
								
								if($item->sem_turno) {
									$role .= ' atk-nt';
								}
							
								break;
						}
					?>
					<div class="atk atk-<?php echo $role ?>" data-id="<?php echo $item->id ?>" <?php if($group): ?>data-group="<?php echo $group ?>"<?php endif; ?> <?php if($level != ""): ?>data-level="<?php echo $level ?>"<?php endif; ?>>
						<img class="icon" src="<?php echo img($item->id_tipo == 23 || $item->id_tipo == 39 ? 'layout/bijuus-batalha/' . $item->id . '.png' : "layout/" . $item->imagem) ?>" width="48" />
					</div>
				<?php endforeach; ?>
				<div class="break"></div>
			</div>
		</div>
	</div>
	<div class="bottom"></div>
</div>
<script type="text/javascript">
	var	__dyn_atk	= [];

	(function () {
		var not_my_action		= false;
		var	can_target			= true;
		var target_p			= null;
		var target_e			= null;
		var elements			= [];
		var elements_r			= [];
		var elements_w			= [];
		var player_stat_tooltip	= [];
		var _items				= {};
		var _turns				= {};
		var is_animating		= false;
		var properties			= [];
		var properties_percent	= [];
		var had_sound			= false;
		var me					= {
			atkf:	<?php echo $basePlayer->getAttribute('atk_fisico_calc') ?>,
			atkm:	<?php echo $basePlayer->getAttribute('atk_magico_calc') ?>,
			cmin:	<?php echo $basePlayer->getAttribute('crit_min_calc') ?>,
			cmax:	<?php echo $basePlayer->getAttribute('crit_max_calc') ?>,
			ctotal:	<?php echo $basePlayer->getAttribute('crit_total_calc') ?>,
			crit:	<?php echo $basePlayer->getAttribute('conc_calc') ?>,
			esquiva: <?php echo $basePlayer->getAttribute('esquiva_calc') ?>,
			def:	<?php echo $basePlayer->getAttribute('def_base_calc') ?>,
			deff:	<?php echo $basePlayer->getAttribute('def_fisico_calc') ?>,
			defm:	<?php echo $basePlayer->getAttribute('def_magico_calc') ?>,
			id:		<?php echo $basePlayer->id ?>
		}
		
		function _round(v, d) {
			var	s	= v.toString().split('.');
			
			if(s.length > 1) {
				var r	= s[0] + '.';
				
				if(s[1].length > d) {
					r	+= s[1].substr(0, d);
				} else {
					r	+= s[1];
				}
				
				return parseFloat(r);
			} else {
				return parseInt(s);				
			}			
		}

		var properties_images	= {
			tai:			'layout/icones/tai.png',
			ken:			'layout/icones/ken.png',
			nin:			'layout/icones/nin.png',
			gen:			'layout/icones/gen.png',
			forc:			'layout/icones/forc.png',
			agi:			'layout/icones/agi.png',
			inte:			'layout/icones/inte.png',
			con:			'layout/icones/conhe.png',
			res:			'layout/icones/defense.png',
			def_base:		'layout/icones/shield.png',
			def_fisico:		'layout/icones/def_fisico.png',
			def_magico:		'layout/icones/def_magico.png',
			ene:			'layout/icones/ene.png',
			atk_fisico:		'layout/icones/atk_fisico.png',
			atk_magico:		'layout/icones/atk_magico.png',
			prec_fisico:	'layout/icones/prec_tai.png',
			prec_magico:	'layout/icones/prec_nin_gen.png',
			conv:			'layout/icones/convic.png',
			esquiva:		'layout/icones/esquiva.png',
			conc:			'layout/icones/target2.png',
			esq:			'layout/icones/esquiva.png',
			det:			'layout/icones/deter.png',
			bonus_hp:		'layout/icones/p_hp.png',
			bonus_sp:		'layout/icones/p_chakra.png',
			bonus_sta:		'layout/icones/p_stamina.png',
		}

		<?php foreach($mod_properties as $property): ?>
			<?php
				$name	= tb('formula.' . $property);
				
				if(!$name) {
					$name	= t('at.' . $property);
				}			
			?>
			properties['<?php echo $property ?>']	= '<?php echo $name ?>';
		<?php endforeach ?>

		<?php foreach($mod_properties_percent as $property): ?>
			properties_percent['<?php echo $property ?>']	= true;
		<?php endforeach ?>

		<?php foreach(Recordset::query('SELECT id, nome AS name FROM elemento', true)->result_array() as $element): ?>
			elements[<?php echo $element['id'] ?>]		= "<?php echo $element['name'] ?>";
			elements_r[<?php echo $element['id'] ?>]	= [];
			elements_w[<?php echo $element['id'] ?>]	= [];
			
			<?php foreach(Recordset::query('SELECT a.id FROM elemento a JOIN elemento_fraqueza b ON b.id_elemento_fraco=a.id WHERE b.id=' . $element['id'], true)->result_array() as $weakness): ?>
			elements_w[<?php echo $element['id'] ?>].push(<?php echo $weakness['id'] ?>);
			<?php endforeach; ?>
			<?php foreach(Recordset::query('SELECT a.id FROM elemento a JOIN elemento_resistencia b ON b.id_elemento_resiste=a.id WHERE b.id=' . $element['id'], true)->result_array() as $resistance): ?>
			elements_r[<?php echo $element['id'] ?>].push(<?php echo $resistance['id'] ?>);
			<?php endforeach; ?>			
		<?php endforeach; ?>

		<?php if(!$is_1x1): ?>
			$('.player-box-player').on('click', function () {
				if(not_my_action || !can_target || !$(this).hasClass('player-alive')) {
					return;
				}
				
				$('.player-box-player').removeClass('player-highlight');
				$(this).addClass('player-highlight');
				
				target_p	= $(this).data('target-id');
			});

			$('.player-box-enemy').on('click', function () {
				if(not_my_action || !can_target || !$(this).hasClass('player-alive')) {
					return;
				}
				
				$('.player-box-enemy').removeClass('enemy-highlight');
				$(this).addClass('enemy-highlight');
				
				target_e	= $(this).data('target-id');
			});
		<?php else: ?>
			target_e	= <?php echo $ids_e[0] ?>;
		<?php endif; ?>
	
		$('.pvp-atk-list .atk, .player-utility .atk').on('click', function () {
			var	_			= $(this);

			if(this.clicked || _turns[_.data('id')]) {
				return;
			}
		
			var _this		= this;
			this.clicked	= true;
			var action		= 1;
			var target		= target_e;
			_is_attacking	= false;
			is_animating	= true;

			if(_.hasClass('atk-nt')) {
				action	= 2;
			}

			if(_.hasClass('atk-buff')) {
				action	= 3;
			}

			if(_.hasClass('atk-medicinal')) {
				action	= 4;
				target	= target_p;
			}

			if(_.hasClass('atk-kinjutsu')) {
				action	= 5;
			}

			if(action == 5) {
				var data	= {'key': '<?php echo $_SESSION['multi_pvp_key'] ?>', action: action, item: _.data('id'), friendly: target_p, enemy: target_e};				
			} else {
				var data	= {'key': '<?php echo $_SESSION['multi_pvp_key'] ?>', action: action, item: _.data('id'), target: target};
			}

			$.ajax({
				url:		'?acao=dojo_batalha_multi_pvp',
				type:		'post',
				dataType:	'json',
				data:		data,
				success:	function (result) {
					_parse_ping(result);
					_is_attacking	= false;

					if(!result.messages.length && _.hasClass('atk-utility')) {
						$('.player-box-<?php echo $basePlayer->id ?> #player-utility-' + _.data('type') + ' .current img').attr('src', $('img', _).attr('src'));
					}
				},
				error:	function () {
					_is_attacking	= false;
				}
			});
			
			$('img.icon', _).stop().hide('explode').show('drop', {}, 'slow', function () {
				_.stop().effect('highlight');
				
				_this.clicked	= false;
				is_animating	= false;
			});
		});
		
		$('.pvp-atk-bar').on('click', '.pvp-atk-filter', function () {
			if(this.no_attacks || is_animating) {
				return;
			}

			var	_		= $(this);
			
			$('.pvp-atk-bar .pvp-atk-filter').removeClass('pvp-atk-filter-selected');
			_.addClass('pvp-atk-filter-selected');
			
			$('.pvp-atk-bar .atk').each(function () {
				if(!$(this).hasClass('atk-' + _.data('filter'))) {
					$(this).stop().hide();
				} else {
					$(this).stop().show('fade');				
				}
			});
		});
		
		$('.pvp-atk-bar .pvp-atk-filter').each(function () {
			if(!$('.pvp-atk-bar .atk-' + $(this).data('filter')).length) {
				//$(this).css('opacity', .4);
				$(this).hide();
				this.no_attacks	= true;
			}
			
			if($(this).data('filter') == '<?php echo $player_role ?>') {
				$(this).trigger('click');
			}
		});
		
		$('.player-box').each(function () {
			var _	= $(this);
			var d	= null;
		
			$('.pvp-stat-box', _).on('mouseover', function () {
				d	= $(document.createElement('DIV')).addClass('ex_tooltip');
				d.html(player_stat_tooltip[_.data('id')] || 'Aguarde...');
				
				$(this).append(d.show());
			}).on('mouseout', function () {
				d.remove();
			});
		});
		
		function _parse_ping(ping) {
			var log			= '';
			not_my_action	= true;
			can_target		= true;
			_turns			= ping.turns;
		
			if(ping.who_is_attacked) {
				can_target	= false;
				log			= ping.who_is_attacked.name + " <?php echo t('geral.g53')?> " + ping.who_is_attacking.name;

				if(ping.who_is_attacked.id == '<?php echo $basePlayer->id ?>') {
					log				+= "<br /><?php echo t('geral.g54')?>";
					not_my_action	= false;
				}
			} else {
				if(ping.who_is_attacking.id == '<?php echo $basePlayer->id ?>') {
					log				= "<?php echo t('geral.g54')?>";
					not_my_action	= false;
				} else {
					log	= ping.who_is_attacking.name + " <?php echo t('geral.g55')?>";
				}
			}
			
			if(not_my_action) {
				<?php if(!$is_1x1): ?>
					target_p	= null;
					target_e	= null;
				<?php endif; ?>
				had_sound	= false
			
				$('.player-box-player').removeClass('player-highlight');
				$('.player-box-enemy').removeClass('enemy-highlight');
				
				$('.player-box-player .player-dead').removeClass('player-dead-highlight');
			} else {
				if(!had_sound) {
					had_sound	= true

					<?php if(isset($_SESSION['usuario']['sound']) && $_SESSION['usuario']['sound']): ?>
						$(document.body).append('<audio autoplay><source src="<?php echo img('media/battle.wav') ?>" type="audio/wav" /></audio>');
					<?php endif; ?>
				}
			}
			
			if(ping.invalid_key) {
				jalert('<?php echo t('geral.g56')?>');
			}

			if(ping.invalid_action) {
				jalert('<?php echo t('geral.g57')?>');
			}

			if(ping.invalid_item) {
				jalert('<?php echo t('geral.g58')?>');
			}
			
			if(ping.not_my_action) {
				jalert('<?php echo t('geral.g59')?>');
			}

			// Adjusts transparency to show item avaliability			
			$('.pvp-atk-list .atk').each(function () {
				var	_				= $(this);
				var id				= parseInt(_.data('id'));
				var should_disable	= false;
				
				for(var i in ping.turns) {
					if(i == id) {
						$('img.icon', _).css({opacity: .3});
						should_disable	= true;

						break;
					}
				}
				
				if(!should_disable) {
					$('img.icon', _).css({opacity: 1});
				}
			})
			
			for(var i in ping.players) {
				var player	= ping.players[i];
				
				setPValue2(player.hp.current,  (player.hp.max  || 1), "HP",		$('.player-box-' + player.id + ' #barHP'),  1);
				setPValue2(player.sp.current,  (player.sp.max  || 1), "CHK",	$('.player-box-' + player.id + ' #barSP'),  1);
				setPValue2(player.sta.current, (player.sta.max || 1), "STA",	$('.player-box-' + player.id + ' #barSTA'), 1);	
				
				if(player.alive) {
					$('.player-box-' + player.id + ' .player-dead').hide();

					if(!$('.player-box-' + player.id).hasClass('player-alive')) {
						$('.player-box-' + player.id).addClass('player-alive');
					}
				} else {
					$('.player-box-' + player.id + ' .player-dead').show();
					$('.player-box-' + player.id).removeClass('player-alive');
				}
				
				$('#pvp-log-data').html(ping.log).scrollTop(1000000);
				
				var	crits_left	= (player.crits.total - (player.crits.used || 0));
				var esqs_left	= (player.esqs.total - (player.esqs.used || 0));

				// Will update clan type buff
				if(player.id == me.id) {
					var ids_to_lock	= [];
				
					for(var i in player.mods) {
						var mod	= player.mods[i];
						
						if(mod.multiplier == "clan") {
							for(var g in mod.parents) {
								ids_to_lock.push(mod.parents[g]);
							}
						}
					}
					
					$('.pvp-atk-list .atk').each(function () {
						var _	= $(this);
					
						ids_to_lock.forEach(function (id) {
							if(id == _.data('id')) {
								$('img.icon', _).css({opacity: .3});
							}
						})
					})
				}
				
				player_stat_tooltip[player.id]	=
					'<b><?php echo t('jogador_vip.jv34') ?></b><hr />' +
					'<b class="azul"><?php echo t('formula.atk_fisico')?>:</b> ' + player.atks.f + "<br />" +
					'<b class="azul"><?php echo t('formula.atk_magico')?>:</b> ' + player.atks.m + "<br />" +
					<?php /*'<b class="azul"><?php echo t('formula.def_base')?>:</b> ' + player.def + "<br />" +*/ ?>
					
					'<b class="azul"><?php echo t('formula.def_fisico')?>:</b> ' + player.deff + "<br />" +
					'<b class="azul"><?php echo t('formula.def_magico')?>:</b> ' + player.defm + "<br />" +
					
					/*'<b class="azul"><?php echo t('formula.prec_fisico')?>:</b> ' + player.precs.f + "<br />" +*/
					'<b class="azul"><?php echo t('formula.prec_magico')?>:</b> ' + player.precs.m + "<br />" +
					'<b class="azul"><?php echo t('formula.det') ?>:</b> ' + player.det + "<br />" +
					'<b class="azul"><?php echo t('formula.conv') ?>:</b> ' + player.conv + " ( Time: " + player.conv_team + " )<br />" +

					'<b class="azul"><?php echo t('formula.esq')?>:</b> ' + _round(player.esqs.current, 2) + '% (<span class="color_green">' + _round(player.esqs.original, 2) + '%</span> - <span class="color_red">' + (_round(player.crits_esqs_red, 2)) + '%</span>)' + '<br />' +
					'<b class="azul"><?php echo t('formula.esq')?> ( Min / Máx ):</b> <span>' + _round(player.esqs.min, 2) + '% ~ ' + _round(player.crits.max, 2) + '%</span><br />' +
					

					'<b class="azul"><?php echo t('formula.conc')?>:</b> ' + _round(player.crits.current, 2) + '% (<span class="color_green">' + _round(player.crits.original, 2) + '%</span> - <span class="color_red">' + (_round(player.crits_esqs_red, 2)) + '%</span>)' + '<br />' +
					'<b class="azul"><?php echo t('formula.conc')?> ( Min / Máx ):</b> <span>' + _round(player.crits.min, 2) + '% ~ ' + _round(player.crits.max, 2) + '%</span><br />' +
					'<b class="azul"><?php echo t('formula2.esquiva')?>:</b> ' + player.esquiva + "<br />" +
					'<hr />' + 
					'<b class="azul">Total de críticos:</b> ' + player.crits.total + ' ( ' + (crits_left < 0 ? 0 : crits_left) + ' Restantes )<br />' +
					'<b class="azul">Total de reduções:</b> ' + player.esqs.total + ' ( ' + (esqs_left < 0 ? 0 : esqs_left) + ' Restantes )<br />';
					
				
				// Atualização dos buffs -->
					var	container		= $('.player-box-' + player.id + ' .pvp-buff-box');
					var role_container	= $('.player-box-' + player.id + ' .pvp-role-box');

					/*
					if(!parseInt(role_container.attr('has_tooltip'))) {
						role_container.attr('has_tooltip', 1);
						
						var role_tooltip	= '';
						
						if(player.role_id == '' || player.role_id == null) {
							role_tooltip	= '<b><?php echo t('geral.g60')?></b>';
						} else {
							switch(parseInt(player.role_id)) {
								case 0:
									role_tooltip += '<b><?php echo t('geral.g61')?> Ninjutsu</b><hr />';
									
									if(player.role_lvl > 0) {
										role_tooltip	+=	'<?php echo t('geral.g62')?> <span class="color_green">' + (player.role_lvl * 10) + '%</span> <?php echo t('geral.g63')?> Ninjutsu<br />' +
															'<?php echo t('geral.g65')?> <span class="color_red">' + (player.role_lvl * 10) + '%</span>';
									} else {
										role_tooltip	+= '<?php echo t('geral.g64')?>';
									}
									
									break;
								case 1:
									role_tooltip += '<b><?php echo t('geral.g61')?> Medicinal</b><hr />';

									if(player.role_lvl > 0) {
										role_tooltip	+=	'<?php echo t('geral.g66')?> <span class="color_green">' + (player.role_lvl * 10) + '%</span><br />' +
															'<?php echo t('geral.g67')?> <span class="color_red">' + (player.role_lvl * 5) + '%</span><br />' +
															'<?php echo t('geral.g68')?> <span class="color_red">' + (player.role_lvl * 5) + '%</span>';

									} else {
										role_tooltip	+= '<?php echo t('geral.g64')?>';
									}
									
									break;
								case 2:
									role_tooltip += '<b><?php echo t('geral.g61')?> Genjutsu</b><hr />';
									
									if(player.role_lvl > 0) {
										role_tooltip	+=	'<?php echo t('geral.g62')?> <span class="color_green">' + (player.role_lvl * 10) + '%</span> <?php echo t('geral.g63')?> Genjutsu<br />' +
															'<?php echo t('geral.g65')?> <span class="color_red">' + (player.role_lvl * 10) + '%</span>';
									} else {
										role_tooltip	+= '<?php echo t('geral.g64')?>';
									}

									break;
								case 3:
									role_tooltip += '<b><?php echo t('geral.g61')?> Taijutsu</b><hr />';
									
									if(player.role_lvl > 0) {
										role_tooltip	+=	'<?php echo t('geral.g62')?> <span class="color_green">' + (player.role_lvl * 10) + '%</span> <?php echo t('geral.g63')?> Taijustu<br />' +
															'<?php echo t('geral.g65')?> <span class="color_red">' + (player.role_lvl * 10) + '%</span>';
									} else {
										role_tooltip	+= '<?php echo t('geral.g64')?>';
									}

									break;
							}
						}
						
						role_container.data('tooltip', role_tooltip);
						
						role_container.on('mouseover', function () {
							var	tooltip	= $(document.createElement('DIV')).addClass('ex_tooltip');
							tooltip.html($(this).data('tooltip'));
							
							$(this).append(tooltip.show());
						}).on('mouseout', function () {
							$('.ex_tooltip', this).remove();							
						});
					}
					*/
					if(player.elements.length && !$('.el-tooltip', container).length) {
						var el_tooltip	= '';
						var	can_see_el	= <?php echo $basePlayer->hasItem(21365) ? 'true' : 'false' ?>;
						var d			= $(document.createElement('DIV')).addClass('el-tooltip');
						var i			= $(document.createElement('IMG')).attr('src', '<?php echo img('layout/elements.png') ?>').attr('width', 20).attr('height', 20);
						
						if((player.id != me.id && can_see_el) || (player.id == me.id)) {
							player.elements.forEach(function (element) {
								el_tooltip	+= "<b>" + elements[element] + "</b><br />";
								
								if(elements_r[element].length) {
									var resistances	= [];
									el_tooltip		+= "Resistências: ";
									
									elements_r[element].forEach(function (element) {
										resistances.push(elements[element]);
									});
		
									el_tooltip	+= resistances.join(', ') + "<br />";
								}
		
								if(elements_w[element].length) {
									var weakness	= [];
									el_tooltip		+= "Fraquezas: ";
									
									elements_w[element].forEach(function (element) {
										weakness.push(elements[element]);
									});
		
									el_tooltip	+= weakness.join(', ') + "<br />";							
								}
		
								el_tooltip	+= '<hr />';
							});
						} else {
							el_tooltip	+= '<b><?php echo t('actions.a68')?></b><hr /><?php echo t('actions.a69')?>';
						}
						
						d.data('tooltip', el_tooltip);
						d.on('mouseover', function () {
							var	tooltip	= $(document.createElement('DIV')).addClass('ex_tooltip');
							tooltip.html($(this).data('tooltip'));
							
							$(this).append(tooltip.show());
						}).on('mouseout', function () {
							$('.ex_tooltip', this).remove();
						});

						container.append(d.append(i));
					}
					
					// Check if there are any icons that are not in the buff array
					$('.buff-tooltip', container).each(function () {
						var found	= false;
						var _this	= $(this);
					
						player.mods.forEach(function (mod) {
							if(_this.hasClass('buff-tooltip-' + mod.id)) {
								found	= true;
							}
						});
						
						if(!found) {
							$(this).remove();
						}
					});
					
					<?php if($is_1x1): ?>
						player.mods.forEach(function (mod) {
							if(mod.turns == 'infinity') {
								$('.player-box-' + player.id + ' #player-utility-' + mod.type + ' .current').css('opacity', 1);
							}
						});
					<?php else: ?>
						player.mods.forEach(function (mod) {
							if($('.buff-tooltip-' + mod.id, container).length) { // Ops, exists? then no need to recreate
								return;
							}
						
							var	d	= $(document.createElement('DIV')).addClass('buff-tooltip buff-tooltip-' + mod.id);
							var i	= $(document.createElement('IMG')).attr('src', mod.img).attr('width', 20).attr('height', 20);
							
							container.append(d.append(i))

							var html	= '<b>' + mod.name + '</b><br /><br />';
							
							<?php foreach(array('p', 'e') as $word): ?>
								if(mod.mo.<?php echo $word ?>) {
									html	+= '<?php echo t('item_tooltip.efeitos.' . $word) ?><hr />';
									
									<?php foreach($mod_properties as $property): ?>
									if(mod.mo.<?php echo $word ?>.<?php echo $property ?>) {
										var multiplier	= mod.multiplier;									
										var val			= mod.mo.<?php echo $word ?>.<?php echo $property ?>;
										
										<?php if(in_array($property, $mod_properties_percent)): ?>
											if(multiplier == 'clan') {
												multiplier	= 'percent';
											}
										<?php else: ?>
											if(multiplier == 'clan') {
												multiplier	= '';
											}
										<?php endif ?>
										
										html	+= '<img src="<?php echo img() ?>' + properties_images['<?php echo $property ?>'] + '" align="absmiddle" />&nbsp;' + properties['<?php echo $property ?>'] + ': ' + (val > 0 ? '+' : '') + val + (multiplier == 'percent' ? '%' : '') + '<br />';
									}
									<?php endforeach; ?>									
								}
							<?php endforeach ?>
							
							d.data('tooltip', html);
							d.on('mouseover', function () {
								var	tooltip	= $(document.createElement('DIV')).addClass('ex_tooltip');
								tooltip.html($(this).data('tooltip'));
								
								d.append(tooltip.show());

								// Margin fix -->
									var l 		= tooltip.offset().left;
									var base_l	= $('#pagina').offset().left
									var r		= l + tooltip.width();
									
									if(r >= 1000) {
										tooltip.css({marginLeft: -(r - 1045)});
									}
								// <--
							}).on('mouseout', function () {
								$('.ex_tooltip', this).remove();
							});
						});
					<?php endif; ?>
				// <--
				
				$('.player-box-player .player-dead').each(function () {
					if(this._has_click_cb) {
						return;
					}
					
					this._has_click_cb	= true;
				
					$(this).on('click', function () {
						if(not_my_action || !can_target) {
							return;
						}
						
						$('.player-box-player .player-dead').removeClass('player-dead-highlight');
						$(this).addClass('player-dead-highlight');
						
						target_p	= $(this).data('target-id');						
					});
				});				
			}
			
			if(ping.messages.length) {
				var	messages	= [];
				
				ping.messages.forEach(function (message) {
					messages.push('<li>' + message + '</li>');
				});
			
				jalert('Ops, você não pode efetuar essa ação pelos seguintes motivos:<br /><br /><ul>' + (messages.join('')) + '</ul>');
			}
			
			for(var i in ping.items) {
				if(!_items[i]) {
					continue;
				}
			
				_items[i].precision	= ping.items[i].precision;
			}
			
			if(ping.quantities) {
				ping.quantities.forEach(function (item) {
					_items[item.id]	= item.quantity;
				});
			}
			
			if(ping.remove) {
				ping.remove.forEach(function(item) {
					$('.pvp-atk-list .atk').each(function () {
						if(parseInt($(this).data('id')) == parseInt(item.id)) {
							$(this).hide('explode').remove();
						}
					});
				});
			}
			
			if(ping.finished) {
				clearInterval(_ping_iv);
				
				log	= 'Batalha finalizada';
				
				$('#d-finished-msg').html(ping.finished);
				$('.pvp-atk-bar').html('').hide();
			}

			$('#pvp-current').html(log);
			
			me	= ping.me;
		}
		
		$('.pvp-atk-list, .player-utility').on('mouseover', '.atk', function () {
			var	tooltip	= $(document.createElement('DIV')).addClass('ex_tooltip');
			var item	= _items[$(this).data('id')];
			var html	= '<b>' + item.name  + '</b>';
			
			if(item.consumable) {
				html	+= '<span class="qty" style="float: right"><b>x' + item.quantity + '</b></span>';
			}
			
			html	+= '<hr />' + item.description + '<hr />';
			
			if(item.kinjutsu) {
				html	+= '<hr /><?php echo t('item_tooltip.kinjutsu.unique') ?><hr />';			
			
				if(item.kinjutsu.revive) {
					if(item.kinjutsu.all) {
						html	+= '<?php echo t('item_tooltip.kinjutsu.dead_all') ?>';
					} else {
						html	+= '<?php echo t('item_tooltip.kinjutsu.dead') ?>';						
					}

					html	+= '<hr />';
				}
			
				if(item.kinjutsu.all) {
					if(item.kinjutsu.dir == 'friendly') {
						html	+= '<?php echo t('item_tooltip.kinjutsu.friendly.all') ?>';
					} else {
						html	+= '<?php echo t('item_tooltip.kinjutsu.enemy.all') ?>';						
					}					
				} else {
					if(item.kinjutsu.dir == 'friendly') {
						html	+= '<?php echo t('item_tooltip.kinjutsu.friendly.one') ?>';
					} else {
						html	+= '<?php echo t('item_tooltip.kinjutsu.enemy.one') ?>';						
					}					
				}
				
				html	+= '<hr />';
			} else {
				for(var f = 0; f <= 1; f++) {
					var word	= f ? 'e' : 'p';
					
					if(item.mods[word]) {
						var	mod_sum	= 0;
	
						for(var i in item.mods[word]) {
							mod_sum	+= item.mods[word][i];
						}
						
						if(mod_sum == 0) {
							continue;
						}
					
						if(item.is_mod) {
							html	+= f ? '<?php echo t('item_tooltip.efeitos.e')?>:<br />' : '<?php echo t('item_tooltip.efeitos.p')?>:<br />';
						}
						
						for(var i in item.mods[word]) {
							var mod				= item.mods[word][i];
							var multiplier		= item.multiplier == 'percent' ? '%' : '';
							var extra			= '';
							var is_atk_or_def	= (i == 'atk_fisico' || i == 'atk_magico' || i == 'def_base' || i == 'def_magico' || i == 'def_fisico');
							
							if(mod == 0) {
								continue;
							}
							
							if(properties_percent[i]) {
								multiplier	= '%';
							}
							
							if(!item.is_mod && is_atk_or_def && item.precision >= 100) {
								if(i == 'def_base') {
									var player_attribute	= me['def'];								
								} else if(i == 'def_fisico') {
									var player_attribute	= me['deff'];								
								} else if(i == 'def_magico') {
									var player_attribute	= me['defm'];								
								} else {
									var player_attribute	= me['atk' + item.direction];
								}
							
								extra	= ' + <span class="color_green">' + percent(me.cmin, mod) + ' ~ ' + percent(me.cmax, mod) + '</span> (+ ' + player_attribute + ')';
								//html	+= (properties_images[i] ? '<img src="<?php echo img() ?>' + properties_images[i] +'" align="absmiddle" />&nbsp;' : '') + (properties[i] || i) + ': ' + mod + multiplier + extra + '<br />';
							}
						
							//if(item.is_mod) {
								html	+= (properties_images[i] ? '<img src="<?php echo img() ?>' + properties_images[i] +'" align="absmiddle" />&nbsp;' : '') + (properties[i] || i) + ': ' + mod + multiplier + extra + '<br />';
							//}
						}
						
						// Precision if not modifier
						if(!item.is_mod) {
							var	precision	= _round(item.precision, 2);
							var error		= _round(100 - item.precision, 2);
							var prec_type	= 'prec_' + (item.direction == 'm' ? 'magico' : 'fisico');
							
							html	+=	'<img src="<?php echo img() ?>' + properties_images[prec_type] +'" align="absmiddle" />&nbsp;' +
										'<?php echo t('fight.f27')?>: <span class="color_green">' + precision + '%</span> ' +
										(error > 0 ? ' ( <span class="color_red">' + error + '%</span> <?php echo t('fight.f28')?>) ' : '') + '<br />';
							
							if(precision >= 100) {
								html	+=	'<img src="<?php echo img() ?>' + properties_images['conc'] + '" align="absmiddle" />&nbsp;' +
											'<?php echo t('fight.f29')?>: <span class="color_green">' + _round(me.crit, 2) + '% + <span style="color: #B01AA6 !important">' + item.enhance_crit + '%</span></span>';
							}
						}
					}
				}				
			}

			if(item.bonus.hp || item.bonus.sp || item.bonus.sta) {
				if(item.kinjutsu) {
					if(item.bonus.hp) {
						if(item.kinjutsu.dir == 'friendly') {
							html	+= '<img src="<?php echo img('layout/icones/p_hp.png')?>" align="absmiddle"/>' + '<?php echo t('item_tooltip.kinjutsu.friendly.bonus_hp') ?><br />'.replace('%s%', item.bonus.hp);
						} else {
							html	+= '<img src="<?php echo img('layout/icones/p_hp.png')?>" align="absmiddle"/>' + '<?php echo t('item_tooltip.kinjutsu.enemy.bonus_hp') ?><br />'.replace('%s%', item.bonus.hp);
						}
					}
	
					if(item.bonus.sp) {
						if(item.kinjutsu.dir == 'friendly') {
							html	+= '<img src="<?php echo img('layout/icones/p_chakra.png')?>" align="absmiddle"/>' + '<?php echo t('item_tooltip.kinjutsu.friendly.bonus_sp') ?><br />'.replace('%s%', item.bonus.sp);
						} else {
							html	+= '<img src="<?php echo img('layout/icones/p_chakra.png')?>" align="absmiddle"/>' + '<?php echo t('item_tooltip.kinjutsu.enemy.bonus_sp') ?><br />'.replace('%s%', item.bonus.sp);
						}
					}
	
					if(item.bonus.sta) {
						if(item.kinjutsu.dir == 'friendly') {
							html	+= '<img src="<?php echo img('layout/icones/p_stamina.png')?>" align="absmiddle"/>' + '<?php echo t('item_tooltip.kinjutsu.friendly.bonus_sta') ?><br />'.replace('%s%', item.bonus.sta);
						} else {
							html	+= '<img src="<?php echo img('layout/icones/p_stamina.png')?>" align="absmiddle"/>' + '<?php echo t('item_tooltip.kinjutsu.enemy.bonus_sta') ?><br />'.replace('%s%', item.bonus.sta);
						}
					}					
				} else {
					html		+= 'Recupera:';
					
					if(item.bonus.hp) {
						html	+= '<img src="<?php echo img('layout/icones/p_hp.png')?>" align="absmiddle"/>' + item.bonus.hp;
					}
	
					if(item.bonus.sp) {
						html	+= '<img src="<?php echo img('layout/icones/p_chakra.png')?>" align="absmiddle"/>' + item.bonus.sp;
					}
	
					if(item.bonus.sta) {
						html	+= '<img src="<?php echo img('layout/icones/p_stamina.png')?>" align="absmiddle"/>' + item.bonus.sta;
					}
					
					html	+= '<br />';					
				}
			}

			if(item.duration || item.cooldown) {
				html	+= '<hr />';

				if(item.is_mod) {
					html	+= '<b><?php echo t('geral.g69')?></b><br /><br />';
				}

				if(item.cooldown) {
					html	+= '<?php echo t('fight.f31')?>: ' + item.cooldown + ' <?php echo t('geral.g70')?>' + (_turns[item.id] ? ' ( <?php echo t('fight.f34')?> ' + _turns[item.id] + ' <?php echo t('geral.g70')?> )' : '') + '<br />';					
				}
				
				if(item.duration) {
					html	+= '<?php echo t('fight.f32')?>: ' + item.duration + ' <?php echo t('geral.g70')?>';					
				}
			} else {
				if(item.is_mod) {
					html	+= '<hr /><b><?php echo t('geral.g69')?></b>';
				}				
			}

			if(item.consume.hp || item.consume.sp || item.consume.sta) {
				html		+= '<hr /><?php echo t('fight.f35')?>:';
				
				if(item.kinjutsu) {
					html	+= '<br /><br />';
				
					if(item.consume.hp) {
						html	+= '<img src="<?php echo img('layout/icones/p_hp.png')?>" align="absmiddle"/>' + item.consume.hp + '% <?php echo t('item_tooltip.base.hp') ?><br />';
					}
	
					if(item.consume.sp) {
						html	+= '<img src="<?php echo img('layout/icones/p_chakra.png')?>" align="absmiddle"/>' + item.consume.sp + '% <?php echo t('item_tooltip.base.sp') ?><br />';
					}
	
					if(item.consume.sta) {
						html	+= '<img src="<?php echo img('layout/icones/p_stamina.png')?>" align="absmiddle"/>' + item.consume.sta + '% <?php echo t('item_tooltip.base.sta') ?>';
					}					
				} else {
					if(item.consume.hp) {
						html	+= '<img src="<?php echo img('layout/icones/p_hp.png')?>" align="absmiddle"/>' + item.consume.hp;
					}
	
					if(item.consume.sp) {
						html	+= '<img src="<?php echo img('layout/icones/p_chakra.png')?>" align="absmiddle"/>' + item.consume.sp;
					}
	
					if(item.consume.sta) {
						html	+= '<img src="<?php echo img('layout/icones/p_stamina.png')?>" align="absmiddle"/>' + item.consume.sta;
					}					
				}				
			}

			tooltip.html(html);
			
			$(this).append(tooltip.show());

			// Margin fix -->
				var l 		= tooltip.offset().left;
				var base_l	= $('#pagina').offset().left
				var r		= (l - base_l) + tooltip.width();
				
				if(r >= 1000) {
					tooltip.css({marginLeft: -(r - 930)});
				}
			// <--
		}).on('mouseout', '.atk', function () {
			$('.ex_tooltip', this).remove();
		});

		$('.player-utility').on('click', '.utility', function () {
			var	selector	= $('.selector', this);

			if(this.shown) {
				selector.animate({opacity: 0, left: 0});
				this.shown	= false;
			} else {
				selector.animate({opacity: 1, left: 53});
				this.shown	= true;
			}
		});
		
		var _can_ping		= true;
		var _is_attacking	= false;
		var	_ping_iv		= setInterval(function () {
			if(!_can_ping || _is_attacking) {
				return;
			}
			
			_can_ping	= false;
		
			$.ajax({
				url:		'?acao=dojo_batalha_multi_pvp',
				type:		'post',
				dataType:	'json',
				data:		{'key': '<?php echo $_SESSION['multi_pvp_key'] ?>'},
				success:	function (result) {
					_parse_ping(result);
					_can_ping	= true;
				},
				error:	function () {
					_can_ping	= true;
				}
			});
		}, 2000);
		
		<?php
			foreach($items as $item) {
				if(!$item->dojo_ativo) {
					continue;
				}
				
				$item->setPlayerInstance($basePlayer);
				$item->parseLevel();
				$item->apply_team_modifiers();

				$it					= new stdClass();
				$it->id				= $item->id;
				$it->name			= $item->nome;
				$it->description	= $item->descricao;
				$it->is_mod			= $item->sem_turno ? true : false;
				$it->medicinal		= $item->id_tipo == 24 ? true : false;
				$it->duration		= (int)$item->getAttribute('cooldown');
				$it->cooldown		= (int)$item->turnos;
				$it->direction		= $item->base_f == 'atk_fisico' ? 'f' : 'm';

				$it->precision		= $item->getAttribute('precisao');
				
				if($item->id_tipo == 23) {
					$it->multiplier	= 'percent';
				} else {
					$it->multiplier	= $item->tipo_bonus ? 'normal' : 'percent';
				}
				
				if($item->id_tipo == 37) {
					$it->kinjutsu			= new stdClass();
					$it->kinjutsu->all		= $item->bonus_treino ? true : false;
					$it->kinjutsu->dir		= $item->tipo_bonus ? 'enemy' : 'friendly';
					$it->kinjutsu->revive	= $item->defensivo ? true : false;
				} else {
					$it->kinjutsu	= false;
				}
				
				$it->consumable		= $item->id_tipo == 1;
				$it->quantity		= $item->qtd;
				$it->enhance_crit	= $item->crit_inc;
				
				$it->mods			= new stdClass();
				$it->mods->p		= false;
				$it->mods->e		= false;
				
				$it->consume		= new stdClass();
				$it->consume->hp	= (int)$item->consume_hp;
				$it->consume->sp	= (int)$item->consume_sp;
				$it->consume->sta	= (int)$item->consume_sta;					

				$it->bonus			= new stdClass();
				$it->bonus->hp		= (int)$item->bonus_hp;
				$it->bonus->sp		= (int)$item->bonus_sp;
				$it->bonus->sta		= (int)$item->bonus_sta;	

				if($item->hasModifiers() && $item->sem_turno) {
					foreach($item->getModifiers() as $_ => $modifier) {
						if($_ == 'id' || $_ == 'id_item') {
							continue;
						}
					
						if(strpos($_, 'self') === false) {
							$property	= 'e';							
						} else {
							$property	= 'p';
						}
						
						$name	= preg_replace('/(self_|target_)/i', '', $_);

						if(!$it->mods->$property) {
							$it->mods->$property	= new stdClass();
						}
						
						$it->mods->$property->$name	= (int)$modifier;
					}
				} else {
					$it->mods->p				= new stdClass();
				    $it->mods->p->nin			= (int)$item->nin;
					$it->mods->p->gen			= (int)$item->gen;
					$it->mods->p->agi			= (int)$item->agi;
					$it->mods->p->con			= (int)$item->con;
					$it->mods->p->forc			= (int)$item->forc;
					$it->mods->p->ene			= (int)$item->ene;
					$it->mods->p->inte			= (int)$item->inte;
					$it->mods->p->res			= (int)$item->res;
					$it->mods->p->atk_fisico	= (int)$item->atk_fisico;
					$it->mods->p->atk_magico	= (int)$item->atk_magico;
					$it->mods->p->def_base		= (int)$item->def_base;
					$it->mods->p->def_fisico	= (int)$item->def_fisico;
					$it->mods->p->def_magico	= (int)$item->def_magico;
					$it->mods->p->prec_fisico	= (int)$item->prec_fisico;
					$it->mods->p->prec_magico	= (int)$item->prec_magico;
					$it->mods->p->crit_min		= (int)$item->crit_min;
					$it->mods->p->crit_max		= (int)$item->crit_max;
					$it->mods->p->crit_total	= (int)$item->crit_total;
					$it->mods->p->esq_min		= (int)$item->esq_min;
					$it->mods->p->esq_max		= (int)$item->esq_max;
					$it->mods->p->esq_total		= (int)$item->esq_total;
					$it->mods->p->esq			= (int)$item->esq;
					$it->mods->p->det			= (int)$item->det;
					$it->mods->p->conv			= (int)$item->conv;
					$it->mods->p->esquiva		= (int)$item->esquiva;
					$it->mods->p->conc			= (int)$item->conc;					
				}
				
				echo '_items[' . $item->id . '] = ' . json_encode($it) . ";\n";
			}
		?>
	})();
</script>
