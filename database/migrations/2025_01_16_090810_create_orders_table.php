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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('payment_id');
            $table->string('phone_number');
            $table->string('email');
            $table->string('fullname');
            $table->string('address');
            $table->decimal('total_amount', 12,2);
            $table->boolean('is_paid', [0, 1])->default(1);
            $table->unsignedBigInteger('coupon_id');
            $table->string('coupon_code');
            $table->string('coupon_description');
            $table->string('coupon_discount_type');
            $table->string('coupon_discount_value');    
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
