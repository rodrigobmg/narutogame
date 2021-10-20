function dojoViewport(vID, obj) {
	$('#cnBatalhaDojo').hide();
	$('#cnLutadores').hide();
	$('#cnQueue').hide();
	$('#cnQueue1x').hide();

	switch(vID) {
		case 0: // Criar sala
			$('#cnBatalhaDojo').show();
		
			break;
		
		case 2: // Desafiar lutadores
			$('#cnLutadores').show();
			$('#cnLutadores .content').html("Atualizando a lista de lutadores...");

			$.ajax({
				url: 'index.php?acao=dojo_lutador_criar',
				type: 'post',
				data: '',
				success: function (e) { 
					$('#cnLutadores .content').html(e);
				}
			});

			break;
		
		case 3:
			$('#cnQueue').show();

			break;

		case 4:
			$('#cnQueue1x').show();

			break;
	}
}

function doLutarLutador() {
	$('#cnBase').html("Aguarde...");
	
	$.ajax({
		url: 'index.php?acao=dojo_lutador_lutar',
		type: 'post',
		data: {begin: 1},
		dataType: "script",
		success: function (e) { 
			//location.href = '?secao=dojo_batalha_lutador';
		}
	});	
}

function doCriarBatalha() {
	if(!$F('fCriarBatalha').tBatalhaNome.value) {
		alert("Por favor diga como quer anunciar sua batalha.");
		return;
	}
	
	$.ajax({
		url: 'index.php?acao=dojo_batalha_criar',
		dataType: 'script',
		type: 'post',
		data: {nome: $F('fCriarBatalha').tBatalhaNome.value, 'same-level': $F('fCriarBatalha')['same-level'].checked ? 1 : 0}
	});
}

function doDesafioDojoPVP(id) {
	$(".bDesafioDojoPVP").each(function () { $(this).attr('disabled', true) });
	
	$.ajax({
		url: 'index.php?acao=dojo_batalha_lutar',
		type: 'post',
		data: {begin: 1, bid: id},
		dataType: 'script'
	});		
}