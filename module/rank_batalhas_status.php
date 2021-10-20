<script>
function playerProfile(id) {
	window.open("?acao=profile&id=" + id, "", "width=830,height=700,statusbar=no,meniubar=no,toolbar=no,scrollbars=yes,resizable=yes");
}

function playerProfileTalent(id) {
	window.open("?acao=profile_talento&id=" + id, "", "width=830,height=700,statusbar=no,meniubar=no,toolbar=no,scrollbars=yes,resizable=yes");
}
</script>
<?php
	$_POST['vila'] = !isset($_POST['vila']) ? $basePlayer->getAttribute('id_vila') : (int)decode($_POST['vila']);
	$_POST['graduacao'] = !isset($_POST['graduacao']) ? $basePlayer->getAttribute('id_graduacao') : (int)decode($_POST['graduacao']);
	$_POST['from'] = !isset($_POST['from']) ? 0 : decode($_POST['from']);
	
	if(!is_numeric($_POST['vila']) || !is_numeric($_POST['from']) || !is_numeric($_POST['graduacao'])) {
		redirect_to("negado");	
	}

	$where = isset($_POST['vila']) && is_numeric($_POST['vila']) && $_POST['vila'] > 0 ? " AND a.id_vila=" . $_POST['vila'] : "";
	$where .= isset($_POST['graduacao']) && is_numeric($_POST['graduacao']) && $_POST['graduacao'] > 0 ? " AND a.id_graduacao=" . $_POST['graduacao'] : "";
	$periodo = isset($_POST['periodo']) && $_POST['periodo'] ? "". addslashes($_POST['periodo']) ."" : "";
	$order = isset($_POST['status']) && $_POST['status'] ? "". addslashes($_POST['status']) ."" : "";
?>
<div class="titulo-secao"><p><?php echo t('titulos.rank_batalhas'); ?></p></div>
<br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "5761438173";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- Ranking -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br />
<form method="post">

    <table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="49" align="left" colspan="6" class="subtitulo-home">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:#FFFFFF"><?php echo t('ranks.filtros'); ?></b></td>
        </tr>
      <tr >
        <td height="34" align="center"><b style="font-size:16px"><?php echo t('ranks.vila'); ?></b><br />        	
        	<select name="vila" id="vila">
        		<option value="<?php echo encode("00") ?>">Geral</option>
        		<?php
          	$qVila = Recordset::query("SELECT * , nome_".Locale::get()." AS nome FROM vila WHERE inicial='1'", true);
          	
			foreach($qVila->result_array() as $rVila) {
				$selected = $_POST['vila'] == $rVila['id'] ? "selected='selected'" : "";
				echo "<option value='" . encode($rVila['id']) . "' $selected>" . htmlentities($rVila['nome']) . "</option>";	
			}
		  ?>
       		</select>
		</td>
		<td  height="34">
			<b style="font-size:16px"><?php echo t('geral.graduacao'); ?></b><br />
			<select name="graduacao" id="graduacao">
			<option value="<?php echo encode("00") ?>">Geral</option>
			 <?php
				$qGraduacao = Recordset::query("SELECT * FROM graduacao", true);
				
				foreach($qGraduacao->result_array() as $rGraduacao) {
					$selected = $_POST['graduacao'] == $rGraduacao['id'] ? "selected='selected'" : "";
					echo "<option value='" . encode($rGraduacao['id']) . "' $selected>" . htmlentities(graduation_name($_POST['vila'] ? $_POST['vila'] : $basePlayer->getAttribute('id_vila')  , $rGraduacao['id'])) . "</option>";	
				}
			  ?>
			</select>
		</td>
        <td height="34"  align="center"><b style="font-size:16px"><?php echo t('ranks.posicao'); ?></b><br />
        	<select name="from" id="from">
        		<?php        
        	$rTotal = Recordset::query("
                SELECT
                    COUNT(a.id) as total
                
                FROM 
                    player_batalhas_status a 
					
                WHERE 1=1 
                     $where													
			")->row_array();
			
			for($f = 0; $f <= $rTotal['total']; $f += 50) {
				$selected = $_POST['from'] == $f ? "selected='selected'" : "";
				echo "<option value='" . encode($f) . "' $selected>" . ($f + 1) . " ".t('geral.ate')." " . ($f + 50) . "</option>";	
			}
		?>
       		</select></td>
        <td  align="center"><b style="font-size:16px">Status</b>
        	<br />
        	<select name="status" id="status">
        		<option value="vitorias_d" <?php echo $order=="vitorias_d" ? "selected='selected'": "" ;?>><?php echo t('ranks.vit_dojo'); ?></option>
        		<option value="vitorias" <?php echo $order=="vitorias" ? "selected='selected'": "" ;?>><?php echo t('ranks.vit_dojo_p'); ?></option>
        		<option value="vitorias_f" <?php echo $order=="vitorias_f" ? "selected='selected'": "" ;?>><?php echo t('ranks.vit_mapa'); ?></option>
        		<option value="derrotas_npc" <?php echo $order=="derrotas_npc" ? "selected='selected'": "" ;?>><?php echo t('ranks.der_dojo'); ?></option>
        		<option value="derrotas" <?php echo $order=="derrotas" ? "selected='selected'": "" ;?>><?php echo t('ranks.derrotas'); ?></option>
				<option value="derrotas_f" <?php echo $order=="derrotas_f" ? "selected='selected'": "" ;?>><?php echo t('ranks.der_dojo2'); ?></option>
        		<option value="empates" <?php echo $order=="empates" ? "selected='selected'": "" ;?>><?php echo t('ranks.empates'); ?></option>
        		<option value="fugas" <?php echo $order=="fugas" ? "selected='selected'": "" ;?>><?php echo t('ranks.fugas'); ?></option>
        		</select>
		</td>
		<td height="34" align="center"><b style="font-size:16px"><?php echo t('ranks.periodo'); ?><br />
		</b>
			
			<select name="periodo" id="periodo">
				<option value="diario" <?php echo $periodo=="diario" ? "selected='selected'" : ""?>><?php echo t('ranks.diario'); ?></option>
				<option value="semanal" <?php echo $periodo=="semanal" ? "selected='selected'" : ""?>><?php echo t('ranks.semanal'); ?></option>
				<option value="mensal" <?php  echo $periodo=="mensal" ? "selected='selected'" : ""?>><?php echo t('ranks.mensal'); ?></option>
				<option value="geral" <?php  echo $periodo=="geral" ? "selected='selected'" : ""?>><?php echo t('ranks.geral'); ?></option>
			</select>
			
		</td>
		<td  align="right"><input type="submit" value="<?php echo t('geral.filtrar')?>" class="button" /></td>
      </tr>
    </table>
    <br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="60" align="center">&nbsp;</td>
            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.nome'); ?></b></td>
            <td width="60" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.vit_dojo2'); ?></b></td>
            <td width="60" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.vit_dojop2'); ?></b></td>
            <td width="60" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.vitorias'); ?></b></td>
            <td width="60" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.derrotas_dojo'); ?></b></td>
			 <td width="60" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.derrotas_dojo_pvp'); ?></b></td>
        	<td width="60" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.derrotas_mapa'); ?></b></td>
			<td width="60" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.empates'); ?></b></td>
			<td width="60" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.fugas'); ?></b></td>
          </tr>
        </table></td>
      </tr>
    </table>
<table width="730" border="0" cellpadding="2" cellspacing="0">
		<?php
			
			switch($periodo){
				case 'diario':
					$periodo = "";
					$order = "ORDER BY ". $order ." DESC";
				break;
				case 'semanal':
					$periodo = "_semana";
					$order = "ORDER BY ". $order ."". $periodo ." DESC";
				break;
				case 'mensal':
					$periodo = "_mes";
					$order = "ORDER BY ". $order ."". $periodo ." DESC";
				break;
				case 'geral':
					$periodo = "_geral";
					$order = "ORDER BY ". $order ."". $periodo ." DESC";
				break;	
			}
			//$i = $basePlayer->getVIPItem(array(20185,20186,20187));
			
			$vantagem_espiao	= $basePlayer->hasItem(array(2019, 2020, 2021));
			$vantagem_conquista = $basePlayer->vip;
			$vantagem_equipamento	= $basePlayer->hasItem(array(21880, 21881, 21882));
			$cn					= 0;
			
			//$order = (int)$_POST['vila'] ? "posicao_vila" : "posicao_geral";
		    $query = Recordset::query("
				SELECT 
					a.*,
					pn.nome
				FROM 
					player_batalhas_status a
					
				JOIN player_nome AS pn ON pn.id_player = a.id_player	
				
				WHERE 1=1
               
                $where
                
               $order LIMIT {$_POST['from']}, 50");

			while($r = $query->row_array()) {
				$cor			= ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
				$posicao	= $cn + $_POST['from'];

				if($cn <= 3){
                    $posRanking = "amarelo";
					$posRanking2 = "font-weight: bold";
                } else {
                    $posRanking = "";
					$posRanking2 = "";
                }

				($r['id_player'] == $basePlayer->id) ? $cor="class='cor_roxa'" : "";
        ?>
      <tr <?php echo $cor ?>>
        <td width="60" align="center" style="font-size: 13px; <?php echo $posRanking2 ?>" class="<?php echo $posRanking ?>"><?php echo $posicao ?>º<br />
		<img src="<?php echo img() ?>layout/bandanas/<?php echo $r['id_vila'] ?>.png" width="48" height="24" />
        		<p>
		<a href="?secao=torneio&player=<?php echo $r['id_player'] ?>" alt="Ver Torneio Ninja" title="<?php echo t('geral.g23')?>" style="text-decoration:none">
			<img src="<?php echo img() ?>layout<?php echo LAYOUT_TEMPLATE?>/bestseller.png" border="0" alt="<?php echo t('geral.g23')?>" title="<?php echo t('geral.g23')?>" align="absmiddle" />
		</a>
        <?php if($vantagem_conquista): ?>
			<a href="?secao=conquistas&id_player=<?php echo $r['id_player'] ?>" alt="<?php echo t('geral.g24')?>" title="<?php echo t('geral.g24')?>" style="text-decoration:none">
				<img src="<?php echo img() ?>layout<?php echo LAYOUT_TEMPLATE?>/trofeu.png" border="0" alt="<?php echo t('geral.g24')?>" title="<?php echo t('geral.g24')?>" align="absmiddle" />
			</a>
		<?php endif; ?>	
		<?php if($vantagem_espiao): ?>
			<a href="javascript:void(0)" onclick="playerProfileTalent('<?php echo urlencode(encode($r['id_player'])) ?>')" alt="<?php echo t('geral.g25')?>" style="text-decoration:none" title="Ver talentos ninja">
				<img src="<?php echo img() ?>layout<?php echo LAYOUT_TEMPLATE?>/arvore.png" border="0" alt="<?php echo t('geral.g25')?>" title="<?php echo t('geral.g25')?>" align="absmiddle" />
			</a>
		<?php endif; ?>		
		<?php if($vantagem_equipamento): ?>
			<a href="javascript:void(0)" onclick="playerProfileEquip('<?php echo urlencode(encode($r['id_player'])) ?>')" alt="<?php echo t('geral.g26')?>" style="text-decoration:none" title="Ver equipamentos ninja">
				<img src="<?php echo img() ?>layout<?php echo LAYOUT_TEMPLATE?>/equips.png" border="0" alt="<?php echo t('geral.g26')?>" title="<?php echo t('geral.g26')?>" align="absmiddle" />
			</a>
		<?php endif; ?>				
        </p>
        </td>
        <td width="140" height="34" align="center" nowrap="nowrap">
        <a class="linkTopo <?php echo $posRanking ?>" style="font-size: 13px; <?php echo $posRanking2 ?>" href="javascript:void(0)" onclick="playerProfile('<?php echo urlencode(encode($r['id_player'])) ?>')"><?php echo player_online($r['id_player'], true)?><?php echo $r['nome'] ?></a><br />
		<img style="padding-top:3px" src="<?php echo img() ?>/layout<?php echo LAYOUT_TEMPLATE?>/dojo/<?php echo $r['id_classe'] ?><?php echo LAYOUT_TEMPLATE=="_azul" ? ".jpg":".png"?>" width="126" height="44" />
		</td>
		<td width="60" align="center"><p><?php echo $r['vitorias_d'. $periodo .''] ?></p></td>
		<td width="60" align="center"><p><?php echo $r['vitorias'.$periodo.''] ?></p></td>
		<td width="60" align="center"><p><?php echo $r['vitorias_f'.$periodo.''] ?></p></td>
        <td width="60" align="center"><p><?php echo $r['derrotas_npc'.$periodo.''] ?></p></td>
		<td width="60" align="center"><p><?php echo $r['derrotas'.$periodo.''] ?></p></td>
		<td width="60" align="center"><p><?php echo $r['derrotas_f'.$periodo.''] ?></p></td>
        <td width="60" align="center"><p><?php echo $r['empates'.$periodo.''] ?></p></td>
        <td width="60" align="center"><p><?php echo $r['fugas'.$periodo.''] ?></p></td>
      </tr>
	  <tr height="4"></tr>
      <?php
			}
	  ?>
    </table>
</form>