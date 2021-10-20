@extends('layouts.dashboard')

@section('content')
<div class="nk-block-head">
	<div class="nk-block-between-md">
		<div class="nk-block-head-content">
			<div class="nk-block-head-sub"><a class="back-to" href="{{route('wallets.index')}}"><em
						class="icon ni ni-arrow-left"></em><span>My Wallets</span></a></div>
			<div class="nk-wgwh">
				<em class="icon-circle icon-circle-lg icon ni ni-sign-btc"></em>
				<div class="nk-wgwh-title h5">
					@switch($wallet->crypto_type)
						@case('btc')
							Bitcoin Wallet
							@break
						@case('eth')
							Ethereum Wallet
							@break
						@case('usdteth')
							USD Tether Wallet
							@break

						@default

					@endswitch
					<small>/</small>
					<div class="dropdown">
						<a class="dropdown-indicator-caret" data-offset="0,4" href="#"
							data-toggle="dropdown"><small>USD</small></a>
						<div class="dropdown-menu dropdown-menu-xxs dropdown-menu-center">
							<ul class="link-list-plain text-center">
								<li><a href="#">BTC</a></li>
								<li><a href="#">ETH</a></li>
								<li><a href="#">YEN</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- .nk-block-head -->
<div class="nk-block">
	<div class="nk-block-between-md g-4">
		<div class="nk-block-content">
			<div class="nk-wg1">
				<div class="nk-wg1-group g-2">
					<div class="nk-wg1-item mr-xl-4">
						<div class="nk-wg1-title text-soft">Available Balance</div>
						<div class="nk-wg1-amount current-crypto-value">
							<div class="amount"><span class="final-balance">{{ $wallet->getWallet('final_balance') }}</span> <small class="currency currency-usd">{{$wallet->crypto_type == 'usdteth'?'USDT' : strtoupper($wallet->crypto_type)}}</small></div>
							<div class="amount-sm">Balance in <span class="total">2.010550</span> <span class="currency currency-ngn">NGN</span></div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- .nk-block-content -->
		<div class="nk-block-content">
			<ul class="nk-block-tools gx-3">
				<li class="btn-wrap"><a href="#" class="btn btn-icon btn-xl btn-dim btn-outline-light"><em
							class="icon ni ni-arrow-up-right"></em></a><span class="btn-extext">Send</span></li>
				<li class="btn-wrap"><a href="#" class="btn btn-icon btn-xl btn-dim btn-outline-light" type="button" data-toggle="modal" data-target="#modalDefault"><em
							class="icon ni ni-arrow-down-left"></em></a><span class="btn-extext">Receive</span></li>
			</ul>
		</div><!-- .nk-block-content -->
	</div><!-- .nk-block-between -->
</div><!-- .nk-block -->
<div class="nk-block nk-block-lg">
	<div class="row g-gs">
		<div class="col-md-6">
			<div class="card card-bordered">
				<div class="card-inner">
					<div class="nk-wg5">
						<div class="nk-wg5-title">
							<h6 class="title overline-title">Total Sent</h6>
						</div>
						<div class="nk-wg5-text">
							<div class="nk-wg5-amount current-crypto-value">
								<div class="amount"><span class="final-balance">{{$wallet->getWallet('total_sent')}}</span> <span class="currency currency-btc">{{$wallet->crypto_type == 'usdteth'?'USDT' : strtoupper($wallet->crypto_type)}}</span>
								</div>
								<div class="amount-sm"><span class="total"></span> <span class="currency currency-usd">NGN</span>
								</div>
							</div>
						</div>
						<div class="nk-wg5-foot">
							<span class="text-soft">Last Send at <span class="text-base">19 Nov, 2019</span></span>
						</div>
					</div><!-- .nk-wg5 -->
				</div>
			</div><!-- .card -->
		</div><!-- .col -->
		<div class="col-md-6">
			<div class="card card-bordered">
				<div class="card-inner">
					<div class="nk-wg5">
						<div class="nk-wg5-title">
							<h6 class="title overline-title">Total Receive</h6>
						</div>
						<div class="nk-wg5-text">
							<div class="nk-wg5-amount current-crypto-value">
								<div class="amount"> {{$wallet->getWallet('total_received')}} <span class="currency currency-btc">{{$wallet->crypto_type == 'usdteth'?'USDT' : strtoupper($wallet->crypto_type)}}</span>
								</div>
								<div class="amount-sm"><span class="total"></span> <span class="currency currency-usd">NGN</span>
								</div>
							</div>
						</div>
						<div class="nk-wg5-foot">
							<span class="text-soft">Last Receive at <span class="text-base">24 Nov, 2019</span></span>
						</div>
					</div>
				</div>
			</div>
		</div><!-- .col -->
		{{-- <div class="col-md-4">
			<div class="card card-bordered">
				<div class="card-inner">
					<div class="nk-wg5">
						<div class="nk-wg5-title">
							<h6 class="title overline-title">Total Withdraw</h6>
						</div>
						<div class="nk-wg5-text">
							<div class="nk-wg5-amount">
								<div class="amount"> 20.001500 <span class="currency currency-btc">BTC</span>
								</div>
								<div class="amount-sm"> 972,360.72 <span class="currency currency-usd">USD</span>
								</div>
							</div>
						</div>
						<div class="nk-wg5-foot">
							<span class="text-soft">Last Withdraw at <span class="text-base">27 Nov, 2019</span></span>
						</div>
					</div>
				</div>
			</div>
		</div><!-- .col --> --}}
	</div><!-- .row -->
</div><!-- .nk-block -->
{{-- <div class="nk-block nk-block-lg">
	<div class="row g-gs">
		<div class="col-12">
			<div class="nk-block-head-sm">
				<div class="nk-block-head-content">
					<h5 class="nk-block-title title">Bitcoin Live MarketCap</h5>
				</div>
			</div>
			<div class="card card-bordered">
				<div class="card-inner">
					<div class="nk-ck2">
						<canvas class="chart-account-summary" id="summaryBalance"></canvas>
					</div><!-- .nk-ck2 -->
				</div>
			</div>
		</div>
	</div><!-- .row -->
</div><!-- .nk-block --> --}}

<!-- Modal Content Code -->
<div class="modal fade" tabindex="-1" id="modalDefault">
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
						Don't send Tether (USDT) or Bitcoin Cash (BCH) to this address as you may not be able to retrieve these funds.
							@break
						@case('eth')
						Make sure you receive only ETH or ERC20-USDT to this wallet address.
						If you receive any other ERC20 token, you may not be able to retrieve these funds.
							@break
						@case('usdteth')
						Make sure you’re sending only ERC20-USDT tokens to this wallet address. If you send any other USDT token,
						you may not be able to retrieve these funds.
							@break

						@default

					@endswitch

			</div>
            <div class="modal-body">
                <div class="form-group">
					<label class="form-label" for="default-01">Your wallet address</label>
					<div class="form-control-wrap">
						<input type="text" class="form-control" id="default-01" value="{{$wallet->address->address}}" disabled>
					</div>
				</div>
            </div>
            <div class="modal-footer bg-light">
                <span class="sub-text">Modal Footer Text</span>
            </div>
        </div>
    </div>
</div>
@endsection
