<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model {
    use HasFactory, SoftDeletes;
    protected $table = 'coupons';
    protected $fillable = [
        'code', 'title', 'description', 'discount_type', 'discount_value',
        'usage_limit', 'usage_count','is_expired', 'start_date', 'end_date'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function restriction()
    {
        return $this->hasOne(CouponRestriction::class, 'coupon_id');
    }
    

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_user', 'coupon_id', 'user_id');
    }

    public function isExpired(): bool {
        return $this->end_date && now()->greaterThan($this->end_date);
    }
}
