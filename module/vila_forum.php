<?php
	$redir_script		= true;
	$can_create_fixed	= false;
	$can_delete_topic	= false;
	$postblock 		= true;

	$vila				= Recordset::query('SELECT id_cons_vila, id_kage, id_cons_guerra, id_cons_defesa FROM vila WHERE id=' . $basePlayer->id_vila)->row_array();

	if($vila['id_kage'] == $basePlayer->id || $vila['id_cons_vila'] == $basePlayer->id ||
	   $vila['id_cons_guerra'] == $basePlayer->id || $vila['id_cons_defesa'] == $basePlayer->id ||
	   $_SESSION['universal']
	) {
	
		$can_create_fixed	= true;	   
	}
	
	if($vila['id_cons_vila'] == $basePlayer->id) {
		$can_delete_topic	= false;
		$postblock = false;

	}
	if($vila['id_cons_guerra'] == $basePlayer->id) {
		$postblock = false;

	}
	if($vila['id_cons_defesa'] == $basePlayer->id) {
		$postblock = false;

	}
	if($vila['id_kage'] == $basePlayer->id) {
		$postblock = false;

	}
	
	
	if(isset($_GET['delete_topic']) && is_numeric($_GET['delete_topic']) && $can_delete_topic) {
		$topic	= Recordset::query('SELECT id_vila FROM vila_forum_topico WHERE id=' . $_GET['delete_topic']);
		
		if($topic->num_rows && $topic->row()->id_vila == $basePlayer->id_vila) {
			Recordset::update('vila_forum_topico', array(
				'removido'	=> 1
			), array(
				'id'		=> $_GET['delete_topic']
			));
			
			redirect_to('vila_forum');
		} else {
			redirect_to('negado');
		}
	}
?>
<script type="text/javascript">
	function doVilaForumPagina(p) {
		$(".topico_page").hide();
		$(".topico_page"+ p).show();	
	}
</script>
<div class="titulo-secao"><p><?php echo t('geral.forum_vila');?></p></div>
<!-- Mensagem nos Topos das Seções -->
<?php msg('3',''.t('geral.sobre_forum').'', ''.t('geral.sobre_mensagem').'');?>
<!-- Mensagem nos Topos das Seções -->
<br/>
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "1438829370";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Forum -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>       
<br/><br/>

<?php if(isset($_GET['created']) && $_GET['created']): ?>
<!-- Mensagem nos Topos das Seções -->
<?php msg('2',''.t('geral.topico_criado').'', ''.t('geral.topico_mensagem').'');?>
<!-- Mensagem nos Topos das Seções -->	
<br />
<br />
<?php endif; ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="43" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="70" align="center">&nbsp;</td>
          <td width="300" align="center"><b style="color:#FFFFFF"><?php echo t('geral.titulo');?> / <?php echo t('geral.criado_por');?></b></td>
          <td width="70" align="center"><b style="color:#FFFFFF"><?php echo t('geral.util');?></b></td>
          <td width="70" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nao_util');?></b></td>
          <td width="80" align="center"><b style="color:#FFFFFF"><?php echo t('geral.respostas');?></b></td>
          <td width="175" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ultimo_post');?></b></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <?php
	$qTopicos = Recordset::query("
		SELECT
			a.id,
			a.data_ins,
			a.titulo,
			a.id_player,
			b.nome AS nome_usuario,
			b.id_usuario,
			a.ult_resposta,
			c.nome AS ult_resposta_nome,
			c.id AS ult_resposta_id,
			a.respostas,
			a.likes,
			a.unlikes
		
		FROM
			vila_forum_topico a JOIN player b ON b.id=a.id_player
			LEFT JOIN player c ON c.id=a.ult_resposta_id
		
		WHERE
			a.id_vila={$basePlayer->id_vila} AND
			a.removido='0' AND
			a.fixo='0'
		
		ORDER BY a.ult_resposta DESC
	");

	$qTopicosFixo = Recordset::query("
		SELECT
			a.id,
			a.data_ins,
			a.titulo,
			b.nome AS nome_usuario,
			a.id_player,
			b.id_usuario,
			a.ult_resposta,
			c.nome AS ult_resposta_nome,
			c.id AS ult_resposta_id,
			a.respostas,
			a.likes,
			a.unlikes,
			a.fixo
		
		FROM
			vila_forum_topico a JOIN player b ON b.id=a.id_player
			LEFT JOIN player c ON c.id=a.ult_resposta_id
		
		WHERE
			a.id_vila={$basePlayer->id_vila} AND
			a.removido='0' AND
			a.fixo='1'
		
		ORDER BY a.ult_resposta DESC
	");
	
	$topicCounter	= 0;
	$pageCounter	= 1;
	$c				= 0;
	
	if(!$qTopicos->num_rows) {
		echo "<tr><td colspan='3'><i>".t('geral.nenhum_topico')."</i></td></tr>";
	}
?>
  <?php while($r = $qTopicosFixo->row_array()): 
	if($r['id_usuario']==2 || $r['id_usuario']==4 || $r['id_usuario']==1 || $r['id_usuario']==3 || $r['id_usuario']==18){
		$cor="style='background-color:#24361a; color:#FFF'";
	}else if($r['fixo']){
		$cor="style='background-color:#270235; color:#FFF'";
	}else{
		$cor="";
	}
  ?>
  <tr>
    <td width="70" height="40" <?php echo $cor?>>
    	<?php if($r['fixo']){?><img src="<?php echo img() ?>layout/important.png"  alt="Tópico Fixo"/><?php }?>
		<?php if($can_delete_topic): ?>
			<a href="?secao=vila_forum&delete_topic=<?php echo $r['id'] ?>"><img src="<?php echo img('/layout/delete_16x16.gif') ?>" /></a>
		<?php endif; ?>    
    </td>
    <td width="300" align="left" <?php echo $cor?>>
	    <a href="?secao=vila_forum_topico&id=<?= encode($r['id']) ?>" class="linksSite">
	    	<strong class="amarelo" style="font-size:13px"><?php echo $r['titulo'] ?></strong>
	    </a><br />
	    <a href="javascript:void" onclick="location.href='?secao=mensagens&msg=<?= $r['nome_usuario'] ?>'" class="linkTopo">
	    	<?= player_icone($r['id_player']) . $r['nome_usuario'] ?>
	    </a>
	    <?php echo t('geral.em')?> <?= date("d/m/Y H:i:s", strtotime($r['data_ins'])) ?>
    </td>      
    <td width="70" <?php echo $cor?>><?php echo $r['likes'] ?></td>
    <td width="70" <?php echo $cor?>><?php echo $r['unlikes'] ?></td>
    <td width="80" <?php echo $cor?>><?php echo $r['respostas'] ?></td>
    <td width="175" <?php echo $cor?>><?= $r['ult_resposta_id'] ? date("d/m/Y H:i:s", strtotime($r['ult_resposta'])) . " ".t('geral.por')."<br />" . player_icone($r['ult_resposta_id']) . $r['ult_resposta_nome'] : "-" ?></td>
  </tr>
  <?php endwhile; ?>

  <?php while($r = $qTopicos->row_array()):
	if($r['id_usuario']==2 || $r['id_usuario']==4 || $r['id_usuario']==1 || $r['id_usuario']==3 || $r['id_usuario']==18){
		$cor="style='background-color:#ff0000; color:#FFF'";
	}else{
		$cor="";
	}	
  ?>
  <?php $bg		= ++$c % 2 ? "cor_sim" : "cor_nao"; ?>
	<tr class="topico_page topico_page<?php echo $pageCounter ?> <?php echo $bg ?>">
		<td width="70" height="40">
			<?php if($can_delete_topic): ?>
				<a href="?secao=vila_forum&delete_topic=<?php echo $r['id'] ?>"><img src="<?php echo img('/layout/delete_16x16.gif') ?>" /></a>
			<?php endif; ?>    
		</td>
		<td width="300" align="left">
			<a href="?secao=vila_forum_topico&id=<?= encode($r['id']) ?>" class="linksSite">
				<strong class="amarelo" style="font-size:13px"><?= $r['titulo'] ?></strong>
			</a><br />
			<a href="javascript:void" onclick="location.href='?secao=mensagens&msg=<?= $r['nome_usuario'] ?>'" class="linkTopo">
				<?= player_icone($r['id_player']) . $r['nome_usuario'] ?>
			</a>
			<?php echo t('geral.em')?> <?= date("d/m/Y H:i:s", strtotime($r['data_ins'])) ?>
		</td>
		<td width="70"><?php echo $r['likes'] ?></td>
		<td width="70"><?php echo $r['unlikes'] ?></td>
		<td width="80"><?php echo $r['respostas'] ?></td>
		<td width="175"><?= $r['ult_resposta_id'] ? date("d/m/Y H:i:s", strtotime($r['ult_resposta'])) . " ".t('geral.por')."<br /><span class='verde'>" . player_icone($r['ult_resposta_id'])  . $r['ult_resposta_nome'].'</span>' : "-" ?></td>
	</tr>
  <?php
	$topicCounter++;

	if($topicCounter == 20) {
		$topicCounter = 0;
		$pageCounter++;
	}
	?>
  <?php endwhile; ?>
</table>
<br />

<div class="paginator">
  <?php for($f = 1; $f <= $pageCounter; $f++): ?>
  <a style="color: #FFF; text-decoration:none" href="javascript:void(0)" class="linkTopo" onclick="doVilaForumPagina(<?php echo $f ?>)"><?php echo $f ?></a>
  <?php endfor; ?>
</div><br/>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- NG - Forum -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9166007311868806"
     data-ad-slot="1438829370"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<br/><br/>

<?php if(get_player_bloqueio($basePlayer->id)): ?>
	<div style="font-size:14px; font-weight:bold; background:#1c1c1c; border:1px solid #151515; padding:10px"><?php echo t('geral.mensagem1');?></div>
<?php endif; ?>

<?php if(!get_player_bloqueio($basePlayer->id) && cani_post_topic($postblock)): ?>
	<?php if($basePlayer->vip || $basePlayer->level > 24): ?> 
	<form name="fCriarTopico" id="fCriarTopico" onsubmit="return false;" method="post" action="?acao=vila_forum_topico_novo">
	  <table width="730" border="0" cellpadding="0" cellspacing="0">
		<tr>
		  <td height="43" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
			  <tr>
				<td width="730" align="center"><b style="color:#FFFFFF"><?php echo t('geral.crie_um_topico');?></b></td>
			  </tr>
			</table></td>
		</tr>
	  </table>
	  <table width="600" border="0" align="center" cellpadding="0" cellspacing="4">
		<tr>
		  <th align="right"><b style="color:#FFFFFF"><?php echo t('geral.titulo');?></b></th>
		  <td align="left"><input name="titulo" type="text" id="titulo" size="79" maxlength="50" /></td>
		</tr>
		<tr>
		  <th align="right"><b style="color:#FFFFFF"><?php echo t('geral.mensagem');?></b></th>
		  <th align="left"><textarea name="topico_conteudo" cols="78" rows="10" id="topico_conteudo" onKeyDown="limitText(this.form.topico_conteudo,this.form.countdown,275);" 
onKeyUp="limitText(this.form.topico_conteudo,this.form.countdown,275);" maxlength="275"></textarea></th>
		</tr>
		<?php if($can_create_fixed): ?>
		<tr>
			<td colspan="2" align="center">
				<input type="checkbox" value="1" name="fixed" /> <?php echo t('geral.topico_fixo');?>
			</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td colspan="2"><input readonly type="text" name="countdown" size="2" value="275"> <?php echo t('geral.caracteres');?> <input type="button" role="button" class="button" name="bNovoTopico" id="bNovoTopico" onclick="doNovoTopico();" value="<?php echo t('botoes.criar_topico');?>" /></td>
		</tr>
	  </table>
	
	</form>
  <?php endif; ?>
<?php endif; ?>  
<script type="text/javascript">
	doVilaForumPagina(1);
</script>