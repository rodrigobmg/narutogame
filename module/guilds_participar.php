<script type="text/javascript" src="js/guilds.js"></script>
<div class="titulo-secao"><p><?php echo t('guilds_participar.g1')?></p></div>
<br/>
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "2636360978";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Guilds -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/><br/>
    <div id="cnBase" class="direita">
<table width="730" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td width="565" align="right"><strong><?php echo t('geral.filtrar')?>:</strong></td>
	<td width="149" align="center">
		<input type="text" id="t-guild-filtro" size="40" />
		<script type="text/javascript">
			$("#t-guild-filtro").keyup(function () {
				//console.log($("#tb-guilds") + " - - " + this.value );
				$.uiTableFilter($("#tb-guilds"), this.value );
			});
		</script>            
	</td>
</tr>
</table>
    <table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
          <tr>
            <td width="130" align="center">&nbsp;</td>
            <td width="200" align="center"><?php echo t('geral.nome')?></td>
            <td width="140" align="center"><?php echo t('geral.lider')?></td>
			<td width="80" align="center"><?php echo t('geral.level')?></td>
			<td width="80" align="center"><?php echo t('geral.membros')?></td>
            <td width="110" align="center">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table>
  
    <table width="730" border="0" cellpadding="0" cellspacing="0" id="tb-guilds">
	<?php
		$cn		= 0;
    	$guilds = Recordset::query("
			SELECT
				a.id,
				a.nome,
				b.nome AS lider,
				a.membros,
				a.level,
				b.id_classe
			
			FROM
				guild a JOIN player b ON b.id=a.id_player AND b.removido=0
				AND a.removido='0'
				AND b.id_vila=" . $basePlayer->getAttribute('id_vila'));
		
		$pendencias 	= Recordset::query('SELECT * FROM guild_pendencia WHERE id_player=' . $basePlayer->id);
		$arPendencias	= array();
		
		foreach($pendencias->result_array() as $pendencia) {
			$arPendencias[$pendencia['id_guild']] = true;
		}
		
		foreach($guilds->result_array() as $r) {
		$cor	 = ++$cn % 2 ? "class='cor_sim equipe'" : "class='cor_nao equipe'";
	?>
    <tr <?php echo $cor;?> id="tr-guild-<?php echo $r['id'] ?>">
        <td width="130" align="center">
        	<img src="<?php echo img() ?>layout<?php echo LAYOUT_TEMPLATE?>/dojo/<?php echo $r['id_classe'] ?><?php echo LAYOUT_TEMPLATE=="_azul" ? ".jpg":".png"?>" width="126" height="44" />
        </td>
    	<td width="200" class="amarelo" style="font-size:13px; font-weight: bold"><?php echo $r['nome'] ?></td>
		<td width="140"><a href="javascript:void(0)" class="linkCabecalho" onclick="location.href='?secao=mensagens&msg=<?php echo $r['lider'] ?>'"><?php echo $r['lider'] ?></a></b></td>
    	<td width="80"><?php echo $r['level'] ?></td>
    	<td width="80"><?php echo $r['membros'] ?></td>
        <td width="110">
        	<?php if(!isset($arPendencias[$r['id']])): ?>
				<a class="button b-entrar-guild" onclick="entrarGuild('<?php echo $r['id'] ?>', this)"><?php echo t('botoes.participar') ?></a>       	
        	<?php else: ?>
				<a class="button ui-state-disabled"><?php echo t('botoes.participar') ?></a>
			<?php endif; ?>
        </td>
    </tr>
	<tr height="4"></tr>
    <?php
		}
	?>
    </table>
	</div>