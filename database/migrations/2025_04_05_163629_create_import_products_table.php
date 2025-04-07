<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportProductsTable extends Migration
{
    public function up()
    {
        Schema::create('import_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_id'); // Liên kết với bảng imports
            $table->unsignedBigInteger('product_id'); // Liên kết với bảng products
            $table->string('product_name'); // Tên sản phẩm
            $table->integer('quantity'); // Số lượng sản phẩm
            $table->decimal('import_price', 15, 2); // Giá nhập
            $table->decimal('total_price', 15, 2); // Tổng tiền
            $table->date('manufacture_date')->nullable(); // Ngày sản xuất
            $table->date('expiry_date')->nullable(); // Ngày hết hạn
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('import_id')->references('id')->on('imports')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('import_products');
    }
}