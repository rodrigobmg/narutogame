<?php
	require '_config.php';
	
	Recordset::query('update player set id_missao_especial = 0 WHERE id_missao_especial in (SELECT id FROM quest WHERE especial=1 AND interativa=1 AND id_vila=0);');
	Recordset::query('delete from player_quest WHERE id_quest in (SELECT id FROM quest WHERE especial=1 AND interativa=1 AND id_vila=0);');
	Recordset::query('delete from player_quest_npc_item WHERE id_player_quest in (SELECT id FROM quest WHERE especial=1 AND interativa=1 AND id_vila=0);');
