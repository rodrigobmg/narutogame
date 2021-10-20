<?php
	require('promocoes/baseintencoder.php');

	$json			= new stdClass();
	$json->messages	= array();
	$json->success	= false;

	if(!isset($_POST['promocao']) || (isset($_POST['promocao']) && !is_numeric($_POST['promocao']))) {
		$json->messages[]	= "Promoção inválida";	
	}

	if(!isset($_POST['code'])) {
		$json->messages[]	= "Por favor insira um código promocional";
	} else {
		if(BaseIntEncoder::decode($_POST['code']) - 1000000 != $_SESSION['usuario']['id']) {
			$json->messages[]	= 'Código inválido';
		}		
	}

	if(!sizeof($json->messages)) {
		$promocao_ativa	= Recordset::query('
			SELECT
				a.*,
				b.nome_br,
				b.nome_en,
				b.premio_coin,
				b.premio_ryou
				
			FROM
				promocao_usuario a JOIN promocao b ON b.id=a.promocao_id
			
			WHERE
				b.ativo=1
				AND a.promocao_id=' . (int)$_POST['promocao'] . '
				AND a.utilizado=0
				AND a.usuario_id=' . $_SESSION['usuario']['id']);
		
		if(!$promocao_ativa->num_rows) {
			$json->messages[]	= 'Promoção inválida ou expirada';	
		}		
	}
	
	if(!sizeof($json->messages)) {
		$promocao_ativa	= $promocao_ativa->row_array();	
		$json->success	= true;
		
		if($promocao_ativa['premio_coin']) {
			Recordset::update('global.user', array(
				'coin'	=> array('escape' => false, 'value' => 'coin + ' . $promocao_ativa['premio_coin'])
			), array(
				'id'	=> $_SESSION['usuario']['id']
			));
			
			Recordset::update('promocao_usuario', array(
				'utilizado'		=> 1,
				'utilizado_em'	=> array('escape' => false, 'value' => 'NOW()')
			), array(
				'id'	=> $promocao_ativa['id']
			));
		}
		
		if($promocao_ativa['premio_ryou']) {
			Recordset::update('player', array(
				'ryou'	=> array('escape' => false, 'value' => 'ryou + ' . $promocao_ativa['premio_ryou'])
			), array(
				'id'	=> $basePlayer->id
			));
		}
	}
	
	echo json_encode($json);
