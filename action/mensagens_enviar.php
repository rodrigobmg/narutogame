<?php
	header('Content-Type: application/json');

	$json			= new stdClass();
	$json->messages	= [];
	$json->success	= false;
	$errors			= [];

	$to				= isset($_POST['to']) && $_POST['to'] ? $_POST['to'] : '';
	$content		= isset($_POST['content']) && $_POST['content'] ? $_POST['content'] : '';
	$subject		= isset($_POST['subject']) && $_POST['subject'] ? $_POST['subject'] : '';
	$vila			= false;
	$player			= Recordset::query("SELECT * FROM player_nome WHERE nome='" . addslashes($to) . "'");

	if(isset($_POST['vila']) && $_POST['vila']) {
		$_GET['vila']	= $_POST['vila'];
	
		if($basePlayer->vila_ranking != 1) {
			$errors[]	= t('actions.a259');
		} else {
			$vila	= true;
		}
	}

	if((!$vila && $to == "") || $content =="" || trim($subject) == ""){
		$errors[]	= t('actions.a222');
	}
	
	if(!$basePlayer->vip && $basePlayer->level < 10) {
		$errors[]	= t('actions.a223');
	}
	
	if(!$vila && !$player->num_rows) {
		$errors[]	= t('actions.a217');
	} else {
    if(!$vila) {
      $player	= $player->row();

      if(Recordset::query("SELECT id FROM mensagem_bloqueio WHERE id_playerb={$basePlayer->id} AND id_player=" . $player->id_player)->num_rows) {
				$errors[]	= t('actions.a224');
			}
			
			if(is_bingo_book_player($basePlayer->id, $player->id_player)) {
				$errors[]	= t('actions.a225');
			}

			if(is_bingo_book_equipe($basePlayer, $player->id_player)) {
				$errors[]	= t('actions.a278');
			}
	
			if(is_bingo_book_guild($basePlayer, $player->id_player)) {
				$errors[]	= t('actions.a253');
			}
		}
	}

	if(!sizeof($errors)) {
		$json->success	= true;

		if($vila) {
			$json->message	= t('actions.a260');	

			Recordset::insert('mensagem_vila', array(
				'id_envio'	=> $basePlayer->id,
				'id_vila'	=> $basePlayer->id_vila,
				'titulo'	=> htmlspecialchars(check_words($subject)),
				'corpo'		=> htmlspecialchars(check_words($content))
			));
		} else {
			$json->message	= t('actions.a226');	

			mensageiro($basePlayer->id, $player->id_player, htmlspecialchars(check_words($subject)), htmlspecialchars(check_words($content)), 'player');
		}
	} else {
		$json->messages	= $errors;
	}

	echo json_encode($json);
