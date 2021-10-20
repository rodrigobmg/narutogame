<?php
	switch($_POST['id']){
		case 1:
			Recordset::update('player_tutorial', array(
				'status'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 2:
			Recordset::update('player_tutorial', array(
				'clas'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 3:
			Recordset::update('player_tutorial', array(
				'invocacao'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 4:
			Recordset::update('player_tutorial', array(
				'sennin'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;	
		case 5:
			Recordset::update('player_tutorial', array(
				'selo'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 6:
			Recordset::update('player_tutorial', array(
				'portoes'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;	
		case 7:
			Recordset::update('player_tutorial', array(
				'talentos'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 8:
			Recordset::update('player_tutorial', array(
				'elementos'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 9:
			Recordset::update('player_tutorial', array(
				'estudo'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 10:
			Recordset::update('player_tutorial', array(
				'sorte'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 11:
			Recordset::update('player_tutorial', array(
				'profissao'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 12:
			Recordset::update('player_tutorial', array(
				'bijuus'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 13:
			Recordset::update('player_tutorial', array(
				'espadas'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 14:
			Recordset::update('player_tutorial', array(
				'fidelity'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 15:
			Recordset::update('player_tutorial', array(
				'treinamento'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 16:
			Recordset::update('player_tutorial', array(
				'golpes'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 17:
			Recordset::update('player_tutorial', array(
				'jutsus'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 18:
			Recordset::update('player_tutorial', array(
				'medicinal'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 19:
			Recordset::update('player_tutorial', array(
				'kinjutsu'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 20:
			Recordset::update('player_tutorial', array(
				'vila'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 21:
			Recordset::update('player_tutorial', array(
				'eventos'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 22:
			Recordset::update('player_tutorial', array(
				'objetivos'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 23:
			Recordset::update('player_tutorial', array(
				'ramen'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 24:
			Recordset::update('player_tutorial', array(
				'shop'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 25:
			Recordset::update('player_tutorial', array(
				'equips'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 26:
			Recordset::update('player_tutorial', array(
				'battle_npc'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 27:
			Recordset::update('player_tutorial', array(
				'graduacao'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 28:
			Recordset::update('player_tutorial', array(
				'battle_4x4'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 29:
			Recordset::update('player_tutorial', array(
				'vip'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 30:
			Recordset::update('player_tutorial', array(
				'missoes'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
		case 31:
			Recordset::update('player_tutorial', array(
				'battle'	=> 1
			), array(
				'id_player'	=> $basePlayer->id
			));
		break;
	}
	$player_tutorial	= Recordset::query('SELECT * FROM player_tutorial WHERE id_player=' . $basePlayer->id)->row();
	if(
		$player_tutorial->status && $player_tutorial->equips && $player_tutorial->invocacao && 
		$player_tutorial->golpes && $player_tutorial->clas && $player_tutorial->selo && 
		$player_tutorial->sennin && $player_tutorial->treinamento && $player_tutorial->ramen && 
		$player_tutorial->portoes && $player_tutorial->missoes && $player_tutorial->vila && 
		$player_tutorial->shop && $player_tutorial->estudo && $player_tutorial->sorte && 
		$player_tutorial->battle_npc && $player_tutorial->battle && $player_tutorial->fidelity && 
		$player_tutorial->battle_4x4 && $player_tutorial->bijuus && $player_tutorial->espadas &&
		$player_tutorial->jutsus && $player_tutorial->medicinal && $player_tutorial->kinjutsu &&
		$player_tutorial->vip && $player_tutorial->objetivos && $player_tutorial->eventos &&
		$player_tutorial->talentos && $player_tutorial->graduacao && $player_tutorial->profissao &&
		$player_tutorial->elementos 
	){
		Recordset::update('player_flags', array(
			'tutorial'	=> 1
		), array(
			'id_player'	=> $basePlayer->id
		));
	
		
	}
	// Conquista --->
		arch_parse(NG_ARCH_SELF, $basePlayer);
	// <---
