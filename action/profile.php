<?php
	$i	= $basePlayer->getVIPItem(array(1027, 1079, 1080));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Personagem Status - Naruto Game</title>
<link href="css/html<?php echo LAYOUT_TEMPLATE?>.css" rel="stylesheet" type="text/css"/>
<link href="css/layout<?php echo LAYOUT_TEMPLATE?>.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="http://narutogame.com.br/js/jquery.js"></script>
<script type="text/javascript" src="http://narutogame.com.br/js/tools.tooltip-1.0.2.js"></script>
</head>
<body>
<?php
	$ar_imagens	= array(
		'agi'				=> 'layout/icones/agi.png',
		'con'				=> 'layout/icones/conhe.png',
		'for'				=> 'layout/icones/forc.png',
		'int'				=> 'layout/icones/inte.png',
		'res'				=> 'layout/icones/defense.png',
		'nin'				=> 'layout/icones/nin.png',
		'gen'				=> 'layout/icones/gen.png',
		'tai'				=> 'layout/icones/tai.png',
		'ken'				=> 'layout/icones/ken.png',
		'atk_fisico'		=> 'layout/icones/atk_fisico.png',
		'atk_magico'		=> 'layout/icones/atk_magico.png',
		'def_base'			=> 'layout/icones/shield.png',
		'def_fisico'		=> 'layout/icones/shield_fisico.png',
		'def_magico'		=> 'layout/icones/shield_magico.png',
		'ene'				=> 'layout/icones/ene.png',
		'hp'				=> 'layout/icones/p_hp.png',
		'sp'				=> 'layout/icones/p_chakra.png',
		'sta'				=> 'layout/icones/p_stamina.png',
		'def_base'			=> 'layout/icones/shield.png',
		'prec_fisico'		=> 'layout/icones/prec_tai.png',
		'prec_magico'		=> 'layout/icones/prec_nin_gen.png',
		'crit_min'			=> 'layout/icones/p_stamina.png',
		'crit_max'			=> 'layout/icones/p_stamina.png',
		'esq_min'			=> 'layout/icones/p_stamina.png',
		'esq_max'			=> 'layout/icones/p_stamina.png',
		'esq'				=> 'layout/icones/esquiva.png',
		'det'				=> 'layout/icones/deter.png',
		'conv'				=> 'layout/icones/convic.png',
		'conc'				=> 'layout/icones/target2.png'
	);

	$pWidth = "240";
	
	if(!is_numeric($_GET['id'])) {
		$_GET['id'] = decode($_GET['id']);
	
		if(!is_numeric($_GET['id'])) {
			redirect_to("negado");
		}
	}
	
	if(!Recordset::query('SELECT id FROM player WHERE id=' . (int)$_GET['id'])->num_rows) {
		redirect_to("negado");		
	}

	$curPlayer = new Player((int)$_GET['id']);	

	$bar_width_1	= "132";
	$max_ats_1 		= max($curPlayer->getAttribute('ken_calc'),$curPlayer->getAttribute('tai_calc'), $curPlayer->getAttribute('nin_calc'), 
				  $curPlayer->getAttribute('gen_calc'), $curPlayer->getAttribute('con_calc'), 
				  $curPlayer->getAttribute('int_calc'), $curPlayer->getAttribute('ene_calc'), 
				  $curPlayer->getAttribute('for_calc'), $curPlayer->getAttribute('agi_calc'));


	$rr = Recordset::query("SELECT * FROM player_quest_status WHERE id_player=" . $curPlayer->id)->row_array();
	
	$rank = Recordset::query("SELECT * FROM ranking WHERE id_player=" . $curPlayer->id)->row_array();
	$rank_conquista = Recordset::query("SELECT * FROM ranking_conquista WHERE id_player=" . $curPlayer->id)->row_array();

	
	$bb_count				= (int)Recordset::query('SELECT COUNT(id) AS total FROM bingo_book WHERE id_player=' . $curPlayer->id . ' AND morto="1"')->row()->total;
	$arena_count			= (int)Recordset::query('SELECT COUNT(id) AS total FROM arena WHERE player_id=' . $curPlayer->id)->row()->total;
	$npc_tounament_count	= (int)Recordset::query('
		SELECT
			SUM(a.vitorias) AS total

		FROM
			torneio_player a JOIN torneio b ON b.id=a.id_torneio

		WHERE
			b.npc="1" AND
			a.id_player=' . $curPlayer->id)->row()->total;

	$pvp_tounament_count	= (int)Recordset::query('
		SELECT
			SUM(a.vitorias) AS total

		FROM
			torneio_player a JOIN torneio b ON b.id=a.id_torneio

		WHERE
			b.npc="0" AND
			a.id_player=' . $curPlayer->id)->row()->total;


?>
<div class="titulo-secao"><p><?php echo t('titulos.status') ?></p></div><br />
<div id="esquerda" style="margin-top: 0">
    <div id="menu_repete_action">
    <div id="character-data">
      <div id="character-image" style="margin:0;"><?php echo player_imagem_ultimate($curPlayer->id) ?></div>
		<div id="character-info">
			<div class="name"><?php echo $curPlayer->nome ?>
        <div class="headline">
          <select name="sPlayerTitulo" id="sPlayerTitulo" onchange="doPlayerTitulo()" style="width: 230px;">
            <?php
              $qTitulo = Recordset::query("SELECT titulo_" . Locale::get() . " AS titulo, id FROM player_titulo WHERE id_usuario=" . $curPlayer->id_usuario);

              while($rTitulo = $qTitulo->row_array()):
            ?>
						<option <?php echo $rTitulo['id'] === $curPlayer->id_titulo ? "selected='selected'" : "" ?> value="<?php echo encode($rTitulo['id']) ?>" disabled="disabled">
              <?php echo htmlspecialchars($rTitulo['titulo']) ?>
            </option>
					<?php endwhile; ?>
			</select><br />
            </div>
        </div>
        </div>
        <div id="character-status">
			<b><?php echo t('status.classe') ?>: </b>
			<?php
				switch($curPlayer->id_classe_tipo){
					case 2:
						echo t('classe_tipo.nin');
					break;
					case 1:
						echo t('classe_tipo.tai');
					break;
					case 3:
						echo t('classe_tipo.gen');
					break;
					case 4:
						echo t('classe_tipo.ken');
					break;
				}
			?><br />
			<b><?php echo t('status.vila') ?>: </b> <?php echo $curPlayer->nome_vila?><br />
			<b>Level:</b> <?php echo $curPlayer->getAttribute('level') ?><br />
			<b><?php echo t('jogador_vip.jv36')?>: <?php echo $curPlayer->getAttribute('fight_power') ?></b><br />
			<b><?php echo t('status.grad') ?>: </b> <?php echo graduation_name($curPlayer->id_vila, $curPlayer->id_graduacao)  ?><br />
			<?php if($curPlayer->getAttribute('nome_cla')):?>
			<b><?php echo t('status.cla') ?>: </b><?php echo $curPlayer->getAttribute('nome_cla') ?><br />
			<?php else: ?>
			<b><?php echo t('status.portao') ?>: </b><?php echo $curPlayer->getAttribute('nome_portao') ?><br />
			<?php endif;?>
			<?php if($curPlayer->getAttribute('nome_selo')):?>
			<b><?php echo t('status.selo') ?>: </b><?php echo $curPlayer->getAttribute('nome_selo') ?><br />
			<?php elseif($curPlayer->getAttribute('nome_sennin')): ?>
			<b><?php echo t('status.sennin') ?>: </b><?php echo $curPlayer->getAttribute('nome_sennin') ?><br />
			<?php endif; ?>
			<b><?php echo t('status.invocacao') ?>: </b><?php echo $curPlayer->getAttribute('nome_invocacao') ?><br />
			<?php 
				$elementos	= array();
				
				foreach($curPlayer->getElementosA() as $elemento) {
					$elementos[] = $elemento['nome'];
				}
			?>
			<?php if(sizeof($elementos)): ?>
				<?php if($basePlayer->hasItem(array(1027, 1079, 1080))): ?>
					<b><?php echo t('status.elementos') ?>: </b><?php echo join(' / ', $elementos) ?></span><br />
				<?php endif;?>	
			<?php endif ?>
			<b><?php echo t('status.rank_vila') ?>: </b><?php echo $rank['posicao_vila'] ? $rank['posicao_vila'] . "&deg;" : "-" ?><br />
			<b><?php echo t('status.rank_geral') ?>: </b><?php echo $rank['posicao_geral'] ? $rank['posicao_geral'] . "&deg;" : "-" ?><br />
			<b>Score: </b><?php echo $rank['pontos'] ?><br />
			<b><?php echo t('status.pt_conquista') ?>: </b><?php echo $rank_conquista['pontos'] ? $rank_conquista['pontos'] : "-" ?><br />
			<b><?php echo t('status.equipe') ?>: </b><?php echo $curPlayer->getAttribute('id_equipe')? $curPlayer->getAttribute('nome_equipe') : "Nenhum(a)" ?><br />
			<b><?php echo t('status.guild') ?>: </b><?php echo $curPlayer->getAttribute('id_guild') ? $curPlayer->getAttribute('nome_guild') : "Nenhum(a)" ?><br />
			<b><?php echo t('status.ult_atividade') ?>: </b><?php echo  date("d/m/Y", strtotime($curPlayer->getAttribute('ult_atividade'))) . " &agrave;s " . date("H:i:s", strtotime($curPlayer->getAttribute('ult_atividade'))); ?><br />
			<br /><br />
		</div>	
</div>
<div id="menu_fundo" class="menu_fundo_profile" style="margin-left: -16px;"></div>
</div>
</div>
<div id="direita-action">
		<div class="h-combates">
			<div style="width: 225px; text-align: center; padding-top: 48px"><b class="amarelo" style="font-family: Mission Script; font-size: 22px;"><?php echo t('status.resumo_combate')?></b></div>
			<div style="width: 225px; text-align: center; padding-top: 16px; font-size: 12px !important; line-height: 14px;">
				<span class="verde"><?php echo t('status.vitoria_dojo') ?>:</span> <?php echo (int)$curPlayer->vitorias_d ?> <br />
				<span class="verde"><?php echo t('status.vitoria_dojo_pvp') ?>:</span> <?php echo (int)$curPlayer->vitorias ?> <br />
				<span class="verde"><?php echo t('status.vitoria_mapa_pvp') ?>:</span> <?php echo (int)$curPlayer->vitorias_f ?> <br />
				<span class="verde"><?php echo t('status.vitoria_rnd') ?>:</span> <?php echo (int)$curPlayer->vitorias_rnd ?> <br />
				<span class="vermelho"><?php echo t('status.derrota_npc') ?>:</span> <?php echo (int)$curPlayer->derrotas_npc ?> <br />
				<span class="vermelho"><?php echo t('status.derrota_pvp') ?>:</span> <?php echo (int)$curPlayer->derrotas ?> <br />
				<span class="vermelho"><?php echo t('status.derrota_pvp2') ?>:</span> <?php echo (int)$curPlayer->derrotas_f ?> <br />
				<span class="vermelho"><?php echo t('status.derrota_rnd') ?>:</span> <?php echo (int)$curPlayer->derrotas_rnd ?> <br />
				<?php echo t('status.empate') ?>:</span> <?php echo (int)$curPlayer->empates ?> <br />
				<?php echo t('status.fugas') ?>:</span> <?php echo (int)$curPlayer->fugas ?>
			</div>
		</div>
		<div class="h-missoes">
			<div style="width: 225px; text-align: center; padding-top: 48px"><b class="amarelo" style="font-family: Mission Script; font-size: 22px;"><?php echo t('status.missoes_completas') ?></b></div>
			<div style="width: 225px; text-align: center; padding-top: 16px; font-size: 12px !important; line-height: 14px;">
				<span class="verde"><?php echo t('status.rank_s') ?>:</span> <?php echo (int)$rr['quest_s'] ?> OK / <?php echo (int)$rr['falha_s'] ?> <?php echo t('missoes.falhas') ?><br />
				<span class="verde"><?php echo t('status.rank_a') ?>:</span> <?php echo (int)$rr['quest_a'] ?> OK / <?php echo (int)$rr['falha_a'] ?> <?php echo t('missoes.falhas') ?><br />
				<span class="verde"><?php echo t('status.rank_b') ?>:</span> <?php echo (int)$rr['quest_b'] ?> OK / <?php echo (int)$rr['falha_b'] ?> <?php echo t('missoes.falhas') ?><br />
				<span class="verde"><?php echo t('status.rank_c') ?>:</span> <?php echo (int)$rr['quest_c'] ?> OK / <?php echo (int)$rr['falha_c'] ?> <?php echo t('missoes.falhas') ?><br />
				<span class="verde"><?php echo t('status.rank_d') ?>:</span> <?php echo (int)$rr['quest_d'] ?> OK / <?php echo (int)$rr['falha_d'] ?> <?php echo t('missoes.falhas') ?><br />
				<span class="verde"><?php echo t('status.tarefas') ?>:</span> <?php echo (int)$rr['tarefa'] ?>
			</div>
		</div>
		<div class="h-treinamento">
			<div style="width: 225px; text-align: center; padding-top: 48px"><b class="amarelo" style="font-family: Mission Script; font-size: 22px;"><?php echo t('status.treino_at') ?></b></div>
			<div style="width: 225px; text-align: center; padding-top: 16px; font-size: 12px !important; line-height: 14px;">
				<span class="laranja"><?php echo t('status.treino_total') ?>:</span> <?php echo $curPlayer->treino_total ?><br />
				<span class="laranja"><?php echo t('status.pontos_dist') ?>:</span> <?php echo $curPlayer->treino_gasto ?><br />
				<span class="laranja"><?php echo t('status.tl_bb') ?>:</span> <?php echo $bb_count ?><br />
				<span class="laranja"><?php echo t('status.tl_hr') ?>:</span> <?php echo $curPlayer->played_hours ?>h<br />
				<span class="laranja"><?php echo t('status.tl_torneio_npc') ?>:</span> <?php echo $npc_tounament_count ?><br />
				<span class="laranja"><?php echo t('status.tl_torneio_pvp') ?>:</span> <?php echo $pvp_tounament_count ?><br />
				<span class="laranja"><?php echo t('status.tl_arena') ?>:</span> <?php echo $arena_count ?><br />
			</div>
		</div>
<?php if($basePlayer->hasItem(array(1027, 1079, 1080))): ?>
	<div align="center"><?php echo t('templates.t62')?> <?php echo $i['vezes'] - $i['uso'] ?> <?php echo t('templates.t63')?>.</div>
	<br />
	<br />
<?php endif ?>

<table width="730" border="0" cellpadding="0" cellspacing="0" >
<?php if($basePlayer->hasItem(array(1027, 1079, 1080))): ?>
	<?php if($i['uso'] < $i['vezes']): ?>
	<?php
		$basePlayer->useVIPItem($i);
		$anti	= anti_espionagem($curPlayer->id);
	?>
	<?php if(!$anti): ?>
	<tr>
		<td width="50%" style="vertical-align:top">
		<div class="titulo-home2"><p><?php echo t('status.atributos')?></div>
		<?php
				$ar_habilidades	= array(
					'tai',
					'ken',
					'nin',
					'gen',
					'for',
					'agi',
					'int',
					'con',
					'res',
					'ene',
					'conc',
					'esq',
					'conv',
					'det'
				);
				
				$cn = 0;
		?>
		<?php foreach($ar_habilidades as $habil):	?>
		<?php $cn++; ?>
		
		<div class="bg_td">
			<div class="cinza atr_float" style="width: 90px; text-align:left; padding-left:8px;"><?php echo t('at.' . $habil) ?></div>
			<div class="atr_float"  style="width: 30px; text-align:left; margin-top: 4px;">
				<img src="<?php echo img($ar_imagens[$habil]) ?>" id="i-<?php echo $habil ?>" />
				<?php 
				 if($habil == "ene"){
					 echo generic_tooltip('i-' . $habil, t('at.desc.' . $habil) . "<br /><br /><b class='bold_bege'>".t('status.class')." + ".t('actions.a257').": </b>". $curPlayer->getAttribute($habil . '_raw') . "<br /><b class='bold_bege'>".t('actions.a258').": </b>". $curPlayer->getAttribute($habil . '_item') . "<br /><b class='bold_bege'>".t('templates.t59').": </b>" . $curPlayer->getAttribute($habil . '_arv') . "<br /><br /><b class='bold_bege'>Total: </b>" . ($curPlayer->getAttribute($habil . '_calc')+$curPlayer->getAttribute($habil . '_calc2')));
				 }else if($habil == "conc" || $habil == "esq" || $habil == "conv" || $habil == "det"){
					 echo generic_tooltip('i-' . $habil, t('at.desc.' . $habil) . "<br /><br /><b class='bold_bege'>".t('status.class')." + ".t('actions.a257').": </b>". $curPlayer->getAttribute($habil . '_raw2') . "<br /><b class='bold_bege'>".t('actions.a258').": </b>". $curPlayer->getAttribute($habil . '_item') . "<br /><b class='bold_bege'>".t('templates.t59').": </b>" . $curPlayer->getAttribute($habil . '_arv') . "<br /><br /><b class='bold_bege'>Total: </b>" . ($curPlayer->getAttribute($habil . '_calc2')));
				 }else{
					 echo generic_tooltip('i-' . $habil, t('at.desc.' . $habil). "<br /><br /><b class='bold_bege'>".t('status.class')." + ".t('actions.a257').": </b>". $curPlayer->getAttribute($habil . '_raw') . "<br /><b class='bold_bege'>".t('actions.a258').": </b>". $curPlayer->getAttribute($habil . '_item') . "<br /><b class='bold_bege'>".t('templates.t59').": </b>" . $curPlayer->getAttribute($habil . '_arv') . "<br /><br /><b class='bold_bege'>Total: </b>" . $curPlayer->getAttribute($habil . '_calc'));
				 }
				 ?>
			</div>
			<div class="atr_float"  style="width: 80px; text-align:left;">
				<?php if($habil == "conc" || $habil == "esq" || $habil == "conv" || $habil == "det"){?>
					<span class="amarelo_claro">+ <?php echo  $curPlayer->getAttribute($habil . '_raw2') ?> ( + <?php echo $curPlayer->getAttribute($habil . '_arv') + $curPlayer->getAttribute($habil . '_item'); ?> )</span>
				<?php }else{?>
					<span class="amarelo_claro">+ <?php echo  $curPlayer->getAttribute($habil . '_raw') ?> ( + <?php echo $curPlayer->getAttribute($habil . '_arv') + $curPlayer->getAttribute($habil . '_item'); ?> )</span>
				<?php }?>
			</div>
			
			<div class="atr_float">
				<?php if($habil == "ene"){
					barra_exp3($curPlayer->getAttribute($habil . '_calc')+$curPlayer->getAttribute($habil . '_calc2'), $max_ats_1, $bar_width_1, $curPlayer->getAttribute($habil . '_calc')+$curPlayer->getAttribute($habil . '_calc2'), "#2C531D", "#537F3D", $cn % 2 ? 1 : 2);
				}else if($habil == "conc" || $habil == "esq" || $habil == "conv" || $habil == "det"){
					barra_exp3($curPlayer->getAttribute($habil . '_calc2'), $max_ats_1, $bar_width_1, $curPlayer->getAttribute($habil . '_calc2'), "#2C531D", "#537F3D", $cn % 2 ? 1 : 2);
				}else{
					barra_exp3($curPlayer->getAttribute($habil . '_calc'), $max_ats_1, $bar_width_1, $curPlayer->getAttribute($habil . '_calc'), "#2C531D", "#537F3D", $cn % 2 ? 1 : 2);
				}
				?>
			</div>
		</div>	
			<?php endforeach; ?>
		
		</td>
		<td width="50%" align="right">
		<div class="titulo-home"><p><?php echo t('status.formulas')?></p></div>
		<?php
			$ar_habilidades	= array(
				'hp',
				'sp',
				'sta',
				'atk_fisico',
				'atk_magico',
				'def_fisico',
				'def_magico',
				'prec_fisico',
				'prec_magico',
				'esq',
				'det',
				'conv',
				'conc'
			);
			
			$cn = 0;
		?>
		<?php foreach($ar_habilidades as $habil): ?>
		<?php
		$cn++;
		
		$desc_extra = '';
		
		if($habil == 'conc') {
			$desc_extra .= '<br /><br /><b class="bold_bege">' . t('formula.crit_min') . ':</b> ' . $curPlayer->getAttribute('crit_min_calc') . ' %<br />';
			$desc_extra .= '<b class="bold_bege">' . t('formula.crit_max') . ':</b> ' . $curPlayer->getAttribute('crit_max_calc') ." %";
		}
		
		if($habil == 'esq') {
			$desc_extra .= '<br /><br /><b class="bold_bege">' . t('formula.esq_min') . ':</b> ' . $curPlayer->getAttribute('esq_min_calc') . ' %<br />';
			$desc_extra .= '<b class="bold_bege">' . t('formula.esq_max') . ':</b> ' . $curPlayer->getAttribute('esq_max_calc') ." %";
		}
		?>
		<div class="bg_td2" style="left: -2px;">
		<div class="cinza atr_float" style="width: 103px; text-align:left; padding-left:8px;"><?php echo t('formula2.' . $habil) ?></div>
		<div class="atr_float"  style="width: 30px; text-align:left; margin-top: 4px;">
			<img src="<?php echo img($ar_imagens[$habil]) ?>" id="i-<?php echo $habil ?>" />
			<?php echo generic_tooltip('i-' . $habil, t('formula.desc.' . $habil) . $desc_extra) ?>
		</div>
		<div class="atr_float"  style="width: 90px; text-align:left;">
			<span class="amarelo_claro">
			<?php
				if($curPlayer->hasAttribute($habil . '_arv_calc')) {
					$sum = $curPlayer->getAttribute($habil . '_arv_calc') + $curPlayer->getAttribute($habil . '_item_calc');
				} else {
					$sum = $curPlayer->getAttribute($habil . '_arv') + $curPlayer->getAttribute($habil . '_item');						
				}
			?>
			<?php if($curPlayer->hasAttribute($habil . '_calc')): ?>
				<?php echo $curPlayer->getAttribute($habil . '_calc') - $sum ?>
			<?php else: ?>
				<?php echo $curPlayer->getAttribute($habil) - $sum ?>
			<?php endif; ?>
			(+ <?php echo $sum ?>)
		
			</span>
		</div>
		<div class="atr_float" style="margin-top: 8px;">
			<?php if($curPlayer->hasAttribute($habil . '_calc')): ?>
				<?php barra_exp3($curPlayer->getAttribute($habil . '_calc'), $max_ats_1, $bar_width_1, $curPlayer->getAttribute($habil . '_calc'), "#2C531D", "#537F3D",  $cn % 2 ? 1 : 2) ?>
			<?php else: ?>
				<?php barra_exp3($curPlayer->getAttribute($habil), $max_ats_1, $bar_width_1, $curPlayer->getAttribute($habil), "#2C531D", "#537F3D",  $cn % 2 ? 1 : 2) ?>
			<?php endif; ?>
		</div>
		</div>
		<?php endforeach; ?>
		</td>
	</tr>
	<?php else: ?>
	<tr>
		<td align="center"><?php echo t('actions.a249') ?></td>
	</tr>
	<?php endif ?>		
<?php else: ?>
	<td align="center"><i><?php echo t('actions.a250') ?></i></td>
<?php endif; ?>
<?php endif; ?> 
</tr>
</table>
</div>
<br />
<br />
<div id="banner-botoes" style="margin: 0 auto; width:900px; text-align:center;">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- NG - Profile -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9166007311868806"
     data-ad-slot="9322022979"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<br />
    <input type="submit" onclick="opener.location.href='?secao=mensagens&amp;msg=<?php echo addslashes($curPlayer->nome) ?>'; window.close()" value="<?php echo t('botoes.enviar_mensagem')?>"/>
    <input type="submit" onclick="window.close()" value="<?php echo t('botoes.cancelar')?>" border="0" />
<script>$(".trigger").tooltip({effect: 'slideleft', position: ['center', 'right']});</script>
</div>
</body>
</html>
