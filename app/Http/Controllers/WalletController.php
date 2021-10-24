<?php

namespace App\Http\Controllers;

use App\Helpers\CryptoProcessingApi;
use App\Helpers\WalletAfricaApi;
use App\Models\Address;
use App\Models\FiatWallet;
use App\Models\Wallet;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
	private $status = 300;
	/**
	 * Display a listing of the resource.
	 *
	 * @return Application|Factory|View
	 */
	public function index()
	{
		$wallets = Wallet::all()->where('user_id', Auth::id());
		$fiatWallet = FiatWallet::all()->where('user_id', Auth::id())->first();
		// dd($fiatWallet);
		return view('wallets.index', compact('wallets', 'fiatWallet'));
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
		if ($crpyo_exists) return redirect(route('wallets.index'))->with('message', "You already have a " . strtoupper($request->crypto_type) . " wallet");

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
			return redirect(route('wallets.index'))->with('message', $response->message);
		}
	}

	// public function storeFiat(Request $request)
	// {

	// 	$walletAfricaApi = new WalletAfricaApi();

	// 	$response = $walletAfricaApi->callApi(
	// 		'POST',
	// 		'/Wallet/generate/',
	// 		[
	// 			"firstName" => auth()->user()->other_names,
	// 			"lastName" => auth()->user()->surname,
	// 			"email" =>  auth()->user()->email,
	// 			"secretKey" => config('app.wallets_africa_secret_key'),
	// 			"currency" => "NGN"
	// 		]
	// 	);
	// 	// dd($response->apiData->data->phoneNumber);

	// 	$fiatWallet = FiatWallet::create([
	// 		'user_id' => auth()->id(),
	// 		'currency' => 'ngn',
	// 		'balance' => 0.00,
	// 		"phoneNumber" => $response->apiData->data->phoneNumber,
	// 		"accountNumber" => $response->apiData->data->accountNumber,
	// 		"bank" => $response->apiData->data->bank,
	// 		"accountName" => $response->apiData->data->accountName,
	// 	]);
	// 	dd($fiatWallet);
	// }

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

	public function getWalletBalance(Wallet $wallet, Address $address)
	{
		$response = [];
		$cryptoProcessingWalletApi = new CryptoProcessingApi();

		$addressResponse = $cryptoProcessingWalletApi->callApi('get', '/wallets' . '/' . $wallet->wallet_id_string . '/addresses' . '/' . $address->address, null);

		if ($addressResponse->error) {
			$response['error'] = true;
			$response['balance'] = '0.00000000';
		} else {
			$response['error'] = false;
			$response['balance'] = number_format($addressResponse->apiData->data->final_balance, 8);
			$this->status = 200;
		}


		return response($response, $this->status);
	}

	public function getFiatWalletBalance(FiatWallet $fiatWallet)
	{
		$response = [];
		$walletAfricaApi = new WalletAfricaApi();

		// Credit Test Money
		$walletAfricaApi->callApi(
			'POST',
			'/wallet/credit/',
			[
				"transactionReference" => Uuid::uuid(),
				"phoneNumber" =>  $fiatWallet->phoneNumber,
				"amount" => 37534,
				"secretKey" => config('app.wallets_africa_secret_key'),
			]
		);
		$apiResponse = $walletAfricaApi->callApi(
			'POST',
			'/wallet/balance/',
			[
				"phoneNumber" =>  $fiatWallet->phoneNumber,
				"currency" => strtoupper($fiatWallet->currency),
				"secretKey" => config('app.wallets_africa_secret_key'),
			]
		);
		if ($apiResponse->error) {
			$response['error'] = true;
			$response['balance'] = '0.00';
		} else {
			$response['error'] = false;
			$response['balance'] = number_format($apiResponse->apiData->data->walletBalance, 2);
			$this->status = 200;
		}


		return response($response, $this->status);
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
