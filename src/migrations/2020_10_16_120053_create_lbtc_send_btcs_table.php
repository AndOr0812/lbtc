<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLbtcSendBtcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lbtc_send_btcs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trx_id')->unsigned()->nullable();
            $table->decimal('amount', 32, 8);
            $table->string('to_address');
            $table->enum('status', ['pending', 'proccessed', 'cancelled']);
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
        Schema::dropIfExists('lbtc_send_btcs');
    }
}
