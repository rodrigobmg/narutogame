<script type="text/javascript">
	var classes	= [[],[],[], []];
	var villages	= {
		<?php
			$villages	= Recordset::query('
				SELECT
					a.id,
					a.nome_'.Locale::get().' AS nome,
					b.total_players AS total
				
				FROM
					vila a JOIN estatistica_vila b ON b.id_vila=a.id
			');
		?>
		
		<?php foreach($villages->result_array() as $village): ?>
		'v<?php echo $village['id'] ?>':	{
			name:	'<?php echo $village['nome'] ?>',
			total:	'<?php echo $village['total'] ?>',
		},
		<?php endforeach ?>
		NULL: null
	};

	$(document).ready(function(e) {
		$('.paginator a').on('click', function () {
			$('.classes img').hide();
			$('.classes .class-page-' + $(this).attr('rel')).show();
			
			$('.paginator a').removeClass('current');
			$(this).addClass('current');
		});

		$('.villages img').on('click', function () {
			$('.villages img').css('opacity', .4).removeClass('current');
			$(this).css('opacity', 1).addClass('current');

			$('.current-village').html(villages['v' + $(this).data('id')].name);
			$('.current-village-total').html(villages['v' + $(this).data('id')].total);
		});
		
		$('#class-image-switcher').on('click', function () {
			var	d 		= $(document.createElement('DIV')).addClass('image-switcher');
			var	klass	= $('.classes img[class*=selected]').data('class');
			var	c		= classes[0][klass];
			var	html	= '';
			
			$(document.body).append(d);
			
			for(var i in c.i) {
				var ultimate	= false;
				
				for(var u in c.ultimate) {
					if(c.ultimate[u] == c.i[i]) {
						ultimate	= true;
					}
				}
			
				if(ultimate) {
					html	+= '<embed <?php echo (LAYOUT_TEMPLATE=="_azul" ? 'height="238" width="195"' : '"height="241" width="226"') ?> src="http://narutogame.com.br/images/layout<?php echo LAYOUT_TEMPLATE?>/profile/' + klass + '/' + c.i[i] + '.swf" quality="high" wmode="transparent" allowscriptaccess="always" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash">';
				} else {
					html	+= '<img src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/profile/') ?>' + klass + '/' + c.i[i] + '<?php echo LAYOUT_TEMPLATE=="_azul" ? ".jpg" : ".png"?>" />';
				}
			}
			
			d.html(html);
			
			d.dialog({
				title:	'<?php echo t('botoes.trocar_imagem') ?>',
				modal:	true,
				width: 860,
				height: 505
			});
		});

		
		$('.classes img').on('click', function () {
			$('.classes img').css('opacity', .4).removeClass('selected');
			$(this).css('opacity', 1);

			$(this).addClass('selected');

			var	class_type	= $('.class-type a[class=selected]').data('class');
			var	c			= classes[class_type][$(this).data('class')];
			var mx			= 0;
			
			if(!c) c = 0;
			
			$('.current-char').html(c.name);
			$('.current-char-total').html(c.total);
			
			$('#class-image').attr('src', '<?php echo img('layout'.LAYOUT_TEMPLATE.'/profile/') ?>' + c.id + '/1<?php echo LAYOUT_TEMPLATE=="_azul" ? ".jpg" : ".png"?>');

			for(var i in c.at) {
				if(c.at[i] > mx) mx	= c.at[i];
			}
			
			for(var i in c.at) {
				var	t = $('#progress-' + i);
				
				if(t.length) {
					setPValue2(c.at[i], mx, "", t);
				}
			}
		});

		$('.class-type a').on('click', function () {
			$('.class-type a').removeClass('selected');
			$(this).addClass('selected');
			
			$('.classes img[class*=selected]').trigger('click');
		});
		
		$('#finish-button').on('click', function () {
			lock_screen(true);
			
			$.ajax({
				url:		'?acao=personagem_criar_final',
				type:		'post',
				dataType:	'json',
				data:		{
					classe:			$('.classes img[class*=selected]').data('class'),
					vila:			$('.villages img[class*=current]').data('id'),
					nome:			$('.name-village input[type=text]').val(),
					classe_tipo:	parseInt($('.class-type a[class=selected]').data('class')) + 1
				},
				success:	function (result) {
					lock_screen(false);	
					
					if(result.success) {
						$('#creation-base').html(result.success);
					} else {
						jalert('<?php echo t('geral.erro_prefixo') ?>:<br/><br/>' + result.messages.join("<br />"));
					}
				},
				error:		function () {
					lock_screen(false);
					
					jalert('<?php echo t('geral.erro_ajax') ?>');
				}
			});
		});
		
		$('.paginator a:first').trigger('click');
		$('.class-type a:first').trigger('click');
		$('.classes img:first').trigger('click');
		$('.villages img:first').trigger('click');
	});
</script>
<style>
	.image-switcher img {
		margin: 4px	
	}
	.info .left {
		width: 475px;
		float: left	
	}
	
	.info .right {
		width: 225px;
		display: table-cell;
		vertical-align: middle;
		position: relative;
		left: 3px;
	}
	
	.villages img {
		cursor: pointer
	}
	.villages{
	    padding-top: 5px;	
	}	
	.data .spaced {
		margin-top: 4px	
	}
	
	.data input[type=text] {
		width: 155px	
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

	.at .progress {
		float: right;
		margin-top: 6px;
		margin-right: 7px;
	}
	.classes img {
		display: none;
		margin: 5px;
		cursor: pointer
	}
	.character-info .headline {
		color: #937B4D;
		margin-top: 2px
	}
</style>
<?php
	$vila_item	= Recordset::query('
		SELECT
			a.id
		FROM
			player a JOIN vila_item b ON b.vila_id=a.id_vila
			JOIN item c ON c.id=b.item_id
		
		WHERE
			c.id=21875 AND
			a.id_usuario=' . $_SESSION['usuario']['id'])->num_rows ? 1 : 0;
	$total		= Recordset::query('SELECT COUNT(id) AS total FROM player WHERE id_usuario=' . $_SESSION['usuario']['id'] . ' AND removido=\'0\'');
	$max		= ($_SESSION['usuario']['vip'] ? 5 : 3) + get_user_field('vip_char_slots') + $vila_item;
?>
<div class="titulo-secao"><p><?php echo t('botoes.criar_personagem')?></p></div>
<?php if($total->row()->total >= $max): ?>
<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
        <b><?php echo t('academia_jutsu.problema')?></b>
        <p>
        <?php echo t('classes.c83')?>
		<?php if(!$_SESSION['usuario']['vip']): ?>
			<?php echo t('classes.c84')?>
		<?php else: ?>
			<?php echo t('classes.c85')?>
		<?php endif; ?>
        </p>
     </div>   
	</div>
</div>
<br />
<?php else: ?>
<div id="creation-base">
	<div class="info">
		<div class="left">
			<div class="data">
				<div class="name-village">
					<div class="pull-left">
						<label><?php echo t('geral.nome') ?>:</label>
						<input type="text" maxlength="14" style="width:300px" />
					</div>
					<div class="villages pull-left">
						<?php foreach(Recordset::query('SELECT * FROM vila WHERE inicial="1"', true)->result_array() as $vila): ?>
							<img data-id="<?php echo $vila['id'] ?>" src="<?php echo img('layout/home/vilas2/' . $vila['id'] . '.jpg') ?>" />
						<?php endforeach ?>
					</div>
				</div>
				<div class="break"></div>
				<div class="spaced">
					<div class="pull-left">
						<label><?php echo t('criacao.personagem') ?>:</label>
						<span class="current-char"></span>
					</div>
					<div class="pull-right">
						<label><?php echo t('criacao.vila') ?>:</label>
						<span class="current-village"></span>
					</div>
				</div>
				<div class="break"></div>
				<div class="spaced">
					<div class="pull-left">
						<label><?php echo t('criacao.estatistica') ?>:</label>
						<span class="current-char-total"></span>
					</div>
					<div class="pull-right">
						<label><?php echo t('criacao.populacao') ?>:</label>
						<span class="current-village-total"></span>
					</div>
				</div>
			</div>
			<div class="class-type">
				<a data-class="0">Taijutsu</a>
				<a data-class="1">Ninjutsu</a>
				<a data-class="2">Genjutsu</a>
				<a data-class="3">Bukijutsu</a>
			</div>
			<div class="ats">
				<?php
					$ats		= array('tai','ken', 'nin', 'gen', 'int', 'for', 'con', 'agi', 'res','ene');
					$ar_imagens	= array(
						'agi'				=> 'layout/icones/agi.png',
						'con'				=> 'layout/icones/conhe.png',
						'for'				=> 'layout/icones/forc.png',
						'int'				=> 'layout/icones/inte.png',
						'res'				=> 'layout/icones/defense.png',
						'nin'				=> 'layout/icones/nin.png',
						'gen'				=> 'layout/icones/gen.png',
						'tai'				=> 'layout/icones/tai.png',
						'ken'				=> 'layout/icones/ken.png',
						'ene'				=> 'layout/icones/ene.png'
					);
				?>
				<?php foreach($ats as $at): ?>
					<div class="at">
						<div class="name" style="background-image: url(<?php echo img($ar_imagens[$at]) ?>); background-repeat: no-repeat">
							<?php echo t('at.' . $at) ?>
						</div>
						<div class="progress" id="progress-<?php echo $at ?>">
							<?php barra_exp3(0, 0, 132, "--", "#2C531D", "#537F3D", 1); ?>
						</div>
					</div>
				<?php endforeach ?>
			</div>
			<div class="break"></div>
		</div>
		<div class="right">
			<img id="class-image" />
			<div class="character-info">
				<div class="name"><a id="class-image-switcher" class="button" style="margin-top:-2px"><?php echo t('botoes.veja_imagens') ?></a></div>
				<div class="headline"></div>
			</div>
			
		</div>
		<div class="break"></div>
	</div>
	<table border="0" cellpadding="4" cellspacing="0" width="730">
		<tr>
			<td class="subtitulo-home" colspan="5" background="<?php echo img('layout/barra_secoes/1.png') ?>">
				<p><?php echo t('criacao.escolha') ?></p>
			</td>
		</tr>
	</table>
	<div class="classes">
		<?php
			$counter	= 0;
			$per_page	= 15;
			$js			= array();
			$classes	= Recordset::query('SELECT * FROM classe ORDER BY ordem ASC');
		?>
		<?php foreach($classes->result_array() as $class): ?>
			<?php
				$page		= ceil(++$counter / $per_page);
				
				for($f = 0; $f <= 3; $f++) {
					$total	= Recordset::query('SELECT total FROM estatistica_player WHERE id_classe=' . $class['id']);
					$total	= $total->num_rows ? $total->row()->total : 0;				
					$liberado	= Player::classe_liberada($class['id']);			
					$p		= new Player(0, true, $f + 1);
					$js[]	= "
						classes[" . $f . "][" . $class['id'] . "] = {
							id:			" . $class['id'] . ",
							name:		'" . $class['nome'] . "',
							total:		" . $total . ",
							liberado:			'" . (isset($class['inicial']) && $class['inicial'] ?  1 : $liberado) . "',
							i:			[" . Recordset::query('SELECT GROUP_CONCAT(imagem) AS images FROM(SELECT imagem FROM classe_imagem WHERE id_classe=' . $class['id'] . ' AND ativo="sim" ORDER BY ordem ASC) w', true)->row()->images . "],
							ultimate:	[" . Recordset::query('SELECT GROUP_CONCAT(imagem) AS images FROM(SELECT imagem FROM classe_imagem WHERE ultimate=1 AND id_classe=' . $class['id'] . ' AND ativo="sim" ORDER BY ordem ASC) w', true)->row()->images . "],
							at: {
								tai:	" . $p->getAttribute('tai_calc') . ",
								ken:	" . $p->getAttribute('ken_calc') . ",
								nin:	" . $p->getAttribute('nin_calc') . ",
								gen:	" . $p->getAttribute('gen_calc') . ",
								res:	" . $p->getAttribute('res_calc') . ",
								con:	" . $p->getAttribute('con_calc') . ",
								agi:	" . $p->getAttribute('agi_calc') . ",
								for:	" . $p->getAttribute('for_calc') . ",
								int:	" . $p->getAttribute('int_calc') . ",
								ene:	" . ($p->getAttribute('ene_calc')-3) . "
							}
						};
					";
				}
			?>
			<img data-class="<?php echo $class['id'] ?>" src="<?php echo img('layout'.LAYOUT_TEMPLATE.'/criacao/pequenas/' . $class['id'] . '.png') ?>" class="class-page class-page-<?php echo $page ?> <?php echo ((isset($class['inicial']) && $class['inicial']) || $liberado ? "" : "locked")?>" />
		<?php endforeach ?>
	</div>
	<div class="break"></div>
	<div class="paginator">
	<?php for($f = 1; $f <= ceil($classes->num_rows / $per_page); $f++): ?>
		<a rel="<?php echo $f ?>"><?php echo $f ?></a>
	<?php endfor ?>
	</div>
	<a class="button" id="finish-button"><?php echo t('botoes.criar_personagem') ?></a>
	<?php endif; ?>
</div>
<?php if(isset($js)): ?>
	<script type="text/javascript">
	<?php foreach($js as $v): ?>
		<?php echo $v ?>
	<?php endforeach ?>
	</script>
<?php endif ?>