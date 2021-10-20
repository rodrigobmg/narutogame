<div class="titulo-secao">
  <p>Cache</p>
</div>
<?php if (!in_array(Recordset::$cache_mode, [RECORDSET_CACHE_SHARED, RECORDSET_CACHE_FILE])) : ?>
  <p>Modo de cache atual não é compatível ou está desligado</p>
  <p>Os métodos abaixo são os únicos suportados</p>
  <blockquote>
    RECORDSET_CACHE_SHARED
    RECORDSET_CACHE_FILE
  </blockquote>
<?php else : ?>
  <p>Modo de cache atual: <?php echo Recordset::$cache_mode === RECORDSET_CACHE_SHARED ? "RECORDSET_CACHE_SHARED" : "RECORDSET_CACHE_FILE" ?></p>
  <br />
  <?php
    if (Recordset::$cache_mode === RECORDSET_CACHE_SHARED) {
      $file_expr  = '/dev/shm/RECSET_' . Recordset::$key_prefix . '*.sqlcache';
    } else {
      $file_expr  = ROOT . '/cache/sql/*.sqlcache';
    }

    if (isset($_GET['clear'])) {
      $files  = glob($file_expr);

      foreach ($files as $file) {
        unlink($file);
      }

      $redir_script  = true;

      redirect_to('cache');
      die();
    }

    $files        = glob($file_expr);
    $cache_size   = 0;
    $file_count   = 0;

    foreach ($files as $file) {
      $cache_size  += filesize($file);
      $file_count++;
    }
  ?>
  <table border="1" width="100%">
    <tr>
      <th>Tamanho de dados</th>
      <td>~<?php echo round($cache_size / (1024 * 1000), 2) ?>Mb</td>
    </tr>
    <tr>
      <th>Total de arquivos cacheados</th>
      <td><?php echo $file_count ?></td>
    </tr>
  </table>
  <br />
  <a href="?secao=cache&clear=1" class="button">Limpar cache</a>
<?php endif ?>
