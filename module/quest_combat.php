<div class="titulo-secao"><p><?php echo t('geral.missoes_combate')?></p></div>
<style>
.ability-speciality-box{
    box-shadow: inset 0px 0px 10px rgba(0,0,0,.7);
    border-radius: 8px;
    width: 175px;
    height: 245px;
    display: inline-block;
    text-align: center;
    padding: 15px;
    margin: 3px;
    background-color: #22140e;
}
.ability-speciality-box .name{
    font-size: 16px;
    margin-bottom: 30px;
    height: 33px;
}
</style>
<?php				
	$player_quest_combats 	= Recordset::query("SELECT * FROM player_quest_combat WHERE finished=0 AND id_player = 0 ORDER BY id_quest_combat ASC");
?>
<?php 
	foreach($player_quest_combats->result_array() as $player_quest_combat):
	$quest_combat 	= Recordset::query("SELECT * FROM quest_combat WHERE id=".$player_quest_combat['id_quest_combat'])->row();
?>
<div class="ability-speciality-box" style="width: 235px !important; height: 300px !important; ">
	<div>
		<div class="image">
			<img src="<?php echo 'images/layout/quest_combat/'.$quest_combat->periodo.'.png' ?>" />
		</div>
		<div class="name">
			<br /><?php echo Locale::get() == "br" ? $quest_combat->nome_br : $quest_combat->nome_en?>
		</div>
		<div class="description" style="height: 40px !important;">
			<span class="verde" style="font-size: 14px"><?php 
				echo $quest_combat->ryou ? $quest_combat->ryou. ' ryous <br/>' : '';
				echo $quest_combat->credits ? $quest_combat->credits. ' ' . t('geral.creditos').' <br/>' : '';
			?></span>
		</div>
		<div class="details">
		<?php
			$rank_combat = Recordset::query("SELECT * FROM player_batalhas_status ORDER BY ".$quest_combat->tipo." DESC LIMIT 1")->result_array();
			$rank_ninja	 = Recordset::query("SELECT * FROM ranking WHERE id_player=".$rank_combat[0]['id_player'])->result_array();
		?>
		<?php if($rank_ninja){?>
			<img src="<?php echo img() ?>/layout/dojo/<?php echo $rank_ninja[0]['id_classe']?>.png" width="126" height="44" /><br />
		<?php
			echo player_online($rank_ninja[0]['id_player'], true) .'<b style="font-size:13px">'. $rank_ninja[0]['nome'] .'</b><br /><br />';
			echo "<span class='laranja'>". $rank_combat[0][$quest_combat->tipo] .' '. (Locale::get() == "br" ? $quest_combat->status_br : $quest_combat->status_en) ."</span>";
		?>
		<?php }?>
		</div>
	</div>
</div>
<?php endforeach;?>
<div class="clearfix"></div><br/>
<table border="0" cellpadding="0" cellspacing="0" align="center" class="with-n-tabs"  id="tb100" data-auto-default="1">
	<tr>
		<td><a class="button" rel="#diario"><?php echo t('geral.missoes_diarias')?></a></td>
        <td width="20"></td>
		<td><a class="button" rel="#semanal"><?php echo t('geral.missoes_semanais')?></a></td>
        <td width="20"></td>
		<td><a class="button" rel="#mensal"><?php echo t('geral.missoes_mensais')?></a></td>
        <td width="20"></td>
	</tr>
</table>
<br />
<br />
<?php				
	$player_quest_combat_periodos 	= Recordset::query("SELECT * FROM player_quest_combat WHERE finished=1 GROUP BY periodo");
?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
	<td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
	  <tr>
		<td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.personagem'); ?></b></td>
		<td width="50" align="center"><b style="color:#FFFFFF">Level</b></td>
		<td width="90" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.vila'); ?></b></td>
		<td width="160" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.nome'); ?> da Missão</b></td>
		<td width="130" align="center"><b style="color:#FFFFFF">Data da Missão</b></td>
		
	  </tr>
	</table></td>
  </tr>
</table>
<?php foreach($player_quest_combat_periodos->result_array() as $player_quest_combat_periodo): ?>
	<table width="730" border="0" cellpadding="2" cellspacing="0" id="<?php echo $player_quest_combat_periodo['periodo']?>">
	<?php 
		$cn	= 0;
		$player_quest_combat_finisheds =  Recordset::query("SELECT * FROM player_quest_combat WHERE finished=1 AND periodo='".$player_quest_combat_periodo['periodo']."' ORDER BY data_ins DESC");
		foreach($player_quest_combat_finisheds->result_array() as $player_quest_combat_finished):
        $cor = ++$cn % 2 ? "#413625" : "#251a13";	
		$rank_ninja_atual	 = Recordset::query("SELECT * FROM ranking WHERE id_player=".$player_quest_combat_finished['id_player'])->result_array();
		$quest_combat 		 = Recordset::query("SELECT * FROM quest_combat WHERE id=".$player_quest_combat_finished['id_quest_combat'])->row();
	?>
		<?php
			if(!$rank_ninja_atual){
				continue;	
			}
		?>
		<tr bgcolor="<?php echo $cor ?>">
			<td width="130" align="center">
				<img src="<?php echo img() ?>/layout/dojo/<?php echo $rank_ninja_atual[0]['id_classe'] ?>.png" width="126" height="44" /><br />
				<a class="linkTopo" style="font-size: 13px;" href="javascript:void(0)" onclick="playerProfile('<?php echo urlencode(encode($rank_ninja_atual[0]['id_player'])) ?>')"><?php echo player_online($rank_ninja_atual[0]['id_player'], true)?><?php echo $rank_ninja_atual[0]['nome'] ?></a>
			<br /><?php echo $rank_ninja_atual[0]['titulo_' . Locale::get()] ?>
			</td>
			<td width="50" align="center"><p><?php echo $rank_ninja_atual[0]['level'] ?></p></td>
			<td width="90" align="center"><img src="<?php echo img() ?>layout/bandanas/<?php echo $rank_ninja_atual[0]['id_vila'] ?>.png" width="48" height="24" /></td>
			<td width="160" height="34" align="center" nowrap="nowrap"><b style="font-size:13px"><?php echo Locale::get() == "br" ? $quest_combat->nome_br : $quest_combat->nome_en?></b></td>
			<td width="130" align="center"><?php echo date("d/m/Y", strtotime($player_quest_combat_finished['data_ins'])) . " &agrave;s " . date("H:i:s", strtotime($player_quest_combat_finished['data_ins']));?></td>

		</tr>
		<tr height="4"></tr>
	
	
	<?php endforeach;?>
	</table>
<?php endforeach;?>