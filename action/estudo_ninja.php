<?php if(between(date('Hi'), '2345', '2359') || between(date('Hi'), '0000', '0015')): ?>
<div>
	<font style="font-size: 18px;"><?php echo t('actions.a131')?></font><br /><br/>
	<?php echo t('actions.a132')?>
</div>
<?php die(); ?>
<?php endif ?>
<?php
  $has_quiz_in_cache = Player::getFlag('estudo_ninja_ser', $basePlayer->id);

  // Verifica se o jogador tem a vantagem que aumenta as questões do Estudo
  if($basePlayer->hasItem(1494334)){
    $total = 15;
  }else{
    $total = 10;
  }

  if(!$has_quiz_in_cache) {
    $quizes = Recordset::query("
      SELECT
      id,
      nome_".Locale::get()." AS nome,
      CEIL(RAND() * 26) AS img
      
      FROM
        quiz
      
      ORDER BY RAND() LIMIT ". $total);
    
    $basePlayer->setFlag('estudo_ninja_ser', serialize($quizes->result_array()));
    $basePlayer->setFlag('estudo_ninja_fim', date('Y-m-d H:i:s', strtotime('+3 minute')));
  } else {
    $quizes = Recordset::fromArray(unserialize($has_quiz_in_cache));
  }

  $_SESSION['estudo_ninja_to_key'] = md5(rand(1, 512384) . date('YmdHis'));

  $time_end = Player::getFlag('estudo_ninja_fim', $basePlayer->id);
?>
<?php if(strtotime($time_end) < strtotime('+0 minute')): ?>
	<div>
		<font style="font-size: 18px;"><?php echo t('actions.a131')?></font><br /><br/>
		<?php echo t('actions.a133')?>
		
	</div>
<?php else: ?>
<style type="text/css">
	.imagem {
		background-repeat: no-repeat; 
		background-position: center center;
	}
	
	.imagem, .x {
		position:relative;
		width: 250px;
		height: 199px;
		margin-left:34px		
	}
	
	.nome {
		text-align: center;	
		background-image: URL('<?php echo img('layout/bingobook/barra_nome.png') ?>');
		background-repeat: no-repeat; 
		background-position: center center;
		color: #FFF;
		font-size: 14px;
		padding-top: 12px;
		height: 34px;
		clear: both;
	}
	.nome2{
		text-align: center;	
		background-image: URL('<?php echo img('layout/bingobook/barra_nome2.png') ?>');
		background-repeat: no-repeat; 
		background-position: center center;
		height: 73px;
		clear: both;
	}
	.nome2 p{
		color: #FFF;
		font-size: 14px;
		padding-top: 12px;
		width:290px;
		padding-left:10px;
	}
	.titulo {
		text-align: center;	
		background-image: URL('<?php echo img('layout/bingobook/barra_titulo.png') ?>');
		background-repeat: no-repeat; 
		background-position: center center;
		color: #FFF;
		font-size: 11px;
		padding-top: 10px;
		height: 23px;
	}
	
	.recompensa {
		padding-top: 13px;
		font-size: 13px;
		font-weight: bold;
		color: #000;
		z-index: 100000000
	}
	
	.recompensa * {
		font-size: 13px;
		font-weight: bold
	}
	
	.b-wrap-right {
		background-image: URL('<?php echo img('layout/bingo_book_r.png') ?>') !important;
		position: absolute;
		right: 0px;
		height: 500px;
	}

	.b-wrap-left {
		background-image: URL('<?php echo img('layout/bingo_book_l.png') ?>') !important;
		position: absolute;
		left: 0px;
		height: 500px;
	}
	
	.bingo_book_pagina {
		padding-left: 13px;	
		color: #000		;
		height: 500px;
		width: 350px;
	}

	.radio {
		width: 19px;
		height: 17px;
		padding: 0 5px 0 0;
		display: block;
		clear: left;
		float: left;
	}
	
	#bingobook {
		position: relative;
	}
	
	#bingobook .paginator {
		position: absolute;
		bottom: 5px;
		right: 30px;
	}
	
	.acerto {
		color: #9A9;
		font-size: 14px;
		font-weight: bold;
		text-align: center;
		display: none;
		clear: both;
	}
	
	.erro {
		color: #F00;
		font-size: 14px;
		font-weight: bold;
		text-align: center;
		display: none;
		clear: both;
	}
</style>
<form id="f-estudo-ninja" onsubmit="return false">
<input type="hidden" name="key" value="<?php echo $_SESSION['estudo_ninja_to_key'] ?>" />
<div id="bingobook">
	<div class="b-load">
    	<div class="bingo_book_pagina" style="color: #000; text-align: left;">
        	<font style="font-size: 18px;"><?php echo t('actions.a131')?></font><br /><br/>
    		<?php
    			$time = get_time_diff($time_end);
    		?>
            <?php echo t('actions.a134')?>
			
		</div>
	<?php $counter = 0; ?>
	<?php foreach($quizes->result_array() as $quiz): ?>
		<?php
      $quiz_answer = Recordset::query("
        SELECT
            id,
            resposta_".Locale::get()." as resposta,
            correto
        
        FROM
            quiz_answer
        
        WHERE id_quiz = " . $quiz['id']."
  
        ORDER BY RAND();");
    ?>
		<div class="bingo_book_pagina" align="center" style="text-align: left;">
			<div class="imagem" style="background-image: URL(<?php echo img('layout/estudo_ninja/'. $quiz['img'] . '.jpg') ?>)">
			</div>
			<div>
				<div id="acerto-<?php echo $quiz['id'] ?>" class="acerto"><?php echo t('actions.a135')?></div>
				<div id="erro-<?php echo $quiz['id'] ?>" class="erro"><?php echo t('actions.a136')?></div>
			</div>
			<div class="nome2">
            		<p><?php echo $quiz['nome'] ?></p>
			</div>
			<div class="recompensa">
            	<ul style="margin:0">
            		<?php foreach($quiz_answer->result_array() as $resp){?>
                   		<li><input type="radio" name="quiz_<?php echo $quiz['id'] ?>" value="<?php echo $resp['id'] ?>" /><?php echo $resp['resposta'] ?></li>
                    <?php }?>
                </ul>    
			</div>
		</div>
	<?php endforeach; ?>
		<div class="bingo_book_pagina" align="center" style="text-align: left;">
		    <font style="font-size: 18px;"><?php echo t('actions.a131')?></font><br /><br/>
			<?php echo t('actions.a137')?>
			<input type="button" class="button" onclick="estudo_ninja_final();" id="b-estudo-ninja-fim" value="<?php echo t('botoes.terminar_estudo_ninja')?>" />
			<br />
			<br />
			<div id="d-estudo-ninja-resultado" style="text-align: center; font-size: 18px">
			
			</div>
		</div>
	</div>
	<div class="paginator">
		<input type="button" class="back-button button" value="<?php echo t('botoes.pagina_anterior')?>" style="cursor:pointer;"/>
		<input type="button" class="next-button button" value="<?php echo t('botoes.pagina_proxima')?>" style="cursor:pointer;"/>
	</div>
</div>
</form>
<script type="text/javascript">
	//$("#bingobook").booklet({width: 725, height: 500, overlays: false, manual: false, tabs: false});
	//$(".trans").css('opacity', .6);
	$('#bingobook').css('width', 700)
				   .css('height', 500);
	
	var c	= 1;
	var lp	= 0;
	var mlp	= 12;
	
	
	$('#bingobook .b-load .bingo_book_pagina').each(function () {
		if(c > 2) {
			$(this).hide();
		}

		$(this).addClass(c++ % 2 ? 'b-wrap-left' : 'b-wrap-right');		
	});

	$('.back-button').click(function () {
		if(!lp) return;
		
		lp -= 2;

		$('.bingo_book_pagina').hide();
		
		$($('#bingobook .b-load .bingo_book_pagina')[lp]).show();
		$($('#bingobook .b-load .bingo_book_pagina')[lp + 1]).show();
	});
	
	$('.next-button').click(function () {
		if(lp > mlp + 2) return;
		lp += 2;
		
		$('.bingo_book_pagina').hide();
		
		$($('#bingobook .b-load .bingo_book_pagina')[lp]).show();
		$($('#bingobook .b-load .bingo_book_pagina')[lp + 1]).show();
	});
	
	var ___ii = setTimeout(function () {
		createTimer(<?php echo $time['h'] ?>, <?php echo $time['m'] ?>, <?php echo $time['s'] ?>, ['f-estudo-ninja-c-timer', 'f-estudo-ninja-c-timer2'], function () {
			alert('<?php echo t('actions.a138')?>');

			$.ajax({
				url: '?acao=estudo_ninja_final',
				type: 'post',
				data: $('#f-estudo-ninja').serialize(),
				success: function (e) {
					eval(e);
					clearTimers();
				}
			});
		});
		
		clearTimeout(___ii);
	}, 1000);
	
	//Custom.init();

</script>
<?php endif; ?>
