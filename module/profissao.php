<?php
  $descriptions = [];
  $professions = Recordset::query("SELECT * FROM profissao", true);
  $profession_level = Player::getFlag('profissao_nivel', $basePlayer->id);
  $current_counter = Player::getFlag('profissao_sair_count', $basePlayer->id);
  $active_count = Player::getFlag('profissao_ativa_vezes', $basePlayer->id);
  $active_limit = 1;

  if(!$current_counter) {
    $exit_string	= 'sair1';
  } elseif($current_counter == 1) {
    $exit_string	= 'sair2';
  } else {
    $exit_string	= 'sair3';
  }
?>
<?php if(!$basePlayer->tutorial()->profissao){?>
<script>
 $("#topo2").css("z-index", 'initial');
 $("#menu-container").css("z-index", 'initial');
$(function () {
    var tour = new Tour({
	  backdrop: true,
	  page: 11,
	 
	  steps: [
	  {
		element: ".msg_gai",
		title: "<?php echo t("tutorial.titulos.profissao.1");?>",
		content: "<?php echo t("tutorial.mensagens.profissao.1");?>",
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
	$(document).ready(function () {
		$('#profession-selector').on('change', function () {
			$('.profession-container').hide();
			$('.profession-description-container').hide();

			$('#profession-container-' + this.value).show();
			$('#profession-description-container-' + this.value).show();
		}).trigger('change');

		$('.profession-container').on('click', '.learn', function () {
			lock_screen(true);

			$.ajax({
				url:		'?acao=profissao_treinar',
				data:		{level: $(this).data('level')},
				dataType:	'json',
				type:		'post',
				success:	function (result) {
					if (result.success) {
						location.reload();
					} else {
						lock_screen(false);
					}
				}
			});
		});
	});

	function learn_profession() {
		lock_screen(true);

		$.ajax({
			url:		'?acao=profissao_aprender',
			data:		{learn: 1, profession: $('#profession-selector').val()},
			dataType:	'json',
			type:		'post',
			success:	function (result) {
				if (result.success) {
					location.reload();
				} else {
					lock_screen(false);
				}
			}
		});
	}

	function active_profession() {
		lock_screen(true);

		$.ajax({
			url:		'?acao=profissao_ativa',
			dataType:	'json',
			type:		'post',
			success:	function (result) {
				if (result.success) {
					location.reload();
				} else {
					lock_screen(false);
					format_error(result);
				}
			}
		});
	}

	function forget_profession() {
		jconfirm('<?php echo t('profissao.' . $exit_string) ?>', null, function () {
			lock_screen(true);

			$.ajax({
				url:		'?acao=profissao_aprender',
				data:		{unlearn: 1},
				type:		'post',
				dataType:	'json',
				success:	function (result) {
					if (result.success) {
						location.reload();
					} else {
						lock_screen(false);
						format_error(result);
					}
				}
			});
		})
	}
</script>
<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p><?php echo t('profissao.titulo'); ?></p></div><br />
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

<?php msg('1',''.t('academia_jutsu.parabens').'', ''.t('selos.possui').''. $basePlayer->nome_selo .'!');?>
	
<?php elseif(isset($_GET['ok']) && $_GET['ok'] == 2 && isset($_GET['h']) && $basePlayer->hasItem($_GET['h'])): ?>

<?php msg('2',''.t('academia_jutsu.parabens').'', ''.t('selos.aprendeu').' '. $basePlayer->getItem($_GET['h'])->getAttribute('nome') .'!');?>

<?php endif; ?>
<?php
	if(isset($_GET['existent'])) {

msg('3',''.t('academia_jutsu.parabens').'', ''.t('selos.treinou').'');
	}
?>
<div class="msg_gai">
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
				<b><?php echo t('profissao.titulo'); ?></b>
				<p>
					<?php echo $basePlayer->id_profissao ? "".t('profissao.p1')." " : t('profissao.p2') . " " ?>
					<select <?php echo $basePlayer->id_profissao ? "disabled='disabled'" : "" ?> name="profession" id="profession-selector">
						<?php foreach($professions->result_array() as $profession): ?>
							<option <?php echo $basePlayer->id_profissao == $profession['id'] ? "selected='selected'" : "" ?> value="<?php echo $profession['id'] ?>"><?php echo $profession['nome_'.Locale::get()] ?></option>
							<?php
								$descriptions[$profession['id']] = $profession['descricao_'.Locale::get()];
							?>
						<?php endforeach ?>
					</select>
					<br /><br />
					<?php foreach($descriptions as $k => $description): ?>
					<div id="profession-description-container-<?php echo $k ?>" class="profession-description-container" style="display: none">
						<span class="cinza"><?php echo $description ?></span>
					</div>
					<?php endforeach ?>
					<br />
					<?php if(!$basePlayer->id_profissao): ?>
						<a class="button" onclick="learn_profession()"><?php echo t('profissao.aprender') ?></a>
					<?php else: ?>
						<?php if ($profession_level >= 1): ?>
							<?php if ($active_count < $active_limit): ?>
								<a class="button" onclick="active_profession()"><?php echo t('profissao.ativa') ?></a>
							<?php else: ?>
								<a class="button ui-state-disabled"><?php echo t('profissao.ativa') ?></a>
							<?php endif ?>
						<?php endif ?>

						<a class="button" onclick="forget_profession()"><?php echo t('profissao.esquecer') ?></a>
					<?php endif; ?>
					<br /><br />
					<span class="laranja">A Habilidade ativa reseta todos os dias às 00:00hs.</span>	
				</p>
		</div>
	</div>
</div>
<br />
 <table width="730" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td height="49"><table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0" class="bold_branco">
          <tr>
            <td width="70" align="center">&nbsp;</td>
            <td width="150" align="center"><?php echo t('geral.nome') ?></td>
            <td width="100" align="center">Req.</td>
            <td width="200" align="center"><?php echo t('geral.g90') ?></td>
            <td width="105" align="center">Status</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php foreach($professions->result_array() as $profession): ?>
<table style="display: none" width="730" class="profession-container" border="0" cellpadding="0" cellspacing="0" id="profession-container-<?php echo $profession['id'] ?>">
	<?php
		if($basePlayer->id_profissao && $profession['id'] != $basePlayer->id_profissao) {
			echo '</table>';
			continue;
		}
	
		$color_counter = 0;
	?>
	<?php for($level = 1; $level <= 5; $level++): ?>
		<?php
			$bg		= ++$color_counter % 2 ? "class='cor_sim'" : "class='cor_nao'";
			$id		= 'i-profession-' . $profession['id'] . '-' . $level;
			$reqs	= Profession::hasRequirement($level, $basePlayer, $profession);
		?>
	    <tr <?php echo $bg ?>>
			<td width="70"  align="center">
				<div class="img-lateral-dojo2">
					<img src="<?php echo img('layout/profissao/' . $profession['id'] . '/' . $level . '.png')?>" width="53" height="53" style="margin-top:5px" />
				</div>	
			</td>
			<td width="150" align="center"><strong class="amarelo" style="font-size:13px"><?php echo $profession['nome_' . Locale::get()] ?> - Lvl. <?php echo $level ?></strong></td>
			<td width="100" align="center">
				<img id="<?php echo $id ?>" src="<?php echo img('layout/requer.gif') ?>" style="cursor: pointer" />
				<?php echo generic_tooltip($id, Profession::getRequirementLog()) ?>
			</td>
			<td height="34" align="center" class="bonus-text" width="200">
				<?php if($profession['medico_passivo']): ?>
				<p>
					<?php echo t('profissao.bonus1') ?> <span class="verde" style="font-size:12px"><?php echo 5 +( $level * 5)?>%</span> <?php echo t('profissao.bonus2') ?>
				</p>
				<?php endif; ?>
				<?php if($profession['cozinheiro_passivo']): ?>
				<p>
					<?php echo t('profissao.bonus3') ?> <span class="verde" style="font-size:12px"><?php echo 5 +( $level * 5)?>%</span>.
				</p>
				<?php endif; ?>
				<?php if($profession['ferreiro_passivo']): ?>
				<p>
					<?php echo t('profissao.bonus4') ?> <span class="verde" style="font-size:12px"><?php echo 5 +( $level * 5)?>%</span>.
				</p>
				<?php endif; ?>
				<?php if($profession['cacador_passivo']): ?>
				<p>
					<?php echo t('profissao.bonus5') ?> <span class="verde" style="font-size:12px"><?php echo $level * 3?>%</span> <?php echo t('profissao.bonus6') ?>
				</p>
				<?php endif; ?>
				<?php if($profession['instrutor_passivo']): ?>
				<p>
					<?php echo t('profissao.bonus7') ?> <span class="verde" style="font-size:12px"><?php echo 15 +( $level * 5)?>%</span>.
				</p>
				<?php endif; ?>
				<?php if($profession['aventureiro_passivo']): ?>
				<p>
					<?php echo t('profissao.bonus8') ?> <span class="verde" style="font-size:12px"><?php echo ( $level * 3)?> <?php echo t('profissao.bonus9') ?></span>.
				</p>
				<?php endif; ?>
			</td>
			<td width="105" align="center">
				<?php if($reqs && $level > $profession_level && $basePlayer->id_profissao): ?>
					<a class="button learn" data-level="<?php echo $level ?>"><?php echo t('botoes.treinar') ?></a>
				<?php else: ?>
					<?php if($level <= $profession_level): ?>
						<a class="button ui-state-green"><?php echo t('botoes.treinado') ?></a>
					<?php else: ?>
						<a class="button ui-state-disabled"><?php echo t('botoes.treinar') ?></a>
					<?php endif; ?>
				<?php endif; ?>
			</td>
		</tr>
		<tr height="4"></tr>
	<?php endfor; ?>
  </table>
<?php endforeach; ?>
</div>
