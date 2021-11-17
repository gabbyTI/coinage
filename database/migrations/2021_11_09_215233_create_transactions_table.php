<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('seller_id')->nullable();
			$table->string('type');
			$table->string('reference');
			$table->integer('withdrawal_amount')->nullable();
			$table->integer('paid_amount')->nullable();
			$table->integer('deposit_amount')->nullable();
			$table->integer('buy_amonut')->nullable();
			$table->string('buy_amount_crypto')->nullable();
			$table->dateTime('logdate')->nullable();
			$table->string('paystack_transaction_id')->nullable();
			$table->integer('paystack_fee')->nullable();
			$table->integer('coinage_fee')->nullable();
			$table->integer('total_fee')->nullable();
			$table->string('paystack_status')->nullable();
			$table->string('ip_address')->nullable();
			$table->string('old_balance');
			$table->string('paystack_response')->nullable();
			$table->string('paystack_transfer_code')->nullable();
			$table->string('response_message')->nullable();
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('transactions');
	}
}
