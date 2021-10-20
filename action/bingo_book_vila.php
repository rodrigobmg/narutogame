<?php
	$vip1 = false;
	$vip2 = false;
	
	if($basePlayer->hasItem(21760)){
		$vip1 = true;
	}
	
	if($basePlayer->hasItem(21761)){
		$vip2 = true;
	}

	$players = new Recordset("
		SELECT
			a.id,
			a.nome,
			a.id_classe,
			b.titulo_" . Locale::get() . " AS titulo,
			c.morto,
			c.sobrevivente,
			c.pt_treino,
			c.exp,
			c.ryou,
			a.level,
			c.id AS uid,
			a.dentro_vila,
			a.id_vila,
			a.id_vila_atual,
			e.nome_".Locale::get()." AS nome_vila,
			f.nome_".Locale::get()." AS nome_vila_atual,
			d.xpos,
			d.ypos
			
		FROM
			player a LEFT JOIN player_titulo b ON b.id=a.id_titulo
			JOIN bingo_book_vila c ON c.id_player_alvo=a.id
			LEFT JOIN player_posicao d ON d.id_player=a.id
			JOIN vila e ON e.id=a.id_vila
			LEFT JOIN vila f ON f.id=a.id_vila_atual
		
		WHERE
			c.id_vila=" . $basePlayer->id_vila ."
		ORDER BY c.morto asc	");
?>
<style type="text/css">
	.imagem {
		background-repeat: no-repeat; 
		background-position: center center;
	}
	
	.imagem, .x {
		width: 320px;
		height: 258px;		
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
				<?php echo t('actions.a10_1')?>
           
	</div>
	<?php foreach($players->result_array() as $player): ?>
		<div class="bingo_book_pagina" align="center" style="text-align: left">
			<div class="imagem <?php echo $player['morto'] ? 'trans' : '' ?>" style="background-image: URL(<?php echo img('layout/bingobook/' . $player['id_classe'] . '.jpg') ?>)">
			<?php if($player['morto']): ?>
				<img class="x" src="<?php echo img('layout/bingobook/x.png') ?>" />
			<?php endif; ?>
			</div>
			<div class="nome" id="bingo-book-<?php echo $player['id'] ?>" style="clear: left; position: relative; top: 6px;">
				<?php if(/*$vip2 && */!$player['morto']): ?>
					<?php echo player_online($player['id'], true) ?>
				<?php endif ?>
				<?php echo $player['nome'] ?> - Lvl. <?php echo $player['level'] ?>
			</div>
			<?php if(($vip1 || $vip2) && !$player['morto']): ?>
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
					<?php if(($vip1 || $vip2) && !$player['morto']): ?>
						<?php echo t('bijuus.mapa_origem') ?>: <?php echo $place ?><br />
						<?php echo t('bijuus.no_mapa') ?>: <?php echo $map ?><br />
					<?php endif ?>
					<?php if($vip2 && !$player['id_vila_atual'] && !$player['morto']): ?>
						<?php echo t('bijuus.coords') ?>: X: <?php echo $x ?> / Y: <?php echo $y ?><br />
					<?php endif ?>		
			<?php if(!$player['morto']): ?>			
				<?php echo generic_tooltip('bingo-book-' . $player['id'], ob_get_clean()) ?>
			<?php endif ?>		
			<?php endif ?>				
			<!--
			<div class="titulo">
				<?php echo $player['titulo'] ? $player['titulo'] : "Nenhum título" ?>
			</div>
			-->
			<div class="recompensa">
				Recompensa
				<ul>
					<?php if($player['ryou']): ?>
					<li>Ryous: <?php echo $player['ryou'] + percent($basePlayer->bonus_profissao['bb_recompensa'], $player['ryou']) ?></li>
					<?php endif; ?>
					
					<?php if($player['pt_treino']): ?>
					<li>Pontos de treino: <?php echo $player['pt_treino'] + percent($basePlayer->bonus_profissao['bb_recompensa'], $player['pt_treino']) ?></li>
					<?php endif; ?>
					
					<?php if($player['exp']): ?>
					<li>Experiência para a Vila: <?php echo $player['exp'] + percent($basePlayer->bonus_profissao['bb_recompensa'], $player['exp']) ?></li>
					<?php endif; ?>
				</ul>
				<br />
			</div>
            <?php if($player['sobrevivente']){?>
            <div align="center">
            	<a class="button ui-state-green">Sobrevivente</a>
            </div>
            <?php }?>
		</div>
	<?php endforeach; ?>
</div>
<script type="text/javascript">
	$("#bingobook").booklet({width: 725, height: 500});
	$(".trans").css('opacity', .6);
	
	
</script>