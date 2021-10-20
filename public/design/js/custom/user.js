// TODO: Code Refactoring
$(function () {
	/**
	 * 1. - Create Offer Functions
	 * 2. - Phone Verification Functions
	 * 3. - Bank Verification Functions
	 **/

	/**================ 1. - Create Offer Functions ================**/
	/**
	 * All Functions for Create Offer Operation
	 */
	function cryptoOffer() {
		function assignCryptoValue() {
			$('#crypto-total').text(accounting.formatMoney(_price, '₦').toString());
			$('#crypto-current').text(accounting.formatMoney(price, '₦').toString());
			$('#crypto-current1').html('<b>' + accounting.formatMoney(price, '₦') + '</b>');
		}

		/**
		 * Change the percentage value in the <span> element
		 * @param {any} val
		 * @param {function} callback
		 */
		function togglePercentage(val, callback) {
			if (val === '')
				val = 0;

			$('#crypto-percent').text(val);

			if (typeof callback === 'function')
				callback();
		}

		$(percentage_inp).on({
			input: function () {
				percentage = $(percentage_inp).val();

				togglePercentage(percentage, function () {
					selectCryptoOffer(crypto_type, function () {
						assignCryptoValue();
					});
				});
			},
			blur: function () {
				/*let max = $(percentage_inp).attr('max'),
					min = $(percentage_inp).attr('min');*/

				if ($(percentage_inp).val() === '')
					$(percentage_inp).val($(percentage_inp).attr('max'));

				percentage = $(percentage_inp).val();

				togglePercentage(percentage, function () {
					selectCryptoOffer(crypto_type, function () {
						assignCryptoValue();
					});
				});
			}
		});

		togglePercentage(percentage, function () {
			selectCryptoOffer(crypto_type, function () {
				assignCryptoValue();
			});
		});
	}

	/**
	 * Delete an existing offer
	 */
	function onDeleteOffer() {
		$('.delete-offer').on('click', function (e) {
			e.preventDefault();
			let uri = $(this).data('uri');

			if (confirm('Are you sure you want to remove this offer?'))
				$.ajax({
					url: uri, method: 'POST', data: {_method: 'delete', _token: token}, dataType: 'json', complete: function (xhr) {
						if (xhr.status === 200 || xhr.status === 201)
							if (xhr.responseJSON === 1) {
								alert('Offer Has been removed!');
								urlLocation.reload();
							} else
								alert('An error has occurred, please refresh this page and try again.\n Please contact us if this continues!');
						else
							alert('An error has occurred, please refresh this page and try again.\n Please contact us if this continues!');
					}
				});
			else
				alert('Offer deletion canceled!');
		});
	}

	$(view_offer).on('click', function (e) {
		e.preventDefault();
		let uri = $(this).data('uri');

		loadPageData('#user-modal-wrapper', uri, null, 'View Offer', '#show-offer-modal', () => {
			/*percentage_inp = '#percentage';*/
			percentage = $(percentage_inp).val();
			crypto_type = $('.crypto_type:checked').val();

			cryptoOffer();
			onDeleteOffer();
		});
	});

	$('.crypto_type').on('change', function () {
		crypto_type = $(this).val();
		cryptoOffer();
	});

	/**================ 2. - Phone Verification Functions ================**/
	/**
	 * Reset Phone-OTP resend count
	 */
	function setResendCount() {
		localStorage.removeItem('phone-otp-resend-count-' + user_id);
		localStorage.setItem('phone-otp-send-wait-count-' + user_id, '0');
	}

	/**
	 * Verify Phone-OTP
	 */
	function verifyPhoneOTP() {
		$('#verify-phone-otp-form').on('submit', function (e) {
			e.preventDefault();
			let target = this;

			$(target).ajaxSubmit({
				method: 'POST', url: formAction(target), data: {_token: token}, dataType: 'json', complete: function (xhr) {
					let resp = xhr.responseJSON;
					console.log(xhr);
					if (xhr.status === 200 || xhr.status === 201) {
						displaySuccess(formAttr(target).form, formAttr(target).that, resp.message)
						setTimeout(() => {
							urlLocation.href = '/profile/verify';
						}, 2000);
					} else
						displayError(formAttr(target).form, formAttr(target).that, resp.message);
				}
			});
		});
	}

	// Send Phone-OTP
	$('#send-phone-otp-form').on('submit', function (e) {
		e.preventDefault();
		let target = this;

		$(target).ajaxSubmit({
			method: 'POST', url: formAction(target), data: {_token: token}, dataType: 'json', complete: function (xhr) {
				let resp = xhr.responseJSON;
				console.log(xhr);
				if (xhr.status === 200 || xhr.status === 201) {
					otp = resp['OTP'];
					displaySuccess(formAttr(target).form, formAttr(target).that, resp.message)

					if (resp.session['OTP'] !== '' || resp.session['OTP'] !== undefined) {
						$(otp_wrapper).slideDown(800, () => {
							resendWait(wait_text, wait_count, phone_otp_btn, function () {
								setResendCount();
								resendPhoneOTP(resend_phone_otp_btn, target);
							});
							verifyPhoneOTP();
						});
					} else if ($(otp_wrapper).css('display') !== 'none')
						$(otp_wrapper).slideUp(800);
				} else
					displayError(formAttr(target).form, formAttr(target).that, resp.message);
			}
		});
	});

	/**================ 2. - Bank Verification Functions ================**/
	let _acc_no,
		acc_no_form = '#verify-acc-no',
		bank_form = '#verify-bank-form',
		acc_no = acc_no_form + ' #account_number',
		verify_account_wrapper = '#verify-account-wrapper';

	/**
	 * Function to complete Bank Verification Process
	 * @param form
	 * @param acc_name
	 * @param bank_name
	 * @param acc_no
	 * @param code
	 */
	function completeBankVerification(form, acc_name, bank_name, acc_no, code) {
		$(form).on('submit', function (e) {
			e.preventDefault();
			let target = this;

			$(target).ajaxSubmit({
				url: formAction(form), method: 'POST', data: {account_number: acc_no, account_name: acc_name, bank_name: bank_name, bank_code: code, _token: token}, dataType: 'json', beforeSend: function () {
					$(acc_no_form + ' button[type="submit"]').prop({disabled: true});
					$('button[type="submit"]', target).prop({disabled: true});
				}, complete: function (xhr) {
					let resp = xhr.responseJSON;

					if (xhr.status === 200 || xhr.status === 201) {
						alert('Verification Successful!');
						urlLocation.href = '/profile/verify';
					} else
						displayError(formAttr(target).form, formAttr(target).that, resp.errors)

					$(acc_no_form + ' button[type="submit"]').prop({disabled: false});
					$('button[type="submit"]', target).prop({disabled: false});
				}
			});
		});
	}

	// Verify that account number exists
	$(acc_no_form).on('submit', function (e) {
		e.preventDefault();
		let target = this;
		_acc_no = $(acc_no).val();

		$.ajax({
			url: 'https://app.nuban.com.ng/api/NUBAN-PKNLOWGQ540?acc_no=' + _acc_no, method: 'GET', timeout: 8000, dataType: 'json', beforeSend: function () {
				$('button[type="submit"]', target).prop({disabled: true});
				$('.throbber').fadeIn(800);
			}, complete: function (xhr) {
				let resp = xhr.responseJSON;
				if (xhr.status === 200)
					if (resp.error === true) {
						let error = {account_number: resp.message};
						displayError(formAttr(target).form, formAttr(target).that, error);
					} else {
						$(bank_form + ' #account_name').val(resp[0]['account_name']);
						$(bank_form + ' #bank_name').html('<option value="' + resp[0]['bank_name'] + '" selected>' + resp[0]['bank_name'] + '</option>');
						$(verify_account_wrapper).slideDown(800, () => {
							completeBankVerification(bank_form, resp[0]['account_name'], resp[0]['bank_name'], resp[0]['account_number'], resp[0]['bank_code']);
						});
					}
				$('button[type="submit"]', target).prop({disabled: false});
				$('.throbber').fadeOut(800);
			}, error: function () {
				alert('Please check your internet connection and try again!');
			}
		});
	});

	if (urlLocation.href.includes('offers/create'))
		cryptoOffer();
});
