<?php
	require_once(ROOT . '/include/generic.php');

	$protocol	= $_SERVER['SERVER_PORT'] == '443' ? 'https' : 'http';
	$banned		= Recordset::query('SELECT id FROM chat_banned WHERE id_usuario=' . $_SESSION['usuario']['id'])->num_rows;
	$village	= Recordset::query('SELECT id_kage, id_cons_defesa, id_cons_vila, id_cons_guerra FROM vila WHERE id=' . $basePlayer->id_vila)->row_array();

	// Chat R10
	//$is_special	= Recordset::query('SELECT id FROM chat_special WHERE id_usuario=' . $_SESSION['usuario']['id'])->num_rows ? true : false;
	
	$color			= '';
	$multi_pvp_pos	= '';

	if($village['id_kage'] == $basePlayer->id) {
		$color	= USER_COLOR_KAGE;
	}
	
	if($village['id_cons_defesa'] == $basePlayer->id) {
		$color	= USER_COLOR_CONS_DEF;
	}

	if($village['id_cons_vila'] == $basePlayer->id) {
		$color	= USER_COLOR_CONS_VILA;
	}

	if($village['id_cons_guerra'] == $basePlayer->id) {
		$color	= USER_COLOR_CONS_GUERRA;
	}

	if($basePlayer->id_batalha_multi_pvp && $basePlayer->id_random_queue) {
		$battle_instance	= Recordset::query('SELECT * FROM batalha_multi_pvp WHERE id=' . $basePlayer->id_batalha_multi_pvp)->row_array();
	
		for($f = 0; $f <= 1; $f++) {
			$key	= $f ? 'e' : 'p';
			
			for($i = 1; $i <= PVPT_MAX_TURNS / 2; $i++) {
				$object	= unserialize($battle_instance[$key . $i]);
				
				if($object->id == $basePlayer->id) {
					if($f == 0) {
						$multi_pvp_pos	= '_1';
					} else {
						$multi_pvp_pos	= '_2';
					}
					
					break;
				}
			}
		}
	}

	$json				= new stdClass();
	$json->uid			= $basePlayer->id;
	$json->user_id		= $basePlayer->id_usuario;
	$json->village		= $basePlayer->id_vila;
	$json->org			= $basePlayer->id_guild;
	$json->org_owner	= $basePlayer->dono_guild;
	$json->team			= $basePlayer->id_equipe;
	$json->team_owner	= $basePlayer->dono_equipe;
	$json->battle		= $basePlayer->id_batalha;
	$json->battle4		= $basePlayer->id_batalha_multi_pvp . $multi_pvp_pos;
	//$json->gm			= in_array($_SESSION['usuario']['id'], array(2,4,6,11));
	$json->gm			= $_SESSION['universal'] ? 1 : 0;
	$json->color		= $color;
	$json->icon			= player_icone($basePlayer->id);
	$json->name			= $basePlayer->nome;
?>
<style type="text/css">
	#chat-v2 {
		position: fixed;
		bottom: 0px;
		left: 20px;
		width: 282px;
		overflow: hidden;
		z-index: 12;
	}
	
	#chat-v2 .title {
		background-image: url(<?php echo img('layout'.LAYOUT_TEMPLATE.'/chat/chat_title.png') ?>);
		padding-left: 5px;
		margin-bottom: 1px;
	}
	
	#chat-v2 .title .titulo-home {
		width: 282px !important;
		cursor: pointer
	}

	#chat-v2 .chat-bg {
		background-color: #333;
		margin-top: -1px;
		padding-top: 1px;		
	}
	
	#chat-v2 .messages {
		height: 312px;
		width: 282px;
		margin-bottom: 1px;
		overflow: auto;
		word-wrap: break-word;
		position: relative
	}

	#chat-v2 .selector {
		width: 282px;
		height: 43px;
		position: relative
	}

	#chat-v2 .selector ul {
		position: absolute;
		bottom: 0px;
		margin-bottom: 29px;
		left: 8px;
		background-color: #333;
		width: 82px;
		padding-left: 23px;
		border-radius: 5px;
		padding-bottom: 10px;
	}

	#chat-v2 .selector ul li {
		height: 20px;
		padding-top: 8px;
		margin-bottom: 2px;
		cursor: pointer
	}
	
	#chat-v2 .selector #message {
		width: 206px;
		border-radius: 5px;
		background-color: #333;
		border: none;
		height: 27px;
		margin-left: 8px;
		margin-top: 7px;
		padding-left: 60px;
		color: #999
	}
	
	#chat-v2 .selector #as {
		position: absolute;
		right: 10px;
		bottom: 11px;
	}
	
	#chat-v2 .selector-trigger {
		background-image: url(<?php echo img('layout/chat/chat_channel.png') ?>);
		background-repeat: no-repeat;
		position: absolute;
		left: 15px;
		top: 16px;
		padding-left: 18px;
		font-size: 11px;
		cursor: pointer
	}

	textarea:focus, input:focus{
		outline: none;
	}
	
	#chat-v2 .chat-world {
		color: #B2B2B2
	}

	#chat-v2 .chat-village {
		color: #B2B2B2
	}
	
	#chat-v2 .chat-gm {
		color: #FFB34F !important
	}
	
	#chat-v2 .chat-gm span {
		background-image: url(<?php echo img('layout/chat/star.png') ?>);
		background-repeat: no-repeat;
		padding-left: 20px;		
	}

	#chat-v2 .chat-org {
		color: #4F72C4
	}

	#chat-v2 .chat-team {
		color: #72CFD6
	}

	#chat-v2 .chat-message {
		padding-left: 8px;
		padding-right: 8px;
		line-height: 16px;
		margin-bottom: 3px
	}
	
	#chat-v2 .chat-system {
		display: block;
		text-align: center;
		background-color: #FFCC99;
		margin: 4px;
		border-radius: 5px;
		padding: 4px;
		font-weight: bold;
		border: solid 1px #C24F15;
		color: #C24F15;		
	}

	#chat-v2 .chat-warn {
		display: block;
		text-align: center;
		background-color: #f95353;
		margin: 4px;
		border-radius: 5px;
		padding: 4px;
		font-weight: bold;
		border: solid 1px #790505;
		color: #FFF;		
	}
	
	.chat-pvt {
		position: fixed;
		bottom: 378px;
		left: 265px;
		cursor: pointer;
		z-index: 13
	}
	
	.chat-pvt .l {
		background-image: url(<?php echo img('/layout/chat/chat_notification_l.png') ?>);	
		width: 22px;
	}

	.chat-pvt .r {
		background-image: url(<?php echo img('/layout/chat/chat_notification_r.png') ?>);	
		background-position: top right;
		margin-left: -10px;
		padding-right: 12px;
		padding-top: 3px;
		height: 27px !important;
		font-weight: bold;
	}
	
	.chat-pvt .l, .chat-pvt .r {
		height: 30px;
		float: left
	}
	
	#chat-v2 .chat-user {
		font-weight: bold;
		cursor: pointer
	}
	
	.reply-box {
		background-image: url(<?php echo img('/layout/chat/chat_bg_private.png') ?>);
		width: 247px;
		height: 122px;
		position: fixed;
		left: 330px;
		bottom: 10px;
		z-index: 1000000;
	}
	
	.reply-box .next, .reply-box .close {
		position: absolute;
		right: 10px;
		bottom: 13px;
		background-repeat: no-repeat;
		background-image: url(<?php echo img('layout/chat/chat_next.png') ?>);
		background-position: right bottom;
		padding-right: 14px;
		cursor: pointer;		
	}
	
	.reply-box .reply {
		position: absolute;
		bottom: 13px;
		left: 23px;
		background-position: left center;
		background-repeat: no-repeat;
		background-image: url(<?php echo img('layout/chat/chat_reply.png') ?>);
		padding-left: 14px;		
		cursor: pointer;		
	}
	
	.reply-box .close {
		background-image: none !important;
	}
	
	.reply-box .from {
		position: absolute;
		left: 23px;
		top: 8px;
		font-weight: bold;		
	}
	
	.reply-box .wait {
		position: absolute;
		bottom: 13px;
		margin-left: 23px;
		width: 216px;
		text-align: center;
		font-weight: bold;		
	}
	
	.reply-box .text {
		position: absolute;
		left: 23px;
		top: 30px;
		width: 216px;
		display: block;		
	}
</style>
<div id="chat-v2">
	<div class="title">
		<div class="titulo-home" style="margin-left: 50px; padding-top: 5px;"><p>Chat Naruto Game</p></div>
	</div>
	<div class="chat-bg">
		<div class="messages">
			<div align="center" class="wait"><?php echo $basePlayer->level > 0 ? "Aguarde..." : "Chat disponivel no level 7"?></div>
		</div>
		<div class="selector">
			<?php if(!$banned): ?>
			<ul style="display: none">
				<li data-channel="world" data-cmd="m"><?php echo t('templates.t73')?></li>
				<li data-channel="village" data-cmd="v"><?php echo $basePlayer->nome_vila ?></li>
				<?php if($basePlayer->id_equipe): ?>
				<li data-channel="team" data-cmd="e"><?php echo t('templates.t55')?></li>
				<?php endif ?>
				<?php if($basePlayer->id_guild): ?>
				<li data-channel="org" data-cmd="o"><?php echo t('templates.t56')?></li>
				<?php endif ?>
				<?php if($basePlayer->id_batalha): ?>
				<li data-channel="battle" data-cmd="b"><?php echo t('templates.t57')?></li>
				<?php endif ?>
				<?php if($basePlayer->id_batalha_multi_pvp): ?>
				<li data-channel="battle4" data-cmd="4">4x4 PVP</li>
				<?php endif ?>

				<?php /* if($is_special): ?>
				<li data-channel="r10" data-cmd="r10">Round 10</li>
				<?php endif 

				<?php if($_SESSION['universal']): ?>
				<li data-channel="system" data-cmd="s"><?php echo t('templates.t58')?></li>
				<?php endif ?>*/ ?>
			</ul>
			<div class="selector-trigger">Mundo</div>
            <?php //if($basePlayer->level > 6): ?>
			<input type="text" id="message" autocomplete="off" name="message" <?php if(!$_SESSION['universal']): ?>maxlength="60"<?php endif?> />
            <?php //endif ?>
			<input type="checkbox" id="as" checked="checked" class="auto-scroll" />
			<?php else: ?>
			<div style="text-align: center; padding-top: 9px; font-weight: normal !important">
				<span style="font-size: 15px; color: #A61E1E">Você foi banido do chat.</span><br />
				Isso é válido para todos os personagens da sua conta.
			</div>
			<?php endif ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	(function () {
		<?php /*if($_SESSION['universal']): ?>
		var __chat_socket	= io.connect('http://chat.<?php echo $_SERVER['SERVER_NAME'] == 'localhost' ? $_SERVER['SERVER_ADDR'] : $_SERVER['SERVER_NAME'] ?>:2534');
		<?php else: ?>
		var __chat_socket	= io.connect('http://<?php echo $_SERVER['SERVER_NAME'] == 'localhost' ? $_SERVER['SERVER_ADDR'] : $_SERVER['SERVER_NAME'] ?>:2534');
		<?php endif*/ ?>

		var __chat_socket	= io.connect('<?php echo $protocol ?>://<?php echo $_SERVER['SERVER_NAME'] == 'localhost' ? $_SERVER['SERVER_ADDR'] : "" . $_SERVER['SERVER_NAME'] ?>:2934');

		var has_type		= false;
		var	channel			= 'village';
		var	real_channel	= 'village';
		var	pvt_dest		= 0;
		var	last_pvt_index	= 0;
		var	trigger_pvt		= false;
		var pvt_data		= null;
		var last_msg		= new Date();
		var blocked			= [];
		var pm_total		= 0;
		
		function resize_selector() {
			var	w	= 266;
			var	tw	= $('#chat-v2 .selector-trigger').outerWidth() + 10;
			
			$('#chat-v2 input[name=message]').css({
				width:	w-tw,
				paddingLeft: tw
			});		
		}

		function diff_in_secs(d1, d2) {
			var diff		= d2 - d1,
				sign		= diff < 0 ? -1 : 1,
				milliseconds,
				seconds,
				minutes,
				hours,
				days;
			
			diff	/= sign;
			diff	= (diff-(milliseconds=diff%1000))/1000;
			diff	= (diff-(seconds=diff%60))/60;
			diff	= (diff-(minutes=diff%60))/60;
			days	= (diff-(hours=diff%24))/24;

			return seconds;
		}
		
		__chat_socket.on('error', function () {
			$('#chat-v2 .messages').html(
				'<div style="padding: 10px">Ocorreu um problema ao conectar ao chat.<br /><br />Você pode ter algum firewall(isso inclui programas anti-hack de jogos on-line)' +
				' ou anti-vírus bloqueando o chat.<br /><br />Se sua rede está conectada através de um proxy, o proxy pode estar bloqueando as conexões ou não suporta conexões via websocket</div>'
			);
		});
	
		__chat_socket.on('connect', function () {
			__chat_socket.emit('register', {
				data: '<?php echo salt_encrypt(json_encode($json), CHAT_KEY) ?>'
			});
			
			$('#chat-v2 .messages .wait').remove();
		});
		
		__chat_socket.on('blocked-broadcast', function (data) {
			blocked	= data;
		});
		__chat_socket.on('pvt-broadcast', function (data) {
			var	container	= $('.chat-pvt .r');
			
			pvt_data	= data;
			
			if(!container.length) {
				if(!data.length) {
					return;
				}
				
				var	l	= $(document.createElement('DIV')).addClass('l');
				var	r	= $(document.createElement('DIV')).addClass('r');
				var	c	= $(document.createElement('DIV')).addClass('chat-pvt');
			
				c.append(l, r);
				container	= r;

				$(document.body).append(c);
				
				c.on('click', function () {
					function dispatch_pvt_read(id) {
						__chat_socket.emit('pvt-was-read', {index: id});
					}

					if(this.shown) {
						$('.reply-box').remove();
						this.shown	= false;
						
						return;
					}
					
					this.shown			= true;
					var	_this			= this;
					
					var	msg_container	= $(document.createElement('DIV')).addClass('reply-box');
					var	msg_reply		= $(document.createElement('A')).html('Responder').addClass('reply');
					var	msg_next		= $(document.createElement('A')).html(pvt_data.length == 1 ? 'Fechar' : 'Próxima').addClass(pvt_data.length == 1 ? 'close' : 'next');
					var	msg_from		= $(document.createElement('SPAN')).html(pvt_data[0].from).addClass('from');
					var	msg_text		= $(document.createElement('SPAN')).html(pvt_data[0].message).addClass('text');
					
					msg_reply.on('click', function () {
						$('#chat-v2 .selector-trigger')
							.html(pvt_data[0].from)
							.css({backgroundImage: 'url(<?php echo img('layout/chat/chat_pvt.png') ?>)'})
							[0].shown = false;
	
						channel		= 'private';
						pvt_dest	= pvt_data[0].id;
		
						$('#chat-v2 input[name=message]').focus();
						resize_selector();						
					});
					
					if(pvt_data.length == 1) {
						msg_next.on('click', function () {
							dispatch_pvt_read(last_pvt_index);
							
							msg_container.remove();
							c.remove();
						});
					} else {
						msg_next.on('click', function () {
							msg_reply.remove();
							msg_next.remove();
							msg_from.remove();
							msg_text.remove();

							// No próximo broadcast ele recarrega =)
							trigger_pvt	= true;
							_this.shown	= false;
							
							msg_container.append('<div class="wait">Aguarde...</div>');

							dispatch_pvt_read(last_pvt_index);
							pm_total--;
						});						
					}
					

					msg_container.append(msg_from, msg_text, msg_reply, msg_next);
					$(document.body).append(msg_container);					
				});
			}
			
			container.html(data.length);
			
			<?php if(isset($_SESSION['usuario']['sound']) && $_SESSION['usuario']['sound']): ?>
			if(data.length > pm_total) {
				pm_total	= data.length;
				$(document.body).append('<audio autoplay><source src="<?php echo img('media/pm.wav') ?>" type="audio/wav" /></audio>');
			}
			<?php endif ?>
			
			if(!data.length && container.length) {
				pm_total	= 0;
				
				container.remove();
			}
			
			last_pvt_index	= data[0].index;
			
			if(trigger_pvt) {
				$('.reply-box').remove();
				
				if(data.length) {
					container.trigger('click');
				}
					
				trigger_pvt	= false;
			}

			if(!parseInt($.cookie('chatv2_show'))) {
				$('.chat-pvt').css({bottom: 30});
			} else {
				$('.chat-pvt').css({bottom: 378});
			}
		});
		__chat_socket.on('broadcast', function (data) {
			/*if(data.channel == 'system' || data.channel == 'warn') {
				$('#chat-v2 .messages').append('<div class="chat-message chat-' + data.channel + '"><div>Aviso de sistema</div><div>' + data.message + '</div></div>')				
			
				return;	
			}
			*/
			if(data.channel != real_channel) {
				return;	
			}
			
			if(!parseInt($.cookie('chatv2_show'))) {
				return;	
			}
			
			// GLobal user block -->
				var is_blocked	= false;
				
				blocked.forEach(function (id) {
					if(parseInt(data.user_id) == parseInt(id)) {
						is_blocked	= true;
					}
				});
				
				if(is_blocked) {
					return;
				}
			// <--
			
			$('#chat-v2 .messages').append(
				'<div ' + (data.color ? 'style="color: ' + data.color + '!important"' : '') + ' class="chat-message chat-' + data.channel + '' + (data.gm ? ' chat-gm' : '') + '">' +
				'<span class="chat-user" data-id="' + data.id + '" data-from="' + data.from + '">' + (data.icon || '') + data.from + '</span>:&nbsp;' + data.message + '</div>')
	
			$('#chat-v2 .messages .chat-user').each(function() {
				if(this.with_callback) {
					return;
				}
	
				this.with_callback	= true;
				var	_				= $(this);
	
				_.on('click', function() {
					if(channel == 'block') {
						$('#chat-v2 input[name=message]').val($(this).data('from')).focus();
					
						return;
					}
				
					$('#chat-v2 .selector-trigger')
						.html(this.innerHTML)
						.css({backgroundImage: 'url(<?php echo img('layout/chat/chat_pvt.png') ?>)'})
						[0].shown = false;

					$('#chat-v2 .selector ul').animate({opacity: 0}, function () {
						$(this).hide()
					});
	
					channel		= 'private';
					pvt_dest	= _.data('id');
	
					$('#chat-v2 input[name=message]').focus();
					resize_selector();
				});
			});
			
			if(has_type) {
				$('#chat-v2 .messages').scrollTop(1000000);				
				has_type	= false;
			}
			
			if($('#chat-v2 .auto-scroll:checked').length) {
				$('#chat-v2 .messages').scrollTop(1000000);				
			}
		});
	
		<?php if(!$banned): ?>
		$(document).ready(function(e) {
			$('#chat-v2 input[name=message]').on('keyup', function(e) {
				var	message	= $(this);
				
				if(e.keyCode == 13 && this.value) {
					<?php if(!$_SESSION['universal']): ?>
						var now	= new Date();
						
						if(diff_in_secs(last_msg, now) < 10) {
							return;
						}
					<?php endif ?>
					
					var broadcast_data	= {
						message:	this.value,
						channel:	channel,
						dest:		pvt_dest
					};
					
					__chat_socket.emit('message', broadcast_data);
					
					this.value	= '';
					has_type	= true;
					
					if(channel == 'private') {
						$('#chat-v2 .selector ul li').each(function () {
							if($(this).data('channel') == real_channel) {
								$(this).trigger('click');	
							}
						});
					} else {
						<?php if(!$_SESSION['universal']): ?>
							message.attr('disabled', 'disabled');
		
							last_msg	= now;
							var _iv1	= setInterval(function () {
								var	now	= new Date();
		
								if(diff_in_secs(last_msg, now) < 10) {
									message.val('Aguarde: ' + (10 - diff_in_secs(last_msg, now)) + ' segundo(s)');
								} else {
									message.removeAttr('disabled').val('');
									
									clearInterval(_iv1);
								}
							}, 1000);
						<?php endif ?>
					}
				}
				
				if(e.keyCode == 32) {
					$('#chat-v2 .selector ul li').each(function () {
						var _	= $(this);
						
						if(message.val().match(new RegExp('\^/' + _.data('cmd')))) {
							_.trigger('click');	
							
							message.val('');
						}
					});
					
					if(message.val().match(/^@[^\s]+/)) {
						var	dest	= message.val().replace(/[@<>]/img, '');
						
						$('#chat-v2 .selector-trigger')
							.html(dest)
							.css({backgroundImage: 'url(<?php echo img('layout/chat/chat_pvt.png') ?>)'})
							[0].shown = false;						

						message.val('');

						channel		= 'private';
						pvt_dest	= dest;
						
						resize_selector();
					}
					
					if(message.val().match('^/block')) {
						$('#chat-v2 .selector-trigger')
							.html('Bloquear')
							.css({backgroundImage: 'url(<?php echo img('layout/chat/chat_blocked.png') ?>)'})
							[0].shown = false;						
						
						channel	= 'block';
						
						message.val('');
						resize_selector();
					}
				}
			}).on('focus', function () {
				$('#chat-v2 #as').stop().animate({opacity: 0});
			}).on('blur', function () {
				$('#chat-v2 #as').stop().animate({opacity: 1});				
			}).on('pvt-switch', function (e, dest) {
				$('#chat-v2 .selector-trigger')
					.html(dest)
					.css({backgroundImage: 'url(<?php echo img('layout/chat/chat_pvt.png') ?>)'})
					[0].shown = false;						

				channel		= 'private';
				pvt_dest	= dest;
				
				resize_selector();
			});
	
			$('#chat-v2 .selector ul li').on('click', function () {
				$('#chat-v2 .selector-trigger')
					.html(this.innerHTML)
					.css({backgroundImage: 'url(<?php echo img('layout/chat/chat_channel.png') ?>)'})
					[0].shown = false;
				
				
				$('#chat-v2 .selector ul').animate({opacity: 0}, function () {
					$(this).hide()
				});
				
				channel			= $(this).data('channel');
				real_channel	= channel;
				
				<?php if(!$_SESSION['universal']): ?>
				if(channel == 'r10') {
					$('#chat-v2 #message').attr('maxlength', 500);
				} else {
					$('#chat-v2 #message').attr('maxlength', 60);
				}
				<?php endif ?>
				
				$('#chat-v2 .messages .chat-message').hide();
				
				$.cookie('chatv2_channel', channel);

				$('#chat-v2 .messages .chat-' + channel).show();
				$('#chat-v2 .messages .chat-warn').show();
				
				$('#chat-v2 input[name=message]').focus();
				resize_selector();
			});
	
			$('#chat-v2 .selector-trigger').on('click', function () {
				if(!this.shown) {
					$('#chat-v2 .selector ul').show().animate({opacity: 1});
					
					this.shown	= true;
				} else {
					$('#chat-v2 .selector ul').animate({opacity: 0}, function () { $(this).hide() });
					
					this.shown	= false;			
				}
			});

			if($.cookie('chatv2_channel')) {
				var	current		= $.cookie('chatv2_channel');
				var was_found	= false;
				
				$('#chat-v2 .selector ul li').each(function () {
					var _		= $(this);
					
					if(_.data('channel') == current) {
						_.trigger('click');
						
						was_found	= true;
					}
				});
				
				if(!was_found) {
					$('#chat-v2 .selector ul li').each(function () {
						var _		= $(this);
						
						if(_.data('channel') == 'village') {
							_.trigger('click');
						}						
					});
				}
			} else {
				$('#chat-v2 .selector ul li').each(function () {
					var _		= $(this);
					
					if(_.data('channel') == 'village') {
						_.trigger('click');
					}						
				});
			}			
		});
		<?php endif ?>
		
		var	__pvt_iv	= setInterval(function() {
			__chat_socket.emit('pvt-query');
		}, 2000)

		var	__block_iv	= setInterval(function() {
			__chat_socket.emit('blocked-query');
		}, 2000)

		$('#chat-v2 .title').on('click', function () {
			if(parseInt($.cookie('chatv2_show'))) {
				$('#chat-v2').animate({height: 52}, function () { $('#chat-v2 .chat-bg').hide(); });
				$('.chat-pvt').animate({bottom: 22});
				
				$.cookie('chatv2_show', 0);
			} else {
				$('#chat-v2 .chat-bg').show();
				
				$('#chat-v2').animate({height: 421});
				$('.chat-pvt').animate({bottom: 378});

				$.cookie('chatv2_show', 1);
			}
			
			resize_selector();
		});
		
		$('#chat-v2 .auto-scroll').on('click', function () {
			if(this.checked) {
				$.cookie('chatv2_as', 1);
			} else {
				$.cookie('chatv2_as', 0);				
			}
		});
		
		if(!parseInt($.cookie('chatv2_show'))) {
			$('#chat-v2').css('height', 52);
			$('#chat-v2 .chat-bg').hide();
		}
		
		if(parseInt($.cookie('chatv2_as'))) {
			$('#chat-v2 .auto-scroll')[0].checked = true;
		}
	})();
</script>