<div class="titulo-secao"><p><?php echo t('menus.sensei')?></p></div>
<?php
	$current_count	= Player::getFlag('sensei_sair_count', $basePlayer->id);
	if($current_count < 1){
		$mensagem = t('sensei.confirmar_sensei');
	}else if($current_count == 1){
		$mensagem = t('sensei.confirmar_troca_sensei');
	}else if($current_count > 1){
		$mensagem = t('sensei.confirmar_sensei_vip');
	}
	$senseis = Recordset::query("SELECT * FROM sensei ORDER BY id ASC", true);
	foreach($senseis->result_array() as $sensei) {
	$player_rank_sensei 	= Recordset::query("select * from ranking_sensei WHERE id_sensei =". $sensei['id'] . " ORDER BY posicao_geral LIMIT 1")->row_array();
	
		
?>
<div class="h-combates" style="margin-bottom: 10px;">
	<div class="h-combates-div"><span class="amarelo" style="font-family:Mission Script; font-size:22px"><?php echo $sensei['nome']?></span></div>
	<div style="width: 230px; text-align: center; padding-top: 10px; font-size: 12px !important; line-height: 14px;">
		<img src="<?php echo img() ?>/layout/sensei/<?php echo $sensei['id']?>.png" class="<?php echo $basePlayer->id_sensei != $sensei['id'] ? "apagado" : "" ?>"/><br />
		<br /><span class="verde"><?php echo t('sensei.melhor_aluno') ?>:</span> <?php echo $player_rank_sensei ? $player_rank_sensei['nome'] : "-" ?> <br />
		<span class="laranja"><?php echo t('sensei.qtd_alunos') ?>:</span> <?php echo $basePlayer->sensei_count($sensei['id'])->total?> <br /><br />
		<?php //if($_SESSION['universal']){?>
		<?php
			if($basePlayer->id_sensei != $sensei['id']){
				if($sensei['vip'] || $sensei['coin'] || $sensei['ryou']){
					if($sensei['vip'] && !$sensei['coin'] && !$sensei['ryou']){
						if(!$basePlayer->getAttribute('vip')){
							echo '<a href="#" class="button">'. t('sensei.ser_vip').'</a>';
						}else{
		?>					
						<a onclick="ActiveSensei(<?php echo $sensei['id']?>, '<?php echo $mensagem ?>')" class="button"><?php echo t('sensei.virar_aluno') ?></a>
		<?php	
            			}	
					}else if(!$sensei['vip'] && $sensei['coin'] && !$sensei['ryou']){
						if(!$basePlayer->sensei($sensei['id'])){
							echo '<a onclick="BuySensei('. $sensei['id'].')" class="button">'. $sensei['coin'] .' '. t('sensei.creditos').'</a>';
						}else{
		?>				
						<a onclick="ActiveSensei(<?php echo $sensei['id']?>, '<?php echo $mensagem ?>')" class="button"><?php echo t('sensei.virar_aluno') ?></a>
        <?php                    
						}
					
					}else if(!$sensei['vip'] && !$sensei['coin'] && $sensei['ryou']){	
						if(!$basePlayer->sensei($sensei['id'])){
							echo '<a onclick="BuySensei('. $sensei['id'].')" class="button">'. $sensei['ryou'].' Ryous</a>';
						}else{
		?>					
						<a onclick="ActiveSensei(<?php echo $sensei['id']?>, '<?php echo $mensagem ?>')" class="button"><?php echo t('sensei.virar_aluno') ?></a>
        <?php                
						}
					}
				
				}else{
			?>		
				<a onclick="ActiveSensei(<?php echo $sensei['id']?>, '<?php echo $mensagem ?>')" class="button"><?php echo t('sensei.virar_aluno') ?></a>
			<?php	
                }
			}else{
				?>
				<a class="button ui-state-green"><?php echo t('sensei.sensei_atual') ?></a>
                <?php
			}
			?>

        <?php
			//}
		?>
		
	</div>
</div>
<?php 
	}	
?>
