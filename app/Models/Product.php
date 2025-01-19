<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
