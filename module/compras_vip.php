<?php
	$items	= Recordset::query('SELECT * FROM coin_compra WHERE id_usuario=' . 1980996 . ' ORDER BY data_ins DESC');
?>
<div class="titulo-secao"><p><?php echo t('titulos.gasto_vip')?></p></div><br /><br />

<table border="0" width="100%">
	<?php foreach($items->result_array() as $item): ?>
	<tr>
		<td><?php echo date('d/m/Y H:i:s', strtotime($item['data_ins'])) ?></td>
		<td><?php echo !$item['data_liberacao'] ? '--' : date('d/m/Y H:i:s', strtotime($item['data_liberacao'])) ?></td>
		<td><?php echo Recordset::query('SElECT titulo FROM coin WHERE id=' . $item['id_coin'])->row()->titulo ?></td>
		<td><?php echo ucwords($item['status']) ?></td>
	</tr>
	<?php endforeach; ?>
</table>