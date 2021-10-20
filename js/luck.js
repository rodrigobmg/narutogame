$(document).ready(function () {
	var	running			= false;
	var	currency		= null;
	var	type			= null;
	var expect			= [];
	var	cur_day			= 0;
	var result_message	= '';

	function _roll() {
		var	counter	= 1;
		var	stopped	= 0;

		//$('#luck-container #result').html(result_message).animate({top: '424px'});

		for(var i = 1; i <= 4; i++) {
			setTimeout(function () {
				var	stop	= false;
				var	current	= counter.toString();
				var	strip	= $('#luck-stripe-' + counter).css({backgroundImage: 'url(images/layout/luck/slot_blured.jpg)'});
				var	pos		= -(Math.random() * 3000);
				var	speed	= 70;
				var	iv		= setInterval(function () {
					pos	-= speed;

					var	top1	= (expect[current - 1] - 1) * 141;
					var	top2	= (expect[current - 1] - 1) * 141 + 20;

					strip.css({
						backgroundPosition: '0px ' + pos + 'px'
					});

					if(stop && Math.abs(pos) >= top1 && Math.abs(pos) <= top2) {
						clearInterval(iv);
						stopped++;

						if(stopped >= 4) {
							running = false;

							$('#luck-container .day-' + cur_day).addClass('green');
							$('#luck-container #result').html(result_message);//.animate({top: '477px'});
						}

						strip.css({backgroundPosition: '0px -' + (top1) + 'px'});
					}

					if(Math.abs(pos) >= 3243) {
						if(speed > 30) {
							pos	= -(Math.random() * 3000);
						} else {
							pos	= 0;
						}
					}
				}, 50);

				setTimeout(function () {
					speed	= 50;
				}, 1000);

				setTimeout(function () {
					speed	= 40;
				}, 2000);

				setTimeout(function () {
					speed	= 30;
					strip.css({backgroundImage: 'url(images/layout/luck/slot.jpg)'});
				}, 2500);

				setTimeout(function () {
					speed	= 20;
					stop	= true;
				}, 3000);

				setTimeout(function () {
					speed	= 15;
				}, 4000);

				counter++;
			}, (500 + (i * (Math.random() * 1000)) / 2));
		}
	}

	$('#luck-button').on('click', function () {
		if (running) {
			return;
		}

		running		= true;

		$.ajax({
			url:		'?acao=sorte_ninja_sorteio',
			type:		'post',
			dataType:	'json',
			data:		{pay_mode: currency, weekly: (type == 'weekly' ? 1 : 0)},
			success:	function (result) {
				if(result.success) {
					expect			= result.slot;
					cur_day			= result.today;
					result_message	= result.message;

					//$('.top-expbar-container .currency').html(result.currency);
					//$('#top-container .credits').html(result.credits);

					_roll();
				} else {
					var	errors	= [];

					result.errors.forEach(function (error) {
						errors.push('<li>&bull; ' + error + '</li>');
					});

					jalert('<ul>' + errors.join('') + '</ul>');

					running	= false;
				}
			}
		});
	});

	$("#luck-container #buttons").on('click', '.luck-button', function () {
		var	_	= $(this);

		currency	= _.data('currency');
		type		= _.data('type');

		$("#luck-container #buttons .luck-button").removeClass('selected');
		_.addClass('selected');
	});

	$("#luck-container .luck-button:first").trigger('click');
});