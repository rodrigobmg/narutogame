<?php
	Player::moveLocal($basePlayer->id, 4);
	
	$qPlayers = new Recordset("SELECT id, id_missao, id_batalha, nome FROM player WHERE id_equipe=" . $basePlayer->id_equipe);
	$tPlayers = $qPlayers->num_rows;
	
	$arMembros = array();
	foreach($qPlayers->result_array() as $rPlayer) {
		$arMembros[] = $rPlayer['id'];
	}
	
	$mblock = false;
	
	$fall = hasFall($basePlayer->id_vila, 4);	
?>
<script type="text/javascript" src="js/missoes.js"></script>
<div class="titulo-secao"><p>Missões de Equipe</p></div>
<br />
<p><input type="image" src="images/bt_ajuda.gif" id="ajuda" /></p>
<div id="msg_help" class="msg_gai" style="display:none; background:url(<?php echo img() ?>msg/msg_gai.jpg);">
	<div class="msg">
		<span style="font-size: 16px; display: block; font-weight: bold; color: #7B1315; margin-bottom: 10px;">Missão de Equipe</span>
		<p>As Missões de Equipe são interativas, ou seja, você e sua equipe tem que matar 4 inimigos no mapa mundi e ao completar os 4 ganham a recompensa.<br /><br />
		Essas missões contam para a graduação e são excelentes para ganhar ryous e exp de forma rápida, porém, possua uma equipe bem participativa para não ter problemas em derrotar npcs sozinho.
		</p>
	</div>
</div>
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
	if($tPlayers < 4) {
	echo "<div class='msg_gai' style='background:url(".img()."msg/msg_error.jpg);'>" .
		 "<div class='msg'>" .
		 "<span style='font-size:16px; display:block; font-weight:bold; color:#7b1315; margin-bottom:10px'>Problema!</span>" .
		 "Sua equipe n&atilde;o tem integrantes suficientes" .
		 "</div></div>";
	}

	foreach($qPlayers->result_array() as $rPlayers) {
		if(!$rPlayers['id_missao'] && !$rPlayers['id_batalha']) continue;
		
		$tarefas = array();
		
		if($rPlayers['id_missao']) $tarefas[] = "Em miss&atilde;o";
		if($rPlayers['id_batalha']) $tarefas[] = "Em batalha";

		echo "<div class='msg_gai' style='background:url(".img()."msg/msg_error.jpg);'>" .
			 "<div class='msg'>" .
			 "<span style='font-size:16px; display:block; font-weight:bold; color:#7b1315; margin-bottom:10px'>Problema!</span>" .
			 "O player {$rPlayers['nome']} esta ocupado em outra tarefa (". join(", ", $tarefas) ."). Voc&ecirc; so podera aceitar as miss&otilde;es da equipe quando todos os players estiverem livres." .
			 "</div></div>";
	
		$mblock = true;
	}
?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="tMissaoSel" onclick="doMissaoRankOld('D', this)" width="146" height="53" align="center" background="<?php echo img(); ?>bt_aba.gif"><b><a href="javascript:void(0)" class="linkCabecalho" style="padding-top:-10px">N&iacute;vel  D</a></b></td>
		<td class="tMissaoSel" onclick="doMissaoRankOld('C', this)" width="146" align="center" background="<?php echo img(); ?>bt_aba.gif"><b><a href="javascript:void(0)" class="linkCabecalho">N&iacute;vel C </a></b></td>
		<td class="tMissaoSel" onclick="doMissaoRankOld('B', this)" width="146" align="center" background="<?php echo img(); ?>bt_aba.gif"><b><a href="javascript:void(0)" class="linkCabecalho">N&iacute;vel B </a></b></td>
		<td class="tMissaoSel" onclick="doMissaoRankOld('A', this)" width="146" align="center" background="<?php echo img(); ?>bt_aba.gif"><b><a href="javascript:void(0)" class="linkCabecalho">N&iacute;vel A </a></b></td>
		<td class="tMissaoSel" onclick="doMissaoRankOld('S', this)" width="146" align="center" background="<?php echo img(); ?>bt_aba.gif"><b><a href="javascript:void(0)" class="linkCabecalho">N&iacute;vel S </a></b></td>
	</tr>
</table>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="48" background="<?php echo img() ?>bg_aba.jpg">
			<table width="730" border="0" cellpadding="2" cellspacing="0">
				<tr>
					<td width="400" align="center"><b style="color:#FFFFFF">Descri&ccedil;&atilde;o</b></td>
					<td width="120" align="center"><b style="color:#FFFFFF">Recompensa</b></td>
					<td width="120" align="center"><b style="color:#FFFFFF">Level</b></td>
					<td width="90" align="center"><b style="color:#FFFFFF">Status</b></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div id="HOTWordsTxt" name="HOTWordsTxt">
<?php
	$ranksb = array("", "D", "C", "B", "A", "S");
	$ranks = array(1,2,3,4,5);
	$first = true;
	
	$quests = Recordset::query('
		SELECT
			 a.*,
			 (SELECT nome_'.Locale::get().' FROM graduacao WHERE id=a.id_graduacao) AS nome_graduacao      
		
		FROM
			quest a FORCE KEY(idx_pai_rank_equipe_especial) 
		
		WHERE
			 a.id NOT IN(SELECT id_quest FROM player_quest WHERE id_player=' . $basePlayer->id . ')
			 AND id_pai IS NULL
			 #AND id_rank = $rank
			 AND equipe=1
			 AND especial=0
			 ORDER BY level
	')->result_array();
	
	$quests_done = Recordset::query('SELECT * FROM player_quest WHERE id_player=' . $basePlayer->id)->result_array();
	
	$quests_other = Recordset::query('
		SELECT
			id_quest, 
			COUNT(id) AS mx
		
		FROM 
			player_quest 
		
		WHERE 
			id_player IN(' . implode(",", $arMembros) . ') AND 
			id_player != ' . $basePlayer->id . '
		
		GROUP BY
			id_quest
	')->result_array();	
?>
<?php foreach($ranks as $rank): ?>
<table class="tMissaoBloco" id="tMissao_<?= $ranksb[$rank] ?>" width="730" border="0" cellpadding="2" cellspacing="0" <?= !$first ? "style='display: none'" : "" ?>>
	<?php foreach($quests as $r): ?>
	<?php
		$is_quest_done = false;
		foreach($quests_done as $q) {
			if($q['id_quest'] == $r['id']) {
				$rr = $q;
			}
		}
		
		foreach($quests_other as $q) {
			if($q['id_quest' == $r['id']]) {
				$rOutros['mx'] = $q;
			}
		}
		
		$cor = ++$cn % 2 ? "#151515" : "#1c1c1c";
		
		if($first) $first = false;
	?>
	<tr>
		<td width="400" height="34" background="<?php echo img(); ?>bg_site.jpg" style="background-color: <?=$cor;?>">
			<b style="font-size:13px; color:#af9d6b"><?= htmlspecialchars($r['nome']) ?></b><br /><br />
			<?= htmlspecialchars($r['descricao']) ?>
			<?php
      	if($r['quest']) {
			echo "<br /><br /><b>Seus alvos ser&atilde;o:</b><br />";
			
			$qi = Recordset::query("SELECT b.nome, a.qtd, x1, y1, x2, y2 FROM quest_npc a JOIN npc b ON a.id_npc=b.id WHERE a.id_quest=" . $r['id']);
			
			while($ri = $qi->row_array()) {
				
				echo "&bull; " . $ri['qtd'] . "x " . $ri['nome'] . " - Coordenadas X de {$ri['x1']} at&eacute;
				{$ri['x2']} e em Y de {$ri['y1']} at&eacute; {$ri['y2']} <br />";
			}
		}
	  ?>
		</td>
        <td width="120" align="center" background="<?php echo img(); ?>bg_site.jpg" style="background-color: <?=$cor;?>">
			<p>Exp <?= sprintf("%1.2f", (float)$r['exp']) ?></p>
			<p>RY$ <?= sprintf("%1.2f", (float)$r['ryou']) ?></p></td>
        <td width="120" align="center" background="<?php echo img(); ?>bg_site.jpg" style="background-color: <?=$cor;?>">
			<b style="font-size:11px; color:#af9d6b"><br />
			<?= $r['nome_graduacao'] ?><br />
			Lvl. <?= $r['level'] ?>
			</b>
		</td>
        <td width="90" align="center" background="<?php echo img(); ?>bg_site.jpg" style="background-color: <?=$cor;?>">
        <?php if($r['level'] <= $basePlayer->level && $basePlayer->id_graduacao >= $r['id_graduacao'] && $tPlayers == 4 && !$mblock && $basePlayer->dono_equipe && !$rr['falha'] && !$rr['completa'] && !$rOutros['mx']){ ?>
          <a href="javascript:void(0)" style="color: #CCCCCC" onclick="doAceitaQuest('<?= encode($r['id']) ?>')"> <img src="<?php echo img() ?>bt_aceitar_on.gif" alt="Aceitar Missão" border="0"/></a>
          <?php } elseif($rr['completa']) { ?>
          	COMPLETA
          <?php } elseif($rr['falha']) { ?>
          	<img src="<?php echo img() ?>bt_falhou.gif" alt="Missão Falhou" border="0"/>
          <?php } elseif($rOutros['mx']) {?>
          	OUTRO INTEGRANTE JA CONCLUIU
          <?php } else{ ?>
          	<img src="<?php echo img() ?>bt_aceitar_off.gif" alt="Aceitar Missão" border="0"/>
          <?php }  ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endforeach; ?>
</div>
</div>
