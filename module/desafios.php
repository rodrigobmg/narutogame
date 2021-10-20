<script>
	$(document).ready(function(e) {
		$('.sensei-batalha').bind('click', function() {
			$.ajax({
				url:	'?acao=sensei_aceitar',
				dataType:	'json',
				type:		'post',
				data:	{npc: $(this).data('npc')},
				success:	function (result) {
				if(result.success) {
						location.href	= 'index.php?secao=dojo_batalha_lutador';
					} else {
						lock_screen(false);
						format_error(result);
					}
				}
			});
		});
	});
</script>
<?php
	$q_player_sensei 			= Recordset::query("SELECT * FROM player_sensei_desafios WHERE id_player=" . $basePlayer->id . " AND id_sensei = ". $basePlayer->id_sensei);
	$q_rank_sensei 			= Recordset::query("SELECT * FROM ranking_sensei WHERE id_sensei =". $basePlayer->id_sensei." ORDER BY posicao_sensei ASC LIMIT 1");
	$q_player_rank_sensei 	= Recordset::query("SELECT * FROM ranking_sensei WHERE id_player=" . $basePlayer->id . " AND id_sensei =". $basePlayer->id_sensei);
	$sensei 				= Recordset::query("SELECT * FROM sensei WHERE id = ". $basePlayer->id_sensei)->row_array();

  if ($q_player_sensei->num_rows) {
    $player_sensei = $q_player_sensei->row_array();
  } else {
    $player_sensei = [
      'desafio' => 0,
      'wins' => 0,
      'losses' => 0,
      'draws' => 0,
    ];
  }

  if ($q_rank_sensei->num_rows) {
    $rank_sensei = $q_rank_sensei->row_array();
  } else {
    $rank_sensei = [
      'posicao_geral' => 0,
      'posicao_sensei' => 0,
    ];
  }

  if ($q_player_rank_sensei->num_rows) {
    $player_rank_sensei = $q_player_rank_sensei->row_array();
  } else {
    $player_rank_sensei = [
      'posicao_geral' => 0,
      'posicao_sensei' => 0,
    ];
  }

	// Escolhe o NPC da Vez.
	$sensei_npc 	= explode(",",$sensei['id_npcs']);
	$sensei_npc 	= $sensei_npc[array_rand($sensei_npc)];
	$sensei_npc		= ($player_sensei && $player_sensei['desafio'] % 5 == 0) ? $sensei['id_boss'] : $sensei_npc;
	
	// Qual é o desafio?
	$desafio = $player_sensei ? $player_sensei['desafio'] : 0;
	
	// Instancia o NPC
	$baseEnemy = new NPC($sensei_npc, $basePlayer, NPC_SENSEI, false, $desafio);
	
?>
<div class="titulo-secao"><p><?php echo t('menus.desafios')?></p></div><br />
<table width="730" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td align="left" style="vertical-align:top">
		<div class="h-combates">
			<div class="h-combates-div"><span class="amarelo" style="font-family:Mission Script; font-size:22px"><?php echo $sensei['nome']?></span></div>
			<div style="width: 230px; text-align: center; padding-top: 18px; font-size: 12px !important; line-height: 14px;">
				<img src="<?php echo img() ?>/layout/sensei/<?php echo $sensei['id']?>.png" class="<?php echo $basePlayer->id_sensei != $sensei['id'] ? "apagado" : "" ?>"/><br />
				<br /><span class="verde"><?php echo t('sensei.melhor_aluno') ?>:</span> - <?php echo $rank_sensei['nome']?><br />
				<span class="verde"><?php echo t('sensei.desafio_vencido') ?>:</span> <?php echo $rank_sensei['desafio']?> <br />
				<span class="laranja"><?php echo t('sensei.qtd_alunos') ?>:</span> <?php echo $basePlayer->sensei_count($sensei['id'])->total?> <br /><br />
			</div>
		</div>
		<div class="h-missoes">
			<div class="h-combates-div"><span class="amarelo" style="font-family:Mission Script; font-size:22px"><?php echo t('status.treino_at') ?></span></div>
			<div style="width: 230px; text-align: center; padding-top: 18px; font-size: 12px !important; line-height: 14px;">
				<br /><span class="verde"><?php echo t('sensei.desafio_atual') ?>:</span> <?php echo $player_sensei['desafio']?> <br />
				<span class="verde"><?php echo t('geral.posicao_g') ?>:</span> <?php echo $player_rank_sensei['posicao_geral']?>º <br />
				<span class="verde"><?php echo t('geral.posicao') ?> Sensei:</span> <?php echo $player_rank_sensei['posicao_sensei']?>º <br />
			</div>
		</div>
		<div class="h-treinamento">
			<div class="h-combates-div"><span class="amarelo" style="font-family:Mission Script; font-size:22px"><?php echo t('status.resumo_combate')?></span></div>
			<div style="width: 230px; text-align: center; padding-top: 18px; font-size: 12px !important; line-height: 14px;">
				<br /><span class="verde"><?php echo t('sensei.total') ?>:</span> <?php echo $player_sensei['wins'] + $player_sensei['losses'] + $player_sensei['draws']?>  <br />
				<span class="verde"><?php echo t('sensei.vitorias') ?>:</span> <?php echo $player_sensei['wins']?> <br />
				<span class="vermelho"><?php echo t('sensei.derrotas') ?>:</span> <?php echo $player_sensei['losses']?> <br />
				<span class="cinza"><?php echo t('status.empate') ?>:</span> <?php echo $player_sensei['draws']?> <br />


			</div>
		</div>
	</td>
    </tr>
</table>
<br />
<?php if($player_sensei['desafio'] != 0 && $player_sensei['desafio'] % 5 == 0): ?>
	<div class="msg_gai">
		<div class="msg">
			<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/sensei/<?php echo $sensei['id'];?>.png); background-repeat: no-repeat;">
					<b><?php echo t('sensei.msg1') ?></b>
					<p><?php echo t('sensei.msg2') ?></p>
			   </div>
		</div>	  
	</div>	
<?php endif; ?>
<div class="break"></div>
<div style="width: 730px;" class="titulo-home"><p><span class="laranja">//</span> <?php echo $player_sensei['desafio'] > 0 ? $player_sensei['desafio'] : 0?>º <?php echo t('sensei.desafio_sensei') ?> ............................................................</p></div>
<br /><br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<?php echo player_imagem_ultimate($basePlayer) ?>
			<div class="character-info">
				<div class="name" style="font-weight: bold; font-size:14px"><?php echo $basePlayer->nome ?></div>
				<div class="headline"><?php echo $basePlayer->nome_titulo ?></div>
			</div>
		</td>
		<td width="255" rowspan="2" align="center" valign="top"><img src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/combate/vs.png'); ?>"/></td>
		<td>
			<img src="<?php echo img( $baseEnemy->getAttribute('imagem')); ?>"/>
			<div class="character-info">
				<div class="name" style="font-weight: bold; font-size:14px"><?php echo $baseEnemy->getAttribute('nome') ?></div>
				<div class="headline"></div>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="3"><br /><input type="button" class="button sensei-batalha" data-npc="<?php echo encode($sensei_npc) ?>" value="<?php echo t('botoes.aceitar')?>" /></td>
	</tr>
</table>

