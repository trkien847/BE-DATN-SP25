<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'brand_id',
        'name',
        'views',
        'content',
        'thumbnail',
        'sku',
        'price',
        'sell_price',
        'sale_price',
        'sale_price_start_at',
        'sale_price_end_at',
        'is_active',
        'is_active',
        'import_at',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

    public function categoryTypes()
    {
        return $this->belongsToMany(CategoryType::class, 'category_type_product', 'product_id', 'category_type_id');
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }
    public function reviews()
    {
        return $this->hasMany(\App\Models\Reviews::class, 'product_id');
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }
    public function minVariantPrice()
    {
        return $this->variants()->min('price');
    }
    public function getMinVariantSalePriceAttribute()
    {
        return $this->variants()->whereNotNull('sale_price')->where('sale_price', '>', 0)->min('sale_price');
    }
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_value_product', 'product_id', 'attribute_value_id')
            ->withPivot('attribute_value_id');
    }
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_product');
    }
    public function importProducts()
    {
        return $this->hasMany(ImportProduct::class);
    }
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function pendingComments()
    {
        return $this->hasMany(Comment::class)->where('is_approved', false);
    }
}
