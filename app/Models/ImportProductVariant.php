<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_product_id',
        'product_variant_id',
        'variant_name',
        'quantity',
        'import_price',
        'total_price'
    ];

    public function importProduct()
    {
        return $this->belongsTo(ImportProduct::class, 'import_product_id');
    }
    
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}