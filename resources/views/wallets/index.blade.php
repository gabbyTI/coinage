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
						<a href="#" class="btn btn-dim btn-outline-light btn-icon" data-toggle="dropdown"><em class="icon ni ni-setting"></em></a>
						<div class="dropdown-menu  dropdown-menu-xs dropdown-menu-right">
							<ul class="link-list-plain sm">
								<li><a href="#">Display</a></li>
								<li><a href="#">Show</a></li>
							</ul>
						</div>
					</li>
					<li><a href="#" class="btn btn-primary"><span>Send</span> <em class="icon ni ni-arrow-long-right"></em></a></li>
					<li><a href="#" class="btn btn-dim btn-outline-light"><span>Withdraw</span> <em class="icon ni ni-arrow-long-right"></em></a></li>
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
		<div class="row g-gs">
			<div class="col-md-6 col-lg-4">
				<div class="card card-bordered">
					<div class="nk-wgw">
						<div class="nk-wgw-inner">
							<a class="nk-wgw-name" href="html/crypto/wallet-bitcoin.html">
								<div class="nk-wgw-icon is-default">
									<em class="icon ni ni-sign-usd"></em>
								</div>
								<h5 class="nk-wgw-title title">Naira Account</h5>
							</a>
							<div class="nk-wgw-balance">
								<div class="amount">{{auth()->user()->getFiatWalletbalance('ngn')}}<span class="currency currency-ngn">NGN</span></div>
							</div>
						</div>
						<div class="nk-wgw-actions">
							<ul>
								{{-- <li><a href="#"><em class="icon ni ni-arrow-up-right"></em> <span>Send</span></a></li> --}}
								<li><a href="#"><em class="icon ni ni-arrow-to-down"></em><span>Deposit</span></a></li>
								<li><a href="#"><em class="icon ni ni-arrow-to-right"></em><span>Withdraw</span></a></li>
							</ul>
						</div>
						<div class="nk-wgw-more dropdown">
							<a href="#" class="btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
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
				<div class="card card-bordered">
					<div class="nk-wgw">
						<div class="nk-wgw-inner">
							<a class="nk-wgw-name" href="html/crypto/wallet-bitcoin.html">
								<div class="nk-wgw-icon">
									<em class="icon ni ni-sign-eur"></em>
								</div>
								<h5 class="nk-wgw-title title">EUR Account</h5>
							</a>
							<div class="nk-wgw-balance">
								<div class="amount">12,495.90<span class="currency currency-eur">EUR</span></div>
								<div class="amount-sm">12,495.90<span class="currency currency-usd">USD</span></div>
							</div>
						</div>
						<div class="nk-wgw-actions">
							<ul>
								<li><a href="#"><em class="icon ni ni-arrow-up-right"></em> <span>Send</span></a></li>
								<li><a href="#"><em class="icon ni ni-arrow-down-left"></em><span>Receive</span></a></li>
								<li><a href="#"><em class="icon ni ni-arrow-to-right"></em><span>Withdraw</span></a></li>
							</ul>
						</div>
						<div class="nk-wgw-more dropdown">
							<a href="#" class="btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
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
			<div class="col-md-6 col-lg-4">
				<div class="card card-bordered dashed h-100">
					<div class="nk-wgw-add">
						<div class="nk-wgw-inner">
							<a href="#">
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
		<div class="row g-gs">

			@foreach ($wallets as $wallet )
				<div class="col-md-6 col-lg-4">
					<div class="card card-bordered">
						<div class="nk-wgw">
							<div class="nk-wgw-inner">
								<a class="nk-wgw-name" href="{{route('wallets.show',$wallet->id)}}">
									<div class="nk-wgw-icon">
										<em class="icon ni ni-sign-{{$wallet->crypto_type == 'usdteth'?'usdt' : $wallet->crypto_type}}"></em>
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
									<div class="amount"><span class="final-balance">{{ $wallet->getWallet('final_balance') }}</span> <span class="currency currency-btc">{{ $wallet->crypto_type == 'usdteth'?'USDT' : strtoupper($wallet->crypto_type) }}</span></div>
									<div class="amount-sm"><span class="total"></span> <span class="currency currency-usd">NGN</span></div>
								</div>
							</div>
							<div class="nk-wgw-actions">
								<ul>
									<li><a href="#"><em class="icon ni ni-arrow-up-right"></em> <span>Send</span></a></li>
									<li><a href="#"><em class="icon ni ni-arrow-down-left"></em><span>Receive</span></a></li>
									<li><a href="#"><em class="icon ni ni-arrow-to-right"></em><span>Withdraw</span></a></li>
								</ul>
							</div>
							<div class="nk-wgw-more dropdown">
								<a href="#" class="btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
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
			@endforeach
			@if (count($wallets) < 3)
				<div class="col-sm-6 col-lg-4 ">
					<div class="card card-bordered dashed h-100">
						<div class="nk-wgw-add">
							<div class="nk-wgw-inner">
								<a href="{{route('wallets.create')}}">
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



	<script src="{{ asset('design/js/custom/user.js') }}"></script>
@endsection
