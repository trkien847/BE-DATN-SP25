<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValueProductVariant extends Model
{
    use HasFactory;
    protected $table = 'attribute_value_product_variant';

    protected $fillable = [
        'product_variant_id',
        'attribute_value_id',
    ];
}
