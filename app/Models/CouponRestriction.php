<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponRestriction extends Model {
    use HasFactory;
    protected $table = 'coupon_restrictions';
    protected $fillable = [
        'coupon_id', 'min_order_value', 'max_discount_value', 'valid_categories', 'valid_products'
    ];

    protected $casts = [
        'valid_categories' => 'array',
        'valid_products' => 'array',
    ];

    public function coupon() {
        return $this->belongsTo(Coupon::class);
    }
}

