<div class="titulo-secao"><p><?php echo t('menus.objetivos')?></p></div>
<style>
	.barra_exp3{
		margin: auto;
	}
</style>
<br>
<?php
	msg(2,'Objetivos Diários, Semanais e Globais','A cada dia um novo objetivo diário ( Acumula no máximo 3.)<br>Todo domindo um novo objetivo semanal ( Acumula no máximo 3.)<br>Objetivos Globais (Em breve)');
?>
<br>
<?php
	if(isset($_GET['sucess']) && $_GET['sucess'] && isset($_GET['sucess']) && isset($_GET['id'])) {
		$premio = Recordset::query("select 
										   nome_br,
										   nome_en
									from
									loteria_premio
									WHERE ID = (select loteria_premio_id from missoes_diarias where id = ".addslashes($_GET['id']).")")->result_array();
		msg(4,'Você ganhou o seguinte prêmio',$premio[0]['nome_'. Locale::get()]);
	}
?>
<table border="0" cellpadding="0" cellspacing="0" align="center" class="with-n-tabs"  id="tb100" data-auto-default="1">
	<tr>
		<td><a class="button" rel="#diarios">Diários</a></td>
        <td width="20"></td>
		<td><a class="button" rel="#semanais">Semanais</a></td>
        <td width="20"></td>
        <td><a class="button" rel="#globais">Globais</a></td>
        <td width="20"></td>
	</tr>
</table>

<?php
	for($i=1; $i <=3; $i++){
		if($i==1){
			$periodo = 'Diário';
			$id="diarios";
		}elseif($i==2){
			$periodo = 'Semanal';
			$id="semanais";
		}else{
			$periodo = 'Global';
			$id="globais";
		}
		$objetivos = Recordset::query(" select 
										a.qtd qtd_real,
										b.qtd qtd_feita,
										a.nome_br,
										a.nome_en,
										a.id,
										b.id id_player_missao,
										(SELECT nome_br FROM loteria_premio WHERE id=a.loteria_premio_id) premio
										from
										missoes_diarias a
										join player_missao_diarias b  on a.id = b.id_missao_diaria
										WHERE periodo = '". $periodo ."' and completo = 0 and id_player = ".$basePlayer->id);
?>
	<div id="<?php echo $id?>">
	<?php			
		foreach($objetivos->result_array() as $objetivo) {	

	?>
	<div class="h-combates" style="margin-bottom: 10px;">
		<div class="h-combates-div"><span class="amarelo" style="font-family:Mission Script; font-size:20px"><?php echo $objetivo['nome_'. Locale::get()]?></span></div>
		<div style="width: 230px; text-align: center; padding-top: 10px; font-size: 12px !important; line-height: 14px;">
			<br>
			<b class="verde">Prêmio: <?php echo $objetivo['premio']?></b>
			<br><br>
			<?php barra_exp3($objetivo['qtd_feita'], $objetivo['qtd_real'], 132,  $objetivo['qtd_feita'] . ' ' . t('geral.de') . ' ' . $objetivo['qtd_real'], "#2C531D", "#537F3D", 1); ?><br>
			<?php
				if($objetivo['qtd_feita'] >= $objetivo['qtd_real']){
			?>		
				<a onclick="ObjetivoPremio(<?php echo $objetivo['id']?>, <?php echo $objetivo['id_player_missao']?>)" class="button">Receber recompensa!</a>
			<?php
				}else{
			?>
				<a class="button ui-state-red">Receber recompensa!</a>			

			<?php		
				}
			?>
		</div>
	</div>
	<?php 
		}
?>
	</div>
<?php		
	}		
?>
