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
            $table->unsignedBigInteger('code_id')->nullable();
            $table->string('paymentType')->nullable();
            $table->double('discountPrice',10,2)->nullable();
            $table->double('productPrice',10,2)->nullable();
            $table->double('taxPrice',10,2)->nullable();
            $table->double('shippingPrice',10,2)->nullable();
            $table->double('totalPrice',10,2)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
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
