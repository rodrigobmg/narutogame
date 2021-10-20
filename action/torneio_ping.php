<?php
	// Agluem entrou em combate, manda pro dojo
	if($basePlayer->getAttribute('id_batalha')) {
		$redir_script = true;
		
		redirect_to('dojo_batalha_pvp');
	}
