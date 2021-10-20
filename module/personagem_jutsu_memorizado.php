<?php
	$has_regs = false;
	
	if($_POST && is_numeric($_POST['item'])) {
		if($basePlayer->COIN < 2) {
			$hasError	= true;
			$errorMsg	= 'Você não possui créditos suficientes';
		} else {
			$has = Recordset::query('SELECT id_item FROM player_item_level WHERE id_item=' . $_POST['item'] . ' AND id_player=' . $basePlayer->id)->num_rows ? true : false;
			$has = $has && !Recordset::query('SELECT id FROM player_item WHERE id_item=' . $_POST['item'] . ' AND id_player=' . $basePlayer->id)->num_rows;

			if(!$has) {
				$hasError	= true;
				$errorMsg	= 'Item inválido';
			} else {
				gasta_coin(2);
				usa_coin(20320, 2);
				
				$it = new Item($_POST['item']);

				Recordset::query('UPDATE player SET total_pt_' . $it->campo_base_t . '_gasto=total_pt_' . $it->campo_base_t . '_gasto-1 WHERE total_pt_' . $it->campo_base_t . '_gasto > 0 AND id=' . $basePlayer->id);
				Recordset::query('UPDATE player_item_level SET level_liberado=\'0\' WHERE id_item=' . $_POST['item'] . ' AND id_player=' . $basePlayer->id);
			}
		}
	}
	
	$items = new Recordset('
		SELECT
			id_item,
			level,
			exp
		
		FROM
			player_item_level
		
		 WHERE
		 	id_player=' . $basePlayer->id . '
		 	AND level_liberado=\'1\'
	');
?>
<script type="text/javascript">
	function alertCheck(o) {
		if(confirm('Serão necessários 2 créditos VIP para essa operação, deseja continuar?')) {
			o.submit();
		}
	}
</script>
<div class="titulo-secao"><p>Técnicas Ninja</p></div>
<br /><br />
<div class="msg_gai" style="background:url(<?php echo img() ?>msg/msg_tsunade.jpg);">
<div class="msg">
		<span style="font-size:16px; display:block; font-weight:bold; color:#7b1315; margin-bottom:10px">Jutsus Memorizados e Esquecidos</span>
		Jogadores que trocaram de clã, mode senin e portão de chakra e acabaram ficando sem os pontos de treinos esquecidos em jutsus antigos agora podem resgatar esses pontos atraves dessa página, portanto os jutsus listados aqui serão apenas os jutsus que você tem ponto de treino gasto e que não possui mais em suas técnicas ninjas para uso em combate!
</div>
</div>
<?php if($hasError): ?>
	<div class="error"><?php echo $errorMsg ?></div>
<?php endif; ?>
<table width="730" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td height="48" background="<?php echo img() ?>bg_aba.jpg">
		<table width="730" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td width="90" align="center">&nbsp;</td>
				<td width="180" align="center"><b style="color:#FFFFFF">Jutsu</b></td>
				<td width="460" align="center">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<table border="0" width="730" cellspacing="0" cellpadding="4">
	<?php foreach($items->result_array() as $k => $v): ?>
	<?php
		// O player ainda tem esse golpe
		if(Recordset::query('SELECT id FROM player_item WHERE id_item=' . $v['id_item'] . ' AND id_player=' . $basePlayer->id)->num_rows) {
			continue;
		}

		$it			= new Item($v['id_item']);
		$has_regs	= true;
		
		$color		= ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
	?>
	<tr <?php echo $color ?>>
		<td width="90"><img src="<?php echo img($it->imagem) ?>" onerror="this.src='<?php echo img('jutsu/base.jpg') ?>'" border="1" /></td>
		<td align="left">
			<b><?php echo $it->name ?> Lvl. <?php echo $v['level'] ?></b><br />
			Pontos de experiência dessa técnica: <?php echo $v['exp'] ?><br /><br />
			<?php
				switch($it->campo_base_t) {
					case 'tai'; echo '<img src="' . img('topo/tai.png') . '" />'; break;
					case 'nin'; echo '<img src="' . img('topo/nin.png') . '" />'; break;
					case 'gen'; echo '<img src="' . img('topo/gen.png') . '" />'; break;
				}
			?>
			Recupera 1 ponto
		</td>
		<td align="right">
			<form method="post" onsubmit="return false;">
			<input type="hidden" name="item" value="<?php echo $v['id_item'] ?>" />
			<input type="image" onclick="alertCheck(this.form)" src="<?php echo img('bt_remover_level.gif') ?>" />
			</form>
		</td>
	</tr>
	<?php endforeach; ?>
	<?php if(!$has_regs): ?>
	<tr>
		<td><i>Nenhuma técnica</i></td>
	</tr>
	<?php endif; ?>
</table>