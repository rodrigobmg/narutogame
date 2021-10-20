<?php
  $option	= isset($_GET['o']) ? (int)$_GET['o'] : '';

  switch($option) {
    case 1:
      $v = decode($_POST['v']);

      if($v != 0) {
        if(!Recordset::query("SELECT id FROM player_titulo WHERE id_usuario=" . $basePlayer->id_usuario . " AND id=" . addslashes((int)$v))->num_rows) {
          redirect_to("negado");
        }
      }

      Recordset::query("UPDATE player SET id_titulo=" . ($v ? $v : 0) . " WHERE id=" . $basePlayer->id);

      break;

    default:
      redirect_to("negado");	
  }
