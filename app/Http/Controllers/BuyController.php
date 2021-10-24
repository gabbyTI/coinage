<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BuyController extends Controller
{
	/**
	 * Display the specified resource.
	 *
	 * @param Offer $offer
	 * @return Application|Factory|View
	 */
	public function show(Offer $offer)
	{
		return view('components.show-buy-crypto-modal', ['offer' => $offer]);
	}

	public function buyBtc()
	{
		$offers = Offer::where([
			['crypto_type', '=', 'BTC'],
			['user_id', '<>', auth()->id()],
		])->get();
		return view('buy.buy', compact('offers', $offers));
	}
	public function buyEth()
	{
		$offers = Offer::where([
			['crypto_type', '=', 'ETH'],
			['user_id', '<>', auth()->id()],
		])->get();
		return view('buy.buy', compact('offers', $offers));
	}
	public function buyLtc()
	{
		$offers = Offer::where([
			['crypto_type', '=', 'LTC'],
			['user_id', '<>', auth()->id()],
		])->get();
		return view('buy.buy', compact('offers', $offers));
	}
	public function buyXrp()
	{
		$offers = Offer::where([
			['crypto_type', '=', 'XRP'],
			['user_id', '<>', auth()->id()],
		])->get();
		return view('buy.buy', compact('offers', $offers));
	}
	public function buyDoge()
	{
		$offers = Offer::where([
			['crypto_type', '=', 'DOGE'],
			['user_id', '<>', auth()->id()],
		])->get();
		return view('buy.buy', compact('offers', $offers));
	}
	public function buyUsdt()
	{
		$offers = Offer::where([
			['crypto_type', '=', 'USDT'],
			['user_id', '<>', auth()->id()],
		])->get();
		return view('buy.buy', compact('offers', $offers));
	}
}
