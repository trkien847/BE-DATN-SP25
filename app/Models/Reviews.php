<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'order_id',
        'user_id',
        'rating',
        'review_text',
        'reason',
        'is_active',
        'created_at',
        'updated_at',
    ];
}
