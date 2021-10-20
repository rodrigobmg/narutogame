<?php
	if($_SESSION['basePlayer']) {
		Recordset::delete('batalha_sala', array(
			'id_player'	=> $basePlayer->id
		));
		
		Recordset::update('player_posicao', array(
			'ult_atividade'	=> NULL
		), array(
			'id_player'	=> $basePlayer->id			
		));
		
		$basePlayer->setAttribute('id_sala', 0);
	}

	to_log("Logoff");
	setcookie('fbsr_' . FB_APP_ID, '', time() - 3600, "/", ".narutogame.com.br");

	if(isset($_SESSION['fb_user_verified']) && $_SESSION['fb_user_verified']) {
	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://connect.facebook.net/pt_BR/all.js"></script>
</head>
<body>
<div id="fb-root"></div>
<h2 align="center" style="font-family: Tahoma, Verdana"><?php echo t('actions.a194')?></h2>
<script type="text/javascript">
	FB.init({ 
	    appId: '<?php echo FB_APP_ID ?>', cookie:true, 
	    status:true, xfbml:true, oauth: true
	});
	
	/*
	FB.logout(function (e) {
		location.href="http://narutogame.com.br";
	})
	;*/
	
	FB.getLoginStatus(function (r) {
		FB.logout(function () {
			 setTimeout(function () {
				 location.href = "http://narutogame.com.br";
			 }, 2000);
		});
		
		if(r.status == "unknown") {
			document.cookie = '';
			location.href="http://narutogame.com.br"
		}
	});
</script>
</body>
</html>
<?php
		session_destroy();
	} else {
		session_destroy();
		redirect_to();
	}
?>