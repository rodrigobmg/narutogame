<?php
	date_default_timezone_set('America/Sao_Paulo');	
	define('IS_SENDER', true);

	require('../include/db.php');
	require('../class/Recordset.php');
	require('baseintencoder.php');
	require('action_mailer.php');

	class Template extends ActionMailer { 
		/*public $host		= '198.199.122.118';
		public $port		= 25;
		public $username	= 'naruto@mail.ultimateninja.com.br';
		public $password	= 'C4bg36?!';
		public $from		= 'naruto@mail.ultimateninja.com.br';//'contato@narutogame.com.br';
		public $from_name	= 'Naruto Game';*/

		
		public $host		= 'mail.narutogame.com.br';
		public $port		= 587;
		public $username	= 'contato@narutogame.com.br';
		public $password	= 'Carlos@2021';
		public $from		= 'contato@narutogame.com.br';
		public $from_name	= 'Naruto Game';
		

		function send($subjet, $to, $content) {
			$this->deliver('', $subjet, $to, $content);
		}
	}
	

	$email			= Recordset::query('SELECT id, email FROM global.user WHERE id=' . $_SERVER['argv'][1])->row_array();

	$_GET['email']	= $email['email'];
	$_GET['code']	= BaseIntEncoder::encode(1000000 + $email['id']);
	
	Recordset::insert('promocao_usuario', array(
		'usuario_id'	=> $email['id'],
		'promocao_id'	=> 5
	));
	
	ob_start();
	require('inativosv3/index.php');
	$data		= ob_get_clean();
	
	$tempalte	= new Template();
	$tempalte->send("Promoção para usuários inativos", $email['email'], $data);
