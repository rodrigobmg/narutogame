<?php
	require('_config.php');

	Recordset::query("UPDATE player_item SET uso=0 WHERE id_item IN(1875,1876,1877,1878)");
