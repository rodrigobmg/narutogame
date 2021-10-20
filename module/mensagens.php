<?php
	$items_per_page = 10;
	$types			= ['guild','team','player','achiv','battle','support','amigos'];
?>
<div class="titulo-secao"><p>Mensageiro</p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "6926959778";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Mensageiro -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br /><br /><br />
<?php if($basePlayer->vila_ranking == 1): ?>
	<a class="button" onclick="messageDoCompose(null, null, true)"><?php echo t('botoes.nova_mensagem_vila') ?></a>
<?php endif ?>
<?php if($basePlayer->vip || $basePlayer->level >= 10): ?>
	<a class="button" onclick="messageDoCompose()"><?php echo t('botoes.nova_mensagem') ?></a>
<?php else: ?>
	<?php msg('3',''.t('geral.g36').'', ''.t('geral.g37').'');?>
<br />
<?php endif; ?>
<a class="button" onclick="messageDoBlockList()"><?php echo t('botoes.bloquear_ninjas') ?></a>
<a class="button" onclick="messageDoRemoveAll()"><?php echo t('botoes.remover_mensagens') ?></a>
<br /><br /><br />
<table width="730" border="0" align="center" cellpadding="0" cellspacing="0" class="with-n-tabs" data-auto-default="1" id="messages-tab-view">
	<tr>
		<?php foreach ($types as $type): ?>
			<?php
				$unread	= Recordset::query("
					SELECT
						COUNT(a.id) AS total

					FROM
						mensagem a

					WHERE
						a.removida='0' AND
						a.id_para=" . $basePlayer->id . " AND
						a.mensagem_tipo='" . $type . "' AND
						a.lida=0

					ORDER BY id DESC");
			?>
			<td>
				<a class="button" rel="#messages-<?php echo $type ?>">
					<?php echo t('mensageiro.types.' . $type) ?>
					<?php if ($unread->row()->total): ?>
						(<?php echo $unread->row()->total ?>)
					<?php endif ?>
				</a>
			</td>
		<?php endforeach ?>
	</tr>
</table>
<br /><br /><br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="49" class="subtitulo-home">
			<table width="730" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="90" align="center">&nbsp;</td>
					<td width="260" align="center"><b style="color:#FFFFFF"><?php echo t('geral.assunto')?></b></td>
					<td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.remetente')?></b></td>
					<td width="160" align="center"><b style="color:#FFFFFF"><?php echo t('geral.entregue')?></b></td>
					<td width="80" align="center">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table width="730" border="0" cellpadding="4" cellspacing="0">
<?php 
	$mensagens_globais = Recordset::query('
		SELECT 
			a.*, 
			corpo_'.Locale::get().' AS corpo,
			titulo_'.Locale::get().' AS titulo,
			
			\''.t('geral.g38').'\' AS nome_envio, 
			b.id_player AS lida 
		FROM 
			mensagem_global a LEFT JOIN mensagem_global_lida b ON b.id_mensagem_global=a.id AND b.id_player=' . $basePlayer->id.' ORDER BY a.id desc'); 

	$mensagens_vila = Recordset::query('
		SELECT 
			a.*, 
			corpo,
			titulo,
			
			(SELECT kage FROM vila WHERE id=a.id_vila) AS nome_envio, 
			b.id_player AS lida 
		FROM 
			mensagem_vila a LEFT JOIN mensagem_vila_lida b ON b.id_mensagem_vila=a.id AND b.id_player=' . $basePlayer->id.'
		
		WHERE
			a.id_vila=' . $basePlayer->id_vila .' AND
			a.id NOT IN(SELECT id_mensagem_vila FROM mensagem_vila_removida WHERE id_player=' . $basePlayer->id . ')
		
		ORDER BY a.id desc');

	$c2 = 0;
	$c3 = 0;
?>
<?php foreach($mensagens_globais->result_array() as $mensagem): ?>
	<tr bgcolor="<?php echo ++$c2 % 2 ? "#8b1d1c" : "#952423" ?>">
		<td width="90" height="34">
		<?php if(!$mensagem['lida']): ?>
			<img src="<?php echo img('layout/mail_unread.gif') ?>" width="16" height="16" alt="<?php echo t('geral.g39')?>" />
		<?php endif; ?> 	
		</td>
		<td width="260">
			<b><a href="javascript:;" class="linkCabecalho" onclick="doMessageRead(<?php echo $mensagem['id'] ?>, 1)">
      		<?php echo $mensagem['titulo'] ?>
      		</a></b>
		</td>
		<td width="140">
			<?php echo $mensagem['nome_envio'] ?>
		</td>
		<td width="160"> <?php echo date("d/m/Y", strtotime($mensagem['data_ins'])) ?>
        as
        <?php echo date("H:i:s", strtotime($mensagem['data_ins'])) ?></td>
		<td width="80"></td>
	</tr>
<?php endforeach; ?>
<?php foreach($mensagens_vila->result_array() as $mensagem): ?>
	<tr bgcolor="<?php echo ++$c2 % 2 ? "#287cbf" : "#1467a8" ?>">
		<td width="90" height="34">
		<?php if(!$mensagem['lida']): ?>
			<img src="<?php echo img('layout/mail_unread.gif') ?>" width="16" height="16" alt="<?php echo t('geral.g39')?>" />
		<?php endif; ?> 	
		</td>
		<td width="260">
			<b><a href="javascript:;" class="linkCabecalho" onclick="doMessageRead(<?php echo $mensagem['id'] ?>, 0, 1)">
      		<?php echo $mensagem['titulo'] ?>
      		</a></b>
		</td>
		<td width="140">
			<?php echo $mensagem['nome_envio'] ?>
		</td>
		<td width="160"> <?php echo date("d/m/Y", strtotime($mensagem['data_ins'])) ?>
        as
        <?php echo date("H:i:s", strtotime($mensagem['data_ins'])) ?></td>
		<td width="80">
		
		</td>
	</tr>
<?php endforeach; ?>
</table>
<?php foreach ($types as $type): ?>
	<div id="messages-<?php echo $type ?>">
		<table width="730" border="0" cellpadding="4" cellspacing="0">
		<?php
			$mensagens	= Recordset::query("
				SELECT
					a.*,
					(SELECT nome FROM player WHERE id=a.id_envio) AS nome_envio,
					(SELECT id_usuario FROM player WHERE id=a.id_envio) AS id_usuario

				FROM
					mensagem a

				WHERE
					a.removida='0' AND
					a.id_para=" . $basePlayer->id . " AND
					a.mensagem_tipo='" . $type . "'

				ORDER BY id DESC");
			$counter	= 0;
			$c			= 0;
		?>
		<?php foreach($mensagens->result_array() as $r): ?>
			<?php $counter++; ?>
			<?php if(on($r['id_usuario'], array(2,4,6))): ?>
			<tr id="message-tr-<?php echo $r['id'] ?>" class="message-item-<?php echo $type ?> message-item-<?php echo $type ?>-<?php echo floor($counter / $items_per_page) ?> <?php echo ++$c2 % 2 ? "cor_sim" : "cor_nao" ?>">
			<?php else: ?>	
			<tr id="message-tr-<?php echo $r['id'] ?>" class="message-item message-item-<?php echo floor($counter / $items_per_page) ?> <?php echo ++$c2 % 2 ? "cor_sim" : "cor_nao" ?>">
			<?php endif; ?>	
				<td width="90" height="34" align="center">
					<?php if(!$r['lida']): ?>
					<img src="<?php echo img() ?>layout/mail_unread.gif" width="16" height="16" alt="<?php echo t('geral.g39')?>" />
					<?php endif; ?>
				</td>
				<td width="260">
					<b>
						<a href="javascript:;" class="linkCabecalho" onclick="doMessageRead(<?php echo $r['id'] ?>)">
							<?php echo $r['titulo'] ?>
						</a>
					</b>
				</td>
				<td width="140" height="34" align="center"><?php echo $r['nome_envio'] ?></td>
				<td width="160" align="center">
					<p>
					<?php echo date("d/m/Y", strtotime($r['data_ins'])) ?>
					as
					<?php echo date("H:i:s", strtotime($r['data_ins'])) ?>
					</p>
				</td>
				<td width="80" align="center">
					<a href="javascript:;" onclick="messageDoCompose(<?php echo $r['id'] ?>)"><img src="<?php echo img() ?>layout/mail2_(edit)_16x16.gif" alt="" border="0"></a>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
		<?php if($mensagens->num_rows): ?>
			<div class="paginator" style="clear:both; width: 730px">
				<?php for($f = 0; $f < ($counter / $items_per_page); $f++): ?>
				<a  style="color:#FFF; text-decoration:none" href="javascript:messagePage(<?php echo $f ?>, '<?php echo $type ?>')" class="linkTopo"><?php echo $f + 1 ?></a>
				<?php endfor; ?>
			</div>
		<?php else: ?>
			<br /><br /><br />
			<?php echo t('mensageiro.no_messages') ?>
		<?php endif; ?>
	</div>
<?php endforeach ?>
<script type="text/javascript">
	head.ready(function () {
	<?php if(isset($_GET['msg']) && $_GET['msg']): ?>
		messageDoCompose(0, '<?php echo $_GET['msg'] ?>');
	<?php endif; ?>
		messagePage(0);
	});
</script>
