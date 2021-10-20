<?php
	class SharedStore {
		static	$key_prefix = "";

		private static function get_store_path() {
			return ROOT . '/cache/shared-store';
		}
		
		static function S($key, $v = NULL) {
			if(defined('IS_CRON') && !defined('ALLOW_SHARED_STORE')) {
				return;
			}
		
			$file	= SharedStore::get_store_path() . SharedStore::$key_prefix . md5($key);

			file_put_contents($file, serialize($v));
			@chmod($file, 0777);
			//@chown($file, 'nginx');
			//@chgrp($file, 'nginx');
		}
		
		static function G($key, $default = NULL) {
			$mem = @file_get_contents(SharedStore::get_store_path() . SharedStore::$key_prefix . md5($key));
			
			return $mem != NULL ? unserialize($mem) : $default;
		}
		
		static function D($key, $t = 10) {
			@unlink(SharedStore::get_store_path() . SharedStore::$key_prefix . md5($key));
		}
	}
