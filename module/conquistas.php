<?php
	arch_check();
?>
<style>
.d-conquista-grupo {
	display: none; 
	width: 698px; 
	border: solid 1px #21170d; 
	padding: 10px; 
	padding-bottom: 0px; 
	margin-left: 16px; 
	position: relative;
	z-index: 1;
}

.grupoConquista {
	display: none; 
	clear: left; 
	padding-top: 10px;
}

.conquista-grupo-titulo {
	position: relative;
}

</style>
<script type="text/javascript">
	$( document ).ready(function() {

		$(".conquista-grupo-titulo").mouseover(function () {
			$(".t", $(this)).show();
		});
	
		$(".conquista-grupo-titulo").mouseover(function () {
			$(".tt", $(this)).show();
		})
			.on("mouseout", function () {
			$(".tt", $(this)).hide();
		});
	
		
	});	
	function abaConquista(i) {
		$(".grupoConquista").hide();
		$("#cGrupo_" + i).show();
	}
	function conquistaDetalhe(i) {
		$("#d-conquista-grupo-" + i).toggleClass("show");
		$("#d-conquista-grupo-" + i + " .borda"); //.bg(10);
	}
</script>

<?php
	//$i = $basePlayer->getVIPItem(array(20185,20186,20187));
	//if(is_numeric($_GET['id_player']) && $basePlayer->hasItem(array(20185,20186,20187)) && ($i['uso'] < $i['vezes'])){
		
	if(isset($_GET['id_player']) && is_numeric($_GET['id_player']) && $basePlayer->vip){	
		$id_player = $_GET['id_player'];
	}else{
		$id_player = $basePlayer->id;	
	}

?>
<div class="titulo-secao"><p><?php echo t('conquistas.c1')?></p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "1078902572";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Conquistas -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/><br/>
<?php 
$qTipo = Recordset::query("SELECT * FROM conquista_tipo order by ordem", true);
$_tabs_left = 5 - $qTipo->num_rows;
$fSel		= 0;

?>
<div style="width:730px; clear:left; height:85px;"  class="with-n-tabs" id="conquista-tabs" data-auto-default="1">
		<?php
            foreach($qTipo->result_array() as $rTipo) {
                if(!$fSel) $fSel = $rTipo['id'];
        ?>
 	<div style="width: 144px; float:left; padding-top: 10px">
			<a class="button" style="width: 140px;" rel="#cGrupo_<?php echo $rTipo['id'] ?>"><?php echo $rTipo['nome_'. Locale::get()] ?></a>
	</div>
<?php	}?>
</div>
<?php 
	foreach($qTipo->result_array() as $rTipo):
?> 
		<div id="cGrupo_<?php echo $rTipo['id'] ?>" class="grupoConquista">
        <?php
			$q		= Recordset::query("SELECT * FROM conquista_grupo WHERE id_conquista_tipo = " . $rTipo['id'] ." ORDER BY ordem", true);
			$ccc	= 0;
			
			foreach($q->result_array() as $r):
		?>
        <?php
        	$qGrupoCompleto = Recordset::query("SELECT id FROM conquista_grupo_item WHERE id_player=" . $id_player . " AND id_conquista_grupo=" . $r['id']);
		?>
        <table border="0" width="730" onclick="javascript:conquistaDetalhe(<?php echo $r['id']?>)" class="conquista-grupo <?php echo $qGrupoCompleto->num_rows ? "conquista-grupo-c" : "conquista-grupo-i" ?>" style="z-index: 5; cursor: pointer">
            <tr>
            	<td width="160" align="center">
                </td>
            	<td align="center" class="conquista-grupo-titulo">
                    <strong style="font-size: 18px">
                    	<?php if ($r['id_graduacao']): ?>
                    		<?php echo t('graduacoes.' . $basePlayer->id_vila . '.' . $r['id_graduacao']) ?>
                    	<?php else: ?>
							<?php echo $r['nome_'. Locale::get()] ?>
                    	<?php endif ?>
                    </strong>
                    <div class="tt">
                    <strong><?php echo t('geral.recompensas')?>:</strong>
                    <ul>
                    <?php
                        $msg = array();
                        
                        // Itens
                        if($r['id_item']) {
                            $rItem = Recordset::query("SELECT nome_br, nome_en FROM item WHERE id=" . $r['id_item'], true)->row_array();
                            $msg[] = "<li>" . $r['mul'] . "x " . $rItem['nome_'. Locale::get()] . "</li>";
                        }
                        
                        // Ryou
                        if($r['ryou']) {
                            $msg[] = "<li>" . $r['ryou'] . " Ryous" . "</li>";
                        }
                    
                        // Exp
                        if($r['exp']) {
                            $msg[] = "<li>" . $r['exp'] . " ". t('conquistas.c2')."</li>";
                        }
                    
                        // Ene
                        if($r['ene']) {
                            $msg[] = "<li>" . $r['ene'] . " ". t('conquistas.c3')."" . "</li>";
                        }
                    
                        // Int
                        if($r['inte']) {
                            $msg[] = "<li>" . $r['inte'] . " ". t('conquistas.c4')."" . "</li>";
                        }
						
						 // Int
                        if($r['res']) {
                            $msg[] = "<li>" . $r['res'] . " ". t('conquistas.c5')."" . "</li>";
                        }
                    
                        // For
                        if($r['forc']) {
                            $msg[] = "<li>" . $r['forc'] . " ". t('conquistas.c6')."" . "</li>";
                        }
                    
                        // Agi
                        if($r['agi']) {
                            $msg[] = "<li>" . $r['agi'] . " ". t('conquistas.c7')."" . "</li>";
                        }
                    
                        // Con
                        if($r['con']) {
                            $msg[] = "<li>" . $r['con'] . " ". t('conquistas.c8')."" . "</li>";
                        }
                    
                        // Tai
                        if($r['tai']) {
                            $msg[] = "<li>" . $r['tai'] . " ". t('conquistas.c9')."" . "</li>";
                        }
						// Ken
                        if($r['ken']) {
                            $msg[] = "<li>" . $r['ken'] . " ". t('conquistas.c91')."" . "</li>";
                        }
                    
                        // Nin
                        if($r['nin']) {
                            $msg[] = "<li>" . $r['nin'] . " ". t('conquistas.c10')."" . "</li>";
                        }
                    
                        // Gen
                        if($r['gen']) {
                            $msg[] = "<li>" . $r['gen'] . " ". t('conquistas.c11')."" . "</li>";
                        }
                    
                        // Coin
                        if($r['coin']) {
                            $msg[] = "<li>" . $r['coin'] . " ". t('conquistas.c12')."" . "</li>";
                        }

                        // Titulo
                        if($r['titulo_'. Locale::get()]) {
                            $msg[] = "<li>". t('conquistas.c13').": " . $r['titulo_'. Locale::get()] . "</li>";
                        }
                    
                        echo join("", $msg);
                    ?>
                    </ul>
                    </div>
                </td>
            	<td width="120" align="center">
                	<div class="conquista-pontos" align="center"><?php echo $r['pontos'] ?>pts</div>
                </td>
            </tr>
        </table>
        <?php
			if(LAYOUT_TEMPLATE=="_azul"){
				$bg_color = $qGrupoCompleto->num_rows ? "#0a1219" : "#0a1219";
			}else{
				$bg_color = $qGrupoCompleto->num_rows ? "#2b180b" : "#140b08";
			}
		?>
        <div id="d-conquista-grupo-<?php echo $r['id']?>" class="d-conquista-grupo" align="center" style="background-color: <?php echo $bg_color ?>">
		<?php
                $qCon = Recordset::query("
                    SELECT
                        a.*,
                        (SELECT qtd FROM conquista_item WHERE id_player={$id_player} AND id_conquista=a.id LIMIT 1) AS total
                    
                    FROM
                        conquista a
                    
                    WHERE
                        id_grupo=" . $r['id'] ."
					ORDER BY id
					
						");
                
                foreach($qCon->result_array() as $rCon):
            ?>
	       	<div class="borda <?php echo $rCon['total'] == $rCon['req_qtd'] ? 'conquista-c' : 'conquista-i' ?>">
            <table border="0" width="100%" class="conquista-item" cellpadding="6">
	            <tr>
    		        <th class="desc" align="left">
                        <strong style="font-size: 14px">
                        	<?php if ($rCon['is_self'] && $rCon['req_id_graduacao']): ?>
                        		<?php echo sprintf(t('conquistas.ser'), t('graduacoes.' . $basePlayer->id_vila . '.' . $rCon['req_id_graduacao'])) ?>
                        	<?php else: ?>
								<?php echo $rCon['nome_'. Locale::get()] ?>                        		
                        	<?php endif ?>
                        </strong>
                    </th>
                    <td width="310">
                    <?php if($rCon['total'] == $rCon['req_qtd']): ?>
                        <?php barra_exp3((int)$rCon['total'], $rCon['req_qtd'], 132, (int)$rCon['total'] . " / " . $rCon['req_qtd'], "#2C531D", "#537F3D", 5) ?>
                    <?php else: ?>
                        <?php barra_exp3((int)$rCon['total'], $rCon['req_qtd'], 132, (int)$rCon['total'] . " / " . $rCon['req_qtd'], "#5E090A", "#8A191B", 4) ?>
                    <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td align="left" colspan="2">
                        <strong><?php echo t('geral.requerimentos')?>:</strong>
<ul>
<?php if($rCon['is_item'] == 4): ?>
	<?php if($rCon['req_id_item']):
			$rItem = Recordset::query("SELECT nome_br, nome_en FROM loteria_premio WHERE id=" . $rCon['req_id_item'], true)->row_array();
	?>
    <li>Ganhar <?php echo $rItem['nome_'. Locale::get()] ?></li>
    <?php endif; ?>                            
<?php else: ?>
	<?php if($rCon['req_id_item']):
			$rItem = Recordset::query("SELECT nome_br, nome_en FROM item WHERE id=" . $rCon['req_id_item'], true)->row_array();
	?>
    <li><?php echo t('geral.requer')?> <?php echo $rItem['nome_'. Locale::get()] ?> x<?php echo $rCon['req_qtd'] ?></li>
    <?php endif; ?>
<?php endif; ?>
<?php if($rCon['req_id_vila']):
		$rVila = Recordset::query("SELECT nome_".Locale::get()." AS nome FROM vila WHERE id=" . $rCon['req_id_vila'], true)->row_array();
?>
<li><?php echo t('conquistas.c14')?>: <?php echo $rVila['nome'] ?></li>
<?php endif; ?>

<?php if($rCon['req_id_vila2']):
		$rVila = Recordset::query("SELECT nome_".Locale::get()." AS nome FROM vila WHERE id=" . $rCon['req_id_vila2'], true)->row_array();
?>
<li><?php echo t('conquistas.c14')?>: <?php echo $rVila['nome'] ?></li>
<?php endif; ?>
<?php if($rCon['req_tutorial']):?>
<li><?php echo t('conquistas.c55')?></li>
<?php endif; ?>
<?php if($rCon['data_inicio'] && $rCon['data_fim']):?>
De <?php echo  date("d/m/Y", strtotime($rCon['data_inicio'])) . " &agrave;s " . date("H:i:s", strtotime($rCon['data_inicio'])); ?> Até <?php echo  date("d/m/Y", strtotime($rCon['data_fim'])) . " &agrave;s " . date("H:i:s", strtotime($rCon['data_fim'])); ?>
<?php endif; ?>

<?php if($rCon['req_layout']):?>
<li><?php echo t('conquistas.c56')?></li>
<?php endif; ?>
<?php if($rCon['req_id_classe']):
		$rClasse = Recordset::query("SELECT nome FROM classe WHERE id=" . $rCon['req_id_classe'], true)->row_array();
?>
<li><?php echo t('conquistas.c15')?>: <?php echo $rClasse['nome'] ?></li>
<?php endif; ?>
<?php if($rCon['req_id_cla']):
		$rCla = Recordset::query("SELECT nome FROM cla WHERE id=" . $rCon['req_id_cla'], true)->row_array();
?>
<li><?php echo t('conquistas.c16')?>: <?php echo $rCla['nome'] ?></li>
<?php endif; ?>
<?php if($rCon['req_id_quest']):
		$rQuest = Recordset::query("SELECT nome_br, nome_en FROM quest WHERE id=" . $rCon['req_id_quest'], true)->row_array();
?>
<li><?php echo t('conquistas.c17')?>: <?php echo $rQuest['nome_'.Locale::get()] ?></li>
<?php endif; ?>
<?php if($rCon['is_quest'] > 1):
		switch($rCon['is_quest']) {
			case 2:
				$rank = "D";
			
				break;
				
			case 3:
				$rank = "C";
			
				break;
				
			case 4:
				$rank = "B";
			
				break;
				
			case 5:
				$rank = "A";
			
				break;
				
			case 6:
				$rank = "S";
			
				break;
			case 7:
				$rank = "Diária";
			
				break;
			case 8:
				$rank = "Semanal";
			
				break;
			case 9:
				$rank = "Mensal";
			
				break;					
		}
	?>
<li><?php echo $rCon['req_qtd'] ?>X <?php echo $rCon['is_quest'] > 6 ? t('geral.missoes_combate'): t('conquistas.c18') ?> <?php echo $rank ?></li>
<?php endif; ?>
<?php if($rCon['req_id_graduacao']):
		$rGrad = Recordset::query("SELECT id AS grad_id FROM graduacao WHERE id=" . $rCon['req_id_graduacao'], true)->row_array();
?>
<li><?php echo t('conquistas.c19')?>: <?php echo graduation_name($basePlayer->id_vila, $rGrad['grad_id']) ?></li>
<?php endif; ?>
<?php if($rCon['req_elemento_a'] && !$rCon['req_elemento_b'] ):
		$rEle = Recordset::query("SELECT nome FROM elemento WHERE id=" . $rCon['req_elemento_a'], true)->row_array(); 
?>
<li><?php echo t('conquistas.c20')?>: <?php echo $rEle['nome'] ?></li>
<?php endif; ?>
<?php if($rCon['req_elemento_a'] && $rCon['req_elemento_b'] ):
		$rEle	= Recordset::query("SELECT nome FROM elemento WHERE id=" . $rCon['req_elemento_a'], true)->row_array();
    	$rEleB	= Recordset::query("SELECT nome FROM elemento WHERE id=" . $rCon['req_elemento_b'], true)->row_array(); 
?>
<li><?php echo t('conquistas.c20')?>: <?php echo $rEle['nome'] ?> e <?php echo $rEleB['nome'] ?></li>
<?php endif; ?>
<?php if($rCon['req_level']): ?>
<li><?php echo t('conquistas.c21')?>: <?php echo $rCon['req_level'] ?></li>
<?php endif; ?>
<?php /*if($rCon['req_id_especializacao']):
		$rEsp = Recordset::query("SELECT nome FROM especializacao WHERE id=" . $rCon['req_id_especializacao'], true)->row_array();
?>
<li><?php echo t('conquistas.c22')?>: <?php echo $rEsp['nome'] ?></li>
<?php endif;*/ ?>
<?php if($rCon['req_id_invocacao']):
		$rInv = Recordset::query("SELECT nome_br, nome_en FROM invocacao WHERE id=" . $rCon['req_id_invocacao'], true)->row_array();
?>
<li><?php echo t('conquistas.c23')?>: <?php echo $rInv['nome_'.Locale::get()] ?></li>
<?php endif; ?>
<?php if($rCon['req_id_selo']):
		$rSelo = Recordset::query("SELECT nome_br, nome_en FROM selo WHERE id=" . $rCon['req_id_selo'], true)->row_array(); 
?>
<li><?php echo t('conquistas.c24')?>: <?php echo $rSelo['nome_'.Locale::get()] ?></li>
<?php endif; ?>
<?php if($rCon['is_player']):
        switch($rCon['is_player']) {
            case 1:
                $pMsg = t('conquistas.c25');
            
                break;
            case 2:
                $pMsg = t('conquistas.c26');
            
                break;
            case 3:
                $pMsg = t('conquistas.c27');
                
                break;
            
            case 4:
                $pMsg = t('conquistas.c28');
            
                break;
        }
?>                            
<li><?php echo t('conquistas.c29')?> <strong><?php echo $rCon['is_guerra'] ? t('conquistas.c54') : t('conquistas.c30')?></strong> <?php echo $pMsg ?> <?php echo $rCon['req_qtd'] ?> <?php echo t('conquistas.c31')?></li>
<?php endif; ?>                                
<?php if($rCon['is_npc'] && $rCon['is_npc'] != 6):
		switch($rCon['is_npc']) {
			case 1:
				$rNPC = array(
					'nome'	=> t('conquistas.c32')
				);
			
				break;
		
		    case 2:
		    case 4:
		        $rNPC = Recordset::query("SELECT nome_".Locale::get()." AS nome FROM npc WHERE id=" . $rCon['req_id_npc'], true)->row_array();
		    
		        break;
		    
		    case 3:
		        $rNPC = Recordset::query("SELECT CONCAT(a.nome, ' - ', a.nome) AS nome FROM npc_vila a JOIN local_mapa b ON b.mlocal=a.mlocal WHERE a.id=" . $rCon['req_id_npc'], true)->row_array();
		    
		        break;
			
			case 5:
			case 8:
				$rNPC = Recordset::query("SELECT nome_" . Locale::get() . " AS nome FROM evento_npc WHERE id=" . $rCon['req_id_npc'], true)->row_array();
			
				break;

			case 7:
				$rNPC = Recordset::query("SELECT nome_" . Locale::get() . " AS nome FROM evento WHERE id=" . $rCon['req_evento4'], true)->row_array();
			
				break;
		}
 ?>
<?php if($rCon['is_npc'] == 7): ?>
	<li><?php echo $rNPC['nome'] ?> finalizado <?php echo $rCon['req_qtd'] ?> <?php echo t('conquistas.c31')?></li>
<?php elseif($rCon['is_npc'] == 8 ): ?>
	<li><?php echo t('conquistas.c52')?> <?php echo $rNPC['nome'] ?> <?php echo $rCon['req_qtd'] ?> <?php echo t('conquistas.c31')?></li>
<?php elseif($rCon['is_npc'] == 9 ): ?>
	<li><?php echo t('conquistas.c57')?> <?php echo $rCon['req_qtd'] ?> <?php echo t('conquistas.c31')?></li>	
<?php elseif($rCon['is_npc'] == 5 ): ?>
	<li><?php echo t('conquistas.c53')?> <?php echo $rNPC['nome'] ?> <?php echo $rCon['req_qtd'] ?> <?php echo t('conquistas.c31')?></li>	
<?php else: ?>
	<li><?php echo t('conquistas.c32')?> <?php echo $rNPC['nome'] ?> <?php echo $rCon['req_qtd'] ?> <?php echo t('conquistas.c31')?></li>
<?php endif ?>
<?php endif; ?>
<?php if($rCon['req_tai']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_tai'] ?> <?php echo t('conquistas.c9')?></li>
<?php endif; ?>
<?php if($rCon['req_ken']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_ken'] ?> <?php echo t('conquistas.c91')?></li>
<?php endif; ?>
<?php if($rCon['req_nin']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_nin'] ?> <?php echo t('conquistas.c10')?></li>
<?php endif; ?>
<?php if($rCon['req_gen']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_gen'] ?> <?php echo t('conquistas.c11')?></li>
<?php endif; ?>
<?php if($rCon['req_ene']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_ene'] ?> <?php echo t('conquistas.c3')?></li>
<?php endif; ?>
<?php if($rCon['req_agi']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_agi'] ?> <?php echo t('conquistas.c7')?></li>
<?php endif; ?>
<?php if($rCon['req_con']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_con'] ?> <?php echo t('conquistas.c8')?></li>
<?php endif; ?>
<?php if($rCon['req_for']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_for'] ?> <?php echo t('conquistas.c6')?></li>
<?php endif; ?>                            
<?php if($rCon['req_int']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_int'] ?> <?php echo t('conquistas.c4')?></li>
<?php endif; ?>
<?php if($rCon['req_res']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_res'] ?> <?php echo t('conquistas.c5')?></li>
<?php endif; ?>
<?php if($rCon['req_ryou']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_ryou'] ?> RYOUs</li>
<?php endif; ?>
<?php if($rCon['req_hp']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_hp'] ?> <?php echo t('conquistas.c35')?></li>
<?php endif; ?>
<?php if($rCon['req_sp']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_sp'] ?> <?php echo t('conquistas.c36')?></li>
<?php endif; ?>
<?php if($rCon['req_sta']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_sta'] ?> <?php echo t('conquistas.c37')?></li>
<?php endif; ?>
<?php if($rCon['req_coin']): ?>
<li><?php echo t('geral.requer')?> <?php echo $rCon['req_coin'] ?> <?php echo t('conquistas.c38')?></li>
<?php endif; ?>
<?php if($rCon['req_evento4'] && $rCon['is_npc'] != 7): ?>
<li><?php echo t('conquistas.c34')?> <?php echo Recordset::query('SELECT nome_br, nome_en FROM evento4 WHERE id=' . $rCon['req_evento4'], true)->row()->{'nome_'.Locale::get()} ?></li>
<?php endif; ?>
</ul>
<?php if($rCon['req_torneio']): ?>
<li><?php echo t('conquistas.c39')?>: <?php echo Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM torneio WHERE id=' . $rCon['req_torneio'], true)->row()->nome ?></li>
<?php endif; ?>

<?php if($rCon['req_arena_vila']): ?>
<li><?php echo sprintf(t('conquistas.c44'), Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM arena WHERE vila_id=' . $rCon['req_arena_vila'])->row()->nome) ?></li>
<?php endif; ?>
<?php if($rCon['req_arena_players']): ?>
<li><?php echo sprintf(t('conquistas.c45'), $rCon['req_qtd']) ?></li>
<?php endif; ?>
<?php if($rCon['req_arena_total']): ?>
<li><?php echo sprintf(t('conquistas.c46'), $rCon['req_qtd']) ?></li>
<?php endif; ?>
<?php if($rCon['req_media_nivel']): ?>
<li><?php echo sprintf(t('conquistas.c47'), $rCon['req_media_nivel']) ?></li>
<?php endif; ?>
<?php if($rCon['req_nivel_equipe']): ?>
<li><?php echo sprintf(t('conquistas.c48'), $rCon['req_nivel_equipe']) ?></li>
<?php endif; ?>
<?php if($rCon['req_vila_equipe']): ?>
<li><?php echo sprintf(t('conquistas.c49'), Recordset::query('SELECT nome_' . Locale::get() . ' AS name FROM vila WHERE id=' . $rCon['req_vila_equipe'])->row()->name) ?></li>
<?php endif; ?>
<?php if($rCon['req_bingo_book']): ?>
<li><?php echo t('conquistas.c50') ?></li>
<?php endif; ?>
<?php if($rCon['req_bingo_book_guild']): ?>
<li><?php echo t('conquistas.c51') ?></li>
<?php endif; ?>
<?php if($rCon['req_id_item_tipo']): ?>
<li><?php echo sprintf(t('conquistas.item_tipo'), Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM item_tipo WHERE id=' . $rCon['req_id_item_tipo'])->row()->nome) ?></li>
<?php endif; ?>
<?php if($rCon['req_id_profissao']): ?>
<li>Requer uso da profissão: <?php Recordset::query('SELECT nome_' . Locale::get() . ' AS nome FROM profissao WHERE id=' . $rCon['req_id_profissao'])->row()->nome ?></li>
<?php endif; ?>

<?php if ($rCon['is_theme']): ?>
	<?php if ($rCon['req_id_npc']): ?>
		<li><?php echo sprintf(t('conquistas.tema_ultimate'), $rCon['req_qtd']) ?></li>
	<?php else: ?>
		<li><?php echo sprintf(t('conquistas.tema_vip'), $rCon['req_qtd']) ?></li>
	<?php endif ?>
<?php endif ?>

</ul>

                    </td>
                </tr>
            </table>
	        </div>
            <br />
			<?php endforeach; ?>
         </div><br />
        <?php endforeach; ?>
	</div>
<?php endforeach; ?>
</form>
<script type="text/javascript">
	abaConquista(1);
</script>