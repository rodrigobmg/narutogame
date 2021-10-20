<?php
	$rU 				= Recordset::query("SELECT * FROM global.user WHERE id=" . $_SESSION['usuario']['id'])->row_array();
	$dataAniversario	= explode ("-",$rU['birthday']);
	$promocao_ativa	= Recordset::query('
		SELECT
			a.*,
			b.nome_br,
			b.nome_en
		FROM
			promocao_usuario a JOIN promocao b ON b.id=a.promocao_id

		WHERE
			b.ativo=1
			AND a.promocao_id=5
			AND a.utilizado=0
			AND a.usuario_id=' . $_SESSION['usuario']['id']);
?>
<script type="text/javascript">
	function validarEdicao() {
		var frm = $F('fCadastro');

		if(!frm.nome.value.match(/^[\w\'\s]+$/i)) {
			alert(". <?php echo t('dados_cadastrais.nome_erro') ?> . ");
			return false;
		}

		if(frm.cep.value) {
			if(!frm.cep.value.match(/[0-9]{8}/)) {
				alert(". <?php echo t('dados_cadastrais.cep_erro') ?> . ");
				return false;
			}
		}

		if(frm.endereco.value) {
			if(!frm.endereco.value.match(/[\w\']+/)) {
				alert(". <?php echo t('dados_cadastrais.endereco_erro') ?> . ");
				return false;
			}
		}

		/*
		if(frm.bairro.value) {
			if(!frm.bairro.value.match(/[\w\']+/)) {
				alert("Campo 'Bairro' com valor inválido! Verifique o dado e tente novamente.");
				return false;
			}
		}
		*/

		if(frm.cidade.value) {
			if(!frm.cidade.value.match(/[\w\']+/)) {
				alert("Campo 'Cidade' com valor inválido! Verifique o dado e tente novamente.");
				return false;
			}
		}

		$.ajax({
			url: 'index.php?acao=usuario_dados_editar',
			type: 'post',
			data: $("#fCadastro").serialize(),
			dataType: 'script'
		});
	}

	<?php if($promocao_ativa->num_rows): ?>

	$(document).ready(function () {
		$('#b-resgatar').on('click', function () {
			var	d	= $(document.createElement('DIV'));

			$(document.body).append(d);

			d.css('text-align', 'center');

			d.html('Digite o código que você recebeu abaixo<br /><br /><input type="text" />').dialog({
				modal:		true,
				height:		150,
				title:		'Resgatar código promocional',
				buttons:	{
					'Resgatar':	function () {
						var	code	= $('input', d).val();

						if(!code.length) {
							return;
						}

						lock_screen(true);

						$.ajax({
							url:		'?acao=promocao',
							data:		{promocao: <?php echo $promocao_ativa->row()->promocao_id ?>, code: code},
							dataType:	'json',
							type:		'post',
							success:	function (result) {
								lock_screen(false);

								if(result.success) {
									d.remove();

									jalert('Código resgatado com sucesso', null, function () {
										location.reload();
									});
								} else {
									jalert('Os seguites problemas impediram que a operação fosse completada:<br /><br />' + result.messages.join('<br />'));
								}
							}
						});
					},
					'Cancelar':	function () {
						d.remove();
					}
				}
			});
		});
	});
	<?php endif ?>
</script>
<form name="fCadastro" id="fCadastro" method="post" onSubmit="return false">
<div class="titulo-secao"><p><?php echo t('titulos.dados_da_conta');?></p></div>
  <?php
	  	if(isset($_GET['ok'])) {
			 msg('2',''. t('dados_cadastrais.dados_atualizados'). '', ''. t('dados_cadastrais.dados_atualizados_msg'). '');
		}
	  ?>
  <div id="cnCadastro">
	<?php msg('1',''. t('dados_cadastrais.atualize'). '', ''. t('dados_cadastrais.atualize_msg'). '');?>
	<br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "3973493377";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Meus Dados -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script><br /> <br />
        <?php /* if($_SESSION['isAdmin']): */ ?>
        	<?php if(!$rU['has_fb']): ?>
			<?php msg('1',''. t('dados_cadastrais.facebook'). '', ''. t('dados_cadastrais.facebook_msg'). '<br /><br /><div><a class="btn-facebook" href="javascript:;" onclick="location.href=\''. $loginUrl .'\'">Conecte sua conta com o facebook</a></div>');?>
   			<?php endif; ?>
   		<?php /* endif; */ ?>
<?php if($promocao_ativa->num_rows): ?>
<div align="center">
	<?php if(!$_SESSION['basePlayer']): ?>
		<?php msg('8','Resgatar código da promoção', 'Selecione primeiro um personagem da sua conta para resgatar o código');?>
	<?php else: ?>
		<a id="b-resgatar" class="button">Resgatar código da promoção "<?php echo $promocao_ativa->row()->{'nome_' . Locale::get()} ?>"</a>
	<?php endif ?>
</div>
<?php endif ?>
   		<br />
   		<br />
   		<br />

    <table width="730" border="0" cellpadding="3" cellspacing="0" id="cadastro">
      <tr>
        <td width="50%" align="left"><span class="cinza" style="font-size: 13px"><?php echo t('geral.nome');?></span></td>
        <td width="50%" align="left"><span class="cinza" style="font-size: 13px">Email</span></td>
      </tr>
      <tr>
        <td align="left"><input name="nome" type="text" id="nome" style="width: 350px"  maxlength="50" value="<?php echo $rU['name'] ?>" /></td>
        <td align="left"><input name="email" type="text" id="email"  style="width: 350px" maxlength="25" value="<?php echo $rU['email'] ?>" disabled="disabled"/></td>
      </tr>
      <tr>
        <td align="left"><span class="cinza" style="font-size: 13px"><?php echo t('dados_cadastrais.sexo');?></span></td>
        <td align="left"><span class="cinza" style="font-size: 13px"><?php echo t('dados_cadastrais.data_nasc');?></span></td>
      </tr>
      <tr>
        <td align="left">
        <?php
	    	if($rU['sex'] == 0){
				$mSel = 'selected="selected"';
				$fSel = '';
			}else{
				$fSel = 'selected="selected"';
				$mSel = '';
			}
		?>
          <select name="sexo" id="sexo" style="width: 350px">
            <option value="0" <?php echo $mSel;?>><?php echo t('dados_cadastrais.masculino'); ?></option>
            <option value="1" <?php echo $fSel;?>><?php echo t('dados_cadastrais.feminino'); ?></option>
          </select></td>
        <td align="left"><input name="dia" type="text" id="dia" style="width: 109px" maxlength="2" value="<?php echo isset($dataAniversario[2]) ? $dataAniversario[2] : '' ?>" />
          /
          <input name="mes" type="text" id="mes" maxlength="2" style="width: 109px" value="<?php echo isset($dataAniversario[1]) ? $dataAniversario[1] : '' ?>"/>
          /
          <input name="ano" type="text" id="ano" maxlength="4" style="width: 109px" value="<?php echo isset($dataAniversario[0]) ? $dataAniversario[0] : '' ?>"/></td>
      </tr>
      <tr>
        <td align="left"><span class="cinza" style="font-size: 13px"><?php echo t('dados_cadastrais.cep'); ?></span></td>
        <td align="left"><span class="cinza" style="font-size: 13px"><?php echo t('dados_cadastrais.end_completo'); ?></span></td>
      </tr>
      <tr>
        <td align="left"><input name="cep" type="text" id="cep" maxlength="50" style="width: 350px" value="<?php echo $rU['zip'] ?>" /></td>
        <td align="left"><input name="endereco" type="text" id="endereco"  style="width: 350px" maxlength="50" value="<?php echo $rU['street'] ?>" /></td>
      </tr>
      <tr>
        <td align="left"><span class="cinza" style="font-size: 13px"><?php echo t('dados_cadastrais.cidade'); ?></span></td>
        <td align="left"><span class="cinza" style="font-size: 13px"><?php echo t('dados_cadastrais.bairro'); ?></span></td>
      </tr>
      <tr>
        <td width="247" align="left"><input name="cidade" type="text" id="cidade" style="width: 350px" maxlength="50" value="<?php echo $rU['city'] ?>" /></td>
        <td align="left"><input name="bairro" type="text" id="bairro" style="width: 350px" maxlength="50" value="<?php echo $rU['neighborhood'] ?>" /></td>
      </tr>
      <tr>
        <td align="left"><span class="cinza" style="font-size: 13px"><?php echo t('dados_cadastrais.estado'); ?></span></td>
        <td align="left"><span class="cinza" style="font-size: 13px"><?php echo t('dados_cadastrais.pais'); ?></span></td>
      </tr>
      <tr>
        <td align="left"><input type="text" name="estado" maxlength="40" style="width: 350px" value="<?php echo $rU['state'] ?>" /></td>
        <td align="left"><select name="pais" style="width: 350px">
            <?php
                    	$qPais = Recordset::query("SELECT * FROM cadastro_pais", true);

						foreach($qPais->result_array() as $rPais) {
							$sel = $rPais['id'] == $rU['id_country'] ? "selected='selected'" : "";

							echo "<option value='" . $rPais['id'] . "' $sel>" . htmlspecialchars($rPais['nome']) . "</option>";
						}
					?>
          </select></td>
      </tr>
      <tr>
        <td colspan="2" align="center">&nbsp;</td>
      </tr>
      <tr>
      	<td colspan="2" align="center">
			<span class="laranja" style="font-size: 13px"><?php echo t('classes.c49')?>.</span><br />
			<input type="checkbox" name="msg_vip" id="msg_vip"  <?php echo $rU['msg_vip']==1 ? "checked='checked'" : '' ?> />
			 <span class="verde" style="font-size: 13px"><?php echo t('indique.i7'); ?></span><br />
			 <br /><br /></td>
      	</tr>
      <tr>
      	<td colspan="2" align="center">
			<span class="laranja" style="font-size: 13px"><?php echo t('usuario_dados.audio_notifications')?>.</span><br />
			<input type="checkbox" name="sound" id="sound"  <?php echo $rU['sound']==1 ? "checked='checked'" : '' ?> />
			 <span class="verde" style="font-size: 13px"><?php echo t('indique.i7'); ?></span><br />
			 <br /><br /></td>
      	</tr>
      <tr>
        <td colspan="2" align="center">
		<span class="laranja" style="font-size: 13px"><?php echo t('dados_cadastrais.newsletter'); ?></span><br />
        <input type="checkbox" name="newsletter" id="newsletter"  <?php echo $rU['newsletter']==1 ? "checked='checked'" : '' ?> />
        <span class="verde" style="font-size: 13px"><?php echo t('indique.i7'); ?></span></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><br />
          <input type="submit" class="button" role="button" onclick="validarEdicao()" value="<?php echo t('botoes.enviar_alteracoes') ?>" /></td>
      </tr>
    </table>
  </div>
</form>
<?php if($_SESSION['usuario']['id'] == 1): ?>
<?php
/*
	fb_post_to_feed('teste ', array(
		'link'	=> 'http://narutogame.com.br'
	));
*/
?>
<?php endif; ?>
