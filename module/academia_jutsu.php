<?php
	$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 1;
?>	 
<?php if($tipo==3){?>
	<?php if(!$basePlayer->tutorial()->medicinal){?>
	<script>
	 $("#topo2").css("z-index", 'initial');
	 $("#menu-container").css("z-index", 'initial');
	$(function () {
		var tour = new Tour({
		  backdrop: true,
		  page: 18,
		 
		  steps: [
		  {
			element: ".subtitulo-home",
			title: "<?php echo t("tutorial.titulos.jutsus.1");?>",
			content: "<?php echo t("tutorial.mensagens.jutsus.1");?>",
			placement: "top"
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
<?php }elseif ($tipo==6){?>
	<?php if(!$basePlayer->tutorial()->kinjutsu){?>
	<script>
	 $("#topo2").css("z-index", 'initial');
	 $("#menu-container").css("z-index", 'initial');
	$(function () {
		var tour = new Tour({
		  backdrop: true,
		  page: 19,
		 
		  steps: [
		  {
			element: ".subtitulo-home",
			title: "<?php echo t("tutorial.titulos.jutsus.2");?>",
			content: "<?php echo t("tutorial.mensagens.jutsus.2");?>",
			placement: "top"
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
<?php }else{?>	
	<?php if(!$basePlayer->tutorial()->jutsus){?>
	<script>
	 $("#topo2").css("z-index", 'initial');
	 $("#menu-container").css("z-index", 'initial');
	$(function () {
		var tour = new Tour({
		  backdrop: true,
		  page: 17,
		 
		  steps: [
		  {
			element: ".subtitulo-home",
			title: "<?php echo t("tutorial.titulos.jutsus.3");?>",
			content: "<?php echo t("tutorial.mensagens.jutsus.3");?>",
			placement: "top"
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
<?php } ?>	
<?php
	$type	= isset($_GET['tipo']) && $_GET['tipo'] ? $_GET['tipo'] : 0;
	
	if(!is_numeric($type)) {
		$type	= 0;
	}
?>
<?php if($_SESSION['usuario']['msg_vip']): ?>
<script type="text/javascript">
	head.ready(function () {
		$(document).ready(function() {
			if(!$.cookie("academia_jutsu")){
				$("#dialog").dialog({ 
					width: 550,
					height: 510, 
					title: '<?php echo t('academia_jutsu.dicas_jutsus');?>', 
					modal: true,
					close: function(){
						$.cookie("academia_jutsu", "foo", { expires: 1 });
					}
				});
			}
		});
	});
</script>
<?php endif ?>
<script type="text/javascript" src="js/academia_treinamento.js"></script>
<div id="dialog" style="display:none">
	<div style="background:url(<?php echo img()?>layout/popup/Treino-Jutsu.png); background-repeat:no-repeat; width:516px !important; height: 450px !important;">
		<div style="position:relative; width:350px; top:240px; padding-left: 80px;">
			
			<b><a href="index.php?secao=vantagens" class="linksSite3" style="font-size:16px"><?php echo t('academia_jutsu.adquira_vantagens');?></a></b><br /><br />
			<ul style="margin:0; padding:0;">
				<li style="margin-bottom:5px">
					<b><a href="index.php?secao=vantagens" class="linksSite3"><?php echo t('academia_jutsu.jutsu_medicinais');?></a></b><br />
					<?php echo t('academia_jutsu.aj_descricao');?>
				</li>
				
			</ul>
		</div>
	</div>
</div>
<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p><?php echo t('academia_jutsu.academia_jutsu_t') ?></p></div>
<?php msg(1, t('academia_jutsu.sobre_as_tecnicas_ninjas') .'',' '. t('academia_jutsu.aj_descricao2').' '); ?>
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "1997232574";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Jutsus -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br /><br />
<?php
	if(isset($_GET['fj']) && $_GET['fj']) {
		msg('3',''. t('academia_jutsu.problema').' ', sprintf(t('academia_jutsu.msg_chakra_stamina'), ($_GET['fj'] == 1 ? "Chakra" : "Stamina")));
	}
	if(isset($_GET['existent']) && $_GET['existent']) {	
		msg('2',''. t('academia_jutsu.problema').'', ''. t('academia_jutsu.voce_ja_treinou_jutsu').'');
	}
	if(isset($_GET['j']) && isset($_GET['e']) && $_GET['j'] && is_numeric($_GET['e']) && $basePlayer->hasItem($_GET['j'])) {
		msg('6',''. t('academia_jutsu.parabens').'', ''. t('academia_jutsu.voce_esforcou_aprendeu').' <span class="laranja">' . $basePlayer->getItem($_GET['j'])->getAttribute('nome') . '</span><br />'. t('academia_jutsu.durante_treinamento').' <span class="verde">'. $_GET['e'] .' '. t('academia_jutsu.de_experiencia').'</span><br />'. t('academia_jutsu.treine_bastante').'');
	}

	$filters 		= array();
	$tabs			= array();
	$show_filters	= false;	
	
	switch($type) {
		case 0: // Tai, nin, gen
			$tabs	= array_map(function ($data) {
				return ['title' => $data['nome'], 'filter' => '.tab-habil-' . $data['id'], 'filter-1' => $data['id']];
			}, Recordset::query('SELECT * FROM habilidade WHERE id IN(1,2,3,4)', true)->result_array());
		
			break;
			
		case 1: // Elemento
			$where	= 'AND sennin=0 AND id_elemento IS NOT NULL and id_cla IS NULL and removido="0"';
			$sort	= "req_level ASC";
			$tabs	= array_map(function ($data) {
				return ['title' => $data['nome'], 'filter' => '.tab-elem-' . $data['id'], 'filter-1' => $data['id']];
			}, Recordset::query('SELECT * FROM elemento', true)->result_array());
			
			break;

		case 2: // Clãs
			$where			= 'AND sennin=0 AND id_elemento IS NULL and id_cla IS NOT NULL';
			$sort			= "id_habilidade ASC, req_level ASC";
			$tabs			= array_map(function ($data) {
				return ['title' => $data['nome'], 'filter' => '.tab-habil-' . $data['id'], 'filter-1' => $data['id']];
			}, Recordset::query('SELECT * FROM habilidade WHERE id IN(1,2,3,4)', true)->result_array());
			
			$show_filters	= true;
			$filter_type	= 0;
			$filters		= array_map(function ($data) {
				return ['filter' => '.tab-cla-' . $data['id'], 'title' => $data['nome'], 'filter-2' => $data['id']];
			}, Recordset::query('SELECT id, nome FROM cla', true)->result_array());
			
			break;
		
		case 3: //Medicinal
			$item_type	= 24;
			
			break;
		
		/*case 4: // sennin
			$where	= " AND sennin=1";
			$sort	= "id_habilidade ASC, req_level ASC";
		
			break;
		*/	
		case 5: // portão
			$where	= " AND sennin=0 AND id_elemento IS NULL AND id_cla IS NULL AND id_habilidade=1 AND req_item IS NOT NULL";
			$sort	= "req_level ASC";
			
			break;

		case 6: // kinjutsu
			$item_type	= 37;
			
			break;
	}

?>
<?php if(sizeof($tabs)): ?>
	<div class="with-n-tabs type-tabs" data-auto-default="1" id="academy-tabs-<?php echo $type ?>">
	<?php foreach($tabs as $k => $v): ?>
		<a class="button" rel="<?php echo $v['filter'] ?>" data-filter="<?php echo $v['filter-1'] ?>"><?php echo $v['title'] ?></a>
		<?php if($v['title']=="Raiton"){ echo "<br /><br />";}?>
	<?php endforeach ?>
	</div>
<?php endif ?>
<?php if($show_filters && $filter_type == 0): ?>
	<table>
		<tr>
			<th style="color:white"><?php echo t('academia_jutsu.filtrar_por');?></th>
			<td>
				<select class="is-sub-tab-of" data-of=".tab-habil" data-owner=".type-tabs" style="border: 1px solid #F30;" id="academy-filter">
					<option value="" data-filter="0"><?php echo t('academia_jutsu.todos');?>:</option>
					<?php foreach($filters as $v): ?>
						<option data-filter="<?php echo $v['filter-2'] ?>" value="<?php echo $v['filter'] ?>"><?php echo $v['title'] ?></option>
					<?php endforeach ?>
				</select>
			</td>
		</tr>
	</table>
<?php elseif($show_filters && $filter_type == 1): ?>
	<div class="is-sub-tab-of" data-of=".tab-habil" data-owner=".type-tabs">
		<?php foreach($filters as $v): ?>
			<a class="button" rel="<?php echo $v['filter'] ?>"><?php echo $v['title'] ?></a>
		<?php endforeach ?>
	</div>
<?php endif; ?>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="subtitulo-home">
			<table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
				<tr>
					<td align="center" width="60">&nbsp;</td>
					<td align="center" width="450"><?php echo t('geral.jutsu') ?></td>
					<td width="120" align="center"><?php echo t('geral.requerimentos') ?></td>
					<td width="100" align="center">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div id="academy-jutsu-list">...</div>
<form name="fTreino" action="?acao=academia_treinamento_jutsu" method="post">
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="tipo" value="<?php echo isset($_GET['tipo']) ? $_GET['tipo'] : '' ?>" />
	<input type="hidden" name="p" value="<?php echo encode($basePlayer->id) ?>" />
</form>	
</div>
<script type="text/javascript">
	(function () {
		var	f1	= 0;
		var	f2	= 0;

		function _refresh() {
			lock_screen(true);

			$.ajax({
				url:		'?acao=academia_jutsu',
				type:		'post',
				data:		{type: <?php echo $type ?>, filter1: f1 || 0, filter2: f2 || 0},
				success:	function (result) {
					lock_screen(false);

					$('#academy-jutsu-list').html(result);
				}
			});
		}

		$('#academy-tabs-<?php echo $type ?>').on('click', '.button', function () {
			f1	= $(this).data('filter');

			_refresh();
		});

		$('#academy-filter').on('change', function () {
			f2	= $(this.options[this.options.selectedIndex]).data('filter');

			_refresh();
		});

		<?php if (!sizeof($tabs)): ?>
			_refresh();
		<?php endif; ?>
	})();
</script>