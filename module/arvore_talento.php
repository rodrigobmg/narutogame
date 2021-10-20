<?php
	$ignored_ats	= [
		'req_level'	=> 1,
		'req_con'	=> 1,
		'req_tai'	=> 1,
		'req_agi'	=> 1,
		'req_for'	=> 1,
		'req_int'	=> 1
	];
?>
<script type="text/javascript">
	function doArvoreTalentoAprender(i) {
		$("#f-arvore-talento-aprender-h-id").val(i);
		
		$("#f-arvore-talento-aprender").submit();
	}
</script>
<?php if($_SESSION['usuario']['msg_vip']){?>
	<script type="text/javascript">
		head.ready(function () {
		$(document).ready(function() {
		if(!$.cookie("arvore_ninja")){
		$("#dialog").dialog({ 
			width: 540,
			height: 460, 
			title: '<?php echo t('arvore_talento.at14')?>', 
			modal: true,
			close: function(){
				$.cookie("arvore_ninja", "foo", { expires: 1 });
			}
		
			});
		}
		});
		});
	</script>
<?php }?>
<?php if(!$basePlayer->tutorial()->talentos){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 7,
	 
	  steps: [
	  {
		element: ".msg_gai",
		title: "<?php echo t("tutorial.titulos.talentos.1");?>",
		content: "<?php echo t("tutorial.mensagens.talentos.1");?>",
		placement: "bottom"
	  }
	]});
	//Renicia o Tour
	tour.restart();
	// Initialize the tour
	tour.init(true);
	// Start the tour
	tour.start(true);
});
</script>	
<?php } ?>
<form action="?acao=arvore_talento_aprender" id="f-arvore-talento-aprender" method="post">
	<input type="hidden" name="id" value="" id="f-arvore-talento-aprender-h-id" />
</form>
<div class="titulo-secao"><p><?php echo t('arvore_talento.at1')?></p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "4032368978";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Talentos -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/><br/>
<?php 
	$pointsLeft = absm(absm($basePlayer->getAttribute('level') - 4) - $basePlayer->getAttribute('arvore_gasto'));
	
	if($basePlayer->level >= 65) {
		$pointsLeft = abs(60 - $basePlayer->getAttribute('arvore_gasto'));
	}
?>
<?php if(!$pointsLeft): ?>
    <div class="msg_gai">
    <div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
				<b><?php echo t('arvore_talento.at2')?></b>
				<p>
				<?php echo t('arvore_talento.at3')?><br />
				<?php barra_exp3($basePlayer->getAttribute('arvore_gasto'), 60, 327, "".$basePlayer->getAttribute('arvore_gasto')." / 60 ". t('arvore_talento.at4') ."", "#2C531D", "#537F3D", 6) ?>
				</p>
           </div>
	</div>	  
    </div>	
<?php else: ?>
    <div class="msg_gai">
    <div class="msg">
           <div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/2.png); background-repeat: no-repeat;">
		    <b><?php echo t('arvore_talento.at5')?></b>
            <p>
			<?php echo t('arvore_talento.at6')?> <strong class="verde"><?php echo $pointsLeft ?> <?php echo t('arvore_talento.at7')?></strong> <?php echo t('arvore_talento.at8')?>
			<?php barra_exp3($basePlayer->getAttribute('arvore_gasto'), 60, 327, "".$basePlayer->getAttribute('arvore_gasto')." / 60 ". t('arvore_talento.at4') ."", "#2C531D", "#537F3D", 6) ?>
			</p>
          </div>
	</div>	  
    </div>		
<?php endif ?>    
<div id="dialog" style="display:none">
	<div style="background:url(<?php echo img()?>layout/popup/Talentos.png); background-repeat:no-repeat; width:495px !important; height: 417px !important;">
		<div style="position:relative; width:280px; top:120px; padding-left: 18px;">
			
			<b><a href="index.php?secao=vantagens" class="linksSite3" style="font-size:16px"><?php echo t('arvore_talento.at9')?></a></b><br /><br />
			<ul style="margin:0; padding:0;">
				<li style="margin-bottom:5px">
					<b><a href="index.php?secao=vantagens" class="linksSite3"><?php echo t('arvore_talento.at10')?></a></b><br />
					<?php echo t('arvore_talento.at11')?>
				</li>
				<li style="margin-bottom:5px">
					<b><a href="index.php?secao=vantagens" class="linksSite3"><?php echo t('arvore_talento.at12')?></a></b><br />
					<?php echo t('arvore_talento.at13')?>
				</li>
			</ul>
		</div>
	</div>
</div>

<table cellpadding="0" border="0" cellspacing="0" style="width: 730px !important;" id="box" background="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/bg_arvore.jpg" height="1060">
<tr>
<td>
<table border="0" cellpadding="0" cellspacing="0" id="t-character-attribute" style="width: 730px !important;" height="1050">
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

					if($basePlayer->hasItem($currentItem['id'])) {
						$learnFunction = "doCharacterTalentTreeLearnLevel";
						
						while(true) {
							$nextItem = Item::getTreeNextItemFor($currentItem['id'], $treeLevel);
							
							$currentItemLevel++;
							
							if(!$nextItem->num_rows) {
								break;
							} else {
								$currentItem = $nextItem->row_array();
								$isNextItem = true;
								
								if(!$basePlayer->hasItem($currentItem['id'])) {
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
					<?php if($basePlayer->hasItem($currentItem['id'])): ?>
                        <img id="f-character-talent-tree-i-item-<?php echo $currentItem['id'] ?>" src="<?php echo img('layout/') . $currentItem['imagem'] ?>" />
                        <?php echo item_tooltip("f-character-talent-tree-i-item-" . $currentItem['id'], $currentItem['id']) ?>
                    <?php else: ?>
                        <?php 
							$clickFunction = Item::hasRequirement($currentItem['id'], $basePlayer, NULL, $ignored_ats) ? "doArvoreTalentoAprender(" . $currentItem['id']. ")" : "";
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
