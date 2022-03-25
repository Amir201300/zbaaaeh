<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_num')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('discount')->nullable();
            $table->string('product_price')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('tracking_status')->nullable();
            $table->unsignedBigInteger('address_id');
            $table->foreign('address_id')->references('id')->on('user_addresses')->onDelete('cascade');
            $table->string('total')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
