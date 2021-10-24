<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SebastianBergmann\Environment\Console;
use Throwable;

class WalletAfricaApi
{
	public function callApi($method, $endpoint, $body)
	{
		$BASE_URI = config('app.wallets_africa_base_uri');
		$TOKEN = config('app.wallets_africa_public_key');
		// dd('Bearer ' . $TOKEN);

		try {
			$response = Http::withHeaders([
				"Accept" => "application/json",
				"Content-Type" => "application/json",
				"Authorization" => "Bearer " . $TOKEN
			])->post($BASE_URI . $endpoint, $body);

			$response = json_decode($response->getBody(), true);

			return response()->json([
				'error' => false,
				'apiData' => $response
			])->getData();
		} catch (Throwable $e) {
			Log::log("Error", $e);
			return response()->json([
				'error' => true,
				'message' => 'Please retry. Contact admin if this persists',
			], 500)->getData();
		}
	}
}
