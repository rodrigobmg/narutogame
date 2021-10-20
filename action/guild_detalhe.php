<?php
	$id = $_POST['id'];

	if(!is_numeric($id)) {
		redirect_to("negado");
	}

	$guild = Recordset::query('
		SELECT
			a.id,
			a.id_player AS id_lider,
			b.nome AS nome_lider,
			a.nome,
			a.descricao,
			a.imagem,
			b.id_vila,
			a.membros,
			a.level,
			c.posicao_geral,
			c.posicao_vila
		
		FROM
			guild a JOIN player b ON a.id_player=b.id
			LEFT JOIN ranking_guild c ON c.id_player=a.id_player
		
		WHERE
			a.id=' . $id);
	
	if(!$guild->num_rows) {
		die('jalert("'.t('actions.a167').'")');
	}
	
	$guild = $guild->row_array();
?>
<div style="background-color: #252525; border: #333 solid 1px">
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr >
      <td colspan="2"><img src="<?php echo img() ?>bt_organizacao.jpg" alt="Organização - Naruto Game" /></td>
    </tr>
  </table>
  <br />
<table width="730" border="0" cellpadding="4" cellspacing="0">
      <tr class="txtNaoConcluido">
        <td colspan="3" align="center"><img src="images/guild/<?php echo $guild['imagem'] ?>" onerror="this.src='images/guild_sem_imagem.jpg'" /></td>
        </tr>
      <tr class="txtNaoConcluido">
        <td width="260" rowspan="2" align="left"><p class="txtDestaque"><?php echo $guild['nome'] ?></p>
          <p><?php echo $guild['descricao'] ?><br />
          </p>
          <p class="txtDestaque">Lider da Organização</p>
          <p><?php echo $guild['nome_lider'] ?></p></td>
        <td width="233" rowspan="2" align="left" valign="top"><p class="txtDestaque">Estat&iacute;sticas</p>
          <p>
          	Posição na vila: <b class="txtConcluido"><?php echo $guild['posicao_geral'] ?>&deg;</b><br />
          	Posição geral: <b class="txtConcluido"><?php echo $guild['posicao_vila'] ?>&deg;</b><br />
            Nível: <b class="txtConcluido"><?php echo $guild['level'] ?></b><br />
          Total de Players: <b class="txtConcluido"><?php echo $guild['membros'] ?></b></p></td>
        <td width="213" align="left" valign="top">Se voc&ecirc; quer participar dessa organização, clique abaixo e espere a autorização do L&iacute;der.<br />
          <br /></td>
      </tr>
      <tr class="txtNaoConcluido">
        <td align="center" valign="top">
        <?php if(!$basePlayer->getAttribute('id_guild')): ?>
        <?php
        	if($guild['membros'] < 9 && $basePlayer->getAttribute('id_vila') == $guild['id_vila'] && $basePlayer->getAttribute('level') >= 15) {
		?>
        <input type="submit" name="btn" id="btn" onclick="this.disabled=true; entrarGuild('<?php echo encode($guild['id']) ?>')" value="Quero participar dessa organização" />
        <?php	} ?>
        <?php endif; ?>
        <?php /*if($basePlayer->dono_guild): ?>
        <form action="?secao=guild_desafio" method="post">
        <input type="hidden" name="guild" value="<?php echo encode($guild['id']) ?>" />
        <input type="submit" value="Desafiar essa organiação" />
        </form>
        <? endif;*/ ?>
        </td>
      </tr>
      <tr>
      	<td colspan="3" align="right"><input type="image" id="b-detalhe-guild-cancelar"  src="images/bt_cancelar.gif"  border="0" />
</td>
      </tr>
    </table>
</div>