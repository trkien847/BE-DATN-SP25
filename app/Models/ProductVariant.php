<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'sale_price',
        'stock',
        'sale_price_start_at',
        'sale_price_end_at',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_product_variant', 'product_variant_id', 'attribute_value_id');
    }
    protected function totalStock(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->variants()->sum('stock')
        );
    }
}
