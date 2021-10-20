<?php
	$healing_was_made	= false;
	
	$now			= new DateTime();
	$last_heal		= new DateTime($basePlayer->last_healed_at);
	$less_hp		= $basePlayer->less_hp;
	$less_sp		= $basePlayer->less_sp;
	$less_sta		= $basePlayer->less_sta;
	$run_update		= false;
	$is_battle		= true;

	if(!$basePlayer->last_healed_at) {
		$basePlayer->last_healed_at	= date('Y-m-d H:i:s');

		Recordset::update('player', [
			'last_healed_at'	=> date('Y-m-d H:i:s')
		], [
			'id'	=> $basePlayer->id
		]);
	}

	if(!$basePlayer->id_batalha && !$basePlayer->id_batalha_multi && !$basePlayer->id_batalha_multi_pvp) {
		$is_battle	= false;
		$heal_diff	= $now->diff($last_heal);
		$num_runs	= floor((($heal_diff->d * (24 * 60)) + ($heal_diff->h * 60) + $heal_diff->i) / 2);

		if($less_hp > 0 || $less_sp > 0 || $less_sta > 0) {
			$heal_percent	= gHasItemW(21770, $basePlayer->id) ? 30 : 20;
			$current_runs	= 0;

			if ($num_runs > 0) {
				$max_hp		= $_SESSION['healing_' . $basePlayer->id]['hp'];
				$max_sp		= $_SESSION['healing_' . $basePlayer->id]['sp'];
				$max_sta	= $_SESSION['healing_' . $basePlayer->id]['sta'];

				$hp_heal	= percent($heal_percent, $max_hp);
				$sp_heal	= percent($heal_percent, $max_sp);
				$sta_heal	= percent($heal_percent, $max_sta);

				while($current_runs++ < $num_runs) {
					if($less_hp > 0) {
						$less_hp	-= $hp_heal;
					} else {
						$less_hp	= 0;
					}

					if($less_sp > 0) {
						$less_sp	-= $sp_heal;
					} else {
						$less_sp	= 0;
					}

					if($less_sta > 0) {
						$less_sta	-= $sta_heal;
					} else {
						$less_sta	= 0;
					}

					if($less_hp == 0 && $less_sp == 0 && $less_sta == 0) {
						break;
					}
				}

				$run_update			= true;
				$healing_was_made	= true;
			}
		} else {
			if($num_runs) {
				$run_update	= true;
			}				
		}
	} else {
		$heal_diff		= $now->diff($last_heal);
		$num_runs		= floor((($heal_diff->d * (24 * 60)) + ($heal_diff->h * 60) + $heal_diff->i) / 2);

		if($num_runs) {
			$run_update	= true;
		}
	}

	if($run_update) {
		if($num_runs) {
			$heal_date	= date('Y-m-d H:i:s', strtotime('+' . ($num_runs * 2) . ' minute', strtotime($basePlayer->last_healed_at)));

			if(strtotime($heal_date) > now()) {
				$heal_date	= now(true);
			}
		} else {
			$heal_date	= $basePlayer->last_healed_at;
		}

		if(!$is_battle) {
			
			$basePlayer->hp 	= isset($basePlayer->hp) ? $basePlayer->hp : 0;
			$basePlayer->max_hp = isset($basePlayer->max_hp) ? $basePlayer->max_hp : 0;	
				
			if($basePlayer->hp > $basePlayer->max_hp || $less_hp < 0) {
				$less_hp	= 0;
			}

			if($basePlayer->sp > $basePlayer->max_sp || $less_sp < 0) {
				$less_sp	= 0;
			}

			if($basePlayer->sta > $basePlayer->max_sta || $less_sta < 0) {
				$less_sta	= 0;
			}
		}

		Recordset::update('player', [
			'less_hp'			=> $less_hp,
			'less_sp'			=> $less_sp,
			'less_sta'			=> $less_sta,
			'last_healed_at'	=> $heal_date,//date('Y-m-d H:i:s'),
			'hospital'			=> $basePlayer->hospital && (!$less_sp && !$less_hp && !$less_sta) ? 0 : $basePlayer->hospital
		], [
			'id'				=> $basePlayer->id
		]);

		if(method_exists($basePlayer, 'atCalc')) {
			$basePlayer->setAttribute('less_hp',  $less_hp,  true);
			$basePlayer->setAttribute('less_sp',  $less_sp,  true);
			$basePlayer->setAttribute('less_sta', $less_sta, true);

			$basePlayer->atCalc();
		}
	}
