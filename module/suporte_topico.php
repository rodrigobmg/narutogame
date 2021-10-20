<?php
	if(!isset($_GET['id']) || (isset($_GET['id']) && !is_numeric($_GET['id']))) {
		redirect_to('negado');
	} else {
		$topic	= Recordset::query('SELECT * FROM kb_topics WHERE id=' . (int)$_GET['id']);
		
		if($topic->num_rows) {
			Recordset::update('kb_topics', array(
				'viewcount'	=>	array('escape' => false, 'value' => 'viewcount+1')
			), array(
				'id'		=> $topic->row()->id
			));
		}
	}
?>
<div class="titulo-secao"><p><?php echo t('suporte.s1') ?></p></div>
<?php if($topic->num_rows): ?>
<?php
	msg('22', $topic->row()->title, nl2br($topic->row()->content));
?>
<?php else: ?>
	<?php
		msg('22', t('suporte.topics.title'), t('suporte.topics.msg'));
	?>
<?php endif ?>
<div align="center">
	<a href="javascript:history.go(-1)" class="button">Voltar</a>
</div>