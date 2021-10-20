<?php header("Content-Type: text/html; charset=utf-8") ?>
<option value=""><?php echo t('actions.a240')?></option>
<option value="">---------------</option>
<?php
	$where .= $_POST['t'] ? " AND b.id_habilidade=" . (int)decode($_POST['t']) : "";
	$where .= $_POST['c'] ? " AND b.id_cla=" . (int)decode($_POST['c']) : "";
	$where .= $_POST['g'] ? " AND b.req_graduacao=" . (int)decode($_POST['g']) : "";

	$qGrupos = Recordset::query("
		SELECT
			a.id,
			a.nome
		
		FROM
			grupo a JOIN item b ON a.id=b.id_grupo
		
		WHERE
			1=1 $where
		
		GROUP BY 1
	");
	
	while($rGrupo = $qGrupos->row_array()):
?>
<option value="<?php echo $_SESSION['readID'] + $rGrupo['id'] ?>"><?php echo $rGrupo['nome'] ?></option>
<?php endwhile; ?>