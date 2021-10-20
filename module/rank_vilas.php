<div class="titulo-secao"><p><?php echo t('titulos.rank_vilas'); ?></p></div>
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
<br/>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="43" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="20" align="center">&nbsp;</td>
          <td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('geral.vila'); ?></b></td>
          <td width="190" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome'); ?></b></td>
		  <td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level'); ?></b></td>
          <td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('geral.vitorias'); ?></b></td>
          <td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('geral.derrotas'); ?></b></td>
          <td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('geral.saldo'); ?></b></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <?php
    $query = Recordset::query("
				select 
						*, 
						(vitorias-derrotas) as saldo 
				from 
						vila 
				WHERE
					inicial='1'
				order by saldo desc;
    ");

    $i	= 1;
    $cn	= 0;
    
    while($r = $query->row_array()) {
      $cor	 = ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
  ?>
  <tr <?php echo $cor ?>>
    <td width="20" align="center"></td>
    <td width="130" align="center"><img src="<?php echo img()?>layout/vilas/<?php echo $r['id'] ?>.png" alt="Vila de <?php echo $r['nome_'. Locale::get()] ?>" /></td>
    <td width="190" height="34" align="center"><b style="font-size:13px;" class="amarelo"><?php echo $i?>&ordm; -
      <?php echo $r['nome_'.Locale::get()] ?>
      </b></td>
	<td width="130" height="34" align="center">Level <?php echo $r['nivel_vila'] ?></td> 
    <td width="130" align="center"><p>
        <?php echo $r['vitorias'] ?>
      </p></td>
    <td width="130" align="center"><p>
        <?php echo $r['derrotas'] ?>
      </p></td>
    <td width="130" align="center"><p>
        <?php echo $r['saldo'] ?>
      </p></td>
  </tr>
  <tr height="4"></tr>
  <?php
			$i++;
			}
	  ?>
</table>
</form>
