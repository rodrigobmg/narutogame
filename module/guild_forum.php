<?php
	$rGuild = Recordset::query("SELECT id_player FROM guild WHERE id=" . $basePlayer->getAttribute('id_guild'), true)->row_array();
	$msg	= '';

	if(isset($_POST['option']) && $_POST['option']) {
		$_POST['option'] = decode($_POST['option']);
		
		$redir_script = true;
		
		if(!is_numeric($_POST['option'])) {
			redirect_to("negado");
		}		
		
		switch($_POST['option']) {
			case 1: // Excluir tópico
				$_POST['id'] = decode($_POST['id']);
				
				if(!is_numeric($_POST['id'])) {
					redirect_to("negado");
				}
				
				Recordset::query("DELETE FROM guild_forum_topico WHERE id=" . (int)$_POST['id']);
				Recordset::query("DELETE FROM guild_forum_topico_post WHERE id_guild_forum_topico=" . (int)$_POST['id']);
				
				$msg = t('geral.topico_deletado');
				
				break;
		}
	}
?>
<form name="fDeletaTopico" id="fDeletaTopico" method="post" action="?secao=guild_forum">
	<input type="hidden" name="option" id="option"  value="<?= encode(1) ?>"/>
	<input type="hidden" name="id" id="id" />
</form>
  <div class="titulo-secao"><p><?php echo t('guild_forum.g1')?></p></div>
<?php if(isset($_GET['created']) && $_GET['created']): ?>
    <?php msg(1,''.t('equipe_forum.ef2').'',''.t('guild_forum.g2').''); ?>
<?php elseif($msg): ?>
    <?php msg(2,''. $msg .'',''. $sMsg .''); ?>
<?php endif; ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="94" align="center">&nbsp;</td>
            <td width="190" align="center"><b style="color:#FFFFFF"><?php echo t('geral.titulo');?></b></td>
            <td width="190" align="center"><b style="color:#FFFFFF"><?php echo t('geral.criado_por');?></b></td>
            <td width="146" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ultimo_post');?></b></td>
            <td width="142" align="center">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table>
    <table width="730" border="0" cellpadding="0" cellspacing="0">
    
    <?php
		$cn			= 0;
    	$qTopicos	= Recordset::query("
			SELECT
				a.id,
				a.data_ins,
				a.titulo,
				b.nome AS nome_usuario,
				(SELECT data_ins FROM guild_forum_topico_post WHERE id_guild_forum_topico=a.id ORDER BY id DESC LIMIT 1) AS data_ult_post
			
			FROM
				guild_forum_topico a JOIN player b ON b.id=a.id_player
			
			WHERE
				a.id_guild=" . $basePlayer->getAttribute('id_guild') . "
			
			ORDER BY 1 DESC
		");
		
		if(!$qTopicos->num_rows) {
			echo "<tr><td colspan='3'><i>". t('geral.nenhum_topico')."</i></td></tr>";
		}
		
		foreach($qTopicos->result_array() as $r) {
			$cor = ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
	?>
     <tr height="30" <?php echo $cor; ?>>
    	<td width="94">&nbsp;</td>
    	<td width="190">
        	<a href="?secao=guild_forum_topico&id=<?= encode($r['id']) ?>" class="amarelo" style="text-decoration:none;"><b><?= $r['titulo'] ?></b></a>
        </td>
    	<td width="190"><b><a href="javascript:void" onclick="location.href='?secao=mensagens&msg=<?= $r['nome_usuario'] ?>'" class="linkTopo"><?= $r['nome_usuario'] ?></a></b><br /><?php echo t('geral.em');?> <?= date("d/m/Y H:i:s", strtotime($r['data_ins'])) ?></td>
    	<td width="146"><?= $r['data_ult_post'] ? date("d/m/Y H:i:s", strtotime($r['data_ult_post'])) : "-" ?></td>
        <?php if($basePlayer->id == $rGuild['id_player']): ?>
	        <td width="142">
			<a class="button" onclick="doDeletaTopicoGuild('<?= encode($r['id']) ?>')"><?php echo t('botoes.excluir_topico')?></a>
			</td>
        <?php else: ?>
        	<td width="142">&nbsp;</td>
        <?php endif; ?>
    </tr>
    <?php
		}
	?>
    </table>
    <form name="fCriarTopico" id="fCriarTopico" onsubmit="return false;" method="post" action="?acao=guild_forum_topico_novo">
    <br />
    <table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="35" align="center">&nbsp;</td>
                  <td width="219" align="left"><b style="color:#FFFFFF"><?php echo t('geral.crie_um_topico');?></b></td>

                </tr>
            </table></td>
          </tr>
      </table>
    <br />
    <table width="470" border="0" align="center" cellpadding="0" cellspacing="2">
    	<tr>
        	<th align="left"><b style="color:#FFFFFF"><?php echo t('geral.titulo');?></b><br />
        		<br />            	
       		<input name="titulo" type="text" id="titulo" size="70" maxlength="50" /></th>
        </tr>
        <tr>
          <th align="left"><b style="color:#FFFFFF"><?php echo t('geral.mensagem');?></b><br />
          	<br />          	
          	<textarea name="topico_conteudo" cols="70" rows="8" id="topico_conteudo" onKeyDown="limitText(this.form.topico_conteudo,this.form.countdown,275);" 
onKeyUp="limitText(this.form.topico_conteudo,this.form.countdown,275);" maxlength="275"></textarea></th>
        </tr>
        <tr>      	
        	<td align="center">
			<input readonly type="text" name="countdown" size="2" value="275"> <?php echo t('geral.caracteres');?>
			<a class="button" onclick="doNovoTopico()"><?php echo t('botoes.criar_topico')?></a></td>
        </tr>
    </table>
    </form>