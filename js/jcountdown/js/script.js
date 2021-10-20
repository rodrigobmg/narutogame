$(function(){
	
	var note = $('#note'),
		ts = new Date(2018, 3, 24, 15),
		newYear = true;
	
	if((new Date()) > ts){
		// The new year is here! Count towards something else.
		// Notice the *1000 at the end - time must be in milliseconds
		ts = (new Date()).getTime() + 10*24*60*60*1000;
		newYear = false;
	}
		
	$('#countdown').countdown({
		timestamp	: ts,
		callback	: function(days, hours, minutes, seconds){
			
			var message = "";
			
			message += days + " Dia" + ( days==1 ? '':'s' ) + ", ";
			message += hours + " Hora" + ( hours==1 ? '':'s' ) + ", ";
			message += minutes + " Minuto" + ( minutes==1 ? '':'s' ) + " e ";
			message += seconds + " Segundo" + ( seconds==1 ? '':'s' ) + " <br />";
			
			if(newYear){
				message += "para o Round 21!!!";
			}
			else {
				message += "left to 10 days from now!";
			}
			
			note.html(message);
		}
	});
	
});
