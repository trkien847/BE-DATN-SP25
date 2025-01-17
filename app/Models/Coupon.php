<?php

// app/Models/Coupon.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    // Các trường mà bạn muốn có thể mass assignable
    protected $fillable = [
        'code',
        'title',
        'description',
        'discount_type',
        'discount_value',
        'usage_limit',
        'is_expired',
        'is_active',
        'start_date',
        'end_date',
        // 'min_usage_amount',
        'max_usage_amount',
        'applicable_products',
        'applicable_categories',
    ];

    // Các trường có kiểu dữ liệu đặc biệt (ví dụ: json)
    protected $casts = [
        'applicable_products' => 'array',
        'applicable_categories' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}

