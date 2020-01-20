<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_payment', function (Blueprint $table) {
            $table->bigIncrements('pay_id');
            $table->integer('fk_user_id')->unsigned();
            $table->foreign('fk_user_id')->references('login_id')->on('user_login');
            // $table->integer('fk_cart_id')->unsigned();
            // $table->foreign('fk_cart_id')->references('cart_id')->on('cart');
            $table->string('total_amt');
            $table->string('payment_type');
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
        Schema::dropIfExists('table_payment');
    }
}
