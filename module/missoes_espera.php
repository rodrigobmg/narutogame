<?php if ($basePlayer->id_missao == -1): ?>
	<?php
		$serialized	= unserialize(Player::getFlag('missao_tempo_vip', $basePlayer->id));
		$diff		= get_time_difference(now(true), $serialized['finishing_time']);
	?>
	<?php if (now() >= strtotime($serialized['finishing_time'])): ?>
	<?php require 'missoes_concluida.php' ?>
	<?php else: ?>
	<div class="titulo-secao">
		<p><?php echo t('titulos.missoes_status') ?></p>
	</div>
	<div class="msg_gai">
		<div class="msg">
			<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/4.png); background-repeat: no-repeat;">
				<?php foreach ($serialized as $key => $value): ?>
				<?php
								if(!is_numeric($key)) {
									continue;
								}
									$quest	= Recordset::query('SELECT * FROM quest WHERE id=' . $value['quest'])->row_array();
							?>
				<b><?php echo $quest['nome_' . Locale::get()] ?></b>
				<hr style="clear: both; border:#413128 1px solid" />
				<br />
				<?php endforeach ?>
				<br />
				<span style="font-size:13px;" class="laranja"><?php echo t('missoes_status.conclusao'); ?> <span id="cnTImer">--:--:--</span></span><br />
				<br />
			</div>
		</div>
	</div>
	<br />
	<br />
	<a class="button" onclick="doCancelaMissao('')"><?php echo t('botoes.cancelar_missao') ?></a>
	<div align="center"> 
		<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "1857631774";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Missões -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
	</div>
	<script type="text/javascript">
				var _h 				= <?php echo $diff['hours'] + ($diff['days'] * 24) ?>;
				var _m				= <?php echo $diff['minutes'] ?>;
				var _s 				= <?php echo $diff['seconds'] ?>;
				
				var _r 				= 0;
				var _i 				= 0;
				var	_should_pause	= false;
			</script>
	<?php endif ?>
<?php else: ?>
	<?php
		$quest			= Recordset::query('SELECT * FROM quest WHERE id=' . $basePlayer->getAttribute('id_missao'), true)->row_array();
		$player_quest	= Recordset::query('SELECT * FROM player_quest WHERE id_quest=' . $basePlayer->getAttribute('id_missao') . ' AND id_player=' . $basePlayer->id)->row_array();
	?>
	<?php if($player_quest['completa']): ?>
		<?php require('missoes_concluida.php'); ?>
	<?php else: ?>
		<?php
			if(date('YmdHis') > date('YmdHis', strtotime($player_quest['data_conclusao']))) {
				$quest['exp']	= $quest['exp'] * $player_quest['multiplicador'];
				
				$quest['ryou']	= $quest['ryou'] * $player_quest['multiplicador'];
				$quest['ryou']	+= percent($basePlayer->getAttribute('inc_ryou') + $basePlayer->bonus_vila['sk_missao_ryou'], $quest['ryou']);
				$quest['exp']	+= percent($basePlayer->bonus_vila['sk_missao_exp'], $quest['exp']);
				
				Recordset::update('player', array(
					'exp'		=> array('escape' => false, 'value' => 'exp  + ' . $quest['exp']),
					'ryou'		=> array('escape' => false, 'value' => 'ryou + ' . $quest['ryou'])
				), array(
					'id'		=> $basePlayer->id
				));

				// Log
				Recordset::insert('player_recompensa_log', array(
					'id_player'	=> $basePlayer->id,
					'fonte'		=> 'quest_tempo',
					'exp'		=> $quest['exp'],
					'ryou'		=> $quest['ryou'],
					'recebido'	=> 1
				));
				
				// Reputação --->
					reputacao($basePlayer->id, $player_quest['id_vila'] ? $player_quest['id_vila'] : $basePlayer->id_vila, $quest['reputacao'] );
				// <---
				
				//equipe_exp(round(percent(3, $quest['exp'] * $player_quest['multiplicador'])));
				
				// Atualiza o rank das missões -->
					if(!Recordset::query('SELECT id FROM player_quest_status WHERE id_player=' . $basePlayer->id)->num_rows) {
						Recordset::insert('player_quest_status', array(
							'id_player'	=> $basePlayer->id
						));
					}
					
					switch($quest['id_rank']) {
						case 5:
							$field = "quest_s";
						
							break;
							
						case 4:
							$field = "quest_a";
						
							break;
							
						case 3:
							$field = "quest_b";
						
							break;
							
						case 2:
							$field = "quest_c";
						
							break;
							
						case 1:
							$field = "quest_d";
						
							break;
						
						default:
							$field = "tarefa";
							
					}
					
					if(!$quest['especial']) {
						Recordset::update('player_quest_status', array(
							$field		=> array('escape' => false, 'value' => $field . ' + 1')
						), array(
							'id_player'	=> $basePlayer->id
						));
					}
				// <---
				
				Recordset::update('player_quest', array(
					'completa'	=> '1'
				), array(
					'id_player'	=> $basePlayer->id,
					'id_quest'	=> $basePlayer->getAttribute('id_missao')
				));

				// Conquista --->
					arch_parse(NG_ARCH_QUEST, $basePlayer);
					arch_parse(NG_ARCH_SELF, $basePlayer);
				// <---

				require('missoes_concluida.php');
				
				$redir_script = true;
				redirect_to("missoes_espera");
				
				die();
			}
			
			$diff = get_time_difference(date('Y-m-d H:i:s'), $player_quest['data_conclusao']);	
		?>
	<script type="text/javascript">
			var _h 				= <?php echo $diff['hours'] ?>;
			var _m				= <?php echo $diff['minutes'] ?>;
			var _s 				= <?php echo $diff['seconds'] ?>;
			
			var _r 				= 0;
			var _i 				= 0;
			var	_should_pause	= false;
		</script>
	<div class="titulo-secao">
		<p><?php echo t('titulos.missoes_status') ?></p>
	</div>
	<div id="HOTWordsTxt" name="HOTWordsTxt">
		<div id="cnBase" class="direita">
			<div class="msg_gai">
				<div class="msg">
					<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/4.png); background-repeat: no-repeat;"> <b><?php echo $quest['nome_' . Locale::get()] ?></b>
						<p> <?php echo $quest['descricao_' . Locale::get()] ?> <br />
							<br />
							<span style="font-size:13px;" class="laranja"><?php echo t('missoes_status.conclusao'); ?> <span id="cnTImer">--:--:--</span></span><br />
							<br />
						</p>
					</div>
				</div>
			</div>
			<div align="center"> 
				<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<!-- NG - Missões -->
				<ins class="adsbygoogle"
					 style="display:inline-block;width:728px;height:90px"
					 data-ad-client="ca-pub-9166007311868806"
					 data-ad-slot="1857631774"></ins>
				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
			</div>
		</div>
		<?php if($quest['imagem']):?>
		<br />
		<br />
		<p align="center"><img src="<?php echo img()?>layout/estudantes/<?php echo (Locale::get()=="br" ? $quest['imagem']."pt.jpg" : $quest['imagem']."en.jpg") ?>" /></p>
	<?php endif; ?>
	<div align="center"> <br />
		<a class="button" onclick="doCancelaMissao('')"><?php echo t('botoes.cancelar_missao') ?></a>
	</div>
</div>
<?php endif; ?>
<?php endif ?>
