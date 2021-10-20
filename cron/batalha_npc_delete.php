<?php
	require('_config.php');
	
	$batalhas	= Recordset::query('
		SELECT id FROM batalha WHERE finalizada=1 and id_tipo in (1,3)
	');
	
	foreach($batalhas->result_array() as $batalha) {
		
			Recordset::delete('batalha', array(
				'id'	=> $batalha['id']
			));

	}