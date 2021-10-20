<script type="text/javascript" src="js/rank_ninjas.js"></script>
<?php
	$_POST['vila'] = !isset($_POST['vila']) ? $basePlayer->getAttribute('id_vila') : decode($_POST['vila']);
	$_POST['from'] = !isset($_POST['from']) ? 0 : decode($_POST['from']);
	
	if(!is_numeric($_POST['vila']) || !is_numeric($_POST['from'])) {
		redirect_to("negado");	
	}

	$where = isset($_POST['vila']) && is_numeric($_POST['vila']) && $_POST['vila'] > 0 ? " AND a.id_vila=" . $_POST['vila'] : "";
	$where .= isset($_POST['nome']) && $_POST['nome'] ? " AND a.nome_guild LIKE '%" . addslashes($_POST['nome']) . "%'" : "";
?>
<div class="titulo-secao"><p>Ranking das Organizações</p></div>
<br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "5761438173";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- Ranking -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br />
<form method="post">

    <table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="43" align="left" colspan="7" class="subtitulo-home">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:#FFFFFF"><?php echo t('ranks.filtros'); ?></b></td>
        </tr>
      <tr >
        <td width="69" height="34" align="center"><b><?php echo t('ranks.vila'); ?></b></td>
        <td width="87" align="left"><select name="vila" id="vila">
          <option value="<?php echo encode("00") ?>">Geral</option>
          <?php
          	$qVila = Recordset::query("SELECT *, nome_".Locale::get()." AS nome FROM vila WHERE inicial='1'", true);
          	
			foreach($qVila->result_array() as $rVila) {
				$selected = $_POST['vila'] == $rVila['id'] ? "selected='selected'" : "";
				echo "<option value='" . encode($rVila['id']) . "' $selected>" . htmlentities($rVila['nome']) . "</option>";	
			}
		  ?>
                </select></td>
        <td width="63"  align="center"><b><?php echo t('ranks.posicao'); ?></b></td>
        <td width="190"  height="34" align="left"><select name="from" id="from">
        <?php
          $qTotal	= Recordset::query("
                    SELECT
                        COUNT(a.id) as total
                    
                    FROM 
                        ranking_guild a 
              
                    WHERE 1=1 
                        $where
          ");
						
        	$rTotal = $qTotal->row_array();
			
			for($f = 0; $f <= $rTotal['total']; $f += 50) {
				$selected = $_POST['from'] == $f ? "selected='selected'" : "";
				echo "<option value='" . encode($f) . "' $selected>" . ($f + 1) . " ".t('geral.ate')." " . ($f + 50) . "</option>";	
			}
		?>
       </select></td>
		<td width="63"  align="center"><b><?php echo t('ranks.nome'); ?></b></td>
		<td width="190"  height="34" align="left">
		<input type="text" name="nome" value="<?php echo isset($_POST['nome']) ? addslashes($_POST['nome']) : '' ?>" />
		</td>
        <td width="321" align="right"><input type="submit" class="button" value="<?php echo t('geral.filtrar')?>" /></td>
      </tr>
    </table>
<br />

<table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="43" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="50" align="center">&nbsp;</td>
            <td width="90" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.posicao'); ?></b></td>
            <td width="220" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.nome_level'); ?></b></td>
            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.pontos'); ?></b></td>
            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.vila'); ?></b></td>
        	<td width="150" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.lider'); ?></b></td>
          </tr>
        </table></td>
      </tr>
    </table>
<table width="730" border="0" cellpadding="2" cellspacing="0">
		<?php
			$cn		= 0;
      $query	= Recordset::query("
                SELECT
                    a.*,
                    b.nome,
					b.id AS id_player,
					c.nome AS nome_guild,
                    b.id_classe,
					c.id AS id_guild,
                    (SELECT nome_".Locale::get()." FROM vila WHERE id=a.id_vila) AS nome_vila
                
                FROM 
                    ranking_guild a JOIN player b ON b.id=a.id_player JOIN guild c ON c.id_player=b.id
                
                WhERE c.removido='0'
                     $where
                
                ORDER BY " . ((int)$_POST['vila'] ? 'posicao_vila' : 'posicao_geral' ) . " ASC LIMIT {$_POST['from']}, 50");
			
      while($r = $query->row_array()) {
				$posicao = (int)$_POST['vila'] ? $r['posicao_vila'] : $r['posicao_geral'];
                $cor	 = ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
                if($posicao <= 3){
                    $posRanking = "amarelo";
					$posRanking2 = "font-weight: bold";
                } else {
                    $posRanking = "";
					$posRanking2 = "";
                }
			
			($r['id_guild'] == $basePlayer->id_guild) ? $cor="class='cor_roxa'" : "";
	
        ?>
       <tr <?php echo $cor ?>>
        <td width="50" align="center">&nbsp;</td>
        <td width="90" align="center" style="font-size: 13px; <?php echo $posRanking2 ?>" class="<?php echo $posRanking ?>"><?php echo $posicao ?>&ordm;</td>
        <td width="220" height="34" align="center">
			<a class="linkTopo <?php echo $posRanking ?>" style="font-size: 13px; <?php echo $posRanking2 ?>"  target="_blank" href="?secao=guild_detalhe&id=<?php echo urlencode(encode($r['id_guild'])) ?>"><?php echo $r['nome_guild'] ?> - Lvl. <?php echo $r['level'] ?></a>
		</td>
        <td width="110" align="center"><?php echo $r['pontos'] ?></td>
        <td width="110" align="center">
			<img src="<?php echo img() ?>layout/bandanas/<?php echo $r['id_vila'] ?>.png" width="48" height="24" />
		</td>
        <td width="150" align="center">
			<a href="javascript:void(0)" class="linkTopo" onclick="playerProfile('<?php echo urlencode(encode($r['id_player'])) ?>')">
			<img border="0" src="<?php echo img() ?>/layout<?php echo LAYOUT_TEMPLATE?>/dojo/<?php echo $r['id_classe'] ?><?php echo LAYOUT_TEMPLATE=="_azul" ? ".jpg":".png"?>" width="126" height="44" /><br /><?php echo $r['nome'] ?>

			</a>
		</td>
      </tr>
	  <tr height="4"></tr>
      <?php
			}
	  ?>
    </table>
</form>