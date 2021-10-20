<?php
	$items	= Recordset::query('SELECT * FROM coin_log WHERE id_player IN(SELECT id FROM player WHERE id_usuario=' . $_SESSION['usuario']['id'] . ') ORDER BY data_ins DESC');
	$c		=0;
?>
<div class="titulo-secao"><p><?php echo t('titulos.gasto_vip')?></p></div><br />
<script type="text/javascript"><!--
google_ad_client = "ca-pub-9048204353030493";
/* NG - Graduações */
google_ad_slot = "0023755321";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/><br/>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="250" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
          <td width="80" align="center"><b style="color:#FFFFFF">Personagem</b></td>
          <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.g72')?></b></td>
		  <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.g71')?></b></td>
          <td width="120" align="center"><b style="color:#FFFFFF"><?php echo t('geral.g73')?></b></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<?php foreach($items->result_array() as $item): ?>
	<?php
		$bg = ++$c % 2 ? "class='cor_sim'" : "class='cor_nao'";
    $it = [
      'id_tipo' => null,
    ];
		
		if($item['id_item']) {
			$q_item = Recordset::query('SELECT nome_' . Locale::get() . ' AS nome, id_tipo, req_graduacao FROM item WHERE id=' . $item['id_item']);

      if ($q_item->num_rows) {
        $it = $q_item->row_array();
      }
		}
	?>
	<tr <?php echo $bg ?>>
		<td width="250" align="center" >
			<strong  style="font-size:13px" class="amarelo"><?php echo $item['id_item'] ? $it['nome'] : 'Não especificado' ?></strong>
		</td>
		<td width="80" align="center" height="35">
			<?php if($item['id_player']): ?>
				<?php
					$player	= Recordset::query('SELECT nome FROM player WHERE id=' . $item['id_player']);
				?>
				<?php if($player->num_rows): ?>
					<?php echo $player->row()->nome ?>
				<?php else: ?>
					Personagem removido ou banido
				<?php endif ?>
			<?php endif ?>
		</td>
		<td width="140" align="center"><?php echo date('d/m/Y H:i:s', strtotime($item['data_ins'])) ?></td>
		<td width="140" align="center">
			<?php if($item['id_item'] && in_array($it['id_tipo'], array(18, 19)) && $it['req_graduacao']): ?>
				<?php echo date('d/m/Y H:i:s', strtotime('+150 day', strtotime($item['data_ins']))) ?>
			<?php else: ?>
				--
			<?php endif ?>
		</td>
		<td width="120" align="center" <?php echo $bg ?>><?php echo $item['coin'] ?></td>
	</tr>
	<tr height="4"></tr>
	<?php endforeach; ?>
</table>
