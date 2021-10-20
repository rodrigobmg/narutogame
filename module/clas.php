<?php
	$clas				= Recordset::query('SELECT * FROM cla', true);
	$js_function		= $_SESSION['el_js_func_name'] = "f" . md5(rand(1, 512384));
	$pay_key_1			= $_SESSION['pay_key_1'] = round(rand(1, 999999)); // Coin
	$dvDescs			= array();
	$current_counter	= Player::getFlag('cla_sair_count', $basePlayer->id);

	$formulas			= [
		'conv',
		'conc',
		'det',
		'esq',
		'esquiva'
	];

	$ats				= [
		'tai',
		'ken',
		'nin',
		'gen',
		'agi',
		'con',
		'forc',
		'inte',
		'res',
		'esq',
		'det',
		'conv',
		'conc'
	];

	if($basePlayer->id_cla) {
		$blocked			= [];
		$distribute_data	= Recordset::query('SELECT * FROM cla WHERE id=' . $basePlayer->id_cla, true)->row();
		$base_item			= Recordset::query('SELECT * FROM item WHERE id_tipo=16 AND ordem=1 AND id_cla=' . $basePlayer->id_cla, true)->row();
		$mine_item			= Recordset::query('SELECT * FROM player_modificadores WHERE id_player=' . $basePlayer->id . ' AND id_tipo=16')->row();

		foreach ($ats as $at) {
			if(!$distribute_data->$at) {
				$blocked[]	= $at;
			}
		}
	}

	if(!$current_counter) {
		$exit_string	= 'sair1';
	} elseif($current_counter == 1) {
		$exit_string	= 'sair2';
	} else {
		$exit_string	= 'sair3';
	}
?>
<style>
	.bonus-text p b {
		color: #af9d6b; 
		font-size:14px;
	}
	
	.bonus-text p {
		float: left; 
		width: 46%;
		padding: 5px;
	}
</style>
<?php if(!$basePlayer->tutorial()->clas){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 2,
	 
	  steps: [
	  {
		element: "#cla-selector",
		title: "<?php echo t("tutorial.titulos.clas.1");?>",
		content: "<?php echo t("tutorial.mensagens.clas.1");?>",
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
<script type="text/javascript">
	function <?php echo $js_function ?>() {
		jconfirm("<?php echo t('clas.c1')?><br /><?php echo t('clas.c2')?><br /><?php echo t('clas.' . $exit_string)?><br /><?php echo t('clas.c4')?>", null, function () {
			lock_screen(true);

			$.ajax({
				url: 'index.php?acao=cla_sair',
				dataType: 'script',
				type: 'post',
				data: {pm: <?php echo $pay_key_1 ?> }
			});
		});
	}

	$(document).ready(function () {
		var	_defaults				= [];
		var	_cla					= 0;
		var	_blocked				= [];

		var	_max_points				= 0;
		var	_allowed				= [];
		var	_allowed_max			= [];

		var	_max_points_formula		= 0;
		var	_allowed_formula		= [];
		var	_allowed_max_formula	= [];

		function _reload_settings() {
			$.ajax({
				url:		'?acao=clas_editar',
				data:		{settings: 1, cla: _cla},
				type:		'post',
				dataType:	'json',
				success:	function (result) {
					_defaults				= result.defaults;
					_blocked				= result.blocked;

					_max_points				= result.max_points;
					_allowed				= result.allowed;
					_allowed_max			= result.allowed_max;

					_max_points_formula		= result.max_points_formula;
					_allowed_formula		= result.allowed_formula;
					_allowed_max_formula	= result.allowed_max_formula;

					_reset();
				}
			});
		}

		function _reset() {
			$('#cla-selector select').removeAttr('disabled').parent().show();

			$('#cla-selector select').each(function (select) {
				var	_			= $(this);

				if(_blocked[_.data('at')]) {
					_.attr('disabled', 'disabled').html('<option value="0">0</option>').parent().hide();
					return;
				}

				var	html		= '';
				var	is_formula	= parseInt($(this).data('formula'));
				var	max			= is_formula ? _allowed_max_formula[_.data('at')] : _allowed_max[_.data('at')];

				for(var i = 0; i <= max; i++) {
					html	+= '<option value="' + i + '">' + i + '</option>';
				}

				_.html(html);

				this.options[parseInt(_defaults[_.data('at')])].selected	= true;
			});

			$('#cla-selector select').trigger('change');

			_show_view();
			_update_left_points();
		}

		function _show_view() {
			var	data	= {preview: 1, cla: _cla};

			$('#cla-selector select').each(function () {
				var	_				= $(this);
				data[_.data('at')]	= _.val();
			});

			$.ajax({
				url:		'?acao=clas_editar',
				type:		'post',
				data:		data,
				success:	function (result) {
					$('#cla-preview')[0].innerHTML	= result;

					updateTooltips();
				}
			});
		}

		function _update_left_points() {
			var	points_left			= _max_points;
			var	points_left_formula	= _max_points_formula;

			$('#cla-selector select').each(function (select) {
				var	_			= $(this);
				var	is_formula	= parseInt($(this).data('formula'));

				if(_blocked[_.data('at')]) {
					return;
				}

				if(is_formula) {
					points_left_formula	-= _.val();
				} else {
					points_left	-= _.val();
				}
			});

			$('#cla-free-points').html(points_left + ' Pontos Disponiveis para ( Tai, Buk, Nin, Gen, Agi, Selo, For, Int ou Res )');
			$('#cla-free-points-formula').html(points_left_formula + ' Pontos Disponiveis para ( Conc, Conv, Perp ou Det )');
		}
			
		$('#cla-selector').on('change', 'select', function () {
			var points_used	= 0;
			var	points_left	= 0;
			var	is_formula	= parseInt($(this).data('formula'));
			var	allow_var	= is_formula ? _allowed_max_formula : _allowed_max;

			$('#cla-selector select').each(function (select) {
				var	_	= $(this);

				if(_blocked[_.data('at')]) {
					return;
				}

				if((is_formula && !parseInt(_.data('formula'))) || (!is_formula && parseInt(_.data('formula')))) {
					return;
				}

				if (_.val()) {
					points_used	+= parseInt(_.val());
				}
			});

			points_left	= (is_formula ? _max_points_formula : _max_points) - points_used;

			$('#cla-selector select').each(function (select) {
				var	_		= $(this);

				if(_blocked[_.data('at')]) {
					return;
				}

				if((is_formula && !parseInt(_.data('formula'))) || (!is_formula && parseInt(_.data('formula')))) {
					return;
				}

				var	current	= parseInt(_.val());
				var	html	= '';
				var	max		= parseInt(allow_var[_.data('at')] > points_left ? points_left : allow_var[_.data('at')]);

				if(!current) {
					for(var i = 0; i <= max; i++) {
						html	+= '<option value="' + i + '">' + i + '</option>';
					}
				} else if(points_left) {
					if(current < allow_var[_.data('at')]) {
						max	= current + points_left;

						if(max >= allow_var[_.data('at')]) {
							max	= allow_var[_.data('at')];
						}
					} else {
						max	= current;
					}

					for(var i = 0; i <= max; i++) {
						html	+= '<option value="' + i + '">' + i + '</option>';
					}
				} else if(!points_left) {
					for(var i = 0; i <= current; i++) {
						html	+= '<option value="' + i + '">' + i + '</option>';
					}					
				}

				_.html(html);

				if (this.options[current]) {
					this.options[current].selected	= true;
				}
			});

			_show_view();
			_update_left_points();
		});
		
		$('#cla-current').on('change', function () {
			_cla	= $('#cla-current').val();
			_reload_settings();

			$('.cla-desc').hide();
			$('#cla-desc-' + this.value).show();
		});

		$('#cla-join').on('click', function () {
			jconfirm("<?php echo t('vantagens_vip.vv29')?>", null, function () {
				lock_screen(true);

				var	id		= $('#cla-current').val();
				var	data	= {id: id, attributes: {}};

				$('#cla-selector select').each(function () {
					var	_	= $(this);

					if(_blocked[_.data('at')]) {
						return;
					}

					data.attributes[_.data('at')]	= _.val();
				});
				
				$.ajax({
					url:		'index.php?acao=cla_entrar',
					dataType:	'json',
					type:		'post',
					data:		data,
					success:	function (result) {
						if(result.success) {
							location.reload();
						} else {
							lock_screen(false);

							var	errors	= [];

							result.messages.forEach(function (error) {
								errors.push('<li>' + error + '</li>');
							});

							jalert('<ul>' + errors.join('') + '</ul>');
						}
					}
				});
			});
		});

		$('#cla-current').trigger('change');
	});
</script>

<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p><?php echo t('clas.c5')?></p></div><br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "8183366979";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Habilidades -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>         
<br/><br/>
<?php if(isset($_GET['ok']) && $_GET['ok'] == 1): ?>
	<!-- Mensagem nos Topos das Seções -->
	<?php msg('1',''. t('clas.c6').'', ''. t('clas.c7').' '. $basePlayer->nome_cla );?>
	<!-- Mensagem nos Topos das Seções -->	
<?php elseif(isset($_GET['ok']) && $_GET['ok'] == 2 && isset($_GET['h']) && $basePlayer->hasItem($_GET['h'])): ?>
<!-- Mensagem nos Topos das Seções -->
	<?php msg('2',''. t('clas.c6').'', ''. t('clas.c8').' '. $basePlayer->getItem($_GET['h'])->getAttribute('nome') .'!');?>
<!-- Mensagem nos Topos das Seções -->	
<?php endif; ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="subtitulo-home">
			<table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
				<tr>
				<td width="42" align="center">&nbsp;</td>
				<td width="153"><?php echo $basePlayer->id_cla ? "". t('clas.c9').": " : "". t('clas.c10').": " ?></td>				
				<td width="535" align="left">
					<select <?php echo $basePlayer->id_cla ? "disabled='disabled'" : "" ?> name="cla" id="cla-current">
						<?php foreach($clas->result_array() as $cla): ?>
						<option <?php echo $basePlayer->id_cla == $cla['id'] ? "selected='selected'" : "" ?> value="<?php echo $cla['id'] ?>"><?php echo $cla['nome'] ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div id="cla-desc-container">
	<?php foreach($clas->result_array() as $cla): ?>
		<div class="cla-desc" id="cla-desc-<?php echo $cla['id'] ?>">
			<p style="width: 720px; margin-top: 5px"><?php echo $cla['descricao_' . Locale::get()] ?></p>
		</div>
	<?php endforeach ?>
</div>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr id="desc_Sharingan">
		<td width="240" height="34" align="left" >
			<?php foreach($dvDescs as $k => $d): ?>
			<div id="dvCla_<?php echo $k ?>" class="claObj p_style" style="display: none;">
				<?php echo $d ?>
			</div>
			<?php endforeach; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php if (!$basePlayer->id_cla): ?>
				<span class="laranja">Adapte o Clã a sua necessidade alterando os valores abaixo se necessário, lembrando que ao clicar no botão Entrar no Clã você estará aceitando os valores abaixo escolhidos e só poderá altera-los abandonando o Clã.</span><br /><br />
				<div id="cla-selector" style="display: inline-block;">
					<?php foreach ($ats as $at): ?>
						<div class="cla-selector-item">
							<img src="<?php echo img('layout/icones/' . ($at == 'con' ? 'conhe' : $at) . '.png') ?>" />
							<br />
							<select  id="cla-selector-<?php echo $at ?>" data-at="<?php echo $at ?>" <?php echo in_array($at, $formulas) ? 'data-formula="1"' : '' ?>></select>
						</div>
					<?php endforeach ?>
					<div class="break"></div>
				</div>
				<div id="cla-free-points" class="azul" style="float: left;"></div>
				<div id="cla-free-points-formula" class="verde" style="float: right"></div>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<td height="34" align="center">
			<br /><br />
			<?php if(!$basePlayer->getAttribute('id_cla') && !$basePlayer->getAttribute('portao')): ?>
				<a class="button" id="cla-join"><?php echo t('botoes.entrar_no_cla') ?></a>
			<?php elseif(!$basePlayer->getAttribute('id_cla') && $basePlayer->getAttribute('portao')): ?>
				<a class="button ui-state-disabled"><?php echo t('botoes.entrar_no_cla') ?></a>
			<?php elseif($basePlayer->getAttribute('id_cla')): ?>
				<a class="button" onclick="<?php echo $js_function ?>()"><?php echo t('botoes.sair_do_cla') ?></a>
			<?php endif; ?>
		</td>
	</tr>
</table>
<br />

<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="subtitulo-home">
			<table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
				<tr>
					<td width="90" align="center">&nbsp;</td>
					<td width="172" align="center"><?php echo t('geral.nome')?></td>
					<td width="138" align="center"><?php echo t('geral.requerimentos')?></td>
					<td width="273" align="center"><?php echo t('geral.bonus')?></td>
					<td width="92" align="center"><?php echo t('geral.status')?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<table id="cla-preview" width="730" border="0" cellpadding="0" cellspacing="0">
</table>
</div>
