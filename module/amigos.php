<script type="text/javascript" src="js/guilds.js"></script>
<div class="titulo-secao"><p><?php echo t('amigos.lista_amigos'); ?></p></div>
<?php msg(1,t('amigos.title2'),t('amigos.description2')); ?>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="150" align="center">&nbsp;</td>
            <td width="150" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.nome'); ?></b></td>
            <td width="100" align="center"><b style="color:#FFFFFF">Level</b></td>
            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.vila'); ?></b></td>
            <td width="100" align="center"></td>
        	<td width="150" align="center"></td>
          </tr>
        </table></td>
      </tr>
</table>	  
<table width="730" border="0" cellpadding="2" cellspacing="0" id="jogadores">
<?php
			$vantagem_espiao		= $basePlayer->hasItem(array(2019, 2020, 2021));
			$vantagem_conquista		= $basePlayer->vip;
			$vantagem_equipamento	= $basePlayer->hasItem(array(21880, 21881, 21882));
			
			$players  = Recordset::query('SELECT * FROM player_friend_lists WHERE id_player='.$basePlayer->id);	
			$cn		 = 0;
			foreach($players->result_array() as $p) {
			$requests = Recordset::query('SELECT * FROM ranking WHERE id_player= '.$p['id_friend']);	
				foreach($requests->result_array() as $r) {
				$cor		= ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
?>				
	  <tr <?php echo $cor ?>>
        <td width="150" align="center"><img src="<?php echo img() ?>/layout/dojo/<?php echo $r['id_classe'] ?>.png" width="126" height="44" /></td>
        <td width="150" height="34" align="left" nowrap="nowrap">
		<a class="linkTopo" style="font-size: 13px;" href="javascript:void(0)" onclick="playerProfile('<?php echo urlencode(encode($r['id_player'])) ?>')"><?php echo player_online($r['id_player'], true)?><?php echo $r['nome'] ?></a>
        <br /><?php echo $r['titulo_' . Locale::get()] ?>
        </td>
        <td width="100" align="center"><p>Level <?php echo $r['level'] ?></p></td>
        <td width="100" align="center"><img src="<?php echo img() ?>layout/bandanas/<?php echo $r['id_vila'] ?>.png" width="48" height="24" /></td>
		<td width="100" align="center">
			<a href="?secao=torneio&player=<?php echo $r['id_player'] ?>" alt="Ver Torneio Ninja" title="<?php echo t('geral.g23')?>" style="text-decoration:none">
				<img src="<?php echo img() ?>layout/bestseller.png" border="0" alt="<?php echo t('geral.g23')?>" title="<?php echo t('geral.g23')?>" align="absmiddle" />
			</a>
			<?php if($vantagem_conquista): ?>
				<a href="?secao=conquistas&id_player=<?php echo $r['id_player'] ?>" alt="<?php echo t('geral.g24')?>" title="<?php echo t('geral.g24')?>" style="text-decoration:none">
					<img src="<?php echo img() ?>layout/trofeu.png" border="0" alt="<?php echo t('geral.g24')?>" title="<?php echo t('geral.g24')?>" align="absmiddle" />
				</a>
			<?php endif; ?>	
			<?php if($vantagem_espiao): ?>
				<a href="javascript:void(0)" onclick="playerProfileTalent('<?php echo urlencode(encode($r['id_player'])) ?>')" alt="<?php echo t('geral.g25')?>" style="text-decoration:none" title="Ver talentos ninja">
					<img src="<?php echo img() ?>layout/arvore.png" border="0" alt="<?php echo t('geral.g25')?>" title="<?php echo t('geral.g25')?>" align="absmiddle" />
				</a>
			<?php endif; ?>		
			<?php if($vantagem_equipamento): ?>
				<a href="javascript:void(0)" onclick="playerProfileEquip('<?php echo urlencode(encode($r['id_player'])) ?>')" alt="<?php echo t('geral.g26')?>" style="text-decoration:none" title="Ver equipamentos ninja">
					<img src="<?php echo img() ?>layout/equips.png" border="0" alt="<?php echo t('geral.g26')?>" title="<?php echo t('geral.g26')?>" align="absmiddle" />
				</a>
			<?php endif; ?>	
		</td>
		<td width="150">
			<a class="button ui-state-red" onClick="amigoRemover(<?php echo $r['id_player']?>, this)"><?php echo t('botoes.remover');?></a>
		</td>
      </tr>
	  <tr height="4"></tr>
<?php				
	}
}
?>
</table>