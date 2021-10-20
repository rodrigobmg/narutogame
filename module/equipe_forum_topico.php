<?php
	$id = decode($_GET['id']);
	
	if(!is_numeric($id)) {
		$redir_script = true;
		redirect_to("negado");
	}
	
	$rTopico = Recordset::query("SELECT * FROM equipe_forum_topico WHERE id=" . (int)$id)->row_array();
	
	// Se o id do topico for e outra liva da negado (vai ser um mlagre isso aki acontecer .. mas por precaução)
	if($rTopico['id_equipe'] != $basePlayer->id_equipe) {
		$redir_script = true;
		redirect_to("negado");
	}
?>
<div class="titulo-secao"><p><?php echo t('equipe_forum.ef1')?></p></div>
  <br />
	<?php if(isset($_GET['created']) && $_GET['created']): ?>
            <?php msg(3,''.t('equipe_forum_topico.ef1').'',''.t('equipe_forum_topico.ef2').''); ?>
    <?php endif; ?>   
     <table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
          <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="50" align="center">&nbsp;</td>
                  <td width="122" align="left"><b style="color:#FFFFFF;"><?php echo t('geral.topico')?>: <?= $rTopico['titulo'] ?></b></td>

                  <td width="526" align="left">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
  </table>
     <table width="730" border="0" align="center" cellpadding="2" cellspacing="0">
 		<tr>
        	<td style="background-color:#413625; min-height: 90px; max-height: none; height:125px;">
            	<?php echo nl2br($rTopico['conteudo']) ?>
            </td>
        </tr>
     </table>
    <br />
    <br />
    <table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
          <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="50" align="center">&nbsp;</td>
                  <td width="122" align="left"><b style="color:#FFFFFF;"><?php echo t('geral.respostas')?></b></td>
                  <td width="526" align="left">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
  </table>
    <?php
    	$qRespostas = Recordset::query("
			SELECT
				a.id,
				a.conteudo,
				a.data_ins,
				b.nome,
				b.id as id_player,
				b.id_classe
			
			FROM
				equipe_forum_topico_post a JOIN player b ON a.id_player=b.id		
			
			WHERE
				a.id_equipe_forum_topico=". (int)$id);
		
		if(!$qRespostas->num_rows) {
			echo "<i>".t('guild_forum.g4')."</i>";
		}
		$cn = 0;
		while($r = $qRespostas->row_array()) {
			$cor	 = ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
	?>
    <div>
      <table width="730" border="0" cellpadding="2" cellspacing="0">
      	<tr <?php echo $cor; ?>>
       		<td rowspan="2" height="34" width="140" align="center">
				<img src="<?php echo player_imagem($r['id_player'], "pequena"); ?>" />
            </td>
            <td width="625" colspan="6" align="left" style="-webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;">
				<div style="padding: 10px; min-height:125px">
					Por <b><a class="link_verde" href="javascript:void" onclick="location.href='?secao=mensagens&msg=<?=  $r['nome'] ?>'"><?php echo player_online($r['id_player'],true)?><?=  $r['nome'] ?></a></b> <?php echo t('geral.em')?> <?= date("d/m/Y H:i:s", strtotime($r['data_ins'])) ?>
					<br /><br />
					<div style="word-wrap: break-word; width:500px; text-align:left;"><?php echo nl2br($r['conteudo']) ?></div>
				</div>	
            </td>
            </tr>
        <tr height="4"></tr>
     </table>      
    </div>
    <br />
    <?php
		}
	?>
    <br />
    <form method="post" name="fResposta" id="fResposta" onsubmit="return false;" action="?acao=equipe_forum_topico_responder">
    <input type="hidden" value="<?= encode($rTopico['id']) ?>" name="id" />
    <br />
    <br />
     <table width="450" style="margin:auto; width:450px !important" border="0" align="center" cellpadding="0" cellspacing="2">
        <tr>
          <th colspan="2" width="441" align="left"><b style="color:#FFFFFF"><?php echo t('geral.sua_resposta')?></b></th>
        </tr>
        <tr>
          <th colspan="2" align="left"><textarea name="msg_conteudo" cols="80" rows="7" id="msg_conteudo" onKeyDown="limitText(this.form.msg_conteudo,this.form.countdown,1000);" 
onKeyUp="limitText(this.form.msg_conteudo,this.form.countdown,650);" maxlength="650"></textarea></th>
        </tr>
        <tr>
        	<td align="left"><input readonly type="text" name="countdown" size="2" value="650"> <?php echo t('geral.caracteres')?></td>
        	<td align="right"><input class="button" role="button" type="submit" name="bPostar" onclick="doResposta()" value="<?php echo t('botoes.postar_resposta')?>" /></td>
      </tr>
    </table>
    </form>