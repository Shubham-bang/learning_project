<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('merchant_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('shop_name');
            $table->string('shop_address');
            $table->string('shop_photo')->default('shop.png')->nullable();
            $table->longText('latitude')->nullable();
            $table->longText('longitude')->nullable();
            $table->longText('shop_description')->nullable();
            $table->string('opening_time')->nullable();
            $table->string('closing_time')->nullable();
            $table->boolean('shop_status')->default(1); // 1 - open && 2 - close
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
        Schema::dropIfExists('merchant');
    }
}
