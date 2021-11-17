<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
	/**
	 * Get transactions for user.
	 */
	public function index()
	{
	}

	public function deposit(Request $request)
	{
		//verify transaction
		$BASE_URI = config('app.paystack_base_uri');
		$TOKEN = config('app.paystack_secret_key');
		$client = new Client();
		// dd($request);
		try {
			$response = $client->request('get', $BASE_URI . '/transaction/verify/' . $request->query('reference'), [
				'headers' => [
					'Authorization' => 'Bearer ' . $TOKEN,
				],
			]);

			$response = json_decode($response->getBody(), true);
			// return $response;
		} catch (GuzzleException $e) {
			throw $e;
		}

		$paystack = $response['data'];

		$transactionReference = $paystack['reference'];

		$userOldWalletBalanceInKobo = auth()->user()->getfiatWalletBalance('ngn');
		$paystackFeeInKobo = $paystack['fees'];
		$requestedAmountInKobo = $paystack['metadata']['requested_amount'];
		$amountPaidInKobo = $paystack['amount'];

		$coinageFeeInKobo = ($amountPaidInKobo - $paystackFeeInKobo) - $requestedAmountInKobo;
		$totalFeeInKobo = $amountPaidInKobo - $requestedAmountInKobo;

		// return $coinageFeeInKobo;
		// $paystackFeeInKobo = $paystack['fees'];
		// $coinageFeeInKobo = (0.5 * $paystackFeeInKobo);
		// $totalFeeInKobo = ($coinageFeeInKobo + $paystackFeeInKobo);

		$transaction = Transaction::where('reference', $transactionReference)->first();

		// Check if transaction reference already exists
		// can happen when a user wants to retry an abandoned transaction
		if ($transaction) {
			//check if transaction status is abandoned
			if ($transaction->paystack_status == 'abandoned') {
				//update the transaction with the new info
				$transaction->update([
					'paystack_fee' => $paystackFeeInKobo,
					'coinage_fee' => $coinageFeeInKobo,
					'total_fee' => $totalFeeInKobo,
					'paystack_status' => $paystack['status'],
					'ip_address' => $paystack['ip_address'],
					'old_balance' => $userOldWalletBalanceInKobo,
					'paystack_response' => $paystack['gateway_response'],
				]);

				// if transation is successful credit, else, dont credit
				if ($paystack['status'] != 'success') {
					return response()->json([
						'message' => 'Your deposit failed. Reason: ' . $paystack['gateway_response']
					]);
				}

				// credit wallet
				$newWalletBalanceInKobo = ($userOldWalletBalanceInKobo + $requestedAmountInKobo);
				$fiatWallet = auth()->user()->fiatWallets->where('currency', 'ngn')->first();
				$fiatWallet->update([
					'balance' => $newWalletBalanceInKobo
				]);

				return response()->json([
					'message' => 'Your deposit was successful'
				]);
			}
			return response()->json([
				'message' => 'Duplicate transaction reference. Refresh page'
			]);
		}

		//save transaction
		Transaction::create([
			'user_id' => $paystack['metadata']['user_id'],
			'type' => 'deposit',
			'reference' => $transactionReference,
			'deposit_amount' => $requestedAmountInKobo,
			'paid_amount' => $paystack['amount'],
			'logdate' => Carbon::now(),
			'paystack_transaction_id' => $paystack['id'],
			'paystack_fee' => $paystackFeeInKobo,
			'coinage_fee' => $coinageFeeInKobo,
			'total_fee' => $totalFeeInKobo,
			'paystack_status' => $paystack['status'],
			'ip_address' => $paystack['ip_address'],
			'old_balance' => $userOldWalletBalanceInKobo,
			'paystack_response' => $paystack['gateway_response'],
		]);

		// if transation is successful credit, else, dont credit
		if ($paystack['status'] != 'success') {
			return response()->json([
				'message' => 'Your deposit failed. Reason: ' . $paystack['gateway_response']
			]);
		}

		// credit wallet
		$newWalletBalanceInKobo = ($userOldWalletBalanceInKobo + $requestedAmountInKobo);
		$fiatWallet = auth()->user()->fiatWallets->where('currency', 'ngn')->first();
		$fiatWallet->update([
			'balance' => $newWalletBalanceInKobo
		]);

		//redirect user
		return response()->json([
			'message' => 'Your deposit was successful'
		]);
	}

	public function payout()
	{
	}

	public function buy()
	{
	}
}
