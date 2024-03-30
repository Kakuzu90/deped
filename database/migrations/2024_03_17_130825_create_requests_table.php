<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('requests', function (Blueprint $table) {
			$table->id();
			$table->foreignId('employee_id')->constrained()->cascadeOnDelete();
			$table->integer('request_type')->default(1);
			$table->foreignId('accepted_by')->nullable()->constrained("admins")->cascadeOnDelete();
			$table->foreignId('released_by')->nullable()->constrained("admins")->cascadeOnDelete();
			$table->integer('status')->default(1);
			$table->timestamp('accepted_at')->nullable();
			$table->timestamp('released_at')->nullable();
			$table->timestamp('deleted_at')->nullable();
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
		Schema::dropIfExists('requests');
	}
}
