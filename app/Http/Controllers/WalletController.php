<?php

namespace App\Http\Controllers;

use App\Helpers\CryptoProcessingApi;
use App\Models\Address;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Application|Factory|View
	 */
	public function index()
	{
		$wallets = Wallet::all()->where('user_id', Auth::id());
		return view('wallets.index', compact('wallets', $wallets));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('wallets.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		/**
		 * 		TODO:
		 * check if wallet type exits already
		 *
		 */

		//validate request
		$request->validate([
			'crypto_type' => ['required', 'string'],
		]);

		// check if user has wallet type already
		$user = auth()->user();
		$crpyo_exists = $user->wallets->where('crypto_type', $request->crypto_type)->first();
		if ($crpyo_exists) return redirect(route('wallets.create'))->with('message', "You already have a " . strtoupper($request->crypto_type) . " wallet");

		$cryptoProcessingWalletApi = new CryptoProcessingApi();

		//call the create wallet endpoint
		$response = $cryptoProcessingWalletApi->callApi(
			'post',
			'/wallets',
			[
				'name' => strtoupper($request->crypto_type) . ' Wallet ' . auth()->id() . Carbon::now()->timestamp,
				'currency' => strtoupper($request->crypto_type),
				'human' => $user->fullname,
				'description' => 'Customer default ' . strtoupper($request->crypto_type) . ' wallet'
			]
		);
		// dd($response->error);

		if (!$response->error) {
			//call get address
			$addressResponse = $cryptoProcessingWalletApi->callApi('get', '/wallets' . '/' . $response->apiData->data->id . '/addresses', null);
			// dd($addressResponse);
			//store wallet data in db
			$wallet = Wallet::create([
				'user_id' => auth()->id(),
				'wallet_id_string' => $response->apiData->data->id,
				'name' => $response->apiData->data->name,
				'crypto_type' => $request->crypto_type,
				'total_received' => $addressResponse->apiData->data[0]->total_received,
				'total_sent' => $addressResponse->apiData->data[0]->total_sent,
				'balance' => $addressResponse->apiData->data[0]->final_balance,
			]);


			//store address info in db
			Address::create([
				'wallet_id' => $wallet->id,
				'address' => $addressResponse->apiData->data[0]->address,
			]);

			return redirect('/wallets');
		} else {
			return redirect(route('wallets.create'))->with('message', $response->message);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param Wallet $wallet
	 * @return Response
	 */
	public function show(Wallet $wallet)
	{
		return view('wallets.show', compact('wallet', $wallet));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Wallet $wallet
	 * @return Response
	 */
	public function edit(Wallet $wallet)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Request $request
	 * @param Wallet $wallet
	 * @return Response
	 */
	public function update(Request $request, Wallet $wallet)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Wallet $wallet
	 * @return Response
	 */
	public function destroy(Wallet $wallet)
	{
		//
	}
}
