<?php
	$polls	= Recordset::query('SELECT * FROM enquetes WHERE ativo=1 AND NOW() BETWEEN inicio AND fim');

	if(isset($_POST['poll']) && is_numeric($_POST['poll']) && isset($_POST['option']) && is_numeric($_POST['option'])) {
		$voted	= Recordset::query('SELECT id FROM enquete_votos WHERE enquete_id=' . $_POST['poll'] . ' AND usuario_id=' . $basePlayer->id_usuario)->num_rows;

		if(!$voted) {
			Recordset::insert('enquete_votos', [
				'usuario_id'			=> $basePlayer->id_usuario,
				'enquete_id'			=> $_POST['poll'],
				'enquete_responta_id'	=> $_POST['option']
			]);

			Recordset::update('enquete_respostas', [
				'votos'			=> ['escape' => false, 'value' => 'votos + 1']
			], [
				'id'			=> $_POST['option'],
				'enquete_id'	=> $_POST['poll']
			]);
		} else {
			$redir_script	= true;
			redirect_to('negado');
		}
	}
?>
<script type="text/javascript">
	$(document).ready(function () {
		$('#poll-vote').on('click', function () {
			if(!$('input[type=radio]:checked', this.form).length) {
				return;
			}

			lock_screen(true);

			this.form.submit();
		});
	});
</script>
<div class="titulo-secao"><p>Enquete</p></div>
<?php if (!$polls->num_rows): ?>
	Não há novas enquetes
<?php else: ?>
	<?php foreach ($polls->result_array() as $poll): ?>
		<?php
			$options	= Recordset::query('SELECT * FROM enquete_respostas WHERE enquete_id=' . $poll['id']);
			$voted		= Recordset::query('SELECT id FROM enquete_votos WHERE enquete_id=' . $poll['id'] . ' AND usuario_id=' . $basePlayer->id_usuario)->num_rows;
			$max		= 0;

			foreach ($options->result_array() as $option) {
				$max	+= $option['votos'];
			}

			$first	= false;
		?>
		<form method="post" onsubmit="return false">
			<input type="hidden" name="poll" value="<?php echo $poll['id'] ?>" />
			<div class="msg_gai">
				<div class="msg">
					<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/3.png); background-repeat: no-repeat;">
						<b style="padding-right: 10px; display: block"><?php echo $poll['nome_' . Locale::get()] ?></b>
						<?php foreach ($options->result_array() as $option): ?>
							<p>
								<label>
									<?php if (!$voted): ?>
										<input type="radio" name="option" value="<?php echo $option['id'] ?>" />
									<?php endif ?>
									<?php echo $option['nome_' . Locale::get()] ?>
								</label>
								<?php if ($voted): ?>
									<?php barra_exp3($option['votos'], $max, 132, $option['votos'] . ' Votos', "#2C531D", "#537F3D"); ?>
								<?php endif ?>
							</p>
						<?php endforeach ?>
						<br /><br />
						<?php if ($voted): ?>
							<button class="button ui-state-disabled">Votar</button>
						<?php else: ?>
							<button class="button" id="poll-vote">Votar</button>
						<?php endif ?>
					</div>
				</div>
			</div>
		</form>
	<?php endforeach ?>
<?php endif ?>