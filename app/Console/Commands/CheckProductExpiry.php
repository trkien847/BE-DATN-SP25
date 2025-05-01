<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ImportProduct;
use App\Models\ImportProductVariant;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;


class CheckProductExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-product-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $oneWeekLater = $now->copy()->addWeek();

        $expiringImports = ImportProduct::whereBetween('expiry_date', [$now, $oneWeekLater])->get();
        $admins = User::where('role_id', 3)->get();

        foreach ($expiringImports as $import) {
            $importVariants = ImportProductVariant::where('import_product_id', $import->id)->get();
            foreach ($importVariants as $variant) {
                $productVariant = ProductVariant::find($variant->product_variant_id);
                if ($productVariant) {
                    $productVariant->stock = max(0, $productVariant->stock - $variant->quantity);
                    $productVariant->save();
                }
                if ($variant->quantity > 0) {
                    foreach ($admins as $admin) {
                        Notification::create([
                            'user_id' => $admin->id,
                            'title' => 'Sản phẩm biến thể ngày nhập đã hết hạn',
                            'content' => 'Đã xóa sản phẩm ' . $import->product_name . ' do hết hạn nhập kho.',
                            'type' => 'expiry',
                            'data' => json_encode(['import_product_id' => $import->id]),
                            'is_read' => 0,
                        ]);
                    }
                }
                $variant->quantity = 0;
                $variant->save();
            }
        }
    } 
}
