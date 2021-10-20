<div class="titulo-secao"><p><?php echo t('menus.temas')?></p></div>
<style>
	.selecao-imagem {
		float: left;
		position: relative;
		display: block;
		height: 275px;
		width: 240px;
	}
	
	.selecao-imagem .aviso-vip {
		text-align: center;
	}
</style>
<br />
<?php
	$_POST['personagem'] = 	!isset($_POST['personagem']) ? $basePlayer->id_classe : $_POST['personagem'];
	$_POST['tipo'] = 		!isset($_POST['tipo']) ? 0 : $_POST['tipo'];
	$_POST['status'] = 		!isset($_POST['status']) ? 0 : $_POST['status'];
?>
<form method="post">
	<table width="730" border="0" cellpadding="0" cellspacing="2">
	  <tr>
		<td height="49" align="left" colspan="7" class="subtitulo-home">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="color:#FFFFFF"><?php echo t('ranks.filtros'); ?></b></td>
		</tr>
	  <tr >
		<td  align="center">
			<b style="font-size:16px"><?php echo t('ranks.personagem'); ?></b><br />
			<?php
				$vilas = Recordset::query("SELECT * FROM vila WHERE inicial='1'");
			?>
			<select name="personagem" id="personagem" style="width:140px">
				  <?php while($vila = $vilas->row_array()) {?>
					<optgroup label="<?php echo $vila['nome_'. Locale::get()] ?>">
						<?php
							$personagens = Recordset::query("SELECT * FROM classe WHERE id_vila=". $vila['id']);
						?>	
					<?php while($personagem = $personagens->row_array()) {?>
						<option value="<?php echo $personagem['id'] ?>" <?php echo $_POST['personagem'] == $personagem['id'] ? "selected='selected'":''?>><?php echo $personagem['nome'] ?></option>
					<?php } ?>
					</optgroup>
				<?php } ?>
			</select>
			
			
		</td>
		<td  height="34" align="center">
			<b style="font-size:16px">Tipo</b><br />
			<select name="tipo" id="tipo">
				<option value="0" <?php echo $_POST['tipo']==0 ? "selected='selected'":''?>>Todos</option>
				<option value="1" <?php echo $_POST['tipo']==1 ? "selected='selected'":''?>>Normal</option>
				<option value="2" <?php echo $_POST['tipo']==2 ? "selected='selected'":''?>>Ultimate</option>
			</select>
	   </td>
		<td  height="34" align="center">
			<b style="font-size:16px">Status</b><br />
			<select name="status" id="status">
				<option value="0" <?php echo $_POST['status']==0 ? "selected='selected'":''?>>Todos</option>
				<option value="1" <?php echo $_POST['status']==1 ? "selected='selected'":''?>>Não Adquirido</option>
				<option value="2" <?php echo $_POST['status']==2 ? "selected='selected'":''?>>Adquirido</option>
			</select>
	   </td>
		<td width="120"  align="center"><input type="submit" class="button" value="<?php echo t('geral.filtrar')?>" /></td>
	  </tr>
	</table>
</form>
<?php
	$temas = Recordset::query("
		SELECT 
			   ci.* 
		FROM 
			 classe_imagem AS ci
			
		WHERE 
			  ci.id_classe	   = ".$_POST['personagem']." AND	
			  ".(!$_POST['tipo'] ? '' : "ci.ultimate = ". ($_POST['tipo']==1 ? 0 : 1) ." AND")."
			  ci.tema          = 1 AND
			  ci.ativo         = 'sim'
	");	
?>
<br /><br />
<?php while($tema = $temas->row_array()) {?>
<?php
	$comprado	= Recordset::query('SELECT * FROM player_imagem_tema WHERE id_usuario=' . $basePlayer->id_usuario . ' AND id_imagem=' . $tema['id'])->num_rows;
?>
	<?php if((!$comprado && $_POST['status']==1) || ($comprado && $_POST['status']==2) || (!$_POST['status'])){?>
		<div class="selecao-imagem">
		<?php if($tema['ultimate']): ?>
			<a style="display: block; <?php echo LAYOUT_TEMPLATE=="_azul" ? "height: 238px; width: 195px" : "height: 241px; width: 226px"?>">
				<embed  <?php echo LAYOUT_TEMPLATE=="_azul" ? 'height="238" width="195"' : 'height="241" width="226"'?> src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE ?>/profile/<?php echo $tema['id_classe'] ?>/<?php echo $tema['imagem'] ?>.swf?_cache=<?php echo rand(1,1000) ?>" style="float: left; margin: 2px" quality="high" wmode="transparent" allowscriptaccess="always" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash">
				<div align="center">
					<?php if($comprado): ?>
						<input type="button" class="button" value="Ativar" onclick="doPersonagemImagemDo('<?php echo encode($tema['id']) ?>', '<?php echo $tema['id_classe'].'-'.$tema['imagem']?>', <?php echo $comprado ?>)" />
					<?php else: ?>
						<input type="button" class="button" value="Comprar" onclick="doPersonagemImagemDo('<?php echo encode($tema['id']) ?>', '<?php echo $tema['id_classe'].'-'.$tema['imagem']?>', <?php echo $comprado ?>)" />
					<?php endif ?>
				</div>
			</a>
		<?php else: ?>
			<a href="javascript:doPersonagemImagemDo('<?php echo encode($tema['id']) ?>', '<?php echo $tema['id_classe'].'-'.$tema['imagem']?>', <?php echo $comprado ?>)">
				<img border="0" style="float: left; margin: 2px" src="<?php echo img()?>layout<?php echo LAYOUT_TEMPLATE ?>/profile/<?php echo $tema['id_classe'] ?>/<?php echo $tema['imagem'] ?><?php echo LAYOUT_TEMPLATE=="_azul" ? ".jpg" : ".png" ?>" />
			</a>
		
		<?php endif ?>
		</div>
	<?php } ?>	
<?php } ?>	