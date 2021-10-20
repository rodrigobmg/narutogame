<?php
	$_POST['a']	= isset($_POST['a']) && $_POST['a'] ? $_POST['a'] : '';
	
	if($_POST['a']) {
		Recordset::query("UPDATE mensagem SET removida ='1' WHERE id_para=" . $basePlayer->id);		
	} else {
		if(isset($_GET['vila']) && $_GET['vila']) {
			if(!Recordset::query('SELECT * FROM mensagem_vila_removida WHERE id_player=' . $basePlayer->id . ' AND id_mensagem_vila=' . (int)$_POST['id'])->num_rows) {
				Recordset::insert('mensagem_vila_removida', array(
					'id_player'			=> $basePlayer->id,
					'id_mensagem_vila'	=> (int)$_POST['id']
				));				
			}
		} else {
			Recordset::query("UPDATE mensagem SET removida ='1' WHERE id=" . (int)$_POST['id'] . " AND id_para=" . $basePlayer->id);			
		}
		
		echo "location.reload()";
	}
