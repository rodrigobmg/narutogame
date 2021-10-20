<?php
	require 'ci_email.php';

	class ActionMailer {
		private	$factory	= null;

		public	$host		= '';
		public	$port		= 25;
		public	$username	= '';
		public	$password	= '';
		public	$debug		= '';
		public	$from		= '';
		public	$from_name	= '';

		function deliver($action, $subject, $to, $message) {
			$content = 'Seja bem vindo!';

			$factory		= new CI_Email(array(
				'protocol'	=> 'smtp',
				'charset'	=> 'utf-8',
				'smtp_user'	=> $this->username,
				'smtp_pass'	=> $this->password,
				'smtp_port'	=> $this->port,
				'smtp_host'	=> $this->host,
				'mailtype'	=> 'html'
			));

			$factory->from($this->from, $this->from_name);
			$factory->to($to);

			$factory->subject($subject);
			$factory->message($message);

			$return			= $factory->send();
			$this->debug	= $factory->print_debugger();

			return $return;
		}

		static function dispatch($method, $params) {
			$class		= get_called_class();
			$callable	= new ReflectionMethod($class, $method);

			$callable->invokeArgs(new $class(), $params);
		}
	}