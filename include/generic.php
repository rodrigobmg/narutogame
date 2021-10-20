<?php
	if(!defined('RECORDSET_TTL')) {
		define('RECORDSET_TTL', 1800);
	}

	if(!defined('PVPT_MAX_TURNS')) {
		define('PVPT_MAX_TURNS', 8);
	}

	function redirect_to($module = "", $action = "", $vars = "", $fr = false) {
		global $redir_script;

		if($fr) {
			$redir_script = $fr;
		}

		if(is_array($vars)) {
			$extra = array();

			foreach($vars as $key => $value) {
				$extra[] = $key . "=" . urlencode($value);
			}

			$extra = "&" . implode("&", $extra);
		} else {
			$extra = $vars;
		}

		if($module) $module = "&secao=" . $module;
		if($action) $module = "&acao=" . $module;

		$url = $module . $action . $extra;

		if(!$redir_script) {
			header("Location: index.php?" . substr($url, 1));
			die();
		} else {
			echo "//<script>\n";
			echo "location.href='?" . substr($url, 1) . "';\n";
			echo "//</script>\n";
			die();
		}
	}

	function on($v, $a) {
		if(!is_array($a)) $a = explode(",", $a);

		foreach($a as $x) {
			if($x == $v) {
				return true;
			}
		}

		return false;
	}

	function frand($min, $max, $prec = 2) {
		$v = round(rand($min, $max) + rand(1, 100 * $prec) / (100 * ($prec + rand(1, 100) / 100)), $prec);

		if($v > $max) {
			return $max;
		} elseif($v < $min) {
			return $min;
		} else {
			return $v;
		}
	}

	function between($v, $a, $b) {
		return $v >= $a && $v <= $b;
	}

	function absm($value) {
		return $value < 0 ? 0 : abs($value);
	}

	function barWidth(int|float $v, int|float $m, int|float $w) {
		if ($m === 0 || $v === 0 || $w === 0) {
			return 0;
		}

		$r = ($w / $m) * $v;

		return (int)($r > $w ? $w : $r);
	}

	function scriptslashes($t, $d = false) {
		if($d) {
			$t = str_replace("\r", "\\\\r", $t);
			$t = str_replace("\n", "\\\\n", $t);
		} else {
			$t = str_replace("\r", "\\r", $t);
			$t = str_replace("\n", "\\n", $t);
		}

		return $t;
	}

	function deny($die = true) {
		echo "<div style='border: solid 1px #FF0000; text-align:center; font-size: 13px !important; background-color: #FFDFE0'>O acesso ao recurso foi negado</div>";

		if($die) die();
	}

	function ajaxDeny($scriptTag = false) {
		if($scriptTag) {
			echo "<script type='text/javascript'>";
		}

		echo "\$('#cnBase').html(\"";
		deny(false);
		echo "\")";

		if($scriptTag) {
			echo "</script>";
		}

		die();
	}

	function percent($p, $v) {
		if($p == 0) return 0;

		return round($v * ($p / 100));
	}

	function percentf($p, $v) {
		if($p == 0) return 0;

		return $v * ($p / 100);
	}

	function malandro() {
		return ''; //"<table width='730' border='0' cellpadding='0' cellspacing='0' align='center'><tr><td width='216' height='153' background='images/msg/malandro.jpg'>&nbsp;</td><td  background='images/msg/msg_bg.jpg' align='left'><strong style='font-size:16px;'><br /><br />Haaaaaaaaa! Pegadinha do malandro</strong><br />  Parabéns você acabou de ser zuado! Melhor do que isso <a href='http://www.youtube.com/watch?v=f2b1D5w82yU'>clique aqui</a>.</td></tr></table><br /><br />";
	}

	function usa_coin($item, $valor) {
		global $basePlayer;

		Recordset::insert('coin_log', [
			'id_player'	=> $basePlayer->id,
			'id_item'	=> $item,
			'coin'		=> $valor
		]);
	}

	function gasta_coin($t, $item = NULL, $extra = NULL) {
		global $basePlayer;

		Recordset::update('global.user', [
			'coin'	=> ['escape' => false, 'value' => 'coin - ' . $t]
		], [
			'id'	=> $_SESSION['usuario']['id']
		]);

		Recordset::insert('coin_log', [
			'id_player'	=> $basePlayer->id,
			'id_item'	=> $item,
			'coin'		=> $t,
			'extra'		=> $extra
		]);
	}

	function to_log($acao) {
		return;
		$key			= 'geoip_' . $_SERVER['REMOTE_ADDR'];
		$geoip_loc_id	= 0;

		if (!isset($_SESSION[$key])) {
			$geoip_block	= Recordset::query('
				SELECT
					a.*
				FROM
					geoip_blocks a FORCE KEY (startIpNum_endIpNum)

				 WHERE
				 	INET_ATON("' . $_SERVER['REMOTE_ADDR'] . '") BETWEEN a.startIpNum AND a.endIpNum
			');

			if($geoip_block->num_rows) {
				$geoip_loc_id	= $geoip_block->row()->locId;
			}

			$_SESSION[$key]	= $geoip_loc_id;
		} else {
			$geoip_loc_id	= $_SESSION[$key];
		}

		$browser	= get_browser(null, true);

		Recordset::insert('log', [
			'id_usuario'	=> $_SESSION['usuario']['id'],
			'id_player'		=> $_SESSION['basePlayer'],
			'acao'			=> $acao,
			'ip'			=> ['escape' => false, 'value' => 'INET_ATON("' . $_SERVER['REMOTE_ADDR'] . '")'],
			'geoip_loc_id'	=> $geoip_loc_id,
			'user_agent'	=> $browser['parent'],
			'cookie_id'		=> $_SESSION['ec_id']
		]);
	}

	function file_log($content, $z = false) {
		$mode = $z ? "w+" : "a+";

		$fp = fopen("log.txt", $mode);
		fwrite($fp, $content);
		fclose($fp);

	}

	function hasFall($id_vila, $mlocal) {
		global $basePlayer;

		$r		= Recordset::query("SELECT tempo_derrota, HOUR(TIMEDIFF(NOW(), tempo_derrota)) as diff FROM npc_vila WHERE id_vila=" . $id_vila . " AND mlocal=" . $mlocal)->row_array();
		$item	= 0;

		switch($mlocal) {
			case 3: $item = 1875; break;
			case 4: $item = 1876; break;
			case 1: $item = 1877; break;
			case 2: $item = 1878; break;
		}

		if($basePlayer->hasItem($item)) {
			return false;
		}

		if($r['tempo_derrota']) {
			return $r['diff'] >= 24 ? false : true;
		} else {
			return false;
		}
	}

	function get_fall_time($id_vila, $mlocal) {
		$npc	= Recordset::query("SELECT tempo_derrota FROM npc_vila WHERE id_vila=" . $id_vila . " AND mlocal=" . $mlocal)->row_array();

		if(!$npc['tempo_derrota']) {
			return null;
		} else {
			$now	= new DateTime();
			$died	= new DateTime(date('Y-m-d H:i:s', strtotime('+24 hour', strtotime($npc['tempo_derrota']))));

			$diff	= $died->diff($now);

			return $diff;
		}
	}

	$mc_cached_ats	= NULL;

	function menu_conds($f, $p) {
		global $proximo_nivel;
		global $mc_cached_ats;

		$result	= array();

		if(!$mc_cached_ats) {
			$mc_cached_ats = array(
				'id_batalha'			=> $p ? $p->getAttribute('id_batalha') 				: 0,
				'id_batalha_multi'		=> $p ? $p->getAttribute('id_batalha_multi')		: 0,
				'tipo_batalha'			=> $p ? $p->getAttribute('tipo_batalha')			: 0,
				'treinando'				=> $p ? $p->getAttribute('treinando')				: 0,
				'treinando_jutsu'		=> $p ? $p->getAttribute('treino_tempo_jutsu')		: 0,
				'hospital'				=> $p ? $p->getAttribute('hospital')				: 0,
				'dentro_vila'			=> $p ? $p->getAttribute('dentro_vila')				: 0,
				'id_vila_atual'			=> $p ? $p->getAttribute('id_vila_atual')			: 0,
				'id_guild'				=> $p ? $p->getAttribute('id_guild')				: 0,
				'id_equipe'				=> $p ? $p->getAttribute('id_equipe')				: 0,
				//'graduando'				=> $p ? $p->getAttribute('graduando')				: 0,
				'id_sala'				=> $p ? $p->getAttribute('id_sala')					: 0,
				'missao_comum'			=> $p ? $p->getAttribute('missao_comum')			: 0,
				'missao_interativa'		=> $p ? $p->getAttribute('missao_interativa')		: 0,
				'id_missao_especial'	=> $p ? $p->getAttribute('id_missao_especial')		: 0,
				'id_missao_guild'		=> $p ? $p->getAttribute('id_missao_guild')			: 0,
				'id_missao_guild2'		=> $p ? $p->getAttribute('id_missao_guild2')		: 0,
				'id_evento'				=> $p ? $p->getAttribute('id_evento')				: 0,
				'level'					=> $p ? $p->getAttribute('level')					: 0,
				'id_graduacao'			=> $p ? $p->getAttribute('id_graduacao')			: 0,
				'id_torneio'			=> $p ? $p->getAttribute('id_torneio')				: 0,
				'id_torneio_espera'		=> $p ? $p->getAttribute('id_torneio_espera')		: 0,
				'id_arena'				=> $p ? $p->getAttribute('id_arena')				: 0,
				'id_batalha_multi_pvp'	=> $p ? $p->getAttribute('id_batalha_multi_pvp')	: 0,
				'id_sala_multi_pvp'		=> $p ? $p->getAttribute('id_sala_multi_pvp')		: 0,
				'id_random_queue'		=> $p ? $p->getAttribute('id_random_queue')			: 0,
				'id_exame_chuunin'		=> $p ? $p->getAttribute('id_exame_chuunin')		: 0,
				'id_sensei'				=> $p ? $p->getAttribute('id_sensei')				: 0
			);
		}

		$ok = true;
		$at	= $mc_cached_ats;

		// Rules if banned users -->
			global $user_is_banned;
			global $denied_reason_is_ban;

			if ($user_is_banned && isset($f['href'])) {
				$allowed		= ['usuario_dados', 'suporte', 'suporte_novo', 'suporte_ticket', 'suporte_topico'];
				$allowed_ids	= [184];

				if (!in_array($f['href'], $allowed) && !in_array($f['id'], $allowed_ids)) {
					$ok	= false;

					$denied_reason_is_ban[]	= $f['href'];
				}
			}
		// <--

		if($f['h_logado'] == 1 && !$_SESSION['logado']) {
			$ok = false;
		} elseif($f['h_logado'] == 2 && $_SESSION['logado']) {
			$ok = false;
		}

		$result[0]	= $ok;

		if($f['h_player'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_player'] == 2 && $p) {
			$ok = false;
		}

		$result[1]	= $ok;

		if($f['h_batalha'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_batalha'] && $p) {
			if($at['id_batalha']) {
				if($f['h_batalha'] == 1 && !$at['id_batalha']) {
					$ok = false;
				} elseif($f['h_batalha'] == 2 && $at['id_batalha']) {
					$ok = false;
				}
			} elseif($at['id_batalha_multi']) {
				if($f['h_batalha'] == 1 && !$at['id_batalha_multi']) {
					$ok = false;
				} elseif($f['h_batalha'] == 2 && $at['id_batalha_multi']) {
					$ok = false;
				}
			} else {
				if($f['h_batalha'] == 1) {
					$ok = false;
				}
			}

			if($f['h_batalha'] == 1 && ($at['id_batalha'] || $at['id_batalha_multi'])) {
				if($f['h_batalha_tipo'] == 1 && !in_array($at['tipo_batalha'], array(1, 3 ,6))) {
					$ok = false;
				}

				if($f['h_batalha_tipo'] == 2 && !in_array($at['tipo_batalha'], array(2, 4 ,5))) {
					$ok = false;
				}

				if($f['h_batalha_tipo'] == 3 && !$at['id_batalha_multi']) {
					$ok = false;
				}
			}
		}

		$result[2]	= $ok;

		if($f['h_batalha_multi'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_batalha_multi'] && $p) {
			if($f['h_batalha_multi'] == 1 && !$at['id_batalha_multi']) {
				$ok = false;
			} elseif($f['h_batalha_multi'] == 2 && $at['id_batalha_multi']) {
				$ok = false;
			}
		}

		$result[3]	= $ok;

		if($f['h_treinando'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_treinando'] && $p) {
			if($f['h_treinando'] == 1 && !$at['treinando']) {
				$ok = false;
			} elseif($f['h_treinando'] == 2 && $at['treinando']) {
				$ok = false;
			}
		}

		$result[4]	= $ok;

		if($f['h_treino_jutsu'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_treino_jutsu'] && $p) {
			if($f['h_treino_jutsu'] == 1 && !$at['treinando_jutsu']) {
				$ok = false;
			} elseif($f['h_treino_jutsu'] == 2 && $at['treinando_jutsu']) {
				$ok = false;
			}
		}

		$result[5]	= $ok;

		if($f['h_hospital'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_hospital'] && $p) {
			if($f['h_hospital'] == 1 && !$at['hospital']) {
				$ok = false;
			} elseif($f['h_hospital'] == 2 && $at['hospital']) {
				$ok = false;
			}
		}

		$result[6]	= $ok;

		if($f['h_dentro_vila'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_dentro_vila'] && $p) {
			if($f['h_dentro_vila'] == 1 && !$at['dentro_vila']) {
				$ok = false;
			} elseif($f['h_dentro_vila'] == 2 && $at['dentro_vila']) {
				$ok = false;
			}
		}

		$result[7]	= $ok;

		if($f['h_mapa_vila'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_mapa_vila'] && $p) {
			if($f['h_mapa_vila'] == 1 && (!$at['dentro_vila'] && !$at['id_vila_atual'])) {
				$ok = false;
			} elseif($f['h_mapa_vila'] == 2 && ($at['dentro_vila'] && !$at['id_vila_atual'])) {
				$ok = false;
			}
		}

		$result[8]	= $ok;

		if($f['h_mapa_mundi'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_mapa_mundi'] && $p) {
			if($f['h_mapa_mundi'] == 1 && $at['id_vila_atual']) {
				$ok = false;
			} elseif($f['h_mapa_mundi'] == 2 && !$at['id_vila_atual']) {
				$ok = false;
			}
		}

		$result[9]	= $ok;

		if($f['h_equipe'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_equipe'] && $p) {
			if($f['h_equipe'] == 1 && !$at['id_equipe']) {
				$ok = false;
			} elseif($f['h_equipe'] == 2 && $at['id_equipe']) {
				$ok = false;
			}
		}

		$result[10]	= $ok;

		if($f['h_guild'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_guild'] && $p) {
			if($f['h_guild'] == 1 && !$at['id_guild']) {
				$ok = false;
			} elseif($f['h_guild'] == 2 && $at['id_guild']) {
				$ok = false;
			}
		}

		$result[11]	= $ok;

		/*
		if($f['h_graduando'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_graduando'] && $p) {
			if($f['h_graduando'] == 1 && !$at['graduando']) {
				$ok = false;
			} elseif($f['h_graduando'] == 2 && $at['graduando']) {
				$ok = false;
			}
		}

		$result[12]	= $ok;
		*/

		if($f['h_espera'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_espera'] && $p) {
			if($f['h_espera'] == 1 && !$at['id_sala']) {
				$ok = false;
			} elseif($f['h_espera'] == 2 && $at['id_sala']) {
				$ok = false;
			}
		}

		$result[13]	= $ok;

		if($f['h_missao'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_missao'] && $p) {
			if($f['h_missao'] == 1 && (!$at['missao_comum'] && !$at['missao_interativa'] && !$at['id_missao_especial'])) {
				$ok = false;
			} elseif($f['h_missao'] == 2 && ($at['missao_comum'] || $at['missao_interativa'] || $at['id_missao_especial'])) {
				$ok = false;
			}
		}

		$result[14]	= $ok;

		if($f['h_missao_tempo'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_missao_tempo'] && $p) {
			if($f['h_missao_tempo'] == 1 && !$at['missao_comum']) {
				$ok = false;
			} elseif($f['h_missao_tempo'] == 2 && $at['missao_comum']) {
				$ok = false;
			}
		}

		$result[15]	= $ok;

		if($f['h_missao_interativa'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_missao_interativa'] && $p) {
			if($f['h_missao_interativa'] == 1 && !$at['missao_interativa']) {
				$ok = false;
			} elseif($f['h_missao_interativa'] == 2 && $at['missao_interativa']) {
				$ok = false;
			}
		}

		$result[16]	= $ok;

		if($f['h_missao_especial'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_missao_especial'] && $p) {
			if($f['h_missao_especial'] == 1 && !$at['id_missao_especial']) {
				$ok = false;
			} elseif($f['h_missao_especial'] == 2 && $at['id_missao_especial']) {
				$ok = false;
			}
		}

		$result[17]	= $ok;

		if($f['h_missao_guild'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_missao_guild'] && $p) {
			if($f['h_missao_guild'] == 1 && !$at['id_missao_guild']) {
				$ok = false;
			} elseif($f['h_missao_guild'] == 2 && $at['id_missao_guild']) {
				$ok = false;
			}
		}

		$result[18]	= $ok;

		if($f['h_evento'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_evento'] && $p) {
			if($f['h_evento'] == 1 && !$at['id_evento']) {
				$ok = false;
			} elseif($f['h_evento'] == 2 && $at['id_evento']) {
				$ok = false;
			}
		}

		$result[19]	= $ok;

		if($f['h_gm'] == 1 && !$_SESSION['universal']) {
			$ok = false;
		} elseif($f['h_gm'] == 2 && $_SESSION['universal']) {
			$ok = false;
		}

		$result[20]	= $ok;

		if($f['h_graduacao'] && !$p) {
			$ok = false;
		} elseif($f['h_graduacao'] && $p) {
			if($f['h_graduacao'] && $at['id_graduacao'] < $f['h_graduacao']) {
				$ok = false;
			}
		}

		$result[21]	= $ok;

		if($f['h_graduacaoi'] && !$p) {
			$ok = false;
		} elseif($f['h_graduacaoi'] && $p) {
			if($f['h_graduacaoi'] && $at['id_graduacao'] > $f['h_graduacaoi']) {
				$ok = false;
			}
		}

		$result[22]	= $ok;

		if($f['h_proximo_nivel'] && $proximo_nivel) {
			$ok = false;
		}

		$result[23]	= $ok;

		if($f['h_level'] && !$p) {
			$ok = false;
		} elseif($f['h_level'] && $p) {
			if($at['level'] < $f['h_level']) {
				$ok = false;
			}
		}

		$result[24]	= $ok;

		if($f['h_torneio'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_torneio'] && $p) {
			if($f['h_torneio'] == 1 && !$at['id_torneio']) {
				$ok = false;
			} elseif($f['h_torneio'] == 2 && $at['id_torneio']) {
				$ok = false;
			}
		}

		$result[25]	= $ok;

		if($f['h_torneio_espera'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_torneio_espera'] && $p) {
			if($f['h_torneio_espera'] == 1 && !$at['id_torneio_espera']) {
				$ok = false;
			} elseif($f['h_torneio_espera'] == 2 && $at['id_torneio_espera']) {
				$ok = false;
			}
		}

		$result[26]	= $ok;

		if($f['h_vila_inicial'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_vila_inicial'] && $p) {
			if($f['h_vila_inicial'] == 1 && !$p->vila_atual_inicial) {
				$ok = false;
			} elseif($f['h_vila_inicial'] == 2 && !$p->vila_atual_inicial) {
				$ok = false;
			}
		}

		$result[27]	= $ok;

		if($f['h_id_vila'] != 0 && !$p) {
			$ok = false;
		} elseif($f['h_id_vila'] && $p) {
			if($f['h_id_vila'] && $p->id_vila_atual == $f['h_id_vila']) {
				$ok = false;
			}
		}

		$result[28]	= $ok;

		if($f['h_no_id_vila'] != 0 && !$p) {
			$ok = false;
		} elseif($f['h_no_id_vila'] && $p) {
			if($f['h_no_id_vila'] && $p->id_vila_atual == $f['h_no_id_vila']) {
				$ok = false;
			}
		}

		$result[29]	= $ok;

		if($f['h_missao_guild2'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_missao_guild2'] && $p) {
			if($f['h_missao_guild2'] == 1 && !$at['id_missao_guild2']) {
				$ok = false;
			} elseif($f['h_missao_guild2'] == 2 && $at['id_missao_guild2']) {
				$ok = false;
			}
		}

		$result[30]	= $ok;

		if($f['h_arena'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_arena'] && $p) {
			if($f['h_arena'] == 1 && !$at['id_arena']) {
				$ok = false;
			} elseif($f['h_arena'] == 2 && $at['id_arena']) {
				$ok = false;
			}
		}

		$result[31]	= $ok;

		if(isset($f['href']) && $_SESSION['universal']) {
			if(!isset($_SESSION['negado'])) {
				$_SESSION['negado'] = array();
			}

			$_SESSION['negado'][$f['href']]	= $result;
		}

		$result[32]	= $ok;

		if($f['h_espera_multi_pvp'] == 1 && !$p) {
			$ok	= false;
		} elseif($f['h_espera_multi_pvp']) {
			if($f['h_espera_multi_pvp'] == 1 && !$at['id_sala_multi_pvp']) {
				$ok = false;
			} elseif($f['h_espera_multi_pvp'] == 2 && $at['id_sala_multi_pvp']) {
				$ok = false;
			}
		}

		$result[33]	= $ok;

		if($f['h_batalha_multi_pvp'] == 1 && !$p) {
			$ok	= false;
		} elseif($f['h_batalha_multi_pvp']) {
			if($f['h_batalha_multi_pvp'] == 1 && !$at['id_batalha_multi_pvp']) {
				$ok = false;
			} elseif($f['h_batalha_multi_pvp'] == 2 && $at['id_batalha_multi_pvp']) {
				$ok = false;
			}
		}

		$result[34]	= $ok;

		if($f['h_random_queue'] == 1 && !$p) {
			$ok	= false;
		} elseif($f['h_random_queue']) {
			if($f['h_random_queue'] == 1 && !$at['id_random_queue']) {
				$ok = false;
			} elseif($f['h_random_queue'] == 2 && $at['id_random_queue']) {
				$ok = false;
			}
		}

		$result[35]	= $ok;

		if($f['h_exame'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_exame'] && $p) {
			if($f['h_exame'] == 1 && !$at['id_exame_chuunin']) {
				$ok = false;
			} elseif($f['h_exame'] == 2 && $at['id_exame_chuunin']) {
				$ok = false;
			}
		}

		$result[36]	= $ok;

		if($f['h_sensei'] == 1 && !$p) {
			$ok = false;
		} elseif($f['h_sensei'] && $p) {
			if($f['h_sensei'] == 1 && !$at['id_sensei']) {
				$ok = false;
			} elseif($f['h_sensei'] == 2 && $at['id_sensei']) {
				$ok = false;
			}
		}

		$result[37]	= $ok;

		return $ok;
	}

	function barra_exp($valor, $maximo, $tamanho, $texto, $fundo, $frente, $modificador = "", $at = "", $return = false) {
		$width = barWidth($valor, $maximo, $tamanho);
		$centro = $tamanho-86;

		$out = "
		<div class='barra_exp' style='background-color: $fundo; width: {$tamanho}px;' $at>
			<div id='p' style='width: {$width}px; background-color: $frente'></div>
			<div id='l' style='background-image: url(images/barras_exp/barra_exp_l{$modificador}.png)'></div>
			<div id='c' style='background-image: url(images/barras_exp/barra_exp_c{$modificador}.png); width: {$centro}px'></div>
			<div id='r' style='background-image: url(images/barras_exp/barra_exp_r{$modificador}.png)'></div>
			<div id='t'>$texto</div>
		</div>
		";

		if($return) {
			return $out;
		} else {
			echo $out;
		}
	}
	function barra_exp3($valor, $maximo, $tamanho, $texto, $fundo, $frente, $modificador = "", $at = "", $return = false) {
		$width = barWidth($valor, $maximo, $tamanho);

		if($tamanho==132){
			$nome_vazio = "barra_vazia_151";
			$nome_cheio = "barra_cheia_151";
		}else if($tamanho==200){
			$nome_vazio = "barra_vazia_200";
			$nome_cheio = "barra_cheia_200";
		}else if($tamanho==220){
			$nome_vazio = "barra_vazia_220";
			$nome_cheio = "barra_cheia_220";
		}else if($tamanho==396){
			$nome_vazio = "barra_vazia_396";
			$nome_cheio = "barra_cheia_396";
		}else if($tamanho==327){
			$nome_vazio = "barra_vazia_340";
			$nome_cheio = "barra_cheia_340";
		}else if($tamanho==581){
			$nome_vazio = "barra_vazia_580_2";
			$nome_cheio = "barra_cheia_580_2";
		}else if($tamanho==730){
			$nome_vazio = "barra_vazia_765";
			$nome_cheio = "barra_cheia_765";
		}else if($tamanho==121){
			$nome_vazio = "barra_vazia_121";
			$nome_cheio = "barra_cheia_121";
		}else if($tamanho==580){
			$nome_vazio = "barra_vazia_580";
			$nome_cheio = "barra_cheia_580";
		}
		$centro = $tamanho-86;


		$out = "
		<div class='barra_exp3' style='background-image: url(images/layout".LAYOUT_TEMPLATE."/barra_exp/". $nome_vazio .".png); width: {$tamanho}px;' $at>
			<div id='p' style='width: {$width}px; background-image: url(images/layout".LAYOUT_TEMPLATE."/barra_exp/". $nome_cheio .".png);'></div>
			<div id='t'>$texto</div>
		</div>
		";

		if($return) {
			return $out;
		} else {
			echo $out;
		}
	}
	function barra_exp5($valor, $maximo, $tamanho, $texto, $fundo, $frente, $modificador, $at, $tipo, $return = false) {
		$width = barWidth($valor, $maximo, $tamanho);

		if($tamanho==119){
			$nome_vazio = $tipo."2_off";
			$nome_cheio = $tipo."2_on";
		}
		$centro = $tamanho-86;


		$out = "
		<div class='barra_exp5' style='background-image: url(images/layout/barra_exp/". $nome_vazio .".png); width: {$tamanho}px; height: 18px; background-repeat: no-repeat' $at>
			<div id='p' style='width: {$width}px; height: 18px; background-image: url(images/layout/barra_exp/". $nome_cheio .".png); background-repeat: no-repeat'></div>
			<div id='t'>$texto</div>
		</div>
		";

		if($return) {
			return $out;
		} else {
			echo $out;
		}
	}
	function barra_exp4($valor, $maximo, $tamanho, $texto, $at = "", $lado="") {
		$width = barWidth($valor, $maximo, $tamanho);
		if($lado){
			if($tamanho==219){
				$nome_vazio = "hp_off_r";
				$nome_cheio = "hp_on_r";
			}else if($tamanho==261){
				$nome_vazio = "sp_off_r";
				$nome_cheio = "sp_on_r";
			}else if($tamanho==232){
				$nome_vazio = "sta_off_r";
				$nome_cheio = "sta_on_r";
			}
		}else{
			if($tamanho==219){
				$nome_vazio = "hp_off";
				$nome_cheio = "hp_on";
			}else if($tamanho==261){
				$nome_vazio = "sp_off";
				$nome_cheio = "sp_on";
			}else if($tamanho==232){
				$nome_vazio = "sta_off";
				$nome_cheio = "sta_on";
			}
		}
		$centro = $tamanho-86;


		$out = "
		<div class='barra_exp4' style='background-image: url(images/layout/barra_exp/". $nome_vazio .".png); background-repeat: no-repeat; width: {$tamanho}px; height: 22px;' $at>
			<div id='p' style='width: {$width}px; height: 22px; background-image: url(images/layout/barra_exp/". $nome_cheio .".png); background-repeat: no-repeat;'></div>
			<div id='t'>$texto</div>
		</div>
		";

		echo $out;
	}

	function player_at_check() {
		global $basePlayer;

		$items	= $basePlayer->getItems([5, 24, 37, 16, 17, 20, 21, 26]);
		$types	= [
			16	=> 'id_cla',
			17	=> 'portao',
			20	=> 'id_selo',
			21	=> 'id_invocacao',
			26	=> 'id_sennin'
		];

		foreach ($items as $item) {
			$ok	= true;

			if(in_array($item->id_tipo, $types)) {
				$prop	= $types[$item->id_tipo];

				if(!$basePlayer->$prop) {
					$ok	= false;
				}
			} elseif(in_array($item->id_tipo, [24, 37])) { // Kinjutsu/Medicinal
				if($item->req_graduacao && $item->req_graduacao > $basePlayer->getAttribute('id_graduacao')) {
					$ok = false;
				}

				if ($item->id_tipo == 24) {
					if($item->ordem > Player::getFlag('equipe_role_1_lvl', $basePlayer->id)) {
						$ok = false;
					}
				} else {
					if($item->ordem > Player::getFlag('equipe_role_4_lvl', $basePlayer->id)) {
						$ok = false;
					}
				}
			} else { // Técnicas
				$item->setPlayerInstance($basePlayer);
				$item->parseLevel();

				$ok	= Item::hasRequirement($item, $basePlayer, NULL, array(
						'req_con'	=> true,
						'req_agi'	=> true,
						'req_level'	=> true
					));
			}

			if(!$ok) {
				Recordset::update('player_item', [
					'removido'	=> 1
				], [
					'id'		=> $item->uid
				]);
			}
		}

		// Ajusta os itens da arvore --->
			$arvore_gasto	= 0;
			$items			= Recordset::query('SELECT a.*, b.arvore_pai FROM player_item a JOIN item b ON b.id=a.id_item WHERE a.id_item_tipo=25 AND a.id_player=' . $basePlayer->id);

			foreach($items->result_array() as $item) {
				$ii		= Recordset::query('SELECT id FROM item WHERE arvore_pai=' . $item['id_item'], true);

				$arvore_gasto++;

				if($ii->num_rows) {
					$iib	= Recordset::query('SELECT id FROM player_item WHERE id_player=' . $basePlayer->id . ' AND id_item=' . $ii->row()->id);

					// não tem item q depende desse
					if(!$iib->num_rows) {
						//echo $item['id_item'] . ' -> NAO -> EQUIP ' . PHP_EOL;
						Recordset::update('player_item', array(
							'equipado'	=> '1'
						), array(
							'id'		=> $item['id']
						));
					} else {
						//echo $item['id_item'] . ' -> SIM' . PHP_EOL;
						Recordset::update('player_item', array(
							'equipado'	=> '0'
						), array(
							'id'		=> $item['id']
						));
					}
				} else {
					//echo $item['id_item'] . ' -> NAO -> EQUIP ' . PHP_EOL;

					Recordset::update('player_item', array(
						'equipado'	=> '1'
					), array(
						'id'		=> $item['id']
					));
				}
			}

			Recordset::query('UPDATE player SET arvore_gasto=' . $arvore_gasto . ' WHERE id=' . $basePlayer->id);
		// <---

		// clas e afins --->
			$basePlayer->rebuildItems();

			$tipos = array(16, 17, 20, 21, 26);

			foreach($tipos as $tipo) {
				$items = Recordset::query('SELECT id FROM item a WHERE a.id_tipo=' . $tipo . ' ORDER BY a.ordem DESC', true);

				foreach($items->result_array() as $item) {
					foreach($basePlayer->arItems as $rItem) {
						if($rItem['id_item'] == $item['id']) {
							Recordset::update('player_item' , array(
								'ativo'	=> '1'
							), array(
								'id'	=> $rItem['id']
							));

							break 2;
						}
					}

				}
			}
		// <---
	}

	function img($url = "") {
		$port = '';

		if ($_SERVER['SERVER_PORT'] !== 80 && $_SERVER['SERVER_PORT'] !== 443) {
			$port = ":{$_SERVER['SERVER_PORT']}";
		}

		return server_protocol() . '://' . $_SERVER['SERVER_NAME'] . $port . '/images/' . $url;
	}

	function server_protocol() {
		return $_SERVER['SERVER_PORT'] == '443' ? 'https' : 'http';
	}

	function batalha_log($p, $e, $win, $tied) {
		$log	= Recordset::query('SELECT id FROM player_batalhas_log WHERE id_player=' . $p . ' AND id_playerb=' . $e);

		if($tied) {
			$tied	= $tied ? 1 : 0;
			$win	= 0;
			$loss	= 0;
		} else {
			$tied	= 0;
			$win	= $win  ? 1 : 0;
			$loss	= $win  ? 0 : 1;
		}

		if($log->num_rows) {
			Recordset::update('player_batalhas_log', [
				'vitorias'	=> ['escape' => false, 'value' => 'vitorias + ' . $win],
				'derrotas'	=> ['escape' => false, 'value' => 'derrotas + ' . $loss],
				'empates'	=> ['escape' => false, 'value' => 'empates + '  . $tied]
			], [
				'id'	=> $log->row()->id
			]);
		} else {
			Recordset::insert('player_batalhas_log', [
				'id_player'		=> $p,
				'id_playerb'	=> $e,
				'vitorias'		=> $win,
				'derrotas'		=> $loss,
				'empates'		=> $tied
			]);
		}
	}

	function item_validade($need, $ignore = NULL) {
		global $basePlayer;

		$r = Recordset::query("SELECT DATEDIFF(NOW(), data_ins) AS diff FROM coin_log WHERE id_player=" . $basePlayer->id . " AND id_item IN(" . implode(",", $need) . ")")->row_array();

		if($ignore) {
			if($basePlayer->hasItem($need) && !$basePlayer->hasItem($ignore)) {
				return absm(150 - $r['diff']);
			} else {
				return false;
			}
		} else {
			if($basePlayer->hasItem($need)) {
				return absm(150 - $r['diff']);
			} else {
				return false;
			}
		}
	}

	function get_user_field($field) {
		return Recordset::query('SELECT `'. $field . '` FROM global.user WHERE id=' . $_SESSION['usuario']['id'])->row()->$field;
	}

	function captcha_text_gen($id) {
		$_SESSION['_CAPTCHA'][strtolower($id)] = strtoupper(dechex(rand(4096, 65535)));

		$out	= '';
		$i		= 0;

		while($i++ < strlen($_SESSION['_CAPTCHA'][$id])) {
			$r = str_pad(dechex(rand(0, 255)), 2, "0", STR_PAD_LEFT);
			$g = str_pad(dechex(rand(0, 175)), 2, "0", STR_PAD_LEFT);
			$b = str_pad(dechex(rand(0, 175)), 2, "0", STR_PAD_LEFT);

			$space = str_pad("", rand(1, 30), " ");

			$out .= "<script>  $space document.write(\"<span $space style='font-size: 18px; $space padding: 4px; background-color: #FFF; color: #$r$g$b' $space>" . $_SESSION['_CAPTCHA'][$id][$i - 1] . "</span>\")</script>";
		}
		return $out;
	}

	function captcha_text_validate($id, $val) {
		if($_SESSION['_CAPTCHA'][strtolower($id)] != strtoupper($val)) {
			return false;
		} else {
			return true;
		}
	}

	function player_imagem($player, $tipo = NULL, $res = null) {
	if(!$tipo || $tipo == 'pequena') {
		if($res) {
			$r	= $res;
		} else {
			$r 	= Recordset::query("SELECT b.imagem, b.id_classe FROM player_imagem a JOIN classe_imagem b ON b.id=a.id_classe_imagem WHERE a.id_player=" . $player);
		}

		$profile	= $tipo == 'pequena' ? 'profile-4x4' : 'profile';

		if(!$r->num_rows) {
			$r = Recordset::query("SELECT id_classe FROM player WHERE id=" . $player)->row_array();

			return img() ."layout". LAYOUT_TEMPLATE . "/" . $profile . "/" . $r['id_classe'] . "/1". (LAYOUT_TEMPLATE=="_azul" ? ".jpg":".png");
		} else {
			$r	= $r->row_array();

			return img() ."layout". LAYOUT_TEMPLATE . "/" . $profile . "/" . $r['id_classe'] . "/" . $r['imagem'] . (LAYOUT_TEMPLATE=="_azul" ? ".jpg":".png");
		}
	 } elseif($tipo == 'npc') {
			return img() . "layout". LAYOUT_TEMPLATE . "/profile4x4/" . $player . "/1". (LAYOUT_TEMPLATE=="_azul" ? ".jpg":".png");
	 }
	}

	function player_tema($player, $res = null) {
		if(!is_a($player, 'Player')) {
			$player	= new Player($player);
		}

		if($res) {
			$imagem	= $res;
		} else {
			$imagem = Recordset::query("SELECT b.imagem, b.id_classe, b.tema, b.id FROM player_imagem a JOIN classe_imagem b ON b.id=a.id_classe_imagem WHERE a.id_player=" . $player->id);
		}

		if($imagem->num_rows) {
			$imagem	= $imagem->row_array();

			if($imagem['tema']) {
				return 'images/layout'.LAYOUT_TEMPLATE.'/topo-logado/' . $imagem['id_classe'] . '-' . $imagem['imagem'] . '.jpg';
			} else {
				return 'images/layout'.LAYOUT_TEMPLATE.'/topo-logado/' . $player->id_classe . '.jpg';
			}
		} else {
			return 'images/layout'.LAYOUT_TEMPLATE.'/topo-logado/' . $player->id_classe . '.jpg';
		}
	}

	function player_imagem_ultimate($player, $tipo = NULL) {
		if(!is_a($player, 'Player')) {
			$player	= new Player($player);
		}

		$imagem = Recordset::query("SELECT b.imagem, b.id_classe, b.tema, b.id, b.ultimate FROM player_imagem a JOIN classe_imagem b ON b.id=a.id_classe_imagem WHERE a.id_player=" . $player->id);

		if($imagem->num_rows && $imagem->row()->ultimate) {
			$file	= ROOT . "/images/layout" . LAYOUT_TEMPLATE . "/profile/" . $player->id_classe . "/" . $imagem->row()->imagem . ".swf";
			$mt		= filemtime($file);

			return '<embed '. (LAYOUT_TEMPLATE=="_azul" ? 'height="238" width="195"' : 'height="241" width="226"') .' src="' . img('layout' . LAYOUT_TEMPLATE .'/profile/' . $player->id_classe . '/' . $imagem->row()->imagem . '.swf?_cache=' . $mt) . '" quality="high" wmode="transparent" allowscriptaccess="always" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash"></embed>';
		} else {
			return '<img src="' . player_imagem($player->id, $tipo, $imagem) . '" />';
		}
	}

	function player_icone($player) {
		$vila	= Recordset::query('
			SELECT
				id_kage,
				id_cons_guerra,
				id_cons_defesa,
				id_cons_vila

			FROM
				vila

			WHERE
				id_kage=' . $player . ' OR
				id_cons_guerra=' . $player . ' OR
				id_cons_defesa=' . $player . ' OR
				id_cons_vila=' . $player);

		if(!$vila->num_rows) {
			return '';
		} else {
			$vila	= $vila->row_array();
			$icon	= '';

			if($vila['id_kage'] == $player) {
				$icon	= 'kage';
			}

			if($vila['id_cons_guerra'] == $player) {
				$icon	= 'espada';
			}

			if($vila['id_cons_defesa'] == $player) {
				$icon	= 'shield';
			}

			if($vila['id_cons_vila'] == $player) {
				$icon	= 'megafone';
			}

			return '<img align="absmiddle" src="' . img('layout/chat/' . $icon . '.png') .  '" />&nbsp;';
		}
	}

	function has_chance($val) {
		$rnd = rand(0, 400) / 4;
		return $rnd <= $val ? true : false;
	}

	function get_chance() {
		return rand(0, 400) / 4;
	}

	function get_time_diff($ts) {
		if(is_numeric($ts)) {
			$ts = date('Y-m-d H:i:s', strtotime($ts));
		}

		$now = date("Y-m-d H:i:s");
		$time = Recordset::query("SELECT TIMEDIFF('" . $ts . "', '" . $now . "') AS diff, DAY(DATEDIFF('" . $ts . "', '" . $now . "')) AS days")->row_array();
		$diff = explode(":", $time['diff']);

		return array(
			"h" => (int)$diff[0] + ($time['days'] ? $time['days'] * 24 : 0),
			"m" => (int)$diff[1],
			"s" => (int)$diff[2]
		);
	}

	function equipe_exp($total) {
		global $basePlayer;

		if(!$basePlayer->id_equipe) {
			return;
		}

		// Player --->
			$diff = 4900 - Recordset::query('SELECT exp_equipe_dia FROM player WHERE id=' . $basePlayer->id)->row()->exp_equipe_dia;
			$total1 = $total > $diff ? $diff : $total;

			if($total1 > 0) {
				Recordset::query('UPDATE player SET exp_equipe_dia=exp_equipe_dia+' . $total1 . ' WHERE id=' . $basePlayer->id);
				Recordset::query('UPDATE player SET exp_equipe_dia_total=exp_equipe_dia_total+' . $total1 . ' WHERE id=' . $basePlayer->id);
			}
		// <---

		// Equipe --->
			if($total1 > 0) {
				$equipe = new Recordset('SELECT exp_level_dia FROM equipe WHERE id=' . $basePlayer->getAttribute('id_equipe'));
				$equipe = $equipe->row_array();

				$diff = 19600 - $equipe['exp_level_dia'];
				$total2 = $total > $diff ? $diff : $total;
				$total2 = $total2 > $total1 ? $total1 : $total2;

				if($total2 > 0) {
					Recordset::query('UPDATE equipe SET exp_level_dia=exp_level_dia+' . $total2 . ',exp_level=exp_level+' . $total2 . ' WHERE id=' . $basePlayer->getAttribute('id_equipe'));
				}
			}
		// <---

		// Bonificações --->
			$equipe = new Recordset('SELECT a.level, b.id_sorte_ninja, a.exp_level AS total, IFNULL(b.exp,0) AS needed FROM equipe a LEFT JOIN equipe_level b ON b.id=a.level WHERE a.id=' . $basePlayer->getAttribute('id_equipe'));
			$equipe = $equipe->row_array();

			if($equipe['total'] >= $equipe['needed']) {
				if($equipe['level'] != 26) {
					Recordset::query('UPDATE equipe SET level=level+1, exp_level=exp_level-' . $equipe['needed'] . ' WHERE id=' . $basePlayer->getAttribute('id_equipe'));

					$sorte_ninja = new Recordset('SELECT * FROM loteria_premio WHERE id=' . (int)$equipe['id_sorte_ninja']);
					$sorte_ninja = $sorte_ninja->row_array();

					$players = new Recordset('SELECT * FROM player WHERE id_equipe=' . $basePlayer->getAttribute('id_equipe') . ' AND removido=0');

					foreach($players->result_array() as $player) {
						if($player['equipe_nivel_min'] >= $equipe['level']) {
							continue;
						}

						Recordset::query('UPDATE player SET equipe_nivel_min=' . $equipe['level'] . ' WHERE id=' . $player['id']);

						// Log recompensa
						Recordset::insert('player_recompensa_log', array(
							'fonte'		=> 'equipe_lvl',
							'id_player'	=> $player['id'],
							'id_item'	=> $sorte_ninja['id_item'],
							'qtd_item'	=> $sorte_ninja['mul'],
							'ryou'		=> $sorte_ninja['ryou'],
							'coin'		=> $sorte_ninja['coin'],
							'exp'		=> $sorte_ninja['exp'],
							'treino_total'	=> $sorte_ninja['treino'],
							'tai'		=> $sorte_ninja['tai'],
							'ken'		=> $sorte_ninja['ken'],
							'nin'		=> $sorte_ninja['nin'],
							'gen'		=> $sorte_ninja['gen'],
							'ene'		=> $sorte_ninja['ene'],
							'inte'		=> $sorte_ninja['inte'],
							'forc'		=> $sorte_ninja['forc'],
							'agi'		=> $sorte_ninja['agi'],
							'con'		=> $sorte_ninja['con'],
							'res'		=> $sorte_ninja['res']
						));
					}
				}
			}
		// <---
	}

	function guild_objetivo_exp($player, $objective, $total = 1) {
		return;
		if($player->id_guild) {
			$squad	= Recordset::query('
				SELECT
					*

				FROM
					guild_esquadrao

				WHERE
					id_player=' . $player->id . ' AND
					id_guild=' . $player->id_guild);

			if($squad->num_rows) {
				$group			= Recordset::query('SELECT * FROM guild_objetivos WHERE grupo=1 AND objetivo=' . $objective);
				$solo			= Recordset::query('SELECT * FROM guild_objetivos WHERE grupo=0 AND objetivo=' . $objective);
				$max			= $solo->row()->total;
				$solo_max		= $max;
				$group_taken	= 0;

				$mine_other		= Recordset::query('
					SELECT
						*

					FROM
						guild_objetivos_player

					WHERE
						id_player=' . $player->id . ' AND
						id_guild!=' . $player->id_guild . ' AND
						objetivo=' . $objective);

				// Ja completei em outra guild? gtfo
				if($mine_other->num_rows && $mine_other->row()->recompensa) {
					return;
				}


				$mine			= Recordset::query('
					SELECT
						*

					FROM
						guild_objetivos_player

					WHERE
						id_player=' . $player->id . ' AND
						id_guild=' . $player->id_guild . ' AND
						objetivo=' . $objective);

				if($group->num_rows) {
					$group_data		= $group->row_array();
					$group_taken	= (int)Recordset::query('
						SELECT
							SUM(total) AS total

						FROM
							guild_objetivos_player

						WHERE
							id_guild=' . $player->id_guild . ' AND
							objetivo=' . $objective)->row()->total;

					if($group_taken < $group_data['total'] && $group_data['excesso']) {
						$max	+= $group_data['excesso'];

						if($mine->num_rows) {
							$max	-= $mine->row()->total;
						}
					}
				}

				if(!$mine->num_rows) {
					if($max) {
						Recordset::insert('guild_objetivos_player', [
							'id_player'	=> $player->id,
							'id_guild'	=> $player->id_guild,
							'objetivo'	=> $objective,
							'total'		=> $total
						]);
					}
				} else {
					if($mine->row()->total < $max) {
						Recordset::update('guild_objetivos_player', [
							'total'	=> ['escape' => false, 'value' => 'total + ' . $total]
						], [
							'id'	=> $mine->row()->id,
						]);
					}
				}

				$mine->repeat();
				$solo_data	= $solo->row_array();

				// Solo rewards 'cause yolo
				if($mine->num_rows && !$mine->row()->recompensa && $mine->row()->total >= $solo_max) {
					Recordset::update('guild_objetivos_player', [
						'recompensa'	=> 1
					], [
						'id'			=> $mine->row()->id
					]);

					// Stat player
					Recordset::insert('guild_objetivos_player_estatistica', [
						'id_player'	=> $player->id,
						'id_guild'	=> $player->id_guild,
						'esquadrao'	=> $squad->row()->esquadrao,
						'objetivo'	=> $objective,
						'data'		=> date('Y-m-d')
					]);

					Recordset::insert('player_recompensa_log', [
						'fonte'			=> 'guild_obj_solo',
						'id_player'		=> $player->id,
						'exp'			=> $solo_data['exp_player'],
						'ryou'			=> $solo_data['ryous'],
						'treino_total'	=> $solo_data['treino'],
						'reputacao'		=> $solo_data['reputacao'],
						'recebido'		=> 0
					]);

					Recordset::update('guild', [
						'exp_level'	=> ['escape' => false, 'value' => 'exp_level + ' . $solo_data['exp_guild']],
						'exp_total'	=> ['escape' => false, 'value' => 'exp_total + ' . $solo_data['exp_guild']],
						'diarias'	=> ['escape' => false, 'value' => 'diarias + 1']
					], [
						'id'		=> $player->id_guild
					]);

					$group_taken++;
				}

				// Group rewards mofo!
				if($group->num_rows && $group_taken >= $group->row()->total) {
					$group_reward	= Recordset::query('SELECT * FROM guild_objetivos_recompensa WHERE id_guild=' . $player->id_guild . ' AND objetivo=' . $objective);

					if(!$group_reward->num_rows) {
						$group_data	= $group->row_array();

						Recordset::update('guild', [
							'exp_level'	=> ['escape' => false, 'value' => 'exp_level + ' . $solo_data['exp_guild']],
							'exp_total'	=> ['escape' => false, 'value' => 'exp_total + ' . $solo_data['exp_guild']],
							'diarias2'	=> ['escape' => false, 'value' => 'diarias2 + 1']
						], [
							'id'		=> $player->id_guild
						]);

						Recordset::insert('guild_objetivos_recompensa', [
							'id_guild'	=> $player->id_guild,
							'objetivo'	=> $objective
						]);

						// Stat guild
						Recordset::insert('guild_objetivos_recompensa_estatistica', [
							'id_guild'	=> $player->id_guild,
							'objetivo'	=> $objective,
							'data'		=> date('Y-m-d')
						]);

						$players	= Recordset::query('SELECT id FROM player WHERE id_guild=' . $player->id_guild);

						foreach ($players->result_array() as $p) {
							Recordset::insert('player_recompensa_log', [
								'fonte'			=> 'guild_obj_grupo',
								'id_player'		=> $p['id'],
								'exp'			=> $group_data['exp_player'],
								'ryou'			=> $group_data['ryous'],
								'treino_total'	=> $group_data['treino'],
								'reputacao'		=> $group_data['reputacao'],
								'recebido'		=> 0
							]);
						}
					}
				}
			}

			// Bonificações --->
				$guild = Recordset::query('SELECT a.level, b.id_sorte_ninja, a.exp_level AS total, b.exp AS needed FROM guild a JOIN guild_level b ON b.id=a.level WHERE a.id=' . $player->id_guild);
				$guild = $guild->row_array();

				if($guild['total'] >= $guild['needed']) {
					if($guild['level'] != 26) {
						Recordset::query('UPDATE guild SET level=level+1, exp_level=exp_level-' . $guild['needed'] . ' WHERE id=' . $player->id_guild);
					}

					$sorte_ninja	= Recordset::query('SELECT * FROM loteria_premio WHERE id=' . (int)$guild['id_sorte_ninja']);
					$sorte_ninja	= $sorte_ninja->row_array();
					$players		= Recordset::query('SELECT id, guild_nivel_min FROM player WHERE id_guild=' . $player->id_guild . ' AND removido=0');

					foreach($players->result_array() as $p) {
						if($p['guild_nivel_min'] >= $guild['level']) {
							continue;
						}

						Recordset::query('UPDATE player SET guild_nivel_min=' . $guild['level'] . ' WHERE id=' . $p['id']);

						// Log recompensa
						Recordset::insert('player_recompensa_log', array(
							'fonte'		=> 'guild_lvl',
							'id_player'	=> $p['id'],
							'id_item'	=> $sorte_ninja['id_item'],
							'qtd_item'	=> $sorte_ninja['mul'],
							'ryou'		=> $sorte_ninja['ryou'],
							'coin'		=> $sorte_ninja['coin'],
							'exp'		=> $sorte_ninja['exp'],
							'treino'	=> $sorte_ninja['treino'],
							'tai'		=> $sorte_ninja['tai'],
							'ken'		=> $sorte_ninja['ken'],
							'nin'		=> $sorte_ninja['nin'],
							'gen'		=> $sorte_ninja['gen'],
							'ene'		=> $sorte_ninja['ene'],
							'inte'		=> $sorte_ninja['inte'],
							'forc'		=> $sorte_ninja['forc'],
							'agi'		=> $sorte_ninja['agi'],
							'con'		=> $sorte_ninja['con'],
							'res'		=> $sorte_ninja['res']
						));
					}
				}
			// <---
		}
	}

	function vila_objetivo_exp($player, $objective, $total = 1) {
		$data		= Recordset::query('SELECT * FROM vila_objetivos_player WHERE id_vila=' . $player->id_vila . ' AND objetivo=' . $objective);
		$objective	= Recordset::query('SELECT * FROM vila_objetivos WHERE objetivo=' . $objective, true)->row();

		if (!$data->num_rows) {
			Recordset::insert('vila_objetivos_player', [
				'id_vila'	=> $player->id_vila,
				'total'		=> $total,
				'objetivo'	=> $objective->objetivo
			]);

			$current	= 1;
		} else {
			Recordset::update('vila_objetivos_player', [
				'total'		=> ['escape' => false, 'value' => 'total + ' . $total]
			], [
				'id_vila'	=> $player->id_vila,
				'objetivo'	=> $objective->objetivo
			]);

			$current	= $total + $data->row()->total;
		}

		if ($current >= $objective->total && $objective) {
			if (!$data->num_rows || $data->num_rows && !$data->row()->recompensa) {
				Recordset::update('vila_objetivos_player', [
					'recompensa'	=> 1
				], [
					'id_vila'		=> $player->id_vila,
					'objetivo'		=> $objective->objetivo
				]);

				Recordset::update('vila', [
					'objetivos'	=> ['escape' => false, 'value' => 'objetivos + 1']
				], [
					'id'		=> $player->id_vila
				]);

				vila_exp($objective->exp_vila,$player->id_vila);
			}
		}
	}

	function get_equipe_status($id) {
		$players = new Recordset('
			SELECT
				a.id,
				a.nome,
				IFNULL(MINUTE(TIMEDIFF(a.ult_atividade, NOW())), 99) AS diff,
				a.id_missao,
				a.id_batalha,
				a.id_batalha_multi,
				a.id_batalha_multi_pvp,
				a.id_sala,
				a.id_sala_multi_pvp,
				a.dentro_vila,
				a.id_vila,
				a.id_vila_atual,
				a.hospital,
				a.treino_tempo_jutsu,
				b.ip,
				a.treinando,
				a.id_random_queue


			FROM player a JOIN global.user b ON b.id=a.id_usuario

			WHERE
				a.id_equipe=' . $id);

		$ret = array(
			'players' => $players->num_rows
		);

		foreach($players->result_array() as $k => $p) {
			$ret[$k] = array(
				'activity' =>	$p['diff'] > 2 ? false : true,
				'battle' =>		($p['id_batalha'] || $p['id_batalha_multi'] || $p['id_batalha_multi_pvp']) || ($p['id_sala'] || $p['id_sala_multi_pvp']) || $p['id_random_queue'] ? true : false,
				'quest' =>		$p['id_missao'] ? true : false,
				'nomap' =>		!$p['dentro_vila'] || ($p['dentro_vila'] && $p['id_vila_atual'] != $p['id_vila']), //between($p['id_vila_atual'], 9, 13)
				//'grad' =>		$p['graduando'] ? true : false,
				'hospital' =>	$p['hospital'] ? true : false,
				'tjutsu' =>		$p['treino_tempo_jutsu'] ? true : false,
				'train' =>		$p['treinando'] ? true : false,
				'name' =>		$p['nome'],
				'id' =>			$p['id'],
				'ip' =>			$p['id']
			);
		}

		return $ret;
	}

	function mensageiro($from, $dest, $t, $m, $type = 'player') {
		Recordset::insert('mensagem', [
			'id_envio'		=> $from,
			'id_para'		=> $dest,
			'titulo'		=> $t,
			'corpo'			=> $m,
			'mensagem_tipo'	=> $type
		]);
	}

	function add_player_bloqueio($user, $player) {
		Recordset::insert('player_bloqueio', [
			'id_usuario'	=> $user,
			'id_player'		=> $player
		]);
	}

	function get_player_bloqueio($player) {
		return Recordset::query('SELECT id FROM player_bloqueio WHERE id_usuario=' . (int)$player)->num_rows;
	}

	function torneio_batalha($player, $batalha) {
		$q = Recordset::query('SELECT * FROM torneio_espera WHERE id_player=' . $player . ' AND id_batalha=' . $batalha);

		if(!$q->num_rows) {
			$q = Recordset::query('SELECT * FROM torneio_espera WHERE id_player_b=' . $player . ' AND id_batalha=' . $batalha);

			return $q->num_rows ? true : false;
		} else {
			return true;
		}
	}

	function torneio_loss($player, $batalha, $fuga = false, $npc = false) {
		// Se não for fuga, continua na mesma chave
		if(!$fuga) {
			//$torneio_player	= Recordset::query('SELECT * FROM torneio_espera WHERE id_player=' . $player . ' AND id_batalha=' . $batalha)->row_array();
			$torneio_player	= Recordset::query('SELECT * FROM torneio_player WHERE id_player=' . $player . ' AND participando=\'1\'');

			if(!$torneio_player->num_rows) {
				return;
			}

			$torneio_player = $torneio_player->row_array();

			$torneio		= Recordset::query('SELECT * FROM torneio WHERE id=' . $torneio_player['id_torneio'])->row_array();
			$torneio_chave	= Recordset::query('SELECT * FROM torneio_player WHERE id_player=' . $player . ' AND id_torneio=' . $torneio['id'])->row_array();

			Recordset::query('UPDATE torneio_player SET chave=1, derrotas=derrotas+1, participando=\'0\' WHERE id_player=' . $player . ' AND id_torneio=' . $torneio['id']);

			if($npc) {
				Recordset::query('DELETE FROM torneio_espera WHERE id_player=' . $player . ' AND id_torneio=' . $torneio['id']);
			}
		}
	}

	function torneio_win($player, $batalha, $fuga = false, $npc = false) {
		if(!$fuga) {
			//$torneio_player	= Recordset::query('SELECT * FROM torneio_player WHERE id_player=' . $player . ' AND id_batalha=' . $batalha)->row_array();
			$torneio_player	= Recordset::query('SELECT * FROM torneio_player WHERE id_player=' . $player . ' AND participando=\'1\'');

			if(!$torneio_player->num_rows) {
				return;
			}

			$torneio_player = $torneio_player->row_array();

			$torneio		= Recordset::query('SELECT *, nome_'.Locale::get().' AS nome FROM torneio WHERE id=' . $torneio_player['id_torneio'])->row_array();
			$torneio_chave	= Recordset::query('SELECT * FROM torneio_player WHERE id_player=' . $player . ' AND id_torneio=' . (int)$torneio['id'])->row_array();

			if($torneio_chave['chave'] + 1 > $torneio['chaves']) {
				Recordset::query('UPDATE torneio_player SET chave=1, participando=\'0\', vitorias=vitorias+1 WHERE id_player=' . $player . ' AND id_torneio=' . $torneio['id']);

				// Bonificações e afins --->
					$m = 'Por vencer o torneio, você ganhou ' . $torneio['exp'] . ' pontos de experiência, ' . $torneio['treino'] . ' pontos de treino e ' . $torneio['ryou'] . ' ryous';

					mensageiro(NULL, $player, 'Parabéns, você venceu o torneio: ' . $torneio['nome'], $m);
					Recordset::update('player', array(
						'exp'		=> array('value' => 'exp+' . $torneio['exp'], 'escape' => false),
						'treino_total'	=> array('value' => 'treino_total+' . $torneio['treino'], 'escape' => false),
						'ryou'		=> array('value' => 'ryou+' . $torneio['ryou'], 'escape' => false)
					), array(
						'id'	=> $player
					));

					// Recompensa
					Recordset::insert('player_recompensa_log', array(
						'fonte'			=> 'torneio_' . ($npc ? 'npc' : 'pvp'),
						'id_player'		=> $player,
						'recebido'		=> 1,
						'exp'			=> $torneio['exp'],
						'treino'		=> $torneio['treino'],
						'ryou'			=> $torneio['ryou']
					));
				// <---

				//Bloqueia o manolo pra ser 1 so por dia(ok, nada de usar função player pra isso ja que não vem objeto no parâmetro =( ... ) --->
				if(!$npc){	
					Recordset::update('player_flags', array(
						'torneio_ganho'	=> '1',
					), array(
						'id_player'		=> $player
					));
				}
				// <---

				// ARCH --->
					arch_parse(NG_ARCH_TORNEIO, $player, NULL, $torneio['id']);
				// <---

				Recordset::insert('torneio_player_torneio', array(
					'id_player'		=> $player,
					'id_torneio'	=> $torneio['id']
				));
			} else {
				Recordset::query('UPDATE torneio_player SET chave=chave+1 WHERE id_player=' . $player . ' AND id_torneio=' . $torneio['id']);
			}

			if($npc) {
				Recordset::query('DELETE FROM torneio_espera WHERE id_player=' . $player . ' AND id_torneio=' . $torneio['id']);
			}
		}
	}

	function blankize($a) {
		foreach($a as $k => $v) {
			if(is_null($v)) {
				$a[$k] = '';
			}
		}

		return $a;
	}
	function verifica_diplomacia($id_vila, $id_vilab){
		$sql = Recordset::query("select * from diplomacia where id_vila=". $id_vila ." and id_vilab=". $id_vilab)->row_array();
		$sqlb = Recordset::query("select * from diplomacia where id_vila=". $id_vilab ." and id_vilab=". $id_vila)->row_array();

		if($sql['dipl'] == 1 && $sqlb['dipl'] == 1){
			return true;
		} else {
			return false;
		}
	}
	function reputacao($player, $vila, $rep = 100) {
		if(!Recordset::query('SELECT id_player FROM player_reputacao WHERE id_player=' . $player . ' AND id_vila=' . $vila)->num_rows) {
			Recordset::insert('player_reputacao', array(
				'id_player'		=> $player,
				'id_reputacao'	=> 5,
				'id_vila'		=> $vila,
				'reputacao'		=> $rep
			));
		} else {
			Recordset::update('player_reputacao', array(
				'reputacao'	=> array('escape' => false, 'value' => 'reputacao+' . $rep)
			), array(
				'id_player'	=> $player,
				'id_vila'	=> $vila
			));
		}

		// Muda o nivel da rep se necessário
		$rep = Recordset::query('
			SELECT
				a.nome_' . Locale::get() . ' AS nome,
				a.pontos AS max_reputacao,
				b.reputacao,
				b.id_reputacao
			FROM
				reputacao a
				JOIN player_reputacao b ON b.id_reputacao=a.id AND b.id_vila=' . $vila . ' AND b.id_player=' . $player
		)->row_array();

		if($rep['max_reputacao'] > 0 && $rep['reputacao'] >= $rep['max_reputacao']) {
			$rep_left = $rep['reputacao'] - $rep['max_reputacao'];

			if($vila == 6 && $rep['id_reputacao'] == 5) {
				Recordset::update('player_reputacao', array(
					'id_reputacao'	=> 1,
					'reputacao'		=> $rep_left
				), array(
					'id_player'		=> $player,
					'id_vila'		=> $vila
				));
			} else {
				Recordset::update('player_reputacao', array(
					'id_reputacao'	=> array('escape' => false, 'value' => 'id_reputacao+1'),
					'reputacao'		=> $rep_left
				), array(
					'id_player'		=> $player,
					'id_vila'		=> $vila
				));
			}
		}
	}

	function gzserialize($data) {
		if(!$data) {
			return NULL;
		}

		return gzcompress(serialize($data), 9);
	}

	function gzunserialize($data) {
		if(!$data) {
			return NULL;
		}

		return unserialize(gzuncompress($data));
	}

	function bingo_book_morto($basePlayer, $baseEnemy, $inversion = true) {
		if($basePlayer->getAttribute('id_graduacao') >= 2) {
			if($rBingoBook = is_bingo_book_vila($basePlayer->id_vila, $baseEnemy->id, $inversion)) {
				global_message2('msg_global.bingo_book_vila', array($basePlayer->nome, $baseEnemy->nome, $baseEnemy->nome_vila));

				/*$rBingoBook['exp']			+= $rBingoBook['exp'];*/
				/*$rBingoBook['ryou']		+= percent($basePlayer->bonus_profissao['bb_recompensa'], $rBingoBook['ryou']);
				$rBingoBook['pt_treino']	+= percent($basePlayer->bonus_profissao['bb_recompensa'], $rBingoBook['pt_treino']);*/

		
				vila_exp((int)$rBingoBook['exp'], $basePlayer->id_vila);
				Recordset::query('
					UPDATE
						bingo_book_vila

					SET
						morto="1"

					WHERE
						id_vila=' . $basePlayer->id_vila . ' AND id_player_alvo=' . $baseEnemy->id);

				/*reputacao($basePlayer->id,$basePlayer->id_vila, 200);*/
			}
		}
		if($basePlayer->getAttribute('id_graduacao') >= 4) {
			if($rBingoBook = is_bingo_book_player($basePlayer->id, $baseEnemy->id, $inversion)) {
				global_message2('msg_global.bingo_book', array($basePlayer->nome, $baseEnemy->nome));

				$rBingoBook['exp']			+= percent($basePlayer->bonus_profissao['bb_recompensa'], $rBingoBook['exp']);
				$rBingoBook['ryou']			+= percent($basePlayer->bonus_profissao['bb_recompensa'], $rBingoBook['ryou']);
				$rBingoBook['pt_treino']	+= percent($basePlayer->bonus_profissao['bb_recompensa'], $rBingoBook['pt_treino']);

				Recordset::query("
					UPDATE
						player
					SET
						ryou=ryou+" . (int)$rBingoBook['ryou'] . ",
						`exp`=`exp`+" . (int)$rBingoBook['exp'] . ",
						treino_total=treino_total+" . (int)$rBingoBook['pt_treino'] . "
					WHERE
						id=" . $basePlayer->id);

				Recordset::query('
					UPDATE
						bingo_book

					SET
						morto="1"

					WHERE
						id_player=' . $basePlayer->id . ' AND
						(
							id_player_alvo=' . $baseEnemy->id . '
						)');

				// Recompensa
				Recordset::insert('player_recompensa_log', array(
					'fonte'			=> 'bingo_book',
					'id_player'		=> $basePlayer->id,
					'recebido'		=> 1,
					'exp'			=> (int)$rBingoBook['exp'],
					'ryou'			=> (int)$rBingoBook['ryou'],
					'treino_total'	=> (int)$rBingoBook['pt_treino']
				));

				reputacao($basePlayer->id,$basePlayer->id_vila, 200);
			}
		}

		if($basePlayer->id_guild) {
			if($rBingoBook = is_bingo_book_guild($basePlayer, $baseEnemy, $inversion)) {
				global_message2('msg_global.bingo_book_guild', array($basePlayer->nome_guild, $baseEnemy->nome));
				guild_exp($rBingoBook['exp'], true);

				Recordset::query('
					UPDATE
						bingo_book_guild

					SET
						morto="1",
						id_player_morto=' . $basePlayer->id . '

					WHERE
						id_guild=' . $basePlayer->id_guild . ' AND
						(
							id_player_alvo=' . $baseEnemy->id . ' OR
							id_player_alvo2=' . $baseEnemy->id . ' OR
							id_player_alvo3=' . $baseEnemy->id . '
						)');
				
				$qPlayers = Recordset::query("select id from player where id_guild =" . $basePlayer->id_guild);
				while($rp = $qPlayers->row_array()) {
					// Recompensa
					Recordset::insert('player_recompensa_log', array(
						'fonte'			=> 'bingo_book_guild',
						'id_player'		=> $rp['id'],
						'recebido'		=> 0,
						'exp'			=> (int)$rBingoBook['exp'],
						'ryou'			=> (int)$rBingoBook['ryou'],
						'treino_total'	=> (int)$rBingoBook['pt_treino']
					));
				}
			}
		}

		if($basePlayer->id_equipe) {
			if($rBingoBook = is_bingo_book_equipe($basePlayer, $baseEnemy, $inversion)) {
				global_message2('msg_global.bingo_book_equipe', array($basePlayer->nome_equipe, $baseEnemy->nome));
				equipe_exp($rBingoBook['exp'], true);

				Recordset::query('
					UPDATE
						bingo_book_equipe

					SET
						morto="1",
						id_player_morto=' . $basePlayer->id . '

					WHERE
						id_equipe=' . $basePlayer->id_equipe . ' AND
						(
							id_player_alvo=' . $baseEnemy->id . '
						)');
				
				
				$qPlayers = Recordset::query("select id from player where id_equipe =".$basePlayer->id_equipe);
				while($rp = $qPlayers->row_array()) {
					// Recompensa
					Recordset::insert('player_recompensa_log', array(
						'fonte'			=> 'bingo_book_equipe',
						'id_player'		=> $rp['id'],
						'recebido'		=> 0,
						'exp'			=> (int)$rBingoBook['exp'],
						'ryou'			=> (int)$rBingoBook['ryou'],
						'treino_total'	=> (int)$rBingoBook['pt_treino']
					));
				}				
			}
		}
	}

	function check_words($text) {
		$expressions	= Recordset::query('SELECT * FROM word_blacklist');

		foreach($expressions->result_array() as $expr) {
			$text = preg_replace('/' . $expr['expr'] . '/i', '****', $text);
		}

		return $text;
	}

	class StaticCache {
		static $_store = array();

		static function store($k, $v) {
			StaticCache::$_store[$k] = $v;
		}

		static function get($k, $default = NULL) {
			if(!isset(StaticCache::$_store[$k])) {
				if(!is_null($default)) {
					StaticCache::store($k, $default);

					return $default;
				} else {
					return false;
				}
			} else {
				return StaticCache::$_store[$k];
			}
		}

		static function flush() {
			StaticCache::$_store	= array();
		}
	}

	function array2obj($a) {
		$out = new stdClass();

		foreach($a as $k => $v) {
			$out->$k = $v;
		}

		return $out;
	}

	function array_concat_by_keys() {
		$args		= array_slice(func_get_args(), 1);
		$source		= func_get_arg(0);
		$ret		= array();

		foreach($args as $arg) {
			if(isset($source[$arg])) {
				$ret	= array_merge($ret, $source[$arg]);
			}
		}

		return $ret;
	}

	function player_online($player, $results = false) {
		if ($player) {
			$player	= Recordset::query('
				SELECT
					(
						HOUR(TIMEDIFF(NOW(), ult_atividade)) * 60 +
						MINUTE(TIMEDIFF(NOW(), ult_atividade))
					) AS diff

				FROM
					player_posicao
				WHERE id_player=' . $player);
		}

		if($player && $player->num_rows) {
			if((int)$player->row()->diff > 2 || is_null($player->row()->diff)) {
				if($results) {
					return '<img src="' . img('layout/off.png') . '" style="position:relative; top: 4px" alt="Player Offline" />';
				} else {
					return false;
				}
			} else {
				if($results) {
					return '<img src="' . img('layout/on.png') . '" style="position:relative; top: 4px" alt="Player Online"/>';
				} else {
					return true;
				}
			}
		} else {
			if($results) {
				return '<img src="' . img('layout/off.png') . '" style="position:relative; top: 4px" alt="Player Offline"/>';
			} else {
				return false;
			}
		}
	}

	function salt_encrypt($v, $key) {
		$cipher =  "aes-256-cbc";
		$iv = substr($key, 0, 16);

		return base64_encode(openssl_encrypt($v, $cipher, $key, 0, $iv));
	}

	function salt_decrypt($v, $key) {
		$cipher =  "aes-256-cbc";
		$iv = substr($key, 0, 16);

		return openssl_decrypt(base64_decode($v), $cipher, $key, 0, $iv);
	}

	function msg($foto, $titulo, $texto, $return = false) {
		global $basePlayer;

		$village	= $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);

		ob_start();
?>
		<div class="msg_gai">
			<div class="msg">
				<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village ?>/<?php echo $foto ?>.png); background-repeat: no-repeat;">
					<b><?php echo $titulo ?></b>
					<p><?php echo $texto ?></p>
				</div>
			</div>
		</div>
		<div style="clear: both"></div>
<?php
		if($return) {
			return ob_get_clean();
		} else {
			echo ob_get_clean();
		}
	}

	function hospital_vila_cura() {
		global $basePlayer;

		$data			= array();

		$hp_percent		= percent($basePlayer->bonus_vila['hospital_vida'], $basePlayer->max_hp);
		$sp_percent		= percent($basePlayer->bonus_vila['hospital_vida'], $basePlayer->max_sp);
		$sta_percent	= percent($basePlayer->bonus_vila['hospital_vida'], $basePlayer->max_sta);

		if($hp_percent < $basePlayer->getAttribute('less_hp')) {
			$data['less_hp']	= $basePlayer->getAttribute('less_hp') - $hp_percent;
		} else {
			$data['less_hp']	= 0;
		}

		if($sp_percent < $basePlayer->getAttribute('less_sp')) {
			$data['less_sp']	= $basePlayer->getAttribute('less_sp') - $sp_percent;
		} else {
			$data['less_sp']	= 0;
		}

		if($sta_percent < $basePlayer->getAttribute('less_sta')) {
			$data['less_sta']	= $basePlayer->getAttribute('less_sta') - $sta_percent;
		} else {
			$data['less_sta']	= 0;
		}

		Recordset::update('player', $data, array(
			'id'	=> $basePlayer->id
		));
	}

	function vila_exp($exp, $vila = NULL) {
		if(!$vila) {
			global $basePlayer;
		} else {
			$basePlayer				= new stdClass();
			$basePlayer->id_vila	= $vila;
		}

		$vila	= Recordset::query('SELECT * FROM vila WHERE id=' . $basePlayer->id_vila)->row_array();
		$level	= Recordset::query('SELECT * FROM vila_nivel WHERE id=' . ($vila['nivel_vila'] + 1))->row_array();
		$total	= $vila['exp_vila'] + $exp;

		if($total >= $level['exp']) {
			Recordset::update('vila', array(
				'exp_vila'		=> $total - $level['exp'],
				'nivel_vila'	=> array('escape' => false, 'value' => 'nivel_vila+1'),
				'nivel_ok'		=> array('escape' => false, 'value' => 'nivel_ok+1')
			), array(
				'id'			=> $basePlayer->id_vila
			));

			global_message2('msg_global.vila', array($vila['nome_' . Locale::get()]));
		} else {
			Recordset::update('vila', array(
				'exp_vila'	=> array('escape' => false, 'value' => 'exp_vila + ' . $exp)
			), array(
				'id'		=> $basePlayer->id_vila
			));
		}
	}

	function vila_has_item($vila, $item) {
		return Recordset::query('SELECT id FROM vila_item WHERE vila_id=' . $vila . ' AND item_id=' . $item)->num_rows;
	}

	function player_nome($id){
		return Recordset::query('SELECT nome FROM player_nome WHERE id_player=' . $id)->row()->nome;
	}

	function anti_espionagem($player) {
		$item	= Recordset::query('SELECT * FROM player_item WHERE id_item=21879 AND id_player=' . $player);

		if($item->num_rows) {
			Recordset::update('player_item', array(
				'uso'	=> array('escape' => false, 'value' => 'uso-1')
			), array(
				'id'	=> $item->row()->id
			));

			if($item->row()->uso - 1 <= 0) {
				Recordset::delete('player_item', array(
					'id'	=> $item->row()->id
				));
			}

			return true;
		}

		return false;
	}

	function now($mysql_format = false) {
		if($mysql_format) {
			return date('Y-m-d H:i:s', strtotime('+0 minutes'));
		} else {
			return strtotime('+0 minutes');
		}
	}

	function global_message($message, $assigns = []) {
		return global_message2($message, $assigns, false);
	}
	function global_message2($message, $assigns = [], $is_yaml = true) {
		$curl      = curl_init();
		$curl_options = [
			CURLOPT_URL             => 'https://' . $_SERVER['SERVER_ADDR'] . ':2533' . '/console/write/',
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_ENCODING        => '',
			CURLOPT_SSL_VERIFYPEER  => false,
			CURLOPT_MAXREDIRS       => 10,
			CURLOPT_TIMEOUT         => 30,
			CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
			CURLOPT_SSL_VERIFYHOST  => 0,
			CURLOPT_SSL_VERIFYPEER  => 0,
			CURLOPT_CUSTOMREQUEST   => 'POST',
			CURLOPT_HTTPHEADER      => ['Content-Type: application/json', 'Accept: application/json'],
			CURLOPT_POSTFIELDS      => json_encode([
				($is_yaml ? 'yaml' : 'message')     => $message,
				'token'                             => 'hW4aZ9kX2nP6uI8rY4xJ6iM1uZ6gH3vU',
				'assigns'                           => $assigns
			])
		];

		curl_setopt_array($curl, $curl_options);

		$response   = curl_exec($curl);
		$error      = curl_error($curl);
		$status     = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		if ($error) {
			return [
				'error'     => $error,
				'status'    => $status
			];
		} else {
			$decoded_response   = json_decode($response, TRUE);
			return [
				'status'    => $status,
				'response'  => $decoded_response ? $decoded_response : $response
			];
		}
	}

	function encode($v) {
		return urlencode(base64_encode($v));
	}

	function decode($v) {
		return base64_decode(urldecode($v));
	}

	function gHasItem($id) {
			global $basePlayer;

			if(is_array($id)) {
				$id = join(",", $id);
			} else {
				$id = (int)$id;
			}

			$qItem = Recordset::query("SELECT id FROM player_item WHERE id_item IN(" . $id . ") AND id_player=" . $basePlayer->id);

			return $qItem->num_rows ? true : false;
	}


	function gHasItemW($id, $player, $uso = NULL, $uso_diff = NULL) {
		if(is_array($id)) {
			$id = join(",", $id);
		} else {
			$id = (int)$id;
		}

		$where = '';

		if($uso) {
			$where .= " AND uso <= " . $uso;
		}

		if(!$uso_diff) { // Se tem o item
			$qItem = Recordset::query("SELECT id FROM player_item WHERE id_item IN(" . $id . ") AND id_player=" . $player . $where);

			return $qItem->num_rows ? true : false;
		} else { // Se tem o item e foi usado no espaço de tempo especificado
			$key	= 'usage_rev1_' . $player . '_' . $id;
			$now	= strtotime('+0 hour');

			/*
			if(!isset($_SESSION[$key])) {
				$qItem 			= Recordset::query("SELECT id, data_uso, uso, HOUR(TIMEDIFF(data_uso, NOW())) AS uso_diff FROM player_item WHERE id_item IN(" . $id . ") AND id_player=" . $player . $where);

				if(!$qItem->num_rows) {
					return false;
				}

				if(!$qItem->row()->data_uso) {
					return false;
				}

				$used_at		= $qItem->row()->uso_diff;
				$_SESSION[$key]	= $used_at;
			} else {
				$used_at		= $_SESSION[$key];
			}
			*/

			$qItem 		= Recordset::query("SELECT id, data_uso, uso, HOUR(TIMEDIFF(data_uso, NOW())) AS uso_diff FROM player_item WHERE id_item IN(" . $id . ") AND id_player=" . $player . $where);

			if(!$qItem->num_rows) {
				return false;
			}

			if(!$qItem->row()->data_uso) {
				return false;
			}

			$used_at	= $qItem->row()->uso_diff;

			if($used_at < $uso_diff) {
				return true;
			} else {
				return false;
			}
		}
	}

	function odds($max = null) {
		if(!$max) {
			if(defined('PVPT_MAX_TURNS_OVERRIDE')) {
				$max	= PVPT_MAX_TURNS_OVERRIDE;
			} else {
				$max	= PVPT_MAX_TURNS;
			}
		}

		$counter	= 1;
		$out		= array();

		for($f = 1; $f <= $max / 2; $f++) {
			$out[]		= $counter;
			$counter	+= 2;
		}

		return $out;
	}

	function evens($max = null) {
		if(!$max) {
			if(defined('PVPT_MAX_TURNS_OVERRIDE')) {
				$max	= PVPT_MAX_TURNS_OVERRIDE;
			} else {
				$max	= PVPT_MAX_TURNS;
			}
		}

		$counter	= 2;
		$out		= array();

		for($f = 1; $f <= $max / 2; $f++) {
			$out[]		= $counter;
			$counter	+= 2;
		}

		return $out;
	}

	function pvp_do_turn_rotation($battle, $players, $inversion = false) {
		$max_turns	= PVPT_MAX_TURNS;

		if(defined('PVPT_MAX_TURNS_OVERRIDE')) {
			$max_turns	= PVPT_MAX_TURNS_OVERRIDE;
		}

		$all_dead_p	= true;
		$all_dead_e	= true;
		$odds		= false;
		$live_ids	= array();
		$enemy_ids	= array();
		$return		= array(
			'next'		=> 0,
			'finished'	=> false,
			'winner'	=> 0,
			'draw'		=> false
		);

		foreach(odds() as $odd) {
			if($players[$odd]->alive) {
				$all_dead_p	= false;
			}
		}

		foreach(evens() as $even) {
			if($players[$even]->alive) {
				$all_dead_e	= false;
			}
		}

		if($all_dead_p && !$all_dead_e) {
			$return['finished']	= true;
			$return['winner']	= $battle['id_equipe_b'];

			return $return;
		} else if($all_dead_e && !$all_dead_p) {
			$return['finished']	= true;
			$return['winner']	= $battle['id_equipe_a'];

			return $return;
		} else if($all_dead_e && $all_dead_p) {
			$return['finished']	= true;
			$return['draw']		= true;

			return $return;
		}

		$counter	= $battle['current'] == $max_turns ? 1 : $battle['current'] + 1;

		while(true) {
			echo "Trying $counter of $max_turns\n";

			if($players[$counter]->alive) {
				$return['next']	= $counter;

				return $return;
			}

			if(++$counter > $max_turns) {
				$counter = 1;
			}
		}

		return $return;
	}

	function odd($number) {
		return (bool)($number % 2);
	}

	function even($number) {
		return !($number % 2);
	}

	function is_bingo_book($src, $target, $bb, $allow_rev = true) {
		if(!$bb) {
			return;
		}

		$return	= false;

		if(is_bingo_book_player($src, $target, $allow_rev)) {
			$return	= true;
		}
		if(is_bingo_book_vila($src, $target, $allow_rev)) {
			$return	= true;
		}
		if(is_bingo_book_guild($src, $target, $allow_rev)) {
			$return	= true;
		}

		if(is_bingo_book_equipe($src, $target, $allow_rev)) {
			$return	= true;
		}

		return $return;
	}

	function is_bingo_book_player($src, $target, $allow_rev = true) {
		if(!is_numeric($src)) {
			$src	= $src->id;
		}

		if(!is_numeric($target)) {
			$target	= $target->id;
		}

		$qBingoBook		= Recordset::query('
			SELECT
				*

			FROM
				bingo_book

			WHERE
				id_player=' . $src . ' AND
				morto="0" AND
				(id_player_alvo=' . $target . ')');

		if($qBingoBook->num_rows) {
			return $qBingoBook->row_array();
		}

		if($allow_rev) {
			$qBingoBookRev	= Recordset::query('
				SELECT
					*

				FROM
					bingo_book

				WHERE
					id_player=' . $target . ' AND
					morto="0" AND
					(id_player_alvo=' . $src . ')');

			if($qBingoBookRev->num_rows) {
				return $qBingoBookRev->row_array();
			}
		}

		return false;
	}
	function is_bingo_book_vila($src, $target, $allow_rev = true) {
		if(!is_numeric($src)) {
			$src	= $src->id;
		}

		if(!is_numeric($target)) {
			$target	= $target->id;
		}

		$qBingoBook		= Recordset::query('
			SELECT
				*

			FROM
				bingo_book_vila

			WHERE
				id_vila=' . $src . ' AND
				morto="0" AND sobrevivente= 0 AND id_player_alvo=' . $target );

		if($qBingoBook->num_rows) {
			return $qBingoBook->row_array();
		}

		if($allow_rev) {
			$qBingoBookRev	= Recordset::query('
				SELECT
					*

				FROM
					bingo_book_vila

				WHERE
					id_vila=' . $target . ' AND
					morto="0" AND sobrevivente= 0 AND id_player_alvo=' . $src );

			if($qBingoBookRev->num_rows) {
				return $qBingoBookRev->row_array();
			}
		}

		return false;
	}
	function is_bingo_book_guild($src, $target, $allow_rev = true) {
		$have_guild	= false;

		if(!is_numeric($src)) {
			$src_guild	= $src->id_guild;
			$src		= $src->id;
		} else {
			$src_guild	= Recordset::query('SELECT id_guild FROM player WHERE id=' . $src)->row()->id_guild;
		}

		if(!is_numeric($target)) {
			$target_guild	= $target->id_guild;
			$target			= $target->id;
		} else {
			$target_guild	= Recordset::query('SELECT id_guild FROM player WHERE id=' . $target)->row()->id_guild;
		}

		if($src_guild) {
			$bingo_book		= Recordset::query("
				SELECT
					*

				FROM
					bingo_book_guild

				WHERE
					morto='0' AND
					id_guild=" . $src_guild . " AND
					(id_player_alvo=" . $target . ")");

			if($bingo_book->num_rows) {
				return $bingo_book->row_array();
			}
		}

		if($target_guild && $allow_rev) {
			$bingo_book_rev	= Recordset::query("
				SELECT
					*

				FROM
					bingo_book_guild

				WHERE
					morto='0' AND
					id_guild=" . $target_guild . " AND
					(id_player_alvo=" . $src . ")");

			if($bingo_book_rev->num_rows) {
				return $bingo_book_rev->row_array();
			}
		}

		return false;
	}

	function is_bingo_book_equipe($src, $target, $allow_rev = true) {
		$have_equipe	= false;

		if(!is_numeric($src)) {
			$src_equipe	= $src->id_equipe;
			$src		= $src->id;
		} else {
			$src_equipe	= Recordset::query('SELECT id_equipe FROM player WHERE id=' . $src)->row()->id_equipe;
		}

		if(!is_numeric($target)) {
			$target_equipe	= $target->id_equipe;
			$target			= $target->id;
		} else {
			$target_equipe	= Recordset::query('SELECT id_equipe FROM player WHERE id=' . $target)->row()->id_equipe;
		}

		if($src_equipe) {
			$bingo_book		= Recordset::query("
				SELECT
					*

				FROM
					bingo_book_equipe

				WHERE
					morto='0' AND
					id_equipe=" . $src_equipe . " AND
					(id_player_alvo=" . $target . ")");

			if($bingo_book->num_rows) {
				return $bingo_book->row_array();
			}
		}

		if($target_equipe && $allow_rev) {
			$bingo_book_rev	= Recordset::query("
				SELECT
					*

				FROM
					bingo_book_equipe

				WHERE
					morto='0' AND
					id_equipe=" . $target_equipe . " AND
					(id_player_alvo=" . $src . ")");

			if($bingo_book_rev->num_rows) {
				return $bingo_book_rev->row_array();
			}
		}

		return false;
	}

	function array_is_empty($arr, $keys = NULL) {
		$result	= true;

		if($keys) {
			foreach($keys as $key) {
				if(isset($arr[$key]) && $arr[$key]) {
					$result	= false;
					break;
				}
			}
		} else {
			foreach($arr as $_ => $v) {
				if($v) {
					$result	= false;
					break;
				}
			}
		}

		return $result;
	}

	function as_percent($total, $v) {
		if($v == $total) {
			return 100;
		}

		return round(100*($v/$total));
	}

	function nl2nothing($text) {
		return preg_replace('/\r|\n/si', '', $text);
	}

	function cani_post_topic($post) {
		if(!$post || $_SESSION['universal']){
			return true;
		}
		$last_post_time	= Recordset::query('
			SELECT
				MINUTE(TIMEDIFF(data_ins, NOW())) + (HOUR(TIMEDIFF(data_ins, NOW())) * 60) AS total,
				data_ins

			FROM vila_forum_topico WHERE id_usuario=' . $_SESSION['usuario']['id'] . '
			ORDER BY 1 ASC
			LIMIT 1
		');

		if(!$last_post_time->num_rows) {
			return true;
		} else {
			if($last_post_time->row()->total < 10) {
				return false;
			} else {
				return true;
			}
		}
	}

	function cani_post_reply($post) {
		if(!$post || $_SESSION['universal']){
			return true;
		}
		$last_post_time	= Recordset::query('
			SELECT
				MINUTE(TIMEDIFF(data_ins, NOW())) + (HOUR(TIMEDIFF(data_ins, NOW())) * 60) AS total,
				data_ins

			FROM vila_forum_topico_post WHERE id_usuario=' . $_SESSION['usuario']['id'] . '
			ORDER BY 1 ASC
			LIMIT 1
		');

		if(!$last_post_time->num_rows) {
			return true;
		} else {
			if($last_post_time->row()->total < 3) {
				return false;
			} else {
				return true;
			}
		}
	}

	function graduation_name($village, $graduation) {
		return t('graduacoes.' . $village . '.' . $graduation);
	}

	function barra_exp_topo($max, $current, $fill) {
		if ($fill == 'exp') {
			$width	= barWidth($current, $max, 188);
			$text	= '<span>EXP: ' . $current . '/' . $max . '</span>';
			return '
				<div class="barra-exp-topo barra-exp-topo-exp">
					<div class="fill" style="width: ' . $width . 'px; background-image: url(' . img('layout/barras_topo/barra_topo_' . $fill) . '_fill.png)">' . $text . '
					<img src="' . img('layout/barras_topo/barra_exp_brilho.png') . '" />
					</div>
				</div>';
		} else {
			$width	= barWidth($current, $max, 57);
			return '<div class="barra-exp-topo"><div class="fill" style="width: ' . $width . 'px; background-image: url(' . img('layout/barras_topo/barra_topo_' . $fill) . '_fill.png)"></div></div>';
		}
	}

	function painel_pvp_item_js($item) {
		$return	= [
			'id'			=> $item->id,
			'n'				=> $item->getAttribute('nome'),
			't'				=> (float)$item->getAttribute('qtd'),
			'd'				=> nl2br($item->getAttribute('descricao')),
			'tr'			=> $item->getAttribute('turnos'),
			'hab'			=> (int)$item->getAttribute('id_habilidade'),
			'trl'			=> 0,
			'du'			=> (float)$item->getAttribute('cooldown'),
			'portao'		=> $item->id_tipo == 17,
			'atkf'			=> (float)$item->atk_fisico,
			'atkm'			=> (float)$item->atk_magico,
			'def'			=> (float)$item->def_base,
			'deff'			=> (float)$item->def_magico,
			'defm'			=> (float)$item->def_fisico,
			'tai'			=> (float)$item->tai,
			'ken'			=> (float)$item->ken,
			'nin'			=> (float)$item->nin,
			'gen'			=> (float)$item->gen,
			'agi'			=> (float)$item->agi,
			'con'			=> (float)$item->con,
			'ene'			=> (float)$item->ene,
			'forc'			=> (float)$item->forc,
			'inte'			=> (float)$item->inte,
			'res'			=> (float)$item->res,
			'precf'			=> (float)$item->getAttribute('prec_fisico'),
			'precm'			=> (float)$item->getAttribute('prec_magico'),
			'cmin'			=> (float)$item->getAttribute('crit_min'),
			'cmax'			=> (float)$item->getAttribute('crit_max'),
			'esq'			=> (float)$item->getAttribute('esq'),
			'det'			=> (float)$item->getAttribute('det'),
			'conv'			=> (float)$item->getAttribute('conv'),
			'conc'			=> (float)$item->getAttribute('conc'),
			'esquiva'		=> (float)$item->getAttribute('esquiva'),
			'b_hp'			=> (float)$item->bonus_hp,
			'b_sp'			=> (float)$item->bonus_sp,
			'b_sta'			=> (float)$item->bonus_sta,
			'enhance_crit'	=> (float)$item->crit_inc,
			'st'			=> $item->sem_turno && $item->hasModifiers() ? 1 : 0,
			'ste'			=> in_array($item->id_tipo, array(16, 17, 20, 21, 23, 24, 26, 39)) ? 1 : 0,
			'chp'			=> (float)$item->consume_hp,
			'csp'			=> (float)$item->consume_sp,
			'csta'			=> (float)$item->consume_sta,
			'pre'			=> $item->getAttribute('precisao'),
			'll'			=> $item->getAttribute('level_liberado'),
			'me'			=> $item->id_tipo == 24,
			'war_timer'		=> false
		];


		if ($item->id_tipo == 41) {
			$village	= Recordset::query('SELECT buff_guerra FROM vila WHERE id=' . $item->_playerInstance->id_vila)->row();

			if ($village->buff_guerra) {
				$return['war_timer']	= get_time_difference(now(), strtotime('+7 day', strtotime($village->buff_guerra)));
			}
		}

		if($item->getAttribute('sem_turno') && $item->hasModifiers()) {
			$modifiers	= $item->getModifiers();

			$has_mod_p	= $modifiers['self_ken']  		|| $modifiers['self_tai']  		|| $modifiers['self_nin']		|| $modifiers['self_gen']			|| $modifiers['self_agi']			|| $modifiers['self_con'] ||
						  $modifiers['self_ene']   		|| $modifiers['self_forc']		|| $modifiers['self_inte']			|| $modifiers['self_res']			|| $modifiers['self_atk_fisico'] ||
						  $modifiers['self_atk_magico']	|| $modifiers['self_def_base']	|| $modifiers['self_prec_fisico']	|| $modifiers['self_prec_magico']	|| $modifiers['self_crit_min'] ||
						  $modifiers['self_crit_max']	|| $modifiers['self_esq']		|| $modifiers['self_det']			|| $modifiers['self_conv']			|| $modifiers['self_conc']		||
						  $modifiers['self_def_fisico']	|| $modifiers['self_def_magico'] || $modifiers['self_esquiva'];

			$has_mod_e	= $modifiers['target_ken']  		|| $modifiers['target_tai']  		|| $modifiers['target_nin']			|| $modifiers['target_gen']			|| $modifiers['target_agi']			|| $modifiers['target_con'] ||
						  $modifiers['target_ene']   		|| $modifiers['target_forc']		|| $modifiers['target_inte']		|| $modifiers['target_res']			|| $modifiers['target_atk_fisico'] ||
						  $modifiers['target_atk_magico']	|| $modifiers['target_def_base']	|| $modifiers['target_prec_fisico']	|| $modifiers['target_prec_magico']	|| $modifiers['target_crit_min'] ||
						  $modifiers['target_crit_max']		|| $modifiers['target_esq']			|| $modifiers['target_det']			|| $modifiers['target_conv']		|| $modifiers['target_conc']	||
						  $modifiers['target_def_magico']	|| $modifiers['target_def_magico'] || $modifiers['target_esquiva'];

			$return['mo']	= [
				'p'	=> $has_mod_p,
				'e'	=> $has_mod_e
			];

			for($f = 0; $f <= 1; $f++) {
				if($f && !$has_mod_e || !$f && !$has_mod_p) {
					continue;
				}

				$mo	= [];

				foreach($modifiers as $k => $v) {
					if(strpos($k, !$f ? 'self_' : 'target_') === false) continue;

					$mo[$k]	= (float)$v;
				}

				$return['mo'][$f ? 'em' : 'pm']	= $mo;
			}
		}

		return json_encode($return);
	}

	function guild_exp($total, $tipo = NULL) {
		global $basePlayer;

		//Verificação de exp quinzenal
		if(!$tipo){
			$guild_exp_level_dia = Recordset::query('SELECT exp_level_dia FROM guild WHERE id=' . $basePlayer->id_guild)->row_array();
			// Caso tenha atingido o limite quinzenal não ganha mais nada
			if($guild_exp_level_dia['exp_level_dia'] >= 192000){
				return;
			}
		}

		// Dá a exp para a guild
		Recordset::update('guild', [
			'exp_level'	=> ['escape' => false, 'value' => 'exp_level + ' . $total]
		], [
			'id'		=> $basePlayer->id_guild
		]);
		// Dá a exp total da guild
		Recordset::update('guild', [
			'exp_total'	=> ['escape' => false, 'value' => 'exp_total + ' . $total]
		], [
			'id'		=> $basePlayer->id_guild
		]);
		$guild = Recordset::query('SELECT a.level, b.id_sorte_ninja, a.exp_level AS total, IFNULL(b.exp,0) AS needed FROM guild a LEFT JOIN guild_level b ON b.id=a.level WHERE a.id=' . $basePlayer->id_guild);
		$guild = $guild->row_array();


		// Passa de nível
		if($guild['total'] >= $guild['needed']) {
			if($guild['level'] != 26) {
				Recordset::query('UPDATE guild SET level=level+1, exp_level=exp_level-' . $guild['needed'] . ' WHERE id=' . $basePlayer->id_guild);

				$sorte_ninja	= Recordset::query('SELECT * FROM loteria_premio WHERE id=' . (int)$guild['id_sorte_ninja']);
				$sorte_ninja	= $sorte_ninja->row_array();
				$players		= Recordset::query('SELECT id, guild_nivel_min FROM player WHERE id_guild=' . $basePlayer->id_guild . ' AND removido=0');

				foreach($players->result_array() as $p) {
					if($p['guild_nivel_min'] >= $guild['level']) {
						continue;
					}

					Recordset::query('UPDATE player SET guild_nivel_min=' . $guild['level'] . ' WHERE id=' . $p['id']);

					// Log recompensa
					Recordset::insert('player_recompensa_log', array(
						'fonte'		=> 'guild_lvl',
						'id_player'	=> $p['id'],
						'id_item'	=> $sorte_ninja['id_item'],
						'qtd_item'	=> $sorte_ninja['mul'],
						'ryou'		=> $sorte_ninja['ryou'],
						'coin'		=> $sorte_ninja['coin'],
						'exp'		=> $sorte_ninja['exp'],
						'treino'	=> $sorte_ninja['treino'],
						'tai'		=> $sorte_ninja['tai'],
						'ken'		=> $sorte_ninja['ken'],
						'nin'		=> $sorte_ninja['nin'],
						'gen'		=> $sorte_ninja['gen'],
						'ene'		=> $sorte_ninja['ene'],
						'inte'		=> $sorte_ninja['inte'],
						'forc'		=> $sorte_ninja['forc'],
						'agi'		=> $sorte_ninja['agi'],
						'con'		=> $sorte_ninja['con'],
						'res'		=> $sorte_ninja['res']
					));
				}
			}
		}
	}

	function player_titulo_grad(&$text, $p) {
		for($f = 1; $f <= 8; $f++) {
			for($g = 1; $g <= 7; $g++) {
				$translation	= t('graduacoes.' . $f . '.' . $g);

				if($text == $translation) {
					$text	= t('graduacoes.' . $p->id_vila . '.' . $g);
					return;
				}
			}
		}
	}

	function mysql_compat_password($password) {
		return "CONCAT('*', UPPER(SHA1(UNHEX(SHA1(\"" . addslashes($password) . "\")))))";
	}
	function proxycheck_function($Visitor_IP) {

		// ------------------------------
		// SETTINGS
		// ------------------------------

		$API_Key = "51x41n-0y9m26-yi3v05-1n49w2"; // Supply your API key between the quotes if you have one
		$VPN = "1"; // Change this to 1 if you wish to perform VPN Checks on your visitors
		$TLS = "0"; // Change this to 1 to enable transport security, TLS is much slower though!
		$TAG = "1"; // Change this to 1 to enable tagging of your queries (will show within your dashboard)

		// If you would like to tag this traffic with a specific description place it between the quotes.
		// Without a custom tag entered below the domain and page url will be automatically used instead.
		$Custom_Tag = "Naruto Game"; // Example: $Custom_Tag = "My Forum Signup Page";

		// ------------------------------
		// END OF SETTINGS
		// ------------------------------

		// Setup the correct querying string for the transport security selected.
		if ( $TLS == 1 ) {
		  $Transport_Type_String = "https://";
		} else {
		  $Transport_Type_String = "http://";
		}

		// By default the tag used is your querying domain and the webpage being accessed
		// However you can supply your own descriptive tag or disable tagging altogether above.
		if ( $TAG == 1 && $Custom_Tag == "" ) {
		  $Post_Field = "tag=" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		} else if ( $TAG == 1 && $Custom_Tag != "" ) {
		  $Post_Field = "tag=" . $Custom_Tag;
		} else {
		  $Post_Field = "";
		}

		// Performing the API query to proxycheck.io/v2/ using cURL
		$ch = curl_init($Transport_Type_String . 'proxycheck.io/v2/' . $Visitor_IP . '?key=' . $API_Key . '&vpn=' . $VPN);

		$curl_options = array(
		  CURLOPT_CONNECTTIMEOUT => 30,
		  CURLOPT_POST => 1,
		  CURLOPT_POSTFIELDS => $Post_Field,
		  CURLOPT_RETURNTRANSFER => true
		);

		curl_setopt_array($ch, $curl_options);
		$API_JSON_Result = curl_exec($ch);
		curl_close($ch);

		// Decode the JSON from our API
		$Decoded_JSON = json_decode($API_JSON_Result);

		// Check if the IP we're testing is a proxy server
		if ( isset($Decoded_JSON->$Visitor_IP->proxy) && $Decoded_JSON->$Visitor_IP->proxy == "yes" ) {

		  // A proxy has been detected.
		  return true;

		} else {

		  // No proxy has been detected.
		  return false;

		}

	}
