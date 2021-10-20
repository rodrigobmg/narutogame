<?php
	define('IS_DEV', true);
	define('ROOT', __DIR__);

	define('VIP_ENC_KEY', 'xFqghtsuoyn9178nsdUN');
	define('VIP_INFO_PREFIX', 'Naruto Game');
	define('VIP_ENC_KEY_ID', 'NG');
	define('USER_COLOR_KAGE','rgb(52, 155, 236)');
	define('USER_COLOR_CONS_DEF', 'rgb(127, 190, 86)');
	define('USER_COLOR_CONS_VILA', 'rgb(177, 100, 180)');
	define('USER_COLOR_CONS_GUERRA', 'rgb(224, 53, 59)');

	define('CHAT_KEY', 'vX2oE1gS1jI6fP7cH3wU1cK2nA3mZ2bU');
	define ("LIMITE_MISSOES", serialize([
		1 => 2,
		2 => 4,
		3 => 6,
		4 => 8,
		5 => 10,
		6 => 12,
		7 => 12
	]));

	session_start();
	ini_set('display_errors', 'on');

	date_default_timezone_set('America/Sao_Paulo');
	error_reporting(E_ALL ^ E_STRICT);

	if(isset($_GET['cxx'])) {
		/*
		$secoes = array('academia_jutsu', 'equipamentos_ninja', 'personagem_status', 'arvore_talento', 'personagem_selecionar');

		$_GET['secao'] = $secoes[rand(0, sizeof($secoes) - 1)];
		*/

		$_GET['acao']				= 'proximo_nivel';
		$_SESSION['mapa_mundi_key']	= uniqid('', true);
		$_POST['key']				= $_SESSION['mapa_mundi_key'];
		$_POST['cx']				= 104;
		$_POST['cy']				= 79;

		$_GET['bf']		= 'dGFp';
		$_GET['f']		= 'MQ%253D%253D';

		$_SESSION['usuario'] = array();
		$_SESSION['usuario']['id']		= 1;
		$_SESSION['usuario']['nome']	= 'Fabio';
		$_SESSION['usuario']['email']	= 'fox.mc.cloud.pro@gmail.com';
		$_SESSION['usuario']['vip']		= '1';
		$_SESSION['usuario']['gm']		= '1';
		$_SESSION['usuario']['lang']	= 'br';

		$_SESSION['basePlayer'] = 1; //rand(1);
		$_SESSION['isAdmin']	= true;
		$_SESSION['logado']		= true;
		$_GET['isgm']		= 1;
		$_SESSION['isAdmin']    = true;
		$_SESSION['universal']    = true;

		$_SESSION['lang']	= 'br';
		$_SESSION['layout']	= 'r10';
	}

	if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
		$_SERVER['REMOTE_ADDR']	= $_SERVER['HTTP_CF_CONNECTING_IP'];
	}

	if (!isset($_SERVER['SERVER_ADDR'])) {
		$_SERVER['SERVER_ADDR']	= '127.0.0.1';
	}

	$regras_valido	= true;
	$remote_ip		= isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

	if ($remote_ip == "::1") {
		$remote_ip = "127.0.0.1";
	}

	if(!isset($_SESSION['orig_user_id'])) {
		$_SESSION['orig_user_id'] = null;
	}

	if(!isset($_SESSION['orig_player_id'])) {
		$_SESSION['orig_player_id'] = null;
	}

	if(!isset($_SESSION['orig_ticket_id'])) {
		$_SESSION['orig_ticket_id'] = null;
	}

	// Comente esse bloco pra entrar em modonormal -->
		if(!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
			if(isset($_GET['isgm'])) {
				$_SESSION['isAdmin'] = true;

				die("Flag ok, d&ecirc; F5 ... ");
			}

			require("manutencao.php");
			die(); 
		}
	// <--

	// Inicialização da sessão --->
		if(!isset($_SESSION['logado'])) {
			$_SESSION['logado']			= false;
			$_SESSION['basePlayer']		= NULL;
			$_SESSION['usuario']		= array();
			$_SESSION['universal']		= false;
			$_SESSION['last_upd']		= NULL;
		}

		if(!isset($_SESSION['isAdmin'])) {
			$_SESSION['isAdmin']		= false;
		}

		if($_SESSION['logado'] && !isset($_SESSION['usuario']['msg_vip'])) {
			$_SESSION['usuario']['msg_vip'] = 1;
		}
	// <---

	require("class/SharedStore.php");
	require("class/Recordset.php");

	SharedStore::$key_prefix	= "NG.r11";
	Recordset::$key_prefix		= "NG.r11";
	Recordset::$cache_mode		= RECORDSET_CACHE_FILE;

	Recordset::connect();

	// Arquivos de include --->
		require('traits/AttributeCalculationTrait.php');
		require('traits/ModifiersTrait.php');

		require('class/Player.php');
		require('class/Enemy.php');
		require('class/NPC.php');
		require('class/NPCEvento.php');
		require('class/NPCVila.php');
		require('class/Item.php');
		require('class/Graduation.php');
		require('class/Profession.php');
		require('class/Fight.php');
		require('class/EMail.php');
		require('class/Vila.php');

		require('class/SessionStorage.php');

		require('include/generic.php');
		require('include/conquista.php');
		require('include/securimage.php');
		require('include/item.php');
		require('include/cron.php');
		require('include/yaml.php');

		// novo login do face
		require('vendor/autoload.php');
		require('fb/fb.php');

		require('include/packer.php');
		require('include/sorte_ninja.php');
	// <---

	// Define o Layout --->
		if(isset($_GET['layout']) && in_array($_GET['layout'], array('r8', 'r10'))) {
			$_SESSION['layout']	= $_GET['layout'];

			if($_SESSION['logado']) {
				Recordset::update('global.user', array(
					'layout'	=> $_GET['layout']
				), array(
					'id'	=> $_SESSION['usuario']['id']
				));

				$_SESSION['usuario']['layout']	= $_GET['layout'];
			}
		}

		if(!isset($_SESSION['layout'])) {
			if($_SESSION['logado']) {
				$_SESSION['layout']	= $_SESSION['usuario']['layout'];
			} else {
				$_SESSION['layout']	= 'r10';
			}
		} else {
			if($_SESSION['logado']) {
				$_SESSION['layout']	= isset($_SESSION['usuario']['layout']) ? $_SESSION['usuario']['layout'] : "r10";
			}
		}

		if(!isset($_SESSION['layout'])) { // if can't extract browser language, default is br
			$_SESSION['layout']	= 'r10';
		}

		define('LAYOUT_TEMPLATE', $_SESSION['layout']=="r8" ? "_azul" : "");
	// <---

	// Define o idioma --->
		if(isset($_GET['lang']) && in_array($_GET['lang'], array('br', 'en', 'es'))) {
			$_SESSION['lang']	= $_GET['lang'];

			if($_SESSION['logado']) {
				Recordset::update('global.user', array(
					'lang'	=> $_GET['lang']
				), array(
					'id'	=> $_SESSION['usuario']['id']
				));

				$_SESSION['usuario']['lang']	= $_GET['lang'];
			}
		}

		if(!isset($_SESSION['lang'])) {
			if($_SESSION['logado']) {
				$_SESSION['lang']	= $_SESSION['usuario']['lang'];
			} else {
				if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
					$lang	= substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

					if(in_array($lang, array('en', 'pt'))) { //, 'es'
						if($lang == 'pt') {
							$lang	= 'br';
						}

						$_SESSION['lang']	= $lang;
					}
				} else {
					$_SESSION['lang']	= 'br';
				}
			}
		} else {
			if($_SESSION['logado']) {
				$_SESSION['lang']	= $_SESSION['usuario']['lang'];
			}
		}

		if(!isset($_SESSION['lang'])) { // if can't extract browser language, default is br
			$_SESSION['lang']	= 'br';
		}

		Locale::set($_SESSION['lang']);
	// <---

	// SID Checker --->
		if($_SESSION['logado'] && !$_SESSION['universal']) {
			$session_on_db = Recordset::query("SELECT sid FROM global.user_game_sid WHERE id_game=1 AND id_user=" . $_SESSION['usuario']['id'])->row();

			if($session_on_db->sid != session_id()) {
				session_destroy();
				redirect_to("negado");
			} else {
				header("SIDCHECK: OK");
			}
		}
	// <---

	$only_map_rules = false;

	if(isset($_SESSION['basePlayer']) && $_SESSION['basePlayer']) {
		if(isset($_GET['acao']) && on($_GET['acao'], 'dojo_lutador_lutar,dojo_batalha_lutar')) {//mapa_posicoes,mapa_image,mapa_posicoes_vila,
			$basePlayer 		= Recordset::query('SELECT a.*, b.ip FROM player a JOIN global.user b ON b.id=a.id_usuario WHERE a.id=' . $_SESSION['basePlayer'])->row();
			$only_map_rules		= true;

			$basePlayer->missao_equipe		= false;
			$basePlayer->missao_interativa	= false;
			$basePlayer->missao_comum		= false;
			$basePlayer->missao_invasao		= false;
			$basePlayer->id_missao_guild2	= 0;

			if($basePlayer->id_missao) {
				if ($basePlayer->id_missao == -1) {
					$basePlayer->missao_comum = true;
				} else {
					$qQuest = Recordset::query('SELECT interativa, equipe FROM quest WHERE id=' . $basePlayer->id_missao, true);

					if ($qQuest->num_rows) {
						$rQuest = $qQuest->row_array();

						if($rQuest['equipe']) {
							$basePlayer->missao_equipe	= true;
						}

						if($rQuest['interativa']) {
							$basePlayer->missao_interativa	= true;
						} else {
							$basePlayer->missao_comum		= true;
						}
					}
				}
			}

			if($basePlayer->id_guild) {
				$guild			= Recordset::query('SELECT * FROM guild WHERE id=' . $basePlayer->id_guild)->row_array();
				$missao_invasao = Recordset::query("SELECT SQL_NO_CACHE id, id_npc_vila, id_vila FROM vila_quest WHERE id_guild=" . $basePlayer->id_guild);

				if($missao_invasao->num_rows) {
					$missao_invasao = $missao_invasao->row_array();

					$basePlayer->missao_invasao			= $missao_invasao['id'];
					$basePlayer->missao_invasao_vila	= $missao_invasao['id_vila'];
					$basePlayer->missao_invasao_npc		= $missao_invasao['id_npc_vila'];
				}

				$basePlayer->id_missao_guild2	= $guild['id_quest_guild'];
			}
		} else {
			$basePlayer = new Player($_SESSION['basePlayer']);
			Player::setInstance($basePlayer);
		}
	} else {
		$basePlayer = NULL;
	}

	if($basePlayer) {
		// Atualização contÌnua no mapa mundi somente quando ele estiver fora da vila
		if(!$basePlayer->id_vila_atual) {
			Recordset::update('player_posicao', array(
				'nome'					=> $basePlayer->nome,
				'classe'				=> $basePlayer->id_classe,
				'vila'					=> $basePlayer->id_vila,
				'vila_atual'			=> $basePlayer->id_vila_atual,
				'dentro_vila'			=> $basePlayer->dentro_vila,
				'nivel'					=> $basePlayer->level,
				'batalha'				=> $basePlayer->id_batalha,
				'hospital'				=> $basePlayer->hospital,
				'id_guild'				=> $basePlayer->id_guild,
				'evento'				=> $basePlayer->id_evento,
				'missao'				=> $basePlayer->id_missao,
				'missao_interativa'		=> $basePlayer->missao_interativa ? 1 : 0,
				'missao_guild'			=> $basePlayer->id_missao_guild ? 1 : 0,
				'ult_atividade'			=> array('escape' => false, 'value' => 'NOW()')
			), array(
				'id_player'				=> $basePlayer->id
			));
		} else {
			Recordset::update('player_posicao', array(
				'nome'					=> $basePlayer->nome,
				'hospital'				=> $basePlayer->hospital,
				'classe'				=> $basePlayer->id_classe,
				'dentro_vila'			=> $basePlayer->dentro_vila,
				'nivel'					=> $basePlayer->level,
				'vila'					=> $basePlayer->id_vila,
				'vila_atual'			=> $basePlayer->id_vila_atual,
				'batalha'				=> $basePlayer->id_batalha,
				'id_guild'				=> $basePlayer->id_guild,
				'evento'				=> $basePlayer->id_evento,
				'missao'				=> $basePlayer->id_missao,
				'missao_interativa'		=> $basePlayer->missao_interativa ? 1 : 0,
				'missao_guild'			=> $basePlayer->id_missao_guild ? 1 : 0,
				'ult_atividade'			=> array('escape' => false, 'value' => 'NOW()')
			), array(
				'id_player'				=> $basePlayer->id
			));
		}

		if(!isset($_SESSION['last_upd'])) {
			$_SESSION['last_upd']	= strtotime('-1 minute');
		}

		if((date("YmdHis") - $_SESSION['last_upd']) > 30) {
			Recordset::query("UPDATE player SET ult_atividade=NOW() WHERE id=" . $basePlayer->id);
			$_SESSION['last_upd'] = date("YmdHis");
		}

		// Healing -->
			require	'include/healing_helper.php';
		// <---
 	} else {
 		$healing_was_made	= false;
 	}

	// Bloqueio do ie6.. id√©ia mais foda ja ciriada pro NG --->
		if(preg_match("/MSIE [2-6]/si", substr($_SERVER['HTTP_USER_AGENT'], 0, 50))) {
			if(isset($_GET['acao']) && $_GET['acao']) {
				$_GET['acao'] = "";
			}

			$_GET['secao'] = "ie6";
		}
	// <---

	// Reward check -->
		if($_SESSION['basePlayer']) {
			$mine_rewards	= Recordset::query('SELECT id FROM player_recompensa_log WHERE recebido=0 AND id_player=' . $basePlayer->id)->num_rows;
		} else {
			$mine_rewards	= false;
		}
	// <--

	// Lista de modulos/actions disponiveis mesmo com recompensas
	$allow_even_with_rewards	= array(
		'recompensa_log_receber',
		'dojo_lutador_lutar',
		'dojo_batalha_lutar',
		'dojo_batalha_multi_pvp',
		'dojo_batalha_multi_ping',
		'mapa',
		'mapa_batalha',
		'mapa_vila',
		'mapa_batalha_vila',
		'mapa_posicoes',
		'mapa_posicoes_vila',
		'mapa_image',
		'mapa_image_vila',
		'mapa_batalha',
		'missoes_concluida_finaliza',
		'inventario'
	);

	// Ações não precisam renderizar templates pois sempre s√£o feitas por ajax ou redirecionam para seções
	if(isset($_GET['acao']) && $_GET['acao']) {
		$is_action = true;

		if($only_map_rules) {
			$redir_script = true;
			$is_battle = false;

			if(on($_GET['acao'], 'dojo_lutador_lutar,dojo_batalha_lutar')) {
				$is_battle = true;
			}

			if(!$is_battle) {
				if($basePlayer->id_batalha) {
					$b = Recordset::query('SELECT id_tipo FROM batalha WHERE id=' . $basePlayer->id)->row_array();

					if(on($b['id_tipo'], array(1, 3, 7, 8))) {
						redirect_to('dojo_batalha_lutador');
					} else {
						redirect_to('dojo_batalha_pvp');
					}
				}

				if($basePlayer->dentro_vila || $basePlayer->id_vila_atual) {
					if($_GET['acao'] != 'mapa_posicoes_vila') {
						if($basePlayer->dentro_vila) {
							redirect_to('personagem_status', NULL, array('map_redir' => 1));
						} else {
							redirect_to('mapa_vila');
						}
					}
				}
			}
		} else {
			require("include/regras.php");
			regras_validate();
		}

		if($regras_valido) {
			if($mine_rewards && !in_array($_GET['acao'], $allow_even_with_rewards)) {
				$redir_script	= true;
				redirect_to('recompensa_log_receber');
				die();
			}

			require("action/" . $_GET['acao'] . ".php");
		} else {
			$redir_script	= true;
			$denied_url		= $_GET['acao'];

			if(!$healing_was_made) {
				redirect_to("negado", NULL, array('byrule' => 'action', 'source' => $denied_url));
			} else {
				redirect_to("perosnagem_status");
			}
		}

		if(isset($_SESSION['universal']) && $_SESSION['universal'] && !isset($json)) {
			echo "// <script>\n";
			echo "// SELECTS: " . (Recordset::$count_queries + Recordset::$count_cache_hits + Recordset::$count_cache_miss) .
				 " (HITS: " . Recordset::$count_cache_hits . "/" . (Recordset::$count_cache_hits + Recordset::$count_cache_miss) . ")\n" .
				 "// INSERTS: " . Recordset::$count_inserts_w_dup . "\n// UPDATES: " . Recordset::$count_updates . "\n" .
				 "// DELETES: " . Recordset::$count_deletes . "\n// ------- MEMORY --------\n" .
				 "// PHP_MEM_PEAK: " . (memory_get_peak_usage() / 1024) . "K\n" .
				 "// PHP_MEM: " . (memory_get_usage() / 1024) . "K [EMALLOC]\n" .
				 "// PHP_MEM: " . (memory_get_usage(true) / 1024) . "K";

				if(isset($_GET['dump'])) {
					echo "/*\n";
					print_r(Recordset::$sqls);
					echo "*/\n";
				}

			echo "\n// </script>";
		}

		die();
	}

	if(isset($_GET['secao'])) {
		if(!is_file("module/" . $_GET['secao'] . ".php")) {
			redirect_to("negado", NULL, array('byrule' => 'sectionfile'));
			die();
		}
	}

	if(!isset($_GET['secao'])) {
		$_GET['secao'] = "home";
	} else {
		$_GET['secao'] = preg_replace("/[^\w_]/si", "", $_GET['secao']);
	}

	$am_i_on_battle	= $basePlayer && ($basePlayer->id_batalha || $basePlayer->id_batalha_multi || $basePlayer->id_batalha_multi_pvp);

	if($am_i_on_battle && !in_array($_GET['secao'], ['dojo_batalha_pvp', 'dojo_batalha_lutador', 'dojo_batalha_multi', 'dojo_batalha_multi_pvp'])) {
		$am_i_on_battle	= false;
	}

	$user_is_banned			= false;
	$is_permanent_ban		= false;
	$ban_will_end_at		= 0;
	$denied_reason_is_ban	= [];

	if ($_SESSION['logado']) {
		$ban_records		= Recordset::query('
			SELECT
				a.*

			FROM
				multi_admin_bans a JOIN multi_admin_occurrences b ON b.id=a.occurrence_id

			WHERE
				b.user_id=' . $_SESSION['usuario']['id']
		);

		if ($ban_records->num_rows) {
			foreach ($ban_records->result_array() as $ban) {
				if($ban['ban_type'] == 4) {
					$user_is_banned		= true;
					$is_permanent_ban	= true;

					break;
				} else {
					if (!$ban['cancelled']) {
						$now	= date('YmdHis');
						$future	= date('YmdHis', strtotime('+' . $ban['ban_type'] . ' day', strtotime($ban['created_at'])));

						if($future > $now) {
							$user_is_banned		= true;
							$ban_will_end_at	= strtotime('+' . $ban['ban_type'] . ' day', strtotime($ban['created_at']));

							break;
						}
					}
				}
			}
		}
	}

	require("include/regras.php");
	require("template/topo.php");

	// arranca o esquerda nas batalhas
	if(!$am_i_on_battle) {
		require("template/esquerda.php");
	}

	regras_validate();

	if(isset($_GET['secao']) && $_GET['secao'] == 'cache' && $_SESSION['universal']) {
		$regras_valido	= true;
	}

	if($regras_valido) {
		if($mine_rewards && !in_array($_GET['secao'], $allow_even_with_rewards)) {
			$_GET['secao']	= 'recompensa_log_receber';
		}

		require("module/" . $_GET['secao'] . ".php");
	} else {
		$redir_script	= true;
		$denied_url		= $_GET['secao'];

		if(!$healing_was_made) {
			redirect_to("negado", NULL, array('byrule' => 'section', 'source' => $denied_url));
		} else {
			redirect_to("personagem_status");
		}
	}

	require("template/rodape.php");
?>
<?php if(isset($_SESSION['universal']) && $_SESSION['universal'] && isset($_GET['debug'])): ?>
<div style="clear: both"></div>
<table border="1">
	<tr>
		<th colspan="6" align="left">SQL Roundup</th>
	</tr>
	<tr>
		<th>SELECTS made without cache:</th>
		<td><?php echo Recordset::$count_queries ?></td>
	</tr>
	<tr>
		<th>SELECTS that missed cache:</th>
		<td><?php echo Recordset::$count_cache_miss ?></td>
	</tr>
	<tr>
		<th>SELECTS that hit the cache:</th>
		<td><?php echo Recordset::$count_cache_hits ?></td>
	</tr>
	<tr>
		<th>SELECTS queries made:</th>
		<td><?php echo Recordset::$count_queries + Recordset::$count_cache_hits ?></td>
	</tr>
	<tr>
		<th>Inserts made:</th>
		<td><?php echo Recordset::$count_inserts ?> (<?php echo Recordset::$count_inserts_w_dup ?> With ON DUPLICATE)</td>
	</tr>
	<tr>
		<th>Updates made:</th>
		<td><?php echo Recordset::$count_updates ?></td>
	</tr>
	<tr>
		<th>Deletes made:</th>
		<td><?php echo Recordset::$count_deletes ?></td>
	</tr>
</table>
// PHP_MEM_PEAK -> <?php echo memory_get_peak_usage() / 1024 ?>K <br />
// PHP_MEM [EMALLOC] -> <?php echo memory_get_usage() / 1024 ?>K <br />
// PHP_MEM -> <?php echo memory_get_usage(true) / 1024 ?>K <br />
/*
	<?php print_r(getrusage()) ?>
*/
<?php
	if(isset($_GET['dump'])) {
		echo '<pre>';
		print_r(Recordset::$sqls);
		echo '</pre>';
	}
?>
<?php endif; ?>
