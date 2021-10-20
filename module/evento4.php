<?php
	$equipe = Recordset::query('SELECT * FROM equipe WHERE id=' . $basePlayer->getAttribute('id_equipe'))->row_array();
	
	$equipe['level']--;
?>
<script type="text/javascript">
	function doAceitaEvento(i) {
		$.ajax({
			url: "?acao=evento4_aceitar",
			type: "post",
			data: {id: i},
			dataType: 'script'
		});
		
		$("#cnBase").html("<?php echo t('evento4.e4')?>");
	}
</script>
<div class="titulo-secao"><p><?php echo t('evento4.e1')?></p></div>
<div id="cnBase" class="direita">
<?php msg(1,''.t('evento4.e2').'',''.t('evento4.e3').''); ?>
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
		  <td width="80" align="center">&nbsp;</td>
		  <td width="260" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nom_desc')?></b></td>
		  <td width="160" align="center"><b style="color:#FFFFFF"><?php echo t('geral.req_prem')?></b></td>
		  <td width="90" align="center"></td>
		</tr>
	</table></td>
  </tr>
</table>
	  
        <table width="730" border="0" cellpadding="0" cellspacing="0">
	<?php 
		$qEventos 	= Recordset::query("SELECT * FROM evento4 ORDER BY ordem", true); 
		$c			= 0;
	?>
	<?php foreach($qEventos->result_array() as $rEvento): 
			$cor	 = ++$c % 2 ? "class='cor_sim'" : "class='cor_nao'";
			
			$ja_concluido			= Recordset::query('SELECT * FROM equipe_evento4 WHERE id_equipe=' . $basePlayer->getAttribute('id_equipe') . ' AND id_evento4=' . $rEvento['id'])->num_rows;
			$lvl_color				= $equipe['level'] >= $rEvento['req_equipe_level'] ? "style='text-decoration: line-through'" : "style='color: #fd2a2a'";	

	?>
        <tr <?php echo $cor ?>>
			<td width="80">
				<?php if($ja_concluido): ?>
					<img src="<?php echo img('layout/button_ok.png') ?>" alt="<?php echo t('evento4.e5')?>"/>				
				<?php else: ?>
					<img src="<?php echo img('layout/button_cancel.png') ?>" alt="<?php echo t('evento4.e6')?>"/>				
				<?php endif; ?>    
            </td>
            <td style="padding:15px 0 15px 0;" width="260">
	            <span class="amarelo" style="font-size: 14px"><?php echo $rEvento['nome_'. Locale::get()] ?><br />
	            <span class="amarelo_claro" style="font-size:11px"><?php echo t('evento4.e7')?>:
				<?php
					switch($rEvento['dificuldade']) {
						case 'normal':
							echo "<span class='verde'>". t('evento4.e8') ."</span>";
						
							break;

						case 'hard':
							echo "<span class='laranja'>".  t('evento4.e9') ."</span>";
						
							break;

						case 'ogro':
							echo "<span style='color:#FF0000;'>". t('evento4.e10') ."</span>";
						
							break;
					}
				?>	            
	            </span></span><br /><br /> <?php echo $rEvento['descricao_'. Locale::get()] ?>
	            </td>
			<td width="160">
				<img id="i-ev4-<?php echo $rEvento['id'] ?>" src="<?php echo img('layout/requer.gif') ?>"  style="cursor:pointer" />
				<?php ob_start(); ?>
					<b><?php echo t('geral.requerimentos')?></b><br /><br />
					<?php echo t('evento4.e11')?>:
					<ul>
						<li <?php echo $lvl_color ?>>&bull; Level: <?php echo $rEvento['req_equipe_level'] ?></li>
					</ul>
					<b><?php echo t('geral.premio')?></b><br />
					<ul>
						<li>RY$: <?php echo $rEvento['ryou'] ?></li>
						<li>Exp: <?php echo $rEvento['exp'] ?></li>
						<li><?php echo t('geral.pontos_treino')?>: <?php echo $rEvento['treino'] ?></li>
					</ul>
				<?php echo generic_tooltip('i-ev4-' . $rEvento['id'], ob_get_clean()) ?>
			</td>
			<td width="90">			
				<?php if(($basePlayer->getAttribute('dono_equipe') || $basePlayer->getAttribute('sub_equipe')) && $equipe['level'] >= $rEvento['req_equipe_level'] && !$basePlayer->getAttribute('id_evento4') && !$ja_concluido): ?>
					<?php if($equipe['membros'] != 4): ?>
						<?php echo t('evento4.e12')?>.<br />
					<?php else: ?>
						<a class="button" onclick="doAceitaEvento('<?php echo encode($rEvento['id']) ?>')"><?php echo t('botoes.aceitar')?></a>
					<?php endif; ?>
				<?php else: ?>
					<a class="button ui-state-disabled"><?php echo t('botoes.aceitar')?></a>
				<?php endif; ?>
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