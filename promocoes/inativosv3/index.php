<?php
	if(!defined('PROMOCAO')) define('PROMOCAO', 5);
	if(!defined('URL')) define('URL', 'http://narutogame.com.br/promocoes/inativosv3/');

	if(!defined('IS_SENDER')) {
		require_once('../../include/db.php');
		require_once('../../class/Recordset.php');
		require_once('../baseintencoder.php');

		$uuid		= BaseIntEncoder::decode(filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING)) - 1000000;
		$user		= Recordset::query('SELECT * FROM global.user WHERE email="' . addslashes(filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL)) . '" AND id=2' . (int)$uuid);
		
		if(!$user->num_rows) {
			die('Inválido :(');	
		}
	
		$promocao	= Recordset::query('SELECT * FROM promocao_usuario WHERE promocao_id=' . PROMOCAO . ' AND usuario_id=' . $user->row()->id);
		
		if(!$promocao->num_rows) {
			die('Não cadastrado na promoção :(');	
		}
	}
?>
<html>
<title>Round 10 - Naruto Game</title>
</head>

<body>
<div align="center"><img src="https://i.imgur.com/QfeDNNM.png"></div>
<div align="center">
<h2>O Novo Round começa dia 21/08/2018 às 15:00hs</h2>
<h3>Volte a jogar Naruto Game e recupere com esse e-mail 10 créditos e 2000 ryous.<br> Acesse sua conta e vá até a página "meus dados" e adicione o código <?php echo $_GET['code'] ?> para resgatar seus prêmios.</h3>

Se você não consegue ver esse e-mail <a href="">Clique aqui</a>. Ou cole a URL abaixo no seu navegador.<br />
<strong>http://narutogame.com.br/promocoes/inativosv3/?email=<?php echo $_GET['email'] ?>&code=<?php echo $_GET['code'] ?></strong>
</div>
<!--<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><img src="http://narutogame.com.br/promocoes/inativosv3/01.jpg" width="267" height="189" style="border:0;" /></td>
    <td><img src="http://narutogame.com.br/promocoes/inativosv3/02.jpg" width="266" height="189" style="border:0;" /></td>
    <td>
		<img name="index_r4_c1" src="<?php echo URL ?>generate.php?code=<?php echo $_GET['code'] ?>&email=<?php echo $_GET['email'] ?>" style="display: block;" border="0" id="index_r4_c1" alt="" />
    </td>
  </tr>
  <tr>
    <td><img src="http://narutogame.com.br/promocoes/inativosv3/04.jpg" width="267" height="189" style="border:0;" /></td>
    <td><img src="http://narutogame.com.br/promocoes/inativosv3/05.jpg" width="266" height="189" style="border:0;" /></td>
    <td><img src="http://narutogame.com.br/promocoes/inativosv3/06.jpg" width="267" height="189" style="border:0;" /></td>
  </tr>
  <tr>
    <td><img src="http://narutogame.com.br/promocoes/inativosv3/07.jpg" width="267" height="189" style="border:0;" /></td>
    <td><img src="http://narutogame.com.br/promocoes/inativosv3/08.jpg" width="266" height="189" style="border:0;" /></td>
    <td><img src="http://narutogame.com.br/promocoes/inativosv3/09.jpg" width="267" height="189" style="border:0;" /></td>
  </tr>
</table>-->
</body>
</html>