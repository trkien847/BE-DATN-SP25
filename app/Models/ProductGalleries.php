<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGalleries extends Model
{
    use HasFactory;
    protected $table = 'product_galleries';

    protected $fillable = [
        'product_id',
        'image',
    ];

    public function product()
    {
        return $this->hasMany(Product::class, 'product_id');
    }
}
