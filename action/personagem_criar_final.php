<?php
	$json			= new stdClass();
	$json->success	= false;
	$json->messages	= array();
	
	$class			= Recordset::query('SELECT * FROM classe WHERE id=' . (int)$_POST['classe']);
	
	$_POST['nome']	= preg_replace('/[^\w]/is', '', $_POST['nome']);

	$exists = Recordset::query("SELECT id_player FROM player_nome WHERE nome = '" . addslashes($_POST['nome']) . "'" );
	
	if($exists->num_rows) {
		$json->messages[]	= t('criacao.action.existente');
	}
	
	if(strlen($_POST['nome']) > 14 || strlen($_POST['nome']) < 3) {
		$json->messages[]	= t('criacao.action.nome_longo');
	}

	if(!preg_match("/[\w]+/si", $_POST['nome'])) {
		$json->messages[]	= t('criacao.action.nome_invalido');
	}

	if(!is_numeric($_POST['vila']) || !Recordset::query('SELECT id FROM vila WHERE inicial="1" AND id=' . (int)$_POST['vila'])->num_rows) {
		$json->messages[]	= t('criacao.action.vila_invalido');
	}

	if(!is_numeric($_POST['classe']) || !$class->num_rows) {
		$json->messages[]	= t('criacao.action.classe_invalido');
	}

	if(!on($_POST['classe_tipo'], '1,2,3,4')) {
		$json->messages[]	= t('criacao.action.classe_tipo_invalido');
	}
	
	$class	= $class->row_array();

	// Verifica se não é inicial e se o cara tem ele liberado.
	if(!$class['inicial']) {
		if(!Player::classe_liberada($class['id'])){
			$json->messages[]	= t('criacao.action.classe_nao_liberada');
		}
	}
	
	if($class['especial']) {
		$player_liberado = Recordset::query("SELECT * FROM coin_log WHERE id_usuario=" . $_SESSION['usuario']['id'] . " AND id_item=1866 AND extra=" . $_POST['classe']);
		
		if(!$player_liberado->num_rows) {
			$coin = Recordset::query("SELECT coin FROM global.user WHERE id=" . $_SESSION['usuario']['id'])->row()->coin;
			
			if($coin < 5) {
				$json->messages[]	= t('criacao.action.sem_credito');
			} else {
				Recordset::insert('coin_log', array(
					'id_usuario'	=> $_SESSION['usuario']['id'],
					'id_item'		=> 1866,
					'coin'			=> 5,
					'extra'			=> $_POST['classe']
				));
				
				Recordset::update('global.user', array(
					'coin'	=> array('escape' => false, 'value' => 'coin - 5')
				), array(
					'id'	=> $_SESSION['usuario']['id']
				));
			}
		}
	} else {
		$vila_item	= Recordset::query('
			SELECT
				a.id
			FROM
				player a JOIN vila_item b ON b.vila_id=a.id_vila
				JOIN item c ON c.id=b.item_id
			
			WHERE
				c.id=21875 AND
				a.id_usuario=' . $_SESSION['usuario']['id'])->num_rows ? 1 : 0;		
		$chars		= Recordset::query("SELECT COUNT(id) AS total FROM player WHERE removido=0 AND id_usuario=" . $_SESSION['usuario']['id'])->row()->total;
		$char_limit	= ($_SESSION['usuario']['vip'] ? 5 : 3) + get_user_field('vip_char_slots') + $vila_item;
		
		if($chars >= $char_limit) {
			$json->messages[]	= sprintf(t('criacao.action.limite_personagem'), $char_limit);
		}												  
	}	
	
	if(!sizeof($json->messages)) {
		$json->success	= msg(1, t('criacao.action.sucesso_titulo'), t('criacao.action.successo_msg'), true);
		
		$id	= Recordset::insert('player', array(
			'id_usuario'		=> $_SESSION['usuario']['id'],
			'id_classe'			=> $_POST['classe'],
			'id_classe_tipo'	=> $_POST['classe_tipo'],
			'nome'				=> $_POST['nome'],
			'ryou'				=> 500,
			'id_vila'			=> $_POST['vila'],
			'id_vila_atual'		=> $_POST['vila']
		));		
		Recordset::insert('player_nome', array(
			'id_player'	=> $id,
			'nome'		=> $_POST['nome']
		));
		Recordset::insert('player_tutorial', array(
			'id_player'	=> $id
		));

		for($f = 1; $f <=8; $f++) {
			Recordset::insert('player_reputacao', array(
				'id_player'		=> $id,
				'id_reputacao'	=> 5,
				'id_vila'		=> $f,
				'reputacao'		=> 0
			));
		}
	}
	
	echo json_encode($json);
