<?php
	require '_config.php';

	Recordset::query('TRUNCATE TABLE profissao_ativa');
	Recordset::query('UPDATE player_flags SET profissao_ativa_vezes = 0');