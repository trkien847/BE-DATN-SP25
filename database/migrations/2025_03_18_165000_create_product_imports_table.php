<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('product_imports', function (Blueprint $table) {
            $table->id(); // ID nhập hàng
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID người nhập hàng
            $table->string('imported_by'); // Tên người nhập
            $table->timestamp('imported_at')->useCurrent(); // Thời gian nhập hàng
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_imports');
    }
};

