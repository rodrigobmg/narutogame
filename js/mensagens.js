function messageDoCompose(r, c, v) {
	var d	= $(document.createElement("DIV"));
	
	$(document.body).append(d);

	d.dialog({
		modal: true,
		width: "500",
		title: "Criar mensagem",
		close: function () {
			d.remove();
		},
		buttons: {	
			"Enviar": function () {
				lock_screen(true);

				$.ajax({
					url: 		'?acao=mensagens_enviar&id=' + $("#f-messages-compose-h-reply-id").val(),
					type: 		"post",
					data: 		$("#f-messages-compose").serialize(),
					dataType:	'json',
					success: function (result) {
						lock_screen(false);

						if(result.success) {
							d.remove();
						} else {
							var	errors	= [];

							result.messages.forEach(function (error) {
								errors.push('<li>&bull; ' + error + '</li>');
							});

							jalert('<ul>' + errors.join('') + '</ul>');
						}
					}
				});
			},
			"Cancelar": function () {
				d.remove();
			}
		}
	});
		
	d.html("Carregando...").ajaxError(function (e, xhr) {
		d.remove();
		
		jalert(xhr.responseText);
	}).load("?acao=mensagens_ler&reply=" + (r || '') + '&vila=' + (v ? 1 : 0), function () {
		if(c) {
			$("#f-messages-compose-t-to").val(c);
		}

		if(v && !$('input', d).length) {
			$('.ui-dialog-buttonpane button:first', d.parent()).remove();
		}
	});
}

function doMessageRead(i, g, v) {
	var d = $(document.createElement("DIV"));
	
	d.html("Carregando...").ajaxError(function (e, xhr) {
		d.remove();
		
		jalert(xhr.responseText);
	}).load("?acao=mensagens_ler&id=" + i + (g ? '&global=1' : '') + (v ? '&vila_ler=1' : ''));	
	
	d.dialog({
		modal: true,
		width: "500",
		title: "Ler mensagem",
		close: function () {
			d.remove();
		}
	})
	
	if(g || v) {
		if(v) {
			d.dialog({
				buttons: {
					"Excluir":	function () {
						$('#message-tr-' + i).remove();
						d.remove();
						
						$.ajax({
							url:	'?acao=mensagens_excluir&vila=1',
							data:	{id: i},
							type:	'post'
						});
					},
					"Fechar": function () {
						d.remove();
					}
				}
			});
		} else {
			d.dialog({
				buttons: {
					"Fechar": function () {
						d.remove();
					}
				}
			});				
		}
	} else {
		d.dialog({
			buttons: {
				"Responder": function () {
					messageDoCompose($("#h-messages-current-meesage-id").val());
					d.remove();
				},
				"Excluir":	function () {
					$('#message-tr-' + i).remove();
					d.remove();
					
					$.ajax({
						url:	'?acao=mensagens_excluir',
						data:	{id: i},
						type:	'post'
					});
				},
				"Fechar": function () {
					d.remove();
				}
			}
		});
	}
}

function messageDoRemoveAll() {
	var d = $(document.createElement("DIV"));

	d.html("Você realmente quer remover todas as suas mensagens?<br />" +
		   "Essa ação não pode ser desfeita.");

	d.dialog({
		modal: true,
		title: "Remover mensagens",
		close: function () {
			d.remove();
		},
		buttons: {
			"Continuar": function () {
				d.remove();
				
				$.ajax({
					url: 		'?acao=mensagens_excluir',
					data:		{a: 1},
					type:		'post',
					success:	function () {
						location.reload();
					}
				});				
			},
			"Cancelar": function () {
				d.remove();
			}
		}
	});
}

function messageDoMarkAllRead() {
	var d = $(document.createElement("DIV"));

	d.html("Você realmente quer marcar todas as suas mensagens como lidas?<br />" +
		   "Essa ação não pode ser desfeita.");

	d.dialog({
		modal: true,
		title: "Marcar mensagens",
		close: function () {
			d.remove();
		},
		buttons: {
			"Continuar": function () {
				d.remove();
				
				$.ajax({
					url: __site + "message/readall",
					success: function () {
						messageDoList();					
					}
				});				
			},
			"Cancelar": function () {
				d.remove();
			}
		}
	});
}

function messageDoBlockList() {
	var d = $(document.createElement("DIV"));

	d.html("Carregando...").attr("id", "d-messages-blocklist").load("?acao=mensagens_bloqueio");
	
	d.dialog({
		modal: true,
		width: "500",
		height: "350",
		title: "Jogadores bloqueados",
		close: function () {
			d.remove();
		},
		buttons: {
			"Fechar": function () {
				d.remove();
			}
		}
	});
}

function messageDoBlock() {
	$.ajax({
		url: "?acao=mensagens_bloqueio&option=1",
		data: $("#f-messages-blocklist").serialize(),
		type: "post",
		success: function (e) {
			$("#d-messages-blocklist").html(e);
		}
	});
	
	$("#d-messages-blocklist").html("Carregando Informações...");
}

function messageDoUnblock(i) {
	$.ajax({
		url: "?acao=mensagens_bloqueio&option=2",
		data: {player: i},
		type: "post",
		success: function (e) {
			$("#d-messages-blocklist").html(e);
		}
	});
	
	$("#d-messages-blocklist").html("Carregando Informações...");
}

function messagePage(p, t) {
	$('.message-item-' + t).hide();
	$('.message-item-' + t + '-' + p).show();
}