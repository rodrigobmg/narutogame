<?php
	$invocacoes			= Recordset::query('SELECT * FROM invocacao', true);
	$js_function		= $_SESSION['el_js_func_name'] = "f" . md5(rand(1, 512384));
	$pay_key_1			= $_SESSION['pay_key_1'] = round(rand(1, 999999)); // Coin
	$dvDescs			= array();
	$current_counter	= Player::getFlag('invocacao_sair_count', $basePlayer->id);

	$formulas			= [
		'conv',
		'conc',
		'det',
		'esq'
	];

	$ats				= [
		'ene',
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

	if($basePlayer->id_invocacao) {
		$blocked			= [];
		$distribute_data	= Recordset::query('SELECT * FROM invocacao WHERE id=' . $basePlayer->id_invocacao, true)->row();
		$base_item			= Recordset::query('SELECT * FROM item WHERE id_tipo=21 AND ordem=1 AND id_invocacao=' . $basePlayer->id_invocacao, true)->row();
		$mine_item			= Recordset::query('SELECT * FROM player_modificadores WHERE id_player=' . $basePlayer->id . ' AND id_tipo=21')->row();

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
<?php if(!$basePlayer->tutorial()->invocacao){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 3,
	 
	  steps: [
	  {
		element: "#invocacao-selector",
		title: "<?php echo t("tutorial.titulos.invocacao.1");?>",
		content: "<?php echo t("tutorial.mensagens.invocacao.1");?>",
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
<script type="text/javascript">
	function <?php echo $js_function ?>() {
		jconfirm("<?php echo t('invocacao.' . $exit_string)?>", null, function () {
			lock_screen(true);

			$.ajax({
				url: 'index.php?acao=invocacao_sair',
				dataType: 'script',
				type: 'post',
				data: {pm: <?php echo $pay_key_1 ?> }
			});
		});
	}

	$(document).ready(function () {
		var	_defaults				= [];
		var	_invocacao				= 0;
		var	_blocked				= [];

		var	_max_points				= 0;
		var	_allowed				= [];
		var	_allowed_max			= [];

		var	_max_points_formula		= 0;
		var	_allowed_formula		= [];
		var	_allowed_max_formula	= [];

		function _reload_settings() {
			$.ajax({
				url:		'?acao=invocacoes_editar',
				data:		{settings: 1, invocacao: _invocacao},
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
			$('#invocacao-selector select').removeAttr('disabled').parent().show();

			$('#invocacao-selector select').each(function (select) {
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

			$('#invocacao-selector select').trigger('change');

			_show_view();
			_update_left_points();
		}

		function _show_view() {
			var	data	= {preview: 1, invocacao: _invocacao};

			$('#invocacao-selector select').each(function () {
				var	_				= $(this);
				data[_.data('at')]	= _.val();
			});

			$.ajax({
				url:		'?acao=invocacoes_editar',
				type:		'post',
				data:		data,
				success:	function (result) {
					$('#invocacao-preview')[0].innerHTML	= result;

					updateTooltips();
				}
			});
		}

		function _update_left_points() {
			var	points_left			= _max_points;
			var	points_left_formula	= _max_points_formula;

			$('#invocacao-selector select').each(function (select) {
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

			$('#invocacao-free-points').html(points_left + ' Pontos Disponíveis para (Ene, Agi, Selo, For, Int ou Res )');
			$('#invocacao-free-points-formula').html(points_left_formula + ' Pontos Disponiveis para ( Conc, Conv, Perp ou Det )');
		}
		$('#invocacao-selector').on('change', 'select', function () {
			var points_used	= 0;
			var	points_left	= 0;
			var	is_formula	= parseInt($(this).data('formula'));
			var	allow_var	= is_formula ? _allowed_max_formula : _allowed_max;

			$('#invocacao-selector select').each(function (select) {
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

			$('#invocacao-selector select').each(function (select) {
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
		$('#invocacao-current').on('change', function () {
			_invocacao	= $('#invocacao-current').val();
			_reload_settings();

			$('.invocacao-desc').hide();
			$('#invocacao-desc-' + this.value).show();
		});

		$('#invocacao-join').on('click', function () {
			jconfirm("<?php echo t('invocacao.i2')?>", null, function () {
				lock_screen(true);

				var	id		= $('#invocacao-current').val();
				var	data	= {id: id, attributes: {}};

				$('#invocacao-selector select').each(function () {
					var	_	= $(this);

					if(_blocked[_.data('at')]) {
						return;
					}

					data.attributes[_.data('at')]	= _.val();
				});
				
				$.ajax({
					url:		'index.php?acao=invocacao_entrar',
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

		$('#invocacao-current').trigger('change');
	});
</script>

<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p><?php echo t('invocacao.i1')?></p></div><br />
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
	<?php msg('1',''. t('clas.c6').'', ''. t('clas.c7').' '. $basePlayer->nome_invocacao );?>
	<!-- Mensagem nos Topos das Seções -->	
<?php elseif(isset($_GET['ok']) && $_GET['ok'] == 2 && isset($_GET['h']) && $basePlayer->hasItem($_GET['h'])): ?>
<!-- Mensagem nos Topos das Seções -->
	<?php msg('2',''. t('clas.c6').'', ''. t('clas.c8').' '. $basePlayer->getItem($_GET['h'])->getAttribute('nome') .'!');?>
<!-- Mensagem nos Topos das Seções -->	
<?php endif; ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="49" class="subtitulo-home">
			<table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
				<tr>
				<td width="42" align="center">&nbsp;</td>
				<td width="153"><?php echo $basePlayer->id_invocacao ? "". t('invocacao.i5').": " : "". t('invocacao.i6').": " ?></td>				
				<td width="535" align="left">
					<select <?php echo $basePlayer->id_invocacao ? "disabled='disabled'" : "" ?> name="invocacao" id="invocacao-current">
						<?php foreach($invocacoes->result_array() as $invocacao): ?>
						<option <?php echo $basePlayer->id_invocacao == $invocacao['id'] ? "selected='selected'" : "" ?> value="<?php echo $invocacao['id'] ?>"><?php echo $invocacao['nome_'.Locale::get()] ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div id="invocacao-desc-container">
	<?php foreach($invocacoes->result_array() as $invocacao): ?>
		<div class="invocacao-desc" id="invocacao-desc-<?php echo $invocacao['id'] ?>">
			<p style="width: 720px; margin-top: 5px"><?php echo $invocacao['descricao_' . Locale::get()] ?></p>
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
			<?php if (!$basePlayer->id_invocacao): ?>
				<span class="laranja">Adapte o Invocação a sua necessidade alterando os valores abaixo se necessário, lembrando que ao clicar no botão Entrar no Clã você estará aceitando os valores abaixo escolhidos e só poderá altera-los abandonando o Clã.</span><br /><br />
				<div id="invocacao-selector" style="display: inline-block;">
					<?php foreach ($ats as $at): ?>
						<div class="invocacao-selector-item">
							<img src="<?php echo img('layout/icones/' . ($at == 'con' ? 'conhe' : $at) . '.png') ?>" />
							<br />
							<select  id="invocacao-selector-<?php echo $at ?>" data-at="<?php echo $at ?>" <?php echo in_array($at, $formulas) ? 'data-formula="1"' : '' ?>></select>
						</div>
					<?php endforeach ?>
					<div class="break"></div>
				</div>
				<div id="invocacao-free-points" class="azul" style="float: left;"></div>
				<div id="invocacao-free-points-formula" class="verde" style="float: right"></div>
			<?php endif ?>
		</td>
	</tr>
	<tr>
		<td height="34" align="center">
			<br /><br />
			<?php if(!$basePlayer->getAttribute('id_invocacao')): ?>
				<a class="button" id="invocacao-join"><?php echo t('botoes.aceitar_pacto') ?></a>
			<?php elseif($basePlayer->getAttribute('id_invocacao')): ?>
				<a class="button" onclick="<?php echo $js_function ?>()"><?php echo t('botoes.quebrar_pacto') ?></a>
			<?php endif; ?>
		</td>
	</tr>
</table>
<br />

<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="49" class="subtitulo-home">
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
<table id="invocacao-preview" width="730" border="0" cellpadding="0" cellspacing="0">
</table>
</div>
