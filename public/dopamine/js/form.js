function silentSubmitForm(elem, opt) {
	let optR, defaults, form, formData, container, validated, submitted = false;
	
	$(document).on('submit', elem, function(e) {
		e.preventDefault();
		form = this;
		validated = validateForm(form);

		if (validated && !submitted) {

			submitted = true;

			$(form).find('button, input, select').prop('readonly', true);

			defaults = {
				type: $(form).attr('method') === undefined ? 'GET' : $(form).attr('method').toUpperCase(),
				process: $(form).attr('enctype') === 'multipart/form-data' ? false : true,
				url: $(form).attr('action') === undefined ? location.pathname : $(form).attr('action'),
				done: $(form).attr('callback')
			}

			optR = Object.assign(defaults, opt);

			if (optR.type == 'GET') {

				container = $(form).data('target') !== undefined ? $(form).data('target') : SELECTOR.content;
				
				formData = $(form).serialize();

				$(container).data('url', optR.url + '?' + formData);

				history.pushState(null, null, optR.url + '?' + formData.replace(/(?:\?|&|)page=.*/, ''));

				$(container).loadFrameURL(function() {
					$(form).find('button, input, select').prop('readonly', false);
					$('.load-more').show();
					submitted = false;
				});

			} else if (optR.process) {
				
				formData = $(form).serialize();
				
				$.ajax({
					type: optR.type,
					url: optR.url + '&ajax',
					data: formData,
					success: function(data) {
						statusToHTML(data, SELECTOR.cardTagContainer);
						if (optR.done !== undefined) {
							typeof optR.done === 'string' ? eval(optR.done) : optR.done(data);
						}
						$(form).find('button, input, select').prop('readonly', false);

						submitted = false;

					}
				});

			} else {
				
				formData = new FormData(form);

				$.ajax({
					type: optR.type,
					url: optR.url + '&ajax',
					contentType: false,
					processData: false,
					data: formData,
					success: function(data) {

						statusToHTML(data, SELECTOR.cardTagContainer);
						if (optR.done !== undefined) {
							typeof optR.done === 'string' ? eval(optR.done) : optR.done(data);
						}
						$(form).find('button, input, select').prop('readonly', false);

						submitted = false;

					}
				});
			
			}
		}
	});
}