<div class="titulo-secao"><p><?php echo t('guild_missoes.g1')?></p></div>
<br />
<?php
	$finalize		= true;
	$helper			= $basePlayer->hasItem(20794);
	$quest			= Recordset::query('SELECT * FROM quest_guild WHERE id=' . $basePlayer->getAttribute('id_missao_guild'), true)->row_array();
	$quest_items	= Recordset::query('
		SELECT 
			a.*,
			b.nome_'.Locale::get().' AS npc_nome,
			c.nome_'.Locale::get().' AS item_nome,
			b.x1,
			b.y1,
			b.x2,
			b.y2
		
		FROM 
			quest_guild_npc_item a LEFT JOIN npc b ON b.id=a.id_npc
			LEFT JOIN item c ON c.id=a.id_item
		
		WHERE 
			a.id_quest_guild=' . $quest['id'], true);
?>
<div class="msg_gai" >
		<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
		<b><?php echo $quest['nome_' . Locale::get()] ?></b>
		<p><?php echo $quest['descricao_' . Locale::get()] ?><br /><br />


		<ul>
		<?php foreach($quest_items->result_array() as $quest_item): ?>
		<?php
			$my_item = Recordset::query('
				SELECT
					a.*
				FROM
					player_quest_guild_npc_item a
				
				WHERE
					a.id_player=' . $basePlayer->id . '
					AND a.id_quest_guild=' . $basePlayer->getAttribute('id_missao_guild') . '
					AND a.id_npc=' . $quest_item['id_npc'] . '
					AND a.id_item=' . $quest_item['id_item'] . '
			')->row_array();
			
			$finalize	= $quest_item['npc_total']  - $my_item['npc_total']  > 0 ? false : $finalize;
			$finalize	= $quest_item['item_total'] - $my_item['item_total'] > 0 ? false : $finalize;
			
			$lack_npc	= $quest_item['npc_total']  - $my_item['npc_total'];
			$lack_item	= $quest_item['item_total'] - $my_item['item_total'];
			$diff 		= get_time_difference(date('Y-m-d H:i:s'), $my_item['conclusao']);
		?>
		<?php if($quest_item['id_npc']): ?>
			<?php $npc_style = $my_item['npc_total'] >= $quest_item['npc_total'] ? 'style="font-size:11px; text-decoration:line-through; color:#ffffff; font-weight:bold;"' : ''; ?>
			<div style="width: 165px; height:auto; float: left">
				<li <?php echo $npc_style ?>>
					<strong class="verde"><?php echo t('missoes.derrotar_npc') ?> <?php echo $quest_item['npc_nome'] ?></strong>
					<?php echo barra_exp3($my_item['npc_total'], $quest_item['npc_total'], 132, $my_item['npc_total'] . ' '.t('equipe_detalhe.e35').' ' . $quest_item['npc_total'] .' ' . t('missoes.vezes'), "#2C531D", "#537F3D",2);?>
					<br />
					<strong class="verde"><?php echo t('missoes.coordenadas') ?></strong><br />
					<span class="cinza">
					X ( <?php echo $quest_item['x1'] ?> <?php echo t('missoes.ate') ?> <?php echo $quest_item['x2'] ?> )<br />
					Y ( <?php echo $quest_item['y1'] ?> <?php echo t('missoes.ate') ?> <?php echo $quest_item['y2'] ?> )
					</span>
				</li>
			<?php if(!$quest_item['id_item']): ?>
			</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php if($quest_item['id_item']): ?>
			<?php $item_style = $my_item['item_total'] >= $quest_item['item_total'] ? 'style="font-size:11px; text-decoration:line-through; color:#ffffff; font-weight:bold;"' : ''; ?>
			<br />
			<li <?php echo $item_style ?>>
				<strong class="laranja"><?php echo t('missoes.adiquirir_itens') ?> ( <?php echo $quest_item['item_nome']?> )</strong>
				<?php echo barra_exp3( $my_item['item_total'], $quest_item['item_total'], 132, $my_item['item_total'] . " " . t('missoes.de') . " " . $quest_item['item_total'], "#2C531D", "#537F3D",2); ?>
			</li><br />
			</div>
		<?php endif; ?>
		<?php endforeach; ?>
		</ul>
		
<?php if($finalize): ?>
<form method="post" action="?acao=guild_missoes_finaliza">
	<div align="center" style="clear:both"><br />
		<a class="button" data-trigger-form="1"><?php echo t('botoes.finalizar_missao') ?></a>
	</div>	
</form>
<?php else: ?>
<form id="f-cancel" method="post" action="?acao=guild_missoes_cancelar" onsubmit="return false;">
	<div align="center" style="clear:both"><br />
		<span style="font-size:13px; margin-right: 50px" class="laranja"><?php echo t('missoes_status.conclusao'); ?> <span id="cnTimer">--:--:--</span></span>
		<script type="text/javascript">
			createTimer(<?php echo $diff['hours'] ?>, <?php echo $diff['minutes'] ?>, <?php echo $diff['seconds'] ?>, 'cnTimer', null, null, true);
		</script>
		<a class="button" id="b-cancelar" data-trigger-form="1"><?php echo t('botoes.cancelar_missao') ?></a>
	</div>
</form>
<script type="text/javascript">
	$('#b-cancelar').bind('click', function () {
		jconfirm('<?php echo t('missao_guild.cancelar') ?>', null, function () {
			$('#f-cancel')[0].submit();			
		});
	});
</script>
<?php endif; ?>
</p>
		</div>
	</div>
</div>
