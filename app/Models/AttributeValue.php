<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $table = 'attribute_values';

    protected $fillable = [
        'attribute_id',
        'value',
        'is_active',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id'); // Định nghĩa khóa ngoại
    }
}

