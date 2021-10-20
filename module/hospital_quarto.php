<?php if($_SESSION['usuario']['msg_vip']){?>
<script type="text/javascript">
	head.ready(function () {
	$(document).ready(function() {
	if(!$.cookie("hospital")){
	$("#dialog").dialog({ 
		width: 540,
		height: 470, 
		title: '<?php echo t('hospital_quarto.h1')?>', 
		modal: true,
		close: function(){
			$.cookie("hospital", "foo", { expires: 1 });
		}
	
		});
	}
	});
	});
</script>
<?php }?>
<div id="dialog" style="display:none">
	<div style="background:url(<?php echo img()?>layout/popup/Hospital.png); background-repeat:no-repeat; width:495px !important; height: 417px !important;">
		<div style="position:relative; width:280px; top:120px; margin-left: 160px;">
			
			<b><a href="index.php?secao=vantagens" class="linksSite3" style="font-size:16px"><?php echo t('hospital_quarto.h2')?></a></b><br /><br />
			<ul style="margin:0; padding:0;">
				<li style="margin-bottom:5px">
					<b><a href="index.php?secao=vantagens" class="linksSite3"><?php echo t('hospital_quarto.h3')?></a></b><br />
					<?php echo t('hospital_quarto.h4')?>
				</li><br />
				<li style="margin-bottom:5px">
					<b><a href="index.php?secao=vantagens" class="linksSite3"><?php echo t('hospital_quarto.h5')?></a></b><br />
					<?php echo t('hospital_quarto.h6')?>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="titulo-secao"><p><?php echo t('hospital_quarto.h7')?></p></div>
<br />
<div id="HOTWordsTxt" name="HOTWordsTxt">
  <div class="msg_gai">
    <div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
		<b><?php echo t('hospital_quarto.h8')?></b>
      <p>
	  <div style="position: relative; width: 460px">
        <?php if($basePlayer->hasItem(array(1007, 1008, 1009))): ?>
        	<?php $i	= $basePlayer->getVIPItem(array(1007, 1008, 1009)); ?>
			<?php if($i['uso'] < $i['vezes']): ?>
				<?php $vip = true; ?>
				<div style="width:150px; float:left; text-align: center">
					<span class="cinza" style="font-size:11px"><?php echo t('hospital_quarto.h9')?></span><br /><br />
					<input class="button" type="button" id="bHospitalQuartoCura" value="<?php echo t('botoes.usar_cartao')?>" onclick="doHospitalQuartoCura(1);" />
				</div>
			<?php endif ?>
		<?php endif ?>
		<?php if($i	= $basePlayer->getItem(22733)): ?>
				<div style="width:150px; float:left; text-align: center">
					<span class="cinza" style="font-size:11px"><?php echo t('hospital_quarto.h14')?><br /><br />
					<input class="button" type="button" id="bHospitalQuartoCura" value="<?php echo t('botoes.usar_cartao2')?>" onclick="doHospitalQuartoCura(2);" />
				</div>
		<?php endif; ?>
        <div style="width:150px; float:left; text-align: center">
		<span class="cinza" style="font-size:11px"><?php echo t('hospital_quarto.h11')?> <?php echo t('hospital_quarto.h12')?>
			<?php 
			
				/*switch($basePlayer->id_graduacao){
					
					case 1:
						$healValue = 35;
						break;
					case 2:
						$healValue = 35;
						break;
					case 3:
						$healValue = 105;
						break;
					case 4:
						$healValue = 175;
						break;
					case 5:
						$healValue = 245;
						break;
					case 6:
						$healValue = 315;
						break;
					case 7:
						$healValue = 385;
						break;
					
					
				}*/

        $healValue	= 20 * $basePlayer->level;
				$healValue	-= percent($basePlayer->bonus_vila['hospital_preco'], $healValue);
				
				echo $healValue;
			?>
        <?php echo t('hospital_quarto.h13')?></span><br /><br />
        <input class="button" type="button" id="bHospitalQuartoCura" value="<?php echo sprintf(t('botoes.aceito_pagar'), $healValue)?>" onclick="doHospitalQuartoCura();" />
		  </div>
        </li>
      </div>
	  <div class="verde" style="position: relative;  margin-top: 10px; float: left;"><?php echo t('hospital_quarto.h10')?></div>
	  <div class="break"></div>
     </div>
	</p>
	</div>
  </div>
</div>
