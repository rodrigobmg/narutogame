<?php
	$json			= new stdClass();
	$json->success	= false;
	$json->messages	= array();

	$equipe	= Recordset::query("SELECT * FROM equipe WHERE id=" . (int)$_POST['equipe']);

	if($equipe->num_rows) {
		if($_POST['vip'] != $_SESSION['vip_key']) {
			$json->messages[]	= t('actions.a125');
		}

		if(Recordset::query("SELECT * FROM player_expulso WHERE id_player=" . $basePlayer->id . " AND id_objeto=" . $_POST['equipe'] . " AND tipo='equipe'")->num_rows) {
			$json->messages[]	= t('actions.a126');
		}
		
		// Não posso ter players da minha conta na equipe --->
			$equipe_lider	 = Recordset::query('SELECT a.id_usuario FROM player a JOIN equipe b ON b.id_player=a.id WHERE b.id=' . $_POST['equipe'])->row_array();
			
			if($equipe_lider['id_usuario'] == $_SESSION['usuario']['id'] && !$_SESSION['universal']) {
				$json->messages[]	= t('actions.a126');
			}
		// <---
	
		if($equipe->row()->membros >= 4) {
			$json->messages[]	= t('actions.a127');
		}
		
		if(Recordset::query('SELECT id FROM equipe_pendencia WHERE id_player=' . $basePlayer->id . ' AND id_equipe=' . $_POST['equipe'])->num_rows) {
			$json->messages[]	= t('actions.a216');
		}
	} else {
		$json->messages[]	= t('actions.a127');
	}

	if(!sizeof($json->messages)) {
		$json->success	= true;

		Recordset::insert('equipe_pendencia', array(
			'id_player'	=> $basePlayer->id,
			'id_equipe'	=> $_POST['equipe']
		));	
		
		Recordset::insert('chat', array(
			'channel'	=> 'private',
			'from'		=> $basePlayer->id,
			'object_id'	=> $equipe->row()->id_player,
			'message'	=> t('actions.a129') .' '. $basePlayer->nome .' '. t('actions.a128'),
			'when'		=> microtime(true)
		));		
	}
	
	echo json_encode($json);