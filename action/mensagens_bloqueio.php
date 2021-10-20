<?php
	if(isset($_GET['option'])) {
		switch($_GET['option']) {
			case 1:
				$qPlayer = Recordset::query("SELECT id_player FROM player_nome WHERE nome='" . addslashes($_POST['name']) . "'");
				if(!$qPlayer->num_rows) {
					echo '<div class="error">'.t('actions.a217').'</div>';
				} else {
					$rPlayer = $qPlayer->row_array();

					Recordset::query("INSERT INTO mensagem_bloqueio(id_player, id_playerb) VALUES({$basePlayer->id}, {$rPlayer['id_player']})");
				}

				break;

			case 2:
				Recordset::query("DELETE FROM mensagem_bloqueio WHERE id_player=" . $basePlayer->id . " AND id_playerb=" . $_POST['player']);

				break;
		}		
	}

	$players = Recordset::query('SELECT a.nome, a.id, b.id AS id_player_bloqueio FROM player a JOIN mensagem_bloqueio b ON b.id_playerb=a.id AND b.id_player=' . $basePlayer->id)->result_array();
?>
<form class="form" id="f-messages-blocklist" onsubmit="return false">
	<p>
		<label><?php echo t('actions.a218')?></label><br /><br />
		<input type="text" name="name" style="width:300px" value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>" />
		<button onclick="messageDoBlock()" style="float: right" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
			<span class="ui-button-text"><?php echo t('actions.a219')?></span>
		</button>
	</p>
</form><br /><br />
<table width="100%" border="0" cellpadding="4" cellspacing="0">
	<?php if(!sizeof($players)): ?>
	<tr>
		<td><?php echo t('actions.a220')?></td>
	</tr>
	<?php endif; ?>
	<?php 
		$color_counter = 0;
		
		foreach($players as $p): 
		
		$bg		= ++$color_counter % 2 ? "bgcolor='#111111'" : "bgcolor='#1c1c1c'";
	?>
	<tr <?php echo $bg ?>>
		<td><?php echo $p['nome'] ?></td>
		<td>
			<button onclick="messageDoUnblock(<?php echo $p['id'] ?>)" style="float: right" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
				<span class="ui-button-text"><?php echo t('actions.a221')?></span>
			</button>		
		</td>
	</tr>
	<?php endforeach; ?>
</table>