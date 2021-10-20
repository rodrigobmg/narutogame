<?php
	if(!defined('RECORDSET_TTL')) {
		define('RECORDSET_TTL', 600);
	}

	if(!defined('RECORDSET_CACHE_NONE')) {
		define('RECORDSET_CACHE_NONE', 0);
	}

	if(!defined('RECORDSET_CACHE_APC')) {
		define('RECORDSET_CACHE_APC', 1);
	}

	if(!defined('RECORDSET_CACHE_SHARED')) {
		define('RECORDSET_CACHE_SHARED', 2);
	}

	if (!defined('RECORDSET_CACHE_FILE')) {
		define('RECORDSET_CACHE_FILE', 3);
	}

	class Recordset {
		public int $num_rows = 0;
		public int $affected_rows = 0;
		public string $sql = '';
		public static $key_prefix	= '';
		private	$recordset = NULL;
		private static PDO $pdo;
		
		public static int $count_cache_hits = 0;
		public static int $count_cache_miss = 0;
		public static int $count_queries = 0;
		public static int $count_inserts = 0;
		public static int $count_updates = 0;
		public static int $count_deletes = 0;
		public static int $count_inserts_w_dup = 0;
		public static int $cache_mode = RECORDSET_CACHE_NONE;
		
		public static $sqls = array();
		
		function __construct($sql = null, $cache = false, $ttl = RECORDSET_TTL) {
			if(!isset(Recordset::$sqls[md5($sql)])) {
				Recordset::$sqls[md5($sql)] = array(
					'sql' => $sql,
					'count' => 1
				);
			} else {
				Recordset::$sqls[md5($sql)]['count']++;
			}

			if(!$cache) {
				$this->__do_query($sql);			
			} else {
				$this->__do_cached_query($sql, $ttl);			
			}
		}
		
		function repeat() {
			$this->recordset	= [];
			$this->num_rows		= 0;
			
			$this->__do_query($this->sql);
			
			return $this;
		}

		private function __do_query($sql) {
			if(!$sql) {
				return $this;
			}
			
			$q = Recordset::$pdo->query($sql);
			
			if($q->errorCode() !== '00000') {
				throw new Exception('SqlSelectException(' . $q->errorCode() . '): ' . $sql);
			}
			
			$this->sql = $sql;
			$this->num_rows = $q->rowCount();
			$this->affected_rows = $q->rowCount();

			if($this->num_rows) {
				while($r = $q->fetch(PDO::FETCH_ASSOC)) {
					$this->recordset[] = $r;
				}
			}

			if($this->recordset) {
				reset($this->recordset);
			}			
			
			Recordset::$count_queries++;
			
			return $this;		
		}
		
		private function __do_cached_query($sql, $ttl = RECORDSET_TTL) {
			if(!$sql) {
				return $this;
			}
			
			$do_query	= true;
			$store		= false;
			$key		= 'RECSET_' . Recordset::$key_prefix . md5($sql);
			
			if(Recordset::$cache_mode == RECORDSET_CACHE_APC) {
				if(apc_exists($key)) {
					$cache			= unserialize(apc_fetch($key));
					$do_query		= false;
					
					if(isset($cache['rows'])) {
						$data			= $cache['data'];
						$this->num_rows	= $cache['rows'];
					} else {
						$data			= $cache;				
						$this->num_rows	= sizeof($cache);
					}
				} else {
					$store	= true;
				}
			} elseif(Recordset::$cache_mode == RECORDSET_CACHE_FILE) {
				$cache_file	= ROOT . '/cache/sql/' . $key . '.sqlcache';
				$cache_data	= @file_get_contents($cache_file);

				$this->cache_file	= $cache_file;
			
				if($cache_data !== false) {
					$cache			= unserialize($cache_data);
					$do_query		= false;

					$data			= $cache['data'];
					$this->num_rows	= $cache['rows'];
				} else {
					$store	= true;
				}
			} elseif(Recordset::$cache_mode == RECORDSET_CACHE_SHARED) {
				$cache_data = SharedStore::G($key);

				if (is_null($cache_data)) {
					$store = true;
				} else {
					$cache = unserialize($cache_data);
					$do_query = false;

					$data = $cache['data'];
					$this->num_rows = $cache['rows'];
				}
			}

			if($do_query) {
				$this->__do_query($sql);

				Recordset::$count_cache_miss++;
			} else {
				if($this->num_rows) {
					if(!is_array($data)) {
						$this->__do_query($sql);
						
						$store	= true;
					} else {					
						foreach($data as $r) {
							$this->recordset[] = $r;
						}
					}
				}
				
				unset($data, $cache);

				Recordset::$count_cache_hits++;
			}
			
			$this->sql = $sql;
			
			if($this->recordset) {
				reset($this->recordset);
			}
			
			if($store) {
				$data_store = array('data' => $this->recordset, 'rows' => $this->num_rows, 'sql' => $sql);
				
				if(Recordset::$cache_mode === RECORDSET_CACHE_APC) {
					apc_store($key, serialize($data_store), $ttl);
				} elseif (Recordset::$cache_mode === RECORDSET_CACHE_FILE) {
					file_put_contents($cache_file, serialize($data_store));
				} elseif (Recordset::$cache_mode === RECORDSET_CACHE_SHARED) {
					SharedStore::S($key, $data_store);
				}
			}
			
			return $this;
		}
		
		function row_array() {
			if($this->recordset) {
				$current = current($this->recordset);
				next($this->recordset);
			} else {
				$current = [];
			}

			return $current;
		}
		
		function result_array() {
			return $this->recordset ? $this->recordset : array();
		}
		
    function row() {
      $current = current($this->recordset);
      next($current);

      if (!$current) {
        return null;
      }

      $ret = new stdClass();

      foreach($current as $k => $v) {
        $ret->$k = $v;
      }
      
      return $ret;
    }

    function next_row() {
      next($this->recordset);
    }

		function previous_row() {
			prev($this->recordset);
		}
		
		function bof() {
			return key($this->recordset) == 0;	
		}
		
		function eof() {
			return key($this->recordset) == $this->num_rows - 1;
		}
		
		function set_records($array) {
			$this->recordset = $array;
			$this->num_rows = sizeof($array);
		}

		function insert_id() {
			return Recordset::$pdo->lastInsertId();
		}

		public static function connect() {
			Recordset::$pdo = new PDO("mysql:host=localhost;dbname=narutoga_prod", "narutoga_prod", "bb7urhj;(2rB");
			Recordset::$pdo->query("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
			Recordset::$pdo->query("SET SESSION time_zone='America/Sao_Paulo';");

			if (!is_dir(ROOT . '/cache/sql')) {
				mkdir(ROOT . '/cache/sql');
			}
		}
		
		static function insert($table, $fields, $duplicate = NULL) {
			$dp		= array();
			$keys	= array();
			$sets	= Recordset::_parse_set($fields, true);
			
			foreach($fields as $k => $v) {
				$keys[] = '`' . $k . '`';
			}
			
			if($duplicate) {
				$dp = Recordset::_parse_where($duplicate);
			}
			
			$sql = 'INSERT INTO ' . $table . '(' . join(',', $keys) . ') VALUES(' . join(',', $sets) . ')' . ($duplicate ? ' ON DUPLICATE KEY UPDATE ' . join(',', $dp) : '');

			$q = Recordset::$pdo->query($sql);
			
			if($q->errorCode() !== '00000') {
				throw new Exception('Insert query error\n\n<pre>' . $sql . '\n\n' . $q->errorCode() . '</pre>');
			}
			
			Recordset::$count_inserts++;
			
			if($duplicate) {
				Recordset::$count_inserts_w_dup++;
			}

			unset($dp);
			unset($keys);
			unset($sets);
			
			return Recordset::$pdo->lastInsertId();
		}
		
		static function update($table, $fields, $where = NULL) {
			$wh		= array();			
			$sets	= Recordset::_parse_set($fields);
			
			if($where) {
				$wh = Recordset::_parse_where($where);
			}
			
			$sql = 'UPDATE ' . $table . ' SET ' . join(',', $sets) . ($where ? ' WHERE ' . join(' AND ', $wh) : '');
			
			Recordset::$sqls[md5($sql)] = array(
				'sql' => $sql
			);			

			$q = Recordset::$pdo->query($sql);

			if($q->errorCode() !== '00000') {
				throw new Exception('Update query error\n\n<pre>' . $sql . '\n\n' . $q->errorCode() . '</pre>');
			}

			Recordset::$count_updates++;			
		}
		
		static function delete($table, $where) {
			$wh = array();
			
			if($where) {
				foreach($where as $k => $v) {
					if(is_array($v)) {
						if($v['escape'] !== false) {
							$v = is_null($v['value']) ? 'NULL' : '\'' . addslashes($v['value']) . '\'';						
						} else {
							$v = $v['value'];
						}
						
						
						$wh[] = '`' . $k . '`=' . $v;
					} else {
						$v		= is_null($v) ? 'NULL' : '\'' . addslashes($v) . '\'';
						$wh[]	= '`' . $k . '`=' . $v;					
					}
				}
			}
			
			$sql = 'DELETE FROM ' . $table . ($where ? ' WHERE ' . join(' AND ', $wh) : '');

			$q = Recordset::$pdo->query($sql);

			if($q->errorCode() !== '00000') {
				throw new Exception('Update query error\n\n<pre>' . $sql . '</pre>');
			}
			
			Recordset::$count_deletes++;
		}
		
		static function fromArray($array) {
			$r = new Recordset();
			$r->set_records($array);
			
			return $r;
		}
		
		static function query($sql, $cache = false, $ttl = RECORDSET_TTL) {
			return new Recordset($sql, $cache, $ttl);
		}

		public static function static_query($sql) {
			$val	= StaticCache::get(md5($sql));
			
			if(!$val) {
				$val	= Recordset::query($sql, false, RECORDSET_TTL);
				
				StaticCache::store(md5($sql), $val);
			}
			
			return $val;
		}
		
		static function shared_query($sql) {
			$key	= Recordset::$key_prefix . '_' . hash("crc32b", $sql);
			$file	= ROOT . '/cache/sql' . $key;
			
			if(file_exists($file)) {
				return Recordset::fromArray(gzunserialize(file_get_contents($file)));
			} else {
				$result	= Recordset::query($sql);
				
				file_put_contents($file, gzserialize($result->result_array()));
				
				return $result;
			}
		}
		
		private static function _parse_where($where) {
			$wh = array();

			foreach($where as $k => $v) {
				$raw_v = $v;
				
				if(is_array($v)) {
					if($v['escape'] !== false) {
						$v = is_null($v['value']) ? 'NULL' : '\'' . addslashes($v['value']) . '\'';						
					} else {
						$v = $v['value'];
					}
					
					if(isset($raw_v['mode'])) {
						switch($raw_v['mode']) {
							case 'in':
								$wh[] = '`' . $k . '` IN(' . $raw_v['value'] . ')';
							
								break;
							
							case 'not_in':
								$wh[] = '`' . $k . '` NOT IN(' . $raw_v['value'] . ')';
							
								break;
							
							case 'not':
								$wh[] = '`' . $k . '` != ' . $raw_v['value'];
							
								break;

							case 'lt':
								$wh[] = '`' . $k . '` < ' . $raw_v['value'];

								break;

							case 'lte':
								$wh[] = '`' . $k . '` <= ' . $raw_v['value'];

								break;

							case 'gt':
								$wh[] = '`' . $k . '` > ' . $raw_v['value'];

								break;

							case 'gte':
								$wh[] = '`' . $k . '` >= ' . $raw_v['value'];

								break;
						}
					} else {
						$wh[] = '`' . $k . '`=' . $v;
					}
				} else {
					$v		= is_null($v) ? 'NULL' : '\'' . addslashes($v) . '\'';
					$wh[]	= '`' . $k . '`=' . $v;					
				}
			}
			
			return $wh;
		}
		
		private static function _parse_set($fields, $insert = false) {
			$sets	= array();
		
			foreach($fields as $k => $v) {
				if(is_array($v)) {
					if($v['escape'] !== false) {
						$v = is_null($v['value']) ? 'NULL' : '\'' . addslashes($v['value']) . '\'';						
					} else {
						$v = $v['value'];
					}					
				} else {
					$v = is_null($v) ? 'NULL' : '\'' . addslashes($v) . '\'';
				}
				
				if($insert) {
					$sets[] = $v;
				} else {
					$sets[]	= '`' . $k . '`=' . $v;
				}
			}
			
			return $sets;
		}
	}
