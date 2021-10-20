<script type="text/javascript">
	function detalheBatalha(i) {
		window.open('?acao=log_batalha&id=' + i, 'log_batalha', 'width=760,height=600,menubar=no,toolbar=no,resizable=yes,scrollbars=yes');
	}
	
	function doPagina(i) {
		$('.batalha-pagina').hide();
		$('.batalha-pagina-' + i).show();
	}
</script>
<div class="titulo-secao"><p><?php echo t('log_batalha.lg1')?></p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "2276434172";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Historico -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/><br/>
<?php if(!$basePlayer->hasItem(array(20313, 20314, 20315))): ?>
<!-- Mensagem nos Topos das Seções -->
<?php msg('5',''.t('log_batalha.lg2').'', ''.t('log_batalha.lg3').'');?>
<!-- Mensagem nos Topos das Seções -->
<?php else: ?>
<form method="post">
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
	<td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
		<tr>
		  <td width="66" align="center">&nbsp;</td>
		  <td width="235" align="left"><b style="color:#FFFFFF"><?php echo t('log_batalha.lg4')?></b></td>

		  <td width="429" align="left">
				
		  </td>
		</tr>
	</table></td>
  </tr>
</table>
<table width="730" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th width="15%"><?php echo t('log_batalha.lg5')?></th>
			<td width="20%"><input type="text" name="jogador1" value="<?php echo isset($_POST['jogador1']) ? $_POST['jogador1'] : '' ?>" /></td>
			<td width="5%" align="center"><strong>VS</strong></td>
			<td width="15%" align="center"><strong><?php echo t('log_batalha.lg6')?></strong></td>
			<td width="20%"><input type="text" name="jogador2" value="<?php echo isset($_POST['jogador2']) ? $_POST['jogador2'] : '' ?>" /></td>
			<td width="20%">
				<input type="submit" class="button" valeu="<?php echo t('botoes.filtrar')?>"/>	
			</td>
		</tr>
		<tr>
			<td align="right" colspan="6">
				<br /></td>
		</tr>	
	</table>
<br />
</form>
<?php endif; ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="150" align="center"><b style="color:#FFFFFF"><?php echo t('log_batalha.lg5')?></b></td>
            <td width="40" align="center"><b style="color:#FFFFFF">VS</b></td>
            <td width="150" align="center"><b style="color:#FFFFFF"><?php echo t('log_batalha.lg6')?></b></td>
            <td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('log_batalha.lg7')?></b></td>
			<td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('log_batalha.lg8')?></b></td>
			<td width="70" align="center"><b style="color:#FFFFFF">&nbsp;</b></td>
          </tr>
        </table></td>
      </tr>
    </table>
<?php
	$where = ' AND a.id_player=' . $basePlayer->id;
	if(isset($_POST['jogador1']) && $_POST['jogador1'] || isset($_POST['jogador2']) && $_POST['jogador2']) {
		$where = '';
		
		if(isset($_POST['jogador1']) && $_POST['jogador1']) {
			$j1 = Recordset::query('SELECT id_player FROM player_nome WHERE nome=\'' . addslashes($_POST['jogador1']) . '\'');
			
			if($j1->num_rows) {
				$where .= ' AND a.id_player=' . (int)$j1->row()->id_player;				
			} else {
				$where .= ' AND a.id_player=0';					
			}
		}

		if(isset($_POST['jogador2']) && $_POST['jogador2']) {
			$j2 = Recordset::query('SELECT id_player FROM player_nome WHERE nome=\'' . addslashes($_POST['jogador2']) . '\'');
			

			if($j2->num_rows) {
				$where .= ' AND a.id_playerb=' . (int)$j2->row()->id_player;				
			} else {
				$where .= ' AND a.id_playerb=0';					
			}
		}
	}

	$batalhas = Recordset::query('
		SELECT
			a.*,
			b.nome AS nome_player,
			c.nome AS nome_playerb,
			b.id_classe,
			c.id_classe AS id_classeb
		FROM
			batalha_log_acao a JOIN player b ON b.id=a.id_player
			JOIN player c ON c.id=a.id_playerb
		
		WHERE
			1=1' . $where . ' ORDER BY id DESC');
	
	$limit	= 20;
	$c		= 0;
?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<?php if(!$_POST && !$batalhas->num_rows): ?>
		<tr><td colspan="6"><?php echo t('log_batalha.lg9')?></td>
	<?php elseif($_POST && !$batalhas->num_rows): ?>
		<tr><td colspan="6"><?php echo t('log_batalha.lg10')?></td>
	<?php endif; ?>
	<?php if($batalhas->num_rows): ?>
		<?php foreach($batalhas->result_array() as $batalha): 
				$bg		= ++$c % 2 ? "class='cor_sim'" : "class='cor_nao'";
		?>
		<tr class="batalha-pagina batalha-pagina-<?php echo (int)($c/$limit) ?> <?php echo $bg ?>">
			<td width="150">
				<img src="<?php echo img ()?>layout/dojo/<?php echo $batalha['id_classe'] ?>.png" /><br />
				<b style="font-size:13px;" class="amarelo"><?php echo $batalha['nome_player'] ?></b>
			</td>
			<td width="40">vs</td>
			<td width="150"><img src="<?php echo img ()?>layout/dojo/<?php echo $batalha['id_classeb'] ?>.png" /><br /><b style="font-size:13px;" class="amarelo"><?php echo $batalha['nome_playerb'] ?></b></td>
			<td width="130"><?php echo date('d/m/Y H:i', strtotime($batalha['data_inicio'])) ?></td>
			<td width="130">
				<?php if(!$batalha['data_fim']): ?>
					<?php if(!$batalha['data_fim'] && $batalha['fuga']): ?>
						<?php echo t('log_batalha.lg11')?>
					<?php else: ?>
						<?php echo t('log_batalha.lg12')?>
					<?php endif; ?>
				<?php else: ?>
					<?php echo date('d/m/Y H:i', strtotime($batalha['data_fim'])) ?>
				<?php endif; ?>
			</td>
			<td width="70">
				<?php if($batalha['data_fim'] || (!$batalha['data_fim'] && $batalha['fuga'])): ?>
					<?php if(!(!$batalha['data_fim'] && $batalha['fuga'])): ?>
						<a class="button" href="javascript:detalheBatalha(<?php echo $batalha['id'] ?>)"><?php echo t('botoes.ver_log')?></a>			
					<?php endif; ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr height="4"></tr>
		<?php endforeach; ?>
	<?php endif; ?>
</table>
<div>
	<br />
	<div style="float:left"><?php echo t('log_batalha.lg13')?>: </div>
	<?php for($f = 0; $f < (int)($batalhas->num_rows/$limit); $f++): ?>
	<div style="float:left"><a class="linkTopo" href="javascript:doPagina(<?php echo $f+1; ?>)"><?php echo $f+1; ?></a> | </div>
	<?php endfor; ?>
</div>
<script type="text/javascript">
	doPagina(0);
</script>