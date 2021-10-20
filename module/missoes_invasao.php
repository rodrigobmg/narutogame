<?php
	$dia_semana = date('N');
	$hora = date("H");
	Player::moveLocal($basePlayer->id, 4);
	
	$guild			= false;
	$vilas			= Recordset::query('SELECT *, nome_'.Locale::get().' AS nome FROM vila WHERE inicial=\'1\'', true);
	$cant_accept	= array();
	
	if($basePlayer->getAttribute('id_guild')){
		$guild		= Recordset::query('SELECT * FROM guild WHERE id=' . $basePlayer->getAttribute('id_guild'))->row_array();
		$membros	= $_SESSION['universal'] ? 20 : $guild['membros'];
		$players	= Recordset::query('SELECT id, nome, HOUR(TIMEDIFF(NOW(), guild_ult_invasao)) AS diff, guild_ult_invasao FROM player WHERE id_guild=' . $basePlayer->id_guild);
		
		foreach($players->result_array() as $player) {
			if($player['guild_ult_invasao'] && $player['diff'] <= 5 * 24) {
				$cant_accept[]	= $player;
			}
		}
	}
?>
<script type="text/javascript" src="js/missoes.js"></script>
<script type="text/javascript">
	function showVila(i, o) {
		$(".vilaArea").hide();
		$("#vila_" + i).show();
		
		$(".tMissaoSel").attr("background", "<?php echo img() ?>bt_aba_menor.gif");
		
		if(o) {
			$(o).attr("background", "<?php echo img() ?>bt_aba_menor2.gif");
		}
	}
	
	$(document).ready(function () {
		showVila(1);
	});
</script>
<style>
.morto {
	filter: alpha(opacity=10);
	opacity: .1;
	-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=10)";
}
</style>
<div class="titulo-secao"><p>Missões de Invasão</p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "1857631774";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Missões -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/><br/>
<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
			<b>Lembretes importantes!</b>
			<p>As missões de invasão possuem uma validade de 24 horas e esse tempo começa apartir das 06:00, ou seja, se sua organização aceita um desafio as 07:00 terá até as 06:00 do outro dia para vencer o guardião.<br>
			<span class="laranja">As invasões só poderão ser aceitas no  Sábado as 06 horas da manhã até Segunda às 06 horas da manhã.</span>
			<?php if($guild): ?>
			<br /><br />
			<span class="verde">Sua organização tem atualmente:</span> <?php echo $guild['exp_total'] ?> pontos(s) de 45000 pontos para aceitar uma missão.</p>
			<?php endif; ?>
			<?php if(sizeof($cant_accept)): ?>
				<p>
				<?php echo t('missao_invasao.guild_ult_invasao') ?>
				<ul>
					<?php foreach($cant_accept as $player): ?>
						<li style="float:left; line-height:10px"><p style="color: #F04646"> <?php echo $player['nome'] ?> <span class="cinza"> / </span></p></li>
					<?php endforeach ?>
				</ul>
				</p>
				<div class="break"></div>
			<?php endif ?>
		</div>	
	</div>
</div>

<div style="clear:both"></div>
<br />
<div id="cnBase" class="direita">
<table border="0" cellpadding="0" cellspacing="0" align="center" class="with-n-tabs"  id="tb100" data-auto-default="1">
	<tr>
		<td>
			<?php foreach($vilas->result_array() as $vila): ?>
				<a class="button" rel="#vila-<?php echo $vila['id'] ?>" style="width:130px; margin: 5px"><?php echo $vila['nome'] ?></a>
			<?php endforeach; ?>
		</td>
	</tr>
</table>
<br /><br />
	<?php 
		foreach($vilas->result_array() as $vila): 
	?>
	<div id="vila-<?php echo $vila['id'] ?>">
	<table  width="730" border="0" cellpadding="4" cellspacing="0">
	<?php
    	$c			= 0;
		$missoes	= Recordset::query('
			SELECT
				  a.id,            
				  a.id_vila,
				  a.id_npc_vila,
				  a.bonus,
				  a.perca,      
				  (SELECT nome_'.Locale::get().' FROM vila WHERE id=a.id_vila) AS vila_nome,      
				  a.id_guild,
				  (SELECT nome FROM guild WHERE id=a.id_guild) AS guild_nome,
				  a.nome,
				  a.imagem,
				  a.descricao,
				  (SELECT tempo_derrota FROM npc_vila WHERE id=a.id_npc_vila) AS tempo_derrota,
				  (SELECT morto FROM npc_vila WHERE id=a.id_npc_vila) AS morto
			
			FROM
				vila_quest a
			
			WHERE
				 #a.id_vila != ' . $basePlayer->getAttribute('id_vila') . ' AND
				 a.id_vila=' . $vila['id'] . '
			
			ORDER BY id_vila				
		');
	?>
	<?php foreach($missoes->result_array() as $r): ?>
	<?php
		$bg		= ++$c % 2 ? "class='cor_sim'" : "class='cor_nao'";
		$dipl	= Player::diplOf($basePlayer->getAttribute('id_vila'), $vila['id']);
		$morto	= $r['morto'] ? 'class="morto"' : '';
	?>
	<tr <?php echo $bg ?>>
		<td  width="510">
			<img src="<?php echo img() ?>layout<?php echo LAYOUT_TEMPLATE?>/guardioes/<?php echo $r['id_vila'] ?>-<?php echo $r['id_npc_vila'] ?><?php echo LAYOUT_TEMPLATE=="_azul" ? ".png" : ".jpg"?>" <?php echo $morto;?> />
		</td>
		<td height="34" align="center">
			<?php if($r['guild_nome']): ?>
				Organização: <?php echo $r['guild_nome'] ?><br /><br />
			<?php endif; ?>
			<b class="verde">Prêmio Organização</b><br /><br />
			<?php echo $r['bonus'];?>
			<br/>
			<br/>
			<b class="vermelho">Punição Vila</b><br /><br />
			<?php echo $r['perca'];?>
			<br/>
			<br/>
			<?php if($basePlayer->getAttribute('id_guild')): ?>
			
				<?php 
					if(
						sizeof($cant_accept) || 
						!$guild || 
						$guild['exp_total'] < 45000 || 
						$r['id_vila'] == $basePlayer->getAttribute('id_vila') ||
						$r['id_guild'] || 
						$r['tempo_derrota'] || 
						$r['morto'] || 
						$basePlayer->getAttribute('missao_invasao') || 
						!$basePlayer->getAttribute('dono_guild') || 
						$membros < 9 || 
						$dipl != 2 || 
						($dia_semana != 6 and $dia_semana != 7 and $dia_semana != 1) ||
						($dia_semana == 6 && $hora < '6') || 
						($dia_semana == 1 && $hora > '6')
					): 
				?>
					<a class="button ui-state-disabled"><?php echo t('botoes.aceitar') ?></a>
				<?php else: ?>
					<a class="button" onclick="doAceitaQuestInvasao('<?php echo encode($r['id']) ?>')"><?php echo t('botoes.aceitar') ?></a>
				<?php endif; ?>
			<?php else: ?>
				<a class="button ui-state-disabled"><?php echo t('botoes.aceitar') ?></a>
			<?php endif; ?>
		</td>
	</tr>
	<tr >
		<td colspan="2"><?php echo $r['descricao'] ?></td>
	</tr>
	<tr height="4"></tr>
  		<?php endforeach; ?>
	</table>
	</div>
	<?php endforeach; ?>
</div>