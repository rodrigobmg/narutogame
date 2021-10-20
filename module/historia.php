<script>
	$(document).ready(function(e) {
		$('.b-historia-batalha').bind('click', function() {
			$.ajax({
				url:	'?acao=historia_aceitar',
				type:	'post',
				data:	{saga: $(this).data('saga'), npc: $(this).data('npc')},
				dataType: 'script'
			});
			
			$('.b-historia-batalha').hide('explode');
		});
		
		$('.b-historia-saga').bind('click', function () {
			if(!this.up) {
				this.src	= '<?php echo img('layout/modo_historia/down.png') ?>';
				this.up		= true;
			} else {
				this.src	= '<?php echo img('layout/modo_historia/up.png') ?>';
				this.up		= false;
			}
			
			$('.historia-saga-' + $(this).attr('rel')).toggleClass('shown');
		});
	});
</script>
<?php //TODO: POR AS PORRAS DOS CACHES DEPPOIS Q CADASTRAR TUDO ?>
<div class="titulo-secao"><p><?php echo t('historia.h1')?></p></div>
<br />
<?php msg('2',''.t('historia.h2').'', ''.t('historia.h3').'');?>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0" class="with-n-tabs" data-auto-default="1" id="modoh-tabs">
	<tr>
	  <td width="25%"><a class="button" rel="#historia-base-210">Naruto</a></td>
	  <td><a class="button" rel="#historia-base-211">Naruto Shippuuden</a></td>
	  <td><a class="button" rel="#historia-base-240"><?php echo t('templates.t70')?></a></td>
	  <td><a class="button" rel="#historia-base-362"><?php echo t('templates.t74')?></a></td>
	</tr>
</table>
<br />
<?php
	$bases = Recordset::query('SELECT * FROM evento WHERE parent_id=0 AND historia=1 ORDER BY id');
?>
<?php foreach($bases->result_array() as $base): ?>
	<div id="historia-base-<?php echo $base['id'] ?>">
	<?php
		$sagas	= Recordset::query('SELECT * FROM evento WHERE parent_id=' . $base['id'] . ' ORDER BY id');
	?>
	<?php foreach($sagas->result_array() as $saga): ?>
		<?php
			$div_id	= 'DIV' . uniqid();
		?>
		<div class="historia-saga historia-base-<?php echo $base['id'] ?> historia-titulo-saga-<?php echo $saga['id'] ?>" data-no-anim="1" id="<?php echo $div_id ?>">
			<div style="float: left; width: 140px; height:100px; position: relative; top: 1px; left: 15px;">
				<img src="<?php echo img()?>layout/modo_historia/<?php echo $saga['id'] ?>.png" />
			</div>
			<div style="width: 455px; height:100px; float: left">
				<div style="font-size: 18px; padding-top: 64px;"><?php echo $saga['nome_'. Locale::get()] ?></div>
			</div>
			<div style="width: 135px; height:100px; float: left; position:relative; top: 66px">	
				<img src="<?php echo img()?>layout/modo_historia/up.png" style="cursor: pointer" rel="<?php echo $saga['id'] ?>" class="b-historia-saga" />
			</div>	
		</div>
		<?php echo generic_tooltip($div_id, ''.t('historia.h4').':<br /><br /><b>'. t('geral.recompensas').'</b><br/><br/>' . sorte_ninja_tooltip($saga['recompensa'])); ?>
		<div class="historia-npc historia-saga-<?php echo $saga['id'] ?>">
		<?php
			$npcs			= Recordset::query('SELECT a.*, b.historia_req_npc, b.historia_req_level, b.historia_req_graduacao FROM evento_npc a JOIN evento_npc_evento b ON b.id_evento_npc=a.id AND b.id_evento=' . $saga['id'].' ORDER by a.id asc');
			$sz_npc			= $npcs->num_rows;
			$total_morto	= 0;
		?>
		<?php foreach($npcs->result_array() as $npc): ?>
		<?php
			$npc_morto	= Recordset::query('SELECT * FROM evento_player_npc WHERE id_evento=' . $saga['id'] . ' AND id_npc=' . $npc['id'] . " AND id_player=" . $basePlayer->id)->num_rows;
			$id			= uniqid();
			$has_reqs	= true;
			$reqs		= "<ul style='padding:0; margin:0'>";
			$style_e	= "style=\"color: #F00\"";

			if($npc_morto) {
				$total_morto++;
			}
			
			if($npc['historia_req_graduacao']) {
				$reqs		.= "<li " . ($basePlayer->id_graduacao >= $npc['historia_req_graduacao'] ? '' : $style_e) . ">".t('historia.h6')." <b>" . graduation_name($basePlayer->id_vila, $npc['historia_req_graduacao']) . "</b> ".t('historia.h5')."</li>";
				
				if(!($basePlayer->id_graduacao >= $npc['historia_req_graduacao'])) {
					$has_reqs	= false;
				}
			}
			if($npc['historia_req_level']) {
				$reqs		.= "<li " . ($basePlayer->level >= $npc['historia_req_level'] ? '' : $style_e) . ">".t('historia.h9')." <b>" . $npc['historia_req_level'] . "</b> ".t('historia.h5')."</li>";
				
				if(!($basePlayer->level >= $npc['historia_req_level'])) {
					$has_reqs	= false;
				}
			}

			if($npc['historia_req_npc']) {
				$req_npc		= Recordset::query('SELECT * FROM evento_npc WHERE id=' . $npc['historia_req_npc'])->row_array();
				$req_npc_morto	= Recordset::query('SELECT * FROM evento_player_npc WHERE id_npc=' . $npc['historia_req_npc'] . " AND id_player=" . $basePlayer->id)->num_rows;
				
				$reqs		.= "<li " . ($req_npc_morto ? '' : $style_e) . ">".t('historia.h7')." <b>" . $req_npc['nome_'. Locale::get()] . "</b> ".t('historia.h8')."</li>";
				
				if(!$req_npc_morto) {
					$has_reqs = false;	
				}
			}
			
			$reqs	.= "</ul>";
		?>
		<div class="historia-saga-npc">
			<div style="position: relative; float: left; width: 150px; top:13px">
				<img src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/modo_historia/dojo/<?php echo $npc['id'] ?>.<?php echo LAYOUT_TEMPLATE=="_azul" ? 'jpg' : 'png'?>" /><br />
				<span style="font-size: 14px;"><?php echo $npc['nome_'. Locale::get()] ?></span>
			</div>
			<div style="position: relative; float: left; width: 325px; top:28px">
				<?php echo $npc['descricao_'. Locale::get()] ?>
			</div>
			<div style="position: relative; float: left; width: 100px; top:19px">
				<img src="<?php echo img('layout/requer.gif') ?>" id="<?php echo $id ?>" />
				<?php echo generic_tooltip($id, $reqs); ?>
			</div>
			<div style="position: relative; float: left; width: 70px; top:26px">
				<?php if(!$npc_morto && $has_reqs): ?>
				<a class="button b-historia-batalha" data-saga="<?php echo encode($saga['id']) ?>" data-npc="<?php echo encode($npc['id']) ?>"><?php echo t('botoes.duelar')?></a>
				<?php endif ?>
				<?php if($npc_morto){?>
					<a class="button ui-state-red">Derrotado</a>
				<?php }?>
			</div>	
		</div>
		<br />
		<?php endforeach; ?>
		</div>
		<?php if($total_morto == $sz_npc): ?>
		<script>
		$('.historia-titulo-saga-<?php echo $saga['id'] ?>').addClass('historia-titulo-completo');
		$('.historia-saga-<?php echo $saga['id'] ?>').addClass('historia-saga-completo');
		</script>
		<?php endif ?>
	<?php endforeach; ?>
	</div>
<?php endforeach; ?><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "8462568573";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Historia -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>