<?php
	$_POST['vila'] = !isset($_POST['vila']) ? $basePlayer->getAttribute('id_vila') : (int)decode($_POST['vila']);
	$_POST['graduacao'] = !isset($_POST['graduacao']) ? "00" : (int)decode($_POST['graduacao']);
	$_POST['from'] = !isset($_POST['from']) ? 0 : decode($_POST['from']);
	$_POST['online'] = !isset($_POST['online']) ? "todos" : ($_POST['online']);

	if(!is_numeric($_POST['vila']) || !is_numeric($_POST['from']) || !is_numeric($_POST['graduacao'])) {
		redirect_to("negado");
	}

	$where = isset($_POST['vila']) && is_numeric($_POST['vila']) && $_POST['vila'] > 0 ? " AND a.id_vila=" . $_POST['vila'] : "";
	$where .= isset($_POST['graduacao']) && is_numeric($_POST['graduacao']) && $_POST['graduacao'] > 0 ? " AND a.id_graduacao=" . $_POST['graduacao'] : "";
	$where .= isset($_POST['nome']) && $_POST['nome'] ? " AND a.nome LIKE '%" . addslashes($_POST['nome']) . "%'" : "";
?>
<div class="titulo-secao"><p><?php echo t('titulos.rank_4x4'); ?></p></div>
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

    <table width="730" border="0" cellpadding="0" cellspacing="2">
      <tr>
        <td height="49" align="left" colspan="7" class="subtitulo-home">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:#FFFFFF"><?php echo t('ranks.filtros'); ?></b></td>
        </tr>
      <tr >
        <td height="34" align="center">
		<b style="font-size:16px"><?php echo t('ranks.vila'); ?></b><br />
		<select name="vila" id="vila">
          <option value="<?php echo encode("00") ?>">Geral</option>
          <?php
          	$qVila = Recordset::query("SELECT *, nome_".Locale::get()." AS nome FROM vila WHERE inicial='1'", true);

			foreach($qVila->result_array() as $rVila) {
				$selected = $_POST['vila'] == $rVila['id'] ? "selected='selected'" : "";
				echo "<option value='" . encode($rVila['id']) . "' $selected>" . htmlentities($rVila['nome']) . "</option>";
			}
		  ?>
        </select>
		</td>
        <td align="center">
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
        <td  align="center">
			<b style="font-size:16px"><?php echo t('ranks.posicao'); ?></b><br />
			<select name="from" id="from">
			 <?php
				$rTotal = Recordset::query("
					SELECT
						COUNT(a.id) as total

					FROM
						ranking_4x4 a

					WHERE 1=1
						 $where
				")->row_array();

				for($f = 0; $f <= $rTotal['total']; $f += 50) {
					$selected = $_POST['from'] == $f ? "selected='selected'" : "";
					echo "<option value='" . encode($f) . "' $selected>" . ($f + 1) . " ".t('geral.ate')." " . ($f + 50) . "</option>";
				}
			?>
       		</select>
		</td>
        <td  height="34" align="center">
			<b style="font-size:16px"><?php echo t('ranks.nome'); ?></b><br />
			<input type="text" name="nome" value="<?php echo isset($_POST['nome']) ? addslashes($_POST['nome']) : '' ?>" />
       </td>
	   <td  height="34" align="center">
			<b style="font-size:16px">Online</b><br />
			<select name="online" id="online">
				<option value="todos" <?php echo $_POST['online'] == "todos" ? 'selected="selected"' : ''?> >Todos</option>
				<option value="online" <?php echo $_POST['online'] == "online" ? 'selected="selected"' : ''?>>Online</option>
       		</select>
       </td>
        <td width="80"  align="center"><input type="submit" class="button" value="<?php echo t('geral.filtrar')?>" /></td>
      </tr>
    </table>
    <br />
<table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="110" align="center">&nbsp;</td>
            <td width="90" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.posicao'); ?></b></td>
            <td width="160" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.nome'); ?></b></td>
            <td width="50" align="center"><b style="color:#FFFFFF">Level</b></td>
            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.pontos'); ?></b></td>
            <td width="90" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.vila'); ?></b></td>
        	<td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('ranks.personagem'); ?></b></td>
          </tr>
        </table></td>
      </tr>
    </table>
<table width="730" border="0" cellpadding="2" cellspacing="0">
		<?php
			//$i = $basePlayer->getVIPItem(array(20185,20186,20187));

			$vantagem_espiao		= $basePlayer->hasItem(array(2019, 2020, 2021));
			$vantagem_conquista		= $basePlayer->vip;
			$vantagem_equipamento	= $basePlayer->hasItem(array(21880, 21881, 21882));
			$cn						= 0;

			$order = (int)$_POST['vila'] ? "posicao_vila" : "posicao_geral";
            $query = Recordset::query("
				SELECT 
					a.*
				FROM
					ranking_4x4 a
				WHERE 1=1

                     $where

                ORDER BY $order ". ($_POST['online'] == "online" ? "" : "ASC LIMIT {$_POST['from']}, 50" )."");

            while($r = $query->row_array()) {
				$posicao = isset($_POST['vila']) && is_numeric($_POST['vila']) && $_POST['vila'] > 0 ? $r['posicao_vila'] : $r['posicao_geral'];
                $cor	 = ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
                if($posicao <= 3){
                    $posRanking = "amarelo";
					$posRanking2 = "font-weight: bold";
                } else {
                    $posRanking = "";
					$posRanking2 = "";
                }
				($r['id_player'] == $basePlayer->id) ? $cor="class='cor_roxa'" : "";

			if($_POST['online']=="online"){
				if(!player_online($r['id_player'], false)){
					continue;
				}
			}

        ?>
      <tr <?php echo $cor ?>>
        <td width="110" align="center">
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
		</td>
        <td width="90" align="center" style="font-size: 13px; <?php echo $posRanking2 ?>" class="<?php echo $posRanking ?>"><?php echo $posicao ?>&ordm;</td>
        <td width="160" height="34" align="left" nowrap="nowrap">
		<a class="linkTopo <?php echo $posRanking ?>" style="font-size: 13px; <?php echo $posRanking2 ?>" href="javascript:void(0)" onclick="playerProfile('<?php echo urlencode(encode($r['id_player'])) ?>')"><?php echo player_online($r['id_player'], true)?><?php echo $r['nome'] ?></a>
        <br /><?php echo $r['titulo_' . Locale::get()] ?>
        </td>
        <td width="50" align="center"><p><?php echo $r['level'] ?></p></td>
        <td width="100" align="center"><p><?php echo $r['pontos'] ?></p></td>
        <td width="90" align="center"><img src="<?php echo img() ?>layout/bandanas/<?php echo $r['id_vila'] ?>.png" width="48" height="24" /></td>
        <td width="130" align="center"><img src="<?php echo img() ?>/layout<?php echo LAYOUT_TEMPLATE?>/dojo/<?php echo $r['id_classe'] ?><?php echo LAYOUT_TEMPLATE=="_azul" ? ".jpg":".png"?>" width="126" height="44" /></td>
      </tr>
	  <tr height="4"></tr>
      <?php
			}
	  ?>
    </table>
</form>
