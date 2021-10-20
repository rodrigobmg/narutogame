<?php
	$dt_inico = $_POST['dt_inicio'] . " " . $_POST['dt_inicio_hora'] . ":" . $_POST['dt_inicio_minuto'] . ":00";
	$dt_fim   = $_POST['dt_fim'] . " " . $_POST['dt_fim_hora'] . ":" . $_POST['dt_fim_minuto'] . ":00";

	$qEvento = Recordset::query("INSERT INTO evento(nome, ativo, dt_inicio, dt_fim, recorrente, ryou, exp) VALUES(
		'" . addslashes($_POST['nome']) . "', " . (int)$_POST['ativo'] . ",'{$dt_inico}', '{$dt_fim}', 0,
		" . (int)$_POST['ryou'] . ", " . (int)$_POST['exp'] . "
	)");
	
	$id = $qEvento->insert_id();
	
	for($f = 0; $f < sizeof($_POST['npc_nome']); $f++) {
		Recordset::query("INSERT INTO evento_npc(id_evento, nome, ken, tai, nin, gen, ene, forc, inte, agi, con) VALUES(
			$id,
			'" . addslashes($_POST['npc_nome'][$f]) . "',
			{$_POST['tai'][$f]},
			{$_POST['ken'][$f]},
			{$_POST['nin'][$f]},
			{$_POST['gen'][$f]},
			{$_POST['ene'][$f]},
			{$_POST['for'][$f]},
			{$_POST['int'][$f]},
			{$_POST['agi'][$f]},
			{$_POST['con'][$f]}
		)");
	}
