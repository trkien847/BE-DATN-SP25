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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->string('description');
            $table->enum('discount_type',['co_dinh','phan_tram'])->default('phan_tram');
            $table->decimal('discount_value',10,2);
            $table->integer('usage_limit');
            $table->integer('usage_count');
            $table->boolean('is_expired')->default(1);
            $table->boolean('is_active')->default(1);
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
