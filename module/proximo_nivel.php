<?php
	$show_torneio	= false;
	$torneio_player	= Recordset::query('SELECT * FROM torneio_player WHERE participando=\'1\' AND id_player=' . $basePlayer->id);
	
	if($torneio_player->num_rows) {
		$torneio	= Recordset::query('SELECT * FROM torneio WHERE id=' . $torneio_player->row()->id_torneio, true)->row_array();
		/*
		echo $basePlayer->level + 1;
		echo ':' . $torneio['req_level_fim'];
		*/
		if(($basePlayer->level + 1) > $torneio['req_level_fim']) {
			$show_torneio	= true;
		}
	}
?>
<script type="text/javascript">
	function doNextLevel() {
		$.ajax({
			url: 'index.php?acao=proximo_nivel',
			type: 'post',
			data: {},
			dataType: 'script'
		});
	}
</script>

<div class="titulo-secao"><p><?php echo t('titulos.proximo_nivel') ?></p></div>
<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
        <b><?php echo t('proximo_nivel.msg1'); ?></b>
		<p>
			<?php echo t('proximo_nivel.msg2'); ?><br />
		</p>
		<?php if($show_torneio): ?>
		<p>
        	<?php echo t('proximo_nivel.if_torneio'); ?>
		</p>		
		<?php endif ?>
    <br />
    <input type="button" class="button" onclick="doNextLevel();" value="<?php echo t('classes.c82')?>" />
    </div>
  </div>
</div>
