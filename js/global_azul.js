$.ajaxSetup({ scriptCharset: "utf-8" , contentType: "application/x-www-form-urlencoded; charset=iso-8859-1"});

var NULL = null;

var _iMsgCallbackI = null;
var _iMsgCallbackState;

function format_error(result) {
	var errors	= [];

	(result.errors || result.messages).forEach(function (error) {
		errors.push('<li>&bull; ' + error + '</li>');
	});

	jalert('<h3>Os seguintes erros impediram de concluir a operação atual:</h3><br /><br /><ul>' + errors.join('') + '</ul>');
}

function _iMsgCallback() {
	_iMsgCallbackI = setInterval(function () {
		if(_iMsgCallbackState) {
			$("#iMsg").animate({opacity: .4}, 400);			
		} else {
			$("#iMsg").animate({opacity: 1}, 400);
		}
		
		_iMsgCallbackState = !_iMsgCallbackState;
	}, 1000);
}

function setPValue(val, mx, desc, obj, al) {
	val = val < 0 ? 0 : val;
	
	if(al) {
		if(val <= (mx / 4)) {
			$(".progFill", $(obj)).css("background-color", "#FF3300");
		} else if (val <= (mx / 2)) {
			$(".progFill", $(obj)).css("background-color", "#FF9900");
		}
	}
	
	$(".progFill", $(obj)).animate(
		{width: (($(obj).width() / mx) * val)}, 500
	);
	
	$(".progText", $(obj)).html(desc + (desc ? ":&nbsp;" : "") + val);
}

function setPValue2(val, mx, desc, obj, al) {
	var	_	= $(obj);
	val 	= val < 0 ? 0 : val;
	
	var _nw = ($(obj).width() / mx) * val;
	var _ow = $(obj).width();
	
	$("#p", _).animate(
		{width: (_nw > _ow ? _ow : _nw)}, 500
	).css({
		float:	_.data('dir') == 'l' ? 'right' : 'left'
	});
	
	if(_.data('dir') == 'l') {
		$('#t', _).css({right: 11});
	} else {
		$('#p', _).css({backgroundPosition: 'left center'});
	}
	
	$("#t", _).html(desc + (desc ? ":&nbsp;" : "") + val);
}

function isBrowserClosed(e) {
	if(navigator.userAgent.indexOf("MSIE") != -1) { // IE
		var browserWindowWidth = 0;
		var browserWindowHeight = 0;
	
		// gets the width and height of the browser window
		if (parseInt(navigator.appVersion) > 3) {
			if (navigator.appName == "Netscape") {
				browserWindowWidth = window.innerWidth;
				browserWindowHeight = window.innerHeight;
			}
	
			if (navigator.appName.indexOf("Microsoft") !=- 1) {
				browserWindowWidth = top.window.document.body.offsetWidth;
				browserWindowHeight = top.window.document.body.offsetHeight;
			}
		}
	
		var r = (event.clientY < 0 && event.screenX > (browserWindowWidth - 25)) ? true : false;
		
		if(!r) {
			if(window.event.clientY < 0) {
				return true;
			} else {
				return false; // WARN: CTRL + W goes here	
			}
		} else {
			return r;
		}
	} else { // FF
		if(!window.innerWidth) {
			return true;	
		} else {
			return false;	
		}
	}
}

function logoff() {
	if(typeof(_isPVP) == "undefined") _isPVP = false;
	
	if(_isPVP) {
		$.ajax({
			url: 'index.php?acao=dojo_batalha_lutar',
			type: 'post',
			data: {action: 2},
			dataType: 'script',
			async: false,
			success: function () {
				location.href = "?acao=logoff";
			},
			error: function () {
				location.href = "?acao=logoff";
			}
		});	
	} else {
		location.href = "?acao=logoff";
	}
}

function sairVila() {
	$.ajax({
		url: '?acao=mapa_vila&e',
		type: 'get',
		dataType: 'script',
		async: false
	});
}

function mapaVila() {
	$.ajax({
		url: '?acao=mapa_vila&v',
		type: 'get',
		dataType: 'script',
		async: false
	});
}

function updateTooltips() {
	$("div.ex_tooltip").each(function () {
		var t 			= $(this);
		var	is_absolute	= false;
		
		if(parseInt(t.attr("is_tooltip"))) {
			return;
		} else {
			t.attr("is_tooltip", 1);
		}
		
		$("#" + t.attr("title")).on('mouseover', function () {
			var	_	= $(this);
			
			if(parseInt(_.data('tooltip-float'))) {
				if(!this._was_append) {
					this._was_append	= true;
					
					$(document.body).append(t);
				}
				
				var	offset	= _.offset();
				
				t.css({
					position: 'absolute',
					top: offset.top + _.height() + 5,
					left: offset.left
				}).show();
			} else {
				if(_.data('no-anim')) {
					t.show();				
				} else {
					t.show().css('opacity', 0).animate({opacity: 1}, 400);
				}
	
				$('.icon', t).each(function () {
					$(this).css('margin-left', -($(this).width() + 5));
					
					t.css('margin-left', $(this).width());
				});
				
				if($(this).css('position') == 'absolute' || parseInt($(this).data('absolute'))) {
					t.css({
						left: 	$(this).position().left + $(this).width() + 5,
						top:	$(this).position().top
					});
					
					var	has_margin_compensation	= true;
				} else {
					var	has_margin_compensation	= false;				
				}
				
				var	left	= t.offset().left;
				var margin	= (($(window).width() - 1000) / 2) - (has_margin_compensation ? 50 : 0);
				
				//console.log('L:' + (left - margin) + ' / LW: ' + ((left - margin) + t.width()));
				
				if((left - margin) + t.width() > 1000) {
					if(has_margin_compensation) {
						t.css('margin-left', -(t.width() + _.width() + 30));
					} else {
						t.css('margin-left', -(t.width() + _.width() + 30 - margin));
					}
				}				
			}
		}).on('mouseout', function () {
			var	_	= $(this);

			if(_.data('no-anim')) {
				t.hide();				
			} else {
				t.stop().hide();
			}
		});
	});
}

function ieGoto(url) {
 var a = document.createElement("a");
 if(!a.click) { //only IE has this (at the moment);
  window.location = url;
  return;
 }
 a.setAttribute("href", url);
 a.style.display = "none";
 $("body").append(a); //prototype shortcut
 a.click();
 }

$(document).ready(function () {
	$("#pIcones .i").on('mouseover', function () {
		$(".t", $(this)).show().css("opacity", .9);
		
		$(".t", $(this)).each(function () {
			var	_		= $(this);
			var	left	= _.offset().left;
			var	margin	= (($(window).width() - 1000) / 2) - 50;
			
			if((left - margin) + _.width() > 1000) {
				_.css('margin-left', -(_.width()));
			}
		});
	}).on('mouseout', function () {
		$(".t", $(this)).hide();
		$(".invetoryDetailPopup").remove();
	});
});
var ___timers = [];
function createTimer(h, m, s, t, f, identifier, change_title) {
	var title	= document.title;
	var _t		= setInterval(function () {
		s--;
		
		if(s <= 0 && m <= 0 && h <= 0) {
			clearInterval(_t);
			
			if(!f) {			
				location.reload();
				return;
			} else {
				f.apply();
			}
		}
		
		if(s <= 0) {
			s = 59;
			m--;
			
			if(m <= 0 && h > 0) {
				h--;
				m = 59;
			}
		}
		
		if(t instanceof Array) {
			for(var ii in t) {
				$("#" + t[ii]).html(
					(h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s)
				);
			}
		} else {
			var	timer	= (h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s);
			
			if(change_title) {
				document.title	= '[' + timer + '] ' + title;
			}
			
			$("#" + t).html(timer);
		}
	}, 1000);
	
	if(!identifier) {
		___timers.push(_t);	
	} else {
		___timers[identifier]	= _t;
	}
}

function clearTimer(id) {
	clearInterval(___timers[id]);
}

function clearTimers() {
	for(var i in ___timers) {
		clearInterval(___timers[i]);
	}
	
	___timers = [];
}

function jalert(m, t, k, w) {
	if(!t) {
		t = "Aviso!";
	}
	
	var d = $(document.createElement("DIV"));
	
	d.html(m);
	
	$(document.body).append(d);
	
	d.dialog({
		modal: true,
		width: w || 300,
		title: t,
		close: function () {
			d.remove();

			try {
				k.apply([]);
			} catch(ee) {}
		},
		buttons: {
			"Fechar": function () {
				d.remove();

				try {
					k.apply([]);
				} catch(ee) {}
			}
		}
	});

	return d;
}

function jconfirm(m, t, k, c, w) {
	if(!t) {
		t = "Aviso";
	}

	var d = $(document.createElement("DIV"));
	
	d.html(m);
	
	$(document.body).append(d);
	
	d.dialog({
		modal: true,
		width: w || 300,
		title: t,
		close: function () {
			try {
				c.apply([]);
			} catch(ee) {}
			
			d.remove();
		},
		buttons: {
			"Cancelar": function () {
				try {
					c.apply([]);
				} catch(ee) {}
				
				d.remove();
			},
			"Ok": function () {
				try {
					k.apply([]);
				} catch(ee) {}
				
				d.remove();				
			}
		}
	});
	
	return d;
}

function is_flash_enabled() {
	if(!navigator.plugins.length) {
		try {
			var oFlash = eval("new ActiveXObject('ShockwaveFlash.ShockwaveFlash.9');");
			
			return oFlash ? true : false;		
		} catch(e) {
			return false;
		}
	}

	for(var i = 0; i < navigator.plugins.length; i++) {
		if(navigator.plugins.item(i).name.toLowerCase() == 'shockwave flash') {
			return true;
		}
	}
	return false;
}



function cronTimer(h, m, s, _h, _m, t) {
	var _t = setInterval(function () {
		s--;
		
		if(s <= 0 && m <= 0 && h <= 0) {
			//clearInterval(_t);
			s = 0;
			h = _h;
			m = _m;
		}
		
		if(s <= 0) {
			s = 59;
			m--;
			
			if(m <= 0 && h > 0) {
				h--;
				m = 59;
			}
		}
		
		if(t instanceof Array) {
			for(var ii in t) {
				$("#" + t[ii]).html(
					(h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s)
				);
			}
		} else {
			$("#" + t).html(
				(h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s)
			);
		}
	}, 1000);
}

function percent(p, v) {
	return Math.round(v * (p / 100));
}

function percentf(p, v) {
	return v * (p / 100);
}

function precision(v, p) {
	p = p || 2;
	v = v.toString().split('.');
	
	if(v.length > 1) {
		return v[0] + '.' + v[1].substr(0, p);
	} else {
		return v[0];
	}
}

function str_pad(s, l, c, p) {
	s = s.toString();
	c = c.toString();

	if(s.length < l) {
		for(var i = s.length; i < l; i++) {
			if(p == 0) {
				s += c;
			} else {
				s = c + s;
			}
		}
		
		return s;
	} else {
		return s;
	}
}

$(function(){
	$("#ajuda").click(function(){
		$("#msg_help").toggle("slow");
	});
});

$(document).ready(function(e) {
	setInterval(function () {
		$('.jui-button').each(function() {
			if(this.buttonized) {
				return;	
			}

			this.buttonized = true;
			
			$(this).button();
		});
		
		$('.with-paginator').each(function () {
			if(this.with_triggers) {
				return;	
			}
	
			this.with_triggers	= true;
			var	_				= $(this);
			var	hide_class		= _.data('paginator-class');
			
			$('a', _).on('click', function () {
				var	_this	= $(this);
				
				$('a', _).removeClass('current');
				_this.addClass('current');
				
				$(hide_class).hide();
				$(hide_class + '-' + _this.data('page')).show();
			});
			
			$('a:first', _).trigger('click');
		});
		
		$('.with-n-tabs').each(function () {
			if(this.with_triggers) {
				return;	
			}
	
			this.with_triggers = true;
			var _this 			= $(this);
			var	_target			= null;
			var _source			= null;
			var	_default_prefix	= 'tab-k-';
			var	_default_key	= _default_prefix + _this.attr('id');
			var _default_value	= $.cookie(_default_key);
			
			if(_this[0].tagName.toUpperCase() == 'SELECT') {
				var	_is_select	= true;
				
				_this.on('change', function () {
					var	_val	= _this.val();
					
					$.cookie(_default_key, _this.val());
					
					for(var i = 0; i < this.options.length; i++) {
						var	val	= $(this.options[i]).attr('value');
						
						if(val == '') {
							continue;	
						}
						
						if(_val != '') {
							$(val[0] == '.' ? val : '#' + val).hide();
						} else {
							$(val[0] == '.' ? val : '#' + val).show();
						}
						
						_this.trigger('changed');
					}
					
					if(_val != '') {
						$(_val[0] == '.' ? _val : '#' + _val).show();
					}

					$.cookie(_default_key, _val);					
				});
			} else {
				var	_is_select	= false;				

				$('a', _this).on('click', function () {
					var	_ 		= $(this);
					var	_rel	= _.attr('rel');
					
					$('a', _this).each(function () {
						var rel = $(this).attr('rel');
						
						if(!rel) return;
	
						if(this.__iv) {
							clearInterval(this.__iv);
						}
	
						$($(this).attr('rel')).hide();
						$(this).removeClass('tabs-hover ui-state-hover');
						$(this).data('selected', 0);					
					});
					
					_.addClass('tabs-hover ui-state-hover');
	
					$(_rel).show();
					
					$.cookie(_default_key, _rel);
					
					this.__iv	= setInterval(function () {
						if(!_.hasClass('ui-state-hover')) {
							_.addClass('ui-state-hover');
						}
					}, 30);

					$(_this).trigger('pagechanged');
					
					if(this.subtab_select) {
						this.subtab_select.trigger('change');
					}
					
					if(console) {
						console.log('tab click was made -> ' + _rel);	
					}
				});
			}

			if(!parseInt(_this.data('auto-default')) || !_default_value) {
				if(!_is_select) {
					$('a:first', _this).trigger('click');
				} else {
					_this.trigger('change');	
				}
			} else {
				if(_is_select) {
					_this.val(_default_value);
				} else {
					$('a', _this).each(function () {
						var	rel	= $(this).attr('rel');
						var __this	= this;
						
						if(rel == _default_value) {
							var __iv	= setInterval(function () {
								$(__this).trigger('click');	
								
								clearInterval(__iv);							
							}, 1000);
							
							if(console) {
								console.log('got default from cookie ' + _default_key + ' -> ' + _default_value);	
							}
						}
					});
				}				
			}
		});
		
		$('.with-int-tabs div').each(function () {
			if(this.stylized) {
				return;	
			}
	
			this.stylized = true;
			var _this = $(this).parentsUntil('table');
			
			$('a', $(this)).each(function () {
				$($(this).attr('rel')).hide();
			});
			
			$(this).bind('click', function () {
				$('div', _this).removeClass('bt_laranja');
				
				$(this).addClass('bt_laranja');

				$('a', _this).each(function () {
					$($(this).attr('rel')).hide();
				});
	
				$('a', $(this)).each(function () {
					$($(this).attr('rel')).show();
				});
			});
			
			$('div:first', _this).trigger('click');
		});
		
		$('.with-int-tabs div:first a').each(function() {
			if(this.triggered) {
				return;	
			}
	
			this.triggered = true;
			
			$(this).trigger('click');
		});
		
		$('.is-sub-tab-of').each(function () {
			if(this.with_triggers) {
				return;	
			}
	
			this.with_triggers	= true;
			this.last_objects	= [];
			var	_				= $(this);
			var	_this			= this;
			
			if(_.data('owner')) {
				$('a', $(_.data('owner'))).each(function () {
					if(_this.tagName.toUpperCase() == 'SELECT') {
						this.subtab_select	= _;
					} else {
						this.subtab_normal	= _;
					}
				});
			}
			
			if(_this.tagName.toUpperCase() == 'SELECT') {
				_.on('change', function () {
					var	val	= _.val();
					
					if(!val) {
						for(var i in this.last_objects) {
							$(this.last_objects[i]).show();	
						}
						
						_this.last_objects	= [];
					} else {
						var	elements	= $(_.data('of'));
						
						if(this.last_objects.length && console) {
							console.log('sub tab restore');	
						}

						for(var i in this.last_objects) {
							$(this.last_objects[i]).show();	
						}
						
						_this.last_objects	= [];

						if(console) {
							console.log('sub tab hide not ->' + val);	
						}
						
						elements.each(function(index, element) {
							if(!$(this).hasClass(val.replace(/\./img, ''))) {
								_this.last_objects.push(this);
								$(this).hide();
							}
						});
					}
				});
			} else {
				
			}
		});
	}, 50);	

	$('#area-esferas-links a').bind('click', function () {
		$('#area-esferas-marcador').css('position', 'absolute').css('top', $(this).attr('role') + "px").css('left', -14);
	});
});

function lock_screen(o) {
	if(o) {
		var d = $(document.createElement('DIV')).addClass('screen-lock');
		var dd = $(document.createElement('DIV')).addClass('screen-lock-text');
		
		dd.html('Aguarde...');
		
		$(document.body).append(d, dd).css('overflow', 'hidden');
		
		if(!window.has_screen_lock_callback) {
			window.has_screen_lock_callback	= true;
			
			$(window).on('resize', function () {
				$('.screen-lock')
					.css('width', $(window).width())
					.css('height', $(window).height());
			});
		}
	} else {
		$(document.body).css('overflow', 'auto');
		$('.screen-lock, .screen-lock-text').remove();
	}
}

/* Contador de Caracteres máximos para uma postagem em forums do jogo */
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}

head.ready(function () {
	$(document).ready(function () {
		$('textarea').each(function () {
			if(this.__with_cb) {
				return;	
			}
			
			this.__with_cb	= true;
			
			var	_ 		= $(this);
			var	maxlen	= _.attr('maxlength');
			
			if(parseInt(maxlen)) {
				var	watcher	= null;
				
				if(_.data('length-watch')) {
					watcher	= $(_.data('length-watch'));
				}
				
				_.on('keyup', function () {
					if(_.val().length > maxlen) {
						_.val(_.val().substr(0, maxlen));	
					}
					
					if(watcher) {
						watcher.html(maxlen - _.val().length);	
					}
				});
				
				_.trigger('keyup');
			}
		});
		
		setInterval(function () {
			$('.button, button').each(function () {
				if(this.buttonized) {
					return;	
				}
				
				this.buttonized = true;
				var	_		= $(this);
				var klass	= _.hasClass('ui-state-disabled') ? 'ui-state-disabled' : '';
			
				_.button()
				_.addClass(klass);
				
				if(navigator.appName.match(/opera/i)) {
					_.css({
						width: _.width(),
						height: _.height(),
						display: 'block',
						float: 'left'
					});
				}
				
				_.on('click', function () {
					if(_.data('trigger-form')) {
						if(_.parent()[0].tagName.toLowerCase() == 'form') {
							_.parent().submit();
						} else {
							_.parentsUntil('form').last().parent().submit();
						}
					}
				});
				
				if(_.data('auto-modal')) {
					_.on('click', function () {
						$('#' + _.data('auto-modal')).dialog({
							title:	_.data('title') || 'Informação',
							width:	_.data('width') || 300,
							height:	_.data('height') || 100,
							modal: true
						});
					});
				}
			});
		}, 100);
	});
});

function in_array (needle, haystack, argStrict) {
    var key = '',
        strict = !! argStrict;
 
    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {
                return true;
            }
        }
    }
 
    return false;
}

function sprintf () {
  // http://kevin.vanzonneveld.net
  // +   original by: Ash Searle (http://hexmen.com/blog/)
  // + namespaced by: Michael White (http://getsprink.com)
  // +    tweaked by: Jack
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      input by: Paulo Freitas
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Dj
  // +   improved by: Allidylls
  // *     example 1: sprintf("%01.2f", 123.1);
  // *     returns 1: 123.10
  // *     example 2: sprintf("[%10s]", 'monkey');
  // *     returns 2: '[    monkey]'
  // *     example 3: sprintf("[%'#10s]", 'monkey');
  // *     returns 3: '[####monkey]'
  // *     example 4: sprintf("%d", 123456789012345);
  // *     returns 4: '123456789012345'
  var regex = /%%|%(\d+\$)?([-+\'#0 ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([scboxXuideEfFgG])/g;
  var a = arguments,
    i = 0,
    format = a[i++];

  // pad()
  var pad = function (str, len, chr, leftJustify) {
    if (!chr) {
      chr = ' ';
    }
    var padding = (str.length >= len) ? '' : Array(1 + len - str.length >>> 0).join(chr);
    return leftJustify ? str + padding : padding + str;
  };

  // justify()
  var justify = function (value, prefix, leftJustify, minWidth, zeroPad, customPadChar) {
    var diff = minWidth - value.length;
    if (diff > 0) {
      if (leftJustify || !zeroPad) {
        value = pad(value, minWidth, customPadChar, leftJustify);
      } else {
        value = value.slice(0, prefix.length) + pad('', diff, '0', true) + value.slice(prefix.length);
      }
    }
    return value;
  };

  // formatBaseX()
  var formatBaseX = function (value, base, prefix, leftJustify, minWidth, precision, zeroPad) {
    // Note: casts negative numbers to positive ones
    var number = value >>> 0;
    prefix = prefix && number && {
      '2': '0b',
      '8': '0',
      '16': '0x'
    }[base] || '';
    value = prefix + pad(number.toString(base), precision || 0, '0', false);
    return justify(value, prefix, leftJustify, minWidth, zeroPad);
  };

  // formatString()
  var formatString = function (value, leftJustify, minWidth, precision, zeroPad, customPadChar) {
    if (precision != null) {
      value = value.slice(0, precision);
    }
    return justify(value, '', leftJustify, minWidth, zeroPad, customPadChar);
  };

  // doFormat()
  var doFormat = function (substring, valueIndex, flags, minWidth, _, precision, type) {
    var number;
    var prefix;
    var method;
    var textTransform;
    var value;

    if (substring === '%%') {
      return '%';
    }

    // parse flags
    var leftJustify = false,
      positivePrefix = '',
      zeroPad = false,
      prefixBaseX = false,
      customPadChar = ' ';
    var flagsl = flags.length;
    for (var j = 0; flags && j < flagsl; j++) {
      switch (flags.charAt(j)) {
      case ' ':
        positivePrefix = ' ';
        break;
      case '+':
        positivePrefix = '+';
        break;
      case '-':
        leftJustify = true;
        break;
      case "'":
        customPadChar = flags.charAt(j + 1);
        break;
      case '0':
        zeroPad = true;
        break;
      case '#':
        prefixBaseX = true;
        break;
      }
    }

    // parameters may be null, undefined, empty-string or real valued
    // we want to ignore null, undefined and empty-string values
    if (!minWidth) {
      minWidth = 0;
    } else if (minWidth === '*') {
      minWidth = +a[i++];
    } else if (minWidth.charAt(0) == '*') {
      minWidth = +a[minWidth.slice(1, -1)];
    } else {
      minWidth = +minWidth;
    }

    // Note: undocumented perl feature:
    if (minWidth < 0) {
      minWidth = -minWidth;
      leftJustify = true;
    }

    if (!isFinite(minWidth)) {
      throw new Error('sprintf: (minimum-)width must be finite');
    }

    if (!precision) {
      precision = 'fFeE'.indexOf(type) > -1 ? 6 : (type === 'd') ? 0 : undefined;
    } else if (precision === '*') {
      precision = +a[i++];
    } else if (precision.charAt(0) == '*') {
      precision = +a[precision.slice(1, -1)];
    } else {
      precision = +precision;
    }

    // grab value using valueIndex if required?
    value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++];

    switch (type) {
    case 's':
      return formatString(String(value), leftJustify, minWidth, precision, zeroPad, customPadChar);
    case 'c':
      return formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad);
    case 'b':
      return formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    case 'o':
      return formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    case 'x':
      return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    case 'X':
      return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad).toUpperCase();
    case 'u':
      return formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    case 'i':
    case 'd':
      number = +value || 0;
      number = Math.round(number - number % 1); // Plain Math.round doesn't just truncate
      prefix = number < 0 ? '-' : positivePrefix;
      value = prefix + pad(String(Math.abs(number)), precision, '0', false);
      return justify(value, prefix, leftJustify, minWidth, zeroPad);
    case 'e':
    case 'E':
    case 'f': // Should handle locales (as per setlocale)
    case 'F':
    case 'g':
    case 'G':
      number = +value;
      prefix = number < 0 ? '-' : positivePrefix;
      method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())];
      textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2];
      value = prefix + Math.abs(number)[method](precision);
      return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]();
    default:
      return substring;
    }
  };

  return format.replace(regex, doFormat);
}

jQuery.cookie=function(e,t,n){if(typeof t!="undefined"){n=n||{};if(t===null){t="";n.expires=-1}var r="";if(n.expires&&(typeof n.expires=="number"||n.expires.toUTCString)){var i;if(typeof n.expires=="number"){i=new Date;i.setTime(i.getTime()+n.expires*24*60*60*1e3)}else{i=n.expires}r="; expires="+i.toUTCString()}var s=n.path?"; path="+n.path:"";var o=n.domain?"; domain="+n.domain:"";var u=n.secure?"; secure":"";document.cookie=[e,"=",encodeURIComponent(t),r,s,o,u].join("")}else{var a=null;if(document.cookie&&document.cookie!=""){var f=document.cookie.split(";");for(var l=0;l<f.length;l++){var c=jQuery.trim(f[l]);if(c.substring(0,e.length+1)==e+"="){a=decodeURIComponent(c.substring(e.length+1));break}}}return a}}

var	should_scroll_the_top	= true;

$(document).ready(function () {
	var	_	= $(window);
	var	z	= $('#loggedin-menu').css('z-index');

	_.on('scroll', function () {
		if(!should_scroll_the_top) {
			return;
		}

		if(_.scrollTop() > 366) {
			$('#loggedin-menu').addClass('floatable-menu');
			//$('#background-topo').addClass('floatable-top');
			//$('#menu-bg-overlay').show();

			//$('#menu-character-topo').css('z-index', 1);

			$('#quest-queue-status').addClass('floatable-quest-queue');
		} else {
			$('#loggedin-menu').removeClass('floatable-menu');
			$('#background-topo').removeClass('floatable-top');
			//$('#menu-bg-overlay').hide();

			$('#loggedin-menu').css('z-index', z);

			$('#quest-queue-status').removeClass('floatable-quest-queue');
		}
	});

	$('#fast-signup-form .submit').on('click', function () {
		lock_screen(true);

		$.ajax({
			url:		'?acao=cadastro_rapido',
			data:		$('#fast-signup-form').serialize(),
			dataType:	'json',
			type:		'post',
			success:	function (result) {
				if(result.success) {
					location.href	= '?secao=personagem_criar';
				} else {
					lock_screen(false);
					var	errors	= [];
					
					result.messages.forEach(function (message) {
						errors.push('<li>' + message + '</li>');
					});
				
					jalert('<ul>' + errors.join('') + '</ul>');
				}
			}
		});
	});
});