<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('serial_no')->nullable();
            $table->text('model_no')->nullable();
            $table->string('brand')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('item_type')->default(1);
            $table->integer('status')->default(1);
            $table->timestamp('purchased_at')->nullable();
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
        Schema::dropIfExists('items');
    }
}
