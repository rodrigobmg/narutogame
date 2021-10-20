<?php
	require('_config.php');

	Recordset::query("UPDATE player_flags SET loteria_uso=0, wo_looses = 0");
	Recordset::query("UPDATE flags SET valor = NULL WHERE id = 1");
