<?
	$redir_script = true;

	if(!$basePlayer->graduando) {
		redirect_to("negado");
	}

	if($_POST['key'] != $_SESSION['el_form_key']) {
		redirect_to("negado");
	}
	
	unset($_SESSION['el_form_key']);
	
	if($_POST['id'] != encode($basePlayer->id)) {
		redirect_to("negado");
	}

	Recordset::query("UPDATE player SET id_graduacao=id_graduacao + 1, graduando=NULL WHERE graduando IS NOT NULL AND id=" . $basePlayer->id);

	// Conqiusta --->
		$basePlayer->__construct($basePlayer->id);

		arch_parse(NG_ARCH_SELF, $basePlayer);
	// <---

?>
location.href = "?secao=personagem_status";