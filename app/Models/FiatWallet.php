<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FiatWallet extends Model
{
	use HasFactory;

	protected $fillable = [
		'user_id',
		'currency',
		'balance',
		"phoneNumber",
		"accountNumber",
		"bank",
		"accountName"
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
