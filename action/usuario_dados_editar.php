<?php
	$msg_vip	= isset($_POST['msg_vip']) && $_POST['msg_vip'] ? 1 : 0;
	$sound		= isset($_POST['sound']) && $_POST['sound'] ? 1 : 0;
	$newsletter	= isset($_POST['newsletter']) && $_POST['newsletter'] ? 1 : 0;
	
	$_SESSION['usuario']['msg_vip']	= $msg_vip;

	if(!$_POST['ano'] || !$_POST['mes'] || !$_POST['dia']) {
		die('jalert("Data de nascimento inválida!")');
	}

	Recordset::query("
		UPDATE global.user SET
			state='" . addslashes($_POST['estado']) . "',
			city='" . addslashes($_POST['cidade']) . "',
			neighborhood='" . addslashes($_POST['bairro']) . "',
			street='" . addslashes($_POST['endereco']) . "',
			zip='" . addslashes($_POST['cep']) . "',
			sex='" . addslashes($_POST['sexo']) . "',
			name='" . addslashes($_POST['nome']) . "',
			birthday='" . addslashes($_POST['ano']) . "-" . addslashes($_POST['mes']) . "-" . addslashes($_POST['dia']) . "',
			id_country=" . (int)$_POST['pais'] . ",
			newsletter=". ($newsletter ? 1 : 0) .",
			msg_vip=". ($msg_vip ? 1 : 0) .",
			sound=". ($sound ? 1 : 0) ."
		
		WHERE
			id=" . $_SESSION['usuario']['id']);
	
	$_SESSION['usuario']['msg_vip']	= $msg_vip;
	$_SESSION['usuario']['sound']	= $sound;
	
	$redir_script = true;
	redirect_to("usuario_dados", NULL, array("ok" => 1));
