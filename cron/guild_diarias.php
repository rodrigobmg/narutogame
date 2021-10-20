<?php
	require '_config.php';

	Recordset::query('delete from player_quest_guild_npc_item');
	Recordset::query('update player set id_missao_guild = 0');
	Recordset::query('update guild_missao_log set exp = 0');
	Recordset::query('delete from guild_quest_npc_item');
	Recordset::query('update guild set id_quest_guild = 0, exp_level_dia = 0');
