<?php
	header("Content-type: text/javascript; charset=utf-8");

	if(!Recordset::query("SELECT id FROM global.user WHERE email='" . addslashes($_POST['email']) . "'")->num_rows) {
		echo "\$('#cnDados').show();";
		echo "\$('#cnMensagem').html(\"<span class='laranja'>".t('actions.a4')."</span>\");";
		
		die();
	}

	echo "\$('#cnMensagem').html(\" <div class='msg_gai'>
		<div class='msg'>
				<div class='msg_text' style='background:url(". img() ."layout/msg/".$village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8)."/1.png); background-repeat: no-repeat;'>
				<b>".t('actions.a5')."</b>
				<p>".t('actions.a6')."</p>
				</div>
		</div>
		</div>\");";	

	Recordset::query("UPDATE global.user SET active= 1 WHERE email='" . addslashes($_POST['email']) . "'");
