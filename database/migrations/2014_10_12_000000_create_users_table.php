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
            $table->string('first_name')->index()->nullable();
            $table->string('last_name')->index()->nullable();
            $table->string('vanity')->index()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('otp_secret');
            $table->rememberToken();
            $table->integer('created_by')->index()->nullable();
            $table->nullableTimestamps();
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
