<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="60" align="center">&nbsp;</td>
          <td width="200" align="center"><b style="color:#FFFFFF"><?php echo t('recompensa_log.header.source') ?></b></td>
           <td width="200" align="center"><b style="color:#FFFFFF"><?php echo t('recompensa_log.header.reward') ?></b></td>
          <td width="200" align="center"><b style="color:#FFFFFF"><?php echo t('recompensa_log.header.received') ?></b></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<?php 
		$c = [];
		foreach($rewards->result_array() as $result): 
		
		if(array_is_empty($result, array_merge(['id_item', 'treino','treino_total', 'ryou', 'coin', 'exp'], $ats))) {
			continue;
		}
		
		$class	= '';
		
		if(in_array($result['fonte'], ['quest', 'quest_tempo', 'quest_especial','npc_vila'])) {
			$class	= 'rec-log-missao';
		}else if(in_array($result['fonte'], ['equipe_lvl', 'quest_equipe', 'equipe_pvp','equipe_npc','evento_equipe','bingo_book_equipe'])){
			$class	= 'rec-log-equipe';
		}else if(in_array($result['fonte'], ['guild_lvl', 'bingo_book_guild', 'guild_solo','guild_grupo','guild_obj_grupo','guild_obj_solo'])){
			$class	= 'rec-log-guild';
		}else if(in_array($result['fonte'], ['torneio_pvp', 'torneio_npc'])){
			$class	= 'rec-log-torneio';
		}else if(in_array($result['fonte'], ['historia'])){
			$class	= 'rec-log-historia';
		}else if(in_array($result['fonte'], ['pvp','npc','random','arena'])){
			$class	= 'rec-log-batalhas';
		}else if(in_array($result['fonte'], ['bingo_book'])){
			$class	= 'rec-log-bingo';	
		}else if(in_array($result['fonte'], ['estudo_ninja'])){
			$class	= 'rec-log-estudo';
		}else if(in_array($result['fonte'], ['historia'])){
			$class	= 'rec-log-historia';
		}

		if(!isset($c[$class])) {
			$c[$class]	= 0;
		}
		$bg		= ++$c[$class] % 2 ? "class='cor_sim'" : "class='cor_nao'";
	?>
	<tr class="<?php echo $class ?>">
		<td width="60" <?php echo $bg ?>>&nbsp;</td>
		<td width="200" height="35" <?php echo $bg ?>><strong class="amarelo" style="font-size:13px"><?php echo t('recompensa_log.reward.' . $result['fonte']) ?></strong></td>
		<td width="200" <?php echo $bg ?>>
			<span class="verde">
			<?php if($result['id_item']): ?>
				<?php echo sprintf(t('recompensa_log.reward.item'), Recordset::query('SELECT nome_' . Locale::get() . ' AS name FROM item WHERE id=' . $result['id_item'], true)->row()->name, $result['qtd_item']) ?><br />
			<?php endif ?>
			<?php if($result['treino']): ?>
				<?php echo sprintf(t('recompensa_log.reward.training'), $result['treino']) ?><br />
			<?php endif ?>
			<?php if($result['treino_total']): ?>
				<?php echo sprintf(t('recompensa_log.reward.training_total'), $result['treino_total']) ?><br />
			<?php endif ?>
			<?php if($result['reputacao']): ?>
				<?php echo sprintf(t('recompensa_log.reward.reputation'), $result['reputacao']) ?><br />
			<?php endif ?>
			<?php if($result['ryou']): ?>
				<?php echo sprintf(t('recompensa_log.reward.ryou'), $result['ryou']) ?><br />
			<?php endif ?>
			<?php if($result['coin']): ?>
				<?php echo sprintf(t('recompensa_log.reward.coin'), $result['coin']) ?><br />
			<?php endif ?>
			<?php if($result['exp']): ?>
				<?php echo sprintf(t('recompensa_log.reward.exp'), $result['exp']) ?><br />
			<?php endif ?>
			<?php if(!array_is_empty($result, $ats)): ?>
				<?php echo t('recompensa_log.reward.at') ?><br />
				<ul>
					<?php foreach($ats as $at): ?>
						<?php if(!$result[$at]) continue; ?>
						<li><?php echo $result[$at] . ' ' . t('at.' . $at) ?></li>
					<?php endforeach ?>
				<li>
			<?php endif ?>
			</li>
		</td>
		<td width="200" <?php echo $bg ?>>
			<?php if($result['recebido'] && !$result['data_recebido']): ?>
				<?php echo date('d/m/Y H:i:s', strtotime($result['data_ins'])); ?>
			<?php else: ?>
				<?php if(!$result['recebido']): ?>
					--
				<?php else: ?>
					<?php echo date('d/m/Y H:i:s', strtotime($result['data_recebido'])); ?>
				<?php endif ?>
			<?php endif ?>
		</td>
	</tr>
	<tr height="4" class="<?php echo $class ?>"></tr>
	<?php endforeach ?>

</table>
