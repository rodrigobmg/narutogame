$(document).ready(function () {
	$('.inventory-container .inventory-data').on('click', '.item', function () {
		var	_			= $(this);
		var	container	= _.parent();
		var	qty			= _.data('quantity') - 1;

		if(_.data('usable')) {
			lock_screen(true);

			$.ajax({
				url:		'?acao=inventario',
				type:		'post',
				data:		{mode: 1, id: _.data('id')},
				dataType:	'json',
				success:	function (result) {
					if(result.redirect) {
						location.href	= result.redirect;
					} else {
						lock_screen(false);
					}

					if(result.success) {
						$('.qty', _).html('x ' + qty);
						_.data('quantity', qty);

						if(!qty) {
							_.hide('drop');
						}

						$('#cnPHPt').html(result.hp.current   + ' / ' + result.hp.max);
						$('#cnPSPt').html(result.sp.current   + ' / ' + result.sp.max);
						$('#cnPSTAt').html(result.sta.current + ' / ' + result.sta.max);
					}

					if(result.messages.length) {
						var	errors	= [];

						result.messages.forEach(function (error) {
							errors.push('<li>' + error + '</li>');
						});

						jalert('<ul>' + errors.join('') + '</ul>');
					}
				}
			});
		}

		if(_.data('sell')) {
			var	msg	= container.data('sell-confirm').replace('%item', _.data('quantity') + 'x ' + _.data('name'));
			msg		= msg.replace('%price', (_.data('quantity') * _.data('price')) / 2);

			jconfirm(msg, null, function () {
				lock_screen(true);

				$.ajax({
					url:		'?acao=inventario',
					type:		'post',
					data:		{mode: 2, item: _.data('id')},
					dataType:	'json',
					success:	function (result) {
						lock_screen(false);

						if(result.success) {
							_.hide('drop');

							$('#cnPRYt').html(result.ryou);
						}
					}
				});				
			});
		}
	});

	$('.inventory-container').on('click', '.inventory-trigger', function () {
		var	_			= $(this);
		var	container	= _.parentsUntil('.inventory-container').parent();
		var	data		= $('.inventory-data', _.parentsUntil('.inventory-container').parent());

		container.toggleClass('shown');

		$.ajax({
			url:		'?acao=inventario',
			type:		'post',
			data:		{mode: 0},
			success:	function (result) {
				data.html(result);
			}
		});
	});
});
