<div class="titulo-secao"><p><?php echo t('atributos.a1');?></p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "9880426177";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Formulas -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<?php msg('3','Sobre as Fórmulas', 'As fórmulas são exibidas de acordo com a sua classe, por exemplo a Stamina, se sua classe é TAI, óbviamente os pontos de TAI vão beneficiar mais você do que os pontos em BUK, se você fosse BUK, claro que os pontos em BUK valeriam mais.');?>
<br/><br/>
<table width="730" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td class="subtitulo-home">
	<table width="730" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td width="70" align="left">&nbsp;</td>
			<td align="left"><b style="color:#FFFFFF"><?php echo t('atributos.a2');?></b></td>
		</tr>
	 </table>
	</td>
  </tr>
</table>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr class="cor_sim">
		<td width="71" align="center"><img src="<?php echo img()?>layout/icones/p_hp.png" width="24" height="24" /></td>
		<td width="175" height="35" align="left" class="cor_sim"><?php echo t('formula.hp');?></td>
		<td width="216" align="left"><?php echo t('atributos.a3');?></td>
		<td width="303" align="left"><img src="<?php echo img()?>layout/icones/ene.png" width="24" height="24" /> * 6</td>
	</tr>
	<tr class="cor_nao">
		<td align="center" class="cor_nao"><img src="<?php echo img()?>layout/icones/p_chakra.png" width="24" height="24" /></td>
		<td height="35" align="left" class="cor_nao">Chakra</td>
		<td align="left" class="cor_nao"><?php echo t('atributos.a4');?><br /></td>
		<td align="left" class="cor_nao"><img src="<?php echo img()?>layout/icones/ene.png" width="24" height="24" /> * 6 +  ( <img src="<?php echo img()?>layout/icones/nin.png" width="24" height="24" /> ) * <?php echo $basePlayer->id_classe_tipo==2 ? 14 : 7 ?> + ( <img src="<?php echo img()?>layout/icones/gen.png" width="24" height="24" /> ) * <?php echo $basePlayer->id_classe_tipo==3 ? 14 : 7 ?></td>
	</tr>
	<tr class="cor_sim">
		<td align="center" class="cor_sim"><img src="<?php echo img()?>layout/icones/p_stamina.png" width="24" height="24" /></td>
		<td height="35" align="left" class="cor_sim">Stamina</td>
		<td align="left" class="cor_sim"><?php echo t('atributos.a9');?><br /></td>
		<td align="left" class="cor_sim"><img src="<?php echo img()?>layout/icones/ene.png" width="24" height="24" /> * 6  + ( <img src="<?php echo img()?>layout/icones/tai.png" width="24" height="24" /> ) * <?php echo $basePlayer->id_classe_tipo==1 ? 14 : 7 ?> + ( <img src="<?php echo img()?>layout/icones/ken.png" width="24" /> ) * <?php echo $basePlayer->id_classe_tipo==4 ? 14 : 7 ?></td>
	</tr>
	<tr class="cor_sim">
		<td align="center" class="cor_nao"><img src="<?php echo img()?>layout/icones/atk_fisico.png" width="24" height="24" /></td>
		<td height="35" align="left" class="cor_nao"><?php echo t('formula.atk_fisico');?></td>
		<td align="left" class="cor_nao">+1 <?php echo t('atributos.a13');?></td>
		<td align="left" class="cor_nao">( <img src="<?php echo img()?>layout/icones/forc.png" width="24" height="24" /> / 2 )</td>
	</tr>
	<tr class="cor_sim">
		<td align="center" class="cor_sim"><img src="<?php echo img()?>layout/icones/atk_magico.png" alt="" width="24" height="24" /></td>
		<td height="35" align="left" class="cor_sim"><?php echo t('formula.atk_magico');?></td>
		<td align="left" class="cor_sim">+1 <?php echo t('atributos.a14');?> <?php echo t('formula.atk_magico');?></td>
		<td align="left" class="cor_sim">( <img src="<?php echo img()?>layout/icones/inte.png" width="24" height="24" /> / 2 )</td>
	</tr>
	<tr class="cor_sim">
		<td align="center" class="cor_nao"><img src="<?php echo img()?>layout/icones/shield.png" width="24" height="24" /></td>
		<td height="35" align="left" class="cor_nao">Defesa ( Nin / Gen)</td>
		<td align="left" class="cor_nao">+1 Defesa ( Nin / Gen)</td>
		<td align="left" class="cor_nao">( <img src="<?php echo img()?>layout/icones/defense.png" width="24" height="24" /> / 2 ) </td>
	</tr>
	<tr class="cor_sim">
		<td align="center" class="cor_sim"><img src="<?php echo img()?>layout/icones/shield.png" width="24" height="24" /></td>
		<td height="35" align="left" class="cor_sim">Defesa ( Tai / Buk)</td>
		<td align="left" class="cor_sim">+1 Defesa ( Tai / Buk)</td>
		<td align="left" class="cor_sim">( <img src="<?php echo img()?>layout/icones/defense.png" width="24" height="24" /> / 2 )</td>
	</tr>
	<tr class="cor_sim">
		<td align="center" class="cor_nao"><img src="<?php echo img()?>layout/icones/precisao.png" /></td>
		<td height="35" align="left" class="cor_nao">Precisão</td>
		<td align="left" class="cor_nao">+1 de Precisão</td>
		<td align="left" class="cor_nao"><img src="<?php echo img()?>layout/icones/conhe.png" /> * 1</td>
	</tr>
	<tr class="cor_sim">
		<td align="center" class="cor_sim"><img src="<?php echo img()?>layout/icones/esquiva.png" /></td>
		<td height="35" align="left" class="cor_sim">Esquiva</td>
		<td align="left" class="cor_sim">+1% de Esquiva</td>
		<td align="left" class="cor_sim"><img src="<?php echo img()?>layout/icones/agi.png" /> / 6</td>
	</tr>
	<!--<tr class="cor_sim">
		<td align="center" class="cor_nao"><img src="<?php echo img()?>layout/icones/esquiva.png" alt="" width="24" height="24" /></td>
		<td height="35" align="left" class="cor_nao"><?php echo t('formula.esq');?></td>
		<td align="left" class="cor_nao">Total de <?php echo t('atributos.a15');?></td>
		<td align="left" class="cor_nao">20% do Total da (<img src="<?php echo img()?>layout/icones/shield.png" width="24" height="24" /> Defesa Base )</td>
	</tr>
	<tr class="cor_sim">
		<td align="center" class="cor_sim"><img src="<?php echo img()?>layout/icones/target2.png" alt="" width="24" height="24" /></td>
		<td height="35" align="left" class="cor_sim"><?php echo t('formula.conc');?></td>
		<td align="left" class="cor_sim">Total de  <?php echo t('atributos.a14');?> <?php echo t('atributos.a16');?></td>
		<td align="left" class="cor_sim">20% do Total  de ( Ataque <img src="<?php echo img()?>layout/icones/atk_fisico.png" width="24" height="24" />  + <img src="<?php echo img()?>layout/icones/atk_magico.png" alt="" width="24" height="24" />)</td>
	</tr>
	<tr class="cor_nao">
		<td align="center" class="cor_nao"><img src="<?php echo img()?>layout/icones/convic.png" alt="" width="24" height="24" /></td>
		<td height="35" align="left" class="cor_nao"><?php echo t('formula.conv');?></td>
		<td align="left" class="cor_nao">Total de <?php echo t('atributos.a14');?> <?php echo t('formula.conv');?></td>
		<td align="left" class="cor_nao">20% do Total de ( Precisão <img src="<?php echo img()?>layout/icones/prec_nin_gen.png" />+  <span class="cor_sim"><img src="<?php echo img()?>layout/icones/prec_tai.png" /></span>)<br /></td>
	</tr>-->
	<tr class="cor_nao">
		<td align="center" class="cor_nao"><img src="<?php echo img()?>layout/icones/esquiva.png" alt="" width="24" height="24" /></td>
		<td height="35" align="left" class="cor_nao"><?php echo t('formula.esq_total');?></td>
		<td align="left" class="cor_nao">1% <?php echo t('atributos.a14');?> <?php echo t('formula.esq_total');?></td>
		<td align="left" class="cor_nao"> ( <?php echo t('atributos.a19');?> / 4 )</td>
	</tr>
	<tr class="cor_sim">
		<td align="center" class="cor_sim"><img src="<?php echo img()?>layout/icones/target2.png" alt="" width="24" height="24" /></td>
		<td height="35" align="left" class="cor_sim"><?php echo t('formula.crit_total');?></td>
		<td align="left" class="cor_sim">1% <?php echo t('atributos.a14');?> <?php echo t('formula.crit_total');?></td>
		<td align="left" class="cor_sim"> ( <?php echo t('atributos.a19');?> / 2)</td>
	</tr>
  <tr class="cor_nao">
  	<td align="center" class="cor_nao"><img src="<?php echo img()?>layout/icones/poder-ninja.png" alt="" /></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('jogador_vip.jv36');?></td>
  	<td align="left" class="cor_nao">% <?php echo t('atributos.a14');?> <?php echo t('jogador_vip.jv36');?></td>
  	<td align="left" class="cor_nao">( <img src="<?php echo img()?>layout/icones/ene.png" width="24" height="24" /> ) * 200 + ( <img src="<?php echo img()?>layout/icones/forc.png" width="24" height="24" /> + <img src="<?php echo img()?>layout/icones/inte.png" width="24" height="24" /> + <img src="<?php echo img()?>layout/icones/defense.png" width="24" height="24" /> ) * 150 + ( <img src="<?php echo img()?>layout/icones/nin.png" width="24" height="24" /> + <img src="<?php echo img()?>layout/icones/gen.png" width="24" height="24" /> + <img src="<?php echo img()?>layout/icones/tai.png" width="24" height="24" /> + <img src="<?php echo img()?>layout/icones/ken.png" width="24" />) * 200 + ( <img src="<?php echo img()?>layout/icones/agi.png" width="24" height="24" /> + <img src="<?php echo img()?>layout/icones/conhe.png" width="24" height="24" />) * 100</td>
  	</tr>
</table>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="70" align="left">&nbsp;</td>
        <td align="left"><b style="color:#FFFFFF"><?php echo t('atributos.a17');?></b></td>
      </tr>
    </table></td>
  </tr>
</table>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr class="cor_sim">
    <td width="132" align="center"><b style="font-size:13px; " class="amarelo">+1000</b></td>
    <td width="360" height="35" align="left"><?php echo t('atributos.a80');?></td>
    <td width="249" align="left">( <?php echo t('atributos.a19');?> * 1000)</td>
  </tr>
  <tr class="cor_nao">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+2000</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a20');?></td>
  	<td align="left">( <?php echo t('atributos.a22');?> + 2000 <?php echo t('atributos.a21');?> )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center"><b style="font-size:13px; " class="amarelo">+60</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a23');?></td>
  	<td align="left">( <?php echo t('atributos.a41');?> * 60 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+30</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a231');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a411');?> * 30 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">+5</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a24');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a42');?> * 5 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">-30</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a25');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a43');?> * -30 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim" ><b style="font-size:13px; " class="amarelo">-30</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a251');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a431');?> * -30 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">-15</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a26');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a44');?> * -15 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">-50</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a27');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a45');?> * -50 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+ Treino/100</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a28');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a46');?> / 100 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">+25</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a29');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a47');?> * 25 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+50</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a30');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a48');?> * 50 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">+75</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a31');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a49');?> * 75 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+100</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a32');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a50');?> * 100 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">+125</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a33');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a51');?> * 125 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+150</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a34');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a52');?> * 150 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">+1000</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a35');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a53');?> * 1000 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+100</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a36');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a53');?> * 100 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">+75</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a37');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a54');?> * 75 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+500</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a38');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a55');?> * 500 )</td>
  	</tr>
  <?php /*<tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">+1000</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a39');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a56');?> * 1000 )</td>
  	</tr>*/?>
    <tr class="cor_nao">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+1000</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a40');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a57');?> * 1000 )</td>
  	</tr>
</table>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="70" align="left">&nbsp;</td>
        <td align="left"><b style="color:#FFFFFF"><?php echo t('atributos.a58');?></b></td>
      </tr>
    </table></td>
  </tr>
</table>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr class="cor_sim">
  	<td width="136" align="center"><b style="font-size:13px; " class="amarelo">+1000</b></td>
  	<td width="375" height="35" align="left" class="cor_sim"><?php echo t('atributos.a18');?></td>
  	<td width="254" align="left">( <?php echo t('atributos.a19');?> * 1000)</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+100</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a62');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a61');?> * 100 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">-10</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a63');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a43');?> * -10 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+1000</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a64');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a61');?> * 1000 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">-500</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a65');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a43');?> * -500 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+50</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a29');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a47');?> * 50 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">+100</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a30');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a48');?> * 100 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+150</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a31');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a49');?> * 150 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">+200</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a32');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a50');?> * 200 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+250</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a33');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a51');?> * 250 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">+300</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a34');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a52');?> * 300 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">-50</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a66');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a47');?> * -50 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">-100</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a67');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a48');?> * -100 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">-150</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a68');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a49');?> * -150 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">-200</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a69');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a50');?> * -200 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">-250</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a70');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a51');?> * -250 )</td>
  	</tr>
  <tr class="cor_sim">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">-300</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a71');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a52');?> * -300 )</td>
  	</tr>
  <tr class="cor_nao">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+200</b></td>
  	<td height="35" align="left" class="cor_nao">Por Bingo Book de Equipe Morto</td>
  	<td align="left" class="cor_nao">( <?php echo t('classes.c87');?> * 200 )</td>
  	</tr>
</table>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="70" align="left">&nbsp;</td>
        <td align="left"><b style="color:#FFFFFF"><?php echo t('atributos.a72');?></b></td>
      </tr>
    </table></td>
  </tr>
</table>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr class="cor_sim">
    <td width="136" align="center"><b style="font-size:13px; " class="amarelo">+1000</b></td>
    <td width="375" height="35" align="left" class="cor_sim"><?php echo t('atributos.a73');?></td>
    <td width="254" align="left">( <?php echo t('atributos.a77');?> * 1000 )</td>
  </tr><tr height="4"></tr>
  <tr class="cor_nao">
    <td align="center"><b style="font-size:13px; " class="amarelo">+100</b></td>
    <td height="35" align="left" class="cor_nao"><?php echo t('atributos.a74');?></td>
    <td align="left" class="cor_nao">( <?php echo t('atributos.a78');?> * 100  )</td>
  </tr><tr height="4"></tr>
  <tr class="cor_nao">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">+75</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a75');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('atributos.a79');?> * 75  )</td>
  	</tr><tr height="4"></tr>
  <tr class="cor_nao">
  	<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+500</b></td>
  	<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a76');?></td>
  	<td align="left" class="cor_nao">( <?php echo t('atributos.a79');?> * 500  )</td>
  	</tr>
  <tr class="cor_nao">
  	<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">+200</b></td>
  	<td height="35" align="left" class="cor_sim"><?php echo t('classes.c86');?></td>
  	<td align="left" class="cor_sim">( <?php echo t('classes.c87');?> * 200 )</td>
  	</tr>
</table>
<br />
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="70" align="left">&nbsp;</td>
				<td align="left"><b style="color:#FFFFFF"><?php echo t('atributos.a87');?></b></td>
			</tr>
		</table></td>
	</tr>
</table>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr class="cor_sim">
		<td width="136" align="center"><b style="font-size:13px; " class="amarelo">+100</b></td>
		<td width="375" height="35" align="left" class="cor_sim"><?php echo t('atributos.a88');?></td>
		<td width="254" align="left">( <?php echo t('atributos.a88');?> * 100 )</td>
	</tr>
	<tr height="4"></tr>
	<tr class="cor_nao">
		<td align="center"><b style="font-size:13px; " class="amarelo">+25</b></td>
		<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a89');?></td>
		<td align="left" class="cor_nao">( <?php echo t('atributos.a89');?> * 25  )</td>
	</tr>
	<tr height="4"></tr>
	<tr class="cor_nao">
		<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">+5</b></td>
		<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a90');?></td>
		<td align="left" class="cor_sim">( <?php echo t('atributos.a90');?> * 5  )</td>
	</tr>
	<tr height="4"></tr>
	<tr class="cor_nao">
		<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+50</b></td>
		<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a91');?></td>
		<td align="left" class="cor_nao">( <?php echo t('atributos.a91');?> * 50  )</td>
	</tr>
	<tr class="cor_nao">
		<td align="center" class="cor_sim"><b style="font-size:13px; " class="amarelo">+20</b></td>
		<td height="35" align="left" class="cor_sim"><?php echo t('atributos.a92');?></td>
		<td align="left" class="cor_sim">( <?php echo t('atributos.a92');?> * 20 )</td>
	</tr>
	<tr class="cor_nao">
		<td align="center" class="cor_nao"><b style="font-size:13px; " class="amarelo">+100</b></td>
		<td height="35" align="left" class="cor_nao"><?php echo t('atributos.a93');?></td>
		<td align="left" class="cor_nao">( <?php echo t('atributos.a93');?> * 100 )</td>
	</tr>
</table>
