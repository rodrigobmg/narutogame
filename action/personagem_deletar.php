<?php
	header("Content-type: text/javascript; charset=utf-8");

	$total	= Recordset::query('SELECT COUNT(id) AS total FROM player WHERE id_usuario=' . $_SESSION['usuario']['id'] . ' AND removido=\'1\'');
	$max	= ($_SESSION['usuario']['vip'] ? 5 : 3) + get_user_field('vip_char_slots');

	if($total->row()->total >= $max) {
		die('jalert("'.t('actions.a241').'", null, function () { location.reload() })');
	}

	$redir_script = true;
	
	$_POST['id'] = decode($_POST['id']);

	if((int)$_POST['id'] == 0) {
		redirect_to("negado");	
	}

	$qVerifica = Recordset::query("SELECT id FROM player WHERE id_usuario={$_SESSION['usuario']['id']} AND id=" . (int)$_POST['id']);
	
	if(!$qVerifica->num_rows) {
		redirect_to("negado");
	} else {
		if($basePlayer && $qVerifica->row()->id == $basePlayer->id) {
			die('jalert("'.t('actions.a242').'", null, function () { location.reload() })');
		}
	
		$p = new Player((int)$_POST['id']);

		if($p->id_equipe) {
			die("jalert('".t('actions.a243')."', null, function () { location.reload() })");
		}

		if($p->id_guild) {
			die("jalert('".t('actions.a244')."', null, function () { location.reload() })");
		}
		
		/*
		if($p->missao_equipe) {
			die("jalert('Operação não disponivel! Jogador em missao de equipe!', null, function () { location.reload() })");
		}
		
		// se for dono de equipe --->
			if($p->dono_equipe) {
				$qM = Recordset::query("SELECT id FROM player WHERE id_equipe=" . $p->id_equipe . " AND id !=" . $p->id);
				while($rM = $qM->row_array()) {
					$pM = new Player($rM['id']);
						
					if($pM->missao_equipe) {
						echo "alert('Operação não disponivel! Membro de equipe em missão de equipe!'); location.reload()";
						die();
					}
				}
				
				
				Recordset::query("UPDATE player SET id_equipe=NULL, membros=1 WHERE id_equipe=" . $p->id_equipe . " AND id !=" . (int)$_POST['id']);
			} else {
				if($p->id_equipe) {
					Recordset::query("UPDATE player SET id_equipe=NULL WHERE id=" . (int)$_POST['id']);
					Recordset::query('UPDATE equipe SET membros=membros-1 WHERE id=' . $p->id_equipe);
				}
			}			
		// <---

		// Verifica se o player a ser deletado tem guild, remove os players da guild pra evitar erro --->
			$qGuild = Recordset::query("SELECT id FROM guild WHERE id_player=" . $_POST['id']);
		
			if($qGuild->num_rows) { // Dono da guiçd
				$rGuild = $qGuild->row_array();
			
				Recordset::query("UPDATE player SET id_guild=NULL WHERE id_guild=" . $rGuild['id'] . " AND id !=" .(int)$_POST['id']);
			} else { // Integrante da guild
				Recordset::query("UPDATE player SET id_guild=NULL WHERE id =" .(int)$_POST['id']);				
			}
		// <---
		*/
		
		Recordset::query("UPDATE player SET removido=1 WHERE id=" . (int)$_POST['id']);
		Recordset::query("DELETE FROM player_nome WHERE id_player=" . (int)$_POST['id']);
		
		to_log("Deletar Personagem");
	}
	
	echo "location.reload()";
