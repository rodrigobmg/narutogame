<?php
	switch($_POST['o']) {
		case 1:
		default:
			$campo = "valor";
			$moeda = "R$";
			
			break;
		
		case 2:
			$campo = "valor_us";
			$moeda = "US$";
			
			break;
		
		case 3:
			$campo = "valor_eur";
			$moeda = "EUR";
			
			break;
			
		case 4:
			$campo = "valor";
			$moeda = "R$";
			
			break;	
		
	}
?>

<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="subtitulo-home">
	<table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="55" align="center">&nbsp;</td>
        <td width="172" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
        <td width="138" align="center"><b style="color:#FFFFFF"><?php echo t('geral.creditos')?></b></td>
        <td width="178" align="center"><b style="color:#FFFFFF"><?php echo t('actions.a13')?></b></td>
        <td width="152" align="center"><b style="color:#FFFFFF"><?php echo t('actions.a14')?></b></td>
      </tr>
    </table>
	</td>
  </tr>
</table>
<table width="730" border="0" cellpadding="2" cellspacing="0">
 <?php
    $q = Recordset::query("SELECT * FROM coin");
 	  $c = 0;

     while($r = $q->row_array()) {
        $color = ++$c % 2 ? "class='cor_sim'" : "class='cor_nao'";
 ?>
  <tr <?php echo $color ?>>
    <td height="35" width="55" align="center"><img src="<?php echo img();?>layout/estrela.gif" width="16" height="16" /></td>
    <td  width="172"><?php echo $r['titulo'] ?></td>
    <td width="138"><?php echo $r['coin'] ?> <?php echo t('geral.creditos')?></td>
    <td width="178"><?php echo $moeda ?> <?php echo sprintf("%1.2f", $r[$campo]) ?></td>
    <td width="152" align="center"><input type="radio" name="<?php echo $_SESSION['vip_field_plano'] ?>" id="<?php echo $_SESSION['vip_field_plano'] ?>" value="<?php echo encode($r['id']) ?>" /></td>
  </tr>
  <tr height="4"></tr>
  <?php
    }
  ?>
</table>
