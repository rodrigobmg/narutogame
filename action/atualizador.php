<?php
	if($basePlayer->id_batalha && !$basePlayer->id_vila_atual) {
		if(strpos($_SERVER['HTTP_REFERER'], "dojo") === false) {
			echo "clearInterval(_pageUpdater);";
			echo "location.href='?secao=dojo_batalha_pvp';";
		}
	}
	
	if(Recordset::query("SELECT id FROM mensagem WHERE lida=0 AND id_para=" . $basePlayer->id)->num_rows) {
		echo "if(!_iMsgCallbackI) { _iMsgCallback(); };";
	} else {
		echo "clearInterval(_iMsgCallbackI); $('#iMsg').animate({opacity: .4}, 400);";
	}
