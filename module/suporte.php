<?php
	$_SESSION['ticket_token']	= uniqid();

	if(!isset($_SESSION['support_step'])) {
		$_SESSION['support_step']	= 1;
		
		unset($_SESSION['support_step_2']);
		unset($_SESSION['support_step_3']);
		unset($_SESSION['support_step_4']);
		unset($_SESSION['support_step_5']);
	} else {
		if(!isset($_GET['step'])) {
			$_SESSION['support_step']	= 1;

			unset($_SESSION['support_step_2']);
			unset($_SESSION['support_step_3']);
			unset($_SESSION['support_step_4']);
			unset($_SESSION['support_step_5']);
		} else {
			if(isset($_SESSION['support_step_' . $_GET['step']]) && $_SESSION['support_step_' . $_GET['step']]) {
				$_SESSION['support_step']	= $_GET['step'];
			} else {
				$_SESSION['support_step']	= 1;
			}
		}
	}	
?>
<style>
	.paginator a {
		display: block;
		float: left;
		margin: 2px;
		width: 20px;
	}	
	.step1-topics .title {
		font-size: 13px !important;
		margin-bottom: 5px
	}
	
	#support-next-step {
		margin-top:  10px
	}
	
	.topic-type-list {
		margin-top: 10px
	}
	
	.topic-type-list li {
		float: left;
		width: 182px;
		margin-bottom: 5px
	}

	.topic-type-list li a {
		width: 80%
	}
</style>
<script type="text/javascript">
	$(document).ready(function () {
		function _refresh(page) {
			page	= page || 0;

			$('#f-search input[name=page]').val(page);
			
			$.ajax({
				url:		'?acao=suporte',
				type:		'post',
				dataType:	'json',
				data:		$('#f-search').serialize(),
				success:	function (result) {
					if(result.success) {
						$('#search-result').html(result.content);
						
						$('#search-result .paginator a').on('click', function () {
							_refresh($(this).data('page'));
						});
					}
				}
			});
		}
		
		$('#f-search').on('submit', function () {
			$('#f-search input[name=page]').val('0');
			_refresh()
		});
		
		var	_step_cb	= function () {
			$('#support-next-step').on('click', function () {
				$(this).hide();
				lock_screen(true);
	
				$.ajax({
					url:		'?acao=suporte',
					type:		'post',
					dataType:	'json',
					data:		{ticket_token: '<?php echo $_SESSION['ticket_token'] ?>'},
					success:	function (result) {
						if(result.success) {
							location.href	= '?secao=suporte&step=' + result.step
						} else {
							jalert('Ops! Aconteceu algum problema! Se você abriu mais de uma página da tela de suporte, isso pode ter causado um problema de verificação nas etapas.');
						}
					}
				});
			});			
		}
		
		$('#step3-search-form input[type=button]').on('click', function () {
			$.ajax({
				url:		'?acao=suporte',
				type:		'post',
				dataType:	'json',
				data:		{keyword: $('#step3-search-form [name=keyword]').val()},
				success:	function (result) {
					if(result.success) {
						$('#step3-results').html(result.data);
						_step_cb();
					} else {
						jalert('Ops! Aconteceu algum problema! Se você abriu mais de uma página da tela de suporte, isso pode ter causado um problema de verificação nas etapas.');
					}
				}
			});
		});
		
		_refresh();
		_step_cb();
	});
</script>
<div class="titulo-secao"><p><?php echo t('suporte.suporte') ?></p></div>
<?php
	if(isset($_GET['created'])) {
		msg('2',''.t('clas.c6').'', ''.t('suporte.s4').'');
	}
?>
<br />

<?php if($_SESSION['support_step'] == 1): ?>
	<?php
		$topics	= Recordset::query('SELECT id, title, LEFT(content, 150) AS content FROM kb_topics ORDER BY viewcount DESC LIMIT 9');
	?>
	<?php msg('1', t('suporte.steps.step1.msg1'), t('suporte.steps.step1.msg2'));?>
	<div class="step1-topics">
		<?php foreach($topics->result_array() as $topic): ?>
			<div class="topic">
				<div class="title">
					<a class="linkTopo" href="?secao=suporte_topico&id=<?php echo $topic['id'] ?>"><?php echo $topic['title'] ?></a>
				</div>
				<div><?php echo strip_tags($topic['content']) ?>...</div>
			</div>
		<?php endforeach; ?>
		<div class="break"></div>
	</div>
	<p class="laranja" style="font-weight: bold"><?php echo t('suporte.steps.step1.msg3') ?></p>
	<p><a class="button" id="support-next-step"><?php echo t('suporte.steps.next') ?></a></p>
<?php endif; ?>
<?php if($_SESSION['support_step'] == 2): ?>
	<?php
		$types	= Recordset::query('SELECT * FROM kb_topic_types');
	?>
	<p><?php msg('3',''.t('suporte.steps.step2.msg3').'', ''.t('suporte.steps.step2.msg1').''); ?></p>
	<ul class="with-n-tabs topic-type-list">
	<?php foreach($types->result_array() as $type): ?>
		<li><a class="button" rel="#category-container-<?php echo $type['id'] ?>" href="javascript:;" class="category"><?php echo $type['name'] ?></a></li>
	<?php endforeach ?>
	</ul>
	<div class="break"></div>
	<?php foreach($types->result_array() as $type): ?>
		<div id="category-container-<?php echo $type['id'] ?>">
			<div class="step1-topics only-titles">
			<?php
				$topics	= Recordset::query('SELECT id, title FROM kb_topics WHERE id_topic_type=' . $type['id']);
			?>
			<?php foreach($topics->result_array() as $topic): ?>
				<div class="topic">
					<div class="title"><a class="linkTopo" href="?secao=suporte_topico&id=<?php echo $topic['id'] ?>"><?php echo $topic['title'] ?></a></div>
				</div>
			<?php endforeach ?>
				<div class="break"></div>
			</div>
			<br />
			<br />
			<br />
			<p class="laranja" style="font-weight: bold;"><?php echo t('suporte.steps.step2.msg2') ?></p>
	</div>
	<?php endforeach ?>
	<p><a class="button" id="support-next-step"><?php echo t('suporte.steps.next') ?></a></p>
<?php endif ?>
<?php if($_SESSION['support_step'] == 3): ?>
	<?php msg('4',''.t('suporte.steps.step3.msg5').'', ''.t('suporte.steps.step3.msg1').'<br /><br /><form id="step3-search-form" onsubmit="return false"><input type="text" name="keyword" /><input type="button" class="button" value="'.t('suporte.steps.search').'" /></form>'); ?>
	<div id="step3-results"><p class="laranja" style="font-weight: bold"><?php echo t('suporte.steps.step3.msg2')?></p></div>
<?php endif ?>
<?php if($_SESSION['support_step'] == 4): ?>
	<?php msg('5', t('suporte.s1'), t('suporte.s2').' <br /><br /><a class="button" href="?secao=suporte_novo">'.t('suporte.s3').'</a>');?>
<?php else: ?>
	<br />
	<p><a class="button" href="?secao=suporte_novo"><?php echo t('suporte.s3') ?></a></p>
<?php endif ?>
<br /><br /><br />
<?php if($_SESSION['universal']): ?>
<form id="f-search" onsubmit="return false">
	<input type="hidden" name="search" value="1" />
	<input type="hidden" name="page" value="0" />
	<table width="730" border="0" cellpadding="0" cellspacing="0">
		<tr >
			<td align="center" valign="top"><b style="font-size:14px">ID</b><br />
			<input type="text" name="id" size="10"/></td>
			<td align="center" valign="top"><b style="font-size:14px"><?php echo t('geral.titulo') ?></b><br />
			<input type="text" name="title" size="25"/>	
			</td>
			<td	align="center" valign="top"><b style="font-size:14px"><?php echo t('suporte.categoria') ?></b><br />
			<select name="category">
				<option value=""><?php echo t('suporte.categories.all') ?></option>
				<option value="bug"><?php echo t('suporte.categories.bug') ?></option>
				<option value="vip"><?php echo t('suporte.categories.vip') ?></option>
				<option value="question"><?php echo t('suporte.categories.question') ?></option>
				<option value="suggestion"><?php echo t('suporte.categories.suggestion') ?></option>
				<option value="translation"><?php echo t('suporte.categories.translation') ?></option>
				<option value="other"><?php echo t('suporte.categories.other') ?></option>
			</select>
			</td>
			<td	align="center" valign="top"><b style="font-size:16px">Status</b> <br />
			<select name="status">
				<option value=""><?php echo t('suporte.statuses.all') ?></option>
				<option value="new" selected="selected"><?php echo t('suporte.statuses.new') ?></option>
				<option value="replied"><?php echo t('suporte.statuses.replied') ?></option>
				<option value="awaiting"><?php echo t('suporte.statuses.awaiting') ?></option>
				<option value="closed"><?php echo t('suporte.statuses.closed') ?></option>
			</select>
			</td>
			<td	align="center"><input type="submit" value="<?php echo t('geral.filtrar')?>" class="button" /></td>
		</tr>
	</table>
</form>
<?php else: ?>
<form id="f-search">
	<input type="hidden" name="search" value="1" />
	<input type="hidden" name="page" value="0" />
</form>
<?php endif ?>
<br /><br />

<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="49" class="subtitulo-home">
			<table width="730" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="220" align="center"><b style="color:#FFFFFF"><?php echo t('suporte.titulo') ?></b></td>
					<td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('suporte.categoria') ?></b></td>
					<td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('suporte.status') ?></b></td>
					<td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('suporte.criacao') ?></b></td>
					<td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('suporte.alteracao') ?></b></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div id="search-result"></div>