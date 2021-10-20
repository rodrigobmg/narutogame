<?php
	$cn = 0;
	

	$_POST['vila'] = (isset($_POST['vila']) && !$_POST['vila']) || !isset($_POST['vila']) ? 0 : decode($_POST['vila']);
	$_POST['from'] = (isset($_POST['from']) && !$_POST['from']) || !isset($_POST['from']) ? 0 : decode($_POST['from']);
	
	if(!is_numeric($_POST['vila']) || !is_numeric($_POST['from'])) {
		redirect_to("negado");	
	}

	$where = (int)$_POST['vila'] ? " AND a.id_vila=" . $_POST['vila'] : "";
	//$where .= (isset($_POST['nome']) && !$_POST['nome']) || !isset($_POST['nome']) ? "" : " AND a.nome LIKE '%" . addslashes($_POST['nome']) . "%'";
?>
<div class="titulo-secao"><p><?php echo t('halldafama.h1')?> - Round 27</p></div>
<div class="msg_gai" >
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
			<b><?php echo t('halldafama.h1')?> <?php echo t('halldafama.h4')?> Naruto Game,</b>
			<p>
			<?php echo t('halldafama.h2')?>
			<br /><br />
			<div style="float:left; width:65px">
			<a href="?secao=halldafama" class="linkTopo">Round 1</a><br />
			<a href="?secao=halldafama" class="linkTopo">Round 2</a><br />
			<a href="?secao=halldafama_r3" class="linkTopo">Round 3</a><br />
			</div>
			<div style="float:left; margin-left:15px; width:65px">
			<a href="?secao=halldafama_r4" class="linkTopo">Round 4</a><br />
            <a href="?secao=halldafama_r5" class="linkTopo">Round 5</a><br />
			 <a href="?secao=halldafama_r6" class="linkTopo">Round 6</a><br />
			</div>
			<div style="float:left; margin-left:15px; width:65px">
			<a href="?secao=halldafama_r7" class="linkTopo">Round 7</a><br />
			<a href="?secao=halldafama_r8" class="linkTopo">Round 8</a><br />
			<a href="?secao=halldafama_r9" class="linkTopo">Round 9</a><br />
			</div>
			<div style="float:left; width:65px">
			<a href="?secao=halldafama_r10" class="linkTopo">Round 10</a><br />
			<a href="?secao=halldafama_r11" class="linkTopo">Round 11</a><br />
			<a href="?secao=halldafama_r12" class="linkTopo">Round 12</a><br />
			</div>
			<div style="float:left; margin-left:10px; width:65px">
			Round 13<br />
			<a href="?secao=halldafama_r14" class="linkTopo">Round 14</a><br />
			<a href="?secao=halldafama_r15" class="linkTopo">Round 15</a><br />
            </div>
            <div style="float:left; width:65px">
            <a href="?secao=halldafama_r16" class="linkTopo">Round 16</a><br />
            <a href="?secao=halldafama_r17" class="linkTopo">Round 17</a><br />
            <a href="?secao=halldafama_r18" class="linkTopo">Round 18</a><br />
			</div>
			<div class="break"></div>
			<div style="float:left; width:65px">
            <a href="?secao=halldafama_r19" class="linkTopo">Round 19</a><br />
            <a href="?secao=halldafama_r20" class="linkTopo">Round 20</a><br />
            <a href="?secao=halldafama_r21" class="linkTopo">Round 21</a><br />
			</div>
			<div style="float:left; margin-left:15px; width:65px">
            <a href="?secao=halldafama_r22" class="linkTopo">Round 22</a><br />
            <a href="?secao=halldafama_r23" class="linkTopo">Round 23</a><br />
            <a href="?secao=halldafama_r24" class="linkTopo">Round 24</a><br />
            </div>
			<div style="float:left; margin-left:15px; width:65px">
            <a href="?secao=halldafama_r25" class="linkTopo">Round 25</a><br />
            <a href="?secao=halldafama_r26" class="linkTopo">Round 26</a><br />
            <a href="?secao=halldafama_r27" class="linkTopo">Round 27</a><br />
            </div>
			</div>
			</p>
			<br><br>
		</div>
	</div>
</div>      
<table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="49" align="left" colspan="2" class="subtitulo-home"><b style="color:#fff; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo t('halldafama.h5')?> ( Round 26 )</b></td>
          </tr>
          <tr class="cor_nao">
            <td width="34" align="center"><img src="<?php echo img() ?>layout/trophy.png" width="16" height="16" /></td>
            <td width="696" height="25" align="left">Vip Sanin</td>
          </tr>
		  <tr height="4"></tr>
          <tr class="cor_sim">
            <td align="center"><img src="<?php echo img() ?>layout/trophy-silver.png" width="16" height="16" /></td>
            <td height="25" align="left">Vip Sanin</td>
          </tr>
		   <tr height="4"></tr>
          <tr class="cor_nao">
            <td align="center"><img src="<?php echo img() ?>layout/trophy-bronze.png" width="16" height="16" /></td>
            <td height="25" align="left">Vip Sanin</td>
          </tr>
          <tr class="cor_sim">
            <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
            <td height="25" align="left">Vip Sanin</td>
          </tr>
		   <tr height="4"></tr>
          <tr class="cor_nao">
            <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
            <td height="25" align="left">Vip Jounin</td>
          </tr>
		   <tr height="4"></tr>
          <tr class="cor_sim">
            <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
            <td height="25" align="left">Vip Genin</td>
          </tr>
</table>
<br />
<form method="post">

    <table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="49" align="left" colspan="7" class="subtitulo-home">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:#FFFFFF"><?php echo t('halldafama.h16')?></b></td>
        </tr>
      <tr >
        <td width="69" height="34" align="center"><b><?php echo t('geral.vila')?></b></td>
        <td width="87" align="left"><select name="vila" id="vila">
          <option value="<?php echo encode("00") ?>"><?php echo t('geral.geral')?></option>
          <?php
          	$qVila = Recordset::query("SELECT id, nome_".Locale::get()." AS nome FROM vila where id in (1,2,3,4,5,6,7,8,9,10,11,12)");
			while($rVila = $qVila->row_array()) {
				$selected = $_POST['vila'] == $rVila['id'] ? "selected='selected'" : "";
				echo "<option value='" . encode($rVila['id']) . "' $selected>" . htmlentities($rVila['nome']) . "</option>";	
			}
		  ?>
                </select></td>
        <td width="63"  align="center"><b><?php echo t('geral.posicao')?></b></td>
        <td width="190"  height="34" align="left"><select name="from" id="from">
        <?php
        	$rTotal = Recordset::query("
                SELECT
                    COUNT(a.id) as total
                
                FROM 
                    historico_ninja a 
					
                WHERE 1=1 
					  AND round = 27
                     $where
					 													
			")->row()->total;
			
			
			for($f = 0; $f <= 49; $f += 25) {
				$selected = $_POST['from'] == $f ? "selected='selected'" : "";
				echo "<option value='" . encode($f) . "' $selected>" . ($f + 1) . " at&eacute; " . ($f + 25) . "</option>";	
			}
		?>
       </select></td>
		<td width="63"  align="center"><!--<b>Nome</b>--></td>
		<td width="190"  height="34" align="left">
		<!--<input type="text" name="nome" value="<?php //echo isset($_POST['nome']) ? addslashes($_POST['nome']) : '' ?>" />-->		</td>
        <td  align="right">
		<a class="button" data-trigger-form="1"><?php echo t('botoes.filtrar')?></a>	
		</td>
      </tr>
      </table><br />

         <table width="730" border="0" cellpadding="0" cellspacing="0">

      <tr >
        <td height="34" colspan="7" align="center">
      	<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "9601224575";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Hall da Fama -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
        </td>
      </tr>

    </table>
<br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="50" align="center">&nbsp;</td>
            <td width="90" align="center"><b style="color:#FFFFFF"><?php echo t('geral.posicao')?></b></td>
            <td width="170" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
			<td width="80" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
            <td width="70" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
            <td width="70" align="center"><b style="color:#FFFFFF"><?php echo t('geral.score')?></b></td>
            <td width="70" align="center"><b style="color:#FFFFFF"><?php echo t('geral.vila')?></b></td>
        	<td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('geral.personagem')?></b></td>
          </tr>
        </table></td>
      </tr>
    </table>
<table width="730" border="0" cellpadding="0" cellspacing="0">
		<?php
			//$i = $basePlayer->getVIPItem(array(20185,20186,20187));
						
			$order = (int)$_POST['vila'] ? "posicao_vila" : "posicao_geral";
            $query = Recordset::query("
				SELECT 
					a.*
				FROM 
					historico_ninja a
				WHERE 1=1
              		  AND round = 27
                     $where
                
                ORDER BY $order ASC LIMIT {$_POST['from']}, 25", true);
			
            foreach($query->result_array() as $r) {
				$posicao = (int)$_POST['vila'] ? $r['posicao_vila'] : $r['posicao_geral'];
                $cor = ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
               if($posicao <= 3){
                    $posRanking = "amarelo";
					$posRanking2 = "font-weight: bold";
                } else {
                    $posRanking = "";
					$posRanking2 = "";
                }
        ?>
      <tr <?php echo $cor ?>>
        <td width="50" align="center">
			
			<?php 
				switch($r['premio']){
					case 1:
						echo '
						<div class="trigger">
							<img src="'. img() .'layout/trophy.png" width="16" height="16" style="cursor:pointer" />
					   </div>
					   <div class="tooltip" style="margin-left:10px"> 
			
						 <b>Prêmio</b><br /><br />
						 Ganhador do XBOX360 + Camiseta do Naruto Game + Vip Sanin ( 64 créditos )
						
					   </div> 
						';
					break;
					
					case 2:
						echo '
						<div class="trigger">
							<img src="'. img() .'layout/trophy-silver.png" width="16" height="16" style="cursor:pointer" />
					   </div>
					   <div class="tooltip" style="margin-left:10px"> 
			
						 <b>Prêmio</b><br /><br />
						 Ganhador do PSP + Camiseta do Naruto Game + Vip Sanin ( 64 créditos )
						
					   </div> 
						';
					break;
					
					case 3:
						echo '
						<div class="trigger">
							<img src="'. img() .'layout/trophy-bronze.png" width="16" height="16" style="cursor:pointer" />
					   </div>
					   <div class="tooltip" style="margin-left:10px"> 
			
						 <b>Prêmio</b><br /><br />
						 Ganhador do PS2 + Camiseta do Naruto Game + Vip Sanin ( 64 créditos )
						
					   </div> 
						';
						
					break;
					
					case 4:
						echo '
						<div class="trigger">
							<img src="'. img() .'layout/award_star_gold_3.png" width="16" height="16" style="cursor:pointer" />
					   </div>
					   <div class="tooltip" style="margin-left:10px"> 
			
						 <b>Prêmio</b><br /><br />
						 Camiseta do Naruto Game + Vip Sanin ( 64 créditos )
						
					   </div> 
						';
					break;
					
					case 5:
						echo '
						<div class="trigger">
							<img src="'. img() .'layout/award_star_silver_3.png" width="16" height="16" style="cursor:pointer" />
					   </div>
					   <div class="tooltip" style="margin-left:10px"> 
			
						 <b>Prêmio</b><br /><br />
						 Vip Jounin ( 35 créditos )
						
					   </div> 
						';
					break;
					
					case 6:
						echo '
						<div class="trigger">
							<img src="'. img() .'layout/award_star_bronze_3.png" width="16" height="16" style="cursor:pointer" />
					   </div>
					   <div class="tooltip" style="margin-left:10px"> 
			
						 <b>Prêmio</b><br /><br />
						 Vip Genin ( 10 créditos )
						
					   </div> 
						';
					break;
				}			
			
			?>
		</td>
        <td width="90" align="center"  style="font-size: 13px; <?php echo $posRanking2 ?>" class="<?php echo $posRanking ?>"><?php echo $posicao ?>&ordm;</td>
        <td width="170" height="34" align="left" nowrap="nowrap">
      		<b class="linkTopo <?php echo $posRanking ?>" style="font-size: 13px; <?php echo $posRanking2 ?>"><?php echo $r['nome'] ?></b>
	   </td>
		<td width="80" align="center"><p>
			<?php switch($r['id_graduacao']){
				case 1:
					echo "Estudante";
				break;
				case 2:
					echo "Genin";
				break;	
				case 3:
					echo "Chunin";
				break;	
				case 4:
					echo "Jounin";
				break;	
				case 5:
					echo "ANBU";
				break;	
				case 6:
					echo "Sannin";
				break;	
				case 7:
					echo "Herói";
				break;								
			}?>
		</p></td>
        <td width="70" align="center"><p><?php echo $r['level'] ?></p></td>
        <td width="70" align="center"><p><?php echo $r['score'] ?></p></td>
        <td width="70" align="center"><img src="<?php echo img() ?>layout/bandanas/<?php echo $r['id_vila'] ?>.png" width="48" height="24" /></td>
        <td width="130" align="center"><img src="<?php echo img() ?>/layout<?php echo LAYOUT_TEMPLATE?>/dojo/<?php echo $r['id_classe'] ?><?php echo LAYOUT_TEMPLATE=="_azul" ? ".jpg":".png"?>" width="126" height="44" /></td>
      </tr>
	  <tr height="4"></tr>
      <?php
			}
	  ?>
    </table>
</form>