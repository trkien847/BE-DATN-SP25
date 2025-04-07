<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_imports', function (Blueprint $table) {
            $table->id();
            $table->string('order_code', 50)->unique();
            $table->string('order_name');
            $table->text('notes')->nullable();
            $table->foreignId('import_id')->constrained('imports')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_imports');
    }
};