<?php
	$json			= new stdClass();
	$errors			= [];
	$json->success	= false;
	$json->messages	= [];
	
	if (!isset($_POST['squad_key']) || (isset($_POST['squad_key']) && $_POST['squad_key'] != $_SESSION['squad_key'])) {
		$json->messages[]	= t('guild.esquadrao.errors.invalid_key');

		die(json_encode($json));
	}

	$base_time	= (date('w', now()) == 0) ? now() : strtotime('last sunday', now());
	$monday		= date('Y-m-d', $base_time);
	$sunday		= date('Y-m-d', strtotime('next saturday', $base_time));

	if ($_POST['action'] == 'show') {
		ob_start();

		if(isset($_POST['guild']) && is_numeric($_POST['guild'])) {
			$guild_id	= $_POST['guild'];
		} else {
			$guild_id	= $basePlayer->id_guild;
		}
?>
<div class="squads-container">
	<?php for($f = 1; $f <= 2; $f++): ?>
		<div class="squad-players-container">
			<table width="730" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="subtitulo-home">
						<div style="font-size:28px; margin:5px 0 0 30px;  color:#f3e6c4; font-family: Mission Script"><?php echo sprintf(t('guild.esquadrao.title'), $f) ?></div>
						
					</td>
				</tr>
			</table>
			<div class="break"></div>
			<?php for($i = 1; $i <= 5; $i++): ?>
				<?php
					/*
					$slot	= Recordset::query('
						SELECT
							b.id,
							b.nome,
							b.level,
							b.id_classe,
							(CASE WHEN b.id_vila=6 THEN nome_ak_' . Locale::get() . ' ELSE nome_' . Locale::get() . ' END) AS graduation,
							(
								SELECT
									COUNT(aa.objetivo)

								FROM
									guild_objetivos_player_estatistica aa

								WHERE
									aa.id_player=b.id AND aa.id_guild=b.id_guild
							) AS objective_count,
							(
								SELECT
									SUM(bb.exp_guild)

								FROM
									guild_objetivos_player_estatistica aa
									JOIN guild_objetivos bb ON bb.objetivo=aa.objetivo

								WHERE
									aa.id_player=b.id AND aa.id_guild=b.id_guild AND bb.grupo=0
							) AS objective_exp

						FROM
							guild_esquadrao a JOIN player b ON b.id=a.id_player
							JOIN graduacao c ON c.id=b.id_graduacao

						WHERE
							a.id_guild=' . $guild_id . ' AND
							a.posicao=' . $i . '
							AND a.esquadrao=' . $f);
					*/

					$slot	= Recordset::query('
						SELECT
							b.id,
							b.nome,
							b.level,
							r.posicao_geral,
							b.id_classe,
							(CASE WHEN b.id_vila=6 THEN nome_ak_' . Locale::get() . ' ELSE nome_' . Locale::get() . ' END) AS graduation,
							(
								SELECT
									aa.missoes_solo

								FROM
									guild_missao_log aa

								WHERE
									aa.id_player=b.id AND aa.id_guild=b.id_guild
							) AS total_quests,
							(
								SELECT
									SUM(aa.exp)

								FROM
									guild_missao_log aa

								WHERE
									aa.id_player=b.id AND aa.id_guild=b.id_guild
							) AS total_exp

						FROM
							guild_esquadrao a JOIN player b ON b.id=a.id_player
							JOIN graduacao c ON c.id=b.id_graduacao
							JOIN ranking r ON r.id_player = b.id

						WHERE
							a.id_guild=' . $guild_id . ' AND
							a.posicao=' . $i . '
							AND a.esquadrao=' . $f);
				?>
				<div class="squad-player-container <?php echo !$slot->num_rows ? 'empty' : '' ?>">
					<div class="position"><span><?php echo t('guild.esquadrao.' . ($i == 1 ? 'leader' : 'member')) ?></span></div>
					<?php if ($slot->num_rows): ?>
						<?php $slot	= $slot->row_array() ?>
						<div><img src="<?php echo player_imagem($slot['id'], "pequena"); ?>" data-slot="<?php echo $i ?>" data-squad="<?php echo $f ?>" data-filled="1" /></div>
						<div class="name"><?php echo player_online($slot['id'],true)?><?php echo $slot['nome'] ?></div>
						<div class="level"><?php echo $slot['graduation'] ?> - Lvl. <?php echo $slot['level'] ?></div>
						<div class="objectives">
							<?php echo t('guild.esquadrao.total_quests') ?>: <span><?php echo (int)$slot['total_quests'] ?></span>
							<br />
							<?php echo t('guild.esquadrao.total_exp') ?>: <span><?php echo (int)$slot['total_exp'] ?></span>
                            <br />
							<?php echo t('geral.posicao_g');?>: <span><?php echo (int)$slot['posicao_geral'] ?>º</span>
						</div>
						<?php if($basePlayer->dono_guild && $basePlayer->id != $slot['id'] && $guild_id == $basePlayer->id_guild): ?>
							<a class="button b-expulsar" rel="<?php echo $slot['id'] ?>"><?php echo t('botoes.expulsar') ?></a>
							<form action="?secao=guild_detalhe&option=<?php echo encode(5) ?>" id="f-expulsar-<?php echo $slot['id'] ?>" method="post" onsubmit="return false">
								<input type="hidden" value="<?php echo $slot['id'] ?>" name="player" />
							</form>
						<?php endif ?>
					<?php else: ?>
						<div><img src="<?php echo img("layout".LAYOUT_TEMPLATE."/4x4-nenhum.jpg") ?>"  data-slot="<?php echo $i ?>" data-squad="<?php echo $f ?>" data-filled="0" /></div>
					<?php endif ?>
				</div>
			<?php endfor; ?>
			<div class="break"></div>
		</div>
	<?php endfor ?>
</div>
<?php
		$json->data		= ob_get_clean();
		$json->success	= true;
	}

	if ($_POST['action'] == 'list') {
		ob_start();

		$players	= Recordset::query('
			SELECT
				id,
				nome,
				id_classe,
				level

			FROM
				player

			WHERE
				id != (SELECT id_player FROM guild WHERE id=' . $basePlayer->id_guild . ') AND 
				id_guild=' . $basePlayer->id_guild);
?>
<div class="player-squad-select-list">
<?php foreach ($players->result_array() as $player): ?>
	<?php
		$slot	= Recordset::query('SELECT * FROM guild_esquadrao WHERE id_guild=' . $basePlayer->id_guild . ' AND id_player=' . $player['id']);
	?>
	<div class="player" data-id="<?php echo $player['id'] ?>" data-filled="<?php echo $slot->num_rows ?>">
		<img src="<?php echo img('/layout/dojo/' . $player['id_classe'] . '.png') ?>" width="126" height="44" />
		<span>
			<?php echo $player['nome'] ?><br />
			Lvl. <?php echo $player['level'] ?>
			<?php if($slot->num_rows): ?>
				<br />
				<?php echo t('guild.esquadrao.actually') ?> <?php echo $slot->row()->esquadrao ?>
				<?php echo t('guild.esquadrao.position') ?> <?php echo $slot->row()->posicao ?>
			<?php endif ?>
		</span>
	</div>
<?php endforeach ?>
</div>
<?php
		$json->data		= ob_get_clean();
		$json->success	= true;
	}

	if ($_POST['action'] == 'add') {
		if(!is_numeric($_POST['player']) || (is_numeric($_POST['player']) && !Recordset::query('SELECT id FROM player WHERE id=' . $_POST['player'] . ' AND id_guild=' . $basePlayer->id_guild)->num_rows)) {
			$errors[]	= t('guild.esquadrao.errors.invalid_player');
		}

		if(!between($_POST['slot'], 1, 5)) {
			$errors[]	= t('guild.esquadrao.errors.invalid_position');
		}

		if(!between($_POST['squad'], 1, 2)) {
			$errors[]	= t('guild.esquadrao.errors.invalid_squad');
		}

		if($_POST['squad'] == 1 && $_POST['slot'] == 1) {
			$errors[]	= t('guild.esquadrao.errors.denied');
		}

		if(!sizeof($errors)) {
			$current_slot	= Recordset::query('SELECT * FROM guild_esquadrao WHERE id_guild=' . $basePlayer->id_guild . ' AND id_player=' . $_POST['player']);
			$dest_slot	 	= Recordset::query('SELECT * FROM guild_esquadrao WHERE id_guild=' . $basePlayer->id_guild . ' AND posicao=' . $_POST['slot'] . ' AND esquadrao=' . $_POST['squad']);

			if($dest_slot->num_rows) {
				Recordset::update('guild_esquadrao', [
					'id_player'	=> $_POST['player']
				], [
					'id_guild'	=> $basePlayer->id_guild,
					'posicao'	=> $_POST['slot'],
					'esquadrao'	=> $_POST['squad']
				]);

				if($current_slot->num_rows) {
					Recordset::update('guild_esquadrao', [
						'id_player'	=> $dest_slot->row()->id_player
					], [
						'id_guild'	=> $basePlayer->id_guild,
						'posicao'	=> $current_slot->row()->posicao,
						'esquadrao'	=> $current_slot->row()->esquadrao
					]);
				}
			} else {
				if($current_slot->num_rows) {
					Recordset::delete('guild_esquadrao', [
						'id_guild'	=> $basePlayer->id_guild,
						'id_player'	=> $current_slot->row()->id_player
					]);

					$json->deleted	= true;
				}

				Recordset::insert('guild_esquadrao', [
					'id_player'	=> $_POST['player'],
					'id_guild'	=> $basePlayer->id_guild,
					'posicao'	=> $_POST['slot'],
					'esquadrao'	=> $_POST['squad']
				]);
			}

			$json->success	= true;
		}
	}

	if ($_POST['action'] == 'remove') {

	}

	$json->messages	= $errors;

	echo json_encode($json);