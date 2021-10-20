<?php
	date_default_timezone_set('America/Sao_Paulo');
	require('_config.php');
	
	Recordset::query("UPDATE npc_vila SET hp = hp + 20000, sp = sp + 20000, sta = sta + 20000");

