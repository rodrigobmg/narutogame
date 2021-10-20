<?php
	//require("../include/db.php");
	error_reporting(E_ALL ^ E_NOTICE);
	if($_POST['validador']){
		
			$level = $_POST['level'];
			$TAI = $_POST['tai'];
			$GEN = $_POST['gen'];
			$NIN = $_POST['nin'];
			$ENE = $_POST['ene'];
			$AGI = $_POST['agi'];
			$CON = $_POST['con'];
			$FOR = $_POST['for'];
			$INT = $_POST['int'];
			
			$ENE2 = $ENE + ($level * 4);
			
			
			// Calculos de HP/SP e STA --->
			$HP = $ENE2 * 5;
			$SP = ($ENE2 * 2 + $INT * 5) + ($CON * 5);
			$STA = ($ENE2 * 2 + $FOR * 5) + ($AGI * 5);				
			
			$CH_ESQ  = round($AGI / 4); // Esquiva
			$CH_DEF  = round($CON / 2); // Defesa extra
			
			$CH_CRIT = round(($FOR + $INT) / 2); // Critico
			
			$CH_DEF_BASE_JUT = round((($TAI + $NIN + $GEN) * 0.3) + ($INT * 0.5));
			$CH_DEF_BASE_TAI = round((($TAI + $NIN + $GEN) * 0.3) + ($FOR * 0.5));
	
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ferramentas - Naruto Game</title>
</head>
<body>
<form action="index.php" method="post" id="calculadora">
	<input type="hidden" name="validador" id="validador" value="1" />
  <table width="730" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="180" align="center"><table width="160" border="0" cellpadding="5" cellspacing="0">
          <tr>
            <td height="30" colspan="2" align="center">Calcule seus Atributos</td>
          </tr>
          <tr>
            <td height="10" colspan="2" align="left"></td>
          </tr>
          <tr>
            <td align="left">Level</td>
            <td align="center"><input name="level" type="text" id="level" value="<?php echo $level;?>" size="5" maxlength="3" /></td>
          </tr>
          <tr>
            <td width="41%" align="left">Taijutsu</td>
            <td width="59%" align="center"><input name="tai" type="text" id="tai" value="<?php echo $TAI;?>" size="5" maxlength="3" /></td>
          </tr>
          <tr>
            <td align="left">Genjutsu</td>
            <td align="center"><input name="gen" type="text" id="gen" value="<?php echo $GEN;?>" size="5" maxlength="3" /></td>
          </tr>
          <tr>
            <td align="left">Ninjutsu</td>
            <td align="center"><input name="nin" type="text" id="nin" value="<?php echo $NIN;?>" size="5" maxlength="3" /></td>
          </tr>
          <tr>
            <td align="left">Energia</td>
            <td align="center"><input name="ene" type="text" id="ene" value="<?php echo $ENE;?>" size="5" maxlength="3" /></td>
          </tr>
          <tr>
            <td align="left">Agilidade</td>
            <td align="center"><input name="agi" type="text" id="agi" value="<?php echo $AGI;?>" size="5" maxlength="3" /></td>
          </tr>
          <tr>
            <td align="left">Conhecimento</td>
            <td align="center"><input name="con" type="text" id="con" value="<?php echo $CON;?>" size="5" maxlength="3" /></td>
          </tr>
          <tr>
            <td align="left">Força</td>
            <td align="center"><input name="for" type="text" id="for" value="<?php echo $FOR;?>" size="5" maxlength="3" /></td>
          </tr>
          <tr>
            <td align="left">Inteligência</td>
            <td align="center"><input name="int" type="text" id="int" value="<?php echo $INT;?>" size="5" maxlength="3" /></td>
          </tr>
          <tr>
            <td colspan="2" align="center"><input type="submit" name="button" id="button" value="&gt; Calcular" /></td>
          </tr>
        </table></td>
      <td align="center">HP: <?php echo $HP;?><br />
        Chakra: <?php echo $SP;?><br />
        Stamina: <?php echo $STA;?><br />
        Critico: <?php echo $CH_CRIT;?><br />
        Esquiva: <?php echo $CH_ESQ;?><br />
        Defesa Extra: <?php echo $CH_DEF;?><br />
        Defesa ( Tai ): <?php echo $CH_DEF_BASE_TAI;?><br />
        Defesa (Nin / Gen): <?php echo $CH_DEF_BASE_JUT;?>
        </td>
    </tr>
  </table>
</form>
</body>
</html>
