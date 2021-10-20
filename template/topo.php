<?php
	$protocol	= $_SERVER['SERVER_PORT'] == '443' ? 'https' : 'http';

	$_SESSION['bingo_book_key'] = md5(rand(1, 512384) . rand(1, 512384));
	if(isset($_GET['ref']) && $_GET['ref']){
		$_SESSION['ref'] = $_GET['ref'];
	}
	if(isset($_GET['uref']) && is_numeric($_GET['uref'])) {
		$_SESSION['user_ref'] = $_GET['uref'];
	}
?>
<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="google-site-verification" content="c9a1zh80SwyaBQewF16AyALFXNpXkZ-rh6km-s-aN4I" />
<meta name="og:image" content="<?php echo server_protocol() ?>://narutogame.com.br/favicon.ico" />

<?php if($_SESSION['logado']): ?>
  <title>Naruto Game - <?php echo t('templates.t11')?></title>
  <meta name="keywords" content="jogo de naruto, naruto game, game naruto, jogo naruto, naruto jogos online" />
  <meta name="keywords" content="jogo de naruto, naruto game, game naruto, jogo naruto, naruto jogos online" />
  <meta name="description" content="<?php echo t('templates.t13')?>" />
  <?php if (in_array($_GET['secao'], ['mapa_vila', 'mapa'])) { ?>
  	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <?php } ?>
<?php else: ?>
	<title>Naruto Game - <?php echo t('templates.t12')?></title>
  <meta name="keywords" content="naruto online, naruto rpg online, naruto rpg, jogo de naruto, naruto game, game naruto, jogo naruto, naruto jogos online" />
  <meta name="description" content="<?php echo t('templates.t13')?>" />
<?php endif; ?>
<link href='https://www.facebook.com/narutogamebr' rel='author'/>
<link href='https://plus.google.com/u/0/b/110764258523291224819/110764258523291224819' rel='me'/>
<link href='https://twitter.com/narutogame' rel='me'/>
<link href="css/layout<?php echo LAYOUT_TEMPLATE?>.css" rel="stylesheet" type="text/css"/>
<link href="css/html<?php echo LAYOUT_TEMPLATE?>.css" rel="stylesheet" type="text/css"/>
<link rel="shortcut icon" href="<?php echo server_protocol() ?>://narutogame.com.br/favicon.ico" type="image/x-icon"/>
<script type="text/javascript" src="js/head.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.countdown.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/tutorial.js"></script>
<script type="text/javascript" src="js/global<?php echo LAYOUT_TEMPLATE?>.js?c=<?php echo filemtime(ROOT . '/js/global'.LAYOUT_TEMPLATE.'.js') ?>"></script>
<script src="js/socket.io.js"></script>

<!-- POP-UP -->

<?php if($basePlayer && ($_GET['secao']!="mapa_vila" || $_GET['secao']!="mapa" ) ): ?>
<script type="text/javascript">
	var	socket	= io.connect('<?php echo $protocol ?>://<?php echo $_SERVER['SERVER_NAME'] == 'localhost' ? $_SERVER['SERVER_ADDR'] : "" . $_SERVER['SERVER_NAME'] ?>:2533');
	socket.on('connect', function () {
		socket.emit('set-language', {lang: '<?php echo Locale::get() ?>'});
	});
	socket.on('message', function (data) {
		var	d	= $(document.createElement('DIV')).addClass('highlight-window');
		var	m	= $(document.createElement('DIV')).addClass('highlight-text');
		var	len	= $('.highlight-window').length;

		m.html(data.message);
		d.append(m);
		$(document.body).append(d);
		if(len) {
			d.css({marginTop: len * (d.height() + 10)});
		}
		var	__iv	= setInterval(function () {
			d.animate({opacity: 0}, function () {
				d.remove();
			});
			clearInterval(__iv);
		}, 7000);
	});
</script>
<?php endif ?>
<script type="text/javascript">
	var __site		= "<?php echo server_protocol() ?>://<?php echo $_SERVER['SERVER_NAME'] ?>/";
	var	__ec_site	= 'https://192.168.25.200/narutogame_r11/';
	var	_strings		= [];
	_strings['fechar']	= '<?php echo t('botoes.fechar') ?>';
	_strings['carregar_itens']	= '<?php echo t('botoes.carregar_itens') ?>';
	_strings['g1']	= '<?php echo t('geral.g1') ?>';
	_strings['g2']	= '<?php echo t('geral.g2') ?>';
	_strings['g3']	= '<?php echo t('geral.g3') ?>';
	_strings['g4']	= '<?php echo t('geral.g4') ?>';
	_strings['g5']	= '<?php echo t('geral.g5') ?>';
	_strings['g6']	= '<?php echo t('geral.g6') ?>';
	_strings['g7']	= '<?php echo t('geral.g7') ?>';
	_strings['g8']	= '<?php echo t('geral.g8') ?>';
	_strings['g9']	= '<?php echo t('geral.g9') ?>';
	_strings['g10']	= '<?php echo t('geral.g10') ?>';

</script>
<!-- FIM POP-UP -->

<script src="js/AC_RunActiveContent.js" type="text/javascript"></script>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.3&appId=220419874734083";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

    <div id="top-container">
		<?php if($_SESSION['logado'] && $_SESSION['basePlayer']): ?>
			<?php
				// Remover isso em quando terminar o Round.
				$player_removed = $basePlayer->getPlayerRemoved($basePlayer->id);
				if($player_removed->removido || $player_removed->banido){
					session_destroy();
				}
			?>
			<div id="background-topo" style="background-image:url(<?php echo player_tema($basePlayer) ?>);"></div>
		<?php else:?>
			<div id="background-topo2"></div>
		<?php endif;?>
		<div id="topo-info" style="background:url('<?php echo img('layout/' . (!$_SESSION['logado'] ? 'topo-info' : 'topo-info2') . '.png'); ?>'); background-repeat: no-repeat;">
			<div class="flag">
				<div style="float: left; margin-left:5px"><img data-lang="br" src="<?php echo img()?>layout/br.png" alt="Naruto Game - Versão em Português"/></div>
				<div style="float: left; margin-left:5px"><img data-lang="en" src="<?php echo img()?>layout/us.png" alt="Naruto Game - English Version"/></div>
            </div>
			<?php
			if(isset($_GET['le']) && $_GET['le']) {
				switch($_GET['le']) {
					case 1:
						$msg = t('templates.t1');
						break;
					case 2:
						$msg = t('templates.t2');
						break;
					case 3:
						$msg = t('templates.t3');
						break;
					case 4:
						$msg = t('templates.t3');
						break;
					case 5:
						$msg = "Sua conta foi banida por infrigir regras do jogo.";
						break;
					case 6:
						$msg = "Não é permitido o uso de Proxy e/ou VPN!";
						break;
				}
			}
			?>
			<?php
				if(isset($msg) && $msg){
			?>
			<div style='position: relative; width: 200px; background: #7B1315; color: white;font-weight: bold; text-align: center; padding: 10px; float: left; top: 45px; left: 3px;}'>
			<?php echo $msg?>
			</div>
			<?php
				}
			?>
			<?php if(!$_SESSION['logado']): ?>
				<div id="formulario">
					<form method="post" action="?acao=login" onsubmit="return false;" name="fIndexLogin" id="fIndexLogin">
						<input type="hidden" name="cookie" value="" />
						<div class="form-group">
							<input type="text" id="email" name="email" placeholder="<?php echo t('geral.email')?>" />
							<input type="password" id="senha" name="senha" placeholder="<?php echo t('cadastro.ca6')?>" />
						</div>
						<div class="form-group" id="captcha-container">
							<input type="text" name="captcha" maxlength="3" id="captcha" class="inputColorido" size="4" placeholder="<?php echo t('templates.t6')?>" />
						</div>
						<img id="captcha-image" src="?acao=captcha&s" alt="<?php echo t('templates.t6')?>" title="<?php echo t('templates.t6')?>"/>
						<input type="image" src="<?php echo img();?>layout/botoes/jogar.png" onclick="indexLogin()" style="height: 31px; margin-top: -8px"/>
						<div id="social-login">
							<a class="btn-facebook" href="javascript:;" onclick="location.href='<?php echo $loginUrl ?>'"><?php echo t('botoes.jogar')?></a>
						</div>
						<div class="break"></div>
					</form>
					<div id="recover-password">
						<a href="?secao=recuperar_senha"><?php echo t('templates.t4')?></a>
					</div>
				</div>

				<script type="text/javascript" src="js/el_cookie.js"></script>
				<script type="text/javascript">
					(function () {
						function S4() {
						   return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
						}

						function guid() {
						   return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
						}
						var	ec	= new EverlastCookie({debug: true});

						ec.get('nggx_r11_ec_id', function (value) {
							console.log(value);

							if (!value || value == '<br />') {
								value	= guid();

								ec.set('nggx_r11_ec_id', value);
							}

							$('#fIndexLogin [name=cookie]').val(value);
						});
					})();
				</script>
				<div id="social" style="position:absolute; right:90px; margin-top:-5px">
					<div style="float: left; padding-top: 4px"><b class="amarelo">SIGA-NOS</b></div>
					<div style="float: left; padding-left: 10px">
						<a href="https://www.facebook.com/narutogamebr" target="_blank"><img src="<?php echo img('layout/social/facebook.png')?>" border="1" style="border:1px solid #2e2d2d"/></a>
						<a href="https://twitter.com/NarutoGame" target="_blank"><img src="<?php echo img('layout/social/twitter.png')?>" border="1" style="border:1px solid #2e2d2d"/></a>
						<a href="https://plus.google.com/+NarutogameBr/posts" target="_blank"><img src="<?php echo img('layout/social/googleplus.png')?>" border="1" style="border:1px solid #2e2d2d"/></a>
						<a href="https://www.youtube.com/animegamevideos" target="_blank"><img src="<?php echo img('layout/social/youtube.png')?>" border="1" style="border:1px solid #2e2d2d"/></a>
					</div>
				</div>
				<div id="logo-deslogado"><a href="https://narutogame.com.br"><img src="<?php echo img('layout/logo.png') ?>" border="0"/></a></div>

			<?php else:?>
				<div id="duracao-logado" <?php if((LAYOUT_TEMPLATE == '_azul') && ($am_i_on_battle)): ?>style="display: none;"<?php endif ?>>
					 <?php $qDias = Recordset::query("SELECT DATEDIFF((SELECT valor FROM flags WHERE id=2), NOW()) AS dias")->row_array(); ?>
					 <div class="<?php echo LAYOUT_TEMPLATE == "_azul" ? "vermelho": "amarelo"?>" style="position: relative; padding-top: 40px; font-size:12px; padding-left: 26px;">
					 	<?php echo t('templates.t5')?>: <b><?php echo $qDias['dias'] ?> <?php echo t('templates.t8')?></b><br />
					 	<?php if(LAYOUT_TEMPLATE == "_azul" && $basePlayer){?>
							<?php
								 if($basePlayer->getAttribute('vip')):
										$linkVip = "?secao=jogador_vip";
								 else:
										$linkVip = "?secao=vantagens";
								 endif;
							 ?>
							<a href="<?php echo $linkVip ?>" title="<?php echo t('topo.vip_tooltip') ?>" class="linkTopo"><?php echo t('topo.vip') ?> <?php echo $basePlayer->getAttribute('coin'); ?></a>
						<?php }?>
					 </div>

                </div>
				<?php
					$uNews = Recordset::query("select id from noticia ORDER BY id desc LIMIT 1")->row_array();
				?>
				<div id="aviso-logado">
					<p style="width: 220px; margin-left: 66px; margin-top: 18px; text-align: center">
						Bem vindo ao Round Oficial 30!<br />Fique ligado na última  <a class="laranja" href="?secao=ler_noticia&id=<?php echo $uNews['id']?>">Notícia!</a>
					</p>
				</div>

				<?php if($_SESSION['basePlayer']):?>
				<?php
					 if($basePlayer->getAttribute('vip')):
							$linkVip = "?secao=jogador_vip";
					 else:
							$linkVip = "?secao=vantagens";
					 endif;
				 ?>
				<div onclick="location.href='<?php echo $linkVip ?>'" id="vip-logado" style="cursor: pointer; <?php echo ($basePlayer->getAttribute('vip') ? "background-image:url(".img('layout/topo-vip2.png').")" : "background-image:url(".img('layout/topo-vip.png').")") ?>" >
					<div style="float:left; font-size:11px; color:#6491a3; margin: 32px 0 0 190px;">
						<span style="position: relative; top: -8px; font-size: 12px">
							<a href="<?php echo $linkVip ?>" title="<?php echo t('topo.vip_tooltip') ?>" class="linkTopo"><?php echo t('topo.vip') ?> <?php echo $basePlayer->getAttribute('coin'); ?></a>
						</span>
					</div>
				</div>
				<?php endif;?>
                <div id="logo-logado">
                	<a href="https://narutogame.com.br"><img src="<?php echo img('layout/logo-logado.png') ?>" border="0" class="logo-ng-topo"/></a>
				</div>
				<?php if($_SESSION['basePlayer']):?>
					<div id="graduacao-logado">
					<a href="?secao=graduacoes"><img src="<?php echo img('layout/graduacoes/'.$basePlayer->id_vila.'-'.$basePlayer->id_graduacao.'.png') ?>" border="0" /></a>
						<p></p>
					</div>
				<?php endif;?>
			<?php endif; ?>
		</div>

		<?php if(!$basePlayer): ?>
			<div id="topo">
				<div id="info-game">
					<h1><span class="amarelo" style="font-size:14px">NARUTO GAME</span><br /><span style="color:#FFF"><?php echo t('templates.t12')?></span></h1>
					<h2><?php echo t('templates.t49')?></h2>
				</div>
				<div id="menu-container"></div>
			</div>
		<?php else: ?>
			<div id="topo2">
				<div id="menu-bg-overlay" <?php if(LAYOUT_TEMPLATE != '_azul'): ?>style="background-image:url(<?php echo player_tema($basePlayer) ?>);<?php endif ?>"></div>
				<div id="menu-container">
					<div id="menu-character-topo">
						<div id="pIcones">
							<!-- Reloginho -->
							<div class="i" id="topClock">
								<div class="t">
									<b class="azul"><?php echo t('templates.t19')?></b><br /><br />
									<?php
										$now		= new DateTime();
										$healing	= $now->diff(new DateTime(date('Y-m-d H:i:s', strtotime('+118 second', strtotime($basePlayer->last_healed_at)))));

										$npc_vila	= cron_next_run('0', '15');
										$ranking_eq	= cron_next_run('0', '4');
										$ranking_or	= cron_next_run('0', '*/4');
										$bijuu		= cron_next_run('30', '23');
										$heal_npc_v	= cron_next_run('0', '*/2');
										$midnight	= cron_next_run('0', '0');
									?>
										<b class="azul">Healing: <span id="d-healing-timer"></span></b><br />
										<?php echo t('templates.t220')?> <?php echo (!$basePlayer->hasItem(array(21770)) ? "20%" : "30%")?> <?php echo t('templates.t221')?><br />
										<?php echo t('templates.t20')?>
									<script>
										cronTimer(<?php echo $healing->h ?>, <?php echo $healing->i ?>, <?php echo $healing->s ?>, 0, 2, 'd-healing-timer');
										cronTimer(<?php echo $npc_vila['h'] ?>, <?php echo $npc_vila['m'] ?>, <?php echo $npc_vila['s'] ?>, 0, 24, 'd-npc-vila-timer');
										cronTimer(<?php echo $ranking_eq['h'] ?>, <?php echo $ranking_eq['m'] ?>, <?php echo $ranking_eq['s'] ?>, 0, 24, ['d-ranking1-eqp-timer', 'd-ranking2-eqp-timer','d-ranking3-eqp-timer']);
										cronTimer(<?php echo $ranking_or['h'] ?>, <?php echo $ranking_or['m'] ?>, <?php echo $ranking_or['s'] ?>, 0, 4, 'd-ranking-org-timer');
										cronTimer(<?php echo $bijuu['h'] ?>, <?php echo $bijuu['m'] ?>, <?php echo $bijuu['s'] ?>, 0, 24, ['d-bijuu-timer', 'd-bijuu-timer2']);
										cronTimer(<?php echo $bijuu['h'] ?>, <?php echo $bijuu['m'] ?>, <?php echo $bijuu['s'] ?>, 0, 24, ['d-bijuu-timer', 'd-bijuu-timer3']);
										cronTimer(<?php echo $heal_npc_v['h'] ?>, <?php echo $heal_npc_v['m'] ?>, <?php echo $heal_npc_v['s'] ?>, 0, 2, 'd-heal-npc-vila-timer');
										cronTimer(<?php echo $midnight['h'] ?>, <?php echo $midnight['m'] ?>, <?php echo $midnight['s'] ?>, 0, 24, ['d-midnight1-timer','d-midnight2-timer' ]);
									</script>
								</div>
							</div>
							<!-- Reloginho -->
							<?php
								$validadePromocao = Recordset::query('SELECT * FROM coin_dobro WHERE NOW() BETWEEN data_ini AND data_fim')->row_array();
							?>
							<div id="topAdvantages">

							<?php if($basePlayer->hasItem(array(20291)) || $basePlayer->hasItem(array(1863,1862)) || $basePlayer->hasItem(array(1007, 1008, 1009)) || $basePlayer->hasItem(array(1026, 20265, 20794)) || $basePlayer->hasItem(array(1027, 1079, 1080)) || $validadePromocao || $basePlayer->id_profissao_ativa): // Ticket do hospital ?>
								<div class="i">
                                <img src="<?php echo img() ?>/layout/topo-logado/icones/kage.png" border="0" width="24" />
									 <div class="t">

										<?php if ($basePlayer->id_profissao_ativa): ?>
											<b class="azul"><?php echo Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM profissao WHERE id=' . $basePlayer->id_profissao_ativa)->row()->nome ?></b>
											<br />
											<?php echo t('profissao.ativas.p' . $basePlayer->id_profissao_ativa) ?>
											<br /><br />
										<?php endif ?>

									<!-- VANTAGENS VIPS -->
									<!-- Creditos VIPS -->
										<?php if($validadePromocao){?>
											<b class="azul"><?php echo t("geral.g107");?></b><br />
											<?php echo t("geral.g108");?> <?= date("d/m/Y", strtotime($validadePromocao['data_fim'])) . " &agrave;s " . date("H:i:s", strtotime($validadePromocao['data_fim']));?></span><br /><br />
										<?php }?>
									<!-- Creditos VIPS -->
									<?php if($basePlayer->hasItem(array(1863))):?>
										<strong class="azul"><?php echo t("geral.g98");?></strong><br />

										<?php if(gHasItemW(1863, $basePlayer->id, NULL, 24)): ?>

										<?php
											$ult_uso	= Recordset::query('SELECT data_uso FROM player_item WHERE id_player=' . $basePlayer->id . ' AND id_item=1863')->row()->data_uso;
											$diff		= get_time_diff(date('Y-m-d H:i:s', strtotime('+24 hour', strtotime($ult_uso))));

											$uso = array(
												'd'	=> floor($diff['h'] / 24),
												'h'	=> $diff['h'] % 24,
												'm' => $diff['m']
											);
										?>
											<?php echo t("geral.g104");?> <span class="laranja"><?php echo $uso['d'] ?> <?php echo t("geral.g95");?>, <?php echo $uso['h'] ?> <?php echo t("geral.g96");?>, <?php echo $uso['m'] ?> <?php echo t("geral.g97");?></span>.<br /><br />
										<?php else: ?>

										<?php
											$ult_uso	= Recordset::query('SELECT data_uso FROM player_item WHERE id_player=' . $basePlayer->id . ' AND id_item=1863')->row()->data_uso;
											$future		= strtotime('+ '.(22 * 24).' hour', strtotime($ult_uso));
											$diff		= get_time_diff(date('Y-m-d H:i:s', $future));

											$uso = array(
												'd'	=> floor($diff['h'] / 24),

												'h'	=> $diff['h'] % 24,

												'm' => $diff['m']

											);
										?>
											<?php if($ult_uso=="" || now() > $future){?>
												<?php echo t("geral.g105");?> <br /><br />

											<?php }else{ ?>
												<?php echo t("geral.g106");?>  <span class="laranja"><?php echo $uso['d'] ?> <?php echo t("geral.g95");?>, <?php echo $uso['h'] ?> <?php echo t("geral.g96");?>, <?php echo $uso['m'] ?> <?php echo t("geral.g97");?></span>.<br /><br />

										<?php 	}

										?>
										<?php endif; ?>
									<?php endif; ?>
									<?php if($basePlayer->hasItem(array(1862))):?>
										<strong class="azul"><?php echo t("geral.g99");?></strong><br />

										<?php if(gHasItemW(1862, $basePlayer->id, NULL, 24)): ?>

										<?php
											$ult_uso	= Recordset::query('SELECT data_uso FROM player_item WHERE id_player=' . $basePlayer->id . ' AND id_item=1862')->row()->data_uso;
											$diff		= get_time_diff(date('Y-m-d H:i:s', strtotime('+24 hour', strtotime($ult_uso))));

											$uso = array(
												'd'	=> floor($diff['h'] / 24),
												'h'	=> $diff['h'] % 24,
												'm' => $diff['m']
											);
										?>
											<?php echo t("geral.g101");?>  <span class="laranja"><?php echo $uso['d'] ?> <?php echo t("geral.g95");?>, <?php echo $uso['h'] ?> <?php echo t("geral.g96");?>, <?php echo $uso['m'] ?> <?php echo t("geral.g97");?></span>.<br /><br />
										<?php else: ?>

										<?php
											$ult_uso	= Recordset::query('SELECT data_uso FROM player_item WHERE id_player=' . $basePlayer->id . ' AND id_item=1862')->row()->data_uso;
											$future		= strtotime('+ '.(22 * 24).' hour', strtotime($ult_uso));
											$diff		= get_time_diff(date('Y-m-d H:i:s', $future));

											$uso = array(
												'd'	=> floor($diff['h'] / 24),
												'h'	=> $diff['h'] % 24,
												'm' => $diff['m']
											);
										?>
											<?php if($ult_uso=="" || now() > $future){?>
												<?php echo t("geral.g102");?> <br /><br />

											<?php }else{ ?>
												<?php echo t("geral.g103");?> <span class="laranja"><?php echo $uso['d'] ?> <?php echo t("geral.g95");?>, <?php echo $uso['h'] ?> <?php echo t("geral.g96");?>, <?php echo $uso['m'] ?> <?php echo t("geral.g97");?></span>.<br /><br />

										<?php 	}

										?>
										<?php endif; ?>
									<?php endif; ?>
									<?php if($basePlayer->hasItem(array(20291))):?>
										<strong class="azul"><?php echo t("geral.g100");?></strong><br />

										<?php if(gHasItemW(20291, $basePlayer->id, NULL, 24)): ?>

										<?php
											$ult_uso	= Recordset::query('SELECT data_uso FROM player_item WHERE id_player=' . $basePlayer->id . ' AND id_item=20291')->row()->data_uso;
											$diff		= get_time_diff(date('Y-m-d H:i:s', strtotime('+24 hour', strtotime($ult_uso))));

											$uso = array(
												'd'	=> floor($diff['h'] / 24),
												'h'	=> $diff['h'] % 24,
												'm' => $diff['m']
											);
										?>
											<?php echo t("geral.g92");?>  <span class="laranja"><?php echo $uso['d'] ?> <?php echo t("geral.g95");?>, <?php echo $uso['h'] ?> <?php echo t("geral.g96");?>, <?php echo $uso['m'] ?> <?php echo t("geral.g97");?></span>.<br /><br />
										<?php else: ?>

										<?php
											$ult_uso	= Recordset::query('SELECT data_uso FROM player_item WHERE id_player=' . $basePlayer->id . ' AND id_item=20291')->row()->data_uso;
											$future		= strtotime('+ '.(21 * 24).' hour', strtotime($ult_uso));
											$diff		= get_time_diff(date('Y-m-d H:i:s', $future));

										$uso = array(
												'd'	=> floor($diff['h'] / 24),
												'h'	=> $diff['h'] % 24,
												'm' => $diff['m']
											);
										?>
											<?php if($ult_uso=="" || now() > $future){?>
												<?php echo t("geral.g93");?> <br /><br />

											<?php }else{ ?>
												<?php echo t("geral.g94");?>  <span class="laranja"><?php echo $uso['d'] ?> <?php echo t("geral.g95");?>, <?php echo $uso['h'] ?> <?php echo t("geral.g96");?>, <?php echo $uso['m'] ?> <?php echo t("geral.g97");?></span>.<br /><br />

										<?php 	}

										?>
										<?php endif; ?>
									<?php endif; ?>
									<?php if($basePlayer->hasItem(array(1007, 1008, 1009))): // Ticket do hospital ?>
											<strong class="azul"><?php echo t('templates.t31')?></strong><br />
											<?php echo t('templates.t32')?>:
											<span class="verde">
											<?php
												$i = $basePlayer->getVIPItem(array(1007, 1008, 1009));
												echo $i['vezes'] - $i['uso'];
											?> <?php echo t('conquistas.c31')?>
											</span>
											<br /><br />
									<?php endif; ?>


									<?php /*if($basePlayer->hasItem(array(1026, 20265, 20794))): // Quest Helper ?>

											<strong class="azul"><?php echo t('templates.t33')?></strong><br />

											<?php if($basePlayer->hasItem(1026)): ?>
												<?php echo t('templates.t34')?><br />
											<?php endif; ?>
											<?php if($basePlayer->hasItem(20265)): ?>
												<?php echo t('templates.t35')?><br />
											<?php endif; ?>
											<?php if($basePlayer->hasItem(20794)): ?>
												<?php echo t('templates.t36')?><br />
											<?php endif; ?>

										 <br />

									<?php endif;*/ ?>

									 <?php if($basePlayer->hasItem(array(1027, 1079, 1080))): // Bingo Book ?>
										<strong class="azul"><?php echo t('templates.t37')?></strong><br />
											<?php echo t('templates.t38')?>:
											<span class="verde">
											<?php
												$i = $basePlayer->getVIPItem(array(1027, 1079, 1080));
												echo $i['vezes'] - $i['uso'];
											?> <?php echo t('conquistas.c31')?>
											</span><br /><br />

									<?php endif; ?>

									 <?php if($basePlayer->hasItem(array(21879))): // Bingo Book ?>
										<?php
											$item	= Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM item WHERE id=21879')->row()->nome;
										?>
										<strong class="azul"><?php echo sprintf(t('templates.t71'), $item) ?></strong><br />
										<?php echo sprintf(t('templates.t72'), $basePlayer->getItem(21879)->uso )?><br /><br />
									<?php endif; ?>
									   </div>
									</div>
								<?php else:?>

								<div class="i">
                                <img src="<?php echo img() ?>/layout/topo-logado/icones/kage.png" border="0" width="24" />
									 <div class="t">
									 	<?php echo t("geral.g91");?>
									 </div>
								</div>
								<?php endif; ?>
								<!-- VANTAGENS VIPS -->
								</div>
								<!-- Reloginho -->
								<?php $playerFidelityTopo = Recordset::query("select * from player_fidelity WHERE id_player=".$basePlayer->id)->row_array(); ?>
								<div id="topFidelity">
									<div class="i">
									   <img src="<?php echo img() ?>layout/icones/<?php echo $playerFidelityTopo['reward'] ? "gift2.png" : "gift.png"?>" border="0" width="20" />
										<div class="t">
											<b class="azul"><?php echo t("fidelity.title")?></b><br />
											<?php echo $playerFidelityTopo['reward'] ? t("fidelity.msg1") : t("fidelity.msg2");?>
										</div>
									</div>
								</div>
							<!-- Reloginho -->
							</div>
							<!-- Friends -->
							<?php	$pendents = Recordset::query("SELECT count(id) as total FROM player_friend_requests WHERE id_friend=".$basePlayer->id)->result_array();?>
							<div id="topFriends">
								<a href="?secao=lista_amigos"><img src="<?php echo img() ?>layout/icones/friend.png" border="0" width="18" style="position:relative; top: 4px" /></a>
								<?php if($pendents[0]['total'] > 0){?>
								<div style="border: solid 2px #FFF; background-color: #DE1010; color: #FFF; border-radius: 10px; width: 20px; height: 20px; padding-top: 2px; position: absolute;right: -7px;top: -9px; text-align: center;">
									<?php echo $pendents[0]['total'] > 9 ? '9+' : $pendents[0]['total'] ?>
								</div>
								<?php }?>
							</div>
							<!-- Friends -->
							<!-- Mensageiro -->
							<div id="topMessages">
							<?php
								$msgTotal	= Recordset::query('SELECT COUNT(id) AS total FROM mensagem WHERE lida=0 AND removida="0" AND id_para=' . $basePlayer->id)->row_array();
								$msgTotalG	= Recordset::query('
									SELECT
										SUM(1) AS total,
										SUM((CASE WHEN b.id_player IS NULL THEN 0 ELSE 1 END)) as lidas
									FROM
										mensagem_global a LEFT JOIN mensagem_global_lida b ON b.id_mensagem_global=a.id AND b.id_player=' . $basePlayer->id)->row_array();

								$msgTotalV	= Recordset::query('
									SELECT
										SUM(1) AS total,
										SUM((CASE WHEN b.id_player IS NULL THEN 0 ELSE 1 END)) as lidas
									FROM
										mensagem_vila a LEFT JOIN mensagem_vila_lida b ON b.id_mensagem_vila=a.id AND b.id_player=' . $basePlayer->id . '

									WHERE
										a.id_vila=' . $basePlayer->id_vila . ' AND
										a.id NOT IN(SELECT id_mensagem_vila FROM mensagem_vila_removida WHERE id_player=' . $basePlayer->id . ')')->row_array();

								$msgTotal['total'] += ($msgTotalG['total'] - $msgTotalG['lidas']) + ($msgTotalV['total'] - $msgTotalV['lidas']);
							?>
							<?php if($msgTotal['total']):?>
								<div style="position: relative; float: left">
									<a href="?secao=mensagens">
										<img src="<?php echo img('layout/topo-logado/icones/mensageiro2.png')?>" border="0" />
										<div style="border: solid 2px #FFF; background-color: #DE1010; color: #FFF; border-radius: 10px; width: 20px; height: 20px; padding-top: 2px; position: absolute;right: -7px;top: -9px; text-align: center;">
											<?php echo $msgTotal['total'] > 9 ? '9+' : $msgTotal['total'] ?>
										</div>
									</a>
								</div>
							<?php else: ?>
								<a href="?secao=mensagens"><img src="<?php echo img('layout/topo-logado/icones/mensageiro2.png')?>" border="0" /></a>
							<?php endif; ?>
							</div>
							<!-- Mensageiro -->
							<!-- Logout -->
							<div id="topLogout">
								<?php $link_logout	= menu_conds(Recordset::query('SELECT * FROM menu WHERE id=21', true)->row_array(), $basePlayer) ? 'javascript:logoff()' : '';?>
								<?php if($link_logout){?>
									<a href="<?php echo $link_logout ?>"><img src="<?php echo img('layout/topo-logado/icones/logout.png')?>" height="20" alt="Logout"/></a>
								<?php } ?>
							</div>
							<!-- Logout -->
					</div>
					<div id="informacoes">
                    <?php if(LAYOUT_TEMPLATE == '_azul'): ?>
                    <div id="character-info">
                        <div class="name"><?php echo $basePlayer->nome ?></div>
                        <div class="headline"><?php echo $basePlayer->nome_titulo ?></div>
                    </div>
                    <?php endif; ?>
						<div id="level-start">
							<span>LVL</span>
							<?php echo $basePlayer->getAttribute('level') ?>
						</div>
						<div id="barra_exp">
						<?php $exp_max = Player::getNextLevel($basePlayer->level); ?>
							<?php echo barra_exp_topo($exp_max, $basePlayer->getAttribute('exp'), 'exp') ?>
						</div>
						<div id="level-end">
							<span>LVL</span>
							<?php echo $basePlayer->getAttribute('level') + 1 ?>
						</div>
						<div class="ryou" id="cnPRYt"><?php echo (int)$basePlayer->getAttribute('ryou') ?>
						</div>
					</div>
					<div id="icones">
						<div class="miniIcones">
							<div class="icon hp"></div>
							<div class="iconeTxt" id="cnPHPt">
								<?php echo $basePlayer->getAttribute('hp') <= 0 ? 1 : $basePlayer->getAttribute('hp')  ?>/<?php echo $basePlayer->getAttribute('max_hp') ?>
								<?php echo barra_exp_topo($basePlayer->getAttribute('max_hp'), $basePlayer->getAttribute('hp'), 'vida') ?>
							</div>
						</div>
						<div class="miniIcones">
							<div class="icon cha"></div>
							<div class="iconeTxt" id="cnPSPt">
								<?php echo $basePlayer->getAttribute('sp') <= 0 ? 1 : $basePlayer->getAttribute('sp') ?>/<?php echo $basePlayer->getAttribute('max_sp') ?>
								<?php echo barra_exp_topo($basePlayer->getAttribute('max_sp'), $basePlayer->getAttribute('sp'), 'chakra') ?>
							</div>
						</div>
						<div class="miniIcones">
							<div class="icon sta"></div>
							<div class="iconeTxt" id="cnPSTAt">
								<?php echo $basePlayer->getAttribute('sta') <= 0 ? 1 : $basePlayer->getAttribute('sta') ?>/<?php echo $basePlayer->getAttribute('max_sta') ?>
								<?php echo barra_exp_topo($basePlayer->getAttribute('max_sta'), $basePlayer->getAttribute('sta'), 'stamina') ?>
							</div>
						</div>
					</div>
					<ul id="loggedin-menu">
						<?php foreach($global_menu_top as $menu_item): ?>
							<?php
								if($menu_item['id'] == 1) {
									continue;
								}

								$has_submenu	= is_array($menu_item['items']) && sizeof($menu_item['items']);

								if($menu_item['id'] == 11) {
									$menu_item['id']	= $basePlayer->id_vila . '-' . $menu_item['id'];
								}
							?>
							<li class="category">
								<a class="category-data <?php echo $has_submenu ? '' : 'disabled' ?>">
									<img src="<?php echo img('layout/categorias_topo/' . $menu_item['id'] . ($has_submenu ? '' : '_disabled') . '.png') ?>">
									<?php echo t($menu_item['item']) ?>
								</a>
								<?php if($has_submenu): ?>
									<ul class="submenu">
										<?php foreach($menu_item['items'] as $menu_subitem): ?>
											<li><a href="<?php echo (isset($menu_subitem['externo']) && $menu_subitem['externo'] ? "": "?secao=") . $menu_subitem['href'] ?>"><?php echo $menu_subitem['item'] ?></a></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
						<li class="category inventory-container">
							<a class="category-data">
								<img src="<?php echo img() ?>layout/topo-logado/icones/bag.png" class="inventory-trigger" alt="<?php echo t('templates.t22')?>" border="0" />
								<?php echo t('menus.m15') ?>
							</a>
						<ul>
								<li>
									<div data-sell-confirm="<?php echo t('actions.a281') ?>" class="arrow_box t inventory-data" data-default="<?php echo t('templates.t21')?>"></div>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>

		<?php endif; ?>
    </div>
	<div id="conteudo">
		<div id="pagina" <?php if ($_SESSION['basePlayer']): ?>style="padding-top: 70px"<?php else: ?>style="padding-top: 50px"<?php endif ?>>
			<div id="colunas" <?php echo $am_i_on_battle ? 'style="background-image: none !important"' : '' ?>>
				<?php if (!$am_i_on_battle): ?>
					<div id="menu_fundo"></div>
				<?php endif ?>