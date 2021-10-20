<?php
	$vip_key = $_SESSION['vip_key'] = "f" . md5(rand(1, 512384));
?>

<div class="titulo-secao"><p><?php echo t('titulos.equipe_participar') ?></p></div>
<script type="text/javascript">
	function doEquipeVIPParticipar() {
		if(confirm("<?php echo t('equipe_participar.e1')?>")) {
			$("#codigo").val($("#vcodigo").val());
			$("#fEquipe")[0].submit();
		}
	}
	
	$(document).ready(function(e) {
		$('.equipe').each(function () {
			var	_	= $(this);
			
			$('a[class=button]', _).bind('click', function () {
				$.ajax({
					url:		'?acao=equipe_participar_final',
					type:		'post',
					data:		{vip: '<?php echo $vip_key ?>', equipe: _.data('equipe')},
					dataType:	'json',
					success:	function (e) {
						if(e.success) {
							jalert('<?php echo t('equipe_participar.e2')?>');
							
							_.hide('fast');
						} else {
							jalert('<?php echo t('equipe_participar.e3')?>:<br/><br/>' + e.messages.join("<br />"));
						}
					}
				});				
			});
		});
	});
</script>
<form method="post" id="fEquipe" action="?acao=equipe_participar_final">
  <input type="hidden" name="vip" id="vip" value="" />
  <input type="hidden" name="codigo" id="codigo" value="" />
  <input type="hidden" name="equipe" id="equipe" value="" />
</form>
<a name="top"></a>
<div id="cnEquipeVIP">
    <?php msg(2,''.t('equipe_participar.e4').'',''.t('equipe_participar.e5').''); ?>
</div>
  <br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "2775961778";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Equipe -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br /><br />
  <table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="40" align="center">&nbsp;</td>
            <td width="258" align="center"><b style="color:#FFFFFF"><?php echo t('equipe.participar.nome') ?></b></td>
            <td width="138" align="center"><b style="color:#FFFFFF"><?php echo t('equipe.participar.lider') ?></b></td>
            <td width="172" align="center"><b style="color:#FFFFFF"><?php echo t('equipe.participar.total') ?></b></td>
            <td width="92" align="center">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table>
    <table width="730" border="0" align="center" cellpadding="4" cellspacing="0">
    	<tr>
        	<td width="122" align="right"><strong><?php echo t('geral.filtrar') ?>:</strong></td>
        	<td align="left">
                <input type="text" id="t-guild-filtro" />
                <script type="text/javascript">
                	head.ready(function () {
	                    $("#t-guild-filtro").keyup(function () {
	                        $.uiTableFilter($("#tb-equipes"), this.value );
	                    });                	
                	});
                </script>            
            </td>
        </tr>
    </table>    
  <table width="730" border="0" cellpadding="2" cellspacing="0" id="tb-equipes">
    <?php
            	$qEquipeVIP = Recordset::query("
					SELECT
						a.id,
						a.nome,
						b.nome AS nome_lider,
						a.membros
					
					FROM
						equipe a JOIN player b ON b.id=a.id_player AND b.removido=0
					
					WHERE
						b.id_vila=" . $basePlayer->getAttribute('id_vila') . " AND
						a.membros < 4
						AND a.removido=0
						AND (SELECT aa.id_equipe FROM equipe_pendencia aa WHERE aa.id_equipe=a.id AND aa.id_player=4008937) IS NULL
				");
				
				$cn = 0;
			?>
    <?php foreach($qEquipeVIP->result_array() as $r): ?>
   	<?php
		$cor	 = ++$cn % 2 ? "class='cor_sim equipe'" : "class='cor_nao equipe'";
	?>
	<tr <?php echo $cor;?> data-equipe="<?php echo $r['id'] ?>">
		<td width="40">&nbsp;</td>
		<td width="258"><?php echo htmlspecialchars($r['nome']) ?></td>
		<td width="138"><?php echo htmlspecialchars($r['nome_lider']) ?></td>
		<td width="172"><?php echo $r['membros'] ?></td>
		<td width="92" align="center">
			<a class="button"><?php echo t('botoes.participar') ?></a>
		</td>
	</tr>
    <tr height="4"></tr>
    <?php endforeach; ?>
  </table>