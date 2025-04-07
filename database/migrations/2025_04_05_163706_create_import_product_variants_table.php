<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportProductVariantsTable extends Migration
{
    public function up()
    {
        Schema::create('import_product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_product_id'); // Liên kết với bảng import_products
            $table->unsignedBigInteger('product_variant_id'); // Liên kết với bảng product_variants
            $table->string('variant_name'); // Tên biến thể
            $table->integer('quantity'); // Số lượng
            $table->decimal('import_price', 15, 2); // Giá nhập
            $table->decimal('total_price', 15, 2); // Tổng tiền
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('import_product_id')->references('id')->on('import_products')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('import_product_variants');
    }
}