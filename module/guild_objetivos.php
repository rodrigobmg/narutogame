<div class="titulo-secao"><p><?php echo t('guild.objetivos.options.obj')?></p></div>
<?php msg(6,''.t('guild.objetivos.options.obj2').'',''.t('guild.objetivos.options.obj3').''); ?>
<?php
	$solos	= Recordset::query('SELECT * FROM guild_objetivos WHERE grupo=0');
	$groups	= Recordset::query('SELECT * FROM guild_objetivos WHERE grupo=1');
?>
<br />
<table width="730" border="0" align="center" cellpadding="0" cellspacing="0" class="with-n-tabs" data-auto-default="1" id="tab-guild-objectives">
	<tr>
		<td><a class="button" rel="#solo-objectives"><?php echo t('guild.objetivos.options.solo') ?></a></td>
		<td><a class="button" rel="#group-objectives"><?php echo t('guild.objetivos.options.group') ?></a></td>
	</tr>
</table>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="subtitulo-home">
			<table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
				<tr>
					<td align="center" width="60">&nbsp;</td>
					<td align="center" width="350"><?php echo t('geral.descricao') ?></td>
					<td width="80" align="center"><?php echo t('geral.premios') ?></td>
					<td width="230" align="center">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div id="solo-objectives">
	<table width="730" border="0" cellpadding="0" cellspacing="0">
		<?php 
			$cn = 0;
			foreach ($solos->result_array() as $solo):
			$cor = ++$cn % 2 ? "#413625" : "#251a13";
		 ?>
			<?php
				$totals			= Recordset::query('SELECT * FROM guild_objetivos_player WHERE objetivo=' . $solo['objetivo'] . ' AND id_guild=' . $basePlayer->id_guild . ' AND id_player=' . $basePlayer->id);
				$totals_other	= Recordset::query('
					SELECT
						*

					FROM
						guild_objetivos_player

					WHERE
						objetivo=' . $solo['objetivo'] . ' AND
						id_player=' . $basePlayer->id . ' AND
						id_guild!=' . $basePlayer->id_guild);

				if($totals->num_rows) {
					$total	= $totals->row()->total;
				} else {
					$total	= 0;
				}

				if($totals_other->num_rows && $totals_other->row()->recompensa) {
					$total	= (int)$totals_other->row()->total;
				}
			?>
			<tr bgcolor="<?php echo $cor?>">
				<td width="60" align="center">
					<img src="<?php echo img()?>/layout/icon/<?php echo ($total >= $solo['total'] ? "button_ok" : "button_cancel" )?>.png" />
				</td>
				<td width="350" align="center"><b class="amarelo"><?php echo $solo['nome_' . Locale::get()] ?></b></td>
				<td width="80" align="center">
					<img src="<?php echo img('layout/requer.gif') ?>" id="i-objetivo-<?php echo $solo['id'] ?>" style="cursor: pointer" />
					<?php 
					$premio = "
					<b class='verde'>".t('geral.premios')."</b><br /><br />
					
					".t('guild.objetivos.options.obj4').": ". $solo['exp_player'] ."<br />
					".t('guild.objetivos.options.obj5').": ". $solo['exp_guild'] ."<br />
					".t('geral.g74').": ". $solo['reputacao'] ."<br />
					Ryous: ". $solo['ryous'] ."<br />
                	 ". t('geral.pontos_treino') .":  ". $solo['treino'] ."<br />";
					
					?>
					
					<?php echo generic_tooltip('i-objetivo-'.$solo['id'], $premio )?>

				</td>
				<td width="230" align="center">
					<?php barra_exp3($total, $solo['total'], 132,  $total . ' ' . t('geral.de') . ' ' . $solo['total'], "#2C531D", "#537F3D", 1); ?>
				</td>
			</tr>
			<tr height="4"></tr>
		<?php endforeach ?>
	</table>
</div>
<div id="group-objectives">
	<table width="730" border="0" cellpadding="0" cellspacing="0">
		<?php 
		$cn = 0;
		foreach ($groups->result_array() as $group): 
		$cor = ++$cn % 2 ? "#413625" : "#251a13";
		?>
			<?php
				$totals	= Recordset::query('
					SELECT
						SUM(total) AS total

					FROM
						guild_objetivos_player

					WHERE
						objetivo=' . $group['objetivo'] . ' AND
						id_guild=' . $basePlayer->id_guild);


				$total	= (int)$totals->row()->total;
			?>
			<tr bgcolor="<?php echo $cor?>">
				<td width="60" align="center">
					<img src="<?php echo img()?>/layout/icon/<?php echo ($total >= $group['total'] ? "button_ok" : "button_cancel" )?>.png" />
				</td>
				<td width="350" align="center"><b class="amarelo"><?php echo $group['nome_' . Locale::get()] ?></b></td>
				<td width="80" align="center">
					<img src="<?php echo img('layout/requer.gif') ?>" id="i-objetivo-group-<?php echo $group['id'] ?>" style="cursor: pointer" />
					
					<?php 
					$premio = "
					<b class='verde'>".t('geral.premios')."</b><br /><br />
					
					".t('guild.objetivos.options.obj4').": ". $group['exp_player'] ."<br />
					".t('guild.objetivos.options.obj5').": ". $group['exp_guild'] ."<br />
					".t('geral.g74').": ". $group['reputacao'] ."<br />
					Ryous: ". $group['ryous'] ."<br />
                	 ". t('geral.pontos_treino') .":  ". $group['treino'] ."<br />";
					
					?>
					
					<?php echo generic_tooltip('i-objetivo-group-'.$group['id'], $premio )?>

				</td>
				<td width="230" align="center">
					<?php barra_exp3($total, $group['total'], 132,  $total . ' ' . t('geral.de') . ' ' . $group['total'], "#2C531D", "#537F3D", 1); ?>
				</td>
			</tr>
			<tr height="4"></tr>
		<?php endforeach ?>
	</table>
</div>