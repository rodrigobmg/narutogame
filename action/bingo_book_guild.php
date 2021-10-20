<?php
	$vip1 = false;
	$vip2 = false;
	
	if($basePlayer->hasItem(21760)){
		$vip1 = true;
	}
	
	if($basePlayer->hasItem(21761)){
		$vip2 = true;
	}
	
	if($_POST && $basePlayer->dono_guild) {
		$json			= new stdClass();
		$json->success	= false;
		
		$has_currency	= isset($_POST['pm']) && $_POST['pm'] == 1 ? $basePlayer->ryou >= 5000 : $basePlayer->coin >= 3;
		$target			= Recordset::query('SELECT * FROM bingo_book_guild WHERE id_guild=' . $basePlayer->id_guild . ' AND id=' . (int)$_POST['target']);

		if(isset($_POST['target']) && is_numeric($_POST['target']) && $target->num_rows && $has_currency) {
			if(isset($_POST['pm']) && $_POST['pm'] == 1) {
				$basePlayer->setAttribute('ryou', $basePlayer->getAttribute('ryou') - 5000);
			} else {
				gasta_coin(3, 21885);
			}

			$mine_targets	= Recordset::query('
				SELECT
					GROUP_CONCAT(id_player_alvo) AS e1

				FROM
					bingo_book_guild WHERE id_guild=' . $basePlayer->id_guild)->row();
			$mine_targets	= $mine_targets->e1 . ',1,2,3,4,18';

			$sql			= '
				SELECT
					id

				FROM
					player

				WHERE
					#id_graduacao >= 4 AND
					id_vila != ' . $basePlayer->id_vila . ' AND
					removido=0 AND
					level > 10 AND
					DATEDIFF(CURDATE(), ult_atividade) < 3 AND
					id NOT IN(' . $basePlayer->id . ($mine_targets ? ',' . $mine_targets : '') . ')
				ORDER BY RAND() LIMIT 1
			';
			
			Recordset::update('bingo_book_guild', [
				'id_player_alvo'	=> [
					'escape'	=> false,
					'value'		=> '(' . $sql . ')'
				],
			], array(
				'id'				=> $_POST['target']
			));
			
			$json->success	= true;
		}
	
		die(json_encode($json));	
	}

	$records = new Recordset("
		SELECT
			*			
		FROM
			bingo_book_guild
		
		WHERE
			id_guild=" . $basePlayer->id_guild ." ORDER BY morto ASC");
?>
<style type="text/css">
	.imagem {
		background-repeat: no-repeat; 
		background-position: center center;
	}
	
	.imagem, .x {
		width: 320px;
		height: 264px;		
	}
	
	.nome {
		text-align: center;	
		background-image: URL('<?php echo img('layout/bingobook/barra_nome.png') ?>');
		background-repeat: no-repeat; 
		background-position: center center;
		color: #FFF;
		font-size: 14px;
		padding-top: 12px;
		height: 34px;
		clear:both;
	}
	.b-wrap {
		box-sizing:content-box
	}		
	.titulo {
		text-align: center;	
		background-image: URL('<?php echo img('layout/bingobook/barra_titulo.png') ?>');
		background-repeat: no-repeat; 
		background-position: center center;
		color: #FFF;
		font-size: 11px;
		padding-top: 10px;
		height: 23px;
	}
	
	.recompensa {
		padding-top: 13px;
		font-size: 13px;
		font-weight: bold;
		color: #000
	}
	
	.recompensa * {
		font-size: 13px;
		font-weight: bold
	}
	
	.b-wrap-right {
		background-image: URL('<?php echo img('layout/bingo_book_r.png') ?>') !important
	}

	.b-wrap-left {
		background-image: URL('<?php echo img('layout/bingo_book_l.png') ?>') !important
	}
	
	.bingo_book_pagina {
		padding-left: 13px;	
		color: #000		
	}
</style>
<div id="bingobook">

    <div style="color: #000; text-align: left; margin-left:15px;">
			<font style="font-size: 18px;"><?php echo t('actions.a8')?></font><br /><br/>
				<?php echo t('actions.a10')?>
            <font style="font-size: 18px;"><?php echo t('actions.a9')?></font><br /><br/>
            	<?php echo t('actions.a11')?>
	</div>
	<?php foreach($records->result_array() as $record): ?>
		<div class="bingo_book_pagina" align="center" style="text-align: left">
			<?php for($f = 1; $f <= 1; $f++): ?>
				<?php
					$player	= Recordset::query('
						SELECT
							a.id,
							a.nome,
							a.id_classe,
							e.titulo_' . Locale::get() . ' AS titulo,
							a.level,
							a.dentro_vila,
							a.id_vila,
							a.id_vila_atual,
							c.nome_' . Locale::get() . ' AS nome_vila,
							d.nome_' . Locale::get() . ' AS nome_vila_atual,
							b.xpos,
							b.ypos

						FROM
							player a LEFT JOIN player_posicao b ON b.id_player=a.id
							JOIN vila c ON c.id=a.id_vila
							LEFT JOIN vila d ON d.id=a.id_vila_atual
							LEFT JOIN player_titulo e ON e.id=a.id_titulo

						WHERE
							a.id=' . $record['id_player_alvo' . ($f == 1 ? '' : $f)])->row_array();
				?>
				<div class="imagem <?php echo $record['morto'] ? 'trans' : '' ?>" style="background-image: URL(<?php echo img('layout/bingobook/' . $player['id_classe'] . '.jpg') ?>)"></div>
				<div class="nome" id="bingo-book-<?php echo $record['id'] ?>-<?php echo $f ?>">
					<?php if(/*$vip2 && */!$record['morto']): ?>
						<?php echo player_online($player['id'], true) ?>
					<?php endif ?>
					<?php echo $player['nome'] ?> - Lvl. <?php echo $player['level'] ?>
				</div>
				<?php if(($vip1 || $vip2) && !$record['morto']): ?>
					<?php ob_start() ?>
						<?php
							$place	= $player['nome_vila'];
							$x		= $player['xpos'] * 22;
							$y		= $player['ypos'] * 22;
							
							if($player['dentro_vila']) {
								$map	= t('geral.nao');
							} else {
								if($player['id_vila_atual']) {
									$map	= $player['nome_vila_atual'];
								} else {
									$map	= t('bijuus.mundi');
								}
							}					
						?>
						<?php if(($vip1 || $vip2) && !$record['morto']): ?>
							<?php echo t('bijuus.mapa_origem') ?>: <?php echo $place ?><br />
							<?php echo t('bijuus.no_mapa') ?>: <?php echo $map ?><br />
						<?php endif ?>
						<?php if($vip2 && !$player['id_vila_atual'] && !$record['morto']): ?>
							<?php echo t('bijuus.coords') ?>: X: <?php echo $x ?> / Y: <?php echo $y ?><br />
						<?php endif ?>		
					<?php if(!$record['morto']): ?>
						<?php echo generic_tooltip('bingo-book-' . $record['id'] . '-' . $f, ob_get_clean()) ?>
					<?php endif ?>
				<?php endif ?>
			<?php endfor; ?>
			<?php if($record['morto']): ?>
				<img class="x" src="<?php echo img('layout/bingobook/x.png') ?>" />
			<?php endif; ?>
			<div class="recompensa">
				Recompensa
				<ul>
					<?php if($record['ryou']): ?>
					<li>Ryous: <?php echo $record['ryou'] ?></li>
					<?php endif; ?>
					
					<?php if($record['pt_treino']): ?>
					<li>Pontos de treino: <?php echo $record['pt_treino'] ?></li>
					<?php endif; ?>
					
					<?php if($record['exp']): ?>
					<li>Pontos de experiência: <?php echo $record['exp'] ?></li>
					<?php endif; ?>
				</ul>
				<?php if($basePlayer->dono_guild): ?>
				<br />
				<div align="center">
					<?php if($basePlayer->ryou >= 5000 && !$record['morto']): ?>
						<a class="button b-change-target" data-target="<?php echo $record['id'] ?>" data-pm="1"><?php echo t('botoes.trocar_alvo') ?></a>
						<br /><br />
					<?php else: ?>
						<a class="button ui-state-disabled"><?php echo t('botoes.trocar_alvo') ?></a>
						<br /><br />
					<?php endif ?>
					
					<?php if($basePlayer->coin >= 3 && !$record['morto']): ?>
						<a class="button b-change-target" data-target="<?php echo $record['id'] ?>" data-pm="2"><?php echo t('botoes.trocar_alvo2') ?></a>
					<?php else: ?>
						<a class="button ui-state-disabled"><?php echo t('botoes.trocar_alvo2') ?></a>
					<?php endif ?>
				</div>
				<?php endif ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
<script type="text/javascript">
	$("#bingobook").booklet({width: 725, height: 500});
	$(".trans").css('opacity', .6);
	
	$(document).ready(function () {
		$('.b-change-target').on('click', function () {
			var	_ = $(this);
			
			jconfirm(_.data('pm') == 2 ? '<?php echo t('bingo_book.msg_troca') ?>' : '<?php echo t('bingo_book.msg_troca2') ?>', null, function () {
				$.ajax({
					url:		'?acao=bingo_book_guild',
					data:		{target: _.data('target'), pm: _.data('pm')},
					type:		'post',
					dataType:	'json',
					success:	function (e) {
						if(e.success) {
							jalert('<?php echo t('bingo_book.msg_troca_ok') ?>');
						}
					}
				});
			});
		});
	});
</script>