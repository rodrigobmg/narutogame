<?php
	require '_config.php';

	Recordset::update('player_flags', [
		'ponto_aprimoramento'	=> ['escape' => false, 'value' => 'ponto_aprimoramento + 1']
	], [
		'ponto_aprimoramento'	=> ['escape' => false, 'value' => '5', 'mode' => 'lt']
	]);