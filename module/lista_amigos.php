<script type="text/javascript" src="js/guilds.js"></script>
<div class="titulo-secao"><p><?php echo t('amigos.solicita_amigos'); ?></p></div>
<form id="frmAmigos" method="post">
 <?php msg(1,t('amigos.title'),t('amigos.description').'<br /><br />
            <input type="text" value="" name="txtBusca"/>&nbsp;&nbsp;&nbsp;<input class="button" type="submit" value="'.t('amigos.procurar').'" onclick="this.disabled=true; this.form.submit()" />'); ?>
</form>
<br />
<?php
	$pendents = Recordset::query("SELECT count(id) as total FROM player_friend_requests WHERE id_friend=".$basePlayer->id)->result_array();
?>
<table border="0" cellpadding="0" cellspacing="0" align="center" class="with-n-tabs"  id="tb100" data-auto-default="1">
	<tr>
		<td><a class="button" rel="#jogadores"><?php echo t('conquistas.c30');?></a></td>
        <td width="20"></td>
		<td><a class="button" rel="#pendencias"><?php echo t('botoes.pendencias');?> (<?php echo $pendents[0]['total']?>)</a></td>
        <td width="20"></td>
	</tr>
</table>
<br />
<table width="730" border="0" cellpadding="2" cellspacing="0" id="jogadores">
<?php
	if($_POST){
		if($_POST['txtBusca']){
			$players  = Recordset::query('SELECT * FROM ranking WHERE nome like "%'.addslashes($_POST['txtBusca']).'%" AND level > 4 AND id_player not in (select id_friend FROM player_friend_lists WHERE id_player='.$basePlayer->id.') AND id_player not in ('.$basePlayer->id.')');	
			$cn		 = 0;
			foreach($players->result_array() as $r) {
			$requests = Recordset::query('SELECT * FROM player_friend_requests WHERE id_player= '.$basePlayer->id.' and id_friend='.$r['id_player'])->result_array();	
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
		<td width="200">
			<?php if($requests){?>
				<a class="button"><?php echo t('amigos.solicitacao')?></a>
			<?php }else{?>
				<a class="button" onClick="sendRequest(<?php echo $r['id_player']?>, this)"><?php echo t('amigos.amizade')?></a>
			<?php }?>	
		</td>
      </tr>
	  <tr height="4"></tr>
<?php				
			}
		}
	}
?>
</table>
<table width="730" border="0" cellpadding="2" cellspacing="0" id="pendencias">
<?php
		$pendencias  = Recordset::query('SELECT * FROM player_friend_requests WHERE id_friend = '.$basePlayer->id);	
		$cn		 = 0;
		foreach($pendencias->result_array() as $p) {
			$players = Recordset::query('SELECT * FROM ranking WHERE id_player = '.$p['id_player']);
			foreach($players->result_array() as $r) {
				$cor = ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
?>	
  <tr <?php echo $cor ?>>
	<td width="150" align="center"><img src="<?php echo img() ?>/layout/dojo/<?php echo $r['id_classe'] ?>.png" width="126" height="44" /></td>
	<td width="150" height="34" align="left" nowrap="nowrap">
	<a class="linkTopo" style="font-size: 13px;" href="javascript:void(0)" onclick="playerProfile('<?php echo urlencode(encode($r['id_player'])) ?>')"><?php echo player_online($r['id_player'], true)?><?php echo $r['nome'] ?></a>
	<br /><?php echo $r['titulo_' . Locale::get()] ?>
	</td>
	<td width="100" align="center"><p>Level <?php echo $r['level'] ?></p></td>
	<td width="100" align="center"><img src="<?php echo img() ?>layout/bandanas/<?php echo $r['id_vila'] ?>.png" width="48" height="24" /></td>
	<td width="200">
		<a class="button" onClick="amigoAceitar(<?php echo $r['id_player']?>, this)">Aceitar</a>
		<a class="button ui-state-red" onClick="amigoNegar(<?php echo $r['id_player']?>, this)">Recusar</a>
	</td>
  </tr>
  <tr height="4"></tr>
  <?php } } ?> 
</table>
	