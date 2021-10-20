<?php
	if($_POST['key'] != $_SESSION['vip_field_postkey_value']) {
		redirect_to("negado");	
	}

	unset($_SESSION['vip_field_postkey_value']);

	Recordset::query("UPDATE player SET treinando=NULL, treino_tempo=NULL WHERE id=" . $basePlayer->id);
	
	redirect_to("academia_treinamento");
