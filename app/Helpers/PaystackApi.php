<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SebastianBergmann\Environment\Console;
use Throwable;

class PaystackApi
{
	public function callApi($method, $endpoint, $body = null)
	{
		$BASE_URI = config('app.paystack_base_uri');
		$TOKEN = config('app.paystack_secret_key');
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
