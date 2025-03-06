<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTypeProduct extends Model
{
    use HasFactory;
    protected $table = 'category_type_product';

    protected $fillable = [
        'product_id',
        'category_type_id',
    ];

    public function categoryType()
    {
        return $this->belongsTo(CategoryType::class, 'category_type_id', 'id');
    }

    public function product()
    {
        return $this->hasMany(Product::class, 'product_id');
    }
}
