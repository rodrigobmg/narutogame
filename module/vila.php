<?php if(!$basePlayer->tutorial()->vila){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 20,
	 
	  steps: [
	  {
		element: ".msg_gai",
		title: "<?php echo t("tutorial.titulos.vila.1");?>",
		content: "<?php echo t("tutorial.mensagens.vila.1");?>",
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

<?php
	$item1		= Recordset::query('SELECT *, (SELECT player_id FROM vila_item a WHERE a.item_id=item.id AND a.vila_id=' . $basePlayer->id_vila . ') AS kage FROM item WHERE id_tipo=33 AND req_graduacao=1 ORDER BY ordem ASC');
	$item2		= Recordset::query('SELECT *, (SELECT player_id FROM vila_item a WHERE a.item_id=item.id AND a.vila_id=' . $basePlayer->id_vila . ') AS kage FROM item WHERE id_tipo=33 AND req_graduacao=2 ORDER BY ordem ASC');
	$item3		= Recordset::query('SELECT *, (SELECT player_id FROM vila_item a WHERE a.item_id=item.id AND a.vila_id=' . $basePlayer->id_vila . ') AS kage FROM item WHERE id_tipo=33 AND req_graduacao=3 ORDER BY ordem ASC');
	$item4		= Recordset::query('SELECT *, (SELECT player_id FROM vila_item a WHERE a.item_id=item.id AND a.vila_id=' . $basePlayer->id_vila . ') AS kage FROM item WHERE id_tipo=33 AND req_graduacao=4 ORDER BY ordem ASC');
	$item5		= Recordset::query('SELECT *, (SELECT player_id FROM vila_item a WHERE a.item_id=item.id AND a.vila_id=' . $basePlayer->id_vila . ') AS kage FROM item WHERE id_tipo=33 AND req_graduacao=5 ORDER BY ordem ASC');
	$item6		= Recordset::query('SELECT *, (SELECT player_id FROM vila_item a WHERE a.item_id=item.id AND a.vila_id=' . $basePlayer->id_vila . ') AS kage FROM item WHERE id_tipo=33 AND req_graduacao=6 ORDER BY ordem ASC');
	$rank		= Recordset::query("SELECT * FROM ranking WHERE posicao_vila = 1 AND id_vila = ".$basePlayer->id_vila."")->row_array();
	$vila		= Recordset::query('SELECT * FROM vila WHERE id=' . $basePlayer->id_vila)->row_array();
	$level		= Recordset::query('SELECT * FROM vila_nivel WHERE id=' . ($vila['nivel_vila'] + 1))->row_array();
	$positions	= array('id_kage', 'id_cons_guerra', 'id_cons_defesa', 'id_cons_vila');
	
	$base_reqs	= true;
	
	if($rank['id_player'] != $basePlayer->id || !$vila['nivel_ok']) {
		$base_reqs	= false;
	}
?>
<script type="text/javascript">
	$(document).ready(function () {
		$('.star-button').on('click', function () {
			var	_	= $(this);
			
			if(_.data('id')) {
				lock_screen(true);
				
				$.ajax({
					url:		'?acao=vila',
					data:		{'item': _.data('id')},
					type:		'post',
					success:	function (result) {
						eval(result);	
					}
				});
			}
		});
	});
</script>
<div class="titulo-secao"><p><?php echo t('vila.v1')?> <?php echo $basePlayer->nome_vila ?></p></div>
<?php if(isset($_GET['ok'])): ?>
	<?php msg('2',''.t('clas.c6').'', ''.t('vila.v2').'');?>
<?php endif ?>
<?php msg('3',''.t('vila.v3').'', ''.t('vila.v4').'');?>

<div style="width: 730px; position:relative; height: 220px">
	<?php foreach($positions as $position): ?>
		<?php
			switch($position) {
				case 'id_kage':
					$icon			= 'layout'.LAYOUT_TEMPLATE.'/vilas/bg-kage.png';
					$description	= $vila['kage'];
					
					break;

				case 'id_cons_guerra':
					$icon	= 'layout'.LAYOUT_TEMPLATE.'/vilas/bg-guerra.png';
					$description	= t('votacao.v7');
					
					break;

				case 'id_cons_defesa':
					$icon	= 'layout'.LAYOUT_TEMPLATE.'/vilas/bg-defesa.png';
					$description	= t('votacao.v6');
					
					break;

				case 'id_cons_vila':
					$icon	= 'layout'.LAYOUT_TEMPLATE.'/vilas/bg-vila.png';
					$description	= t('votacao.v5');
					
					break;
			}
			
			$player	= false;
			
			if($vila[$position]) {
				$player	= Recordset::query('
					SELECT
						a.id,
						nome,
						level,
						b.id AS grad_id
					
					FROM
						player a JOIN graduacao b ON b.id=a.id_graduacao
					
					WHERE
						a.id=' . $vila[$position])->row_array();
			}
		?>
	<div style="float: left; width:182px">
		<div style="position: relative; padding-bottom: 5px; width: 182px; height:45px; background-image:url('<?php echo img($icon)?>'); background-repeat: no-repeat">
			<div class="azul" style="position:relative; top: 20px; font-size: 13px; left: 10px"><?php echo $description ?></div>
		</div>
		<div>
			<?php if($player): ?>
				<img src="<?php echo player_imagem($player['id'], "pequena"); ?>" />
			<?php else: ?>
				--
			<?php endif ?>
		</div>
		<div style="padding-top: 5px;">
			<b style="font-size: 13px;">
				<?php echo $player ? $player['nome'] : '--' ?>
			</b><br />
			<b style="font-size: 13px;" class="laranja">
				<?php echo $player ? graduation_name($basePlayer->id_vila, $player['grad_id']) . ' - Lvl. ' . $player['level'] : '--' ?>
			</b>
		</div>
	</div>
	<?php endforeach ?>
</div>

<div id="vila-stats">
	<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="10%" rowspan="2">
			<div>
				LEVEL<br />
				<span style="font-size: 50px; color: <?php echo LAYOUT_TEMPLATE=="_azul" ? "#0e3a57" : "#3f2b1c"?>; top: 8px; position: relative"><?php echo $vila['nivel_vila']?></span>	
			</div>
		</td>
		<td width="25%" height="58" align="center"><b class="azul" style="font-size: 13px"><?php echo t('vila.v5')?></b><br /><?php echo $rank['nome']?></td>
		<td width="25%" align="center"><b class="azul" style="font-size: 13px"><?php echo t('vila.v6')?></b><br /><?php echo ($vila['nivel_vila'] - $vila['nivel_ok'] )?> <?php echo t('vila.v6')?></td>
		<td width="25%" align="center"><b class="azul" style="font-size: 13px"><?php echo t('vila.v7')?></b><br /><?php echo $vila['nivel_ok']?> <?php echo t('vila.v7')?></td>
		<td width="10%" rowspan="2" align="center">
			<div>
				MÁX. LEVEL<br />
				<span style="font-size: 50px; top: 8px; position: relative" class="laranja">20</span>	
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center"><?php barra_exp3($vila['exp_vila'], $level['exp'], 580, $vila['exp_vila'] . " Exp ". t('vila.v8')." / " . $level['exp'] ." Exp", "#840303", "#E90E0E", 3) ?></td>
	</tr>
	</table>

</div>
<br />

<div style="position: relative; width:730px">
	<div style="float: left; background-image:url('<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/<?php echo $vila['id'] ?>_kage.jpg'); height: 223px; width: 232px; margin: 5px 0 0 7px">
		<div style="position:relative; top: 180px;">
			<?php foreach($item1->result_array() as $item): ?>
				<?php
					$id		= 'i' . uniqid();
					$kage	= "";
					
					if($base_reqs && Vila::hasRequirements($vila, $item)) {
						$click_function	= 'data-id="' . $item['id'] . '"';
					} else {
						$click_function	= '';	
					}
				?>
				<?php if(vila_has_item($basePlayer->id_vila, $item['id'])): ?>
					<?php
						$kage	= "<br /><ul><b>".t('vila.v9').":</b><br /><li>" . player_nome($item['kage']) ."</li></ul>";
					?>
					<img src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/star.png" id="<?php echo $id ?>" />
				<?php else: ?>
					<img class="star-button" src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/star_off.png" id="<?php echo $id ?>" <?php echo $click_function ?>  style="cursor:pointer"/>
				<?php endif ?>
				<?php echo vila_item_tooltip($id, $item, $kage) ?>
			<?php endforeach ?>
		</div>
	</div>
	<div style="float: left; background-image:url('<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/<?php echo $vila['id'] ?>_dojo.jpg'); height: 223px; width: 232px; margin: 5px 0 0 7px">
		<div style="position:relative; top: 180px;">
			<?php foreach($item2->result_array() as $item): ?>
				<?php
					$id	= 'i' . uniqid();
					$kage	= "";
					
					if($base_reqs && Vila::hasRequirements($vila, $item)) {
						$click_function	= 'data-id="' . $item['id'] . '"';
					} else {
						$click_function	= '';	
					}
				?>
				<?php if(vila_has_item($basePlayer->id_vila, $item['id'])): ?>
					<?php
						$kage	= "<br /><ul><b>".t('vila.v9').":</b><br /><li>" . player_nome($item['kage']) ."</li></ul>";
					?>
					<img src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/star.png" id="<?php echo $id ?>" />
				<?php else: ?>
					<img class="star-button" src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/star_off.png" id="<?php echo $id ?>" <?php echo $click_function ?> style="cursor:pointer"/>
				<?php endif ?>
				<?php echo vila_item_tooltip($id, $item, $kage) ?>
			<?php endforeach ?>
		</div>
	</div>
	<div style="float: left; background-image:url('<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/<?php echo $vila['id'] ?>_ramen.jpg'); height: 223px; width: 232px; margin: 5px 0 0 7px">
		<div style="position:relative; top: 180px;">
			<?php foreach($item3->result_array() as $item): ?>
				<?php
					$id	= 'i' . uniqid();
					$kage	= "";
					
					if($base_reqs && Vila::hasRequirements($vila, $item)) {
						$click_function	= 'data-id="' . $item['id'] . '"';
					} else {
						$click_function	= '';	
					}
				?>
				<?php if(vila_has_item($basePlayer->id_vila, $item['id'])): ?>
					<?php
						$kage	= "<br /><ul><b>".t('vila.v9').":</b><br /><li>" . player_nome($item['kage']) ."</li></ul>";
					?>
					<img src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/star.png" id="<?php echo $id ?>" />
				<?php else: ?>
					<img class="star-button" src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/star_off.png" id="<?php echo $id ?>" <?php echo $click_function ?> style="cursor:pointer"/>
				<?php endif ?>
				<?php echo vila_item_tooltip($id, $item, $kage) ?>
			<?php endforeach ?>
		</div>
	</div>
	<div style="float: left; background-image:url('<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/<?php echo $vila['id'] ?>_hospital.jpg'); height: 223px; width: 232px; margin: 5px 0 0 7px">
		<div style="position:relative; top: 180px;">
			<?php foreach($item4->result_array() as $item): ?>
				<?php
					$id	= 'i' . uniqid();
					$kage	= "";
					
					if($base_reqs && Vila::hasRequirements($vila, $item)) {
						$click_function	= 'data-id="' . $item['id'] . '"';
					} else {
						$click_function	= '';	
					}
				?>
				<?php if(vila_has_item($basePlayer->id_vila, $item['id'])): ?>
					<?php
						$kage	= "<br /><ul><b>".t('vila.v9').":</b><br /><li>" . player_nome($item['kage']) ."</li></ul>";
					?>
					<img src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/star.png" id="<?php echo $id ?>" />
				<?php else: ?>
					<img class="star-button" src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/star_off.png" id="<?php echo $id ?>" <?php echo $click_function ?> style="cursor:pointer"/>
				<?php endif ?>
				<?php echo vila_item_tooltip($id, $item, $kage) ?>
			<?php endforeach ?>
		</div>
	</div>
	<div style="float: left; background-image:url('<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/<?php echo $vila['id'] ?>_ninja_shop.jpg'); height: 223px; width: 232px; margin: 5px 0 0 7px">
		<div style="position:relative; top: 180px;">
			<?php foreach($item5->result_array() as $item): ?>
				<?php
					$id	= 'i' . uniqid();
					$kage	= "";
					
					if($base_reqs && Vila::hasRequirements($vila, $item)) {
						$click_function	= 'data-id="' . $item['id'] . '"';
					} else {
						$click_function	= '';	
					}
				?>
				<?php if(vila_has_item($basePlayer->id_vila, $item['id'])): ?>
					<?php
						$kage	= "<br /><ul><b>".t('vila.v9').":</b><br /><li>" . player_nome($item['kage']) ."</li></ul>";
					?>
					<img src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/star.png" id="<?php echo $id ?>" />
				<?php else: ?>
					<img class="star-button" src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/star_off.png" id="<?php echo $id ?>" <?php echo $click_function ?> style="cursor:pointer"/>
				<?php endif ?>
				<?php echo vila_item_tooltip($id, $item, $kage) ?>
			<?php endforeach ?>
		</div>
	</div>
	<div style="float: left; background-image:url('<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/<?php echo $vila['id'] ?>_casa.jpg'); height: 223px; width: 232px; margin: 5px 0 0 7px">
		<div style="position:relative; top: 180px;">
			<?php foreach($item6->result_array() as $item): ?>
				<?php
					$id	= 'i' . uniqid();
					$kage	= "";
					
					if($base_reqs && Vila::hasRequirements($vila, $item)) {
						$click_function	= 'data-id="' . $item['id'] . '"';
					} else {
						$click_function	= '';	
					}
				?>
				<?php if(vila_has_item($basePlayer->id_vila, $item['id'])): ?>
					<?php
						$kage	= "<br /><ul><b>".t('vila.v9').":</b><br /><li>" . player_nome($item['kage']) ."</li></ul>";
					?>
					<img src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/star.png" id="<?php echo $id ?>" />
				<?php else: ?>
					<img class="star-button" src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE?>/vilas/star_off.png" id="<?php echo $id ?>" <?php echo $click_function ?> style="cursor:pointer"/>
				<?php endif ?>
				<?php echo vila_item_tooltip($id, $item, $kage) ?>
			<?php endforeach ?>
		</div>
	</div>
</div>
