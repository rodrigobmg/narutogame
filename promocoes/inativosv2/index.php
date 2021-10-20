<?php
	if(!defined('PROMOCAO')) define('PROMOCAO', 3);
	if(!defined('URL')) define('URL', 'http://narutogame.com.br/promocoes/inativos/');

	if(!defined('IS_SENDER')) {
		require_once('../../include/db.php');
		require_once('../../class/Recordset.php');
		require_once('../baseintencoder.php');

		$uuid		= BaseIntEncoder::decode(filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING)) - 1000000;
		$user		= Recordset::query('SELECT * FROM global.user WHERE email="' . addslashes(filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL)) . '" AND id=' . (int)$uuid);
		
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
<title>Round 9 - Naruto Game</title>
</head>

<body>
<div align="center">
	Se você não consegue ver esse e-mail <a href="">Clique aqui</a>. Ou cole a URL abaixo no seu navegador.<br />
	<strong>http://narutogame.com.br/promocoes/inativos/?email=<?php echo $_GET['email'] ?>&code=<?php echo $_GET['code'] ?></strong>
</div>
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td><img src="http://narutogame.com.br/promocoes/inativos/NarutoGame_E-mail-MKT_800px_01.jpg" width="267" height="189" style="border:0;" /></td>
    <td><img src="http://narutogame.com.br/promocoes/inativos/NarutoGame_E-mail-MKT_800px_02.jpg" width="266" height="189" style="border:0;" /></td>
    <td>
		<img name="index_r4_c1" src="<?php echo URL ?>generate.php?code=<?php echo $_GET['code'] ?>&email=<?php echo $_GET['email'] ?>" style="display: block;" border="0" id="index_r4_c1" alt="" />
    </td>
  </tr>
  <tr>
    <td><img src="http://narutogame.com.br/promocoes/inativos/NarutoGame_E-mail-MKT_800px_04.jpg" width="267" height="189" style="border:0;" /></td>
    <td><img src="http://narutogame.com.br/promocoes/inativos/NarutoGame_E-mail-MKT_800px_05.jpg" width="266" height="189" style="border:0;" /></td>
    <td><img src="http://narutogame.com.br/promocoes/inativos/NarutoGame_E-mail-MKT_800px_06.jpg" width="267" height="189" style="border:0;" /></td>
  </tr>
  <tr>
    <td><img src="http://narutogame.com.br/promocoes/inativos/NarutoGame_E-mail-MKT_800px_07.jpg" width="267" height="189" style="border:0;" /></td>
    <td><img src="http://narutogame.com.br/promocoes/inativos/NarutoGame_E-mail-MKT_800px_08.jpg" width="266" height="189" style="border:0;" /></td>
    <td><img src="http://narutogame.com.br/promocoes/inativos/NarutoGame_E-mail-MKT_800px_09.jpg" width="267" height="189" style="border:0;" /></td>
  </tr>
</table>
</body>
</html>