<?php
	$redir_script = true;
	
	Recordset::query("UPDATE player SET id_tipo_treino_jutsu=0, treino_tempo_jutsu=NULL WHERE id=" . $basePlayer->id);

	$basePlayer->setFlag("treino_jutsu", 0);
	
	redirect_to("personagem_status");
