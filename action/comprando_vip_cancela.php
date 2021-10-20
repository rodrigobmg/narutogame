<?php
	if($_POST[$_SESSION['vip_field_postkeyb']] != $_SESSION['vip_field_postkey_valueb']) {
		redirect_to("negado");
	}

	Recordset::query("UPDATE globa.user SET pay_lock=0 WHERE id=" . $_SESSION['usuario']['id']);
	Recordset::query("UPDATE coin_compra SET processado=1 WHERE 
				processado=0 AND liberado=0 
				id_usuario=" . $_SESSION['usuario']['id'] . " AND id_pagamento=" . $basePlayer->trava_pagto);
	
	redirect_to("home");
