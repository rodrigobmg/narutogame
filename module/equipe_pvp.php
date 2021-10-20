<?php
	$team_mine	= Recordset::query('SELECT a.*, b.id_vila, ((SELECT SUM(level) FROM player aa WHERE aa.id_equipe=a.id) / 4) AS media_level FROM equipe a JOIN player b ON b.id=a.id_player WHERE a.id=' . $basePlayer->id_equipe)->row_array();
?>
<script>
	$(document).ready(function () {
		var	_can_accept		= false;
		var	_is_accepting	= false;
		var	_can_queue		= false;
		var	_is_queueing	= false;
	
		// Search timer
		setInterval(function () {
			if(_is_accepting) {
				return;
			}

			$.ajax({
				url:		'?acao=equipe_pvp',
				type:		'post',
				data:		{mode: 'search'},
				success:	function (result) {
					$('#team-search').html(result);
					
					_can_accept	= true;
					
					
					$('.accept', $('#team-search')).on('click', function () {
						if(_can_accept) {
							_can_accept		= false;
							_is_accepting	= true;

							$.ajax({
								url:		'?acao=equipe_pvp',
								type:		'post',
								data:		{mode: 'battle', id: $(this).data('id')},
								dataType:	'json',
								success:	function (result) {
									if(result.success) {
										setTimeout(function () {
											location.href	= '?secao=dojo_batalha_multi_pvp';
										}, 2000);
									} else {
										var errors	= [];
										
										result.messages.forEach(function (error) {
											errors.push('<li>' + error + '</li>');
										});
										
										jalert('<ul>' + errors.join('') + '</ul>');
										lock_screen(false);
										
										_is_accepting	= false;
									}
								}
							});							
						} else {
							return;
						}
					
						lock_screen(true);
					});
				}
			});
		}, 5000);

		// Search timer
		setInterval(function () {
			if(_is_queueing) {
				return;
			}

			$.ajax({
				url:		'?acao=equipe_pvp',
				type:		'post',
				data:		{mode: 'queue'},
				success:	function (result) {
					$('#team-queue').html(result);

					_can_queue	= true;

					$('.accept', $('#team-queue')).on('click', function () {
						if(_can_queue) {
							_can_queue		= false;
							_is_queueing	= true;
							
							$.ajax({
								url:		'?acao=equipe_pvp',
								type:		'post',
								data:		{mode: 'make-queue'},
								dataType:	'json',
								success:	function (result) {
									if(result.success) {
										location.href	= '?secao=espera_multi_pvp';
									} else {
										var errors	= [];
										
										result.messages.forEach(function (error) {
											errors.push('<li>' + error + '</li>');
										});
										
										jalert('<ul>' + errors.join('') + '</ul>');
										lock_screen(false);

										_is_queueing	= false;
									}
								}
							});
						} else {
							return;
						}
					
						lock_screen(true);
					});
				}
			});
		}, 5000);
	});
</script>
<div class="titulo-secao"><p><?php echo t('equipe.pvp.title'); ?></p></div>
<?php echo msg(6, t('equipe.pvp.title'), t('equipe.pvp.msg').' '. round($team_mine['media_level']) .'</span>') ?>
<table width="730" border="0" align="center" cellpadding="0" cellspacing="0" class="with-n-tabs">
	<tr>
	<td><a class="button" rel="#team-search"><?php echo t('equipe.pvp.search') ?></a></td>
	<td><a class="button" rel="#team-queue"><?php echo t('equipe.pvp.queue') ?></a></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
</table>
<br />
<div id="team-search">
	<?php echo t('evento4.e4')?>
</div>
<div id="team-queue">
	<?php echo t('evento4.e4')?>
</div>