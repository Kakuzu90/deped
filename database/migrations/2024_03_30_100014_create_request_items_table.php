<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('request_items', function (Blueprint $table) {
			$table->id();
			$table->foreignId('request_id')->constrained()->cascadeOnDelete();
			$table->foreignId('item_id')->constrained()->cascadeOnDelete();
			$table->integer('quantity');
			$table->integer('status')->default(1);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('request_items');
	}
}
