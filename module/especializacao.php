<?
	$where = $basePlayer->id_especializacao ? " WHERE id=" . $basePlayer->id_especializacao : "";

	$js_function = $_SESSION['el_js_func_name'] = "f" . md5(rand(1, 512384));
	$js_functionb = $_SESSION['el_js_func_nameb'] = "f" . md5(rand(1, 512384));
	$pay_key_0 = $_SESSION['pay_key_0'] = round(rand(1, 999999)); // Ryou
	$pay_key_1 = $_SESSION['pay_key_1'] = round(rand(1, 999999)); // Coin
?>
<script type="text/javascript">
	function mudaEspecializacao(){
		var v = document.getElementById("especializacao").value;
		
		$(".especializacaoObj").hide();
		
		document.getElementById("dvEspecializacao_" + v).style.display = "block";
		document.getElementById("tbEspecializacao_" + v).style.display = "block";
	}
	
	function entrarEspecializacao() {
		if(confirm("Você realmente quer realmente essa especialização?")) {
			
			$("#btEntrarEspecializacao").attr("disabled", true);
			
			$.ajax({
				url: 'index.php?acao=especializacao_entrar',
				dataType: 'script',
				type: 'post',
				data: {id: document.getElementById("especializacao").value }
			});
		}
	}
	
	function <?= $js_function ?>() {
		if(!confirm("Trocando de especialização fara você perder todas as habilidades e jutsus do mesmo.\n" +
					"Você também perderá qualquer equipamento que requeira sua especialização!\n" + 
					"Também serão necessários 2 créditos.\n" + 
					"Quer continuar?")) {
			return false;	
		}
		
		$.ajax({
			url: 'index.php?acao=especializacao_sair',
			dataType: 'script',
			type: 'post',
			data: {pm: <?= $pay_key_1 ?> }
		});		
	}
	
	function <?= $js_functionb ?>(i, km) {
		$("#pm_" + i).val(!km ? '<?= $pay_key_0 ?>' : '<?= $pay_key_1 ?>');
		
		document.getElementById("pm_" + i).form.pm.value = !km ? '<?= $pay_key_0 ?>' : '<?= $pay_key_1 ?>';
	}
</script>
<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p>Especializações</p></div>
  <p><button id="ajuda">Ajuda ?</button></p>
  <div id="msg_help" class="msg_gai" style="display:none; background:url(<?php echo img() ?>msg/msg_shika.jpg);">
  	<div class="msg"><span style="font-size: 16px; display: block; font-weight: bold; color: #7B1315; margin-bottom: 10px;">Fique Atento!</span>As Especializações funcionam da seguinte maneira: Quando você compra o nível 1, você ganha o upgrade do valor integral, mas quando adquirir o segundo nível, o bonus garantido é baseado no valor bruto, ou seja, sem contar o primeiro nível! Por exemplo: na Especialização Furtividade Ninja, no nível 1 quando você ganhar 100 Ryous irá ganhar 110 Ryous (10%), já no level 2, você irá ganhar 115 Ryous (15%) e não 125 Ryous (que seriam 15% sobre 115).
    </div>
  </div>
  <br />

<?php if($_GET['ok'] == 1): ?>

        <div class="msg_gai" style="background:url(<?php echo img() ?>msg/msg_naruto.jpg);">
		<div class="msg">
				<span style="font-size:16px; display:block; font-weight:bold; color:#7b1315; margin-bottom:10px">Parab&eacute;ns!</span>
				Voc&ecirc; agora voc&ecirc; possui a especializa&ccedil;&atilde;o <?= $basePlayer->nome_especializacao ?>!
		</div>
</div>	
<?php elseif($_GET['ok'] == 2): ?>
<?php
	$rItem = Recordset::query("
		SELECT
			nome
		
		FROM item
		WHERE id=" . (int)decode($_GET['h']))->row_array();
?>

            <div class="msg_gai" style="background:url(<?php echo img() ?>msg/msg_naruto.jpg);">
		<div class="msg">
				<span style="font-size:16px; display:block; font-weight:bold; color:#7b1315; margin-bottom:10px">Parab&eacute;ns!</span>
				Depois de seu esfor&ccedil;o no treinamento, voc&ecirc; aprendeu usar  <?= $rItem['nome'] ?>!
		</div>
</div>	
<?php endif; ?>
<?php
	if($_GET['existent']) {
	
	echo "<div class='msg_gai' style='background:url(" . img() . "msg/msg_error.jpg);'>
		<div class='msg'>
				<span style='font-size:16px; display:block; font-weight:bold; color:#7b1315; margin-bottom:10px'>Problema!</span>
				Voc&ecirc; ja treinou a habilidade escolhida.	
		</div>
</div>	";	

	}
	
	if($_GET['malandro']) {
		malandro("area de coin das especializacoes");
	}
	
	if($_GET['err_ryou']) {
		echo "<div class='msg_gai' style='background:url(". img() . "msg/msg_error.jpg);'>
		<div class='msg'>
				<span style='font-size:16px; display:block; font-weight:bold; color:#7b1315; margin-bottom:10px'>Problema!</span>
				Voc&ecirc; n&atilde;o tem dinheiro suficiente para comprar esse item.
		</div>
</div>	";	

	}
?>
   <br />

      <table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="48" background="<?php echo img() ?>bg_aba.jpg"><table width="730" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="82" align="center">&nbsp;</td>
                  <td width="219" align="center">
                  <b style="color:#FFFFFF">
          			<?php echo $basePlayer->id_especializacao ? "Voc&ecirc; possui a especializa&ccedil;&atilde;o: " : "Escolha uma especializa&ccedil;&atilde;o: " ?>
                  </b>  
                  </td>

                  <td width="429" align="left">
                  		<select <?php echo $basePlayer->id_especializacao ? "disabled='disabled'" : "" ?> name="especializacao" id="especializacao" onchange="mudaEspecializacao();">
						<?php
                            $dvDescs = array();
                            $qEspecializacao = Recordset::query("SELECT * FROM especializacao $where");
                            
                            while($rEspecializacao = $qEspecializacao->row_array()) {
                        ?>
                            <option <?= $basePlayer->id_especializacao == $rEspecializacao['id'] ? "selected='selected'" : "" ?> value="<?= encode($rEspecializacao['id']) ?>"><?= $rEspecializacao['nome'] ?></option>
                        <?php
                                $dvDescs[$rEspecializacao['id']] = $rEspecializacao['descricao'];
                            }
                        ?>
          				</select>
                  </td>
                </tr>
            </table>
            </td>
          </tr>
      </table>
      
    <table width="730" border="0" cellpadding="0" cellspacing="0">
        <tr id="desc_Sharingan">
          <td height="34" colspan="3" align="left" >
          <?php
          	foreach($dvDescs as $k => $d) {
		  ?>
		  <div id="dvEspecializacao_<?= encode($k) ?>" class="especializacaoObj" style="display: none">
          	<?php echo $d ?>
          </div>
		  <?php	
			}
		  ?>
          </td>
        </tr>
        <tr >
          <td height="34" colspan="3" align="right">
         <?php if(!$basePlayer->id_especializacao): ?>
	         <input type="image" id="btEntrarEspecializacao" src="<?php echo img() ?>bt_aprender_especializacao.gif" onclick="entrarEspecializacao()" />
         <?php elseif($basePlayer->id_especializacao): ?>
         	<input type="image" src="<?php echo img() ?>bt_trocar_especializacao.gif" onclick="<?= $js_function ?>()" />
         <?php endif; ?>
         </td>
        </tr>
        <tr>
        	<td colspan="3"><br />
<script type="text/javascript">
//<!--
google_ad_client = "ca-pub-9048204353030493";
/* NG - Especialização */
google_ad_slot = "6493482085";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
            </td>
        </tr> 
    </table>
<br />
	<table width="730" border="0" cellpadding="0" cellspacing="0" >
      <tr>
        <td height="45"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="48" background="<?php echo img() ?>bg_aba.jpg"><table width="730" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="80" align="center">&nbsp;</td>
                <td width="150" align="center"><b style="color:#FFFFFF">Nome</b></td>
                <td width="128" align="center"><b style="color:#FFFFFF">Requerimentos</b></td>
                <td width="228" align="center"><b style="color:#FFFFFF">Bonus</b></td>
                <td width="80" align="center"><b style="color:#ffffff">Compra</b></td>
                <td width="92" align="center"><b style="color:#FFFFFF">Status</b></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
    <?php
		if($basePlayer->id_especializacao) {
			$maxOrd = Recordset::query("
				SELECT MAX(ordem) AS mx FROM item WHERE id IN(SELECT id_item FROM player_item WHERE id_player={$basePlayer->id}) AND id_especializacao={$basePlayer->id_especializacao}
			")->row_array();
			
			$maxOrd = (int)$maxOrd['mx'];
		}

		$qEspecializacao = Recordset::query("SELECT * FROM especializacao $where");
    	
		while($rEspecializacao = $qEspecializacao->row_array()) {
	?>
    <table style="display:none" width="730" class="especializacaoObj" border="0" cellpadding="0" cellspacing="0" id="tbEspecializacao_<?= encode($rEspecializacao['id']) ?>">
        <?php
        	$qItem = Recordset::query("
				SELECT
					a.id,
					a.nome,
					a.imagem,
					a.descricao,
					a.req_level,
					a.req_graduacao,
					b.nome_".Locale::get()." AS nome_req_graduacao,
					a.bonus_hp,
					a.bonus_sp,
					a.bonus_sta,
					a.consume_hp,
					a.consume_sp,
					a.consume_sta,
					a.req_level,
					a.req_graduacao,
					a.ordem,
					a.coin,
					a.preco,
					a.ryou,
					a.turnos,
					a.bonus_treino,
					a.tai,
					a.nin,
					a.gen,
					a.agi,
					a.con,
					a.forc,
					a.ene,
					a.inte,
					a.res
				FROM 
					item a JOIN graduacao b ON a.req_graduacao = b.id
					JOIN item_tipo c ON c.id=a.id_tipo
				WHERE a.id_tipo=22 AND a.id_especializacao={$rEspecializacao['id']} AND c.equipamento=0		
				ORDER BY a.ordem ASC
			", true);
			
			$c = intval(rand(1, 65535));

      while($rItem = $qItem->row_array()) {
				$cor	 = ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
				
			 	$reqGrad = $basePlayer->id_graduacao >= $rItem['req_graduacao'] ? true : false;
			 	$reqLevel = $basePlayer->level >= $rItem['req_level'] ? true : false;
				
				
				$reqOrd = $rItem['ordem'] > $maxOrd ? true : false;
				
				$grd_color = $basePlayer->id_graduacao >= $rItem['req_graduacao'] ? "style='text-decoration: line-through'" : "style='color: #fd2a2a'";
				$lvl_color = $basePlayer->level >= $rItem['req_level'] ? "style='text-decoration: line-through'" : "style='color: #fd2a2a'";				
		?>
        <tr <?php echo $cor ?>>
          <td width="80" align="center"><img src="<?= img() . $rItem['imagem'] ?>" width="48" height="48" /></td>
          <td width="150" align="center"><b style="font-size:13px; color:#af9d6b"><?php echo $rItem['nome'] ?></b></td>
          <td width="128" align="center">
           <div class="trigger">
        		<img src='<?php echo img() ?>layout/requer.gif'  style='cursor:pointer' />
           </div>
		   <div class="tooltip"> 

			 <b>Requerimentos</b><br />
			 Saiba o que você precisa para aprender essa habilidade:
             <ul>
			 <li <?php echo $lvl_color ?>>&bull; <b>Level</b>: <?php echo $rItem['req_level'] ?></li>
			 <li <?php echo $grd_color ?>>&bull; <b>Gradua&ccedil;&atilde;o:</b> <?= $rItem['nome_req_graduacao'] ?></li>
			 </ul>
           </div> 
          </td>
          <td width="228" <?= $bg ?> height="34" align="center">
			  <?php if($rItem['nin']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?php echo $rItem['nin'] ?>%</b><br />Ninjutsu <img src="<?php echo img() ?>topo/nin.png" />
              </p>
              <?php endif; ?>
              <?php if($rItem['tai']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?php echo $rItem['tai'] ?>%</b><br />Taijutsu <img src="<?php echo img() ?>topo/tai.png" />
              </p>
              <?php endif; ?>
              <?php if($rItem['gen']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?php echo $rItem['gen'] ?>%</b><br />Genjutsu <img src="<?php echo img() ?>topo/gen.png" />

              </p>
              <?php endif; ?>
              <?php if($rItem['ene']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?php echo $rItem['ene'] ?>%</b><br />Energia <img src="<?php echo img() ?>topo/ene.png" />
              </p>
              <?php endif; ?>
              <?php if($rItem['forc']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?php echo $rItem['forc'] ?>%</b><br />For&ccedil;a <img src="<?php echo img() ?>topo/forc.png" />
              </p>
              <?php endif; ?>
              <?php if($rItem['inte']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?php echo $rItem['inte'] ?>%</b><br />Intelig&ecirc;ncia <img src="<?php echo img() ?>topo/inte.png" />
              </p>
              <?php endif; ?>
              <?php if($rItem['con']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?php echo $rItem['con'] ?>%</b><br />Selo <img src="<?php echo img() ?>topo/conhe.png" />
              </p>
              <?php endif; ?>
              <?php if($rItem['agi']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?php echo $rItem['agi'] ?>%</b><br />Agilidade <img src="<?php echo img() ?>topo/agi.png" />
              </p>
              <?php endif; ?>
              <?php if($rItem['res']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?php echo $rItem['res'] ?>%</b><br />Resist&ecirc;ncia <img src="<?php echo img() ?>topo/defense.png" />
              </p>
              <?php endif; ?>

             <?php if($rItem['consume_hp']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;">-<?php echo $rItem['consume_hp'] ?>%</b><br /> HP para usar técnicas <img src="<?php echo img() ?>topo/p_hp.png" />
              </p>
              <?php endif; ?>
              
			  <?php if($rItem['consume_sp']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;">-<?php echo $rItem['consume_sp'] ?>%</b><br /> Chakra para usar técnicas<img src="<?php echo img() ?>topo/p_chakra.png" />
              </p>
              <?php endif; ?>
              
              <?php if($rItem['consume_sta']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;">-<?= $rItem['consume_sta'] ?>%</b><br />Stamina para usar técnicas<img src="<?php echo img() ?>topo/p_stamina.png" />
              </p>
              <?php endif; ?>
                            
              <?php if($rItem['bonus_hp']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?= $rItem['bonus_hp'] ?>%</b><br />HP <img src="<?php echo img() ?>topo/p_hp.png" />
              </p>
              <? endif; ?>
              <? if($rItem['bonus_sp']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?= $rItem['bonus_sp'] ?>%</b><br />Chakra <img src="<?php echo img() ?>topo/p_chakra.png" />
              </p>
              <? endif; ?>
              <? if($rItem['bonus_sta']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?= $rItem['bonus_sta'] ?>%</b><br />Stamina <img src="<?php echo img() ?>topo/p_stamina.png" />
              </p>
              <? endif; ?>

              <? if($rItem['turnos'] < 0): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?= abs($rItem['turnos']) ?></b><br /> Menos Turnos de espera nas técnicas<img src="<?php echo img() ?>topo/p_menosturnos.png" />
              </p>
              <? endif; ?>
              
              <? if($rItem['turnos'] > 0): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?= $rItem['turnos'] ?></b><br /> Turnos de duração <img src="<?php echo img() ?>topo/p_maisturnos.png" />
              </p>
              <? endif; ?>

              <? if($rItem['ryou']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?= $rItem['ryou'] ?>%</b><br /> de Ryou nas bonificações <img src="<?php echo img() ?>topo/p_money.png" />
              </p>
              <? endif; ?>

              <? if($rItem['bonus_treino']): ?>
              <p style="float: left; width:50%">
                <b style="color:#af9d6b; font-size:14px;"><?= $rItem['bonus_treino'] ?></b><br /> pontos a mais de treino diario <img src="<?php echo img() ?>topo/p_treino.png" />
              </p>
              <? endif; ?>
          </td>
		  <td width="80" align="center" nowrap="nowrap">
		  <?
			if($rItem['coin'] && $rItem['preco']) { // Grana e Coin
				$area_compra = "<input name='ck_" . ($c + $rItem['id']) ."' type='radio' onclick='" . $js_functionb . "(" . ($c + $rItem['id']) . ", 0)'> RY$ $rItem[preco]<br />" .
							   "<input name='ck_" . ($c + $rItem['id']) ."' type='radio' onclick='" . $js_functionb . "(" . ($c + $rItem['id']) . ", 1)'> $rItem[coin] Credito(s)";
			} elseif($rItem['coin']  && !$rItem['preco']) { // So coin
				$area_compra ="$rItem[coin] Credito(s) <input id='pm_" . ($c + $rItem['id']) ."' name='pm_" . ($c + $rItem['id']) ."' type='hidden' value='" . $pay_key_1 . "' />";
			} elseif(!$rItem['coin'] && $rItem['preco']) { // So grana
				$area_compra ="RY$ $rItem[preco] <input id='pm_" . ($c + $rItem['id']) ."' name='pm_" . ($c + $rItem['id']) ."' type='hidden' value='" . $pay_key_0 . "' />";
			}
			
			if($rItem['ordem'] <= $maxOrd) {
				echo "Treinado";	
			} else {
				echo $area_compra;
			}
		  ?>
		  </td>
          <td width="92" align="center">
			 <? if($basePlayer->id_especializacao && ($reqGrad && $reqLevel && $reqOrd)): ?>
             <form method="post" action="?acao=especializacao_treinar" onsubmit="if(!$('#pm_<?= ($c + $rItem['id']) ?>').val()) return false;">
                <input type="hidden" name="id" value="<?= encode($rItem['id']) ?>" />
             	<input type="image" src="<?php echo img() ?>bt_treinar_on.gif" />
				<input name="pm_<?= ($c + $rItem['id']) ?>" id="pm_<?= ($c + $rItem['id']) ?>" type="hidden" value="" />
				<input type="hidden" name="pm" />
             </form>
             <? else: ?>
             	<? if($rItem['ordem'] <= $maxOrd): ?>
				 <img src="<?php echo img() ?>bt_treinado.gif" />
				 <? else: ?>
	             <img src="<?php echo img() ?>bt_treinar_off.gif" />
                 <? endif; ?>
             <? endif; ?>
          </td>
        </tr>
	<?
			}	
	?>
      </table>
	<?
		}
	?>

<script type="text/javascript">
$(document).ready(function () {
	mudaEspecializacao();
});
</script>
</div>