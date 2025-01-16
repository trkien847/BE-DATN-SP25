<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('google_id');
            $table->string('phone_number')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('fullname');
            $table->string('password');
            $table->string('avatar');
            $table->enum('gender', ['Nam','Nu']);
            $table->date('birthday');
            $table->integer('loyalty_points');
            $table->enum('role',['Admin','user']);
            $table->enum('status',['Online','Offline']);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
