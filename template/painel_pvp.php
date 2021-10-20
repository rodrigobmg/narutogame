<?php
	if(!isset($evento4)) {
		$evento4 = false;
	}

	if(!$evento4 && !$baseEnemy) {
		$redir_script = true;

		$basePlayer->setAttribute('id_batalha', 0);

		redirect_to('personagem_status');

		die();
	}

	if(!$evento4) {
		$basePlayer->setLocalAttribute('less_conv', $baseEnemy->getAttribute('conv_calc'));
		$baseEnemy->setLocalAttribute('less_conv', $basePlayer->getAttribute('conv_calc'));

		$basePlayer->atCalc();
		$baseEnemy->atCalc();

		$e_modifiers	= $baseEnemy->getModifiers();
	}

	$pvpToken		= $_SESSION['_pvpToken'] = md5(rand(1, 9999999));
	$modifiers		= $basePlayer->getModifiers();
	$player_role	= '';
	$batalha		= Recordset::query('SELECT * FROM batalha WHERE id=' . $basePlayer->id_batalha)->row_array();

	if(!$evento4) {
		$stat	= Recordset::query('SELECT * FROM player_batalhas_log WHERE id_player=' . $basePlayer->id . ' AND id_playerb=' . $baseEnemy->id);

		if($stat->num_rows && (!$basePlayer->id_arena && !$basePlayer->id_torneio && !(!$basePlayer->id_random_queue && $batalha['id_tipo'] == 2))) {
			$stat_losing	= $stat->row()->vitorias - $stat->row()->derrotas <= -10;
			$stat_winning	= $stat->row()->vitorias - $stat->row()->derrotas >= 10;
		} else {
			$stat_losing	= false;
			$stat_winning	= false;
		}
	}

	switch($basePlayer->id_classe_tipo) {
		case 1:	$player_role	= 'tai';	break;
		case 2:	$player_role	= 'nin';	break;
		case 3:	$player_role	= 'gen';	break;
		case 4:	$player_role	= 'ken';	break;
	}

	$role_id		= Player::getFlag('equipe_role', $basePlayer->id);
	$role0_lvl		= Player::getFlag('equipe_role_0_lvl', $basePlayer->id);
	$role1_lvl		= Player::getFlag('equipe_role_1_lvl', $basePlayer->id);
	$role2_lvl		= Player::getFlag('equipe_role_2_lvl', $basePlayer->id);
	$role3_lvl		= Player::getFlag('equipe_role_3_lvl', $basePlayer->id);

	$modifiers_key	= array_keys(Recordset::query('SELECT * FROM item_modificador LIMIT 1', true)->row_array());

	$ar_mods		= array(
		'agi'				=> array('nome' => t('at.agi'),					'i' => 'layout/icones/agi.png'),
		'con'				=> array('nome' => t('at.con'),					'i' => 'layout/icones/conhe.png'),
		'forc'				=> array('nome' => t('at.for'),					'i' => 'layout/icones/forc.png'),
		'inte'				=> array('nome' => t('at.int'),					'i' => 'layout/icones/inte.png'),
		'res'				=> array('nome' => t('at.res'),					'i' => 'layout/icones/defense.png'),
		'nin'				=> array('nome' => 'Ninjutsu',					'i' => 'layout/icones/nin.png'),
		'gen'				=> array('nome' => 'Genjutsu',					'i' => 'layout/icones/gen.png'),
		'tai'				=> array('nome' => 'Taijutsu',					'i' => 'layout/icones/tai.png'),
		'ken'				=> array('nome' => 'Bukijutsu',					'i' => 'layout/icones/ken.png'),
		'atk_fisico'		=> array('nome' => t('formula.atk_fisico'),		'i' => 'layout/icones/atk_fisico.png'),
		'atk_magico'		=> array('nome' => t('formula.atk_magico'),		'i' => 'layout/icones/atk_magico.png'),
		'def_base'			=> array('nome' => t('formula.def_base'),		'i' => 'layout/icones/shield.png'),
		'def_fisico'		=> array('nome' => t('formula.def_fisico'),		'i' => 'layout/icones/def_fisico.png'),
		'def_magico'		=> array('nome' => t('formula.def_magico'),		'i' => 'layout/icones/def_magico.png'),
		'ene'				=> array('nome' => t('at.ene'),					'i' => 'layout/icones/ene.png'),
		'hp'				=> array('nome' => t('formula.hp'),				'i' => 'layout/icones/p_hp.png'),
		'sp'				=> array('nome' => t('formula.sp'),				'i' => 'layout/icones/p_chakra.png'),
		'sta'				=> array('nome' => t('formula.sta'),			'i' => 'layout/icones/p_stamina.png'),
		'def_base'			=> array('nome' => t('formula.def_base'),		'i' => 'layout/icones/shield.png'),
		'prec_fisico'		=> array('nome' => t('formula.prec_fisico'),	'i' => 'layout/icones/prec_tai.png'),
		'prec_magico'		=> array('nome' => t('formula.prec_magico'),	'i' => 'layout/icones/prec_nin_gen.png'),
		'crit_min'			=> array('nome' => t('formula.crit_min'),		'i' => 'layout/icones/p_stamina.png'),
		'crit_max'			=> array('nome' => t('formula.crit_max'),		'i' => 'layout/icones/p_stamina.png'),
		'crit_total'		=> array('nome' => t('formula.crit_total'),		'i' => 'layout/icones/p_stamina.png'),
		'esq_min'			=> array('nome' => t('formula.esq_min'),		'i' => 'layout/icones/p_stamina.png'),
		'esq_max'			=> array('nome' => t('formula.esq_max'),		'i' => 'layout/icones/p_stamina.png'),
		'esq_total'			=> array('nome' => t('formula.esq_total'),		'i' => 'layout/icones/p_stamina.png'),
		'esq'				=> array('nome' => t('formula.esq'),			'i' => 'layout/icones/esquiva.png'),
		'det'				=> array('nome' => t('formula.det'),			'i' => 'layout/icones/deter.png'),
		'conv'				=> array('nome' => t('formula.conv'),			'i' => 'layout/icones/convic.png'),
		'conc'				=> array('nome' => t('formula.conc'),			'i' => 'layout/icones/target2.png'),
		'esquiva'			=> array('nome' => t('formula2.esquiva'),		'i' => 'layout/icones/esquiva.png')
	);
?>
<style type="text/css">
	/* width */
	::-webkit-scrollbar {
	width: 10px;
	}

	/* Track */
	::-webkit-scrollbar-track {
	background: #f1f1f1; 
	}
	
	/* Handle */
	::-webkit-scrollbar-thumb {
	background: #888; 
	}

	/* Handle on hover */
	::-webkit-scrollbar-thumb:hover {
	background: #555; 
	}
	.pvpAtkDetailPopup {
		position: absolute;
		width: 270px;
		background-color: #FFFFFF;
		font-family: Verdana, Arial, Helvetica, sans-serif;
		font-size: 11px;
		background-color: #333333;
		padding: 4px;
		border: solid 1px #FF9900;
		color: #FFFFFF;
	}

	.atk-tooltip {
		display: none;
		z-index: 10000 !important;
		color: #FFF;
		text-align: left;
		position: absolute;
		z-index: 10000000;
		-moz-border-bottom-colors: none;
		-moz-border-image: none;
		-moz-border-left-colors: none;
		-moz-border-right-colors: none;
		-moz-border-top-colors: none;
		background-color: #0a0a0a;
		background-position: 1px 1px;
		background-repeat: no-repeat;
		border-color: #B1B2B4 #434445 #2F3032;
		border-radius: 3px 3px 3px 3px;
		border-style: solid;
		border-width: 2px;
		overflow: hidden;
		padding: 8px;
		width: 250px;
		top: 30px;
		font-size: 11px;
		line-height: normal;
	}
	.atk-tooltip b{
		color: #FF6600;
	}
	.atk-tooltip hr, #cnLog hr{
		color: #c1c1c1;
		border: 1px dotted #272727;
	}
	.d-icon-counter {
		background-position: top left;
		background-repeat: no-repeat;
		width: 34px;
		height: 34px;
		float: left;
		color: #FFF;
	}
	.d-icon-counter span{
		position: relative;
		left: -12px;
		font-size: 9px;
		top: 3px;
	}
	.d-icon-counter-r {
		float: left !important
	}

	.d-buff {
		position: relative;
		cursor: pointer;
		z-index: 2;
		float: left;
		width: 65px;
		height: 65px;
	}

	.d-buff div {
		position: absolute;
		left: 0px;
		top: 0px;
		margin-left: 63px;
		margin-top: 9px;
		white-space: nowrap;
		display: none;
		background-color: #020e1a;
		padding: 4px;
		padding-left: 6px;
		height: 44px;
		border-radius: 5px;
	}

	.d-buff div img {
		cursor: pointer;
	}

	#imgDP, #imgDE {
		width: 226px;
		z-index: 1;
	}

	#cnLog {
		overflow: auto;
	}

	#cnLog .buff, #cnLog .buff .pvp_p_atk {
		color: #a4399f !important
	}

	#cnFinal .msg_gai {
		margin: 0px auto;
		float: none !important;
	}

	#painel-pvp-village1, #painel-pvp-village2 {
		width: 60px;
		height: 54px;
		position: absolute;
		top: 16px;
	}

	#painel-pvp-village1, #painel-pvp-flight {
		left: 54px
	}

	#painel-pvp-village2, #painel-pvp-star {
		right: 54px
	}

	#painel-pvp-star, #painel-pvp-flight {
		width: 60px;
		height: 54px;
		position: absolute;
		bottom: 19px;
	}

	#painel-pvp-flight {
	}
</style>
<div id="cnFinal"></div>
<?php if($basePlayer->getAttribute('id_batalha')): ?>
	<div id="painel-pvp-container">
		<?php if (is_a($basePlayer, 'Player') && is_a($baseEnemy, 'Player')): ?>
			<a href="javascript:;" id="painel-pvp-village1" style="background-image: url(<?php echo img('layout/combate/vilas/' . $basePlayer->id_vila . '.png') ?>)"></a>
			<a href="javascript:;" id="painel-pvp-village2" style="background-image: url(<?php echo img('layout/combate/vilas/' . $baseEnemy->id_vila . '.png') ?>)"></a>
		<?php endif ?>
		<div class="new_dojo">
			<?php if (is_a($basePlayer, 'Player') && is_a($baseEnemy, 'Player')): ?>
				<div id="pvp-battle-stat">
					<span class="title">Estatisticas de Batalhas</span>
					<?php if ($stat->num_rows): ?>
						<?php
							$stat	= $stat->row();

							echo sprintf(t('actions.a283'), $stat->vitorias, $stat->derrotas, $stat->empates);
						?>
					<?php else: ?>
						<?php
							echo sprintf(t('actions.a283'), 0, 0, 0);
						?>
					<?php endif ?>
				</div>
			<?php endif ?>
			<div style="position: absolute; <?php echo LAYOUT_TEMPLATE == "_azul" ? "bottom: 431px" : "bottom: 210px" ?>; width: 100%; text-align: center; font-size: 22px; background-image: url(<?php echo img('layout/combate/d-seta-b.png') ?>); background-position: center center; background-repeat: no-repeat" id="d-atk-timer"></div>
			<div class="a-topo-batalha">
				<div style="width: 250px; position:relative;">
					<div id="cnPBuff"></div>
				</div>
			</div>
			<div class="b-topo-batalha">
				<div style="width: 250px; position:relative;">
					<div id="cnEBuff"></div>
				</div>

			</div>
			<div class="break"></div>
			<div class="a-esquerda-batalha">
				<div class="a-habilidades-batalha" id="imgPP">
					<?php if($basePlayer->getAttribute('id_selo')): ?>
						<div class="d-buff">
							<?php
								$order	= 1;
							?>
							<div>
								<?php foreach($basePlayer->getItems() as $item): ?>
									<?php if($item->getAttribute('id_tipo') != 20) continue; ?>
									<img id="i-nt-<?php echo $item->id ?>" src="<?php echo img('layout/selos/' . $basePlayer->getAttribute('id_selo') . '/' . $item->getAttribute('ordem') . '.gif') ?>" role="<?php echo $item->id ?>" width="36" />
									<?php
										foreach($modifiers as $modifier) {
											if($modifier['id'] == $item->id && $modifier['direction'] == 0) {
												$order	= Recordset::query('SELECT ordem FROM item WHERE id=' . $item->id)->row()->ordem;

												break;
											}
										}
									?>
								<?php endforeach; ?>
							</div>
							<span class="img-lateral-dojo"><img class="i-buff-status" src="<?php echo img('layout/selos/' . $basePlayer->getAttribute('id_selo') . '/' . $order . '.gif') ?>" id="i-pbuff-20" border="0" style="margin-top:5px"/></span>
						</div>
						<?php endif; ?>
						<?php if($basePlayer->getAttribute('id_invocacao')): ?>
						<div class="d-buff">
							<?php
								$order	= 1;
							?>
							<div>
								<?php foreach($basePlayer->getItems() as $item): ?>
									<?php if($item->getAttribute('id_tipo') != 21) continue; ?>
									<img id="i-nt-<?php echo $item->id ?>" src="<?php echo img('layout/invocacoes/' . $basePlayer->getAttribute('id_invocacao') . '/' . $item->getAttribute('ordem') . '.png') ?>" role="<?php echo $item->id ?>" width="36" />
									<?php
										foreach($modifiers as $modifier) {
											if($modifier['id'] == $item->id && $modifier['direction'] == 0) {
												$order	= Recordset::query('SELECT ordem FROM item WHERE id=' . $item->id)->row()->ordem;

												break;
											}
										}
									?>
								<?php endforeach; ?>
							</div>
							<span class="img-lateral-dojo"><img class="i-buff-status" src="<?php echo img('layout/invocacoes/' . $basePlayer->getAttribute('id_invocacao') . '/' . $order . '.png') ?>" id="i-pbuff-21" border="0" style="margin-top:5px"/></span>
						</div>
						<?php endif; ?>
						<?php if($basePlayer->getAttribute('sennin')): ?>
						<div class="d-buff">
							<?php
								$order	= 1;
							?>
							<div>
								<?php foreach($basePlayer->getItems() as $item): ?>
									<?php if($item->getAttribute('id_tipo') != 26) continue; ?>
									<img id="i-nt-<?php echo $item->id ?>" src="<?php echo img('layout/mode_sennin/' . $basePlayer->getAttribute('id_sennin') . '/' . $item->getAttribute('ordem') . '.png') ?>" role="<?php echo $item->id ?>" width="36" />
									<?php
										foreach($modifiers as $modifier) {
											if($modifier['id'] == $item->id && $modifier['direction'] == 0) {
												$order	= Recordset::query('SELECT ordem FROM item WHERE id=' . $item->id)->row()->ordem;

												break;
											}
										}
									?>
								<?php endforeach; ?>
							</div>
							<span class="img-lateral-dojo"><img class="i-buff-status" src="<?php echo img('layout/mode_sennin/' . $basePlayer->getAttribute('id_sennin') . '/' . $order . '.png')  ?>" id="i-pbuff-26" border="0" style="margin-top:5px"/></span>
						</div>
						<?php endif; ?>
						<?php if($basePlayer->getAttribute('portao')): ?>
						<div class="d-buff">
							<?php
								$order	= 1;
							?>
							<div>
								<?php foreach($basePlayer->getItems() as $item): ?>
									<?php if($item->getAttribute('id_tipo') != 17) continue; ?>
									<img id="i-nt-<?php echo $item->id ?>" src="<?php echo img('layout/portoes/' . $item->getAttribute('id') . '.gif') ?>" role="<?php echo $item->id ?>" width="36" />
									<?php
										foreach($modifiers as $modifier) {
											if($modifier['id'] == $item->id && $modifier['direction'] == 0) {
												$order	= Recordset::query('SELECT ordem FROM item WHERE id=' . $item->id)->row()->ordem;

												break;
											}
										}
									?>
								<?php endforeach; ?>
							</div>
							<span class="img-lateral-dojo"><img class="i-buff-status" src="<?php echo img('layout/portoes/' . $order . '.gif')  ?>" id="i-pbuff-17" border="0" style="margin-top:5px"/></span>
						</div>
						<?php endif; ?>
						<?php if($basePlayer->getAttribute('id_cla')): ?>
						<div class="d-buff">
							<?php
								$order	= 1;
							?>
							<div>
								<?php foreach($basePlayer->getItems() as $item): ?>
									<?php if($item->getAttribute('id_tipo') != 16) continue; ?>
									<img id="i-nt-<?php echo $item->id ?>" src="<?php echo img('layout/clas/' . $basePlayer->getAttribute('id_cla') . '/' . $item->getAttribute('ordem') . '.png') ?>" role="<?php echo $item->id ?>" width="36" />
									<?php
										foreach($modifiers as $modifier) {
											if($modifier['id'] == $item->id && $modifier['direction'] == 0) {
												$order	= Recordset::query('SELECT ordem FROM item WHERE id=' . $item->id)->row()->ordem;

												break;
											}
										}
									?>
								<?php endforeach; ?>
							</div>
							<span class="img-lateral-dojo"><img class="i-buff-status" src="<?php echo img('layout/clas/' . $basePlayer->getAttribute('id_cla') . '/' . $order . '.png') ?>" id="i-pbuff-16" border="0" style="margin-top:5px"/></span>
						</div>
						<?php endif; ?>
						<?php if($basePlayer->hasItem(array(1459, 1460, 1461, 1462, 1463, 1464, 1465, 1466, 1467,1468))): ?>
						<div class="d-buff">
							<?php foreach($basePlayer->getItems() as $item): ?>
								<?php if($item->getAttribute('id_tipo') != 23) continue; ?>
								<span class="img-lateral-dojo"><img class="i-buff-status" src="<?php echo img('layout/bijuus-batalha/' . $item->id . '.png') ?>" id="i-pbuff-23" border="0" style="margin-top:5px"/></span>
								<div>
									<img id="i-nt-<?php echo $item->id ?>" src="<?php echo img('layout/bijuus-batalha/' . $item->id . '.png') ?>" role="<?php echo $item->id ?>" width="36" />
								</div>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>

						<?php if($basePlayer->hasItem(array(22732, 22731, 22730, 22729, 22728, 22727, 22726))): // espadas ?>
						<div class="d-buff">
							<?php foreach($basePlayer->getItems() as $item): ?>
								<?php if($item->getAttribute('id_tipo') != 39) continue; ?>
								<span class="img-lateral-dojo"><img class="i-buff-status" src="<?php echo img('layout/bijuus-batalha/' . $item->id . '.png') ?>" id="i-pbuff-39" border="0" style="margin-top:5px"/></span>
								<div>
									<img id="i-nt-<?php echo $item->id ?>" src="<?php echo img('layout/bijuus-batalha/' . $item->id . '.png') ?>" role="<?php echo $item->id ?>" width="36" />
								</div>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>
				</div>
				<div class="a-profile-batalha">
					<!--
					<img src="<?php echo player_imagem($basePlayer->id); ?>"/>
					-->
					<?php echo player_imagem_ultimate($basePlayer); ?>
				</div>
				<div class="a-stats-batalha">
					<div class="a-stats-batalha-nome">
						<div>
							<span class="player-name">
								<?php echo $basePlayer->getAttribute('nome') ?>
							</span>
							<span class="player-headline">
								<?php echo $basePlayer->getAttribute('nome_titulo') ?>
							</span>
						</div>
					</div>
					<div id="imgDP">
						<div class="d-icon-counter" id="d-pcrit-count" style="background-image: URL(<?php echo img('layout'.LAYOUT_TEMPLATE.'/crit.png') ?>);">
							<span><?php echo $basePlayer->getAttribute('max_crit_hits') ?></span>
						</div>
						<div class="d-icon-counter" id="d-pesq-count" style="background-image: URL(<?php echo img('layout'.LAYOUT_TEMPLATE.'/esq.png') ?>)">
							<span><?php echo $basePlayer->getAttribute('max_esq_hits') ?></span>
						</div>
						<div class="player-info-box">
							<div>
								<?php echo is_a($basePlayer, 'Player') ? graduation_name($basePlayer->getAttribute('id_vila'), $basePlayer->getAttribute('id_graduacao')) : '--' ?>
								- Lvl <?php echo $basePlayer->getAttribute('level') ?>
							</div>
							<div>
								<span><?php echo t('jogador_vip.jv36')?>:</span>
								<span id="ninjap-p"><?php echo $basePlayer->getAttribute('fight_power') ?></span>
							</div>
						</div>
					</div>
					<div class="a-stats-batalha-barras a-stats-batalha-barras-l">
						<?php barra_exp4(0, 0, 219, "", "id='cnPHP' data-dir='l'") ?>
						<div style="clear: both"></div>
						<?php barra_exp4(0, 0, 261, "", "id='cnPSP' data-dir='l'") ?>
						<div style="clear: both"></div>
						<?php barra_exp4(0, 0, 232, "", "id='cnPSTA' data-dir='l'") ?>
						<? if(isset($multi) && $multi): ?>
						<!--MULTI-->
						<? endif; ?>
					</div>
				</div>
			</div>
			<div class="vs-batalha">
				<?php
					if(is_a($baseEnemy, "Player")) {
						$img = player_imagem($baseEnemy->id);
					} else {
						$img = img($baseEnemy->getAttribute('imagem'));
					}
				?>
				<div class="log-batalha">
					<div id="cnLog" class="cnLog"></div>
					<div id="cnPVPLog" class="cnPVPLog laranja">Aguarde...</div>
					<div id="cnDebug"></div>
				</div>
			</div>
			<div class="b-direita-batalha">
				<div class="b-habilidades-batalha" id="imgPE">
					<?php if($baseEnemy->getAttribute('id_selo')): ?>
						<?php
							$order	= 1;

							foreach($e_modifiers as $modifier) {
								$item	= Recordset::query('SELECT id, ordem FROM item WHERE id_tipo=20 AND id=' . $modifier['id']);

								if($item->num_rows) {
									$order	= $item->row()->ordem;

									break;
								}
							}
						?>
						<div class="d-buff">
						   <span class="img-lateral-dojo"><img class="i-buff-status" src="<?php echo img('layout/selos/' . $baseEnemy->getAttribute('id_selo') . '/' . $order . '.gif') ?>" id="i-ebuff-20" border="0" style="margin-top:5px"/></span>
						</div>
			        <?php endif; ?>
			        <?php if($baseEnemy->getAttribute('id_invocacao')): ?>
						<?php
							$order	= 1;

							foreach($e_modifiers as $modifier) {
								$item	= Recordset::query('SELECT id, ordem FROM item WHERE id_tipo=21 AND id=' . $modifier['id']);

								if($item->num_rows) {
									$order	= $item->row()->ordem;

									break;
								}
							}
						?>
						<div class="d-buff">
							<span class="img-lateral-dojo"><img class="i-buff-status" src="<?php echo img('layout/invocacoes/' . $baseEnemy->getAttribute('id_invocacao') . '/' . $order . '.png') ?>" id="i-ebuff-21" border="0" style="margin-top:5px"/></span>
						</div>
			        <?php endif; ?>
			        <?php if($baseEnemy->getAttribute('sennin')): ?>
						<?php
							$order	= 1;

							foreach($e_modifiers as $modifier) {
								$item	= Recordset::query('SELECT id, ordem FROM item WHERE id_tipo=26 AND id=' . $modifier['id']);

								if($item->num_rows) {
									$order	= $item->row()->ordem;

									break;
								}
							}
						?>
						<div class="d-buff">
							<span class="img-lateral-dojo"><img class="i-buff-status" src="<?php echo img('layout/mode_sennin/' . $baseEnemy->getAttribute('id_sennin') . '/' . $order . '.png')  ?>" id="i-ebuff-26" border="0" style="margin-top:5px"/></span>
						</div>
			        <?php endif; ?>
			        <?php if($baseEnemy->getAttribute('portao')): ?>
						<?php
							$order	= 1;

							foreach($e_modifiers as $modifier) {
								$item	= Recordset::query('SELECT id, ordem FROM item WHERE id_tipo=17 AND id=' . $modifier['id']);

								if($item->num_rows) {
									$order	= $item->row()->ordem;

									break;
								}
							}
						?>
						<div class="d-buff">
							<span class="img-lateral-dojo"><img class="i-buff-status" src="<?php echo img('layout/portoes/' . $order . '.gif')  ?>" id="i-ebuff-17" border="0" style="margin-top:5px"/></span>
						</div>
			        <?php endif; ?>
			        <?php if($baseEnemy->getAttribute('id_cla')): ?>
						<?php
							$order	= 1;

							foreach($e_modifiers as $modifier) {
								$item	= Recordset::query('SELECT id, ordem FROM item WHERE id_tipo=16 AND id=' . $modifier['id']);

								if($item->num_rows) {
									$order	= $item->row()->ordem;

									break;
								}
							}
						?>
						<div class="d-buff">
							<span class="img-lateral-dojo"><img class="i-buff-status" src="<?php echo img('layout/clas/' . $baseEnemy->getAttribute('id_cla') . '/' . $order . '.png') ?>" id="i-ebuff-16" border="0" style="margin-top:5px"/></span>
						</div>
			        <?php endif; ?>
			        <?php if($baseEnemy->hasItem(array(1459, 1460, 1461, 1462, 1463, 1464, 1465, 1466, 1467,1468))): ?>
					<div class="d-buff">
						<?php foreach($baseEnemy->getItems() as $item): ?>
							<?php if($item->getAttribute('id_tipo') != 23) continue; ?>
							<span class="img-lateral-dojo"><img class="i-buff-status" src="<?php echo img('layout/bijuus-batalha/' . $item->id . '.png') ?>" id="i-ebuff-23" border="0" style="margin-top:5px"/></span>
							<div>
								<img id="i-nt-<?php echo $item->id ?>" src="<?php echo img('layout/bijuus-batalha/' . $item->id . '.png') ?>" role="<?php echo $item->id ?>" width="36" />
							</div>
						<?php endforeach; ?>
					</div>
			        <?php endif; ?>
			        <?php if($baseEnemy->hasItem(array(22732, 22731, 22730, 22729, 22728, 22727, 22726))): // espadas ?>
					<div class="d-buff">
						<?php foreach($baseEnemy->getItems() as $item): ?>
							<?php if($item->getAttribute('id_tipo') != 39) continue; ?>
							<span class="img-lateral-dojo"><img class="i-buff-status" src="<?php echo img('layout/bijuus-batalha/' . $item->id . '.png') ?>" id="i-ebuff-39" border="0" style="margin-top:5px"/></span>
							<div>
								<img id="i-nt-<?php echo $item->id ?>" src="<?php echo img('layout/bijuus-batalha/' . $item->id . '.png') ?>" role="<?php echo $item->id ?>" width="36" />
							</div>
						<?php endforeach; ?>
					</div>
			        <?php endif; ?>

				</div>
				<div class="b-profile-batalha">
					<?php if(is_a($baseEnemy, 'Player')): ?>
						<?php echo player_imagem_ultimate($baseEnemy); ?>
					<?php else: ?>
						<img  src="<?php echo $img ?>"/>
					<?php endif ?>
				</div>
				<div class="break"></div>
				<div class="b-stats-batalha">
					<div class="b-stats-batalha-nome">
						<div>
							<span class="player-name">
								<?php echo $baseEnemy->getAttribute('nome'); ?>
							</span>
							<span class="player-headline">
								<?php echo isset($baseEnemy->nome_titulo) && $baseEnemy->nome_titulo ? $baseEnemy->nome_titulo : "" ?>
							</span>
						</div>
					</div>
					<div id="imgDE">
						<div class="d-icon-counter d-icon-counter-r" id="d-ecrit-count" style="background-image: URL(<?php echo img('layout'.LAYOUT_TEMPLATE.'/crit.png') ?>);">
							<span><?php echo $baseEnemy->getAttribute('max_crit_hits') ?></span>
						</div>
						<div class="d-icon-counter d-icon-counter-r" id="d-eesq-count" style="background-image: URL(<?php echo img('layout'.LAYOUT_TEMPLATE.'/esq.png') ?>)">
							<span><?php echo $baseEnemy->getAttribute('max_esq_hits') ?></span>
						</div>
						<div class="player-info-box">
							<div>
								<?php echo is_a($baseEnemy, 'Player') ? graduation_name($baseEnemy->getAttribute('id_vila'), $baseEnemy->getAttribute('id_graduacao')) : '--' ?>
								- Lvl <?php echo $baseEnemy->getAttribute('level') ?>
							</div>
							<div>
								<span><?php echo t('jogador_vip.jv36')?>:</span>
								<span id="ninjap-e"><?php echo $baseEnemy->getAttribute('fight_power') ?></span>
							</div>
						</div>
					</div>
					<div class="b-stats-batalha-barras">
						<?php barra_exp4(0, 0, 219, "", "id='cnEHP'", "r") ?>
						<div style="clear: both"></div>
						<?php barra_exp4(0, 0, 261, "", "id='cnESP'", "r") ?>
						<div style="clear: both"></div>
						<?php barra_exp4(0, 0, 232, "", "id='cnESTA'", "r") ?>
					</div>

				</div>
			</div>
			<div class="break"></div>
		</div>
	</div>
	<div id="cnActionBar">
		<?php require("painel_pvp_actionbar.php"); ?>
	</div>
<?php endif; ?>
<script type="text/javascript">
	var _tooltip		= null;
	var	_pToken			= '<?php echo $pvpToken ?>';
	var _check_triggers	= [];
	var _nt_disabled	= [];
	var _cur_atk_tab	= 0;
	var is_animating	= false;
	var __can_atk		= false;
	var	__dyn_atk		= [];

	$('#cnLog').on('mouseover', '.pvp_p_atk', item_hover_f);
	$('#cnLog').on('mouseout', '.pvp_p_atk', item_out_f);

	<?php if(!$basePlayer->id_batalha_multi): ?>
		var	__timer_m	= 0;
		var __timer_s	= 0;

		var __timer_atk_iv = setInterval(function () {
			if(__timer_m < 0) {
				$('#d-atk-timer').html('--:--');

				return;
			}

			__timer_s--;

			if(__timer_s <= 0) {
				__timer_s = 59;
				__timer_m--;
			}

			if(__timer_m < 0) {
				$('#d-atk-timer').html('--:--');

				return;
			}

			$('#d-atk-timer').html(
				(__timer_m < 10 ? '0' + __timer_m : __timer_m) +
				':' +
				(__timer_s < 10 ? '0' + __timer_s : __timer_s)
			);
		}, 1000);

		<?php for($f = 0; $f <= 1; $f++): ?>
		<?php
			$var	= $f ? 'e' : 'p';
			$obj	= $f ? $baseEnemy : $basePlayer;
		?>
		var	<?php echo $var ?>mod = [];
		var	<?php echo $var ?> = {
			atkf:	<?php echo $obj->getAttribute('atk_fisico_calc') ?>,
			atkm:	<?php echo $obj->getAttribute('atk_magico_calc') ?>,
			def:	<?php echo $obj->getAttribute('def_base_calc') ?>,
			deff:	<?php echo $obj->getAttribute('def_fisico_calc') ?>,
			defm:	<?php echo $obj->getAttribute('def_magico_calc') ?>,
			mhp:	<?php echo $obj->getAttribute('max_hp') ?>,
			hp:		<?php echo $obj->getAttribute('hp') ?>,
			msp:	<?php echo $obj->getAttribute('max_sp') ?>,
			sp:		<?php echo $obj->getAttribute('sp') ?>,
			msta:	<?php echo $obj->getAttribute('max_sta') ?>,
			sta:	<?php echo $obj->getAttribute('sta') ?>,
			cmin:	<?php echo $obj->getAttribute('crit_min_calc') ?>,
			cmax:	<?php echo $obj->getAttribute('crit_max_calc') ?>,
			ctotal:	<?php echo $obj->getAttribute('crit_total_calc') ?>,
			emin:	<?php echo $obj->getAttribute('esq_min_calc') ?>,
			emax:	<?php echo $obj->getAttribute('esq_max_calc') ?>,
			etotal:	<?php echo $obj->getAttribute('esq_total_calc') ?>,
			conc:	<?php echo $obj->getAttribute('conc_calc') ?>,
			esquiva:	<?php echo $obj->getAttribute('esquiva_calc') ?>
		}
		<?php endfor; ?>

		_xc				= <?php echo isset($_GET['profile']) ? 'true' : 'false' ?>;
		var _buffTimer	= setInterval(function () {
			var html	= '';

			<?php for($f = 0; $f <= 1; $f++): ?>
			<?php
				$var	= $f ? 'emod' : 'pmod';
				$word	= $f ? 'E' : 'P';
				$obj	= $f ? $baseEnemy : $basePlayer;
			?>

			html	= '';
			for(var i in <?php echo $var ?>) {
				var c	= <?php echo $var ?>[i];

				html	+= '<img class="i-buff" width="22" rel="' + c.id + '" role="<?php echo strtolower($word) ?>"  src="<?php echo img() ?>' + c.i + '" />';
			}

			$('#cn<?php echo $word ?>Buff').html(html);
			<?php endfor; ?>

			$('.i-buff').bind('mouseover', function () {
				item_hover_f.apply(this, [null, pmod, emod])
			})
						.bind('mouseout', item_out_f);
		}, 1200);
	<?php else: ?>
		<?php
			$batalha	= Recordset::query('SELECT id_tipo FROM batalha_multi WHERE id=' . $basePlayer->id_batalha_multi)->row_array();
		?>
		<?php for($f = 0; $f <= 1; $f++): ?>
		<?php for($i = 1; $i <= 4; $i++): ?>
		<?php
			$var	= $f ? 'e' . $i .'mod' : 'p' . $i . 'mod';
		?>
		<?php echo $var ?> = [];
		<?php endfor; ?>
		<?php endfor; ?>

		var _buffTimer	= setInterval(function () {
			var html	= '';

			<?php for($f = 0; $f <= 1; $f++): ?>
			<?php for($i = 1; $i <= ($f == 1 ? ($batalha['id_tipo'] == 1 ? 1 : 4) : 4); $i++): ?>
			<?php
				$var	= $f ? 'e' . $i .'mod' : 'p' . $i . 'mod';
				$word	= $f ? 'E' : 'P';
			?>

			html	= '';
			for(var i in <?php echo $var ?>) {
				var c	= <?php echo $var ?>[i];

				html	+= '<img style="float: left" class="i-buff" rel="' + c.id + '" title="<?php echo $i ?>" role="<?php echo strtolower($word) ?>" width="18" src="<?php echo img() ?>' + c.i + '" />';
			}

			$('#cn<?php echo $word ?>Buff<?php echo $f == 0 ? $i : $i + 4 ?>').html(html);
			<?php endfor; ?>
			<?php endfor; ?>

			$('.i-buff').bind('mouseover', function () {
				var id = $(this).attr('title');

				item_hover_f.apply(this, [id, eval('p' + id + 'mod'), eval('e' + id + 'mod')]);
			}).bind('mouseout', item_out_f);
		}, 1200);
	<?php endif; ?>

	$(document).ready(function () {
		$('.pvp-atk-filter').on('click', function () {
			if(this.no_attacks || is_animating) {
				return;
			}

			var	_	= $(this);

			$('.pvp-atk-filter').removeClass('pvp-atk-filter-selected');
			_.addClass('pvp-atk-filter-selected');

			$('#d-actionbar .atk').each(function () {
				if(!$(this).hasClass('atk-' + _.data('filter'))) {
					$(this).stop().hide();
				} else {
					$(this).stop().show('fade');
				}
			});
		});

		$('.pvp-atk-filter').each(function () {
			if(!$('#d-actionbar .atk-' + $(this).data('filter')).length) {
				$(this).hide();
				this.no_attacks	= true;
			}

			if($(this).data('filter') == '<?php echo $player_role ?>') {
				$(this).trigger('click');
			}
		});

		$('.atk img, .a-esquerda-batalha .d-buff div img').bind('click', function () {
			var _this	= $(this);
			var i		= _items[_this.attr('role')];

			var action = 1;

			if(i.ste) {
				action = 3;

				for(var f in _nt_disabled) {
					if(_nt_disabled[f] == i.id) {
						jalert('<?php echo t('fight.f20')?>');

						return;
					}
				}
			} else if(i.st) {
				action = 2;
			}

			if(i.me) {
				action = 4;
			}

			if(_this.attr('id').match(/i-nt|atki-/i)) {
				if(_this.attr('id').match(/atki-/i)) {
					is_animating	= true;
				}

				$('.i-buff-status', $(this).parent().parent()).attr('src', $(this).attr('src'));

				_this.hide('explode', 'slow', function () {
					_this.show('drop', function () {
						if(_this.attr('id').match(/atki-/i)) {
							is_animating	= false;
						}
					});

					//$('.ui-effects-wrapper').remove();
				});
			}

			doAttack(i.id, action);

			_this.trigger('mouseout');
		}).bind('mouseover', item_hover_f)
		  .bind('mouseout', item_out_f);

		$('.a-esquerda-batalha .d-buff').bind('click', function () {
			var _this = $(this);

			if(!parseInt(_this.attr('shown'))) {
				$('div', _this).show('drop');

				_this.attr('shown', 1);
			} else {
				$('div', _this).hide();

				_this.attr('shown', 0);
			}
		});
	});

	function item_hover_f(mi, pp, ee) {
		var _this	= $(this);
		var html	= '';

		if(pp) {
			pmod = pp;
		}

		if(ee) {
			emod = ee;
		}

		if(_this.hasClass('i-buff')) {
			mi = parseInt(mi);

			if(_this.attr('role') == 'e') {
				for(var g in emod) {
					if(emod[g].id == _this.attr('rel')) {
						var i	= emod[g];
					}
				}
			} else {
				if(!isNaN(mi)) {
					var pmod = eval('p' + parseInt(mi) + 'mod');
				}

				for(var g in pmod) {
					if(pmod[g].id == _this.attr('rel')) {
						var i	= pmod[g];
					}
				}
			}
		} else {
			if(_this.hasClass('pvp_p_atk')) {
				var i	= __dyn_atk[_this.data('atk')];
			} else {
				var i	= _items[_this.attr('role')];
			}
		}

		_tooltip	= $(document.createElement('DIV'));

		$(document.body).append(_tooltip);

		html = '<b>' + i.n + '</b>' + (!i.ub ? '<span style="float: right">x' + i.t + '</span>' : '') + '<hr />';

		if(i.d) {
			html += '<div>' + i.d + '</div><hr />';
		}

		if(i.els && i.els.length) {
			html += '<b><?php echo t('fight.f21')?>:</b> (<?php echo t('fight.f23')?>)<br />' +
					'<b><?php echo t('fight.f22')?>:</b> (<?php echo t('fight.f38')?>)<hr />';

			for(var g in i.els) {
				if(i.els[g]) {
					html += '<?php echo t('fight.f24')?>: ' + i.els[g].n;

					html += '<blockquote style="padding: 0px; padding-left: 20px; margin: 0px"><b><?php echo t('fight.f21')?></b><br />';

					for(var h in i.els[g].f) {
						html += '' + i.els[g].f[h];
					}

					html += '</blockquote>';

					html += '<blockquote style="padding: 0px; padding-left: 20px; margin: 0px"><b><?php echo t('fight.f22')?></b><br />';

					for(var h in i.els[g].r) {
						html += '' + i.els[g].r[h];
					}

					html += '</blockquote>';
				}
			}
		}

		if(!i.st && !i.ste) { // Normal
			if(i.atkf) {
				html += "<img src='<?php echo img()?>layout/icones/atk_fisico.png' alt='<?php echo t('formula.atk_fisico')?>' align='absmiddle'/>";
				if(i.pre >= 100 && i.ll) {
					html += ' <?php echo t('formula.atk_fisico')?>: ' + i.atkf + ' + <span class="color_green">' +  percent(p.ctotal, i.atkf) + '</span> + (+' + p.atkf + ')<br />';
				} else {
					html += ' <?php echo t('formula.atk_fisico')?>: ' + i.atkf + ' (+' + p.atkf + ')<br />';
				}
			}

			if(i.atkm) {
				html += "<img src='<?php echo img()?>layout/icones/atk_magico.png' alt='<?php echo t('formula.atk_magico')?>' align='absmiddle'/>";

				if(i.pre >= 100 && i.ll) {
					html += ' <?php echo t('formula.atk_magico')?>: ' + i.atkm + ' + <span class="color_green">' + percent(p.ctotal, i.atkm) + '</span> + (+' + p.atkm + ')<br />';
				} else {
					html += ' <?php echo t('formula.atk_magico')?>: ' + i.atkm +' (+' + p.atkm + ')<br />';

				}
			}

			if(i.def) {
				html += "<img src='<?php echo img()?>layout/icones/shield.png' alt='<?php echo t('formula.def_base')?>' align='absmiddle'/>";

				if(i.pre >= 100 && i.ll) {
					html += ' <?php echo t('formula.def_base')?>: ' + i.def + ' + <span class="color_green">' + percent(p.ctotal, i.def) + '</span> + (+' + p.def + ')<br />';
				} else {
					html += ' <?php echo t('formula.def_base')?>: ' + i.def + ' (+' + p.def + ')<br />';
				}
			}

			if(i.deff) {
				html += "<img src='<?php echo img()?>layout/icones/def_fisico.png' alt='<?php echo t('formula.def_fisico')?>' align='absmiddle'/>";

				if(i.pre >= 100 && i.ll) {
					html += ' <?php echo t('formula.def_fisico')?>: ' + i.deff + ' + <span class="color_green">' + percent(p.ctotal, i.deff) + '</span> + (+' + p.deff + ')<br />';
				} else {
					html += ' <?php echo t('formula.def_fisico')?>: ' + i.deff + ' (+' + p.deff + ')<br />';
				}
			}

			if(i.defm) {
				html += "<img src='<?php echo img()?>layout/icones/def_magico.png' alt='<?php echo t('formula.def_magico')?>' align='absmiddle'/>";

				if(i.pre >= 100 && i.ll) {
					html += ' <?php echo t('formula.def_magico')?>: ' + i.defm + ' + <span class="color_green">' + percent(p.ctotal, i.defm) + '</span> + (+' + p.defm + ')<br />';
				} else {
					html += ' <?php echo t('formula.def_magico')?>: ' + i.defm + ' (+' + p.defm + ')<br />';
				}
			}
		} else if(i.st) { // Buff
			<?php for($f = 0; $f <= 1; $f++): ?>
			<?php if($f): ?>
			if(i.mo.e) {
				html += '<?php echo t('fight.f25')?><br />';
			<?php else: ?>
			if(i.mo.p) {
				html += '<?php echo t('fight.f26')?><br />';
			<?php endif ?>
				<?php foreach($modifiers_key as $v): ?>
					<?php
						if(strpos($v, !$f ? 'self_' : 'target_') === false) {
							continue;
						}

						$k		= $f ? substr($v, 7) : substr($v, 5);
						$js_k	=  'i.mo.' . ($f ? 'em' : 'pm') . '.' . $v;
					?>
					html += <?php echo $js_k ?> ? '<img src="<?php echo img($ar_mods[$k]['i']) ?>" /> <?php echo $ar_mods[$k]['nome'] ?>: ' + <?php echo $js_k ?> + '<br />' : '';
				<?php endforeach; ?>
			}
			<?php endfor; ?>
		}

		if(!i.ste && !i.ub && !i.st) {

			if (i.hab==1){
				html += "<img src='<?php echo img()?>layout/icones/prec_tai.png' alt='<?php echo t('formula.prec_fisico')?>' align='absmiddle'/> ";
			} else {
				html += "<img src='<?php echo img()?>layout/icones/prec_nin_gen.png' alt='<?php echo t('formula.prec_magico')?>' align='absmiddle'/> ";
			}

			var err_chance	= (100 - i.pre);

			if(err_chance > 100) {
				err_chance	= 100;
			}

			html += '<?php echo t('fight.f27')?> <span class="color_green">' + Math.round(i.pre < 0 ? 0 : i.pre, 2) + '%</span>' + (i.pre < 100 ? ' (<span class="color_red"> ' + Math.round(err_chance, 2) + '%</span> <?php echo t('fight.f28')?>)': '') + '<br />';

			if(i.pre >= 100 && i.ll) {
				html += "<img src='<?php echo img()?>layout/icones/target2.png' alt='<?php echo t('fight.f29')?>' align='absmiddle'/> ";
				html += '<?php echo t('fight.f29')?> ( <span class="color_green">' + p.conc + '% + <span style="color: #B01AA6 !important">' + i.enhance_crit + '%</span></span>)<br />';
			}

			html += '<hr />';
		} else if(i.ste) {
			<?php if($basePlayer->id_batalha_multi): ?>
			i = _items[i.id];
			<?php endif; ?>

			if(i.atkf) {
				html += "<img src='<?php echo img()?>layout/icones/atk_fisico.png' alt='<?php echo t('formula.atk_fisico')?>' align='absmiddle'/>";
				html += ' <?php echo t('formula.atk_fisico')?>: +' + i.atkf + '<br />';
			}

			if(i.atkm) {
				html += "<img src='<?php echo img()?>layout/icones/atk_magico.png' alt='<?php echo t('formula.atk_magico')?>' align='absmiddle'/>";
				html += ' <?php echo t('formula.atk_magico')?>: +' + i.atkm + '<br />';
			}

			if(i.tai) {
				html += "<img src='<?php echo img()?>layout/icones/tai.png' alt='Taijutsu' align='absmiddle'/>";
				html += ' Taijutsu: +' + i.tai + '<br />';
			}

			if(i.ken) {
				html += "<img src='<?php echo img()?>layout/icones/ken.png' alt='Bukijutsu' align='absmiddle'/>";
				html += ' Bukijutsu: +' + i.ken + '<br />';
			}

			if(i.nin) {
				html += "<img src='<?php echo img()?>layout/icones/nin.png' alt='Ninjutsu' align='absmiddle'/>";
				html += ' Ninjutsu: +' + i.nin + '<br />';
			}

			if(i.gen) {
				html += "<img src='<?php echo img()?>layout/icones/gen.png' alt='Genjutsu' align='absmiddle'/>";
				html += ' Genjutsu: +' + i.gen + '<br />';
			}

			if(i.agi) {
				html += "<img src='<?php echo img()?>layout/icones/agi.png' alt='<?php echo t('at.agi')?>' align='absmiddle'/>";
				html += ' <?php echo t('at.agi')?>: +' + i.agi + '<br />';
			}

			if(i.con) {
				html += "<img src='<?php echo img()?>layout/icones/conhe.png' alt='<?php echo t('at.con')?>' align='absmiddle'/>";
				html += ' <?php echo t('at.con')?>: +' + i.con + '<br />';
			}

			if(i.ene) {
				html += "<img src='<?php echo img()?>layout/icones/ene.png' alt='<?php echo t('at.ene')?>' align='absmiddle'/>";
				html += ' <?php echo t('at.ene')?>: +' + i.ene + '<br />';
			}

			if(i.forc) {
				html += "<img src='<?php echo img()?>layout/icones/forc.png' alt='<?php echo t('at.for')?>' align='absmiddle'/>";
				html += ' <?php echo t('at.for')?>: +' + i.forc + '<br />';
			}

			if(i.inte) {
				html += "<img src='<?php echo img()?>layout/icones/inte.png' alt='<?php echo t('at.int')?>' align='absmiddle'/>";
				html += ' <?php echo t('at.int')?>: +' + i.inte + '<br />';
			}

			if(i.res) {
				html += "<img src='<?php echo img()?>layout/icones/defense.png' alt='<?php echo t('at.res')?>' align='absmiddle'/>";
				html += ' <?php echo t('at.res')?>: +' + i.res + '<br />';
			}
			if(i.def) {
				html += "<img src='<?php echo img()?>layout/icones/shield.png' alt='<?php echo t('formula.def_base')?>' align='absmiddle'/>";
				html += ' <?php echo t('formula.def_base')?>: ' + i.def + '<br />';
			}
			if(i.precf) {
				html += "<img src='<?php echo img()?>layout/icones/prec_tai.png' alt='<?php echo t('formula.prec_fisico')?>' align='absmiddle'/>";
				html += ' <?php echo t('formula.prec_fisico')?>: ' + i.precf + '<br />';
			}

			if(i.precm) {
				html += "<img src='<?php echo img()?>layout/icones/prec_nin_gen.png' alt='<?php echo t('formula.prec_magico')?>' align='absmiddle'/>";
				html += ' <?php echo t('formula.prec_magico')?>: ' + i.precm + '<br />';
			}

			/*if(i.cmin) {
				html += ' <?php echo t('formula.crit_min')?>: ' + i.cmin + ' %<br />';
			}

			if(i.cmax) {
				html += ' <?php echo t('formula.crit_max')?>: ' + i.cmax + ' %<br />';
			}
			if(i.emin) {
				html += ' <?php echo t('formula.esq_min')?>: ' + i.cmin + ' %<br />';
			}

			if(i.emax) {
				html += ' <?php echo t('formula.esq_max')?>: ' + i.cmax + ' %<br />';
			}*/
			if(i.ctotal) {
				html += ' <?php echo t('formula.crit_total')?>: ' + i.ctotal + ' %<br />';
			}
			if(i.etotal) {
				html += ' <?php echo t('formula.esq_total')?>: ' + i.etotal + ' %<br />';
			}
			if(i.esq) {
				html += "<img src='<?php echo img()?>layout/icones/esquiva.png' alt='<?php echo t('formula.esq')?>' align='absmiddle'/>";
				html += ' <?php echo t('formula.esq')?>: ' + i.esq + ' %<br />';
			}

			if(i.det) {
				html += "<img src='<?php echo img()?>layout/icones/deter.png' alt='<?php echo t('formula.det')?>' align='absmiddle'/>";
				html += ' <?php echo t('formula.det')?>: ' + i.det + ' %<br />';
			}

			if(i.conv) {
				html += "<img src='<?php echo img()?>layout/icones/convic.png' alt='<?php echo t('formula.conv')?>' align='absmiddle'/>";
				html += ' <?php echo t('formula.conv')?>: ' + i.conv + ' %<br />';
			}

			if(i.conc) {
				html += "<img src='<?php echo img()?>layout/icones/target2.png' alt='<?php echo t('formula.conc')?>' align='absmiddle'/>";
				html += ' <?php echo t('formula.conc')?>: ' + i.conc + ' %<br />';
			}
			if(i.esquiva) {
				html += "<img src='<?php echo img()?>layout/icones/esquiva.png' alt='<?php echo t('formula2.esquiva')?>' align='absmiddle'/>";
				html += ' <?php echo t('formula2.esquiva')?>: ' + i.esquiva + ' %<br />';
			}

			if(i.b_hp) {
				html += "<img src='<?php echo img()?>layout/icones/p_hp.png' alt='<?php echo t('formula.hp')?>' align='absmiddle'/>";

				if(i.me) {
					html += ' <?php echo t('formula.hp')?>: ' + i.b_hp + '<br />';
				} else {
					html += ' <?php echo t('formula.hp')?>: ' + i.b_hp + '%<br />';
				}
			}

			if(i.b_sp) {
				html += "<img src='<?php echo img()?>layout/icones/p_chakra.png' alt='<?php echo t('formula.sp')?>' align='absmiddle'/>";

				if(i.me) {
					html += ' <?php echo t('formula.sp')?>: ' + i.b_sp + ' <br />';
				} else {
					html += ' <?php echo t('formula.sp')?>: ' + i.b_sp + ' %<br />';
				}
			}

			if(i.b_sta) {
				html += "<img src='<?php echo img()?>layout/icones/p_stamina.png' alt='<?php echo t('formula.sta')?>' align='absmiddle'/>";

				if(i.me) {
					html += ' <?php echo t('formula.sta')?>: ' + i.b_sta + '<br />';
				} else {
					html += ' <?php echo t('formula.sta')?>: ' + i.b_sta + '%<br />';
				}
			}
		}

		if((i.st || i.ste) && !i.ub) {
			html += '<br /><b><?php echo t('fight.f30')?></b><br />';
		}

		if(i.tr) {
			html += '<?php echo t('fight.f31')?>: ' + i.tr + ' <?php echo t('fight.f33')?>' + (i.trl ? '(<?php echo t('fight.f34')?> ' + i.trl +' <?php echo t('fight.f33')?>)' : '') + '<br />';
		}

		if(i.du) {
			html += '<?php echo t('fight.f32')?>: ' + i.du + ' <?php echo t('fight.f33')?><br />';
		}

		if(!i.ub && (i.chp || i.csp || i.csta)) {
			html 	+= '<hr /><?php echo t('fight.f35')?>: ';

			if(i.chp) {
				html	+= '<img src="<?php echo img($ar_mods['hp']['i']) ?>" align="absmiddle" /> ' + i.chp;
			}

			if(i.csp) {
				if(i.chp) {
					html	+= ' / ';
				}

				html	+= '<?php echo t('fight.f35')?>: <img src="<?php echo img($ar_mods['sp']['i']) ?>" align="absmiddle"/> ' + i.csp;
			}

			if(i.csta) {
				if(i.chp || i.csp) {
					html	+= ' / ';
				}

				html	+= '<img src="<?php echo img($ar_mods['sta']['i']) ?>" align="absmiddle" /> ' + i.csta;
			}

		}

		if (i.war_timer) {
			html	+= '<hr /><b>Tempo restante:</b> ' + (i.war_timer.days < 1 ? 'Menos de 24 horas' : i.war_timer.days + ' dia(s) e ' + i.war_timer.hours + ' hora(s)');
		}

		_tooltip.addClass('atk-tooltip')
				.css('top', _this.offset().top)
				.html(html)
				.show('fade');

		var margin	= (($(window).width() - 1000) / 2) - 50;
		var left	= _this.offset().left;

		if((left - margin) + _tooltip.width() > 1000) {
			left	= _this.offset().left - _tooltip.width() - 23;
		} else {
			left	+=  + _this.width() + 3;
		}

		_tooltip.css('left', left);
	}

	function item_out_f() {
		if(_tooltip) {

			_tooltip.remove();
			$('.atk-tooltip').remove();
		}
	}

	$('.d-buff .i-buff-status').css('opacity', .2);

	var _check_timer = setInterval(function () {
		for(var i in _items) {
			if(!_check_triggers[i]) {
				_check_triggers[i] = {
					is_locked:	false
				}
			}

			var c	= _items[i];
			var ct	= _check_triggers[i];
			// Desativa
			if(c.trl && !ct.is_locked) {
				ct.is_locked = true;

				$('#atki-' + c.id).animate({opacity: .3});

			}
			if(c.trl){
				$('.atki-' + c.id).html(c.trl);
			}
			// Ativa
			if(!c.trl && ct.is_locked) {

				ct.is_locked = false;

				var o	= $('#atki-' + c.id);
				if(_cur_atk_tab == o.parent().attr('class').replace(/[^\d]+/, '')) {
					o.animate({opacity: 1})
					 .parent().effect('highlight', null, 500);
				} else {
					$('#atki-' + c.id).css('opacity', 1);
				}
			}
		}
	}, 700)

	<?php if(!$basePlayer->id_batalha_multi): ?>
	$(document).ready(function(e) {
		setPValue2(p.hp,  (p.mhp  || 1), "<?php echo t('formula.hp')?>",	$("#cnPHP"),  1);
		setPValue2(p.sp,  (p.msp  || 1), "Chakra",	$("#cnPSP"),  1);
		setPValue2(p.sta, (p.msta || 1), "Stamina",	$("#cnPSTA"), 1);

		setPValue2(<?php echo $baseEnemy->getAttribute('hp') ?>,  (<?php echo $baseEnemy->getAttribute('max_hp') ?>  || 1), "<?php echo t('formula.hp')?>",	$("#cnEHP"),  1);
		setPValue2(<?php echo $baseEnemy->getAttribute('sp') ?>,  (<?php echo $baseEnemy->getAttribute('max_sp') ?>  || 1), "Chakra",	$("#cnESP"),  1);
		setPValue2(<?php echo $baseEnemy->getAttribute('sta') ?>, (<?php echo $baseEnemy->getAttribute('max_sta') ?> || 1), "Stamina",	$("#cnESTA"), 1);
	});

	<?php

		if(!is_a($baseEnemy, "NPC")) {
			Recordset::update('batalha', array(
				'do_updates'	=> 1,
				'do_updatesb'	=> 1
			), array(
				'id'			=> $basePlayer->id_batalha
			));
		}
	?>
	<?php endif; ?>
	/*
			_this.parent().effect('highlight');
		_this.animate({opacity: 1});

	*/

	function numericAnimateTo(v, text, o) {
		var o = $(o);
		var intervals	= v.toString().length;
		var ot			= v.toString();
		var cc			= 0;
		var cc2			= intervals;
		var c			= 0;
		var t			= "";
		var rnd			= 512384;

		if(parseInt(o.html()) == parseInt(v)) {
			return;
		}

		var _i = setInterval(function () {
			if(cc == intervals) {
				o.html(text + " " + ot);

				clearInterval(_i);
				return;
			}

			if(!(c % 6)) {
				cc2--;

				if(ot[cc2]) {
					t = ot[cc2].toString() + t;
				}

				rnd = 1024 * Math.abs(cc2);

				cc++;
			}

			o.html(text + " " + Math.round(Math.random() * rnd) + t);

			c++;
		}, 50);
	}
</script>
<?php if(!$evento4) {?>
	<div align="center" style="position: relative; top: 5px">
	<script type="text/javascript">
		google_ad_client = "ca-pub-9166007311868806";
		google_ad_slot = "2217558578";
		google_ad_width = 728;
		google_ad_height = 90;
	</script>
	<!-- NG - Combate -->
	<script type="text/javascript"
	src="//pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
	</div>
<?php }?>
