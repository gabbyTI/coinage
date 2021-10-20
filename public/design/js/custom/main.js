// TODO: Code Refactoring
/**
 * i - Variable Declarations
 *
 * 1. - General Functions
 * 2. - Crypto Functions
 **/

/**================ i - Variable Declarations ================**/
let otp,
	price,
	_price,
	index_price,
	_index_price,
	_dismiss = '',

	mess_tag = '#mess .resp',
	mess_text = '#mess .mess',
	mess = $(mess_text).html(),

	percentage_inp = '#percentage',
	view_offer = '.view-offer',
	percentage = $(percentage_inp).val(),
	crypto_type = $('.crypto_type:checked').val(),

	wait_text = '#wait-text',
	wait_count = wait_text + ' #count',

	otp_wrapper = '#verify-otp-wrapper',
	phone_otp_btn = '#send-phone-otp-form button',
	resend_phone_otp_btn = '#send-phone-otp-form .resend',

	requested = false,
	urlLocation = window.location,
	href = urlLocation.href,
	href_array = href.split('/'),
	user_id = $('meta[name="user-id"]').attr('content'),
	token = $('meta[name="csrf-token"]').attr('content');


/**================ 1. - General Functions ================**/
/**
 * Function to check if the given element is hovered on
 * @returns {boolean | jQuery}
 */
jQuery.fn.mouseIsOver = function () {
	return $(this[0]).is(':hover');
};

/**
 * Function to return the action attribute of given form
 * @param {selector | string} _form
 * @returns {string | jQuery}
 */
function formAction(_form) {
	return $(_form).attr('action');
}

/**
 *Function to return all elements of the given form, and the forms' ID
 * @param {selector | string} form
 * @returns {{that: *, form: string}}
 */
function formAttr(form) {
	let ele = $(form)[0].elements,
		_form = '#' + $(form).attr('id');

	return {
		that: ele,
		form: _form,
	};
}

/**
 * Function to load Page Data Asynchronously
 * @param {selector | string} _wrapper: Element to load data into when loaded.
 * @param {string} _uri: URI for the given operation (i.e where to load the data from)
 * @param {string | object} data: Additional data to be passed with the URI (** optional [set to null if empty] **)
 * @param {string} _slug: Name of Page to be loaded
 * @param {selector | string} _modal
 * @param {function} callback: Callback function to be called on load completion (** optional [set to null if empty] **)
 */
function loadPageData(_wrapper, _uri, data = null, _slug, _modal = null, callback = null) {
	$(_wrapper).load(_uri, data, function (response, status, xhr) {
		if (status === 'success') {
			if (_modal !== null)
				$(_modal).modal({backdrop: true});

			if (typeof callback === 'function')
				callback();
		} else
			alert('There was an error loading ' + _slug + '\r\nPlease reload this page.');
	});
}

function removeInvalidFeedback(field_id) {
	$(field_id).removeClass('is-invalid');
	$(field_id + 'Valid').removeClass('invalid-feedback');
	$(field_id + 'Valid > strong').html('');
}

// Display Error message(s) and add the error class (** Requires the instance of the form field, the error array, the single error message (optional), and the addRegErr() & message() functions)
function displaySuccess(form, that, mess) {
	for (let i = 0; i < that.length; i++) {
		let element_id = that[i].id;
		console.log(element_id)
		if (element_id in mess && (mess[element_id] !== '')) {
			$(form + ' #' + element_id)
				.removeClass('is-invalid')
				.removeClass('is-valid')
				.addClass('is-valid');
			$(form + ' #' + element_id + 'Valid')
				.removeClass('invalid-feedback')
				.removeClass('valid-feedback')
				.addClass('valid-feedback');

			$(form + ' #' + element_id + 'Valid > strong').html(mess[element_id]);
		}
	}
}

// Display Error message(s) and add the error class (** Requires the instance of the form field, the error array, the single error message (optional), and the addRegErr() & message() functions)
function displayError(form, that, error, mess, mess_tag) {
	for (let i = 0; i < that.length; i++) {
		let element_id = that[i].id;
		if (element_id in error && (error[element_id] !== '')) {
			$(form + ' #' + element_id)
				.removeClass('is-valid')
				.removeClass('is-invalid')
				.addClass('is-invalid');
			$(form + ' #' + element_id + 'Valid')
				.removeClass('valid-feedback')
				.removeClass('invalid-feedback')
				.addClass('invalid-feedback');

			$(form + ' #' + element_id + 'Valid > strong').html(error[element_id]);
		}
	}
	/*if (mess) message(danger_rem_class, fa_exc, mess, mess_tag);*/
}

/**
 * Function to display messages
 * (** Requires html class attributes: bs4 (eg. .alert-danger), fa icon (eg. fa-exclamation), the message, target message element, the dismissible choice (optional), error element ID (optional) and instance of the form fields' error element (optional) and the error message **)
 * @param {string} class_opt
 * @param {string} fa_tag
 * @param {string} mess
 * @param {selector | string} mess_tag
 * @param {boolean} dismiss
 * @param {string} _id
 * @param {any | context} _context
 */
function showMessage(class_opt, fa_tag, mess, mess_tag, dismiss = false, _id = '', _context = null) {
	let _mess_tag = $(mess_tag);

	if (dismiss === true) {
		_dismiss = '<a type="button" class="text-danger" data-dismiss="alert"><i class="icon ni ni-stop-circle"></i></a>';
		class_opt += ' alert-dismissible';
	} else
		_dismiss = '';

	if (_context !== null)
		_mess_tag = $(mess_tag, _context);

	_mess_tag.html('\
        <div class="alert d-flex justify-content-between align-items-center ' + class_opt + '" data-id="' + _id + '">\n\
            <div class="mx-1">\
                <i class="icon ' + fa_tag + '"></i> \n\
                ' + mess + '\n\
            </div>\
            <div>' + _dismiss + '</div>\
        </div>\
    ');
}

/**================ 2. - Crypto Functions ================**/
/**
 * Get Current Crypto rate and assign values
 * @param {string} _crypto_type
 * @param {function | string} _percentage
 * @param {function} callback
 */
function selectCryptoOffer(_crypto_type, _percentage = percentage, callback = null) {
	let uri = 'https://api.coinbase.com/v2/exchange-rates?currency=' + _crypto_type;

	$.ajax({
		url: uri, method: 'GET', timeout: 8000, dataType: 'json', complete: function (xhr) {
			let /*_percentage,*/
				resp = xhr.responseJSON;

			if (xhr.status === 200)

				if (typeof _percentage === 'function') {
					price = parseFloat(resp.data.rates.NGN);
					_price = ((price * percentage) / 100) + price;
					/*_percentage = ((price * percentage) / 100);
					_price = _percentage + price;*/
				} else {
					index_price = parseFloat(resp.data.rates.NGN);
					_index_price = ((index_price * _percentage) / 100) + index_price;
				}

			if (typeof _percentage === 'function')
				_percentage();
			else if (typeof callback === 'function')
				callback();
		}, error: function () {
			/*alert('Please check your internet connection!');*/
		}
	});
}

/**
 * Get Current Crypto rate and assign values every 4000ms(4s)
 * @param target
 * @param _crypto_type
 * @param _percentage
 */
function currentCryptoOffer(target, _crypto_type, _percentage) {
	(function currentCryptoOffer() {
		setTimeout(() => {
			selectCryptoOffer(_crypto_type, _percentage, function () {
				$('.crypto-total', target).text(accounting.formatMoney(_index_price, '₦').toString());
				$('.crypto-current', target).text(accounting.formatMoney(index_price, '₦').toString());
			});
			currentCryptoOffer();
		}, 4000);
	})();
}

/**================ 3. - Phone Verification Functions ================**/
function cancelPhoneOTPSendCount(callback) {
	let left = parseInt(localStorage.getItem('phone-otp-send-wait-count-' + user_id));

	$(resend_phone_otp_btn).prop({disabled: true});
	// Count from x to y
	$({countNum: left}).animate({countNum: 0}, {
		duration: left * 1000,
		easing: 'linear',
		step: function () {
			// What todo on every count
			if (urlLocation.pathname === '/profile/verify/phone' || urlLocation.pathname === '/profile/verify/phone/')
				$('#otp-send-count').text(this.countNum.toFixed(0));
			localStorage.setItem('phone-otp-send-wait-count-' + user_id, this.countNum.toFixed(0));
		},
		complete: function () {
			$(resend_phone_otp_btn).prop({disabled: false});
			localStorage.removeItem('phone-otp-resend-count-' + user_id);
			localStorage.removeItem('phone-otp-send-wait-count-' + user_id);

			if (typeof callback === 'function')
				callback();
		}
	});
}

/**
 *
 * @param a
 * @param b
 * @param c
 * @param callback
 */
function resendWait(a, b, c, callback = null) {
	$(a).fadeIn(800);
	$(c)[1].disabled = true;

	if ($(resend_phone_otp_btn).css('display') === 'none')
		$(c).animate({width: 'toggle', fade: 'toggle'}, -1, 'linear');

	// Count from x to y
	$({countNum: 10}).animate({countNum: 0}, {
		duration: 10000,
		easing: 'linear',
		step: function () {
			// What todo on every count
			$(b).text(this.countNum.toFixed(0));
		},
		complete: function () {
			$(a).fadeOut(800, function () {
				$(c)[1].disabled = false;

				if (typeof callback === 'function')
					callback();
			});
		}
	});
}

function resendPhoneOTP(e, form) {
	let uri = $(e).data('uri'),
		resend_count = parseInt(localStorage.getItem('phone-otp-resend-count-' + user_id));

	$(e).on('click', function (e) {
		e.preventDefault();

		if (resend_count < 2) {
			$(otp_wrapper).slideUp(800);

			$.ajax({
				url: uri, method: 'POST', data: {_token: token}, dataType: 'json', complete: function (xhr) {
					let resp = xhr.responseJSON;
					console.log(xhr);
					if (xhr.status === 200 || xhr.status === 201) {
						resend_count += 1;
						/*otp = resp['OTP'];*/
						localStorage.setItem('phone-otp-resend-count-' + user_id, resend_count);

						displaySuccess(formAttr(form).form, formAttr(form).that, resp.message)

						if (resp.session['OTP'] !== '' || resp.session['OTP'] !== undefined) {
							$(otp_wrapper).slideDown(800, () => {
								resendWait(wait_text, wait_count, phone_otp_btn, function () {
									resendPhoneOTP(e, form);
								});
							});
						} else if ($(otp_wrapper).css('display') !== 'none')
							$(otp_wrapper).slideUp(800);
					} else
						displayError(formAttr(form).form, formAttr(form).that, resp.message);
				}
			});
		} else {
			let error = {phone: 'Number of resend exceeded! Please wait for <span id="otp-send-count">60</span>s'}
			localStorage.setItem('phone-otp-send-wait-count-' + user_id, '60');
			displayError(formAttr(form).form, formAttr(form).that, error);
			cancelPhoneOTPSendCount(() => {
				urlLocation.reload();
			});
		}
	});
}

if (localStorage.getItem('phone-otp-send-wait-count-' + user_id) !== null && localStorage.getItem('phone-otp-send-wait-count-' + user_id) !== undefined) {
	if (urlLocation.pathname === '/profile/verify/phone' || urlLocation.pathname === '/profile/verify/phone/') {
		let error = {phone: 'Number of resend exceeded! Please wait for <span id="otp-send-count">' + parseInt(localStorage.getItem('phone-otp-send-wait-count-' + user_id)) + '</span>s'}
		displayError(formAttr('#send-phone-otp-form').form, formAttr('#send-phone-otp-form').that, error);
		$(phone_otp_btn).prop({disabled: true})
	}

	cancelPhoneOTPSendCount(() => {
		if (urlLocation.pathname === '/profile/verify/phone' || urlLocation.pathname === '/profile/verify/phone/')
			urlLocation.reload();
	});
} else if (parseInt(localStorage.getItem('phone-otp-resend-count-' + user_id)) > 0) {
	if (urlLocation.pathname === '/profile/verify/phone' || urlLocation.pathname === '/profile/verify/phone/') {
		$(phone_otp_btn).animate({width: 'toggle', fade: 'toggle'}, -1, 'linear');
		resendPhoneOTP(resend_phone_otp_btn, '#send-phone-otp-form')
	}
} else {
	if (urlLocation.pathname === '/profile/verify/phone' || urlLocation.pathname === '/profile/verify/phone/')
		$(phone_otp_btn).prop({disabled: false});
}

if ($(view_offer).length)
	$(view_offer).each(function (idx, val) {
		let target = this,
			_percentage = $(val).data('percent'),
			_crypto_type = $('.crypto-currency', this).html();

		selectCryptoOffer(_crypto_type, _percentage, function () {
			$('.crypto-total', target).text(accounting.formatMoney(_index_price, '₦').toString());
			$('.crypto-current', target).text(accounting.formatMoney(index_price, '₦').toString());
		});
		currentCryptoOffer(target, _crypto_type, _percentage);
	});

// TODO: Changes made here****
$('.current-crypto-value').each(function (idx, val) {
	let target = this,
		_crypto_type = $('.amount > .currency-btc', target).html(),
		_wallet_bal = $('.amount > .final-balance', target).html();
	selectCryptoOffer(_crypto_type, function () {
		let _price_val = price * _wallet_bal;
		$('.amount-sm > .total', target).text(accounting.formatMoney(_price_val, '₦').toString());
	});
});

// Logout of the Application on Logout click
$('.logout').on('click', function (e) {
	e.preventDefault();
	const route = $(this).attr('href');
	$.post(route, {_token: token}, function (xhr, status) {
		if (status === 'success')
			urlLocation.href = '/login'
	});
});
