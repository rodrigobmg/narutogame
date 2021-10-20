<?php
	$redir_script	= true;
	$expulsar_mul	= Player::getFlag('equipe_expulsar_mul', $basePlayer->id);
	$sair_mul		= Player::getFlag('equipe_sair_mul', $basePlayer->id);
	$del_mul		= Player::getFlag('equipe_del_mul', $basePlayer->id);
	$is_other		= false;
	
	
	if(isset($_GET['id']) && is_numeric($_GET['id'])) {
		redirect_to("negado");
	}else if(isset($_GET['id']) && !is_numeric($_GET['id'])) {
		$id 		= decode($_GET['id']);
		$is_other	= true;
		
		if(!is_numeric($id)) {
			redirect_to("negado");			
		}
	} else {
		if($basePlayer->getAttribute('id_equipe')){
			$id = $basePlayer->getAttribute('id_equipe');
		}else{
			redirect_to("negado");		
		}	
	}
	
	$equipe = Recordset::query('
		SELECT
			a.*,
			b.pontos,
			b.posicao_geral
		FROM 
			equipe a LEFT JOIN ranking_equipe b ON b.id_player=a.id_player 
		
		WHERE 
			a.removido=0 AND 
			a.id=' . (int)$id)->row_array();

	$team_mine	= Recordset::query('SELECT a.*, b.id_vila, ((SELECT SUM(level) FROM player aa WHERE aa.id_equipe=a.id) / 4) AS media_level FROM equipe a JOIN player b ON b.id=a.id_player WHERE a.id=' . $basePlayer->id_equipe)->row_array();	
	
	$uploadError	= '';
	$hasError		= '';
	
	if(isset($_GET['option']) && $_GET['option']) {
		$_GET['option'] = decode($_GET['option']);
		
		if(!is_numeric($_GET['option'])) {
			redirect_to("negado");
		}

		switch($_GET['option']) {
			case 1: // aceitar/recusar integrantes
				if($basePlayer->missao_equipe) {
					$hasError	= true;
					$errorMsg	= t('equipe_detalhe.e1');
				} else {
					if(isset($_POST['rplayer']) && is_array($_POST['rplayer'])) {
						foreach($_POST['rplayer'] as $v) {
							if(!is_numeric($v)) {
								redirect_to("negado", NULL, array('e' => 4));
							}
							
							Recordset::delete('equipe_pendencia', array(
								'id_player'	=> $v,
								'id_equipe'	=> $basePlayer->getAttribute('id_equipe')
							));
							
							mensageiro(NULL, $v, t('equipe_detalhe.e2'), t('equipe_detalhe.e3'). $basePlayer->getAttribute('nome_equipe') . t('equipe_detalhe.e4'), 'team');
						}
					}
	
					if(isset($_POST['aplayer']) && is_array($_POST['aplayer'])) {
						foreach($_POST['aplayer'] as $v) {
							if(!is_numeric($v)) {
								redirect_to("negado", NULL, array('e' => 5));
							}
							
							if($equipe['membros'] >= 4) {
								$hasError = true;
								$errorMsg = t('equipe_detalhe.e5');
								
								continue;
							}
							
							$equipe['membros']++;
							
							// Se for aceito, remove todas as pendencias
							Recordset::delete('equipe_pendencia', array(
								'id_player'	=> $v,
							));

							if(Recordset::query('SELECT id FROM player WHERE id=' . $v . ' AND id_equipe !=0')->num_rows) {
								continue;
							}

							Recordset::update('player', array(
								'id_equipe'	=> $basePlayer->getAttribute('id_equipe')
							), array(
								'id'		=> $v
							));

							mensageiro(NULL, $v, t('equipe_detalhe.e6'), t('equipe_detalhe.e7') .': ' . $basePlayer->getAttribute('nome_equipe'), 'team');

							// Atualiza o contador
							Recordset::update('equipe', array(
								'membros'	=> array('escape' => false, 'value' => 'membros+1')
							), array(
								'id'		=> $basePlayer->getAttribute('id_equipe')
							));

							Recordset::insert('chat', array(
								'channel'	=> 'private',
								'from'		=> $basePlayer->id,
								'object_id'	=> $v,
								'message'	=> sprintf(t('equipe_detalhe.aceito'), $basePlayer->nome_equipe),
								'when'		=> microtime(true)
							));							
						}
					}
				}
				
				break;
			

			case 2: // Postar imagem
				$mime = array(
					"image/jpeg",
					"image/png",
					"image/gif"
				);
				
				$file = $_FILES['equipe_foto'];
				
				if(!$file['error'] && $file['tmp_name']) {
					if(!in_array(image_type_to_mime_type(exif_imagetype($file['tmp_name'])), $mime)) {
						$uploadError = true;
					}
					
					if(!in_array( strtolower(substr($file['name'], -3, 3)), array('jpg', 'png', 'gif') )) {
						$uploadError = true;
					}
				} else {
					$uploadError = true;
				}
				
				if(!$uploadError) {
					$nome = md5($file['name'] . rand(1, 999999999)) . substr($file['name'], stripos($file['name'], "."));
					$path = realpath(dirname(__FILE__) . "/../") . "/images/equipe/" . $nome;
					
					$sz = getimagesize($file['tmp_name']);
					
					if($sz['0'] > 663 || $sz['1'] > 166) {
						$uploadError = true;
					} else {
						move_uploaded_file($file['tmp_name'], $path);

						Recordset::update('equipe', array(
							'imagem'	=> $nome
						), array(
							'id'		=> $basePlayer->getAttribute('id_equipe')
						));
						
						redirect_to("equipe_detalhe");
					}
				}
				
				break;
			
			case 3: // Sair/Expulsar
				$player			= decode($_POST['player']);				
				$rPlayerEquipe	= Recordset::query("SELECT id, id_batalha_multi, id_batalha_multi_pvp, id_missao, id_evento FROM player WHERE id=" . (int)$player . " AND id_equipe=" . $basePlayer->getAttribute('id_equipe'))->row_array();

				// Tentariva de injeção de id para remoção do líder
				if($rPlayerEquipe['id_evento']) {
					$hasError = true;
					$errorMsg = t('equipe_detalhe.e8');
				} else {
					if($equipe['id_player'] == $rPlayerEquipe['id']) {
						$is_quest_equipe = false;
						
						if($basePlayer->getAttribute('missao_equipe')) {
							$hasError = true;
							$errorMsg = t('equipe_detalhe.e9');
						} else {
							// Verifica se tem algum membro ainda em missao de equipe
							$players 				= Recordset::query('SELECT id, nome, id_missao FROM player WHERE id_equipe=' . $basePlayer->getAttribute('id_equipe'));
							$player_missao_equipe	= array();
							
							foreach($players->result_array() as $p) {
								if($p['id_missao']) {
									if(Recordset::query('SELECT equipe FROM quest WHERE id=' . $p['id_missao'])->row()->equipe) {
										$player_missao_equipe[] = $p['nome'];
									}
								}
							}
							
							if(sizeof($player_missao_equipe)) {
								$hasError = true;
								$errorMsg = t('equipe_detalhe.e10') .": " . join(',', $player_missao_equipe) . " ) ". t('equipe_detalhe.e11');
							} else {
								if($basePlayer->getAttribute('coin') < 2 * $del_mul) {
									redirect_to("vantagens");
								}
								
								foreach($players->result_array() as $p) {
									Recordset::update('player', array(
										'id_equipe'	=> 0
									), array(
										'id'		=> $p['id']
									));
									
									if($p['id'] != $basePlayer->id) {
										mensageiro(NULL, $p['id'], t('equipe_detalhe.e13'), t('equipe_detalhe.e12'). addslashes($basePlayer->getAttribute('nome_equipe')) . t('equipe_detalhe.e14'), 'team');
									}
								}
								
								Recordset::update('equipe', array(
									'removido'	=> '1'
								), array(
									'id'		=> $basePlayer->getAttribute('id_equipe')
								));
								
								// Atualizar o custo
								$basePlayer->setFlag('equipe_del_mul', $del_mul + 1);
								
								gasta_coin(2 * $del_mul,21886);
								redirect_to("personagem_status");
							}
						}			
					} else {
						if($rPlayerEquipe['id_batalha_multi'] || $rPlayerEquipe['id_batalha_multi_pvp']) {
							$hasError = true;
							$errorMsg = t('equipe_detalhe.e15');						
						} elseif($rPlayerEquipe['id']) { // Evita problemas com aba
							$is_quest_equipe = false;
							
							
							if($rPlayerEquipe['id_missao'] > 0) {
								$is_quest_equipe = Recordset::query('SELECT equipe FROM quest WHERE id=' . $rPlayerEquipe['id_missao'])->row()->equipe;
							}
							
							if($is_quest_equipe) {
								$hasError = true;
								$errorMsg = t('equipe_detalhe.e16');	
							} else {
								if(!$basePlayer->getAttribute('dono_equipe')) {
									if($basePlayer->getAttribute('ryou') < 500 * $sair_mul) {
										redirect_to("equipe_detalhe", NULL, array("e" => 1));
									}
	
									// Atualiza o custo
									$basePlayer->setFlag('equipe_sair_mul', $sair_mul + 1);	
									$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') - (500 * $sair_mul));

									Recordset::insert('player_expulso', array(
										'id_player' => $basePlayer->id,
										'id_objeto' => $basePlayer->id_equipe,
										'tipo'		=> 'equipe'
									));
								} else {
									//if($basePlayer->getAttribute('ryou') < 500 * $expulsar_mul) {
									if($basePlayer->getAttribute('ryou') < 500) {
										redirect_to("equipe_detalhe", NULL, array("e" => 1));
									}
									
									// Atualiza o custo
									$basePlayer->setFlag('equipe_expulsar_mul', $expulsar_mul + 1);
									//$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') - (500 * $expulsar_mul));
									$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') - (500));

									Recordset::insert('player_expulso', array(
										'id_player' => $player,
										'id_objeto' => $basePlayer->id_equipe,
										'tipo'		=> 'equipe'
									));
									
									mensageiro(NULL, $player, t('equipe_detalhe.e17'), t('equipe_detalhe.e18') . addslashes($basePlayer->getAttribute('nome_equipe')) . t('equipe_detalhe.e19') . addslashes($basePlayer->getAttribute('nome')), 'team');
								}
	
								Recordset::update('player', array(
									'id_equipe'	=> 0
								), array(
									'id'		=> $player
								));
								
								Recordset::update('equipe', array(
									'membros'	=> array('escape' => false, 'value' => 'membros-1')
								), array(
									'id'		=> $basePlayer->getAttribute('id_equipe')
								));
								
								if(!$basePlayer->getAttribute('dono_equipe')) {
									redirect_to("personagem_status");							
								} else {
									redirect_to("equipe_detalhe");							
								}
							}
						}					
					}
				}

				break;			
			
			case 4: // mensagem
				$equipe = new Recordset('SELECT * FROM equipe WHERE id=' . $basePlayer->getAttribute('id_equipe'));				
				$players = new Recordset('SELECT id FROM player WHERE id_equipe=' . $basePlayer->getAttribute('id_equipe'));
				
				if(!trim($_POST['message']) || !trim($_POST['title'])) {
					$hasError	= true;
					$errorMsg	= t('actions.a222');
				}

				if(trim($_POST['message']) && trim($_POST['title'])) {
					foreach($players->result_array() as $player) {
						if($player['id'] == $basePlayer->id) {
							continue;
						}
						
						mensageiro($basePlayer->id, $player['id'], htmlspecialchars($_POST['title']), htmlspecialchars($_POST['message']), 'team');
					}
					
					redirect_to("equipe_detalhe");
				}

				break;
			case 5:
				$removed =  isset($basePlayer->playerRemoved($equipe['id_player'])->removido) || isset($basePlayer->playerRemoved($equipe['id_player'])->banido) ?  1 : 0;
				if(!$removed && strtotime($basePlayer->getPlayerRemoved($equipe['id_player'])->ult_atividade. "+7 days") > now() && $basePlayer->getAttribute('id_equipe') != $equipe['id']){
					$hasError	= true;
					$errorMsg	= "Você não pode fazer essa ação!";
				}else{
					if($basePlayer->getAttribute('coin') < 2) {
						redirect_to("vantagens");
					}
					
					Recordset::query("UPDATE equipe SET id_player=". $basePlayer->id .", membros = membros-1 WHERE id =".$basePlayer->getAttribute('id_equipe'));
				
					Recordset::update('player', array(
						'id_equipe'	=> 0
					), array(
						'id'		=> $equipe['id_player']
					));		
								
					gasta_coin(2,1494206);
				}
				
			break;
			case 6:
				$equipe = Recordset::query('SELECT * FROM equipe WHERE id=' . $basePlayer->getAttribute('id_equipe'))->row_array();	
				$now 		= strtotime('now');
				$data_fim 	= $equipe['data_fim'] ? strtotime($equipe['data_fim']. " +7 days") : $now;
				
				if($now >= $data_fim){
					$player	= decode($_POST['player']);
					Recordset::update('equipe', array(
						'id_playerb'	=> $player,
						'data_fim'		=> now(true)
					), array(
						'id'			=> $basePlayer->getAttribute('id_equipe')
					));
				}else{
					$hasError	= true;
					$errorMsg	= "Você só poderá fazer essa ação em: ". date('d-m-Y H:i:s',strtotime($equipe['data_fim']. " +7 days"))."";
				}
		}
	}

	$equipe = new Recordset('
		SELECT 
			a.*, 
			b.pontos, 
			b.posicao_geral 
		FROM 
			equipe a LEFT JOIN ranking_equipe b ON b.id_player=a.id_player 
		
		WHERE 
			a.removido=0 AND 
			a.id=' . (int)$id);
	$equipe = $equipe->row_array();

	$quests = new Recordset('
		SELECT
			  id_player,
			  SUM(CASE WHEN id_rank=0 THEN 1 ELSE 0 END) AS lvl_tarefa,
			  SUM(CASE WHEN id_rank=1 THEN 1 ELSE 0 END) AS lvl_d,
			  SUM(CASE WHEN id_rank=2 THEN 1 ELSE 0 END) AS lvl_c,
			  SUM(CASE WHEN id_rank=3 THEN 1 ELSE 0 END) AS lvl_b,
			  SUM(CASE WHEN id_rank=4 THEN 1 ELSE 0 END) AS lvl_a,
			  SUM(CASE WHEN id_rank=5 THEN 1 ELSE 0 END) AS lvl_s
		
		FROM 
			quest a JOIN player_quest b ON b.id_quest = a.id  
		
		WHERE 
			b.id_player=(SELECT id_player FROM equipe WHERE id=' . (int)$id . ') AND
			b.id_equipe = ' . (int)$id . ' AND
			a.equipe=1 AND
			b.falha=0');
	$quests = $quests->row_array();
	
	$quest_falha = new Recordset('
		SELECT 
			COUNT(id) AS mx 
		FROM 
			player_quest 
		
		WHERE 
			falha=1 AND 
			id_equipe = '. $equipe['id'] .' AND
			id_player=' . $equipe['id_player'] . ' AND
			id_quest IN(SELECT id FROM quest WHERE equipe=1)');
	$quest_falha = $quest_falha->row_array();

	$js_function  = $_SESSION['el_js_func_name'] = "f" . md5(rand(1, 512384));
	$js_functionb = $_SESSION['el_js_func_nameb'] = "f" . md5(rand(1, 512384));
	$js_functionc  = $_SESSION['el_js_func_namec'] = "f" . md5(rand(1, 512384));

	$q_equipe_level_atual = Recordset::query("SELECT * FROM equipe_level WHERE id=" . $equipe['level'], true);

	if ($q_equipe_level_atual->num_rows) {
		$equipe_level_atual = $q_equipe_level_atual->row_array();
	} else {
		$equipe_level_atual = [
			'exp' => 0,
		];
	}
	
	
	if($is_other) {
		$other_player	= new Player($equipe['id_player']);	
	}
	$membros = Recordset::query("
		SELECT
			b.id AS id_player,
			b.id_classe AS classe,
			b.level AS level_player,
			b.nome AS nome_player,
			b.equipe_nivel_min
		
		FROM
			equipe a JOIN player b ON a.id=b.id_equipe
		WHERE
			a.id=" . $id);
	
	foreach($membros->result_array() as $membro) {
			$min_levels[]	= array(
				'level'	=> $membro['equipe_nivel_min'],
				'name'	=> $membro['nome_player'],
				'id'	=> $membro['id_player']
			);
	}
?>
<?php if($basePlayer->id_equipe == $equipe['id']): ?>
<style>
	.team-message {
		width: 300px;
		display: none
	}

	.team-message p {
		text-align: left	
	}
	
	.team-message input, .team-message textarea {
		width: 270px;	
	}
</style>
<script type="text/javascript">
	function <?php echo $js_functionc ?>(p) {
		if(p) {
			if(!confirm("Você tem certeza que esse será seu novo Sub-Líder? A mudança só poderá ser feita uma vez por semana!")) {
				return false;
			}			
		} else {
			return false;
		}

		$("#fEquipeESP").val(p);
		$("#fEquipeES").attr("action", $("#fEquipeES").attr("action") + "<?php echo encode(6) ?>").submit();
	}
	function <?php echo $js_function ?>(i, o, t) {
		if(t) {
			if(!confirm("<?php echo t('equipe_detalhe.e20')?> <?php echo 2 * $del_mul ?> <?php echo t('geral.creditos')?>.")) {
				return false;
			}			
		} else {
			if(o) {
				if(!confirm("<?php echo t('equipe_detalhe.e21')?> <?php echo 500 * $sair_mul ?> <?php echo t('equipe_detalhe.e23')?>")) {
					return false;
				}
			} else {
				//if(!confirm("Confirma expulsar esse player da sua equipe?\nSerão necessário <?php echo 500 * $expulsar_mul ?> ryous. Lembrando que esse jogador não poderá mais participar dessa equipe.")) {
				if(!confirm("<?php echo t('equipe_detalhe.e22')?> <?php echo 500 ?> <?php echo t('equipe_detalhe.e24')?>")) {
					return false;
				}
			}
		}

		$("#fEquipeESP").val(i);
		$("#fEquipeES").attr("action", $("#fEquipeES").attr("action") + "<?php echo encode(3) ?>").submit();
	}

	var __pergaminho_e = [];
	
	function expande_pergaminho(id) {
		if(!id) {
			id = '';
		}
	
		if(__pergaminho_e[id]) {
			$('#p-meio' + id).show('blind');
		} else {
			$('#p-meio' + id).hide('blind');
		}
		
		__pergaminho_e[id] = !__pergaminho_e[id];
	}
	
	function expande_div(t, id) {
		if(!parseInt($(t).attr('expandido'))) {
			$(t).attr('src', '<?php echo img('layout/menos.png') ?>');			
			$(t).attr('expandido', 1);
			
			$('#' + id).show('blind');
		} else {
			$(t).attr('src', '<?php echo img('layout/mais.png') ?>');
			$(t).attr('expandido', 0);

			$('#' + id).hide('blind');
		}
	}
	
	function doEquipeRole(v) {
		//$('#i-equipe-my-role').attr('src', v == 5 ? '<?php echo img('layout/equipe_roles/nenhum.png') ?>' : '<?php echo img('layout/equipe_roles/') ?>' + v + '.png');
		$('#i-equipe-my-role').attr('src', '<?php echo img('layout') ?>' + $('#s-my-role option[value=' + v + ']').data('image'));
		
		$.ajax({
			url: '?acao=equipe_detalhe',
			type: 'post',
			data: {role: v},
			success: function (e) {
				if(e == '1') {
					alert('<?php echo t('equipe_detalhe.e25')?>');
				} else {
					alert('<?php echo t('equipe_detalhe.e26')?>');
				}
			}
		});
	}

 	$(document).ready(function(e) {
		$('#b-team-message').on('click', function () {
			$('.team-message').dialog({
				title:		'<?php echo t('equipe.confirmar_mensagem_t') ?>',
				modal:		true,
				buttons:	{
					'Enviar':	function () {
						jconfirm('<?php echo t('equipe.confirmar_mensagem') ?>', null, function () {
							$('.team-message form').submit();
						});
					}
				}
			});
		});
	});
</script>
<form id="fEquipeES" method="post" action="?secao=equipe_detalhe&option=">
	<input type="hidden" name="player" id="fEquipeESP" value="" />
</form>
<?php endif; ?>
<style>
.character-info {
	background-image: url(<?php echo img('layout/bg_profile.png')?>);
	width: 226px;
	height:  40px;
	margin: 0px auto;
}
.character-info .headline {
	color: #937B4D;
	margin-top: 2px
}
.name {
	font-size: 14px;
	font-weight: bold;
}
</style>
<div class="titulo-secao"><p><?php echo t('equipe_detalhe.e27')?></p></div><br />
<?php msg(1,''.t('equipe_detalhe.e28').'',''.t('equipe_detalhe.e29').''); ?>
<div class="break"></div>
<br/><br/>
	<div id="HOTWordsTxt" name="HOTWordsTxt">
		<?php if($uploadError): ?>
			<div class="error"><?php echo t('equipe_detalhe.e30')?></div>
			<div class="break"></div>
			<br />
		<?php endif; ?>
		<?php if($hasError): ?>
			<div class="error"><?php echo $errorMsg ?></div>
			<div class="break"></div>
			<br />
		<?php endif; ?>
		<?php if(isset($_GET['e']) && $_GET['e']): ?>
			<?php msg(2,''.t('academia_jutsu.problema').'',''.t('equipe_detalhe.e31').''); ?>
			<div class="break"></div>
		<?php endif; ?>
		<div id="bg_foto_equipe" style="background:url(<?php echo img('layout/bg_guilds_foto.png') ?>); width:730px; height:205px; position:relative; left:-7px">
 		  <div id="foto_equipe" style="position:relative; top:14px; left: 9px">
                <img src="<?php echo $equipe['imagem'] ? img('equipe/' . $equipe['imagem']) : img('layout/foto_equipe.jpg') ?>" />
          </div>
        </div>
		<div id="level_equipe">
			<table width="730" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="10%" rowspan="2">
						<div>
							LEVEL<br />
							<span style="font-size: 50px; color: <?php echo LAYOUT_TEMPLATE=="_azul" ? "#0e3a57" : "#3f2b1c"?>; top: 8px; position: relative"><?php echo $equipe['level'] > 25 ? 25 : $equipe['level']?></span>	
						</div>
					</td>
					<td height="30" align="center" style="position: relative">
						<p style="width: 550px" id="guild-description"><?php //echo $equipe['descricao'] ? $equipe['descricao'] : 'Sem descrição' ?></p>
					</td>
					<td width="10%" rowspan="2" align="center">
							<div>
								MÁX. LEVEL<br />
								<span style="font-size: 50px; top: 8px; position: relative" class="laranja">25</span>	
							</div>
					</td>
				</tr>
				<tr>
					<td align="center">
						<?php barra_exp3($equipe['exp_level'], $equipe_level_atual['exp'], 580, $equipe['exp_level'] . " Exp ". t('vila.v8')." / " . $equipe_level_atual['exp'] ." Exp", "#840303", "#E90E0E", 3) ?><br />
						<?php if(!$is_other): ?>
							<a class="button" id="b-team-message" style="margin-bottom:4px;"><?php echo t('botoes.enviar_msg_equipe') ?></a>
						<?php endif ?>
						<?php if(!$basePlayer->getAttribute('missao_equipe')): ?>
							<?php if($basePlayer->getAttribute('id_equipe') == $equipe['id'] && $basePlayer->id != $equipe['id_player']): ?>
									<a style="margin-top:-3px" class="button" onclick="<?php echo $js_function ?>('<?php echo encode($basePlayer->id) ?>', true)" id="btn"><?php echo t('botoes.sair_equipe') ?></a>
							<?php elseif($basePlayer->getAttribute('id_equipe') == $equipe['id'] && $basePlayer->id == $equipe['id_player']): ?>
								<?php if ($equipe['membros'] == 1): ?>
									<a style="margin-top:-3px" class="button" onclick="<?php echo $js_function ?>('<?php echo encode($basePlayer->id) ?>', true, true)" id="btn"><?php echo t('botoes.excluir_equipe') ?></a>
								<?php endif; ?>
							<?php endif; ?>
							<?php else: ?>
								<?php if($basePlayer->getAttribute('id_equipe') == $equipe['id'] && $basePlayer->id != $equipe['id_player']): ?>
								<?php echo t('equipe_detalhe.e42')?>
								<?php endif; ?>
						<?php endif; ?>
						
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div style="position:relative; top: 8px;">
						<?php $equipe_level = new Recordset("SELECT * FROM equipe_level", true); ?>
							<?php foreach($equipe_level->result_array() as $level): ?>
								<?php ob_start(); ?>
								<b class='verde'><?php echo t('geral.g78')?></b><br /><br />
								<?php if($level['id'] < $equipe['level']): ?>
									<?php barra_exp3($level['exp'], $level['exp'], 220, $level['exp'] . " Exp " . t('equipe_detalhe.e35') ." ". $level['exp'] ." Exp", "#2C531D", "#537F3D",7) // Imagem ?>
								<?php elseif($level['id'] > $equipe['level']): ?>
									<?php barra_exp3(0, $level['exp'], 220, "0 Exp " . t('equipe_detalhe.e35') ." ". $level['exp'] ." Exp", "#2C531D", "#537F3D",6) ?>
								<?php else: ?>
									<?php barra_exp3($equipe['exp_level'], $level['exp'], 220, (int)$equipe['exp_level'] . " Exp " .t('equipe_detalhe.e35')." ". $level['exp'] ." Exp", "#2C531D", "#537F3D",6) ?>
								<?php endif; ?>
								
								<?php $sorte_ninja = new Recordset('SELECT nome_br, nome_en FROM loteria_premio WHERE id=' . $level['id_sorte_ninja']) ?>
									<br /><b class='verde'><?php echo t('geral.premios')?></b><br /><br />
									<p class="laranja"><?php echo t('equipe_detalhe.e50')?> <strong><?php echo $sorte_ninja->row()->{'nome_' . Locale::get()} ?></strong>.</p>
								<?php
									$has_min_level	= array();
									
									foreach($min_levels as $p) {
										if($p['level'] >= $level['id'] && $p['level'] >= $equipe['level']) {
											$has_min_level[]	= $p['name'];
										}
									}
								?>	
								<?php if(sizeof($has_min_level)): ?>
									<br /><p style="color: #774B9C"><?php echo t('geral.g79')?>: <?php echo join(',', $has_min_level) ?></p>
								<?php endif ?>
								<?php $premio = ob_get_clean();?>
								
								<div style="float: left; padding-left: 4px;">
								<?php if((int)$equipe['level'] > $level['id']):?>
									<img src="<?php echo img()?>layout/bg_guilds_star.png" id="premios-<?php echo $level['id']?>" style="cursor: pointer"/>
								<?php else:?>
									<img src="<?php echo img()?>layout/bg_guilds_star2.png" id="premios-<?php echo $level['id']?>" style="cursor: pointer"/>	
								<?php endif ?>
									<?php echo generic_tooltip('premios-'. $level['id'], $premio )?>
								</div>	
							<?php endforeach; ?>
						</div>	
					</td>
				</tr>
			</table>
			</div>
		<?php if($basePlayer->id == $equipe['id_player']){ ?>
		<br />
		<div style="width: 665px; clear:both; padding-top:5px; margin-left:auto;">
            <form action="?secao=equipe_detalhe&option=<?php echo encode(2) ?>" method="post" enctype="multipart/form-data">
				<div style="float: left;"><b style="color: #ab6820;"><?php echo t('equipe_detalhe.e32')?></b></div>
				<div style="float: left; width: 345px; margin-top: -9px">
				<a class="button" data-trigger-form="1"><?php echo t('botoes.alterar') ?></a><input type="file" name="equipe_foto" style="width: 210px;"/></div>
				<div style="float: left; width: 180px; margin-top: -7px;"><span style="color:#ab6820; font-size:10px"><?php echo t('equipe_detalhe.e33')?></span></div>
            </form>
        </div>
        <br />
        <div style="clear:both"></div>
        <br />
		<?php }?>
        <div id="player-atual" style="float:left; width: 226px">
			<?php if($is_other): ?>
				<?php echo player_imagem_ultimate($equipe['id_player']) ?><br />
			<?php else: ?>
				<?php echo player_imagem_ultimate($basePlayer->id) ?><br />
			<?php endif ?>
		</div>
        <div id="info_equipe" style="float:left; width:500px; height:115px; margin-bottom: 13px;">
		<div class="guilds_bg equipe">
                <div class="titulos-box-equipe"><b class="amarelo" style="font-size:13px"><?php echo t('equipe_detalhe.e34')?></b></div>
                <div class="conteudo-box-equipe">
                	<div style="padding-bottom: 3px;"><?php barra_exp3($equipe['level'], 25, 220, ($equipe['level']) ." ". t('equipe_detalhe.e35') . "  25 ". t('equipe_detalhe.e36'), "#2C531D", "#537F3D",3) ?></div>
                    <div style="padding-bottom: 3px;"><?php barra_exp3($equipe['exp_level_dia'], 19600, 220, t('equipe_detalhe.e37') .": ". $equipe['exp_level_dia'] . " / 19600 Exp", "#2C531D", "#537F3D",3) ?></div>
                    <div style="padding-bottom: 3px;"><?php barra_exp3($equipe['exp_level'], $equipe_level_atual['exp'], 220, t('equipe_detalhe.e38'). ": " . (int)$equipe['exp_level'] . " / " . $equipe_level_atual['exp'] ." Exp", "#2C531D", "#537F3D",3) ?></div>
            	</div>
        </div>
        <div class="guilds_bg pontos">
        	<div class="titulos-box-equipe"><b class="amarelo" style="font-size:13px"><?php echo t('equipe_detalhe.e39')?></b></div>
			<div class="conteudo-box-equipe">
            	<div class="pontuacao-equipe"><?php echo t('geral.score')?>: <b class="azul"><?php echo $equipe['pontos'] ?></b></div>
				<div class="pontuacao-equipe"><?php echo t('geral.ranking')?>: <b class="amarelo"><?php echo $equipe['posicao_geral'] ? $equipe['posicao_geral'] . '&deg;' : '-' ?></b></div>
                <div class="pontuacao-equipe"><?php echo t('geral.g44')?>: <b class="verde"><?php echo $equipe['vitoria']?></b></div>
                <div class="pontuacao-equipe"><?php echo t('geral.g46')?>: <b class="vermelho"><?php echo $equipe['derrota']?></b></div>
				<div class="pontuacao-equipe"><?php echo t('geral.g45')?>: <b class="verde"><?php echo $equipe['vitoria_pvp']?></b></div>
                <div class="pontuacao-equipe"><?php echo t('geral.g47')?>: <b class="vermelho"><?php echo $equipe['derrota_pvp']?></b></div>
				<div class="pontuacao-equipe" style="width:150px;"><?php echo t('geral.g48')?>: <b class="verde"><?php echo round($team_mine['media_level'])?></b></div>
            </div>
        </div>
        </div>
        <br />
        <div id="info_equipe" style="float:left; width:500px; height:115px;">
        <div class="guilds_bg missions">
			<div class="titulos-box-equipe"><b class="amarelo"><?php echo t('equipe_detalhe.e40')?></b></div>
			<div class="conteudo-box-equipe">
                <div style="width: 115px; float: left"><?php echo t('menus.missoes')?> D: <b class="verde"><?php echo $quests['lvl_d'] ?></b></div>
                <div style="width: 115px; float: left"><?php echo t('menus.missoes')?> C: <b class="verde"><?php echo $quests['lvl_c'] ?></b></div>
                <div style="width: 115px; float: left"><?php echo t('menus.missoes')?> B: <b class="verde"><?php echo $quests['lvl_b'] ?></b></div>
                <div style="width: 115px; float: left"><?php echo t('menus.missoes')?> A: <b class="verde"><?php echo $quests['lvl_a'] ?></b></div>
                <div style="width: 115px; float: left"><?php echo t('menus.missoes')?> S: <b class="verde"><?php echo $quests['lvl_s'] ?></b></div>
                <div style="width: 115px; float: left"><?php echo t('geral.falhas')?>: <b class="vermelho"><?php echo $quest_falha['mx'] ?></b></div>
            </div>
        </div>
       
  <?php
    $qMembros = Recordset::query("
			SELECT 
				a.id, 
				a.nome,
				a.id_classe,
				b.nome_".Locale::get()." AS nome_vila,
				c.nome_".Locale::get()." AS nome_graduacao,
				c.id AS grad_id,
				d.posicao_geral AS `rank`,
				a.level,
				a.id_missao,
				a.exp_equipe_dia,
				equipe_nivel_min
			FROM 
				player a JOIN vila b ON b.id=a.id_vila
				JOIN graduacao c ON c.id=a.id_graduacao
				LEFT JOIN ranking d ON d.id_player=a.id
			WHERE a.id_equipe=" . $id);
		
		$arTH		=  $arTDA = $arTDB = array();
		$membros	= $qMembros->num_rows;
		$min_levels	= array();
		
		while($r = $qMembros->row_array()) {
			$min_levels[]	= array(
				'level'	=> $r['equipe_nivel_min'],
				'name'	=> $r['nome'],
				'id'	=> $r['id']
			);
		
			if($is_other) {
				if($r['id'] == $equipe['id_player'] && $qMembros->num_rows > 1) {
					continue;	
				}
			} else {
				if($r['id'] == $basePlayer->id) {
					continue;	
				}
			}
			
			$classe = $r['id'] == $equipe['id_player'] ? t('geral.g27') : t('geral.g28');
			
			
			
			$arTDA[] = "<td style='width: 243px;' align='center'>
			
			<div id='character-image'>". player_imagem_ultimate($r['id']) ."</div>
			<div class='character-info'>
				<div class='name'><span class='azul'>".player_online($r['id'],true)."</span>". $r['nome'] ."</div>
				<div class='headline'>". graduation_name($basePlayer->id_vila, $r['grad_id']) . " Lvl." . $r['level'] . "</div>
			</div>
			
			</td>";
			
	   
			$arTH_TDB[] ="<td style='width: 243px;' align='center'>
						<div class='squad-player-container2'><div class='position'><span>" . ($equipe['id_playerb'] == $r['id'] ? t('botoes.sublider') : $classe) . "</span></div></div>
						<div>Ranking: <span class='amarelo'>" . ($r['rank'] ? $r['rank'] : "-") . "&deg;</span></div>
						<div class='barrinha-equipe'>" . barra_exp3($r['exp_equipe_dia'], 4900, 121, $r['exp_equipe_dia'] . " exp de 4900 exp", "#2C531D", "#537F3D",3, "", true) ."</div>
						</td>";
					
            $role_id	= Player::getFlag('equipe_role', $r['id']);
            
            switch($role_id) {
            	case 0:		$tooltip = 'Mestre Ninjutsu';	break;
            	case 1:		$tooltip = 'Mestre Medicinal';	break;
            	case 2:		$tooltip = 'Mestre Genjutsu';	break;
            	case 3:		$tooltip = 'Mestre Taijutsu';	break;
            	case 4:		$tooltip = 'Mestre Kinjutsu';	break;
            	case 5:		$tooltip = 'Mestre Bukijutsu';	break;
				case 6:		$tooltip = 'Mestre Defensivo';	break;
            }
            
            if(is_null($role_id)) {
            	$tooltip	= "Nenhum";
            	$role_img	= '/equipe_roles/nenhum.png';
            } else {
	            $role_lvl	= Player::getFlag('equipe_role_' . $role_id . '_lvl', $r['id']);
	            
	            if($role_lvl >= 1) {
		            $role_img	= Recordset::query('SELECT imagem FROM item WHERE id_habilidade=' . $role_id . ' AND ordem=' . $role_lvl . ' AND id_tipo=22')->row()->imagem;		            
	            } else {
		            $role_img	= Recordset::query('SELECT imagem FROM item WHERE id_habilidade=' . $role_id . ' AND ordem=1 AND id_tipo=22')->row()->imagem;
	            }
            }
            
			$arTDRoles[] = '<td valign="top"><img alt="' . $tooltip . '" title="' . $tooltip . '" src="' . img('layout/' . $role_img) . '" />
			<br />
			</td>';
			
			if(!$basePlayer->getAttribute('missao_equipe')) {
				$admin = $basePlayer->id == $equipe['id_player'] && $r['id'] != $basePlayer->id ? "<br /><input class=\"button\" role=\"button\" type=\"button\" value=\"".t('botoes.excluir_membro')."\" onclick=\"$js_function('" . encode($r['id']) . "')\" />" : "";
				$admin2 = $basePlayer->id == $equipe['id_player'] && $r['id'] != $basePlayer->id ? "<br /><input class=\"button ". ($equipe['id_playerb'] == $r['id'] ? "ui-state-green" : "") ."\" role=\"button\" type=\"button\" value=\"".t('botoes.sublider')."\" onclick=\"$js_functionc('" . encode($r['id']) . "')\" />" : "";
			} else {
				$admin 	= $basePlayer->id == $equipe['id_player'] && $r['id'] != $basePlayer->id ? "".t('equipe_detalhe.e44')."" : "";
				$admin2 = $basePlayer->id == $equipe['id_player'] && $r['id'] != $basePlayer->id ? "".t('equipe_detalhe.e44')."" : "";;
			}
			
			if($basePlayer->getAttribute('dono_equipe')) {
				$p		= new Player($r['id']);
				$admin	= $p->getAttribute('missao_equipe') ? "".t('equipe_detalhe.e45')."" : $admin;
				$admin2	= $p->getAttribute('missao_equipe') ? "".t('equipe_detalhe.e45')."" : $admin2;
				
			} else {
				$admin	= $basePlayer->getAttribute('missao_equipe') ? "".t('equipe_detalhe.e46')."" : $admin;
				$admin2	= $basePlayer->getAttribute('missao_equipe') ? "".t('equipe_detalhe.e46')."" : $admin2;
			}
			
			$arADM[] = '<td valign="top">' . $admin . ' <br />' . $admin2 . '</td>';
            
		}		
	?>
        
        <div class="guilds_bg lider-integrante">
				<?php
					$ranking	= Recordset::query('SELECT posicao_geral FROM ranking WHERE id_player=' . ($is_other ? $other_player->id : $basePlayer->id));
					$classe		= $basePlayer->id == $equipe['id_player'] ? t('geral.g27'): t('geral.g28');
				?>
	        <div class="titulos-box-equipe"><b class="amarelo"><?php echo $equipe['id_playerb'] == $basePlayer->id ? t('botoes.sublider') : $classe?></b></div>
			<div style="float: left; width: 140px">
					<div class="barrinha-equipe">
						<?php echo barra_exp3(($is_other ? $other_player->exp_equipe_dia : $basePlayer->exp_equipe_dia), 4900, 121, ($is_other ? $other_player->exp_equipe_dia : $basePlayer->exp_equipe_dia) . " " . t('geral.de') . " 4900 exp", "#2C531D", "#537F3D",3, "", true) ?>
					</div>
					<div style="color:#FFFFFF; width:130px; text-align:left; padding-left: 5px;">
						Level: <span class="cinza"><?php echo $is_other ? $other_player->level : $basePlayer->level ?></span><br>
						Ranking: 
						<span class="amarelo">
							<?php echo $ranking->num_rows ? $ranking->row()->posicao_geral : '-' ?>&deg;
						</span>
					</div>
			</div>
			<?php 
				$role_id	 	= Player::getFlag('equipe_role', $is_other ? $equipe['id_player'] : $basePlayer->id);
				
				if($role_id == '') {
					$mine_role_img	= '/equipe_roles/nenhum.png';
				} else {
					$mine_role_lvl	= Player::getFlag('equipe_role_' . $role_id . '_lvl', $is_other ? $equipe['id_player'] : $basePlayer->id);
					
					if($mine_role_lvl > 1) {
						$mine_role_img	= Recordset::query('SELECT imagem FROM item WHERE id_habilidade=' . $role_id . ' AND ordem=' . $mine_role_lvl . ' AND id_tipo=22', true)->row()->imagem;
					} else {
						$mine_role_img	= Recordset::query('SELECT imagem FROM item WHERE id_habilidade=' . $role_id . ' AND ordem=1 AND id_tipo=22', true)->row()->imagem;						
					}
				}
			
				$select = array( 
					'0' => 'Ninjutsu', 
					'1' => t('equipe_detalhe.e43'), 
					'2' => 'Genjutsu', 
					'3' => 'Taijutsu',
					'4' => 'Kinjutsu' ,
					'5' => 'Bukijutsu',
					'6' => 'Defensivo',
					'7' => t('equipe_detalhe.e51')
				); 
				
				$select_data = array(); 
				
				foreach($select as $k => $v) {
					if($k == 7) {
						$image	= $role_img	= '/equipe_roles/nenhum.png';
					} else {
						$role_lvl	= Player::getFlag('equipe_role_' . $k . '_lvl', $is_other ? $equipe['id_player'] : $basePlayer->id);
						
						if($role_lvl >= 1) {
							$image	= Recordset::query('SELECT imagem FROM item WHERE id_habilidade=' . $k . ' AND ordem=' . $role_lvl . ' AND id_tipo=22', true)->row()->imagem;							
						} else {
							$image	= Recordset::query('SELECT imagem FROM item WHERE id_habilidade=' . $k . ' AND ordem=1 AND id_tipo=22', true)->row()->imagem;
						}
					}
				
					$select_data[]	= '<option data-image="' . $image . '"' . ($role_id == $k && !is_null($role_id) || ($k == 7 && is_null($role_id)) ? ' selected="selected"' : '') . ' value="' . $k . '">' . $v . '</option>';
				}
			?>
			<div style="float: left; position:relative;">
				<p style="text-align:center; margin-top: 4px">
					<?php if(!$is_other): ?>
                    	<select style="margin:0; font-size:12px" id="s-my-role" onchange="doEquipeRole(this.value)"><?php echo join('', $select_data) ?></select>
                    <?php endif ?>
					<br />
                    <img id="i-equipe-my-role" style="margin-top: 2px;" src="<?php echo img('layout/' . $mine_role_img) ?>" />
				</p>
			</div>	 
        </div>
        </div>
	<div style="clear: both; margin-bottom:10px"></div>
	   <?php 
 				if(
	 				($basePlayer->playerRemoved($equipe['id_player'])->removido || 
					$basePlayer->playerRemoved($equipe['id_player'])->banido || 
					strtotime($basePlayer->getPlayerRemoved($equipe['id_player'])->ult_atividade. "+7 days") < now()) && $basePlayer->getAttribute('id_equipe') == $equipe['id']
				){
			?>
		   <div class="msg_gai">
				<div class="msg">
					<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
							<b><?php echo t("equipe.e5");?></b>
							<p>
							<?php echo t("equipe.e6");?><br /><br />
							 <form method="post" action="?secao=equipe_detalhe&option=<?php echo encode(5) ?>">
								<a class="button" data-trigger-form="1"><?php echo t('botoes.trocar_lider') ?></a>
							</form>
							</p>
					   </div>
				</div>	  
			</div>	
		<?php }?>
<div style="clear: both; margin-bottom:10px"></div>
	<table width="730" border="0" cellpadding="0" cellspacing="0">
        <tr>
			<td valign="middle" width="730" class="subtitulo-home">
				<div id="integrantes-equipe"><?php echo t('equipe_detalhe.e47')?>: <?php echo $equipe['nome'] ?></div>
			</td>
		</tr>
        <tr height="6"></tr>
	</table>
	<?php if(isset($arTH_TDB)): ?>
		<table width="730" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<?php echo join($arTDA) ?>
			</tr>
			<tr>
				<td colspan="4" align="center" height="5"></td>
			</tr>
			<tr>
				<?php echo join($arTH_TDB) ?>
			</tr>
			<tr>
				<?php echo join($arADM) ?>
			</tr>
			<tr height="6"></tr>
			<tr>
				<?php echo join($arTDRoles) ?>
			</tr>
		</table>  
	<?php endif ?>
	<br />
	</div>
<br />
<?php if($basePlayer->id == $equipe['id_player']): ?>
<div id="cnBase" class="direita">
  <form method="post" action="?secao=equipe_detalhe&option=<?php echo encode(1) ?>">
    <table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="subtitulo-home">
        <table width="730" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="30">&nbsp;</td>
              <td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('equipe_detalhe.e52')?></b></td>
              <td width="90" align="left" nowrap="nowrap">
              	&nbsp;&nbsp;<input type="checkbox" onclick="$('.aceitar_chk').attr('checked', this.checked)" />
                <b style="color:#FFFFFF"><?php echo t('equipe_detalhe.e53')?></b>
              </td>
              <td width="90" align="left" nowrap="nowrap">
               &nbsp;&nbsp;<input type="checkbox" onclick="$('.recusar_chk').attr('checked', this.checked)" />
                <b style="color:#FFFFFF"><?php echo t('equipe_detalhe.e54')?></b>
              </td>
              <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.player')?></b></td>
              <td width="90" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
              <td width="160" align="center"><b style="color:#FFFFFF"><?php echo t('geral.vila')?></b></td>
            </tr>
          </table></td>
      </tr>
    </table>
    <table width="730" border="0" align="center" cellpadding="0" cellspacing="0" style="margin:0">
      <?php
    	$pendencias = Recordset::query('
			SELECT
				a.id AS rid,
				a.id_player,
				b.id_classe,
				b.level AS level_player,
				b.nome As nome_player,
				c.nome_' . Locale::get() . ' AS nome_vila
			
			FROM
				equipe_pendencia a JOIN player b ON a.id_player=b.id
				JOIN vila c ON c.id=b.id_vila
			
			WHERE
				a.id_equipe=' . $basePlayer->getAttribute('id_equipe') . ' AND
				b.id_equipe=0 AND
				b.id_vila = ' . $basePlayer->getAttribute('id_vila'));
		
		$cn = 0;
	?>
	<?php foreach($pendencias->result_array() as $pendencia): ?>
	<?php
		$cor	 = ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
	?>
      <tr <?php echo $cor ?>>
        <td width="30">&nbsp;</td>
        <td width="130"><img src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/dojo/<?php echo $pendencia['id_classe'] ?>.<?php echo LAYOUT_TEMPLATE=="_azul" ? "jpg" : "png"?>" /></td>
        <td width="90" align="left"><input class="aceitar_chk" type="checkbox" name="aplayer[]" value="<?php echo $pendencia['id_player'] ?>" /></td>
        <td width="90" align="left"><input class="recusar_chk" type="checkbox" name="rplayer[]" value="<?php echo $pendencia['id_player'] ?>" /></td>
        <td width="140"><?php echo $pendencia['nome_player'] ?></td>
        <td width="90"><?php echo $pendencia['level_player'] ?></td>
        <td width="160"><?php echo $pendencia['nome_vila'] ?></td>
      </tr>
      <tr height="4"></tr>
	<?php endforeach; ?>
    </table>
    <div align="center">
      <a class="button" data-trigger-form="1"><?php echo t('botoes.enviar_alteracoes')?></a>
    </div>
  </form>
</div>
<?php endif; ?>
<div class="team-message">
	<form method="post" action="?secao=equipe_detalhe&option=<?php echo encode(4) ?>">
		<p><?php echo t('geral.titulo') ?></p>
			<p><input type="text" name="title" /></p>
		<p>
			<label><?php echo t('geral.mensagem') ?></label>
		</p>
		<p>
			<textarea name="message" rows="5"></textarea>
		</p>
	</form>
</div>
<br />
<br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "2775961778";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Equipe -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
