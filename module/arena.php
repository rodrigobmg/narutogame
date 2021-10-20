<?php
	$prev_arenas	= Recordset::query('
		SELECT
			a.*,
			b.nome_' . Locale::get() . ' AS vila,
			c.nome AS player
		
		FROM 
			arena a JOIN vila b ON b.id=a.vila_id
			LEFT JOIN player c ON c.id=a.player_id
		
		WHERE (a.data_fim < NOW() OR finalizado=1) and a.removido = 0 ORDER BY id desc');
	
	$current_arenas	= Recordset::query('
		SELECT
			a.*,
			b.nome_' . Locale::get() . ' AS vila
		
		FROM 
			arena a JOIN vila b ON b.id=a.vila_id
		
		WHERE
			finalizado=0 and a.removido = 0 ORDER BY data_inicio asc
			#NOW() BETWEEN DATE_ADD(a.data_inicio, INTERVAL -10 MINUTE) AND a.data_fim AND finalizado=0
	');
?>
<script type="text/javascript">
	$(document).ready(function(e) {
		$('.b-arena').on('click', function () {
			$(this).hide();
			
			$.ajax({
				url:		'?acao=arena_aceitar',
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
		$('.b-arena-detalhe').on('click', function () {
			if(!this.shown) {
				$('.arena-' + $(this).data('arena')).show();
				this.shown	= true;
			} else {
				$('.arena-' + $(this).data('arena')).hide();
				this.shown	= false;
			}
		});
	});
</script>
<div class="titulo-secao"><p><?php echo t('arena.titulo')?></p></div>
<br />
<?php msg('1',''.t('geral.g40').'', ''.t('geral.g41').'');?>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<?php foreach($current_arenas->result_array() as $current_arena): ?>
			<?php
				if($current_arena['player_id'] !=0){
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
							<span class="amarelo" style="font-size: 14px"><?php echo $current_arena['nome_'. Locale::get()] ?></span><br /><br />
							<?php echo t('geral.de')?> <strong><?php echo date("d/m/Y H:i", strtotime($current_arena['data_inicio'])) ?></strong><br />
							<?php echo t('geral.ate')?> <strong><?php echo date("d/m/Y H:i", strtotime($current_arena['data_fim'])) ?></strong><br />	<br />			
							<?php echo t('vila.v31')?> <span class="verde"><?php echo $current_arena['experiencia'] ?> <?php echo t('academia_treinamento.at21')?>  <?php echo t('geral.e')?> <?php echo $current_arena['ryous'] ?> <?php echo t('geral.de')?> Ryous</span>				
						</div>	
						
					</div>
					<div class="evento-detalhe" style="top: 40px !important">
					<?php if(now() >= strtotime('-10 minute', strtotime($current_arena['data_inicio'])) && now() <= strtotime($current_arena['data_inicio'])): ?>
						<a class="button b-arena"><?php echo t('botoes.inscrever') ?></a>
					<?php else: ?>
						<a class="button ui-state-disabled"><?php echo t('botoes.inscrever') ?></a>
					<?php endif ?>
					<!--
					<br /><br /><a class="button b-evento" data-evento="<?php echo $current_arena['id'] ?>"><?php echo t('botoes.detalhes')?></a>
					-->
					</div>
				</div>
			<?php endforeach ?>
		</td>
		</tr>
</table>
<?php if($prev_arenas->num_rows): ?>
	<table width="730" border="0" cellpadding="0" cellspacing="0">
	<?php foreach($prev_arenas->result_array() as $arena): ?>
	<?php
		if($arena['player_id'] !=0 && $arena['player_id'] == $basePlayer->id){
			$bg	= 'torneio_evento_on';
		} else {
			$bg	= 'torneio_evento_on';
		}
	
	?>
		<tr>
			<td>
				<div class="evento-container <?php echo $bg?>">
					<div class="evento-texto">
						<div style="float: left; width: 160px; position: relative; top: 2px; left: 10px">
							&nbsp;
						</div>	
						<div style="float: left; width: 450px; position: relative; top: 35px">
							<span class="amarelo" style="font-size: 14px"><?php echo $arena['nome_'. Locale::get()] ?></span><br /><br />
							<?php echo t('geral.de')?> <strong><?php echo date("d/m/Y H:i", strtotime($arena['data_inicio'])) ?></strong><br />
							<?php echo t('geral.ate')?> <strong><?php echo date("d/m/Y H:i", strtotime($arena['data_fim'])) ?></strong><br />	<br />			
							<?php echo t('vila.v31')?> <span class="verde"><?php echo $arena['experiencia'] ?> <?php echo t('academia_treinamento.at21')?>  <?php echo t('geral.e')?> <?php echo $arena['ryous'] ?> <?php echo t('geral.de')?> Ryous</span>				
						</div>	
						
					</div>
					<div class="evento-detalhe">
						<a class="button b-arena-detalhe" data-arena="<?php echo $arena['id'] ?>"><?php echo t('botoes.detalhes')?></a>
					</div>
				</div>
			</td>
		</tr>
		<tr class="arena arena-<?php echo $arena['id'] ?>" style="display: none">
			<td colspan="5">
				<div class="evento-items" style="margin-bottom:10px; height: 64px;">
					<table width="693" align="center" cellpadding="5" cellspacing="0">
						<tr>
							<?php if($arena['player_id']): ?>
								<?php
									$batalhas	= Recordset::query('SELECT COUNT(id) AS total FROM arena_log WHERE player_id=' . $arena['player_id'] . ' AND arena_id=' . $arena['id']);
									$player		= Recordset::query('
										SELECT
											nome,
											id_classe,
											level
										
										FROM
											player
										
										WHERE
											id=' . $arena['player_id'])->row_array();
								?>
								<td>
									<img src="<?php echo img() ?>/layout/dojo/<?php echo $player['id_classe'] ?>.png" width="126" height="44" />
								</td>
								<td><b class="verde"><?php echo $player['nome'] ?></b><br />Lvl. <?php echo $player['level'] ?></td>
								<td><?php echo $batalhas->row()->total . ' ' . t('arena.batalhas') ?></td>
							<?php else: ?>
								<td><?php echo t('arena.sem_vencedor') ?></td>
							<?php endif ?>
							<td><?php echo $arena['inscritos'] . ' ' . t('arena.inscritos') ?></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr class="spacer"></tr>
	<?php endforeach ?>
	</table>
<?php endif ?>
