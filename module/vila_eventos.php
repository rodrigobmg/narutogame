<?php if(!$basePlayer->tutorial()->eventos){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 21,
	 
	  steps: [
	  {
		element: ".msg_gai",
		title: "<?php echo t("tutorial.titulos.vila.2");?>",
		content: "<?php echo t("tutorial.mensagens.vila.2");?>",
		placement: "top"
	  }
	]});
	//Renicia o Tour
	tour.restart();
	// Initialize the tour
	tour.init(true);
	// Start the tour
	tour.start(true);
});
</script>	
<?php } ?>
<?php
	$eventos		= Recordset::query('SELECT * FROM evento_vila WHERE finalizado = 0 AND data_inicio > DATE_ADD(NOW(), INTERVAL -30 DAY)');
	$color_counter	= 0;
	$color_counter2	= 0;
	$img_counter	= 0;
	$img_counter2	= 0;
	$vip			= $basePlayer->hasItemW(21873);
?>
<script type="text/javascript">
	$(document).ready(function(e) {
		$('.b-evento').on('click', function () {
			if(!this.shown) {
				$('.evento-' + $(this).data('evento')).show();
				this.shown	= true;
			} else {
				$('.evento-' + $(this).data('evento')).hide();
				this.shown	= false;
			}
		});
	});
</script>
<div class="titulo-secao"><p><?php echo t('geral.g13')?></p></div>
<?php msg('2',''.t('geral.g13').'', ''.t('geral.g14').'');?>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<?php foreach($eventos->result_array() as $evento): ?>
		<?php
			switch($evento['tipo']) {
				case 'bijuu';	$tipo	= '34'; break;
				case 'armas';	$tipo	= '35'; break;
				case 'espadas';	$tipo	= '36'; break;
			}
		
			$items	= Recordset::query('SELECT * FROM item WHERE id_tipo=' . $tipo);
			//$bg		= ++$color_counter % 2 ? "#040f1c" : "#0f294a";
			//$pn		= ++$img_counter % 2 ? "pontilhado-listagem1.jpg" : "pontilhado-listagem.jpg";
			
			if($evento['iniciado']) {
				$bg	= 'vila_evento_on';				
			} else {
				$bg	= 'vila_evento_off';
			}
		?>
		<tr>
			<td>
				<div class="evento-container <?php echo $bg ?>">
					<div class="evento-texto">
						<div style="float: left; width: 160px; position: relative; top: 2px; left: 10px">
							<img src="<?php echo img()?><?php echo $evento['imagem'] ?>" />
						</div>	
						<div style="float: left; width: 450px; position: relative; top: 35px">
							<span class="amarelo" style="font-size: 14px"><?php echo $evento['nome_'. Locale::get()] ?></span><br /><br />
							<?php echo t('geral.de')?> <strong><?php echo date("d/m/Y H:i", strtotime($evento['data_inicio'])) ?></strong><br />
							<?php echo t('geral.ate')?> <strong><?php echo date("d/m/Y H:i", strtotime($evento['data_fim'])) ?></strong><br />	<br />			
							Ganhe <span class="verde"><?php echo $evento['exp'] ?> <?php echo t('academia_treinamento.at21')?></span> <?php echo t('geral.g15')?>				
						</div>	
						
					</div>
					<div class="evento-detalhe">
						<a class="button b-evento" data-evento="<?php echo $evento['id'] ?>"><?php echo t('botoes.detalhes')?></a>
					</div>
				</div>
			</td>
		</tr>
		<tr class="evento evento-<?php echo $evento['id'] ?>" style="display: none">
			<td colspan="5">
				<div class="evento-items">
					<table align="center" cellpadding="5" cellspacing="0" style="width:693px !important">
						<?php foreach($items->result_array() as $item): ?>
							<?php
								$qPlayer	= Recordset::query('
									SELECT
										b.id,
										b.id_vila,
										b.nome,
										c.xpos,
										c.ypos,
										b.id_vila_atual,
										b.dentro_vila,
										d.nome_' . Locale::get() . ' AS nome_vila,
										e.nome_' . Locale::get() . ' AS nome_vila2
									
									FROM 
										player_item a JOIN player b ON b.id=a.id_player
										LEFT JOIN player_posicao c ON c.id_player=a.id_player
										LEFT JOIN vila d ON d.id=b.id_vila_atual
										LEFT JOIN vila e ON e.id=b.id_vila
									
									WHERE 
										a.id_item=' . $item['id']);
								
								$player = $qPlayer->row_array();
							?>
							<tr class="item <?php echo $qPlayer->num_rows && $player['id_vila'] == $basePlayer->id_vila ? "cor_sim" : "cor_nao"?>">
								<td width="200" align="center">
									<img src="<?php echo img()?>layout/vilas/evento/<?php echo $item['id']?>.png" />
								</td>
								<?php if($evento['iniciado']): ?>
									<td width="490" align="left">
										<b class="laranja" style="font-size: 13px"><?php echo t('geral.g16')?> <?php echo $item['nome_'. Locale::get()] ?></b><br /><br />
										<div style="float: left; width: 170px">
											<!-- Sem Vip -->
											<?php if($qPlayer->num_rows): ?>
												<b class="cinza"><?php echo t('geral.g17')?>:</b> <?php echo player_online($player['id'], true) ?><?php echo $player['nome'] ?><br />
												<b class="cinza"><?php echo t('geral.vila')?>:</b> <?php echo $player['nome_vila2'] ?><br />
											<?php endif; ?>
											<!-- Sem Vip -->
										</div>
										<?php if($vip): ?>
											<?php
												$at_map	= $player['dentro_vila'] ? 'Não' : 'Sim';
												$mul	= 1;
												
												if($player['dentro_vila']) {
													$map	= t('missoes.nenhuma');
												} else {
													if($player['id_vila_atual']) {
														$map	= Recordset::query('SELECT nome_' . Locale::get() . ' FROM vila WHERE id=' . $player['id_vila_atual'])->row()->{'nome_' . Locale::get()};
													} else {
														$map	= 'Mundi';
														$mul	= 22;
													}

													$pos	= Recordset::query('SELECT xpos, ypos FROM player_posicao WHERE id_player=' . $player['id'])->row_array();
												}
											?>
											<div style="float: left;  width: 170px">
												<b class="cinza"><?php echo t('geral.g18')?>:</b> <?php echo $at_map ?><br />
												<b class="cinza"><?php echo t('geral.g19')?>:</b> <?php echo $map ?><br /> 
											</div>
											<?php if(!$player['dentro_vila']): ?>
												<div style="float: left;  width: 100px">
													<b class="cinza"><?php echo t('geral.g20')?>:</b><br />
													X: <?php echo $pos['xpos'] * $mul ?> / Y: <?php echo $pos['ypos'] * $mul ?>
												</div>
											<?php endif ?>
										<?php endif ?>
									</td>
								<?php else: ?>
									<td width="300"><?php echo t('geral.g21')?></td>
									<td width="143"><?php echo t('geral.g21')?></td>
								<?php endif ?>
							</tr>
							<tr class="spacer"></tr>
						<?php endforeach ?>
					</table>
				</div>
			</td>
		</tr>		
	<?php endforeach ?>
</table>
<br /><br />
