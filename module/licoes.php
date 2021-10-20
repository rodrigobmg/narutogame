<div class="titulo-secao"><p><?php echo t('licoes.l1')?></p></div><br />
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
<?php if($basePlayer->getAttribute('id_graduacao') > 1): ?>
<div id="cnBase" class="direita">
<?php msg('1',''.t('licoes.l2').'', ''.t('licoes.l3').'');?>
</div>
<?php else: ?>
  <div id="cnBase" class="direita">
	<table width="730" border="0" cellpadding="2" height="49" class="branco_bold subtitulo-home" cellspacing="0">
    	<tr>
            <td width="350" align="center"><b><?php echo t('geral.descricao')?></b></td>
            <td width="100" align="center"><b><?php echo t('geral.duracao')?></b></td>
            <td width="100" align="center"><b><?php echo t('geral.recompensa')?></b></td>
            <td width="90" align="center"><b><?php echo t('geral.level')?></b></td>
            <td width="90" align="center"><b><?php echo t('geral.status')?></b></td>
        </tr>
    </table>
    <table class="tMissaoBloco" width="730" border="0" cellpadding="0" cellspacing="0">
      <?php
	  $_SESSION['missoes_key']	= md5(date("YmdHis") . rand(1, 32768));
	  $rank = 0;
	
	$qQuest = Recordset::query("
			SELECT
				 a.*,
				 (SELECT id FROM graduacao WHERE id=a.id_graduacao) AS grad_id      
			
			FROM
				quest a #FORCE KEY(idx_pai_rank_equipe_especial) 
			
			WHERE
				 a.id NOT IN(SELECT id_quest FROM player_quest WHERE id_player={$basePlayer->id} AND falha=0)
				 AND id_pai IS NULL
				 AND id_rank = $rank
				 AND equipe=0
				 AND especial=0
	");
	
	if(!$qQuest->num_rows) {
		echo "<tr class='txtNaoConcluido'><td align='center'><i>Nenhuma missão</i></td></tr>";
	}
	
	$cn = 0;
	$cp = 0;
	
	while($r = $qQuest->row_array()) {
		if(Recordset::query("SELECT id FROM player_quest WHERE id_player={$basePlayer->id} AND id_quest=" . $r['id'])->num_rows) {
			continue;	
		}

    $cor = ++$cn % 2 ? "cor_sim" : "cor_nao";
?>
      <tr class="<?php echo $cor?>">
        <td width="350" style="vertical-align:middle; padding:15px 0 15px 0;"><b style="font-size:14px; color:#f5b600"><?= $r['nome_'. Locale::get()] ?></b>
            <br />
            <p class="cinza" style="width: 345px; margin:auto; color:#FFFFFF"> <?php echo $r['descricao_'. Locale::get()] ?></p>
		</td>
        <td width="100" align="center" style="vertical-align:middle;color:#0fc590"><p>
            <?php
    			echo substr($r['duracao'], 0, 2) . ":" . substr($r['duracao'], 2, 2) . ":" . substr($r['duracao'], 4, 2);
			?>
          </p></td>
        <td width="100" align="center" style="vertical-align:middle;color:#0fc590"><p>RY$
            <?php echo sprintf("%1.2f", (float)$r['ryou']) ?>
          </p></td>
        <td width="90" align="center" style="vertical-align:middle; background:url('<?php echo img(); ?>layout/interno/bg-requerimento.png') no-repeat center"><b style="font-size:10px; color:#bebf27">
          <?php echo graduation_name($basePlayer->id_vila, $r['grad_id'])?><br />
		  Lvl. <?php echo  $r['level'] ?>
          </b></td>
        <td width="90" align="center" style="vertical-align:middle;"><?php if($r['level'] <= $basePlayer->level){ ?>
		  <a class="button" onclick="doAceitaQuest('<?php echo $r['id'] ?>','<?php echo $_SESSION['missoes_key']?>')"><?php echo t('botoes.aceitar')?></a>
          <?php }else{ ?>
		  <a class="button ui-state-disabled"><?php echo t('botoes.aceitar')?></a>
          <?php }  ?>
        </td>
      </tr>
      <tr height="4"></tr>
      <?php
		}
	  ?>
    </table>
    </div>
  <?php endif; ?>