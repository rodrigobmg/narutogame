<?php
	$redir_script	= true;

	if(!isset($_GET['id']) || (isset($_GET['id']) && !is_numeric($_GET['id']))) {
		redirect_to('negado', NULL, array('e' => 1));
	}
	
	$ticket	= Recordset::query('
		SELECT 
			a.*,
			c.email,
			b.nome AS player_name,
			b.id_classe AS player_class,
			c.id AS id_usuario,
			b.id_batalha,
			b.id_batalha_multi,
			b.id_vila,
			b.id_vila_atual,
			d.nome_' . Locale::get() . ' AS nome_vila,
			e.nome_' . Locale::get() . ' AS nome_vila_atual,
			b.id_equipe,
			b.id_guild,
			b.level,
			c.vip
		
		FROM 
			suporte a LEFT JOIN player b ON b.id=a.player_id
			JOIN global.user c ON c.id=a.user_id
			LEFT JOIN vila d ON d.id=b.id_vila
			LEFT JOIN vila e ON e.id=b.id_vila_atual
		
		WHERE a.id=' . $_GET['id']);
	
	if(!$_SESSION['universal'] && $ticket->row()->id_usuario != $_SESSION['usuario']['id']) {
		redirect_to('negado', NULL, array('e' => 2));		
	}

	$base_ticket	= $ticket;
	$ticket			= $ticket->row_array();

	if ($_POST && isset($_POST['switch']) && $_POST['switch'] && $_SESSION['universal']) {
		$_SESSION['orig_user_id']	= $_SESSION['usuario']['id'];
		$_SESSION['orig_player_id']	= $_SESSION['basePlayer'];
		$_SESSION['orig_ticket_id']	= $ticket['id'];

		$_SESSION['usuario']['id']	= $ticket['user_id'];

		if (isset($_POST['with_player'])) {
			$_SESSION['basePlayer']	= $ticket['player_id'];
		} else {
			$_SESSION['basePlayer']	= null;
		}

		die();
	} elseif($_POST && isset($_POST['switch_back']) && $_POST['switch_back'] && $_SESSION['universal']) {
		$_SESSION['usuario']['id']	= $_SESSION['orig_user_id'];
		$_SESSION['basePlayer']		= $_SESSION['orig_player_id'];

		$_SESSION['orig_user_id']	= null;
		$_SESSION['orig_player_id']	= null;

		die();
	} else {
		if($_POST && $ticket['status'] != 'closed') {
			if(isset($_POST['auto']) && $_POST['auto'] && $_SESSION['universal']) {
				$_POST['content']	= Recordset::query('SELECT content_' . $_POST['auto_lang'] . ' AS content FROM suporte_resposta_auto WHERE id=' . $_POST['auto'])->row()->content;
			}
			
			Recordset::insert('suporte_resposta', array(
				'suporte_id'	=> $ticket['id'],
				'user_id'		=> $_SESSION['usuario']['id'],
				'content'		=> $_SESSION['universal'] ? $_POST['content'] : htmlentities($_POST['content'])
			));
			
			if($_SESSION['universal'] && $ticket['player_id']) {
				mensageiro(NULL, $ticket['player_id'], 'Seu ticket #' . $ticket['id'] . ' ' . t('suporte.foi_respondido'),
					t('suporte.seu_ticket') .' #' . $ticket['id'] . ' ' . t('suporte.foi_respondido').'<br />'.t('actions.a59').' <a href="?secao=suporte_ticket&id=' . $ticket['id'] . '" style="color:#000 !important; font-weight: bold">Ticket</a>', 'support');
			}
			
			if(isset($_POST['close']) && $_POST['close']) {
				Recordset::update('suporte', array(
					'status'		=> 'closed',
					'last_reply_id'	=> $_SESSION['usuario']['id']
				), array(
					'id'		=> $ticket['id']
				));
			} else {
				Recordset::update('suporte', array(
					'status'	=> $_SESSION['universal'] ? 'replied' : 'awaiting',
					'last_reply_id'	=> $_SESSION['usuario']['id']
				), array(
					'id'		=> $ticket['id']
				));
			}
			
			$ticket	= $base_ticket->repeat()->row_array();
		}
	}

	$is_first		= true;
	$files			= Recordset::query('SELECT * FROM suporte_arquivos WHERE suporte_id=' . $ticket['id']);
	$auto_answers	= Recordset::query('SELECT * FROM suporte_resposta_auto');
	$answers		= Recordset::query('
		SELECT
			a.*,
			b.name
		
		FROM suporte_resposta a LEFT JOIN global.user b ON b.id=a.user_id
		
		WHERE
			a.suporte_id=' . $ticket['id']);	
?>
<script type="text/javascript">
	$(document).ready(function(e) {
		$('.ticket-player-msg').on('click', function () {
			$('#chat-window input[name=message]').val('@' + $(this).html() + ' ').focus();
		});

		$('#switch-user').on('click', function () {
			lock_screen(true);

			$.ajax({
				url:		'?secao=suporte_ticket&id=<?php echo $ticket['id'] ?>',
				data:		{'switch': 1},
				type:		'post',
				success:	function () {
					location.reload();
				}
			});
		});

		$('#switch-player').on('click', function () {
			lock_screen(true);

			$.ajax({
				url:		'?secao=suporte_ticket&id=<?php echo $ticket['id'] ?>',
				data:		{'switch': 1, with_player: 1},
				type:		'post',
				success:	function () {
					location.reload();
				}
			});
		});
	});
</script>
<div class="titulo-secao"><p><?php echo t('suporte.suporte') ?></p></div>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="49" class="subtitulo-home">
 		<p><?php echo $ticket['title'] ?> [ ID: <?php echo $ticket['id'] ?> ]</p>
		</td>
	</tr>
</table>
<table width="730" border="0" cellpadding="2" cellspacing="0">
	<tr class="cor_nao">
		<td width="170" align="center" style="min-height: 90px; max-height: none; height:125px;">
			<?php if ($ticket['player_id']): ?>
				<img src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/criacao/pequenas/<?php echo $ticket['player_class'] ?>.png" /><br />
				<?php echo player_online($ticket['player_id'], true)?>
				<b class="amarelo" style="font-size:13px;">
					<?php if($_SESSION['universal']): ?>
						 <a href="javascript:;" class="ticket-player-msg linkTopo"><?php echo $ticket['player_name'] ?></a>
					<?php else: ?>
						 <?php echo $ticket['player_name'] ?>
					<?php endif ?>
				</b>
			<?php else: ?>
				Um personagem não foi selecionado				
				<br />
				<br />
				<br />
				<br />
			<?php endif ?>
			<?php if($_SESSION['universal']): ?>
			<div style="line-height: 18px">
				<?php echo ''.t('suporte.user').': ' . $ticket['user_id'] ?> /
				<?php if ($ticket['player_id']): ?>
					<?php echo 'Player: ' . $ticket['player_id'] ?>
					<br />
					Batalha: <?php echo $ticket['id_batalha'] ? "Sim -> " . $ticket['id_batalha'] : 'Não' ?>
					<br />
					Bt. Multi: <?php echo $ticket['id_batalha_multi'] ? "Sim -> " . $ticket['id_batalha_multi'] : 'Não' ?>
					<br />
					Vila: <?php echo $ticket['nome_vila'] . " [ " . $ticket['id_vila'] . " ]" ?>
					/
					Atual: <?php echo $ticket['id_vila_atual'] ? $ticket['nome_vila_atual'] . " [ " . $ticket['id_vila_atual'] . " ]" : 'Mapa mundi' ?>
					<br />
					Organização: <?php echo $ticket['id_guild'] ? $ticket['id_guild'] : 'Não' ?>
					/
					Equipe: <?php echo $ticket['id_equipe'] ? $ticket['id_equipe'] : 'Não' ?>
					<br />
					Nível: <?php echo $ticket['level'] ?>
					/					
				<?php endif ?>
				VIP: <?php echo $ticket['vip'] ? 'Sim' : 'Não' ?>
			</div>
			<?php endif ?>
		</td>
		<td width="560" style="min-height: 90px; max-height: none; height:125px;">
		<table style="width: 560px !important" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="25%" height="30" align="left"><b class="verde"><?php echo t('suporte.email') ?></b></td>
				<td width="25%" align="left"><b class="verde"><?php echo t('suporte.categoria') ?></b></td>
				<td width="25%" align="left"><b class="verde"><?php echo t('suporte.status') ?></b></td>
				<td width="25%" align="left"><b class="verde"><?php echo t('suporte.data_ocorrido') ?></b></td>
			</tr>
			<tr>
				<td width="25%" height="30" align="left"><?php echo $ticket['email'] ?></td>
				<td width="25%" align="left"><span class="laranja"><?php echo t('suporte.categories.' . $ticket['category']) ?></span></td>
				<td width="25%" align="left"><span class="azul"><?php echo t('suporte.statuses.' . $ticket['status']) ?></span></td>
				<td width="25%" align="left"><?php echo date('d/m/Y H:i:s', strtotime($ticket['when'])) ?> </td>
			</tr>
			<tr>
				<td width="25%" height="30" align="left"><b class="verde"><?php echo t('suporte.criacao') ?></b></td>
				<td width="25%" align="left"><b class="verde"><?php echo t('suporte.alteracao') ?></b></td>
				<td width="25%" align="left"><b class="verde"><?php echo t('suporte.navegador') ?></b></td>
				<td width="25%" align="left"><b class="verde"><?php echo t('suporte.ultimo') ?></b></td>
			</tr>
			<tr>
				<td width="25%" height="30" align="left"><?php echo date('d/m/Y H:i:s', strtotime($ticket['created_at'])) ?></td>
				<td width="25%" align="left"><?php echo date('d/m/Y H:i:s', strtotime($ticket['updated_at'])) ?></td>
				<td width="25%" align="left"><?php echo $ticket['ua'] ?></td>
				<td width="25%" align="left"><?php echo $ticket['last_reply_id'] ? Recordset::query('SELECT name FROM global.user WHERE id=' . $ticket['last_reply_id'])->row()->name : '--' ?></td>
			</tr>
		</table></td>
</tr>
<tr class="cor_nao">
	<td colspan="2" align="left" style="min-height: 90px; max-height: none; height:125px; ">
		<?php foreach($answers->result_array() as $answer): ?>
			<?php if($is_first): ?>
				<hr style="border: 2px solid <?php echo LAYOUT_TEMPLATE == "_azul" ? "#0f294a":"#413625"?>;  width: 95%"/>
				<?php if (!$_SESSION['orig_user_id'] && $_SESSION['universal']): ?>
					<div align="center">
						<a href="javascript:;" class="button" id="switch-user">Trocar para usuário</a>
						<?php if ($ticket['player_id']): ?>
							<a href="javascript:;" class="button" id="switch-player">Trocar para jogador</a>
						<?php endif ?>
					</div>
				<?php endif ?>
				<hr style="border: 2px solid <?php echo LAYOUT_TEMPLATE == "_azul" ? "#0f294a":"#413625"?>;  width: 95%"/>
				<p style="padding: 10px; word-wrap: break-word; width: 710px; line-height: 16px;"><?php echo nl2br($answer['content']) ?></p>
				<?php if($files->num_rows): ?>
					<hr style="float: left; border: dotted 1px #413625; width: 95%" />
					<div class="break"></div>
                    <div style="padding: 10px;">
                        <strong><?php echo t('suporte.anexos') ?></strong><br />
                        <ul>
                            <?php foreach($files->result_array() as $file): ?>
                                <li><a target="_blank" href="suporte_upload/<?php echo $file['file'] ?>" class="linkTopo"><?php echo $file['file'] ?></a></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                   
				<?php endif ?>
				<?php $is_first	= false ?>
			<?php else: ?>
				<hr style="border: 2px solid <?php echo LAYOUT_TEMPLATE == "_azul" ? "#0f294a":"#413625"?>; width: 95%"/>
				<p style="padding: 10px; word-wrap: break-word; width: 710px; line-height: 16px;">
                    <strong><?php echo t('suporte.resposta') ?> <span class="laranja"><?php echo $answer['name'] ?></span></strong> <?php echo t('geral.em')?> <?php echo date('d/m/Y H:i:s', strtotime($answer['created_at'])) ?>
                    <br /><br />
					<?php echo nl2br($answer['content']) ?>
                </p>   
			<?php endif ?>
		<?php endforeach ?>
	</td>
</tr>
</table>
<br />
<br />
<?php if($ticket['status'] != 'closed'): ?>
<form method="post">
	<table width="730" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td height="49" class="subtitulo-home" class="subtitulo-home">
			<p><?php echo t('suporte.adicionar_resposta') ?></p>
	
			</td>
		</tr>
	</table>
	<table width="730" border="0" cellpadding="2" cellspacing="0">
	<tr class="cor_sim">
			<td width="180" align="center" >
				<textarea name="content" cols="75" rows="10"></textarea>
				
			</td>
	</tr>
	<?php if($_SESSION['universal']): ?>
	<tr class="cor_sim">
		<td align="center">
			<select name="auto_lang" style="width: 625px">
				<option value="br">BR</option>
				<option value="en">EN</option>
			</select>
		 </td>
	</tr>
	<tr class="cor_sim">
		<td align="center">
			<select name="auto" style="width: 625px">
				<option value=""><?php echo t('suporte.nenhuma') ?></option>
				<?php foreach($auto_answers->result_array() as $answer): ?>
				<option value="<?php echo $answer['id'] ?>"><?php echo $answer['title'] ?></option>
				<?php endforeach ?>
			</select>
		 </td>
	</tr>
	<?php endif ?>
	<tr class="cor_sim">
		<td align="right">
			<br />
			<?php if($_SESSION['universal']): ?>
			<input type="checkbox" name="close">
			<span class="azul" style="position:relative;"><?php echo t('suporte.fechar') ?></span>
			<?php endif ?>
			<a class="button" data-trigger-form="1" style="margin-left: 10px; margin-right: 66px"><?php echo t('suporte.adicionar_resposta') ?></a>
			<br />
			<br />
		</td>
	</tr>
	</table>
</form>	
<?php endif ?>