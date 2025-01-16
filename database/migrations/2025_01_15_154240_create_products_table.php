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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
            $table->string('name');
            $table->string('name_link');
            $table->string('slug');
            $table->integer('views');
            $table->text('content');
            $table->string('thumbnail');
            $table->string('sku');
            $table->decimal('price',11,2);
            $table->decimal('sell_price',11,2);
            $table->decimal('sale_price',11,2);
            $table->timestamp('sale_price_start_at');
            $table->timestamp('sale_price_end_at')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
