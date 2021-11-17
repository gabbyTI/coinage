<?php

use App\Http\Controllers\BuyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PaystackController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return view('welcome');
});
Route::get('/about', function () {
	return view('about');
});


Auth::routes(['verify' => true]);

Route::middleware(['auth', 'verified', 'password.confirm'])->group(function () {
	Route::get('/home', [HomeController::class, 'index'])->name('home');

	Route::middleware(['verified.profile'])->group(function () {
		Route::get('/offers/create', [OfferController::class, 'create'])->name('offers.create');
	});
	//profile
	Route::get('/my-account', [ProfileController::class, 'profile']);
	Route::get('/profile/verify', [ProfileController::class, 'profileVerify'])->name('profile.verify.index');
	Route::get('/profile/verify/phone', [ProfileController::class, 'verifyPhone'])->name('profile.verify.phone');
	Route::get('/profile/verify/bank', [ProfileController::class, 'verifyBank'])->name('profile.verify.bank');
	Route::get('/profile/verify/id', [ProfileController::class, 'verifyId']);

	Route::post('/profile/verify/phone/sendOtp', [ProfileController::class, 'sendOtp'])->name('phone.otp.send');
	Route::post('/profile/verify/phone/resendOtp', [ProfileController::class, 'resendOtp'])->name('phone.otp.resend');
	Route::post('/profile/verify/phone/verifyOtp', [ProfileController::class, 'verifyOtp'])->name('phone.otp.verify');

	Route::post('/profile/verify/bank/process', [ProfileController::class, 'updateBank'])->name('profile.bank.update');
	Route::post('/profile/verify/id/process', [ProfileController::class, 'uploadId'])->name('profile.verify.id');

	//Wallets
	Route::get('/wallets', [WalletController::class, 'index'])->name('wallets.index');
	Route::get('/wallets/getWalletBalance/{wallet}/{address}', [WalletController::class, 'getWalletBalance'])->name('wallet.balance');
	Route::get('/wallets/getFiatWalletBalance/{fiatWallet}', [WalletController::class, 'getFiatWalletBalance'])->name('fiatWallet.balance');
	Route::get('/wallets/create', [WalletController::class, 'create'])->name('wallets.create');
	Route::post('/wallets/store', [WalletController::class, 'store'])->name('wallets.store');
	// Route::post('/wallets/storeFiat', [WalletController::class, 'storeFiat'])->name('wallets.storeFiat');
	Route::get('/wallets/show/{wallet}', [WalletController::class, 'show'])->name('wallets.show');


	//Offers
	Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
	Route::get('/offers/show/{offer:id}', [OfferController::class, 'show'])->name('offers.show');
	/*Route::get('/offers/edit', [OfferController::class, 'edit'])->name('offers.edit');*/

	Route::post('/offers/create', [OfferController::class, 'store'])->name('offers.store');
	Route::patch('/offers/update/{offer:id}', [OfferController::class, 'update'])->name('offers.update');
	Route::delete('/offers/delete/{offer:id}', [OfferController::class, 'destroy'])->name('offers.destroy');


	//Buy
	Route::get('/buy/btc', [BuyController::class, 'buyBtc'])->name('buy.btc');
	Route::get('/buy/eth', [BuyController::class, 'buyEth'])->name('buy.eth');
	Route::get('/buy/ltc', [BuyController::class, 'buyLtc'])->name('buy.ltc');
	Route::get('/buy/xrp', [BuyController::class, 'buyXrp'])->name('buy.xrp');
	Route::get('/buy/doge', [BuyController::class, 'buyDoge'])->name('buy.doge');
	Route::get('/buy/usdt', [BuyController::class, 'buyUsdt'])->name('buy.usdt');


	Route::get('/buy/{offer:id}', [BuyController::class, 'show'])->name('buy.show');

	//Paystack
	Route::get('/paystack/verify_transaction', [TransactionController::class, 'deposit']);
	// Route::post('/paystack/verify', [PaystackController::class, 'testTwo']);
});
