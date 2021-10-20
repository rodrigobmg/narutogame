<style>

	.d-img-p {

		float: left;

		position: relative;

	}

	.char-l {

		position:relative;

		float: left;

		width: 145px;

		height: 118px;	

		margin-right: -43px;	

	}

	.char-m {

		position:relative;

		float: left;

		width: 145px;

		height: 118px;

		top: 59px;	

	}

	.char-r {

		position:relative;

		float: left;

		width: 145px;

		height: 118px;

		margin-left: -43px;	

	}

	.ats {

		width: 506px	

	}

	.at .name {

		float: left;

		padding-left: 25px;

		height: 25px;

		background-position: 0px -1px;

		padding-top: 5px;

		margin-top: 5px;

		margin-left: 9px;

		padding-left: 31px;

		

	}

	.banned{

		width: 128px;

		height: 128px;

		background: url(../images/layout/banned.png);

		position: absolute;

		top: -6px;

	}

	.d-img-p ul{

		margin: 0;

		padding: 0;

	}

	.d-img-p li{

		line-height: 20px;

	}

</style>

<div id="HOTWordsTxt" name="HOTWordsTxt">

<div class="titulo-secao"><p><?php echo t('titulos.selecione_personagem'); ?></p></div>

 	<?php 

		//msg('4','Retorno Round 30', 'Reinicio 29/05 às 15:00');

	?>

  <div id="cnBase" class="direita">

    <form action="index.php?acao=personagem_selecionar_jogar" name="fPersonagem" id="fPersonagem" onsubmit="return false" method="post">

    	<?php if($basePlayer && $basePlayer->getAttribute('id_missao')): ?>

      <?php if($basePlayer->getAttribute('missao_equipe')): ?>

      <input type="hidden" name="missao_equipe" value="1" />

      <?php endif; ?>

      	<?php endif; ?>

      <input type="hidden" name="id" />

	<div style="position:relative; width: 730px; height: 260px;">

		<div style="float: left; width: 230px; height: 238px;" id="imgP">

			<img src="<?php echo img('personagens/pers_unknown.gif') ?>" width="195" height="238" />

		</div>

		<div style="float: left; width: 240px; padding-left: 10px; padding-top: 25px;">

			<div class="titulo-home"><p><span class="laranja">//</span> <?php echo t('geral.informacoes') ?>.....</p></div>

			<div class="selecione_grande">

				<p style="text-align: left; width: 230px; padding-left: 18px; padding-top: 10px; line-height: 16px;">

					<span id="cnNinjaP" class="laranja" style="font-size:14px; padding-bottom: 10px; display:block">-</span>

					<b>Level: </b><span id="cnLevelP">-</span><br />

					<b><?php echo t('geral.graduacao')?>: </b><span id="cnGraduacaoP">-</span><br />

					<b>Ryous: </b><span id="cnRyouP">-</span><br />

					<b><?php echo t('geral.vila')?>: </b><span id="cnVilaP">-</span>

				</p>

			</div>

		</div>

		<div style="float: left; width: 254px; padding-top: 25px;">

			<div class="titulo-home"><p><span class="verde">//</span> <?php echo t('geral.atributos') ?>.....</p></div>

			<div class="at">

				<div class="name" style="background-image: url(<?php echo img()?>layout/icones/p_hp.png); background-repeat: no-repeat">

					<?php echo t('formula.hp')?>

				</div>

				<div class="progress" id="progress-hp">

					<?php barra_exp3(0, 0, 132, "", "#2C531D", "#537F3D", 3, "id='cnAtHP'") ?>

				</div>

			</div>

			<div class="at">

				<div class="name" style="background-image: url(<?php echo img()?>layout/icones/p_chakra.png); background-repeat: no-repeat">

					Chakra

				</div>

				<div class="progress" id="progress-chakra">

					<?php barra_exp3(0, 0, 132, "", "#2C531D", "#537F3D", 3, "id='cnAtSP'") ?>

				</div>

			</div>

			<div class="at">

				<div class="name" style="background-image: url(<?php echo img()?>layout/icones/p_stamina.png); background-repeat: no-repeat">

					Stamina

				</div>

				<div class="progress" id="progress-stamina">

					<?php barra_exp3(0, 0, 132, "", "#2C531D", "#537F3D", 3, "id='cnAtSTA'") ?>

				</div>

			</div>

			

		</div>

		<!-- Comente apenas o IF -->

		<?php  //if($_SESSION['universal']){ ?>

		<div style="float: left; padding-top: 25px; margin-left: -68px;">

			<a class="button" onclick="doJogar()"><?php echo t('botoes.jogar') ?></a>

			<a class="button" onclick="doDeletar()"><?php echo t('botoes.remover') ?></a>

		</div>

		<?php  //} ?>

		<!-- Comente apenas o IF -->

		

	</div>

	<table border="0" cellpadding="4" cellspacing="0" width="730">

		<tr>

			<td class="subtitulo-home" colspan="5" background="<?php echo img('layout/barra_secoes/1.png') ?>">

				<p><?php echo t('personagem_sel.selecionar') ?></p>

			</td>

		</tr>

	</table>

	<div style="position:relative; width: 730px; height: auto;">

		<div style="float: left; width: 390px">

			<div>

				<?php

					$break	= 3;

					$cn		= 1;

					

					$players = Recordset::query(Player::getPlayerView() . " a.id_usuario={$_SESSION['usuario']['id']} AND a.removido=0 ORDER BY a.level desc");

				?>

				<?php foreach($players->result_array() as $r): ?>

				<?php

								

					$p = new Player($r['id']);

					$pObj = "{id: {$r['id_classe']}, nome: '$r[nome]'," .

						   "ryou: " . (int)$r['ryou'] . "," .

						   "level: $r[level]," .

						   "vila: '" . addslashes($p->nome_vila) . "'," .

						   "hp:  " . $p->getAttribute('hp')  . "," .

						   "sp:  " . $p->getAttribute('sp')  . "," .

						   "sta: " . $p->getAttribute('sta') . "," .

						   "mhp: " . $p->getAttribute('max_hp')  . "," .

						   "msp: " . $p->getAttribute('max_sp')  . "," .

						   "msta:" . $p->getAttribute('max_sta') . "," .

						   "g: '". graduation_name($p->id_vila, $r['id_graduacao'])."'}";

				?>

				<div class="d-img-p char-page char-page-<?php echo ceil($cn++ / 6) ?>">

				<img class="imgPers" data-image="<?php echo str_replace("\"", "'", player_imagem_ultimate($r['id'])) ?>" onerror="this.src='<?php echo img('personagens/pers_unknown.gif') ?>'"  onclick="doSelecionaPersonagem(this, '<?php echo encode($r['id']) ?>', <?php echo $pObj ?>)" 

					onload="$(this).animate({opacity: .5}, 100)" 

					src="<?php echo $r['imagem'] ? img('layout'.LAYOUT_TEMPLATE.'/criacao/pequenas/' . $r['id_classe'] . '.png') : img('personagens/pers_unknown.gif') ?>" />

				<?php

					if($r['banido']){

						$player_banido = $p->playerBanido($r['id']);

						echo "<div class='banned' id='banido-". $r['id']."'></div>";

						$mensagem = "

						<b>Personagem Banido</b><br><br>

						<ul>

						<li><b>Tipo: </b>". $player_banido['tipo'] ."</li>

						<li><b>Motivo: </b>". $player_banido['motivo'] ."</li>

						<li><b>Round: </b> Round ". $player_banido['round'] ."</li>

						<li><b>Data Banido: </b>". date("d/m/Y", strtotime($player_banido['data_banido'])) . " &agrave;s " . date("H:i:s", strtotime($player_banido['data_banido'])) ."</li>

						<li><b>Data Liberação: </b>".  date("d/m/Y", strtotime($player_banido['data_liberado'])) . " &agrave;s " . date("H:i:s", strtotime($player_banido['data_liberado'])) ."</li>

						</ul>

						";

						echo generic_tooltip('banido-' . $r['id'], $mensagem);

					}

				?>

                </div>

                

				<?php endforeach; ?>

			</div>

			<div class="break"></div>

			<div class="with-paginator paginator" data-paginator-class=".char-page">

				<?php for($f = 1; $f <= ceil($players->num_rows / 6); $f++): ?>

				<a data-page="<?php echo $f ?>"><?php echo $f ?></a>

				<?php endfor; ?>

			</div>

		</div>

		<div style="float: left; width: 340px; padding-top:5px">

			<script type="text/javascript">

				google_ad_client = "ca-pub-0077685756201057";

				google_ad_slot = "3531512840";

				google_ad_width = 336;

				google_ad_height = 280;

			</script>

			<!-- NG - Selecionar -->

			<script type="text/javascript"

			src="//pagead2.googlesyndication.com/pagead/show_ads.js">

			</script>

               

		</div>

	</div>

    </form>

  </div>

</div>