<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    use HasFactory;

    protected $table = 'category_product';

    protected $fillable = [
        'category_id',
        'product_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id', 'category_id');
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'product_id');
    }
}
