<?php if(!$basePlayer->tutorial()->fidelity){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 14,
	 
	  steps: [
	  {
		element: ".tutorial-0",
		title: "<?php echo t("tutorial.titulos.fidelidade.1");?>",
		content: "<?php echo t("tutorial.mensagens.fidelidade.1");?>",
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
<div class="titulo-secao"><p><?php echo t('fidelity.title')?></p></div>
<?php msg(1,t('fidelity.title2'),t('fidelity.description2')); ?><br />
<?php
$days	= [
			'0',
			'1',
			'2',
			'3',
			'4',
			'5',
			'6',
			'7'
			
		];
$names	= [
			t('fidelity.days.1'),
			t('fidelity.days.2'),
			t('fidelity.days.3'),
			t('fidelity.days.4'),
			t('fidelity.days.5'),
			t('fidelity.days.6'),
			t('fidelity.days.7'),
			t('fidelity.days.8')
			
		];					
$player_fidelity 	= Recordset::query("SELECT * FROM player_fidelity WHERE id_player=".$basePlayer->id)->row();
if($player_fidelity->reward){
	$day_atual = $player_fidelity->day+1;
}else{
	$day_atual = $player_fidelity->day-1;
}
$user_stats 		= Recordset::query("SELECT credits FROM global.user_ref_given WHERE id_user=".$basePlayer->id_usuario);
if(!$user_stats->num_rows){
	Recordset::query("INSERT INTO global.user_ref_given(id_user) VALUES(" . $basePlayer->id_usuario . ")");
	$user_stats 		= Recordset::query("SELECT credits FROM global.user_ref_given WHERE id_user=".$basePlayer->id_usuario)->row();
}else{
	$user_stats = $user_stats->row();	
}
?>
<?php foreach($days as $day):?>
<?php 
	if($player_fidelity->day == 1 && $day+1 == 1 && $player_fidelity->reward==1){
		$active = 'active';
	}elseif($player_fidelity->day > 1 && $day+1 < $player_fidelity->day){
		$active = 'active';
	}elseif($player_fidelity->day == $day+1 && $player_fidelity->reward==1){	
		$active = 'active';
	}else{
		$active = '';	
	}
?>
<div class="ability-speciality-box <?php echo $active?> tutorial-<?php echo $day?>" style="width: 175px !important; height: 250px !important">
	<div>
		<div class="image">
			<img src="<?php echo 'images/layout/fidelity/'. ($day+1) .'.png' ?>" />
		</div>
		<div class="name">
			<?php echo t('fidelity.logar');?> <?php echo $day+1?> <?php echo t('fidelity.dias');?>
		</div>
		<div class="description" style="height: 40px !important;">
			<?php 
				if($user_stats->credits){
					if(strtotime(date('Y-m-d H:i:s')) >= strtotime($user_stats->credits . "+7 days") || $day + 1 != 8){
						echo $names[$day];
					}else{	
						echo "<span class='laranja'>".t('fidelity.ja_ganhou')."</span>";
					}
				}else{
					echo $names[$day];
				}
			?>
		</div>
		<div class="details">
		</div>
		<div style="position:relative;">
			<?php if($player_fidelity->day == $day+1 && $player_fidelity->reward==0 && !$active){?>
				<a class="button" onclick="rewardFidelity('<?php echo $day+1?>')"><?php echo t('fidelity.buttons.receber');?></a>
			<?php }elseif($active){?>
				<a class="button ui-state-green"><?php echo t('fidelity.buttons.recebido');?></a>
			<?php }else{?>
				<a class="button ui-state-red"><?php echo t('fidelity.buttons.nao_recebido');?></a>
			<?php }?>	
		</div>
	</div>
</div>
<?php endforeach;?>