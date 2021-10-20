<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo t('templates.t59')?> - Naruto Game</title>
<link href="css/html<?php echo LAYOUT_TEMPLATE?>.css" rel="stylesheet" type="text/css"/>
<link href="css/layout<?php echo LAYOUT_TEMPLATE?>.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/global<?php echo LAYOUT_TEMPLATE?>.js"></script>
</head>
<body>
<div class="titulo-secao"><p><?php echo t('arvore_talento.at1')?></p></div>
<?php
	$basePlayer = new Player($basePlayer->id);

	$_GET['id'] = decode($_GET['id']);

	$redir_script = true;

	if(!is_numeric($_GET['id'])) {
		redirect_to("negado");
	}
	
	if(!$basePlayer->hasItem(array(2019, 2020, 2021))) {
		echo "<div align='center'>".t('templates.t60')."</div>";
	} else {
        $i = $basePlayer->getVIPItem(array(2019, 2020, 2021));
    
        if($i['uso'] >= $i['vezes']) {
			echo "<div align='center'>".t('templates.t61')."</div>";
		} else {
			$basePlayer->useVIPItem($i);
			
			$curPlayer	= new Player((int)$_GET['id']);
			$anti		= anti_espionagem($curPlayer->id);

?>
<div align="center"><?php echo t('templates.t62')?> <?php echo $i['vezes'] - $i['uso'] ?> <?php echo t('templates.t63')?>.</div>
<?php if(!$anti): ?>
	<div align="center"><?php echo t('templates.t64')?>: <?php echo $curPlayer->arvore_gasto ?></div>
	<table cellpadding="0" border="0" cellspacing="0" id="box" background="<?php echo img()?>layout/bg_arvore.jpg" height="1060">
	<tr>
	<td>
	<table border="0" cellpadding="0" cellspacing="0" id="t-character-attribute" width="730" height="1050">
		<?php
			$treeLevel = 0;
			$maxHorizontal = Item::getTreeMaxSort();
		?>
		<?php while(true): ?>
			<?php
				$qTree = Item::getTreeByLevel(++$treeLevel);
				
				if(!$qTree->num_rows) break;
				
				$rTree = $qTree->result_array();
			?>
			<tr height="48" align="center" valign="middle">
			<?php for($f = 1; $f <= $maxHorizontal; $f++): ?>
				<?php
					$currentItem = NULL;
					
					foreach($rTree as $treeItem) {
						if($treeItem['arvore_ordem'] == $f) {
							$currentItem = $treeItem;
							
							break;	
						}
					}			
				?>
				<?php if($currentItem): ?>
					<?php	
						// Loops until the lastest level from item available to train
						// is in screen, but only if o have the first level
						$currentItemLevel = 0;
						$currentItemMaxLevel = 1;
						$isNextItem = false;
	
						$currentItemTemp = $currentItem;
	
						if($curPlayer->hasItem($currentItem['id'])) {
							$learnFunction = "doCharacterTalentTreeLearnLevel";
							
							while(true) {
								$nextItem = Item::getTreeNextItemFor($currentItem['id'], $treeLevel);
								
								$currentItemLevel++;
								
								if(!$nextItem->num_rows) {
									break;
								} else {
									$currentItem = $nextItem->row_array();
									$isNextItem = true;
									
									if(!$curPlayer->hasItem($currentItem['id'])) {
										break;
									}
								}
							}
						} else {
							$learnFunction = "doCharacterTalentTreeLearn";
						}
						
						while(true) {
							$nextItem = Item::getTreeNextItemFor($currentItemTemp['id'], $treeLevel);
	
							if(!$nextItem->num_rows) {
								break;
							} else {
								$currentItemTemp = $nextItem->row_array();
								
								$currentItemMaxLevel++;	
							}
						}				
					?>
					<td width="48" height="48" align="center" valign="middle">
					<div class="d-itembox">
						<div class="d-itembox-total">
							<?php echo $currentItemLevel ?> / <?php echo $currentItemMaxLevel ?>
						</div>
						<?php if($curPlayer->hasItem($currentItem['id'])): ?>
							<img id="f-character-talent-tree-i-item-<?php echo $currentItem['id'] ?>" src="<?php echo img('layout/') . $currentItem['imagem'] ?>" />
							<?php echo item_tooltip("f-character-talent-tree-i-item-" . $currentItem['id'], $currentItem['id']) ?>
						<?php else: ?>
							<?php 
								$clickFunction = Item::hasRequirement($currentItem['id'], $curPlayer, NULL, array('req_level'=>1)) ? "doArvoreTalentoAprender(" . $currentItem['id']. ")" : "";
								$transparent = $currentItemLevel > 0 ? "" : "$(this).animate({opacity: .4})"; 
							?>
							
							<img style="cursor: pointer" id="f-character-talent-tree-i-item-<?php echo $currentItem['id'] ?>" src="<?php echo img('layout/') . $currentItem['imagem'] ?>" onclick="<?php echo $clickFunction ?>" onload="<?php echo $transparent ?>" />
							<?php echo item_tooltip("f-character-talent-tree-i-item-" . $currentItem['id'], $currentItem['id'], true) ?>
						<?php endif; ?>
					</div>
					
					</td>
				<?php else: ?>
					<td width="48" height="48"></td>
				<?php endif; ?>
			<?php endfor; ?>
			</tr>
		<?php endwhile; ?>
	</table>
	</td>
	</tr>
	</table>
<?php else: ?>
	<div align="center"><?php echo t('actions.a249') ?></div>
<?php endif ?>
<?php
		}
	}
?>
<br /><br />
<table width="730" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
  <td>
  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- NG - Profile -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9166007311868806"
     data-ad-slot="9322022979"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
  </td>
</tr>
<tr>
      <td align="center">
      <br />
      <span style="padding-right: 20px">
        <input type="button" onclick="opener.location.href='?secao=mensagens&amp;msg=<?php echo addslashes($curPlayer->nome) ?>'; window.close()" value="<?php echo t('botoes.enviar_mensagem')?>"/>
        <input type="button" onclick="window.close()" src="<?php echo img(); ?>bt_cancelar.gif" value="<?php echo t('botoes.cancelar')?>" border="0" />
      </span></td>
    </tr>
</table>
</body>
</html>