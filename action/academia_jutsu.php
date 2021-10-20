<?php
	if(!is_numeric($_POST['type']) || !is_numeric($_POST['filter1']) || !is_numeric($_POST['filter2'])) {
		die('D:');
	}

	$where			= '';
	$sort			= '';
	$item_type		= 5;
	$type			= $_POST['type'];
	
	switch($type) {
		case 0: // Tai, nin, gen
			$where	= 'AND sennin=0 AND id_elemento IS NULL and id_cla IS NULL AND req_item IS NULL AND removido="0"';
			$sort	= "req_graduacao ASC, req_level ASC";

			$where	.= ' AND id_habilidade=' . $_POST['filter1'];
		
			break;
			
		case 1: // Elemento
			$where	= 'AND sennin=0 AND id_elemento IS NOT NULL and id_cla IS NULL and removido="0"';
			$sort	= "req_level ASC";

			$where	.= ' AND id_elemento=' . $_POST['filter1'];
			
			break;

		case 2: // Clãs
			$where	= 'AND sennin=0 AND id_elemento IS NULL and id_cla IS NOT NULL AND removido="0"';
			$sort	= "id_habilidade ASC, req_level ASC";
			
			$where	.= ' AND id_habilidade=' . $_POST['filter1'];

			if($_POST['filter2']) {
				$where	.= ' AND id_cla=' . $_POST['filter2'];
			}
			
			break;
		
		case 3: //Medicinal
			$item_type	= 24;
			
			break;
		
		case 5: // portão
			$where	= " AND sennin=0 AND id_elemento IS NULL AND id_cla IS NULL AND id_habilidade=1 AND req_item IS NOT NULL";
			$sort	= "req_level ASC";
			
			break;

		case 6: // kinjutsu
			$item_type	= 37;
			
			break;
	}
?>
<?php
	$color_counter	= 0;
	$cp				= 0;	
	$jutsus = Recordset::query("
		SELECT
			a.id,
			a.nome_br,
			a.nome_en,
			a.descricao_br,
			a.descricao_en,
			a.imagem,
			a.id_habilidade,
			a.id_elemento,
			a.id_cla,
			a.req_graduacao
		FROM 
			item a 
			LEFT JOIN elemento d ON d.id=a.id_elemento
			LEFT JOIN cla e ON e.id=a.id_cla
			LEFT JOIN elemento f ON f.id=a.id_elemento2
			LEFT JOIN habilidade g ON g.id=a.id_habilidade
			
		WHERE 
			id_tipo=" . $item_type . " " . $where . ($sort ? " ORDER BY $sort" : ""), true);
?>
<?php foreach($jutsus->result_array() as $jutsu): ?>
	<?php
			if($basePlayer->hasItem($jutsu['id'])) {
				continue;
			}

			$show_grad	= false;

			if(!isset($grad_was_set[$jutsu['id_habilidade']])) {
				$grad_was_set[$jutsu['id_habilidade']]	= array();
			}

			if(!isset($grad_was_set[$jutsu['id_habilidade']][$jutsu['req_graduacao'] . '_' . $jutsu['id_elemento']])) {
				$grad_was_set[$jutsu['id_habilidade']][$jutsu['req_graduacao'] . '_' . $jutsu['id_elemento']]	= true;
				$show_grad	= true;
			}
			
			$cur_item = new Item($jutsu['id']);
			$cur_item->setPlayerInstance($basePlayer);
			$cur_item->parseLevel();

	
			$reqs		= Item::hasRequirement($cur_item, $basePlayer, NULL, array(
				'req_con'	=> true,
				'req_agi'	=> true,
				'req_level' => false
			));

			
			$extra			= t('academia_jutsu.aprenda_esse_jutsu').':' .
					  			'<img align="absmiddle" src="' . img('layout/icones/p_chakra.png')		. '" /> ' . ($cur_item->getAttribute('consume_sp')  * 2) . ' ' .
					  			'<img align="absmiddle" src="' . img('layout/icones/p_stamina.png')	. '" /> ' . ($cur_item->getAttribute('consume_sta') * 2);

			/*if(!$cur_item->sem_turno) {
				$extra	.= '<hr />';
			 	$req 	= $cur_item->req_agi > 0 ? $cur_item->req_agi : $cur_item->req_con;
			 	$have	= $cur_item->req_agi > 0 ? $cur_item->have_agi : $cur_item->have_con;
			 	$extra	.= '<div style="float: left">' . t('arena.' . ($cur_item->req_agi ? 'agi' : 'selo') . '_req') . ': </div><div style="float: right">' .
			 			  barra_exp3($have, $req, 132, ($have > $req ? $req : $have) . ' de '. $req, "#2C531D", "#537F3D", 2, "", true) .
						  '</div><div style="clear: both"></div>';
			}*/
			
	?>
	<table border="0" cellspacing="0" cellpadding="4">
		<?php if ($show_grad): ?>
			<tr>
				<td colspan="4" align="left" style="white-space: nowrap"><div class="titulo-home"><p><span class="laranja">//</span><?php echo graduation_name($basePlayer->id_vila, $jutsu['req_graduacao']) ?>................................................................</p></div></td>
			</tr>
		<?php endif ?>
		<tr class="<?php echo ($cur_item->req_sensei_battle ? 'cor_sensei' : (++$color_counter % 2 ? 'cor_sim' : 'cor_nao')) ?>">
			<td width="60" valign="top">
				<div class="img-lateral-dojo2"><img src="<?php echo img() ?>layout/<?php echo $jutsu['imagem']?>" id="i-jutsu-<?php echo $jutsu['id'] ?>"  width="53" height="53"  style="margin-top:5px"  /></div>
				<?php echo bonus_tooltip('i-jutsu-' . $jutsu['id'], $cur_item, NULL, $extra) ?>
			</td>
			<td align="left" width="450">
				<b class="amarelo"><?php echo $cur_item->nome ?></b>
				<br />
				<br />
				<p><?php echo $jutsu['descricao_'. Locale::get()] ?></p>
			</td>
			<td width="120">
				<img src="<?php echo img('layout/requer.gif') ?>" id="i-jutsu-req-<?php echo $jutsu['id'] ?>" style="cursor: pointer" />
				<?php echo generic_tooltip('i-jutsu-req-' . $jutsu['id'], Item::getRequirementLog()) ?>
			</td>
			<td width="100">
				<?php if($reqs): ?>
					<a class="button" onclick="doJutsu(<?php echo $jutsu['id'] ?>)"><?php echo t('botoes.treinar') ?></a>
				<?php else: ?>
					<a class="button ui-state-disabled"><?php echo t('botoes.treinar') ?></a>
				<?php endif; ?>
			</td>
		</tr>
		<tr height="4"></tr>
	</table>
<?php endforeach; ?>