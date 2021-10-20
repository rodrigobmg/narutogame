<?php
	if(isset($_GET['premio']) && $_GET['premio']){
		echo '<div class="titulo-secao"><p>Prêmios do Pacote</p></div>';
		$premios = Recordset::query("SELECT * FROM packs_log WHERE id=". $_GET['premio'])->row_array();
		if($premios){
			$premio 	= explode(",", $premios['premios']);
			$tipo		= explode(",", $premios['tipos']);
			
			for($i = 1; $i <= count($premio); $i++){
?>
            <div class="pack-rewards">
                <?php 
					if($tipo[$i - 1]!=10 && $tipo[$i - 1]!=10 && $tipo[$i - 1]!=11 && $tipo[$i - 1]!=13 && $tipo[$i - 1]!=14 && $tipo[$i - 1]!=15 && $tipo[$i - 1]!=29 && $tipo[$i - 1]!=44 && $tipo[$i - 1]!=45){
						
						$item = $basePlayer->getItem($premio[$i - 1], true);
                ?>
                        <div class="pack-name"><b><?php echo $item->nome ?></b></div>
                        <div class="item" id="<?php echo $item->id ?>">
                           	<?php if($tipo[$i - 1] == 2):?>
                            	<img id="" src="<?php echo img('layout/equipamentos/2/' . $item->id.'.png') ?>" title="<?php echo $item->nome ?>" name="<?php echo $item->nome ?>" />
                            <?php else: ?>
                           		 <img id="" src="<?php echo img('layout/' . $item->imagem) ?>" title="<?php echo $item->nome ?>" name="<?php echo $item->nome ?>" />
                            <?php endif;?>
                            <?php echo bonus_tooltip($item->id, $item) ?>
                        </div>
                        
                <?php 
					}else if($tipo[$i - 1]==44){
						$player_item = Recordset::query("SELECT * FROM player_item WHERE id=". $premio[$i - 1])->row_array();
				?>
                		<div class="pack-name"><b>Fragmento</b></div>
                        <img src="<?php echo img('layout/criacao/pequenas/'. $player_item['id_classe'] .'.png')?>" width="80"/>
                <?php 
					}else if($tipo[$i - 1]==45){
						$player_item = Recordset::query("SELECT * FROM player_classe WHERE id=". $premio[$i - 1])->row_array();
				?>
                		<div class="pack-name"><b>Personagem</b></div>
                        <img src="<?php echo img('layout/criacao/pequenas/'. $player_item['id_classe'] .'.png')?>" width="80"/>
                <?php 
					}else if($tipo[$i - 1]==10 || $tipo[$i - 1]==11 || $tipo[$i - 1]==13 || $tipo[$i - 1]==14 || $tipo[$i - 1]==15 || $tipo[$i - 1]==29){
						$player_item = Recordset::query("SELECT * FROM player_item WHERE id=". $premio[$i - 1])->row_array();
						$player_item_atributos = Recordset::query("SELECT * FROM player_item_atributos WHERE id_player_item=". $premio[$i - 1])->row_array();
				?>
                		<div class="pack-name"><b><?php echo $player_item_atributos['nome']?></b></div>
                       
                      	<?php
							if($player_item['id_item_tipo'] == 10){
						?>
							<img src="<?php echo img('layout/equipamentos/'. $player_item['id_item_tipo'] .'/'. $player_item['raridade'][0].'-'.$basePlayer->id_vila.'-'.$player_item_atributos['imagem'].'.png')?>" width="100"/>
						<?php
							}else{
						?>		
							<img src="<?php echo img('layout/equipamentos/'. $player_item['id_item_tipo'] .'/'. $player_item['raridade'][0].'-'.$player_item_atributos['imagem'].'.png')?>" width="100"/>
						<?php
							}
						?>
                        
                <?php		
                    } 
                ?>    
            </div>		
<?php			
			}
?>
			<div class="break"></div>
			<a class='button' onclick='location.href="index.php?secao=packs";'>Voltar</a>			
<?php            
		}
	}else{
?>
    <div class="titulo-secao"><p>Pacotes</p></div><br/>
    <table border="0" cellpadding="0" cellspacing="0" align="center" class="with-n-tabs"  id="tb100" data-auto-default="1">
		<tr>
			<td><a class="button packs-personagens" rel="#packs-personagens">Personagens</a></td>
			<td width="20"></td>
			<td><a class="button packs-equipamentos" rel="#packs-equipamentos">Equipamentos</a></td>
			<td width="20"></td>
			<td><a class="button packs-armas" rel="#packs-armas">Armas</a></td>
			<td width="20"></td>
			<td><a class="button packs-especiais" rel="#packs-especiais">Especiais</a></td>
			<td width="20"></td>
			<td><a class="button packs-esgotados" rel="#packs-esgotados">Esgotados</a></td>
		</tr>
	</table>
   <br/>
   	<?php 
			$packs_tipos = Recordset::query('select distinct(tipo) from packs
											union
											select "esgotados" from packs');
	?>
   	<?php foreach($packs_tipos->result_array() as $pack_tipo):?>
   			<?php
				$ativo = $_SESSION['universal'] ? "" : "AND ativo = 1";
				if($pack_tipo['tipo'] != 'esgotados'){
					$packs	= Recordset::query('SELECT * FROM packs WHERE (NOW() BETWEEN data_ini AND data_fim || data_ini is NULL) '. $ativo .' AND tipo="'.$pack_tipo['tipo'].'"');
				}else{
					$packs	= Recordset::query('SELECT * FROM packs WHERE (NOW() BETWEEN data_ini AND data_fim || data_ini is NULL) '. $ativo);
				}
			?>
		<div id="packs-<?php echo $pack_tipo['tipo'] ?>">
			<?php foreach($packs->result_array() as $pack):?>
				<?php if($pack_tipo['tipo'] != "esgotados"): ?>
					<?php if($pack['limite']):?>
						<?php if($basePlayer->packs_limited($pack['id']) >= $pack['limite']):?>
							<?php continue;?>
						<?php endif;?>
					<?php endif;?>
				<?php else: ?>
					<?php if($pack['limite']):?>
						<?php if($basePlayer->packs_limited($pack['id']) < $pack['limite']):?>
							<?php continue;?>
						<?php endif;?>
					<?php else:?>
						<?php continue;?>
					<?php endif;?>
				<?php endif;?>
				<div class="packs" id="pack-<?php echo $pack['id']?>" style="background-image:url(images/layout/packs/packs/<?php echo $pack['id']?>.png)">
					<div class="pack-topo">	
						<div class="pack-items">
							<b><?php echo $pack['qtd']?></b><br /><span>Items</span>
						</div>
						<?php if($pack['limite']):?>
							<div class="pack-limite">
								<b><?php echo $pack['limite']?></b><br /><span>Limite</span>
							</div>
						<?php endif?>
					</div> 
					 <?php echo generic_tooltip("pack-". $pack['id'], nl2br($pack['descricao_'. Locale::get()])); ?> 
					<div class="pack-bottom">
						<b><?php echo $pack['nome_'.Locale::get()] ?></b>
						<?php if($pack['data_ini']):?>
							<div class="pack-clock" id="clock-<?php echo $pack['id']?>"></div>
							<script type="text/javascript">
								$(document).ready(function () {
									$('#clock-<?php echo $pack['id']?>').countdown("<?php echo $pack['data_fim']?>", function(event) {
									  var totalHours = event.offset.totalDays * 24 + event.offset.hours;
									  $(this).html(event.strftime(totalHours + ' Hora %M Min %S Seg'));
									});
								});
							</script>
						<?php endif?>
					</div> 

					<div>
					<?php if($pack['limite']):?>	
						<?php if($basePlayer->packs_limited($pack['id']) < $pack['limite']):?>
							<?php if($pack['ryou']):?>
								<a class="button" onclick="BuyPacks(<?php echo $pack['id']?>, 1)"><?php echo $pack['ryou']?> Ryous</a>
							<?php endif?>
							<?php if($pack['coin']):?>
								<a class="button" onclick="BuyPacks(<?php echo $pack['id']?>, 2)"><?php echo $pack['coin']?> Créditos</a>
							<?php endif?>
							<?php if($pack['fidelidade']):?>
								<a class="button" onclick="BuyPacks(<?php echo $pack['id']?>, 3)"><?php echo $pack['fidelidade']?> Pts. Fidelidade</a>
							<?php endif?>
						<?php else:?>
							<a class="button ui-state-red">Esgotado</a>
						<?php endif?>
					<?php else:?>	
							<?php if($pack['ryou']):?>
								<a class="button" onclick="BuyPacks(<?php echo $pack['id']?>, 1)"><?php echo $pack['ryou']?> Ryous</a>
							<?php endif?>
							<?php if($pack['coin']):?>
								<a class="button" onclick="BuyPacks(<?php echo $pack['id']?>, 2)"><?php echo $pack['coin']?> Créditos</a>
							<?php endif?>
							<?php if($pack['fidelidade']):?>
								<a class="button" onclick="BuyPacks(<?php echo $pack['id']?>, 3)"><?php echo $pack['fidelidade']?> Pts. Fidelidade</a>
							<?php endif?>
					<?php endif?>			
					</div>
				</div>	
			<?php endforeach?>
		</div>
	<?php endforeach?>
<?php } ?>    
