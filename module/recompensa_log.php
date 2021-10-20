<?php
	$ats			= array('ken','tai', 'nin', 'gen', 'ene', 'inte', 'forc', 'agi', 'con', 'res');
	$rewards		= Recordset::query('SELECT * FROM player_recompensa_log WHERE id_player=' . $basePlayer->id . ' ORDER BY id DESC');
?>
<div class="titulo-secao"><p><?php echo t('recompensa_log.title')?></p></div>
<?php msg('4', t('recompensa_log.msg_title') , t('recompensa_log.msg'));?>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0" class="with-n-tabs" id="tabs-recompensa-log" data-auto-default="1">
	<tr>
	  <td><a class="button" rel=".rec-log-missao">Missões</a></td>
	  <td><a class="button" rel=".rec-log-equipe">Equipe</a></td>
	  <td><a class="button" rel=".rec-log-guild">Organização</a></td>
	  <td><a class="button" rel=".rec-log-torneio">Torneios</a></td>
	  <td><a class="button" rel=".rec-log-bingo">Bingo Book</a></td>
	  <td><a class="button" rel=".rec-log-estudo">Estudo</a></td>
	  <td><a class="button" rel=".rec-log-batalhas">Batalhas</a></td>
	  <td><a class="button" rel=".rec-log-historia">História</a></td>
	</tr>
</table>
<br />
<?php require 'recompensa_log_loop.php' ?>
