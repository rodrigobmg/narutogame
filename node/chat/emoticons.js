var	emoticons	= {
	'=P':		'1.png',
	':P':		'1.png',
	'=D':		'2.png',
	':D':		'2.png',
	'8D':		'3.png',
	';)':		'4.png',
	'=o':		'5.png',
	':o':		'5.png',
	"='(":		'6.png',
	":(":		'6.png',
	"=(":		'6.png',
	'=#':		'7.png',
	'=3':		'8.png',
	':3':		'8.png',
	'<_<':		'9.png',
	'XD':		'10.png',
	'D<':		'11.png',
	'=S':		'12.png',
	':S':		'12.png',
	':shuri:':	'13.gif'
}

var	emoticons_gm = {
	':lee:':		'alt1_1.gif',
	':lol:':		'alt1_2.png',
	':megusta:':	'alt1_3.png',
	':memeok:':		'alt1_4.png'
}

exports.parse	= function (text, gm) {
	for(var i in emoticons) {
		var	emoticon	= emoticons[i];

		while(text.indexOf(i) != -1) {
			text	= text.replace(i, ' <img src="/images/layout/emotes/' + emoticon + '" class="emoticon" /> ');			
		}
	}

	if(gm) {
		for(var i in emoticons_gm) {
			var	emoticon	= emoticons_gm[i];

			while(text.indexOf(i) != -1) {
				text	= text.replace(i, ' <img src="/images/layout/emotes/' + emoticon + '" class="emoticon" /> ');
			}
		}		
	}

	return text;
}