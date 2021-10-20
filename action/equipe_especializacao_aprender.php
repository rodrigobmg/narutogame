<?php
	$redir_script = true;

	if($_POST['key'] != $_SESSION['equipe_esp_key']) {
		redirect_to('negado', NULL, array('e' => 1));
	}
	
	if(!between($_POST['role'], 0, 6)) {
		redirect_to('negado', NULL, array('e' => 2));
	}
	
	if(!between($_POST['level'], 1, 5)) {
		redirect_to('negado', NULL, array('e' => 3));
	}

	$field		= 'equipe_role_' . (int)$_POST['role'] . '_lvl';
	$role_lvl	= Player::getFlag($field, $basePlayer->id);
	$current_pt	= $basePlayer->getAttribute('exp_equipe_dia_total');

	if($role_lvl == 5) {
		redirect_to('negado', NULL, array('e' => 5));	
	}

	$lvl_exp = array(
		'1' => 2100,
		'2' => 4200,
		'3' => 6300,
		'4' => 8400,
		'5' => 14000
	);

	$role_exp = $lvl_exp[($role_lvl + 1)];
	$reqs = $current_pt >= $lvl_exp[($role_lvl + 1)];
	
	$f = $role_lvl + 1;
	
	/*if($f == 5) {
		$reqs = $reqs && $basePlayer->getAttribute('coin') >= 2;
	}*/
	
	$reqs = $reqs && between($_POST['level'], 1, 5);

	if(!$reqs) {
		redirect_to('negado', NULL, array('e' => 4));
	}
	
	$basePlayer->setFlag($field, $role_lvl + 1);
	
	Recordset::query('UPDATE player SET exp_equipe_dia_total=exp_equipe_dia_total-' . (int)$role_exp . ' WHERE id=' . $basePlayer->id);

	/*if($f == 5) {
		gasta_coin(2);
	}*/

	redirect_to('equipe_especializacao', NULL, array('ok' => 1));
