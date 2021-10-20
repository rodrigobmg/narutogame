<?php
  $script_redirect = true;
  $time_end = Player::getFlag('estudo_ninja_fim', $basePlayer->id);

  if(strtotime($time_end) < strtotime('+0 minute')) {
    if($_SESSION['estudo_ninja_to_key'] != $_POST['key']) {
      redirect_to('negado', NULL, array('e' => 1));		
    }
  }

  $_SESSION['estudo_ninja_to_key'] = md5('YmdHis');

  $quizes = Recordset::fromArray(unserialize(Player::getFlag('estudo_ninja_ser', $basePlayer->id)));
  $total = 0;
  $acertos = $erros = array();

  foreach($quizes->result_array() as $quiz) {
    $quiz_variable = 'quiz_' . $quiz['id'];

    if(!isset($_POST[$quiz_variable])) {
      $_POST[$quiz_variable] = 0;
    }
	
    $answer = Recordset::query('
      SELECT 
        * 
      FROM 
        quiz_answer 
      
      WHERE 
        id_quiz=' . $quiz['id'] . ' AND 
        correto=\'1\' AND
        id=' . (int)$_POST[$quiz_variable]
      );

    if($answer->num_rows) {
      $total++;
      $basePlayer->setFlag('estudo_ninja_pontos', Player::getFlag('estudo_ninja_pontos', $basePlayer->id) + 1);

      $acertos[] = $quiz['id'];
    } else {
      $erros[] = $quiz['id'];		
    }
  }

  $basePlayer->setFlag('estudo_ninja_fim', date('Y-m-d H:i:s', strtotime('-20 minute')));

  foreach($acertos as $acerto) {
    echo '$("#acerto-' . $acerto . '").show();' . PHP_EOL;
  }

  foreach($erros as $erro) {
    echo '$("#erro-' . $erro . '").show();' . PHP_EOL;
  }
  
  echo '$("#b-estudo-ninja-fim").hide(); $("#f-estudo-ninja-c-timer").remove(); $("#f-estudo-ninja-c-timer2").remove()' . PHP_EOL;
  echo '$("#d-estudo-ninja-resultado").html("'.t('actions.a139').' ' . $total . ' '.t('actions.a140').'")';

  if($basePlayer->hasMissaoDiariaPlayer(4)->total){
    // Adiciona os contadores nas Missões do Estudo Ninja
    Recordset::query("UPDATE player_missao_diarias set qtd = qtd + 1 
            WHERE id_player = ". $basePlayer->id." 
            AND id_missao_diaria in (select id from missoes_diarias WHERE tipo = 4) 
            AND completo = 0 ");
  }
