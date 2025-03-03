<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $table = 'attributes';

    protected $fillable = [
        'name',
        'slug',
        'is_variant',
        'is_active'
    ];

    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id'); // Định nghĩa khóa ngoại
    }
}

