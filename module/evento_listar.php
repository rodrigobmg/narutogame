<?php
	$membros	= 0;
	if($basePlayer->dono_equipe || $basePlayer->sub_equipe) {
		$membros	= Recordset::query('SELECT membros FROM equipe WHERE id=' . $basePlayer->id_equipe)->row()->membros;
		$players	= Recordset::query('SELECT GROUP_CONCAT(id) AS players FROM player WHERE id_equipe=' . $basePlayer->id_equipe)->row()->players;
	}
?>
<script type="text/javascript">
	function doAceitaEvento(i) {
		$.ajax({
			url: "?acao=evento_aceitar",
			type: "post",
			data: {id: i},
			dataType: 'script'
		});
		
		$("#cnBase").html("<?php echo t('evento4.e4')?>");
	}
</script>
<div class="titulo-secao"><p><?php echo t('evento4.e1')?></p></div>
<div id="cnBase" class="direita">
<?php msg(1,''.t('evento_listar.e1').'',''.t('evento_listar.e2').''); ?>
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "2775961778";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Equipe -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br />
<br />
		<table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="305" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nom_desc')?></b></td>
                  <td width="155" align="center"><b style="color:#FFFFFF"><?php echo t('geral.data_horario')?></b></td>
				  <td width="190" align="center"><b style="color:#FFFFFF"><?php echo t('geral.requerimentos')?> / <?php echo t('geral.premio')?></b></td>
                  <td width="90" align="center"></td>
                </tr>
            </table></td>
          </tr>
      </table>
        <table width="730" border="0" cellpadding="0" cellspacing="0">
	<?php 
		$qEventos	= Recordset::query("SELECT * FROM evento WHERE removido=0 AND finalizado=0 AND historia=0 and global=0 ORDER BY dt_inicio ASC"); 
		$c			= 0;
		$cn			= 0;
	?>
	<?php if(!$qEventos->num_rows): ?>
		<tr><td><?php echo t('evento_listar.e3')?></td></tr>
	<?php endif; ?>
	<?php foreach($qEventos->result_array() as $rEvento): 
			$cor		= ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
			$qItem		= Recordset::query("SELECT nome_".Locale::get()." AS nome FROM item WHERE id = " . $rEvento['id_item'], true)->row_array();
    		
    		$total_npc	= Recordset::query('SELECT COUNT(id) AS total FROM evento_npc_evento WHERE id_evento=' . $rEvento['id'])->row_array();
    		
    		if($basePlayer->dono_equipe || $basePlayer->sub_equipe) {
	    		$qNPCs	= new Recordset("SELECT SUM(CASE WHEN morto=1 THEN 1 ELSE 0 END) AS total_morto, COUNT(id) AS total FROM evento_npc_equipe WHERE id_evento=" . $rEvento['id'] . " AND id_equipe=" . $basePlayer->id_equipe);
	    		$rNPCs	= $qNPCs->row_array();
	    		
	    		$conc	= Recordset::query('SELECT id_player FROM evento_player WHERE id_player IN(' . $players .') AND id_evento=' . $rEvento['id'])->num_rows;
    		} else {
    			$rNPCs	= array('total' => 0, 'total_morto' => 0);
    			$conc	= false;
    		}
    		
    		$participado	= $rNPCs['total'] ? true : false;
    		$vitoria		= $rNPCs['total_morto'] == $total_npc['total'] ? true : false;
    ?>
        <tr <?php echo $cor; ?>>
			<td style="padding: 12px 0 12px 0;" width="305">
				<?php if($basePlayer->id_evento == $rEvento['id']): ?>
					<img src="<?php echo img('layout/icon/bookmark.png') ?>" alt="<?php echo t('evento_listar.e4')?>" width="16"/>
				<?php else: ?>
					<?php if($participado): ?>
						<?php if($vitoria): ?>
							<img src="<?php echo img('layout/icon/button_ok.png') ?>" alt="<?php echo t('evento_listar.e5')?>" width="24"/>				
						<?php else: ?>
							<img src="<?php echo img('layout/icon/button_cancel.png') ?>" alt="<?php echo t('evento_listar.e6')?>" width="24"/>						
						<?php endif; ?>
					<?php endif; ?>
				<?php endif; ?>
				<span class="amarelo" style="font-size: 14px"><?php echo $rEvento['nome_'. Locale::get()] ?></span><br />
            <br /><?php echo $rEvento['descricao_'. Locale::get()] ?>
            </td>
			<td width="155">
				 <?php echo t('geral.de')?> <span class="verde"><?php echo date("d/m/Y H:i", strtotime($rEvento['dt_inicio'])) ?></span><br />
				 <?php echo t('geral.ate')?> <span class="laranja"><?php echo date("d/m/Y H:i", strtotime($rEvento['dt_fim'])) ?></span>
			</td>
			<td  width="190">
				<img src="<?php echo img('layout/requer.gif') ?>" id="requerimentos-premios-<?php echo $rEvento['id']?>" style="cursor: pointer" />
				
				<?php 
					$premio = "
					<b class='laranja'>".t('geral.requerimentos')."</b><br /><br />
					". ($membros == 4 ? " <span class='verde'>- ".t('equipe.e1')."</span>" : "<span class='vermelho'> - ".t('equipe.e1')."</span>") ."<br />
					". ($basePlayer->dono_equipe || $basePlayer->sub_equipe ? "<span class='verde'> - ".t('equipe.e2')."</span>":"<span class='vermelho'> - ".t('equipe.e2')."</span>")."<br />
					". ($basePlayer->id_graduacao > 2 ? "<span class='verde'> - ".t('equipe.e3')."</span>":"<span class='vermelho'> - ".t('equipe.e3')."</span>")."<br />
					". (!$conc ? "<span class='verde'> - ".t('equipe.e4')."</span>":"<span class='vermelho'> - ".t('equipe.e4')."</span>")."<br />
					<br /><b class='verde'>".t('geral.premios')."</b><br /><br />
					RY$: ". $rEvento['ryou'] ."<br />
					Exp: ". $rEvento['exp'] ."<br />
                	 ". t('geral.pontos_treino') .":  ". $rEvento['treino'] ."<br />
                	Ramens:  ". $rEvento['qtd'] . " " . $qItem['nome'] ."<br />";
				?>
				<?php echo generic_tooltip('requerimentos-premios-'.$rEvento['id'], $premio )?>
				
			</td>
			<td width="90">
					<?php if(!$participado): ?>
						<?php if(!$conc && $membros == 4 && ($basePlayer->dono_equipe || $basePlayer->sub_equipe) && strtotime($rEvento['dt_inicio']) <= strtotime("+0 minute") && !$basePlayer->id_evento && $rEvento['ativo'] && strtotime($rEvento['dt_fim']) > strtotime("+0 minute")): ?>
							<?php if($basePlayer->id_graduacao > 2) {?>
								<a class="button" onclick="doAceitaEvento('<?php echo encode($rEvento['id']) ?>')"><?php echo t('botoes.aceitar')?></a>
							<?php } ?> 	
						<?php endif; ?>
					<?php endif; ?>
					<a class="button" onclick="location.href='?secao=evento_detalhe&id=<?php echo encode($rEvento['id']) ?>'"><?php echo t('botoes.detalhes')?></a>
			</td>
		</tr>
        <tr height="4"></tr>
	<?php endforeach; ?>
</table>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-9048204353030493";
/* NG - Evento Equipe */
google_ad_slot = "9506366193";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>