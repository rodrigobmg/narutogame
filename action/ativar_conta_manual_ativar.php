<?php
	header("Content-type: text/javascript; charset=utf-8");

	if(!isset($_POST['txtEmailConta'])) {
		echo "\$('#cnDados').show();";
		echo "\$('#cnMensagem').html(\"E-Mail inválido\");";
		
		die();	
	}

	$user = Recordset::query('SELECT id, active FROM global.user WHERE email=\'' . addslashes($_POST['txtEmailConta']) . '\'');

	if(!$user->num_rows) {
		echo "\$('#cnDados').show();";
		echo "\$('#cnMensagem').html(\"<span class='laranja'>".t('actions.a4')."</span>\");";
		
		die();
	} elseif($user->row()->active) {
		echo "\$('#cnDados').show();";
		echo "\$('#cnMensagem').html(\"<span class='verde'>".t('actions.a7')."</span>\");";
		
		die();	
	}
	
   	echo "\$('#cnMensagem').html(\" <div class='msg_gai'>" .
		 "<div class='msg'>" .
		 "<div class='msg_text' style='background:url(". img() ."layout/msg/".$village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8)."/1.png); background-repeat: no-repeat;'>" .
		 "<b>".t('actions.a5')."</b>" . 
		 "<p>".t('actions.a6')."</p>" .
		"</div></div></div>\");";


	Recordset::update('global.user', array(
		'active'	=> '1'
	), array(
		'email'		=> addslashes($_POST['txtEmailConta'])
	));
?>	