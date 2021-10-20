<?php
	if(!(isset($_POST['key']) && isset($_POST['item']) && is_numeric($_POST['item']))) {
		die(t('actions.110'));
	}
	
	if($_POST['key'] != $_SESSION['eqp_ninja_ley']) {
		die(t('actions.111'));
	}

	if(!$basePlayer->hasItemW($_POST['item'])) {
		die(t('actions.112'));
	}
	
	$item	= $basePlayer->getItem($_POST['item']);
	$qt		= $item->equipado ? $item->total - 1 : $item->total;
	
	if($item->equipado && $item->total == 1) {
		die(t('actions.113'));
	}
	
	if($item->venda) {
		$qt -= $item->venda_total;
	}
	
	if($qt <= 0) {
		die(t('actions.114'));
	}
	
	// Verifica se ja existe uma auction desse item --->
		$auction = Recordset::query('SELECT id FROM leilao WHERE id_player=' . $basePlayer->id . ' AND id_item=' . $item->id);
		
		if($auction->num_rows) {
			die(''.t('actions.a115').' <!-- @EXIST -->');
		}
	// <---
	
	if(isset($_POST['action'])) {
		switch($_POST['action']) {
			case 1: // Ninja shop
				if($_POST['total'] > $qt) {
					die('jalert("Quantidade inválida!")');
				}
			
				$basePlayer->setAttribute('ryou', $basePlayer->ryou + (floor($item->preco / 2) * $_POST['total']));
				
				$basePlayer->removeItem($item, $_POST['total']);
				
				break;
			
			case 2: // Auction
				if($_POST['total'] > $qt || $_POST['total'] <= 0) {
					die('jalert("'.t('actions.a116').'")');
				}
				
				if($_POST['bid'] <= 0) {
					die('jalert("'.t('actions.a117').'")');
				}
				
				switch($_POST['time']) {
					case 1: $end_time = '+1 hour'; break;
					case 2: $end_time = '+2 hour'; break;
					case 3: $end_time = '+4 hour'; break;
					case 4: $end_time = '+8 hour'; break;
					case 5: $end_time = '+12 hour'; break;
					case 6: $end_time = '+24 hour'; break;
					default:
						die('jalert("'.t('actions.a117').'")');
					
						break;
				}
				
				if($_POST['has_buyout']) {
					$buyout	= $_POST['buyout'];
					
					if($buyout < $_POST['bid']) {
						die('jalert("'.t('actions.a118').'")');
					}
				} else {
					$buyout	= 0;		
				}
				
				Recordset::update('player_item', array(
					'venda'			=> '1',
					'venda_total'	=> $_POST['total']
				), array(
					'id'			=> $item->uid
				));
				
				Recordset::insert('leilao', array(
					'id_player'		=> $basePlayer->id,
					'id_item'		=> $item->id,
					'id_item_tipo'	=> $item->id_tipo,
					'total'			=> $_POST['total'],
					'start_time'	=> date('Y-m-d H:i:s'),
					'end_time'		=> date('Y-m-d H:i:s', strtotime($end_time)),
					'bid'			=> $_POST['bid'],
					'buyout'		=> $buyout
				));
				
				die('jalert("'.t('actions.a120').'")');
					
				break;
		}
	}

	//if(!$item->drop) {
		die(t('actions.a121'));
	//}	
?>
<form id="f-acution" onsubmit="return false">
<input type="hidden" name="key" value="<?php echo $_POST['key'] ?>" />
<input type="hidden" name="item" value="<?php echo $_POST['item'] ?>" />
<input type="hidden" name="action" value="2" />
<h2>Leiloe esse item</h2>
<p>
	<label>Quantidade para vender</label>
	<select name="total">
		<?php for($f = 1; $f <= $qt; $f++): ?>
		<option value="<?php echo $f ?>"><?php echo $f ?></option>
		<?php endfor; ?>
	</select>
</p>
<p>
	<label>Lance mínimo</label>
	<input type="text" name="bid" class="t-bid" />
</p>
<p>
	<label><input type="checkbox" value="1" name="has_buyout" class="c-has-buyout" /> Preço de compra direto</label>
	<input type="text" name="buyout" class="t-buyout" disabled="disabled" />
</p>
<p>
	<label>Tempo de leilão</label><br />
	<input name="time" type="radio" value="1" /> 1 Horas<br />
	<input name="time" type="radio" value="2" /> 2 Horas<br />
	<input name="time" type="radio" value="3" /> 4 Horas<br />
	<input name="time" type="radio" value="4" /> 8 Horas<br />
	<input name="time" type="radio" value="5" /> 12 Horas<br />
	<input name="time" type="radio" value="6" /> 24 Horas
</p>
</form>
<script type="text/javascript">
	$('.c-has-buyout').click(function () {
		if($(this)[0].checked) {
			$('.t-buyout').removeAttr('disabled').focus();
		} else {
			$('.t-buyout').attr('disabled', 'disabled');
		}
	});	
</script>