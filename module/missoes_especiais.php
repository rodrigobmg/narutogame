<?php
	Player::moveLocal($basePlayer->id, 4);

	$fall = hasFall($basePlayer->id_vila, 4);
	
	/*
	if($fall):
?>
<table width="766" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="21" background="<?php echo img() ?>bg_sessao_topo.jpg">&nbsp;</td>
  </tr>
  <tr>
    <td background="<?php echo img() ?>bg_sessao_repete.jpg">
        <table width='730' border='0' cellpadding='0' cellspacing='0' align='center'>
            <tr>
                <td width='216' height='153' background='<?php echo img() ?>msg/msg_bg_3.jpg'>&nbsp;</td>
                <td  background='<?php echo img() ?>msg/msg_bg.jpg' align='left'>
                    <b style='font-size:16px;'><br />
                    Sala do Kage em Reconstru&ccedil;&atilde;o</b><br />
Seu kage est&aacute; muito machucado e as miss&otilde;es foram canceladas por 24 horas!</td>
            </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td height="21" background="<?php echo img() ?>bg_sessao_rodape.jpg">&nbsp;</td>
  </tr>
</table><br /><br />
<?php die(); ?>
<?php endif;*/ ?>
<script type="text/javascript" src="js/missoes.js"></script>
<div class="titulo-secao"><p>Missões Especiais</p></div>
  <br />
  <p><input type="image" src="images/bt_ajuda.gif" id="ajuda" /></p>
  <div id="msg_help" class="msg_gai" style="display:none; background:url(<?php echo img() ?>msg/msg_orochimaru.jpg);">
  	<div class="msg"><span style="font-size: 16px; display: block; font-weight: bold; color: #7B1315; margin-bottom: 10px;">Informações sobre as Missões Especiais!</span><p>
Miss&otilde;es Especiais t&ecirc;m como recompensa, DESCONTAR os pontos da Barra de Treino Di&aacute;rio de seu ninja.<br /><br />  Se pretende realizar essa missão, ANTES de iniciar, treine os atributos de seu personagem, pelo menos até a metade da Barra de Limite Diário. Somente serão considerados para concluir as Missões Especiais  os ninjas com status Inimigo, que forem derrotados dentro dos mapas das Vilas inimigas. Vitória por Inatividade ou outros tipos de vitórias, NÃO são aceitas. Essas missões aparecem nos Status, com níves de MIssão Rank D até S, porém é meramente ilustrativo e são removidas em 15 dias e NÃO contam score.

</p></div>
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
  <table width="730" border="0" cellpadding="0" cellspacing="0">
  
    <tr>
      <td class="tMissaoSel" onclick="doMissaoRankOld('D', this)" width="146" height="53" align="center" background="<?php echo img() ?>bt_aba.gif"><b><a href="javascript:void(0)" class="linkCabecalho" style="padding-top:-10px">N&iacute;vel  D</a></b></td>
      <td class="tMissaoSel" onclick="doMissaoRankOld('C', this)" width="146" align="center" background="<?php echo img() ?>bt_aba.gif"><b><a href="javascript:void(0)" class="linkCabecalho">N&iacute;vel C </a></b></td>
      <td class="tMissaoSel" onclick="doMissaoRankOld('B', this)" width="146" align="center" background="<?php echo img() ?>bt_aba.gif"><b><a href="javascript:void(0)" class="linkCabecalho">N&iacute;vel B </a></b></td>
      <td class="tMissaoSel" onclick="doMissaoRankOld('A', this)" width="146" align="center" background="<?php echo img() ?>bt_aba.gif"><b><a href="javascript:void(0)" class="linkCabecalho">N&iacute;vel A </a></b></td>
      <td class="tMissaoSel" onclick="doMissaoRankOld('S', this)" width="146" align="center" background="<?php echo img() ?>bt_aba.gif"><b><a href="javascript:void(0)" class="linkCabecalho">N&iacute;vel S </a></b></td>
    </tr>
  </table>
  <table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="48" background="<?php echo img() ?>bg_aba.jpg">
            <table width="730" border="0" cellpadding="2" cellspacing="0">
              <tr>
                <td width="480" align="center"><b style="color:#FFFFFF">Descri&ccedil;&atilde;o</b></td>
                <td width="150" align="center"><b style="color:#FFFFFF">Recompensa</b></td>
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
	
	foreach($ranks as $rank) {
?>
    <table class="tMissaoBloco" id="tMissao_<?= $ranksb[$rank] ?>" width="730" border="0" cellpadding="4" cellspacing="0" <?= !$first ? "style='display: none'" : "" ?>>
      <?php
		if($first) $first = false;
			
		$qQuest = Recordset::query("
			SELECT
				 a.*,
				 (SELECT id FROM graduacao WHERE id=a.id_graduacao) AS grad_id      
			
			FROM
				quest a FORCE KEY(idx_pai_rank_equipe_especial) 
			
			WHERE
				 a.id NOT IN(SELECT id_quest FROM player_quest WHERE id_player={$basePlayer->id} AND falha=0)
				 AND id_pai IS NULL
				 AND id_rank = $rank
				 AND equipe=0
				 AND especial=1
			
			/*
			UNION
			
			SELECT
				  a.*,      
				  (SELECT id FROM graduacao WHERE id=a.id_graduacao) AS grad_id
			FROM
				quest a FORCE KEY(idx_pai_rank_equipe_especial)     
			
			WHERE
				 a.id NOT IN(SELECT id_quest FROM player_quest WHERE id_player={$basePlayer->id} AND falha=0)
				 AND a.id_pai IN(SELECT id_quest FROM player_quest WHERE id_player={$basePlayer->id})     
				 AND a.id_pai IS NOT NULL
				 AND id_rank = $rank
				 AND equipe=0
				 AND especial=1
			*/
		");
		
		if(!$qQuest->num_rows) {
			echo "<tr class='txtNaoConcluido'><td background='". img() . "bg_site.jpg' align='center'><i>Nenhuma missão</i></td></tr>";
		}
		
		while($r = $qQuest->row_array()) {
			$rr = Recordset::query("SELECT * FROM player_quest WHERE id_player={$basePlayer->id} AND id_quest=" . $r['id'])->row_array();
			
			/*if(Recordset::query("SELECT id FROM player_quest WHERE id_player={$basePlayer->id} AND id_quest=" . $r['id'])->num_rows) {
				continue;	
			}*/
			
			$cor = ++$cn % 2 ? "#151515" : "#1c1c1c";
			$cor = $r['especial'] ? "#051123" : $cor;
?>
      <tr >
        <td width="480" height="34" background="<?php echo img() ?>bg_site.jpg" style="background-color: <?=$cor;?>"><b style="font-size:13px; color:#af9d6b">
          <?php echo ($r['nome']) ?>
          </b><br />
          <br />
          <?php echo ($r['descricao']) ?>
          <?
      	if($r['especial']) {
			echo "<br /><br /><b>Seu objetivo será:</b><br />";
			
			$qi = Recordset::query("SELECT a.npc_total FROM quest_npc_item a WHERE a.id_quest=" . $r['id']);
			
			while($ri = $qi->row_array()) {
				echo "&bull; Eliminar " . $ri['npc_total'] . " ninja(s) inimigos<br />";
			}
		}
	  ?></td>
        <td width="150" align="center" background="<?php echo img() ?>bg_site.jpg" style="background-color: <?php echo $cor;?>"><p>
            <?
		echo $r['exp'];	
	?>
            Pontos abatidos em seu Treino Diário</p></td>
        <td width="90" align="center" background="<?php echo img() ?>bg_site.jpg" style="background-color: <?php echo $cor;?>"><?php if(!$rr['falha'] && !$rr['completa']){ ?>
          <a href="javascript:void(0)" style="color: #CCCCCC" onclick="doAceitaQuest('<?= encode($r['id']) ?>')"> <img src="<?php echo img() ?>bt_aceitar_on.gif" alt="Aceitar Missão" border="0"/></a>
          <?php } elseif($rr['falha'] && !$rr['completa']) { ?>
          <img src="<?php echo img() ?>bt_falhou.gif" alt="Missão Falhou" border="0"/>
          <?php } else { ?>
          <img src="<?php echo img() ?>bt_aceitar_off.gif" alt="Aceitar Missão" border="0"/>
          <?php }  ?></td>
      </tr>
      <?
		}
	}
?>
    </table>
  </div>
</div>
