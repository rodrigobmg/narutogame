<?php
  session_start();
  ini_set('display_errors', 'on');
  error_reporting(E_ALL & ~E_STRICT);
  date_default_timezone_set('America/Sao_Paulo');

  echo '[CRON] GC ENABLED:' . gc_enabled() . "\n";

  if (!isset($_SERVER['SERVER_ADDR'])) {
    $_SERVER['SERVER_ADDR']	= '127.0.0.1';
  }

  define('IS_CRON', true);

  if(!defined('ROOT')) {
    define('ROOT', realpath(dirname(__FILE__) . '/../'));	
  }

  $use_master = true;

  if(isset($_GET['f']) && $_GET['f']) {
    require('../traits/AttributeCalculationTrait.php');
    require('../traits/ModifiersTrait.php');

    require("../include/generic.php");
    require("../class/Player.php");
    require("../class/NPC.php");
    require("../class/Item.php");
    require("../class/Recordset.php");
    require("../class/SharedStore.php");
    require("../class/Fight.php");
    require("../include/yaml.php");
  } else {
    require(ROOT . '/traits/AttributeCalculationTrait.php');
    require(ROOT . '/traits/ModifiersTrait.php');

    require(ROOT . '/include/generic.php');
    require(ROOT . '/class/Player.php');
    require(ROOT . '/class/NPC.php');
    require(ROOT . '/class/Item.php');
    require(ROOT . '/class/Recordset.php');
    require(ROOT . '/class/SharedStore.php');
    require(ROOT . '/class/Fight.php');
    require(ROOT . '/include/yaml.php');
  }

  SharedStore::$key_prefix	= 'NG.r11'; // nunca tirar o NG.
  Recordset::$key_prefix		= 'NG.r11';

  Recordset::$cache_mode		= RECORDSET_CACHE_FILE;

  Recordset::connect();

  register_shutdown_function(function () {
    echo memory_get_usage(true) / 1024;
  });

  function check_for_lock($full_file_name) {
    $file = basename($full_file_name);
    $lock_file = ROOT . "/cache/cron_lock_{$file}.lock";
    $is_running = false;

    if (file_exists($lock_file)) {
      $pid = file_get_contents($lock_file);

      if (file_exists("/proc/{$pid}")) {
        $is_running = true;
      } else {
        echo "[CRON:LOCK] Lock file exists, but process isn't found(process died?)\n";
      }
    }

    if ($is_running) {
      echo "[CRON:LOCK] Process is running {$pid}\n";
      exit(0);
    }

    echo "[CRON:LOCK] Created lock file\n";

    file_put_contents($lock_file, getmypid(), );
    register_shutdown_function('unlink', $lock_file);
  }
