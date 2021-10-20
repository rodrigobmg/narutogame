<?php
	$qIndicados = Recordset::query("SELECT * FROM global.user WHERE id_ref=" . $_SESSION['usuario']['id']);
?>
<div class="titulo-secao"><p><?php echo t('indique.i1')?></p></div><br />
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- NG - Indique -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9166007311868806"
     data-ad-slot="5310625776"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<br/><br/>
<!-- Mensagem nos Topos das Seções -->
<?php msg('5',''.t('indique.i2').'', ''.t('indique.i3').':<br /><br /><textarea cols="50" style="height:22px">http://narutogame.com.br/?secao=cadastro&uref='. $_SESSION['usuario']['id'].'</textarea><br /><br /><strong class="verde"> - 1000 RYOUS - </strong> '.t('indique.i4').'.<br /><strong class="verde">- 2 '.t('comprando_vip.cv14').' -</strong>  '.t('indique.i5').'');?>
<!-- Mensagem nos Topos das Seções -->

<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="60" align="center">&nbsp;</td>
          <td width="220" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
           <td width="220" align="center"><b style="color:#FFFFFF"><?php echo t('geral.email')?></b></td>
          <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.data_cadastro')?></b></td>
          <td width="60" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ativo')?></b></td>
          <td width="60" align="center"><b style="color:#FFFFFF">Vip</b></td>
        </tr>
      </table></td>
  </tr>
</table>
<table  width="730" border="0" cellpadding="0" cellspacing="0">
    <?php if(!$qIndicados->num_rows): ?>
    <tr>
    	<td colspan="4"><?php echo t('indique.i6')?></td>
    </tr>
    <?php else: ?>
		<?php 
			$c =0;
			while($rIndicado = $qIndicados->row_array()): 
				$bg = ++$c % 2 ? "class='cor_sim'" : "class='cor_nao'";
		?>
        <tr <?php echo $bg ?>>
            <td  width="60" height="35"><img src="<?php echo img() ?>layout/estrela.gif" width="16" height="16" /></td>
            <td  width="220"><?php echo $rIndicado['name'] ?></td>
            <td  width="220"><?php echo $rIndicado['email'] ?></td>
            <td  width="100"><?php echo date("d/m/Y H:i:s", strtotime($rIndicado['date_ins'])) ?></td>
            <td  width="60"><?php echo $rIndicado['active'] ? t('indique.i7') : t('indique.i8') ?></td>
            <td  width="60"><?php echo $rIndicado['vip'] ? "<img src='" . img() . "layout/ok_16x16.gif' width='16' height='16' />" : "<img src='" . img() . "layout/delete_16x16.gif' width='16' height='16' />" ?></td>
        </tr>
		<tr height="4"></tr>
        <?php endwhile; ?>
    <?php endif; ?>
</table>
