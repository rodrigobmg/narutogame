<?php
  $_SESSION['personagem_imagem_key']	= md5(date("YmdHis") . rand(1, 32768));
  $rank								= Recordset::query('SELECT * FROM ranking WHERE id_player=' . $basePlayer->id);
  $rank_conquista						= Recordset::query('SELECT * FROM ranking_conquista WHERE id_player=' . $basePlayer->id);
?>
<script type="text/javascript">
  function doPlayerTitulo() {
    $.ajax({
      url: "?acao=personagem_status&o=1",
      type: "post",
      data: {
        v: $('#sPlayerTitulo').val()
      }
    });
  }

	function doPersonagemImagem() {
		var d = $(document.createElement("DIV"));

		d.load("?acao=personagem_imagem");
		d.dialog({
			width: 750,
			height: 510,
			title: '<?php echo t('fight.f36')?>',
			modal: true,
			close: function () {
				d.remove();
			}
		});
	}
	
	function doPersonagemImagemDo(i, tema, comprado) {
		var	title	= '';
		
		if(tema && !comprado) {
			var msg	= "<img width='722' src='<?php echo img('layout/temas/#id.jpg')?>' />".replace('#id', tema);
		} else {
			var msg	= "<?php echo t('fight.f37')?>";
		}
		
		var win = jconfirm(msg, title, function () {
			$.ajax({
				url: '?acao=personagem_imagem',
				type: 'post',
				data: {id: i, personagem_imagem_key: "<?php echo $_SESSION['personagem_imagem_key'] ?>"},
				dataType: 'script',
				success: function () {
					location.reload();
				}
			});
		}, null, (tema && !comprado) ? 750 : 300);
	}
</script>
<div id="character-data">
	<div id="character-image"><?php echo player_imagem_ultimate($basePlayer->id) ?></div>
	<div id="character-info">
		<div class="name"><?php echo $basePlayer->nome ?></div>
		<div class="headline"><?php echo $basePlayer->nome_titulo ?></div>
	</div>
	<div id="character-attributes">
		<!-- Bijuus -->
		<?php if($basePlayer->hasItem(array(1459, 1460, 1461, 1462, 1463, 1464, 1465, 1466, 1467,1468))): ?>
			<div class="box">
				<?php foreach($basePlayer->getItems() as $item): ?>
				<?php if($item->getAttribute('id_tipo') != 23) continue; ?>
						<div class="i">
							<a href="?secao=bijuus"><img src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/topo-logado/icones/bijuus/' . $item->id . '.png') ?>" border="0"/></a>
							<div class="t">    
								<?php echo t('templates.t46')?>.<br />
								<div style="float: left"><?php echo t('vantagens_vip.vv30')?>: </div><div id="d-bijuu-timer2" class="verde" style="float: left; font-weight:bold">--:--:--</div>
							</div>
						</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		
		<!-- Espadas nevoa -->
		<?php if($basePlayer->hasItem(array(22726, 22727, 22728, 22729, 22730, 22731, 22732))): ?>
			<div class="box">
				<?php foreach($basePlayer->getItems() as $item): ?>
				<?php if($item->getAttribute('id_tipo') != 39) continue; ?>
						<div class="i">
							<a href="?secao=espadas"><img src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/topo-logado/icones/bijuus/' . $item->id . '.png') ?>" border="0"/></a>
							<div class="t">    
								<?php echo t('templates.t75')?>.<br />
								<div style="float: left"><?php echo t('vantagens_vip.vv30')?>: </div><div id="d-bijuu-timer3" class="verde" style="float: left; font-weight:bold">--:--:--</div>
							</div>
						</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		
		<!-- Clã -->
		<?php if($basePlayer->getAttribute('id_cla') && $basePlayer->getAttribute('id_cla_atual')): ?>
			<div class="box">
				<div class="i">
					<a href="?secao=clas"><img src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/topo-logado/icones/clas/'. $basePlayer->getAttribute('id_cla')) ?>.png" id="iBag" border="0" /></a>
					<div class="t">    
						  <b class="azul"><?php echo $basePlayer->getAttribute('nome_cla') ?></b><br /><br />
						  <b><?php echo t('templates.t41')?> <?php echo $basePlayer->getAttribute('nome_cla') ?>, <?php echo t('templates.t42')?> <a href="?secao=clas" class="azul"><?php echo t('ativacao_enviar.ae4')?></a></b><br /><br />
					</div>
				</div>
			</div>
		<?php endif; ?>
			
		<!-- Selo -->
		<?php if($basePlayer->getAttribute('id_selo')): ?>
			<div class="box">
				<div class="i">
					<a href="?secao=selo"><img src="<?php echo img() ?>layout<?php echo LAYOUT_TEMPLATE?>/topo-logado/icones/selo/<?php echo $basePlayer->id_selo ?>.png" id="iBag" border="0" /></a>
					<div class="t">
						<strong class="azul"><?php echo $basePlayer->getAttribute('nome_selo') ?></strong><br /><br />
					   <b><?php echo t('templates.t43')?> <?php echo $basePlayer->getAttribute('nome_selo') ?>, <?php echo t('templates.t42')?> <a href="?secao=selo" class="azul"><?php echo t('ativacao_enviar.ae4')?></a></b><br /><br />
					</div>
				</div>
			</div>
		<?php endif; ?>
			
		<!-- Invocação -->
		<?php if($basePlayer->getAttribute('id_invocacao')): ?>
			<div class="box">
	
				<div class="i">
					<a href="?secao=invocacao"><img src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/topo-logado/icones/invocacao/' . $basePlayer->getAttribute('id_invocacao') . '.png') ?>" id="iBag" border="0" /></a>
					<div class="t">
						<strong class="azul"><?php echo $basePlayer->getAttribute('nome_invocacao') ?></strong><br /><br />
						<b><?php echo t('templates.t44')?> <?php echo $basePlayer->getAttribute('nome_invocacao') ?>, <?php echo t('templates.t42')?> <a href="?secao=invocacao" class="azul"><?php echo t('ativacao_enviar.ae4')?></a></b><br /><br />
					</div>
				</div>
			</div>
		<?php endif; ?>
			
		<!-- Elemento -->
		<?php if(sizeof($basePlayer->getElementosA())): ?>	
			<div class="box">
				<div class="i">
					<a href="?secao=personagem_elementos"><img src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/topo-logado/icones/elemento.png') ?>" id="iBag" border="0" /></a>
					<div class="t">
					<?php foreach($basePlayer->getElementosA() as $elemento): ?>
						<strong class="azul"><?php echo $elemento['nome'] ?></strong><br /><br />
						<b><?php echo t('templates.t45')?> <?php echo $elemento['nome'] ?>, <?php echo t('templates.t42')?> <a href="?secao=personagem_elementos" class="azul"><?php echo t('ativacao_enviar.ae4')?></a></b><br /><br />
					<?php endforeach; ?>	
					</div>
				</div>
			</div>
		<?php endif ?>
			  
		<!-- Portão -->   
		<?php if($basePlayer->getAttribute('portao')): ?>
			<div class="box">
				<div class="i">
					<a href="?secao=portoes"><img src="<?php echo img();?>layout<?php echo LAYOUT_TEMPLATE?>/topo-logado/icones/portao.png" border="0" /></a>
					<div class="t">
						  <b class="azul"><?php echo $basePlayer->getAttribute('nome_portao') ?></b><br /><br />
						  <b><?php echo t('templates.t43')?> <?php echo $basePlayer->getAttribute('nome_portao') ?>, <?php echo t('templates.t42')?> <a href="?secao=portoes" class="azul"><?php echo t('ativacao_enviar.ae4')?></a></b><br /><br />
					</div>
				</div>
			</div>
		<?php endif; ?>
			 
		<!-- Sennin -->           
		<?php if($basePlayer->getAttribute('sennin')): ?>
				<div class="box">
					<div class="i">
						<a href="?secao=mode_sennin"><img src="<?php echo img() ?>layout<?php echo LAYOUT_TEMPLATE?>/topo-logado/icones/sennin/<?php echo $basePlayer->id_sennin ?>.png" id="iBag" border="0" /></a>
						<div class="t">    
							<strong class="azul"><?php echo $basePlayer->getAttribute('nome_sennin') ?></strong><br /><br />
						   <b><?php echo t('templates.t43')?> <?php echo $basePlayer->getAttribute('nome_sennin') ?>, <?php echo t('templates.t42')?> <a href="?secao=mode_sennin" class="azul"><?php echo t('ativacao_enviar.ae4')?></a></b><br /><br />
						</div>
					</div>
				</div>
		<?php endif; ?>
		<div class="category inventory-container box" style="float: right; margin-right: 5px;">
			<a class="category-data">
				<img src="<?php echo img() ?>layout<?php echo LAYOUT_TEMPLATE?>/topo-logado/icones/bag2.png" class="inventory-trigger" alt="<?php echo t('templates.t22')?>" border="0" />
			</a>
			<div data-sell-confirm="<?php echo t('actions.a281') ?>" class="arrow_box t inventory-data" data-default="<?php echo t('templates.t21')?>" style="margin-left: -200px;"></div>
		</div>

		<div class="break"></div>
	</div>
	<?php if($basePlayer->dentro_vila){?>
	<div id="character-status">
		<input class="button" type="button" onclick="doPersonagemImagem()" value="<?php echo t('botoes.trocar_imagem') ?>" /><br /><br />
		<div class="layout_change">
			<b>Layout Atual</b><br />
			<div style="float: left; margin-left:5px"><img data-layout="r8" src="<?php echo img()?>layout/layout_r8.jpg" alt="Naruto Game - Layout Azul do Round 8" width="105"/></div>
			<div style="float: left; margin-left:5px"><img data-layout="r10" src="<?php echo img()?>layout/layout_r10.jpg" alt="Naruto Game - Layout Padrão do Round 10" width="105"/></div>
		</div>	
		<b><?php echo t('status.titulo') ?>: </b><br />
		<select name="sPlayerTitulo" id="sPlayerTitulo" onchange="doPlayerTitulo()" style="width:190px">
			<option value="<?php echo encode(0) ?>"><?php echo t('missoes.nenhuma')?></option>
			<?php
				$qTitulo = Recordset::query("SELECT id, titulo_br, titulo_en FROM player_titulo WHERE id_usuario=" . $basePlayer->id_usuario . " GROUP BY titulo_br");
			?>
			<?php while($rTitulo = $qTitulo->row_array()): ?>
				<?php
					$headline	= htmlspecialchars($rTitulo['titulo_' . Locale::get()]);
					player_titulo_grad($headline, $basePlayer);
				?>
				<option <?php echo $rTitulo['id'] == $basePlayer->id_titulo ? "selected='selected'" : "" ?> value="<?php echo encode($rTitulo['id']) ?>"><?php echo $headline ?></option>
			<?php endwhile; ?>
		</select>
		<p>
			<b><?php echo t('status.classe') ?>: </b>
			<?php
				switch($basePlayer->id_classe_tipo){
					case 2:
						echo t('classe_tipo.nin');
					break;
					case 1:
						echo t('classe_tipo.tai');
					break;
					case 3:
						echo t('classe_tipo.gen');
					break;
					case 4:
						echo t('classe_tipo.ken');
					break;
				}
			?>
		</p>
		<p>
			<b><?php echo t('status.vila') ?>: </b> <a href="?secao=vila"><?php echo $basePlayer->nome_vila ?></a>
		</p>
		<?php 
			switch($basePlayer->id_profissao){
				case 1:
					$profissao = "Médico"; 
				break;
				case 2:
					$profissao = "Cozinheiro"; 
				break;
				case 3:
					$profissao = "Ferreiro"; 
				break;
				case 4:
					$profissao = "Caçador"; 
				break;
				case 5:
					$profissao = "Instrutor"; 
				break;
				case 6:
					$profissao = "Aventureiro"; 
				break;
			}
		?>	
		<p>
			<b><?php echo t('menus.profissao') ?>:</b> <a href="?secao=profissao"><?php echo $basePlayer->id_profissao ? $profissao : "-"?></a>
		</p>
		<p>
			<b>Level:</b> <?php echo $basePlayer->getAttribute('level') ?>
		</p>
		<p>
			<b><?php echo t('jogador_vip.jv36')?>: <?php echo $basePlayer->getAttribute('fight_power') ?></b>
		</p>
		<!--
		<p>
			<b><?php echo t('requerimentos.experiencia')?>: <?php echo $basePlayer->getAttribute('experience_ninja') ?></b>
		</p>
		-->
		<?php
			$qtd_dias_jogado = Recordset::query('select DATEDIFF(now(), data_ins) qtd_dias_jogado from player_flags where id_player = ' . $basePlayer->id)->row_array();
		?>							   
		<p>
			<b><?php echo t('status.qtd_dias')?>: </b> <?php echo $qtd_dias_jogado['qtd_dias_jogado']?>
		</p>
		<p>
			<b><?php echo t('status.fidelidade')?>: </b> <?php echo Player::getFlag('fidelidade_points', $basePlayer->id) ?>
		</p>
		<p>
			<b><?php echo t('status.rank_vila') ?>: </b><?php echo $rank->num_rows ? $rank->row()->posicao_vila . "&deg;" : "-" ?>
		</p>
		<p>
			<b><?php echo t('status.rank_geral') ?>: </b><?php echo $rank->num_rows ? $rank->row()->posicao_geral . "&deg;" : "-" ?>
		</p>
		<p>
			<b>Score: </b><?php echo $rank->num_rows ? $rank->row()->pontos : '--' ?>
		</p>
		<p>
			<b><?php echo t('status.pt_conquista') ?>: </b><a href="?secao=conquistas"><?php echo $rank_conquista->num_rows ? $rank_conquista->row()->pontos : "-" ?></a>
		</p>
		<p>
			<b><?php echo t('status.equipe') ?>: </b><?php echo $basePlayer->getAttribute('id_equipe')? "<a href='?secao=equipe_detalhe'>". $basePlayer->getAttribute('nome_equipe')."</a>" : t('missoes.nenhuma') ?>
		</p>
		<p>
			<b><?php echo t('status.guild') ?>: </b><?php echo $basePlayer->getAttribute('id_guild') ? "<a href='?secao=guild_detalhe'>". $basePlayer->getAttribute('nome_guild')."</a>" : t('missoes.nenhuma') ?>
		</p>		
	</div>
	<!--<div style="position: relative; width: 120px; height: 60px; left: 58px; top: 15px;">
		<div style="float: left">
			 <div class="fb-like" data-href="http://www.facebook.com/narutogamebr" data-send="false" data-layout="box_count" data-width="70" data-show-faces="false"></div>
		</div>
		<div style="float: left; padding-left: 10px">
			<div class="g-plusone" data-size="tall" data-href="http://narutogame.com.br"></div>
		</div>
	</div>-->
	<?php }?>
</div>
<br />
<?php if ($basePlayer->id_exame_chuunin != 0 && $basePlayer->exame_chuunin_etapa == 1): ?>
	<?php
		$exam	= Recordset::query('SELECT data_inicio, DATE_ADD(data_inicio, INTERVAL 1 HOUR) AS future FROM exame_chuunin WHERE id=' . $basePlayer->id_exame_chuunin, true)->row_array();
		$diff	= get_time_difference(now(true), strtotime($exam['future']));

		$sky	= Recordset::query('SELECT COUNT(a.id) AS total FROM player_item a JOIN player b ON b.id=a.id_player WHERE id_item=22916 AND b.id_exame_chuunin=' . $basePlayer->id_exame_chuunin)->row()->total;
		$earth	= Recordset::query('SELECT COUNT(a.id) AS total FROM player_item a JOIN player b ON b.id=a.id_player WHERE id_item=22917 AND b.id_exame_chuunin=' . $basePlayer->id_exame_chuunin)->row()->total;
	?>
	<?php if (date('YmdHis', strtotime($exam['data_inicio'])) >= now()): ?>
		<div class="titulos-menu"><img src="http://narutogame.com.br/images/layout/menu-titulos/exame.png"></div>
		<p class="chumbo">Você está participando do <span class="laranja_menu">Exame Ninja</span> e ele terminará em: <span id="exam-timer">--:--:--</span>
		<script type="text/javascript">
			createTimer(<?php echo $diff['hours'] ?>, <?php echo $diff['minutes'] ?>, <?php echo $diff['seconds'] ?>, 'exam-timer', function () {});
		</script>
		<br />
		ou quando um dos <span class="laranja_menu"><?php echo $sky ?> pergaminhos do céu</span> ou <span class="laranja_menu"><?php echo $earth ?> pergaminhos da terra</span> acabarem no Exame.</p>
	<?php endif ?>
<?php endif;?>
<?php if ($basePlayer->exame_chuunin_etapa == 2): ?>
	<?php
		$next_exam	= Recordset::query('
			SELECT
				data_inicio

			FROM
				exame_chuunin

			WHERE
				id_graduacao=' . $basePlayer->id_graduacao . '
				AND data_inicio > NOW()
				AND etapa1=0

			ORDER BY data_inicio ASC');
	?>
	<?php if ($next_exam->num_rows): ?>
		<?php
			$next_exam	= $next_exam->row_array();
			$diff_next	= get_time_difference(now(true), strtotime($next_exam['data_inicio']));
		?>
		<div class="titulos-menu"><img src="http://narutogame.com.br/images/layout/menu-titulos/exame.png"></div>
		<p class="chumbo">Você esta classificado para a <span class="laranja_menu">2º Etapa do Exame Ninja</span> e ele começará em: <span id="next-exam-timer">--:--:--</span></p>
		<script type="text/javascript">
			createTimer(<?php echo $diff_next['hours'] ?>, <?php echo $diff_next['minutes'] ?>, <?php echo $diff_next['seconds'] ?>, 'next-exam-timer');
		</script>
	<?php endif ?>
<?php endif;?>
<?php 
	$polls	= Recordset::query('SELECT * FROM enquetes WHERE ativo=1 AND NOW() BETWEEN inicio AND fim');
?>
<?php if ($polls->num_rows): ?>
<div class="titulos-menu"><img src="http://narutogame.com.br/images/layout/menu-titulos/enquete.png"></div>
<ul><li><a href="?secao=enquete">Enquete</a></li></ul>
<?php endif;?>
<?php if ($basePlayer->id_guerra_ninja): ?>
	<div class="titulos-menu"><img src="<?php echo img('layout/menu-titulos/guerra.png') ?>"></div>
	<ul>
		<li><a href="javascript:;" id="guerra-ninja-menu"><?php echo t('menus.guerra_ninja') ?></a></li>
	</ul>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#guerra-ninja-menu').on('click', function () {
				lock_screen(true);

				$.ajax({
					url:		'?acao=guerra_ninja',
					success:	function (result) {
						lock_screen(false);

						var d = $(document.createElement("DIV"));
						d.html(result);
						
						$(document.body).append(d);

						d.dialog({
							modal: true,
							width: 780,
							title: 'Guerra Ninja',
							close: function () {
								d.remove();
							},
							buttons: {
								"Fechar": function () {
									d.remove();
								}
							}
						});
					}
				});
			});
		});
	</script>
<?php endif ?>
