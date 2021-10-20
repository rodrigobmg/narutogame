<?php
	$attributes	= array('req_graduacao', 'bonus_hp', 'bonus_sp', 'bonus_sta', 'atk_fisico', 'atk_magico', 'def_magico','def_fisico', 'tai','ken', 'nin', 'gen', 'agi', 'con', 'ene', 'inte', 'forc', 'res', 'def_base', 'prec_fisico', 'prec_magico', 'esq_min', 'esq_max','esq_total','crit_min', 'crit_max', 'crit_total','esq', 'det', 'conv', 'conc','esquiva');

	if(isset($_POST['system_sell'])) {
		$json			= new stdClass();
		$json->success	= false;
		$json->messages	= array();
		
		$item_to_sell	= Recordset::query('
			SELECT
				* 
			FROM
				player_item
			WHERE
				id_player=' . $basePlayer->id . ' AND 
				id=' . (int)$_POST['uid']
		);
		
		if(!$item_to_sell->num_rows) {
			$json->messages[]	= "Item não encontrado!";
		}

		if(!sizeof($json->messages)) {
			$json->success	= true;
			//$price			= Recordset::query('SELECT preco FROM item WHERE id=' . $item_to_sell->row()->id_item, true)->row()->preco / 2;
			
			$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') + 200);
			
			Recordset::delete('player_item', array(
				'id'			=> (int)$_POST['uid']
			));
			Recordset::delete('player_item_atributos', array(
				'id_player_item'			=> (int)$_POST['uid']
			));

			// Missões diárias de Venda de Equipamentos
			if($basePlayer->hasMissaoDiariaPlayer(6)->total){
				
				// Adiciona os contadores nas missões de tempo.
				Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
							 WHERE id_player = ". $basePlayer->id." 
							 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 6) 
							 AND completo = 0 ");
			}
		}
		
		die(json_encode($json));
	}

	$armors			= Recordset::query('SELECT * FROM item_tipo WHERE equipamento=1', true);
	$allowed_types	= array();
	$redir_script	= true;
	
	foreach($armors->result_array() as $armor) {
		$allowed_types[]	= $armor['id'];
	}
	
	if(!on(@$_POST['type'], $allowed_types)) {
		redirect_to('negado', NULL, array('e' => 1));
	}
	
	if(isset($_POST['equip']) && is_numeric($_POST['equip'])) {
		$item_to_equip	= Recordset::query('SELECT * FROM player_item_atributos WHERE id_player_item=(SELECT id FROM player_item WHERE id=' . $_POST['equip'] . ')', true)->row_array();
		
		if(isset($item_to_equip['req_level']) && $item_to_equip['req_level'] && $item_to_equip['req_level'] > $basePlayer->level) {
			die();	
		}

		if(isset($item_to_equip['req_graduacao']) && $item_to_equip['req_graduacao'] && $item_to_equip['req_graduacao'] > $basePlayer->id_graduacao) {
			die();	
		}
		
		Recordset::update('player_item', array(
			'equipado'	=> 0
		), array(
			'id_player'		=> $basePlayer->id,
			'id_item_tipo'	=> $_POST['type']
		));
		
		Recordset::update('player_item', array(
			'equipado'	=> 1
		), array(
			'id_player'	=> $basePlayer->id,
			'id'		=> $_POST['equip']
		));
		
		die();
	}
	
	$items	= Recordset::query('SELECT * FROM player_item WHERE id_item_tipo IN(' . $_POST['type'] . ') AND id_player=' . $basePlayer->id . ' AND equipado=\'0\'');
	$colors	= array(
		'comum'		=> '#b4b4b4',
		'raro'		=> '#265ec1',
		'epico'		=> '#df42b6',
		'lendario'	=> '#d77810',
		'set'		=> '#489C33'
	);
?>
<?php foreach($items->result_array() as $item): ?>
	<?php
		// Mostra se um item é novo para o jogador
		$now_time	= new DateTime();
		$drop_time	= new DateTime(date('Y-m-d H:i:s',strtotime($item['data_ins'])));
		$diff		= $drop_time->diff($now_time);

		if($item['id_item_tipo']!=2){
			
			$ats	= Recordset::query('SELECT nome,imagem, req_graduacao, ' . join(',', $attributes) . ' FROM player_item_atributos WHERE id_player_item=' . $item['id'], true)->row_array();
			if($item['id_item_tipo']==10){
				$bg 	= img('layout/equipamentos/' . $item['id_item_tipo'] . '/' . $item['raridade'][0].'-'.$basePlayer->id_vila.'-'. $ats['imagem'] . '.png');
			}else{
				$bg 	= img('layout/equipamentos/' . $item['id_item_tipo'] . '/' . $item['raridade'][0].'-'. $ats['imagem'] . '.png');
			}
			
			
		}else{
			$ats	= Recordset::query('SELECT req_level, req_graduacao, id,raridade, nome_' . Locale::get() . ' AS nome, ordem, preco, imagem,nome_' . Locale::get() . ' AS nome, raridade, ' . join(',', $attributes) . ', id_tipo FROM item WHERE id=' . $item['id_item'], true)->row_array();
			$bg		= img("layout/equipamentos/2/".$ats['id'].".png");
		}
		
		/*if($ats['id_tipo']==2){
			$bg = img("layout/equipamentos/2/".$ats['id'].".png");
		}else{
			$bg		= str_replace('%vila', $basePlayer->id_vila, img($ats['imagem']));
		}*/
		
		$price	= 200;
		$reqs	= 0 . "|" . $ats['req_graduacao'];
	?>
	<div class="armor-item-ns" data-reqs="<?php echo $reqs ?>" data-sell="<?php echo $price ?>" data-id="<?php echo $item['id'] ?>" data-type="<?php echo $item['id_item_tipo'] ?>" data-name="<?php echo $ats['nome'] ?>" data-id="<?php echo $item['id'] ?>" data-color="<?php echo $colors[$item['raridade']] ?>">
		<?php if($diff->h <= 1){?>
			<div class="armor-novo"><b class="blink_me">Novo</b></div>
		<?php } ?>
		<img src="<?php echo $bg ?>" data-id="<?php echo $item['id'] ?>" data-initial="0" />
	</div>
	<script>
		if(!_equips['<?php echo $item['id'] ?>']) {
			_equips['<?php echo $item['id'] ?>']	= {
				id:				'<?php echo $item['id_item'] ?>',
				name:			'<?php echo $ats['nome'] ?>',
				description:	'<?php echo $ats['nome'] ?>',
				raridade:		'<?php echo $item['raridade'] ?>',
				at: {
					<?php foreach($attributes as $v): ?>
					<?php echo $v ?>: <?php echo $ats[$v] ?>,
					<?php endforeach ?>
					nil:	 null
				}
			}
		}
	</script>
<?php endforeach ?>