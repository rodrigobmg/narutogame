<?php
	ignore_user_abort(true);
	set_time_limit(120);
	
	function _get_equipe_status($equipe, $as_array = false) {
		$equipe_status = get_equipe_status($equipe);
		
		if($as_array) {
			$msg	= array();		
		} else {
			$msg	= "";
		}
		
		if($equipe_status['players'] != 4) {
			$msg	.= t('actions.a143');
		} else {
			for($f = 0; $f <= 3; $f++) {
				if(!$equipe_status[$f]['activity']) {
					if(!$_SESSION['universal']) {
						if($as_array) {
							$msg[]	= t('actions.a144')." " . addslashes($equipe_status[$f]['name']) .' '. t('actions.a145');
						} else {
							$msg	.= "<li class='laranja'>".t('actions.a144')." " . addslashes($equipe_status[$f]['name']) .' '. t('actions.a145')."</li>";
						}						
					}
				}
		
				if($equipe_status[$f]['battle']) {
					if($as_array) {
						$msg[]	= t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a146');
					} else {
						$msg	.= "<li class='laranja'>".t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a146')."</li>";						
					}
				}
		
				if($equipe_status[$f]['quest']) {
					if($as_array) {
						$msg[]	= t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a147');
					} else {
						$msg	.= "<li class='laranja'>".t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a147')."</li>";
					}
				}
		
				if($equipe_status[$f]['nomap']) {
					if($as_array) {
						$msg[]	= t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a148');
					} else {
						$msg	.= "<li class='laranja'>".t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a148')."</li>";
					}
				}
		
				if($equipe_status[$f]['hospital']) {
					if($as_array) {
						$msg[]	= t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a150');
					} else {
						$msg	.= "<li class='laranja'>".t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a150')."</li>";
					}
				}
		
				if($equipe_status[$f]['tjutsu']) {
					if($as_array) {
						$msg[]	= t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a151');
					} else {
						$msg	.= "<li class='laranja'>".t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a151')."</li>";
					}
				}
		
				if($equipe_status[$f]['train']) {
					if($as_array) {
						$msg[]	= t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a152');
					} else {
						$msg	.= "<li class='laranja'>".t('actions.a144')." " . addslashes($equipe_status[$f]['name'])  .' '.  t('actions.a152')."</li>";
					}
				}
				
				$ips[] = $equipe_status[$f]['ip'];
				
				if($msg && !$as_array) {
					$msg	= '<ul>' . $msg . '</ul>';
				}
			}			
		}
		
		return $msg;
	}
?>
<?php if(isset($_POST['mode']) && $_POST['mode'] == 'search'): ?>
	<?php
		if(!$basePlayer->dono_equipe) {
			die(t('equipe.pvp.battle.only_leader'));
		}
		
		if($basePlayer->id_sala_multi_pvp || $basePlayer->id_batalha_multi_pvp) {
			die('Já em sala de espera ou batalha');
		}
	?>
	<?php
		$queued	= Recordset::query('SELECT * FROM batalha_multi_pvp_espera WHERE ranked=0');
		$msg	= _get_equipe_status($basePlayer->id_equipe);

		if(!$msg) {
			$_SESSION['team_pvp_battle_enter']	= true;
		} else {
			$_SESSION['team_pvp_battle_enter']	= false;			
		}

		// REMOVE PROD
		/*
		if($basePlayer->id_equipe != 319) {
			$msg	= "Sua equipe não pode aceitar batalhas no momento";
		}
		*/
		
		if($msg) {
			die(msg(1, t('equipe.pvp.battle.problem'), sprintf(t('equipe.pvp.battle.problem_msg'), $msg)));
		}
	?>
	<table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td height="49" background="<?php echo img() ?>layout/barra_secoes/1.png">
            <table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
                <tr>
                    <td width="90" align="center">
                    </td>
					<td width="180" align="center">
                    	<?php echo t('equipe.participar.nome')?>
                    </td>
					 <td width="180" align="center">
                    	<?php echo t('equipe.participar.nome_vila')?>
                    </td>
                    <td width="180" align="center">
                   		<?php echo t('equipe.participar.range_combate')?>
                    </td>
                    <td width="90" align="center">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
	<table width="730" border="0" cellpadding="0" cellspacing="0">
		
	<?php if(!$queued->num_rows): ?>
		<tr>
			<td><?php echo t('equipe.pvp.battle.none') ?></td>
		</tr>
	<?php endif ?>
	<?php 
		$c 			= 0;
		$team_mine	= Recordset::query('SELECT a.*, b.id_vila, ((SELECT SUM(level) FROM player aa WHERE aa.id_equipe=a.id) / 4) AS media_level FROM equipe a JOIN player b ON b.id=a.id_player WHERE a.id=' . $basePlayer->id_equipe)->row_array();

		foreach($queued->result_array() as $queue): 
	?>
		<?php
			$bg 	= ++$c % 2 ? "bgcolor='#0f294a'" : "bgcolor='#040f1c'";	
			$team	= Recordset::query('SELECT a.*, b.id_vila, ((SELECT SUM(level) FROM player aa WHERE aa.id_equipe=a.id) / 4) AS media_level FROM equipe a JOIN player b ON b.id=a.id_player WHERE a.id=' . $queue['id_equipe'])->row_array();
		?>
		<tr>
			<td width="90" <?php echo $bg ?> height="35"></td>
			<td width="180" <?php echo $bg ?>><b class="amarelo" style="font-size:13px"><?php echo $team['nome'] ?></b><br /> Equipe Level <?php echo $team['level'] ?></td>
			<td width="180" <?php echo $bg ?>><?php echo Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM vila WHERE id=' . $team['id_vila'], true)->row()->nome ?></td>
			<td width="180" <?php echo $bg ?>><?php echo round($team['media_level']) ?></td>
			<td width="90" <?php echo $bg ?>>
				<?php if(!$msg): ?>
					<?php if(between($team_mine['media_level'], $team['media_level'] - 5, $team['media_level'] + 5) || $_SESSION['universal']): ?>
						<a href="javascript:;" class="accept button" data-id="<?php echo $queue['id'] ?>"><?php echo t('equipe.pvp.battle.accept') ?></a>
					<?php else: ?>
						<a href="javascript:;" class="button ui-state-disabled"><?php echo t('equipe.pvp.battle.accept') ?></a>
					<?php endif ?>
				<?php endif ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
<?php if(isset($_POST['mode']) && $_POST['mode'] == 'battle'): ?>
	<?php
		$json			= new stdClass();
		$json->success	= false;
		$json->messages	= array();
		
		if(isset($_POST['id']) && is_numeric($_POST['id'])) {
			$queue			= Recordset::query('SELECT * FROM batalha_multi_pvp_espera WHERE id=' . $_POST['id']);
			
			if(!$queue->num_rows) {
				$json->messages[]	= t('equipe.pvp.battle.invalid');				
			}
		} else {
			$json->messages[]	= t('equipe.pvp.battle.invalid');
		}

		if(!isset($_SESSION['team_pvp_battle_enter']) || (isset($_SESSION['team_pvp_battle_enter']) && !$_SESSION['team_pvp_battle_enter'])) {
			$json->messages[]	= t('equipe.pvp.battle.requirements');			
		}

		if($basePlayer->id_sala_multi_pvp || $basePlayer->id_batalha_multi_pvp) {
			$json->messages[]	= 'Já em sala de espera ou batalha';
		}

		if(!sizeof($json->messages)) {
			$json->success	= true;
			
			Recordset::delete('batalha_multi_pvp_espera', array(
				'id'	=> $_POST['id']
			));

			function _make_object($c, $instance) {
				$out					= new stdClass();
				$out->id				= $instance->id;
				$out->level				= $instance->level;
				
				$out->hp				= new stdClass();
				$out->hp->max			= $instance->max_hp;
				$out->hp->current		= $instance->hp;

				$out->sp				= new stdClass();
				$out->sp->max			= $instance->max_sp;
				$out->sp->current		= $instance->sp;

				$out->sta				= new stdClass();
				$out->sta->max			= $instance->max_sta;
				$out->sta->current		= $instance->sta;
				
				$out->name				= $instance->nome;
				$out->headline			= $instance->getAttribute('nome_titulo');
				$out->graduation		= $instance->getAttribute('nome_graduacao');
				
				$out->crits				= new stdClass();
				$out->crits->min		= $instance->getAttribute('crit_min_calc');
				$out->crits->max		= $instance->getAttribute('crit_max_calc');
				$out->crits->original	= $instance->getAttribute('conc_calc');
				$out->crits->current	= $instance->getAttribute('conc_calc');
				$out->crits->total		= $instance->getAttribute('max_crit_hits');
				$out->crits->used		= 0;
				
				$out->esqs				= new stdClass();
				$out->esqs->min			= $instance->getAttribute('esq_min_calc');
				$out->esqs->min			= $instance->getAttribute('esq_max_calc');
				$out->esqs->original	= $instance->getAttribute('esq_calc');
				$out->esqs->current		= $instance->getAttribute('esq_calc');
				$out->esqs->total		= $instance->getAttribute('max_esq_hits');
				$out->esqs->used		= 0;
				
				$out->atks				= new stdClass();
				$out->atks->f			= $instance->getAttribute('atk_fisico_calc');
				$out->atks->m			= $instance->getAttribute('atk_magico_calc');

				$out->precs				= new stdClass();
				$out->precs->f			= $instance->getAttribute('prec_fisico_calc');
				$out->precs->m			= $instance->getAttribute('prec_magico_calc');

				$out->def				= $instance->getAttribute('def_base_calc');
				$out->det				= $instance->getAttribute('det_calc');
				$out->conv				= $instance->getAttribute('conv_calc');
				
				$out->mods				= array();
				$out->alive				= true;
				$out->elements			= $instance->getElementos();
				
				$out->role_id			= Player::getFlag('equipe_role', $instance->id);
				
				if($out->role_id != '') {
					$out->role_lvl		= Player::getFlag('equipe_role_' . $out->role_id . '_lvl', $instance->id);
				} else {
					$out->role_lvl		= NULL;
				}
				
				return $out;
			}

			$players		= Recordset::query('SELECT id FROM player WHERE id_equipe=' . $basePlayer->id_equipe);
			$enemies		= Recordset::query('SELECT id FROM player WHERE id_equipe=' . $queue->row()->id_equipe);
			$ids			= array();
			$conv_p			= 0;
			$conv_e			= 0;
			$instances_p	= array();
			$instances_e	= array();
			$data			= array(
				'data_atk'		=> array('escape' => false, 'value' => 'NOW()'),
				'current'		=> 1,
				'id_equipe_a'	=> $basePlayer->id_equipe,
				'id_equipe_b'	=> $queue->row()->id_equipe
			);
			
			$counter	= 1;
			$range_p	= 0;
			$range_e	= 0;
			
			foreach($players->result_array() as $player) {
				$ids[]							= $player['id'];
				$instance						= new Player($player['id']);
				$instance->clearModifiers();
				
				$instances_p['p' . $counter]	= $instance;
				$data['p' . $counter++]			= _make_object($counter, $instance);
				$conv_p							+= $instance->getAttribute('conv_calc');
				
				$range_p	+= $instance->level;
			}

			$counter	= 1;
			foreach($enemies->result_array() as $player) {
				$ids[]							= $player['id'];
				$instance						= new Player($player['id']);
				$instance->clearModifiers();

				$instances_p['e' . $counter]	= $instance;
				$data['e' . $counter++]			= _make_object($counter, $instance);
				$conv_e							+= $instance->getAttribute('conv_calc');

				$range_e	+= $instance->level;
			}
			
			$conv_e		= $conv_e / (PVPT_MAX_TURNS / 2);
			$conv_p		= $conv_e / (PVPT_MAX_TURNS / 2);
			$range_p	= $range_p / (PVPT_MAX_TURNS / 2);
			$range_e	= $range_e / (PVPT_MAX_TURNS / 2);
			
			$data['range_a']	= $range_p;
			$data['range_b']	= $range_e;
			
			// Update crits and conviction(also the serializatino was move to here) (should do this shit every attack and buff rotation later =( ) -->
				foreach($instances_p as $_ => $instance) {
					$data[$_]->crits->original	= $instance->getAttribute('conc_calc');
					$data[$_]->esqs->original	= $instance->getAttribute('esq_calc');
					$data[$_]->crits_esqs_red	= $conv_e;				
				
					$instance->setLocalAttribute('less_conv', $conv_e);
					$instance->atCalc();
					
					$data[$_]->conv_team		= $conv_p;
					
					$data[$_]->crits->min		= $instance->getAttribute('crit_min_calc');
					$data[$_]->crits->max		= $instance->getAttribute('crit_max_calc');
					$data[$_]->crits->current	= $instance->getAttribute('conc_calc');
					$data[$_]->crits->total		= $instance->getAttribute('max_crit_hits');

					$data[$_]->esqs->min		= $instance->getAttribute('esq_min_calc');
					$data[$_]->esqs->min		= $instance->getAttribute('esq_max_calc');
					$data[$_]->esqs->current	= $instance->getAttribute('esq_calc');
					$data[$_]->esqs->total		= $instance->getAttribute('max_esq_hits');
					
					$data[$_]					= serialize($data[$_]);
				}

				foreach($instances_e as $_ => $instance) {
					$data[$_]->crits->original	= $instance->getAttribute('conc_calc');
					$data[$_]->esqs->original	= $instance->getAttribute('esq_calc');
					$data[$_]->crits_esqs_red	= $conv_p;				

					$instance->setLocalAttribute('less_conv', $conv_p);
					$instance->atCalc();

					$data[$_]->conv_team		= $conv_e;
	
					$data[$_]->crits->min		= $instance->getAttribute('crit_min_calc');
					$data[$_]->crits->max		= $instance->getAttribute('crit_max_calc');
					$data[$_]->crits->current	= $instance->getAttribute('conc_calc');
					$data[$_]->crits->total		= $instance->getAttribute('max_crit_hits');

					$data[$_]->esqs->min		= $instance->getAttribute('esq_min_calc');
					$data[$_]->esqs->min		= $instance->getAttribute('esq_max_calc');
					$data[$_]->esqs->current	= $instance->getAttribute('esq_calc');
					$data[$_]->esqs->total		= $instance->getAttribute('max_esq_hits');

					$data[$_]					= serialize($data[$_]);
				}
			// <--
			
			$battle_id	= Recordset::insert('batalha_multi_pvp', $data);
			
			Recordset::update('player', array(
				'id_batalha_multi_pvp'	=> $battle_id,
				'id_sala_multi_pvp'		=> 0
			), array(
				'id'					=> array('escape' => false, 'mode' => 'in', 'value' => join(',', $ids))
			));
		}

		die(json_encode($json));		
	?>
<?php endif; ?>
<?php if(isset($_POST['mode']) && $_POST['mode'] == 'queue'): ?>
	<?php
		if(!$basePlayer->dono_equipe) {
			die(t('equipe.pvp.battle.only_leader'));
		}

		if($basePlayer->id_sala_multi_pvp || $basePlayer->id_batalha_multi_pvp) {
			die('Já em sala de espera ou batalha');
		}
	?>
	<?php
		$msg	= _get_equipe_status($basePlayer->id_equipe);
		
		if(!$msg) {
			$_SESSION['team_pvp_battle_queue']	= true;
		} else {
			$_SESSION['team_pvp_battle_queue']	= false;			
		}

		/*
		if($basePlayer->id_equipe != 2) {
			$msg	= "Sua equipe não pode criar salas de espera no momento";
		}
		*/
		
		if($msg) {
			die(msg(1, t('equipe.pvp.queue_problem'), sprintf(t('equipe.pvp.queue_problem_msg'), $msg)));
		}
	?>
	<?php if(!$msg): ?>
		<br />
		<?php msg('2',''.t('equipe.pvp.battle.pvp1').'', ''.t('equipe.pvp.battle.pvp2').'.<br /><br /><a href="javascript:;" class="accept button">'. t('equipe.pvp.battle.accept') .'</a>');?>
		
	<?php endif ?>
<?php endif; ?>
<?php if(isset($_POST['mode']) && $_POST['mode'] == 'make-queue'): ?>
<?php
		header('Content-Type: application/json');
	
		$json			= new stdClass();
		$json->success	= false;
		$json->messages	= array();
		
		if(!isset($_SESSION['team_pvp_battle_queue']) || (isset($_SESSION['team_pvp_battle_queue']) && !$_SESSION['team_pvp_battle_queue'])) {
			$json->messages[]	= t('equipe.pvp.battle.requirements');			
		}

		if(!$basePlayer->dono_equipe) {
			$json->messages[]	= t('equipe.pvp.battle.only_leader');
		}

		if($basePlayer->id_sala_multi_pvp || $basePlayer->id_batalha_multi_pvp) {
			$json->messages[]	= 'Já em sala de espera ou batalha';
		}
		
		if(!sizeof($json->messages)) {
			$json->success	= true;
			
			$wait_id	= Recordset::insert('batalha_multi_pvp_espera', array(
				'id_equipe'	=> $basePlayer->id_equipe
			));
			
			Recordset::update('player', array(
				'id_sala_multi_pvp'	=> $wait_id
			), array(
				'id_equipe'			=> $basePlayer->id_equipe
			));
		}
		
		die(json_encode($json));
	?>
<?php endif; ?>