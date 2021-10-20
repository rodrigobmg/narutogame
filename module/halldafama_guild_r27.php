<script type="text/javascript">    
    $(document).ready(function () {
    	$('.b-detalhe').click(function () {
    		var id = $(this).attr('id');

    		if(parseInt($('#t-detalhe-player-' + id).attr('shown'))) {
    			$('#d-detalhe-player-' + id).hide('slide');
    		
    			$('#t-detalhe-player-' + id).hide();
    			$('#t-detalhe-player-' + id).attr('shown', 0);
    		} else {
    			$('#t-detalhe-player-' + id).show();
    			$('#t-detalhe-player-' + id).attr('shown', 1);

    			$('#d-detalhe-player-' + id).show('slide');
    		}
    	});
    });
</script>
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
<div class="titulo-secao"><p><?php echo t('halldafama.h18')?> - Round 27</p></div>
<div class="msg_gai" >
	<div class="msg">
		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
			<b><?php echo t('halldafama.h18')?>,</b>
			<p>
			<?php echo t('halldafama.h2')?>
			<br /><br />
			<div style="float:left; margin-left:20px">
			<a href="?secao=halldafama_guild_r10" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 10</a><br />
			<a href="?secao=halldafama_guild_r11" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 11</a><br />
			<a href="?secao=halldafama_guild_r12" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 12</a><br />
			<?php echo t('halldafama.h18')?> do Round 13<br />
			<a href="?secao=halldafama_guild_r14" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 14</a><br />
			<a href="?secao=halldafama_guild_r15" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 15</a><br />
            <a href="?secao=halldafama_guild_r16" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 16</a><br />
            <a href="?secao=halldafama_guild_r17" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 17</a><br />
            <a href="?secao=halldafama_guild_r18" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 18</a><br />
            <a href="?secao=halldafama_guild_r19" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 19</a><br />
            <a href="?secao=halldafama_guild_r20" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 20</a><br />
            <a href="?secao=halldafama_guild_r21" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 21</a><br />
            <a href="?secao=halldafama_guild_r22" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 22</a><br />
            <a href="?secao=halldafama_guild_r23" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 23</a><br />
            <a href="?secao=halldafama_guild_r24" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 24</a><br />
            <a href="?secao=halldafama_guild_r25" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 25</a><br />
            <a href="?secao=halldafama_guild_r26" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 26</a><br />
            <a href="?secao=halldafama_guild_r27" class="linkTopo"><?php echo t('halldafama.h18')?> do Round 27</a><br />
			</div>
			<div class="break"></div>
			</p>
		</div>
	</div>
</div>      
<br /><br />
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
          	$qVila = Recordset::query("SELECT id, nome_".Locale::get()." AS nome FROM vila where id in (1,2,3,4,5,6,7,8)");
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
                    historico_guild a 
					
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
            <td width="30" align="center">&nbsp;</td>
            <td width="90" align="center"><b style="color:#FFFFFF"><?php echo t('geral.posicao')?></b></td>
            <td width="170" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
            <td width="70" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
            <td width="70" align="center"><b style="color:#FFFFFF"><?php echo t('geral.score')?></b></td>
            <td width="70" align="center"><b style="color:#FFFFFF"><?php echo t('geral.vila')?></b></td>
			<td width="125" align="center">&nbsp;</td>
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
					historico_guild a
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
        <td width="30" align="center">
			
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
      		<b class="linkTopo <?php echo $posRanking ?>" style="font-size: 13px; <?php echo $posRanking2 ?>"><?php echo $r['nome_guild'] ?></b>
	   </td>
        <td width="70" align="center"><p><?php echo $r['level_guild'] == 26 ? 25 :  $r['level_guild']?></p></td>
        <td width="70" align="center"><p><?php echo $r['score_guild'] ?></p></td>
        <td width="70" align="center"><img src="<?php echo img() ?>layout/bandanas/<?php echo $r['id_vila'] ?>.png" width="48" height="24" /></td>
		<td width="125" align="center"><a id="<?php echo $r['id_guild'] ?>" class="button b-detalhe"><?php echo t('botoes.detalhes')?></a></td>

      </tr>
	  <tr height="4"></tr>
	  <tr <?php echo $cor ?> id="t-detalhe-player-<?php echo $r['id_guild'] ?>" style="display: none">
		<td colspan="7">
			<div id="d-detalhe-player-<?php echo $r['id_guild'] ?>" style="display: block"><br />
				<b style="font-size:13px;" class="verde">Membros da Organização</b><br /><br />
				<?php 
				$qMembros = Recordset::query("SELECT nome, level, id_graduacao, score, id_classe,id_vila FROM historico_ninja WHERE id_player in (".$r['id_players'].") AND round=15");
				while($rMembros = $qMembros->row_array()) {
				?>	
				<div style="float: left; width: 180px">
					<img src="<?php echo img() ?>/layout<?php echo LAYOUT_TEMPLATE?>/dojo/<?php echo $rMembros['id_classe'] ?><?php echo LAYOUT_TEMPLATE=="_azul" ? ".jpg":".png"?>" width="126" height="44" /><br />
					<b class="azul" style="font-size:13px;"><?php echo $rMembros['nome']?></b><br />
					<b><?php echo graduation_name($rMembros['id_vila'],$rMembros['id_graduacao'])?> Lvl. <?php echo $rMembros['level']?></b><br />
					<b class="verde">Score: <?php echo $rMembros['score']?></b>
				</div>	
				<?php
				}
				?>
			</div>
		</td>		
	   </tr> 			
      <?php
			}
	  ?>
    </table>
</form>