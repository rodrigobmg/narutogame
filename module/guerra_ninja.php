<?php
	if ($basePlayer->id_guerra_ninja) {
		$current_war	= $basePlayer->guerra_ninja;
	} else {
		$war	= Recordset::query('SELECT * FROM guerra_ninja WHERE NOW() BETWEEN data_inicio AND data_fim AND finalizado=0');

		if ($war->num_rows) {
			$current_war	= $war->row();
		} else {
			$current_war	= false;
		}
	}

	$next_wars	= Recordset::query('SELECT * FROM guerra_ninja WHERE data_inicio > NOW() ORDER BY data_inicio asc');
	$past_wars	= Recordset::query('SELECT * FROM guerra_ninja WHERE data_fim < NOW() ORDER BY data_inicio asc');
?>
<div class="titulo-secao"><p><?php echo t('guerra_ninja.titulo') ?></p></div>
<br />
<div style="text-align: center">
	<div class="with-n-tabs" id="war-tabs">
		<?php if ($current_war): ?>
			<a class="button" rel="#current-war"><?php echo t('geral.g89')?> </a>
		<?php endif ?>
		<a class="button" rel="#next-wars"><?php echo t('geral.g87')?></a>
		<a class="button" rel="#past-wars"><?php echo t('geral.g88')?> </a>
	</div><br />
	<?php if ($current_war): ?>
	<table width="730" border="0" cellpadding="0" cellspacing="0" id="current-war">
		<tr>
			<td>
				<?php
					$bg = $current_war->akatsuki ? "layout/npc/dojo_guerra/akatsuki.png" : "layout/npc/dojo_guerra/alianca.png";
				?>
					<div class="evento-container" style="background-image: url(<?php echo img($bg) ?>)">
						<div class="evento-texto">
							<div style="float: left; width: 160px; position: relative; top: 2px; left: 10px">
								&nbsp;
							</div>	
							<div style="float: left; width: 450px; position: relative; top: 35px">
								<span class="amarelo" style="font-size: 14px"><?php echo $current_war->{'nome_' . Locale::get()} ?> ( <?php echo t('geral.g81')?> <?php echo $current_war->etapa ?> ) </span><br /><br />
								<?php echo t('geral.de')?> <strong><?php echo date("d/m/Y H:i", strtotime($current_war->data_inicio)) ?></strong><br />
								<?php echo t('geral.ate')?> <strong><?php echo date("d/m/Y H:i", strtotime($current_war->data_fim)) ?></strong><br />	<br />			
								<?php echo t('vila.v31')?> <span class="verde"><?php echo $current_war->exp_vila ?> <?php echo t('geral.g80')?></span>				
							</div>	
							
						</div>
						<div class="evento-detalhe" style="top: 40px !important">
						<a class="button b-arena-detalhe" data-arena="<?php echo $current_war->id ?>"><?php echo t('botoes.detalhes')?></a>
						</div>
					</div>
				</td>
			</tr>
			<tr class="arena arena-<?php echo $current_war->id ?>" style="display: none">
				<td colspan="5">
					<div class="evento-items" style="margin-bottom:10px; height: 130px;">
						<table width="693" align="center" cellpadding="5" cellspacing="0">
							<tr>
								<td>
									<div style="width: 170px; float: left;" align="center">
										<?php $img = $current_war->akatsuki ? 5 : 1?>
										<img src="<?php echo img('layout/npc/dojo_guerra/'. $img .'.png')?>"/><br />
										<?php echo t('geral.g81')?> 1<br /><br />
										<?php $total_npc = $current_war->akatsuki ? "5000" : "10000" ?>
										<span class="amarelo" style="font-size:13px"><?php echo $current_war->akatsuki ? t('geral.g86') : 'Zetsus';?></span><br />
										<?php $total = Recordset::query('SELECT COUNT(id) AS total FROM guerra_ninja_npcs WHERE etapa=1 AND morto=1 AND akatsuki=' . $current_war->akatsuki)->row()->total ?>
										<?php barra_exp3($total, $total_npc, 132,  $total . ' ' . t('geral.de') . ' ' . $total_npc, "#2C531D", "#537F3D", 1); ?>

									</div>
									<div style="width: 170px; float: left" align="center">
										<?php $img = $current_war->akatsuki ? 6 : 2?>
										<img src="<?php echo img('layout/npc/dojo_guerra/'. $img .'.png')?>"/><br />
										<?php echo t('geral.g81')?> 2<br /><br />
										<?php $total_npc2 = $current_war->akatsuki ? "27" : "34" ?>
										<span class="amarelo" style="font-size:13px"><?php echo $current_war->akatsuki ? t('geral.g84') : 'Edo Tensei';?></span><br />
										<?php $total2 =  Recordset::query('SELECT COUNT(id) AS total FROM guerra_ninja_npcs WHERE etapa=2 AND morto=1 AND akatsuki=' . $current_war->akatsuki)->row()->total ?>
										<?php barra_exp3($total2, $total_npc2, 132,  $total2 . ' ' . t('geral.de') . ' ' . $total_npc2, "#2C531D", "#537F3D", 1); ?>
									</div>
									<div style="width: 170px; float: left" align="center">
										<?php $img = $current_war->akatsuki ? 7 : 3?>
										<img src="<?php echo img('layout/npc/dojo_guerra/'. $img .'.png')?>"/><br />
										<?php echo t('geral.g81')?> 3<br /><br />
										<?php $total_npc3 = $current_war->akatsuki ? "9" : "4" ?>
										<span class="amarelo" style="font-size:13px"><?php echo $current_war->akatsuki ? t('geral.g82') : t('geral.g83');?></span><br />
										<?php $total3 = Recordset::query('SELECT COUNT(id) AS total FROM guerra_ninja_npcs WHERE etapa=3 AND morto=1 AND akatsuki=' . $current_war->akatsuki)->row()->total ?>
										<?php barra_exp3($total3, $total_npc3, 132,  $total3 . ' ' . t('geral.de') . ' ' . $total_npc3, "#2C531D", "#537F3D", 1); ?>

									</div>
									<div style="width: 170px; float: left" align="center">
										<?php $img = $current_war->akatsuki ? 8 : 4?>
										<img src="<?php echo img('layout/npc/dojo_guerra/'. $img .'.png')?>"/><br />
										<?php echo t('geral.g81')?> 4<br /><br />
										<?php $total_npc4 = $current_war->akatsuki ? "3" : "1" ?>
										<span class="amarelo" style="font-size:13px"><?php echo $current_war->akatsuki ? t('geral.g85') : 'Madara Uchiha';?></span><br />
										<?php $total4 =  Recordset::query('SELECT COUNT(id) AS total FROM guerra_ninja_npcs WHERE etapa=4 AND morto=1 AND akatsuki=' . $current_war->akatsuki)->row()->total ?>
										<?php barra_exp3($total4, $total_npc4, 132,  $total4 . ' ' . t('geral.de') . ' ' . $total_npc4, "#2C531D", "#537F3D", 1); ?>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<tr class="spacer"></tr>
	</table>
	<?php endif ?>
	<div id="next-wars">
		<?php foreach ($next_wars->result_array() as $war): ?>
			<div class="war-container">
				<table width="730" border="0" cellpadding="0" cellspacing="0" id="current-war">
					<tr>
						<td>
							<?php
								$bg = $war['akatsuki'] ? "layout/npc/dojo_guerra/akatsuki.png" : "layout/npc/dojo_guerra/alianca.png";
							?>
								<div class="evento-container" style="background-image: url(<?php echo img($bg) ?>)">
									<div class="evento-texto">
										<div style="float: left; width: 160px; position: relative; top: 2px; left: 10px">
											&nbsp;
										</div>	
										<div style="float: left; width: 450px; position: relative; top: 35px">
											<span class="amarelo" style="font-size: 14px"><?php echo $war['nome_' . Locale::get()] ?> ( <?php echo t('geral.g81')?> <?php echo $war['etapa'] ?> ) </span><br /><br />
											<?php echo t('geral.de')?> <strong><?php echo date("d/m/Y H:i", strtotime($war['data_inicio'])) ?></strong><br />
											<?php echo t('geral.ate')?> <strong><?php echo date("d/m/Y H:i", strtotime($war['data_fim'])) ?></strong><br />	<br />			
											<?php echo t('vila.v31')?> <span class="verde"><?php echo $war['exp_vila'] ?> <?php echo t('geral.g80')?></span>				
										</div>	
										
									</div>
									<div class="evento-detalhe" style="top: 40px !important">
									<a class="button b-arena-detalhe" data-arena="<?php echo $war['id'] ?>"><?php echo t('botoes.detalhes')?></a>
									</div>
								</div>
							</td>
						</tr>
						<tr class="arena arena-<?php echo $war['id']?>" style="display: none">
							<td colspan="5">
								<div class="evento-items" style="margin-bottom:10px; height: 130px;">
									<table width="693" align="center" cellpadding="5" cellspacing="0">
										<tr>
											<td>
												<div style="width: 170px; float: left;" align="center">
													<?php $img = $war['akatsuki'] ? 5 : 1?>
													<img src="<?php echo img('layout/npc/dojo_guerra/'. $img .'.png')?>"/><br />
													<?php echo t('geral.g81')?> 1<br /><br />
													<?php $total_npc = $war['akatsuki'] ? "5000" : "10000" ?>
													<span class="amarelo" style="font-size:13px"><?php echo $war['akatsuki'] ? t('geral.g86') : 'Zetsus';?></span><br />
													<?php $total = 0 ?>
													<?php barra_exp3($total, $total_npc, 132,  $total . ' ' . t('geral.de') . ' ' . $total_npc, "#2C531D", "#537F3D", 1); ?>
				
												</div>
												<div style="width: 170px; float: left" align="center">
													<?php $img = $war['akatsuki'] ? 6 : 2?>
													<img src="<?php echo img('layout/npc/dojo_guerra/'. $img .'.png')?>"/><br />
													<?php echo t('geral.g81')?> 2<br /><br />
													<?php $total_npc2 = $war['akatsuki'] ? "27" : "34" ?>
													<span class="amarelo" style="font-size:13px"><?php echo $war['akatsuki'] ? t('geral.g84') : 'Edo Tensei';?></span><br />
													<?php $total2 =  0 ?>
													<?php barra_exp3($total2, $total_npc2, 132,  $total2 . ' ' . t('geral.de') . ' ' . $total_npc2, "#2C531D", "#537F3D", 1); ?>
												</div>
												<div style="width: 170px; float: left" align="center">
													<?php $img = $war['akatsuki'] ? 7 : 3?>
													<img src="<?php echo img('layout/npc/dojo_guerra/'. $img .'.png')?>"/><br />
													<?php echo t('geral.g81')?> 3<br /><br />
													<?php $total_npc3 = $war['akatsuki'] ? "9" : "4" ?>
													<span class="amarelo" style="font-size:13px"><?php echo $war['akatsuki'] ?  t('geral.g82') : t('geral.g83') ;?></span><br />
													<?php $total3 = 0 ?>
													<?php barra_exp3($total3, $total_npc3, 132,  $total3 . ' ' . t('geral.de') . ' ' . $total_npc3, "#2C531D", "#537F3D", 1); ?>
				
												</div>
												<div style="width: 170px; float: left" align="center">
													<?php $img = $war['akatsuki'] ? 8 : 4?>
													<img src="<?php echo img('layout/npc/dojo_guerra/'. $img .'.png')?>"/><br />
													<?php echo t('geral.g81')?> 4<br /><br />
													<?php $total_npc4 = $war['akatsuki'] ? "3" : "1" ?>
													<span class="amarelo" style="font-size:13px"><?php echo $war['akatsuki'] ? t('geral.g85') : 'Madara Uchiha';?></span><br />
													<?php $total4 = 0 ?>
													<?php barra_exp3($total4, $total_npc4, 132,  $total4 . ' ' . t('geral.de') . ' ' . $total_npc4, "#2C531D", "#537F3D", 1); ?>
												</div>
											</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
						<tr class="spacer"></tr>
				</table>
			</div>
		<?php endforeach ?>
	</div>
	<div id="past-wars">
		<?php foreach ($past_wars->result_array() as $war): ?>
			<div class="war-container">
				<div class="war-container">
					<table width="730" border="0" cellpadding="0" cellspacing="0" id="current-war">
						<tr>
							<td>
								<?php
									$bg = $war['akatsuki'] ? "layout/npc/dojo_guerra/akatsuki.png" : "layout/npc/dojo_guerra/alianca.png";
								?>
									<div class="evento-container" style="background-image: url(<?php echo img($bg) ?>)">
										<div class="evento-texto">
											<div style="float: left; width: 160px; position: relative; top: 2px; left: 10px">
												&nbsp;
											</div>	
											<div style="float: left; width: 450px; position: relative; top: 35px">
												<span class="amarelo" style="font-size: 14px"><?php echo $war['nome_' . Locale::get()] ?> ( <?php echo t('geral.g81')?> <?php echo $war['etapa'] ?> ) </span><br /><br />
												<?php echo t('geral.de')?> <strong><?php echo date("d/m/Y H:i", strtotime($war['data_inicio'])) ?></strong><br />
												<?php echo t('geral.ate')?> <strong><?php echo date("d/m/Y H:i", strtotime($war['data_fim'])) ?></strong><br />	<br />			
												<?php echo t('vila.v31')?> <span class="verde"><?php echo $war['exp_vila'] ?> <?php echo t('geral.g80')?></span>				
											</div>	
											
										</div>
										<div class="evento-detalhe" style="top: 40px !important">
										<a class="button b-arena-detalhe" data-arena="<?php echo $war['id'] ?>"><?php echo t('botoes.detalhes')?></a>
										</div>
									</div>
								</td>
							</tr>
							<tr class="arena arena-<?php echo $war['id']?>" style="display: none">
								<td colspan="5">
									<div class="evento-items" style="margin-bottom:10px; height: 130px;">
										<table width="693" align="center" cellpadding="5" cellspacing="0">
											<tr>
												<td>
													<div style="width: 170px; float: left;" align="center">
														<?php $img = $war['akatsuki'] ? 5 : 1?>
														<img src="<?php echo img('layout/npc/dojo_guerra/'. $img .'.png')?>"/><br />
														<?php echo t('geral.g81')?> 1<br /><br />
														<?php $total_npc = $war['akatsuki'] ? "5000" : "10000" ?>
														<span class="amarelo" style="font-size:13px"><?php echo $war['akatsuki'] ? t('geral.g86') : 'Zetsus';?></span><br />
														<?php $total = $war['etapa3_vitoria']==1 ? $total_npc : 0  ?>
														<?php barra_exp3($total, $total_npc, 132,  $total . ' ' . t('geral.de') . ' ' . $total_npc, "#2C531D", "#537F3D", 1); ?>
					
													</div>
													<div style="width: 170px; float: left" align="center">
														<?php $img = $war['akatsuki'] ? 6 : 2?>
														<img src="<?php echo img('layout/npc/dojo_guerra/'. $img .'.png')?>"/><br />
														<?php echo t('geral.g81')?> 2<br /><br />
														<?php $total_npc2 = $war['akatsuki'] ? "27" : "34" ?>
														<span class="amarelo" style="font-size:13px"><?php echo $war['akatsuki'] ? t('geral.g84') : 'Edo Tensei';?></span><br />
														<?php $total2 = $war['etapa3_vitoria']==1 ? $total_npc2 : 0  ?>
														<?php barra_exp3($total2, $total_npc2, 132,  $total2 . ' ' . t('geral.de') . ' ' . $total_npc2, "#2C531D", "#537F3D", 1); ?>
													</div>
													<div style="width: 170px; float: left" align="center">
														<?php $img = $war['akatsuki'] ? 7 : 3?>
														<img src="<?php echo img('layout/npc/dojo_guerra/'. $img .'.png')?>"/><br />
														<?php echo t('geral.g81')?> 3<br /><br />
														<?php $total_npc3 = $war['akatsuki'] ? "9" : "4" ?>
														<span class="amarelo" style="font-size:13px"><?php echo $war['akatsuki'] ? t('geral.g82') : t('geral.g83');?></span><br />
														<?php $total3 = $war['etapa3_vitoria']==1 ? $total_npc3 : 0  ?>
														<?php barra_exp3($total3, $total_npc3, 132,  $total3 . ' ' . t('geral.de') . ' ' . $total_npc3, "#2C531D", "#537F3D", 1); ?>
					
													</div>
													<div style="width: 170px; float: left" align="center">
														<?php $img = $war['akatsuki'] ? 8 : 4?>
														<img src="<?php echo img('layout/npc/dojo_guerra/'. $img .'.png')?>"/><br />
														<?php echo t('geral.g81')?> 4<br /><br />
														<?php $total_npc4 = $war['akatsuki'] ? "3" : "1" ?>
														<span class="amarelo" style="font-size:13px"><?php echo $war['akatsuki'] ? t('geral.g85') : 'Madara Uchiha';?></span><br />
														<?php $total4 = $war['etapa4_vitoria']==1 ? $total_npc4 : 0  ?>
														<?php barra_exp3($total4, $total_npc4, 132,  $total4 . ' ' . t('geral.de') . ' ' . $total_npc4, "#2C531D", "#537F3D", 1); ?>
													</div>
												</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
							<tr class="spacer"></tr>
					</table>
				</div>
			</div>
		<?php endforeach ?>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(e) {
		if(!document.__damn_cb_from_war) {
			document.__damn_cb_from_war	= true;

			$(document).on('click', '.b-arena-detalhe', function () {
				if(!this.shown) {
					$('.arena-' + $(this).data('arena')).show();
					this.shown	= true;
				} else {
					$('.arena-' + $(this).data('arena')).hide();
					this.shown	= false;
				}
			});
		}
	});
</script>
