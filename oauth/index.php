<?php
	session_start();
	
	require('../include/db.php');
	require('../class/Recordset.php');
    require('lib/phpoauth/http.php');
	require('lib/phpoauth/phpoauth.php');

	
    $client 	= new oauth_client_class;
	$user		= false;

	if (!isset($remote_ip)) {
		$remote_ip	= @$_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	}

	if(!isset($_SESSION['logado'])) {
		$_SESSION['logado']			= false;
		$_SESSION['basePlayer']		= NULL;
		$_SESSION['usuario']		= array();
		$_SESSION['universal']		= false;
		$_SESSION['last_upd']		= NULL;
		$_SESSION['_MOD_BASE']		= '';			
	}

	if(isset($_GET['provider'])) {
		switch($_GET['provider']) {
			case 'facebook':
				$client->server			= 'Facebook';
				$client->redirect_uri	= 'http://narutogame.com.br/oauth/?callback&provider=facebook';
			
				$client->client_id		= '278794155478934';
				$client->client_secret	= '1f5ffa715e6bd42839fe2378b1154b4c';
				$client->scope			= 'email';
			
				if(($success = $client->Initialize())) {
					if(($success = $client->Process())) {
						if(strlen($client->access_token)) {
							$success = $client->CallAPI(
								'https://graph.facebook.com/me', 
								'GET', array(), array('FailOnAccessError'=>true), $user);
						}
					}
					
					$success = $client->Finalize($success);
				}
			
				break;
			
			default:
				die('Provedor de autenticação inválido');
			
				break;
		}
		
		if($success && $user) {
			if($_SESSION['logado']) {
				$db_user	= Recordset::query('SELECT id FROM global.user WHERE fb_uid=' . addslashes($user->id));
				
				if(!$db_user->num_rows) {
					Recordset::update('global.user', array(
						'last_activity'		=> array('escape' => false, 'value' => 'NOW()'),
						'last_login'		=> array('escape' => false, 'value' => 'NOW()'),
						'ip'				=> array('escape' => false, 'value' => 'INET_ATON(\'' . $remote_ip . '\')'),
						'has_fb'			=> 1,
						'fb_uid'			=> $user->id
					), array(
						'id'				=> $_SESSION['usuario']['id']
					));
					
					header('Location: /?secao=usuario_dados&linked=facebook');
				} else {
					$_SESSION['_js_global_msg']		= 'jalert("Não foi possível vincular sua conta do facebook com a conta atual pois ela já está vinculada a outra conta!");' . 
													  'FB.logout(function (e) { });';
					
					header('Location: /?secao=usuario_dados&failed');
					die();
				}
			} else {
				$db_user	= Recordset::query('SELECT * FROM global.user WHERE fb_uid=' . $user->id);
				
				if(!$db_user->num_rows) { // Criar conta
					$id = Recordset::insert('global.user', array(
						'name'				=> $user->name,
						'email'				=> $user->email,
						'sex'				=> $user->gender == 'male' ? 0 : 1,
						'has_fb'			=> 1,
						'last_activity'		=> array('escape' => false, 'value' => 'NOW()'),
						'last_login'		=> array('escape' => false, 'value' => 'NOW()'),
						'ip'				=> array('escape' => false, 'value' => 'INET_ATON(\'' . $remote_ip . '\')'),
						'fb_uid'			=> $user->id,
						'active'			=> 1,
						'sid'				=> session_id()
					));
	
					// somente ng
					if(!Recordset::query('SELECT sid FROM global.user_game_sid WHERE id_user=' . $id . ' AND id_game=1')->num_rows) {
						Recordset::insert('global.user_game_sid', array(
							'sid'		=> session_id(),
							'id_user'	=> $id,
							'id_game'	=> 1
						));
					}
					
					$_SESSION['usuario']	= array(
						'id'	=> $id,
						'nome'	=> $user->name,
						'email'	=> $user->email,
						'vip'	=> 0,
						'lang'	=> $_SESSION['lang']
					);					
				} else { // Editar conta
					$db_user	= $db_user->row();
				
					Recordset::update('global.user', array(
						'sid'				=> session_id()
					), array(
						'id'				=> $db_user->id
					));
					
					// somente ng
					if(!Recordset::query('SELECT * FROM global.user_game_sid WHERE id_user=' . $db_user->id . ' AND id_game=1')->num_rows) {
						Recordset::insert('global.user_game_sid', array(
							'sid'		=> session_id(),
							'id_user'	=> $db_user->id,
							'id_game'	=> 1
						));
					} else {
						Recordset::update('global.user_game_sid', array(
							'sid'		=> session_id()
						), array(
							'id_user'	=> $db_user->id,
							'id_game'	=> 1
						));					
					}
					
					$_SESSION['usuario']	= array(
						'id'		=> $db_user->id,
						'nome'		=> $db_user->name,
						'email'		=> $db_user->email,
						'vip'		=> $db_user->vip,
						'lang'		=> $db_user->lang,
						'msg_vip'	=> $db_user->msg_vip,
						'sound'		=> $db_user->sound
					);					
				}

				$_SESSION['universal']		= false;
				$_SESSION['basePlayer']		= NULL;
				$_SESSION['last_upd']		= NULL;
				$_SESSION['_MOD_BASE']		= '';
				$_SESSION['logado']	= true;
				$_SESSION['key']	= md5(rand(0, 512384) . rand(0, 512384));

				if(!Recordset::query('SELECT id FROM player WHERE id_usuario=' . $_SESSION['usuario']['id'])->num_rows) {
					header('Location: /?secao=personagem_criar');
					die();
				} else {
					header('Location: /?secao=personagem_selecionar');
					die();					
				}
			}
		} else {
			if(isset($_GET['callback'])) {
				header('Location: /?oauth_error');
				die();
			}
		}
	} else {
		die('Operação inválida');
	}