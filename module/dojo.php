<?php
	$total					= 5 + $basePlayer->bonus_vila['dojo_limite_npc_dojo'];
	$rnd_count				= Recordset::query('SELECT COUNT(id) AS total FROM player_batalhas_rnd WHERE id_player=' . $basePlayer->id)->row()->total;
	$last_rnd_inactivity	= Player::getFlag('ult_inatividade', $basePlayer->id);
	$can_battle_rnd			= true;
	$today					= 3;//date('N');
	
	if($last_rnd_inactivity != '') {
		$diff	= Recordset::query('SELECT HOUR(TIMEDIFF(NOW(), ult_Inatividade)) AS diff, TIMEDIFF(DATE_ADD(ult_inatividade, INTERVAL 1 HOUR), NOW()) AS total_diff FROM player_flags WHERE id_player=' . $basePlayer->id);
	
		// Já passou 1h ?
		if($diff->row()->diff < 1) {
			$can_battle_rnd	= false;
		}
	}
?>
<script type="text/javascript">
	$(document).ready(function () {
	
		$('#battle-1x-enter-queue').on('click', function () {
			lock_screen(true);
			
			$.ajax({
				url:		'?acao=dojo_random_queue',
				type:		'post',
				data:		{queue1x: 1},
				success:	function (result) {
					if(result.success) {
						location.href	= '?secao=dojo_random_wait';					
					} else {
						lock_screen(false);
						jalert('<?php echo nl2nothing(t('random.error1x')) ?>');
					}
				}
			});
		});

		setInterval(function () {
			$.ajax({
				url: 'index.php?acao=dojo_batalha_listar',
				type: 'post',
				data: '',
				success: function (e) { 
					$('#cnDesafiantes').html(e);
				}
			});
		}, 2000);
	});
</script>
<?php if(!$basePlayer->tutorial()->battle_npc){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 26,
	 
	  steps: [
	  {
		element: "#tabs-dojo",
		title: "<?php echo t("tutorial.titulos.dojo.1");?>",
		content: "<?php echo t("tutorial.mensagens.dojo.1");?>",
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
<div class="titulo-secao"><p>Dojo</p></div>
  <?php $qtdLutas = Recordset::query('SELECT COUNT(id) as totalLutas FROM player_batalhas_npc WHERE id_player='. $basePlayer->id .'')->row()->totalLutas; ?>
    <div id="cnBase" class="direita">
          <br />
          <table width="730" border="0" align="center" cellpadding="0" cellspacing="0" class="with-n-tabs" data-auto-default="1" id="tabs-dojo">
            <tr>
			  <td><a class="button" rel="#cnLutadores" onclick="dojoViewport(2, this)"><?php echo t('botoes.lutadores_do_dojo') ?></a></td>
			  <td><a class="button" rel="#cnQueue1x" onclick="dojoViewport(4, this)"><?php echo t('botoes.lutadores_pvp') ?></a></td>
			  <td><a class="button" rel="#cnBatalhaDojo" onclick="dojoViewport(0, this)"><?php echo t('botoes.criar_batalha') ?></a></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="10" colspan="5" align="center">&nbsp;</td>
            </tr>
            <tr>
              <td height="34" colspan="5" align="center">
			  <br />
			  <script type="text/javascript">
				google_ad_client = "ca-pub-9166007311868806";
				google_ad_slot = "2668370972";
				google_ad_width = 728;
				google_ad_height = 90;
			</script>
			<!-- Dojo -->
			<script type="text/javascript"
			src="//pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
              </td>
            </tr>
          </table>
    <br />
    <div id="cnBatalhaDojo">
<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/5.png); background-repeat: no-repeat;">
				<b><?php echo t('dojo.d7')?></b>
				<p>
					<?php echo t('dojo.d8')?>
				</p>
			  </div>		
			</div>
		</div>
		<div id="cnCriarBatalha" style="clear:both">
			<form name="fCriarBatalha" onsubmit="return false;">
				<table width="730" border="0" align="center" cellpadding="0" cellspacing="2">
				<tr>
					<td width="24%" align="center"><b><?php echo t('dojo.nome_da_sala') ?></b></td>
					<td width="48%" align="center">
						<input name="tBatalhaNome" type="text" id="tBatalhaNome" size="40" maxlength="30" /><br />
						<input type="checkbox" name="same-level" value="1" /> <?php echo t('dojo.check_nivel')?>
					</td>
					<td width="28%">
						<a class="button" onclick="doCriarBatalha()"><?php echo t('botoes.criar') ?></a>
					</td>
				</tr>
			</table>
			</form>
		</div>
		<br /><br />
		<div id="cnDesafiantes"></div>
    </div>
    <div id="cnLutadores" style="display: none">
<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
				<b><?php echo t('dojo.d1')?></b>
				<p>
					<?php echo sprintf(t('dojo.d2'), $total) ?><br /><br />
					<?php barra_exp3($qtdLutas, $total, 327, " $qtdLutas ".t('dojo.d4')." " . $total . " ".t('dojo.d3')."", "#2C531D", "#537F3D", 6)?>
				</p>
		</div>		
	</div>
</div>
		<div class="break"></div>
		<br />
		<br />
		<div class="content"></div>
    </div>
    <div id="cnQueue1x">
		<div class="msg_gai">
            <div class="msg">
                <div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/3.png); background-repeat: no-repeat;">
				<b><?php echo t('dojo.d5')?></b>
				<p>
					<?php echo t('dojo.d6')?>
				</p>
				
				<?php
					if($basePlayer->id_graduacao == 1){
				?>
					<br /><br /><span class="laranja">Somente Ninjas com a graduação Genin podem duelar no Dojo PVP</span>
				<?php 
					}else{
				?>	
					<br /><br /><span class="laranja">As Batalhas do Dojo PVP dão Experiência e Ryous até o level 15.</span>
				<?php }?>
			  </div>		
			</div>
		</div>
   		<?php
			if($basePlayer->id_graduacao > 1 and $basePlayer->credibilidade == 100){
		?>
    		<a class="button" id="battle-1x-enter-queue"><?php echo t('geral.g77') ?></a>
    	<?php }?>
    </div>
    </td>
  </tr>
</table>
</div>
