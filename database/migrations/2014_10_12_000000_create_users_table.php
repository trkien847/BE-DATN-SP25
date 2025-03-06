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
            $table->bigInteger('google_id')->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('fullname');
            $table->string('password');
            $table->string('avatar')->default('default-avatar.png');
            $table->enum('gender', ['Nam', 'Nu'])->nullable();
            $table->date('birthday')->nullable();
            $table->integer('loyalty_points')->default(0); 
            $table->enum('status', ['Online', 'Offline'])->default('Offline');
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
