<style type="text/css">
.jogadores{
	color: #CCC;
	font-size: 11px;
}
</style>

<table style="width: 358px !important" border="0" align="center" cellpadding="0" cellspacing="0" id="p-estatisticas" style="vertical-align:middle;">
<?php
	$_POST['start'] = !isset($_POST['start']) || (isset($_POST['start']) && $_POST['start'] <= 0) ? 0 : $_POST['start'];

	$regsPerPage	= 8;	
	$cn				= 0;
	$totalPlayers	= 0;
	
	$qPlayers = Recordset::query("
		SELECT * FROM estatistica_player		
		ORDER BY total DESC LIMIT " . ((int)$_POST['start'] * $regsPerPage) . ", " . $regsPerPage, true);

?>
    <tr class="cor_nao" height="31">
    <?php 

		if(!$qPlayers->num_rows) {
			echo "<td style=\"color:#FFF\"><i>".t('actions.a174')."</i></td></tr>";	
		}

		$iP = 1;
		foreach($qPlayers->result_array() as $k=> $rP) {
		$totalPlayers += $rP['total'];
    ?>
    <?php
	
		if($k>1):
			$resize 	= "width='30' height='30'";
		    $valing 	= "style='vertical-align:middle;'";
			$nomeclasse = "style='font-size:12px; color:#e3eda3;'";
			$position 	= "";
			$position2 	= "";
			$fontsize	= "";
        elseif ($k==0):
            $valing 	= "style='vertical-align:top;'";
            $resize 	= "width='90' height='90'";
			$nomeclasse = "style='font-size:19px; position:absolute; color:#e3eda3;'";
			$position 	= "style='margin: 5px;'";
			$position2 	= "style='position:absolute; top:71px;'";
			$fontsize	= "";
		elseif($k==1):
            $valing 	= "style='vertical-align:bottom;'";
            $resize 	= "width='60' height='60'";
			$nomeclasse = "style='font-size:19px; position:absolute; top:91px; left:217px; color:#e3eda3'";
			$position 	= "style='position:absolute; left: 155px; top:90px;'";
			$position2 	= "style='position:absolute; top:108px; right:50px;'";
			$fontsize	= "";
        endif;
	?>
            
        <td width="90" <?php echo $valing ?> height="31" align="center" style="color:#e3eda3"><img src="<?php echo img();?>layout/home/<?php echo $rP['id_classe']; ?>.jpg" <?php echo $position ?> alt="<?php echo $rP['nome']; ?>" <?php echo $resize ?> /></td>
        <td width="150" <?php echo $valing ?> height="31" align="left" style="color:#e3eda3"><span <?php echo $nomeclasse ?>><?php echo $rP['nome']; ?></span><br />
        <span class="jogadores" <?php echo $position2 ?>><?php echo $rP['total'];?> <?php echo t('conquistas.c30')?></span></td>
         
       <?php   if($iP % 2 == 0){ 
                   $cor = ++$cn % 2 ? "cor_sim" : "cor_nao";
                    echo "</tr><tr height='3'><td></td></tr><tr height='31' class='" . $cor . "'>";
                 }
         
            $iP++;
        } 
    ?>
    </tr>
    <tr>
    	<td colspan="4">

        </td>
    </tr>
</table>