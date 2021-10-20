<script type="text/javascript">    
    $(document).ready(function () {
    	$('.b-detalhe').click(function () {
    		var id = $(this).attr('id');

    		if(parseInt($('#t-detalhe-player-' + id).attr('shown'))) {
    			$('#d-detalhe-player-' + id).hide('slide');
    		
    			$('#t-detalhe-player-' + id).hide();
    			$('#t-detalhe-player-' + id).attr('shown', 0);
    		} else {
    			$('#t-detalhe-player-' + id).show();
    			$('#t-detalhe-player-' + id).attr('shown', 1);

    			$('#d-detalhe-player-' + id).show('slide');
    		}
    	});
    });
</script>

<div class="titulo-secao"><p><?php echo t('historico.h1')?></p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "2276434172";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Historico -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/>
<br/>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="100" align="center">&nbsp;</td>
					<td width="125" align="center"><b style="color:#FFFFFF"><?php echo t('geral.personagem')?></b></td>
					<td width="175" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
					<td width="90" align="center"><b style="color:#FFFFFF"><?php echo t('geral.score')?></b></td>
					<td width="80" align="center"><b style="color:#FFFFFF"><?php echo t('geral.rank_vila')?></b></td>
					<td width="80" align="center"><b style="color:#FFFFFF"><?php echo t('geral.rank_geral')?></b></td>
					<td width="80" align="center">&nbsp;</td>
				</tr>
			</table></td>
	</tr>
</table>
<table width="730" border="0" cellpadding="0" cellspacing="0">
<?php $players = new Recordset('SELECT * FROM historico_ninja WHERE id_player = '. $basePlayer->id .'') ?>
<?php
	   $c = 0;
	  
	   
	  foreach($players->result_array() as $k => $p): 
	  
	  $elemento1 = "";
	  $elemento2 = "";
	   
	 $bg	= ++$c % 2 ? "class='cor_sim'" : "class='cor_nao'";
?>
		
	<?php 
		switch($p['id_vila']){
			case 1:
				$vila = "Folha";
			break;
			case 2:
				$vila = "Areia";
			break;
			case 3:
				$vila = "Nevoa";
			break;
			case 4:
				$vila = "Pedra";
			break;
			case 5:
				$vila = "Nuvem";
			break;
			case 6:
				$vila = "Akatsuki";
			break;
			case 7:
				$vila = "Som";
			break;
			case 8:
				$vila = "Chuva";
			break;
		} 
	
	?>
	<?php 
	if($p['elemento1'] != 0){
		switch($p['elemento1']){
			case 1:
				$elemento1 = "Katon";
			break;
			case 2:
				$elemento1 = "Fuuton";
			break;
			case 3:
				$elemento1 = "Raiton";
			break;
			case 4:
				$elemento1 = "Doton";
			break;
			case 5:
				$elemento1 = "Suiton";
			break;
			
		} 
	}
	?>
	<?php 
		if($p['elemento2'] != 0){
			switch($p['elemento2']){
				case 1:
					$elemento2 = "/ Katon";
				break;
				case 2:
					$elemento2 = "/ Fuuton";
				break;
				case 3:
					$elemento2 = "/ Raiton";
				break;
				case 4:
					$elemento2 = "/ Doton";
				break;
				case 5:
					$elemento2 = "/ Suiton";
				break;
					
				} 
		}
	?>

	<tr <?php echo $bg?>>
		<td width="100" height="35" align="center"><a class="button <?php echo $p['round']==13 ? 'ui-state-red' : '' ?>"> Round <?php echo $p['round'] ?></a></td>
		<td width="125" align="center"><img src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/dojo/'. $p['id_classe'] . (LAYOUT_TEMPLATE == "_azul" ? '.jpg' : '.png'))?>" /></td>
		<td width="175" align="center" ><b style="font-size:13px;" class="amarelo"><?php echo $p['nome'] ?></b></td>
		<td width="90" align="center" ><?php echo $p['score'] ?> <?php echo t('geral.pontos')?></td>
		<td width="80" align="center" ><?php echo $p['posicao_vila'] ? $p['posicao_vila'] : '?' ?>°</td>
		<td width="80" align="center" ><?php echo $p['posicao_geral'] ? $p['posicao_geral'] : '?' ?>º</td>
		<td width="80" align="center" ><a id="<?php echo $k ?>" class="button b-detalhe"><?php echo t('botoes.detalhes')?></a></td>
	</tr>
	<tr height="4"></tr>
	<tr <?php echo $bg?> id="t-detalhe-player-<?php echo $k ?>" style="display: none">
		<td colspan="7">
			<div id="d-detalhe-player-<?php echo $k ?>" style="display: block"><br /><br />
				<b style="font-size:13px;" class="verde"><?php echo t('historico.h3')?></b>
				<table border="0" cellpadding="10" cellspacing="0" width="730">
					<tr>
						<td width="22%" align="left" valign="top">
						<?php echo t('geral.nome')?>: <?php echo $p['nome'] ?><br />
						<?php echo t('geral.graduacao')?>: <?php echo graduation_name($p['id_vila'], $p['id_graduacao'])?><br />
						<?php echo t('geral.level')?>: <?php echo $p['level'] ?><br />
						<?php echo t('status.treino_total')?>: <?php echo $p['treino_total'] ?> <?php echo t('geral.pontos')?><br />
						<?php echo t('geral.vila')?>: <?php echo $vila ?><br />
						<?php echo t('geral.elemento')?>: <?php echo $elemento1 ?> <?php echo $elemento2?>
						</td>
						<td width="22%" align="left" valign="top">
						<?php echo t('requerimentos.vitorias_pvp')?>: <?php echo $p['vitorias_pvp'] ?><br />
						<?php echo t('requerimentos.vitorias_npc')?>: <?php echo $p['vitorias_npc'] ?><br />
						<?php echo t('requerimentos.derrotas_pvp')?>: <?php echo $p['derrotas'] ?><br />
						<?php echo t('requerimentos.derrotas_npc')?>: <?php echo $p['derrotas_npc'] ?><br />
						<?php echo t('requerimentos.empates')?>: <?php echo $p['empate'] ?><br />
						<?php echo t('requerimentos.fugas')?>: <?php echo $p['fugas'] ?>
						</td>
						<td width="22%" align="left" valign="top">
						<?php echo t('menus.missoes')?> D: <?php echo $p['quest_d'] ?><br />
						<?php echo t('menus.missoes')?> C: <?php echo $p['quest_c'] ?><br />
						<?php echo t('menus.missoes')?> B: <?php echo $p['quest_b'] ?><br />
						<?php echo t('menus.missoes')?> A: <?php echo $p['quest_a'] ?><br />
						<?php echo t('menus.missoes')?> S: <?php echo $p['quest_s'] ?>
						</td>
						<td width="34%" align="center" valign="top">
						<?php
							if($p['id_cla']){
								echo "<img src='". img(). "layout/clas/". $p['id_cla'] ."/5.png'/>";
							}
						?>
						<?php
							if($p['id_portao']){
								echo "<img src='". img(). "layout/portoes/362.gif'/>";
							}
						?>
						<?php
							if($p['id_sennin']){
								echo "<img src='". img(). "layout/mode_sennin/1/3.png'/>";

							}
						?>	
						<?php
							if($p['id_selo']){
								echo "<img src='". img(). "layout/selos/". $p['id_selo'] ."/3.gif'/>";

							}
						?>			
						<?php
							if($p['id_invocacao']){
								echo "<img src='". img(). "layout/invocacoes/". $p['id_invocacao'] ."/5.png'/>";

							}
						?>
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
<?php endforeach; ?>
</table>
