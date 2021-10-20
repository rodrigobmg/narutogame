<?php
	$tipos	= array(1, 2, 10, 11, 12, 13, 14, 15, 29, 30, 31);
	
	if($_POST && isset($_POST['do_bid'])) {
		if(!is_numeric($_POST['id'])) {
			die('Leilão inválido');
		}
	
		$item	= Recordset::query('SELECT * FROM leilao WHERE id=' . $_POST['id']);
		
		// Verif --->
			if(!$item->num_rows) {
				die('O leilão escolhido já expirou ou não existe!');
			}
			
			$item = $item->row_array();

			if($item['id_player'] == $basePlayer->id) {
				die('jalert("Você não pode dar lances em seus próprios leilões")');
			}
		
			if(!$item['buyout'] && $_POST['buyout']) {
				die('jalert("Compra direta não está disponível para esse item!")');
			}
		
			if($_POST['bid'] <= $item['last_bid'] && !$_POST['buyout']) {
				die('jalert("O valor do lance tem que ser maior que o último lance!")');
			}

			if($basePlayer->ryou < $_POST['bid']) {
				die('jalert("Você não tem ryous suficientes para esse lance!")');
			}
			
			if(date('YmdHis') > date('YmdHis', strtotime($item['end_time']))) {
				die('jalert("O leilão escolhido, já expirou!")');
			}
		// <---
		
		echo '$("#a-' . $_POST['id'] . '").hide("explode");';
		
		// Devolve a grana do manolo
		if($item['id_player_last']) {
			Recordset::update('player', array(
				'ryou'	=> array('escape' => false, 'value' => 'ryou+' . $item['last_bid'])
			), array(
				'id'	=> $item['id_player_last']
			));
			
			// Bids seguidos
			if($item['id_player_last'] == $basePlayer->id) {
				$basePlayer->ryou += $item['last_bid'];
			}
		}
		
		$basePlayer->setAttribute('ryou', $basePlayer->ryou - $_POST['bid']);
		
		Recordset::update('leilao', array(
			'id_player_last'	=> $basePlayer->id,
			'last_bid'			=> $_POST['bid'],
			'total_bids'		=> array('escape' => false, 'value' => 'total_bids+1')
		), array(
			'id'				=> $_POST['id']
		));
		
		if($_POST['buyout']) {
			$basePlayer->addItemW($item['id_item']);
			
			// Verifica as quantidades colocadas a venda relativo ao player de origem, e remove a quantidade ou o item em si --->
			$player_item		= new Item($item['id_item'], $item['id_player_last']);
			$player_item->qtd	= $player_item->equipado ? $player_item->qtd-- : $player_item->qtd;
			$player_item->qtd	= $player_item->venda ? $player_item->qtd - $player_item->venda_total: $player_item->qtd;
			
			if(!$player_item->qtd) {
				Recordset::delete('player_item', array(
					'id_player'	=> $item['id_player_last'],
					'id_item'	=> $item['id_item']
				));
			} else {
				Recordset::update('player_item', array(
					'qtd'			=> array('escape' => false, 'value' => 'qtd-' . $item['total']),
					'venda'			=> '0',
					'venda_total'	=> 0
				), array(
					'id_player'		=> $item['id_player_last'],
					'id_item'		=> $item['id_item']
				));
			}
			// <---
		}
		
		die();
	} elseif($_POST && isset($_POST['show_reqs'])) {
		if(!isset($_POST['item']) || isset($_POST['item']) && !is_numeric($_POST['item'])) {
			die('jalert("Item inválido")');
		}
	
		$item = new Item($_POST['item']);
		
		echo bonus_tooltip('d-ca-img-i', $item);
		
		die();
	}
?>
<script>
	var _tsa = [];
</script>
<div id="d-ah-search" <?php echo $_POST ? 'style="display: none"' : '' ?>>
	<div id="p-topo"></div>
	<form method="post" id="f-ah-search" onsubmit="return false">
		<p>
			<label>Raridade</label>
			<select name="raridade">
				<option value="comum">Comum</option>
				<option value="raro">Raro</option>
				<option value="epico">Épico</option>
				<option value="lendario">Lendário</option>
			</select>
		</p>
		<p>
			<label>Tipo</label>
			<select name="tipo">
				<option value="0">Todos</option>
				<?php foreach($tipos as $tipo): ?>
				<?php
					$tp = Recordset::query('SELECT nome FROm item_tipo WHERE id=' . $tipo, true)->row();
				?>
				<option value="<?php echo $tipo ?>"><?php echo $tp->nome ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label>Preço inicial</label>
			<input type="text" name="preco_i" value="0" />
		</p>
		<p>
			<label>Preço final</label>
			<input type="text" name="preco_e" value="0" />
		</p>
		<p>
			<label>Somente itens com "Compre agora"</label>
		</p>
		<p>
			<input type="button" id="f-ah-search-b-search" value="Pesquisar" />
		</p>
	</form>
	<div id="p-fim"></div>
</div>
<div <?php echo !$_POST ? 'style="display: none"' : '' ?>>
	<input type="button" id="b-new-search" value="Nova pesquisa" />
</div>
<?php if($_POST): ?>
	<?php
		$is_valid	= true;
		$where		= '';
		
		if(!isset($_POST['preco_i']) || !isset($_POST['preco_e']) || !isset($_POST['raridade']) || !isset($_POST['tipo'])) {
			$is_valid = false;
		}
		
		if($is_valid && strlen($_POST['preco_i']) && is_numeric($_POST['preco_i'])) {
			if($_POST['preco_i']) {
				$where .= ' AND last_bid >= ' . $_POST['preco_i'];
			}
		} elseif($is_valid && strlen($_POST['preco_i']) && !is_numeric($_POST['preco_i'])) {
			$is_valid = false;
		}

		if($is_valid && strlen($_POST['preco_e']) && is_numeric($_POST['preco_e'])) {
			if($_POST['preco_e']) {
				$where .= ' AND last_bid <= ' . $_POST['preco_e'];
			}
		} elseif($is_valid && strlen($_POST['preco_e']) && !is_numeric($_POST['preco_e'])) {
			$is_valid = false;
		}
		
		if($is_valid && (in_array($_POST['tipo'], $tipos) || $_POST['tipo'] == 0)) {
			if($_POST['tipo']) {
				$where .= ' AND id_item_tipo=' . $_POST['tipo'];
			}
		} else {
			$is_valid = false;
		}
		
		if($is_valid) {
			switch($_POST['raridade']) {
				case 'comum':		$where .= ''; break;
				case 'raro':		$where .= ''; break;
				case 'epico':		$where .= ''; break;
				case 'lendario':	$where .= ''; break;
				default:
					$is_valid	= false;
				
					break;
			}
		}
	?>
	<?php if($is_valid): ?>
	<div id="p-topo"></div>
	<div>
		<div id="d-ca-img"></div>
		<div>
			<p>Tempo restante: <span id="d-ca-bid-time">--</span></p>
			<p>Preço inicial: <span id="d-ca-bid">--</span></p>
			<p>Lance atual: <span id="d-ca-bid-last">--</span></p>
			<p>Seu lance: 
				<input type="button" id="b-bid-dec" value="-" />
				<span id="d-ca-bid-my">--</span>
				<input type="button" id="b-bid-inc" value="+" />
				<input type="button" id="b-bid-bid" value="Dar lance" />
			</p>
			<p id="p-ca-buyout">Compre agora por: <span id="d-ca-buyout">--</span> <input type="button" id="b-bid-buyout" value="Comprar" /></p>
		</div>
		<div>
			<p>Vendedor: <span id="d-ca-s">xx</span></p>
			<p>Total de lances: <span id="d-ca-bid-total">xx</span></p>
		</div>
	</div>
	<div id="p-fim"></div>
	<div>
	<?php
		$items	= Recordset::query('
			SELECT 
				a.id,
				a.id_item,
				TIMEDIFF(a.end_time, NOW()) AS diff,
				a.bid,
				a.buyout,
				a.last_bid,
				a.id_player_last,
				a.total,
				a.id_player_last,
				(CASE WHEN id_player_last != 0 THEN (SELECT nome FROM player WHERE id=id_player_last) END) AS nome_player_last,
				b.nome AS nome_player,
				c.imagem,
				c.nome AS nome_item,
				a.total_bids
			
			FROM
				leilao a JOIN player b ON b.id=a.id_player JOIN item c ON c.id=a.id_item
			
			WHERE
				1=1 ' . $where);
	?>
	<?php if(!$items->num_rows): ?>
	<div>Nenhum item encontrado</div>
	<?php endif; ?>
	<?php foreach($items->result_array() as $item): ?>
	<div class="s-item"><img id="a-<?php echo $item['id'] ?>" role="<?php echo $item['id'] ?>" src="<?php echo img($item['imagem']) ?>" /></div>
	<script>
		<?php
			$diff = explode(':', $item['diff']);
		?>
		_tsa[<?php echo $item['id'] ?>] = {
			id:		<?php echo $item['id_item'] ?>,
			uid:	<?php echo $item['id'] ?>,
			n:		'<?php echo addslashes($item['nome_item']) ?>',
			b:		<?php echo $item['bid'] ?>,
			lb:		<?php echo addslashes($item['last_bid']) ?>,
			bo:		<?php echo $item['buyout'] ?>,
			s:		'<?php echo addslashes($item['nome_player']) ?>',
			bl:		'<?php echo addslashes($item['nome_player_last']) ?>',
			t:		<?php echo $item['total'] ?>,
			bt:		<?php echo $item['total_bids'] ?>,
			tmr:	{
				h:	<?php echo (int)$diff[0] ?>,
				m:	<?php echo (int)$diff[1] ?>,
				s:	<?php echo (int)$diff[2] ?>
			}
		};
	</script>
	<?php endforeach; ?>
	</div>
	<?php else: ?>
	<div>Houve um problema recebendo as informações de pesquisa, atualize a página e tente novamente</div>
	<?php endif; ?>
<?php endif; ?>
<script>
	$(document).ready(function () {
		var _my_bid		= 0;
		var _min_bid	= 0;
		var _max_bid	= 0;
		var _bid_inc	= 100;
		var _c			= null;
		
		var	_ac_tmr		= setInterval(function () {
			for(var i in _tsa) {
				if(--_tsa[i].tmr.s < 0) {
					_tsa[i].tmr.s = 59;
					
					if(--_tsa[i].tmr.m < 0) {
						if(--_tsa[i].tmr.h < 0) {
							do_clean(_c.uid);
						}						
					}
				}
			}
		
			if(!_c) {
				$('#d-ca-bid-time').html('--');
				
				return;
			}
			
			$('#d-ca-bid-time').html(str_pad(_tsa[_c.uid].tmr.h, 2, 0, 1) + ':' + str_pad(_tsa[_c.uid].tmr.m, 2, 0, 1) + ':' + str_pad(_tsa[_c.uid].tmr.s, 2, 0, 1));
		}, 1000);
		
		$('#f-ah-search-b-search').bind('click', function () {		
			$.ajax({
				url:		'?acao=leilao_listar',
				data:		$('#f-ah-search').serialize(),
				type:		'post',
				success:	function (e) {
					$('#d-itens').html(e);
				}
			});
			
			_c = null;
			
			clearInterval(_ac_tmr);
			
			$('#d-itens').html('Aguarde…');
		});
		
		<?php if($_POST): ?>
		$('#b-new-search').bind('click', function () {
			$('#d-ah-search').show();
		});
		
		$('.s-item img').bind('click', function () {
			var _this	= $(this);
			var i		= _tsa[$(this).attr('role')];
			
			$('#d-ca-bid').html('RY$ ' + i.b);
			$('#d-ca-buyout').html('RY$ ' + i.bo);
			$('#d-ca-s').html(i.s);
			$('#d-ca-bid-last').html('RY$ ' + i.lb);
			$('#d-ca-bid-total').html(i.bt);
			$('#d-ca-bid-my').html('RY$ ' + ((i.lb || i.b) + 100));
			
			_my_bid		= ((i.lb || i.b) + 100);
			_min_bid	= i.lb || i.b;
			_max_bid	= i.bo;
			_c			= i;
			
			if(i.bo) {
				$('#p-ca-buyout').show();
			} else {
				$('#p-ca-buyout').hide();
			}
			
			// Tooltip
			$.ajax({
				url:		'?acao=leilao_listar',
				data:		{item: i.id, id: i.uid, show_reqs: 1},
				type:		'post',
				success:	function (e) {
					$('#d-ca-img').html(_this.clone().attr('id', 'd-ca-img-i')).append(e);
				}
			});			
		});
		
		$('#b-bid-dec').bind('click', function () {
			if(!_c) return;
		
			_my_bid -= _bid_inc;
			
			if(_my_bid <= _min_bid) {
				_my_bid += _bid_inc;
			}

			$('#d-ca-bid-my').html('RY$ ' + _my_bid);
		});

		$('#b-bid-inc').bind('click', function () {
			if(!_c) return;
		
			_my_bid += _bid_inc;
			
			if(_my_bid >= _max_bid && _max_bid > 0) {
				_my_bid -= _bid_inc;
			}
			
			$('#d-ca-bid-my').html('RY$ ' + _my_bid);
		});
		
		$('#b-bid-bid, #b-bid-buyout').bind('click', function () {
			if(!_c) {
				jalert('Nenhum leilão selecionado');
				
				return;
			}
		
			var data = {
				item:	_c.id,
				id:		_c.uid,
				buyout:	0,
				bid:	_my_bid,
				do_bid:	1
			};
			
			if($(this).attr('role')) {
				data.buyout = 1;
			}
			
			$.ajax({
				url:		'?acao=leilao_listar',
				type:		'post',
				data:		data,
				dataType:	'script'
			});

			do_clean(_c.uid);
			
			_c = null;			
		});
		
		function do_clean(id, d) {
			if(!d) {
				$('#d-ca-bid').html('--');
				$('#d-ca-buyout').html('--');
				$('#d-ca-s').html('--');
				$('#d-ca-bid-last').html('--');
				$('#d-ca-bid-total').html('--');
				$('#d-ca-bid-my').html('--');
				$('#d-ca-img').html('');
			}
		
			_c = null;
		}
		<?php endif; ?>
	});
</script>