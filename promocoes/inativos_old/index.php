<?php
	if(!defined('PROMOCAO')) define('PROMOCAO', 1);
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Promoção Naruto Game</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">td img {display: block;}</style>
<!--Fireworks CS3 Dreamweaver CS3 target.	Created Thu Feb 14 12:28:29 GMT-0200 2013-->
</head>
<body bgcolor="#ffffff">
<div align="center">
	Se você não consegue ver esse e-mail <a href="">Clique aqui</a>. Ou cole a URL abaixo no seu navegador.<br />
	<strong>http://narutogame.com.br/promocoes/inativos/?email=<?php echo $_GET['email'] ?>&code=<?php echo $_GET['code'] ?></strong>
</div>
<table border="0" cellpadding="0" cellspacing="0" style="display: block;" width="600" align="center">
<!-- fwtable fwsrc="EmailR8cs5.png" fwpage="Page 1" fwbase="index.gif" fwstyle="Dreamweaver" fwdocid = "117415020" fwnested="0" -->
	<tr>
		<td><img src="<?php echo URL ?>spacer.gif" style="display: block;" width="288" height="1" border="0" alt="" /></td>
		<td><img src="<?php echo URL ?>spacer.gif" style="display: block;" width="312" height="1" border="0" alt="" /></td>
		<td><img src="<?php echo URL ?>spacer.gif" style="display: block;" width="1" height="1" border="0" alt="" /></td>
	</tr>

	<tr>
		<td colspan="2"><img name="index_r1_c1" src="<?php echo URL ?>index_r1_c1.jpg" style="display: block;" width="600" height="420" border="0" id="index_r1_c1" alt="" /></td>
		<td><img src="<?php echo URL ?>spacer.gif" style="display: block;" width="1" height="420" border="0" alt="" /></td>
	</tr>
	<tr>
		<td colspan="2"><img name="index_r2_c1" src="<?php echo URL ?>index_r2_c1.jpg" style="display: block;" width="600" height="48" border="0" id="index_r2_c1" alt="" /></td>
		<td><img src="<?php echo URL ?>spacer.gif" style="display: block;" width="1" height="48" border="0" alt="" /></td>
	</tr>
	<tr>
		<td><img name="index_r3_c1" src="<?php echo URL ?>index_r3_c1.jpg" style="display: block;" width="288" height="253" border="0" id="index_r3_c1" alt="" /></td>
		<td><img name="index_r3_c2" src="<?php echo URL ?>index_r3_c2.jpg" style="display: block;" width="312" height="253" border="0" id="index_r3_c2" alt="" /></td>
		<td><img src="<?php echo URL ?>spacer.gif" style="display: block;" width="1" height="253" border="0" alt="" /></td>
	</tr>
	<tr>
		<td>
			<!-- index_r4_c1.jpg -->
			<img name="index_r4_c1" src="<?php echo URL ?>generate.php?code=<?php echo $_GET['code'] ?>&email=<?php echo $_GET['email'] ?>" style="display: block;" width="288" height="91" border="0" id="index_r4_c1" alt="" />
		</td>
		<td><img name="index_r4_c2" src="<?php echo URL ?>index_r4_c2.jpg" style="display: block;" width="312" height="91" border="0" id="index_r4_c2" alt="" /></td>
		<td><img src="<?php echo URL ?>spacer.gif" style="display: block;" width="1" height="91" border="0" alt="" /></td>
	</tr>
	<tr>
		<td colspan="2"><img name="index_r5_c1" src="<?php echo URL ?>index_r5_c1.jpg" style="display: block;" width="600" height="156" border="0" id="index_r5_c1" alt="" /></td>
		<td><img src="<?php echo URL ?>spacer.gif" style="display: block;" width="1" height="156" border="0" alt="" /></td>
	</tr>
	<tr>
		<td colspan="2">
			<a href="http://www.youtube.com/watch?v=AtA0rYmR7Nc">
				<img name="index_r6_c1" src="<?php echo URL ?>index_r6_c1.jpg" style="display: block;" width="600" height="311" border="0" id="index_r6_c1" alt="" />
			</a>
		</td>
		<td><img src="<?php echo URL ?>spacer.gif" style="display: block;" width="1" height="311" border="0" alt="" /></td>
	</tr>
	<tr>
		<td colspan="2">
			<a href="http://narutogame.com.br">
				<img name="index_r7_c1" src="<?php echo URL ?>index_r7_c1.jpg" style="display: block;" width="600" height="79" border="0" id="index_r7_c1" alt="" />
			</a>
		</td>
		<td><img src="<?php echo URL ?>spacer.gif" style="display: block;" width="1" height="79" border="0" alt="" /></td>
	</tr>
</table>
</body>
</html>
