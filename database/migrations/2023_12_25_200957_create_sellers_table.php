<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('picture')->nullable();
            $table->string('address')->nullable();

            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('status',['pending','inReview','active'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_email')->nullable();

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
        Schema::dropIfExists('sellers');
    }
};
