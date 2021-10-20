<?php
	$_SESSION['dipl_key'] = md5(rand(1,512384));
?>
<script type="text/javascript">
	  	
	function doExibeVila(i,o) {
		$(".vila").hide();
		$(".vila" + i).show();
		//$(".tMissaoSel").attr("background", "<?php //echo img() ?>bt_aba_menor.gif");
		
		if(o) {
			//$(o).attr("background", "<?php //echo img() ?>bt_aba_menor2.gif");
		}
	}
</script>
<div id="HOTWordsTxt" name="HOTWordsTxt">
<div class="titulo-secao"><p><?php echo t('diplomacia.d1')?></p></div>
<?php msg('6',''.t('diplomacia.d2').'', ''.t('diplomacia.d3').'');?>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- NG - Diplomacia -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9166007311868806"
     data-ad-slot="7485362977"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<br/><br/>

<div id="cnBase" class="direita">
    <table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="80" align="center"></td>
			<td width="80" align="center"><b style="color:#FFFFFF"><?php echo t('geral.vila')?></b></td>
            <td width="160" align="center"><b style="color:#FFFFFF"><?php echo t('geral.diplomacia')?></b></td>
            <td align="center"><b style="color:#FFFFFF"><?php echo t('geral.status')?></b></td>
          </tr>
        </table></td>
      </tr>
    </table>
    <table width="730" border="0" cellpadding="0" cellspacing="0" >
    	<?php
            $qVila	= Recordset::query("SELECT * FROM vila WHERE inicial='1'", true);
            $c		= 0;

            foreach($qVila->result_array() AS $rVila):
				$bg = ++$c % 2 ? "bgcolor='#413625'" : "bgcolor='#251a13'";
                $rDipl = Recordset::query("SELECT SQL_NO_CACHE dipl FROM diplomacia WHERE id_vila=" . $basePlayer->id_vila . " AND id_vilab=" . $rVila['id'])->row_array();
        ?>
    	<tr <?php echo $bg ?>>
			<td width="80" <?php echo $bg ?>></td>
        	<td width="80" <?php echo $bg ?>><img src="<?php echo img() ?>layout/diplomacia/<?php echo $rVila['id'] ?>.jpg" class="imgVila" /></td>
            <td width="160" <?php echo $bg ?> align="center"><strong class="amarelo" style="font-size:13px"><?php echo $rVila['nome_'.Locale::get().''] ?></strong></td>
            <td <?php echo $bg ?>  align="center">
            	<?php
                	switch((int)$rDipl['dipl']) {
						
						case 0:
							echo "<img src='".img()."layout/legenda/0.jpg' alt='".t('diplomacia.d4')."'/>";
						
							break;
						
						case 1:
							echo "<img src='".img()."layout/legenda/1.jpg' alt='".t('diplomacia.d5')."'/>";
						
							break;
						
						case 2:
							echo "<img src='".img()."layout/legenda/2.jpg' alt='".t('diplomacia.d6')."'/>";
							
						
							break;
					}
				?>
            </td>
			<?php if(date("N") == 5 && $basePlayer->id_vila_atual == 13): ?>
            <td width="155" <?php echo $bg ?> align="center">
			<?php 
                $qVoto		= Recordset::query("SELECT SQL_NO_CACHE id FROM diplomacia_voto WHERE id_vila=" . $basePlayer->id_vila . " AND id_vilab= " . $rVila['id'] . " AND id_usuario=" . $_SESSION['usuario']['id']);
                $canVote	= true;
                
                if($qVoto->num_rows) {
                    $canVote = false;
                }

				if($basePlayer->id_vila == $rVila['id']) {
					$canVote = false;
				}
			
				if($basePlayer->id_vila == 6 || $rVila['id'] == 6 || $basePlayer->id_graduacao < 3) {
					$canVote = false;
				}
            	
				if(date('Hi') == '2359') {
					$canVote = false;
				}				
			?>
			<?php if($canVote): ?>
  				<form method="post" id="f-voto-<?php echo $rVila['id'] ?>" action="?acao=diplomacia_votar">   
              		<input type="hidden" name="vila" value="<?php echo encode($rVila['id']) ?>" />
					<input type="hidden" name="key" value="<?php echo $_SESSION['dipl_key'] ?>" />
                    <select name="dipl">
                        <option value="<?php echo encode(1) ?>"><?php echo t('diplomacia.d4')?></option>
                        <option value="<?php echo encode(2) ?>"><?php echo t('diplomacia.d5')?></option>
                        <option value="<?php echo encode(3) ?>"><?php echo t('diplomacia.d6')?></option>
                    </select>
				</form>
                <?php endif; ?>   
           </td>
            <td width="155" <?php echo $bg ?> align="center" >
            <?php if($canVote): ?>
                <a class="button" onclick="$('#f-voto-<?php echo $rVila['id'] ?>').submit()"><?php echo t('botoes.votar')?></a>
            <?php else:?>
            	<?php if($basePlayer->vila_ranking > 25): ?>
                	-
                <?php else: ?>
                    <a class="button ui-state-disabled"><?php echo t('botoes.votar')?></a>
                <?php endif; ?>
            <?php endif; ?>            
            </td>
			<?php endif; ?>
    	</tr>
		<tr height="4"></tr>
		<?php endforeach; ?>
    </table>
    </div>

<?php msg('2',''.t('diplomacia.d7').'', ''.t('diplomacia.d8').'');?>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- NG - Diplomacia -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-9166007311868806"
     data-ad-slot="7485362977"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<br/><br/>

 <table width="730" border="0" cellpadding="0" cellspacing="0" class="with-n-tabs" id="tabs-diplomacia" data-auto-default="1">
    <tr>
      <?php foreach($qVila->result_array() as $vila): ?>
	  <td><a class="button" rel="#vila-<?php echo $vila['id'] ?>"><?php echo $vila['nome_'.Locale::get()] ?></a></td>	
      <?php endforeach; ?>
    </tr>
  </table>
 <br /> 
    <table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="50" align="center">&nbsp;</td>
            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.vila')?></b></td>
            <td width="140" align="center"><b style="color:#FFFFFF"><?php echo t('geral.vilab')?></b></td>
            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.aliados')?></b></td>
            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.inimigos')?></b></td>
            <td width="100" align="center"><b style="color:#FFFFFF"><?php echo t('geral.neutros')?></b></td>
	       </tr>
        </table></td>
      </tr>
    </table>
	<?php foreach($qVila->result_array() as $vila): ?>
	<div id="vila-<?php echo $vila['id'] ?>">
		<?php foreach($qVila->result_array() as $vilab): 
				$bg = ++$c % 2 ? "bgcolor='#413625'" : "bgcolor='#251a13'";
		?>
		<?php
			if($vila['id'] == $vilab['id']) {
				continue;
			}
		
			$rDipl = Recordset::query("
				SELECT
				SUM(dipl0) AS dipl0,
				SUM(dipl1) AS dipl1,
				SUM(dipl2) AS dipl2
				FROM
				(
					SELECT
					(SELECT COUNT(id) FROM diplomacia_voto WHERE id_vila=" . $vila['id'] . " AND id_vilab=" . $vilab['id'] . " AND dipl=0) AS dipl0,
					(SELECT COUNT(id) FROM diplomacia_voto WHERE id_vila=" . $vila['id'] . " AND id_vilab=" . $vilab['id'] . " AND dipl=1) AS dipl1,
					(SELECT COUNT(id) FROM diplomacia_voto WHERE id_vila=" . $vila['id'] . " AND id_vilab=" . $vilab['id'] . " AND dipl=2) AS dipl2
				) w				
			")->row_array();
		?>
	<table width="730" border="0" cellpadding="0" cellspacing="0">	
		<tr <?php echo $bg ?>>
			<td width="50" height="25"><img src="<?php echo img() ?>layout/home/vilas/<?php echo $vila['id'] ?>.jpg" alt="<?php echo $vila['nome_'.Locale::get().''] ?>"/></td>
            <td width="140"><b><?php echo $vila['nome_'.Locale::get().''] ?></b></td>
			<td width="140"><?php echo $vilab['nome_'.Locale::get().''] ?></td>
			<td width="100"><?php echo $rDipl['dipl1'] ?> <img src='<?php echo img()?>layout/legenda/1.jpg' alt='<?php echo t('diplomacia.d5')?>'/></td>
			<td width="100"><?php echo $rDipl['dipl2'] ?> <img src='<?php echo img()?>layout/legenda/2.jpg' alt='<?php echo t('diplomacia.d6')?>'/></td>
			<td width="100"><?php echo $rDipl['dipl0'] ?> <img src='<?php echo img()?>layout/legenda/0.jpg' alt='<?php echo t('diplomacia.d4')?>'/></td>
			<?php /*
			<td width="100"><?php echo $rDipl['dipl1'] > 25 ? 25 : $rDipl['dipl1']?> <img src='http://narutogame.com.br/images/ico_mapa_ninja2.png' alt='Diplomacia - Aliado'/></td>
			<td width="100"><?php echo $rDipl['dipl2'] > 25 ? 25 : $rDipl['dipl2'] ?> <img src='http://narutogame.com.br/images/ico_mapa_ninja1.png' alt='Diplomacia - Inimigo'/></td>
			<td width="100"><?php echo $rDipl['dipl0'] > 25 ? 25 : $rDipl['dipl0'] ?> <img src='http://narutogame.com.br/images/ico_mapa_ninja.png' alt='Diplomacia - Neutro'/></td>
			<td width="100">
			<?php
				$noVotes = 25 - $rDipl['dipl0'] - $rDipl['dipl1'] - $rDipl['dipl2'];
				
				echo $noVotes < 0 ? 0 : $noVotes;
			?>
			</td>
			*/ ?>
		</tr>
		<tr height="4"></tr>
		</table>
		<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
	
</div>
<script type="text/javascript">
	$(document).ready(function () {
		doExibeVila(<?php echo $basePlayer->getAttribute('id_vila') ?>);
	});
</script>