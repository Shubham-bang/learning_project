<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_request', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('merchant_id');
            $table->string('category_name');
            $table->longText('category_description')->nullable();
            $table->boolean('status')->default(1); // 1 - pending && 2 - responded
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
        Schema::dropIfExists('category_request');
    }
}
