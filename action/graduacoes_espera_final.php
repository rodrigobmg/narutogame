<?php
	header("Content-type: text/javascript; charset=utf-8");
	
	$redir_script = true;

	if(!$basePlayer->graduando) {
		redirect_to("negado");
	}
	
	if(date("YmdHis") > date("YmdHis", strtotime($basePlayer->graduando))) {
		echo "location.href='?secao=graduacoes_conclusao'";
	} else {
		redirect_to("negado");
	}