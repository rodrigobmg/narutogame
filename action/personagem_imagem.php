<?php
	if($_POST) {
		
		$id = decode($_POST['id']);
		if(!is_numeric($id)) {
			redirect_to("negado");
		}
		
	
		if($_SESSION['personagem_imagem_key'] != $_POST['personagem_imagem_key'] || !$_SESSION['personagem_imagem_key']) {
			die('jalert("Chave inválida", null, function () {location.reload()})');
		}
		
		$imagem	= Recordset::query('SELECT ci.*, c.nome FROM classe_imagem as ci JOIN classe as c on c.id = ci.id_classe WHERE ci.id=' . $id)->row_array();
		
		if($basePlayer->id_classe != $imagem['id_classe'] ) {
			//die('jalert("Chave inválida", null, function () {location.reload()})');
		}
		
		if($imagem['id']==3075){
			$coin	= 0;
			
		}else{
			$coin	= $imagem['ultimate'] ? 10 : 5;
		}
		
		
		if($imagem['tema']) {
			if(!Recordset::query('SELECT * FROM player_imagem_tema WHERE id_usuario=' . $basePlayer->id_usuario . ' AND id_imagem=' . $id)->num_rows) {
				if($basePlayer->coin < $coin) {
					die('jalert("Você não possui créditos suficientes", null, function () {location.reload()})');			
				}

				gasta_coin($coin, 21889);
				
				Recordset::insert('player_imagem_tema', array(
					'id_usuario'	=> $basePlayer->id_usuario,
					'id_imagem'		=> $id
				));
				
				Recordset::insert('player_titulo', array(
					'id_player' 	=> $basePlayer->id,
					'id_usuario'	=> $basePlayer->id_usuario,
					'titulo_br'		=> $imagem['titulo_br'],
					'titulo_en'		=> $imagem['titulo_en']
				));
				
				if($imagem['ultimate']) {
					global_message2('msg_global.tema_vip_ultimate', array($basePlayer->nome, $imagem['nome']));
				} else {
					global_message2('msg_global.tema_vip', array($basePlayer->nome, $imagem['nome']));					
				}

				$instance			= new stdClass();
				$instance->ultimate	= $imagem['ultimate'];
				$instance->id		= $imagem['id'];

				arch_parse(NG_ARCH_TEMA_VIP, NULL, NULL, $instance);
				
				// Missões diárias de Compra de Temas
				if($basePlayer->hasMissaoDiariaPlayer(10)->total){
					// Adiciona os contadores nas missões de tempo.
					Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
								 WHERE id_player = ". $basePlayer->id." 
								 AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 10) 
								 AND completo = 0 ");
				}
				
			}
			
			// Verifica se o player atual tem o tútulo
				if(!Recordset::query('SELECT id FROM player_titulo WHERE id_usuario=' . $basePlayer->id_usuario . ' AND titulo_br="' . $imagem['titulo_br'] . '"')->num_rows) {
					Recordset::insert('player_titulo', array(
						'id_player' => $basePlayer->id,
						'titulo_br'	=> $imagem['titulo_br'],
						'titulo_en'	=> $imagem['titulo_en']
					));					
				}
			if($basePlayer->id_classe == $imagem['id_classe'] ) {
				Recordset::update('player', array(
					'id_titulo'	=> Recordset::query('SELECT id FROM player_titulo WHERE id_usuario=' . $basePlayer->id_usuario . ' AND titulo_br="' . $imagem['titulo_br'] . '"')->row()->id
				), array(
					'id'		=> $basePlayer->id
				));
			}
		}
		
		if($basePlayer->id_classe == $imagem['id_classe'] ) {
			if(!Recordset::query("SELECT id FROM player_imagem WHERE id_player=" . $basePlayer->id)->num_rows) {
				Recordset::insert('player_imagem', array(
					'id_player'			=> $basePlayer->id,
					'id_classe_imagem'	=> $id
				));
			} else {
				Recordset::update('player_imagem', array(
					'id_classe_imagem'	=> $id
				), array(
					'id_player'			=> $basePlayer->id
				));
			}
		}
		die();
	}
	
	$q = Recordset::query("SELECT id, imagem, tema, id_classe, ultimate FROM classe_imagem WHERE ativo='sim' AND id_classe=" . $basePlayer->id_classe . ' ORDER BY ordem');
?>
<?php foreach($q->result_array() as $r): ?>
	<?php if($r['tema']): ?>
		<?php
			//if(!$_SESSION['universal']) continue;
			
			$comprado	= Recordset::query('SELECT * FROM player_imagem_tema WHERE id_usuario=' . $basePlayer->id_usuario . ' AND id_imagem=' . $r['id'])->num_rows;
		?>
		<div class="selecao-imagem">
			<?php if($r['ultimate']): ?>
				<?php
					/*
					if(!$_SESSION['universal']) {
						continue;
					}
					*/
				
					$file	= ROOT . "/images/layout".LAYOUT_TEMPLATE."/profile/" . $basePlayer->id_classe . "/" . $r['imagem'] . ".swf";
					$mt		= filemtime($file);
				?>
				<a style="display: block; <?php echo LAYOUT_TEMPLATE=="_azul" ? "height: 238px; width: 195px" : "height: 241px; width: 226px"?>">
					<embed  <?php echo LAYOUT_TEMPLATE=="_azul" ? 'height="238" width="195"' : 'height="241" width="226"'?> src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE ?>/profile/<?php echo $basePlayer->id_classe ?>/<?php echo $r['imagem'] ?>.swf?_cache=<?php echo $mt ?>" style="float: left; margin: 2px" quality="high" wmode="transparent" allowscriptaccess="always" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash">
					<div align="center">
						<?php if($comprado): ?>
							<input type="button" class="button" value="Ativar" onclick="doPersonagemImagemDo('<?php echo encode($r['id']) ?>', '<?php echo $r['id_classe'].'-'.$r['imagem']?>', <?php echo $comprado ?>)" />
						<?php else: ?>
							<input type="button" class="button" value="Comprar" onclick="doPersonagemImagemDo('<?php echo encode($r['id']) ?>', '<?php echo $r['id_classe'].'-'.$r['imagem']?>', <?php echo $comprado ?>)" />
						<?php endif ?>
					</div>
				</a>
			<?php else: ?>
				<a href="javascript:doPersonagemImagemDo('<?php echo encode($r['id']) ?>', '<?php echo $r['id_classe'].'-'.$r['imagem']?>', <?php echo $comprado ?>)">
					<img border="0" style="float: left; margin: 2px" src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/profile/<?php echo $basePlayer->id_classe ?>/<?php echo $r['imagem'] ?><?php echo LAYOUT_TEMPLATE=="_azul" ? ".jpg":".png"?>" />
				</a>
			<?php endif ?>
		</div>
	<?php else: ?>
		<div class="selecao-imagem">
			<a href="javascript:doPersonagemImagemDo('<?php echo encode($r['id']) ?>')">
				<img border="0" style="float: left; margin: 2px" src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/profile/<?php echo $basePlayer->id_classe ?>/<?php echo $r['imagem'] ?><?php echo LAYOUT_TEMPLATE=="_azul" ? ".jpg":".png"?>" />
			</a>
		</div>
	<?php endif ?>
<?php endforeach; ?>
<style>
	.selecao-imagem {
		float: left;
		position: relative;
		display: block;
		height: 280px;
	}
	
	.selecao-imagem .aviso-vip {
		text-align: center;
	}
</style>