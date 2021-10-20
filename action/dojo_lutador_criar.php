<?php
	$_SESSION['dojoEnemy']	= NULL;
	$total					= 10 + $basePlayer->bonus_vila['dojo_limite_npc_dojo'];
	/* Verificação se existe uma luta ativa */
	
	$batalhas = Recordset::query("SELECT COUNT(id) AS total FROM player_batalhas_npc WHERE id_player=" . $basePlayer->id)->row_array();
	
	if($batalhas['total'] >= $total) {
		die("<center><strong>".sprintf(t('actions.a108'), $total)."</strong></center>");
	}
	
	$baseEnemy = new NPC(NULL, $basePlayer);
	$baseEnemy->atCalc();

	if(!$_SESSION['dojoEnemy']) {
		$_SESSION['dojoEnemy'] = gzserialize($baseEnemy);
	} else {
		$baseEnemy = gzunserialize($_SESSION['dojoEnemy']);
	}
	
	// Limites de 50% --->
		if($basePlayer->getAttribute('hp') < ($basePlayer->getAttribute('max_hp') / 2)) {
			die("<center><strong>".t('actions.a109')."</strong></center>");
		}
	
		if($basePlayer->getAttribute('sp') < ($basePlayer->getAttribute('max_sp') / 2)) {
			die("<center><strong>".t('actions.a109')."</strong></center>");
		}
	
		if($basePlayer->getAttribute('sta') < ($basePlayer->getAttribute('max_sta') / 2)) {
			die("<center><strong>".t('actions.a109')."</strong></center>");
		}
	// <---
?>
<?php /*
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="3">
  <tr>
    <td width="157"><img src="<?php echo img('dojo/' . $baseEnemy->getAttribute('id_classe') . '.jpg'); ?>" /></td>
    <td width="650" style="color:#FFFFFF">
    <b><?php echo $baseEnemy->getAttribute('nome') ?></b> está pronto para lutar com você.</td>
    <td width="350"><input type="button" value="Aceitar &gt;" onclick="doLutarLutador()" /></td>
  </tr>
</table>

*/?>

<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>

		<td>
			<?php echo player_imagem_ultimate($basePlayer) ?>
			<div class="character-info">
				<div class="name" style="font-weight: bold; font-size:14px"><?php echo $basePlayer->nome ?></div>
				<div class="headline"><?php echo $basePlayer->nome_titulo ?></div>
			</div>
		</td>
		<td width="255" rowspan="2" align="center" valign="top"><img src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/combate/vs.png'); ?>"/></td>
		<td>
			<img src="<?php echo img( $baseEnemy->getAttribute('imagem')); ?>"/>
			<div class="character-info">
				<div class="name" style="font-weight: bold; font-size:14px"><?php echo $baseEnemy->getAttribute('nome') ?></div>
				<div class="headline"></div>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="3"><br /><input type="button" class="button" value="<?php echo t('botoes.aceitar')?>" onclick="doLutarLutador()" /></td>
	</tr>
</table>
<?php if ($_SESSION['universal']): ?>
<?php echo $baseEnemy->getAttribute('hp') ?><br />
<?php echo $baseEnemy->getAttribute('sp') ?><br />
<?php echo $baseEnemy->getAttribute('sta') ?><br />	
<?php endif ?>

<?php /*
<pre style="text-align: left">
Serialize <?php echo strlen(serialize($baseEnemy)) / 1024 ?><br />
GZ-Serialize <?php echo strlen(gzserialize($baseEnemy)) / 1024 ?><br />
<?php echo $baseEnemy->getAttribute('hp') ?><br />
<?php echo $baseEnemy->getAttribute('sp') ?><br />
<?php echo $baseEnemy->getAttribute('sta') ?><br />
[<?php echo $baseEnemy->getAttribute('id_classe_tipo') ?> --> ID: <?php echo $baseEnemy->id ?>] -> ITEMS:
<?php foreach($baseEnemy->items_id as $k => $v): ?>
<?php $i = Recordset::query('SELECT nome FROM item WHERE id=' . $k)->row_array() ?>
<?php echo $i['nome'] ?><br />
<?php endforeach ?>
</pre>
*/ ?>