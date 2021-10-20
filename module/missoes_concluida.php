<div class="titulo-secao">
	<p><?php echo t('titulos.missoes_concluida') ?></p>
</div>
<script type="text/javascript">
	var _h				= 0;
	var _m				= 0;
	var _s				= 0;
	
	var _r 				= 0;
	var _i				= 0;
	var	_should_pause	= true;
</script> 
<script type="text/javascript" src="js/missoes_concluida.js"></script>
<?php if ($basePlayer->id_missao == -1): ?>
<?php
		$serialized	= unserialize(Player::getFlag('missao_tempo_vip', $basePlayer->id));
		$exp		= 0;
		$rep		= 0;
		$ryou		= 0;
	?>
<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/4.png); background-repeat: no-repeat;">
			<?php foreach ($serialized as $key => $value): ?>
			<?php
					if(!is_numeric($key)) {
						continue;
					}
					
					$quest			= Recordset::query('SELECT * FROM quest WHERE id=' . $value['quest'])->row_array();
					$quest['ryou']	+= percent($basePlayer->getAttribute('inc_ryou') + $basePlayer->bonus_vila['sk_missao_ryou'], $quest['ryou']);
					$quest['exp']	+= percent($basePlayer->bonus_vila['sk_missao_exp'], $quest['exp']);

					switch($value['duration']) {
						case 1:
							$mul = 1;
							
							break;
						case 2:
							$mul = 2;
							
							break;
						case 3:
							$mul = 4;
							
							break;
						case 4:
							$mul = 8;
							
							break;
						case 5:
							$mul = 12;
							
							break;
					}
					
					$exp	+= $quest['exp'] * $mul;
					$rep	+= $quest['reputacao'] * $mul;
					$ryou	+= $quest['ryou'] * $mul;
				?>
			<b><?php echo $quest['nome_' . Locale::get()] ?></b>
			<hr style="clear: both; border:#413128 1px solid" />
			<br />
			<?php endforeach ?>
			<span class="cinza"><?php echo t('missoes_status.missao_completa2');?>:</span><br />
			<strong class="verde"><?php echo round($exp) ?> <?php echo t('geral.pontos_exp') ?></strong><br />
			<strong class="azul">RY$ <?php echo sprintf("%1.2f", round($ryou)) ?></strong><br />
			<strong class="azul"><?php echo t('geral.rep') ?> <?php echo $rep ?></strong> <br />
			<br />
			<a class="button" onclick="doFinalizaMissaso('<?php echo $basePlayer->getAttribute('id_missao') ?>');"><?php echo t('botoes.finalizar')?></a><br />
		</div>
	</div>
</div>
<?php else: ?>
<?php
		$redir_script = true;

		if(!$basePlayer->getAttribute('id_missao')) {
			redirect_to("negado");
		}

		$r			= Recordset::query('SELECT * FROM quest WHERE id=' . $basePlayer->getAttribute('id_missao'), true)->row_array();
		$rq			= Recordset::query('SELECT multiplicador FROM player_quest WHERE id_quest=' . $basePlayer->getAttribute('id_missao') . ' AND id_player=' . $basePlayer->id)->row_array();

		$r['ryou']	+= percent($basePlayer->getAttribute('inc_ryou') + $basePlayer->bonus_vila['sk_missao_ryou'], $r['ryou']);
		$r['exp']	+= percent($basePlayer->bonus_vila['sk_missao_exp'], $r['exp']);
	?>
<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/4.png); background-repeat: no-repeat;"> <b><?php echo t('missoes_status.missao_completa1');?></b><br />
			<p><?php echo $r['texto_conclusao_' . Locale::Get()] ?><br />
				<br />
				<?php echo t('missoes_status.missao_completa2');?>:<br />
				<strong class="verde"><?php echo round($r['exp'] * $rq['multiplicador']) ?></strong> <?php echo t('geral.pontos_exp') ?><br />
				<strong class="azul">RY$ <?php echo sprintf("%1.2f", round($r['ryou'] * $rq['multiplicador'])) ?></strong></p>
			<br />
			<input type="button" class="button" value="<?php echo t('botoes.finalizar')?>" onclick="doFinalizaMissaso('<?php echo $basePlayer->getAttribute('id_missao') ?>');" />
			<br />
		</div>
	</div>
</div>
<?php endif ?>
<br />
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
