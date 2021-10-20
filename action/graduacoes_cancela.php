<?php
	Recordset::query("UPDATE player SET graduando = NULL WHERE id=" . $basePlayer->id);
?>
location.href = "?secao=personagem_status";