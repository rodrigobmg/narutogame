<?php
	$rewards	= Recordset::query('SELECT * FROM player_recompensa_log WHERE id_player=' . $basePlayer->id . ' AND recebido=0');
	$ats		= array('ken','tai', 'nin', 'gen', 'ene', 'inte', 'forc', 'agi', 'con', 'res');
?>
<div class="titulo-secao"><p><?php echo t('recompensa_receber.title')?></p></div><br /><br />
<br/>
<script type="text/javascript">
	$(document).ready(function () {
		$('#b-receive-reward').on('click', function() {
			if($(this).hasClass('ui-state-disabled')) {
				return;
			}
		
			$(this).addClass('ui-state-disabled');
			
			$.ajax({
				url:		'?acao=recompensa_log_receber',
				success:	function () {
					<?php if($_GET['secao'] == 'recompensa_log_receber'): ?>
						location.href	= '?secao=personagem_status';
					<?php else: ?>
						location.reload();
					<?php endif ?>
				}
			});
		});
	});
</script>
<?php if($mine_rewards): ?>
	<?php msg('4', t('recompensa_receber.msg_title2') , t('recompensa_receber.msg2'));?>
	<?php require 'recompensa_log_loop.php' ?>
	<a class="button" id="b-receive-reward"><?php echo t('recompensa_receber.receive') ?></a>
<?php else: ?>
	<?php msg('5', t('recompensa_receber.msg_title1') , t('recompensa_receber.msg1'));?>
<?php endif ?>