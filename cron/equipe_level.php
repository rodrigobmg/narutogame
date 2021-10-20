<?php
	require('_config.php');

	Recordset::query('UPDATE equipe SET exp_level_dia=0');
	Recordset::query('UPDATE player SET exp_equipe_dia=0 WHERE exp_equipe_dia > 0');

	Recordset::query('UPDATE guild SET exp_level_dia=0');
	Recordset::query('UPDATE player SET exp_guild_dia=0 WHERE exp_guild_dia > 0');
