<?php
	if (!session_id()) {
		session_start();
	}

	//require 'oauth/facebook/facebook.php';
	$facebook_user = false;

	if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
		$_SERVER['REMOTE_ADDR']	= $_SERVER['HTTP_CF_CONNECTING_IP'];
	}

	if(!defined('FB_APP_ID')) {
		define('FB_APP_ID', 278794155478934);
	}

	$facebook_instance = new \Facebook\Facebook([
	 'app_id'     => FB_APP_ID,
	 'app_secret'    => '1f5ffa715e6bd42839fe2378b1154b4c',
	 'default_graph_version'  => 'v2.8',
	 //'default_access_token' => '{access-token}', // optional
	]);

	$helper = $facebook_instance->getRedirectLoginHelper();

	if (isset($_GET['state'])) {
		$helper->getPersistentDataHandler()->set('state', $_GET['state']);
	}
	
	$permissions = ['public_profile','email'];
	$loginUrl = $helper->getLoginUrl('https://narutogame.com.br', $permissions);

	//$facebook_user	= $facebook_instance->getUser();

	$accessToken = $helper->getAccessToken();
	if ($accessToken != FALSE && isset($accessToken)) {
	 $_SESSION['facebook_access_token'] = (string) $accessToken;

	 $response = $facebook_instance->get('/me?fields=id,name,email,picture,gender', $accessToken);
	 $facebook_user = $response->getGraphUser();
	}

	if (!isset($remote_ip)) {
		$remote_ip		= @$_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	}

	if($facebook_user && isset($_GET['state']) && isset($_GET['code'])) { // Acabou de vir do oauth do facebook
		$profile = $facebook_user;
		$db_user	= Recordset::query('SELECT id, email, sound, msg_vip, name, vip, lang,layout FROM global.user WHERE fb_uid=' . addslashes($profile['id']));

		if($_SESSION['logado']) { // Linkar conta
			if(!$db_user->num_rows) { // Nâo achou o usuario do face no banco ? blza
				Recordset::update('global.user', array(
					'last_activity'		=> array('escape' => false, 'value' => 'NOW()'),
					'last_login'		=> array('escape' => false, 'value' => 'NOW()'),
					'ip'				=> array('escape' => false, 'value' => 'INET_ATON(\'' . $remote_ip . '\')'),
					'has_fb'			=> 1,
					'fb_uid'			=> $profile['id']
				), array(
					'id'				=> $_SESSION['usuario']['id']
				));

				header('Location: /?secao=usuario_dados&linked=facebook');
			} else { // Já achou, se fudeu, não da pra "juntar"
				$_SESSION['_js_global_msg']		= 'jalert("Não foi possível vincular sua conta do facebook com a conta atual pois ela já está vinculada a outra conta!");' .
												  'FB.logout(function (e) { });';

				header('Location: /?secao=usuario_dados&failed');
				die();
			}
		} else {
			if(!$db_user->num_rows) { // Não achou o id no banco? cadastra
				$id = Recordset::insert('global.user', array(
					'name'				=> $profile['name'],
					'email'				=> $profile['email'],
					'sex'				=> $profile['gender'] == 'male' ? 0 : 1,
					'has_fb'			=> 1,
					'last_activity'		=> array('escape' => false, 'value' => 'NOW()'),
					'last_login'		=> array('escape' => false, 'value' => 'NOW()'),
					'ip'				=> array('escape' => false, 'value' => 'INET_ATON(\'' . $remote_ip . '\')'),
					'fb_uid'			=> $profile['id'],
					'layout'			=> 'r10',
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
					'id'		=> $id,
					'nome'		=> $profile['name'],
					'email'		=> $profile['email'],
					'vip'		=> 0,
					'lang'		=> $_SESSION['lang']
				);
			} else { // Logar conta
				$db_user	= $db_user->row();
				$user_data	= [
					'sid'	=> session_id(),
				];

				if(!$db_user->email) {
					$user_data['email']	= isset($profile['email']) ? $profile['email'] : $user_data['email'];
				}

				Recordset::update('global.user', $user_data, [
					'id'	=> $db_user->id
				]);

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
					'layout'		=> $db_user->layout,
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
	}
