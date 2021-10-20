<?php
	Recordset::query("UPDATE batalha_sala SET data_atividade=NOW() WHERE id=" . $basePlayer->id_sala);

	if($basePlayer->id_batalha) {
		echo "clearTimeout(_dojoTimer);\n";
		echo "location.href='?secao=dojo_batalha_pvp';\n";
	}
