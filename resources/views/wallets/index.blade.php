@extends('layouts.dashboard')
@section('content')

<div class="nk-block-head">
	<div class="nk-block-head-sub"><span>Account Wallet</span></div><!-- .nk-block-head-sub -->
	<div class="nk-block-between-md g-4">
		<div class="nk-block-head-content">
			<h2 class="nk-block-title fw-normal">Wallet / Assets</h2>
			<div class="nk-block-des">
				<p>Here is the list of your assets / wallets!</p>
			</div>
		</div>
		<div class="nk-block-head-content">
			<ul class="nk-block-tools gx-3">
				<li class="opt-menu-md dropdown">
					<a href="#" class="btn btn-dim btn-outline-light btn-icon" data-toggle="dropdown"><em
							class="icon ni ni-setting"></em></a>
					<div class="dropdown-menu  dropdown-menu-xs dropdown-menu-right">
						<ul class="link-list-plain sm">
							<li><a href="#">Display</a></li>
							<li><a href="#">Show</a></li>
						</ul>
					</div>
				</li>
				<li><a href="#" class="btn btn-primary"><span>Send</span> <em
							class="icon ni ni-arrow-long-right"></em></a></li>
				<li><a href="#" class="btn btn-dim btn-outline-light"><span>Withdraw</span> <em
							class="icon ni ni-arrow-long-right"></em></a></li>
			</ul>
		</div>
	</div><!-- .nk-block-between -->
</div><!-- .nk-block-head -->

<div class="nk-block nk-block-lg">
	<div class="nk-block-head-sm">
		<div class="nk-block-head-content">
			<h5 class="nk-block-title title">Fiat Accounts</h5>
		</div>
	</div>
	@if (session('messageFiat'))
	<div class="center mb-3">
		<div class="alert col-md-4 center alert-{{session('isSuccess') ? 'success' : 'danger'}} alert-dismissible">
			<a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
			<strong><i class="bi bi-thumbs-up"></i></strong> {{session('messageFiat')}}
		</div>
	</div>
	@endif
	<div class="row g-gs">
		<div class="col-md-6 col-lg-4 view-fiat-wallet">
			<input type="text" class="fiat-wallet-id" value="{{$fiatWallet->id}}" hidden>
			<div class="card card-bordered">
				<div class="nk-wgw">
					<div class="nk-wgw-inner">
						<a class="nk-wgw-name" href="html/crypto/wallet-bitcoin.html">
							{{-- <div class="nk-wgw-icon is-default">
								<em class="icon ni ni-sign-usd"></em>
							</div> --}}
							<div class="nk-wgw-icon">
								???
								{{-- <em class="icon ni ni-sign-usd"></em> --}}
							</div>
							<h5 class="nk-wgw-title title">Naira Account</h5>
						</a>
						<div class="nk-wgw-balance">
							<div class="amount" id="fiat-amount">
								<span class="final-balance">
									{{-- <img src="{{asset('design/img/ajax-loader.gif')}}" class="loaderImageFiat"
									alt="loading"> --}}
									{{$fiatWallet->balance/100}}
								</span>
								<span class="currency currency-ngn">NGN</span>
							</div>
						</div>
					</div>
					<div class="nk-wgw-actions">
						<ul>
							{{-- <li><a href="#"><em class="icon ni ni-arrow-up-right"></em> <span>Send</span></a></li> --}}
							<li><a href="#" type="button" data-toggle="modal" data-target="#showAccountModal"><em
										class="icon ni ni-arrow-to-down"></em><span>Deposit</span></a></li>
							<li><a href="#"><em class="icon ni ni-arrow-to-right"></em><span>Withdraw</span></a></li>
						</ul>
					</div>
					<div class="nk-wgw-more dropdown">
						<a href="#" class="btn btn-icon btn-trigger" data-toggle="dropdown"><em
								class="icon ni ni-more-h"></em></a>
						<div class="dropdown-menu dropdown-menu-xs dropdown-menu-right">
							<ul class="link-list-plain sm">
								<li><a href="#">Details</a></li>
								<li><a href="#">Edit</a></li>
								<li><a href="#">Delete</a></li>
								<li><a href="#">Make Default</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div><!-- .card -->
		</div><!-- .col -->

		{{-- <div class="col-md-6 col-lg-4">
			<div class="card card-bordered dashed h-100">
				<div class="nk-wgw-add">
					<div class="nk-wgw-inner">
						<a href="#" type="button" data-toggle="modal"
						data-target="#showCreateFiatWalletModal">
							<div class="add-icon">
								<em class="icon ni ni-plus"></em>
							</div>
							<h6 class="title">Add New Wallet</h6>
						</a>
						<span class="sub-text">You can add your more wallet in your account to manage separetly.</span>
					</div>
				</div>
			</div><!-- .card -->
		</div><!-- .col --> --}}
	</div><!-- .row -->
</div><!-- .nk-block -->

<div class="nk-block">
	<div class="nk-block-head-sm">
		<div class="nk-block-head-content">
			<h5 class="nk-block-title title">Crypto Accounts</h5>
		</div>
	</div>
	@if (session('message'))
	<div class="center mb-3">
		<div class="alert col-md-4 center alert-danger alert-dismissible">
			<a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
			<strong><i class="bi bi-thumbs-up"></i></strong> {{session('message')}}
		</div>
	</div>
	@endif
	<div class="row g-gs">

		@foreach ($wallets as $wallet )
		<div class="col-md-6 col-lg-4 view-wallet">
			<input type="text" class="wallet-id" value="{{$wallet->id}}" hidden>
			<input type="text" class="address-id" value="{{$wallet->address->id}}" hidden>
			<div class="card card-bordered">
				<div class="nk-wgw">
					<div class="nk-wgw-inner">
						<a class="nk-wgw-name" href="{{route('wallets.show',$wallet->id)}}">
							<div class="nk-wgw-icon">
								<em
									class="icon ni ni-sign-{{$wallet->crypto_type == 'usdteth'?'usdt' : $wallet->crypto_type}}"></em>
							</div>
							@switch($wallet->crypto_type)
							@case('btc')
							<h5 class="nk-wgw-title title">Bitcoin Wallet</h5>
							@break
							@case('eth')
							<h5 class="nk-wgw-title title">Ethereum Wallet</h5>
							@break
							@case('usdteth')
							<h5 class="nk-wgw-title title">USD Tether Wallet</h5>
							@break
							@default
							@endswitch
						</a>
						<div class="nk-wgw-balance current-crypto-value">
							<div class="amount">
								<span class="final-balance"><img src="{{asset('design/img/ajax-loader.gif')}}"
										class="loaderImage" alt="loading"></span>

								<span
									class="currency currency-btc">{{ $wallet->crypto_type == 'usdteth'?'USDT' : strtoupper($wallet->crypto_type) }}</span>
							</div>
							<div class="amount-sm"><span class="total"></span> <span
									class="currency currency-usd">NGN</span></div>
						</div>
					</div>
					<div class="nk-wgw-actions">
						<ul>
							<li><a href="#"><em class="icon ni ni-arrow-up-right"></em> <span>Send</span></a></li>
							<li><a href="#" type="button" data-toggle="modal"
									data-target="#showAddressModal-{{$wallet->id}}"><em
										class="icon ni ni-arrow-down-left"></em><span>Receive</span></a></li>
							<li><a href="#"><em class="icon ni ni-arrow-to-right"></em><span>Withdraw</span></a></li>
						</ul>
					</div>
					<div class="nk-wgw-more dropdown">
						<a href="#" class="btn btn-icon btn-trigger" data-toggle="dropdown"><em
								class="icon ni ni-more-h"></em></a>
						<div class="dropdown-menu dropdown-menu-xs dropdown-menu-right">
							<ul class="link-list-plain sm">
								<li><a href="{{route('wallets.show',$wallet->id)}}">Details</a></li>
								{{-- <li><a href="#">Edit</a></li>
												<li><a href="#">Delete</a></li>
												<li><a href="#">Make Default</a></li> --}}
							</ul>
						</div>
					</div>
				</div>
			</div><!-- .card -->
		</div><!-- .col -->

		<!--Show Wallet Address Modal -->
		<div class="modal fade" tabindex="-1" id="showAddressModal-{{$wallet->id}}">
			<div class="modal-dialog modal-dialog-top" role="document">
				<div class="modal-content">
					<a href="#" class="close" data-dismiss="modal" aria-label="Close">
						<em class="icon ni ni-cross"></em>
					</a>
					<div class="modal-header">
						<h5 class="modal-title">Modal Title</h5>
					</div>
					<div class=" m-3 alert alert-warning alert-dismissible">
						@switch($wallet->crypto_type)
						@case('btc')
						Remember to send only Bitcoin (BTC) to this address.
						Don't send Tether (USDT) or Bitcoin Cash (BCH) to this address as you may not be able to
						retrieve these funds.
						@break
						@case('eth')
						Make sure you receive only ETH or ERC20-USDT to this wallet address.
						If you receive any other ERC20 token, you may not be able to retrieve these funds.
						@break
						@case('usdteth')
						Make sure you???re sending only ERC20-USDT tokens to this wallet address. If you send any other
						USDT token,
						you may not be able to retrieve these funds.
						@break

						@default

						@endswitch

					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="form-label" for="default-01">Your wallet address</label>
							<div class="form-control-wrap">
								<input type="text" class="form-control bg-white" id="default-01"
									value="{{$wallet->address->address}}" disabled>
							</div>
						</div>
					</div>
					<div class="modal-footer bg-light">
						<span class="sub-text">Modal Footer Text</span>
					</div>
				</div>
			</div>
		</div>


		@endforeach

		@if (count($wallets) < 3) <div class="col-sm-6 col-lg-4 ">
			<div class="card card-bordered dashed h-100">
				<div class="nk-wgw-add">
					<div class="nk-wgw-inner">
						<a href="#" type="button" data-toggle="modal" data-target="#showCreateWalletModal">
							<div class="add-icon">
								<em class="icon ni ni-plus"></em>
							</div>
							<h6 class="title">Add New Wallet</h6>
						</a>
						<span class="sub-text">You can add your more wallet in your account to manage separetly.</span>
					</div>
				</div>
			</div><!-- .card -->
	</div><!-- .col -->
	@endif

</div><!-- .row -->
</div>

<!--Create Wallet Modal -->
<div class="modal fade" tabindex="-1" id="showCreateWalletModal">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<a href="#" class="close" data-dismiss="modal" aria-label="Close">
				<em class="icon ni ni-cross"></em>
			</a>
			<div class="modal-header">
				<h5 class="modal-title">Create Wallet</h5>
			</div>

			<div class="modal-body">
				<form action="{{route('wallets.store')}}" method="POST" class="gy-3" name="formA">
					@csrf
					<div class="form-group">
						<label class="form-label">Select Crypto Type</label>
						<div class="form-control-wrap">
							<select name="crypto_type" class="form-select form-control form-control-lg">
								<option value="btc">Bitcoin</option>
								<option value="eth">Ethereum</option>
								<option value="usdteth">Tether</option>
							</select>
						</div>
					</div>

					<div class="row g-3">
						<div class="col-lg-7 offset-lg-5">
							<div class="form-group">
								<button type="submit" class="btn btn-lg btn-primary">Create</button>
							</div>
						</div>
					</div>
				</form>
			</div>

		</div>
	</div>
</div>

{{-- response message modal --}}
<div class="modal fade" tabindex="-1" id="myMessage">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<a href="#" class="close" data-dismiss="modal" aria-label="Close">
				<em class="icon ni ni-cross"></em>
			</a>
			<div class="modal-header text-center">
				<h5 class="modal-title w-100">Response Message</h5>
			</div>

			<div class="modal-body text-center">
				<strong id="response-message"></strong>
			</div>

		</div>
	</div>
</div>


{{-- deposit modal --}}
<div class="modal fade" tabindex="-1" id="depositModal">
	<div class="modal-dialog modal-dialog-top" role="document">
		<div class="modal-content">
			<a href="#" class="close" data-dismiss="modal" aria-label="Close">
				<em class="icon ni ni-cross"></em>
			</a>
			<div class="modal-header text-center">
				<h5 class="modal-title w-100">Make a deposit</h5>
			</div>

			<div class="modal-body">
				<form id="paymentForm">
					<input type="email" id="email-address" value="{{auth()->user()->email}}"
						required hidden />
					<input type="text" id="first-name" value="{{auth()->user()->other_names}}"
						hidden />
					<input type="text" id="last-name" value="{{auth()->user()->surname}}"
						hidden />
						<div class="form-group">
						<label for="transaction-fee">Processing Fee</label>
						<input type="tel" class="form-control fs-15px mb-1" id="transaction-fee" disabled required />
					</div>
					<div class="form-group">
						<label for="amount">Deposit Amount</label>
						<input type="tel" class="form-control fs-15px mb-1" id="amount" disabled required />
					</div>
					<div class="form-submit text-center">
						<button type="submit" class="btn btn-primary"
							onclick="proceedToPay()"> Pay </button>
						{{-- <button type="submit" class="btn btn-primary"
							onclick="payWithPaystack()"> Pay </button> --}}
					</div>
				</form>
			</div>

		</div>
	</div>
</div>

<!--Show deposit Wallet account details Modal -->
<div class="modal fade" tabindex="-1" id="showAccountModal">
	<div class="modal-dialog modal-dialog-top" role="document">
		<div class="modal-content">
			<a href="#" class="close" data-dismiss="modal" aria-label="Close">
				<em class="icon ni ni-cross"></em>
			</a>
			<div class="modal-header">
				<h5 class="modal-title">Deposit</h5>
			</div>

			<div class="modal-body">
				<div id="accordion" class="accordion">
					<div class="accordion-item">
						<a href="#" class="accordion-head" data-toggle="collapse"
							data-target="#accordion-item-1">
							<h6 class="fs-20px">Deposit with bank transfer</h6>
							<span class="accordion-icon"></span>
						</a>
						<div class="accordion-body collapse" id="accordion-item-1" data-parent="#accordion">
							<div class="accordion-inner">
								<div class=" alert alert-info alert-dismissible">
									Kindly transfer funds to your dedicated bank account and your wallet will be
									credited. Find the account details below ,
								</div>
								Account Name
								<input type="text" class="form-control fs-18px bg-white mb-1"
									value="{{$fiatWallet->accountName}}" disabled>
								Account Number
								<input type="text" class="form-control fs-18px bg-white mb-1"
									value="{{$fiatWallet->accountNumber}}" disabled>
								Bank Name
								<input type="text" class="form-control fs-18px bg-white mb-1"
									value="{{$fiatWallet->bank}}" disabled>
							</div>
						</div>
					</div>
					<div class="accordion-item">
						<a href="#" class="accordion-head collapsed" data-toggle="collapse"
							data-target="#accordion-item-2">
							<h6 class="fs-20px">Deposit with card</h6>
							<span class="accordion-icon"></span>
						</a>
						<div class="accordion-body collapse" id="accordion-item-2" data-parent="#accordion">
							<div class="accordion-inner">
								{{-- <div class=" alert alert-info alert-dismissible">
							This feature isn't available yet,
						</div> --}}

								<form id="getDepositAmountForm">
									<div class="form-group">
										<input type="number" class="form-control fs-15px bg-white mb-1" id="deposit-amount"
											placeholder="Enter Deposit Amount" required />
									</div>
									<div class="form-submit text-center">
										<button type="submit" class="btn btn-primary"
											onclick="proceedToPay()"> Proceed </button>
										{{-- <button type="submit" class="btn btn-primary"
											onclick="payWithPaystack()"> Pay </button> --}}
									</div>
								</form>
							</div>
						</div>
					</div>

				</div>
			</div>

		</div>
	</div>
</div>
<script src="{{ asset('design/js/custom/user.js') }}"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
	const viewWallets = '.view-wallet';

	$(viewWallets).each(function (index, val) {
		let target = this,
			walletId = $('.wallet-id', target),
			addressId = $('.address-id', target),
			uri = '{{ config('
		app.url ') }}/wallets/getWalletBalance/' + walletId.val() + '/' + addressId.val();
		// var wallet_balance = $('#wallet-balance-' + index).val();

		// console.log(uri);
		$('.loaderImage', target).show();
		$.ajax({
			url: uri,
			method: 'GET',
			timeout: 8000,
			dataType: 'json',
			complete: function (xhr) {
				let resp = xhr.responseJSON;
				if (xhr.status === 200)
					$('.final-balance', target).text(resp.balance);
				$('.loaderImage').hide();
			},
			error: function () {
				alert('Please check your network connection.');
				// $('.loaderImage').hide();
			}
		});

	});

</script>
<script>
	var depositAmountForm = document.getElementById('getDepositAmountForm');
	var paymentForm = document.getElementById('paymentForm');

	depositAmountForm.addEventListener('submit', proceedToPay, false);
	paymentForm.addEventListener('submit', payWithPaystack, false);

	function payWithPaystack(event) {
		event.preventDefault();
		var transaction_reference = 'D' + Math.floor(100000000000000 + Math.random() * 900000000000000);
		var d_amount = calculateAmountAndFee($('#deposit-amount').val());
		var base_url = {!! json_encode(URL::to('/')) !!};
		$('#depositModal').modal('hide');
		var handler = PaystackPop.setup({
			key: 'pk_test_305cb5b5787b3adfe45ca9d51b643344a2174c9e', // Replace with your public key
			email: document.getElementById('email-address').value,
			// amount: (document.getElementById('amount').value * 100) + (document.getElementById('transaction-fee').value * 100), // the amount value is multiplied by 100 to convert to the lowest currency unit
			amount: d_amount * 100, // the amount value is multiplied by 100 to convert to the lowest currency unit
			channels: ['card'],
			ref: transaction_reference,
			metadata: {
				'user_id': '{{Auth::user()->id}}',
				'requested_amount': $('#deposit-amount').val() * 100
			},
			currency: 'NGN', // Use GHS for Ghana Cedis or USD for US Dollars
			callback: function (response) {
				$.ajax({
					url: base_url + '/paystack/verify_transaction?reference=' + response
						.reference,
					method: 'get',
					complete: function (response) {
						// location.reload();
						$('#response-message').text(response.responseJSON.message);
						$( "#fiat-amount" ).load(window.location.href + " #fiat-amount" );
						$('#myMessage').modal('show');
						console.log(response.responseJSON.message);
					}
				});
			},
			onClose: function () {
				alert('Transaction was not completed, window closed. '+ transaction_reference);
				$.ajax({
					url: base_url + '/paystack/verify_transaction?reference=' + transaction_reference,
					method: 'get',
					complete: function (response) {
						$('#response-message').text(response.responseJSON.message);
						$('#myMessage').modal('show');
						console.log(response.responseJSON.message);
						// location.reload();
					}
				});
			},
		});
		handler.openIframe();
	}

	function proceedToPay(event){
		event.preventDefault();
		var amount = $('#deposit-amount').val();
		// calculate total fee
		var totalFee = calculateFee(amount);
		console.log('total fee: '+ totalFee);
		// close showAccountModal
		$('#showAccountModal').modal('hide');
		//update form fields(fee, amount) in depositModal
		$('#transaction-fee').val(totalFee);
		$('#amount').val(amount);
		// show deposit modal
		$('#depositModal').modal('show');

	}

	function calculateAmountAndFee(amount){
		let price = parseInt(amount);
		let final_amt;
		var decimal_fee = (0.3 * 0.015) + 0.015;
		console.log('decimal_fee = '+ (1-decimal_fee));
		let paystackAppfee;
		if (price < 2500){
			// if the amount is les than 2500 then the appfee can never be greater than 2000, so just return the final amount
			final_amt = Math.round(( price / (1 - decimal_fee) ) + 0.01);
			console.log('amt when price less than 2500 = ' + final_amt);
			return final_amt;
		}else{
			// when the price is greater than 2500, then there will be a flat fee of N100 added to every fee amount
			paystackAppfee = Math.round(0.015 * $('#deposit-amount').val()) + 100;
			console.log('paystack app fee = ' + paystackAppfee);
			// now check if the paystack fee is greater than the fee cap of 2000
			if(paystackAppfee > 2000){
				// if it is greater than 2000 the return the amount
				final_amt = price + (2000 + (0.3 * 2000));
				console.log('final amount when paystack fee exceeds fee cap = ' + final_amt );
				return final_amt;
			}else{
				final_amt = Math.round(((price + 100) / (1 - decimal_fee)) + 0.01);
				console.log('final amount when paystack fee is lower than fee cap = ' + final_amt );
				return final_amt;
			}
		}
	}

	function calculateFee(amount){
		let price = parseInt(amount);
		let final_amt;
		var decimal_fee = (0.3 * 0.015) + 0.015;
		console.log('decimal_fee = '+ (1-decimal_fee));
		let paystackAppfee;
		if (price < 2500){
			// if the amount is les than 2500 then the appfee can never be greater than 2000, so just return the final amount
			final_amt = Math.floor(( price / (1 - decimal_fee) ) + 0.01);
			console.log('amt when price less than 2500 = ' + final_amt);
			return final_amt - price;
		}else{
			// when the price is greater than 2500, then there will be a flat fee of N100 added to every fee amount
			paystackAppfee = Math.round(0.015 * $('#deposit-amount').val()) + 100;
			console.log('paystack app fee = ' + paystackAppfee);
			// now check if the paystack fee is greater than the fee cap of 2000
			if(paystackAppfee > 2000){
				// if it is greater than 2000 the return the amount
				final_amt = price + (2000 + (0.3 * 2000));
				console.log('final amount when paystack fee exceeds fee cap = ' + final_amt );
				return final_amt - price;
			}else{
				final_amt = Math.round(((price + 100) / (1 - decimal_fee)) + 0.01);
				console.log('final amount when paystack fee is lower than fee cap = ' + final_amt );
				return final_amt - price;
			}
		}
	}

</script>

{{-- <script>
	const viewFiatWallets = '.view-fiat-wallet';

	$(viewFiatWallets).each(function (index, val) {
		let target = this,
			fiatWalletId = $('.fiat-wallet-id', target),
			uri = '{{ config('app.url') }}/wallets/getFiatWalletBalance/' + fiatWalletId.val();

// console.log(uri);
$('.loaderImageFiat', target).show();
$.ajax({
url: uri,
method: 'GET',
timeout: 8000,
dataType: 'json',
complete: function (xhr) {
let resp = xhr.responseJSON;
// console.log(resp);
if (xhr.status === 200)
$('.final-balance', target).text(resp.balance);
$('.loaderImage').hide();
},
error: function () {
alert('Please check your network connection.');
// $('.loaderImage').hide();
}
});

});

</script> --}}


@endsection
