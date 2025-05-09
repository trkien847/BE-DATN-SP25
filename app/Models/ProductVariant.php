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
        'import_price',
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
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }
    protected function totalStock(): Attribute
    {
        $attribute = Attribute::make();
        $attribute->stock = $this->variants()->sum('stock');
        return $attribute;
    }
    public function cartVariant()
    {
        return $this->hasMany(Cart::class, 'product_variant_id');
    }

    public function getVariantName()
    {
        $attributeValues = $this->attributeValues()
            ->with('attribute')
            ->get()
            ->map(function ($value) {
                return $value->attribute->name . ': ' . $value->value;
            });

        return $attributeValues->join(', ');
    }
}
