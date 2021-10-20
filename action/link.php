<?php /*
	$_GET['go']	= isset($_GET['go']) && $_GET['go'] ? $_GET['go'] : '';

	if($_GET['go'] && is_numeric($_GET['id'])) {
		Recordset::update('link_patrocinado', [
			'cliques'	=> ['escape' => false, 'value' => 'cliques + 1']
		], [
			'id'		=> $_GET['id']
		]);
	} else {
		redirect_to('negado');
		die();
	}

	header("Location: " . urldecode($_GET['go']));
	die();
	*/
?>