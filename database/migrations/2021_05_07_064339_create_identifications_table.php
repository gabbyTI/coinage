<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdentificationsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('identifications', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('user_id')->unique();
			$table->date('dob')->nullable();
			$table->string('id_number')->nullable();
			$table->string('id_type')->nullable();
			$table->string('id_front')->nullable();
			$table->string('id_back')->nullable();
			$table->boolean('is_verified')->default(false);
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('identifications');
	}
}
