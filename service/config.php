<?php
	require '../include/db.php';
	require '../include/generic.php';
	require '../include/yaml.php';
	require '../class/SharedStore.php';
	require '../class/Recordset.php';
	require '../class/Player.php';
	require '../class/Item.php';

	define('USER_TABLE', 'global.user');

	header('Content-Type: application/json');