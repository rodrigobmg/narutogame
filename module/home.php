<?php
	$rnd_rank = rand(0, 3);
?>
<?php 
	$totalPlayers = Recordset::query("SELECT SUM(total) as t FROM estatistica_player", true)->row()->t;
?>
<script>
function do_home_rank(v) {
	$('.rank').hide();
	$('.rank_' + v).show();
};

$(document).ready(function(){
   $("#vila-seletor a").click(function(){
      var id =  $(this).attr('id');
      id = id.split('_');
      $("#areaKages div").hide(); 
      $("#areaKages #kage_"+id[1]).show();
   });
});
</script>
<?php /*
<!--<div id="banner-home">
    <!--<script type="text/javascript">
		AC_FL_RunContent('codebase','https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','712','height','234','title','Banner - Home','src','<?php echo img() ?>layout/banners/home2','wmode','transparent','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','<?php echo img() ?>layout/banners/home2' ); //end AC code
		</script>
    <noscript>
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="712" height="234" title="Banner - Home">
        <param name="movie" value="<?php echo img() ?>layout/banners/home2.swf" />
        <param name="quality" value="high" />
        <param name="bgcolor" value="#ffffff" />
        <param name="play" value="true" />
        <param name="loop" value="true" />
        <param name="wmode" value="transparent" />
        <param name="scale" value="showall" />
        <param name="menu" value="false" />
        <param name="devicefont" value="false" />
        <param name="salign" value="" />
        <param name="allowScriptAccess" value="sameDomain" />
    </object>
    </noscript>-->
<!--</div>-->
*/ ?>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<!-- NG - Home -->
	<ins class="adsbygoogle"
		 style="display:inline-block;width:728px;height:90px"
		 data-ad-client="ca-pub-9166007311868806"
		 data-ad-slot="1576868973"></ins>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script><br />

<div id="noticias-estatisticas">
	<div id="areaNoticias">
      	<?php require("action/home_noticias.php") ?>
	</div>
    <div id="areaEstatisticas">
    <div class="areaTitulos2"><p>Estatísticas Ninja</p></div>
        <table style="width: 340px !important" border="0" align="center" cellpadding="0" cellspacing="0" style="vertical-align:middle;">
          <tr>
            <td id="cnRankingH" align="center"><?php echo t('home.h1');?></td>
          </tr>
		</table>
			<div class="area-nav2">
        		<strong style="color:#FFFFFF; font-size:14px;"><?php echo sprintf(t('home.h2'), $totalPlayers) ?></strong>
				<a href="javascript:void(0);" style="color:#fd7f10" onclick="homeRanking(-1)"><div class="esq-nav"><img src="<?php echo img();?>layout/home/left.png" /></div></a> <a href="javascript:void(0);" onclick="homeRanking(1)"><div class="dir-nav"><img src="<?php echo img();?>layout/home/right.png" /></div></a>
			</div>
	</div>
</div>
<div id="kages-ranking">
	<div id="madeiraTitulo"><p>Kages & Vilas</p></div>
    
				<?php
			$qVilas = Recordset::query("
				SELECT * FROM estatistica_vila
				ORDER BY id_vila ASC	
			");	
        ?>
    <div id="vila-seletor">
		<div style="width:36px; height: 176px; float: left; position: absolute; right: 40px; margin-top: 4px;">
			<a id="show_1"><img id="vilaK" style="margin:1px 0 1px 0; cursor:pointer" src="https://narutogame.com.br/images/layout/home/vilas2/1.jpg" ></a>
			<a id="show_2"><img id="vilaK" style="margin:1px 0 1px 0; cursor:pointer" src="https://narutogame.com.br/images/layout/home/vilas2/2.jpg" ></a>
			<a id="show_3"><img id="vilaK" style="margin:1px 0 1px 0; cursor:pointer" src="https://narutogame.com.br/images/layout/home/vilas2/3.jpg" ></a>
			<a id="show_4"><img id="vilaK" style="margin:1px 0 1px 0; cursor:pointer" src="https://narutogame.com.br/images/layout/home/vilas2/4.jpg" ></a>
			<a id="show_5"><img id="vilaK" style="margin:1px 0 1px 0; cursor:pointer" src="https://narutogame.com.br/images/layout/home/vilas2/5.jpg" ></a>
			<a id="show_6"><img id="vilaK" style="margin:1px 0 1px 0; cursor:pointer" src="https://narutogame.com.br/images/layout/home/vilas2/6.jpg" ></a>
		</div>
		<div style="width:36px; height: 176px; float: right; position: absolute; right: 11px; margin-top: 4px;">
			<a id="show_7"><img id="vilaK" style="margin:1px 0 1px 0; cursor:pointer" src="https://narutogame.com.br/images/layout/home/vilas2/7.jpg" ></a>
			<a id="show_8"><img id="vilaK" style="margin:1px 0 1px 0; cursor:pointer" src="https://narutogame.com.br/images/layout/home/vilas2/8.jpg" ></a>
			<a id="show_9"><img id="vilaK" style="margin:1px 0 1px 0; cursor:pointer" src="https://narutogame.com.br/images/layout/home/vilas2/9.jpg" ></a>
			<a id="show_10"><img id="vilaK" style="margin:1px 0 1px 0; cursor:pointer" src="https://narutogame.com.br/images/layout/home/vilas2/10.jpg" ></a>
			<a id="show_11"><img id="vilaK" style="margin:1px 0 1px 0; cursor:pointer" src="https://narutogame.com.br/images/layout/home/vilas2/11.jpg" ></a>
			<a id="show_12"><img id="vilaK" style="margin:1px 0 1px 0; cursor:pointer" src="https://narutogame.com.br/images/layout/home/vilas2/12.jpg" ></a>
		</div>
    </div>
    <?php
    
        $qKages = Recordset::query("	
                SELECT
                    a.*,
                    v.kage 
                FROM 
                    ranking a
                JOIN vila as v on v.id = a.id_vila 	
                WHERE posicao_vila=1
                     
        ");				  
	?>
    <?php $display = "display: none" ?>
    <div id="areaKages">
	<?php while($r = $qKages->row_array()): ?>
	    <div id="kage_<?php echo $r['id_vila']; ?>" style="background:url(<?php echo img() ?>layout/home/kages-<?php echo $r['id_vila']; ?>.jpg); <?php if ($r['id_vila']>1) echo $display ?>">
            <span id="pkage">
            	<img src="<?php echo img();?>layout/home/<?php echo $r['id_classe']; ?>.jpg" />
                <span class="pInfo">
    	            <strong style="font-size: 22px; color:#e3eda3"><?php echo $r['nome']; ?></strong><br />
	                <span style="font-weight:normal"><?php echo $r['kage']; ?> - Level <?php echo $r['level']; ?></span>
                </span>
            </span>
            <span class="vInfo">
				<?php foreach($qVilas->result_array() as $rV): ?>
                    <?php if($r['id_vila'] == $rV['id_vila']) echo $rV['total_players'] .' '. t('home.h8')?>
                <?php endforeach ?>
            </span>
    	</div>

    <!-- FIM VILA  <?php echo $r['id_vila']; ?> -->
<?php endwhile; ?>
	</div>
</div>
    <div id="areaFacebook">
    	<div class="areaTitulos3"><p>Facebook</p></div>
        <div id="bg-face"><div class="fb-page" data-href="https://www.facebook.com/narutogamebr" data-width="340" data-height="280" data-small-header="false" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/narutogamebr"><a href="https://www.facebook.com/narutogamebr">Naruto Game</a></blockquote></div></div></div>
    </div>
    <div id="areaRankings">
    <div class="areaTitulos2"><p>Ranking de
    	<select onchange="do_home_rank(this.value)" class="select-box-home">
			<option value="0" <?php echo $rnd_rank == 0 ? 'selected="selected"' : '' ?>><?php echo t('home.h3')?></option>
			<option value="1" <?php echo $rnd_rank == 1 ? 'selected="selected"' : '' ?>><?php echo t('home.h4')?></option>
			<option value="2" <?php echo $rnd_rank == 2 ? 'selected="selected"' : '' ?>><?php echo t('home.h6')?></option>
			<option value="3" <?php echo $rnd_rank == 3 ? 'selected="selected"' : '' ?>><?php echo t('home.h5')?></option>
		</select>
        </p>
	</div>
          <!-- RANK DE ORGANIZAçÃO -->
            <?php
                $qGuilds = Recordset::query("select rg.*, (select nome from guild where id_player = rg.id_player AND removido='0' LIMIT 1) as nome_guild from ranking_guild as rg order by posicao_geral LIMIT 8");	
            ?>
			<table style="width: 355px !important" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-left:5px;" id="rankings-home">
                <?php 
                    while($rG = $qGuilds->row_array()) {
                    $cor = ++$cn % 2 ? "cor_sim" : "cor_nao";   
            	?>
				<tr height="29" class="rank rank_0 <?php echo $cor; ?>" style="display: none">
					<td width="40" align="center"  style="color:#e3eda3"><span style="color:#c0804e; font-weight:normal"><?php echo $rG['posicao_geral']; ?>º</span></td>
					<td width="190" align="left"  style="color:#e3eda3"><b><?php echo $rG['nome_guild']; ?></b></td>
					<td width="60" align="left"  style="color:#e3eda3"><span style="color:#c0804e; font-weight:normal"><?php echo $rG['pontos']; ?> pts.</span></td>
					<td width="60" align="center"  style="color:#e3eda3"><img src="<?php echo img() ?>layout/bandanas/<?php echo $rG['id_vila']; ?>.png" width="48" height="24" /></td>
				</tr>
                <tr height="3" class="rank rank_0" style="display: none"></tr>
            <?php }	?>
          <!-- RANK DE ORGANIZAçÃO -->
          
          <!-- RANK DE EQUIPE -->
          
            <?php
                $qEquipes = Recordset::query("select re.*, (select nome from equipe where id_player = re.id_player and removido='0' LIMIT 1) as nome_equipe from ranking_equipe as re order by posicao_geral LIMIT 8")	
            ?>
            <?php 
                $cn = 0;
                while($rE = $qEquipes->row_array()) {
                $cor = ++$cn % 2 ? "cor_sim" : "cor_nao";   
            ?>
                <tr class="rank rank_1 <?php echo $cor; ?>" style="display: none" height="29" valign="top">
                    <td width="40" align="center" style="color:#e3eda3"><span style="color:#c0804e; font-weight:normal"><?php echo $rE['posicao_geral']; ?>º</span></td>
                    <td width="190" align="left" style="color:#e3eda3"><b><?php echo $rE['nome_equipe']; ?></b></td>
                    <td width="60" align="left" style="color:#e3eda3"><span style="color:#c0804e; font-weight:normal"><?php echo $rE['pontos']; ?> pts.</span></td>
                    <td width="60" align="center" style="color:#e3eda3"><img src="<?php echo img() ?>layout/bandanas/<?php echo $rE['id_vila']; ?>.png" width="48" height="24" /></td>
                </tr>
                <tr height="3" class="rank rank_1" style="display: none"></tr>
            <?php 
            } 
            ?>
        <!-- RANK DE EQUIPE -->
        
        <!-- RANK DE CONQUISTA -->
            <?php
                $qConquista = Recordset::query("select * from ranking_conquista order by posicao_geral LIMIT 16")	
            ?>
            <?php $cn = 0; ?>
            <?php while($rC = $qConquista->row_array()): ?>
                <?php if(!($cn % 2)): ?>
                <tr class="rank rank_2" style="display: none">
                <?php endif; ?>
                <?php
                    $cor = ++$cn % 2 ? "cor_sim" : "cor_nao";
                ?>
                <td class="<?php echo $cor; ?>" width="38" height="29" align="center">
                    <img src="<?php echo img() ?>layout/home/<?= $rC['id_classe']; ?>.jpg" alt="<?= $rC['nome']; ?>" width="28" height="28" />
                </td>
                <td class="<?php echo $cor; ?>" width="130" height="29" align="left" style="color:#e3eda3">
                    <span style="color:#c0804e; font-weight:normal"><?php echo $rC['posicao_geral']; ?>º</span> <strong><?= $rC['nome']; ?></strong><br />
                    <span style="color:#c0804e; font-weight:normal">
                        Score <?php echo $rC['pontos']; ?> pts. 
                    </span>
                </td>
                <?php if(!($cn % 2)): ?>
                </tr>
                <tr height="3" class="rank rank_2" style="display: none"></tr>
                <?php endif; ?>
            <?php endwhile; ?>
        <!-- RANK DE CONQUISTA -->
        
        <!-- RANK DE ESTUDO NINJA -->
        
<?php
                $qEstudo = Recordset::query("select * from ranking_estudo_ninja order by posicao_geral LIMIT 16", true);
            ?>
            <?php $cn = 0; ?>
            <?php foreach($qEstudo->result_array() as $rE): ?>
                <?php if(!($cn % 2)): ?>
                <tr class="rank rank_3 <?php echo $cor ? "cor_sim" : "cor_nao" ?>" style="display: none">
                <?php endif; ?>
                <?php
                    $cor = ++$cn % 2 ? "cor_sim" : "cor_nao";
                ?>
                <td width="38" height="29" align="center">
                    <img src="<?php echo img() ?>layout/home/<?= $rE['id_classe']; ?>.jpg" alt="<?= $rE['nome']; ?>" width="28" height="28" />
                </td>
                <td width="130" height="29" align="left" style="color:#e3eda3">
                    <span style="color:#c0804e; font-weight:normal"><?php echo $rE['posicao_geral']; ?>º</span> <strong><?= $rE['nome']; ?></strong><br />
                    <span style="color:#c0804e; font-weight:normal">
                        <?php echo sprintf(t('home.h7'), $rE['pontos_estudo_ninja']) ?>
                    </span>
                </td>
                <?php if(!($cn % 2)): ?>
                </tr>
                <tr height="3" class="rank rank_3" style="display: none"></tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </table>
        <!-- RANK DE ESTUDO NINJA -->
    </div>

<!-- ****************************** -->

<script>
$(document).ready(function () {
    var current = 0;
    var max     = $('#vila-seletor a').length - 1;
	do_home_rank(<?php echo $rnd_rank ?>);

    setInterval(function () {
        $($('#vila-seletor a')[current]).trigger('click');

        current++;
        if(current > max) {
            current = 0;
        }
    }, 4000);
});
</script>
