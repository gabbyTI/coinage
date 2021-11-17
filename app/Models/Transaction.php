<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	use HasFactory;

	protected $fillable = [
		'user_id',
		'seller_id',
		'type',
		'reference',
		'withdrawal_amount',
		'paid_amount',
		'deposit_amount',
		'buy_amonut',
		'buy_amount_crypto',
		'logdate',
		'paystack_transaction_id',
		'paystack_fee',
		'coinage_fee',
		'total_fee',
		'paystack_status',
		'ip_address',
		'old_balance',
		'paystack_response',
		'paystack_transfer_code',
		'response_message',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function seller()
	{
		return $this->belongsTo(User::class, 'seller_id');
	}
}
