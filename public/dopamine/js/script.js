let date = new Date(),
	toastCounter = 0,
	isFullscreen = false,
	themeMode = localStorage.getItem('theme'),
	tabsOpened = Number(localStorage.getItem('tabs'));

const SELECTOR = {
	contentHeight: window.innerHeight,
	contentWidth: window.innerWidth,
	content: '#content',
	loadFrameURL: '[data-toggle="load"]',
	loadHostFrameURL: '[data-toggle="load-host"],[data-toggle="tab"]',
	reloadFrameURL: '[data-toggle="reload"]',
	silentSubmitForm: 'form.silent',
	confirmAction: '[data-toggle="confirm"]',
	shortcutPostAction: '[data-toggle="shortcut-post-action"]',
	toggleSlide: '[data-toggle="slide"]',
	maximizeTool: '[data-toggle="maximize"]',
	minimizeTool: '.minimized,[data-toggle="minimize"]',
	dockTool: '[data-toggle="dock"]',
	dockContainer: '.dock',
	toastContainer: '.toasts',
	cardTagContainer: '.card-tags',
	customSelectOption: '.select'
};

const VALIDATOR = {
	required: [/.+/, 'Required'],
	decimal: [/^[0-9]*(?:|\.)[0-9]{0,2}$/, 'Numbers upto decimal place (e.g: 10.02)'],
	rDecimal: [/^[0-9]+(?:|\.)[0-9]{0,2}$/, 'Numbers upto decimal place (e.g: +xxx...), <code><i>Required</i></code>'],
	text: [/^[a-z ]*$/i, 'Alphabets only (e.g: Lorem ipsum)'],
	rText: [/^[a-z ]+$/i, 'Alphabets only (e.g: Lorem ipsum), <code><i>Required</i></code>'],
	email: [/^(?:[a-z0-9._]+@\w+\.\w+|)$/i, 'Email (e.g: name@domain.suff)'],
	rEmail: [/^[a-z0-9._]+@\w+\.\w+$/i, 'Email (e.g: name@domain.suff), <code><i>Required</i></code>'],
	phone: [/^(?:\+|)[0-9+]*$/, 'Numbers (e.g: +xxx...)'],
	rPhone: [/^(?:\+|)[0-9+]+$/, 'Numbers (e.g: +xxx...), <code><i>Required</i></code>']
};

$(document).ready(function() {

	__construct();

	$(document.documentElement).addClass(themeMode);
	$('.change-theme-mode').text((themeMode == 'dark' ? 'Light' : 'Dark') + ' theme');
	$('.page-sidebar').css({minHeight: SELECTOR.contentHeight + 'px'});
	$(window).resize(function() {
		$('.page-sidebar').css({minHeight: window.innerHeight + 'px'});
	});

	reloadFrameURL(SELECTOR.reloadFrameURL, function(c) {
		__construct(c);
	});
	loadHostFrameURL(SELECTOR.loadHostFrameURL, function(c) {
		__construct(c);
		if ($(c).is(SELECTOR.content)) {
			history.pushState(null, null, $(c).data('url'));
		}
	});

	silentSubmitForm(SELECTOR.silentSubmitForm);
	confirmAction(SELECTOR.confirmAction);
	shortcutPostAction(SELECTOR.shortcutPostAction);
	maximize(SELECTOR.maximizeTool);
	minimize(SELECTOR.minimizeTool);
	dock(SELECTOR.dockTool, SELECTOR.dockContainer);
	toggleSlide(SELECTOR.toggleSlide);
	contextMenu('.main-content');
	customSelectOption(SELECTOR.customSelectOption);

	// $('[title]').tooltip({ container: 'body' });

});

window.onpopstate = (function() {
	$(SELECTOR.content).loadInto(location.pathname + location.search, function(c) {
		__construct(c);
	});
});

function __construct(c) {
	
	plugin();

	if (c === undefined) {
		c = $(SELECTOR.content);
	} else {
		c = $(c);
	}
	if (SELECTOR.contentWidth < 992) {
		$(document.documentElement).addClass('closed');
	}
	c.find(SELECTOR.loadFrameURL).loadFrameURL();
	$(SELECTOR.toastContainer).html('');
	customSelectOption(c.find(SELECTOR.customSelectOption));
	c.find(SELECTOR.customSelectOption).each(function() {
		let selected = $(this).data('selected');
		selected = selected != '' ? $(this).find(`input[value="${selected}"]`) : undefined;
		if (selected !== undefined) {
			$(this).find('[data-toggle="dropdown"]').html($(selected).prop('checked', true).siblings('span').html().replace(/<\w+>.*<\/\w+>/, ''));
		}
	});
	c.find(SELECTOR.silentSubmitForm + '[data-target]').on('change', function() {
		$(this).submit();
	});
	c.find('[data-toggle="popover"]').popover({
		container: 'body',
		trigger: 'focus',
		html: true
	});
}

function plugin() {
	// plugin load
	// remove previous plugins
	$('script.plgn, link.plgn').remove();
	$('[data-plugin]').each(function() {
		var p = $(this).data('plugin');
		p = p.replace(' ', '').split(',');
		// each plugin
		for (var s = 0; s < p.length; s++) {
			// each module in plugin
			for (var m = 0; m < MODULE_CONFIG[p[s]].length; m++) {
				// js plugin
				if (MODULE_CONFIG[p[s]][m].match(/^.*\.js$/)) {
					$('body').append('<script src="' + MODULE_CONFIG[p[s]][m] + '" class="plgn"></script>');
				}
				// css plugin
				if (MODULE_CONFIG[p[s]][m].match(/^.*\.css$/)) {
					$('head').append('<link rel="stylesheet" href="' + MODULE_CONFIG[p[s]][m] + '" class="plgn">');
				}
			}
		}
		// remove plugin attr
		$(this).removeAttr('data-plugin');
	});
}

function statusToHTML(data, elem) {

	let audio, optR = {}, defaults = {
		type: '',
		title: '',
		icon: '',
		body: ''
	};
	
	try {

		data = JSON.parse(data);
		
		switch(true) {
			case data.hasOwnProperty('toast') :
				optR.toast = Object.assign(defaults, data.toast);
				makeToast(optR.toast.title, optR.toast.icon, optR.toast.body);
				audio = new Audio('/dopamine/sounds/toast.mp3');
				audio.play();
				break;

			case data.hasOwnProperty('alert') :
				optR.alert = Object.assign(defaults, data.alert);
				$(elem).html('<div class="alert alert-'+optR.alert.type+' d-flex flex-start border-dotted" role="alert">'
				+'<div class="alert-header">'+optR.alert.title+'</div>'
				+'<p>'+data.alert.body+'</p></div>');
				audio = new Audio('/dopamine/sounds/alert.mp3');
				audio.play();
				break;

			case data.hasOwnProperty('card-tag') :
				optR.cardTag = Object.assign(defaults, data.cardTag);
				$(elem).html('<div class="card-tag card-tag-'+optR.cardTag.type+'"">'
				+optR.cardTag.body+'</div>');
				audio = new Audio('/dopamine/sounds/alert.mp3');
				audio.play();
				break;
		}

		switch(true) {
			case data.hasOwnProperty('reset') :
				$(SELECTOR.silentSubmitForm).get(0).reset();
				$(SELECTOR.silentSubmitForm + ':first input:first').focus();
				break;

			case data.hasOwnProperty('reload') :
				$(data.reload).loadFrameURL();
				break;

			case data.hasOwnProperty('redir') :
				redir(data.redir);
				break;
		}

	} catch(exp) {}

}

$.expr[":"].contains = $.expr.createPseudo(function(arg) {
	return function( elem ) {
		return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
	};
});

jQuery.fn.extend({

	loadFrameURL: function(options) {
		$(this).each(function() {

			let url, init, callType, type = 'replace', container = this;

			url = $(container).data('url');
			url = url !== undefined ? encodeURI(url) : false;

			if (options === undefined) {
				callback = $(container).attr('callback');
			} else if (typeof options === 'function') {
				callback = options;
			} else if (typeof options === 'object') {
				callback = options.done;
				type = options.type;
			}

			init = (function() {
				function extendCallback(data) {
					if (typeof callback === 'string') {
						eval(callback);
					} else if(typeof callback === 'function') {
						callback(container, data);
					}
					statusToHTML(data, container);
				}

				return extendCallback;
			})(init);

			if (type == 'replace' && url) {
				$(container).append('<div class="loading-spinner-container position-absolute d-flex justify-content-center align-items-center h-100 w-100 rounded">'
					+'<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
				$(container).load(url, init);
			} else if(url) {
				$.get(url, function(data) {
					$(container).append(data);
					init(data);
				});
			}

		});
	},

	loadInto: function(url, callback) {
		$(this).data('url', url).loadFrameURL(callback);
	},

	toggleCheck: function(root) {
		if ($(root).prop('checked')) {
			$(this).prop('checked', true);
		} else {
			$(this).prop('checked', false);
		}
	},
	
	loadMoreTableRow: function(table) {
		let loader = this, page = 2, end = false, loaderTitle = $(loader).text(), rowCount = 0;
		$(loader).click(function() {

			url = $(table).data('url').replace(/(?:\?|&|)page=.*/, '');
			if (rowCount == 0) {
				rowCount = $(table + ' tr:first td').length;
			}

			if (end && !$(table + ' .load-more-table-row-end-of-result').length) {
				page = 1;
				end = false;
			}

			if (!end && !$(table + ' .load-more-table-row-end-of-result').length) {

				$(loader).html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
				$(loader).prop('disabled', true);
				$(table).data('url', url + '&page=' + page);
				$(table).loadFrameURL({
					type: 'append',
					done: function(obj, row) {
						$(loader).html(loaderTitle);
						$(loader).prop('disabled', false);
						if (row == '') {
							if(!end) {
								$(table).append('<tr class="load-more-table-row-end-of-result"><td class="text-center lead text-muted" colspan="' + rowCount + '">End of result</td></tr>');
								$(loader).hide();
							}
							end = true;
						}
						page++;
					}
				});
			
			}
		});
	}

});

function loadHostFrameURL(elem, callback) {
	$(document).on('click', elem, function() {
		event.preventDefault();
		$($(this).data('target')).data('url', $(this).attr('href') === undefined ? $(this).data('url') : $(this).attr('href'));
		$($(this).data('target')).loadFrameURL(callback);
	});
}

function reloadFrameURL(elem, callback) {
	$(document).on('click', elem, function() {
		$($(this).data('target')).loadFrameURL(callback);
	});
}

function redir(url) {
	$(SELECTOR.content).data('url', url).loadFrameURL();
}

function filterDataRow(obj) {
	let elem;
	elem = $(obj).attr('filter');
	$(elem).parent().hide();
	$(elem + ':contains(' + $(obj).val() + ')').parent().show();
}

function validateForm(form) {
	let validate, validated = true;
	$(form).find('.is-invalid').removeClass('is-invalid');
	$(form).find('[validate]').each(function() {
		validate = $(this).attr('validate');
		if ($(this).val().match(VALIDATOR[validate][0]) === null) {
			if (validated) {
				validated = false;
			}
			$(this).addClass('is-invalid').attr('data-toggle', 'popover')
			.attr('title', 'Invalid Format').attr('data-content', VALIDATOR[validate][1]).popover({
				container: 'body',
				trigger: 'focus',
				html: true
			});
		}
	});
	$(form).find('.is-invalid:first').focus();
	return validated;
}

function makeToast(title, icon, body, time) {
	time = time ? time : date.toLocaleTimeString();
	$(SELECTOR.toastContainer).append('<div class="toast" id="toast'+toastCounter+'" data-delay="5000" data-autohide="true">'
	+'<div class="toast-header">'
	+'<i class="fa fa-'+icon+' mr-2"></i> '
	+'<strong class="mr-auto">'+title+'</strong>'
	+'<small>'+time+'</small>'
	+'<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">'
	+'<span aria-hidden="true">&times;</span>'
	+'</button>'
	+'</div>'
	+'<div class="toast-body">'
	+body
	+'</div>'
	+'</div>');
	$('#toast'+toastCounter).toast('show');
	toastCounter++;
}

function makeModal(title, icon, body, confirm, deny) {
	$('body').append('<div class="modal fade" tabindex="-1" role="dialog">'
	+'<div class="modal-dialog modal-dialog-centered" role="document">'
	+'<div class="modal-content">'
	+'<div class="modal-header border-0">'
	+'<h6 class="modal-title"><i class="'+icon+'"></i><span class="ml-2">'+title+'</span></h6>'
	+'<button type="button" class="close" data-dismiss="modal">'
	+'<span>&times;</span>'
	+'</button>'
	+'</div>'
	+'<div class="modal-body lead">'
	+body
	+'</div>'
	+'<div class="modal-footer border-0">'
	+'<button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal" '+deny+'>Close</button>'
	+(confirm ? '<button type="button" class="btn btn-theme-dark btn-sm confirm" '+confirm+' onclick="$(\'.modal\').modal(\'hide\')">Yes, continue</button>' : '')
	+'</div>'
	+'</div>'
	+'</div>'
	+'</div>');
	$('.modal').modal('show');
	$('.modal').on('hidden.bs.modal', function() { $('.modal, .modal-backdrop').remove(); });
}

function confirmAction(elem) {
	let title, icon, body, url, id, callback;
	$(document).on('click', elem, function() {
		title = 'Confirm action';
		icon = 'fa fa-exclamation-circle fa-lg';
		body = $(this).data('body');
		url = $(this).data('url');
		id = $(this).data('id');
		callback = $(this).attr('callback');
		makeModal(title, icon, body, 'data-toggle="shortcut-post-action" data-url="'+url+'" data-data="id='+id+'" callback="'+callback+'"');
	});
}

function shortcutPostAction(elem) {
	let callback;
	$(document).on('click', elem, function() {
		callback = $(this).attr('callback');
		$.post($(this).data('url'), $(this).data('data'), function() {
			eval(callback);
		});
	});
}

function toggleSlide(elem) {
	$(document).on('click', elem, function() {
		$($(elem).data('target')).slideToggle('fast');
	});
}

function maximize(elem) {
	
	$(document).on('click', elem, function() {
	
		target = $(this).data('target');
	
		$(target).toggleClass('maximized');
	
	});
}

function minimize(elem) {
	$(document).on('click', elem, function() {
		
		target = $(this).data('target');
		
		if ($(this).is('.minimized')) {
			$(this).removeClass('minimized');
		}
		
		$(target).toggleClass('minimized');
	
	});
}

function dock(elem, dock) {
	let diffX, diffY, target, _target, x, y;
	$(document).on('click', elem, function() {
		
		target = $(this).data('target');
		
		if ($(target).parent().is('.dock')) {
			return false;
		}
		
		$(target).unwrap().css({
			top: '15px',
			left: '15px'
		});
		
		$(dock).append($(target));
		_target = document.querySelector(target);
		
		document.querySelector(target + ' .card-header').onmousedown = function() {
			
			diffX = event.x - Number(_target.style.left.replace('px', ''));
			diffY = event.y - Number(_target.style.top.replace('px', ''));
			
			$('.context-menu').hide();
			
			document.onmousemove = function(e) {

				x = e.x - diffX;
				y = e.y - diffY;

				if (x >= 15 && x <= (SELECTOR.contentWidth - 50)) {
					_target.style.left = x + 'px';
				}
				if (y >= 15 && y <= (SELECTOR.contentHeight - 50)) {
					_target.style.top = y + 'px';
				}
				
			}
			
			document.onmouseup = function() {
				document.onmousemove = null;
				document.onmouseup = null;
			}
		
		}
	});
}

function contextMenu(elem) {
	let menu, selection, coordsX, target;
	$(elem).contextmenu(function(e) {

		target = e.target;
		if ($(target).is('input')) {
			return;
		}

		e.preventDefault();
		selection = window.getSelection();
		menu = '';
		
		if ($(target).is('a')) {
			if ($(target).attr('href') !== undefined && $(target).attr('href').match(/\w+:.*/) === null) {
				menu += $('<div></div>').append($(target).clone().attr('class', 'context-item').html('Open link')).html();
				menu += $('<div></div>').append($(target).clone().attr('class', 'context-item').html('New tab<i class="text-muted float-right">Open link</i>').attr('target', '_blank')).html();
			}
		}
		if(selection.type == 'Range'){
			menu += (menu != '' ? '<hr>' : '')
			+ '<button class="context-item" onclick="document.execCommand(\'cut\')">Cut<i class="text-muted float-right">Ctrl+X</i></button>'
			+ '<button class="context-item" onclick="document.execCommand(\'copy\')">Copy<i class="text-muted float-right">Ctrl+C</i></button>'
		}
		if ($(target).parents('tr').length) {
			if ($(target).parents('tr').find('.dropdown-menu').length) {
				menu += (menu != '' ? '<hr>' : '')
				+ $('<div></div>').append($($(target).parents('tr').find('.dropdown-menu').html()).addClass('context-item')).html();
			}
		}
		if ($(target).parents('.card').length) {
			if ($(target).parents('.card').find('.card-header .dropdown-menu').length) {
				menu += (menu != '' ? '<hr>' : '')
				+ $('<div></div>').append($(target).parents('.card').find('.card-header .dropdown-menu .dropdown-item').clone().addClass('context-item'))
				.html();
			}
		}
		menu += (menu != '' ? '<hr>' : '')
		+ '<button class="context-item" data-toggle="reload" data-target="#content">Reload<i class="text-muted float-right">Ctrl+R</i></button>'
		+ '<button class="context-item" onclick="window.print()">Print<i class="text-muted float-right">Ctrl+P</i></button>';
		if (!$('.context-menu').length) {
			$('body').append('<div class="context-menu"></div>');
		}
		if (menu != '') {
			coordsX = e.clientX + 200 < window.innerWidth ? e.clientX : e.clientX - 200;
			$('.context-menu').html(menu).css({
				top: e.clientY + 'px',
				left: coordsX + 'px'
			}).show();

		}
		document.onmouseup = function() {
			$('.context-menu').hide();
			document.onmouseup = null;
		}
	});
}

function fullscreen(obj) {
	if (!isFullscreen) {
		let elem = document.documentElement;
		if (elem.requestFullscreen) {
			elem.requestFullscreen();
		} else if (elem.mozRequestFullScreen) { /* Firefox */
			elem.mozRequestFullScreen();
		} else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
			elem.webkitRequestFullscreen();
		} else if (elem.msRequestFullscreen) { /* IE/Edge */
			elem.msRequestFullscreen();
		}
		$(obj).html('<span class="btn btn-icon btn-xs mr-2"><i class="fa fa-compress fa-lg"></i></span><span>Exit Fullscreen</span>');
		isFullscreen = true;
	} else {
		if (document.exitFullscreen) {
		document.exitFullscreen();
		} else if (document.mozCancelFullScreen) { /* Firefox */
		document.mozCancelFullScreen();
		} else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
		document.webkitExitFullscreen();
		} else if (document.msExitFullscreen) { /* IE/Edge */
		document.msExitFullscreen();
		}
		$(obj).html('<span class="btn btn-icon btn-xs mr-2"><i class="fa fa-expand fa-lg"></i></span><span>Enter Fullscreen</span>');
		isFullscreen = false;
	}
}

function changeThemeMode(obj) {
	if (themeMode == 'dark') {
		$(document.documentElement).removeClass('dark');
		localStorage.setItem('theme', 'light');
		$('.change-theme-mode').text('Dark theme');
		themeMode = 'light';
	} else {
		$(document.documentElement).addClass('dark');
		localStorage.setItem('theme', 'dark');
		$('.change-theme-mode').text('Light theme');
		themeMode = 'dark';
	}
}

function previewInputImage(obj, elem) {
	if (obj.files[0]) {
		var reader = new FileReader();

		reader.onload = function(e) {
			if ($(elem).length) {
				$(elem).attr('src', e.target.result);
			} else {
				makeModal('Profile Image', 'image', '<img src="'+e.target.result+'" class="w-100">');
			}
		}

		reader.readAsDataURL(obj.files[0]);
	}
}

function customSelectOption(elem) {
	let data;
	$(elem).on('change', function() {
		data = $(this).find('input[type="radio"]:checked');
		if (data.length) {
			$(this).val(data.val());
			$(this).find('[data-toggle="dropdown"]').text(data.siblings('span').html().replace(/<\w+>.*<\/\w+>/, ''));
		}
	});
}