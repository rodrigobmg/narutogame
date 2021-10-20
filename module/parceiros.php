<?php
	$rParceiros = Recordset::query("SELECT * FROM parceiros WHERE ativo = 1 ORDER BY id");
?>

<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p>Parceiros</p></div>
  <br />
<table width="730" border="0" cellpadding="4" cellspacing="4">
    <tr>
      <td align="left">Se voc&ecirc; gostou do nosso jogo e quer torna-se parceiro, fa&ccedil;a o seguinte:<br />
        <br />
        Envie-nos um <a href="index.php?secao=fale_conosco" class="linksSite"><b>Email</b></a> com os seguintes dados:<br />
        <br />
        <b>Nome do Site<br />
          Url do Site<br />
          Url do Bot&atilde;o<br />
          Qtd de Visitantes por dia<br />
          <br />
        </b> Responderemos o mais breve poss&iacute;vel o seu pedido de parceria. </td>
    </tr>
</table>
<br />
<table width="730" border="0" cellpadding="4" cellspacing="4">
  <tr>
    <td height="34" align="left"><div style="width:700px; text-align:center; position:relative; height:auto;">
      <?php 
				//Come&ccedil;o do While dos Parceiros
	while($r = $rParceiros->row_array()) {
?>
      <div style="width:92px; height:31px; float:left;"> <a href="<?= $r['site'] ?>" target="_blank"><img src="<?= $r['button'] ?>" alt="<?= $r['nome'] ?>" border="0"/></a></div>
      <?php
	} // Fim do While dos Parceiros
?>
    </div></td>
  </tr>
</table>
</div>