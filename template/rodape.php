		<div class="break"></div>
	</div>
	<div class="break"></div>
</div>
<div class="break"></div>

<div id="l-banner" style="position: absolute; height: auto; width: 160px; top: -200px; right: -180px; z-index:1000;">

	<div style="position:fixed">
	<script type="text/javascript"><!--
	google_ad_client = "ca-pub-9166007311868806";
	google_ad_slot = "5168311778";
	google_ad_width = 300;
	google_ad_height = 600;
	//-->
	</script>
	<script type="text/javascript"
	src="//pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>

	</div>

</div>

</div>
<div class="break"></div>
</div>
</div>
<div id="rodape-fim"></div>
<div id="rodape">
    	<div id="parceiros">
		    <div class="parceiros_logos">
					<a href="https://borutogame.com.br"><img src="<?php echo img() ?>/layout/logo/bg.png" alt="Boruto Game" border="0" width="200"></a>
			</div>
			<div class="parceiros_logos">
					<a href="https://allstarsgame.com.br"><img src="<?php echo img() ?>/layout/logo/aasg.png" alt="Anime All Stars Game" border="0" width="180"></a>
			</div>
            <div class="parceiros_logos">
            		<a href="https://cdzgame.com.br"><img src="<?php echo img() ?>layout/rodape/cdzg-logo.png" alt="CDZ Game" border="0" width="180"/></a>
            </div>
			 <div class="parceiros_logos">
            		<a href="https://dragonballgame.com.br"><img src="<?php echo img() ?>layout/rodape/dbg-logo.png" alt="Dragon Ball Game" border="0" width="180"/></a>
            </div>
            <div class="parceiros_logos">
            		<a href="https://bleachgame.com.br"><img src="<?php echo img() ?>layout/rodape/bg-logo.png" alt="Bleach Game" border="0" width="180"/></a>
            </div>
    	</div>
	        <div id="texto">
            <p class="text_rodape"> <span dir="ltr">&copy;2009 - 2016&nbsp;</span><b>Naruto Game</b> - <a href="index.php?secao=aviso_legal"><?php echo t('aviso_legal.al1')?></a> - <a href="index.php?secao=politica_privacidade"><?php echo t('cadastro.ca16')?></a> - <a href="index.php?secao=termos_uso"><?php echo t('cadastro.ca15')?></a> - <a href="index.php?secao=regras_punicoes"><?php echo t('cadastro.ca17')?></a><br />
            <?php echo t('templates.t50')?> </p>
        </div>
    <div id="rodape_interno"></div>
</div>
<div id="cnInventario"></div>
<div id="cnProfile"></div>
<div id="cnCla"></div>
<link href="css/ui-darkness/jquery-ui-darkness.css?c=1" rel="stylesheet" type="text/css"/>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>
<link href="css/tutorial.css" rel="stylesheet" type="text/css"/>
<link href="css/tabs.css" rel="stylesheet" type="text/css"/>
<link href="css/jquery.booklet.css?c=1" rel="stylesheet" type="text/css"/>
<link href="css/jquery.smartspinner.css" rel="stylesheet" type="text/css"/>
<script>
  head.js ("https://www.google-analytics.com/ga.js", function () {
    var tracker=_gat._getTracker ("UA-5481713-1");
    tracker._trackPageview ();
  });
</script>
<?php  if($_SESSION['logado'] && $_SESSION['basePlayer']): ?>
		<?php include 'template/chat.php' ?>
<?php endif; ?>

<script type="text/javascript">
head.js(
	{ui:			"js/jquery/jquery.ui.js?c=1"},
	//{ui:			"http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"},
	{tabs:			"js/jquery.tabs.js"},
	{tablefilter:	"js/jquery.uitablefilter.js"},
	{index:			"js/index.js"},
	//{social:		"js/social.js"},
	//{maskedinput:	"js/jquery.maskedinput.js"},
	{countdown:		"js/jquery.countdown.min.js"},
	{numeric:		"js/jquery.numeric.js"},
	{easing:		"js/jquery.easing.js"},
	{booklet:		"js/jquery.booklet.js"},
	{formcontrols:	"js/jquery.formcontrols.js"},
	{spinner:		"js/jquery.smartspinner.js"}

<?php
	$_GET['secao'] = $_GET['secao'] ? $_GET['secao'] : "home";

	if($_GET['secao'] && file_exists("js/" . $_GET['secao'] . ".js")) {
		echo ",'js/" . $_GET['secao'] . ".js?c=" . filemtime('js/' . $_GET['secao'] . '.js') . "'";
	}

	if($basePlayer) {
		echo ",'js/inventario.js?c=" . filemtime(ROOT . '/js/inventario.js') . "'";
	}
?>

);
	head.ready(function () {
		$.extend($.ui.dialog.prototype, {
		        'addbutton': function(buttonName, func) {
		                var buttons = this.element.dialog('option', 'buttons');
		                buttons[buttonName] = func;
		                this.element.dialog('option', 'buttons', buttons);
		        }
		});

		$.extend($.ui.dialog.prototype, {
		        'removebutton': function(buttonName) {
		                var buttons = this.element.dialog('option', 'buttons');
		                delete buttons[buttonName];
		                this.element.dialog('option', 'buttons', buttons);
		        }
		});

		$(document).ready(function () {
			<?php
				if(isset($_SESSION['_js_global_msg']) && $_SESSION['_js_global_msg']) {

					echo $_SESSION['_js_global_msg'];

					$_SESSION['_js_global_msg']	= '';
				}
			?>
		});
	});


	$(document).ready(function(e) {
		$('.flag img').css('opacity', .3).each(function() {
			if($(this).data('lang') == '<?php echo $_SESSION['lang'] ?>') {
				$(this).css('opacity', 1);
			}
		}).on('click', function () {
			var href		= location.href.replace(/(\?|&)lang=(.{2})/i, '');
			location.href	= href + (href.indexOf('?') == -1 ? '?lang=' : '&lang=') + $(this).data('lang');
		});
	});
	$(document).ready(function(e) {
		$('.layout_change img').css('opacity', .3).each(function() {
			if($(this).data('layout') == '<?php echo $_SESSION['layout'] ?>') {
				$(this).css('opacity', 1);
			}
		}).on('click', function () {
			var href		= location.href.replace(/(\?|&)layout=(.{2})/i, '');
			location.href	= href + (href.indexOf('?') == -1 ? '?layout=' : '&layout=') + $(this).data('layout');
		});
	});
</script>
<script type="text/javascript">
<?php if($basePlayer && $basePlayer->id_graduacao >= 4): ?>
	var __d_bingo_book = null;

	function bingo_book() {
		__d_bingo_book = $(document.createElement('DIV'));

		$(document.body).append(__d_bingo_book);

		$(__d_bingo_book).dialog({
			title: 'Bingo Book',
			modal: true,
			width: 770,
			height: 555,
			resizable: false,
			close: function () {
				__d_bingo_book.remove();
			}
		}).load('?acao=bingo_book');
	}
<?php endif; ?>
<?php if($basePlayer && $basePlayer->id_graduacao >= 2): ?>
	var __d_bingo_book_vila = null;

	function bingo_book_vila() {
		__d_bingo_book_vila = $(document.createElement('DIV'));

		$(document.body).append(__d_bingo_book_vila);

		$(__d_bingo_book_vila).dialog({
			title: 'Bingo Book da Vila',
			modal: true,
			width: 770,
			height: 555,
			resizable: false,
			close: function () {
				__d_bingo_book_vila.remove();
			}
		}).load('?acao=bingo_book_vila');
	}
<?php endif; ?>

<?php if($basePlayer && $basePlayer->id_equipe): ?>
	var __d_bingo_book_equipe = null;

	function bingo_book_equipe() {
		__d_bingo_book_equipe = $(document.createElement('DIV'));

		$(document.body).append(__d_bingo_book_equipe);

		$(__d_bingo_book_equipe).dialog({
			title: 'Bingo Book da Equipe',
			modal: true,
			width: 780,
			height: 580,
			resizable: false,
			close: function () {
				__d_bingo_book_equipe.remove();
			}
		}).load('?acao=bingo_book_equipe');
	}
<?php endif; ?>

<?php if($basePlayer && $basePlayer->id_guild): ?>
	var __d_bingo_book_guild = null;

	function bingo_book_guild() {
		__d_bingo_book_guild = $(document.createElement('DIV'));

		$(document.body).append(__d_bingo_book_guild);

		$(__d_bingo_book_guild).dialog({
			title: 'Bingo Book da Organização',
			modal: true,
			width: 780,
			height: 580,
			resizable: false,
			close: function () {
				__d_bingo_book_guild.remove();
			}
		}).load('?acao=bingo_book_guild');
	}
<?php endif; ?>

	function estudo_ninja() {
		__d_bingo_book = $(document.createElement('DIV'));

		$(document.body).append(__d_bingo_book);

		$(__d_bingo_book).dialog({
			title: 'Estudo Ninja',
			modal: true,
			width: 730,
			height: 565,
			resizable: false,
			close: function () {
				clearTimers();
				__d_bingo_book.remove();
			}
		}).load('?acao=estudo_ninja');
	}

	function estudo_ninja_final() {
		//if(confirm('Verifique se você respondeu todas as perguntas.\nAs perguntas que não forem respondidas serão ignoradas')) {
			$.ajax({
				url: '?acao=estudo_ninja_final',
				type: 'post',
				data: $('#f-estudo-ninja').serialize(),
				success: function (e) {
					eval(e);
				}
			});

			clearTimeout(___ii);
			clearTimers();
		//}
	}

	__bingo_book_key = '<?php echo $_SESSION['bingo_book_key'] ?>';

	head.ready(function () {
		$(document).ready(function () {
			<?php
				if(isset($_SESSION['_js_global_msg']) && $_SESSION['_js_global_msg']) {

					echo $_SESSION['_js_global_msg'];

					$_SESSION['_js_global_msg']	= '';
				}
			?>
		});
	});

</script>
</body>
</html>
