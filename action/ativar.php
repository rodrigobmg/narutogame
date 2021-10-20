<?php
	if(!isset($_GET['token'])) {
		redirect_to('negado');
	}
	
	$user = Recordset::query('SELECT id FROM global.user WHERE `key`=\'' . addslashes($_GET['token']) . '\' AND active=\'0\'');
	
	if(!$user->num_rows) {
		redirect_to("ativacao_erro");
	} else {
		Recordset::update('global.user', array(
			'active'	=> '1'
		), array(
			'id'		=> $user->row()->id
		));
	
		redirect_to("ativacao_ativado");	
	}
