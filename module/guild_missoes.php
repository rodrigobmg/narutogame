<?php
	$today			= date('N');
	$quests			= Recordset::query('SELECT * FROM quest_guild WHERE ativo=1', true);
	$ar_quests		= array();
	$ar_quests_made	= array();
	$quests_fail	= array();
	$guild			= Recordset::query('SELECT * FROM guild WHERE id=' . $basePlayer->getAttribute('id_guild'))->row_array();
	$quests_made	= Recordset::query('SELECT id_quest_guild, falha FROM player_quest_guild_npc_item WHERE id_player=' . $basePlayer->id . ' GROUP BY id_quest_guild')->result_array();
	$quests_made2	= Recordset::query('SELECT id_quest_guild, falha FROM guild_quest_npc_item WHERE id_guild=' . $basePlayer->getAttribute('id_guild') . ' GROUP BY id_quest_guild')->result_array();

	$quests_made	= array_merge($quests_made, $quests_made2);

	foreach(array_merge($quests_made, $quests_made2) as $quest_made) {
		$ar_quests_made[]	= $quest_made['id_quest_guild'];
	}

	foreach($quests->result_array() as $quest) {
		foreach($quests_made as $quest_made) {
			if($quest_made['falha']) {
				$quests_fail[]	= $quest_made['id_quest_guild'];
			}

			if($quest_made['id_quest_guild'] == $quest['id']) {
				continue 2;
			}
		}

		$ar_quests[] = $quest['id'];
	}
?>
<script type="text/javascript">
	$(document).ready(function () {
		$('.b-aceitar').bind('click', function () {
			$.ajax({
				url:		'?acao=guild_missoes_aceitar',
				dataType:	'script',
				type:		'post',
				data:		{quest: $(this).attr('rel')}
			});
		});
	});
</script>
<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p><?php echo t('guild_missoes.g1')?></p></div>
<br>
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "2636360978";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Guilds -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br><br>
<br><br>
<table width="730" border="0" cellpadding="0" cellspacing="2" class="with-n-tabs">
	<tr>
	  <td><a class="button" rel="#missoes-solo"><?php echo t('guild_detalhe.g22')?></a></td>
	</tr>
</table>
<br>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="subtitulo-home">
			<table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
				<tr>
					<td width="420" align="center"><?php echo t('geral.descricao')?></td>
					<td width="180" align="center"><?php echo t('geral.recompensa')?></td>
					<td width="100" align="center"><?php echo t('geral.status')?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php
	$tipos	= array('solo');
?>
<?php foreach($tipos as $tipo): ?>
	<?php
		$color_counter 	= 0;
		$cp				= 0;
	?>
    <table width="730" border="0" cellpadding="0" cellspacing="0" id="missoes-<?php echo $tipo ?>">
        <?php foreach($quests->result_array() as $quest): ?>
		<?php if($quest['tipo'] != $tipo) { continue; } ?>
        <?php
            $bg			= ++$color_counter % 2 ? "class='cor_sim'" : "class='cor_nao'";
			$pontilhado = ++$cp % 2 ? "pontilhado-listagem.jpg" : "pontilhado-listagem1.jpg";
            $tooltip	= array();

            if($quest['reputacao']) {
                //$tooltip[]	= $quest['reputacao'] .' '.  t('guild_missoes.g2');
            }

            if($quest['exp_guild']) {
                $tooltip[]	= $quest['exp_guild'] .' '.  t('guild_missoes.g3');
            }

            if($quest['ryou']) {
                if($quest['tipo'] == 'guild') {
                    $tooltip[]	=  $quest['ryou'] .' Ryous '. t('guild_missoes.g4');
                } else {
                    $tooltip[]	=  $quest['ryou'] .' Ryous '. t('guild_missoes.g5');
                }
            }

            if($quest['exp']) {
                $tooltip[]	= $quest['exp'] .' '.  t('guild_missoes.g6');
            }
        ?>
        <tr <?php echo $bg;?>>
            <td width="420" align="left" style="vertical-align:middle; padding:15px 0 15px 0;">
				<b class="amarelo" style="font-size:13px; margin-left: 7px"><?php echo $quest['nome_'. Locale::get()] ?></b>
				<br>
                <p style="margin-left: 7px"><?php echo $quest['descricao_'. Locale::get()] ?></p><br>
                <ul style="text-align:left; margin-left: 7px">
			    	<li class="azul" style="font-weight:bold;"><?php echo t('missoes.objetivos') ?>:</li><br>
                <?php
                    $quest_items = Recordset::query('
                        SELECT
                            a.*,
                            b.nome_'.Locale::get().' AS npc_nome,
                            c.nome_'.Locale::get().' AS item_nome

                        FROM
                            quest_guild_npc_item a LEFT JOIN npc b ON b.id=a.id_npc
                            LEFT JOIN item c ON c.id=a.id_item

                        WHERE
                            a.id_quest_guild=' . $quest['id'], true);
                ?>
                    <?php foreach($quest_items->result_array() as $quest_item):	?>
                    <?php if($quest_item['id_npc']): ?>
                    <li><b class='verde'><?php echo t('missoes.derrotar')?>:</b> <?php echo $quest_item['npc_total'] ?>x <?php echo t('missoes.o_npc')?> <?php echo $quest_item['npc_nome'] ?></li>
                    <?php endif; ?>
                    <?php if($quest_item['id_item']): ?>
                    <li><b class="laranja"><?php echo t('missoes.adiquirir')?>:</b> <?php echo $quest_item['item_total'] ?>x <?php echo $quest_item['item_nome'] ?></li><br>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
			</td>
            <td width="180"><img src="<?php echo img('layout/requer.gif') ?>" id="i-reward-<?php echo $quest['id'] ?>" /> <?php echo generic_tooltip('i-reward-' . $quest['id'], $tooltip) ?></td>
            <td width="100">
				<?php if($quest['tipo'] == 'guild' && !$basePlayer->getAttribute('dono_guild')): ?>
					<?php echo t('guild_missoes.g7')?>
				<?php elseif($quest['tipo'] == 'guild' && $basePlayer->getAttribute('id_missao_guild2')): ?>
					<?php echo t('guild_missoes.g8')?>
				<?php elseif($guild['membros'] != 9 && $quest['tipo'] == 'guild'): ?>
					<a class="button ui-state-disabled"><?php echo t('botoes.aceitar') ?></a>
				<?php else: ?>
					<?php if(in_array($quest['id'], $quests_fail)): ?>
						<a class="button ui-state-red"><?php echo t('botoes.falha') ?></a>
					<?php elseif(in_array($quest['id'], $ar_quests)): ?>
						<a class="button b-aceitar" data-trigger-form="1" rel="<?php echo $quest['id'] ?>"><?php echo t('botoes.aceitar') ?></a>
					<?php elseif(in_array($quest['id'], $ar_quests_made)): ?>
						<a class="button ui-state-green"><?php echo t('botoes.concluido') ?></a>
					<?php else: ?>
						<a class="button ui-state-disabled"><?php echo t('botoes.aceitar') ?></a>
					<?php endif; ?>
				<?php endif; ?>
			</td>
        </tr>
		<tr height="4"></tr>
        <?php endforeach; ?>
    </table>
<?php endforeach ?>
</div>
