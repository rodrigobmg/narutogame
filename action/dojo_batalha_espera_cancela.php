<?php
	Recordset::query("DELETE FROM batalha_sala WHERE id={$basePlayer->id_sala}");
	Recordset::query("UPDATE player SET id_sala=0 WHERE id={$basePlayer->id}");
	
	echo "location.href='?secao=dojo'";