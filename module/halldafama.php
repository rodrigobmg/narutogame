<script type="text/javascript" src="js/jquery.tools.min.js"></script>
<style>
a:active {
	outline:none;
}
:focus {
	-moz-outline-style:none;
}
div.wrap {
	width:730px;
	margin-bottom:40px;
}
.wrap .pane {
	position:relative;
	top:20px;
	display:none;
	font-size:11px;
	text-align:left;
}
.wrap .pane p {
	font-size:38px;
	margin:-10px 0 -20px 0;
	text-align:right;
}
</style>
<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p><?php echo t('halldafama.h1')?></p></div>
<table width="730" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="730"><div class="wrap">
        <!-- the tabs -->
        <ul class="tabs">
          <li><a href="#">Round Beta</a></li>
          <li><a href="#">Round <?php echo t('halldafama.h3')?></a></li>
          <li><a href="#">Round 1</a></li>
          <li><a href="#">Round <?php echo t('halldafama.h3')?> (R1)</a></li>
          <li><a href="#">Round 2</a></li>
        
        </ul>
        
		<div class="msg_gai">
        		<div class="msg">
                		<div class="msg_text" style="background:url(<?php echo img() ?>layout/msg/<?php echo $village = $_SESSION['basePlayer'] ? $basePlayer->id_vila : rand(1, 8);?>/1.png); background-repeat: no-repeat;">
							<b><?php echo t('halldafama.h1')?> <?php echo t('halldafama.h4')?> Naruto Game,</b>
							<p><?php echo t('halldafama.h2')?>
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
                            <a href="?secao=halldafama_r19" class="linkTopo">Round 19</a><br />
                            <a href="?secao=halldafama_r20" class="linkTopo">Round 20</a><br />
                            <a href="?secao=halldafama_r21" class="linkTopo">Round 21</a><br />
                            <a href="?secao=halldafama_r22" class="linkTopo">Round 22</a><br />
                            <a href="?secao=halldafama_r23" class="linkTopo">Round 23</a><br />
                            <a href="?secao=halldafama_r24" class="linkTopo">Round 24</a><br />
                            <a href="?secao=halldafama_r25" class="linkTopo">Round 25</a><br />
                            </div>
							<div class="break"></div>
							</p>	
						</div>
                </div>
        </div>           
        <br />
        <table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="49" align="left" colspan="2" class="subtitulo-home"><b style="color:#fff; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo t('halldafama.h5')?> ( Round 2 )</b></td>
          </tr>
          <tr>
            <td width="34" align="center" bgcolor="#413625"><img src="<?php echo img() ?>layout/trophy.png" width="16" height="16" /></td>
            <td width="696" height="25" align="left" bgcolor="#413625"><?php echo t('halldafama.h6')?> XBOX360 + <?php echo t('halldafama.h7')?> Naruto Game + Vip Sanin</td>
          </tr>
		  <tr height="4"></tr>
          <tr bgcolor="#251a13">
            <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/trophy-silver.png" width="16" height="16" /></td>
            <td height="25" align="left" bgcolor="#251a13"><?php echo t('halldafama.h6')?> PSP + <?php echo t('halldafama.h7')?> Naruto Game + Vip Sanin</td>
          </tr>
		   <tr height="4"></tr>
          <tr>
            <td align="center" bgcolor="#413625"><img src="<?php echo img() ?>layout/trophy-bronze.png" width="16" height="16" /></td>
            <td height="25" align="left" bgcolor="#413625"><?php echo t('halldafama.h6')?> PS2 + <?php echo t('halldafama.h7')?> Naruto Game + Vip Sanin</td>
          </tr>
		   <tr height="4"></tr>
          <tr bgcolor="#251a13">
            <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
            <td height="25" align="left" bgcolor="#251a13"><?php echo t('halldafama.h7')?> Naruto Game + Vip Sanin</td>
          </tr>
		   <tr height="4"></tr>
          <tr>
            <td align="center" bgcolor="#413625"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
            <td height="25" align="left" bgcolor="#413625">Vip Jounin</td>
          </tr>
		   <tr height="4"></tr>
          <tr bgcolor="#251a13">
            <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
            <td height="25" align="left" bgcolor="#251a13">Vip Genin</td>
          </tr>
        </table>
        <!-- tab "panes" -->
        <div class="pane">
          <!-- the tabs -->
          <ul class="tabs">
            <li><a href="#">Kages</a></li>
            <li><a href="#">Folha</a></li>
            <li><a href="#">Areia</a></li>
            <li><a href="#">Nevoa</a></li>
            <li><a href="#">Som</a></li>
            <li><a href="#">Akatsuki</a></li>
            <li><a href="#">Nuvem</a></li>
            <li><a href="#">Chuva</a></li>
            <li><a href="#">Pedra</a></li>
            <li><a href="#">Extras</a></li>
          </ul>
          <!-- tab "panes" -->
          <div class="pane">
          
            <table width="730" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr bgcolor="#413625">
                <td width="70" align="center"><img src="<?php echo img() ?>layout/trophy.png" width="16" height="16" /></td>
                <td width="100" align="center"><img src="<?php echo img() ?>layout/bandanas/1.jpg" /></td>
                <td width="140" align="center">Knight of konoha</td>
                <td width="100" align="center" >47</td>
                <td width="110" align="center" >Sanin</td>
                <td width="110" align="center" >224703</td>
                <td width="100" align="center" ><img src="<?php echo img() ?>layout/dojo/78.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/6.jpg" /></td>
                <td align="center">Kain Raziel</td>
                <td align="center">42</td>
                <td align="center">ANBU</td>
                <td align="center">187177</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/7.jpg" /></td>
                <td align="center">HoppE</td>
                <td align="center">47</td>
                <td align="center">Sanin</td>
                <td align="center">206255</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/3.jpg" /></td>
                <td align="center">lee cash</td>
                <td align="center">45</td>
                <td align="center">Sanin</td>
                <td align="center">189246</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/5.jpg" /></td>
                <td align="center">Scorpius</td>
                <td align="center">40</td>
                <td align="center">ANBU</td>
                <td align="center">171404</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/4.jpg" /></td>
                <td align="center">XxxGai SenseixxX</td>
                <td align="center">46</td>
                <td align="center">Sanin</td>
                <td align="center">193770</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/8.jpg" /></td>
                <td align="center">BankaiSama</td>
                <td align="center">41</td>
                <td align="center">ANBU</td>
                <td align="center">167709</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/2.jpg" /></td>
                <td align="center">Twister Hyuuga</td>
                <td align="center">45</td>
                <td align="center">Sanin</td>
                <td align="center">186115</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
            </table>
          </div>
          <div class="pane">
            <table width="730" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr bgcolor="#413625"> 
                <td width="70" align="center"><img src="<?php echo img() ?>layout/trophy.png" width="16" height="16" /></td>
                <td width="100" align="center">1&ordm;</td>
                <td width="140" align="center">Knight of konoha</td>
                <td width="100" align="center">47</td>
                <td width="110" align="center">Sanin</td>
                <td width="110" align="center">224703</td>
                <td width="100" align="center"><img src="<?php echo img() ?>layout/dojo/78.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/trophy-silver.png" width="16" height="16" /></td>
                <td align="center">2&ordm;</td>
                <td align="center">Frostezin</td>
                <td align="center">48</td>
                <td align="center">Sanin</td>
                <td align="center">219568</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/trophy-bronze.png" width="16" height="16" /></td>
                <td align="center">3&ordm;</td>
                <td align="center">Kay kyu</td>
                <td align="center">46</td>
                <td align="center">Sanin</td>
                <td align="center">216540</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">4&ordm;</td>
                <td align="center">DarkIori</td>
                <td align="center">43</td>
                <td align="center">ANBU</td>
                <td align="center">180434</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">5&ordm;</td>
                <td align="center">SlayerBlade</td>
                <td align="center">39</td>
                <td align="center">ANBU</td>
                <td align="center">180290</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">6&ordm;</td>
                <td align="center">Hyuuga Neji</td>
                <td align="center">41</td>
                <td align="center">ANBU</td>
                <td align="center">166688</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">7&ordm;</td>
                <td align="center">Rayfous</td>
                <td align="center">45</td>
                <td align="center">ANBU</td>
                <td align="center">166547</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">8&ordm;</td>
                <td align="center">ZeroUela</td>
                <td align="center">40</td>
                <td align="center">ANBU</td>
                <td align="center">144385</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">9&ordm;</td>
                <td align="center">Broken</td>
                <td align="center">42</td>
                <td align="center">ANBU</td>
                <td align="center">142266</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">10&ordm;</td>
                <td align="center">Chimera</td>
                <td align="center">38</td>
                <td align="center">ANBU</td>
                <td align="center">136731</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">11&ordm;</td>
                <td align="center">__Akatsuna__</td>
                <td align="center">41</td>
                <td align="center">ANBU</td>
                <td align="center">127883</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">12&ordm;</td>
                <td align="center">Felipe</td>
                <td align="center">33</td>
                <td align="center">Jounin</td>
                <td align="center">119610</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">13&ordm;</td>
                <td align="center">SilverstoneS</td>
                <td align="center">34</td>
                <td align="center">Jounin</td>
                <td align="center">116812</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">14&ordm;</td>
                <td align="center">*Fly Hyuuga**</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">116556</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">15&ordm;</td>
                <td align="center">raio elemental</td>
                <td align="center">44</td>
                <td align="center">ANBU</td>
                <td align="center">112463</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">16&ordm;</td>
                <td align="center">Faire</td>
                <td align="center">38</td>
                <td align="center">ANBU</td>
                <td align="center">111057</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">17&ordm;</td>
                <td align="center">Tickoo</td>
                <td align="center">33</td>
                <td align="center">Jounin</td>
                <td align="center">110906</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">18&ordm;</td>
                <td align="center">Toroah</td>
                <td align="center">33</td>
                <td align="center">Jounin</td>
                <td align="center">109926</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">19&ordm;</td>
                <td align="center">SlayerBolt</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">109074</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">20&ordm;</td>
                <td align="center">Lee_SJC</td>
                <td align="center">32</td>
                <td align="center">Jounin</td>
                <td align="center">108768</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">21&ordm;</td>
                <td align="center">Dam Hum</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">107408</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">22&ordm;</td>
                <td align="center">GM Malboa</td>
                <td align="center">20</td>
                <td align="center">Chunin</td>
                <td align="center">107350</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">23&ordm;</td>
                <td align="center">GBT</td>
                <td align="center">41</td>
                <td align="center">ANBU</td>
                <td align="center">105996</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/101.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">24&ordm;</td>
                <td align="center">Warlord</td>
                <td align="center">40</td>
                <td align="center">ANBU</td>
                <td align="center">105947</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/53.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">25&ordm;</td>
                <td align="center">\\\\_Fe_//</td>
                <td align="center">35</td>
                <td align="center">Jounin</td>
                <td align="center">105412</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
            </table>
          </div>
          <div class="pane">
            <table width="730" border="0" cellpadding="0" cellspacing="0">
               <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr bgcolor="#413625">                
                <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td width="100" align="center">1&ordm;</td>
                <td width="140" align="center">Twister Hyuuga</td>
                <td align="center" width="100">45</td>
                <td align="center" width="110">Sanin</td>
                <td align="center" width="110">186115</td>
                <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">2&ordm;</td>
                <td align="center">Shadiness</td>
                <td align="center">41</td>
                <td align="center">ANBU</td>
                <td align="center">170872</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">3&ordm;</td>
                <td align="center">Namikaze Loki</td>
                <td align="center">38</td>
                <td align="center">ANBU</td>
                <td align="center">149732</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">4&ordm;</td>
                <td align="center">morto25</td>
                <td align="center">41</td>
                <td align="center">ANBU</td>
                <td align="center">148380</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">5&ordm;</td>
                <td align="center">Loki the Ripper</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">141466</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">6&ordm;</td>
                <td align="center">OlhaLaOninhoDeTonGoiA</td>
                <td align="center">35</td>
                <td align="center">Jounin</td>
                <td align="center">133535</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">7&ordm;</td>
                <td align="center">Sabaku no trust</td>
                <td align="center">40</td>
                <td align="center">ANBU</td>
                <td align="center">123430</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/4.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">8&ordm;</td>
                <td align="center">JAuM_</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">109139</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">9&ordm;</td>
                <td align="center">Sabakuu no Gaara</td>
                <td align="center">35</td>
                <td align="center">Jounin</td>
                <td align="center">105985</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/4.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">10&ordm;</td>
                <td align="center">YURI GATINHO</td>
                <td align="center">38</td>
                <td align="center">ANBU</td>
                <td align="center">104531</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/4.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">11&ordm;</td>
                <td align="center">Cecilia Fernanda</td>
                <td align="center">33</td>
                <td align="center">Jounin</td>
                <td align="center">103333</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">12&ordm;</td>
                <td align="center">sombra_celha</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">96256</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">13&ordm;</td>
                <td align="center">Love Hinata</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">95519</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">14&ordm;</td>
                <td align="center">Kaguya Kigimaro</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">90272</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">15&ordm;</td>
                <td align="center">xX Jinchuuriki Xx</td>
                <td align="center">35</td>
                <td align="center">Jounin</td>
                <td align="center">88823</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">16&ordm;</td>
                <td align="center">Morgax</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">88229</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/4.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">17&ordm;</td>
                <td align="center">Crash</td>
                <td align="center">38</td>
                <td align="center">ANBU</td>
                <td align="center">87800</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">18&ordm;</td>
                <td align="center">_Hyuga_Neji_</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">85335</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">19&ordm;</td>
                <td align="center">Konoha_Dai_Senppuu</td>
                <td align="center">27</td>
                <td align="center">Jounin</td>
                <td align="center">83632</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">20&ordm;</td>
                <td align="center">Gaara do Goias</td>
                <td align="center">23</td>
                <td align="center">Chunin</td>
                <td align="center">73684</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/4.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">21&ordm;</td>
                <td align="center">Gaara Sam</td>
                <td align="center">26</td>
                <td align="center">Chunin</td>
                <td align="center">70656</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/4.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">22&ordm;</td>
                <td align="center">fabiosilva1992</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">70603</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/4.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">23&ordm;</td>
                <td align="center">Ichigo Bankai</td>
                <td align="center">27</td>
                <td align="center">Jounin</td>
                <td align="center">68990</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">24&ordm;</td>
                <td align="center">Suzumiya Haruka</td>
                <td align="center">24</td>
                <td align="center">Chunin</td>
                <td align="center">68855</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">25&ordm;</td>
                <td align="center">AlanGaara</td>
                <td align="center">27</td>
                <td align="center">Jounin</td>
                <td align="center">67921</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/4.png" /></td>
              </tr>
            </table>
          </div>
          <div class="pane">
            <table width="730" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr bgcolor="#413625">

                
                <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td width="100" align="center">1&ordm;</td>
                <td width="140" align="center">lee cash</td>
                <td align="center" width="100">45</td>
                <td align="center" width="110">Sanin</td>
                <td align="center" width="110">189246</td>
                <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">2&ordm;</td>
                <td align="center">Lyndis</td>
                <td align="center">42</td>
                <td align="center">ANBU</td>
                <td align="center">171392</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">3&ordm;</td>
                <td align="center">Fenix__</td>
                <td align="center">45</td>
                <td align="center">Sanin</td>
                <td align="center">165280</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/43.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">4&ordm;</td>
                <td align="center">Marck</td>
                <td align="center">35</td>
                <td align="center">ANBU</td>
                <td align="center">141530</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">5&ordm;</td>
                <td align="center">Khampa</td>
                <td align="center">34</td>
                <td align="center">Jounin</td>
                <td align="center">126687</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/75.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">6&ordm;</td>
                <td align="center">Kurosaki Obito</td>
                <td align="center">40</td>
                <td align="center">ANBU</td>
                <td align="center">124281</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">7&ordm;</td>
                <td align="center">ThomyLeeRola</td>
                <td align="center">35</td>
                <td align="center">ANBU</td>
                <td align="center">119499</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">8&ordm;</td>
                <td align="center">shiryu</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">111771</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">9&ordm;</td>
                <td align="center">II Maito Gai II</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">102171</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">10&ordm;</td>
                <td align="center">garou x</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">98202</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">11&ordm;</td>
                <td align="center">Rock lee hokage 8</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">93944</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">12&ordm;</td>
                <td align="center">Yoks Haku</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">90497</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/44.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">13&ordm;</td>
                <td align="center">Kaijyuu</td>
                <td align="center">26</td>
                <td align="center">Jounin</td>
                <td align="center">90022</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">14&ordm;</td>
                <td align="center">Zaraki kenpachi</td>
                <td align="center">28</td>
                <td align="center">Jounin</td>
                <td align="center">89706</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">15&ordm;</td>
                <td align="center">Sininho</td>
                <td align="center">26</td>
                <td align="center">Chunin</td>
                <td align="center">88931</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/59.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">16&ordm;</td>
                <td align="center">Dradivul</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">78992</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">17&ordm;</td>
                <td align="center">Efui</td>
                <td align="center">28</td>
                <td align="center">Jounin</td>
                <td align="center">78491</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">18&ordm;</td>
                <td align="center">Win</td>
                <td align="center">28</td>
                <td align="center">Jounin</td>
                <td align="center">77516</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">19&ordm;</td>
                <td align="center">Hyuuga Neji Desu</td>
                <td align="center">26</td>
                <td align="center">Jounin</td>
                <td align="center">77404</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">20&ordm;</td>
                <td align="center">Leviathan</td>
                <td align="center">25</td>
                <td align="center">Chunin</td>
                <td align="center">75405</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/44.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">21&ordm;</td>
                <td align="center">Ahou</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">72901</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">22&ordm;</td>
                <td align="center">_Ulquiorra_</td>
                <td align="center">26</td>
                <td align="center">Chunin</td>
                <td align="center">72135</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">23&ordm;</td>
                <td align="center">Jef__LEE</td>
                <td align="center">25</td>
                <td align="center">Chunin</td>
                <td align="center">71672</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">24&ordm;</td>
                <td align="center">A MorTi</td>
                <td align="center">25</td>
                <td align="center">Chunin</td>
                <td align="center">71434</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/110.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">25&ordm;</td>
                <td align="center">MASSACRE</td>
                <td align="center">23</td>
                <td align="center">Chunin</td>
                <td align="center">70023</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/101.png" /></td>
              </tr>
            </table>
          </div>
          <div class="pane">
            <table width="730" border="0" cellpadding="0" cellspacing="0">
               <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr bgcolor="#413625">
                <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td width="100" align="center">1&ordm;</td>
                <td width="140" align="center">HoppE</td>
                <td align="center" width="100">47</td>
                <td align="center" width="110">Sanin</td>
                <td align="center" width="110">206255</td>
                <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">2&ordm;</td>
                <td align="center">Kojiro Hyuuga</td>
                <td align="center">44</td>
                <td align="center">ANBU</td>
                <td align="center">180994</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">3&ordm;</td>
                <td align="center">Link Uchiha</td>
                <td align="center">42</td>
                <td align="center">ANBU</td>
                <td align="center">179813</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">4&ordm;</td>
                <td align="center">Kozure Okami</td>
                <td align="center">45</td>
                <td align="center">ANBU</td>
                <td align="center">178107</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">5&ordm;</td>
                <td align="center">Dani</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">116069</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/91.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">6&ordm;</td>
                <td align="center">Rousselet</td>
                <td align="center">37</td>
                <td align="center">ANBU</td>
                <td align="center">115770</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">7&ordm;</td>
                <td align="center">inooo</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">109909</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">8&ordm;</td>
                <td align="center">ytalo lindo</td>
                <td align="center">40</td>
                <td align="center">ANBU</td>
                <td align="center">109237</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">9&ordm;</td>
                <td align="center">Kinimaro</td>
                <td align="center">32</td>
                <td align="center">Jounin</td>
                <td align="center">102850</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">10&ordm;</td>
                <td align="center">Dark Cloud SaikyouAnimes</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">102605</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">11&ordm;</td>
                <td align="center">[_Karim_Benzema_]</td>
                <td align="center">44</td>
                <td align="center">ANBU</td>
                <td align="center">99348</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">12&ordm;</td>
                <td align="center">Byakungan</td>
                <td align="center">33</td>
                <td align="center">Jounin</td>
                <td align="center">95634</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">13&ordm;</td>
                <td align="center">HELLISHER</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">94176</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/86.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">14&ordm;</td>
                <td align="center">Maito_Gai</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">91075</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">15&ordm;</td>
                <td align="center">ET Hyuuga</td>
                <td align="center">34</td>
                <td align="center">Jounin</td>
                <td align="center">90826</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">16&ordm;</td>
                <td align="center">Magnanimuss</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">89288</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">17&ordm;</td>
                <td align="center">Sangue nu Zooii</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">88465</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">18&ordm;</td>
                <td align="center">Rocke Lee</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">88435</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">19&ordm;</td>
                <td align="center">Bestaverde_dekonoha</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">85643</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">20&ordm;</td>
                <td align="center">Shangai</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">84870</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">21&ordm;</td>
                <td align="center">Lechuga Benji</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">83627</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">22&ordm;</td>
                <td align="center">SueDKiLLeR</td>
                <td align="center">28</td>
                <td align="center">Jounin</td>
                <td align="center">81232</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">23&ordm;</td>
                <td align="center">Jiraya o Ero Sennin</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">80946</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">24&ordm;</td>
                <td align="center">Kaguya Guerra</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">80391</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">25&ordm;</td>
                <td align="center">.Kimimaro.</td>
                <td align="center">27</td>
                <td align="center">Jounin</td>
                <td align="center">78886</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
            </table>
          </div>
          <div class="pane">
            <table width="730" border="0" cellpadding="0" cellspacing="0">
               <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr  bgcolor="#413625">
                <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td width="100" align="center" >1&ordm;</td>
                <td width="140" align="center">Kain Raziel</td>
                <td align="center" width="100">42</td>
                <td align="center" width="110">ANBU</td>
                <td align="center" width="110">187177</td>
                <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">2&ordm;</td>
                <td align="center">Yahito</td>
                <td align="center">42</td>
                <td align="center">ANBU</td>
                <td align="center">172270</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">3&ordm;</td>
                <td align="center">Namikaze Minato</td>
                <td align="center">43</td>
                <td align="center">ANBU</td>
                <td align="center">166445</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">4&ordm;</td>
                <td align="center">Uchiha Itachi</td>
                <td align="center">38</td>
                <td align="center">ANBU</td>
                <td align="center">148640</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">5&ordm;</td>
                <td align="center">Peinfox</td>
                <td align="center">41</td>
                <td align="center">ANBU</td>
                <td align="center">131689</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">6&ordm;</td>
                <td align="center">Oriibu</td>
                <td align="center">35</td>
                <td align="center">ANBU</td>
                <td align="center">120785</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/105.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">7&ordm;</td>
                <td align="center">Itachi Uchiha Nyu</td>
                <td align="center">32</td>
                <td align="center">Jounin</td>
                <td align="center">113619</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">8&ordm;</td>
                <td align="center">Konomoda</td>
                <td align="center">34</td>
                <td align="center">Jounin</td>
                <td align="center">111928</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">9&ordm;</td>
                <td align="center">Hidan Art</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">111396</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/105.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">10&ordm;</td>
                <td align="center">PainH</td>
                <td align="center">34</td>
                <td align="center">Jounin</td>
                <td align="center">111345</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">11&ordm;</td>
                <td align="center">poncitrana</td>
                <td align="center">35</td>
                <td align="center">ANBU</td>
                <td align="center">110671</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/104.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">12&ordm;</td>
                <td align="center">_Sephiroth_</td>
                <td align="center">35</td>
                <td align="center">Jounin</td>
                <td align="center">109795</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">13&ordm;</td>
                <td align="center">Dark Itachi 13</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">108393</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">14&ordm;</td>
                <td align="center">RSF</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">101629</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">15&ordm;</td>
                <td align="center">Uchiha Felipe</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">99814</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">16&ordm;</td>
                <td align="center">FnX lee</td>
                <td align="center">32</td>
                <td align="center">Jounin</td>
                <td align="center">99122</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">17&ordm;</td>
                <td align="center">Dark_Mage</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">97353</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">18&ordm;</td>
                <td align="center">Green Beast</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">95926</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">19&ordm;</td>
                <td align="center">Lee 1</td>
                <td align="center">28</td>
                <td align="center">Jounin</td>
                <td align="center">95217</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">20&ordm;</td>
                <td align="center">Kisame_Hoshigaki</td>
                <td align="center">40</td>
                <td align="center">ANBU</td>
                <td align="center">94701</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/54.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">21&ordm;</td>
                <td align="center">Sono</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">94519</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">22&ordm;</td>
                <td align="center">camakas</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">93909</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">23&ordm;</td>
                <td align="center">Uchiha Hashirama</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">93409</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">24&ordm;</td>
                <td align="center">batatinha</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">92859</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">25&ordm;</td>
                <td align="center">Onigumo Naraku</td>
                <td align="center">28</td>
                <td align="center">Jounin</td>
                <td align="center">91954</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/109.png" /></td>
              </tr>
            </table>
          </div>
          <div class="pane">
            <table width="730" border="0" cellpadding="0" cellspacing="0">
               <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr  bgcolor="#413625">
                <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td width="100" align="center">1&ordm;</td>
                <td width="140" align="center">Scorpius</td>
                <td align="center" width="100">40</td>
                <td align="center" width="110">ANBU</td>
                <td align="center" width="110">171404</td>
                <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">2&ordm;</td>
                <td align="center">Henriquecrono</td>
                <td align="center">39</td>
                <td align="center">ANBU</td>
                <td align="center">146026</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">3&ordm;</td>
                <td align="center">Nati San</td>
                <td align="center">37</td>
                <td align="center">ANBU</td>
                <td align="center">145797</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/56.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">4&ordm;</td>
                <td align="center">Sevencards Shikamaru Nara</td>
                <td align="center">40</td>
                <td align="center">ANBU</td>
                <td align="center">141815</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">5&ordm;</td>
                <td align="center">Orochimaru</td>
                <td align="center">40</td>
                <td align="center">ANBU</td>
                <td align="center">135558</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/76.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">6&ordm;</td>
                <td align="center">BloodEyes20</td>
                <td align="center">35</td>
                <td align="center">ANBU</td>
                <td align="center">128439</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">7&ordm;</td>
                <td align="center">GuTi* GuTi* da MaMae</td>
                <td align="center">40</td>
                <td align="center">ANBU</td>
                <td align="center">127949</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">8&ordm;</td>
                <td align="center">Hill</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">118281</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/78.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">9&ordm;</td>
                <td align="center">Dimrol</td>
                <td align="center">33</td>
                <td align="center">Jounin</td>
                <td align="center">113781</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/102.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">10&ordm;</td>
                <td align="center">__NeJi__HyUuGa__</td>
                <td align="center">35</td>
                <td align="center">ANBU</td>
                <td align="center">113424</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">11&ordm;</td>
                <td align="center">Dinarte</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">112179</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">12&ordm;</td>
                <td align="center">ByLaw</td>
                <td align="center">34</td>
                <td align="center">Jounin</td>
                <td align="center">108595</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">13&ordm;</td>
                <td align="center">NeJi MasTer</td>
                <td align="center">32</td>
                <td align="center">Jounin</td>
                <td align="center">106976</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">14&ordm;</td>
                <td align="center">Isanagi</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">106841</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">15&ordm;</td>
                <td align="center">Masaki_Nuvem</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">98166</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">16&ordm;</td>
                <td align="center">Kotarou Miyamoto</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">97084</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">17&ordm;</td>
                <td align="center">llAmaterasull</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">95066</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">18&ordm;</td>
                <td align="center">NejiSannin</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">92890</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">19&ordm;</td>
                <td align="center">Soulkiller</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">91791</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/105.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">20&ordm;</td>
                <td align="center">mario valmir</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">86370</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">21&ordm;</td>
                <td align="center">Seiji</td>
                <td align="center">26</td>
                <td align="center">Jounin</td>
                <td align="center">84632</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">22&ordm;</td>
                <td align="center">mostro verde</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">83814</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">23&ordm;</td>
                <td align="center">Xaruto Uzucrak</td>
                <td align="center">27</td>
                <td align="center">Chunin</td>
                <td align="center">83577</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">24&ordm;</td>
                <td align="center">Ura Renge Lee</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">82412</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">25&ordm;</td>
                <td align="center">Silencer</td>
                <td align="center">28</td>
                <td align="center">Jounin</td>
                <td align="center">81601</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/44.png" /></td>
              </tr>
            </table>
          </div>
          <div class="pane">
            <table width="730" border="0" cellpadding="0" cellspacing="0">
               <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr  bgcolor="#413625">
                <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td width="100" align="center">1&ordm;</td>
                <td width="140" align="center">BankaiSama</td>
                <td align="center" width="100">41</td>
                <td align="center" width="110">ANBU</td>
                <td align="center" width="110">167709</td>
                <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">2&ordm;</td>
                <td align="center">Maito 666</td>
                <td align="center">39</td>
                <td align="center">ANBU</td>
                <td align="center">161020</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">3&ordm;</td>
                <td align="center">For Teen</td>
                <td align="center">38</td>
                <td align="center">ANBU</td>
                <td align="center">142325</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">4&ordm;</td>
                <td align="center">Frozen Heart</td>
                <td align="center">38</td>
                <td align="center">ANBU</td>
                <td align="center">136977</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/87.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">5&ordm;</td>
                <td align="center">Endercroth_</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">133539</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">6&ordm;</td>
                <td align="center">Scorpion</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">114436</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">7&ordm;</td>
                <td align="center">lee500</td>
                <td align="center">34</td>
                <td align="center">Jounin</td>
                <td align="center">114290</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">8&ordm;</td>
                <td align="center">Sayajin_</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">109820</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">9&ordm;</td>
                <td align="center">Rock25Lee</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">101178</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">10&ordm;</td>
                <td align="center">X Pein Fenix X</td>
                <td align="center">33</td>
                <td align="center">Jounin</td>
                <td align="center">94861</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">11&ordm;</td>
                <td align="center">Seikatsu</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">94793</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">12&ordm;</td>
                <td align="center">Maycon xxt</td>
                <td align="center">35</td>
                <td align="center">Jounin</td>
                <td align="center">93377</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/104.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">13&ordm;</td>
                <td align="center">Raiky</td>
                <td align="center">32</td>
                <td align="center">Jounin</td>
                <td align="center">91037</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">14&ordm;</td>
                <td align="center">Vesper</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">89789</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">15&ordm;</td>
                <td align="center">hyuuga_senpai</td>
                <td align="center">35</td>
                <td align="center">ANBU</td>
                <td align="center">89410</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">16&ordm;</td>
                <td align="center">RROOCCKK LEE.</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">87787</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">17&ordm;</td>
                <td align="center">slbninja</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">85925</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">18&ordm;</td>
                <td align="center">setrupicaeuteempurronolombo</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">85811</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/58.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">19&ordm;</td>
                <td align="center">_VSF_</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">81859</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/117.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">20&ordm;</td>
                <td align="center">kimimaro da silva kaguya</td>
                <td align="center">28</td>
                <td align="center">Jounin</td>
                <td align="center">78299</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">21&ordm;</td>
                <td align="center">CurrY</td>
                <td align="center">27</td>
                <td align="center">Jounin</td>
                <td align="center">77630</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">22&ordm;</td>
                <td align="center">yullian da chuva</td>
                <td align="center">28</td>
                <td align="center">Jounin</td>
                <td align="center">77428</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">23&ordm;</td>
                <td align="center">fb kakashi</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center">75107</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">24&ordm;</td>
                <td align="center">Sr Chuck Norris</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">74430</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">25&ordm;</td>
                <td align="center">calaveras</td>
                <td align="center">26</td>
                <td align="center">Chunin</td>
                <td align="center">72894</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
            </table>
          </div>
          <div class="pane">
            <table width="730" border="0" cellpadding="0" cellspacing="0">
               <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr  bgcolor="#413625">
                <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td width="100" align="center">1&ordm;</td>
                <td width="140" align="center">XxxGai SenseixxX</td>
                <td align="center" width="100">46</td>
                <td align="center" width="110">Sanin</td>
                <td align="center" width="110">193770</td>
                <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">2&ordm;</td>
                <td align="center">Uzumaki liipe</td>
                <td align="center">44</td>
                <td align="center">ANBU</td>
                <td align="center">154257</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">3&ordm;</td>
                <td align="center">Atrocidade</td>
                <td align="center">39</td>
                <td align="center">ANBU</td>
                <td align="center">145044</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">4&ordm;</td>
                <td align="center">LuigiEncanador</td>
                <td align="center">37</td>
                <td align="center">ANBU</td>
                <td align="center">118163</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">5&ordm;</td>
                <td align="center">XxxxUchiha ItachixxxX</td>
                <td align="center">32</td>
                <td align="center">Jounin</td>
                <td align="center">114732</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">6&ordm;</td>
                <td align="center">Sempai Neji</td>
                <td align="center">41</td>
                <td align="center">ANBU</td>
                <td align="center">114113</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">7&ordm;</td>
                <td align="center">Goku Lee</td>
                <td align="center">32</td>
                <td align="center">Jounin</td>
                <td align="center">111831</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">8&ordm;</td>
                <td align="center">hsdh</td>
                <td align="center">34</td>
                <td align="center">Jounin</td>
                <td align="center">109039</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">9&ordm;</td>
                <td align="center">_Roock Lee_</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">100913</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">10&ordm;</td>
                <td align="center">zig3</td>
                <td align="center">31</td>
                <td align="center">Jounin</td>
                <td align="center">98259</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">11&ordm;</td>
                <td align="center">Im Back</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">95910</td>

                <td align="center"><img src="<?php echo img() ?>layout/dojo/52.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">12&ordm;</td>
                <td align="center">Taiag</td>
                <td align="center">32</td>
                <td align="center">Jounin</td>
                <td align="center">93947</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">13&ordm;</td>
                <td align="center">Pada_Ten_Dou</td>
                <td align="center">28</td>
                <td align="center">Jounin</td>
                <td align="center">92273</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">14&ordm;</td>
                <td align="center">Kigama</td>
                <td align="center">28</td>
                <td align="center">Jounin</td>
                <td align="center">86540</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/75.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">15&ordm;</td>
                <td align="center">jBMaster</td>
                <td align="center">28</td>
                <td align="center">Jounin</td>
                <td align="center">85522</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">16&ordm;</td>
                <td align="center">Massacration Hyuuga</td>
                <td align="center">27</td>
                <td align="center">Jounin</td>
                <td align="center">85055</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">17&ordm;</td>
                <td align="center">__SlayerBloodline__</td>
                <td align="center">26</td>
                <td align="center">Jounin</td>
                <td align="center">83101</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">18&ordm;</td>
                <td align="center">LOTUS OCULTA</td>
                <td align="center">26</td>
                <td align="center">Jounin</td>
                <td align="center">80167</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">19&ordm;</td>
                <td align="center">Espada Relampago</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center">78400</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">20&ordm;</td>
                <td align="center">Uzumaki axeel</td>
                <td align="center">34</td>
                <td align="center">Jounin</td>
                <td align="center">76070</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">21&ordm;</td>
                <td align="center">Akasa Debora</td>
                <td align="center">26</td>
                <td align="center">Jounin</td>
                <td align="center">74763</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">22&ordm;</td>
                <td align="center">henrique18</td>
                <td align="center">28</td>
                <td align="center">Jounin</td>
                <td align="center">74036</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">23&ordm;</td>
                <td align="center">Death23</td>
                <td align="center">29</td>
                <td align="center">Jounin</td>
                <td align="center">73548</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">24&ordm;</td>
                <td align="center">Mario_</td>
                <td align="center">26</td>
                <td align="center">Chun in</td>
                <td align="center">72569</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">25&ordm;</td>
                <td align="center">MaStEr Of GeN</td>
                <td align="center">28</td>
                <td align="center">Chunin</td>
                <td align="center">72483</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
              </tr>
            </table>
          </div>
          <div class="pane"> <strong style="font-size:16px; color:#FFF;"><?php echo t('halldafama.h9')?></strong><br />
            <strong style="font-size:16px; color:#FFF"><?php echo t('guild_missoes_status_guild.g2')?> - Killers Instinct ( <?php echo t('arvore_talento.at7')?> - 5437846)</strong><br />
            <br />
            <table width="730" border="0" cellpadding="0" cellspacing="0">
               <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="80" align="center">&nbsp;</td>
                            <td width="120" align="center"><b style="color:#FFFFFF"><?php echo t('geral.vila')?></b></td>
                            <td width="190" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr  bgcolor="#413625">
                <td width="80" align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td width="120" align="center"><img src="<?php echo img() ?>layout/bandanas/7.jpg" /></td>
                <td width="190" align="center">Fenix__</td>
                <td width="110" align="center">45</td>
                <td width="130" align="center">Sannin</td>
                <td width="100" align="center"><img src="<?php echo img() ?>layout/dojo/43.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center" bgcolor="#251a13">&nbsp;</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/bandanas/4.jpg" /></td>
                <td align="center" bgcolor="#251a13">XxxGai SenseixxX</td>
                <td align="center" bgcolor="#251a13">46</td>
                <td align="center" bgcolor="#251a13">Sannin</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center">&nbsp;</td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/2.jpg" /></td>
                <td align="center">Twister Hyuuga</td>
                <td align="center">45</td>
                <td align="center">Sannin</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center" bgcolor="#251a13">&nbsp;</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/bandanas/3.jpg" /></td>
                <td align="center" bgcolor="#251a13">HoppE</td>
                <td align="center" bgcolor="#251a13">47</td>
                <td align="center" bgcolor="#251a13">Sannin</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center">&nbsp;</td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/4.jpg" /></td>
                <td align="center">Uzumaki liipe</td>
                <td align="center">44</td>
                <td align="center">ANBU</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center" bgcolor="#251a13">&nbsp;</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/bandanas/6.jpg" /></td>
                <td align="center" bgcolor="#251a13">Peinfox</td>
                <td align="center" bgcolor="#251a13">41</td>
                <td align="center" bgcolor="#251a13">ANBU</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center">&nbsp;</td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/8.jpg" /></td>
                <td align="center">BankaiSama</td>
                <td align="center">41</td>
                <td align="center">ANBU</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center" bgcolor="#251a13">&nbsp;</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/bandanas/1.jpg" /></td>
                <td align="center" bgcolor="#251a13">__Akatsuna__</td>
                <td align="center" bgcolor="#251a13">41</td>
                <td align="center" bgcolor="#251a13">ANBU</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center">&nbsp;</td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/2.jpg" /></td>
                <td align="center">morto25</td>
                <td align="center">41</td>
                <td align="center">ANBU</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center" bgcolor="#251a13">&nbsp;</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/bandanas/5.jpg" /></td>
                <td align="center" bgcolor="#251a13">Sevencards Shikamaru Nara</td>
                <td align="center" bgcolor="#251a13">40</td>
                <td align="center" bgcolor="#251a13">ANBU</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center">&nbsp;</td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/5.jpg" /></td>
                <td align="center">GuTi* GuTi* da MaMae</td>
                <td align="center">40</td>
                <td align="center">ANBU</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center" bgcolor="#251a13">&nbsp;</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/bandanas/4.jpg" /></td>
                <td align="center" bgcolor="#251a13">Atrocidade</td>
                <td align="center" bgcolor="#251a13">39</td>
                <td align="center" bgcolor="#251a13">ANBU</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center">&nbsp;</td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/8.jpg" /></td>
                <td align="center">Endercroth_</td>
                <td align="center">36</td>
                <td align="center">ANBU</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center" bgcolor="#251a13">&nbsp;</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/bandanas/2.jpg" /></td>
                <td align="center" bgcolor="#251a13">OlhaLaOninhoDeTonGoiA</td>
                <td align="center" bgcolor="#251a13">35</td>
                <td align="center" bgcolor="#251a13">Jounin</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center">&nbsp;</td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/4.jpg" /></td>
                <td align="center">XxxxUchiha ItachixxxX</td>
                <td align="center">32</td>
                <td align="center">Jounin</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center" bgcolor="#251a13">&nbsp;</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/bandanas/6.jpg" /></td>
                <td align="center" bgcolor="#251a13">Skillful</td>
                <td align="center" bgcolor="#251a13">31</td>
                <td align="center" bgcolor="#251a13">Jounin</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center">&nbsp;</td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/3.jpg" /></td>
                <td align="center">Dradivul</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center" bgcolor="#251a13">&nbsp;</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/bandanas/3.jpg" /></td>
                <td align="center" bgcolor="#251a13">II Maito Gai II</td>
                <td align="center" bgcolor="#251a13">30</td>
                <td align="center" bgcolor="#251a13">Jounin</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center">&nbsp;</td>
                <td align="center"><img src="<?php echo img() ?>layout/bandanas/3.jpg" /></td>
                <td align="center">garou x</td>
                <td align="center">30</td>
                <td align="center">Jounin</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
            </table>
            <br />
            <br />
            <strong style="font-size:16px; color:#FFF;"><?php echo t('halldafama.h10')?></strong><br />
            <strong style="font-size:16px; color:#FFF"><?php echo t('halldafama.h11')?> Konoha - Konoha Rules ( Pontos: 139834 )</strong> <br />
            <br />
             <table width="730" border="0" cellpadding="0" cellspacing="0">
               <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.integrantes')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
         
              <tr  bgcolor="#413625">
                <td width="70" align="center"><img src="<?php echo img() ?>layout/trophy.png" width="16" height="16" /></td>
                <td width="100" align="center">1&ordm;</td>
                <td width="140" align="center">Knight of konoha</td>
                <td width="100" align="center">47</td>
                <td width="110" align="center">Sannin</td>
                <td width="110" align="center">224703</td>
                <td width="100" align="center"><img src="<?php echo img() ?>layout/dojo/78.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center" bgcolor="#251a13">6&ordm;</td>
                <td align="center" bgcolor="#251a13">Hyuuga Neji</td>
                <td align="center" bgcolor="#251a13">41</td>
                <td align="center" bgcolor="#251a13">ANBU</td>
                <td align="center" bgcolor="#251a13">166688</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">106&ordm;</td>
                <td align="center">dayvid lee</td>
                <td align="center">28</td>
                <td align="center">Jounin</td>
                <td align="center">67050</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center" bgcolor="#251a13">4º</td>
                <td align="center" bgcolor="#251a13">DarkIori</td>
                <td align="center" bgcolor="#251a13">43</td>
                <td align="center" bgcolor="#251a13">Sannin</td>
                <td align="center" bgcolor="#251a13">180434</td>
                <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
            </table>
          </div>
        </div>
        <div class="pane">
          <!-- the tabs -->
          <ul class="tabs">
            <li><a href="#"><?php echo t('halldafama.h12')?></a></li>
          </ul>
          <!-- tab "panes" -->
          <div class="pane">
          
          <table width="730" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="10" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="60" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="60" align="center"><b style="color:#FFFFFF">Level</b></td>
                            <td width="70" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="80" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao_final')?></b></td>
                            <td width="50" align="center"><b style="color:#FFFFFF"><?php echo t('geral.vitorias')?></b></td>
                            <td width="50" align="center"><b style="color:#FFFFFF"><?php echo t('geral.derrotas')?></b></td>
                            <td width="50" align="center"><b style="color:#FFFFFF"><?php echo t('geral.empates')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr  bgcolor="#413625">
                <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td width="60" align="center">1&ordm;</td>
                <td width="140" align="center">Yahito</td>
                <td width="60" align="center">52</td>
                <td width="70" align="center">Sanin</td>
                <td width="80" align="center">92913</td>
                <td width="50" align="center">581</td>
                <td width="50" align="center">11</td>
                <td width="50" align="center">51</td>
                <td width="100" align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">2&ordm;</td>
                <td align="center">Uzumaki liipe</td>
                <td align="center">53</td>
                <td align="center">Sanin</td>
                <td align="center">92533</td>
                <td align="center">557</td>
                <td align="center">19</td>
                <td align="center">30</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">3&ordm;</td>
                <td align="center">Uchiha Obito</td>
                <td align="center">52</td>
                <td align="center">Sanin</td>
                <td align="center">88740</td>
                <td align="center">529</td>
                <td align="center">42</td>
                <td align="center">88</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">4&ordm;</td>
                <td align="center">HoppE My LovE</td>
                <td align="center">52</td>
                <td align="center">Sanin</td>
                <td align="center">88649</td>
                <td align="center">483</td>
                <td align="center">4</td>
                <td align="center">17</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">5&ordm;</td>
                <td align="center">Hadou Kakashi</td>
                <td align="center">51</td>
                <td align="center">Sanin</td>
                <td align="center">87099</td>
                <td align="center">476</td>
                <td align="center">8</td>
                <td align="center">17</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">6&ordm;</td>
                <td align="center">Quicksilver</td>
                <td align="center">51</td>
                <td align="center">Sanin</td>
                <td align="center">86186</td>
                <td align="center">525</td>
                <td align="center">74</td>
                <td align="center">33</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">7&ordm;</td>
                <td align="center">XxFairexX</td>
                <td align="center">52</td>
                <td align="center">Sanin</td>
                <td align="center">86015</td>
                <td align="center">529</td>
                <td align="center">89</td>
                <td align="center">64</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">8&ordm;</td>
                <td align="center">Dywix</td>
                <td align="center">51</td>
                <td align="center">Sanin</td>
                <td align="center">85352</td>
                <td align="center">441</td>
                <td align="center">7</td>
                <td align="center">23</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/73.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">9&ordm;</td>
                <td align="center">x Nightmare x</td>
                <td align="center">51</td>
                <td align="center">Sanin</td>
                <td align="center">85296</td>
                <td align="center">458</td>
                <td align="center">22</td>
                <td align="center">34</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/118.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">10&ordm;</td>
                <td align="center">XxxxUchiha ItachixxxX</td>
                <td align="center">51</td>
                <td align="center">Sanin</td>
                <td align="center">85098</td>
                <td align="center">440</td>
                <td align="center">10</td>
                <td align="center">53</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
            </table>
          </div>
        </div>
         <!-- tab "panes" -->
              <div class="pane">
                <!-- the tabs -->
                <ul class="tabs">

                  <li><a href="#">Kages</a></li>
                  <li><a href="#">Konoha</a></li>
                  <li><a href="#">Areia</a></li>
                  <li><a href="#">Nevoa</a></li>
                  <li><a href="#">Som</a></li>
                  <li><a href="#">Akatsuki</a></li>

                  <li><a href="#">Nuvem</a></li>
                  <li><a href="#">Chuva</a></li>
                  <li><a href="#">Pedra</a></li>
                  <li><a href="#">Extras</a></li>
                </ul>
                <!-- tab "panes" -->
                <div class="pane">

                <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                    <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
                    <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td  width="100" align="center"><img src="<?php echo img() ?>layout/bandanas/1.jpg" /></td>
                      <td width="140" align="center">Uchira_Juni0r</td>

                      <td width="100" align="center">55</td>
                      <td width="110" align="center">Sannin</td>
                      <td width="110" align="center">240030</td>
                      <td width="100" align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/trophy.png" width="16" height="16" /></td>

                      <td align="center"><img src="<?php echo img() ?>layout/bandanas/6.jpg" /></td>
                      <td height="17" align="center">X_Zell_X</td>
                      <td align="center">64</td>
                      <td align="center">Sannin</td>
                      <td align="center">319130</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>

                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td align="center"><img src="<?php echo img() ?>layout/bandanas/7.jpg" /></td>
                      <td height="17" align="center">Ni-Sama(L)</td>
                      <td align="center">56</td>
                      <td align="center">Sannin</td>
                      <td align="center">258875</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td align="center"><img src="<?php echo img() ?>layout/bandanas/3.jpg" /></td>
                      <td height="17" align="center">Electra</td>
                      <td align="center">56</td>
                      <td align="center">Sannin</td>

                      <td align="center">264829</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/51.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/trophy-bronze.png" width="16" height="16" /></td>
                      <td align="center"><img src="<?php echo img() ?>layout/bandanas/5.jpg" /></td>
                      <td height="17" align="center">_Sephiroth_</td>
                      <td align="center">62</td>

                      <td align="center">Sannin</td>
                      <td align="center">278739</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td align="center"><img src="<?php echo img() ?>layout/bandanas/4.jpg" /></td>
                      <td height="17" align="center">Paladino_Warlord </td>

                      <td align="center">51</td>
                      <td align="center">Sannin</td>
                      <td align="center">205806</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/53.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>

                      <td align="center"><img src="<?php echo img() ?>layout/bandanas/8.jpg" /></td>
                      <td height="17" align="center">_Nagatsu_</td>
                      <td align="center">63</td>
                      <td align="center">Sannin</td>
                      <td align="center">267737</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/53.png" /></td>
                    </tr>

                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/trophy-silver.png" width="16" height="16" /></td>
                      <td align="center"><img src="<?php echo img() ?>layout/bandanas/2.jpg" /></td>
                      <td align="center">TaKaRRaShI</td>
                      <td align="center">58</td>
                      <td align="center">Sannin</td>
                      <td align="center">290656</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                  </table>
                </div>
                <div class="pane">
                  <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                            <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

                    <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" align="center">Uchira_Juni0r</td>
                      <td width="100" align="center" width="74">55</td>
                      <td width="110" align="center">Sannin</td>
                      <td width="110" align="center" width="127">240030</td>

                      <td width="100" align="center" width="99"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td align="center">Obito1000</td>
                      <td align="center">56</td>

                      <td align="center">Sannin</td>
                      <td width="127" align="center">233818</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td align="center">Rock_Killer_Lee</td>
                      <td align="center">52</td>
                      <td align="center">Sannin</td>
                      <td width="127" align="center">211495</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td align="center">Maycon XxT</td>
                      <td align="center">54</td>
                      <td align="center">Sannin</td>
                      <td width="127" align="center">203175</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/104.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td align="center">King Uchiha</td>
                      <td align="center">55</td>

                      <td align="center">Sannin</td>
                      <td width="127" align="center">193422</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td align="center">Destruidor_de_Konoha</td>
                      <td align="center">54</td>
                      <td align="center">Sannin</td>
                      <td width="127" align="center">190144</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td align="center">RockLee006</td>
                      <td align="center">46</td>
                      <td align="center">Sannin</td>
                      <td width="127" align="center">178596</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td align="center">Pensador</td>
                      <td align="center">46</td>

                      <td align="center">ANBU</td>
                      <td width="127" align="center">175477</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td align="center">LeeFight_Xx</td>
                      <td align="center">45</td>
                      <td align="center">ANBU</td>
                      <td width="127" align="center">173417</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td align="center">Diego_Weliton</td>
                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td width="127" align="center">168949</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td align="center">__Yondaime__</td>
                      <td align="center">45</td>

                      <td align="center">ANBU</td>
                      <td width="127" align="center">162728</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td align="center">rock_lee_1000</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td width="127" align="center">157424</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td align="center">petykaze2</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td width="127" align="center">155264</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td align="center">Aya Natsume</td>
                      <td align="center">40</td>

                      <td align="center">ANBU</td>
                      <td width="127" align="center">153473</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/59.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td align="center">hokage_minato_sensei</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td width="127" align="center">150624</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td align="center">o_ViNGADOR___</td>
                      <td align="center">45</td>
                      <td align="center">ANBU</td>
                      <td width="127" align="center">145930</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td align="center">UlchihaItachi</td>
                      <td align="center">37</td>

                      <td align="center">ANBU</td>
                      <td width="127" align="center">143962</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td align="center">Killer_Juugo</td>
                      <td align="center">37</td>
                      <td align="center">Jounin</td>
                      <td width="127" align="center">143847</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/116.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td align="center">Ryuudan</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td width="127" align="center">143799</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td align="center">laurence_roch lee comanda</td>
                      <td align="center">45</td>

                      <td align="center">ANBU</td>
                      <td width="127" align="center">142951</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td align="center">Akira Sai</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td width="127" align="center">142753</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/99.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>
                      <td align="center">Hyuuga_Hiinata</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td width="127" align="center">139372</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>

                      <td align="center">Nejisam</td>
                      <td align="center">39</td>

                      <td align="center">Jounin</td>
                      <td width="127" align="center">139355</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td align="center">Andrellus</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td width="127" align="center">135065</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td align="center">MATADOR.Uchiha_Itachi_Eddy</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td width="127" align="center">133449</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                  </table>
                </div>
                <div class="pane">
                  <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                           <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

                    <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/trophy-silver.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" width="190" align="center">TaKaRRaShI</td>
                      <td align="center" width="100">58</td>
                      <td width="110" align="center">Sannin</td>
                      <td align="center" width="110">290656</td>

                      <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td align="center">Dimrol</td>
                      <td align="center">54</td>

                      <td align="center">Sannin</td>
                      <td align="center">226066</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/81.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td align="center">Rock Lee         </td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">206342</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td align="center">Yamata no Kyuu</td>
                      <td align="center">50</td>
                      <td align="center">Sannin</td>

                      <td align="center">196843</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/76.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td align="center">Afterlife</td>
                      <td align="center">52</td>

                      <td align="center">Sannin</td>
                      <td align="center">188091</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td align="center">kurosaki_tobi</td>
                      <td align="center">46</td>
                      <td align="center">Sannin</td>
                      <td align="center">187908</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/104.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td align="center">sasuke_chidori_negro</td>
                      <td align="center">45</td>
                      <td align="center">ANBU</td>
                      <td align="center">174980</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td align="center">????</td>
                      <td align="center">46</td>

                      <td align="center">Sannin</td>
                      <td align="center">171431</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/53.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td align="center">spy winchester</td>
                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td align="center">167514</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/58.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>

                      <td align="center">10&ordm;</td>
                      <td align="center">Prodige Uchiha</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">166172</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td align="center">Marukimi</td>
                      <td align="center">44</td>

                      <td align="center">ANBU</td>
                      <td align="center">161078</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td align="center">Kay kyu</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">158871</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td align="center">P a i n _</td>
                      <td align="center">45</td>
                      <td align="center">ANBU</td>
                      <td align="center">153813</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td align="center">Uchiha Kidinho</td>
                      <td align="center">41</td>

                      <td align="center">ANBU</td>
                      <td align="center">152054</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td align="center">DrownSect</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">148226</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/58.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td align="center">Tara_Perdida</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">142017</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td align="center">Hyuuga_Neji</td>
                      <td align="center">39</td>

                      <td align="center">ANBU</td>
                      <td align="center">137071</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td align="center">Lee_feroz</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">136966</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td align="center">S.W.A.T</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">136911</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/4.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td align="center">_HoppE_</td>
                      <td align="center">35</td>

                      <td align="center">Jounin</td>
                      <td align="center">136474</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td align="center">Thiago_prodigio</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">135108</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>
                      <td align="center">Hyuuga_JP_Neji</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">135065</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>
                      <td align="center">x</td>
                      <td align="center">36</td>

                      <td align="center">ANBU</td>
                      <td align="center">134096</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/51.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td align="center">Twister Hyuuga</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">131673</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td align="center">G.R.FA. Buck Pensa Fernaoid LTDA</td>
                      <td align="center">35</td>
                      <td align="center">Jounin</td>
                      <td align="center">120844</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/75.png" /></td>
                    </tr>
                  </table>
                </div>
                <div class="pane">
                  <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                           <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

                    <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" height="17" align="center">Electra</td>
                      <td align="center" width="100">56</td>
                      <td width="110" align="center">Sannin</td>
                      <td align="center" width="110">264829</td>
                      <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/51.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td height="17" align="center">_GBL_</td>
                      <td align="center">56</td>

                      <td align="center">Sannin</td>
                      <td align="center">229018</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td height="17" align="center">Kill Galinha_</td>
                      <td align="center">52</td>
                      <td align="center">Sannin</td>
                      <td align="center">216116</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td height="17" align="center">Hime Chan</td>
                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td align="center">212161</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td height="17" align="center">Uchiha_Brand</td>
                      <td align="center">49</td>

                      <td align="center">Sannin</td>
                      <td align="center">204467</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td height="17" align="center">Pimpolhinho</td>
                      <td align="center">50</td>
                      <td align="center">Sannin</td>
                      <td align="center">196018</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td height="17" align="center">a??ic??s</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">194023</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td height="17" align="center">Souchirou</td>
                      <td align="center">52</td>

                      <td align="center">Sannin</td>
                      <td align="center">193870</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td height="17" align="center">Byakugan san</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">193251</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td height="17" align="center">Uchiha Sannin</td>
                      <td align="center">52</td>
                      <td align="center">Sannin</td>
                      <td align="center">189490</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td height="17" align="center">Tsukenn</td>
                      <td align="center">51</td>

                      <td align="center">Sannin</td>
                      <td align="center">189223</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td height="17" align="center">DarkJuugo</td>
                      <td align="center">51</td>
                      <td align="center">Sannin</td>
                      <td align="center">186379</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/116.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td height="17" align="center">blue_beast</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">182163</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/54.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td height="17" align="center">Kaphwan_</td>
                      <td align="center">45</td>

                      <td align="center">ANBU</td>
                      <td align="center">182076</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td height="17" align="center">Fabio Tonin</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td align="center">175648</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/101.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td height="17" align="center">O_Devorador_de_Almas</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td align="center">172762</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td height="17" align="center">Jack Estripador</td>
                      <td align="center">46</td>

                      <td align="center">ANBU</td>
                      <td align="center">172113</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td height="17" align="center">Uchiha Roger</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">171760</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td height="17" align="center">Rikudou_Sannin_Tai</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">167607</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td height="17" align="center">Ã—HellÃ—</td>
                      <td align="center">51</td>

                      <td align="center">Sannin</td>
                      <td align="center">162872</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td height="17" align="center">Hill San</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td align="center">161343</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>
                      <td height="17" align="center">Sabaku no trust</td>
                      <td align="center">45</td>
                      <td align="center">ANBU</td>
                      <td align="center">157907</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/4.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>
                      <td height="17" align="center">Vini Papito Master</td>
                      <td align="center">47</td>

                      <td align="center">ANBU</td>
                      <td align="center">157322</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/4.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td height="17" align="center">** Yondaime Hokage **</td>
                      <td align="center">45</td>
                      <td align="center">ANBU</td>
                      <td align="center">153854</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td height="17" align="center">Sai02</td>
                      <td align="center">46</td>
                      <td align="center">ANBU</td>
                      <td align="center">151930</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
                    </tr>
                  </table>
                </div>
                <div class="pane">
                  <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                           <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

                    <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" height="17" align="center">Ni-Sama(L)</td>
                      <td width="100" align="center" width="74">56</td>
                      <td width="110" align="center">Sannin</td>
                      <td align="center" width="110">258875</td>

                      <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td height="17" align="center">Tua Mae</td>
                      <td align="center">55</td>

                      <td align="center">Sannin</td>
                      <td align="center">202789</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/113.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td height="17" align="center">Massacration Hyuuga</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">195860</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td height="17" align="center">...2010...</td>
                      <td align="center">46</td>
                      <td align="center">ANBU</td>
                      <td align="center">189544</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td height="17" align="center">canino_branco.</td>
                      <td align="center">47</td>

                      <td align="center">Sannin</td>
                      <td align="center">186583</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td height="17" align="center">SakeEstragado</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">174708</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td height="17" align="center">Link Uchiha</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">166803</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td height="17" align="center">z</td>
                      <td align="center">39</td>

                      <td align="center">ANBU</td>
                      <td align="center">152324</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td height="17" align="center">Pirata do Caribe</td>
                      <td align="center">36</td>
                      <td align="center">ANBU</td>
                      <td align="center">151434</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td height="17" align="center">Wonka</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">149281</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td height="17" align="center"> Tai Rock </td>
                      <td align="center">46</td>

                      <td align="center">ANBU</td>
                      <td align="center">145762</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td height="17" align="center">.Lust.   </td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">143179</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td height="17" align="center">_The_Demon_Face_</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">138421</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/52.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td height="17" align="center">Orochi no Jiroubou (Shun)</td>
                      <td align="center">39</td>

                      <td align="center">ANBU</td>
                      <td align="center">136346</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/70.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td height="17" align="center">Kayakai</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">129778</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td height="17" align="center">abracadabra</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">129430</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td height="17" align="center">Herium</td>
                      <td align="center">38</td>

                      <td align="center">ANBU</td>
                      <td align="center">129289</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td height="17" align="center">Slash23</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">125974</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td height="17" align="center">Cisinho</td>
                      <td align="center">34</td>
                      <td align="center">Jounin</td>
                      <td align="center">125353</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/76.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td height="17" align="center">Richard__RPC</td>
                      <td align="center">34</td>

                      <td align="center">Jounin</td>
                      <td align="center">125059</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td height="17" align="center">ISAAC_NEPO</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">124641</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>
                      <td height="17" align="center">tonyboy3</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">124151</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/4.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>
                      <td height="17" align="center">_Sombrancelhudo_</td>
                      <td align="center">37</td>

                      <td align="center">Jounin</td>
                      <td align="center">123076</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td height="17" align="center">Setsuna_Uchiha</td>
                      <td align="center">37</td>
                      <td align="center">Jounin</td>
                      <td align="center">120514</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td height="17" align="center">DarkLee</td>
                      <td align="center">27</td>
                      <td align="center">Chuunin</td>
                      <td align="center">120140</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                  </table>
                </div>
                <div class="pane">
                <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                           <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
                    <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/trophy.png" width="16" height="16" /></td>
                      <td width="100" align="center" >1&ordm;</td>
                      <td width="140" height="17" align="center">X_Zell_X</td>
                      <td width="100" align="center" width="74">64</td>
                      <td width="110" align="center">Sannin</td>
                      <td align="center" width="110">319130</td>

                      <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td height="17" align="center">Proserpine</td>
                      <td align="center">53</td>

                      <td align="center">Sannin</td>
                      <td align="center">230057</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/88.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td height="17" align="center">     Uchiha Itachi</td>
                      <td align="center">46</td>
                      <td align="center">ANBU</td>
                      <td align="center">211233</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td height="17" align="center">xxx Jonico X</td>
                      <td align="center">56</td>
                      <td align="center">Sannin</td>
                      <td align="center">205099</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td height="17" align="center">Shimpato_Yamasaki</td>
                      <td align="center">56</td>

                      <td align="center">Sannin</td>
                      <td align="center">199677</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td height="17" align="center">Teu Pai</td>
                      <td align="center">48</td>
                      <td align="center">ANBU</td>
                      <td align="center">194682</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td height="17" align="center">Neji_Ryu</td>
                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td align="center">193711</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td height="17" align="center">Hanabi Metzli</td>
                      <td align="center">48</td>

                      <td align="center">ANBU</td>
                      <td align="center">192383</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td height="17" align="center">Hyuuga Aizau</td>
                      <td align="center">56</td>
                      <td align="center">Sannin</td>
                      <td align="center">188050</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td height="17" align="center">islan</td>
                      <td align="center">52</td>
                      <td align="center">Sannin</td>
                      <td align="center">180333</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td height="17" align="center">Okusama</td>
                      <td align="center">49</td>

                      <td align="center">ANBU</td>
                      <td align="center">176784</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td height="17" align="center">Broken</td>
                      <td align="center">47</td>
                      <td align="center">ANBU</td>
                      <td align="center">175724</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td height="17" align="center">Uchiha Hashirama</td>
                      <td align="center">46</td>
                      <td align="center">ANBU</td>
                      <td align="center">172216</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td height="17" align="center">AlemDaLeenda</td>
                      <td align="center">42</td>

                      <td align="center">ANBU</td>
                      <td align="center">171015</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td height="17" align="center">PeinKillerXD</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">170039</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td height="17" align="center">Nobztrakt</td>
                      <td align="center">49</td>
                      <td align="center">ANBU</td>
                      <td align="center">168726</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/116.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td height="17" align="center">GuTi* GuTi* da MaMae</td>
                      <td align="center">41</td>

                      <td align="center">ANBU</td>
                      <td align="center">166161</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td height="17" align="center">Drenar</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td align="center">166141</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td height="17" align="center">Orochimaru_du_Mal</td>
                      <td align="center">50</td>
                      <td align="center">Sannin</td>
                      <td align="center">166123</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/76.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td height="17" align="center"> Uchiha    Sasuke</td>
                      <td align="center">46</td>

                      <td align="center">ANBU</td>
                      <td align="center">166084</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td height="17" align="center">Neji_Th</td>
                      <td align="center">53</td>
                      <td align="center">Sannin</td>
                      <td align="center">164619</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>
                      <td height="17" align="center">Divinorum &amp;#2384;</td>
                      <td align="center">46</td>
                      <td align="center">ANBU</td>
                      <td align="center">163676</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/79.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>
                      <td height="17" align="center">Kisame, O Pior</td>
                      <td align="center">49</td>

                      <td align="center">ANBU</td>
                      <td align="center">163047</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/54.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td height="17" align="center">Rock      Lee</td>
                      <td align="center">45</td>
                      <td align="center">ANBU</td>
                      <td align="center">157417</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td height="17" align="center">Paladino_Scarlate</td>
                      <td align="center">45</td>
                      <td align="center">ANBU</td>
                      <td align="center">157019</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                  </table>
                </div>
                <div class="pane">
                  <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                           <tr>
                                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                                          <tr>
                                            <td width="70" align="center">&nbsp;</td>
                                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                                            <td width="100" align="center">&nbsp;</td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table></td>
                              </tr>

                    <tr>
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/trophy-bronze.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" height="17" align="center">_Sephiroth_</td>
                      <td align="center" width="100">62</td>
                      <td width="110" align="center">Sannin</td>
                      <td align="center" width="110">278739</td>

                      <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td height="17" align="center">YodaimeS100</td>
                      <td align="center">62</td>

                      <td align="center">Sannin</td>
                      <td align="center">278150</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td height="17" align="center">Kazama</td>
                      <td align="center">52</td>
                      <td align="center">Sannin</td>
                      <td align="center">225758</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td height="17" align="center">Comigo eh na base do beijo!</td>
                      <td align="center">54</td>
                      <td align="center">Sannin</td>
                      <td align="center">214410</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td height="17" align="center">Lucas F</td>
                      <td align="center">58</td>

                      <td align="center">Sannin</td>
                      <td align="center">213815</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td height="17" align="center">*Brandon* *Neon*</td>
                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td align="center">210360</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td height="17" align="center">batatinha</td>
                      <td align="center">51</td>
                      <td align="center">Sannin</td>
                      <td align="center">210012</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td height="17" align="center">ByakuganN.</td>
                      <td align="center">51</td>

                      <td align="center">Sannin</td>
                      <td align="center">205307</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td height="17" align="center">Neji Byakugan</td>
                      <td align="center">56</td>
                      <td align="center">Sannin</td>
                      <td align="center">202103</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td height="17" align="center">Dark_Gambite</td>
                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td align="center">198099</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td height="17" align="center">Paladino_Crash</td>
                      <td align="center">55</td>

                      <td align="center">Sannin</td>
                      <td align="center">193104</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td height="17" align="center">UchihaItachiMangekyou</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">182780</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td height="17" align="center">Cryomancer_Leds</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">178596</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td height="17" align="center">**Brandon**Neon**</td>
                      <td align="center">43</td>

                      <td align="center">ANBU</td>
                      <td align="center">177783</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/77.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td height="17" align="center">Death27</td>
                      <td align="center">52</td>
                      <td align="center">Sannin</td>
                      <td align="center">173001</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/75.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td height="17" align="center">SharingaanN.</td>
                      <td align="center">46</td>
                      <td align="center">ANBU</td>
                      <td align="center">171966</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td height="17" align="center">Aphrodisiac</td>
                      <td align="center">41</td>

                      <td align="center">ANBU</td>
                      <td align="center">163211</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td height="17" align="center">.Ascendecy.</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">160174</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/88.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td height="17" align="center">UchihaSasukeMangekyou</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">157495</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td height="17" align="center">NejiByakugan_Full</td>
                      <td align="center">45</td>

                      <td align="center">ANBU</td>
                      <td align="center">157432</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td height="17" align="center">__YusuKe__</td>
                      <td align="center">46</td>
                      <td align="center">ANBU</td>
                      <td align="center">157204</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>
                      <td height="17" align="center">Edo Tenrai</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">157147</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/67.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>
                      <td height="17" align="center">__Uchiha__Sasuke.</td>
                      <td align="center">48</td>

                      <td align="center">ANBU</td>
                      <td align="center">155278</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td height="17" align="center">Jiraya_O_sensei</td>
                      <td align="center">45</td>
                      <td align="center">ANBU</td>
                      <td align="center">152906</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td height="17" align="center">_god of death_</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">145344</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                  </table>
                </div>
                <div class="pane">
                  <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                            <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
                    <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" height="17" align="center">_Nagatsu_</td>
                      <td align="center" width="100">63</td>
                      <td width="110" align="center">Sannin</td>
                      <td align="center" width="110">267737</td>

                      <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/53.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td height="17" align="center">Sakaru</td>
                      <td align="center">61</td>

                      <td align="center">Sannin</td>
                      <td align="center">265433</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td height="17" align="center">[S][K][U][L][L]</td>
                      <td align="center">60</td>
                      <td align="center">Sannin</td>
                      <td align="center">249041</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td height="17" align="center">zig3</td>
                      <td align="center">51</td>
                      <td align="center">Sannin</td>
                      <td align="center">203625</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td height="17" align="center">Bruninho_Sadam</td>
                      <td align="center">57</td>

                      <td align="center">Sannin</td>
                      <td align="center">203023</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td height="17" align="center">Lley_Wes..._</td>
                      <td align="center">57</td>
                      <td align="center">Sannin</td>
                      <td align="center">198512</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td height="17" align="center">Genio Lee</td>
                      <td align="center">50</td>
                      <td align="center">Sannin</td>
                      <td align="center">196950</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td height="17" align="center">Stixy kaguya</td>
                      <td align="center">51</td>

                      <td align="center">Sannin</td>
                      <td align="center">195961</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td height="17" align="center">Guim_Glad</td>
                      <td align="center">53</td>
                      <td align="center">Sannin</td>
                      <td align="center">184218</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td height="17" align="center">Suvaco de Cobra</td>
                      <td align="center">54</td>
                      <td align="center">Sannin</td>
                      <td align="center">176450</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td height="17" align="center">Uchiha_Madara_Seagal</td>
                      <td align="center">41</td>

                      <td align="center">ANBU</td>
                      <td align="center">171164</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td height="17" align="center"> Sieghart</td>
                      <td align="center">48</td>
                      <td align="center">ANBU</td>
                      <td align="center">169873</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/88.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td height="17" align="center">Shu_Suzaku</td>
                      <td align="center">45</td>
                      <td align="center">ANBU</td>
                      <td align="center">164419</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td height="17" align="center">Drown97-Elkim</td>
                      <td align="center">45</td>

                      <td align="center">ANBU</td>
                      <td align="center">162138</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td height="17" align="center">ItachiSama.</td>
                      <td align="center">48</td>
                      <td align="center">ANBU</td>
                      <td align="center">157723</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td height="17" align="center">r0nd1k99</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td align="center">156553</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td height="17" align="center">Vieri</td>
                      <td align="center">47</td>

                      <td align="center">Sannin</td>
                      <td align="center">150365</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td height="17" align="center">xXHashirama_SenjuXx_</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">142266</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/78.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td height="17" align="center">itachi_jack_lee</td>
                      <td align="center">47</td>
                      <td align="center">ANBU</td>
                      <td align="center">141173</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td height="17" align="center">SenpaiItachi</td>
                      <td align="center">45</td>

                      <td align="center">ANBU</td>
                      <td align="center">140319</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td height="17" align="center">Yahik&amp;#963;</td>
                      <td align="center">49</td>
                      <td align="center">ANBU</td>
                      <td align="center">140242</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
                    </tr>

                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>
                      <td height="17" align="center">Xulin</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">139214</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/56.png" /></td>
                    </tr>
                    <tr>
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>
                      <td height="17" align="center">___Uchiha Itachi___</td>
                      <td align="center">40</td>

                      <td align="center">ANBU</td>
                      <td align="center">138954</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td height="17" align="center">RROOCCKK LEE.</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">138433</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td height="17" align="center">f4kn</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">135007</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                  </table>
                </div>
                <div class="pane">
                  <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                            <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

                    <tr bgcolor="#413625">
                      <td  width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" height="17" align="center">Paladino_Warlord </td>
                      <td align="center" width="100">51</td>
                      <td width="110" align="center">Sannin</td>
                      <td align="center" width="110">205806</td>

                      <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/53.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td height="17" align="center">Nara         Shikamaru</td>
                      <td align="center">52</td>

                      <td align="center">Sannin</td>
                      <td align="center">186989</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td height="17" align="center">jack5</td>
                      <td align="center">45</td>
                      <td align="center">Sannin</td>
                      <td align="center">173290</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td height="17" align="center">Okazaki</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">172575</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td height="17" align="center">joao18lx</td>
                      <td align="center">44</td>

                      <td align="center">ANBU</td>
                      <td align="center">171123</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/116.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td height="17" align="center">D4rk 4ngel </td>
                      <td align="center">52</td>
                      <td align="center">Sannin</td>
                      <td align="center">167015</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td height="17" align="center">kaguya thiagoso</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">165625</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td height="17" align="center">Pedregulho [off]</td>
                      <td align="center">41</td>

                      <td align="center">ANBU</td>
                      <td align="center">162989</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td height="17" align="center">Deidara_skulL</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">160375</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/52.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td height="17" align="center">Ryiey</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td align="center">156319</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td height="17" align="center">Paladino_Bakuryu</td>
                      <td align="center">41</td>

                      <td align="center">ANBU</td>
                      <td align="center">153555</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td> 
                      <td height="17" align="center">Vuikutoru</td>

                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">150695</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>

                      <td align="center">13&ordm;</td>
                      <td height="17" align="center">kusch</td>
                      <td align="center">45</td>
                      <td align="center">ANBU</td>
                      <td align="center">148537</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>

                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td height="17" align="center">_Kimimaro Sama_</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>

                      <td align="center">146103</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>
                      <td height="17" align="center">Deidara_Akatsuki47</td>

                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td align="center">144513</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>

                      <td align="center">16&ordm;</td>
                      <td height="17" align="center">Sangue nu Zooii</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">138204</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>

                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td height="17" align="center">ShikaBiluu</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>

                      <td align="center">137722</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/52.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>
                      <td height="17" align="center">_Hide_</td>

                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">135434</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>

                      <td align="center">19&ordm;</td>
                      <td height="17" align="center">.ScolfielD.</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">131410</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>

                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td height="17" align="center">20comer70correr</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>

                      <td align="center">131158</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>
                      <td height="17" align="center">Uchiha     Papito</td>

                      <td align="center">35</td>
                      <td align="center">Jounin</td>
                      <td align="center">129959</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>

                      <td align="center">22&ordm;</td>
                      <td height="17" align="center">Goku Lee</td>
                      <td align="center">32</td>
                      <td align="center">Jounin</td>
                      <td align="center">124932</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>

                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>
                      <td height="17" align="center">Uchiha         Itachi</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>

                      <td align="center">123889</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>
                      <td height="17" align="center">sasuke_Uchiha57</td>

                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">122363</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>

                      <td align="center">25&ordm;</td>
                      <td height="17" align="center">KAKASHISAN</td>
                      <td align="center">34</td>
                      <td align="center">Jounin</td>
                      <td align="center">120882</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>

                    </tr>
                  </table>
                </div>
<div class="pane">
                 <strong style="font-size:16px; color:#FFF;"><?php echo t('halldafama.h9')?></strong><br />
                 <strong style="font-size:16px; color:#FFF"><?php echo t('halldafama.13')?> - Akatsuki Legend\\\'s ( <?php echo t('halldafama.h14')?> - 28871802)</strong><br /><br />
                  <table width="700" cellpadding="0" cellspacing="0" style="color:#FFF">
                    <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="80" align="center">&nbsp;</td>
                            <td width="120" align="center"><b style="color:#FFFFFF"><?php echo t('geral.vila')?></b></td>
                            <td width="190" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
                    <tr bgcolor="#413625">
                      <td width="80" align="center"><img src="<?php echo img() ?>layout/trophy.png" width="16" height="16" /></td>
                      <td width="120" align="center"><img src="<?php echo img() ?>layout/bandanas/6.jpg" /></td>
                      <td width="190" height="17" align="center">X_Zell_X</td>
                      <td width="110" align="center">64</td>
                      <td width="130" align="center">Sannin</td>

                      <td width="100" align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                  </table>
                  <br /><br />
                  <strong style="font-size:16px; color:#FFF;"><?php echo t('halldafama.h10')?></strong><br />
                 <strong style="font-size:16px; color:#FFF"><?php echo t('halldafama.h15')?> Nuvem - Paladino Teen ( <?php echo t('halldafama.h14')?>: 184439 )</strong>
                 <br /><br />
<table width="700" cellpadding="0" cellspacing="0" style="color:#FFF">

                   <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.integrantes')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
                    <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/trophy-bronze.png" width="16" height="16" /></td>
                      <td width="100" align="center">3º</td>
                      <td width="140" align="center">_Sephiroth_</td>

                      <td width="100" align="center">62</td>
                      <td width="110" align="center">Sannin</td>
                      <td align="center" width="110">278739</td>
                      <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr>
                      <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>

                      <td align="center" bgcolor="#251a13">17&ordm;</td>
                      <td align="center" bgcolor="#251a13">Comigo eh na base do beijo!</td>
                      <td align="center" bgcolor="#251a13">54</td>
                      <td align="center" bgcolor="#251a13">Sannin</td>
                      <td align="center" bgcolor="#251a13">214410</td>
                      <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>

                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">82º</td>
                      <td align="center">SharingaanN.</td>
                      <td align="center">46</td>
                      <td align="center">ANBU</td>

                      <td align="center">171966</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
                    </tr>
                    <tr>
                      <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center" bgcolor="#251a13">26º</td>
                      <td align="center" bgcolor="#251a13">ByakuganN.</td>

                      <td align="center" bgcolor="#251a13">51</td>
                      <td align="center" bgcolor="#251a13">Sannin</td>
                      <td align="center" bgcolor="#251a13">205307</td>
                      <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
</tr>
                  </table>
                </div>
            </div>
       		  <div class="pane">
          <!-- the tabs -->
          <ul class="tabs">
            <li><a href="#">Top Geral</a></li>
          </ul>
          <!-- tab "panes" -->
    <div class="pane">
          
<table width="730" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="10" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="60" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="60" align="center"><b style="color:#FFFFFF">Level</b></td>
                            <td width="70" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="80" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao_final')?></b></td>
                            <td width="50" align="center"><b style="color:#FFFFFF"><?php echo t('geral.vitorias')?></b></td>
                            <td width="50" align="center"><b style="color:#FFFFFF"><?php echo t('geral.derrotas')?></b></td>
                            <td width="50" align="center"><b style="color:#FFFFFF"><?php echo t('geral.empates')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr  bgcolor="#413625">
                <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                <td width="60" align="center">1&ordm;</td>
                <td width="140" align="center">Lucas F</td>
                <td width="60" align="center">62</td>
                <td width="70" align="center">Sanin</td>
                <td width="80" align="center">150196</td>
                <td width="50" align="center">1338</td>
                <td width="50" align="center">83</td>
                <td width="50" align="center">30</td>
                <td width="100" align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">2&ordm;</td>
                <td align="center">Darkness_M4dara</td>
                <td align="center">61</td>
                <td align="center">Sanin</td>
                <td align="center">146669</td>
                <td align="center">1267</td>
                <td align="center">61</td>
                <td align="center">29</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/104.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                <td align="center">3&ordm;</td>
                <td align="center">_Uchiha_Demolidor_</td>
                <td align="center">61</td>
                <td align="center">Sanin</td>
                <td align="center">144107</td>
                <td align="center">1261</td>
                <td align="center">103</td>
                <td align="center">29</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">4&ordm;</td>
                <td align="center">!!Karin_Benzema!!</td>
                <td align="center">60</td>
                <td align="center">Sanin</td>
                <td align="center">138935</td>
                <td align="center">1150</td>
                <td align="center">63</td>
                <td align="center">36</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">5&ordm;</td>
                <td align="center">King Uchiha</td>
                <td align="center">58</td>
                <td align="center">Sanin</td>
                <td align="center">135316</td>
                <td align="center">1163</td>
                <td align="center">82</td>
                <td align="center">38</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">6&ordm;</td>
                <td align="center">KoushirouSama</td>
                <td align="center">60</td>
                <td align="center">Sanin</td>
                <td align="center">134068</td>
                <td align="center">1110</td>
                <td align="center">114</td>
                <td align="center">33</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">7&ordm;</td>
                <td align="center">WENTZ</td>
                <td align="center">59</td>
                <td align="center">Sanin</td>
                <td align="center">133003</td>
                <td align="center">1072</td>
                <td align="center">82</td>
                <td align="center">34</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/85.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">8&ordm;</td>
                <td align="center">[S][K][U][L][L]</td>
                <td align="center">58</td>
                <td align="center">Sanin</td>
                <td align="center">131492</td>
                <td align="center">993</td>
                <td align="center">22</td>
                <td align="center">17</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
              </tr>
              <tr  bgcolor="#413625">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">9&ordm;</td>
                <td align="center">Kill Galinha_</td>
                <td align="center">58</td>
                <td align="center">Sanin</td>
                <td align="center">129947</td>
                <td align="center">955</td>
                <td align="center">45</td>
                <td align="center">24</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
              </tr>
              <tr bgcolor="#251a13">
                <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                <td align="center">10&ordm;</td>
                <td align="center">Neji Byakugan</td>
                <td align="center">59</td>
                <td align="center">Sanin</td>
                <td align="center">128928</td>
                <td align="center">979</td>
                <td align="center">73</td>
                <td align="center">25</td>
                <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
              </tr>
            </table>
          <br />
          <br />
    </div>
        </div>
        <!-- PREMIAÇÂO R3 -->
        <!-- tab "panes" -->
              <div class="pane">
                <!-- the tabs -->
                <ul class="tabs">

                  <li><a href="#">Kages</a></li>
                  <li><a href="#">Konoha</a></li>
                  <li><a href="#">Areia</a></li>
                  <li><a href="#">Nevoa</a></li>
                  <li><a href="#">Som</a></li>
                  <li><a href="#">Akatsuki</a></li>
                  <li><a href="#">Nuvem</a></li>
                  <li><a href="#">Chuva</a></li>
                  <li><a href="#">Pedra</a></li>
                  <li><a href="#">Extras</a></li>
                </ul>
                <!-- tab "panes" -->
                <div class="pane">

                <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                    <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
                    <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/trophy.png" width="16" height="16" /></td>
                      <td  width="100" align="center"><img src="<?php echo img() ?>layout/bandanas/1.jpg" /></td>
                      <td width="140" align="center">Scorpius</td>
                      <td width="100" align="center">55</td>
                      <td width="110" align="center">Sannin</td>
                      <td width="110" align="center">385854</td>
                      <td width="100" align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>

                      <td align="center"><img src="<?php echo img() ?>layout/bandanas/6.jpg" /></td>
                      <td height="17" align="center"> Kozure Okami</td>
                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td align="center">313635</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/116.png" /></td>
                    </tr>

                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td align="center"><img src="<?php echo img() ?>layout/bandanas/7.jpg" /></td>
                      <td height="17" align="center">.:XxIzanagi_ShiroxX:.</td>
                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td align="center">319120</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/trophy-bronze.png" width="16" height="16" /></td>
                      <td align="center"><img src="<?php echo img() ?>layout/bandanas/3.jpg" /></td>
                      <td height="17" align="center">___Uchiha Itachi___</td>
                      <td align="center">52</td>
                      <td align="center">Sannin</td>

                      <td align="center">341626</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td align="center"><img src="<?php echo img() ?>layout/bandanas/5.jpg" /></td>
                      <td height="17" align="center">SarkhanVol</td>
                      <td align="center">46</td>
                      <td align="center">Sannin</td>
                      <td align="center">303267</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/126.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td align="center"><img src="<?php echo img() ?>layout/bandanas/4.jpg" /></td>
                      <td height="17" align="center">WaRninG.</td>
                      <td align="center">45</td>
                      <td align="center">Sannin</td>
                      <td align="center">278532</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>

                      <td align="center"><img src="<?php echo img() ?>layout/bandanas/8.jpg" /></td>
                      <td height="17" align="center">S0nne</td>
                      <td align="center">52</td>
                      <td align="center">Sannin</td>
                      <td align="center">336492</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/105.png" /></td>
                    </tr>

                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td align="center"><img src="<?php echo img() ?>layout/bandanas/2.jpg" /></td>
                      <td align="center">[S][K][U][L][L]</td>
                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td align="center">306771</td>

                      <td align="center"><img src="<?php echo img() ?>layout/dojo/104.png" /></td>
                    </tr>
                  </table>
                </div>
                <div class="pane">
                  <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                            <tr>
                    <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td width="70" align="center">&nbsp;</td>
                                <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                                <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                                <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                                <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                                <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                                <td width="100" align="center">&nbsp;</td>
                              </tr>
                            </table></td>
                        </tr>
                      </table></td>
                  </tr>

                  <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/trophy.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" align="center">Scorpius</td>
                      <td width="100" align="center">55</td>
                      <td width="110" align="center">Sannin</td>
                      <td width="110" align="center">385854</td>
                      <td width="100" align="center" ><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/trophy-silver.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td align="center">Obito1000</td>
                      <td align="center">54</td>
                      <td align="center">Sannin</td>
                      <td align="center">374955</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td align="center">Hinata Sama</td>
                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td align="center">313372</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td align="center">secorwa10</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">302456</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td align="center">xyyx</td>
                      <td align="center">50</td>
                      <td align="center">Sannin</td>
                      <td align="center">298807</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td align="center">Nazumato</td>
                      <td align="center">51</td>
                      <td align="center">Sannin</td>
                      <td align="center">292406</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td align="center">iron_uchiha</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">253403</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td align="center">_KRS_</td>
                      <td align="center">42</td>
                      <td align="center">Sannin</td>
                      <td align="center">243291</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/73.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td align="center">FX NARUTO</td>
                      <td align="center">46</td>
                      <td align="center">Sannin</td>
                      <td align="center">242368</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td align="center">Raquel_Scheffer</td>
                      <td align="center">42</td>
                      <td align="center">Sannin</td>
                      <td align="center">242363</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/109.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td align="center">  Naruto</td>
                      <td align="center">45</td>
                      <td align="center">Sannin</td>
                      <td align="center">225248</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td align="center">XxChuckxX</td>
                      <td align="center">45</td>
                      <td align="center">Sannin</td>
                      <td align="center">222454</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td align="center">Marcelo_Sennin</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">219906</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td align="center">xXMinato_NamikazeXx</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">219482</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td align="center">        rock  lee</td>
                      <td align="center">46</td>
                      <td align="center">Sannin</td>
                      <td align="center">216543</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td align="center">xSarutobi x Asumax</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">216211</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/79.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td align="center">toronto</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">211845</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td align="center">_...neji..._</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td align="center">211792</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td align="center">.Fell.</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">209818</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td align="center">Pensador</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">209584</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td align="center">saninn_pervertido</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">207982</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>
                      <td align="center">jonixd</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">206601</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/1.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>

                      <td align="center">Laprat_destructor</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">206140</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/88.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td align="center">Ryu.</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">203203</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td align="center">Otomo</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">202601</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/116.png" /></td>
                    </tr>
                  </table>
                </div>
                <div class="pane">
                  <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                           <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

                    <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" height="17" align="center">[S][K][U][L][L]</td>
                      <td align="center" width="100">49</td>
                      <td align="center" width="110">Sannin</td>
                      <td align="center" width="110">306771</td>
                      <td width="100" align="center" ><img src="<?php echo img() ?>layout/dojo/104.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td height="17" align="center">Tyrel</td>
                      <td align="center">50</td>
                      <td align="center">Sannin</td>
                      <td align="center">293730</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/113.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td height="17" align="center">.:Dokgan:.</td>
                      <td align="center">50</td>
                      <td align="center">Sannin</td>
                      <td align="center">287512</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/88.png" /></td>
   </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td height="17" align="center">GAME/OVER</td>
                      <td align="center">51</td>
                      <td align="center">Sannin</td>
                      <td align="center">283209</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/115.png" /></td>
   </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td height="17" align="center">Dark.Gambite</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">276011</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/53.png" /></td>
   </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td height="17" align="center">ET Hyuuga</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">274489</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/80.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td height="17" align="center">Raio Elemental    X</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">262191</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td height="17" align="center">Divinorum    &amp;#2384;</td>
                      <td align="center">46</td>
                      <td align="center">Sannin</td>
                      <td align="center">257166</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/86.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td height="17" align="center">Lestat de    Lioncourt</td>
                      <td align="center">46</td>
                      <td align="center">Sannin</td>
                      <td align="center">256873</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/86.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td height="17" align="center">Shakespeare    &amp;#2384;</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">246450</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/42.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td height="17" align="center">.Uchiha_Rafael.</td>
                      <td align="center">45</td>
                      <td align="center">Sannin</td>
                      <td align="center">240614</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td height="17" align="center">_GBL_</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">229192</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td height="17" align="center">Vini</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">215753</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td height="17" align="center">_Terrorist_Teddy_</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">214657</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/104.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td height="17" align="center">Baang</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">205493</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td height="17" align="center">_ZeRo_</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">205056</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td height="17" align="center">yamato_trinchador</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">204678</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td height="17" align="center">Eutass Kid</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">203343</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td height="17" align="center">Tio_Guedes</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">201653</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/66.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td height="17" align="center">sasuke_chidori_negro</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">197503</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td height="17" align="center">Jack Estripador</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">195871</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>
                      <td height="17" align="center">Tickoo</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">189526</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>

                      <td height="17" align="center">Sueco</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">188090</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/52.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td height="17" align="center">karin_sama_02</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">187511</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/117.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td height="17" align="center">dragao    branco de olhos azuis</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">186091</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/86.png" /></td>
  </tr>
                  </table>
                </div>
                <div class="pane">
                  <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                           <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

                     <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/trophy-bronze.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" height="17" align="center">___Uchiha Itachi___</td>
                      <td align="center" width="100">52</td>
                      <td align="center" width="110">Sannin</td>
                      <td align="center" width="110">341626</td>
                      <td width="100" align="center" ><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td height="17" align="center">Takezo.</td>
                      <td align="center">54</td>
                      <td align="center">Sannin</td>
                      <td align="center">329893</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/129.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td height="17" align="center">Chibaku Tensei</td>
                      <td align="center">53</td>
                      <td align="center">Sannin</td>
                      <td align="center">314141</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
   </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td height="17" align="center">ace</td>
                      <td align="center">51</td>
                      <td align="center">Sannin</td>
                      <td align="center">310234</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
   </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td height="17" align="center">   Minato Namizake</td>
                      <td align="center">52</td>
                      <td align="center">Sannin</td>
                      <td align="center">301090</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
   </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td height="17" align="center">Shark Killer</td>
                      <td align="center">52</td>
                      <td align="center">Sannin</td>
                      <td align="center">293061</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td height="17" align="center">** PainSama **</td>
                      <td align="center">50</td>
                      <td align="center">Sannin</td>
                      <td align="center">257327</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/56.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td height="17" align="center">Maito_Gai85</td>
                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td align="center">254033</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td height="17" align="center">God of War</td>
                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td align="center">253191</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td height="17" align="center">Apocality</td>
                      <td align="center">46</td>
                      <td align="center">Sannin</td>
                      <td align="center">247298</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/2.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td height="17" align="center">VAI_SE_XXXX</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">245882</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td height="17" align="center">__.:BAITOLA_DA_PAVUNA:.__</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td align="center">238534</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td height="17" align="center">Ultimatte_Gohan</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">235734</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td height="17" align="center">Uchiha_Madara_Seagal</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td align="center">235268</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td height="17" align="center">Bruno_s_z</td>
                      <td align="center">45</td>
                      <td align="center">Sannin</td>
                      <td align="center">233494</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td height="17" align="center">Yodaime_Minato</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">227218</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td height="17" align="center">Tsuken</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">226804</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/52.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td height="17" align="center">Lord_carioca</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">225902</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td height="17" align="center">Madara_vs</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">225611</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td height="17" align="center">Kurai no Zabuza</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">220282</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/43.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td height="17" align="center"> Pedro</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">216958</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/86.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>
                      <td height="17" align="center">Hirosh Omegawa</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">215503</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>

                      <td height="17" align="center">Nala_Pain</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">215052</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td height="17" align="center">PaladinoKimimaro</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">212689</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td height="17" align="center">internet_XXX</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">211668</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/105.png" /></td>
  </tr>
                  </table>
                </div>
                <div class="pane">
                  <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                           <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

                     <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" align="center">.:XxIzanagi_ShiroxX:.</td>
                      <td width="100" align="center">49</td>
                      <td width="110" align="center">Sannin</td>
                      <td width="110" align="center">319120</td>
                      <td width="100" align="center" ><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td align="center">z</td>
                      <td align="center">51</td>
                      <td align="center">Sannin</td>
                      <td align="center">308304</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td align="center">..::Uchiha::Fudencio::..</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">278732</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
   </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td align="center">~Farway~</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">277277</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/86.png" /></td>
   </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td align="center">Xx.:Madara_Sempai.:xX</td>
                      <td align="center">45</td>
                      <td align="center">Sannin</td>
                      <td align="center">275428</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
   </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td align="center">.:F.O.X.TCHULINHA:.</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">257409</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/91.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td align="center">Xx:.Mitsurugy.:.Blasken.:xX</td>
                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td align="center">249691</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/77.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td align="center">.:F.O.X.D.E.M.U</td>
                      <td align="center">46</td>
                      <td align="center">Sannin</td>
                      <td align="center">241104</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/129.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td align="center">.:F.O.X.PISTOLINHA:.</td>
                      <td align="center">45</td>
                      <td align="center">Sannin</td>
                      <td align="center">234540</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/75.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td align="center">Jiraiya_Descartes</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">223613</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td align="center">Orochimaru_Fushi_Ten</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">218511</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/76.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td align="center">Massacration Hyuuga</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">215251</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td align="center">Uchiha         Itachi</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">213363</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td align="center">BigMinato</td>
                      <td align="center">45</td>
                      <td align="center">Sannin</td>
                      <td align="center">210182</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/88.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td align="center">..Tayuya.of.Sound..</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">209938</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/89.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td align="center">Juniot_Lee</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">209782</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td align="center">ALONE</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">207329</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td align="center">.::RafaeL::.</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">196746</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td align="center">Ninja Preto Canibal do Piaui</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">191399</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/101.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td align="center">MagnuM _ApocalypsE</td>
                      <td align="center">36</td>
                      <td align="center">ANBU</td>
                      <td align="center">188015</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td align="center">P4P3L</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">187101</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/61.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>
                      <td align="center">.:F.O.X.SUKAKU:.</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">185727</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/86.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>

                      <td align="center">DarkSwon</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">185498</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/52.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td align="center">kuchiki_Igor</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">183978</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td align="center">OAKLEEY</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">181905</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
  </tr>
                  </table>
                </div>
                <div class="pane">
                <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                           <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
                    <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" align="center"> Kozure    Okami</td>
                      <td width="100" align="center">49</td>
                      <td width="110" align="center">Sannin</td>
                      <td width="110" align="center">313635</td>
                      <td width="100" align="center" ><img src="<?php echo img() ?>layout/dojo/116.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td align="center">X_Zell_X</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">302221</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td align="center">Violate</td>
                      <td align="center">51</td>
                      <td align="center">Sannin</td>
                      <td align="center">281955</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/52.png" /></td>
   </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td align="center">Botelho</td>
                      <td align="center">46</td>
                      <td align="center">Sannin</td>
                      <td align="center">270604</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
   </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td align="center">thg_bad</td>
                      <td align="center">46</td>
                      <td align="center">Sannin</td>
                      <td align="center">253455</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
   </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td align="center">Black Knight</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">249928</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td align="center">.:.madara_tobi.:.</td>
                      <td align="center">45</td>
                      <td align="center">Sannin</td>
                      <td align="center">244659</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td align="center">Delin_Delux</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">244384</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td align="center">Extincion</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">240167</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/54.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td align="center">BeanN</td>
                      <td align="center">45</td>
                      <td align="center">Sannin</td>
                      <td align="center">237512</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td align="center">lShikamaru_Naral</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">237340</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td align="center">Uchiha_TNT</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">233248</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td align="center">ABUSADINHO_</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td align="center">227124</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/43.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td align="center">izanagi_itachi</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">225733</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td align="center">Shino Aburame</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">221198</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/58.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td align="center">jirayasenninsupremo</td>
                      <td align="center">46</td>
                      <td align="center">Sannin</td>
                      <td align="center">220628</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td align="center">Legacy_Sama</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">219341</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/53.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td align="center">Itachi san</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">213721</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td align="center">Jrsol_Kurenai</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">213655</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/91.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td align="center">Dm_died</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">212839</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/116.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td align="center">Jrsol</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">208274</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>
                      <td align="center">Dm:.</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">202921</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/99.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>

                      <td align="center">BladeInsane</td>
                      <td align="center">36</td>
                      <td align="center">ANBU</td>
                      <td align="center">199562</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td align="center">Ithinkso</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">199552</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td align="center">ShadOw__Ms</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">197347</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
  </tr>
                  </table>
                </div>
                <div class="pane">
                  <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                           <tr>
                                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                                          <tr>
                                            <td width="70" align="center">&nbsp;</td>
                                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                                            <td width="100" align="center">&nbsp;</td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table></td>
                              </tr>

                   <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" align="center">SarkhanVol</td>
                      <td width="100" align="center">46</td>
                      <td width="110" align="center">Sannin</td>
                      <td width="110" align="center">303267</td>
                      <td width="100" align="center" ><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td align="center">Afterlife</td>
                      <td align="center">52</td>
                      <td align="center">Sannin</td>
                      <td align="center">289813</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td align="center">Knight of Cy</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">281406</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
   </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td align="center">DEVIL_AZAZEL</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">274514</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/86.png" /></td>
   </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td align="center">UchihaItachiMangekyou</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">266369</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
   </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td align="center">mandara_gun</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">262621</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/104.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td align="center">Uchiha Insany</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">248895</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td align="center">Victor H.</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">247527</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/46.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td align="center">Uchiha&nbsp;Obito</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">232981</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td align="center">DAIKI_UCHILA_OBITO</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">220087</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td align="center">Itachi_Djuninho</td>
                      <td align="center">49</td>
                      <td align="center">Sannin</td>
                      <td align="center">219958</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td align="center">The Knight Of The Darck</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">219774</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td align="center">DEVIL_AZAZEL_3</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">217976</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/129.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td align="center">Marukimi</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">213988</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td align="center">Fread Brack</td>
                      <td align="center">45</td>
                      <td align="center">Sannin</td>
                      <td align="center">211553</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td align="center">Orochimaru_du_Mal</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">210567</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/76.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td align="center">_.Rodrigo._.Uchiha._</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">210010</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td align="center">_Destroyer_</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">206963</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/85.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td align="center">Foda_d__</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">205722</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/116.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td align="center">Brandon Neon</td>
                      <td align="center">36</td>
                      <td align="center">ANBU</td>
                      <td align="center">205458</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/77.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td align="center">BiRo_hyuga</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">200138</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/57.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>
                      <td align="center">.::.Player.::.</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">198547</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>

                      <td align="center">Hondie</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">197492</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/58.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td align="center">__dERSON</td>
                      <td align="center">34</td>
                      <td align="center">Jounin</td>
                      <td align="center">195829</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td align="center">Byet</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">195421</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
  </tr>
                  </table>
                </div>
                <div class="pane">
                  <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                            <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
                     <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" align="center">S0nne</td>
                      <td width="100" align="center">52</td>
                      <td width="110" align="center">Sannin</td>
                      <td width="110" align="center">336492</td>
                      <td width="100" align="center" ><img src="<?php echo img() ?>layout/dojo/105.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td align="center">Natsuko_Takarashi</td>
                      <td align="center">50</td>
                      <td align="center">Sannin</td>
                      <td align="center">285128</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td align="center">Kibaku_Pein</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td align="center">257403</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
   </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td align="center">ShiniGami_VI</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">245049</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/141.png" /></td>
   </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td align="center">:.:Dezalmado:.:</td>
                      <td align="center">46</td>
                      <td align="center">Sannin</td>
                      <td align="center">243581</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/75.png" /></td>
   </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td align="center">&gt;EXTERMINADOR&lt;</td>
                      <td align="center">42</td>
                      <td align="center">ANBU</td>
                      <td align="center">236218</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td align="center">The_Devil_in_me</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td align="center">235772</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td align="center">Douglas_Nagato_CM</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">232956</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td align="center">:.Kelly_Hyuuga.:</td>
                      <td align="center">44</td>
                      <td align="center">ANBU</td>
                      <td align="center">226210</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/69.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td align="center">Bozolino_Sadam</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">224338</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td align="center">Minato_Ren</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">215196</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td align="center">ThDark</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">213753</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td align="center">Mach5</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">212805</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td align="center">Lord_MagnuM</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">210130</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/113.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td align="center">Kurenai_Y_Sama</td>
                      <td align="center">35</td>
                      <td align="center">ANBU</td>
                      <td align="center">206777</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/91.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td align="center">Illusionist_RS</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">204894</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/104.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td align="center">* La Muerte *</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">200739</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/76.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td align="center"> Utakata</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">193912</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/143.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td align="center">.::Rock.Lee::.</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">192644</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td align="center">Rock_Lee_da_Chuva</td>
                      <td align="center">36</td>
                      <td align="center">ANBU</td>
                      <td align="center">190978</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td align="center"> BushidO</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">190880</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>
                      <td align="center">x_Game_Over_x</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">189406</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>

                      <td align="center">manghara</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">187829</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td align="center">leekiller</td>
                      <td align="center">36</td>
                      <td align="center">ANBU</td>
                      <td align="center">179969</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td align="center">Sharingan_Assassin</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">177722</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
  </tr>
                  </table>
                </div>
                <div class="pane">
                  <table width="700" border="0" cellpadding="0" cellspacing="0" style="color:#FFF">
                            <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

                    <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/award_star_gold_3.png" width="16" height="16" /></td>
                      <td width="100" align="center">1&ordm;</td>
                      <td width="140" align="center">WaRninG.</td>
                      <td width="100" align="center">45</td>
                      <td width="110" align="center">Sannin</td>
                      <td width="110" align="center">278532</td>
                      <td width="100" align="center" ><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
                    </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">2&ordm;</td>
                      <td align="center">Pegasus_alado</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">273444</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/52.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">3&ordm;</td>

                      <td align="center">Kill Galinha</td>
                      <td align="center">47</td>
                      <td align="center">Sannin</td>
                      <td align="center">268950</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/46.png" /></td>
   </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">4&ordm;</td>
                      <td align="center">Souren</td>
                      <td align="center">46</td>
                      <td align="center">Sannin</td>
                      <td align="center">268715</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/53.png" /></td>
   </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_silver_3.png" width="16" height="16" /></td>
                      <td align="center">5&ordm;</td>
                      <td align="center">Kuchiki Uchiha</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">250296</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
   </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">6&ordm;</td>

                      <td align="center">The_King_Of_Noob_s</td>
                      <td align="center">45</td>
                      <td align="center">Sannin</td>
                      <td align="center">248791</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/63.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">7&ordm;</td>
                      <td align="center">Magnanimus</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">241060</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/85.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">8&ordm;</td>
                      <td align="center">mumummum</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">234089</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">9&ordm;</td>

                      <td align="center">Ka Ka Shi</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">227014</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">10&ordm;</td>
                      <td align="center">JefreyllIXIlll</td>
                      <td align="center">41</td>
                      <td align="center">ANBU</td>
                      <td align="center">220896</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/4.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">11&ordm;</td>
                      <td align="center">Falcão Negro</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">209305</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/91.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">12&ordm;</td>

                      <td align="center">Sucesso</td>
                      <td align="center">43</td>
                      <td align="center">ANBU</td>
                      <td align="center">203728</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">13&ordm;</td>
                      <td align="center">GBL_Uchiha</td>
                      <td align="center">40</td>
                      <td align="center">ANBU</td>
                      <td align="center">195312</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/71.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">14&ordm;</td>
                      <td align="center">Victor_DM</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">195253</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/84.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">15&ordm;</td>

                      <td align="center">_1Madara1_</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">190610</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">16&ordm;</td>
                      <td align="center">__tikinho__</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">189006</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/72.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">17&ordm;</td>
                      <td align="center">Jacques Mesrine</td>
                      <td align="center">34</td>
                      <td align="center">Jounin</td>
                      <td align="center">187711</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/116.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">18&ordm;</td>

                      <td align="center">Shark_Slayer</td>
                      <td align="center">37</td>
                      <td align="center">ANBU</td>
                      <td align="center">187681</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/52.png" /></td>
                    </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">19&ordm;</td>
                      <td align="center">Ryudan</td>
                      <td align="center">36</td>
                      <td align="center">ANBU</td>
                      <td align="center">184811</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/103.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">20&ordm;</td>
                      <td align="center">Fukurokuju.</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">184027</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">21&ordm;</td>

                      <td align="center">Nerimaru</td>
                      <td align="center">34</td>
                      <td align="center">Jounin</td>
                      <td align="center">181208</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/62.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">22&ordm;</td>

                      <td align="center">_Uchiha_Demolidor_</td>
                      <td align="center">38</td>
                      <td align="center">ANBU</td>
                      <td align="center">180242</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/52.png" /></td>
  </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">23&ordm;</td>

                      <td align="center">:Uchiha.Madara:</td>
                      <td align="center">33</td>
                      <td align="center">Jounin</td>
                      <td align="center">179953</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
  </tr>
                    <tr bgcolor="#251a13">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">24&ordm;</td>

                      <td align="center">Kabuto_kenpachi</td>
                      <td align="center">35</td>
                      <td align="center">ANBU</td>
                      <td align="center">178638</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/101.png" /></td>
  </tr>
                    <tr bgcolor="#413625">

                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">25&ordm;</td>
                      <td align="center">Rock_leeFL</td>
                      <td align="center">39</td>
                      <td align="center">ANBU</td>
                      <td align="center">177482</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/3.png" /></td>
  </tr>
                  </table>
                </div>
<div class="pane">
                 <strong style="font-size:16px; color:#FFF;"><?php echo t('halldafama.h9')?></strong><br />
                 <strong style="font-size:16px; color:#FFF"><?php echo t('halldafama.h13')?> - Hunters of Npc\\\'s ( <?php echo t('halldafama.h14')?> - 78900)</strong><br /><br />
                  <table width="700" cellpadding="0" cellspacing="0" style="color:#FFF">
                    <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="80" align="center">&nbsp;</td>
                            <td width="120" align="center"><b style="color:#FFFFFF"><?php echo t('geral.vila')?></b></td>
                            <td width="190" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="130" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
                    <tr bgcolor="#413625">
                      <td width="80" align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td width="120" align="center"><img src="<?php echo img() ?>layout/bandanas/3.jpg" /></td>
                      <td width="190" height="17" align="center">Shark Killer</td>
                      <td width="110" align="center">52</td>
                      <td width="130" align="center">Sannin</td>

                      <td width="100" align="center"><img src="<?php echo img() ?>layout/dojo/122.png" /></td>
                    </tr>
                  </table>
                  <br /><br />
                  <strong style="font-size:16px; color:#FFF;"><?php echo t('halldafama.h10')?></strong><br />
                 <strong style="font-size:16px; color:#FFF"><?php echo t('halldafama.h11')?> Konoha - Scorpius, Nazu, Iron, Gbl ( <?php echo t('halldafama.h14')?>: 206886 )</strong>
                 <br /><br />
<table width="700" cellpadding="0" cellspacing="0" style="color:#FFF">

                   <tr>
                <td colspan="7" align="center"><table width="730" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="49" class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td width="70" align="center">&nbsp;</td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.ranking')?></b></td>
                            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.integrantes')?></b></td>
                            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.level')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.graduacao')?></b></td>
                            <td width="110" align="center"><b style="color:#FFFFFF"><?php echo t('geral.pontuacao_final')?></b></td>
                            <td width="100" align="center">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
                    <tr bgcolor="#413625">
                      <td width="70" align="center"><img src="<?php echo img() ?>layout/trophy.png" width="16" height="16" /></td>
                      <td width="100" align="center">1º</td>
                      <td width="140" align="center">Scorpius</td>
                      <td width="100" align="center">55</td>
                      <td width="110" align="center">Sannin</td>
                      <td align="center" width="110">385854</td>
                      <td align="center" width="100"><img src="<?php echo img() ?>layout/dojo/60.png" /></td>
                    </tr>
                    <tr>
                      <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>

                      <td align="center" bgcolor="#251a13">20&ordm;</td>
                      <td align="center" bgcolor="#251a13">Nazumato</td>
                      <td align="center" bgcolor="#251a13">51</td>
                      <td align="center" bgcolor="#251a13">Sannin</td>
                      <td align="center" bgcolor="#251a13">292406</td>
                      <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/90.png" /></td>

                    </tr>
                    <tr bgcolor="#413625">
                      <td align="center"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center">48º</td>
                      <td align="center">iron_uchiha</td>
                      <td align="center">48</td>
                      <td align="center">Sannin</td>
                      <td align="center">253403</td>
                      <td align="center"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
                    </tr>
                    <tr>
                      <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/award_star_bronze_3.png" width="16" height="16" /></td>
                      <td align="center" bgcolor="#251a13">95º</td>
                      <td align="center" bgcolor="#251a13">XxChuckxX</td>

                      <td align="center" bgcolor="#251a13">45</td>
                      <td align="center" bgcolor="#251a13">Sannin</td>
                      <td align="center" bgcolor="#251a13">222454</td>
                      <td align="center" bgcolor="#251a13"><img src="<?php echo img() ?>layout/dojo/55.png" /></td>
</tr>
                  </table>
                </div>
            </div>
       </td>
  </tr>
</table>
</div>
<script>
$(function() {
	$("ul.tabs").tabs("> .pane");
});
</script>