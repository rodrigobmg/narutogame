<?php
	$redir_script		= true;
	$avail				= Player::getPtTotal($basePlayer->getAttribute('treino_total')) - $basePlayer->getAttribute('treino_gasto');
	$avail2 			= $basePlayer->level * 3 - $basePlayer->getAttribute('treino_gasto');
	$has_redist_item	= $basePlayer->hasItem(array(20316, 20317, 20318));

	if($has_redist_item) {
		$redist_item	= $basePlayer->getItem(array(20316, 20317, 20318));
	}

	$redists			= $has_redist_item ? $redist_item->getAttribute('turnos') - $redist_item->getAttribute('uso') : 0;
	
	$ar_imagens	= array(
		'agi'				=> 'layout/icones/agi.png',
		'con'				=> 'layout/icones/conhe.png',
		'for'				=> 'layout/icones/forc.png',
		'int'				=> 'layout/icones/inte.png',
		'res'				=> 'layout/icones/defense.png',
		'nin'				=> 'layout/icones/nin.png',
		'gen'				=> 'layout/icones/gen.png',
		'ene'				=> 'layout/icones/ene.png',
		'tai'				=> 'layout/icones/tai.png',
		'ken'				=> 'layout/icones/ken.png',
		'conc'				=> 'layout/icones/target2.png',
		'esq'				=> 'layout/icones/esquiva.png',
		'conv'				=> 'layout/icones/convic.png',
		'det'				=> 'layout/icones/deter.png'
	);

	$ar_at = array(
		1 => "tai",
		2 => "ken",
		3 => "nin",
		4 => "gen",
		5 => "con",
		6 => "agi",
		7 => "for",
		8 => "int",
		9 => "ene",
		10 => "res"
		/*11 => "conc",
		12 => "esq",
		13 => "conv",
		14 => "det"*/
		
	);

	$ar_at_upd = array(
		1 => "tai",
		2 => "ken",
		3 => "nin",
		4 => "gen",
		5 => "con",
		6 => "agi",
		7 => "forc",
		8 => "inte",
		9 => "ene",
		10 => "res"
		/*11 => "conc",
		,
		12 => "esq",
		13 => "conv",
		14 => "det"*/
	);

	$ar_desc = array(
		1 => t('at.tai'),
		2 => t('at.ken'),
		3 => t('at.nin'),
		4 => t('at.gen'),
		5 => t('at.con'),
		6 => t('at.agi'),
		7 => t('at.for'),
		8 => t('at.int'),
		9 => t('at.ene'),
		10 => t('at.res')
		/*11 => t('at.conc'),
		,
		12 => t('at.esq'),
		13 => t('at.conv'),
		14 => t('at.det')*/
		
	);

	$ar_at_raw = array(
		1 => "TAI_RAW",
		2 => "KEN_RAW",
		3 => "NIN_RAW",
		4 => "GEN_RAW",
		5 => "CON_RAW",
		6 => "AGI_RAW",
		7 => "FOR_RAW",
		8 => "INT_RAW",
		9 => "ENE_RAW",
		10 => "RES_RAW"
		/*11 => "CONC_RAW2",
		,
		12 => "ESQ_RAW2",
		13 => "CONV_RAW2",
		14 => "DET_RAW2"*/
		
	);
	
	if($_POST) {
		if($_POST['key'] != $_SESSION['dist_key']) {
			redirect_to("negado", null, ['e' => 1]);
		}

		$option		= isset($_POST['attribute']) && is_numeric($_POST['attribute']) ? $_POST['attribute'] : 0;
		$quantity	= isset($_POST['quantity']) && is_numeric($_POST['quantity']) ? $_POST['quantity'] : 0;
		$is_redist	= isset($_POST['redist']) && $_POST['redist'];
		
		switch($option) {
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
			case 13:
			case 14:
				$field		= $ar_at[$option];
				$field_upd	= $ar_at_upd[$option];
				$field_raw	= $ar_at_raw[$option];
			
				break;
			
			default:
				redirect_to("negado", null, ['e' => 2]);
			
				break;
		}
	
		if($is_redist) {
			// So redistribui se tiver o item e o atributo tiver treinado, caso contrario, não faz nadegas
			$can_redist	= $basePlayer->getAttribute($field) > 0 && $quantity > 0 && $quantity <= $redists && $quantity <= $basePlayer->getAttribute($field);

			if($redists && $can_redist) {
				Recordset::update('player', array(
					$field_upd		=> array('escape' => false, 'value' => $field_upd . '- ' . $quantity),
					'treino_gasto'	=> array('escape' => false, 'value' => 'treino_gasto - ' . $quantity)
				), array(
					'id'	=> $basePlayer->id
				));
				
				// atualiza o uso do item e se o uso do item é igual ao total de vezes, remove --->
					$item = $basePlayer->getItem(array(20316, 20317, 20318));
					$item->setAttribute('uso', $item->getAttribute('uso') + $quantity);
					
					if(($item->getAttribute('turnos') - $item->getAttribute('uso')) <= 0) {
						Recordset::delete('player_item', array(
							'id'	=> $item->uid
						));
						
						unset($basePlayer->_items[$item->id]);

					}
				// <---			
			}			
		} else {
			if($avail2 && $quantity > 0 && $quantity <= $avail2 && $quantity <= $avail) {
				Recordset::update('player', array(
					$field_upd		=> array('escape' => false, 'value' => $field_upd . '+ ' . $quantity),
					'treino_gasto'	=> array('escape' => false, 'value' => 'treino_gasto + ' . $quantity)
				), array(
					'id'	=> $basePlayer->id
				));

				$basePlayer->setLocalAttribute($field_upd, $basePlayer->getAttribute($field_upd) + $quantity);
				$basePlayer->setLocalAttribute('treino_gasto', $basePlayer->getAttribute('treino_gasto') + $quantity);
			}else{
				die('erro');	
			}
		}
	}
	
	$basePlayer = new Player($basePlayer->id);

	// Conquista --->
		arch_parse(NG_ARCH_SELF, $basePlayer);
	// <---

	$pWidth		= 327;
	$pWidth2	= 737;
	$tre		= $basePlayer->getAttribute('treino_total') - $basePlayer->getPt(Player::getPtTotal($basePlayer->getAttribute('treino_total')));
	$tren		= $basePlayer->getPtM(Player::getPtTotal($basePlayer->getAttribute('treino_total')) + 1);
	$avail		= Player::getPtTotal($basePlayer->getAttribute('treino_total')) - $basePlayer->getAttribute('treino_gasto');
	$avail2 	= $basePlayer->level * 3 - $basePlayer->getAttribute('treino_gasto');
	$i_src		= $avail ? "bt_treinar_on.gif" : "bt_treinar_off.gif";
	$maxValue	= max($basePlayer->getAttribute('tai'), $basePlayer->getAttribute('nin'), $basePlayer->getAttribute('gen'), $basePlayer->getAttribute('ken'),
				$basePlayer->getAttribute('agi'), $basePlayer->getAttribute('con'), $basePlayer->getAttribute('for'), 
				$basePlayer->getAttribute('int') ,$basePlayer->getAttribute('ene'), $basePlayer->getAttribute('res'), $basePlayer->getAttribute('conc'), $basePlayer->getAttribute('esq'), $basePlayer->getAttribute('conv'), $basePlayer->getAttribute('det'));

	$has_redist_item	= $basePlayer->hasItem(array(20316, 20317, 20318));

	if($has_redist_item) {
		$redist_item	= $basePlayer->getItem(array(20316, 20317, 20318));
	}

	$redists			= $has_redist_item ? $redist_item->getAttribute('turnos') - $redist_item->getAttribute('uso') : 0;	
	$has_redist_item	= $basePlayer->hasItem(array(20316, 20317, 20318));
	$cn					= 0;
	$treinor			=  $tren - $tre;
?>
<?php if($avail): ?>
	<?php msg('3',''.t('academia_treinamento.at37').'', ''.t('academia_treinamento.at38').' <strong class="verde">'. $avail .'</strong> '.t('academia_treinamento.at39').' <strong class="laranja">'. ($basePlayer->level * 3 - $basePlayer->getAttribute('treino_gasto') > 0 ? $basePlayer->level * 3 - $basePlayer->getAttribute('treino_gasto') : 0 ) .'</strong> '.t('academia_treinamento.at391').'');?>
<?php else: ?>
<?php msg('2',''.t('academia_treinamento.at40').'', ''.t('academia_treinamento.at41').' <span class="verde">'. $treinor .'</span> '.t('academia_treinamento.at42').'');?>
<?php endif; ?>

<table width="730" border="0">
  <tr>
    <td align="left">
		<div style="width: 730px;" class="titulo-home"><p><span class="azul">//</span> <?php echo t('academia_treinamento.at43');?></p></div>
	</td>
  <tr>
    <td>
    <?php barra_exp3($tre, $tren, 730, "$tre/$tren", "#840303", "#E90E0E", 3) ?>
    </td>
    <br />
  <tr >
    <td><?php echo t('academia_treinamento.at44');?></td>
</table>
<!-- Mensagem nos Topos das Seções -->
<?php msg('6',''.t('academia_treinamento.at45').'', ''.t('academia_treinamento.at46').'');?>
<!-- Mensagem nos Topos das Seções -->

<div style="clear:both"></div>
<table border="0" cellpadding="4" cellspacing="0" width="730">
	<tr>
		<td class="subtitulo-home" colspan="9">
			<p><?php echo t('academia_treinamento.at47');?></p>			
		</td>
	</tr>
	<?php foreach($ar_at as $k => $v): ?>
	<?php
		$cor		= ++$cn % 2 ? "class='cor_sim'" : "class='cor_nao'";
	?>
	<tr <?php echo $cor ?>>
		<td align="left" valign="middle" ><b><?php echo $ar_desc[$k] ?></b></td>
		<td align="center" valign="middle" >
        <img src="<?php echo img($ar_imagens[$v]);?>" alt=""  id="<?php echo "i-". $v ."" ?>"  />
        	<?php echo generic_tooltip('i-' . $v, t('at.desc.' . $v)); ?>
        </td>
		<td align="left" valign="middle" >
			<?php barra_exp3($basePlayer->getAttribute($v), $maxValue, $pWidth, $basePlayer->getAttribute($v), "#2C531D", "#537F3D", $cn % 2 ? 1 : 2) ?>
		</td>
		<td>
			<?php if($avail2): ?>
				<select name="<?php echo $k ?>_val">
					<?php for($i = 1; $i <= ($avail > $avail2 ? $avail2:$avail ); $i++): ?>
						<option value="<?php echo $i ?>"><?php echo $i ?></option>
					<?php endfor; ?>
				</select>
			<?php endif ?>
		</td>
		<td align="left" valign="middle" >
			<?php if($avail && $avail2): ?>
				<a class="button distribute" data-attribute="<?php echo $k ?>"><?php echo t('botoes.treinar') ?></a>
			<?php else: ?>
				<a class="button ui-state-disabled"><?php echo t('botoes.treinar') ?></a>
			<?php endif ?>
		</td>
		<?php if($has_redist_item && $basePlayer->getAttribute($v) > 0): ?>
			<td>
				<select name="<?php echo $k ?>_val_redist">
					<?php for($i = 1; $i <= ($basePlayer->getAttribute($v) > $redists ? $redists : $basePlayer->getAttribute($v)); $i++): ?>
						<option value="<?php echo $i ?>"><?php echo $i ?></option>
					<?php endfor; ?>
				</select>
			</td>
			<td align="left" valign="middle" >
				<a class="button redistribute" data-attribute="<?php echo $k ?>"><?php echo t('botoes.remover') ?></a>
			</td>
		<?php else: ?>
			<td></td>
			<td></td>
		<?php endif; ?>
	</tr>
    <tr height="4"></tr>
	<?php endforeach; ?>  
</table>
<?php if($has_redist_item): ?>
	<?php echo sprintf(t('academia_treinamento.at48'), $redists) ?>
<?php endif; ?>
<?php 
	if(isset($_GET['acao'])) {
		player_at_check();
	}
?>