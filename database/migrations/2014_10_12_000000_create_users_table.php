<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->string('first_name')->default('');
            $table->string('last_name')->default('');
            $table->string('image_url')->default('');
            $table->string('username')->uniqid()->default('');
            $table->string('phone_number')->uniqid();
            $table->string('email')->default('');
            $table->timestamp('phone_verified_at')->nullable();
            $table->text('token')->nullable();
            $table->string('otp_token')->nullable();
            $table->timestamp('otp_send_at')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
