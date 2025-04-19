<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_id',
        'product_id',
        'product_name',
        'quantity',
        'total_price',
        'manufacture_date',
        'expiry_date'
    ];

    public function import()
    {
        return $this->belongsTo(Import::class, 'import_id');
    }

    public function variants()
    {
        return $this->hasMany(ImportProductVariant::class, 'import_product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function importProductVariants()
    {
        return $this->hasMany(ImportProductVariant::class, 'import_product_id');
    }
}
