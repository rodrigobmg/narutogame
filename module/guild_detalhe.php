<?php
	$redir_script			= true;
	$_SESSION['squad_key']	= uniqid(uniqid());

	if(isset($_GET['id']) && $_GET['id']) {
		if(!is_numeric(decode($_GET['id']))) {
			redirect_to("negado", NULL, array("e" => 1));
		}
		
		$guild_id = (int)decode($_GET['id']);
	} else {
		$guild_id = $basePlayer->getAttribute('id_guild');
	}
	
	if(!$guild_id) {
		redirect_to("negado", NULL, array('e' => 2));		
	}

	$hasError		= false;
	$expulsar_mul	= Player::getFlag('guild_expulsar_mul', $basePlayer->id);
	$sair_mul		= Player::getFlag('guild_sair_mul', $basePlayer->id);
	$del_mul		= Player::getFlag('guild_del_mul', $basePlayer->id);
	$uploadError	= '';
	$errorMsg		= '';
	$guild_base		= Recordset::query("
		SELECT
			a.id,
			a.id_player AS id_lider,
			b.nome AS nome_lider,
			a.nome,
			a.descricao,
			a.vitorias,
			a.derrotas,
			a.imagem,
			c.posicao_geral,
			c.posicao_vila,
			c.pontos,
			a.membros,
			a.level,
			a.exp_level,
			a.exp_level_dia,
			a.exp_total,
			a.diarias,
			a.diarias2,
			a.id_quest_guild
		
		FROM
			guild a 
			
			JOIN player b ON a.id_player=b.id 
			LEFT JOIN ranking_guild c ON c.id_player=b.id
		
		WHERE
			a.id=" . $guild_id);

	$guild = $guild_base->row_array();
	$min_levels	= array();
	$membros = Recordset::query("
		SELECT
			b.id AS id_player,
			b.id_classe AS classe,
			b.level AS level_player,
			b.nome AS nome_player,
			b.guild_nivel_min
		
		FROM
			guild a JOIN player b ON a.id=b.id_guild
		WHERE
			a.id=" . $guild_id);
	
	foreach($membros->result_array() as $membro) {
			$min_levels[]	= array(
				'level'	=> $membro['guild_nivel_min'],
				'name'	=> $membro['nome_player'],
				'id'	=> $membro['id_player']
			);
	}

	$bb_guild		= Recordset::query("
		SELECT
			SUM(CASE WHEN morto = '1' THEN 1 ELSE 0 END) AS mortos,
			SUM(CASE WHEN morto = '0' THEN 1 ELSE 0 END) AS vivos		
		FROM
			bingo_book_guild 
		WHERE
			id_guild= " . $guild_id);
			
	$guild_bb = $bb_guild->row_array();		

	if(isset($_GET['option']) && $_GET['option']) {
		$_GET['option'] = decode($_GET['option']);
		
		$redir_script = true;
		
		if(!is_numeric($_GET['option'])) {
			redirect_to("negado", NULL, array('e' => 3));
		}
		
		switch($_GET['option']) {
			case 1: // Mudar a descricao
				Recordset::update('guild', array(
					'descricao'	=> htmlspecialchars(substr($_POST['descricao'], 0, 195))
				), array(
					'id'		=> $basePlayer->getAttribute('id_guild')
				));
				
				break;
			
			case 2: // Postar imagem
				$mime = array(
					"image/jpeg",
					"image/png",
					"image/gif"
				);
				
				$file = $_FILES['guild_foto'];
				
				if(!$file['error']) {
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
					$nome = md5($file['name']) . substr($file['name'], stripos($file['name'], "."));
					$path = realpath(dirname(__FILE__) . "/../images") . "/guild/" . $nome;
					
					$sz = getimagesize($file['tmp_name']);
					
					if($sz['0'] > 663 || $sz['1'] > 166) {
						$uploadError = true;
					} else {
						move_uploaded_file($file['tmp_name'], $path);
						Recordset::query("UPDATE guild SET imagem='" . addslashes($nome) . "' WHERE id=" . $basePlayer->getAttribute('id_guild'));
	
						redirect_to("guild_detalhe");
					}
				}
				
				break;
			
			case 4:
				$qQuest = Recordset::query('SELECT id FROM vila_quest WHERE id_guild=' . $basePlayer->getAttribute('id_guild'));
				
				if($qQuest->num_rows) {
					$hasError	= true;
					$errorMsg	= t('guild_detalhe.g1');
				} else {
					if(isset($_POST['rplayer']) && is_array($_POST['rplayer'])) {
						foreach($_POST['rplayer'] as $v) {
							if(!is_numeric($v)) {
								redirect_to("negado", NULL, array('e' => 4));
							}
							
							Recordset::delete('guild_pendencia', array(
								'id_player'	=> $v,
								'id_guild'	=> $basePlayer->getAttribute('id_guild')
							));
							
							mensageiro(NULL, $v, t('guild_detalhe.g2'), sprintf(t('guild_detalhe.g3'), $basePlayer->getAttribute('nome_guild')), 'guild');
						}
					}
	
					if(isset($_POST['aplayer']) && is_array($_POST['aplayer'])) {
						foreach($_POST['aplayer'] as $v) {
							if(!is_numeric($v)) {
								redirect_to("negado", NULL, array('e' => 5));
							}
							
							if($guild['membros'] >= 9) {
								$hasError = true;
								$errorMsg = t('guild_detalhe.g4');
								
								continue;
							}
							
							$guild['membros']++;
							
							// Se for aceito, remove todas as pendencias
							Recordset::delete('guild_pendencia', array(
								'id_player'	=> $v,
							));

							if(Recordset::query('SELECT id FROM player WHERE id=' . $v . ' AND id_guild !=0')->num_rows) {
								continue;
							}
							
							Recordset::update('player', array(
								'id_guild'	=> $basePlayer->getAttribute('id_guild')
							), array(
								'id'		=> $v
							));

							mensageiro(NULL, $v, t('guild_detalhe.g5'), t('guild_detalhe.g6').': ' . $basePlayer->getAttribute('nome_guild'), 'guild');

							// Atualiza o contador
							Recordset::update('guild', array(
								'membros'	=> array('escape' => false, 'value' => 'membros+1')
							), array(
								'id'		=> $basePlayer->getAttribute('id_guild')
							));
						}
					}
				}

				break;
			
			case 3: // Excluir organização
			case 5: // Sair da guild
				if(!isset($_POST['player']) || (isset($_POST['player']) && !is_numeric($_POST['player']))) {
					redirect_to("negado", NULL, array('e' => 6));					
				}
				
				$qQuest = Recordset::query('SELECT id FROM vila_quest WHERE id_guild=' . $basePlayer->getAttribute('id_guild'));
				
				if($qQuest->num_rows) {
					$hasError	= true;
					$errorMsg	= t('guild_detalhe.g1');
				} else {
					$player = Recordset::query('SELECT nome, id_missao_guild FROM player WHERE id=' . $_POST['player'] . ' AND id_guild=' . $basePlayer->getAttribute('id_guild'));
					
					if(!$player->num_rows) {
						redirect_to('negado', NULL, array('e' => 7));
					}
					
					$player = $player->row_array();
					
					if($player['id_missao_guild']) {
						$hasError	= true;
						$errorMsg	= t('guild_detalhe.g7');
					} elseif($guild['id_quest_guild']) {
						$hasError	= true;
						$errorMsg	= t('guild_detalhe.g8');
					} else {
						if($_POST['player'] == $guild['id_lider']) { // excluir guild
							if($guild['membros'] >= 1) {
								$hasError = true;
								$errorMsg =  t('guild_detalhe.g9');	
							} else {
								// Removendo os créditos do lider para remover a guild.
								if($basePlayer->getAttribute('coin') < 2 * $del_mul) {
									redirect_to("vantagens");
								}								
								Recordset::update('player', array(
									'id_guild'	=> 0
								), array(
									'id_guild'	=> $basePlayer->getAttribute('id_guild')
								));
								
								Recordset::update('guild', array(
									'removido'	=> '1'
								),array(
									'id'		=> $basePlayer->getAttribute('id_guild')
								));
								
								// Atualizar o custo para deletar a guild
								
								Recordset::update('player_flags', array(
									'guild_del_mul'	=> array('escape' => false, 'value' => 'guild_del_mul + 1')
								),array(
									'id_player'		=> $basePlayer->id
								));
								
								gasta_coin(2 * $del_mul,21887);
								
								redirect_to("personagem_status");
							}
						} else { // Ação normal
							if($_POST['player'] == $basePlayer->id) { // Eu saindo
								if($basePlayer->getAttribute('ryou') < 500 * $sair_mul) {
									$hasError = true;
									$errorMsg = t('guild_detalhe.g10');
								} else {
									mensageiro(NULL, $guild['id_lider'], 'Naruto Game: '.t('guild_detalhe.g11'), $player['nome'] . ' '.t('guild_detalhe.g12'), 'guild');
									
									$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') - (500 * $sair_mul));
									$basePlayer->setFlag('guild_sair_mul', $sair_mul + 1);

									Recordset::insert('player_expulso', array(
										'id_player' => $basePlayer->id,
										'id_objeto' => $basePlayer->id_guild,
										'tipo'		=> 'guild'
									));
								}
							} else { // Lider expulsando
								//if($basePlayer->getAttribute('ryou') < 500 * $expulsar_mul) {
								if($basePlayer->getAttribute('ryou') < 500) {
									$hasError = true;
									$errorMsg = "Você não tem ryous suficientes";
								} else {
									//$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') - (500 * $expulsar_mul));
									$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') - (500));
									$basePlayer->setFlag('guild_expulsar_mul', $expulsar_mul + 1);

									Recordset::insert('player_expulso', array(
										'id_player' => $_POST['player'],
										'id_objeto' => $basePlayer->id_guild,
										'tipo'		=> 'guild'
									));
	
									mensageiro(NULL, $_POST['player'], 'Naruto Game: '.t('guild_detalhe.g11'), t('guild_detalhe.g13') . $guild['nome'], 'guild');
								}
							}
							
							if(!$hasError) {
								Recordset::delete('guild_esquadrao', [
									'id_player'	=> $_POST['player']
								]);

								Recordset::update('player', array(
									'id_guild'	=> 0
								), array(
									'id'		=> $_POST['player']
								));
								
								// Atualiza o contador
								Recordset::update('guild', array(
									'membros'	=> array('escape' => false, 'value' => 'membros-1')
								), array(
									'id'		=> $basePlayer->getAttribute('id_guild')
								));
								
								if($_POST['player'] == $basePlayer->id) {
									redirect_to('personagem_status');
								} else {
									redirect_to('guild_detalhe');						
								}							
							}
						}					
					}
				}			
				
				break;
			
			case 6: // Enviar mensagem pra guilda
				if(!$_POST['title']) {
					$errorMsg = t('guild_detalhe.g14');
				} else {
					if(!$_POST['message']) {
						$errorMsg = t('guild_detalhe.g15');
					} else {
						$players = Recordset::query('SELECT id FROM player WHERE id_guild=' . $basePlayer->getAttribute('id_guild'));
						
						foreach($players->result_array() as $player) {
							if($player['id'] == $basePlayer->id) {
								continue;	
							}

							mensageiro($basePlayer->id, $player['id'], htmlspecialchars($_POST['title']), htmlspecialchars($_POST['message']), 'guild');
						}
					}

					redirect_to("guild_detalhe");
				}
				
				break;
			case 7:
				$removed =  isset($basePlayer->playerRemoved($guild['id_lider'])->removido) || isset($basePlayer->playerRemoved($guild['id_lider'])->banido) ?  1 : 0;
				if(!$removed && strtotime($basePlayer->PlayerRemoved($guild['id_lider'])->ult_atividade. "+7 days") > now() && $basePlayer->id_guild != $guild['id'] ){
					$hasError	= true;
					$errorMsg	= "Você não pode fazer essa ação!";
				}else{
					if($basePlayer->getAttribute('coin') < 2) {
						redirect_to("vantagens");
					}
					
					Recordset::query("UPDATE guild SET id_player=". $basePlayer->id .", membros = membros-1 WHERE id =".$basePlayer->getAttribute('id_guild'));
					Recordset::query("DELETE FROM guild_esquadrao WHERE id_player=". $basePlayer->id);
					Recordset::query("UPDATE guild_esquadrao SET id_player=". $basePlayer->id ." WHERE id_player =".$guild['id_lider']);
					
					Recordset::update('player', array(
						'id_guild'	=> 0
					), array(
						'id'		=> $guild['id_lider']
					));		
								
					gasta_coin(2,1494206);
				}
				
			break;		
		}
	}
	
	if(isset($_GET['id']) && $_GET['id']) {
		if(!is_numeric(decode($_GET['id']))) {
			redirect_to("negado", NULL, array("e" => 8));
		}
		
		$guild_id = (int)decode($_GET['id']);
	} else {
		$guild_id = $basePlayer->getAttribute('id_guild');
	}
	
	if(!$guild_id) {
		redirect_to("negado", NULL, array('e' => 9));		
	}

	$guild = $guild_base->repeat()->row_array();
	
	$q_guild_level_atual = Recordset::query("SELECT * FROM guild_level WHERE id=" . $guild['level'], true);

	if ($q_guild_level_atual->num_rows) {
		$guild_level_atual = $q_guild_level_atual->row_array();
	} else {
		$guild_level_atual = [
			'exp' => 0,
		];
	}
?>
<script type="text/javascript">
	function sairGuild() {
		jconfirm("<?php echo t('guild_detalhe.g17')?> <?php echo $sair_mul * 500 ?> ryous.", '<?php echo t('guild_detalhe.g16')?>', function () {
			$("#fSairGuild")[0].submit();		
		});
	}
</script>

<script type="text/javascript">
    
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
	
	<?php if($basePlayer->id == $guild['id_lider']): ?>
		function deletaCla() {
			jconfirm("Quer realmente excluir essa organização? Serão necessários <?php echo 2 * $del_mul ?> <?php echo t('geral.creditos')?>", null, function () {
				$("#fDeletaCla")[0].submit();
			});
		}

		$(document).ready(function(e) {
			$('#b-guild-message').on('click', function () {
				$('.guild-message').dialog({
					title:		'<?php echo t('guild_detalhe.confirmar_mensagem_t') ?>',
					modal:		true,
					buttons:	{
						'Enviar':	function () {
							jconfirm('<?php echo t('guild_detalhe.confirmar_mensagem') ?>', null, function () {
								$('.guild-message form').submit();
							});
						}
					}
				});
			});


			$('#guild-description').on('click', function () {
				var	_			= $(this);
				var	d			= $(document.createElement('DIV')).addClass('description-editor-container');
				var	controls	= $(document.createElement('DIV')).addClass('controls');
				var	field		= $(document.createElement('TEXTAREA')).attr('maxlength', 195).text(_.text());
				var	cancel		= $(document.createElement('A')).html('Cancelar').addClass('button');
				var	save		= $(document.createElement('A')).html('Salvar').addClass('button');
				var	label		= $(document.createElement('SPAN')).html('Editar descrição:').addClass('title');

				controls.append(cancel, save);
				d.append(label, field, controls);

				cancel.on('click', function () {
					d.remove();
				});

				save.on('click', function () {
					lock_screen(true);

					var	form	= $('<form action="?secao=guild_detalhe&option=<?php echo encode(1) ?>" method="post"><input type="hidden" name="descricao" value=""></form>');

					$(document.body).append(form);

					$('input', form).val(field.val());
					form.submit();
				});

				_.parent().append(d);

				field.focus();
			});
		});
	<?php endif; ?>

	$(document).ready(function () {
		function _reload_squad() {
			var data	= {squad_key: '<?php echo $_SESSION['squad_key'] ?>', action: 'show'};

			<?php if ($guild['id'] != $basePlayer->id_guild): ?>
				data.guild	= <?php echo $guild['id'] ?>;
			<?php endif ?>

			$.ajax({
				url:		'?acao=guild_esquadrao',
				data:		data,
				dataType:	'json',
				type:		'post',
				success:	function (result) {
					if(result.success) {
						var	container	= $('#d-squad-data');
						
						container.html(result.data);

						<?php if($basePlayer->id == $guild['id_lider'] && $guild['id'] == $basePlayer->id_guild): ?>
							$('.b-expulsar').bind('click', function () {
								var _this = $(this);
								
								jconfirm('<?php echo t('guild_detalhe.g19')?> <?php echo 500 ?> <?php echo t('guild_detalhe.g20')?>', '<?php echo t('guild_detalhe.g18')?>', function () {
									this.disabled = true;

									$('#f-expulsar-' + _this.attr('rel'))[0].submit();			
								});
							});

							$('.squad-player-container img', container).on('click', function () {
								var	_		= $(this);

								if(_.data('slot') == 1 && _.data('squad') == 1) {
									return;
								}

								$.ajax({
									url:	'?acao=guild_esquadrao',
									data:		{squad_key: '<?php echo $_SESSION['squad_key'] ?>', action: 'list'},
									dataType:	'json',
									type:		'post',
									success:	function (result) {
										if (result.success) {
											var	win	= jalert(result.data, '<?php echo t('guild.esquadrao.select') ?>', null, 770);

											$('.player', win).on('click', function () {
												var	_this	= $(this);

												jconfirm(_this.data('filled') ? '<?php echo t('guild.esquadrao.change') ?>' : '<?php echo t('guild.esquadrao.add') ?>', null, function () {
													win.dialog('destroy');

													lock_screen(true);
													$.ajax({
														url:	'?acao=guild_esquadrao',
														data:		{
															squad_key:	'<?php echo $_SESSION['squad_key'] ?>',
															action:		'add',
															slot:		_.data('slot'),
															squad:		_.data('squad'),
															player:		_this.data('id')
														},
														dataType:	'json',
														type:		'post',
														success:	function (result) {
															lock_screen(false);

															if (result.success) {
																if (!_this.data('filled')) {
																	jalert('<?php echo t('guild.esquadrao.success.added') ?>');
																} else {
																	jalert('<?php echo t('guild.esquadrao.success.changed') ?>');
																}

																_reload_squad();
															}
														}
													});
												});
											});
										}
									}
								});
							});
						<?php endif; ?>
					}
				}
			});
		}

		_reload_squad();
	});
</script>
<div class="titulo-secao"><p><?php echo t('guild_detalhe.g11')?></p></div><br />
<?php if($uploadError): ?>
<div class="error"><?php echo t('equipe_detalhe.e30')?></div>
<br />
<?php endif; ?>
<?php
	if($errorMsg) {

		msg('3',''.t('academia_treinamento.at15').'', ''.$errorMsg.'');
	
	}
?>
<div id="bg_foto_equipe" style="background:url(<?php echo img('layout/bg_guilds_foto.png') ?>); width:730px; height:205px; position:relative; left:-7px">
  <div id="foto_equipe" style="position:relative; top:14px; left: 9px">
  		<img src="<?php echo $guild['imagem'] ? img('guild/' . $guild['imagem']) : img('layout/foto_equipe.jpg') ?>" />
  </div>
</div>
<?php if($basePlayer->id == $guild['id_lider']){ ?>
<div style="width: 665px; clear:both; padding-top:5px; margin-left:auto;">
<form action="?secao=guild_detalhe&option=<?php echo encode(2) ?>" method="post" enctype="multipart/form-data">
	<div style="float: left;"><b style="color: #ab6820;"><?php echo t('equipe_detalhe.e32')?></b></div>
	<div style="float: left; width: 345px; margin-top: -9px">
			<a class="button" data-trigger-form="1"><?php echo t('botoes.alterar') ?></a><input type="file" name="guild_foto" style="width: 210px;"/></div>
	<div style="float: left; width: 180px; margin-top: -7px;"><span style="color:#ab6820; font-size:10px"><?php echo t('equipe_detalhe.e33')?></span></div>
</form>
</div>
<?php }?>
<br />
<br />
<br />
<div id="guild-container">
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="10%" rowspan="2">
			<div>
				LEVEL<br />
				<span style="font-size: 50px; color: <?php echo LAYOUT_TEMPLATE=="_azul" ? "#0e3a57" : "#3f2b1c"?>; top: 8px; position: relative"><?php echo $guild['level'] > 25 ? 25 : $guild['level']?></span>	
			</div>
		</td>
		<td height="58" align="center" style="position: relative">
			<p style="width: 550px" id="guild-description"><?php echo $guild['descricao'] ? $guild['descricao'] : 'Sem descrição' ?></p>
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
			<?php barra_exp3($guild['exp_level'], $guild_level_atual['exp'], 580, $guild['exp_level'] . " Exp ". t('vila.v8')." / " . $guild_level_atual['exp'] ." Exp", "#840303", "#E90E0E", 3) ?>
			<?php barra_exp3($guild['exp_total'], 100000, 581, "Total de Experiência Acumulada: ". $guild['exp_total'], "#840303", "#E90E0E", 3) ?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<div style="position:relative; top: 8px;">
			<?php $guild_level = new Recordset("SELECT * FROM guild_level", true); ?>
				<?php foreach($guild_level->result_array() as $level): ?>
					<?php ob_start(); ?>
					<b class='verde'><?php echo t('geral.g78')?></b><br /><br />
					<?php if($level['id'] < $guild['level']): ?>
						<?php barra_exp3($level['exp'], $level['exp'], 220, $level['exp'] . " Exp " . t('equipe_detalhe.e35') ." ". $level['exp'] ." Exp", "#2C531D", "#537F3D",7) // Imagem ?>
					<?php elseif($level['id'] > $guild['level']): ?>
						<?php barra_exp3(0, $level['exp'], 220, "0 Exp " . t('equipe_detalhe.e35') ." ". $level['exp'] ." Exp", "#2C531D", "#537F3D",6) ?>
					<?php else: ?>
						<?php barra_exp3($guild['exp_level'], $level['exp'], 220, (int)$guild['exp_level'] . " Exp " .t('equipe_detalhe.e35')." ". $level['exp'] ." Exp", "#2C531D", "#537F3D",6) ?>
					<?php endif; ?>
					
					<?php $sorte_ninja = new Recordset('SELECT nome_br, nome_en FROM loteria_premio WHERE id=' . $level['id_sorte_ninja']) ?>
						<br /><b class='verde'><?php echo t('geral.premios')?></b><br /><br />
						<p class="laranja"><?php echo t('guild_detalhe.g27')?> <strong><?php echo $sorte_ninja->row()->{'nome_' . Locale::get()} ?></strong>.</p>
					<?php
						$has_min_level	= array();
						
						foreach($min_levels as $p) {
							if($p['level'] >= $level['id'] && $p['level'] >= $guild['level']) {
								$has_min_level[]	= $p['name'];
							}
						}
					?>	
					<?php if(sizeof($has_min_level)): ?>
						<br /><p style="color: #774B9C"><?php echo t('geral.g79')?>: <?php echo join(',', $has_min_level) ?></p>
					<?php endif ?>
					<?php $premio = ob_get_clean();?>
					
					<div style="float: left; padding-left: 4px;">
					<?php if((int)$guild['level'] > $level['id']):?>
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
<div style="width:730px; height: 120px; top: 5px; position: relative; clear:both; float: left">
	<div class="guilds_bg">
		<div style="padding: 10px 0 0 20px"><b class="amarelo" style="font-size:13px"><?php echo t('equipe_detalhe.e39')?></b></div>
		<div style="padding-top: 10px; padding-left: 6px; width: 230px; line-height:16px">
			<div style="width: 110px; float: left"><?php echo t('geral.score')?>: <b class="azul"><?php echo $guild['pontos'] ?></b></div>
			<div style="width: 110px; float: left"><?php echo t('geral.membros')?>: <b class="laranja"><?php echo $guild['membros'] + 1 ?></b></div>
			<div style="width: 110px; float: left"><?php echo t('geral.posicao_g')?>:<b class="azul"> <?php echo $guild['posicao_geral'] ?> </b> º</div>
			<div style="width: 114px; float: left"><?php echo t('geral.posicao_v')?>: <b class="amarelo"><?php echo $guild['posicao_vila'] ?></b> º</div>
			<div style="width: 110px; float: left"><?php echo t('geral.vitorias')?>:<b class="verde"> <?php echo $guild['vitorias'] ?></b></div>
			<div style="width: 110px; float: left"><?php echo t('geral.derrotas')?>: <b class="vermelho"><?php echo $guild['derrotas'] ?></b></div>
		</div>
	</div>
	<div class="guilds_bg">
		<div style="padding: 10px 0 0 20px"><b class="amarelo" style="font-size:13px"><?php echo t('equipe_detalhe.e55')?> / BINGO BOOK</b></div>
		<div style="padding-top: 10px; padding-left: 6px; width: 230px; line-height:16px">
			<div style="width: 110px; float: left"><?php echo t('guild_detalhe.g22')?>:<b class="verde"> <?php echo $guild['diarias'] ?></b></div>
			<div style="width: 110px; float: left"><?php echo t('guild_detalhe.g23')?>: <b class="verde"><?php echo $guild['diarias2'] ?></b></div>
            <div style="width: 110px; float: left"><?php echo t('guilds_participar.alvo_vivo')?>:<b class="vermelho"> <?php echo $guild_bb['vivos'] ?></b></div>
            <div style="width: 110px; float: left"><?php echo t('guilds_participar.alvo_morto')?>:<b class="verde"> <?php echo $guild_bb['mortos'] ?></b></div>
		</div>
	</div>
	<div class="guilds_bg">
		<div style="padding: 10px 0 0 20px"><b class="amarelo" style="font-size:13px"><?php echo t('guild_detalhe.g21')?></b></div>
		<?php if ($guild['id'] == $basePlayer->id_guild): ?>
			<div style="padding-top: 2px; padding-left: 9px;" align="center">
				<?php if($basePlayer->getAttribute('id_guild') == $guild['id'] && !($basePlayer->id == $guild['id_lider'])): ?>
						<form id="fSairGuild" action="?secao=guild_detalhe&option=<?php echo encode(5) ?>" method="post" onsubmit="return false;">
							<input type="hidden" value="<?php echo $basePlayer->id ?>" name="player" />
							<a class="button" onclick="sairGuild()"  id="btn"><?php echo t('botoes.deixar_guild') ?></a>
						</form>
                        <br />
				<?php else: ?>
					<?php if($basePlayer->id == $guild['id_lider'] && $guild['membros'] <= 0): ?>
						  <form action="?secao=guild_detalhe&option=<?php echo encode(3) ?>" method="post" name="fDeletaCla" id="fDeletaCla" onsubmit="return false;">
							<input type="hidden" value="<?php echo $basePlayer->id ?>" name="player" />
							
							<a class="button" onclick="deletaCla()" ><?php echo t('botoes.excluir_guild') ?></a>
						  </form>
                          <br />
					<?php endif; ?>
				<?php endif; ?>
				<a class="button" id="b-guild-message"><?php echo t('botoes.enviar_msg_guild') ?></a>
			</div>
		<?php endif ?>
	</div>
</div>
<div class="break"></div>
<?php 
	if(
		($basePlayer->playerRemoved($guild['id_lider'])->removido ||
		$basePlayer->playerRemoved($guild['id_lider'])->banido || 
		strtotime($basePlayer->PlayerRemoved($guild['id_lider'])->ult_atividade. "+7 days") < now()) && $basePlayer->id_guild == $guild['id']
	){
?>
   <div class="msg_gai">
		<div class="msg">
			<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
					<b><?php echo t("equipe.e5");?></b>
					<p>
					<?php echo t("equipe.e6");?><br /><br />
					 <form method="post" action="?secao=guild_detalhe&option=<?php echo encode(7) ?>">
						<a class="button" data-trigger-form="1"><?php echo t('botoes.trocar_lider') ?></a>
					</form>
					</p>
			   </div>
		</div>	  
	</div>	
<?php }?>
<div id="d-squad-data" style="clear:both">...</div>
<div id="d-squad-stats" style="clear:both">...</div>
<br /><br />
<?php if($basePlayer->id == $guild['id_lider']): ?>
<div id="cnBase" class="direita">
  <form method="post" action="?secao=guild_detalhe&option=<?php echo encode(4) ?>">
    <table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="30">&nbsp;</td>
              <td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('equipe_detalhe.e52')?></b></td>
              <td width="110" align="left" nowrap="nowrap">
              	&nbsp;&nbsp;<input type="checkbox" onclick="$('.aceitar_chk').attr('checked', this.checked)" />
                <b style="color:#FFFFFF"><?php echo t('equipe_detalhe.e53')?></b>
              </td>
              <td width="110" align="left" nowrap="nowrap">
               &nbsp;&nbsp;<input type="checkbox" onclick="$('.recusar_chk').attr('checked', this.checked)" />
                <b style="color:#FFFFFF"><?php echo t('equipe_detalhe.e54')?></b>
              </td>
              <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.player')?></b></td>
              <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
              <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.vila')?></b></td>
            </tr>
          </table></td>
      </tr>
    </table>
    <table width="730" border="0" cellpadding="0" cellspacing="0">
      <?php
    	$pendencias = Recordset::query('
			SELECT
				a.id AS rid,
				a.id_player,
				b.id_classe,
				b.level AS level_player,
				b.nome As nome_player,
				c.nome_'. Locale::get().' AS nome_vila
			
			FROM
				guild_pendencia a JOIN player b ON a.id_player=b.id
				JOIN vila c ON c.id=b.id_vila
			
			WHERE
				a.id_guild=' . $basePlayer->getAttribute('id_guild') . ' AND
				b.id_guild=0 AND
				b.id_vila = ' . $basePlayer->getAttribute('id_vila'));
		
		$cn = 0;
	?>
	<?php foreach($pendencias->result_array() as $pendencia): ?>
	<?php
		$cor = ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
	?>
      <tr class="<?php echo $cor ?>">
        <td width="30">&nbsp;</td>
        <td width="130"><img src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE ?>/dojo/<?php echo $pendencia['id_classe'] ?>.<?php echo LAYOUT_TEMPLATE=="_azul" ? "jpg" : "png"?>" /></td>
        <td width="110" align="left"><input class="aceitar_chk" type="checkbox" name="aplayer[]" value="<?php echo $pendencia['id_player'] ?>" /></td>
        <td width="110" align="left"><input class="recusar_chk" type="checkbox" name="rplayer[]" value="<?php echo $pendencia['id_player'] ?>" /></td>
        <td width="140"><?php echo $pendencia['nome_player'] ?></td>
        <td width="110"><?php echo $pendencia['level_player'] ?></td>
        <td width="100"><?php echo $pendencia['nome_vila'] ?></td>
      </tr>
	  <tr height="4"></tr>
	<?php endforeach; ?>
    </table>
    <div align="center" style="width: 730px; padding-top: 10px">
	  <a class="button" data-trigger-form="1"><?php echo t('botoes.enviar_alteracoes') ?></a>
    </div>
  </form>
</div>
<?php endif; ?>
<div class="guild-message">
	<form method="post" action="?secao=guild_detalhe&option=<?php echo encode(6) ?>">
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
    google_ad_slot = "2636360978";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Guilds -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
