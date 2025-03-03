<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValueProduct extends Model
{
    use HasFactory;
    protected $table = 'attribute_value_product';

    protected $fillable = [
        'product_id',
        'attribute_value_id',
    ];
}
