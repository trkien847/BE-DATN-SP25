<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImportDetail extends Model
{
    use HasFactory;
    
    protected $fillable = ['import_id', 'product_id', 'quantity', 'name_vari', 'price'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
