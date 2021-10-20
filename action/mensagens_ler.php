<?php
	$vila	= false;

	if(isset($_GET['reply']) && $_GET['reply'] && is_numeric($_GET['reply'])) {
		$_GET['id'] = $_GET['reply'];
	}

	if(isset($_GET['vila']) && $_GET['vila']) {
		if($basePlayer->vila_ranking != 1) {
			echo '<div class="error">'.t('actions.a259').'</div>';
			die();
		} else {
			$vila	= true;
		}
	}

	if(isset($_GET['id']) && $_GET['id'] && !isset($_GET['global']) && !isset($_GET['vila_ler'])) {
	   	$message =  Recordset::query('SELECT a.*, (SELECT nome FROM player WHERE id=a.id_envio) AS nome_envio FROM mensagem a WHERE id=' . (int)$_GET['id'] . ' AND a.id_para=' . $basePlayer->id)->row_array();
	   	
	   	Recordset::update('mensagem', array(
	   		'lida'	=> '1'
	   	), array(
	   		'id'	=> (int)$_GET['id']
	   	));
	} elseif(isset($_GET['id']) && $_GET['id'] && isset($_GET['global'])) {
	   	$message =  Recordset::query('SELECT a.*, \'Aviso Interno Naruto Game\' AS nome_envio, a.titulo_'.Locale::get().' AS titulo,a.corpo_'.Locale::get().' AS corpo  FROM mensagem_global a WHERE id=' . (int)$_GET['id'])->row_array();

	   	if(!Recordset::query('SELECT * FROM mensagem_global_lida WHERE id_player=' . $basePlayer->id . ' AND id_mensagem_global=' . (int)$_GET['id'])->num_rows) {
	   		Recordset::insert('mensagem_global_lida', array(
	   			'id_mensagem_global'	=> (int)$_GET['id'],
	   			'id_player'				=> $basePlayer->id
	   		));
	   	}
	} elseif(isset($_GET['id']) && $_GET['id'] && isset($_GET['vila_ler'])) {
	   	$message =  Recordset::query('SELECT a.*, \'Mensagem do Kage de sua vila\' AS nome_envio, a.titulo, a.corpo AS corpo  FROM mensagem_vila a WHERE id=' . (int)$_GET['id'])->row_array();

	   	if(!Recordset::query('SELECT * FROM mensagem_vila_lida WHERE id_player=' . $basePlayer->id . ' AND id_mensagem_vila=' . (int)$_GET['id'])->num_rows) {
	   		Recordset::insert('mensagem_vila_lida', array(
	   			'id_mensagem_vila'	=> (int)$_GET['id'],
	   			'id_player'			=> $basePlayer->id
	   		));
	   	}
	}
	
	if(!isset($message)) {
		$message = array(
			'nome_envio'	=> ''
		);
	}
?>
<?php if(isset($_GET['id']) && $_GET['id'] && !isset($_GET['reply'])): ?>
<div class="form">
	<input type="hidden" value="<?php echo $_GET['id'] ?>" id="h-messages-current-meesage-id" />
	<p>
		<label><b><?php echo t('actions.a227')?></b></label>
		<div style="background-color: #FFFFFF; color: #000; padding: 5px;"><?php echo $message['nome_envio'] ?></div>
	</p>
    <br />
	<p>
		<label><b><?php echo t('geral.titulo')?></b></label>
		<div style="background-color: #FFFFFF; color: #000; padding: 5px;"><?php echo htmlspecialchars($message['titulo']) ?></div>
	</p>
    <br />
	<p>
		<label><b><?php echo t('geral.mensagem')?></b></label>
		<div style="height: 200px; overflow: auto; background-color: #FFFFFF; color: #000000; padding: 5px;" class="linkTopo">
			<?php if(isset($message['id_envio']) && $message['id_envio']): ?>
			<?php echo nl2br($message['corpo']) ?>
			<?php else: ?>
			<?php echo nl2br($message['corpo']) ?>			
			<?php endif; ?>
		</div>
	</p>
</div>
<?php else:	?>
<form id="f-messages-compose">
	<input type="hidden" name="reply_id" id="f-messages-compose-h-reply-id" value="<?php echo isset($_GET['reply']) ? $_GET['reply'] : '' ?>" />
	<?php if(!$vila): ?>
	<p>
		<label><b><?php echo t('actions.a228')?></b></label><br />
		<input type="text" id="f-messages-compose-t-to" name="to" style="width:460px;" size="35" value="<?php echo isset($_POST['to']) && $_POST['to'] ? $_POST['to'] : $message['nome_envio'] ?>" />
	</p>
    <br />
    <?php else: ?>
    <input type="hidden" name="vila" value="1" />
    <?php endif; ?>
	<p>
		<label><b><?php echo t('geral.titulo')?></b></label><br />
		<input type="text" name="subject" size="35" style="width:460px;" value="<?php echo isset($_POST['subject']) && $_POST['subject'] ? $_POST['subject'] : (isset($_GET['reply']) && $_GET['reply'] ? 'Re:' . $message['titulo'] : '') ?>" />
	</p>
    <br />
    <p>
		<label><b><?php echo t('geral.mensagem')?></b></label><br />
		<textarea name="content"  style="height: 100px; width:460px;"><?php echo isset($_POST['content']) && $_POST['content'] ? $_POST['content'] : (isset($_GET['reply']) && $_GET['reply'] ? "\n\n--------\n" . $message['corpo'] : '') ?></textarea>
	</p>
</form>
<?php endif; ?>	