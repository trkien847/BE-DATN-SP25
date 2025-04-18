<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportsTable extends Migration
{
    public function up()
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->string('import_code')->unique(); // Mã nhập hàng
            $table->date('import_date'); // Ngày nhập
            $table->unsignedBigInteger('user_id'); // Người nhập
            $table->unsignedBigInteger('supplier_id')->nullable(); // Nhà cung cấp
            $table->boolean('is_confirmed')->default(false); // Trạng thái xác nhận
            $table->integer('total_quantity'); // Tổng số lượng sản phẩm
            $table->decimal('total_price', 15, 2); // Tổng tiền nhập
            $table->string('proof_image')->nullable(); // Ảnh chứng minh
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('imports');
    }
}