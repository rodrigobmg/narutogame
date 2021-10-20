<?php
  if(!is_numeric($_POST['n']) || !$_SESSION['logado'] || trim($_POST['conteudo']) === "") {
    redirect_to("negado");
  }
  
  Recordset::query("INSERT INTO noticia_comentario(id_usuario, id_noticia, conteudo) VALUES(" .
    $_SESSION['usuario']['id'] . "," . addslashes($_POST['n']) . ", '" . addslashes($_POST['conteudo']) . "'
  )");
  
  redirect_to("", "", array("secao" => "ler_noticia", "id" => $_POST['n'], "ok" => 1));
