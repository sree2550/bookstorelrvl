<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->Increments('cart_id');
            $table->integer('fk_book_id')->unsigned();
            $table->foreign('fk_book_id')->references('book_id')->on('book');
            $table->integer('fk_user_id')->unsigned();
            $table->foreign('fk_user_id')->references('login_id')->on('user_login');
            $table->string('quantity');
            $table->string('status');
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
        Schema::dropIfExists('cart');
    }
}
