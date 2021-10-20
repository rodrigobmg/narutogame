<?php 
	$usuario_blocked = false;
	
	if(Recordset::query("SELECT * FROM suporte_blocked WHERE usuario_id = ". $_SESSION['usuario']['id']."")->num_rows){
		$usuario_blocked = true;
	}

	$redir_script	= true;
	$errors			= array();
	$categories		= array(
		'bug'			=> t('suporte.categories.bug'),
		'vip'			=> t('suporte.categories.vip'),
		'question'		=> t('suporte.categories.question'),
		'suggestion'	=> t('suporte.categories.suggestion'),
		'other'			=> t('suporte.categories.other')
	);

	if($_POST) {
		if(!isset($_POST['category']) && (isset($_POST['title']) && !in_array($_POST['category'], array_keys($categories)))) {
			$errors[]	= t('suporte.erros.categoria');
		}

		if(!isset($_POST['title']) || (isset($_POST['title']) && !$_POST['title'])) {
			$errors[]	= t('suporte.erros.titulo');
		}

		if(!isset($_POST['content']) || (isset($_POST['content']) && !$_POST['content'])) {
			$errors[]	= t('suporte.erros.conteudo');
		}

		if(!isset($_POST['date']) || (isset($_POST['date']) && !$_POST['date'])) {
			$errors[]	= t('suporte.erros.data');
		}

		if($_FILES && isset($_FILES['attachments'])) {
			$allowed_exts	= array('png', 'jpg', 'bmp');
			$allowed_types	= array('image/png', 'image/jpeg', 'images/jpg', 'image/bmp');
			
			for($f = 0; $f < sizeof($_FILES['attachments']['name']); $f++) {
				if(!$_FILES['attachments']['error'][$f] && $_FILES['attachments']['name'][$f]) {
					$ext	= substr($_FILES['attachments']['name'][$f], -3, 3);
					$type	= $_FILES['attachments']['type'][$f];
					
					if(!in_array($ext, $allowed_exts)) {
						$errors[]	= t('suporte.erros.ext_invalido');
					}

					if(!in_array($type, $allowed_types)) {
						$errors[]	= t('suporte.erros.tipo_invalido');
					}
				}
			}
		}

		if(!sizeof($errors)) {
			$id	= Recordset::insert('suporte', array(
				'user_id'		=> $_SESSION['usuario']['id'],
				'player_id'		=> $_SESSION['basePlayer'],
				'category'		=> $_POST['category'],
				'status'		=> 'new',
				'title'			=> $_POST['title'],
				'ua'			=> isset($_POST['current_ua']) && $_POST['current_ua'] ? $_SERVER['HTTP_USER_AGENT'] : $_POST['ua'],
				'when'			=> $_POST['date'] . ' ' . $_POST['hour'] . ':' . $_POST['minute'] . ':00',
				'created_at'	=> array('escape' => false, 'value' => 'NOW()')
			));
			
			Recordset::insert('suporte_resposta', array(
				'suporte_id'	=> $id,
				'user_id'		=> $_SESSION['usuario']['id'],
				'created_at'	=> array('escape' => false, 'value' => 'NOW()'),
				'content'		=> $_SESSION['universal'] ? $_POST['content'] : htmlentities(substr($_POST['content'], 0, 5000))
			));			

			if($_FILES && isset($_FILES['attachments'])) {
				for($f = 0; $f < sizeof($_FILES['attachments']['name']); $f++) {
					if(!$_FILES['attachments']['error'][$f] && $_FILES['attachments']['name'][$f]) {
						$filename	= uniqid() . '.' . uniqid();
						$ext		= substr($_FILES['attachments']['name'][$f], -3, 3);
						
						move_uploaded_file($_FILES['attachments']['tmp_name'][$f], 'suporte_upload/' . $filename . '.' . $ext);
						
						Recordset::insert('suporte_arquivos', array(
							'suporte_id'	=> $id,
							'file'			=> $filename . '.' . $ext
						));
					}
				}
			}			
		}
		
		if(!sizeof($errors)) {
			redirect_to('suporte', NULL, array('created' => 1));
		}
	}
?>
<script type="text/javascript">
	head.ready(function () {
		$(document).ready(function () {
			$('#f-new-ticket input[name=date]').datepicker({'dateFormat': 'yy-mm-dd'});
			
			$('#f-new-ticket input[name=current_ua]').on('click', function () {
				if(!this.checked) {
					$('#f-new-ticket input[name=ua]').removeAttr('disabled');
				} else {
					$('#f-new-ticket input[name=ua]').attr('disabled', 'disabled');
				}
			});
			
			$('.add-file').on('click', function () {
				$('<p><label><?php echo t('suporte.arquivo') ?></label><input type="file" name="attachments[]" /></p>').insertBefore($('.file-divider'));
			});
		});		
	});
</script>
<style type="text/css">
	form {
		text-align: left	
	}

	form p {
		margin-bottom: 10px;
		height: 20px
	}

	form p label {
		float: left;
		margin-right: 10px;
		width: 120px;
		font-weight: bold;
		margin-top: 6px
	}
	
	form input {
		margin: 0px	
	}
	
	form legend {
		margin-bottom: 10px	
	}
	
	#f-new-ticket hr {
		border: none;
		border-top: dotted 1px #FFF;
		width: 765px;
		float: left
	}
</style>
<div class="titulo-secao"><p><?php echo t('suporte.novo') ?></p></div>
<?php if($_POST && sizeof($errors)): ?>
<?php ob_start() ?>
<ul>
	<?php foreach($errors as $error): ?>
	<li><?php echo $error ?></li>
	<?php endforeach ?>
</ul>
<?php
	msg('22','Problema', sprintf(t('suporte.erros.ticket_novo'), ob_get_clean()));
?>
<?php endif ?>
<?php if(!$usuario_blocked){?>
<form id="f-new-ticket" enctype="multipart/form-data" method="post" class="cor_nao" style="padding:10px; <?php echo LAYOUT_TEMPLATE == "_azul" ? "width: 737px;":"width: 730px;"?>">
	<p>
		<label><?php echo t('suporte.categoria') ?></label>
		<select name="category">
			<option value="bug"><?php echo t('suporte.categories.bug') ?></option>
			<option value="vip"><?php echo t('suporte.categories.vip') ?></option>
			<option value="question"><?php echo t('suporte.categories.question') ?></option>
			<option value="suggestion"><?php echo t('suporte.categories.suggestion') ?></option>
			<option value="other"><?php echo t('suporte.categories.other') ?></option>
		</select>		
	</p>
	<p>
		<label><?php echo t('suporte.titulo') ?></label>
		<input type="text" maxlength="50" name="title" />
	</p>
	<p>
		<label><?php echo t('suporte.data') ?></label>
		<input type="text" name="date" />
		<select name="hour">
			<?php for($f = 0; $f <= 23; $f++): ?>
			<option value="<?php echo $f ?>"><?php echo str_pad($f, 2, '0', STR_PAD_LEFT) ?></option>
			<?php endfor ?>
		</select>
		<select name="minute">
			<?php for($f = 0; $f <= 60; $f++): ?>
			<option value="<?php echo $f ?>"><?php echo str_pad($f, 2, '0', STR_PAD_LEFT) ?></option>
			<?php endfor ?>
		</select>
	</p>
	<p>
		<label><?php echo t('suporte.navegador') ?></label>
		<input type="text" name="ua" />
		<input type="checkbox" name="current_ua" value="1" /> <?php echo t('suporte.navegador_atual') ?>
	</p>	
	<p>
		<label><?php echo t('suporte.descricao') ?></label>
	</p>
	<textarea name="content" maxlength="5000" data-length-watch="#ticket-chars-left" style="width: 700px; height: 200px"></textarea>
	<br />
	<?php echo t('geral.caracteres') ?>: <span id="ticket-chars-left"></span>
	<hr style="float: left; border: dotted 1px <?php echo LAYOUT_TEMPLATE == "_azul" ? "#0f294a":"#413625"?>; width: 97%" />
   
	<div class="break"></div> <br /><br />
	<fieldset>
		<legend><?php echo t('suporte.arquivos') ?></legend>
		<p>
			<label><?php echo t('suporte.arquivo') ?></label>
			<input type="file" name="attachments[]" />
		</p>
		<hr style="float: left; border: dotted 1px <?php echo LAYOUT_TEMPLATE == "_azul" ? "#0f294a":"#413625"?>; width: 97%" class="file-divider" />
		<a class="button add-file"><?php echo t('suporte.adicionar_arquivo') ?></a>
	</fieldset>
	<hr style="float: left; border: dotted 1px <?php echo LAYOUT_TEMPLATE == "_azul" ? "#0f294a":"#413625"?>; width: 97%" /><br /><br />
	<div align="center">
		<a class="button" data-trigger-form="1"><?php echo t('botoes.criar') ?></a>
	</div>
</form>
<?php }else{?>
<?php
	msg('4','Conta Banida de Postar Tickets', 'A sua conta foi banida por mal uso da ferramenta de tickets, esperamos que agora você aprenda a valorizar nosso tempo e tenha mais respeito com quem realmente precisa de ajuda no jogo.');
?>
<?php }?>
