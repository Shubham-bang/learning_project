<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_type'); // 1 - customer && 2 - merchant
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->longText('api_token')->nullable();
            $table->longText('device_token')->nullable();
            $table->string('device_type')->nullable();
            $table->boolean('is_admin')->default(0);
            $table->string('provider_name')->nullable();
            $table->string('provider_id')->nullable();
            $table->boolean('is_active')->default(0); // 1 - active && 2 - inactive
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
        Schema::dropIfExists('users');
    }
}
