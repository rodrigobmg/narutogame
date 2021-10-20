<?php
	define("STORAGE_SESSION", 0);
	define("STORAGE_OBJECT", 1);

	class SessionStorage {
		private $_storageName = NULL;
		private $_storageMethod = NULL;
		private $_store = array();
		
		function __construct($sName = NULL, $storageMethod = STORAGE_SESSION) {
			if($storageMethod == STORAGE_SESSION) {
				if(!headers_sent()) {
					@session_start();
				}
			}
			
			$this->_storageMethod = $storageMethod;
			
			if($sName != NULL) {
				$this->setStorageName($sName);
			}
		}
		
		function setStorageName($sName) {
			$this->_storageName = $sName;

			if($this->_storageMethod == STORAGE_SESSION) {
				if(!is_array($_SESSION)) {
					$_SESSION = array();
				}
				
				if(!isset($_SESSION[$sName])) {
					$_SESSION[$sName] = array();
				}
			}
		}
		
		function clearStorage() {
			if($this->_storageMethod == STORAGE_SESSION) {
				$_SESSION[$this->_storageName] = array();	
			}
		}
		
		function &__get($v) {
			if($this->_storageMethod == STORAGE_SESSION) {
				return $_SESSION[$this->_storageName][$v];
			} else {
				return $this->_store[$v];
			}
		}
		
		function __set($v, $m) {
			if($this->_storageMethod == STORAGE_SESSION) {
				$_SESSION[$this->_storageName][$v] = $m;
			} else {
				$this->_store[$v] = $m;
			}
		}
	}
?>