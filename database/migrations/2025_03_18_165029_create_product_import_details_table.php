<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('product_import_details', function (Blueprint $table) {
            $table->id(); // ID chi tiết nhập hàng
            $table->foreignId('import_id')->constrained('product_imports')->onDelete('cascade'); // ID lần nhập hàng
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // ID sản phẩm
            $table->integer('quantity'); // Số lượng nhập
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_import_details');
    }
};
