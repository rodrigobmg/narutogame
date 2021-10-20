<?php if(!$basePlayer->tutorial()->treinamento){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 15,
	 
	  steps: [
	  {
		element: ".tutorial-treinamento",
		title: "<?php echo t("tutorial.titulos.treinamento.1");?>",
		content: "<?php echo t("tutorial.mensagens.treinamento.1");?>",
		placement: "top"
	  },{
		element: "#distribute-container",
		title: "<?php echo t("tutorial.titulos.treinamento.2");?>",
		content: "<?php echo t("tutorial.mensagens.treinamento.2");?>",
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
<?php
	Player::moveLocal($basePlayer->id, 2, $basePlayer->id_vila_atual);

	$pWidth2 	= "715";
	$dist_key	= $_SESSION['dist_key'] = uniqid(uniqid(), true);
	$max_treino	= $basePlayer->getAttribute('max_treino');
	$fall_timer	= get_fall_time($basePlayer->getAttribute('id_vila'), 2);
?>
<?php if($_SESSION['usuario']['msg_vip']){?>
<script type="text/javascript">
	 head.ready(function () {
		$(document).ready(function() {
		if(!$.cookie("academia_treino")){
			$("#dialog").dialog({ 
			width: 600,
			height: 440, 
			title: '<?php echo t('academia_treinamento.at1')?>', 
			modal: true,
			close: function(){
				$.cookie("academia_treino", "foo", { expires: 1 });
			}

			});
		}
		});
	});
</script>
<?php }?>
<script type="text/javascript">
	$(document).ready(function () {
		var	distribute_container	= $('#distribute-container');

		function _distribute_points(data) {
			$.ajax({
				url:		'?acao=academia_treinamento_dist&_cache=' + ((Math.random() * 65535).toString() + (Math.random() * 65535).toString()),
				type:		data ? 'post' : 'get',
				data:		data,
				success:	function (result) {
					distribute_container.html(result);
				}
			});
		}

		distribute_container.on('click', '.distribute', function () {
			var	_	= $(this);
			var	qty	= parseInt($('[name=' + _.data('attribute') + '_val]', distribute_container).val());

			if(qty) {
				_distribute_points({
					attribute:	_.data('attribute'),
					quantity:	qty,
					key:		'<?php echo $dist_key ?>'
				});				
			}
		});

		distribute_container.on('click', '.redistribute', function () {
			var	_	= $(this);
			var	qty	= parseInt($('[name=' + _.data('attribute') + '_val_redist]', distribute_container).val());

			if(qty) {
				_distribute_points({
					attribute:	_.data('attribute'),
					quantity:	qty,
					redist:		1,
					key:		'<?php echo $dist_key ?>'
				});				
			}
		});

		_distribute_points();
	});
</script>
<div id="dialog" style="display:none">
	<div style="background:url(<?php echo img()?>layout/popup/Treino.png); background-repeat:no-repeat; width:550px !important; height: 386px !important;">
		<div style="position:relative; width:280px; top:110px; margin-left: 250px;">
			
			<b><a href="index.php?secao=vantagens" class="linksSite3" style="font-size:16px"><?php echo t('academia_treinamento.at2')?></a></b><br /><br />
			<ul style="margin:0; padding:0;">
				<li style="margin-bottom:5px">
					<b><a href="index.php?secao=vantagens" class="linksSite3"><?php echo t('academia_treinamento.at3')?></a></b><br />
					<?php echo t('academia_treinamento.at4')?>
				</li>
				<li style="margin-bottom:5px">
					<b><a href="index.php?secao=vantagens" class="linksSite3"><?php echo t('academia_treinamento.at5')?></a></b><br />
					<?php echo t('academia_treinamento.at6')?>
				</li>
				<li>
					<b><a href="index.php?secao=vantagens" class="linksSite3"><?php echo t('academia_treinamento.at7')?></a></b><br />
					<?php echo t('academia_treinamento.at8')?>
				</li>
			</ul>
		</div>
	</div>
</div>
<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p><?php echo t('academia_treinamento.at11')?></p></div>
  <?php msg(1,''. t('academia_treinamento.at9').'',''. t('academia_treinamento.at10').'') ?>
	<script type="text/javascript">
		google_ad_client = "ca-pub-9166007311868806";
		google_ad_slot = "5090299777";
		google_ad_width = 728;
		google_ad_height = 90;
	</script>
	<!-- NG - Treinamento Atributos -->
	<script type="text/javascript"
	src="//pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
<br/><br/>
<?php if(hasFall($basePlayer->getAttribute('id_vila'), 2) && $basePlayer->getAttribute('level') >= 15): ?>
	  <?php msg(3,''. t('academia_treinamento.at12').'',''. t('academia_treinamento.at13').'') ?>		
<br />
<?php endif; ?>
<div id="cnBase" class="direita">
        <form action="index.php?acao=academia_treinamento_treinar" method="post">
<?php
	if(isset($_GET['p']) && $_GET['p'] && isset($_GET['e']) && isset($_GET['t'])) {
		msg(4,''. t('academia_treinamento.at14').'',''. t('academia_treinamento.at17').' <span class="laranja" style="font-size: 13px; font-weight: bold">'.$_GET['sc'].'</span> <img src="'. img() . 'layout/icones/p_chakra.png" alt="'. t('academia_treinamento.at18').'"/> <span class="laranja"  style="font-size: 13px; font-weight: bold">'.$_GET['tc'].'</span> <img src="'. img() .'layout/icones/p_stamina.png" alt="'. t('academia_treinamento.at19').'"/><br /><br />'. t('academia_treinamento.at20').' <span class="verde">'.$_GET['e'].'</span> '. t('academia_treinamento.at21').' <br /> '. t('academia_treinamento.at20').' <span class="verde">'. $_GET['p'] .'</span>  '. t('academia_treinamento.at22').'');
	}
	
	if(isset($_GET['f']) && $_GET['f']) {
		msg(5,''. t('academia_treinamento.at15').'',''. t('academia_treinamento.at23').' ' . ($_GET['f'] == 1 ? "Chakra" : "Stamina") . ' '. t('academia_treinamento.at24').'');
	}
	
	if(isset($_GET['c']) && $_GET['c']) {
		msg(6,''. t('academia_treinamento.at15').'',''. t('academia_treinamento.at16').'');
	}
	
	if(isset($_SESSION['_TREINO_CAPTCHA']) && $_SESSION['_TREINO_CAPTCHA'] >= 10) {
		msg(1,'',sprintf( t('academia_treinamento.at26'), captcha_text_gen("captcha_treino")) .'<input name="captcha" type="text" id="captcha" size="5" maxlength="4" style="position:absolute; margin-top:1px; margin-left:2px;"');
	}
?>
         <br />
<table id="infoT" width="730" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="subtitulo-home" height="49">
            <table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
                <tr>
                    <td width="200" align="center">
                    	<?php echo t('academia_treinamento.at27')?>
                    </td>
                    <td width="120" align="center">
                   		 <?php echo t('academia_treinamento.at28')?>
                    </td>
                    <td width="90" align="center">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<script type="text/javascript">
<?php
	if(in_array($basePlayer->getAttribute('id_classe_tipo'), array(1,4))) {
		$sp_consume		=  (2 + round($basePlayer->level / 2));
		$sta_consume	= (6 + $basePlayer->level);
	} elseif (in_array($basePlayer->getAttribute('id_classe_tipo'), array(2, 3))) {
		$sp_consume		= (6 + $basePlayer->level);
		$sta_consume	=  (2 + round($basePlayer->level / 2));
	}

	//$sp_consume		-= percent($basePlayer->bonus_profissao['custo_treino'], $sp_consume);
	//$sta_consume	-= percent($basePlayer->bonus_profissao['custo_treino'], $sta_consume);

	$sp_consume		= round($sp_consume);
	$sta_consume	= round($sta_consume);

?>

	function tManCalc() {
		var v = parseInt($("#qtd").val());
		
		var sp = <?php echo $sp_consume ?> * v;
		var sta = <?php echo $sta_consume ?> * v;
		
		
		
		var sp = sp - (sp * <?php echo $basePlayer->bonus_profissao['custo_treino']?>) / 100;
		var sta = sta - (sta * <?php echo $basePlayer->bonus_profissao['custo_treino']?>) / 100;
		
		
		$("#cnTSP").html(Math.round(sp));
		$("#cnTSTA").html(Math.round(sta));
	}
</script>
      <table width="730" border="0" cellpadding="0" cellspacing="0">
     
            <tr >
              <td height="34" width="200" align="center">
              		<?php echo t('academia_treinamento.at29')?>
					<br />
                    <?php echo t('academia_treinamento.at30')?> <span id="cnTSP"><?php echo $basePlayer->bonus_profissao['custo_treino'] ? $sp_consume - (percent($basePlayer->bonus_profissao['custo_treino'], $sp_consume))  : $sp_consume ?></span><img src="<?php echo img() ?>layout/icones/p_chakra.png" alt="<?php echo t('academia_treinamento.at18')?>" /><br />
                    <?php echo t('academia_treinamento.at30')?> <span id="cnTSTA"><?php echo$basePlayer->bonus_profissao['custo_treino'] ? $sta_consume - (percent($basePlayer->bonus_profissao['custo_treino'], $sta_consume)) : $sta_consume ?></span><img src="<?php echo img() ?>layout/icones/p_stamina.png" alt="<?php echo t('academia_treinamento.at19')?>" /><br />
              </td>
              <td width="120" height="34" align="center" ><select name="qtd" id="qtd" onchange="tManCalc()">
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4</option>
                  <option>5</option>
                  <option>6</option>
                  <option>7</option>
                  <option>8</option>
                  <option>9</option>
                  <option>10</option>
                  <option>11</option>
                  <option>12</option>
                  <option>13</option>
                  <option>14</option>
                  <option>15</option>
                  <option>20</option>
                  <option>25</option>
                  <option>30</option>
                 
                </select></td>
              <td width="90" height="34" align="center" ><?php if($basePlayer->treino_dia < $max_treino): ?>
				<a class="button" data-trigger-form="1"><?php echo t('botoes.treinar') ?></a>
                <?php else: ?>
				<a class="button ui-state-disabled"><?php echo t('botoes.treinar') ?></a>
                <?php endif; ?>
              </td>
            </tr>
          </table>
        </form>
        <br />
        <br />
         <?php if($basePlayer->hasItem(array(1028, 1081, 1082))): ?>
		<form action="?acao=academia_treinamento_auto" method="post"> 
        <table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="subtitulo-home" height="49">
                
                <table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
                    <tr>
                      <td width="200" align="center">
                      		<?php echo t('academia_treinamento.at5') ?>
                      </td>
    
                      <td width="120" align="center">
                    		<?php echo t('academia_treinamento.at28') ?>
                      </td>
                      <td width="90" align="center">
                    		
                      </td>
                    </tr>
                </table>
                
            </td>
          </tr>
        </table>       
        <table width="730" border="0" cellpadding="0" cellspacing="0">
            <tr >
              <td width="200" height="34"><?php echo t('academia_treinamento.at31') ?></td>
              <td width="120" height="34" align="center" ><select name="id">
                <?php if($basePlayer->hasItem(array(1028, 1081, 1082))): ?>
                <option value="<?= encode(1028) ?>"><?php echo t('academia_treinamento.at32') ?></option>
                <?php endif; ?>
                <?php if($basePlayer->hasItem(array(1081, 1082))): ?>
                <option value="<?= encode(1081) ?>"><?php echo t('academia_treinamento.at33') ?></option>
                <?php endif; ?>
                <?php if($basePlayer->hasItem(array(1082))): ?>
                <option value="<?= encode(1082) ?>"><?php echo t('academia_treinamento.at34') ?></option>
                <?php endif; ?>
              </select></td>
              <td width="90" height="34" align="center" >
			  	<?php if($basePlayer->getAttribute('treino_dia') < $max_treino) :// && $basePlayer->hasItem(array(1028, 1081, 1082), NULL, 0)): ?>
					<a class="button" data-trigger-form="1"><?php echo t('botoes.treinar') ?></a>
                <?php else: ?>
					<a class="button ui-state-disabled"><?php echo t('botoes.treinar') ?></a>
                <?php endif; ?>
              </td>
            </tr>
        </table>
        </form>  
       <?php endif; ?>
        <table width="730" border="0" class="tutorial-treinamento">
          <tr>
            <td align="left">
	            <div style="width: 730px;" class="titulo-home"><p><span class="laranja">//</span> <?php echo t('academia_treinamento.at35') ?></p></div>
            </td>
          <tr>
            <td>
            <?php $treino = $basePlayer->getAttribute('treino_dia') > $max_treino ? $max_treino : $basePlayer->getAttribute('treino_dia') ?>
            <?php barra_exp3($treino, $max_treino, 730, $treino . "/" . $max_treino, "#840303", "#E90E0E", 3) ?>
            </td>
            <br />
          <tr >
            <td><?php echo t('academia_treinamento.at36') ?></td>
        </table>
        <br />
        <br />
        <div id="distribute-container">...</div>
    </div>
</table>
</div>
<div style="margin-top: 10px; clear:both; float: left;">
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "5090299777";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Treinamento Atributos -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
<?php if ($fall_timer): ?>
	<script type="text/javascript">
		createTimer(<?php echo $fall_timer->format('%H') ?>, <?php echo $fall_timer->format('%i') ?>, <?php echo $fall_timer->format('%s') ?>, 'd-penality-timer');
	</script>
<?php endif ?>