<?php
	Player::moveLocal($basePlayer->id, 4);
	
	$equipe		= Recordset::query("SELECT * FROM equipe WHERE id=" . $basePlayer->getAttribute('id_equipe'))->row_array();
	$players	= Recordset::query("SELECT id, id_missao, id_batalha, nome FROM player WHERE id_equipe=" . $basePlayer->getAttribute('id_equipe'));
	$mblock		= false;
	
	$fall		= hasFall($basePlayer->id_vila, 4);	
	$fall_timer	= get_fall_time($basePlayer->getAttribute('id_vila'), 4);
?>
<script type="text/javascript" src="js/missoes.js"></script>
<div class="titulo-secao"><p><?php echo t('titulos.missoes_equipe'); ?></p></div>
<?php msg(6, t('missoes_status.missoes_equipe1'), t('missoes_status.missoes_equipe2')); ?>
<br/>
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "1857631774";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Missões -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/><br/>
<div id="cnBase" class="direita">
<?php
	if($equipe['membros'] < 4) {
		msg(4,t('academia_jutsu.problema'),t('academia_jutsu.equipe_insuficiente'));
	}

	foreach($players->result_array() as $membro) {
		if(!$membro['id_missao'] && !$membro['id_batalha']) continue;
		
		$tarefas = array();
		
		if($membro['id_missao']) $membro[] = "Em missão";
		if($membro['id_batalha']) $membro[] = "Em batalha";
		
		echo msg(3,t('academia_jutsu.problema'),sprintf(t('missoes_status.equipe_travada'), $membro['nome'], join(", ", $tarefas)));
		
		
	
		$mblock = true;
	}
?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td onclick="doMissaoRankOld('D', this)" width="146" height="53" align="center"><b><a class="button linkCabecalho" href="javascript:void(0)" style="padding-top:-10px"><?php echo t('status.rank')?> D</a></b></td>
		<td onclick="doMissaoRankOld('C', this)" width="146" align="center"><b><a class="button linkCabecalho" href="javascript:void(0)"><?php echo t('status.rank')?> C </a></b></td>
		<td onclick="doMissaoRankOld('B', this)" width="146" align="center"><b><a class="button linkCabecalho" href="javascript:void(0)"><?php echo t('status.rank')?> B </a></b></td>
		<td onclick="doMissaoRankOld('A', this)" width="146" align="center"><b><a class="button linkCabecalho" href="javascript:void(0)"><?php echo t('status.rank')?> A </a></b></td>
		<td onclick="doMissaoRankOld('S', this)" width="146" align="center"><b><a class="button linkCabecalho" href="javascript:void(0)"><?php echo t('status.rank')?> S </a></b></td>
	</tr>
</table>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="44" class="subtitulo-home">
			<table width="730" border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td width="500" align="center"><b style="color:#FFFFFF"><?php echo t('geral.descricao'); ?></b></td>
					<td width="120" align="center"><b style="color:#FFFFFF"><?php echo t('geral.recompensa'); ?></b></td>
					<td width="120" align="center"><b style="color:#FFFFFF">Level</b></td>
					<td width="90" align="center"><b style="color:#FFFFFF">Status</b></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div id="HOTWordsTxt" name="HOTWordsTxt">
<?php
	$ranksb		= array("", "D", "C", "B", "A", "S");
	$ranks		= array(1,2,3,4,5);
	$first		= true;
	$arMembros	= array();
	$cn			= 0;
	$cp			= 0;
	$_SESSION['missoes_key']	= md5(date("YmdHis") . rand(1, 32768));

	foreach($players->result_array() as $membro) {
		$arMembros[]	= $membro['id'];
	}

	$quests = Recordset::query('
		SELECT
			 a.*,
			 (SELECT id FROM graduacao WHERE id=a.id_graduacao) AS grad_id      
		
		FROM
			quest a
		
		WHERE
			 #a.id NOT IN(SELECT id_quest FROM player_quest WHERE id_player=' . $basePlayer->id . ') AND
			 id_pai IS NULL
			 AND equipe=1
			 AND especial=0
			 ORDER BY level
	', true);
	
	$quests_done	= Recordset::query('SELECT * FROM player_quest WHERE id_equipe=' . $basePlayer->id_equipe);	
?>
<?php foreach($ranks as $rank): ?>
<table class="tMissaoBloco" id="tMissao_<?php echo $ranksb[$rank] ?>" width="730" border="0" cellpadding="2" cellspacing="0" <?php echo !$first ? "style='display: none'" : "" ?> >
	<?php foreach($quests->result_array() as $r): ?>
	<?php
		if($r['id_rank'] != $rank) {
			continue;
		}
	
		$is_quest_done	= false;
		$rr				= array('completa' => 0, 'falha' => 0);
		$cor			= ++$cn % 2 ? "cor_sim" : "cor_nao";
		$pont			= ++$cp % 2 ? "pontilhado-listagem1.jpg" : "pontilhado-listagem.jpg";
		
		foreach($quests_done->result_array() as $q) {
			if($q['id_quest'] == $r['id']) {
				if(!$q['falha'] && is_null($q['completa'])) {
					$q['completa']	= 1;
				}
			
				$rr = $q;
				break;
			}
		}
		
		if($first) {
			$first = false;
		}
	?>
	<tr class="<?php echo $cor?>">
		<td width="500" align="left" height="34" style="padding: 15px 0 15px 7px;">
			<b style="font-size:13px;" class="amarelo"><?php echo htmlspecialchars($r['nome_' . Locale::Get()]) ?></b><br /><br />
            <span style="color:#FFFFFF;"><?php echo htmlspecialchars($r['descricao_' . Locale::Get()]) ?>
            <ul style="text-align:left; margin-left: 7px">
           		
			<?php
		      	if($r['interativa']) {
			?>
            <br /><br />
            <li class="azul" style="font-weight:bold;"><?php echo t('missoes.objetivos') ?>:</li>
            		
			<?php		
					$qi = Recordset::query("SELECT b.nome_br, b.nome_en, a.npc_total, x1, y1, x2, y2 FROM quest_npc_item a JOIN npc b ON a.id_npc=b.id WHERE a.id_quest=" . $r['id'], true);
					
					foreach($qi->result_array() as $ri) {
						echo "<br /><li><b class='verde'>" . t('missoes.derrotar') . ": </b><span class='cinza'>" . $ri['npc_total'] . "x " . t('missoes.o_npc') ." ".  $ri['nome_' . Locale::Get()] . "<br />".t('missoes_status.coordenadas_x')." {$ri['x1']} ".t('missoes.ate')."
						{$ri['x2']} ".t('missoes_status.coordenadas_y')." {$ri['y1']} ".t('missoes.ate')." {$ri['y2']}</span></li>";
						

					}
				}
			?>
            </ul>
            </span>
			<?php
				$others	= array();
				
				foreach($players->result_array() as $player) {
					if(Recordset::query('SELECT id FROM player_quest WHERE id_player=' . $player['id'] . ' AND completa=1 AND id_equipe !=' . $basePlayer->id_equipe . ' AND id_quest=' . $r['id'])->num_rows) {
						$others[]	= $player['nome'];
					}
				}
			?>
			<?php if($others): ?>
			<br />
			<img src="<?php echo img('layout/interno/'). $pont ?>" />
			<br /><br />
			<?php echo t('actions.a248') ?><br /><br />
			<ul>
				<?php foreach($others as $other): ?>
				<li style="padding-left: 20px"><?php echo $other ?></li>
				<?php endforeach ?>
			</ul>
			<?php endif ?>
			</td>
        <td class="verde" width="120" align="center">
			<p>Exp <?php echo sprintf("%1.2f", (float)$r['exp'] + percent($basePlayer->bonus_vila['sk_missao_exp'], $r['exp'])) ?></p>
			<p>RY$ <?php echo sprintf("%1.2f", (float)$r['ryou'] +  percent($basePlayer->getAttribute('inc_ryou') + $basePlayer->bonus_vila['sk_missao_ryou'], $r['ryou'])) ?></p></td>
        <td width="120" class="amarelo" align="center" style="vertical-align:middle; background: <?php echo $cor;?> url('<?php echo img(); ?>layout/interno/bg-requerimento.png') no-repeat center">
			<b style="font-size:10px;">
			Líder Lvl. <?php echo $r['level'] ?>
            </b>
		</td>
        <td width="90" align="center" background="<?php echo img(); ?>bg_site.jpg">
		<?php if($rr['completa']): ?>
			<a class="button ui-state-green"><?php echo t('missoes_status.completa'); ?></a>
		<?php elseif($rr['falha']): ?>
			<a class="button ui-state-red"><?php echo t('missoes_status.falhou'); ?></a>
		<?php elseif($r['level'] <= $basePlayer->getAttribute('level')  && $equipe['membros'] == 4 && !$mblock && ($basePlayer->getAttribute('dono_equipe') || $basePlayer->getAttribute('sub_equipe') ) && !$rr['falha'] && !$rr['completa']): ?>
			<a class="button" href="javascript:void(0)" style="color: #CCCCCC" onclick="doAceitaQuest('<?php echo $r['id'] ?>','','<?php echo $_SESSION['missoes_key']?>')"><?php echo t('botoes.aceitar')?></a>
		<?php else: ?>
			<a class="button ui-state-disabled"><?php echo t('botoes.aceitar')?></a>
		<?php endif; ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endforeach; ?>
</div>
</div>
<?php if ($fall_timer): ?>
	<script type="text/javascript">
		createTimer(<?php echo $fall_timer->format('%H') ?>, <?php echo $fall_timer->format('%i') ?>, <?php echo $fall_timer->format('%s') ?>, 'd-penality-timer');
	</script>
<?php endif ?>