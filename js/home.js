var _i = 0;

function homeRanking(i) {
	_i += i;
	_i = _i < 0 ? 0 : _i;
	
	$.ajax({
		url: "?acao=home_ranking",
		type: "post",
		data: {start: _i},
		success: function (e) {
			$("#cnRankingH").html(e);
		}
	});
}

$(document).ready(function () {
	homeRanking(0);
});

function homeNoticias(i) {

	$.ajax({
		url: "?acao=home_noticias",
		type: "post",
		data: {start: i},
		success: function (e) {
			$("#areaNoticias").html(e);
		}
	});
}