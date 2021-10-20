<?php
	$exams	= Recordset::query('
		SELECT
			a.*
		
		FROM 
			exame_chuunin a
		
		WHERE
			finalizado=0
			AND id_graduacao=' . $basePlayer->id_graduacao . '
			AND etapa1=' . ($basePlayer->exame_chuunin_etapa == 1 ? 1 : 0) . '

		ORDER BY data_inicio ASC
	');

	$finished	= Recordset::query('
		SELECT
			a.*
		
		FROM 
			exame_chuunin a
		
		WHERE
			finalizado=1

		ORDER BY data_inicio ASC
	');
?>
<script type="text/javascript">
	$(document).ready(function(e) {
		$('.b-exame').on('click', function () {
			$(this).hide();
			
			$.ajax({
				url:		'?acao=exame_aceitar',
				dataType:	'json',
				success:	function (result) {
					location.href	= result.redirect;	
				}
			});
		});
	});
</script>
<script type="text/javascript">
	$(document).ready(function(e) {
		$('.b-exame-detalhe').on('click', function () {
			if(!this.shown) {
				$('.exame-' + $(this).data('exame')).show();
				this.shown	= true;
			} else {
				$('.exame-' + $(this).data('exame')).hide();
				this.shown	= false;
			}
		});
	});
</script>
<div class="titulo-secao"><p><?php echo t('exame.titulo')?></p></div>
<br />
<?php msg('1',''.t('exame.info_titulo').'', ''.t('exame.info_texto').'');?>
<br />
<div class="with-n-tabs" id="exam-tabs">
	<a class="button" rel="#current-exams">Atuais</a>
	<a class="button" rel="#past-exams">Passados</a>
</div><br />
<table width="730" border="0" cellpadding="0" cellspacing="0" id="current-exams">
	<tr>
		<td>
			<?php foreach($exams->result_array() as $current_exam): ?>
			<?php
				if($current_exam['id_vencedor'] !=0){
						$bg	= 'torneio_evento_on';
					}else{
						$bg	= 'torneio_evento_off';
				}
			?>
				<div class="evento-container <?php echo $bg ?>">
					<div class="evento-texto">
						<div style="float: left; width: 160px; position: relative; top: 2px; left: 10px">
							&nbsp;
						</div>	
						<div style="float: left; width: 450px; position: relative; top: 35px">
							<span class="amarelo" style="font-size: 14px"><?php echo $current_exam['nome_' . Locale::get()] ?></span><br /><br />
							<?php echo t('exame.inicio')?> <strong><?php echo date("d/m/Y H:i", strtotime($current_exam['data_inicio'])) ?></strong><br /><br />
							<?php echo t('vila.v31')?> <span class="verde"><?php echo $current_exam['treino'] ?> Pontos de Treino  <?php echo t('geral.e')?> <?php echo $current_exam['ryous'] ?> <?php echo t('geral.de')?> Ryous</span>				
						</div>	
						
					</div>
					<div class="evento-detalhe" style="top: 40px !important">
					<?php if(now() >= strtotime('-20 minute', strtotime($current_exam['data_inicio'])) && now() <= strtotime($current_exam['data_inicio'])): ?>
						<a class="button b-exame"><?php echo t('botoes.inscrever') ?></a>
					<?php else: ?>
						<a class="button ui-state-disabled"><?php echo t('botoes.inscrever') ?></a>
					<?php endif ?>
					</div>
				</div>
			<?php endforeach ?>
		</td>
		</tr>
</table>

<table width="730" border="0" cellpadding="0" cellspacing="0" id="past-exams">
	<tr>
		<td>
			<?php foreach($finished->result_array() as $current_exam): ?>
			<?php
				if ($current_exam['etapa1']) {
					if(Recordset::query('SELECT id FROM player_exame WHERE id_player=' . $basePlayer->id . ' AND id_exame_chuunin=' . $current_exam['id'])->num_rows){
						$bg	= 'torneio_evento_on';
					} else {
						$bg	= 'torneio_evento_off';
					}
				} else {
					if($current_exam['id_vencedor'] == $basePlayer->id){
						$bg	= 'torneio_evento_on';
					} else {
						$bg	= 'torneio_evento_off';
					}
				}
			?>
				<div class="evento-container <?php echo $bg?>">
					<div class="evento-texto">
						<div style="float: left; width: 160px; position: relative; top: 2px; left: 10px">
							&nbsp;
						</div>	
						<div style="float: left; width: 450px; position: relative; top: 35px">
							<span class="amarelo" style="font-size: 14px"><?php echo $current_exam['nome_' . Locale::get()] ?></span><br /><br />
							<?php echo t('exame.inicio')?> <strong><?php echo date("d/m/Y H:i", strtotime($current_exam['data_inicio'])) ?></strong><br /><br />
							<?php echo t('vila.v31')?> <span class="verde"><?php echo $current_exam['treino'] ?> Pontos de Treino  <?php echo t('geral.e')?> <?php echo $current_exam['ryous'] ?> <?php echo t('geral.de')?> Ryous</span>				
						</div>
					</div>
				</div>
			<?php endforeach ?>
		</td>
	</tr>
</table>
