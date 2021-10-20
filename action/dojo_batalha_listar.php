<?php
	$qLista = Recordset::query("
		SELECT 
			a.*,
			b.id AS id_player,
			b.nome AS nome_player,
			b.level,
			b.id_classe,
			c.imagem,
			d.nome_".Locale::get()." AS nome_graduacao,
			d.id AS grad_id
		
		FROM 
			batalha_sala a FORCE KEY(id_vila) JOIN player b ON b.id=a.id_player
			JOIN classe c ON c.id=b.id_classe
			JOIN graduacao d ON d.id=b.id_graduacao");
	
	echo "<table width='730' border='0' cellpadding='0' cellspacing='0'>";
	
	if(!$qLista->num_rows) {
		echo "<tr><td align='center'>". t('dojo.nao_ha_desafiantes') ."</td></tr>";
	}
	
	$cn	= 0;
	
	while($rLista = $qLista->row_array()) {
		$color = $cn++ % 2 ? "class='cor_sim'" : "class='cor_nao'";
		
		// Se ja batalhei com o player listado, não lista, pra evitar multi --->
			/*
			$another_battle	= Recordset::query('
				SELECT
					id
				
				FROM
					player_batalhas
				
				WHERE
					(id_player=' . $basePlayer->id . ' AND id_playerb=' . $rLista['id_player'] . ') OR 
					(id_player=' . $rLista['id_player'] . ' AND id_playerb=' . $basePlayer->id . ')');
			
			if($another_battle->num_rows) {
				continue;
			}
			*/
		// <---
	
		echo "<tr $color>" .
			 "<td width='136'><img src='".img()."layout/dojo/{$rLista['id_classe']}.png' /></td>" .
			 "<td style='color:#ffffff; font-size: 13px'><b>{$rLista['nome_player']}</b><br /> 
			 <span class='verde'>".graduation_name($basePlayer->id_vila, $rLista['grad_id'])."</span> - <span class='laranja'>Lvl {$rLista['level']}</span></td>" .
			 "<td style='color:#ffffff;'>{$rLista['nome']}</td>";

		if($rLista['mesmo_nivel'] && $basePlayer->level != $rLista['level']) {
			echo "<td>" . t('dojo.msg_nivel') . "</td>";
		} else {
			echo "<td><a class='button' onclick=\"doDesafioDojoPVP('" . encode($rLista['id']) . "')\">".t('botoes.aceitar')."</a></td>";
		}
		
		echo "</tr><tr height='4'></tr>";
	}

	echo "</table>";
