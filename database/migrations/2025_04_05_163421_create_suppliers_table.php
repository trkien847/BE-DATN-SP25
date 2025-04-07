<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên nhà cung cấp
            $table->string('email')->nullable(); // Email nhà cung cấp
            $table->string('phone')->nullable(); // Số điện thoại nhà cung cấp
            $table->string('address')->nullable(); // Địa chỉ nhà cung cấp
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}