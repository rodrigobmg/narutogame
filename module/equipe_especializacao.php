<?php
	$_SESSION['equipe_esp_key'] = md5(rand(1, 512384) . date('YmdHis'));
?>
<script>
	function equipeEspAba(v, o) {
		$('.tEquipeEsp').hide();
		$('#tEquipeEsp' + v).show();
		
		if(o) {
		
		}
	}
	
	function equipeEspAprender(v, f) {
		$.ajax({
			url: '?acao=equipe_especializacao_aprender',
			data: {role: v, level: f, key: '<?php echo $_SESSION['equipe_esp_key'] ?>'},
			type: 'post',
			success: function(e) {
				eval(e);
			}
		});
		
		$('#cnBase').html('<?php echo t('evento4.e4')?>');
	}
	
	$(document).ready(function () {
		equipeEspAba(0, null);
	});
</script>
<div class="titulo-secao"><p><?php echo t('equipe_especializacao.ee1')?></p></div>
    <br />
<script type="text/javascript">
    google_ad_client = "ca-pub-9166007311868806";
    google_ad_slot = "4531896570";
    google_ad_width = 728;
    google_ad_height = 90;
</script>
<!-- NG - Equipamentos -->
<script type="text/javascript"
src="//pagead2.googlesyndication.com/pagead/show_ads.js">
</script>           
<br/><br/>
<?php if(isset($_GET['ok']) && $_GET['ok']): ?>
<?php msg('4',''.t('clas.c6').'', ''.t('equipe_especializacao.ee2').'');?>
<?php endif; ?>
<?php msg(3,''.t('equipe_especializacao.ee3').'',''. sprintf(t('equipe_especializacao.ee4'),$basePlayer->exp_equipe_dia_total). ''); ?>
<script type="text/javascript">
	    $(function(){
        $("#ajuda")
            .click(function(){
                $("#msg_help")
                    .toggle("slow");
            });
    });
</script>

<div id="cnBase">
	<table width="730" border="0" cellpadding="0" cellspacing="0" style="clear:both">
		<tr>
        	<td><a class="button" onclick="equipeEspAba(0, this)">Ninjutsu</a></td>
            <td><a class="button" onclick="equipeEspAba(1, this)"><?php echo t('equipe_especializacao.ee5') ?></a></td>
            <td><a class="button" onclick="equipeEspAba(2, this)">Genjutsu</a></td>
            <td><a class="button" onclick="equipeEspAba(3, this)">Taijutsu</a></td>
            <td><a class="button" onclick="equipeEspAba(4, this)">Kinjutsu</a></td>
			<td><a class="button" onclick="equipeEspAba(5, this)">Bukijutsu</a></td>
			<td><a class="button" onclick="equipeEspAba(6, this)">Defensivo</a></td>
		</tr>
	</table>
    <br />

	<?php
		$roles_lvl[0] = Player::getFlag('equipe_role_0_lvl', $basePlayer->id);
		$roles_lvl[1] = Player::getFlag('equipe_role_1_lvl', $basePlayer->id);
		$roles_lvl[2] = Player::getFlag('equipe_role_2_lvl', $basePlayer->id);
		$roles_lvl[3] = Player::getFlag('equipe_role_3_lvl', $basePlayer->id);
		$roles_lvl[4] = Player::getFlag('equipe_role_4_lvl', $basePlayer->id);
		$roles_lvl[5] = Player::getFlag('equipe_role_5_lvl', $basePlayer->id);
		$roles_lvl[6] = Player::getFlag('equipe_role_6_lvl', $basePlayer->id);
	
		$especializacoes = array(
			'Ninjutsu'	=> 0, 
			t('equipe_especializacao.ee5') => 1, 
			'Genjutsu'	=> 2, 
			'Taijutsu'	=> 3,
			'Kinjutsu'	=> 4,
			'Bukijutsu'	=> 5,
			'Defensivo'	=> 6
			);
		
		$lvl_exp = array(
			'1' => 2100,
			'2' => 4200,
			'3' => 6300,
			'4' => 8400,
			'5' => 14000
		);
		
		$current_pt = $basePlayer->getAttribute('exp_equipe_dia_total');
		$c			= 0;
	?>
	<table width="730" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td class="subtitulo-home"><table width="730" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="90" align="center">&nbsp;</td>
            <td width="370" align="center"><b style="color:#FFFFFF"><?php echo t('geral.nome_descricao') ?></b></td>
            <td width="170" align="center"><b style="color:#FFFFFF"><?php echo t('geral.req_bonus') ?></b></td>
            <td width="100" align="center"><b style="color:#FFFFFF"></b></td>
          </tr>
        </table></td>
      </tr>
    </table>
	<?php foreach($especializacoes as $k => $v): ?>
	<table id="tEquipeEsp<?php echo $v ?>" class="tEquipeEsp" width="730" border="0" cellpadding="0" cellspacing="0">
		<?php for($f = 1; $f <=5; $f++): ?>
		<?php
			$reqs	= $current_pt >= $lvl_exp[$f] && $f == ($roles_lvl[$v] + 1);
			$cor	 = ++$c % 2 ? "class='cor_sim'" : "class='cor_nao'";
			
			$lvl_color	= $reqs ? "style='text-decoration: line-through'" : "style='color: #fd2a2a'";
			$id			= 'el' . uniqid();
			$item		= Recordset::query('SELECT id,imagem FROM item WHERE id_tipo=22 AND id_habilidade=' . $v . ' AND ordem=' . $f, true)->row_array();
		?>
		<tr <?php echo $cor ?>>
			<td width="90" align="center">
				<div class="img-lateral-dojo2">
					<img src="<?php echo img('layout/'. $item['imagem'] .' ') ?>" width="53" height="53" style="margin-top:5px"/>
				</div>			 
			</td>
			<td width="370">
				<b style="font-size:13px;" class="amarelo"><?php echo t('equipe_especializacao.ee6')?> <?php echo $k ?> <?php echo t('equipe_especializacao.ee7')?> <?php echo $f ?></b>
			</td>
			<td width="170">
				<img src='<?php echo img('layout/requer.gif') ?>'  style="cursor:pointer" id="<?php echo $id ?>" />
				<?php specialization_tooltip($item['id'], $id, $basePlayer, '<li ' . $lvl_color . '> ' . $lvl_exp[$f] . ' ' . t('geral.pontos_exp') . '</li>') ?>
				<?php /*
				<?php ob_start(); ?>
				 <?php echo t('equipe_especializacao.ee8')?>
				 <ul style="margin:0; padding:5px">
					 <li <?php echo $lvl_color ?>> <?php echo $lvl_exp[$f] ?> <?php echo t('geral.pontos_exp')?></li>
					 
				 </ul>
                 <br /><br />
				 <b>Bônus</b><br />
				 <ul style="margin:0; padding:5px">
				<?php if($v == 0): ?>
					 <li> <span class="verde">+<?php echo $f * 10 ?>%</span><?php echo t('dojo_batalha_multi.db9');?></li>
					 <li> <span style="color:#ff0000"><?php echo $f * 10 ?>%</span> <?php echo t('dojo_batalha_multi.db10');?></li>
				<?php elseif($v == 1): ?>
					 <li> <span class="verde">+<?php echo $f * 10 ?>%</span> <?php echo t('dojo_batalha_multi.db11');?></li>
					 <li> <span style="color:#ff0000"><?php echo $f * 5 ?>%</span> <?php echo t('dojo_batalha_multi.db12');?></li>			
					 <li> <span style="color:#ff0000"><?php echo $f * 5 ?>%</span> <?php echo t('dojo_batalha_multi.db13');?></li>			
				<?php elseif($v == 2): ?>
					 <li> <span class="verde">+<?php echo $f * 10 ?>%</span> <?php echo t('dojo_batalha_multi.db14');?></li>
					 <li> <span style="color:#ff0000"><?php echo $f * 10 ?>%</span> <?php echo t('dojo_batalha_multi.db10');?></li>			
				<?php else: ?>
					 <li> <span class="verde">+<?php echo $f * 10 ?>%</span> <?php echo t('dojo_batalha_multi.db15');?></li>
					 <li> <span style="color:#ff0000"><?php echo $f * 10 ?>%</span> <?php echo t('dojo_batalha_multi.db10');?></li>			
				<?php endif; ?>
				 </ul>
				<?php echo generic_tooltip('i-req-' . $v . '-' . $f, ob_get_clean()) ?>
				*/?>
			</td>
			<td width="100">
				<?php if($f <= $roles_lvl[$v]): // Meu nivel atual/inferior ?>
                    <a class="button ui-state-green"><?php echo t('botoes.treinado');?></a>
				<?php elseif($reqs): // tenho os reqs ?>
                    <a class="button" onclick="equipeEspAprender(<?php echo $v ?>, <?php echo $f ?>)"><?php echo t('botoes.treinar');?></a>
				<?php else: // Sem requerimento/level superior ?>
                    <a class="button ui-state-disabled"><?php echo t('botoes.treinar');?></a>
				<?php endif; ?>
			</td>
            <tr height="4"></tr>
		</tr>
		<?php endfor; ?>
	</table>
	<?php endforeach; ?>
</div>
