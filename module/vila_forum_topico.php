<?php
	$redir_script	= true;
	$id				= decode($_GET['id']);
	$postblock 		= true;
	
	if(!is_numeric($id)) {
		redirect_to("negado", null, array('e' => 1));
	}
		
	if($_SESSION['universal'] && isset($_GET['block']) && $_GET['block']) {
		add_player_bloqueio($_GET['usuario'], $_GET['player']);
	}
	
	$can_delete_response	= false;
	$vila					= Recordset::query('SELECT id_cons_vila,id_kage, id_cons_guerra,id_cons_defesa FROM vila WHERE id=' . $basePlayer->id_vila)->row_array();
	$rTopico				= Recordset::query("SELECT * FROM vila_forum_topico WHERE id=" . (int)$id)->row_array();
	$rTopico2				= Recordset::query("SELECT vf.*, vft.conteudo FROM vila_forum_topico as vf
												JOIN vila_forum_topico_post as vft ON vf.id = vft.id_vila_forum_topico 
												WHERE vf.id=".(int)$id." and vf.id_player = vft.id_player
												order by vft.id LIMIT 1")->row_array();
	
	if($_SESSION['universal']) {
		$can_delete_response	= true;
	}
	
	if($vila['id_cons_vila'] == $basePlayer->id) {
		$can_delete_response	= false;
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
	
	// Se o id do topico for e outra liva da negado (vai ser um mlagre isso aki acontecer .. mas por precaução)
	if($rTopico['id_vila'] != $basePlayer->id_vila) {
		$redir_script = true;
		redirect_to("negado", null, array('e' => 2));
	}
	
	// Deletar um post do topico
	if(isset($_GET['delete_response']) && is_numeric($_GET['delete_response']) && $can_delete_response) {
		$response	= Recordset::query('SELECT id_vila_forum_topico FROM vila_forum_topico_post WHERE id=' . $_GET['delete_response']);
		
		if($response->num_rows && $response->row()->id_vila_forum_topico == $id) {
			Recordset::update('vila_forum_topico_post', array(
				'removido'	=> 1
			), array(
				'id'		=> $_GET['delete_response']
			));
			
			redirect_to("vila_forum_topico", null, array('id' => $_GET['id']));
		} else {
			redirect_to("negado", null, array('e' => 1));
		}
	}
?>
<style type="text/css">
	.response-controls {
		float: right
	}
</style>
<script type="text/javascript">
	function doVilaForumTopicoPagina(p) {
		$(".topico_page").hide();
		$(".topico_page"+ p).show();	
	}

	var arForumTopicVotes = [];	
	function doForumTopicoPostVoto(i, o) {
		for(var f in arForumTopicVotes) {
			if(arForumTopicVotes[f] == i) {
				return;
			}
		}

		$("#f-forum-topic-vote-h-vote-" + i).val(o);
		$("#d-response-" + (o ? "" : "un") + "likes-" + i).html(parseInt(document.getElementById("d-response-" + (o ? "" : "un") + "likes-" + i).innerHTML) + 1);
				
		arForumTopicVotes.push(i);

		$.ajax({
			url: '?acao=vila_forum_topico_voto',
			type: 'post',
			data: $("#f-forum-topic-vote-" + i).serialize(),
			success: function () {
				alert('<?php echo t('geral.mensagem3');?>');
			}
		});		
	}
</script>
<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p><?php echo t('geral.forum_vila');?></p></div>
<br />
<?php if(isset($_GET['created']) && $_GET['created']): ?>
<?php msg('2',''.t('equipe_forum_topico.ef1').'', ''.t('geral.mensagem2').'');?>
<br /><br />	
<?php endif; ?>   

  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="730" align="left"><b style="color:#FFFFFF;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo t('geral.topico')?>: <?= $rTopico['titulo'] ?></b></td>
          </tr>
        </table></td>
    </tr>
  </table>
	<?php
		$qRespostas = Recordset::query("
			SELECT
				a.id,
				a.conteudo,
				a.id_player,
				a.id_usuario,
				a.data_ins,
				b.nome,
				b.id_classe,
				a.likes,
				a.unlikes,
				a.kage,
				a.cons_vila,
				a.cons_def,
				a.cons_guerra
			
			FROM
				vila_forum_topico_post a JOIN player b ON a.id_player=b.id
				
			WHERE
				a.id_vila_forum_topico=". (int)$id ." AND
				a.removido=0 ORDER BY a.data_ins");
			
		$replyCounter	= 0;
		$pageCounter	= 1;
		$j				= 0;
		$c				= 0;
	?>
	<?php while($r = $qRespostas->row_array()): ?>
	<?php
		$bg		= ++$c % 2 ? "class='cor_sim'" : "class='cor_nao'";
		if($r['id_usuario']==2 || $r['id_usuario']==4 || $r['id_usuario']==1 || $r['id_usuario']==3 || $r['id_usuario']==18){
			$cor="style='color:#af9d6b'";
		} else {
			$cor="";
		}
		
		$color	= '';
		
		if($r['kage']) {
			$color	= 'color: ' . USER_COLOR_KAGE;
		}

		if($r['cons_vila']) {
			$color	= 'color: ' . USER_COLOR_CONS_VILA;
		}

		if($r['cons_def']) {
			$color	= 'color: ' . USER_COLOR_CONS_DEF;
		}

		if($r['cons_guerra']) {
			$color	= 'color: ' . USER_COLOR_CONS_GUERRA;
		}
		
		$canVote = Recordset::query("SELECT id FROM vila_forum_topico_post_voto WHERE id_player=" . $basePlayer->id . " AND id_vila_forum_topico_post=" . $r['id'])->num_rows ? false : true;
	?>
	<br />
	<table class="topico_page topico_page<?php echo $pageCounter ?>" width="690" border="0" cellpadding="0" cellspacing="0">
		<tr <?php echo $bg ?>>
			<td width="140" valign="top" rowspan="2">
				<img src="<?php echo player_imagem($r['id_player'], "pequena"); ?>" width="119px" height="106px" /><br />
			</td>
			<td  width="525" height="80" colspan="6" align="left" style="-webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;">
			<div style="padding: 10px; min-height:106px; width:605px;">
				Por <b><a class="link_verde" href="javascript:void" onclick="location.href='?secao=mensagens&msg=<?php echo  $r['nome'] ?>'"><?php echo player_online($r['id_player'],true)?><?= player_icone($r['id_player']) .  $r['nome'] ?></a></b> <?php echo t('geral.em')?> <?php echo date("d/m/Y H:i:s", strtotime($r['data_ins'])) ?>
				<div class="response-controls">
					<?php if($_SESSION['universal']): ?>
						<a href="?secao=vila_forum_topico&id=<?php echo $_GET['id'] ?>&block=1&usuario=<?php echo $r['id_usuario'] ?>&player=<?php echo $r['id_player'] ?>"><img border="0" src="<?php echo img()?>layout/block.png" alt="Bloquear" style="cursor:pointer" /></a>
					<?php endif; ?>
					<?php if($can_delete_response): ?>
						<a href="?secao=vila_forum_topico&id=<?php echo $_GET['id'] ?>&delete_response=<?php echo $r['id'] ?>"><img src="<?php echo img('/layout/delete_16x16.gif') ?>" /></a>
					<?php endif; ?>
				</div>
				<br /><br />
				<div style="word-wrap: break-word; width:600px; text-align:left; <?php echo $color ?>">
				<?php echo nl2br($r['conteudo']) ?>
				</div>
                <br />
				<?php if($canVote): ?>
					<div align="right">
						<form id="f-forum-topic-vote-<?php echo $r['id'] ?>">
							<input type="hidden" name="id" value="<?php echo encode($r['id']) ?>" />
							<input type="hidden" name="vote" id="f-forum-topic-vote-h-vote-<?php echo $r['id'] ?>" />
						</form>
						<?php endif; ?>
						<?php if($j <= 0){ ?>
						<?php echo t('geral.m4')?> <span id="d-response-likes-<?php echo $r['id'] ?>"><?php echo $r['likes'] ?></span>
						<input type="image" onclick="<?php echo $canVote ? "doForumTopicoPostVoto(" . $r['id'] . ", 1)" : "" ?>" src="<?php echo img()?>layout/like.png" />
						<?php echo t('geral.m5')?> <span id="d-response-unlikes-<?php echo $r['id'] ?>"><?php echo $r['unlikes'] ?></span>
						<input type="image" onclick="<?php echo $canVote ? "doForumTopicoPostVoto(" . $r['id'] . ", 0)" : "" ?>" src="<?php echo img()?>layout/unlike.png" />
					</div>
				<?php } ?>
			</div>	
			</td>
		</tr>
        <tr height="4"></tr>
	</table>      
	<?php
		$replyCounter++;
		$j++;
		if($replyCounter == 10) {
			$replyCounter = 0;
			$pageCounter++;
		}
	?>
	<?php endwhile; ?>
</div><br />
<div class="paginator">
	<?php for($f = 1; $f <= $pageCounter; $f++): ?>
		<a style="color: #FFF; text-decoration: none" href="javascript:void(0)" onclick="doVilaForumTopicoPagina(<?php echo $f ?>)"><?php echo $f ?></a>
	<?php endfor; ?>
</div>
<br />
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
<br /><br />
<?php if(get_player_bloqueio($basePlayer->id)): ?>
	<div style="font-size:14px; font-weight:bold; background:#1c1c1c; border:1px solid #151515; padding:10px"><?php echo t('geral.mensagem1')?></div>
<?php endif; ?>

<?php if(!get_player_bloqueio($basePlayer->id) && cani_post_reply($postblock)): ?>
	<?php if($basePlayer->vip || $basePlayer->level > 14): ?> 
	<form method="post" name="fResposta" id="fResposta" onsubmit="return false;" action="?acao=vila_forum_topico_responder">
	<input type="hidden" value="<?php echo encode($rTopico['id']) ?>" name="id" />
	  <table width="730" border="0" cellpadding="0" cellspacing="0">
		<tr>
		  <td height="43" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
			  <tr>
				<td width="730" align="center"><b style="color:#FFFFFF; font-size:14px"><?php echo t('ler_noticia.l7')?></b></td>
			  </tr>
			</table></td>
		</tr>
	  </table>
	<table width="450" border="0" align="center" cellpadding="0" cellspacing="4">
	
		<tr>
		  <th align="left"><textarea name="msg_conteudo" cols="87" rows="7" id="msg_conteudo" onKeyDown="limitText(this.form.msg_conteudo,this.form.countdown,1000);" 
onKeyUp="limitText(this.form.msg_conteudo,this.form.countdown,650);" maxlength="650"></textarea></th>
		</tr>
		<tr>
			<td align="center"><input readonly type="text" name="countdown" size="1" value="1000"> <?php echo t('geral.caracteres');?><input type="submit" value="<?php echo t('geral.m6')?>" name="bPostar" onclick="doResposta();"/></td>
		</tr>
	</table>
	</form>
	<?php endif; ?>
<?php endif; ?>	
<script type="text/javascript">
	doVilaForumTopicoPagina(1);
</script>