<?php
	$helper			= $basePlayer->hasItem(20794);
	$finalize		= true;
	$quest			= Recordset::query('SELECT * FROM quest_guild WHERE id=' . $basePlayer->getAttribute('id_missao_guild2'), true)->row_array();
	$quest_items	= Recordset::query('
		SELECT 
			a.*,
			b.nome_' . Locale::get() . ' AS npc_nome,
			c.nome_' . Locale::get() . ' AS item_nome,
			b.x1,
			b.y1,
			b.x2,
			b.y2,
			d.nome_'.Locale::get().' AS nome_vila,
			d.id AS id_vila
		
		FROM 
			quest_guild_npc_item a LEFT JOIN npc b ON b.id=a.id_npc
			LEFT JOIN item c ON c.id=a.id_item LEFT JOIN vila d ON d.id=b.id_vila
		
		WHERE 
			a.id_quest_guild=' . $basePlayer->getAttribute('id_missao_guild2'), true);
?>
<div class="titulo-secao"><p><?php echo t('guild_missoes_status_guild.g1')?></p></div>
<br />
<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
        <b><?php echo $quest['nome_' . Locale::get()] ?></b>
        <p>
		<?php echo $quest['descricao_' . Locale::get()] ?>
        <ul>
        
        <br /><br />
        <?php foreach($quest_items->result_array() as $quest_item): ?>
        <?php
            $my_item = Recordset::query('
                SELECT
                    a.*
                FROM
                    guild_quest_npc_item a
                
                WHERE
                    a.id_guild=' . $basePlayer->getAttribute('id_guild') . '
                    AND a.id_quest_guild=' . $basePlayer->getAttribute('id_missao_guild2') . '
                    AND a.id_npc=' . $quest_item['id_npc'] . '
                    AND a.id_item=' . $quest_item['id_item'] . '
            ')->row_array();
			
            $finalize	= $quest_item['npc_total']  - $my_item['npc_total']  > 0 ? false : $finalize;
            $finalize	= $quest_item['item_total'] - $my_item['item_total'] > 0 ? false : $finalize;
            
            $lack_npc	= $quest_item['npc_total']  - $my_item['npc_total'];
            $lack_item	= $quest_item['item_total'] - $my_item['item_total'];
			$diff 		= get_time_difference(date('Y-m-d H:i:s'), $my_item['conclusao']);
        ?>
        <?php $npc_style = $my_item['npc_total'] >= $quest_item['npc_total'] ? 'style="font-size:11px; text-decoration:line-through; color:#ffffff; font-weight:bold;"' : ''; ?>
        <?php if($quest_item['id_npc']): ?>
            <li <?php echo $npc_style ?>><span class="laranja" style="font-weight:bold"><?php echo t('missoes.derrotar')?>: </span> <span class="cinza"><?php echo $quest_item['npc_total'] ?>x <?php echo $quest_item['npc_nome'] ?></span><br />
            <?php if($helper): ?>
            <span class="azul"><?php echo t('geral.coordenada')?> X <?php echo t('geral.de')?> <?php echo $quest_item['x1'] ?> <?php echo t('geral.ate')?> <?php echo $quest_item['x2'] ?> <?php echo t('geral.e')?> Y <?php echo t('geral.de')?> <?php echo $quest_item['y1'] ?> <?php echo t('geral.ate')?> <?php echo $quest_item['y2'] ?></span><br />
            <?php endif; ?>
            <?php if($quest_item['id_vila']): ?>
            <?php echo t('geral.no')?> <strong><?php echo $quest_item['nome_vila'] ?></strong>
            <?php endif ?>								
            </li>
            <?php barra_exp3($my_item['npc_total'], $quest_item['npc_total'], 327, "".  $my_item['npc_total'] ." de ". $quest_item['npc_total'] ." NPCs Derrotados.", "#2C531D", "#537F3D", 6) ?>
            <br /><br />
        <?php endif; ?>
        <?php $item_style = $my_item['item_total'] >= $quest_item['item_total'] ? 'style="font-size:11px; text-decoration:line-through; color:#ffffff; font-weight:bold;"' : ''; ?>
        <?php if($quest_item['id_item']): ?>
            <li <?php echo $item_style ?>> <?php echo t('geral.adquirir')?> <?php echo $quest_item['item_total'] ?>x <?php echo $quest_item['item_nome'] ?>
            (<?php echo t('geral.faltando')?> <?php echo $lack_item < 0 ? 0 : $lack_item  ?>)
            </li>
        <?php endif; ?>
        <?php endforeach; ?>
        </ul>
        <?php if($basePlayer->getAttribute('dono_guild')): ?>
            <?php if($finalize): ?>
            <form method="post" action="?acao=guild_missoes_finaliza">
            <input type="hidden" name="guild" value="1" />
            <input type="submit" value="Finalizar missão >" />
            </form>
            <?php else: ?>
			<span style="font-size:13px; margin-right: 50px" class="laranja"><?php echo t('missoes_status.conclusao'); ?> <span id="cnTimer">--:--:--</span></span>
			<script type="text/javascript">
				createTimer(<?php echo $diff['hours'] + (24 * $diff['days']) ?>, <?php echo $diff['minutes'] ?>, <?php echo $diff['seconds'] ?>, 'cnTimer', null, null, true);
			</script>
            <form id="f-cancel" method="post" action="?acao=guild_missoes_cancelar" style="display: inline" onsubmit="return false;">
				<input type="hidden" name="guild" value="1" />
				<input type="submit" class="button" id="b-cancelar" value="<?php echo t('botoes.cancelar_missao')?>" />
            </form>
            <script type="text/javascript">
                $('#b-cancelar').bind('click', function () {
					jconfirm('<?php echo t('missao_guild.cancelar2') ?>', null, function () {
						$('#f-cancel')[0].submit();			
					});
                });
            </script>
            <?php endif; ?>
		<?php endif ?>
		</p>
        </div>
    </div>
</div>