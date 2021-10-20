<?php
	if($basePlayer->id_cla) {
		$_POST['cla']	= $basePlayer->id_cla;
		$base_item			= Recordset::query('SELECT * FROM player_modificadores WHERE id_tipo=16 AND id_player=' . $basePlayer->id)->row();
	} else {
		$base_item			= Recordset::query('SELECT * FROM item WHERE id_tipo=16 AND ordem=1 AND id_cla=' . $_POST['cla'], true)->row();		
	}

	$json				= new stdClass();
	$json->messages		= [];
	$json->success		= false;
	$errors				= [];
	$distribute_data	= Recordset::query('SELECT * FROM cla WHERE id=' . $_POST['cla'], true)->row();
	$max_points			= 0;
	$max_points_formula	= 0;
	$formulas			= [
		'conv',
		'conc',
		'det',
		'esq'
	];

	$ats	= [
		'tai',
		'ken',
		'nin',
		'gen',
		'agi',
		'con',
		'forc',
		'inte',
		'res',
		'esq',
		'det',
		'conv',
		'conc'
	];

	foreach ($ats as $at) {
		if($distribute_data->$at) {
			if(in_array($at, $formulas)) {
				$max_points_formula	+= $base_item->$at;
			} else {
				$max_points			+= $base_item->$at;
			}
		}
	}

	if(isset($_POST['settings']) && $_POST['settings']) {
		$json->max_points			= $max_points;
		$json->max_points_formula	= $max_points_formula;
		$json->allowed				= [];
		$json->allowed_max			= [];
		$json->blocked				= [];
		$json->defaults				= [];

		foreach ($ats as $at) {
			if($distribute_data->$at) {
				if(in_array($at, $formulas)) {
					$json->allowed_formula[]		= $at;
					$json->allowed_max_formula[$at]	= $distribute_data->$at;
				} else {
					$json->allowed[]				= $at;
					$json->allowed_max[$at]			= $distribute_data->$at;
				}

				$json->defaults[$at]	= $base_item->$at;
			} else {
				$json->blocked[$at]	= $at;
			}
		}

		die(json_encode($json));
	}

	if(isset($_POST['preview']) && $_POST['preview']) {
		$bg				= 0;
		$color_counter	= 0;
		$cla			= Recordset::query('SELECT * FROM cla WHERE id=' . $_POST['cla'], true)->row_array();

		for($f = 1; $f <= 5; $f++) {
			$i	= new Item(Recordset::query('SELECT id FROM item WHERE id_tipo=16 AND ordem=' . $f . ' AND id_cla=' . $_POST['cla'], true)->row()->id);

			foreach ($ats as $at) {
				if($basePlayer->id_cla) {
					$i->$at	= $base_item->$at * $f;
				} else {
					if(isset($_POST[$at]) && is_numeric($_POST[$at])) {
						$i->$at	= $_POST[$at] * $f;
					} else {
						$i->$at	= 0;
					}
				}
			}

			require ROOT . '/module/clas_loop.php';
		}
	}
