<?php if(!$basePlayer->tutorial()->battle_4x4){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 28,

	  steps: [
	  {
		element: ".msg_gai",
		title: "<?php echo t("tutorial.titulos.dojo.2");?>",
		content: "<?php echo t("tutorial.mensagens.dojo.2");?>",
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
	$total					= 5;
	$rnd_count				= Recordset::query('SELECT COUNT(id) AS total FROM player_batalhas_rnd WHERE id_player=' . $basePlayer->id)->row()->total;
	$last_rnd_inactivity	= Player::getFlag('ult_inatividade', $basePlayer->id);
	$can_battle_rnd			= true;
	$can_battle_rnd2		= true;
	$today					= date('N');

	if($last_rnd_inactivity) {
		$diff	= (new DateTime())->diff(new DateTime($last_rnd_inactivity));

		if($last_rnd_inactivity && $diff->h < 1 && $diff->d < 1) {
			$can_battle_rnd	= false;
			$end_inactivity	= date('Y-m-d H:i:s', strtotime('+1 hours', strtotime($last_rnd_inactivity)));
			$diff			= (new DateTime($end_inactivity))->diff(new DateTime());
		}
	}
	if($rnd_count >= 5){
		$can_battle_rnd2	= false;
	}
?>
<script type="text/javascript">
	$(document).ready(function () {
		$('#battle-4x-enter-queue').on('click', function () {
			lock_screen(true);

			$.ajax({
				url:		'?acao=dojo_random_queue',
				type:		'post',
				data:		{queue4x: 1},
				success:	function (result) {
					if(result.success) {
						location.href	= '?secao=dojo_random_wait';
					} else {
						lock_screen(false);
						jalert('<?php echo nl2nothing(t('random.error')) ?>');
					}
				}
			});
		});
	});
</script>
<div class="titulo-secao"><p>Dojo 4x4</p></div>
<br />

    <div id="cnBase" class="direita">

    <div id="cnQueue">
		<div class="msg_gai">
            <div class="msg">
                <div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
				<b>Batalha 4x4</b>
				<p>
					<?php echo t('geral.g75') ?><br /><br />
					<?php barra_exp3($rnd_count, $total, 327, sprintf(t('random.progress'), $rnd_count > 5 ? 5 : $rnd_count), "#2C531D", "#537F3D", 6)?>
					<br />
					<span class="verde"><?php echo t('geral.g76') ?></span>
					<br /><br />
					<?php //if($rnd_count >= 3): ?>
						<?php  /*<p class="laranja"><?php echo t('random.max_count'); ?></p><br /><br />*/?>
					<?php //endif ?>

					<?php if ($today == 2 || $today == 4 ): ?>
						<?php if($can_battle_rnd && $can_battle_rnd2): ?>
					    	<?php if($basePlayer->hp < $basePlayer->max_hp / 2 || $basePlayer->sp < $basePlayer->max_sp / 2 || $basePlayer->sta < $basePlayer->max_sta / 2): ?>
								<p class="laranja"><?php echo t('actions.a31'); ?></p>
							<?php elseif($basePlayer->id_graduacao <= 2): ?>
								<p class="laranja"><?php echo t('random.error_lvl'); ?></p>
					    	<?php else: ?>
								<a class="button" id="battle-4x-enter-queue"><?php echo t('geral.g77') ?></a>
							<?php endif ?>
						<?php else: ?>
							<?php if(!$can_battle_rnd){?>
								<p class="laranja"><?php echo t('random.error_inactivity'); ?></p>
								<script type="text/javascript">
									$(document).ready(function () {
										createTimer(<?php echo $diff->h ?>, <?php echo $diff->i ?>, <?php echo $diff->s ?>, 'rnd-timer-count');
									});
								</script>
							<?php }else{?>
								<p class="laranja"><?php echo t('random.error_day2'); ?></p>
							<?php }?>
						<?php endif ?>
					<?php else: ?>
						<p class="laranja"><?php echo t('random.error_day'); ?></p>
					<?php endif ?>
				</p>
			  </div>
			</div>
		</div>
    </div>
</div>
<br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "4392295775";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Dojo -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
