<?php if(!$basePlayer->tutorial()->graduacao){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 27,
	 
	  steps: [
	  {
		element: "#tutorial-2",
		title: "<?php echo t("tutorial.titulos.graduacao.1");?>",
		content: "<?php echo t("tutorial.mensagens.graduacao.1");?>",
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
<?php
	$_SESSION['graduacaoKEY'] = md5(rand(0,512384));
	
	$rQuest		= Recordset::query("SELECT * FROM player_quest_status WHERE id_player=" . $basePlayer->id)->row_array();
?>
<div class="titulo-secao"><p><?php echo t('titulos.graduacoes') ?></p></div><br />
<?php msg('1', 'Requerimentos de Batalha Mapa PVP', 'O requerimento de batalhas das graduações passam a ser Batalhas Mapa PVP, ou seja, a soma das vitórias, derrotas e empates do jogador.');?>
<script type="text/javascript">
google_ad_client = "ca-pub-9048204353030493";
/* NG - Graduações */
google_ad_slot = "0023755321";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br/><br/>

<?php if (isset($_GET['finished'])): ?>
	<?php msg('1',''.t('graduacoes_espera.g2').'', ''.t('graduacoes_espera.g3'));?>
<?php endif ?>
<?php if($basePlayer->id_torneio): ?>
	<?php msg('3', t('graduation.tournament.title'), t('graduation.tournament.message'));?>
<?php else: ?>
	<table width="730" border="0" cellpadding="0" cellspacing="0">
	  <tr>
	    <td height="49" class="subtitulo-home">
	    <table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
	      <tr>
	        <td width="60" align="center">&nbsp;</td>
			<td align="center"><?php echo t('geral.descricao') ?></td>
	        <td width="130" align="center"><?php echo t('geral.requerimentos') ?></td>
	        <td width="100" align="center"><?php echo t('geral.status') ?></td>
	      </tr>
	    </table>
	    </td>
	  </tr>
	</table>
	<table width="730" border="0" cellpadding="0" cellspacing="0">
		<?php
			$graduacoes	= Recordset::query("SELECT * FROM graduacao WHERE id > 1", true);
			$cn			= 0;
			$cp			= 0;
		?>
		<?php foreach($graduacoes->result_array() as $grad): ?>
		<?php
			$cor		= ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
			$hasRequirement	= Graduation::hasRequirement($basePlayer, $grad['id']);
		?>
		<tr style="padding:4px;" <?php echo $cor; ?> id="tutorial-<?php echo $grad['id']?>">
			<td width="50" align="center">
			</td>
			<td align="left">
		        <br />
				<b class="amarelo" style="font-size:13px;"><?php echo graduation_name($basePlayer->id_vila, $grad['id'])?></b>
				<br /><br />
				<p><?php echo $grad['descricao' . ($basePlayer->id_vila == 6 ? '_ak' : '') . '_' . Locale::get()] ?></p>
	            <br />
			</td>
			<td width="150" align="center">
				<img src="<?php echo img('layout/requer.gif') ?>" id="i-req-<?php echo $grad['id'] ?>" style="cursor:pointer" />
				<?php echo generic_tooltip('i-req-' . $grad['id'], Graduation::getRequirementLog()) ?>
			</td>
			<td width="100" align="center">
				<?php if($basePlayer->getAttribute('id_graduacao') >= $grad['id']): ?>
				<a class="button ui-state-green"><?php echo t('botoes.graduado') ?></a>
	
				<?php elseif($hasRequirement && $grad['id'] == ($basePlayer->getAttribute('id_graduacao') + 1)): ?>
				<input type="hidden" name="_tmp" id="_tmp" value="<?php echo $_SESSION['graduacaoKEY'] ?>" />
				<a class="button" onclick="doAceitaGraduacao()"><?php echo t('botoes.graduar') ?></a>
	
				<?php else: ?>
				<a class="button ui-state-disabled"><?php echo t('botoes.graduar') ?></a>
	
				<?php endif ?>
			</td>
		</tr>
	    <tr height="4"></tr>
		<?php endforeach ?>
	</table>
<?php endif ?>