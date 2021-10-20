<?php
	$players_to_vote	= Recordset::query('SELECT * FROM ranking WHERE id_vila=' . $basePlayer->id_vila . ' AND posicao_vila BETWEEN 2 AND 25 ORDER BY posicao_vila');
	$can_vote			= in_array(date('d'), array(1, 15));
	$types_to_ignore	= array();
	$should_ignore		= array();
	$voted				= Recordset::query('
		SELECT
			id_player_eleito,
			voto

		FROM
			vila_voto

		WHERE
			id_vila=' . $basePlayer->id_vila . ' AND
			id_usuario=' . $_SESSION['usuario']['id']);
	
	foreach($voted->result_array() as $vote) {
		$should_ignore[]	= $vote['id_player_eleito'];
		$types_to_ignore[]	= $vote['voto'];
	}

	$vote_village	= false;
	$vote_defense	= false;
	$vote_war		= false;
	
	$voted_village	= Recordset::query('
		SELECT * FROM ranking WHERE id_player=(
			SELECT id_player_eleito FROM (
				SELECT
					SUM(1),
					id_player_eleito 
				
				FROM
					vila_voto
				
				WHERE
					id_vila=' . $basePlayer->id_vila . ' AND
					voto="vila"
				
				GROUP BY 2 ORDER BY 1 DESC LIMIT 1
			) w
		)	
	');

	if($voted_village->num_rows) {
		$vote_village	= $voted_village->row_array();
	}

	$voted_defense	= Recordset::query('
		SELECT * FROM ranking WHERE id_player=(
			SELECT id_player_eleito FROM (
				SELECT
					SUM(1),
					id_player_eleito 
				
				FROM
					vila_voto
				
				WHERE
					id_vila=' . $basePlayer->id_vila . ' AND
					voto="defesa" ' . 
					($vote_village ? ' AND id_player_eleito!=' . $vote_village['id_player'] : '') . '
				
				GROUP BY 2 ORDER BY 1 DESC LIMIT 1
			) w
		)	
	');

	if($voted_defense->num_rows) {
		$vote_defense	= $voted_defense->row_array();
	}

	$voted_war	= Recordset::query('
		SELECT * FROM ranking WHERE id_player=(
			SELECT id_player_eleito FROM (
				SELECT
					SUM(1),
					id_player_eleito 
				
				FROM
					vila_voto
				
				WHERE
					id_vila=' . $basePlayer->id_vila . ' AND
					voto="guerra" ' . 
					($vote_village ? ' AND id_player_eleito!=' . $vote_village['id_player'] : '') .
					($vote_defense ? ' AND id_player_eleito!=' . $vote_defense['id_player'] : '') . '
				
				GROUP BY 2 ORDER BY 1 DESC LIMIT 1
			) w
		)	
	');

	if($voted_war->num_rows) {
		$vote_war	= $voted_war->row_array();
	}
?>
<style type="text/css">
	.player-box {
		float: left;
		width: 140px;
		margin: 20px
	}
</style>
<script type="text/javascript">
	$(document).ready(function () {
		$('.c-vila').on('click', function () {
			var	_	= $(this);
		
			if(_.hasClass('ui-state-disabled')) {
				return;
			}
		
			jconfirm('<?php echo t('votacao.v1')?>', null, function () {
				$('.c-vila').addClass('ui-state-disabled');
				$('.player-box-' + _.data('id') + ' .button').addClass('ui-state-disabled');
				
				$.ajax({
					url:		'?acao=votacao_vila',
					type:		'post',
					data:		{vote: 'vila', player: _.data('id')},
					dataType:	'json',
					success:	function (result) {
						if(!result.success) {
							jalert('Você já votou antes para esse cargo.');
						}
					}
				});
			});
		});

		$('.c-guerra').on('click', function () {
			var	_	= $(this);
		
			if(_.hasClass('ui-state-disabled')) {
				return;
			}
		
			jconfirm('<?php echo t('votacao.v2')?>', null, function () {
				$('.c-guerra').addClass('ui-state-disabled');
				$('.player-box-' + _.data('id') + ' .button').addClass('ui-state-disabled');

				$.ajax({
					url:	'?acao=votacao_vila',
					type:	'post',
					data:	{vote: 'guerra', player: _.data('id')},
					dataType:	'json',
					success:	function (result) {
						if(!result.success) {
							jalert('Você já votou antes para esse cargo,');
						}
					}
				});
			});
		});

		$('.c-defesa').on('click', function () {
			var	_	= $(this);
		
			if(_.hasClass('ui-state-disabled')) {
				return;
			}
		
			jconfirm('<?php echo t('votacao.v3')?>', null, function () {
				$('.c-defesa').addClass('ui-state-disabled');
				$('.player-box-' + _.data('id') + ' .button').addClass('ui-state-disabled');

				$.ajax({
					url:	'?acao=votacao_vila',
					type:	'post',
					data:	{vote: 'defesa', player: _.data('id')},
					dataType:	'json',
					success:	function (result) {
						if(!result.success) {
							jalert('Você já votou antes para esse cargo,');
						}
					}
				});
			});
		});
	});
</script>
<div class="titulo-secao"><p><?php echo t('titulos.votacao_vila')?></p></div>
<?php if($can_vote): ?>

<div style="width: 730px;" class="titulo-home"><p><span class="laranja">//</span> <?php echo t('votacao.v4')?> ...................................................... </p></div>
	
	<div style="width: 765px; position:relative; height: 220px">
		<?php if($vote_village): ?>
			<div style="float: left; width:190px">
				<div style="position: relative; padding-bottom: 5px; width: 182px; height:45px; background-image:url('<?php echo img('layout/vilas/bg-vila.png')?>'); background-repeat: no-repeat">
					<div class="azul" style="position:relative; top: 20px; font-size: 13px; left: 10px"><?php echo t('votacao.v5')?></div>
				</div>
				<div>
					<img src="<?php echo player_imagem($vote_village['id_player'], "pequena"); ?>"  />
				</div>
				<div style="padding-top: 5px;">
					<b style="font-size: 13px;"><?php echo $vote_village['nome'] ?></b><br />
					<b style="font-size: 13px;" class="laranja">Lvl. <?php echo $vote_village['level'] ?></b>
				</div>
			</div>
		<?php else: ?>
			<div style="float: left; width:190px">
				<div style="position: relative; padding-bottom: 5px; width: 182px; height:45px; background-image:url('<?php echo img('layout/vilas/bg-vila.png')?>'); background-repeat: no-repeat">
					<div class="azul" style="position:relative; top: 20px; font-size: 13px; left: 10px"><?php echo t('votacao.v5')?></div>
				</div>
				<div>
					<img src="<?php echo img('layout/4x4-nenhum.jpg')?>"  />
				</div>
				<div style="padding-top: 5px;">
					<b style="font-size: 13px;">?????</b><br />
					<b style="font-size: 13px;" class="laranja">Lvl. ???</b>
				</div>
			</div>
		<?php endif; ?>
		<?php if($vote_defense): ?>

			<div style="float: left; width:190px">
				<div style="position: relative; padding-bottom: 5px; width: 182px; height:45px; background-image:url('<?php echo img('layout/vilas/bg-defesa.png')?>'); background-repeat: no-repeat">
					<div class="azul" style="position:relative; top: 20px; font-size: 13px; left: 10px"><?php echo t('votacao.v6')?></div>
				</div>
				<div>
					<img src="<?php echo player_imagem($vote_defense['id_player'], "pequena"); ?>"  />
				</div>
				<div style="padding-top: 5px;">
					<b style="font-size: 13px;"><?php echo $vote_defense['nome'] ?></b><br />
					<b style="font-size: 13px;" class="laranja">Lvl. <?php echo $vote_defense['level'] ?></b>
				</div>
			</div>
		<?php else: ?>
			<div style="float: left; width:190px">
				<div style="position: relative; padding-bottom: 5px; width: 182px; height:45px; background-image:url('<?php echo img('layout/vilas/bg-defesa.png')?>'); background-repeat: no-repeat">
					<div class="azul" style="position:relative; top: 20px; font-size: 13px; left: 10px"><?php echo t('votacao.v5')?></div>
				</div>
				<div>
					<img src="<?php echo img('layout/4x4-nenhum.jpg')?>"  />
				</div>
				<div style="padding-top: 5px;">
					<b style="font-size: 13px;">?????</b><br />
					<b style="font-size: 13px;" class="laranja">Lvl. ???</b>
				</div>
			</div>
		<?php endif; ?>	
		<?php if($vote_war): ?>
			<div style="float: left; width:190px">
				<div style="position: relative; padding-bottom: 5px; width: 182px; height:45px; background-image:url('<?php echo img('layout/vilas/bg-guerra.png')?>'); background-repeat: no-repeat">
					<div class="azul" style="position:relative; top: 20px; font-size: 13px; left: 10px"><?php echo t('votacao.v7')?></div>
				</div>
				<div>
					<img src="<?php echo player_imagem($vote_war['id_player'], "pequena"); ?>"  />
				</div>
				<div style="padding-top: 5px;">
					<b style="font-size: 13px;"><?php echo $vote_war['nome'] ?></b><br />
					<b style="font-size: 13px;" class="laranja">Lvl. <?php echo $vote_war['level'] ?></b>
				</div>
			</div>
		<?php else: ?>
			<div style="float: left; width:190px">
				<div style="position: relative; padding-bottom: 5px; width: 182px; height:45px; background-image:url('<?php echo img('layout/vilas/bg-guerra.png')?>'); background-repeat: no-repeat">
					<div class="azul" style="position:relative; top: 20px; font-size: 13px; left: 10px"><?php echo t('votacao.v5')?></div>
				</div>
				<div>
					<img src="<?php echo img('layout/4x4-nenhum.jpg')?>"  />
				</div>
				<div style="padding-top: 5px;">
					<b style="font-size: 13px;">?????</b><br />
					<b style="font-size: 13px;" class="laranja">Lvl. ???</b>
				</div>
			</div>
		<?php endif; ?>	
	</div>
	
	<?php msg('1',''.t('votacao.v11').'', ''.t('votacao.v12').'');?>

	<?php foreach($players_to_vote->result_array() as $player): ?>
		<div class="player-box player-box-<?php echo $player['id_player'] ?>">
			<img src="<?php echo player_imagem($player['id_player'], "pequena"); ?>"  /><br />
			<div align="center">
				<b style="font-size: 13px;"><?php echo $player['nome'] ?></b><br />
				<b style="font-size: 13px;" class="laranja">Lvl. <?php echo $player['level'] ?></b>
			</div>
			<br />
			<?php if(in_array($player['id_player'], $should_ignore)): ?>
				<a class="button ui-state-disabled"><?php echo t('votacao.v13')?></a><br /><br />
				<a class="button ui-state-disabled"><?php echo t('votacao.v15')?></a><br /><br />
				<a class="button ui-state-disabled"><?php echo t('votacao.v14')?></a>			
			<?php else: ?>
				<?php if(!in_array('vila', $types_to_ignore)): ?>
					<a class="button c-vila" data-id="<?php echo $player['id_player'] ?>"><?php echo t('votacao.v13')?></a><br /><br />
				<?php else: ?>
					<a class="button ui-state-disabled"><?php echo t('votacao.v13')?></a><br /><br />
				<?php endif ?>
				
				<?php if(!in_array('guerra', $types_to_ignore)): ?>
					<a class="button c-guerra" data-id="<?php echo $player['id_player'] ?>"><?php echo t('votacao.v15')?></a><br /><br />
				<?php else: ?>
				<a class="button ui-state-disabled"><?php echo t('votacao.v15')?></a><br /><br />
				<?php endif ?>
				
				<?php if(!in_array('defesa', $types_to_ignore)): ?>
					<a class="button c-defesa" data-id="<?php echo $player['id_player'] ?>"><?php echo t('votacao.v14')?></a>
				<?php else: ?>
					<a class="button ui-state-disabled"><?php echo t('votacao.v14')?></a>			
				<?php endif ?>
			<?php endif ?>
		</div>
	<?php endforeach ?>
<?php else: ?>
	<?php msg('2',''.t('votacao.v11').'', ''.t('votacao.v16').'');?>
<?php endif; ?>