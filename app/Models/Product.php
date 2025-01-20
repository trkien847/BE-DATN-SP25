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
        'name_link',
        'slug',
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
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
