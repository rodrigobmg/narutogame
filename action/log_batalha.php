<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo t('actions.a186')?> - Naruto Game</title>
<link href="css/html.css" rel="stylesheet" type="text/css"/>
<link href="css/layout.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="http://narutogame.com.br/js/jquery.js"></script>
<script type="text/javascript" src="http://narutogame.com.br/js/tools.tooltip-1.0.2.js"></script>
</head>
<body>
<?php
	if(!is_numeric($_GET['id'])) {
		redirect_to('negado', NULL, array('e' => 1));
	}

	$q = new Recordset('
		SELECT
			a.*,
			a.log,
			b.nome AS nome_player,
			c.nome AS nome_playerb,
			b.id,
			c.id AS idb
		FROM
			batalha_log_acao a JOIN player b ON b.id=a.id_player
			JOIN player c ON c.id=a.id_playerb
		
		WHERE
			a.id=' . (int)$_GET['id'] . '	
	');
	
	if(!$q->num_rows) {
		redirect_to('negado', NULL, array('e' => 2));	
	}
	
	$batalha	= $q->row_array();
	$show_count	= false;
	
	if(($batalha['id_player'] != $basePlayer->id && $batalha['id_playerb'] != $basePlayer->id) && !$basePlayer->hasItem(array(20313, 20314, 20315))) {
		redirect_to('negado', NULL, array('e' => 3));	
	} else {
		if($batalha['id_player'] != $basePlayer->id && $batalha['id_playerb'] != $basePlayer->id) {
			$show_count = true;
		
			$i 		= $basePlayer->getVIPItem(array(20313, 20314, 20315));
			$diff	= $i['vezes'] - $i['uso'];
	
			if($i['uso'] > $i['vezes']) {
				die(t('actions.187'));
			} else {
				$basePlayer->useVIPItem($i);
			}		
		}
	}
?>

<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="210" id="cnPBuff" style="padding-bottom:10px"></td>
    <td width="310"></td>
    <td width="210" id="cnEBuff" style="padding-bottom:10px"></td>
  </tr>
  <tr>
  	<td height="30" colspan="3" align="center" style="background:#151515; font-size:16px; color:#CCC">
		<?php if($show_count): ?>
			<?php if(!($i['vezes'] - $i['uso'])): ?>
			<?php echo t('actions.a188')?>
			<?php else: ?>
			<?php echo t('actions.a189')?> <?php echo ($i['vezes'] - $i['uso']) ?> <?php echo t('actions.a190')?>
			<?php endif; ?>
		<?php endif; ?>
	</td>
  	</tr>
  <tr>
  	<td height="10" colspan="3" align="center"  >&nbsp;</td>
  	</tr>
  <tr>
  	<td height="30" colspan="3" align="center"  style="background:#151515; font-size:16px; color:#CCC">
		<?php if($batalha['empate']): ?>
		<?php echo t('actions.a191')?>
		<?php elseif($batalha['fuga'] && $batalha['vencedor'] == $batalha['id_player']): ?>
		<?php echo t('actions.a129')?> <?php echo $batalha['nome_playerb'] ?> <?php echo t('actions.a192')?>
		<?php elseif($batalha['fuga'] && $batalha['vencedor'] == $batalha['id_playerb']): ?>
		<?php echo t('actions.a129')?> <?php echo $batalha['nome_player'] ?> <?php echo t('actions.a192')?>
		<?php elseif($batalha['vencedor'] == $batalha['id_player']): ?>
		<?php echo t('actions.a129')?> <?php echo $batalha['nome_player'] ?> <?php echo t('actions.a193')?>
		<?php elseif($batalha['vencedor'] == $batalha['id_playerb']): ?>
		<?php echo t('actions.a129')?> <?php echo $batalha['nome_playerb'] ?> <?php echo t('actions.a193')?>		
		<?php endif; ?>
	</td>
  	</tr>
  <tr>
  	<td height="10" colspan="3" align="center" id="imgPP3" style="position:relative">&nbsp;</td>
  	</tr>
  <tr>
    <td width="210" height="246" align="center" id="imgPP" style="position:relative">
		<div id="imgDP"><?php echo player_imagem_ultimate($batalha['id']) ?></div>
	</td>
    <td width="310" align="center" height="200">
      <img src="<?php echo img() ?>layout/combate/vs.png" /></td>
    <td width="210" height="246" align="center" id="imgPE" style="position:relative">
		<div id="imgDE"><?php echo player_imagem_ultimate($batalha['idb']) ?></div>
	</td>
  </tr>
  <tr>
  	<td align="center" valign="top"><span style="color:#FFFFFF; font-size:13px; font-weight:bold;">
  		<?php echo $batalha['nome_player'] ?>
  		</span></td>
  	<td align="center">
  		<table style="#background-color: #3f3f3f">
  			<tr>
  				<td>
  					<?php echo $batalha['log'] ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="background:#151515; font-size:16px; color:#CCC" align="center">
					<?php if($batalha['empate']): ?>
					<?php echo t('actions.a191')?>
					<?php elseif($batalha['fuga'] && $batalha['vencedor'] == $batalha['id_player']): ?>
					<?php echo t('actions.a129')?> <?php echo $batalha['nome_playerb'] ?> <?php echo t('actions.a192')?>
					<?php elseif($batalha['fuga'] && $batalha['vencedor'] == $batalha['id_playerb']): ?>
					<?php echo t('actions.a129')?> <?php echo $batalha['nome_player'] ?> <?php echo t('actions.a192')?>
					<?php elseif($batalha['vencedor'] == $batalha['id_player']): ?>
					<?php echo t('actions.a129')?> <?php echo $batalha['nome_player'] ?> <?php echo t('actions.a193')?>
					<?php elseif($batalha['vencedor'] == $batalha['id_playerb']): ?>
					<?php echo t('actions.a129')?> <?php echo $batalha['nome_playerb'] ?> <?php echo t('actions.a193')?>				
					<?php endif; ?>
				</td>
			</tr>
  		</table>
  		
  		
  		</td>
  	<td align="center" valign="top"><span style="color:#FFFFFF; font-size:13px; font-weight:bold;">
  		<?php echo $batalha['nome_playerb']	?>
  		</span></td>
  	</tr>
</table>

<script>$(".trigger").tooltip({effect: 'slideleft', position: ['center', 'right']});</script>

</body>
</html>